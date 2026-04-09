<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    // Table name (optional if following Laravel conventions)
    protected $table = 'wallets';

    // Fillable fields for mass assignment
    protected $fillable = [
        'debit_user_id',
        'user_to',
        'user_by',
        'transaction_id',
        'amount',
        'status', // 'credit' or 'debit'
    ];

    // Optional: cast amount to float/double
    protected $casts = [
        'amount' => 'double',
    ];

    // Relationships (optional but recommended)

    // User who receives the wallet credit/debit
    public function recipient()
    {
        return $this->belongsTo(User::class, 'user_to');
    }

    // User who triggered the transaction
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_by');
    }

    // Optional: transaction relationship if you have transactions table
    // public function transaction()
    // {
    //     return $this->belongsTo(Order::class, 'transaction_id');
    // }
}
