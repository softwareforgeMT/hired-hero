@extends('front.layouts.app')
@section('title') Presentation Feedback @endsection
@section('css')
<style>
  .presentation-section {
    border-bottom: 1px solid #ddd; /* subtle line for separation */
  }
</style>
@endsection

@section('content')
<div class="page__content">
    <section class="section">
        <div class="container">
            <h1>Presentation Feedback</h1>
            @if(isset($error))
                <div class="alert alert-danger">{{ $error }}</div>
            @else
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="h5 font-weight-bold">Overall Feedback</h2>
                                <p>{!! nl2br(e($feedback)) !!}</p> <!-- Ensure to escape output with e() -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="h5 font-weight-bold">Presentation Details</h3>
                                <p>Topic: <strong>{{ $presentationData['presentation_topic'] ?? 'Not specified' }}</strong></p>
                                <p>Transcription:</p>
                                <p>{!! nl2br(e($presentationData['transcription'])) !!}</p> <!-- Escape output to prevent XSS -->
                                <audio controls class="w-100 mt-3">
                                    <source src="{{ asset('presentations/recordings/' . $presentationData['audio_filename']) }}" type="audio/webm">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
@section('script')
@endsection
