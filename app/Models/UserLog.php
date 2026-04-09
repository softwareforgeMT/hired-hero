<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    use HasFactory;

    protected $table = 'user_logs'; // Optional, only needed if table name differs from convention

    protected $fillable = [
        'user_id',
        'ip_address',
        'cookies',
        'status',
    ];
    

    /**
     * Relationship: Each log belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
