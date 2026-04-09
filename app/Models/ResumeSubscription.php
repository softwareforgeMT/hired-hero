<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ResumeSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stripe_subscription_id',
        'stripe_customer_id',
        'plan_type',
        'status',
        'amount',
        'started_at',
        'expires_at',
        'canceled_at',
        'payment_method',
        'stripe_product_id',
        'stripe_price_id',
        'promo_code_id',
        'discount_amount',
        'original_amount',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'canceled_at' => 'datetime',
    ];

    /**
     * Get the user that owns this subscription
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the promo code used for this subscription
     */
    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }

    /**
     * Get the placement profiles using this subscription
     */
    public function placementProfiles()
    {
        return $this->hasMany(PlacementProfile::class, 'active_subscription_id');
    }

    /**
     * Check if subscription is currently active
     */
    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Check if subscription has expired
     */
    public function hasExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get days remaining on subscription
     */
    public function getDaysRemaining(): int
    {
        if (!$this->expires_at || $this->expires_at->isPast()) {
            return 0;
        }

        return $this->expires_at->diffInDays(now());
    }

    /**
     * Cancel the subscription
     */
    public function cancel(): void
    {
        $this->update([
            'status' => 'canceled',
            'canceled_at' => now(),
        ]);
    }

    /**
     * Activate the subscription
     */
    public function activate(): void
    {
        $planDays = $this->plan_type === 'weekly' ? 7 : 30;
        
        $this->update([
            'status' => 'active',
            'started_at' => now(),
            'expires_at' => now()->addDays($planDays),
        ]);
    }

    /**
     * Get the duration label (Weekly / Monthly)
     */
    public function getDurationLabel(): string
    {
        return ucfirst($this->plan_type);
    }

    /**
     * Scope to get all active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope to get expired subscriptions
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'active')
            ->where('expires_at', '<=', now());
    }

    /**
     * Scope to get subscriptions for a user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
