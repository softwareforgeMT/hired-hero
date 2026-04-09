<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'job_match_id',
        'job_title',
        'company_name',
        'job_url',
        'status',
        'applied_at',
        'last_activity_at',
        'days_since_application',
        'reminder_sent',
        'reminder_sent_at',
        'interview_date',
        'interview_notes',
        'cover_letter',
        'used_ai_cover_letter',
        'offer_salary',
        'offer_date',
        'offer_accepted',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
        'interview_date' => 'datetime',
        'offer_date' => 'datetime',
        'offer_accepted' => 'boolean',
        'reminder_sent' => 'boolean',
        'used_ai_cover_letter' => 'boolean',
    ];

    /**
     * Get the user associated with this application
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the job match
     */
    public function jobMatch()
    {
        return $this->belongsTo(JobMatch::class);
    }

    /**
     * Check if application needs reminder
     */
    public function needsReminder()
    {
        return !$this->reminder_sent && 
               $this->status === 'applied' &&
               $this->applied_at &&
               now()->diffInDays($this->applied_at) >= 10;
    }

    /**
     * Mark application with reminder sent
     */
    public function markReminderSent()
    {
        $this->update([
            'reminder_sent' => true,
            'reminder_sent_at' => now(),
        ]);
    }

    /**
     * Update application status
     */
    public function updateStatus($status, $notes = null)
    {
        $this->update([
            'status' => $status,
            'last_activity_at' => now(),
        ]);

        if ($notes && $status === 'interview') {
            $this->update(['interview_notes' => $notes]);
        }
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColor()
    {
        return match($this->status) {
            'to-review' => 'warning',
            'ready' => 'info',
            'applied' => 'primary',
            'callback' => 'secondary',
            'interview' => 'success',
            'offer' => 'success',
            'hired' => 'success',
            'rejected' => 'danger',
            'archived' => 'dark',
            default => 'secondary',
        };
    }
}
