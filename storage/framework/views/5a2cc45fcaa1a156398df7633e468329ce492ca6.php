
<?php $__env->startSection('title', 'Your Job Matches'); ?>
<?php $__env->startSection('content'); ?>

<div class="job-matches-container">
    <!-- Sidebar Filters -->
    <aside class="filters-sidebar">
        <div class="filters-header">
            <i class="fas fa-sliders-h"></i>
            <h3>Filters</h3>
        </div>

        <!-- Job Source -->
        <div class="filter-section">
            <label class="filter-label">Job Source</label>
            <select name="job_source" id="job_source" class="filter-select">
                <option value="">All Sources</option>
                <option value="workday">Source 1</option>
                <option value="wellfound">Source 2</option>
                <!-- <option value="glassdoor">Source 3</option>
                <option value="workopolis">Source 4 </option> -->
            </select>
        </div>

        <!-- Match Score -->
        <div class="filter-section">
            <label class="filter-label">Match Score</label>
            <div class="range-slider-container">
                <input type="range"
                    name="min_match_score"
                    id="min_match_score"
                    min="0"
                    max="100"
                    value="<?php echo e(request('min_match_score', 0)); ?>"
                    class="range-slider">
                <div class="range-value">
                    <span class="range-label">Minimum</span>
                    <span class="range-number"><?php echo e(request('min_match_score', 0)); ?>%</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="filter-actions">
            <button type="button" class="btn-apply-filters">
                <i class="fas fa-search"></i>
                <?php echo e('Apply Filters'); ?>

            </button>
            <button type="button" class="btn-reset-filters">
                Reset Filters
            </button>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="job-matches-main">
        <!-- Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div>
                    <h1 class="page-title">Your Job Matches</h1>
                    <p class="page-subtitle">Find your perfect career opportunity</p>
                </div>
            </div>
            <a href="<?php echo e(route('user.dashboard')); ?>" class="btn-back-dashboard">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>
        <!-- Job Listings -->
        <div class="job-listings">
            <?php
            $user = auth()->user();
            $activeSubscription = $user ? $user->getActiveSubscription() : null;
            $hasAiTailoredResumeFeature = false;
            $hasAiTailoredCoverFeature = false;
            $aiTailoredCoverLimit = 0;

            if ($activeSubscription && $activeSubscription->isActive() && $activeSubscription->plan) {
            $accessSection = $activeSubscription->plan->access_section;

            // Handle both array and JSON string formats
            if (is_array($accessSection) && isset($accessSection['jobMatches']['ai_tailored_resume'])) {
            $hasAiTailoredResumeFeature = (bool) $accessSection['jobMatches']['ai_tailored_resume'];
            } elseif (is_string($accessSection)) {
            $decoded = json_decode($accessSection, true);
            if (isset($decoded['jobMatches']['ai_tailored_resume'])) {
            $hasAiTailoredResumeFeature = (bool) $decoded['jobMatches']['ai_tailored_resume'];
            }
            }

            // Check for cover letter feature
            if (is_array($accessSection) && isset($accessSection['jobMatches']['ai_tailored_cover'])) {
            $coverFeature = $accessSection['jobMatches']['ai_tailored_cover'];
            $hasAiTailoredCoverFeature = $coverFeature === true || $coverFeature === 'unlimited' || is_numeric($coverFeature);
            if (is_numeric($coverFeature)) {
            $aiTailoredCoverLimit = (int) $coverFeature;
            }
            } elseif (is_string($accessSection)) {
            $decoded = json_decode($accessSection, true);
            if (isset($decoded['jobMatches']['ai_tailored_cover'])) {
            $coverFeature = $decoded['jobMatches']['ai_tailored_cover'];
            $hasAiTailoredCoverFeature = $coverFeature === true || $coverFeature === 'unlimited' || is_numeric($coverFeature);
            if (is_numeric($coverFeature)) {
            $aiTailoredCoverLimit = (int) $coverFeature;
            }
            }
            }
            }
            ?>
            <?php if($jobMatches->count() > 0): ?>
            <?php $__currentLoopData = $jobMatches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="job-card">
                <div class="job-card-header">
                    <div class="job-match-badge">
                        <div class="match-score"><?php echo e($job->match_score); ?></div>
                        <div class="match-label">MATCH</div>
                    </div>

                    <div class="company-avatar">
                        <?php echo e(strtoupper(substr($job->company_name, 0, 1))); ?>

                    </div>

                    <div class="job-main-info">
                        <h3 class="job-title"><?php echo e($job->job_title); ?></h3>
                        <div class="company-info">
                            <span class="company-name"><?php echo e($job->company_name); ?></span>
                            <span class="job-source-badge"><?php echo e(strtoupper($job->source)); ?></span>
                        </div>
                    </div>
                </div>

                <div class="job-card-body">
                    <div class="job-meta">
                        <span class="meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo e($job->location ?? 'Remote'); ?>

                        </span>
                        <span class="meta-item">
                            <i class="far fa-calendar"></i>
                            <?php echo e($job->posted_date); ?>

                        </span>
                        <?php if($job->job_type): ?>
                        <span class="meta-item">
                            <i class="fas fa-laptop-house"></i>
                            <?php echo e($job->job_type); ?>

                        </span>
                        <?php endif; ?>
                    </div>

                    <?php if($job->matched_skills): ?>
                    <div class="matched-skills">
                        <span class="skills-label">Matched Skills</span>
                        <div class="skills-tags">
                            <?php $__currentLoopData = $job->matched_skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="skill-tag"><?php echo e($skill); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="job-card-footer">
                    <a href="<?php echo e($job->job_url); ?>" target="_blank" class="btn-apply pointer-events-auto">
                        <i class="fas fa-external-link-alt"></i>
                        Apply on Source
                    </a>
                    <?php
                    $source = strtolower($job->source ?? '');
                    $isSupportedSource = in_array($source, ['workday', 'wellfound']);
                    ?>

                    <?php if($isSupportedSource): ?>
                    <button
                        class="btn-apply pointer-events-auto tailor-resume-btn"
                        style="background-color: #10b981; border-color: #10b981;"
                        data-job-id="<?php echo e($job->id); ?>"
                        data-job-title="<?php echo e(htmlspecialchars($job->job_title ?? '', ENT_QUOTES, 'UTF-8')); ?>"
                        data-job-description="<?php echo e(htmlspecialchars($job->job_description ?? '', ENT_QUOTES, 'UTF-8')); ?>"
                        type="button">
                        Tailor My Resume to This Job ⭐
                    </button>

                    <!-- Cover Letter Button -->
                    <button
                        class="btn-apply pointer-events-auto need-cover-letter-btn"
                        style="background-color: #3b82f6; border-color: #3b82f6;"
                        data-job-id="<?php echo e($job->id); ?>"
                        data-job-title="<?php echo e(htmlspecialchars($job->job_title ?? '', ENT_QUOTES, 'UTF-8')); ?>"
                        data-job-description="<?php echo e(htmlspecialchars($job->job_description ?? '', ENT_QUOTES, 'UTF-8')); ?>"
                        data-company-name="<?php echo e(htmlspecialchars($job->company_name ?? '', ENT_QUOTES, 'UTF-8')); ?>"
                        type="button">
                        <i class="fas fa-file-alt"></i>
                        Need Cover Letter?
                    </button>
                    <?php endif; ?>
                    <button type="button"
                        class="btn-remove-job"
                        title="Remove from matches"
                        onclick="removeJobMatch(<?php echo e($job->id); ?>, event); event.preventDefault(); event.stopPropagation();">
                        <i class="fas fa-trash-alt"></i>
                        Remove
                    </button>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <!-- Pagination -->
            <?php if($jobMatches->hasPages()): ?>
            <div class="pagination-wrapper">
                <?php echo e($jobMatches->links()); ?>

            </div>
            <?php endif; ?>
            <?php else: ?>
            <!-- No Results -->
            <div class="no-results">
                <div class="no-results-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="no-results-title">No job matches found</h3>
                <p class="no-results-text">Try adjusting your filters to find more opportunities.</p>
                <button class="btn-update-preferences">
                    Update Your Preferences
                </button>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<style>
    /* Color Variables - Dark Professional Theme */
    :root {
        --bg-primary: #1a1f2e;
        --bg-secondary: #242936;
        --bg-tertiary: #2d3444;
        --bg-hover: #353d4f;

        --text-primary: #e5e7eb;
        --text-secondary: #9ca3af;
        --text-muted: #6b7280;

        --border-color: #374151;
        --border-light: #2d3748;

        --accent-primary: #4f5b73;
        --accent-secondary: #5a6679;

        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;

        --match-high: #e86c6c;
        --match-bg: rgba(232, 108, 108, 0.15);
    }

    * {
        box-sizing: border-box;
    }

    body {
        background-color: var(--bg-primary);
        color: var(--text-primary);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    }

    .job-matches-container {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 2rem;
        max-width: 1600px;
        margin: auto;
        margin-top: 4rem;
        padding: 2rem;
    }

    /* Sidebar Filters */
    .filters-sidebar {
        background-color: var(--bg-secondary);
        border-radius: 12px;
        padding: 1.5rem;
        height: fit-content;
        position: sticky;
        top: 2rem;
        border: 1px solid var(--border-color);
    }

    .filters-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-light);
    }

    .filters-header i {
        color: var(--accent-primary);
        font-size: 1.25rem;
    }

    .filters-header h3 {
        margin: 0;
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .filter-section {
        margin-bottom: 1.5rem;
    }

    .filter-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .filter-select {
        width: 100%;
        padding: 0.625rem 0.875rem;
        background-color: var(--bg-tertiary);
        border: 1px solid var(--border-color);
        border-radius: 6px;
        color: var(--text-primary);
        font-size: 0.9375rem;
        transition: all 0.2s;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--accent-secondary);
        background-color: var(--bg-hover);
    }

    .range-slider-container {
        background-color: var(--bg-tertiary);
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }

    .range-slider {
        width: 100%;
        height: 6px;
        border-radius: 3px;
        background: var(--border-color);
        outline: none;
        margin-bottom: 0.75rem;
    }

    .range-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: var(--accent-primary);
        cursor: pointer;
    }

    .range-slider::-moz-range-thumb {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: var(--accent-primary);
        cursor: pointer;
        border: none;
    }

    .range-value {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .range-label {
        font-size: 0.8125rem;
        color: var(--text-muted);
    }

    .range-number {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
        background-color: var(--bg-secondary);
        padding: 0.25rem 0.625rem;
        border-radius: 4px;
    }

    .filter-actions {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-light);
    }

    .btn-apply-filters {
        width: 100%;
        padding: 0.625rem 1rem;
        background-color: var(--accent-primary);
        color: var(--text-primary);
        border: none;
        border-radius: 6px;
        font-size: 0.9375rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-apply-filters:hover {
        background-color: var(--accent-secondary);
    }

    .btn-reset-filters {
        width: 100%;
        padding: 0.625rem 1rem;
        background-color: transparent;
        color: var(--text-secondary);
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-reset-filters:hover {
        background-color: var(--bg-tertiary);
        color: var(--text-primary);
    }

    /* Main Content */
    .job-matches-main {
        min-height: 100vh;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border-light);
        gap: 1.5rem;
    }

    .header-content {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .header-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--text-primary);
    }

    .page-title {
        font-size: 1.875rem;
        font-weight: 700;
        margin: 0 0 0.25rem 0;
        color: var(--text-primary);
    }

    .page-subtitle {
        font-size: 0.9375rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .jobs-count-badge {
        display: flex;
        align-items: center;
        gap: 0.875rem;
        background-color: var(--bg-secondary);
        padding: 0.875rem 1.25rem;
        border-radius: 10px;
        border: 1px solid var(--border-color);
    }

    .jobs-count-badge i {
        font-size: 1.5rem;
        color: var(--accent-primary);
    }

    .count-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
    }

    .count-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-top: 0.25rem;
    }

    /* Search Bar */
    .search-bar-container {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .search-input-wrapper {
        flex: 1;
        position: relative;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 1rem;
    }

    .search-input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 2.75rem;
        background-color: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-primary);
        font-size: 0.9375rem;
        transition: all 0.2s;
    }

    .search-input::placeholder {
        color: var(--text-muted);
    }

    .search-input:focus {
        outline: none;
        border-color: var(--accent-secondary);
        background-color: var(--bg-tertiary);
    }

    .btn-search {
        padding: 0.875rem 1.5rem;
        background-color: var(--accent-primary);
        color: var(--text-primary);
        border: none;
        border-radius: 8px;
        font-size: 0.9375rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-search:hover {
        background-color: var(--accent-secondary);
    }

    /* Back to Dashboard Button */
    .btn-back-dashboard {
        padding: 0.75rem 1.5rem;
        background-color: var(--accent-primary);
        color: var(--text-primary);
        border: none;
        border-radius: 8px;
        font-size: 0.9375rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-back-dashboard:hover {
        background-color: var(--accent-secondary);
        color: var(--text-primary);
        text-decoration: none;
    }

    .btn-back-dashboard i {
        font-size: 0.75rem;
    }

    .job-listings {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .job-card {
        background-color: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        transition: all 0.2s;
    }

    .job-card:hover {
        border-color: var(--accent-primary);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .job-card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
        border-bottom: 1px solid var(--border-light);
    }

    .job-match-badge {
        background-color: var(--match-bg);
        border: 1px solid var(--match-high);
        border-radius: 8px;
        padding: 0.625rem 0.875rem;
        text-align: center;
        min-width: 70px;
    }

    .match-score {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--match-high);
        line-height: 1;
    }

    .match-label {
        font-size: 0.625rem;
        font-weight: 600;
        color: var(--match-high);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-top: 0.25rem;
    }

    .company-avatar {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .job-main-info {
        flex: 1;
    }

    .job-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 0.375rem 0;
    }

    .company-info {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        flex-wrap: wrap;
    }

    .company-name {
        font-size: 0.9375rem;
        color: var(--text-secondary);
    }

    .job-source-badge {
        font-size: 0.6875rem;
        font-weight: 600;
        color: var(--accent-primary);
        background-color: var(--bg-tertiary);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }



    .job-card-body {
        padding: 1.25rem;
    }

    .job-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.25rem;
        margin-bottom: 1rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .meta-item i {
        color: var(--text-muted);
        font-size: 0.875rem;
    }

    .matched-skills {
        margin-top: 1rem;
    }

    .skills-label {
        display: block;
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .skills-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .skill-tag {
        font-size: 0.8125rem;
        color: var(--text-primary);
        background-color: var(--bg-tertiary);
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        border: 1px solid var(--border-color);
    }

    .job-card-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid var(--border-light);
        display: flex;
        justify-content: flex-start;
        gap: 0.75rem;
    }

    .btn-apply {
        padding: 0.625rem 1.25rem;
        background-color: var(--accent-primary);
        color: var(--text-primary);
        border: none;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-apply:hover {
        background-color: var(--accent-secondary);
    }

    .btn-apply i {
        font-size: 0.75rem;
    }

    .btn-remove-job {
        padding: 0.625rem 1.25rem;
        background-color: transparent;
        color: var(--danger);
        border: 1px solid var(--danger);
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-remove-job:hover {
        background-color: #dc3545;
        color: white;
    }

    .btn-remove-job i {
        font-size: 0.75rem;
    }

    /* No Results */
    .no-results {
        text-align: center;
        padding: 4rem 2rem;
        background-color: var(--bg-secondary);
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .no-results-icon {
        width: 80px;
        height: 80px;
        background-color: var(--bg-tertiary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        color: var(--text-muted);
    }

    .no-results-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .no-results-text {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0 0 1.5rem 0;
    }

    .btn-update-preferences {
        padding: 0.75rem 1.5rem;
        background-color: var(--accent-primary);
        color: var(--text-primary);
        border: none;
        border-radius: 8px;
        font-size: 0.9375rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-update-preferences:hover {
        background-color: var(--accent-secondary);
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .job-matches-container {
            grid-template-columns: 1fr;
        }

        .filters-sidebar {
            position: relative;
            top: 0;
        }
    }

    @media (max-width: 768px) {
        .job-matches-container {
            padding: 1rem;
            gap: 1.5rem;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .btn-back-dashboard {
            width: 100%;
            justify-content: center;
        }

        .search-bar-container {
            flex-direction: column;
        }

        .btn-search {
            width: 100%;
            justify-content: center;
        }

        .job-card-header {
            flex-wrap: wrap;
        }

        .job-meta {
            flex-direction: column;
            gap: 0.75rem;
        }
    }
</style>

<!-- Upgrade Required Modal -->
<div class="modal fade" id="upgradeRequiredModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-gradient border-0">
                <h5 class="modal-title text-white">
                    <i class="fas fa-star me-2"></i>Upgrade Your Plan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3" style="font-size: 3rem; color: #f59e0b;">
                    <i class="fas fa-lock"></i>
                </div>
                <h5 class="mb-2">Premium Feature</h5>
                <p class="text-muted mb-4">
                    The Resume Tailoring feature is only available with a premium subscription.
                    Upgrade your plan to tailor your resume for each job and increase your chances of getting hired!
                </p>
                <div class="alert alert-info mb-4" role="alert">
                    <strong>✨ With Premium:</strong>
                    <ul class="mt-2 text-start" style="font-size: 0.9rem;">
                        <li>Tailor resumes to specific jobs</li>
                        <li>Optimize for ATS systems</li>
                        <li>Multiple resume templates</li>
                        <li>AI-powered resume enhancement</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer border-0 py-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="<?php echo e(route('front.pricing')); ?>" class="btn btn-success">
                    <i class="fas fa-crown me-2"></i>Upgrade Now
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Resume Generator Modal -->
<div class="modal fade" id="resumeGeneratorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header bg-gradient border-0">
                <h5 class="modal-title text-white">Generate Tailored Resume</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Step 1: Template Selection -->
                <div id="resumeStep1">
                    <h6 class="mb-3">Choose a Resume Template</h6>
                    <div class="template-grid">
                        <div class="template-card" onclick="selectTemplate(this, 'modern')">
                            <div class="template-preview modern-preview">
                                <div class="template-header"></div>
                                <div class="template-line short"></div>
                                <div class="template-line"></div>
                                <div class="template-line short"></div>
                            </div>
                            <div class="template-name mt-2">Modern</div>
                        </div>
                        <div class="template-card" onclick="selectTemplate(this, 'professional')">
                            <div class="template-preview professional-preview">
                                <div class="template-header"></div>
                                <div class="template-line short"></div>
                                <div class="template-line"></div>
                                <div class="template-line short"></div>
                            </div>
                            <div class="template-name mt-2">Professional</div>
                        </div>
                        <div class="template-card" onclick="selectTemplate(this, 'minimalist')">
                            <div class="template-preview minimalist-preview">
                                <div class="template-header"></div>
                                <div class="template-line short"></div>
                                <div class="template-line"></div>
                                <div class="template-line short"></div>
                            </div>
                            <div class="template-name mt-2">Minimalist</div>
                        </div>
                        <div class="template-card" onclick="selectTemplate(this, 'classic')">
                            <div class="template-preview classic-preview">
                                <div class="template-header"></div>
                                <div class="template-line short"></div>
                                <div class="template-line"></div>
                                <div class="template-line short"></div>
                            </div>
                            <div class="template-name mt-2">Classic ⭐</div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Review -->
                <div id="resumeStep2" style="display: none;">
                    <div class="resume-preview-card">
                        <h6 class="mb-3" style="color: #1a1a1a;">Review Your Resume</h6>
                        <p class="mb-2" style="color: #1a1a1a;"><strong>Job Title:</strong> <span id="previewJobTitle" style="color: #2c3e50; font-weight: 500;"></span></p>
                        <p class="mb-3" style="color: #1a1a1a;"><strong>Template:</strong> <span id="previewTemplate" style="color: #2c3e50; font-weight: 500;"></span></p>
                        <div class="alert alert-info" role="alert">
                            <strong>💡 ATS Optimization:</strong> Your resume will be formatted to pass applicant tracking systems and match job keywords.
                        </div>
                        <div class="alert alert-warning" role="alert">
                            <strong>⏱️ Note:</strong> Resume generation takes 15-30 seconds. Please wait for completion.
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="resumeLoading" style="display: none;" class="text-center py-5">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted">Generating your tailored resume...</p>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                    </div>
                </div>

                <!-- Success State -->
                <div id="resumeSuccess" style="display: none;" class="text-center py-5">
                    <div class="mb-3" style="font-size: 3rem; color: #10b981;">✓</div>
                    <h5 class="text-success mb-2">Resume Ready!</h5>
                    <p class="text-muted mb-4">Your tailored resume has been generated successfully.</p>
                </div>
            </div>
            <div class="modal-footer border-0 py-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="resetResumeModal()">Close</button>
                <button type="button" id="resumeActionBtn" class="btn btn-primary" onclick="nextResumeStep()">Next: Review Details</button>
                <a id="resumeDownloadBtn" href="#" class="btn btn-success" style="display: none;">
                    <i class="fas fa-download"></i> Download Resume
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Styles -->
<style>
    .template-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .template-card {
        border: 2px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        position: relative;
    }

    .template-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .template-card.selected {
        border-color: #3b82f6;
        background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }

    .template-card.selected::after {
        content: '\2713';
        position: absolute;
        top: -8px;
        right: -8px;
        width: 28px;
        height: 28px;
        background: #3b82f6;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .template-card.selected .template-name {
        color: #1f2937;
    }

    .template-preview {
        height: 100px;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.75rem;
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .template-header {
        height: 12px;
        background: #1f2937;
        border-radius: 2px;
        width: 60%;
    }

    .template-line {
        height: 8px;
        background: #cbd5e0;
        border-radius: 1px;
    }

    .template-line.short {
        width: 70%;
    }

    .modern-preview {
        background: linear-gradient(135deg, #f3f4f6 0%, #ffffff 100%);
    }

    .professional-preview {
        border-left: 4px solid #1f2937;
    }

    .minimalist-preview {
        background: #ffffff;
    }

    .classic-preview {
        background: #f9fafb;
        border-left: 3px solid #9333ea;
    }

    .template-name {
        font-weight: 600;
        color: #ffffff;
        font-size: 0.9rem;
    }

    .resume-preview-card {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        color: #1a1a1a;
    }

    .resume-preview-card h6 {
        color: #1a1a1a;
        font-weight: 600;
    }

    .resume-preview-card p {
        color: #2c3e50;
        margin-bottom: 0.75rem;
    }

    #previewJobTitle,
    #previewTemplate {
        color: #2c3e50 !important;
        font-weight: 500;
    }

    .bg-gradient {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    /* Upgrade Modal Styles */
    #upgradeRequiredModal .modal-content {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
    }

    #upgradeRequiredModal .modal-header {
        border-bottom: 1px solid var(--border-color);
    }

    #upgradeRequiredModal .modal-body {
        color: var(--text-primary);
    }

    #upgradeRequiredModal .modal-body .text-muted {
        color: var(--text-secondary) !important;
    }

    #upgradeRequiredModal .modal-body .alert {
        background-color: var(--bg-tertiary);
        border-color: var(--border-color);
        color: var(--text-secondary);
    }

    #upgradeRequiredModal .modal-body .alert strong {
        color: var(--text-primary);
    }

    #upgradeRequiredModal .modal-body .alert ul {
        color: var(--text-secondary);
    }

    #upgradeRequiredModal .modal-footer {
        border-top: 1px solid var(--border-color);
    }
</style>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    // Store subscription and feature flags from server
    const hasAiTailoredResumeFeature = <?php echo e($hasAiTailoredResumeFeature ? 'true' : 'false'); ?>;
    const hasAiTailoredCoverFeature = <?php echo e($hasAiTailoredCoverFeature ? 'true' : 'false'); ?>;
    const aiTailoredCoverLimit = <?php echo e($aiTailoredCoverLimit); ?>;


    document.addEventListener('DOMContentLoaded', function() {
        // Range slider update
        const rangeSlider = document.getElementById('min_match_score');
        const rangeNumber = document.querySelector('.range-number');

        if (rangeSlider) {
            rangeSlider.addEventListener('input', function() {
                rangeNumber.textContent = this.value + '%';
            });
        }

        // Apply filters AJAX
        const applyFiltersBtn = document.querySelector('.btn-apply-filters');
        if (applyFiltersBtn) {
            applyFiltersBtn.addEventListener('click', function() {
                applyFiltersAJAX();
            });
        }

        // Reset filters AJAX
        const resetFiltersBtn = document.querySelector('.btn-reset-filters');
        if (resetFiltersBtn) {
            resetFiltersBtn.addEventListener('click', function() {
                resetFiltersAJAX();
            });
        }

        // Handle tailor resume button clicks using event delegation
        document.addEventListener('click', function(e) {
            const button = e.target.closest('.tailor-resume-btn');
            if (button) {
                e.preventDefault();
                e.stopPropagation();
                handleTailorResumeClick(button);
            }

            // Handle cover letter button clicks
            const coverLetterBtn = e.target.closest('.need-cover-letter-btn');
            if (coverLetterBtn) {
                e.preventDefault();
                e.stopPropagation();
                handleNeedCoverLetterClick(coverLetterBtn);
            }
        });
    });

    // AJAX function to remove job
    function removeJobMatch(jobId, event) {
        event.preventDefault();
        if (!confirm('Are you sure you want to remove this job match?')) {
            return;
        }

        const jobCard = event.target.closest('.job-card');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
            document.querySelector('input[name="_token"]')?.value;

        fetch(`/placement/job-matches/${jobId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                return response.json();
            })
            .then(data => {
                // Fade out and remove the card
                jobCard.style.opacity = '0';
                setTimeout(() => {
                    jobCard.remove();

                    // Check if no more jobs and show no results message
                    const jobListings = document.querySelector('.job-listings');
                    if (jobListings && jobListings.querySelectorAll('.job-card').length === 0) {
                        location.reload(); // Refresh to show "No results" message
                    }
                }, 300);
            })
            .catch(error => {
                console.error('Error removing job:', error);
                alert('Failed to remove job. Please try again.');
            });
    }

    // AJAX function to apply filters
    function applyFiltersAJAX() {
        const jobSource = document.getElementById('job_source')?.value || '';
        const minScore = document.getElementById('min_match_score')?.value || '0';

        const params = new URLSearchParams({
            job_source: jobSource,
            min_match_score: minScore
        });

        fetch(`/placement/jobs/filter?${params}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                return response.json();
            })
            .then(data => {
                updateJobListings(data.jobs);
            })
            .catch(error => {
                console.error('Error applying filters:', error);
                alert('Failed to apply filters. Please try again.');
            });
    }

    // AJAX function to reset filters
    function resetFiltersAJAX() {
        // Reset all filter values
        if (document.getElementById('job_source')) {
            document.getElementById('job_source').value = '';
        }
        if (document.getElementById('min_match_score')) {
            document.getElementById('min_match_score').value = '0';
            document.querySelector('.range-number').textContent = '0%';
        }

        // Reload the page or fetch all jobs
        location.reload();
    }

    // Helper function to update job listings
    function updateJobListings(jobs) {
        const jobListings = document.querySelector('.job-listings');
        if (!jobListings) return;

        if (jobs.length === 0) {
            jobListings.innerHTML = `
                <div class="no-results">
                    <div class="no-results-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="no-results-title">No job matches found</h3>
                    <p class="no-results-text">Try adjusting your filters to find more opportunities.</p>
                    <button class="btn-update-preferences" onclick="resetFiltersAJAX()">
                        Update Your Preferences
                    </button>
                </div>
            `;
            return;
        }

        let html = '';
        jobs.forEach(job => {
            html += `
                <div class="job-card">
                    <div class="job-card-header">
                        <div class="job-match-badge">
                            <div class="match-score">${job.match_score}</div>
                            <div class="match-label">MATCH</div>
                        </div>

                        <div class="company-avatar">
                            ${job.company_name.charAt(0).toUpperCase()}
                        </div>

                        <div class="job-main-info">
                            <h3 class="job-title">${job.job_title}</h3>
                            <div class="company-info">
                                <span class="company-name">${job.company_name}</span>
                                <span class="job-source-badge">${job.source.toUpperCase()}</span>
                            </div>
                        </div>
                    </div>

                    <div class="job-card-body">
                        <div class="job-meta">
                            <span class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                ${job.location || 'Remote'}
                            </span>
                            <span class="meta-item">
                                <i class="far fa-calendar"></i>
                                ${job.posted_date}
                            </span>
                            ${job.job_type ? `<span class="meta-item">
                                <i class="fas fa-laptop-house"></i>
                                ${job.job_type}
                            </span>` : ''}
                        </div>

                        ${job.matched_skills && job.matched_skills.length > 0 ? `
                            <div class="matched-skills">
                                <span class="skills-label">Matched Skills</span>
                                <div class="skills-tags">
                                    ${job.matched_skills.map(skill => `<span class="skill-tag">${skill}</span>`).join('')}
                                </div>
                            </div>
                        ` : ''}
                    </div>

                    <div class="job-card-footer">
                        <a href="${job.job_url}" target="_blank" class="btn-apply pointer-events-auto">
                            <i class="fas fa-external-link-alt"></i>
                            Apply on Source
                        </a>
                        ${(job.source && (job.source.toLowerCase() === 'workday' || job.source.toLowerCase() === 'wellfound')) ? `
                        <button class="btn-apply pointer-events-auto tailor-resume-btn"
                            style="background-color: #10b981; border-color: #10b981;"
                            type="button"
                            data-job-id="${job.id}"
                            data-job-title="${(job.job_title || '').replace(/"/g, '&quot;')}"
                            data-job-description="${(job.job_description || '').replace(/"/g, '&quot;')}">
                            Tailor My Resume to This Job ⭐
                        </button>

                        <button
                            class="btn-apply pointer-events-auto need-cover-letter-btn"
                            style="background-color: #3b82f6; border-color: #3b82f6;"
                            type="button"
                            data-job-id="${job.id}"
                            data-job-title="${(job.job_title || '').replace(/"/g, '&quot;')}"
                            data-job-description="${(job.job_description || '').replace(/"/g, '&quot;')}"
                            data-company-name="${(job.company_name || '').replace(/"/g, '&quot;')}">
                            <i class="fas fa-file-alt"></i>
                            Need Cover Letter?
                        </button>
                        ` : ''}
                        <button class="btn-remove-job" title="Remove from matches" onclick="removeJobMatch(${job.id}, event)">
                            <i class="fas fa-trash-alt"></i>
                            Remove
                        </button>
                    </div>
                </div>
            `;
        });

        jobListings.innerHTML = html;
    }

    // Resume Generation Functions
    let currentJobData = {
        id: null,
        title: null,
        description: null,
        selectedTemplate: null
    };

    /**
     * Prepare resume generation with job data
     */
    function prepareResumeGeneration(event, jobId, jobTitle, jobDescription) {
        event.preventDefault();

        // Check if user has ai_tailored_resume feature enabled
        if (!hasAiTailoredResumeFeature) {
            // Show upgrade modal instead
            const upgradeModal = new bootstrap.Modal(document.getElementById('upgradeRequiredModal'));
            upgradeModal.show();
            return;
        }

        currentJobData = {
            id: jobId,
            title: jobTitle,
            description: jobDescription,
            selectedTemplate: null
        };

        // Reset modal to step 1
        resetResumeModal();
        document.getElementById('resumeStep1').style.display = 'block';
    }

    /**
     * Handle tailor resume button click from job listings
     */
    function handleTailorResumeClick(button) {
        const jobId = button.getAttribute('data-job-id');
        const jobTitle = button.getAttribute('data-job-title');
        const jobDescription = button.getAttribute('data-job-description');

        // Check if user has ai_tailored_resume feature enabled
        if (!hasAiTailoredResumeFeature) {
            // Show upgrade modal instead
            const upgradeModal = new bootstrap.Modal(document.getElementById('upgradeRequiredModal'));
            upgradeModal.show();
            return;
        }

        currentJobData = {
            id: jobId,
            title: jobTitle,
            description: jobDescription,
            selectedTemplate: null
        };

        // Reset modal to step 1
        resetResumeModal();
        document.getElementById('resumeStep1').style.display = 'block';
        const resumeModal = new bootstrap.Modal(document.getElementById('resumeGeneratorModal'));
        resumeModal.show();
    }

    /**
     * Select a template
     */
    function selectTemplate(element, templateName) {
        // Remove previous selection
        document.querySelectorAll('.template-card').forEach(card => {
            card.classList.remove('selected');
        });

        // Mark as selected
        element.classList.add('selected');
        currentJobData.selectedTemplate = templateName;
    }

    /**
     * Move to next step or generate resume
     */
    function nextResumeStep() {
        const step1 = document.getElementById('resumeStep1');
        const step2 = document.getElementById('resumeStep2');
        const actionBtn = document.getElementById('resumeActionBtn');
        if (step1.style.display !== 'none') {
            // Validate template selection
            if (!currentJobData.selectedTemplate) {
                alert('Please select a template');
                return;
            }

            // Move to step 2
            step1.style.display = 'none';
            step2.style.display = 'block';

            // Update preview
            document.getElementById('previewJobTitle').textContent = currentJobData.title;
            document.getElementById('previewTemplate').textContent =
                currentJobData.selectedTemplate.charAt(0).toUpperCase() + currentJobData.selectedTemplate.slice(1).replace('-', ' ');

            // Update button
            actionBtn.textContent = 'Generate Resume';
            actionBtn.className = 'btn btn-success';
            actionBtn.onclick = generateResume;
        }
    }

    /**
     * Generate tailored resume
     */
    function generateResume() {
        const loadingDiv = document.getElementById('resumeLoading');
        const successDiv = document.getElementById('resumeSuccess');
        const step2 = document.getElementById('resumeStep2');
        const actionBtn = document.getElementById('resumeActionBtn');
        const downloadBtn = document.getElementById('resumeDownloadBtn');

        // Validate that a template is selected
        if (!currentJobData.selectedTemplate) {
            alert('Please select a template first');
            return;
        }

        // Show loading state
        step2.style.display = 'none';
        loadingDiv.style.display = 'block';
        actionBtn.style.display = 'none';

        // Call API to generate resume
        fetch('<?php echo e(route("placement.resumes.generate")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    job_id: currentJobData.id,
                    template: currentJobData.selectedTemplate
                })
            })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 403) {
                        return response.json().then(data => {
                            throw new Error('Subscription required');
                        });
                    }
                    throw new Error('Failed to generate resume');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Redirect to edit page where user can preview/edit before downloading
                    window.location.href = '<?php echo e(route("placement.resume.edit", ["jobId" => ":jobId"])); ?>'
                        .replace(':jobId', currentJobData.id) + '?template=' + currentJobData.selectedTemplate;

                    console.log('Redirecting to resume editor:', data.message);
                } else {
                    throw new Error(data.message || 'Failed to generate resume');
                }
            })
            .catch(error => {
                console.error('Resume generation error:', error);

                // Hide loading, show error
                loadingDiv.style.display = 'none';
                step2.style.display = 'block';
                actionBtn.style.display = 'inline-block';

                // Show error message
                const errorMsg = error.message === 'Subscription required' ?
                    'Your subscription has expired. Please renew to continue.' :
                    'Failed to generate resume. Please try again.';

                alert(errorMsg);
            });
    }

    /**
     * Reset resume modal
     */
    function resetResumeModal() {
        document.getElementById('resumeStep1').style.display = 'block';
        document.getElementById('resumeStep2').style.display = 'none';
        document.getElementById('resumeLoading').style.display = 'none';
        document.getElementById('resumeSuccess').style.display = 'none';

        document.querySelectorAll('.template-card').forEach(card => {
            card.classList.remove('selected');
        });

        const actionBtn = document.getElementById('resumeActionBtn');
        const downloadBtn = document.getElementById('resumeDownloadBtn');
        actionBtn.style.display = 'inline-block';
        actionBtn.textContent = 'Next: Review Details';
        actionBtn.className = 'btn btn-primary';
        actionBtn.onclick = nextResumeStep;
        downloadBtn.style.display = 'none';

        currentJobData.selectedTemplate = null;
    }

    /**
     * Handle need cover letter button click
     */
    function handleNeedCoverLetterClick(button) {
        // Check if user has cover letter feature enabled
        if (!hasAiTailoredCoverFeature) {
            // Show upgrade modal instead
            const upgradeModal = new bootstrap.Modal(document.getElementById('upgradeRequiredModal'));
            upgradeModal.show();
            return;
        }

        const jobId = button.getAttribute('data-job-id');
        const jobTitle = button.getAttribute('data-job-title');
        const jobDescription = button.getAttribute('data-job-description');
        const companyName = button.getAttribute('data-company-name');

        // Redirect to cover letter generation page
        window.location.href = `/placement/cover-letter/generate?job_id=${jobId}&job_title=${encodeURIComponent(jobTitle)}&company=${encodeURIComponent(companyName)}`;
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('front.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Herd-Projects\hired-hero\resources\views/placement/job-matches/index.blade.php ENDPATH**/ ?>