<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlacementProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'job_type',
        'salary_min',
        'salary_max',
        'country',
        'city',
        'work_permit_status',
        'industries',
        'job_level',
        'job_languages',
        'email',
        'resume_path',
        'resume_data',
        'has_resume',
        'skills',
        'extracted_skills',
        'years_experience',
        'past_companies',
        'past_sectors',
        'suggested_roles',
        'selected_roles',
        'standardized_profile',
        'current_step',
        'is_completed',
        'completed_at',
        'free_access_expires_at',
        'has_active_placement_subscription',
        'placement_subscription_expires_at',
    ];

    protected $casts = [
        'industries' => 'array',
        'job_languages' => 'array',
        'resume_data' => 'array',
        'skills' => 'array',
        'extracted_skills' => 'array',
        'past_companies' => 'array',
        'past_sectors' => 'array',
        'suggested_roles' => 'array',
        'selected_roles' => 'array',
        'standardized_profile' => 'array',
        'completed_at' => 'datetime',
        'free_access_expires_at' => 'date',
        'placement_subscription_expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the placement profile
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all job matches for this profile
     */
    public function jobMatches()
    {
        return $this->hasMany(JobMatch::class);
    }

    /**
     * Get all workflow steps for this profile
     */
    public function workflowSteps()
    {
        return $this->hasMany(PlacementWorkflowStep::class);
    }

    /**
     * Get all surveys for this profile
     */
    public function surveys()
    {
        return $this->hasMany(PlacementSurvey::class);
    }

    /**
     * Get all job applications for this profile
     */
    public function jobApplications()
    {
        return $this->hasManyThrough(JobApplication::class, User::class, 'id', 'user_id', 'user_id', 'id');
    }

    /**
     * Check if user has free access
     */
    public function hasFreeAccess()
    {
        return $this->free_access_expires_at && now()->lessThanOrEqualTo($this->free_access_expires_at);
    }

    /**
     * Check if user has active placement subscription
     */
    public function hasActiveSubscription()
    {
        return $this->has_active_placement_subscription && 
               $this->placement_subscription_expires_at && 
               now()->lessThanOrEqualTo($this->placement_subscription_expires_at);
    }

    /**
     * Check if user can access job matches
     */
    public function canAccessJobMatches()
    {
        return $this->hasFreeAccess() || $this->hasActiveSubscription();
    }

    /**
     * Get the current workflow step
     */
    public function getCurrentStep()
    {
        return $this->workflowSteps()->where('step_number', $this->current_step)->first();
    }

    /**
     * Mark a step as completed
     */
    public function completeStep($stepNumber, $data = null)
    {
        $step = $this->workflowSteps()->where('step_number', $stepNumber)->first();
        
        if (!$step) {
            $step = $this->workflowSteps()->create([
                'step_number' => $stepNumber,
                'status' => 'completed',
                'step_data' => $data,
                'completed_at' => now(),
            ]);
        } else {
            $step->update([
                'status' => 'completed',
                'step_data' => $data,
                'completed_at' => now(),
            ]);
        }

        // Move to next step
        if ($stepNumber < 7) {
            $this->update(['current_step' => $stepNumber + 1]);
        } else {
            $this->markAsCompleted();
        }

        return $step;
    }

    /**
     * Mark profile as completed
     */
    public function markAsCompleted()
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
            'free_access_expires_at' => now()->addDays(14),
        ]);
    }
}
