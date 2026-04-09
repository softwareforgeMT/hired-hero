@extends('placement.wizard.layout')

@section('step-content')
<div class="wizard-step-content">
    <div class="mb-5">
        <h2 class="step-title mb-3">Where are you located?</h2>
        <p class="step-description">Tell us about your location and work eligibility.</p>
    </div>

    @php
        $currentCountry = $profile ? $profile->country : ($sessionData[2]['country'] ?? old('country'));
    @endphp

    <form id="step2Form" action="{{ route('placement.wizard.submit', ['step' => 2]) }}" method="POST">
        @csrf

        <div class="form-group mb-4">
            <label for="country" class="form-label fw-semibold mb-2">
                Country <span class="text-danger">*</span>
            </label>
            <select name="country" id="country" class="form-control form-control-lg input-layout @error('country') is-invalid @enderror" required>
                <option value="">Select your country...</option>
                @foreach ($countries ?? [] as $country)
                    <option value="{{ $country->country_name }}" {{ $currentCountry === $country->country_name ? 'selected' : '' }}>
                        {{ $country->country_name }}
                    </option>
                @endforeach
            </select>
            @error('country')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-4">
            <label for="city" class="form-label fw-semibold mb-2">City</label>
            @php
                $currentCity = $profile ? $profile->city : ($sessionData[2]['city'] ?? old('city'));
            @endphp
            <input type="text" name="city" id="city" class="form-control form-control-lg input-layout"
                   value="{{ $currentCity }}" placeholder="e.g., New York">
        </div>

        <div class="form-group mb-5">
            <label for="work_permit_status" class="form-label fw-semibold mb-2">
                Work Permit/Citizenship Status <span class="text-danger">*</span>
            </label>
            @php
                $currentPermitStatus = $profile ? $profile->work_permit_status : ($sessionData[2]['work_permit_status'] ?? old('work_permit_status'));
            @endphp
            <select name="work_permit_status" id="work_permit_status" class="form-control form-control-lg input-layout @error('work_permit_status') is-invalid @enderror" required>
                <option value="">Select status...</option>
                <option value="yes" {{ $currentPermitStatus === 'yes' ? 'selected' : '' }}>
                    Yes, I have work authorization
                </option>
                <option value="no-sponsorship" {{ $currentPermitStatus === 'no-sponsorship' ? 'selected' : '' }}>
                    No, but not looking for sponsorship
                </option>
                <option value="no-remote-only" {{ $currentPermitStatus === 'no-remote-only' ? 'selected' : '' }}>
                    No, remote positions only
                </option>
            </select>
            @error('work_permit_status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center pt-4 border-top">
            <span class="progress-text">
                Progress: <strong>20%</strong>
            </span>
            <div>
                <a href="{{ route('placement.wizard.step', ['step' => 1]) }}" class="btn btn-outline-secondary me-2 waves-effect waves-light">
                    Back
                </a>
                <button type="button" class="btn btn-primary waves-effect waves-light" id="nextBtn">Next</button>
            </div>
        </div>
    </form>
</div>

<style>
    /* Country Dropdown Styling */
    #country.form-control {
        padding: 0.5rem 0.75rem !important;
        font-size: 0.9rem !important;
        height: auto !important;
    }

    #country.form-control:focus {
        padding: 0.5rem 0.75rem !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .completion-modal-content {
            border-radius: 12px;
        }

        .completion-title {
            font-size: 1.5rem;
        }

        .completion-icon {
            width: 100px;
            height: 100px;
        }

        .completion-icon svg {
            width: 50px;
            height: 50px;
        }

        .btn-completion {
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
        }
    }
</style>

<style>
    /* Enhanced Typography and Spacing */
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

    /* Form Labels */
    .form-label {
        font-size: 0.95rem;
        color: #2d3748;
        letter-spacing: 0.01em;
    }

    .form-label .text-danger {
        font-weight: 600;
    }

    /* Form Controls */
    .form-control-lg {
        padding: 0.75rem 1rem;
        font-size: 1rem;
        border-radius: 0.5rem;
        border: 1.5px solid #cbd5e0;
        transition: all 0.2s ease;
    }

    .form-control-lg:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-control-lg::placeholder {
        color: #a0aec0;
    }

    /* Enhanced Dropdown Styling */
    select.form-control-lg {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        padding-right: 2.5rem;
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%234b5056' d='M1 1l5 5 5-5' stroke='%234b5056' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 12px 8px;
        padding-left: 1rem;
    }

    select.form-control-lg:hover:not(:disabled) {
        border-color: #3b82f6;
        background-color: #ffffff;
    }

    select.form-control-lg:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        outline: none;
    }

    select.form-control-lg option {
        padding: 0.5rem 1rem;
        color: #4b5056;
        background-color: #ffffff;
    }

    select.form-control-lg option:hover {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    select.form-control-lg option:checked {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    select.input-layout {
        background-color: #d8dadc !important;
        color: #4b5056 !important;
    }

    select.input-layout:hover:not(:disabled) {
        background-color: #e0e2e4 !important;
    }

    /* Progress Text */
    .progress-text {
        font-size: 0.95rem;
        color: #4a5568;
    }

    .progress-text strong {
        color: #2d3748;
        font-weight: 600;
    }

    /* Buttons */
    .btn-outline-secondary {
        border-width: 1.5px;
    }

    .btn-outline-secondary:hover {
        background-color: #f7fafc;
        transform: translateY(-1px);
    }

    .input-layout {
        color: #4b5056 !important;
        background-color: #d8dadc !important;
    }

    /* Border Top Separator */
    .border-top {
        border-color: #e2e8f0 !important;
        margin-top: 1rem;
    }

    /* Form Group Spacing */
    .form-group.mb-4 {
        margin-bottom: 2rem !important;
    }

    .form-group.mb-5 {
        margin-bottom: 2.5rem !important;
    }

    .mb-5 {
        margin-bottom: 2.5rem !important;
    }

    /* Invalid Feedback */
    .invalid-feedback {
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .step-title {
            font-size: 1.5rem;
        }

        .wizard-step-content {
            padding: 1rem 0;
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
    document.addEventListener('DOMContentLoaded', function() {
        const nextBtn = document.getElementById('nextBtn');
        const form = document.getElementById('step2Form');

        // Handle Next button click
        nextBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Validate form
            if (form.checkValidity() === false) {
                e.stopPropagation();
                form.classList.add('was-validated');
            } else {
                // Form is valid, submit it
                form.submit();
            }
        });
    });
</script>

@endsection
