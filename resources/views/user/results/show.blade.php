@extends('front.layouts.app')
@section('title', 'Result Details')

@section('content')
<section class="section page__content">
  <div class="container">
    <div class="row mt-5">
      {{-- Sidebar --}}
      <div class="col-md-3">
        @include('user.layouts.sidebar')
      </div>

      {{-- Main --}}
      <div class="col-md-9">
        {{-- Header WITHOUT time (you said skip the time) --}}
        <div class="d-flex align-items-center mb-3">
          <a href="{{ route('user.results.index') }}" class="me-2">&larr; Back to Results</a>
          <h4 class="mb-0">Result Details</h4>
        </div>

        {{-- ===== Summary ===== --}}
        <div class="card mb-3">
          <div class="card-body">
            @php
              // Normalize payload
              $payload = is_array($attempt->payload)
                  ? $attempt->payload
                  : (json_decode($attempt->payload ?? '[]', true) ?: []);

              $overall = $payload['overall'] ?? [];
              $job     = $payload['job'] ?? null;

              // Prefer processed questions; fall back to raw session capture
              $questions = $payload['questions'] ?? [];
              if (empty($questions) && !empty($payload['raw'])) {
                  $questions = $payload['raw'];
              }

              // Robust per-question score extraction (handles different keys)
              $extractScore = function($q) {
                  foreach (['score','question_score','rating','points'] as $k) {
                      if (isset($q[$k]) && is_numeric($q[$k])) return (float)$q[$k];
                  }
                  return null;
              };

              $perQuestionScores = array_values(array_filter(
                  array_map($extractScore, $questions),
                  fn($v) => $v !== null
              ));

              $avgFromQuestions = !empty($perQuestionScores)
                  ? round(array_sum($perQuestionScores) / count($perQuestionScores))
                  : null;

              // Try overall score sources in order:
              // 1) DB column 'score'  2) payload.overall.score  3) AVG(per-question score)
              $scoreFromPayload = isset($overall['score']) && is_numeric($overall['score'])
                                  ? (float)$overall['score']
                                  : null;

              $finalScore = $attempt->score ?? $scoreFromPayload ?? $avgFromQuestions;
            @endphp

            {{-- Retake with same job description --}}
            @if($job)
              <form action="{{ route('mock.job-details.create') }}" method="GET" class="mb-3">
                <input type="hidden" name="job" value="{{ $job }}">
                <button type="submit" class="btn btn-primary">
                  Retake with same job description
                </button>
              </form>
            @endif

            <div class="row g-3">
              <div class="col-md-4">
                <div class="fw-semibold">Average Score (this round)</div>
                <div>{{ $finalScore !== null ? $finalScore : '—' }}</div>
              </div>
              <div class="col-md-2">
                <div class="fw-semibold">Questions</div>
                <div>{{ $attempt->question_count }}</div>
              </div>
              <div class="col-md-6">
                @if($job)
                  <div class="fw-semibold mb-1">Job Description (snippet)</div>
                  <div style="max-height:84px;overflow:auto;white-space:pre-wrap;">
                    {{ \Illuminate\Support\Str::limit(preg_replace('/\s+/', ' ', $job), 240) }}
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>

        {{-- ===== Questions ===== --}}
        @if(empty($questions))
          <div class="alert alert-secondary">No question details were stored for this attempt.</div>
        @else
          @foreach($questions as $i => $q)
            @php
              // Support both shapes:
              // raw[] = {question_index, question_text, transcription, audio_filename}
              // questions[] = {question, feedback?, score?, answer?}
              $qText    = $q['question_text'] ?? $q['question'] ?? ('Question '.($i+1));
              $answer   = $q['transcription'] ?? $q['answer'] ?? null;
              $qScore   = $extractScore($q);
              $feedback = $q['feedback'] ?? null;
            @endphp

            <div class="card mb-3">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                  <h5 class="mb-2">Q{{ $i+1 }}. {{ $qText }}</h5>
                  @if(!is_null($qScore))
                    <span class="badge bg-secondary">Score: {{ $qScore }}</span>
                  @endif
                </div>

                @if($answer)
                  <div class="mb-2">
                    <div class="fw-semibold mb-1">Your Answer</div>
                    <div style="white-space:pre-wrap;">{{ $answer }}</div>
                  </div>
                @endif

                @if($feedback)
                  <div class="mt-2">
                    <div class="fw-semibold mb-1">AI Feedback</div>
                    <div style="white-space:pre-wrap;">{{ $feedback }}</div>
                  </div>
                @endif
                {{-- Audio intentionally removed --}}
              </div>
            </div>
          @endforeach
        @endif

      </div>
    </div>
  </div>
</section>
@endsection
