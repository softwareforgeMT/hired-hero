@extends('front.layouts.app')
@section('title', 'Edit Job Application')

@section('css')
<style>
    .form-section {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .form-section h4 {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f2f5;
    }

    .form-section h4 i {
        color: #ff6b35;
        font-size: 1.4rem;
    }

    .form-group {
        margin-bottom: 1.75rem;
    }

    .form-group label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.75rem;
        display: block;
        font-size: 0.95rem;
    }

    .form-control,
    .form-select {
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.875rem 1rem;
        font-size: 0.95rem;
        background-color: #f9fafb;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        background-color: white;
        border-color: #ff6b35;
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
    }

    .form-control::placeholder {
        color: #a0aec0;
    }

    .form-text {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.85rem;
        color: #718096;
        line-height: 1.5;
    }

    .status-guide {
        background: #fef5f0;
        border-left: 4px solid #ff6b35;
        padding: 1rem;
        border-radius: 6px;
        margin-top: 1rem;
        font-size: 0.85rem;
        color: #2d3748;
        line-height: 1.6;
    }

    .status-guide strong {
        color: #ff6b35;
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 700;
    }

    .btn-primary {
        background: linear-gradient(135deg, #ff6b35 0%, #ff5722 100%);
        border: none;
        font-weight: 700;
        padding: 0.875rem 2rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #ff5722 0%, #e64a19 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
    }

    .btn-secondary {
        padding: 0.875rem 2rem;
        font-weight: 700;
        border-radius: 8px;
        border: 1.5px solid #e5e7eb;
    }

    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #cbd5e0;
    }

    .btn-danger {
        font-weight: 700;
        border-radius: 8px;
        padding: 0.875rem 2rem;
    }

    .text-muted-small {
        font-size: 0.85rem;
        color: #718096;
    }

    .alert {
        border: none;
        border-radius: 8px;
        border-left: 4px solid;
    }

    .alert-danger {
        background: #fee;
        border-color: #dc3545;
        color: #842029;
    }

    .alert li {
        margin-bottom: 0.5rem;
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        font-weight: 500;
    }

    .is-invalid {
        border-color: #dc3545 !important;
        background-color: #fff5f5 !important;
    }

    .is-invalid:focus {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1) !important;
    }

    .card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .card-body {
        padding: 2rem;
    }

    .card-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1a202c;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title i {
        font-size: 1.2rem;
    }

    .list-unstyled li {
        padding-bottom: 0.5rem;
    }

    .list-unstyled strong {
        color: #2d3748;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .list-unstyled small {
        color: #718096;
        font-size: 0.85rem;
    }

    dl.row {
        font-size: 0.85rem;
    }

    dl.row dt {
        color: #2d3748;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    dl.row dd {
        color: #718096;
        margin-bottom: 0.75rem;
        margin-left: 0;
    }

    .page-header {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .page-header h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #1a202c;
    }

    .page-header p {
        font-size: 0.95rem;
        color: #718096;
        margin-top: 0.5rem;
    }

    .button-group {
        display: flex;
        gap: 1rem;
        padding-top: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }

    @media (max-width: 768px) {
        .form-section {
            padding: 1.5rem;
        }

        .form-section h4 {
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }

        .button-group {
            flex-direction: column;
        }

        .button-group button,
        .button-group a {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<section class="section page__content">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header mb-5">
            <div class="d-flex justify-content-between align-items-start gap-3">
                <div>
                    <h2 class="mb-2">Edit Job Application</h2>
                    <p class="mb-0">Update the details of your job application or adjust the status as you progress.</p>
                </div>
                <a href="{{ route('placement.applications.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ri-arrow-left-line"></i> Back to Tracker
                </a>
            </div>
        </div>

        <!-- Error Alert -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <strong><i class="ri-error-warning-line"></i> Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <form action="{{ route('placement.applications.update', $application) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Job Details Section -->
                    <div class="form-section">
                        <h4><i class="ri-briefcase-line"></i> Job Details</h4>

                        <div class="row g-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="job_title" class="form-label">Job Title <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('job_title') is-invalid @enderror" 
                                        id="job_title" 
                                        name="job_title"
                                        placeholder="e.g., Senior Software Engineer"
                                        value="{{ old('job_title', $application->job_title) }}"
                                        required
                                    >
                                    @error('job_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="form-text">The title of the position you're applying for</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('company_name') is-invalid @enderror" 
                                        id="company_name" 
                                        name="company_name"
                                        placeholder="e.g., Google, Microsoft, Startup Inc."
                                        value="{{ old('company_name', $application->company_name) }}"
                                        required
                                    >
                                    @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="form-text">The name of the company posting the job</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="job_url" class="form-label">Job URL <span class="text-muted-small">(Optional)</span></label>
                                    <input 
                                        type="url" 
                                        class="form-control @error('job_url') is-invalid @enderror" 
                                        id="job_url" 
                                        name="job_url"
                                        placeholder="https://example.com/jobs/123"
                                        value="{{ old('job_url', $application->job_url) }}"
                                    >
                                    @error('job_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="form-text">Link to the job posting (if available)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Section -->
                    <div class="form-section">
                        <h4><i class="ri-flag-line"></i> Application Status</h4>

                        <div class="row g-4">
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="status" class="form-label">Current Status <span class="text-danger">*</span></label>
                                    <select 
                                        class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status"
                                        required
                                    >
                                        <option value="">-- Select a status --</option>
                                        @foreach($statuses as $key => $label)
                                            <option value="{{ $key }}" {{ old('status', $application->status) === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="status-guide">
                                    <strong>Status Pipeline:</strong>
                                    To Review (collecting info) → Ready (to apply) → Applied (submitted) → Callback (got response) → Interview (scheduled) → Offer (received) → Hired (accepted)
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="applied_at" class="form-label">Applied Date <span class="text-danger">*</span></label>
                                    <input 
                                        type="date" 
                                        class="form-control @error('applied_at') is-invalid @enderror" 
                                        id="applied_at" 
                                        name="applied_at"
                                        value="{{ old('applied_at', $application->applied_at->format('Y-m-d')) }}"
                                        max="{{ now()->format('Y-m-d') }}"
                                        required
                                    >
                                    @error('applied_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="form-text">When you applied or started tracking this job</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Interview Details Section -->
                    <div class="form-section">
                        <h4><i class="ri-calendar-line"></i> Interview Details <span class="text-muted-small">(Optional)</span></h4>

                        <div class="row g-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="interview_date" class="form-label">Interview Date <span class="text-muted-small">(Optional)</span></label>
                                    <input 
                                        type="date" 
                                        class="form-control @error('interview_date') is-invalid @enderror" 
                                        id="interview_date" 
                                        name="interview_date"
                                        value="{{ old('interview_date', $application->interview_date?->format('Y-m-d')) }}"
                                    >
                                    @error('interview_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="form-text">If you have a scheduled interview</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="interview_notes" class="form-label">Interview Notes <span class="text-muted-small">(Optional)</span></label>
                                    <textarea 
                                        class="form-control @error('interview_notes') is-invalid @enderror" 
                                        id="interview_notes" 
                                        name="interview_notes"
                                        rows="4"
                                        placeholder="e.g., Round 1: Technical assessment, Round 2: Manager interview, Feedback: ..."
                                    >{{ old('interview_notes', $application->interview_notes) }}</textarea>
                                    @error('interview_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="form-text">Keep notes about interview rounds, feedback, or next steps</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="ri-save-line"></i> Save Changes
                        </button>
                        <a href="{{ route('placement.applications.index') }}" class="btn btn-secondary btn-lg">
                            <i class="ri-close-line"></i> Cancel
                        </a>
                        <button type="button" class="btn btn-danger btn-lg ms-auto" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="ri-delete-bin-line"></i> Delete
                        </button>
                    </div>
                </form>
            </div>

            <!-- Side Info Panel -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="ri-lightbulb-line"></i> Editing Tips
                        </h5>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <strong>Keep Information Updated</strong>
                                <small class="text-muted d-block">Update job details and status as you progress</small>
                            </li>
                            <li class="mb-3">
                                <strong>Track Interview Rounds</strong>
                                <small class="text-muted d-block">Add dates and notes for each interview stage</small>
                            </li>
                            <li class="mb-3">
                                <strong>Document Everything</strong>
                                <small class="text-muted d-block">Record feedback and next steps in notes</small>
                            </li>
                            <li>
                                <strong>Progress Tracking</strong>
                                <small class="text-muted d-block">Use status changes to visualize your pipeline</small>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="ri-information-line"></i> Status Guide
                        </h5>
                        <dl class="row mb-0">
                            <dt class="col-6"><small>To Review</small></dt>
                            <dd class="col-6"><small>Researching</small></dd>

                            <dt class="col-6"><small>Ready</small></dt>
                            <dd class="col-6"><small>Ready to apply</small></dd>

                            <dt class="col-6"><small>Applied</small></dt>
                            <dd class="col-6"><small>Submitted</small></dd>

                            <dt class="col-6"><small>Callback</small></dt>
                            <dd class="col-6"><small>Got response</small></dd>

                            <dt class="col-6"><small>Interview</small></dt>
                            <dd class="col-6"><small>In process</small></dd>

                            <dt class="col-6"><small>Offer</small></dt>
                            <dd class="col-6"><small>Got offer</small></dd>

                            <dt class="col-6"><small>Hired</small></dt>
                            <dd class="col-6"><small>Accepted</small></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="ri-alert-line" style="color: #dc3545;"></i> Delete Application
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this application?</p>
                <p class="text-muted"><strong>{{ $application->job_title }}</strong> at <strong>{{ $application->company_name }}</strong></p>
                <p class="text-danger small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('placement.applications.destroy', $application) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ri-delete-bin-line"></i> Delete Application
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .form-section {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .form-section h4 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-section h4 i {
        color: #ff6b35;
        font-size: 1.3rem;
    }

    .form-group label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #ff6b35;
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.15);
    }

    .btn-primary {
        background: linear-gradient(135deg, #ff6b35 0%, #ff5722 100%);
        border: none;
        font-weight: 700;
        padding: 0.75rem 2rem;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #ff5722 0%, #e64a19 100%);
    }

    .btn-secondary {
        padding: 0.75rem 2rem;
        font-weight: 700;
    }

    .text-muted-small {
        font-size: 0.85rem;
        color: #718096;
    }

    .alert {
        border: none;
        border-radius: 8px;
    }

    .alert-danger {
        background: #fee;
        color: #842029;
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    .is-invalid {
        border-color: #dc3545;
    }
</style>
@endsection

@section('content')
<section class="section page__content">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <h2 class="mb-0">Edit Job Application</h2>
                    <a href="{{ route('placement.applications.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ri-arrow-left-line"></i> Back to Tracker
                    </a>
                </div>
                <p class="text-muted">Update the details of your job application or adjust the status as you progress.</p>
            </div>
        </div>

        <!-- Error Alert -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Oops!</strong> Please fix the following errors:
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <form action="{{ route('placement.applications.update', $application) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Job Details Section -->
                    <div class="form-section">
                        <h4><i class="ri-briefcase-line"></i> Job Details</h4>

                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="job_title" class="form-label">Job Title <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('job_title') is-invalid @enderror" 
                                        id="job_title" 
                                        name="job_title"
                                        placeholder="e.g., Senior Software Engineer"
                                        value="{{ old('job_title', $application->job_title) }}"
                                        required
                                    >
                                    @error('job_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="text-muted-small">The title of the position you're applying for</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('company_name') is-invalid @enderror" 
                                        id="company_name" 
                                        name="company_name"
                                        placeholder="e.g., Google, Microsoft, Startup Inc."
                                        value="{{ old('company_name', $application->company_name) }}"
                                        required
                                    >
                                    @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="text-muted-small">The name of the company posting the job</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="job_url" class="form-label">Job URL <span class="text-muted-small">(Optional)</span></label>
                                    <input 
                                        type="url" 
                                        class="form-control @error('job_url') is-invalid @enderror" 
                                        id="job_url" 
                                        name="job_url"
                                        placeholder="https://example.com/jobs/123"
                                        value="{{ old('job_url', $application->job_url) }}"
                                    >
                                    @error('job_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="text-muted-small">Link to the job posting (if available)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Section -->
                    <div class="form-section">
                        <h4><i class="ri-flag-line"></i> Application Status</h4>

                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="status" class="form-label">Current Status <span class="text-danger">*</span></label>
                                    <select 
                                        class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status"
                                        required
                                    >
                                        <option value="">-- Select a status --</option>
                                        @foreach($statuses as $key => $label)
                                            <option value="{{ $key }}" {{ old('status', $application->status) === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="text-muted-small d-block mt-2">
                                        <strong>Status Guide:</strong>
                                        To Review (collecting info) → Ready (to apply) → Applied (submitted) → Callback (got response) → Interview (scheduled) → Offer (received) → Hired (accepted)
                                    </small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="applied_at" class="form-label">Applied Date <span class="text-danger">*</span></label>
                                    <input 
                                        type="date" 
                                        class="form-control @error('applied_at') is-invalid @enderror" 
                                        id="applied_at" 
                                        name="applied_at"
                                        value="{{ old('applied_at', $application->applied_at->format('Y-m-d')) }}"
                                        max="{{ now()->format('Y-m-d') }}"
                                        required
                                    >
                                    @error('applied_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="text-muted-small">When you applied or started tracking this job</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Interview Details Section -->
                    <div class="form-section">
                        <h4><i class="ri-calendar-line"></i> Interview Details <span class="text-muted-small">(Optional)</span></h4>

                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="interview_date" class="form-label">Interview Date <span class="text-muted-small">(Optional)</span></label>
                                    <input 
                                        type="date" 
                                        class="form-control @error('interview_date') is-invalid @enderror" 
                                        id="interview_date" 
                                        name="interview_date"
                                        value="{{ old('interview_date', $application->interview_date?->format('Y-m-d')) }}"
                                    >
                                    @error('interview_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="text-muted-small">If you have a scheduled interview</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="interview_notes" class="form-label">Interview Notes <span class="text-muted-small">(Optional)</span></label>
                                    <textarea 
                                        class="form-control @error('interview_notes') is-invalid @enderror" 
                                        id="interview_notes" 
                                        name="interview_notes"
                                        rows="3"
                                        placeholder="e.g., Round 1: Technical assessment, Round 2: Manager interview..."
                                    >{{ old('interview_notes', $application->interview_notes) }}</textarea>
                                    @error('interview_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="text-muted-small">Keep notes about interview rounds, feedback, or next steps</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line"></i> Save Changes
                        </button>
                        <a href="{{ route('placement.applications.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="button" class="btn btn-danger ms-auto" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="ri-delete-bin-line"></i> Delete
                        </button>
                    </div>
                </form>
            </div>

            <!-- Side Info Panel -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="ri-lightbulb-line" style="color: #ff6b35;"></i> Tips
                        </h5>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <strong class="d-block mb-1">Keep Information Updated</strong>
                                <small class="text-muted">Update job details and status as you progress</small>
                            </li>
                            <li class="mb-3">
                                <strong class="d-block mb-1">Track Interview Rounds</strong>
                                <small class="text-muted">Add dates and notes for each interview stage</small>
                            </li>
                            <li class="mb-3">
                                <strong class="d-block mb-1">Document Everything</strong>
                                <small class="text-muted">Record feedback and next steps in notes</small>
                            </li>
                            <li>
                                <strong class="d-block mb-1">Progress Tracking</strong>
                                <small class="text-muted">Use status changes to visualize your pipeline</small>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="ri-information-line" style="color: #ff6b35;"></i> Status Meanings
                        </h5>
                        <dl class="row mb-0">
                            <dt class="col-sm-5"><small><strong>To Review</strong></small></dt>
                            <dd class="col-sm-7"><small>Researching the role</small></dd>

                            <dt class="col-sm-5"><small><strong>Ready</strong></small></dt>
                            <dd class="col-sm-7"><small>Prepared to apply</small></dd>

                            <dt class="col-sm-5"><small><strong>Applied</strong></small></dt>
                            <dd class="col-sm-7"><small>Submitted application</small></dd>

                            <dt class="col-sm-5"><small><strong>Interview</strong></small></dt>
                            <dd class="col-sm-7"><small>In interview process</small></dd>

                            <dt class="col-sm-5"><small><strong>Offer</strong></small></dt>
                            <dd class="col-sm-7"><small>Got a job offer</small></dd>

                            <dt class="col-sm-5"><small><strong>Hired</strong></small></dt>
                            <dd class="col-sm-7"><small>Accepted the offer</small></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="ri-alert-line" style="color: #dc3545;"></i> Delete Application
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this application?</p>
                <p class="text-muted"><strong>{{ $application->job_title }}</strong> at <strong>{{ $application->company_name }}</strong></p>
                <p class="text-danger small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('placement.applications.destroy', $application) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ri-delete-bin-line"></i> Delete Application
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
