<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewAttempt extends Model
{
    protected $fillable = ['user_id','question_count','score','payload','completed_at'];

    protected $casts = [
        'payload' => 'array',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
