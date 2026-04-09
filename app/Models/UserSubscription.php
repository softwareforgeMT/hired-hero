<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'plan_slug',
        'amount',
        'payment_id',
        'token',
        'starts_at',
        'expires_at',
        'access_section',
        'features_used',
        'status',
        'cancelled_at',
    ];

    protected $casts = [
        'access_section' => 'array',
        'features_used' => 'array',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Get the user that owns the subscription
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan that this subscription is for
     */
    public function plan()
    {
        return $this->belongsTo(SubPlan::class);
    }

    /**
     * Check if subscription is currently active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && 
               ($this->expires_at === null || $this->expires_at > now());
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at <= now();
    }

    /**
     * Scope to get only active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where(function($q) {
                         $q->whereNull('expires_at')
                           ->orWhere('expires_at', '>', now());
                     });
    }

    /**
     * Scope to get subscriptions by user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get subscriptions by plan
     */
    public function scopeForPlan($query, $planId)
    {
        return $query->where('plan_id', $planId);
    }

    /**
     * Get transactions for this subscription
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'subscription_id');
    }

    /**
     * Get latest transaction for this subscription
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'subscription_id')->latest();
    }

    /**
     * Increment a feature usage counter
     * @param string $featureName - e.g., 'cover_letters', 'job_searches'
     * @param int $amount - amount to increment (default 1)
     */
    public function incrementFeatureUsage($featureName, $amount = 1)
    {
        $used = $this->features_used ?? [];
        $used[$featureName] = ($used[$featureName] ?? 0) + $amount;
        $this->update(['features_used' => $used]);
        return $used[$featureName];
    }

    /**
     * Get feature usage count
     * @param string $featureName
     * @return int
     */
    public function getFeatureUsage($featureName)
    {
        $used = $this->features_used ?? [];
        return $used[$featureName] ?? 0;
    }

    /**
     * Check if user has reached limit for a feature
     * @param string $featureName - e.g., 'ai_tailored_cover'
     * @return bool
     */
    public function hasReachedFeatureLimit($featureName)
    {
        $accessSection = $this->access_section ?? [];
        $jobMatches = $accessSection['jobMatches'] ?? [];
        $limit = $jobMatches[$featureName] ?? null;

        // If limit is 'unlimited' or null, they haven't reached it
        if ($limit === 'unlimited' || $limit === null) {
            return false;
        }

        $used = $this->getFeatureUsage($featureName);
        return $used >= $limit;
    }

    /**
     * Get remaining count for a feature
     * @param string $featureName
     * @return int|string - returns 'unlimited' or the remaining count
     */
    public function getRemainingFeatureCount($featureName)
    {
        $accessSection = $this->access_section ?? [];
        $jobMatches = $accessSection['jobMatches'] ?? [];
        $limit = $jobMatches[$featureName] ?? 0;

        if ($limit === 'unlimited') {
            return 'unlimited';
        }

        $used = $this->getFeatureUsage($featureName);
        return max(0, $limit - $used);
    }
}
