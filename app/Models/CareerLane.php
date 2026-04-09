<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerLane extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'related_titles',
        'key_skills',
        'seniority_level',
        'primary_sector',
        'alternate_sectors',
        'is_active',
    ];

    protected $casts = [
        'related_titles' => 'array',
        'key_skills' => 'array',
        'alternate_sectors' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get active career lanes only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Find by slug
     */
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug)->first();
    }
}
