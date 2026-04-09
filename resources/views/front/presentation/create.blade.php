@extends('front.layouts.app')
@section('title') Presentation Setup @endsection
@section('css')
@endsection
@section('content')
<div class="page__content">
    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h1 class="text-center">Setup Your Presentation</h1>
                    <p class="text-muted text-center">Enter the details of your presentation topic to begin.</p>
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('presentation.record') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="presentation_topic" class="form-label">{{ __('Presentation Topic Details') }}</label>
                                    <textarea id="presentation_topic" class="form-control @error('presentation_topic') is-invalid @enderror" 
                                              name="presentation_topic" rows="5" required 
                                              maxlength="255">{{ old('presentation_topic') }}</textarea>
                                    <span class="text-sm">Max 255 characters.</span>
                                    @error('presentation_topic')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group mb-0 text-center">
                                    <button type="submit" class="btn btn-primary">{{ __('Proceed') }}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('script')

<script type="text/javascript">
    var presentation_topic=`Cybersecurity in the Age of IoT: Challenges and Solutions`;
    $('#presentation_topic').val(presentation_topic);    
</script>
@endsection
