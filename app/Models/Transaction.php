<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'order_id',
        'subscription_id',
        'plan_id',
        'amount',
        'payment_method',
        'payment_id',
        'txn_id',
        'transaction_type',
        'status',
        'referrer_link',
        'is_cleared',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function order() {
       return $this->belongsTo(Order::class, 'order_id');
    }

    public function subscription() {
        return $this->belongsTo(UserSubscription::class, 'subscription_id');
    }

    public function plan() {
        return $this->belongsTo(SubPlan::class, 'plan_id');
    }
}
