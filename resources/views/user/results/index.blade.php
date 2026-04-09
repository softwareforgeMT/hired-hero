@extends('front.layouts.app')
@section('title', 'My Results')

@section('content')
<section class="section page__content">
  <div class="container">
    <div class="row mt-5">
      <!-- Sidebar -->
      <div class="col-md-3">
        @include('user.layouts.sidebar')
      </div>

      <!-- Main Content -->
      <div class="col-md-9">
        <div class="card">
          <div class="card-body">
            <h4 class="mb-3">My Results</h4>

            @if($attempts->isEmpty())
              <p>No results yet. Run a mock interview and come back.</p>
            @else
              <div class="row">
                @foreach($attempts as $a)
                  <div class="col-md-6 col-lg-4 mb-3">
                    <a href="{{ route('user.results.show', $a) }}" class="card" style="text-decoration:none;">
                      <div class="card-body">
                        <div class="text-muted" style="font-size:12px;">
                          {{ optional($a->completed_at)->format('M d, Y H:i') }}
                        </div>
                        <div class="mt-2 fw-semibold">Score: {{ $a->score ?? '—' }}</div>
                        <div class="text-muted">Questions: {{ $a->question_count }}</div>
                      </div>
                    </a>
                  </div>
                @endforeach
              </div>

              <div class="mt-3">
                {{ $attempts->links() }}
              </div>
            @endif

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
