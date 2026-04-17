
<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('css'); ?>
    <style type="text/css">
        .flex-col-row>[class*='col-'] {
            display: flex;
        }

        .position-top {
            bottom: 100% !important;
            right: 0%;
            top: unset !important;
            margin-bottom: 3px;
        }

        .tooltip-active .valid-tooltip {
            display: block;
        }

        .bg-copylink {
            background: none !important;
            border: 1px solid grey;
            color: black !important;
            cursor: pointer;
        }

        .activelink .bg-copylink {
            border-color: green;
            color: green !important;
        }

        /* Dashboard Enhancements - Theme Matched */
        .stats-card {
            background: linear-gradient(135deg, var(--bs-primary) 0%, #4f3aa6 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .stats-card.green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .stats-card.blue {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        }

        .stats-card.orange {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        }

        .stats-card.red {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .stats-card h6 {
            font-size: 0.875rem;
            font-weight: 500;
            margin: 0 0 0.5rem 0;
            opacity: 0.9;
            color: white;
        }

        .stats-card .number {
            font-size: 2rem;
            font-weight: bold;
            margin: 0;
            color: white;
        }

        .feature-card {
            background: var(--bs-card-bg, var(--bs-gray-200));
            border: 1px solid var(--bs-border-color);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            border-color: var(--bs-primary);
        }

        .feature-card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
            color: white;
        }

        .feature-icon.purple {
            background: linear-gradient(135deg, var(--bs-primary) 0%, #4f3aa6 100%);
            color: white;
        }

        .feature-icon.green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .feature-icon.blue {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            color: white;
        }

        .feature-icon.orange {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
        }

        .feature-icon.red {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .feature-description {
            flex: 1;
        }

        .feature-description h5 {
            margin: 0 0 0.25rem 0;
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--bs-body-color);
        }

        .feature-description p {
            margin: 0;
            font-size: 0.875rem;
            color: var(--bs-gray-500);
        }

        .feature-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .feature-actions .btn {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }

        .status-badge {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            margin-top: 0.5rem;
        }

        .status-badge.active {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        .status-badge.pending {
            background: rgba(249, 115, 22, 0.15);
            color: #f97316;
        }

        .status-badge.inactive {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
        }

        .dashboard-section-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 2rem 0 1.5rem 0;
            color: var(--bs-body-color);
            border-bottom: 3px solid var(--bs-primary);
            padding-bottom: 0.5rem;
        }

        /* Card styling consistency */
        .card {
            background: var(--bs-card-bg, var(--bs-gray-200));
            border-color: var(--bs-border-color);
        }

        .card-body {
            color: var(--bs-body-color);
        }

        .card-title {
            color: var(--bs-body-color);
        }

        /* Icon visibility */
        .feature-icon i,
        .stats-card i {
            color: white;
        }

        .feature-card-header i {
            color: white;
        }

        /* SVG Icon styling */
        .feature-icon svg,
        .stats-card svg {
            stroke: white;
            fill: none;
            color: white;
        }

        .feature-icon svg line,
        .feature-icon svg circle,
        .feature-icon svg path,
        .feature-icon svg polygon,
        .feature-icon svg polyline,
        .feature-icon svg rect {
            stroke: white;
            fill: none;
            color: white;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="section page__content">
    <div class="container">
        <div class="row mt-5">
            <!-- Sidebar -->
            <div class="col-md-3">
                <?php echo $__env->make('user.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <!-- Welcome Header -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <i class="ri-dashboard-2-line align-middle fs-16 me-2" style="color: var(--bs-primary);"></i>
                                <h4 class="mb-0" style="display: inline;">Dashboard</h4>
                            </div>
                            <p class="mb-0 text-muted">Welcome back, <strong><?php echo e(Auth::user()->name); ?></strong>!</p>
                        </div>
                    </div>
                </div>

                <!-- Account & Subscription Info -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Account Information</h5>
                        <?php
                            $activeSubscription = auth()->user()->subscriptionsActive();
                            $planName = $activeSubscription && $activeSubscription->plan ? $activeSubscription->plan->name : 'None';
                        ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Current Subscription:</strong> <span class="badge bg-primary"><?php echo e($planName); ?></span></p>
                                <p><strong>Referral Code:</strong> <code><?php echo e(Auth::user()->affiliate_code); ?></code></p>
                                <?php if(Auth::user()->referred_by): ?>
                                    <p><strong>Referred By:</strong> <?php echo e(Auth::user()->referred_by); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Money Earned:</strong> <span class="text-success">$<?php echo e(number_format($totalAmount ?? 0, 2)); ?></span></p>
                                <p><strong>Wallet Balance:</strong> <span class="text-primary">$<?php echo e(number_format(Auth::user()->wallet, 2)); ?></span></p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="<?php echo e(route('user.profile')); ?>" class="btn btn-outline-primary btn-sm">
                                <i class="ri-user-settings-line me-1"></i>Settings
                            </a>
                            <a href="<?php echo e(route('user.earnings')); ?>" class="btn btn-outline-success btn-sm">
                                <i class="ri-money-dollar-circle-line me-1"></i>Withdraw
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <h5 class="dashboard-section-title">Quick Statistics</h5>
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stats-card green">
                            <h6>Job Matches</h6>
                            <p class="number"><?php echo e($jobMatchesCount); ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card blue">
                            <h6>Applications</h6>
                            <p class="number"><?php echo e($totalApplications); ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card orange">
                            <h6>Resumes</h6>
                            <p class="number"><?php echo e($resumeCount); ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card red">
                            <h6>Interview Attempts</h6>
                            <p class="number"><?php echo e($totalInterviewAttempts); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Job Placement Features -->
                <h5 class="dashboard-section-title">Job Placement Hub</h5>

                <!-- Placement Profile Section -->
                <div class="feature-card">
                    <div class="feature-card-header">
                        <div class="feature-icon purple">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 28px; height: 28px;">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                            </svg>
                        </div>
                        <div class="feature-description">
                            <h5>Placement Profile</h5>
                            <p><?php echo e($placementProfileExists ? 'Your job matching profile is active' : 'Create your job matching profile to get started'); ?></p>
                        </div>
                    </div>
                    <?php if($placementProfileExists): ?>
                        <div class="status-badge active">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; vertical-align: middle; display: inline-block; margin-right: 4px;"><polyline points="20 6 9 17 4 12\"></polyline></svg>Active
                        </div>
                    <?php else: ?>
                        <div class="status-badge inactive">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; vertical-align: middle; display: inline-block; margin-right: 4px;\"><circle cx=\"12\" cy=\"12\" r=\"10\"></circle><line x1=\"12\" y1=\"8\" x2=\"12\" y2=\"16\"></line></svg>Not Started
                        </div>
                    <?php endif; ?>
                    <div class="feature-actions mt-3">
                        <?php if($placementProfileExists): ?>
                            <a href="<?php echo e(route('placement.jobs.index')); ?>" class="btn btn-primary btn-sm">
                                <i class="ri-eye-line me-1"></i>View Job Matches
                            </a>
                        <?php else: ?>
                            <a href="<?php echo e(route('placement.start')); ?>" class="btn btn-primary btn-sm">
                                <i class="ri-add-line me-1"></i>Create Profile
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Job Matches Section -->
                <div class="feature-card">
                    <div class="feature-card-header">
                        <div class="feature-icon green">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 28px; height: 28px;">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </div>
                        <div class="feature-description">
                            <h5>Job Matches</h5>
                            <p><?php echo e($jobMatchesCount); ?> matching jobs found for your profile</p>
                        </div>
                    </div>
                    <?php if($jobMatchesCount > 0): ?>
                        <div class="status-badge active">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 14px; height: 14px; vertical-align: middle; display: inline-block; margin-right: 4px;"><circle cx="12" cy="12" r="10"></circle></svg><?php echo e($jobMatchesCount); ?> Match<?php echo e($jobMatchesCount != 1 ? 'es' : ''); ?>

                        </div>
                        <div class="mt-3">
                            <small class="text-muted d-block mb-2">Recent matches:</small>
                            <?php $__currentLoopData = $pendingJobMatches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <small class="d-block text-truncate mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#10b981" style="width: 12px; height: 12px; vertical-align: middle; display: inline-block; margin-right: 4px;"><circle cx="12" cy="12" r="8"></circle></svg>
                                    <?php echo e($match->job_title); ?> at <?php echo e($match->company_name); ?>

                                </small>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="feature-actions mt-3">
                            <a href="<?php echo e(route('placement.jobs.index')); ?>" class="btn btn-primary btn-sm">
                                <i class="ri-search-line me-1"></i>View All Matches
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="status-badge pending">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; vertical-align: middle; display: inline-block; margin-right: 4px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>No matches yet
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Application Tracker Section -->
                <div class="feature-card">
                    <div class="feature-card-header">
                        <div class="feature-icon blue">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 28px; height: 28px;">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="12" y1="11" x2="12" y2="17"></line>
                                <line x1="9" y1="14" x2="15" y2="14"></line>
                            </svg>
                        </div>
                        <div class="feature-description">
                            <h5>Application Tracker</h5>
                            <p>Track <?php echo e($totalApplications); ?> application<?php echo e($totalApplications != 1 ? 's' : ''); ?> across all companies</p>
                        </div>
                    </div>
                    <?php if($totalApplications > 0): ?>
                        <div class="row mt-3">
                            <div class="col-6">
                                <small class="text-muted">In Progress:</small>
                                <p class="mb-0"><strong class="text-warning"><?php echo e($applicationsInProgress); ?></strong></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Offered:</small>
                                <p class="mb-0"><strong class="text-success"><?php echo e($applicationsOffered); ?></strong></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No applications yet. Start applying to jobs!</p>
                    <?php endif; ?>
                    <div class="feature-actions mt-3">
                        <a href="<?php echo e(route('placement.applications.index')); ?>" class="btn btn-primary btn-sm">
                            <i class="ri-list-check-2 me-1"></i>View Tracker
                        </a>
                    </div>
                </div>

                <!-- Interview & Resume Features -->
                <h5 class="dashboard-section-title">Professional Development</h5>

                <!-- Mock Interview Section -->
                <div class="feature-card">
                    <div class="feature-card-header">
                        <div class="feature-icon red">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 28px; height: 28px;">
                                <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path>
                                <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
                                <line x1="12" y1="19" x2="12" y2="23"></line>
                                <line x1="8" y1="23" x2="16" y2="23"></line>
                            </svg>
                        </div>
                        <div class="feature-description">
                            <h5>Mock Interview Practice</h5>
                            <p>Practice interviews and get AI feedback (<?php echo e($totalInterviewAttempts); ?> attempt<?php echo e($totalInterviewAttempts != 1 ? 's' : ''); ?>)</p>
                        </div>
                    </div>
                    <?php if($totalInterviewAttempts > 0): ?>
                        <div class="status-badge active">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; vertical-align: middle; display: inline-block; margin-right: 4px;"><polyline points="20 6 9 17 4 12"></polyline></svg><?php echo e($totalInterviewAttempts); ?> Completed
                        </div>
                        <?php if($interviewAttempts->count() > 0): ?>
                            <div class="mt-3">
                                <small class="text-muted d-block mb-2">Recent attempts:</small>
                                <?php $__currentLoopData = $interviewAttempts->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attempt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <small class="d-block mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; vertical-align: middle; display: inline-block; margin-right: 4px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        <?php echo e($attempt->created_at->format('M d, Y')); ?>

                                    </small>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted mb-2">Start practicing with AI-powered mock interviews!</p>
                    <?php endif; ?>
                    <div class="feature-actions mt-3">
                        <a href="<?php echo e(url('/mock/add-job-details')); ?>" class="btn btn-primary btn-sm">
                            <i class="ri-play-circle-line me-1"></i>Start Interview
                        </a>
                    </div>
                </div>

                <!-- Resume Builder Section -->
                <div class="feature-card">
                    <div class="feature-card-header">
                        <div class="feature-icon orange">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 28px; height: 28px;">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="9" y1="11" x2="15" y2="11"></line>
                                <line x1="9" y1="15" x2="15" y2="15"></line>
                            </svg>
                        </div>
                        <div class="feature-description">
                            <h5>Resume Builder</h5>
                            <p>Create and manage professional resumes (<?php echo e($resumeCount); ?> resume<?php echo e($resumeCount != 1 ? 's' : ''); ?>)</p>
                        </div>
                    </div>
                    <?php if($resumeCount > 0): ?>
                        <div class="status-badge active">
                            <i class="ri-check-line"></i> <?php echo e($resumeCount); ?> Resume<?php echo e($resumeCount != 1 ? 's' : ''); ?>

                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-2">Build your first resume now!</p>
                    <?php endif; ?>
                    <div class="feature-actions mt-3">
                        <a href="<?php echo e(route('resume-builder.form')); ?>" class="btn btn-primary btn-sm">
                            <i class="ri-file-add-line me-1"></i>Go to Resume Builder
                        </a>
                    </div>
                </div>

                <!-- Presentation Practice Section -->
                <div class="feature-card">
                    <div class="feature-card-header">
                        <div class="feature-icon green">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 28px; height: 28px;">
                                <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                            </svg>
                        </div>
                        <div class="feature-description">
                            <h5>Presentation Practice</h5>
                            <p>Practice your presentation skills with AI feedback</p>
                        </div>
                    </div>
                    <p class="text-muted mb-2">Record presentations and receive detailed analysis</p>
                    <div class="feature-actions mt-3">
                        <a href="<?php echo e(route('presentation.create')); ?>" class="btn btn-primary btn-sm">
                            <i class="ri-video-record-line me-1"></i>Start Practice
                        </a>
                    </div>
                </div>

                <!-- Referral Program -->
                <?php if($gs->is_affilate == 1): ?>
                    <h5 class="dashboard-section-title">Referral Program</h5>
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="<?php echo e(URL::asset('assets/images/giftbox.png')); ?>" alt="" style="width: 50px;">
                            <h5 class="mt-3">Promote <?php echo e($gs->name); ?></h5>
                            <p class="text-muted">Refer friends and earn commissions on their purchases</p>

                            <div class="col-md-12 position-relative copy-text-in mb-3">
                                <div class="input-group has-validation">
                                    <input type="text"
                                           class="form-control link"
                                           aria-describedby=""
                                           value="<?php echo e(route('front.index')); ?>?reff=<?php echo e(Auth::user()->affiliate_code); ?>"
                                           readonly>
                                    <button class="input-group-text1 copy-text btn btn-outline-secondary" id="">
                                        <i class="ri-file-copy-line"></i>
                                    </button>
                                    <div class="valid-tooltip position-top">
                                        Copied!
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="<?php echo e(route('user.earnings')); ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="ri-bar-chart-line me-1"></i>View Earnings
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script>

<script type="text/javascript">
    let copyText = document.querySelector(".copy-text-in");
    if (copyText) {
        copyText.querySelector(".copy-text").addEventListener("click", function() {
            let input = copyText.querySelector("input.link");
            input.select();
            document.execCommand("copy");
            copyText.classList.add("tooltip-active");
            window.getSelection().removeAllRanges();
            setTimeout(function() {
                copyText.classList.remove("tooltip-active");
            }, 1500);
        });
    }
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('front.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Herd-Projects\hired-hero\resources\views/user/dashboard.blade.php ENDPATH**/ ?>