<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'amount',
        'payment_id',
        'token',
        'expires_at',
        'access_section'
    ];

    protected $casts = [
        'access_section' => 'array',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubPlan::class);
    }

    public function userActivity()
    {
        return $this->hasOne(UserActivity::class);
    }

    // Relationship to Transaction
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
