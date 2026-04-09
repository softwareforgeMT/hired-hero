<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoverLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_match_id',
        'job_title',
        'company_name',
        'content',
        'file_path',
        'file_url',
        'status',
    ];

    /**
     * Get the user that owns the cover letter
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the job match that this cover letter is for
     */
    public function jobMatch(): BelongsTo
    {
        return $this->belongsTo(JobMatch::class);
    }

    /**
     * Check if the cover letter is finalized
     */
    public function isFinalized(): bool
    {
        return $this->status === 'finalized';
    }

    /**
     * Finalize the cover letter
     */
    public function finalize(): void
    {
        $this->update(['status' => 'finalized']);
    }

    /**
     * Archive the cover letter
     */
    public function archive(): void
    {
        $this->update(['status' => 'archived']);
    }

    /**
     * Check if file exists
     */
    public function fileExists(): bool
    {
        return $this->file_path && file_exists(storage_path('app/' . $this->file_path));
    }

    /**
     * Get display title for the cover letter
     */
    public function getDisplayTitle(): string
    {
        return "{$this->job_title} - {$this->company_name}";
    }
}
