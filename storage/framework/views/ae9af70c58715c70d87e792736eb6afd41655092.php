
<?php $__env->startSection('title', 'Application Tracker'); ?>

<?php $__env->startSection('css'); ?>
<style>
    .pipeline-stage {
        flex: 1;
        text-align: center;
        padding: 1rem;
        border-radius: 8px;
        background: #f8f9fa;
    }

    .pipeline-stage h5 {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .pipeline-stage .count {
        font-size: 2rem;
        font-weight: 700;
        color: #00A3FF;
    }

    .app-row {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
        hover: background: #f8f9fa;
    }

    .status-badge {
        min-width: 120px;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        text-align: center;
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

    .filter-loading {
        position: relative;
    }

    .filter-loading::after {
        content: '';
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        border: 2px solid #f0f0f0;
        border-top-color: #ff6b35;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
        display: none;
    }

    .filter-loading.active::after {
        display: block;
    }

    @keyframes spin {
        to { transform: translateY(-50%) rotate(360deg); }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="section page__content">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Application Tracker</h2>
            <a href="<?php echo e(route('placement.applications.create')); ?>" class="btn btn-primary">
                <i class="ri-add-line"></i> Add Application
            </a>
        </div>

        <!-- Success Message -->
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-checkbox-circle-line"></i> <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Pipeline Overview -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex gap-2 flex-wrap">
                    <div class="pipeline-stage">
                        <h5>To Review</h5>
                        <div class="count"><?php echo e($stats['to_review']); ?></div>
                    </div>
                    <div class="pipeline-stage">
                        <h5>Ready</h5>
                        <div class="count"><?php echo e($stats['ready']); ?></div>
                    </div>
                    <div class="pipeline-stage">
                        <h5>Applied</h5>
                        <div class="count"><?php echo e($stats['applied']); ?></div>
                    </div>
                    <div class="pipeline-stage">
                        <h5>Callback</h5>
                        <div class="count"><?php echo e($stats['callback']); ?></div>
                    </div>
                    <div class="pipeline-stage">
                        <h5>Interview</h5>
                        <div class="count"><?php echo e($stats['interview']); ?></div>
                    </div>
                    <div class="pipeline-stage">
                        <h5>Offer</h5>
                        <div class="count"><?php echo e($stats['offer']); ?></div>
                    </div>
                    <div class="pipeline-stage">
                        <h5>Hired</h5>
                        <div class="count"><?php echo e($stats['hired']); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="card mb-4">
            <div class="card-body">
                <form id="filterForm" action="<?php echo e(route('placement.applications.filter')); ?>" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <div style="position: relative;">
                            <input 
                                type="text" 
                                id="companyInput"
                                name="company" 
                                class="form-control" 
                                placeholder="Search by company..."
                                value="<?php echo e(request('company')); ?>"
                                autocomplete="off"
                            >
                            <small class="text-muted d-block mt-2" style="font-size: 0.8rem;">Results update as you type</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select id="statusFilter" name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="to-review" <?php echo e(request('status') === 'to-review' ? 'selected' : ''); ?>>To Review</option>
                            <option value="ready" <?php echo e(request('status') === 'ready' ? 'selected' : ''); ?>>Ready</option>
                            <option value="applied" <?php echo e(request('status') === 'applied' ? 'selected' : ''); ?>>Applied</option>
                            <option value="callback" <?php echo e(request('status') === 'callback' ? 'selected' : ''); ?>>Callback</option>
                            <option value="interview" <?php echo e(request('status') === 'interview' ? 'selected' : ''); ?>>Interview</option>
                            <option value="offer" <?php echo e(request('status') === 'offer' ? 'selected' : ''); ?>>Offer</option>
                            <option value="hired" <?php echo e(request('status') === 'hired' ? 'selected' : ''); ?>>Hired</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="ri-search-line"></i> Apply Filters
                            </button>
                            <a href="<?php echo e(route('placement.applications.index')); ?>" class="btn btn-outline-secondary">
                                <i class="ri-refresh-line"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Applications List -->
        <div class="card">
            <?php if($applications->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Job Title</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Applied Date</th>
                                <th>Last Activity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><strong><?php echo e($app->job_title); ?></strong></td>
                                    <td><?php echo e($app->company_name); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo e($app->status); ?>">
                                            <?php echo e(match($app->status) {
                                                'to-review' => 'To Review',
                                                'ready' => 'Ready',
                                                'applied' => 'Applied',
                                                'callback' => 'Callback',
                                                'interview' => 'Interview',
                                                'offer' => 'Offer',
                                                'hired' => 'Hired',
                                                'rejected' => 'Rejected',
                                                'archived' => 'Archived',
                                                default => ucfirst($app->status)
                                            }); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($app->applied_at->format('M d, Y')); ?></td>
                                    <td><?php echo e($app->last_activity_at?->diffForHumans() ?? '—'); ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="<?php echo e(route('placement.applications.edit', $app)); ?>" 
                                               class="btn btn-outline-warning" title="Edit">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <a href="<?php echo e(route('placement.applications.show', $app)); ?>" 
                                               class="btn btn-outline-primary" title="View Details">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <?php if($app->job_url): ?>
                                            <a href="<?php echo e($app->job_url); ?>" target="_blank" 
                                               class="btn btn-outline-secondary" title="View Job">
                                                <i class="ri-external-link-line"></i>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer">
                    <?php echo e($applications->links()); ?>

                </div>
            <?php else: ?>
                <div class="card-body text-center py-5">
                    <i class="ri-inbox-line" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">You haven't applied to any jobs yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
    // Debounce functionality for real-time search
    let debounceTimer;
    const companyInput = document.getElementById('companyInput');
    const statusFilter = document.getElementById('statusFilter');
    const filterForm = document.getElementById('filterForm');

    function submitFilterForm() {
        // Show loading indicator
        if (companyInput.value.trim().length > 0) {
            companyInput.parentElement.classList.add('filter-loading', 'active');
        }
        
        // Submit the form after a short delay
        setTimeout(() => {
            filterForm.submit();
        }, 300);
    }

    // Debounced search on company input
    companyInput.addEventListener('input', function(e) {
        clearTimeout(debounceTimer);
        
        // If input is empty, still submit to show all
        if (this.value.trim().length === 0) {
            this.parentElement.classList.remove('filter-loading', 'active');
            debounceTimer = setTimeout(() => {
                submitFilterForm();
            }, 500);
        } else {
            // Wait 800ms before submitting
            debounceTimer = setTimeout(() => {
                submitFilterForm();
            }, 800);
        }
    });

    // Immediate submit on status change
    statusFilter.addEventListener('change', function() {
        filterForm.submit();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('front.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Herd-Projects\HiredHero-Job-Match\resources\views/placement/applications/tracker.blade.php ENDPATH**/ ?>