<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TrendCard extends Model
{
    protected $table = 'trend_cards';

    protected $fillable = [
        'batch_key',
        'category',
        'badge_text',
        'title',
        'summary',
        'image_url',
        'read_url',
        'is_active',
    ];

    // ✅ ADD BELOW THIS LINE (INSIDE THE CLASS)

    public static function currentBatchKey(): string
    {
        $now = Carbon::now(config('app.timezone'));

        $week = (int) $now->isoWeek();
        $year = (int) $now->isoWeekYear();
        $biweek = (int) ceil($week / 2);

        return sprintf('%d-BW%02d', $year, $biweek);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}
