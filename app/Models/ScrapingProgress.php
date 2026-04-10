<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScrapingProgress extends Model
{
    use HasFactory;

    protected $table = 'scraping_progress';

    protected $fillable = [
        'user_id',
        'status',
        'progress',
        'message',
        'total_jobs',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Get active/current job scraping
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'processing']);
    }

    /**
     * Check if scraping is in progress
     */
    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    /**
     * Check if scraping is completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if scraping failed
     */
    public function isFailed()
    {
        return $this->status === 'failed';
    }
}
