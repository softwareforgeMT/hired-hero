<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\TrendCard;

class TrendsController extends Controller
{
    public function index()
    {
        $latestBatchKey = TrendCard::query()
            ->published()
            ->orderByDesc('published_at')
            ->value('batch_key');

        $cards = TrendCard::query()
            ->published()
            ->when($latestBatchKey, fn ($q) => $q->where('batch_key', $latestBatchKey))
            ->orderByRaw("FIELD(category,'job_seekers','employers','interview')")
            ->get();

        $lastUpdated = $cards->max('published_at');

        return view('front.trends.index', compact('cards', 'lastUpdated', 'latestBatchKey'));
    }
}
