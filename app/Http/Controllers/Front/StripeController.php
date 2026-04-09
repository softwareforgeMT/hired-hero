<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubPlan;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\User;
use App\Models\UserActivity;
use App\Models\UserSubscription;
use App\CentralLogics\TransactionLogic;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Support\Str;
use App\Jobs\SendSubscriptionEmailJob;

class StripeController extends Controller
{
    /**
     * Referral discount percentage (20%).
     * DB-based, first purchase only.
     */
    private float $referralDiscountRate = 0.20;

    /**
     * CREATE CHECKOUT SESSION
     * ----------------------------------
     * LOGIN REQUIRED
     * DISCOUNT FROM PROMO CODE OR REFERRAL
     */
    public function processPayment(Request $request, $slug)
    {
        /** ----------------------------
         *  REQUIRE LOGIN
         * ---------------------------- */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('user.login')
                ->with('error', 'Please login to continue.');
        }

        /** ----------------------------
         *  LOAD PLAN
         * ---------------------------- */
        $plan = SubPlan::where('slug', $slug)->firstOrFail();

        if ($plan->slug === 'free-plan') {
            return redirect()->route('front.pricing')
                ->with('error', 'You cannot purchase this plan.');
        }

        /** ----------------------------
         *  SECURITY TOKEN
         * ---------------------------- */
        $securityToken = Str::random(100);
        Session::put('stripeSecurityToken', $securityToken);
        Session::put('plan_slug', $plan->slug);
        
        // Store payment source (resume-builder or pricing page)
        $paymentSource = $request->input('source', 'front');
        Session::put('stripe_payment_source', $paymentSource);

        /** ----------------------------
         *  PROMO CODE DISCOUNT (Priority)
         * ---------------------------- */
        $promoCode = null;
        $promoDiscountAmount = 0;
        $discountUsedType = 'none';
        if ($request->has('promo_code')) {
            $promoCodeStr = strtoupper($request->query('promo_code'));
            $promoCode = \App\Models\PromoCode::where('code', $promoCodeStr)->first();

            if ($promoCode && $promoCode->active && !$promoCode->hasExpired() && 
                $promoCode->used_count < $promoCode->max_usage) {
                
                // Check if user is assigned to this code
                $userAssignment = $promoCode->users()->where('user_id', $user->id)->first();
                
                if ($userAssignment && !$userAssignment->pivot->used) {
                    $basePrice = (float) $plan->price;
                    $promoDiscountAmount = round($basePrice * ($promoCode->discount_percentage / 100), 2);
                    $discountUsedType = 'promo';
                    
                    Session::put('promo_code_id', $promoCode->id);
                }
            }
        }

        /** ----------------------------
         *  FIRST-TIME PURCHASE CHECK (Fallback if no promo)
         * ---------------------------- */
        $isReferralEligible = false;
        if ($discountUsedType === 'none') {
            $hasPreviousOrder = Order::where('user_id', $user->id)->exists();

            $isReferralEligible =
                !$hasPreviousOrder &&                             // FIRST PURCHASE ONLY
                !empty($user->referred_by) &&
                (int)$user->referral_discount_used === 0 &&
                $user->discount === 'allow';
        }

        /** ----------------------------
         *  PRICE CALCULATION
         * ---------------------------- */
        $basePrice = (float) $plan->price;
        
        if ($discountUsedType === 'promo') {
            $discountAmount = $promoDiscountAmount;
        } elseif ($isReferralEligible) {
            $discountAmount = round($basePrice * $this->referralDiscountRate, 2);
            $discountUsedType = 'referral';
        } else {
            $discountAmount = 0;
        }

        $finalPrice = max($basePrice - $discountAmount, 0);

        /** ----------------------------
         *  STRIPE CHECKOUT SESSION
         * ---------------------------- */
        $stripe = new StripeClient(env('STRIPE_SECRET'));

        $checkoutSession = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',

            'metadata' => [
                'security_token'   => $securityToken,
                'user_id'          => (string) $user->id,
                'plan_slug'        => $plan->slug,
                'discount_type'    => $discountUsedType,  // 'promo', 'referral', or 'none'
                'discount_amount'  => (string) $discountAmount,
                'promo_code_id'    => $promoCode ? (string) $promoCode->id : '',
            ],

            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $plan->name ?? 'Subscription Plan',
                    ],
                    'unit_amount' => (int) round($finalPrice * 100),
                ],
                'quantity' => 1,
            ]],

            'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('stripe.cancel'),
        ]);

        // Log checkout session creation for webhook tracking
        Log::info('Stripe checkout session created', [
            'session_id' => $checkoutSession->id,
            'user_id' => $user->id,
            'plan_slug' => $plan->slug,
        ]);

        return redirect($checkoutSession->url);
    }

    /**
     * STRIPE SUCCESS CALLBACK
     * ----------------------------------
     * SECURITY: This only confirms redirect
     * Actual subscription creation happens in webhook
     */
    public function success(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $sessionId = $request->query('session_id');
        $paymentSource = Session::get('stripe_payment_source', 'front');
        
        if (!$sessionId) {
            Session::forget('stripe_payment_source');
            $redirectRoute = ($paymentSource === 'resume-builder') ? 'resume-builder.pricing' : 'front.pricing';
            return redirect()->route($redirectRoute)
                ->with('error', 'Payment session missing.');
        }

        try {
            $session = StripeSession::retrieve($sessionId);

            /** ----------------------------
             *  VERIFY SECURITY TOKEN
             * ---------------------------- */
            if (($session->metadata['security_token'] ?? null) !== Session::get('stripeSecurityToken')) {
                Session::forget('stripe_payment_source');
                $redirectRoute = ($paymentSource === 'resume-builder') ? 'resume-builder.pricing' : 'front.pricing';
                return redirect()->route($redirectRoute)
                    ->with('error', 'Invalid payment session.');
            }

            Session::forget('stripeSecurityToken');
            Session::forget('plan_slug');
            Session::forget('stripe_payment_source');

            /** ----------------------------
             *  REDIRECT BASED ON PAYMENT SOURCE
             * Webhook will handle subscription creation
             * ---------------------------- */
            if ($paymentSource === 'resume-builder') {
                return redirect()->route('resume-builder.form')
                    ->with('success', 'Payment successful! Let\'s build your resume.');
            }
            
            return redirect()->route('front.pricing')
                ->with('success', 'Payment submitted. Your subscription will be activated shortly.');

        } catch (\Exception $e) {
            Session::forget('stripe_payment_source');
            $redirectRoute = ($paymentSource === 'resume-builder') ? 'resume-builder.pricing' : 'front.pricing';
            return redirect()->route($redirectRoute)
                ->with('error', 'Error verifying payment. Please contact support.');
        }
    }

    /**
     * STRIPE WEBHOOK HANDLER
     * ----------------------------------
     * SECURE: Only creates subscriptions on confirmed webhook events
     * Handles: charge.succeeded, checkout.session.completed
     */
    public function handleStripeWebhook(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        $payload = $request->getContent();
        $sig_header = $request->header('stripe-signature');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'charge.succeeded':
                $this->handleChargeSucceeded($event->data->object);
                break;

            case 'checkout.session.completed':
                $this->handleCheckoutSessionCompleted($event->data->object);
                break;

            case 'charge.refunded':
                $this->handleChargeRefunded($event->data->object);
                break;

            default:
                // Unhandled event type
                break;
        }

        return response()->json(['success' => true]);
    }

    /**
     * HANDLE CHARGE SUCCEEDED
     * Creates subscription and related records
     */
    private function handleChargeSucceeded($charge)
    {
        // Avoid duplicate processing
        $existingTransaction = \App\Models\Transaction::where('payment_id', $charge->id)->exists();
        if ($existingTransaction) {
            return;
        }

        // Get metadata from Checkout Session (not from charge/payment intent)
        $metadata = [];
        $sessionId = null;

        // Step 1: Get PaymentIntent from charge
        if (!empty($charge->payment_intent)) {
            try {
                $paymentIntent = \Stripe\PaymentIntent::retrieve($charge->payment_intent);
                
                // Step 2: Find the checkout session via the payment intent
                // Search for the session that used this payment intent
                $sessions = \Stripe\Checkout\Session::all([
                    'payment_intent' => $paymentIntent->id,
                    'limit' => 1,
                ]);

                if (!empty($sessions->data) && count($sessions->data) > 0) {
                    $session = $sessions->data[0];
                    $sessionId = $session->id;
                    $metadata = $session->metadata ?? [];
                }
            } catch (\Exception $e) {
                \Log::error('Failed to retrieve session from payment intent', [
                    'payment_intent' => $charge->payment_intent ?? 'none',
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Fallback to charge/payment intent metadata if session lookup fails
        if (empty($metadata)) {
            try {
                if (!empty($charge->payment_intent)) {
                    $paymentIntent = \Stripe\PaymentIntent::retrieve($charge->payment_intent);
                    $metadata = $paymentIntent->metadata ?? [];
                }
            } catch (\Exception $e) {
                // Continue
            }
        }

        // Last resort fallback to charge metadata
        if (empty($metadata)) {
            $metadata = $charge->metadata ?? [];
        }

        $userId = $metadata['user_id'] ?? null;
        $planSlug = $metadata['plan_slug'] ?? null;
        
        if (!$userId || !$planSlug) {
            \Log::error('Stripe charge missing required metadata', [
                'charge_id' => $charge->id,
                'payment_intent' => $charge->payment_intent ?? 'none',
                'session_id' => $sessionId ?? 'not_found',
                'metadata' => $metadata
            ]);
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            \Log::error('User not found for charge', ['user_id' => $userId, 'charge_id' => $charge->id]);
            return;
        }

        $plan = SubPlan::where('slug', $planSlug)->first();
        if (!$plan) {
            \Log::error('Plan not found', ['plan_slug' => $planSlug, 'charge_id' => $charge->id]);
            return;
        }

        // Calculate expiry date
        $expiryDate = match ($plan->interval) {
            'weekly'   => now()->addWeek(),
            'biweekly' => now()->addWeeks(2),
            'monthly'  => now()->addMonth(),
            'quarterly' => now()->addMonths(3),
            'yearly'   => now()->addYear(),
            default    => now()->addWeek(),
        };

        $amountPaid = $charge->amount / 100;
        $token = strtoupper(Str::random(6)) . '-' . time();

        // Create or update order
        $order = Order::firstOrCreate(
            ['payment_id' => $charge->id],
            [
                'user_id'        => $user->id,
                'plan_id'        => $plan->id,
                'amount'         => $amountPaid,
                'token'          => $token,
                'expires_at'     => $expiryDate,
                'access_section' => $plan->access_section,
            ]
        );

        // Create or update user subscription
        $subscription = UserSubscription::firstOrCreate(
            ['payment_id' => $charge->id],
            [
                'user_id'        => $user->id,
                'plan_id'        => $plan->id,
                'plan_slug'      => $plan->slug,
                'amount'         => $amountPaid,
                'token'          => $token,
                'starts_at'      => now(),
                'expires_at'     => $expiryDate,
                'access_section' => $plan->access_section,
                'features_used'  => [],
                'status'         => 'active',
            ]
        );
        // Track user activity
        UserActivity::create([
            'user_id'  => $user->id,
            'order_id' => $order->id,
        ]);

        // Log transaction
        TransactionLogic::createTransaction(
            $order->id,
            $user->id,
            'stripe',
            $amountPaid,
            0,
            $plan->id,
            'subscription',
            $charge->id
        );

        // Handle discount
        $discountType = $metadata['discount_type'] ?? 'none';

        if ($discountType === 'promo') {
            $promoCodeId = $metadata['promo_code_id'] ?? null;
            if ($promoCodeId) {
                $promoCode = \App\Models\PromoCode::find($promoCodeId);
                if ($promoCode) {
                    $promoCode->increment('used_count');
                    $promoCode->users()->updateExistingPivot($user->id, ['used' => true]);
                }
            }
        } elseif ($discountType === 'referral') {
            if ((int)$user->referral_discount_used === 0) {
                $user->referral_discount_used = 1;
                $user->save();
            }

            if (!empty($user->referred_by)) {
                $referrer = User::where('affiliate_code', $user->referred_by)->first();
                if ($referrer) {
                    $commission = $amountPaid * ($plan->commission_percentage / 100);
                    $referrer->increment('wallet', $commission);

                    Wallet::create([
                        'user_to'        => $referrer->id,
                        'user_by'        => $user->id,
                        'transaction_id' => $charge->id,
                        'amount'         => $commission,
                        'status'         => 'credit',
                    ]);
                }
            }
        }

        // Send confirmation email
        try {
            SendSubscriptionEmailJob::dispatch($user, $order, $plan);
        } catch (\Throwable $e) {
            \Log::error('Failed to send subscription email', ['user_id' => $user->id, 'error' => $e->getMessage()]);
        }

        \Log::info('Subscription created from webhook', [
            'user_id' => $user->id,
            'order_id' => $order->id,
            'charge_id' => $charge->id,
        ]);
    }

    /**
     * HANDLE CHECKOUT SESSION COMPLETED
     * Fallback for checkout.session.completed event
     */
    private function handleCheckoutSessionCompleted($session)
    {
        if ($session->payment_status === 'paid') {
            // Get charge ID and process
            if (!empty($session->payment_intent)) {
                $paymentIntent = \Stripe\PaymentIntent::retrieve($session->payment_intent);
                if (!empty($paymentIntent->charges->data)) {
                    $charge = $paymentIntent->charges->data[0];
                    $this->handleChargeSucceeded($charge);
                }
            }
        }
    }

    /**
     * HANDLE CHARGE REFUNDED
     * Deactivate subscription on refund
     */
    private function handleChargeRefunded($charge)
    {
        $subscription = UserSubscription::where('payment_id', $charge->id)->first();
        if ($subscription) {
            $subscription->update(['status' => 'refunded', 'expires_at' => now()]);
            
            \Log::info('Subscription refunded', [
                'subscription_id' => $subscription->id,
                'charge_id' => $charge->id,
            ]);
        }
    }

    /**
     * STRIPE CANCEL CALLBACK
     */
    public function cancel()
    {
        Session::forget('plan_slug');
        Session::forget('stripeSecurityToken');
        
        // Check payment source and redirect accordingly
        $paymentSource = Session::get('stripe_payment_source', 'front');
        Session::forget('stripe_payment_source');
        
        $redirectRoute = ($paymentSource === 'resume-builder') ? 'resume-builder.pricing' : 'front.pricing';
        
        return redirect()->route($redirectRoute)
            ->with('info', 'Payment cancelled. You can try again anytime.');
    }
}
