@extends('placement.wizard.layout')

@section('step-content')
<div class="wizard-step-content">
    <div class="mb-5">
        <h2 class="step-title mb-3">Tell us about your professional background</h2>
        <p class="step-description">Help us understand your experience and interests.</p>
    </div>

    <form action="{{ route('placement.wizard.submit', ['step' => 3]) }}" method="POST">
        @csrf

        <div class="form-group mb-5">
            <label class="form-label fw-semibold mb-3">
                Industries of Interest <span class="text-danger">*</span>
            </label>
            <p class="form-text text-muted mb-3">This helps us tailor your resume better</p>
            <div class="industry-grid">
                @php
                    $industriesOptions = [
                        'technology' => 'Technology',
                        'finance' => 'Finance',
                        'healthcare' => 'Healthcare',
                        'retail' => 'Retail',
                        'manufacturing' => 'Manufacturing',
                        'education' => 'Education',
                        'government' => 'Government',
                        'nonprofits' => 'Nonprofits',
                        'hospitality' => 'Hospitality',                    ];
                    $profileIndustries = $profile ? ($profile->industries ?? []) : ($sessionData[3]['industries'] ?? []);
                    $selectedIndustries = old('industries', $profileIndustries);
                @endphp

                @foreach ($industriesOptions as $value => $label)
                    <div class="form-check text-center">
                        <input type="checkbox" name="industries[]" id="industry_{{ $value }}" 
                               value="{{ $value }}" class="form-check-input"
                               {{ in_array($value, $selectedIndustries) ? 'checked' : '' }}>
                        <label class="form-check-label text-center" for="industry_{{ $value }}">
                            {{ $label }}
                        </label>
                    </div>
                @endforeach
            </div>
            @error('industries')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>



        <div class="d-flex justify-content-between align-items-center pt-4 border-top">
            <span class="progress-text">
                Progress: <strong>30%</strong>
            </span>
            <div>
                <a href="{{ route('placement.wizard.step', ['step' => 2]) }}" class="btn btn-outline-secondary me-2 waves-effect waves-light">
                    Back
                </a>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Next</button>
            </div>
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

    /* Industry Grid */
    .industry-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .form-check {
        padding: 0.75rem;
        border: 2px solid #cbd5e0;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        background-color: #f8f9fa;
    }

    .form-check:hover {
        border-color: #3b82f6;
        background: #f0f4ff;
    }

    .form-check-input {
        display: none;
    }

    .form-check-input:checked ~ .form-check-label {
        color: #3b82f6;
        font-weight: 600;
    }

    .form-check-input:checked ~ .form-check-label,
    input[type="checkbox"]:checked ~ .form-check-label {
        color: white;
        font-weight: 600;
    }

    input[type="checkbox"]:checked ~ .form-check-label {
        background-color: inherit;
    }

    .form-check:has(input[type="checkbox"]:checked) {
        border-color: #3b82f6;
        background-color: #3b82f6;
    }

    .form-check:has(input[type="checkbox"]:checked) .form-check-label {
        color: white;
        font-weight: 600;
    }

    .form-check-label {
        margin-bottom: 0;
        cursor: pointer;
        color: #2d3748;
        display: block;
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

        .industry-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }
    }
</style>
@endsection
