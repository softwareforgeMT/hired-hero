<?php

return [
    // Toggle referred-user discount (keep false until you finish testing)
    'discount_enabled' => (bool) env('REFERRAL_DISCOUNT_ENABLED', false),

    // 20 % one-time discount for new referred users
    'discount_percent' => (int) env('REFERRAL_DISCOUNT_PERCENT', 20),

    // Stripe coupon ID for that 20 %-once discount
    'stripe_coupon_id' => env('REFERRAL_STRIPE_COUPON_ID'),

    // Flat 20 % commission for referrers
    'commission_percent' => (int) env('REFERRAL_COMMISSION_PERCENT', 20),
];
