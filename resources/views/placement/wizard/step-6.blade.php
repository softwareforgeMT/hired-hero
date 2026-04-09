@extends('placement.wizard.layout')

@section('step-content')
<div class="wizard-step-content">
    <div class="mb-5">
        <h2 class="step-title mb-3">Submit Your Resume</h2>
        <p class="step-description">Choose your preferred method to submit your resume</p>
    </div>

    @php
    $existingResumes = \App\Models\Resume::where('user_id', Auth::id())->latest()->get();
    $hasExistingResumes = $existingResumes->count() > 0;

    // Check user's active subscription from UserSubscription table for resume builder access
    $user = Auth::user();
    $activeSubscription = $user->getActiveSubscription();

    // Determine resume builder eligibility and display states
    $canUseResumeBuilder = false;
    $showFreeTrialBadge = false;
    $showUpgradeBadge = false;

    if (!$activeSubscription) {
    // User has no subscription - cannot use resume builder
    $showUpgradeBadge = true;
    } elseif ($activeSubscription->plan->slug === 'free-plan') {
    // User is on free plan - check if within 14-day trial
    $daysSinceCreated = $user->created_at->diffInDays(now());
    if ($daysSinceCreated < 14) {
        // Within trial period - can use resume builder
        $canUseResumeBuilder=true;
        $showFreeTrialBadge=true;
        } else {
        // Trial expired - cannot use resume builder
        $showUpgradeBadge=true;
        }
        } else {
        // User has paid plan - can use resume builder
        $canUseResumeBuilder=true;
        }
        @endphp

        <form action="{{ route('placement.wizard.submit', ['step' => 6]) }}" method="POST" enctype="multipart/form-data" id="step6Form">
        @csrf

        <!-- Email Input -->
        <div class="form-group mb-4">
            <label for="email" class="form-label fw-semibold mb-2">
                Email Address <span class="text-danger">*</span>
            </label>
            <input type="email" name="email" id="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                placeholder="you@example.com" value="{{ old('email', $profile->email ?? '') }}" required>
            <small class="text-muted d-block mt-2">
                <i class="ri-mail-line"></i>
                We'll send your job matches and subscription details here.
            </small>
            @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Resume Selection Tabs -->
        <div class="form-group mb-4">
            <label class="form-label fw-semibold mb-3">
                Choose Resume Option <span class="text-danger">*</span>
            </label>

            <!-- Resume Option Tabs -->
            <div class="resume-options-tabs mb-4">
                <label class="resume-option-card">
                    <input type="radio" name="resume_option" value="existing" class="d-none resume-option-input" onchange="switchResumeOption('existing')">
                    <div class="resume-option-body">
                        <div class="option-icon">
                            <i class="ri-bookmark-line"></i>
                        </div>
                        <div class="option-text">
                            <h5><i class="ri-check-line"></i> Use Existing Resume in Portal</h5>
                            <p>Select from your previously built resumes</p>
                        </div>
                    </div>
                </label>

                <label class="resume-option-card">
                    <input type="radio" name="resume_option" value="upload" class="d-none resume-option-input" onchange="switchResumeOption('upload')" @if(!$hasExistingResumes) checked @endif>
                    <div class="resume-option-body">
                        <div class="option-icon">
                            <i class="ri-upload-cloud-line"></i>
                        </div>
                        <div class="option-text">
                            <h5>Upload Resume File</h5>
                            <p>Upload PDF, DOC, or DOCX from your computer</p>
                        </div>
                    </div>
                </label>

                <label class="resume-option-card">
                    <input type="radio" name="resume_option" value="builder" class="d-none resume-option-input" onchange="switchResumeOption('builder')">
                    <div class="resume-option-body">
                        <div class="option-icon">
                            <i class="ri-flashlight-line"></i>
                        </div>
                        <div class="option-text">
                            <h5>
                                @if($canUseResumeBuilder)
                                Use Resume Builder
                                @else
                                Upgrade Resume Builder
                                @endif
                                @if($showFreeTrialBadge)
                                <span class="badge bg-success text-white ms-2">Free Trial</span>
                                @endif
                            </h5>
                            <p>
                                @if($canUseResumeBuilder)
                                Create a professional resume with AI
                                @else
                                <span class="badge bg-warning text-dark">Upgrade Plan</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </label>
            </div>

            <!-- Option 1: Existing Resumes -->
            <div class="resume-option-content" id="existing-content" style="display: none;">
                <div class="form-group mb-4">
                    <label class="form-label fw-semibold mb-3">
                        Select a Resume <span class="text-danger">*</span>
                    </label>
                    @if($hasExistingResumes)
                    <div class="existing-resumes-list" id="resumesList">
                        @foreach($existingResumes as $resume)
                        <label class="existing-resume-item" data-resume-id="{{ $resume->id }}">
                            <input type="radio" name="existing_resume_id" value="{{ $resume->id }}" class="d-none resume-radio" onchange="selectResume('{{ $resume->id }}', '{{ route('resume-builder.download', ['resume' => $resume->id]) }}')">
                            <div class="resume-item-wrapper">
                                <div class="resume-item-content">
                                    <div class="resume-item-left">
                                        <div class="resume-item-checkbox">
                                            <i class="ri-checkbox-blank-circle-line"></i>
                                        </div>
                                        <div class="resume-item-info">
                                            <h6 class="resume-item-title">{{ $resume->title ?? 'Untitled Resume' }}</h6>
                                            <div class="resume-item-meta-row">
                                                <span class="resume-item-template">{{ ucfirst($resume->template_name ?? 'Professional') }}</span>
                                                <span class="resume-item-date">{{ $resume->created_at->format('M d, Y') }}</span>
                                            </div>
                                            <p class="resume-item-person">
                                                <i class="ri-user-line"></i>
                                                {{ $resume->data['personal_info']['full_name'] ?? $resume->data['full_name'] ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="resume-item-actions">
                                        <a href="{{ route('resume-builder.view', ['resume' => $resume->id]) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Preview">
                                            <i class="ri-eye-line"></i> Preview
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-resume-btn" data-resume-id="{{ $resume->id }}" title="Delete permanently">
                                            <i class="ri-delete-bin-line"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @else
                    <div class="alert alert-info" role="alert">
                        <div class="d-flex align-items-start gap-3">
                            <i class="ri-information-line text-info" style="font-size: 1.5rem; flex-shrink: 0;"></i>
                            <div>
                                <strong>No resumes created yet</strong>
                                <p class="mb-2 mt-1">Start building professional resumes using our AI-powered Resume Builder. You'll be able to select them here after creation.</p>
                                <a href="{{ route('resume-builder.pricing') }}" class="btn btn-sm btn-info">
                                    <i class="ri-arrow-right-line"></i> View Resume Builder Plans
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Option 2: Upload File -->
            <div class="resume-option-content" id="upload-content" @if(!$hasExistingResumes) style="display: block;" @else style="display: none;" @endif>
                <div class="form-group mb-4">
                    <label class="form-label fw-semibold mb-3">
                        Upload Resume <span class="text-danger">*</span>
                    </label>
                    <div class="resume-upload-area @error('resume') is-invalid @enderror" id="resumeUploadArea">
                        <div class="upload-content">
                            <i class="ri-file-pdf-line upload-icon"></i>
                            <h5 class="mt-3 mb-2">Drag & drop your resume here</h5>
                            <p class="text-muted mb-3">or click to browse (PDF, DOC, DOCX)</p>
                            <input type="file" name="resume" id="resume" class="d-none" accept=".pdf,.doc,.docx">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('resume').click()">
                                Choose File
                            </button>
                        </div>
                        <div class="upload-preview d-none">
                            <div class="preview-item">
                                <i class="ri-file-pdf-fill"></i>
                                <span id="fileName">document.pdf</span>
                                <button type="button" class="btn-close-sm" onclick="clearResume()"></button>
                            </div>
                        </div>
                    </div>
                    @error('resume')
                    <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Option 3: Resume Builder -->
            <div class="resume-option-content" id="builder-content" style="display: none;">
                @if($canUseResumeBuilder)
                <div class="builder-active-card">
                    <div class="builder-card-header">
                        <i class="ri-flashlight-fill"></i>
                        <h5>Resume Builder Active @if($showFreeTrialBadge)<span class="badge bg-success text-white ms-2">Free Trial</span>@endif</h5>
                    </div>
                    <p>@if($showFreeTrialBadge)You have access to Resume Builder for 14 days. Try it out and create a professional resume!@else You have an active subscription with Resume Builder access. Click below to create or continue building your resume.@endif</p>
                    <a href="{{ route('resume-builder.form') }}" class="btn btn-primary w-100 mt-3">
                        <i class="ri-file-text-line"></i>
                        Open Resume Builder
                    </a>
                    <p class="text-muted mt-3 text-center mb-0">
                        After creating your resume, you'll be able to submit it directly from the builder to continue to Step 7.
                    </p>
                </div>
                @else
                <div class="builder-upsell-card">
                    <div class="upsell-header">
                        <i class="ri-flashlight-line"></i>
                        <h5>Unlock AI Resume Builder</h5>
                    </div>
                    <p class="upsell-description">Upgrade your subscription to access our AI-powered resume builder and create a professional, ATS-friendly resume in minutes.</p>
                    <ul class="upsell-features">
                        <li><i class="ri-check-line"></i> AI-powered suggestions</li>
                        <li><i class="ri-check-line"></i> ATS-optimized formatting</li>
                        <li><i class="ri-check-line"></i> Real-time preview</li>
                        <li><i class="ri-check-line"></i> 5 Professional templates</li>
                        <li><i class="ri-check-line"></i> Download as PDF</li>
                    </ul>
                    <div class="mt-4">
                        <a href="{{ route('resume-builder.pricing') }}" class="btn btn-primary w-100">
                            <i class="ri-arrow-right-line"></i>
                            Upgrade Plan
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Upsell Cards Row -->
        <div class="row g-3 mb-5" id="upsellSection" style="display: none;">
            <!-- Resume Builder Upsell -->
            <div class="col-lg-6">
                <div class="upsell-card">
                    <div class="upsell-header">
                        <div class="upsell-badge">Pro Feature</div>
                        <i class="ri-star-fill"></i>
                    </div>
                    <h5 class="upsell-title">Resume Builder</h5>
                    <p class="upsell-description">
                        Create an ATS-friendly, professionally designed resume with our AI-powered builder.
                    </p>
                    <ul class="upsell-features">
                        <li><i class="ri-check-line"></i> AI-powered suggestions</li>
                        <li><i class="ri-check-line"></i> ATS-optimized formatting</li>
                        <li><i class="ri-check-line"></i> Real-time preview</li>
                        <li><i class="ri-check-line"></i> 5 Professional templates</li>
                    </ul>
                    <button type="button" class="btn btn-outline-primary w-100 mt-3" data-upsell="resume-builder">
                        Upgrade Plan
                    </button>
                </div>
            </div>

            <!-- Cover Letter Upsell -->
            <div class="col-lg-6">
                <div class="upsell-card">
                    <div class="upsell-header">
                        <div class="upsell-badge">Pro Feature</div>
                        <i class="ri-sparkles-line"></i>
                    </div>
                    <h5 class="upsell-title">AI Cover Letter</h5>
                    <p class="upsell-description">
                        Generate customized, compelling cover letters for each job application.
                    </p>
                    <ul class="upsell-features">
                        <li><i class="ri-check-line"></i> AI-generated content</li>
                        <li><i class="ri-check-line"></i> Customize per job</li>
                        <li><i class="ri-check-line"></i> Professional tone</li>
                        <li><i class="ri-check-line"></i> Increase response rate</li>
                    </ul>
                    <button type="button" class="btn btn-outline-primary w-100 mt-3" data-upsell="cover-letter">
                        Upgrade Plan
                    </button>
                </div>
            </div>
        </div>

        <!-- Terms and Conditions Section -->
        <div class="form-group mb-4 mt-4 pt-3 border-top">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="termsCheck" name="terms_agreed" required>
                <label class="form-check-label" for="termsCheck">
                    I agree to save my email for marketing purposes and receiving job opportunities.
                    <a href="/terms-and-conditions" target="_blank" class="text-primary">
                        <strong>Terms & Conditions</strong>
                    </a>
                    <span class="text-danger">*</span>
                </label>
                <div class="invalid-feedback d-block" id="termsError" style="display: none;">
                    Please agree to the terms and conditions to continue
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center pt-4 border-top">
            <span class="progress-text">
                Progress: <strong>75%</strong>
            </span>
            <div>
                <a href="{{ route('placement.wizard.step', ['step' => 5]) }}" class="btn btn-outline-secondary me-2 waves-effect waves-light">
                    Back
                </a>
                <button type="submit" class="btn btn-primary waves-effect waves-light" id="submitBtn">Next</button>
            </div>
        </div>
        </form>
</div>

<!-- Modal for Upsell -->
<div class="modal fade" id="upsellModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">Upgrade to HiredHero Pro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="ri-flashlight-line text-warning" style="font-size: 3rem;"></i>
                <h5 class="mt-3 mb-2" id="upsellTitle">Resume Builder</h5>
                <p class="text-muted mb-4" id="upsellMessage">
                    Create a professional, ATS-friendly resume with our AI-powered builder.
                </p>
                <div class="d-flex justify-content-center gap-2 mb-4">
                    <button class="btn btn-lg btn-primary px-4" data-plan="weekly">
                        $4.99/week
                    </button>
                    <button class="btn btn-lg btn-outline-primary px-4" data-plan="monthly">
                        $19/month
                    </button>
                </div>
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
    }

    .form-control-lg {
        padding: 0.75rem 1rem;
        font-size: 1rem;
        border-radius: 0.5rem;
        border: 1.5px solid #cbd5e0;
    }

    .form-control-lg:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Resume Options Tabs */
    .resume-options-tabs {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .resume-option-card {
        position: relative;
        cursor: pointer;
    }

    .resume-option-card input[type="radio"]:checked+.resume-option-body {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        background: #eff6ff;
    }

    .resume-option-body {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 1.25rem;
        padding: 2rem 1.5rem;
        border: 2px solid #e5e7eb;
        border-radius: 0.75rem;
        background: #f9fafb;
        transition: all 0.3s ease;
        min-height: 130px;
        flex-wrap: nowrap;
    }

    .resume-option-card:hover .resume-option-body {
        border-color: #3b82f6;
        background: #eff6ff;
        transform: translateY(-2px);
    }

    .option-icon {
        font-size: 2.5rem;
        color: #3b82f6;
        flex-shrink: 0;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(59, 130, 246, 0.1);
        border-radius: 0.75rem;
    }

    .option-text {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 100%;
    }

    .option-text h5 {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        line-height: 1.3;
    }

    .option-text h5 i {
        font-size: 0.95rem;
    }

    .option-text p {
        font-size: 0.875rem;
        color: #6b7280;
        margin: 0;
        line-height: 1.4;
    }

    /* Existing Resumes List */
    .existing-resumes-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .existing-resume-item {
        position: relative;
        cursor: pointer;
    }

    .existing-resume-item input[type="radio"]:checked~.resume-item-wrapper,
    .existing-resume-item.active .resume-item-wrapper {
        border: 2px solid #3b82f6;
        background: #eff6ff;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .existing-resume-item input[type="radio"]:checked~.resume-item-wrapper .resume-item-checkbox i,
    .existing-resume-item.active .resume-item-wrapper .resume-item-checkbox i {
        display: none;
    }

    .existing-resume-item input[type="radio"]:checked~.resume-item-wrapper .resume-item-checkbox::after,
    .existing-resume-item.active .resume-item-wrapper .resume-item-checkbox::after {
        content: '\eaa0';
        font-family: 'Remix Icon';
        font-size: 1.25rem;
        color: #3b82f6;
        font-weight: 700;
    }

    .resume-item-wrapper {
        background: #f9fafb;
        border: 2px solid #e5e7eb;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
    }

    .existing-resume-item:hover .resume-item-wrapper {
        border-color: #cbd5e0;
        background: #f0f4f8;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .resume-item-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 1.5rem;
        gap: 1rem;
    }

    .resume-item-left {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        flex: 1;
    }

    .resume-item-checkbox {
        flex-shrink: 0;
        width: 24px;
        height: 24px;
        border: 2px solid #d1d5db;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 2px;
        transition: all 0.3s ease;
    }

    .existing-resume-item:hover .resume-item-checkbox {
        border-color: #3b82f6;
    }

    .resume-item-checkbox i {
        font-size: 1rem;
        color: #9ca3af;
    }

    .resume-item-info {
        flex: 1;
    }

    .resume-item-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 0.5rem 0;
        line-height: 1.4;
    }

    .resume-item-meta-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        flex-wrap: wrap;
    }

    .resume-item-template {
        display: inline-block;
        background: #3b82f6;
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .resume-item-date {
        display: inline-block;
        font-size: 0.85rem;
        color: #6b7280;
    }

    .resume-item-person {
        font-size: 0.9rem;
        color: #4b5563;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .resume-item-person i {
        color: #3b82f6;
        font-size: 1rem;
    }

    .resume-item-actions {
        display: flex;
        gap: 0.75rem;
        flex-shrink: 0;
    }

    .resume-item-actions .btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
        white-space: nowrap;
    }

    .delete-resume-btn {
        border-color: #fecaca !important;
        color: #dc2626 !important;
    }

    .delete-resume-btn:hover {
        background: #fee2e2 !important;
        border-color: #ef4444 !important;
    }

    .delete-resume-btn.deleting {
        opacity: 0.7;
        pointer-events: none;
    }

    /* Success/Error Messages */
    .resume-delete-success {
        animation: slideOut 3s ease forwards;
    }

    @keyframes slideOut {
        0% {
            opacity: 1;
            transform: translateX(0);
        }

        80% {
            opacity: 1;
            transform: translateX(0);
        }

        100% {
            opacity: 0;
            transform: translateX(-100%);
        }
    }

    /* Resume Upload Area */
    .resume-upload-area {
        border: 2px dashed #cbd5e0;
        border-radius: 0.75rem;
        padding: 3rem 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: #f8f9fa;
    }

    .resume-upload-area:hover {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }

    .resume-upload-area.drag-over {
        border-color: #3b82f6;
        background-color: #eff6ff;
        transform: scale(1.01);
    }

    .upload-icon {
        font-size: 3rem;
        color: #9ca3af;
        display: block;
    }

    .resume-upload-area h5 {
        color: #2d3748;
        font-weight: 600;
    }

    .upload-preview {
        padding: 1rem;
    }

    .preview-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #f3f4f6;
        border-radius: 0.5rem;
    }

    .preview-item i {
        font-size: 1.75rem;
        color: #ec4c4c;
    }

    .btn-close-sm {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.25rem;
        color: #9ca3af;
        margin-left: auto;
    }

    .btn-close-sm:hover {
        color: #6b7280;
    }

    /* Resume Builder Options */
    .builder-active-card {
        border: 1.5px solid #10b981;
        border-radius: 0.75rem;
        padding: 2rem;
        background: linear-gradient(135deg, #f0fdf4 0%, #f0fdf4 100%);
    }

    .builder-card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .builder-card-header i {
        font-size: 1.5rem;
        color: #10b981;
    }

    .builder-card-header h5 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #047857;
        margin: 0;
    }

    .builder-upsell-card {
        border: 1.5px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 2rem;
        background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
    }

    .upsell-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .upsell-header i {
        font-size: 2rem;
        color: #fbbf24;
    }

    .upsell-header h5 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0;
    }

    .upsell-description {
        color: #6b7280;
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: 1.5rem;
    }

    .upsell-features {
        list-style: none;
        padding: 0;
        margin: 0 0 1.5rem 0;
    }

    .upsell-features li {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #4b5563;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .upsell-features i {
        color: #10b981;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .pricing-options {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .pricing-options .btn {
        flex-grow: 1;
        padding: 1rem 1.5rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        min-height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 0.25rem;
    }

    .pricing-options .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        color: white;
    }

    .pricing-options .btn-primary:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
        transform: translateY(-2px);
    }

    .pricing-options .btn-outline-primary {
        border: 2px solid #3b82f6;
        color: #3b82f6;
        background: white;
        font-weight: 600;
    }

    .pricing-options .btn-outline-primary:hover {
        background: #eff6ff;
        border-color: #2563eb;
        color: #2563eb;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        transform: translateY(-2px);
    }

    .pricing-options .period {
        display: block;
        font-size: 0.8rem;
        color: inherit;
        font-weight: 400;
        opacity: 0.85;
    }

    /* Card Styling */
    .upsell-card {
        border: 1.5px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 2rem;
        background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
    }

    .upsell-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.1);
        transform: translateY(-4px);
    }

    .upsell-badge {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .upsell-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.75rem;
    }

    .upsell-pricing {
        margin: 2rem 0 1.5rem 0;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f0f4ff 0%, #f9fafb 100%);
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
    }

    .price-badge {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1a1a1a;
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
    }

    .price-badge .period {
        font-size: 0.9rem;
        color: #6b7280;
        font-weight: 500;
    }

    .alt-pricing {
        font-size: 0.9rem;
        color: #6b7280;
        margin-top: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .alt-pricing strong {
        color: #3b82f6;
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

        .resume-options-tabs {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .resume-option-body {
            min-height: 150px;
            padding: 1.5rem 1rem;
        }

        .option-icon {
            width: 45px;
            height: 45px;
            font-size: 1.75rem;
        }

        .option-text h5 {
            font-size: 0.95rem;
            justify-content: flex-start;
        }

        .option-text p {
            font-size: 0.8rem;
        }

        .resume-upload-area {
            padding: 2rem 1rem;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }

        .pricing-options {
            flex-direction: column;
            gap: 0.75rem;
        }

        .pricing-options .btn {
            width: 100%;
            padding: 1rem 1.5rem;
            min-height: 55px;
        }

        .btn-outline-secondary,
        button[type="submit"] {
            width: 100%;
        }

        .progress-text {
            order: -1;
            text-align: center;
        }

        .upsell-card {
            padding: 1.5rem;
        }

        /* Resume item mobile layout */
        .resume-item-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .resume-item-left {
            width: 100%;
            gap: 0.75rem;
        }

        .resume-item-actions {
            width: 100%;
            margin-left: 0;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .resume-item-actions .btn {
            flex: 1;
            min-width: 120px;
        }

        .pricing-options {
            flex-direction: column;
        }

        .resume-item-title {
            font-size: 0.95rem;
        }

        .resume-item-person {
            font-size: 0.85rem;
        }
    }

    @media (max-width: 480px) {
        .resume-item-actions {
            flex-direction: column;
        }

        .resume-item-actions .btn {
            width: 100%;
            min-width: auto;
        }

        .resume-item-checkbox {
            width: 20px;
            height: 20px;
        }

        .resume-item-left {
            gap: 0.5rem;
        }
    }

    /* Terms and Conditions Styling */
    .form-check {
        padding: 1rem;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .form-check:hover {
        background: #f1f3f5;
        border-color: #dee2e6;
    }

    .form-check-input {
        width: 1.25rem;
        height: 1.25rem;
        margin-top: 0.3rem;
        cursor: pointer;
        border: 2px solid #dee2e6;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .form-check-input:checked {
        background-color: var(--bs-primary, #00A3FF);
        border-color: var(--bs-primary, #00A3FF);
    }

    .form-check-input:focus {
        border-color: var(--bs-primary, #00A3FF);
        box-shadow: 0 0 0 0.25rem rgba(0, 163, 255, 0.25);
    }

    .form-check-input.is-invalid {
        border-color: #dc3545;
    }

    .form-check-label {
        cursor: pointer;
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: 0;
    }

    .form-check-label a {
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .form-check-label a:hover {
        text-decoration: underline;
    }

    #termsError {
        font-size: 0.875rem;
        color: #dc3545;
        margin-top: 0.5rem;
        display: block !important;
    }
</style>

<script>
    let selectedResumeId = null;
    let selectedResumeDownloadUrl = null;

    // Email validation helper
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Add email blur validation
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.addEventListener('blur', function() {
                const email = this.value.trim();
                if (email && !isValidEmail(email)) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });

            // Also remove invalid class on focus
            emailInput.addEventListener('focus', function() {
                this.classList.remove('is-invalid');
            });
        }

        // Add terms checkbox handler
        const termsCheckbox = document.getElementById('termsCheck');
        const termsError = document.getElementById('termsError');
        if (termsCheckbox) {
            termsCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    termsError.style.display = 'none';
                    this.classList.remove('is-invalid');
                }
            });
        }
    });

    // Resume option switching
    function switchResumeOption(option) {
        // Hide all content sections
        document.querySelectorAll('.resume-option-content').forEach(el => {
            el.style.display = 'none';
        });

        // Show selected content
        const contentId = option + '-content';
        const content = document.getElementById(contentId);
        if (content) {
            content.style.display = 'block';
        }

        // Reset selected resume when switching away from existing option
        if (option !== 'existing') {
            selectedResumeId = null;
            selectedResumeDownloadUrl = null;
        }

        // Update form validation based on selection
        updateFormValidation(option);
    }

    function updateFormValidation(option) {
        const resumeInput = document.getElementById('resume');
        const existingResumeInputs = document.querySelectorAll('input[name="existing_resume_id"]');

        if (option === 'upload') {
            resumeInput.required = true;
            existingResumeInputs.forEach(input => input.required = false);
        } else if (option === 'existing') {
            resumeInput.required = false;
            existingResumeInputs.forEach(input => input.required = false); // We'll validate with selectedResumeId
        } else if (option === 'builder') {
            resumeInput.required = false;
            existingResumeInputs.forEach(input => input.required = false);
        }
    }

    // Upgrade navigation function
    function goToUpgrade(plan) {
        window.location.href = '{{ route("resume-builder.pricing") }}';
    }

    // Select resume and update active state
    function selectResume(resumeId, downloadUrl) {
        selectedResumeId = resumeId;
        selectedResumeDownloadUrl = downloadUrl;

        // Update active class
        document.querySelectorAll('.existing-resume-item').forEach(item => {
            if (item.getAttribute('data-resume-id') === resumeId) {
                item.classList.add('active');
                // Scroll into view if needed
                item.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            } else {
                item.classList.remove('active');
            }
        });

        console.log('Resume selected:', resumeId, downloadUrl);
    }

    // Delete resume with AJAX
    function deleteResume(resumeId, event) {
        event.preventDefault();
        event.stopPropagation();

        const resumeItem = document.querySelector(`.existing-resume-item[data-resume-id="${resumeId}"]`);
        const deleteButton = event.target.closest('.delete-resume-btn');

        if (!deleteButton) return;

        // Confirm before delete
        if (!confirm('Are you sure you want to permanently delete this resume? This action cannot be undone.')) {
            return;
        }

        // Show loading state
        deleteButton.classList.add('deleting');
        deleteButton.innerHTML = '<i class="ri-loader-4-line"></i> Deleting...';

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
            document.querySelector('input[name="_token"]')?.value || '';

        if (!csrfToken) {
            console.error('CSRF token not found');
            deleteButton.classList.remove('deleting');
            deleteButton.innerHTML = '<i class="ri-delete-bin-line"></i> Delete';
            alert('Security token missing. Please refresh the page and try again.');
            return;
        }

        // Send AJAX request to delete
        fetch(`/resume-builder/${resumeId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Delete response status:', response.status);
                console.log('Delete response ok:', response.ok);

                if (response.status === 404) {
                    throw new Error('Resume not found');
                }

                if (response.status === 403) {
                    throw new Error('You do not have permission to delete this resume');
                }

                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Response text:', text);
                        throw new Error(`HTTP error! status: ${response.status}`);
                    });
                }

                return response.json();
            })
            .then(data => {
                console.log('Delete successful:', data);

                // Fadeout and remove the resume item
                resumeItem.style.transition = 'all 0.3s ease';
                resumeItem.style.opacity = '0';
                resumeItem.style.transform = 'translateX(-30px)';

                setTimeout(() => {
                    resumeItem.remove();

                    // Check if any resumes left
                    const resumesList = document.getElementById('resumesList');
                    if (resumesList && resumesList.children.length === 0) {
                        // Show no resumes message
                        const existingContent = document.getElementById('existing-content');
                        if (existingContent) {
                            existingContent.innerHTML = `
                            <div class="alert alert-info" role="alert">
                                <i class="ri-information-line"></i>
                                <strong>No resumes yet!</strong> Create one using the Resume Builder or upload a file.
                            </div>
                        `;
                        }
                    }

                    // If deleted resume was selected, clear selection
                    if (selectedResumeId === resumeId) {
                        selectedResumeId = null;
                        selectedResumeDownloadUrl = null;
                    }

                    console.log('Resume deleted successfully');
                }, 300);
            })
            .catch(error => {
                console.error('Complete error object:', error);
                console.error('Error deleting resume:', error.message);

                deleteButton.classList.remove('deleting');
                deleteButton.innerHTML = '<i class="ri-delete-bin-line"></i> Delete';

                let errorMessage = 'Failed to delete resume. Please try again.';
                if (error.message) {
                    errorMessage = error.message;
                }

                alert(errorMessage);
            });
    }

    // Attach delete button listeners
    function attachDeleteListeners() {
        document.querySelectorAll('.delete-resume-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const resumeId = this.getAttribute('data-resume-id');
                deleteResume(resumeId, e);
            });
        });
    }

    // Initial setup
    document.addEventListener('DOMContentLoaded', function() {
        attachDeleteListeners();
    });

    // Re-attach listeners after resume deletion
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                attachDeleteListeners();
            }
        });
    });

    const resumesList = document.getElementById('resumesList');
    if (resumesList) {
        observer.observe(resumesList, {
            childList: true
        });
    }

    // Resume file upload handling
    const resumeUploadArea = document.getElementById('resumeUploadArea');
    if (resumeUploadArea) {
        const resumeInput = document.getElementById('resume');
        const uploadContent = resumeUploadArea.querySelector('.upload-content');
        const uploadPreview = resumeUploadArea.querySelector('.upload-preview');

        // Drag and drop
        resumeUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            resumeUploadArea.classList.add('drag-over');
        });

        resumeUploadArea.addEventListener('dragleave', () => {
            resumeUploadArea.classList.remove('drag-over');
        });

        resumeUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            resumeUploadArea.classList.remove('drag-over');
            handleFileSelect(e.dataTransfer.files[0]);
        });

        resumeInput.addEventListener('change', (e) => {
            if (e.target.files[0]) {
                handleFileSelect(e.target.files[0]);
            }
        });

        function handleFileSelect(file) {
            if (!file) return;

            const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            const maxSize = 5 * 1024 * 1024; // 5MB

            if (!validTypes.includes(file.type)) {
                alert('Please upload a PDF or Word document');
                return;
            }

            if (file.size > maxSize) {
                alert('File size must be less than 5MB');
                return;
            }

            document.getElementById('fileName').textContent = file.name;
            uploadContent.classList.add('d-none');
            uploadPreview.classList.remove('d-none');
        }

        window.clearResume = function() {
            resumeInput.value = '';
            uploadContent.classList.remove('d-none');
            uploadPreview.classList.add('d-none');
        }
    }

    // Form submission - Handle existing resume conversion to file
    document.getElementById('step6Form').addEventListener('submit', async function(e) {
        e.preventDefault();

        // Validate email first
        const emailInput = document.getElementById('email');
        if (!emailInput || !emailInput.value.trim()) {
            alert('Please enter your email address before proceeding');
            if (emailInput) emailInput.focus();
            e.stopImmediatePropagation();
            return false;
        }

        // Validate terms and conditions
        const termsCheckbox = document.getElementById('termsCheck');
        const termsError = document.getElementById('termsError');
        if (!termsCheckbox || !termsCheckbox.checked) {
            termsError.style.display = 'block';
            termsCheckbox.classList.add('is-invalid');
            termsCheckbox.focus();
            e.stopImmediatePropagation();
            return false;
        } else {
            termsError.style.display = 'none';
            termsCheckbox.classList.remove('is-invalid');
        }

        const selectedOption = document.querySelector('input[name="resume_option"]:checked');

        if (!selectedOption) {
            alert('Please select a resume option');
            e.stopImmediatePropagation();
            return false;
        }

        const option = selectedOption.value;

        // Validation based on option
        if (option === 'upload') {
            const fileInput = document.getElementById('resume');
            if (!fileInput || !fileInput.value) {
                alert('Please select a resume file to upload');
                e.stopImmediatePropagation();
                return false;
            }
            // Normal form submission - allow loading overlay
            this.submit();
        } else if (option === 'existing') {
            // Validate that a resume was selected
            if (!selectedResumeId || !selectedResumeDownloadUrl) {
                alert('Please select a resume from your existing resumes');
                e.stopImmediatePropagation();
                return false;
            }

            // Convert existing resume to file and submit
            try {
                await convertAndSubmitExistingResume();
            } catch (error) {
                console.error('Error submitting resume:', error);
                alert('An error occurred. Please try again.');
                e.stopImmediatePropagation();
                return false;
            }
        } else if (option === 'builder') {
            alert('Please complete the Resume Builder process first');
            e.stopImmediatePropagation();
            window.location.href = '{{ route("resume-builder.form") }}';
            return false;
        }
    });

    // Convert existing resume PDF to file and submit form
    async function convertAndSubmitExistingResume() {
        try {
            // Fetch the resume PDF
            const response = await fetch(selectedResumeDownloadUrl);

            if (!response.ok) {
                throw new Error('Failed to fetch resume PDF');
            }

            const blob = await response.blob();

            // Get the form
            const form = document.getElementById('step6Form');
            const fileInput = document.getElementById('resume');

            // Create a File object from the blob
            const timestamp = new Date().getTime();
            const file = new File([blob], `resume-${timestamp}.pdf`, {
                type: 'application/pdf'
            });

            // Create DataTransfer and set files
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;

            // Update UI to show file selected
            document.querySelector('.upload-content').classList.add('d-none');
            const uploadPreview = document.querySelector('.upload-preview');
            if (uploadPreview) {
                uploadPreview.classList.remove('d-none');
                document.getElementById('fileName').textContent = `resume-${selectedResumeId}.pdf`;
            }

            // Submit the form
            form.submit();
        } catch (error) {
            console.error('Error converting resume:', error);
            throw error;
        }
    }

    // Upsell modal handling
    document.querySelectorAll('[data-upsell]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const upsellType = this.dataset.upsell;

            if (upsellType === 'resume-builder') {
                window.location.href = '{{ route("resume-builder.pricing") }}';
            } else {
                const titleMap = {
                    'cover-letter': 'AI Cover Letter'
                };
                document.getElementById('upsellTitle').textContent = titleMap[upsellType];
                new bootstrap.Modal(document.getElementById('upsellModal')).show();
            }
        });
    });

    document.querySelectorAll('[data-plan]').forEach(btn => {
        btn.addEventListener('click', function() {
            const plan = this.dataset.plan;
            window.location.href = '{{ route("resume-builder.pricing") }}';
        });
    });
</script>
@endsection