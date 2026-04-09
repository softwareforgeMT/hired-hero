<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobMatchEmailLog extends Model
{
    use HasFactory;

    protected $table = 'job_match_email_logs';

    protected $fillable = [
        'user_id',
        'placement_profile_id',
        'selected_role',
        'job_count',
        'job_ids',
        'sent_at',
        'last_sent_week',
    ];

    protected $casts = [
        'job_ids' => 'array',
        'sent_at' => 'datetime',
        'last_sent_week' => 'datetime',
    ];

    /**
     * Get the user associated with this email log
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the placement profile associated with this email log
     */
    public function placementProfile()
    {
        return $this->belongsTo(PlacementProfile::class);
    }

    /**
     * Get the jobs sent in this email
     */
    public function jobs()
    {
        return JobMatch::whereIn('id', $this->job_ids ?? [])->get();
    }
}
