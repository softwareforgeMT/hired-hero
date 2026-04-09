
<?php $__env->startSection('title', 'Application Details - ' . $application->job_title); ?>

<?php $__env->startSection('css'); ?>
<style>
    .detail-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .detail-section h4 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f2f5;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .detail-section h4 i {
        color: #ff6b35;
        font-size: 1.3rem;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #f0f2f5;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #2d3748;
        font-size: 0.95rem;
        min-width: 150px;
    }

    .detail-value {
        color: #4a5568;
        font-size: 0.95rem;
        text-align: right;
        flex: 1;
        margin-left: 2rem;
    }

    .detail-value.full-width {
        text-align: left;
        margin-left: 0;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .status-badge.to-review { background: #fff3cd; color: #856404; }
    .status-badge.ready { background: #cfe2ff; color: #084298; }
    .status-badge.applied { background: #cff4fc; color: #055160; }
    .status-badge.callback { background: #e2e3e5; color: #383d41; }
    .status-badge.interview { background: #d1e7dd; color: #0f5132; }
    .status-badge.offer { background: #d1e7dd; color: #0f5132; }
    .status-badge.hired { background: #d1e7dd; color: #0f5132; }
    .status-badge.rejected { background: #f8d7da; color: #842029; }
    .status-badge.archived { background: #e2e3e5; color: #383d41; }

    .badge-link {
        display: inline-block;
        color: #ff6b35;
        text-decoration: none;
        word-break: break-all;
    }

    .badge-link:hover {
        text-decoration: underline;
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
        margin-bottom: 0.5rem;
    }

    .page-header p {
        color: #718096;
        margin: 0;
    }

    .btn-primary {
        background: linear-gradient(135deg, #ff6b35 0%, #ff5722 100%);
        border: none;
        font-weight: 700;
        border-radius: 8px;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #ff5722 0%, #e64a19 100%);
        transform: translateY(-2px);
    }

    .btn-secondary {
        border: 1.5px solid #e5e7eb;
        font-weight: 700;
        border-radius: 8px;
    }

    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #cbd5e0;
    }

    .btn-danger {
        font-weight: 700;
        border-radius: 8px;
    }

    .btn-group-custom {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #e5e7eb;
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #718096;
    }

    .empty-state i {
        font-size: 2rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .detail-row {
            flex-direction: column;
        }

        .detail-value {
            text-align: left;
            margin-left: 0;
            margin-top: 0.5rem;
        }

        .btn-group-custom {
            flex-direction: column;
        }

        .btn-group-custom button,
        .btn-group-custom a {
            width: 100%;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="section page__content">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-start gap-3">
                <div>
                    <h2 class="mb-2"><?php echo e($application->job_title); ?></h2>
                    <p class="mb-0">
                        <i class="ri-building-2-line"></i> <?php echo e($application->company_name); ?>

                    </p>
                </div>
                <a href="<?php echo e(route('placement.applications.index')); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="ri-arrow-left-line"></i> Back to Tracker
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Job Information -->
                <div class="detail-card detail-section">
                    <h4><i class="ri-briefcase-line"></i> Job Information</h4>

                    <div class="detail-row">
                        <span class="detail-label">Job Title:</span>
                        <span class="detail-value"><?php echo e($application->job_title); ?></span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Company:</span>
                        <span class="detail-value"><?php echo e($application->company_name); ?></span>
                    </div>

                    <?php if($application->job_url): ?>
                    <div class="detail-row">
                        <span class="detail-label">Job Link:</span>
                        <span class="detail-value">
                            <a href="<?php echo e($application->job_url); ?>" target="_blank" class="badge-link">
                                <i class="ri-external-link-line"></i> View Job Posting
                            </a>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Application Status -->
                <div class="detail-card detail-section">
                    <h4><i class="ri-flag-line"></i> Application Status</h4>

                    <div class="detail-row">
                        <span class="detail-label">Current Status:</span>
                        <span class="detail-value">
                            <span class="status-badge <?php echo e($application->status); ?>">
                                <?php echo e(match($application->status) {
                                    'to-review' => 'To Review',
                                    'ready' => 'Ready',
                                    'applied' => 'Applied',
                                    'callback' => 'Callback',
                                    'interview' => 'Interview',
                                    'offer' => 'Offer',
                                    'hired' => 'Hired',
                                    'rejected' => 'Rejected',
                                    'archived' => 'Archived',
                                    default => ucfirst($application->status)
                                }); ?>

                            </span>
                        </span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Applied Date:</span>
                        <span class="detail-value">
                            <?php echo e($application->applied_at->format('M d, Y')); ?>

                            <small class="text-muted d-block"><?php echo e($application->applied_at->diffForHumans()); ?></small>
                        </span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Last Updated:</span>
                        <span class="detail-value">
                            <?php echo e($application->last_activity_at?->format('M d, Y') ?? '—'); ?>

                            <?php if($application->last_activity_at): ?>
                            <small class="text-muted d-block"><?php echo e($application->last_activity_at->diffForHumans()); ?></small>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>

                <!-- Interview Details -->
                <?php if($application->interview_date || $application->interview_notes): ?>
                <div class="detail-card detail-section">
                    <h4><i class="ri-calendar-line"></i> Interview Details</h4>

                    <?php if($application->interview_date): ?>
                    <div class="detail-row">
                        <span class="detail-label">Interview Date:</span>
                        <span class="detail-value">
                            <?php echo e($application->interview_date->format('M d, Y')); ?>

                            <?php if($application->interview_date->isFuture()): ?>
                            <small class="text-muted d-block">in <?php echo e($application->interview_date->diffForHumans()); ?></small>
                            <?php elseif($application->interview_date->isPast()): ?>
                            <small class="text-muted d-block"><?php echo e($application->interview_date->diffForHumans()); ?></small>
                            <?php endif; ?>
                        </span>
                    </div>
                    <?php endif; ?>

                    <?php if($application->interview_notes): ?>
                    <div class="detail-row">
                        <span class="detail-label">Interview Notes:</span>
                        <span class="detail-value full-width">
                            <?php echo e(nl2br(e($application->interview_notes))); ?>

                        </span>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Offer Details -->
                <?php if($application->offer_date || $application->offer_salary || $application->offer_accepted !== null): ?>
                <div class="detail-card detail-section">
                    <h4><i class="ri-gift-line"></i> Offer Details</h4>

                    <?php if($application->offer_date): ?>
                    <div class="detail-row">
                        <span class="detail-label">Offer Date:</span>
                        <span class="detail-value"><?php echo e($application->offer_date->format('M d, Y')); ?></span>
                    </div>
                    <?php endif; ?>

                    <?php if($application->offer_salary): ?>
                    <div class="detail-row">
                        <span class="detail-label">Salary Offered:</span>
                        <span class="detail-value">$<?php echo e(number_format($application->offer_salary, 0)); ?></span>
                    </div>
                    <?php endif; ?>

                    <?php if($application->offer_accepted !== null): ?>
                    <div class="detail-row">
                        <span class="detail-label">Status:</span>
                        <span class="detail-value">
                            <?php if($application->offer_accepted): ?>
                            <span class="badge bg-success">Accepted</span>
                            <?php else: ?>
                            <span class="badge bg-warning">Declined</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Cover Letter -->
                <?php if($application->cover_letter): ?>
                <div class="detail-card detail-section">
                    <h4><i class="ri-file-text-line"></i> Cover Letter</h4>

                    <div class="detail-row">
                        <span class="detail-value full-width">
                            <?php echo e(nl2br(e($application->cover_letter))); ?>

                        </span>
                    </div>

                    <?php if($application->used_ai_cover_letter): ?>
                    <div class="mt-3 p-2 bg-light rounded text-muted small">
                        <i class="ri-ai-generate"></i> AI-Generated Cover Letter
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="btn-group-custom">
                    <a href="<?php echo e(route('placement.applications.edit', $application)); ?>" class="btn btn-primary">
                        <i class="ri-edit-line"></i> Edit Application
                    </a>
                    <a href="<?php echo e(route('placement.applications.index')); ?>" class="btn btn-secondary">
                        <i class="ri-arrow-left-line"></i> Back to List
                    </a>
                    <button type="button" class="btn btn-danger ms-auto" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="ri-delete-bin-line"></i> Delete
                    </button>
                </div>
            </div>

            <!-- Side Panel -->
            <div class="col-lg-4">
                <!-- Quick Stats -->
                <div class="detail-card">
                    <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.8rem; letter-spacing: 0.05em;">
                        <i class="ri-bar-chart-line"></i> Quick Stats
                    </h6>

                    <div class="detail-row" style="padding: 0.75rem 0;">
                        <span class="detail-label" style="min-width: 120px; font-size: 0.9rem;">Days In Pipeline:</span>
                        <span class="detail-value" style="text-align: left; margin-left: 1rem;">
                            <strong style="font-size: 1.3rem; color: #ff6b35;">
                                <?php echo e($application->applied_at->diffInDays(now())); ?>

                            </strong>
                            <small class="text-muted d-block">since applied</small>
                        </span>
                    </div>

                    <div class="detail-row" style="padding: 0.75rem 0;">
                        <span class="detail-label" style="min-width: 120px; font-size: 0.9rem;">Status:</span>
                        <span class="detail-value" style="text-align: left; margin-left: 1rem;">
                            <span class="status-badge <?php echo e($application->status); ?>">
                                <?php echo e(match($application->status) {
                                    'to-review' => 'Reviewing',
                                    'ready' => 'Ready',
                                    'applied' => 'Applied',
                                    'callback' => 'Callback',
                                    'interview' => 'Interview',
                                    'offer' => 'Offer',
                                    'hired' => 'Hired',
                                    'rejected' => 'Rejected',
                                    'archived' => 'Archived',
                                    default => ucfirst($application->status)
                                }); ?>

                            </span>
                        </span>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="detail-card mt-4">
                    <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.8rem; letter-spacing: 0.05em;">
                        <i class="ri-history-line"></i> Timeline
                    </h6>

                    <div style="font-size: 0.9rem;">
                        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
                            <div style="color: #ff6b35; font-weight: 700; min-width: 30px; text-align: center;">
                                <i class="ri-check-line"></i>
                            </div>
                            <div>
                                <strong>Applied</strong>
                                <small class="text-muted d-block"><?php echo e($application->applied_at->format('M d, Y')); ?></small>
                            </div>
                        </div>

                        <?php if($application->interview_date): ?>
                        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
                            <div style="color: <?php echo e($application->interview_date->isFuture() ? '#f0ad4e' : '#0f5132'); ?>; font-weight: 700; min-width: 30px; text-align: center;">
                                <?php echo e($application->interview_date->isFuture() ? '◯' : '✓'); ?>

                            </div>
                            <div>
                                <strong>Interview</strong>
                                <small class="text-muted d-block"><?php echo e($application->interview_date->format('M d, Y')); ?></small>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if($application->offer_date): ?>
                        <div style="display: flex; gap: 1rem;">
                            <div style="color: <?php echo e($application->offer_accepted ? '#0f5132' : '#842029'); ?>; font-weight: 700; min-width: 30px; text-align: center;">
                                <?php echo e($application->offer_accepted ? '✓' : '✗'); ?>

                            </div>
                            <div>
                                <strong>Offer <?php echo e($application->offer_accepted ? '(Accepted)' : '(Declined)'); ?></strong>
                                <small class="text-muted d-block"><?php echo e($application->offer_date->format('M d, Y')); ?></small>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="detail-card mt-4">
                    <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.8rem; letter-spacing: 0.05em;">
                        <i class="ri-lightbulb-line"></i> Next Steps
                    </h6>

                    <?php switch($application->status):
                        case ('to-review'): ?>
                            <p style="font-size: 0.9rem; color: #4a5568; margin: 0;">
                                Prepare your application materials and update your status when ready to apply.
                            </p>
                            <?php break; ?>

                        <?php case ('applied'): ?>
                            <p style="font-size: 0.9rem; color: #4a5568; margin: 0;">
                                Wait for a response from the company. Update status once you hear back!
                            </p>
                            <?php break; ?>

                        <?php case ('interview'): ?>
                            <p style="font-size: 0.9rem; color: #4a5568; margin: 0;">
                                Prepare for your interview. Add notes about the rounds and feedback you receive.
                            </p>
                            <?php break; ?>

                        <?php case ('offer'): ?>
                            <p style="font-size: 0.9rem; color: #4a5568; margin: 0;">
                                Review the offer carefully and make your decision. Update status based on your choice.
                            </p>
                            <?php break; ?>

                        <?php case ('hired'): ?>
                            <p style="font-size: 0.9rem; color: #4a5568; margin: 0;">
                                🎉 Congratulations! You've accepted this offer. Best of luck in your new role!
                            </p>
                            <?php break; ?>

                        <?php default: ?>
                            <p style="font-size: 0.9rem; color: #4a5568; margin: 0;">
                                Update your application status as you progress through the interview process.
                            </p>
                    <?php endswitch; ?>
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
                <p class="text-muted"><strong><?php echo e($application->job_title); ?></strong> at <strong><?php echo e($application->company_name); ?></strong></p>
                <p class="text-danger small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="<?php echo e(route('placement.applications.destroy', $application)); ?>" method="POST" style="display:inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">
                        <i class="ri-delete-bin-line"></i> Delete Application
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('front.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Herd-Projects\HiredHero-Job-Match\resources\views/placement/applications/show.blade.php ENDPATH**/ ?>