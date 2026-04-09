<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'placement_profile_id',
        'template_name',
        'title',
        'file_path',
        'file_url',
        'data',
        'status',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Get the user that owns this resume
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the placement profile for this resume
     */
    public function placementProfile()
    {
        return $this->belongsTo(PlacementProfile::class);
    }

    /**
     * Check if resume file exists
     */
    public function fileExists(): bool
    {
        return $this->file_path && \Storage::disk('private')->exists($this->file_path);
    }

    /**
     * Get the download URL for the resume
     */
    public function getDownloadUrl(): string
    {
        return route('resume.download', $this->id);
    }

    /**
     * Scope to get latest resume for a user
     */
    public function scopeLatest($query)
    {
        return $query->latest('created_at');
    }

    /**
     * Scope to get active resumes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
