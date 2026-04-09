<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubPlan extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'slug',
        'interval',
        'duration_unit',
        'duration_value',
        'description',
        'price',
        'price_per_unit',
        'crossed_price_per_unit',
        'total_price',
        'crossed_total_price',
        'access_section',
        'status',
    ];

    protected $casts = [
        'access_section' => 'json',
        'status' => 'boolean',
    ];

    /**
     * Scope to get only active plans
     */
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    /**
     * Calculate discounted price based on discount percentage
     * 
     * @param float $discountPercentage
     * @return float
     */
    public function getDiscountedPrice($discountPercentage)
    {
        $discountAmount = ($this->price * $discountPercentage) / 100;
        return max(0, round($this->price - $discountAmount, 2));
    }

    /**
     * Calculate discount amount
     * 
     * @param float $discountPercentage
     * @return float
     */
    public function getDiscountAmount($discountPercentage)
    {
        return round(($this->price * $discountPercentage) / 100, 2);
    }
}
