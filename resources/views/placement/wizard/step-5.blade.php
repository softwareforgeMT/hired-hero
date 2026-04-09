@extends('placement.wizard.layout')

@section('step-content')
<div class="wizard-step-content">
    <div class="mb-5">
        <h2 class="step-title mb-3">What Languages Do You Speak?</h2>
        <p class="step-description">Help us find positions that match your language skills. (Optional)</p>
    </div>

    <form id="step5Form" action="{{ route('placement.wizard.submit', ['step' => 5]) }}" method="POST">
        @csrf

        <div class="form-group mb-5">
            <label class="form-label fw-semibold mb-3">
                Job Posting Language
            </label>
            <div class="language-grid">
                @php
                    $languages = [
                        'English' => 'English',
                        'French' => 'François',
                        'Spanish' => 'Español'
                    ];
                    $profileLanguages = $profile ? ($profile->job_languages ?? []) : ($sessionData[5]['job_languages'] ?? []);
                    $currentLanguages = old('job_languages', $profileLanguages);
                @endphp

                @foreach ($languages as $value => $label)
                    <div class="language-card">
                        <input type="checkbox" name="job_languages[]" id="lang_{{ strtolower($value) }}" 
                               value="{{ $value }}" class="language-checkbox"
                               {{ in_array($value, $currentLanguages) ? 'checked' : '' }}>
                        <label for="lang_{{ strtolower($value) }}" class="language-label">
                            {{ $label }}
                        </label>
                    </div>
                @endforeach

                <div class="language-card">
                    <input type="checkbox" name="job_languages[]" id="lang_any" 
                           value="Any" class="language-checkbox"
                           {{ in_array('Any', $currentLanguages) ? 'checked' : '' }}>
                    <label for="lang_any" class="language-label">
                        Any Language
                    </label>
                </div>
            </div>
            <small class="text-muted d-block mt-2">
                <i class="ri-information-line"></i>
                Leave unchecked to see all positions regardless of language requirements.
            </small>
            @error('job_languages')
                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center pt-4 border-top">
            <span class="progress-text">
                <!-- Progress: <strong>50%</strong> -->
            </span>
            <div>
                <a href="{{ route('placement.wizard.step', ['step' => 4]) }}" class="btn btn-outline-secondary me-2 waves-effect waves-light">
                    Back
                </a>
                <button type="button" class="btn btn-primary waves-effect waves-light" id="nextBtn">Next</button>
            </div>
        </div>
    </form>
</div>

<!-- Authentication Modal -->
@if (!Auth::check())
<div class="modal fade" id="authModal" tabindex="-1" role="dialog" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content auth-modal-content">
            <div class="modal-body text-center p-5">
                <div class="mb-4 auth-icon-wrapper">
                    <div class="auth-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                        </svg>
                    </div>
                </div>
                
                <h3 class="modal-title mb-3 auth-title" id="authModalLabel">
                    Ready to find your perfect job?
                </h3>
                
                <p class="auth-subtitle mb-5">
                    Sign in or create an account to continue building your profile and unlock job matches.
                </p>
                
                <div class="auth-buttons d-flex flex-column gap-3">
                    <a href="{{ route('user.login') }}?redirect=placement.wizard.step&step=6" class="btn btn-primary btn-lg waves-effect waves-light auth-btn">
                        <i class="ri-login-box-line me-2"></i>
                        Sign In
                    </a>
                    <a href="{{ route('user.register') }}?redirect=placement.wizard.step&step=6" class="btn btn-outline-primary btn-lg waves-effect waves-light auth-btn">
                        <i class="ri-user-add-line me-2"></i>
                        Create Account
                    </a>
                </div>
                
                <p class="auth-footer mt-4">
                    <button type="button" class="btn-link-plain" data-bs-dismiss="modal">
                        I'll do this later
                    </button>
                </p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- 50% Completion Modal -->
<div class="modal fade" id="completionModal" tabindex="-1" role="dialog" aria-labelledby="completionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content completion-modal-content">
            <div class="modal-body text-center p-5">
                <div class="mb-4 completion-icon-wrapper">
                    <div class="completion-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                
                <h3 class="modal-title mb-3 completion-title" id="completionModalLabel">
                    You're getting closer to your dream job!
                </h3>
                
                <p class="completion-subtitle mb-4">
                    You've completed 50% of the profile setup. Keep going!
                </p>
                
                <div class="progress completion-progress" style="height: 10px; border-radius: 10px; margin: 2rem 0; overflow: hidden; background: rgba(255, 255, 255, 0.2);">
                    <div class="progress-bar completion-progress-bar" role="progressbar" style="width: 50%; background: linear-gradient(90deg, #00D4A8 0%, #00A3FF 100%); border-radius: 10px;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                
                <p class="completion-percentage">
                    <strong>50%</strong> <span>Complete</span>
                </p>
                
                <button type="submit" form="step5Form" class="btn btn-completion btn-lg waves-effect waves-light">
                    <span>Continue to Next Step</span>
                    <svg style="width: 18px; height: 18px; margin-left: 8px; display: inline-block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
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

    .language-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .language-card {
        position: relative;
    }

    .language-checkbox {
        display: none;
    }

    .language-label {
        display: block;
        padding: 1rem;
        border: 2px solid #cbd5e0;
        border-radius: 0.5rem;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        font-weight: 500;
        margin: 0;
        color: #2d3748;
    }

    .language-checkbox:checked + .language-label {
        border-color: #3b82f6;
        background: #eff6ff;
        color: #3b82f6;
    }

    .language-label:hover {
        border-color: #3b82f6;
        background-color: #f0f4ff;
    }

    .form-label {
        font-size: 0.95rem;
        color: #2d3748;
        letter-spacing: 0.01em;
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

        .language-grid {
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

<style>
    /* Modal Content Styling */
    .completion-modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        background: linear-gradient(135deg, #1a1f2e 0%, #2c3e50 100%);
        overflow: hidden;
        position: relative;
    }

    .completion-modal-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #00D4A8 0%, #00A3FF 50%, #fbbf24 100%);
    }

    .modal-body {
        background: linear-gradient(135deg, #1a1f2e 0%, #2c3e50 100%);
    }

    /* Icon Styling */
    .completion-icon-wrapper {
        display: flex;
        justify-content: center;
        position: relative;
        z-index: 2;
    }

    .completion-icon {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 10px 40px rgba(251, 191, 36, 0.4);
        animation: pulseIcon 2s ease-in-out infinite;
        position: relative;
    }

    .completion-icon::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 100%);
        animation: shimmer 2s ease-in-out infinite;
    }

    .completion-icon svg {
        width: 60px;
        height: 60px;
        z-index: 1;
        animation: checkSlide 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    /* Title Styling */
    .completion-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #ffffff;
        line-height: 1.4;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Subtitle Styling */
    .completion-subtitle {
        font-size: 1.1rem;
        color: #cbd5e0;
        line-height: 1.6;
        font-weight: 500;
    }

    /* Progress Bar Styling */
    .completion-progress {
        position: relative;
        background: rgba(79, 172, 254, 0.1) !important;
        border-radius: 10px;
        overflow: hidden;
    }

    .completion-progress-bar {
        background: linear-gradient(90deg, #00D4A8 0%, #00A3FF 100%);
        box-shadow: 0 0 20px rgba(0, 212, 168, 0.5);
    }

    /* Percentage Styling */
    .completion-percentage {
        font-size: 1rem;
        color: #e0e7ff;
        margin-bottom: 2rem;
    }

    .completion-percentage strong {
        font-size: 1.8rem;
        color: #00D4A8;
        font-weight: 800;
        display: inline-block;
        margin-right: 8px;
    }

    .completion-percentage span {
        color: #cbd5e0;
        font-weight: 500;
    }

    /* Button Styling */
    .btn-completion {
        width: 100%;
        padding: 1rem 2rem;
        font-weight: 700;
        font-size: 1.05rem;
        letter-spacing: 0.5px;
        background: linear-gradient(135deg, #00D4A8 0%, #00A3FF 100%);
        border: none;
        color: white;
        border-radius: 10px;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 10px 30px rgba(0, 212, 168, 0.3);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .btn-completion::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
    }

    .btn-completion:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(0, 212, 168, 0.5);
        background: linear-gradient(135deg, #00A3FF 0%, #00D4A8 100%);
    }

    .btn-completion:hover::before {
        left: 100%;
    }

    .btn-completion:active {
        transform: translateY(-1px);
    }

    /* Animations */
    @keyframes pulseIcon {
        0% {
            transform: scale(1);
            box-shadow: 0 10px 40px rgba(251, 191, 36, 0.4);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 10px 50px rgba(251, 191, 36, 0.6);
        }
        100% {
            transform: scale(1);
            box-shadow: 0 10px 40px rgba(251, 191, 36, 0.4);
        }
    }

    @keyframes checkSlide {
        0% {
            transform: scale(0) rotate(-45deg);
            opacity: 0;
        }
        50% {
            transform: scale(1.1) rotate(10deg);
        }
        100% {
            transform: scale(1) rotate(0);
            opacity: 1;
        }
    }

    @keyframes shimmer {
        0%, 100% {
            opacity: 0.5;
        }
        50% {
            opacity: 0;
        }
    }

    /* Modal Backdrop */
    .modal-backdrop.fade {
        background-color: rgba(0, 0, 0, 0.7);
    }

    .modal.fade .modal-dialog {
        animation: slideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes slideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Authentication Modal Styles */
    .auth-modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        background: linear-gradient(135deg, #1a1f2e 0%, #2c3e50 100%);
        overflow: hidden;
        position: relative;
    }

    .auth-modal-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6 0%, #0ea5e9 50%, #06b6d4 100%);
    }

    .auth-icon-wrapper {
        display: flex;
        justify-content: center;
        position: relative;
        z-index: 2;
    }

    .auth-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #3b82f6 0%, #0ea5e9 50%, #06b6d4 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 10px 40px rgba(59, 130, 246, 0.4);
        animation: pulseIcon 2s ease-in-out infinite;
        position: relative;
    }

    .auth-icon::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 100%);
        animation: shimmer 2s ease-in-out infinite;
    }

    .auth-icon svg {
        width: 50px;
        height: 50px;
        z-index: 1;
        animation: checkSlide 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .auth-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: #ffffff;
        line-height: 1.4;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .auth-subtitle {
        font-size: 1rem;
        color: #cbd5e0;
        line-height: 1.6;
        font-weight: 500;
    }

    .auth-buttons {
        margin: 0;
    }

    .auth-btn {
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-primary.auth-btn {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        color: white;
    }

    .btn-primary.auth-btn:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
    }

    .btn-outline-primary.auth-btn {
        border: 2px solid #3b82f6;
        color: #3b82f6;
        background: transparent;
    }

    .btn-outline-primary.auth-btn:hover {
        background: rgba(59, 130, 246, 0.1);
        border-color: #2563eb;
        color: #2563eb;
        transform: translateY(-2px);
    }

    .auth-footer {
        margin-top: 1.5rem;
    }

    .btn-link-plain {
        background: none;
        border: none;
        color: #cbd5e0;
        cursor: pointer;
        font-size: 0.95rem;
        font-weight: 500;
        transition: color 0.2s ease;
        padding: 0;
    }

    .btn-link-plain:hover {
        color: #f0f4f8;
        text-decoration: underline;
    }

    .modal-backdrop.fade {
        background-color: rgba(0, 0, 0, 0.7);
    }

    .modal.fade .modal-dialog {
        animation: slideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nextBtn = document.getElementById('nextBtn');
        const form = document.getElementById('step5Form');
        const modal = document.getElementById('completionModal');
        const authModal = document.getElementById('authModal');
        const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
        let formIsValid = false;

        // Handle Next button click
        nextBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Validate form
            if (form.checkValidity() === false) {
                e.stopPropagation();
                form.classList.add('was-validated');
            } else {
                // Form is valid
                if (isAuthenticated) {
                    // Authenticated user - show completion modal and submit
                    formIsValid = true;
                    const bsModal = new bootstrap.Modal(modal, {
                        backdrop: true,
                        keyboard: true
                    });
                    bsModal.show();
                } else {
                    // Guest user - show authentication modal
                    const bsAuthModal = new bootstrap.Modal(authModal, {
                        backdrop: 'static',
                        keyboard: false
                    });
                    bsAuthModal.show();
                }
            }
        });
    });
</script>
@endsection
