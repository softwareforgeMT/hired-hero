<?php

namespace App\Services;

use App\Models\PromoCode;
use App\Models\User;

class PromoCodeService
{
    /**
     * Validate a promo code for a user
     */
    public function validatePromoCode(string $code, User $user): array
    {
        $code = strtoupper($code);
        $promoCode = PromoCode::where('code', $code)->first();

        if (!$promoCode) {
            return [
                'valid' => false,
                'message' => 'Promo code not found.',
            ];
        }

        if (!$promoCode->canUserUse($user)) {
            if ($promoCode->hasExpired()) {
                return [
                    'valid' => false,
                    'message' => 'This promo code has expired.',
                ];
            }

            if ($promoCode->used_count >= $promoCode->max_usage) {
                return [
                    'valid' => false,
                    'message' => 'This promo code has reached its usage limit.',
                ];
            }

            return [
                'valid' => false,
                'message' => 'This promo code cannot be used by you.',
            ];
        }

        return [
            'valid' => true,
            'promo_code' => $promoCode,
            'discount_percentage' => $promoCode->discount_percentage,
        ];
    }

    /**
     * Calculate discount for a given amount and promo code
     */
    public function calculateDiscount(float $amount, PromoCode $promoCode): array
    {
        $discount = ($amount * $promoCode->discount_percentage) / 100;
        $finalAmount = max(0, $amount - $discount);

        return [
            'original_amount' => $amount,
            'discount_percentage' => $promoCode->discount_percentage,
            'discount_amount' => round($discount, 2),
            'final_amount' => round($finalAmount, 2),
            'promo_code' => $promoCode->code,
        ];
    }

    /**
     * Apply promo code to user
     */
    public function applyPromoCode(PromoCode $promoCode, User $user, float $amount): array
    {
        // Validate user can use code
        if (!$promoCode->canUserUse($user)) {
            return [
                'success' => false,
                'message' => 'Cannot apply this promo code.',
            ];
        }

        // Calculate discount
        $discountData = $this->calculateDiscount($amount, $promoCode);

        return array_merge([
            'success' => true,
            'message' => 'Promo code applied successfully!',
        ], $discountData);
    }

    /**
     * Get all active promo codes for a user
     */
    public function getUserActivePromoCodes(User $user)
    {
        return $user->getActivePromoCodes();
    }

    /**
     * Check if code exists and is valid
     */
    public function codeExists(string $code): bool
    {
        return PromoCode::where('code', strtoupper($code))
            ->where('active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    /**
     * Get promo code by code string
     */
    public function getByCode(string $code): ?PromoCode
    {
        return PromoCode::where('code', strtoupper($code))->first();
    }
}
