<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\InterviewAttempt;
use Illuminate\Http\Request;

class ResultHistoryController extends Controller
{
    public function index(Request $request)
    {
        $attempts = InterviewAttempt::where('user_id', $request->user()->id)
            ->latest('completed_at')
            ->paginate(12);

        return view('user.results.index', compact('attempts'));
    }

    public function show(Request $request, InterviewAttempt $attempt)
    {
        abort_unless($attempt->user_id === $request->user()->id, 403);
        return view('user.results.show', compact('attempt'));
    }
}
