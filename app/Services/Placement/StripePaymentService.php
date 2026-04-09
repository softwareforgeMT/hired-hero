<?php

namespace App\Services\Placement;

use App\Models\User;
use App\Models\ResumeSubscription;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;

class StripePaymentService
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Create a Stripe customer for the user
     */
    public function createStripeCustomer(User $user): ?string
    {
        try {
            // Check if customer already exists
            $existingCustomers = $this->stripe->customers->all([
                'email' => $user->email,
                'limit' => 1,
            ]);

            if (!empty($existingCustomers->data)) {
                return $existingCustomers->data[0]->id;
            }

            // Create new customer
            $customer = $this->stripe->customers->create([
                'email' => $user->email,
                'name' => $user->name,
                'description' => "User ID: {$user->id}",
                'metadata' => [
                    'user_id' => $user->id,
                ],
            ]);

            return $customer->id;
        } catch (ApiErrorException $e) {
            Log::error('Stripe customer creation failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Create a payment checkout session for Resume Builder
     * Returns array with checkout URL and session ID to store in session
     */
    public function createCheckoutSession(User $user, string $planType = 'weekly', ?\App\Models\PromoCode $promoCode = null): ?array
    {
        try {
            // Get or create Stripe customer
            $customerId = $user->stripe_customer_id;
            if (!$customerId) {
                $customerId = $this->createStripeCustomer($user);
                $user->update(['stripe_customer_id' => $customerId]);
            }

            // Define base prices in cents
            $basePrices = [
                'weekly' => 499,    // $4.99
                'monthly' => 1900,  // $19.00
            ];

            $baseAmount = $basePrices[$planType];
            $finalAmount = $baseAmount;
            $discountAmount = 0;

            // Calculate discounted price if promo code is provided
            if ($promoCode) {
                $discountPercentage = $promoCode->discount_percentage;
                $discountAmount = (int) ($baseAmount * $discountPercentage / 100);
                $finalAmount = $baseAmount - $discountAmount;

                // Ensure final amount is at least 50 cents (Stripe minimum)
                $finalAmount = max(50, $finalAmount);
            }

            // Prepare line items - use price_data with custom unit_amount for discounted prices
            if ($promoCode) {
                // Use price_data for custom discounted amount
                $lineItems = [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => ucfirst($planType) . ' Resume Builder Pass',
                                'description' => 'AI-Powered Resume Builder - ' . ($planType === 'weekly' ? '7 days' : '30 days') . ' access',
                            ],
                            'unit_amount' => $finalAmount,
                        ],
                        'quantity' => 1,
                    ],
                ];
            } else {
                // Use configured price ID for original price
                $priceId = $planType === 'weekly' 
                    ? config('services.stripe.resume_builder_weekly_price_id')
                    : config('services.stripe.resume_builder_monthly_price_id');

                $lineItems = [
                    [
                        'price' => $priceId,
                        'quantity' => 1,
                    ],
                ];
            }

            // Create checkout session (one-time payment)
            $sessionData = [
                'customer' => $customerId,
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('resume-builder.checkout-success'),
                'cancel_url' => route('placement.wizard.step', ['step' => 6]),
                'metadata' => [
                    'user_id' => $user->id,
                    'plan_type' => $planType,
                    'base_amount' => $baseAmount,
                    'final_amount' => $finalAmount,
                    'discount_amount' => $discountAmount,
                ],
            ];

            // Add promo code metadata if provided
            if ($promoCode) {
                $sessionData['metadata']['promo_code_id'] = $promoCode->id;
                $sessionData['metadata']['promo_code'] = $promoCode->code;
                $sessionData['metadata']['discount_percentage'] = $promoCode->discount_percentage;
            }

            $session = $this->stripe->checkout->sessions->create($sessionData);

            // Return both URL and session ID
            // The session ID will be stored in the user's session during redirect
            return [
                'url' => $session->url,
                'session_id' => $session->id,
            ];
        } catch (ApiErrorException $e) {
            Log::error('Stripe checkout session failed', [
                'user_id' => $user->id,
                'plan_type' => $planType,
                'promo_code' => $promoCode?->code,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Retrieve subscription from Stripe
     */
    public function getSubscription(string $subscriptionId): ?object
    {
        try {
            return $this->stripe->subscriptions->retrieve($subscriptionId);
        } catch (ApiErrorException $e) {
            Log::error('Failed to retrieve Stripe subscription', [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Cancel a Stripe subscription
     */
    public function cancelSubscription(string $subscriptionId): bool
    {
        try {
            $this->stripe->subscriptions->cancel($subscriptionId);
            return true;
        } catch (ApiErrorException $e) {
            Log::error('Failed to cancel Stripe subscription', [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Retrieve a checkout session
     */
    public function getCheckoutSession(string $sessionId): ?object
    {
        // Validate session ID is not a placeholder
        if (empty($sessionId) || $sessionId === '{CHECKOUT_SESSION_ID}' || strpos($sessionId, '{') === 0) {
            Log::error('Invalid checkout session ID', [
                'session_id' => $sessionId,
                'error' => 'Session ID is invalid or appears to be a placeholder',
            ]);
            return null;
        }

        try {
            return $this->stripe->checkout->sessions->retrieve($sessionId);
        } catch (ApiErrorException $e) {
            Log::error('Failed to retrieve checkout session', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Handle successful payment and create subscription record
     */
    public function handleSuccessfulPayment(string $sessionId): bool
    {
        
        try {
            $session = $this->getCheckoutSession($sessionId);
            
            if (!$session) {
                return false;
            }
            

            // For payment mode, check payment intent
            if ($session->payment_status !== 'paid') {
                return false;
            }
    
            $metadata = $session->metadata ?? [];
            $userId = $metadata->user_id ?? null;
            $planType = $metadata->plan_type ?? 'weekly';
            $promoCodeId = $metadata->promo_code_id ?? null;
            
            // Calculate expiration date
            $planDays = $planType === 'weekly' ? 7 : 30;
            $expiresAt = now()->addDays($planDays);

            // Get the line item to extract price/product info
            $lineItems = $this->stripe->checkout->sessions->allLineItems($sessionId);
            $lineItem = $lineItems->data[0] ?? null;

            if (!$lineItem || !$lineItem->price) {
                return false;
            }

            // Use amounts from metadata if available (more accurate and prevents rounding errors)
            $baseAmount = isset($metadata->base_amount) ? (int) $metadata->base_amount / 100 : null;
            $finalAmount = isset($metadata->final_amount) ? (int) $metadata->final_amount / 100 : null;
            $discountAmount = isset($metadata->discount_amount) ? (int) $metadata->discount_amount / 100 : 0;
            
            // Fallback to calculating from line item if metadata not available
            if (!$finalAmount) {
                $finalAmount = $lineItem->price->unit_amount / 100; // Convert cents to dollars
                $baseAmount = $finalAmount;
            }

            // If no discount in metadata but promo code exists, try to calculate
            if (!isset($metadata->discount_amount) && $promoCodeId) {
                $promoCode = \App\Models\PromoCode::find($promoCodeId);
                if ($promoCode && $promoCode->isValid()) {
                    $discountAmount = ($baseAmount * $promoCode->discount_percentage) / 100;
                    $finalAmount = max(0, $baseAmount - $discountAmount);
                }
            }

            // Mark promo code as used if applicable
            if ($promoCodeId) {
                $promoCode = \App\Models\PromoCode::find($promoCodeId);
                if ($promoCode) {
                    $user = User::find($userId);
                    if ($user && $promoCode->isValid()) {
                        // Mark user as having used this code
                        $promoCode->markUsedByUser($user);
                    }
                }
            }

            // Create subscription record (tracking the one-time payment)
            $subscription = ResumeSubscription::create([
                'user_id' => $userId,
                'stripe_subscription_id' => $session->payment_intent, // Store payment intent ID
                'stripe_customer_id' => $session->customer,
                'plan_type' => $planType,
                'status' => 'active',
                'amount' => $finalAmount,
                'original_amount' => $baseAmount,
                'discount_amount' => $discountAmount > 0 ? $discountAmount : null,
                'promo_code_id' => $promoCodeId,
                'started_at' => now(),
                'expires_at' => $expiresAt,
                'stripe_product_id' => $lineItem->price->product,
                'stripe_price_id' => $lineItem->price->id,
            ]);

            // Update user flag
            $user = User::find($userId);
            if ($user) {
                $user->update(['has_paid_resume_builder' => true]);
            }
            
            Log::info('Resume subscription payment processed successfully', [
                'user_id' => $userId,
                'subscription_id' => $subscription->id,
                'session_id' => $sessionId,
                'plan_type' => $planType,
                'amount' => $finalAmount,
                'discount_amount' => $discountAmount,
                'promo_code' => $metadata->promo_code ?? null,
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to handle successful payment', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Check if user has active subscription
     */
    public function userHasActiveSubscription(User $user): bool
    {
        $subscription = ResumeSubscription::forUser($user->id)
            ->active()
            ->first();

        return $subscription !== null;
    }

    /**
     * Get user's active subscription
     */
    public function getUserActiveSubscription(User $user): ?ResumeSubscription
    {
        return ResumeSubscription::forUser($user->id)
            ->active()
            ->first();
    }
}
