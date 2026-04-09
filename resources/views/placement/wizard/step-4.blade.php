@extends('placement.wizard.layout')

@section('step-content')
<div class="wizard-step-content">
    <div class="mb-5">
        <h2 class="step-title mb-3">What's Your Job Level?</h2>
        <p class="step-description">Select the position level that matches your experience and expertise.</p>
    </div>

    <form action="{{ route('placement.wizard.submit', ['step' => 4]) }}" method="POST">
        @csrf

        <div class="form-group mb-5">
            <label class="form-label fw-semibold mb-3">
                Job Level <span class="text-danger">*</span>
            </label>
            <div class="level-grid">
                @php
                    $levels = [
                        'entry' => ['Entry Level', 'Recent graduate or 0-2 years experience'],
                        'mid' => ['Mid-Level', '3-7 years of relevant experience'],
                        'senior' => ['Senior Level', '8+ years of experience'],
                        'executive' => ['Executive Level', 'Manager, Director, or C-Suite']
                    ];
                    $currentJobLevel = $profile ? $profile->job_level : ($sessionData[4]['job_level'] ?? old('job_level'));
                @endphp

                @foreach ($levels as $value => $label)
                    <div class="level-card">
                        <input type="radio" name="job_level" id="level_{{ $value }}" 
                               value="{{ $value }}" class="level-radio"
                               {{ $currentJobLevel === $value ? 'checked' : '' }}
                               required>
                        <label for="level_{{ $value }}" class="level-label">
                            <span class="level-title">{{ $label[0] }}</span>
                            <small class="level-desc">{{ $label[1] }}</small>
                        </label>
                    </div>
                @endforeach

                <div class="level-card">
                    <input type="radio" name="job_level" id="level_no_preference" 
                           value="no-preference" class="level-radio"
                           {{ $currentJobLevel === 'no-preference' ? 'checked' : '' }}>
                    <label for="level_no_preference" class="level-label">
                        <span class="level-title">No Preference</span>
                        <small class="level-desc">Show me all opportunities</small>
                    </label>
                </div>
            </div>
            @error('job_level')
                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center pt-4 border-top">
            <span class="progress-text">
                Progress: <strong>40%</strong>
            </span>
            <div>
                <a href="{{ route('placement.wizard.step', ['step' => 3]) }}" class="btn btn-outline-secondary me-2 waves-effect waves-light">
                    Back
                </a>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Next</button>
            </div>
        </div>
    </form>
</div>

<style>
    .wizard-step-content {
        padding: 2rem 0;
    }

    .step-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: #1a1a1a;
        line-height: 1.3;
    }

    .step-description {
        font-size: 1rem;
        color: #6c757d;
        line-height: 1.6;
        max-width: 600px;
    }

    .level-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .level-card {
        position: relative;
    }

    .level-radio {
        display: none;
    }

    .level-label {
        display: block;
        padding: 1.5rem;
        border: 2px solid #cbd5e0;
        border-radius: 0.75rem;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        margin: 0;
        color: #2d3748;
    }

    .level-title {
        display: block;
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .level-desc {
        display: block;
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    .level-radio:checked + .level-label {
        border-color: #3b82f6;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #1e40af;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .level-label:hover {
        border-color: #3b82f6;
        background-color: #f0f4ff;
        transform: translateY(-2px);
    }

    .form-label {
        font-size: 0.95rem;
        color: #2d3748;
        letter-spacing: 0.01em;
    }

    .form-label .text-danger {
        font-weight: 600;
    }

    .progress-text {
        font-size: 0.95rem;
        color: #4a5568;
    }

    .progress-text strong {
        color: #2d3748;
        font-weight: 600;
    }

    .btn-outline-secondary {
        border-width: 1.5px;
    }

    .btn-outline-secondary:hover {
        background-color: #f7fafc;
        transform: translateY(-1px);
    }

    .border-top {
        border-color: #e2e8f0 !important;
        margin-top: 1rem;
    }

    .invalid-feedback {
        font-size: 0.875rem;
        color: #dc2626;
    }

    @media (max-width: 768px) {
        .step-title {
            font-size: 1.5rem;
        }

        .wizard-step-content {
            padding: 1rem 0;
        }

        .level-grid {
            grid-template-columns: 1fr;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }

        .btn-outline-secondary,
        button[type="submit"] {
            width: 100%;
        }

        .progress-text {
            order: -1;
            text-align: center;
        }
    }
</style>

<script>
    document.querySelectorAll('.level-label').forEach(label => {
        label.addEventListener('click', function() {
            const radio = this.previousElementSibling;
            radio.checked = true;
        });
    });
</script>
@endsection
