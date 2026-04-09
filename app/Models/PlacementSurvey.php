<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlacementSurvey extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'placement_profile_id',
        'days_after_completion',
        'survey_type',
        'received_interviews',
        'interviews_count',
        'applications_count',
        'additional_feedback',
        'interested_in_interview_practice',
        'email_sent',
        'email_sent_at',
        'email_opened',
        'email_opened_at',
        'responded',
        'responded_at',
    ];

    protected $casts = [
        'email_sent' => 'boolean',
        'email_opened' => 'boolean',
        'responded' => 'boolean',
        'received_interviews' => 'boolean',
        'email_sent_at' => 'datetime',
        'email_opened_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the placement profile
     */
    public function placementProfile()
    {
        return $this->belongsTo(PlacementProfile::class);
    }

    /**
     * Mark email as sent
     */
    public function markEmailSent()
    {
        $this->update([
            'email_sent' => true,
            'email_sent_at' => now(),
        ]);
    }

    /**
     * Mark email as opened
     */
    public function markEmailOpened()
    {
        $this->update([
            'email_opened' => true,
            'email_opened_at' => now(),
        ]);
    }

    /**
     * Mark as responded
     */
    public function markResponded()
    {
        $this->update([
            'responded' => true,
            'responded_at' => now(),
        ]);
    }
}
