@extends('placement.wizard.layout')
@section('step-content')
<div class="wizard-step-content">
    <div class="mb-5">
        <h2 class="step-title mb-3">What type of job are you looking for?</h2>
        <p class="step-description">Let us know your job preferences so we can find the best matches for you.</p>
    </div>

    <form action="{{ route('placement.wizard.submit', ['step' => 1]) }}" method="POST">
        @csrf

        <div class="form-group mb-4">
            <label for="job_type" class="form-label fw-semibold mb-2">
                Job Type <span class="text-danger">*</span>  
            </label>
            <select name="job_type" id="job_type" class="form-control form-control-lg input-layout @error('job_type') is-invalid @enderror" required>
                <option value="">Select job type...</option>
                @php
                    $currentJobType = $profile ? $profile->job_type : ($sessionData[1]['job_type'] ?? old('job_type'));
                @endphp
                <option value="remote" {{ $currentJobType === 'remote' ? 'selected' : '' }}>
                    Remote
                </option>
                <option value="hybrid" {{ $currentJobType === 'hybrid' ? 'selected' : '' }}>
                    Hybrid
                </option>
                <option value="in-person" {{ $currentJobType === 'in-person' ? 'selected' : '' }}>
                    In-Person
                </option>
                <option value="no-preference" {{ $currentJobType === 'no-preference' ? 'selected' : '' }}>
                    No Preference
                </option>
            </select>
            @error('job_type')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="salary_min" class="form-label fw-semibold mb-2">
                        Minimum Salary (Optional)
                    </label>
                    @php
                        $salaryMin = $profile ? $profile->salary_min : ($sessionData[1]['salary_min'] ?? old('salary_min'));
                    @endphp
                    <input type="number" name="salary_min" id="salary_min"
                        class="form-control form-control-lg input-layout @error('salary_min') is-invalid @enderror"
                        value="{{ $salaryMin }}"
                        min="0" step="1000" placeholder="e.g., 50000">
                    @error('salary_min')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="salary_max" class="form-label fw-semibold mb-2">
                        Maximum Salary (Optional)
                    </label>
                    @php
                        $salaryMax = $profile ? $profile->salary_max : ($sessionData[1]['salary_max'] ?? old('salary_max'));
                    @endphp
                    <input type="number" name="salary_max" id="salary_max"
                        class="form-control form-control-lg input-layout @error('salary_max') is-invalid @enderror"
                        value="{{ $salaryMax }}"
                        min="0" step="1000" placeholder="e.g., 80000">
                    @error('salary_max')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center pt-4 border-top">
            <span class="progress-text">
                Progress: <strong>10%</strong>
            </span>
            <button type="submit" class="btn btn-primary waves-effect waves-light">
                Next
            </button>
        </div>
    </form>
</div>

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

    /* Row Gutters */
    .row.g-4 {
        --bs-gutter-x: 1.5rem;
        --bs-gutter-y: 1.5rem;
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
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        font-weight: 500;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
    }

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

    .btn-next {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
    }

    .btn-next:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(59, 130, 246, 0.3);
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

    .mb-5 {
        margin-bottom: 2.5rem !important;
    }

    /* Gap Utility */
    .gap-4 {
        gap: 1.5rem;
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

        .btn-lg {
            width: 100%;
        }

        .progress-text {
            order: -1;
            text-align: center;
        }
    }
</style>
@endsection