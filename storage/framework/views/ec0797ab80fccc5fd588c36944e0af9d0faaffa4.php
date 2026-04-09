
<?php $__env->startSection('title', 'Add Job Application'); ?>

<?php $__env->startSection('css'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="section page__content">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header mb-5">
            <div class="d-flex justify-content-between align-items-start gap-3">
                <div>
                    <h2 class="mb-2">Add Job Application</h2>
                    <p class="mb-0">Track jobs you're interested in and monitor your progress through the interview process.</p>
                </div>
                <a href="<?php echo e(route('placement.applications.index')); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="ri-arrow-left-line"></i> Back to Tracker
                </a>
            </div>
        </div>

        <!-- Error Alert -->
        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <strong><i class="ri-error-warning-line"></i> Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <form action="<?php echo e(route('placement.applications.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <!-- Job Details Section -->
                    <div class="form-section">
                        <h4><i class="ri-briefcase-line"></i> Job Details</h4>

                        <div class="row g-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="job_title" class="form-label">Job Title <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control <?php $__errorArgs = ['job_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="job_title" 
                                        name="job_title"
                                        placeholder="e.g., Senior Software Engineer"
                                        value="<?php echo e(old('job_title')); ?>"
                                        required
                                    >
                                    <?php $__errorArgs = ['job_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text">The title of the position you're applying for</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control <?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="company_name" 
                                        name="company_name"
                                        placeholder="e.g., Google, Microsoft, Startup Inc."
                                        value="<?php echo e(old('company_name')); ?>"
                                        required
                                    >
                                    <?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text">The name of the company posting the job</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="job_url" class="form-label">Job URL <span class="text-muted-small">(Optional)</span></label>
                                    <input 
                                        type="url" 
                                        class="form-control <?php $__errorArgs = ['job_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="job_url" 
                                        name="job_url"
                                        placeholder="https://example.com/jobs/123"
                                        value="<?php echo e(old('job_url')); ?>"
                                    >
                                    <?php $__errorArgs = ['job_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                        class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="status" 
                                        name="status"
                                        required
                                    >
                                        <option value="">-- Select a status --</option>
                                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key); ?>" <?php echo e(old('status') === $key ? 'selected' : ''); ?>>
                                                <?php echo e($label); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                        class="form-control <?php $__errorArgs = ['applied_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="applied_at" 
                                        name="applied_at"
                                        value="<?php echo e(old('applied_at', now()->format('Y-m-d'))); ?>"
                                        max="<?php echo e(now()->format('Y-m-d')); ?>"
                                        required
                                    >
                                    <?php $__errorArgs = ['applied_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                        class="form-control <?php $__errorArgs = ['interview_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="interview_date" 
                                        name="interview_date"
                                        value="<?php echo e(old('interview_date')); ?>"
                                    >
                                    <?php $__errorArgs = ['interview_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text">If you have a scheduled interview</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="interview_notes" class="form-label">Interview Notes <span class="text-muted-small">(Optional)</span></label>
                                    <textarea 
                                        class="form-control <?php $__errorArgs = ['interview_notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="interview_notes" 
                                        name="interview_notes"
                                        rows="4"
                                        placeholder="e.g., Round 1: Technical assessment, Round 2: Manager interview, Feedback: ..."
                                    ><?php echo e(old('interview_notes')); ?></textarea>
                                    <?php $__errorArgs = ['interview_notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text">Keep notes about interview rounds, feedback, or next steps</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="ri-add-line"></i> Add Application
                        </button>
                        <a href="<?php echo e(route('placement.applications.index')); ?>" class="btn btn-secondary btn-lg">
                            <i class="ri-close-line"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>

            <!-- Side Info Panel -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="ri-lightbulb-line"></i> Tracking Tips
                        </h5>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <strong>Track Everything</strong>
                                <small class="text-muted d-block">Add jobs as soon as you apply to stay organized</small>
                            </li>
                            <li class="mb-3">
                                <strong>Update Status Regularly</strong>
                                <small class="text-muted d-block">Update the status when you get callbacks or interview invites</small>
                            </li>
                            <li class="mb-3">
                                <strong>Keep Notes</strong>
                                <small class="text-muted d-block">Write down interview details and feedback</small>
                            </li>
                            <li>
                                <strong>Monitor Progress</strong>
                                <small class="text-muted d-block">Use the dashboard to see your application pipeline</small>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('front.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Herd-Projects\HiredHero-Job-Match\resources\views/placement/applications/create.blade.php ENDPATH**/ ?>