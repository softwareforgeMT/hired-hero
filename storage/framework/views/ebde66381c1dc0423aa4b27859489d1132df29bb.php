

<?php $__env->startSection('title', 'Job Fairs in Canada & United States'); ?>

<?php $__env->startSection('css'); ?>
<style>
    .hh-fair-hero {
        background: radial-gradient(circle at top left, var(--brand-green,#00D4A8), #0b1120 50%, #020617 100%);
        color: #ffffff;
        padding: 72px 0 48px;
    }
    .hh-fair-eyebrow {
        text-transform: uppercase;
        letter-spacing: .14em;
        font-size: .75rem;
        opacity: .75;
    }
    .hh-fair-title {
        font-size: clamp(2rem, 3vw, 2.4rem);
        font-weight: 700;
        margin-bottom: .75rem;
    }
    .hh-fair-subtitle {
        max-width: 680px;
        opacity: .88;
    }
    .hh-fair-pills {
        display:flex;
        flex-wrap:wrap;
        gap:.5rem;
        margin-top:.75rem;
    }
    .hh-fair-pill {
        border-radius:999px;
        padding:.25rem .75rem;
        background:rgba(15,23,42,.7);
        border:1px solid rgba(148,163,184,.5);
        font-size:.78rem;
        display:inline-flex;
        align-items:center;
        gap:.3rem;
    }

    /* Tabs bar */
    .hh-fair-tabs {
        padding: 20px 0;
        border-bottom: 1px solid #e2e8f0;
        background:#ffffff;
    }

    .hh-fair-tabs .btn {
        background:#f3f4f6 !important;
        color:#0f172a !important;
        border:1px solid #cbd5e1 !important;
        font-weight:500;
        opacity:1 !important;
        border-radius:999px !important;
    }
    .hh-fair-tabs .btn:hover,
    .hh-fair-tabs .btn:focus,
    .hh-fair-tabs .btn.active {
        background:#e5e7f0 !important;
        border-color:#94a3b8 !important;
        color:#0f172a !important;
    }

    /* Filter bar */
    .hh-fair-filters {
        margin-top:1rem;
        padding: 1rem 1.25rem;
        border-radius: 16px;
        background:#f9fafb;
        border:1px solid #e5e7eb;
    }
    .hh-fair-filters .form-label {
        font-size:.72rem;
        text-transform:uppercase;
        letter-spacing:.08em;
        color:#6b7280;
        margin-bottom:.25rem;
    }
    .hh-fair-filters .form-control,
    .hh-fair-filters .form-select {
        font-size:.85rem;
        border-radius:999px;
        border:1px solid #d1d5db;
        padding: .35rem .9rem;
    }
    .hh-fair-filters .form-control::placeholder {
        color:#9ca3af;
    }

    /* Light sections – force dark, readable text everywhere */
    .hh-fair-section {
        padding: 32px 0 40px;
        background: var(--light-bg,#f7f9fc);
        color:#0f172a !important;
    }
    .hh-fair-section h1,
    .hh-fair-section h2,
    .hh-fair-section h3,
    .hh-fair-section h4,
    .hh-fair-section h5,
    .hh-fair-section h6,
    .hh-fair-section .h5,
    .hh-fair-section p,
    .hh-fair-section li {
        color:#0f172a !important;
    }
    .hh-fair-section .text-muted,
    .hh-fair-section .small {
        color:#4b5563 !important;
        opacity:1 !important;
    }

    .hh-fair-card {
        background:#ffffff;
        border-radius:16px;
        border:1px solid #e2e8f0;
        padding:14px 14px 12px;
        height:100%;
        box-shadow:0 14px 35px rgba(15,23,42,.05);
        font-size:.86rem;
        color:#111827;
    }
    .hh-fair-badge {
        border-radius:999px;
        padding:.15rem .6rem;
        font-size:.7rem;
        background:#eff6ff;
        color:#1d4ed8;
        margin-right:.25rem;
    }
    .hh-fair-meta {
        font-size:.78rem;
        color:#6b7280;
    }

    .hh-fair-link-list {
        list-style:none;
        padding-left:0;
        margin-bottom:.5rem;
    }
    .hh-fair-link-list li + li {
        margin-top:.25rem;
    }
    .hh-fair-link-list a {
        text-decoration:none;
    }
    .hh-fair-link-list a:hover {
        text-decoration:underline;
    }

    .hh-fair-footer {
        padding: 32px 0 48px;
        background:#0b1120;
        color:#e5e7eb;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    
    <section class="hh-fair-hero">
        <div class="container">
            <div class="row gy-3 align-items-center">
                <div class="col-lg-7">
                    <div class="hh-fair-eyebrow">Job Fairs Hub</div>
                    <h1 class="hh-fair-title">
                        2026 Job Fairs & Career Expos — Canada & United States
                    </h1>
                    <p class="hh-fair-subtitle">
                        Use these feeds to find in-person and virtual job fairs, then prep your resume, pitch,
                        and interviews so you walk in ready. This page points you to sources that update themselves.
                    </p>
                    <div class="hh-fair-pills">
                        <span class="hh-fair-pill">
                            <i class="fas fa-map-marker-alt"></i> Canada & U.S. only
                        </span>
                        <span class="hh-fair-pill">
                            <i class="fas fa-sync-alt"></i> Auto-updating feeds (no dead listings)
                        </span>
                        <span class="hh-fair-pill">
                            <i class="fas fa-user-tie"></i> Built for job seekers
                        </span>
                    </div>
                </div>
                <div class="col-lg-5 text-lg-end">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('resources.individuals')); ?>" class="btn btn-light btn-lg">
                            Prep for interviews & follow-up
                        </a>
                        <a href="#before-fair" class="btn btn-outline-light">
                            What to do before, during & after a fair
                        </a>
                        <a href="https://81e41aba.sibforms.com/serve/MUIFAPR5ekYe5uipLxwj5WRoZnJgIDmC_oCsFm5CTdjH4IT7vRhGlnqvIEcywdzWGQsXfE5jYDKFxcTjIoli3HsH-hxD8QTl5kD1mxwMW3NaVw6yQCoUcZoaicotjoLXdjoCpD_WYGTugU0laQZv7amn0jJb4JXFmqbKoIZgbhhc3G8XEw0qERfaVyKmuUbAl12T5bsGAaQZgZVEMg=="
                           target="_blank" rel="noopener noreferrer"
                           class="btn btn-primary">
                            Subscribe to our newsletter
                        </a>
                    </div>
                    <p class="small mt-2 mb-0 text-muted">
                        Last updated: <?php echo e(now()->format('F j, Y')); ?>

                    </p>
                </div>
            </div>
        </div>
    </section>

    
    <section class="hh-fair-tabs">
        <div class="container">
            <div class="d-flex flex-wrap gap-2">
                <a href="#canada" class="btn btn-sm active">
                    Canada
                </a>
                <a href="#united-states" class="btn btn-sm">
                    United States
                </a>
                <a href="#virtual" class="btn btn-sm">
                    Virtual fairs
                </a>
                <a href="#before-fair" class="btn btn-sm">
                    Before / During / After Playbook
                </a>
            </div>

            
            <div class="hh-fair-filters">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-md-4">
                        <label for="fairSearch" class="form-label">Search by organizer or keyword</label>
                        <input id="fairSearch"
                               type="text"
                               class="form-control"
                               placeholder="e.g. healthcare, Toronto, tech, virtual">
                    </div>
                    <div class="col-6 col-md-3">
                        <label for="fairCountry" class="form-label">Country</label>
                        <select id="fairCountry" class="form-select">
                            <option value="all">All</option>
                            <option value="canada">Canada</option>
                            <option value="us">United States</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label for="fairType" class="form-label">Format</label>
                        <select id="fairType" class="form-select">
                            <option value="all">All</option>
                            <option value="in-person">In person only</option>
                            <option value="virtual">Virtual / hybrid</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <label for="fairAudience" class="form-label">Audience</label>
                        <select id="fairAudience" class="form-select">
                            <option value="all">All</option>
                            <option value="general">General</option>
                            <option value="students">Students / grads</option>
                            <option value="healthcare">Healthcare</option>
                            <option value="tech">Tech / IT</option>
                            <option value="workforce">Workforce / gov</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <section class="hh-fair-section" id="canada">
        <div class="container">
            <h2 class="h5 mb-3">Canada — Job Fair Feeds</h2>
            <p class="text-muted small mb-3">
                These links take you to live calendars and directories that list current job fairs in Canada.
                Filter by province, city, and industry on their sites, then use HiredHeroAI to prep.
            </p>

            <div class="row g-3">

                
                <div class="col-md-6 col-lg-4"
                     data-fair-card
                     data-country="canada"
                     data-type="in-person"
                     data-audience="all">
                    <div class="hh-fair-card">
                        <div class="mb-1">
                            <span class="hh-fair-badge">Canada-wide</span>
                            <strong>Career Fair Canada</strong>
                        </div>
                        <div class="hh-fair-meta mb-2">
                            In-person career fairs across multiple Canadian cities.
                        </div>
                        <ul class="hh-fair-link-list small mb-2">
                            <li>
                                <a href="https://careerfaircanada.ca/"
                                   target="_blank" rel="noopener noreferrer">
                                    Visit careerfaircanada.ca →
                                </a>
                            </li>
                        </ul>
                        <a href="<?php echo e(route('resources.individuals')); ?>" class="btn btn-sm btn-soft-primary">
                            Prep for Career Fair Canada
                        </a>
                    </div>
                </div>

                
                <div class="col-md-6 col-lg-4"
                     data-fair-card
                     data-country="canada"
                     data-type="both"
                     data-audience="all">
                    <div class="hh-fair-card">
                        <div class="mb-1">
                            <span class="hh-fair-badge">Canada-wide</span>
                            <strong>Eventbrite — Job Fairs in Canada</strong>
                        </div>
                        <div class="hh-fair-meta mb-2">
                            Aggregated job fair listings across Canada, including virtual events.
                        </div>
                        <ul class="hh-fair-link-list small mb-2">
                            <li>
                                <a href="https://www.eventbrite.ca/d/canada/job-fairs/"
                                   target="_blank" rel="noopener noreferrer">
                                    Browse job fairs on Eventbrite →
                                </a>
                            </li>
                        </ul>
                        <a href="<?php echo e(route('resources.individuals')); ?>" class="btn btn-sm btn-soft-primary">
                            Get interview-ready
                        </a>
                    </div>
                </div>

                
                <div class="col-md-6 col-lg-4"
                     data-fair-card
                     data-country="both"
                     data-type="both"
                     data-audience="all">
                    <div class="hh-fair-card">
                        <div class="mb-1">
                            <span class="hh-fair-badge">Canada / US</span>
                            <strong>DiversityX Job Fairs</strong>
                        </div>
                        <div class="hh-fair-meta mb-2">
                            Diversity-focused job fairs across Canada and the US (in-person and virtual).
                        </div>
                        <ul class="hh-fair-link-list small mb-2">
                            <li>
                                <a href="https://www.diversityx.net/job-fairs-near-me"
                                   target="_blank" rel="noopener noreferrer">
                                    Find a DiversityX fair near you →
                                </a>
                            </li>
                        </ul>
                        <a href="<?php echo e(route('resources.individuals')); ?>" class="btn btn-sm btn-soft-primary">
                            Practice behavioral questions
                        </a>
                    </div>
                </div>

                
                <div class="col-md-6 col-lg-4"
                     data-fair-card
                     data-country="canada"
                     data-type="both"
                     data-audience="healthcare">
                    <div class="hh-fair-card">
                        <div class="mb-1">
                            <span class="hh-fair-badge">BC — Vancouver</span>
                            <strong>Vancouver Coastal Health Job Fairs & Events</strong>
                        </div>
                        <div class="hh-fair-meta mb-2">
                            Healthcare roles with Vancouver Coastal Health • in-person & virtual.
                        </div>
                        <ul class="hh-fair-link-list small mb-2">
                            <li>
                                <a href="https://www.vch.ca/en/careers/connect-us/job-fairs-and-events"
                                   target="_blank" rel="noopener noreferrer">
                                    View VCH job fairs & events →
                                </a>
                            </li>
                        </ul>
                        <a href="<?php echo e(route('resources.individuals')); ?>" class="btn btn-sm btn-soft-primary">
                            Prep for healthcare interviews
                        </a>
                    </div>
                </div>

                
                <div class="col-md-6 col-lg-4"
                     data-fair-card
                     data-country="canada"
                     data-type="both"
                     data-audience="workforce,all">
                    <div class="hh-fair-card">
                        <div class="mb-1">
                            <span class="hh-fair-badge">BC — Province-wide</span>
                            <strong>WorkBC Career & Education Fairs</strong>
                        </div>
                        <div class="hh-fair-meta mb-2">
                            Career and education fairs, hiring events, and workshops across BC.
                        </div>
                        <ul class="hh-fair-link-list small mb-2">
                            <li>
                                <a href="https://www.workbc.ca/plan-career/career-events/career-and-education-fairs"
                                   target="_blank" rel="noopener noreferrer">
                                    View WorkBC career events →
                                </a>
                            </li>
                        </ul>
                        <a href="<?php echo e(route('resources.individuals')); ?>" class="btn btn-sm btn-soft-primary">
                            Prep for WorkBC fairs
                        </a>
                    </div>
                </div>

                
                <div class="col-md-6 col-lg-4"
                     data-fair-card
                     data-country="canada"
                     data-type="virtual"
                     data-audience="tech">
                    <div class="hh-fair-card">
                        <div class="mb-1">
                            <span class="hh-fair-badge">Canada — Tech</span>
                            <strong>Tech Talent Canada Virtual Job Fairs</strong>
                        </div>
                        <div class="hh-fair-meta mb-2">
                            Virtual tech job fairs connecting Canadian tech employers with talent.
                        </div>
                        <ul class="hh-fair-link-list small mb-2">
                            <li>
                                <a href="https://techtalent.ca/"
                                   target="_blank" rel="noopener noreferrer">
                                    Explore Tech Talent Canada events →
                                </a>
                            </li>
                        </ul>
                        <a href="<?php echo e(route('resources.individuals')); ?>" class="btn btn-sm btn-soft-primary">
                            Prep for tech interviews
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    
    <section class="hh-fair-section" id="united-states">
        <div class="container">
            <h2 class="h5 mb-3">United States — Job Fair Feeds</h2>
            <p class="text-muted small mb-3">
                Use these national and state-level feeds to find hiring events. Most let you filter by city,
                industry, and whether the fair is virtual or in-person.
            </p>

            <div class="row g-3">

                
                <div class="col-md-6 col-lg-4"
                     data-fair-card
                     data-country="us"
                     data-type="both"
                     data-audience="all">
                    <div class="hh-fair-card">
                        <div class="mb-1">
                            <span class="hh-fair-badge">US-wide</span>
                            <strong>DiversityX Job Fairs</strong>
                        </div>
                        <div class="hh-fair-meta mb-2">
                            Diversity-focused job fairs across major US cities (in-person & virtual).
                        </div>
                        <ul class="hh-fair-link-list small mb-2">
                            <li>
                                <a href="https://www.diversityx.net/job-fairs-near-me"
                                   target="_blank" rel="noopener noreferrer">
                                    Find US job fairs on DiversityX →
                                </a>
                            </li>
                        </ul>
                        <a href="<?php echo e(route('resources.individuals')); ?>" class="btn btn-sm btn-soft-primary">
                            Practice common fair questions
                        </a>
                    </div>
                </div>

                
                <div class="col-md-6 col-lg-4"
                     data-fair-card
                     data-country="us"
                     data-type="both"
                     data-audience="all">
                    <div class="hh-fair-card">
                        <div class="mb-1">
                            <span class="hh-fair-badge">US-wide</span>
                            <strong>Eventbrite — Job Fairs in the US</strong>
                        </div>
                        <div class="hh-fair-meta mb-2">
                            Large directory of job fairs across the United States, including online events.
                        </div>
                        <ul class="hh-fair-link-list small mb-2">
                            <li>
                                <a href="https://www.eventbrite.ca/d/united-states/job-fairs/"
                                   target="_blank" rel="noopener noreferrer">
                                    Browse US job fairs on Eventbrite →
                                </a>
                            </li>
                        </ul>
                        <a href="<?php echo e(route('resources.individuals')); ?>" class="btn btn-sm btn-soft-primary">
                            Get interview-ready
                        </a>
                    </div>
                </div>

                
                <div class="col-md-6 col-lg-4"
                     data-fair-card
                     data-country="us"
                     data-type="both"
                     data-audience="all">
                    <div class="hh-fair-card">
                        <div class="mb-1">
                            <span class="hh-fair-badge">US-wide</span>
                            <strong>CareerOneStop Job Fairs</strong>
                        </div>
                        <div class="hh-fair-meta mb-2">
                            National directory of job fairs and hiring events from the US career system.
                        </div>
                        <ul class="hh-fair-link-list small mb-2">
                            <li>
                                <a href="https://www.careeronestop.org/JobSearch/FindJobs/job-fairs.aspx"
                                   target="_blank" rel="noopener noreferrer">
                                    Search job fairs on CareerOneStop →
                                </a>
                            </li>
                        </ul>
                        <a href="<?php echo e(route('resources.individuals')); ?>" class="btn btn-sm btn-soft-primary">
                            Turn fairs into interviews
                        </a>
                    </div>
                </div>

                
                <div class="col-md-6 col-lg-4"
                     data-fair-card
                     data-country="us"
                     data-type="both"
                     data-audience="students">
                    <div class="hh-fair-card">
                        <div class="mb-1">
                            <span class="hh-fair-badge">US — Students</span>
                            <strong>Handshake Career Fairs & Events</strong>
                        </div>
                        <div class="hh-fair-meta mb-2">
                            Campus career fairs and employer events for students and new grads (virtual & in-person).
                        </div>
                        <ul class="hh-fair-link-list small mb-2">
                            <li>
                                <a href="https://joinhandshake.com/students/events/"
                                   target="_blank" rel="noopener noreferrer">
                                    View fairs in Handshake (login required) →
                                </a>
                            </li>
                        </ul>
                        <a href="<?php echo e(route('resources.individuals')); ?>" class="btn btn-sm btn-soft-primary">
                            Prep as a student / new grad
                        </a>
                    </div>
                </div>

                
                <div class="col-md-6 col-lg-4"
                     data-fair-card
                     data-country="us"
                     data-type="both"
                     data-audience="workforce,all">
                    <div class="hh-fair-card">
                        <div class="mb-1">
                            <span class="hh-fair-badge">US — Texas</span>
                            <strong>Texas Workforce Commission Events</strong>
                        </div>
                        <div class="hh-fair-meta mb-2">
                            Calendar of hiring events and job fairs across Texas.
                        </div>
                        <ul class="hh-fair-link-list small mb-2">
                            <li>
                                <a href="https://www.twc.texas.gov/events"
                                   target="_blank" rel="noopener noreferrer">
                                    View Texas hiring events →
                                </a>
                            </li>
                        </ul>
                        <a href="<?php echo e(route('resources.individuals')); ?>" class="btn btn-sm btn-soft-primary">
                            Prep for state workforce fairs
                        </a>
                    </div>
                </div>

                
                <div class="col-md-6 col-lg-4"
                     data-fair-card
                     data-country="us"
                     data-type="both"
                     data-audience="workforce,all">
                    <div class="hh-fair-card">
                        <div class="mb-1">
                            <span class="hh-fair-badge">US — Ohio</span>
                            <strong>OhioMeansJobs Events & Career Fairs</strong>
                        </div>
                        <div class="hh-fair-meta mb-2">
                            Virtual and in-person hiring events and workshops across Ohio.
                        </div>
                        <ul class="hh-fair-link-list small mb-2">
                            <li>
                                <a href="https://jobseeker.ohiomeansjobs.monster.com/ExploreIt/Events.aspx"
                                   target="_blank" rel="noopener noreferrer">
                                    View OhioMeansJobs events →
                                </a>
                            </li>
                        </ul>
                        <a href="<?php echo e(route('resources.individuals')); ?>" class="btn btn-sm btn-soft-primary">
                            Prep for Ohio events
                        </a>
                    </div>
                </div>

                
                <div class="col-md-6 col-lg-4"
                     data-fair-card
                     data-country="both"
                     data-type="both"
                     data-audience="tech">
                    <div class="hh-fair-card">
                        <div class="mb-1">
                            <span class="hh-fair-badge">North America — Tech</span>
                            <strong>Tech Jobs Fair</strong>
                        </div>
                        <div class="hh-fair-meta mb-2">
                            Tech-focused job fairs with both physical and virtual events, including North America.
                        </div>
                        <ul class="hh-fair-link-list small mb-2">
                            <li>
                                <a href="https://techjobsfair.com/"
                                   target="_blank" rel="noopener noreferrer">
                                    Explore Tech Jobs Fair dates →
                                </a>
                            </li>
                        </ul>
                        <a href="<?php echo e(route('resources.individuals')); ?>" class="btn btn-sm btn-soft-primary">
                            Prep for tech roles
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    
    <section class="hh-fair-section" id="virtual">
        <div class="container">
            <h2 class="h5 mb-3">Virtual Job Fairs (Canada & US)</h2>
            <p class="text-muted small mb-3">
                These platforms and directories host virtual or hybrid job fairs you can attend from anywhere.
                Use their filters to target roles that match your skills.
            </p>

            <div class="row g-3">

                
                <div class="col-md-6 col-lg-4"
                     data-fair-card
                     data-country="both"
                     data-type="virtual"
                     data-audience="all">
                    <div class="hh-fair-card">
                        <div class="mb-1">
                            <span class="hh-fair-badge">Virtual</span>
                            <strong>Virtual fairs on Eventbrite</strong>
                        </div>
                        <div class="hh-fair-meta mb-2">
                            Search for “virtual” or “online” job fairs and set your location to Canada or US.
                        </div>
                        <ul class="hh-fair-link-list small mb-2">
                            <li>
                                <a href="https://www.eventbrite.ca/d/online/job-fairs/"
                                   target="_blank" rel="noopener noreferrer">
                                    Explore online job fairs on Eventbrite →
                                </a>
                            </li>
                        </ul>
                        <a href="<?php echo e(route('resources.individuals')); ?>" class="btn btn-sm btn-soft-primary">
                            Prep for remote interviews
                        </a>
                    </div>
                </div>

                
                <div class="col-md-6 col-lg-4"
                     data-fair-card
                     data-country="both"
                     data-type="virtual"
                     data-audience="all">
                    <div class="hh-fair-card">
                        <div class="mb-1">
                            <span class="hh-fair-badge">Virtual</span>
                            <strong>Virtual events via DiversityX</strong>
                        </div>
                        <div class="hh-fair-meta mb-2">
                            Many DiversityX fairs are hybrid or online — look for virtual options.
                        </div>
                        <ul class="hh-fair-link-list small mb-2">
                            <li>
                                <a href="https://www.diversityx.net/job-fairs-near-me"
                                   target="_blank" rel="noopener noreferrer">
                                    Check for virtual DiversityX fairs →
                                </a>
                            </li>
                        </ul>
                        <a href="<?php echo e(route('resources.individuals')); ?>" class="btn btn-sm btn-soft-primary">
                            Practice video interview questions
                        </a>
                    </div>
                </div>

                
                <div class="col-md-6 col-lg-4"
                     data-fair-card
                     data-country="both"
                     data-type="virtual"
                     data-audience="all">
                    <div class="hh-fair-card">
                        <div class="mb-1">
                            <span class="hh-fair-badge">Virtual</span>
                            <strong>Brazen Virtual Career Fairs</strong>
                        </div>
                        <div class="hh-fair-meta mb-2">
                            Platform used by employers, universities, and governments to host virtual hiring events.
                        </div>
                        <ul class="hh-fair-link-list small mb-2">
                            <li>
                                <a href="https://brazencareerist.com/"
                                   target="_blank" rel="noopener noreferrer">
                                    Learn about Brazen-hosted fairs →
                                </a>
                            </li>
                        </ul>
                        <a href="<?php echo e(route('resources.individuals')); ?>" class="btn btn-sm btn-soft-primary">
                            Get ready for chat + video fairs
                        </a>
                    </div>
                </div>

                
                <div class="col-md-6 col-lg-4"
                     data-fair-card
                     data-country="both"
                     data-type="virtual"
                     data-audience="all">
                    <div class="hh-fair-card">
                        <div class="mb-1">
                            <span class="hh-fair-badge">Virtual</span>
                            <strong>vFairs Virtual Job Fairs</strong>
                        </div>
                        <div class="hh-fair-meta mb-2">
                            Virtual and hybrid job fairs hosted on the vFairs platform.
                        </div>
                        <ul class="hh-fair-link-list small mb-2">
                            <li>
                                <a href="https://www.vfairs.com/event-management-platform/virtual-job-fair/"
                                   target="_blank" rel="noopener noreferrer">
                                    Learn about vFairs job fairs →
                                </a>
                            </li>
                        </ul>
                        <a href="<?php echo e(route('resources.individuals')); ?>" class="btn btn-sm btn-soft-primary">
                            Prep for online fair formats
                        </a>
                    </div>
                </div>

                
                <div class="col-md-6 col-lg-4"
                     data-fair-card
                     data-country="us"
                     data-type="virtual"
                     data-audience="students">
                    <div class="hh-fair-card">
                        <div class="mb-1">
                            <span class="hh-fair-badge">Virtual — Students</span>
                            <strong>Handshake Virtual Career Fairs</strong>
                        </div>
                        <div class="hh-fair-meta mb-2">
                            Many Handshake fairs are fully virtual with 1:1 and group sessions.
                        </div>
                        <ul class="hh-fair-link-list small mb-2">
                            <li>
                                <a href="https://joinhandshake.com/students/events/"
                                   target="_blank" rel="noopener noreferrer">
                                    Find virtual fairs in Handshake →
                                </a>
                            </li>
                        </ul>
                        <a href="<?php echo e(route('resources.individuals')); ?>" class="btn btn-sm btn-soft-primary">
                            Prep for student virtual fairs
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    
    <section class="hh-fair-section" id="before-fair">
        <div class="container">
            <h2 class="h5 mb-3">What to do before, during & after a job fair</h2>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="hh-fair-card">
                        <div class="text-uppercase small text-muted fw-semibold mb-1">Before</div>
                        <ul class="small mb-2 ps-3">
                            <li>Pick 3–5 employers you’ll prioritize.</li>
                            <li>Update your resume for those roles.</li>
                            <li>Prepare a 20–30 second intro (“pitch”).</li>
                            <li>Practice 5 behavioral questions.</li>
                        </ul>
                        <a href="<?php echo e(route('resources.individuals')); ?>" class="btn btn-sm btn-soft-primary">
                            Use prompts & practice tools
                        </a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="hh-fair-card">
                        <div class="text-uppercase small text-muted fw-semibold mb-1">During</div>
                        <ul class="small mb-2 ps-3">
                            <li>Use your intro, then ask about current hiring.</li>
                            <li>Take quick notes after each conversation.</li>
                            <li>Ask how to best follow up (email, portal, etc.).</li>
                        </ul>
                        <p class="small text-muted mb-0">
                            Tip: recruiters remember specific, curious questions
                            more than “I’ll do anything” energy.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="hh-fair-card">
                        <div class="text-uppercase small text-muted fw-semibold mb-1">After</div>
                        <ul class="small mb-2 ps-3">
                            <li>Send a short thank-you / follow-up within 24–48 hours.</li>
                            <li>Connect on LinkedIn if appropriate.</li>
                            <li>Apply to the roles they mentioned while it’s still fresh.</li>
                        </ul>
                        <a href="<?php echo e(route('resources.individuals')); ?>" class="btn btn-sm btn-soft-primary">
                            Use follow-up email prompts
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <section class="hh-fair-footer">
        <div class="container">
            <div class="row gy-3 align-items-center">
                <div class="col-lg-7">
                    <h2 class="h6 mb-1">Stay in the loop</h2>
                    <p class="small mb-2">
                        Get updates when we add new job fair feeds for Canada or the US,
                        plus prep tips so you’re not walking in cold.
                    </p>
                    <a href="https://81e41aba.sibforms.com/serve/MUIFAPR5ekYe5uipLxwj5WRoZnJgIDmC_oCsFm5CTdjH4IT7vRhGlnqvIEcywdzWGQsXfE5jYDKFxcTjIoli3HsH-hxD8QTl5kD1mxwMW3NaVw6yQCoUcZoaicotjoLXdjoCpD_WYGTugU0laQZv7amn0jJb4JXFmqbKoIZgbhhc3G8XEw0qERfaVyKmuUbAl12T5bsGAaQZgZVEMg=="
                       class="btn btn-primary btn-sm"
                       target="_blank" rel="noopener noreferrer">
                        Subscribe for job fair updates
                    </a>
                </div>
                <div class="col-lg-5">
                    <h2 class="h6 mb-1">Suggest a job fair</h2>
                    <p class="small mb-2">
                        If you’d like to suggest a job fair, contact us with the link, city, and date and
                        we’ll review it for this page.
                    </p>
                    <a href="mailto:<?php echo e($gs->from_email); ?>?subject=Job%20Fair%20Suggestion&body=Hi%2C%0A%0AI%20would%20like%20to%20suggest%20adding%20this%20job%20fair%20to%20your%20list%3A%0A%0AEvent%20name%3A%0ACity%2C%20Province%2FState%3A%0ADate%28s%29%3A%0AOfficial%20link%3A%0A%0AThanks%21"
                       class="btn btn-outline-light btn-sm">
                        Email us a job fair
                    </a>
                </div>
            </div>
        </div>
    </section>

    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput   = document.getElementById('fairSearch');
            const countrySelect = document.getElementById('fairCountry');
            const typeSelect    = document.getElementById('fairType');
            const audienceSelect= document.getElementById('fairAudience');
            const cards         = document.querySelectorAll('[data-fair-card]');

            function norm(str) {
                return (str || '').toLowerCase();
            }

            function applyFilters() {
                const q        = norm(searchInput ? searchInput.value : '');
                const country  = countrySelect ? countrySelect.value : 'all';
                const type     = typeSelect ? typeSelect.value : 'all';
                const audience = audienceSelect ? audienceSelect.value : 'all';

                cards.forEach(function (card) {
                    const cardCountry  = card.getAttribute('data-country') || 'all';
                    const cardType     = card.getAttribute('data-type') || 'in-person'; // in-person / virtual / both
                    const rawAudience  = card.getAttribute('data-audience') || '';
                    const cardAudience = rawAudience.split(',').map(a => a.trim()).filter(Boolean);
                    const text         = norm(card.innerText);

                    let visible = true;

                    // keyword search
                    if (q && !text.includes(q)) {
                        visible = false;
                    }

                    // country filter (cards marked "both" count for both)
                    if (country !== 'all' && cardCountry !== country && cardCountry !== 'both') {
                        visible = false;
                    }

                    // format filter: "both" matches either in-person or virtual
                    if (type !== 'all') {
                        if (!(cardType === type || cardType === 'both')) {
                            visible = false;
                        }
                    }

                    // audience filter:
                    // - if user chose "all" → skip
                    // - otherwise card must include selected audience OR "all"
                    if (audience !== 'all') {
                        if (!(cardAudience.includes(audience) || cardAudience.includes('all'))) {
                            visible = false;
                        }
                    }

                    card.style.display = visible ? '' : 'none';
                });
            }

            [searchInput, countrySelect, typeSelect, audienceSelect].forEach(function (el) {
                if (!el) return;
                const evt = el.tagName === 'INPUT' ? 'input' : 'change';
                el.addEventListener(evt, applyFilters);
            });

            applyFilters();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('front.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\hired-hero\resources\views/front/resources/job-fairs.blade.php ENDPATH**/ ?>