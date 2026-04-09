<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_percentage',
        'max_usage',
        'used_count',
        'expires_at',
        'active',
        'is_bulk',
        'description',
        'sent_to_user_ids',
        'sent_to_custom_emails',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'sent_to_user_ids' => 'array',
        'sent_to_custom_emails' => 'array',
    ];

    /**
     * Get users assigned to this promo code
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'promo_code_user')
                    ->withPivot('used', 'used_at')
                    ->withTimestamps();
    }

    /**
     * Check if the promo code is valid and can be used
     */
    public function isValid(): bool
    {
        // Check if code is active
        if (!$this->active) {
            return false;
        }

        // Check if code has expired
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        // Check if code has reached max usage
        if ($this->used_count >= $this->max_usage) {
            return false;
        }

        return true;
    }

    /**
     * Check if a specific user can use this promo code
     */
    public function canUserUse(User $user): bool
    {
        // First check if code is valid
        if (!$this->isValid()) {
            return false;
        }

        // Check if user is assigned this code
        $userAssignment = $this->users()->where('user_id', $user->id)->first();
        
        if (!$userAssignment) {
            return false;
        }

        // Check if user has already used it
        if ($userAssignment->pivot->used) {
            return false;
        }

        return true;
    }

    /**
     * Apply promo code to a user
     */
    public function applyToUser(User $user, float $originalPrice): array
    {
        if (!$this->canUserUse($user)) {
            return [
                'success' => false,
                'message' => 'This promo code cannot be applied.',
                'discount' => 0,
                'final_price' => $originalPrice
            ];
        }

        $discount = ($originalPrice * $this->discount_percentage) / 100;
        $finalPrice = $originalPrice - $discount;

        return [
            'success' => true,
            'message' => 'Promo code applied successfully!',
            'discount_percentage' => $this->discount_percentage,
            'discount_amount' => $discount,
            'original_price' => $originalPrice,
            'final_price' => max(0, $finalPrice) // Ensure price doesn't go below 0
        ];
    }

    /**
     * Mark the code as used by a user
     */
    public function markUsedByUser(User $user): bool
    {
        // Update the pivot table
        $this->users()->updateExistingPivot($user->id, [
            'used' => true,
            'used_at' => now()
        ]);

        // Increment used count
        $this->increment('used_count');

        return true;
    }

    /**
     * Check if code has expired
     */
    public function hasExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get days remaining on the code
     */
    public function getDaysRemaining(): int
    {
        if (!$this->expires_at || $this->hasExpired()) {
            return 0;
        }

        return now()->diffInDays($this->expires_at);
    }

    /**
     * Scope to get active codes
     */
    public function scopeActive($query)
    {
        return $query->where('active', true)
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Scope to get expired codes
     */
    public function scopeExpired($query)
    {
        return $query->where(function ($q) {
            $q->where('expires_at', '<=', now())
              ->orWhere('used_count', '>=', $this->getAttribute('max_usage'));
        });
    }
}
