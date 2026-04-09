<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'placement_profile_id',
        'job_title',
        'company_name',
        'source',
        'job_description',
        'required_skills',
        'location',
        'job_url',
        'salary_min',
        'salary_max',
        'match_score',
        'matched_skills',
        'missing_skills',
        'posted_date',
        'days_posted',
        'image_url',
    ];

    protected $casts = [
        'required_skills' => 'array',
        'matched_skills' => 'array',
        'missing_skills' => 'array',
        'posted_date' => 'string',
    ];

    /**
     * Get the user associated with this job match
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
     * Get the job application if it exists
     */
    public function jobApplication()
    {
        return $this->hasOne(JobApplication::class);
    }

    /**
     * Get all cover letters for this job
     */
    public function coverLetters()
    {
        return $this->hasMany(CoverLetter::class);
    }

    /**
     * Get the latest cover letter for this job
     */
    public function latestCoverLetter()
    {
        return $this->hasOne(CoverLetter::class)->latest();
    }

    /**
     * Get match percentage (0-100)
     */
    public function getMatchPercentage()
    {
        return $this->match_score ?? 0;
    }

    /**
     * Determine match quality badge
     */
    public function getMatchQuality()
    {
        $score = $this->getMatchPercentage();
        
        if ($score >= 80) {
            return 'excellent';
        } elseif ($score >= 60) {
            return 'good';
        } elseif ($score >= 40) {
            return 'fair';
        } else {
            return 'poor';
        }
    }
}
