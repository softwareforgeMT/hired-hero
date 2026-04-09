@extends('front.layouts.app')
@section('title') Home @endsection

@section('css')
@endsection

@section('content')
<div class="page__content">
  <section class="section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <h1 class="text-center">Setup Your Mock Interview</h1>
          <p class="text-muted text-center">Enter the job details and select your preferred question difficulty to begin.</p>

          <div class="card">
            <div class="card-body">
              <form method="POST" action="{{ route('mock.interview.index') }}">
                @csrf

                {{-- Job Description --}}
                <div class="mb-3">
                  <label for="job_description" class="form-label">{{ __('Job Details') }}</label>
                  <textarea id="job_description"
                            class="form-control @error('job_description') is-invalid @enderror"
                            name="job_description"
                            rows="5"
                            required>{{ old('job_description', request('job')) }}</textarea>
                  <span class="text-sm">Max 8000 characters.</span>
                  @error('job_description')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>

                {{-- Difficulty Level --}}
                <div class="mb-3">
                  <label>{{ __('Difficulty Level') }}</label><br>
                  <div class="hstack difficulty_level_btns gap-2 flex-wrap">
                    @foreach ($availableLevels as $level)
                      <input type="radio"
                             class="btn-check visually-hidden"
                             name="difficulty_level"
                             id="btn-check-{{ $level }}"
                             value="{{ $level }}"
                             {{ old('difficulty_level') == $level ? 'checked' : '' }}
                             required>
                      <label class="d-flex gap-2 btn btn-outline-primary @error('difficulty_level') is-invalid @enderror"
                             for="btn-check-{{ $level }}">
                        <i class="ri-dashboard-line"></i> {{ ucfirst($level) }}
                      </label>
                    @endforeach
                  </div>
                  @error('difficulty_level')
                    <div class="invalid-feedback d-block">
                      <strong>{{ $message }}</strong>
                    </div>
                  @enderror
                </div>

                <div class="form-group mb-0 text-center">
                  <button type="submit" class="btn btn-success">
                    {{ __('Generate Mock Interview') }}
                  </button>
                </div>

              </form>
            </div> <!-- /card-body -->
          </div> <!-- /card -->

        </div>
      </div>
    </div>
  </section>
</div>
@endsection

@section('script')
<script>
(function() {
  var ta = document.getElementById('job_description');
  if (!ta) return;

  // If server already prefilled (old() or ?job=...), do nothing.
  if (ta.value && ta.value.trim().length > 0) return;

  // If URL has ?job= param (retake flow), do nothing.
  if (new URLSearchParams(window.location.search).has('job')) return;

  // Otherwise, set your demo template for first-time/blank visits.
  var jobDescription = `Job Title: Laravel Developer
Location:
Remote - US Based

Job Description:
We are looking for a skilled Laravel developer to join our dynamic development team. This role requires a driven individual who is able to collaborate with team members and work with minimal supervision to craft high-quality web applications.

Responsibilities:
Develop and maintain web applications using Laravel framework.
Collaborate with front-end developers to integrate user-facing elements with server-side logic.
Build efficient, testable, and reusable PHP modules.
Solve complex performance problems and architectural challenges.
Integrate data storage solutions including databases, key-value stores, blob stores, etc.
Ensure the highest level of security and data protection.
Maintain and improve existing codebases and peer review code changes.
Liaise with developers, designers, and system administrators to identify new features.

Requirements:
Bachelor's degree in Computer Science, Engineering, or a related subject.
Proven software development experience in PHP and Laravel framework.
Demonstrable knowledge of web technologies including HTML, CSS, JavaScript, AJAX, etc.
Good knowledge of relational databases, version control tools, and developing web services.
Experience in common third-party APIs (Google, Facebook, Ebay, etc).
Passion for best design and coding practices and a desire to develop new bold ideas.
Excellent relational and communication skills.

Preferred Qualifications:
Experience with AWS or other cloud services.
Familiarity with front-end development frameworks such as AngularJS or React.

Benefits:
Competitive salary and stock options.
Health, dental, and vision insurance.
Paid time off and parental leave.
Remote work opportunities.
A vibrant and inclusive team environment.
Continuous learning and professional development opportunities.`;

  ta.value = jobDescription;
})();
</script>
@endsection
