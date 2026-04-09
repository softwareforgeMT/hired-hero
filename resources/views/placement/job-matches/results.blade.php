@extends('front.layouts.app')

@section('content')
<div class="job-results-container">
    <!-- Header Section -->
    <div class="results-header">
        <div class="header-content">
            <h1 class="results-title">Your Job Matches Found!</h1>
            <p class="results-subtitle">
                We found <strong>{{ $jobMatches->count() }} highly relevant positions</strong> for <strong>{{ implode(', ', $profile->selected_roles ?? []) }}</strong>
            </p>
        </div>
        <div class="header-actions">
            <a href="{{ route('placement.applications.tracker') }}" class="btn btn-outline-secondary">
                <i class="ri-file-list-line"></i> View Applications
            </a>
            <a href="{{ route('placement.wizard.step', ['step' => 8]) }}" class="btn btn-primary">
                <i class="ri-refresh-line"></i> Refine Search
            </a>
        </div>
    </div>

    <div class="results-body">
        <!-- Filters Sidebar -->
        <aside class="filters-sidebar">
            <div class="filter-card">
                <h5 class="filter-title">
                    <i class="ri-filter-2-line"></i> Filter Results
                </h5>
                
                <!-- Job Source Filter -->
                <div class="filter-group">
                    <label class="filter-label">Job Source</label>
                    <div class="filter-options">
                        <label class="filter-checkbox">
                            <input type="checkbox" value="linkedin" checked> LinkedIn
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" value="indeed" checked> Indeed
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" value="glassdoor" checked> Glassdoor
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" value="workopolis" checked> Workopolis
                        </label>
                    </div>
                </div>

                <!-- Match Score Filter -->
                <div class="filter-group">
                    <label class="filter-label">Match Score</label>
                    <input type="range" class="filter-range" min="0" max="100" value="0">
                    <div class="range-labels">
                        <span>0%</span>
                        <span id="rangeValue">0%+</span>
                    </div>
                </div>

                <!-- Salary Range Filter -->
                <div class="filter-group">
                    <label class="filter-label">Salary Range</label>
                    <div class="salary-inputs">
                        <input type="number" placeholder="Min" class="form-control form-control-sm">
                        <input type="number" placeholder="Max" class="form-control form-control-sm">
                    </div>
                </div>

                <!-- Job Type Filter -->
                <div class="filter-group">
                    <label class="filter-label">Job Type</label>
                    <div class="filter-options">
                        <label class="filter-checkbox">
                            <input type="checkbox" value="remote" checked> Remote
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" value="hybrid" checked> Hybrid
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" value="onsite" checked> On-site
                        </label>
                    </div>
                </div>

                <button class="btn btn-sm btn-outline-secondary w-100 mt-3">Reset Filters</button>
            </div>

            <!-- Saved Searches -->
            <div class="filter-card">
                <h5 class="filter-title">
                    <i class="ri-heart-line"></i> Saved Searches
                </h5>
                <p class="text-muted small mb-0">
                    No saved searches yet. Click the heart icon on any job to save it.
                </p>
            </div>
        </aside>

        <!-- Main Results Area -->
        <main class="results-main">
            <!-- Results Toolbar -->
            <div class="results-toolbar">
                <div class="results-info">
                    <span class="results-count">{{ $jobMatches->count() }} matches found</span>
                </div>
                <div class="results-controls">
                    <select class="form-select form-select-sm" style="max-width: 200px;">
                        <option value="relevance">Sort by Relevance</option>
                        <option value="recent">Most Recent</option>
                        <option value="salary">Highest Salary</option>
                        <option value="company">Company Name</option>
                    </select>
                </div>
            </div>

            <!-- Job Cards -->
            <div class="job-cards-container">
                @forelse ($jobMatches as $job)
                    <article class="job-card" data-match-score="{{ $job->match_score ?? 0 }}">
                        <!-- Match Badge -->
                        <div class="match-badge" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                            <div class="match-percentage">{{ $job->match_score ?? 85 }}%</div>
                            <div class="match-label">Match</div>
                        </div>

                        <!-- Main Content -->
                        <div class="job-content">
                            <!-- Company & Job Title -->
                            <div class="job-header">
                                <div>
                                    <h3 class="job-title">{{ $job->title ?? 'Job Title' }}</h3>
                                    <p class="job-company">
                                        <i class="ri-building-line"></i> {{ $job->company ?? 'Company' }}
                                    </p>
                                </div>
                                <button class="btn-save-job" title="Save this job">
                                    <i class="ri-heart-line"></i>
                                </button>
                            </div>

                            <!-- Quick Info -->
                            <div class="job-meta">
                                <span class="meta-item">
                                    <i class="ri-map-pin-line"></i> {{ $job->location ?? 'Remote' }}
                                </span>
                                <span class="meta-item">
                                    <i class="ri-briefcase-line"></i> {{ $job->job_type ?? 'Full-time' }}
                                </span>
                                <span class="meta-item">
                                    <i class="ri-calendar-line"></i> Posted {{ $job->posted_date ? $job->posted_date->diffForHumans() : '2 days ago' }}
                                </span>
                            </div>

                            <!-- Salary -->
                            @if($job->salary_min || $job->salary_max)
                                <div class="job-salary">
                                    <i class="ri-money-dollar-circle-line"></i>
                                    @if($job->salary_min && $job->salary_max)
                                        ${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}
                                    @elseif($job->salary_min)
                                        From ${{ number_format($job->salary_min) }}
                                    @else
                                        Up to ${{ number_format($job->salary_max) }}
                                    @endif
                                    per year
                                </div>
                            @endif

                            <!-- Skills Match -->
                            <div class="skills-match">
                                <span class="skills-label">Skills Match:</span>
                                <div class="matched-skills">
                                    @php
                                        $skills = array_slice(explode(',', $job->matched_skills ?? ''), 0, 3);
                                    @endphp
                                    @foreach ($skills as $skill)
                                        <span class="skill-tag">{{ trim($skill) }}</span>
                                    @endforeach
                                    @if(count(explode(',', $job->matched_skills ?? '')) > 3)
                                        <span class="skill-tag more">
                                            +{{ count(explode(',', $job->matched_skills ?? '')) - 3 }} more
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Job Source -->
                            <div class="job-source">
                                <strong>From {{  strtoupper($job->source ?? 'INDEED') }}</strong>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="job-actions">
                            <a href="{{  $job->job_url ?? '#' }}" target="_blank" class="btn btn-primary btn-sm waves-effect">
                                <i class="ri-external-link-line"></i> View Job
                            </a>
                            {{-- Generate Tailored Resume Button (LinkedIn only, subscription required) --}}
                            @php
                                $hasActiveSubscription = auth()->check() && auth()->user()->getActiveSubscription() !== null && auth()->user()->getActiveSubscription()->isActive();
                            @endphp
                            @if(strtolower($job->source ?? '') === 'linkedin' && $hasActiveSubscription)
                                <button class="btn btn-success btn-sm waves-effect" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#resumeGeneratorModal"
                                        onclick="prepareResumeGeneration(event, '{{ $job->id }}', '{{ addslashes($job->job_title ?? 'Position') }}', '{{ addslashes($job->job_description ?? '') }}')">
                                    <i class="ri-file-earmark-text-line"></i> Generate Resume
                                </button>
                            @endif
                            <button class="btn btn-outline-secondary btn-sm waves-effect" onclick="saveJob(this)">
                                <i class="ri-bookmark-line"></i> Save
                            </button>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">
                        <i class="ri-search-eye-line"></i>
                        <h4>No jobs found</h4>
                        <p>Try adjusting your filters or refining your role selection.</p>
                        <a href="{{ route('placement.wizard.step', ['step' => 8]) }}" class="btn btn-primary btn-sm">
                            Refine Search
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Load More -->
            @if($jobMatches->count() > 0)
                <div class="load-more-container">
                    <button class="btn btn-outline-primary" onclick="loadMoreJobs()">
                        Load More Jobs
                    </button>
                </div>
            @endif
        </main>
    </div>

    <!-- Email Subscription CTA -->
    <div class="subscription-cta">
        <div class="cta-content">
            <div class="cta-icon">
                <i class="ri-mail-check-line"></i>
            </div>
            <div>
                <h5>Stay Updated with New Matches</h5>
                <p>Get new job matches sent to your email every day</p>
            </div>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#emailSubscriptionModal">
            Enable Email Updates
        </button>
    </div>
</div>

<!-- Email Subscription Modal -->
<div class="modal fade" id="emailSubscriptionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title">Daily Job Matches</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">
                    We'll send you the best matching jobs every morning at 8 AM.
                </p>
                <form>
                    <div class="form-group mb-3">
                        <label for="frequency" class="form-label">Email Frequency</label>
                        <select class="form-select" id="frequency">
                            <option value="daily">Daily (8 AM)</option>
                            <option value="3xweek">3 Times per Week</option>
                            <option value="weekly">Weekly (Monday)</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary w-100" onclick="subscribeToEmails()">
                        Enable Updates
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Resume Generator Modal -->
<div class="modal fade" id="resumeGeneratorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header bg-gradient border-0">
                <div>
                    <h5 class="modal-title mb-0">Generate Tailored Resume</h5>
                    <small class="text-muted">Create a professional ATS-friendly resume for this position</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Step 1: Template Selection -->
                <div id="resumeStep1">
                    <h6 class="mb-3">
                        <span class="badge bg-primary me-2">Step 1</span> Choose Resume Template
                    </h6>
                    <p class="text-muted small mb-3">
                        Select a professional template that will make your resume stand out
                    </p>
                    
                    <div class="template-grid">
                        <div class="template-card" onclick="selectTemplate(this, 'modern')">
                            <div class="template-preview modern-preview">
                                <div class="template-header">Name</div>
                                <div class="template-line"></div>
                                <div class="template-line short"></div>
                            </div>
                            <p class="template-name mt-2 mb-0">Modern</p>
                        </div>

                        <div class="template-card" onclick="selectTemplate(this, 'professional')">
                            <div class="template-preview professional-preview">
                                <div class="template-header">Name</div>
                                <div class="template-line"></div>
                                <div class="template-line short"></div>
                            </div>
                            <p class="template-name mt-2 mb-0">Professional</p>
                        </div>

                        <div class="template-card" onclick="selectTemplate(this, 'minimalist')">
                            <div class="template-preview minimalist-preview">
                                <div class="template-header">Name</div>
                                <div class="template-line"></div>
                                <div class="template-line short"></div>
                            </div>
                            <p class="template-name mt-2 mb-0">Minimalist</p>
                        </div>

                        <div class="template-card" onclick="selectTemplate(this, 'classic')">
                            <div class="template-preview classic-preview">
                                <div class="template-header">Name</div>
                                <div class="template-line"></div>
                                <div class="template-line short"></div>
                            </div>
                            <p class="template-name mt-2 mb-0">Classic ⭐</p>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Review & Generate -->
                <div id="resumeStep2" style="display: none;">
                    <h6 class="mb-3">
                        <span class="badge bg-primary me-2">Step 2</span> Review & Generate
                    </h6>
                    <p class="text-muted small mb-3">
                        Your resume will be tailored to this position based on your profile and experience
                    </p>

                    <div class="resume-preview-card">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-600">Position</label>
                                <p id="previewJobTitle" class="text-dark mb-0 fw-500">-</p>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-600">Selected Template</label>
                                <p id="previewTemplate" class="text-dark mb-0 fw-500">-</p>
                            </div>
                            <div class="col-12">
                                <div class="alert alert-info mb-0" role="alert">
                                    <i class="ri-information-line"></i>
                                    Your resume will be optimized for ATS systems and tailored to match the job requirements.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning mt-3" role="alert">
                        <i class="ri-time-line"></i>
                        <strong>Processing time:</strong> This may take 15-30 seconds as we customize your resume based on the job description and your experience.
                    </div>
                </div>

                <!-- Loading State -->
                <div id="resumeLoading" style="display: none;" class="text-center py-5">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Generating...</span>
                    </div>
                    <h6>Generating Your Tailored Resume</h6>
                    <p class="text-muted small">This may take a moment...</p>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
                    </div>
                </div>

                <!-- Success State -->
                <div id="resumeSuccess" style="display: none;" class="text-center py-5">
                    <div class="success-icon mb-3">
                        <i class="ri-check-circle-fill text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="mb-2">Resume Generated Successfully!</h6>
                    <p class="text-muted small mb-4">Your tailored resume is ready to download and use</p>
                </div>
            </div>
            <div class="modal-footer border-0 py-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="resetResumeModal()">
                    Close
                </button>
                <button type="button" class="btn btn-primary" id="resumeActionBtn" onclick="nextResumeStep()">
                    Next: Review Details
                </button>
                <a href="#" id="resumeDownloadBtn" style="display: none;" class="btn btn-success" download>
                    <i class="ri-download-line"></i> Download Resume
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .job-results-container {
        min-height: 100vh;
        background: #f8f9fa;
    }

    .results-header {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        padding: 3rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
    }

    .header-content h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .results-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    .results-body {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 2rem;
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .filters-sidebar {
        position: sticky;
        top: 2rem;
        height: fit-content;
    }

    .filter-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }

    .filter-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a1a;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .filter-group {
        margin-bottom: 1.5rem;
    }

    .filter-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2d3748;
        display: block;
        margin-bottom: 0.75rem;
    }

    .filter-options {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        font-size: 0.9rem;
        color: #4b5563;
    }

    .filter-checkbox input {
        cursor: pointer;
    }

    .filter-range {
        width: 100%;
    }

    .range-labels {
        display: flex;
        justify-content: space-between;
        font-size: 0.8rem;
        color: #6b7280;
        margin-top: 0.5rem;
    }

    .salary-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
    }

    .results-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: white;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e5e7eb;
    }

    .results-count {
        font-weight: 600;
        color: #1a1a1a;
    }

    .job-cards-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .job-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.5rem;
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 1.5rem;
        align-items: start;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .job-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.12);
        transform: translateY(-2px);
    }

    .match-badge {
        width: 70px;
        height: 70px;
        border-radius: 0.75rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        font-weight: 700;
        text-align: center;
        flex-shrink: 0;
    }

    .match-percentage {
        font-size: 1.5rem;
    }

    .match-label {
        font-size: 0.7rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .job-content {
        flex: 1;
    }

    .job-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .job-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
        margin-bottom: 0.25rem;
    }

    .job-company {
        color: #6b7280;
        font-weight: 500;
        margin: 0;
    }

    .btn-save-job {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #cbd5e0;
        transition: all 0.2s;
    }

    .btn-save-job:hover {
        color: #ef4444;
        transform: scale(1.1);
    }

    .btn-save-job.saved {
        color: #ef4444;
    }

    .job-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        color: #6b7280;
    }

    .meta-item i {
        color: #3b82f6;
    }

    .job-salary {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: #1a1a1a;
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    .job-salary i {
        color: #10b981;
        font-size: 1.25rem;
    }

    .skills-match {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .skills-label {
        font-weight: 600;
        color: #2d3748;
        white-space: nowrap;
    }

    .matched-skills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .skill-tag {
        background: linear-gradient(135deg, #e0e7ff 0%, #f3f4f6 100%);
        color: #3b82f6;
        padding: 0.35rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .skill-tag.more {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        color: #6b7280;
    }

    .job-source {
        font-size: 0.8rem;
        color: #6b7280;
        margin-bottom: 1rem;
    }

    .job-actions {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-end;
    }

    .job-actions .btn {
        white-space: nowrap;
    }

    .empty-state {
        grid-column: 1/-1;
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 0.75rem;
        border: 1px dashed #cbd5e0;
    }

    .empty-state i {
        font-size: 3rem;
        color: #cbd5e0;
        display: block;
        margin-bottom: 1rem;
    }

    .empty-state h4 {
        color: #1a1a1a;
        margin-bottom: 0.5rem;
    }

    .load-more-container {
        text-align: center;
        padding: 2rem;
    }

    .subscription-cta {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        padding: 2rem;
        border-radius: 0.75rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
        margin-top: 3rem;
    }

    .cta-content {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .cta-icon {
        font-size: 2.5rem;
    }

    .subscription-cta h5 {
        margin: 0 0 0.25rem 0;
        font-weight: 700;
    }

    .subscription-cta p {
        margin: 0;
        opacity: 0.9;
    }

    /* Resume Generator Styles */
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

    @media (max-width: 768px) {
        .template-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
        .results-body {
            grid-template-columns: 1fr;
        }

        .filters-sidebar {
            position: static;
        }

        .job-card {
            grid-template-columns: auto 1fr;
        }

        .job-actions {
            grid-column: 2;
            flex-direction: row;
            gap: 0.5rem;
        }
    }

    @media (max-width: 768px) {
        .results-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .btn {
            flex: 1;
        }

        .job-card {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .match-badge {
            width: 60px;
            height: 60px;
        }

        .job-meta {
            gap: 1rem;
        }

        .subscription-cta {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<script>
    // Current job data for resume generation
    let currentJobData = {
        id: null,
        title: null,
        description: null,
        selectedTemplate: null
    };

    function saveJob(btn) {
        btn.classList.toggle('active');
        const icon = btn.querySelector('i');
        if (btn.classList.contains('active')) {
            icon.classList.remove('ri-bookmark-line');
            icon.classList.add('ri-bookmark-fill');
        } else {
            icon.classList.add('ri-bookmark-line');
            icon.classList.remove('ri-bookmark-fill');
        }
    }

    function loadMoreJobs() {
        console.log('Loading more jobs...');
        // Implement pagination here
    }

    function subscribeToEmails() {
        const frequency = document.getElementById('frequency').value;
        alert(`Subscribed to ${frequency} email updates!`);
        bootstrap.Modal.getInstance(document.getElementById('emailSubscriptionModal')).hide();
    }

    // Add heart icon functionality
    document.querySelectorAll('.btn-save-job').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            this.classList.toggle('saved');
        });
    });

    /**
     * Prepare resume generation with job data
     */
    function prepareResumeGeneration(event, jobId, jobTitle, jobDescription) {
        event.preventDefault();
        
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
                alert('Please select a template to continue');
                return;
            }

            // Move to step 2
            step1.style.display = 'none';
            step2.style.display = 'block';

            // Update preview
            document.getElementById('previewJobTitle').textContent = currentJobData.title;
            document.getElementById('previewTemplate').textContent = 
                currentJobData.selectedTemplate.charAt(0).toUpperCase() + currentJobData.selectedTemplate.slice(1);

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
        fetch('{{ route("placement.resumes.generate") }}', {
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
                // Hide loading, show success
                loadingDiv.style.display = 'none';
                successDiv.style.display = 'block';

                // Setup download button
                downloadBtn.style.display = 'inline-block';
                downloadBtn.href = data.download_url;
                downloadBtn.onclick = function() {
                    window.location.href = data.download_url;
                    return false;
                };

                // Hide next button
                actionBtn.style.display = 'none';

                console.log('Resume generated successfully:', data.message);
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
            const errorMsg = error.message === 'Subscription required' 
                ? 'Your subscription has expired. Please renew to continue.'
                : 'Failed to generate resume. Please try again.';
            
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
</script>
@endsection
