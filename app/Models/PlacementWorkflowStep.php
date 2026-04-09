<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlacementWorkflowStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'placement_profile_id',
        'step_number',
        'status',
        'step_data',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'step_data' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
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
     * Get step name
     */
    public function getStepName()
    {
        return match($this->step_number) {
            1 => 'Entry',
            2 => 'Location',
            3 => 'Professional Profile',
            4 => 'Resume Upload',
            5 => 'AI Feedback',
            6 => 'Role Mapping',
            7 => 'Job Matches',
            default => 'Unknown',
        };
    }

    /**
     * Get step description
     */
    public function getStepDescription()
    {
        return match($this->step_number) {
            1 => 'Tell us about your job preferences',
            2 => 'Where are you located?',
            3 => 'Your professional background',
            4 => 'Upload your resume',
            5 => 'See what we found',
            6 => 'Select your target roles',
            7 => 'Your personalized job matches',
            default => 'Unknown step',
        };
    }
}
