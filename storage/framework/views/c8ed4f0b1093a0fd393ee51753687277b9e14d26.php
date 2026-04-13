

<?php $__env->startSection('title','Home'); ?>

<?php $__env->startSection('meta'); ?>
<meta name="description" content="HiredHeroAI is a workforce readiness and employability platform for colleges, workforce boards, training providers, and nonprofits. Institutional portals include AI-scored mock interviews, skills quizzes, resume feedback, certificates, and reporting dashboards. Individuals can also practice mock interviews and presentations.">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('assets/libs/swiper/swiper.min.css')); ?>" rel="stylesheet" type="text/css" />
<style>
    :root {
        --brand-blue: #00A3FF;
        --brand-green: #00D4A8;
        --dark-bg: #0b0f16;
        --dark-bg-2: #12161d;
        --mid-bg: #232831;
        --light-bg: #f7f9fc;
        --card-bg: #ffffff;
        --border-light: #e6e8ec;
        --text-dark: #0f1217;
    }

    /* ===== GLOBAL ===== */
    body {
        background: var(--dark-bg);
        color: #f5f5f5;
    }

    .page__content {
        background: var(--dark-bg);
    }

    /* ===== SECTIONS & BACKGROUNDS ===== */
    .section {
        padding-top: 2.25rem;
        padding-bottom: 2.25rem;
    }

    .section-dark {
        background: var(--dark-bg);
        color: #ffffff;
    }

    .section-dark-2 {
        background: var(--dark-bg-2);
        color: #e8eef6;
    }

    .section-mid {
        background: var(--mid-bg);
        color: #ffffff;
    }

    .section-light {
        background: var(--light-bg);
        color: var(--text-dark);
    }

    .section-light p,
    .section-light h1,
    .section-light h2,
    .section-light h3,
    .section-light h4,
    .section-light h5,
    .section-light h6,
    .section-light span,
    .section-light li {
        color: var(--text-dark);
    }

    .section-light .text-muted {
        color: #4b5563 !important;
    }

    /* Slightly tighter spacing for some stacked sections */
    .targets-section,
    #screenshots,
    #differentiators,
    #why-hired-hero-ai,
    #individual-users {
        padding-top: 1.75rem;
        padding-bottom: 1.75rem;
    }

    /* Links on dark */
    .section-dark a:not(.btn),
    .section-dark-2 a:not(.btn),
    .section-mid a:not(.btn),
    #hero a:not(.btn) {
        color: #d1e9ff;
    }

    .section-dark a:not(.btn):hover,
    .section-dark-2 a:not(.btn):hover,
    .section-mid a:not(.btn):hover {
        color: #ffffff;
    }

    /* ===== BUTTONS ===== */
    .btn-primary {
        background: linear-gradient(90deg, var(--brand-blue) 0%, var(--brand-green) 100%);
        border: 0;
        border-radius: 12px;
        color: #fff;
        font-weight: 600;
    }

    .btn-primary:hover {
        filter: brightness(1.06);
    }

    .btn-warning {
        background: linear-gradient(90deg, #FFD05A 0%, #00A3FF 100%);
        border: 0;
        border-radius: 12px;
        color: #fff;
        font-weight: 600;
    }

    .btn-warning:hover {
        filter: brightness(1.06);
    }

    .btn-success {
        border-radius: 12px;
    }

    /* ===== HERO ===== */
    #hero {
        position: relative;
        overflow: visible;
        /* LET THE CIRCLES SHOW */
        min-height: 70vh;
        color: #fff;
    }

    /* Background image */
    #hero .hero-bg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Dark overlay */
    #hero .hero-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.80);
    }

    /* Main content container (overrides py-5) */
    #hero .container-1 {
        position: relative;
        z-index: 2;
        padding-top: 3.5rem;
        padding-bottom: 4.75rem;
    }

    /* Highlight text */
    .hero-highlight {
        color: var(--brand-blue);
        font-weight: 800;
    }

    /* Floating circular images (desktop only) */
    .hero-floating-img {
        position: absolute;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, 0.9);
        z-index: 1;
    }

    .hero-floating-left,
    .hero-floating-right {
        bottom: -24px;
        /* slightly below the content area */
    }

    .hero-floating-left {
        left: 2%;
    }

    .hero-floating-right {
        right: 2%;
    }

    .hero-subgroup {
        margin-top: 0.75rem;
        /* bump this up to 1rem or 1.25rem if you want more space */
    }


    /* ===== TARGET CLIENT CAROUSEL ===== */
    .targets-section {
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }

    .targets-swiper {
        padding: 0.5rem 0 1rem;
    }

    .target-card {
        background: #1a2129;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.10);
        padding: 1.5rem;
        height: 100%;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
    }

    .target-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.75rem;
        border-radius: 999px;
        background: rgba(0, 0, 0, 0.5);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #cbd5f5;
        margin-bottom: 0.75rem;
    }

    .targets-badge-dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: linear-gradient(90deg, var(--brand-blue), var(--brand-green));
    }

    .target-card h4 {
        font-size: 1.05rem;
        margin-bottom: 0.4rem;
        color: #ffffff;
    }

    .target-card p {
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
        color: #cfd7f0;
    }

    .target-meta {
        font-size: 0.8rem;
        opacity: 0.85;
    }

    /* ===== STATS ===== */
    .stats-item {
        text-align: center;
        margin-bottom: 1rem;
    }

    .stats-item strong {
        display: block;
        font-size: 1.3rem;
    }

    /* ===== CARDS / TABLES ===== */
    .simple-card {
        background: var(--card-bg);
        border-radius: 16px;
        border: 1px solid var(--border-light);
        padding: 1.75rem;
        box-shadow: 0 8px 24px rgba(16, 24, 40, 0.06);
    }

    .chatgpt-compare .table {
        margin-bottom: 0;
        background: #fff;
    }

    .chatgpt-compare .table th {
        background: #eef2f7;
        color: var(--text-dark);
    }

    .chatgpt-compare .table td {
        background: #ffffff;
        color: var(--text-dark);
    }

    .chatgpt-compare .table td,
    .chatgpt-compare .table th {
        border-color: var(--border-light) !important;
    }

    /* ===== IMAGES ===== */
    .image-container {
        position: relative;
        width: 100%;
        aspect-ratio: 16/9;
        border-radius: 12px;
        overflow: hidden;
        background: #000;
    }

    .image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* ===== STEPS ===== */
    .step-card {
        background: #1a2129;
        border-radius: 14px;
        padding: 1.5rem 1rem;
        margin-bottom: 1.5rem;
    }

    .step-number {
        display: inline-block;
        width: 32px;
        height: 32px;
        line-height: 32px;
        border-radius: 50%;
        background: #fff;
        color: #000;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    /* ===== NAV OVERRIDE ===== */
    .navbar,
    .navbar-nav .nav-link {
        color: #ffffff;
    }

    .navbar-nav .nav-link.active,
    .navbar-nav .nav-link:focus-visible {
        color: var(--brand-blue) !important;
    }

    /* ===== MOBILE TUNING (phones & small tablets) ===== */
    @media (max-width: 768px) {

        /* Overall section spacing: tighter on mobile */
        .section {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        /* HERO: make it readable & not gigantic */
        #hero .container-1 {
            padding-top: 2.25rem;
            padding-bottom: 2.5rem;
            text-align: center;
        }

        #hero h1 {
            font-size: 1.9rem;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        #hero .lead {
            font-size: 0.95rem;
            margin-bottom: 1.25rem;
        }

        #hero .d-flex.flex-column.flex-sm-row {
            gap: 0.75rem;
        }

        #hero .small {
            font-size: 0.8rem;
        }

        /* Floating hero images: hide on mobile to avoid chaos */
        .hero-floating-img {
            display: none;
        }

        /* Targets / cards */
        .targets-section {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        .targets-swiper {
            padding: 0.25rem 0 0.75rem;
        }

        .target-card {
            padding: 1.25rem;
        }

        /* Stats */
        .stats-item {
            margin-bottom: 0.75rem;
        }

        .stats-item strong {
            font-size: 1.15rem;
        }

        /* Screenshots section */
        #screenshots .image-container {
            aspect-ratio: 4 / 3;
            margin-bottom: 0.75rem;
        }

        #screenshots h5 {
            font-size: 1rem;
            margin-bottom: 0;
        }

        /* Differentiator cards */
        #differentiators .simple-card {
            padding: 1.25rem;
            margin-bottom: 0.75rem;
        }

        /* Individual users image */
        #individual-users .image-container {
            margin-top: 1rem;
            aspect-ratio: 4 / 3;
        }

        /* Step cards */
        .step-card {
            padding: 1.25rem 0.75rem;
            margin-bottom: 0.75rem;
        }

        .step-number {
            width: 28px;
            height: 28px;
            line-height: 28px;
            font-size: 0.85rem;
        }
    }

    .hero-video-wrapper {
        position: relative;
        width: 100%;
        max-width: 520px;
        margin-left: auto;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 18px 45px rgba(0, 0, 0, 0.65);
        border: 1px solid rgba(255, 255, 255, 0.12);
        background: #000;
    }

    .hero-video-inner {
        position: relative;
        padding-top: 56.25%;
        /* 16:9 */
    }

    .hero-video-inner iframe {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }

    @media (max-width: 768px) {
        .hero-video-wrapper {
            margin: 1.75rem auto 0;
        }
    }
</style>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="page__content">
    <?php
    $trialUrl = \Illuminate\Support\Facades\Route::has('trial.start')
    ? route('trial.start')
    : (\Illuminate\Support\Facades\Route::has('mock.job-details.create')
    ? route('mock.job-details.create')
    : url('/trial/start'));
    ?>

    <!-- HERO -->
    <section id="hero" class="section section-dark d-flex align-items-start">
        <img
            src="<?php echo e(asset('images/home/home2.png')); ?>"
            alt="Students and job seekers practicing workforce readiness skills on HiredHeroAI"
            class="hero-bg" />
        <div class="hero-overlay"></div>

        <img src="<?php echo e(asset('images/hero-lady-left.png')); ?>"
            alt="Learner practicing job interview skills"
            class="hero-floating-img hero-floating-left">
        <img src="<?php echo e(asset('images/hero-student-right.png')); ?>"
            alt="Student reviewing AI-powered feedback"
            class="hero-floating-img hero-floating-right">
        <div class="container mb-5" style="margin-top: 0px;">
            <div class="row">
                <div class="col-lg-12 col-md-12 text-center">
                    <?php if(session('new_user_banner')): ?>
                    <div class="alert alert-success text-center mb-4" style="max-width:600px;margin: auto;">
                        🎉 Discount for first-time users <strong>applied at checkout</strong>.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">

                <div class="container container-1 py-4 position-relative">
                    <div class="row align-items-center justify-content-between">
                        
                        <div class="col-lg-6 col-md-10 text-center text-md-start mb-4 mb-lg-0">
                            <h1 class="fw-bold mb-4">
                                Help More Jobseekers Get Hired
                                <span class="hero-highlight">Without Burning Out Your Staff</span>
                            </h1>

                            <div class="hero-subgroup">
                                <p class="lead mb-3">
                                    One portal for colleges and workforce programs to measure and prove student job-readiness.
                                </p>

                                <div class="d-flex flex-column flex-sm-row align-items-center gap-2 mb-2">
                                    <a href="mailto:info@hiredheroai.com?subject=Book%20an%20Institutional%20Demo"
                                        class="btn btn-primary btn-lg px-4">
                                        Book Institutional Demo
                                    </a>

                                    <a href="<?php echo e($trialUrl); ?>" class="btn btn-outline-light btn-lg px-4" style="border-radius:12px;">
                                        Try as an Individual for Free
                                    </a>
                                </div>

                                <p class="small mb-0 mt-2 text-white-50">
                                    Outcome reports for funders, accreditation, and leadership.
                                </p>
                            </div>
                        </div>

                        
                        <div class="col-lg-5 col-md-10 mx-auto">
                            <div class="hero-video-wrapper" style="max-width:420px; margin-left:auto;">
                                <div class="hero-video-inner" style="border-radius:14px; overflow:hidden;">
                                    <iframe
                                        src="https://www.youtube.com/embed/7AcP8LKElaI?rel=0&modestbranding=1&showinfo=0"
                                        title="Workforce Readiness Platform"
                                        loading="lazy"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MISSION -->
    <section id="mission" class="section section-dark py-5">
        <div class="container">
            <div class="row justify-content-center text-center mb-4">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-2">Our Mission</h2>
                    <p class="text-primary fw-semibold mb-3">
                        Empowering students. Supporting staff. Strengthening communities.
                    </p>
                    <p class="lead mb-0">
                        Our mission is to give every learner in North America the skills, confidence,
                        and support they deserve — while giving overworked career and employment staff
                        the tools to serve more people without burning out.
                    </p>
                </div>
            </div>

            <div class="row g-3 g-lg-4 justify-content-center">
                <div class="col-md-4">
                    <div class="p-3 p-lg-4 h-100 rounded-4 border border-opacity-25 border-light bg-transparent">
                        <h5 class="mb-2">For Students &amp; Job Seekers</h5>
                        <p class="small mb-0">
                            Build confidence with AI-powered practice and feedback.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-3 p-lg-4 h-100 rounded-4 border border-opacity-25 border-light bg-transparent">
                        <h5 class="mb-2">For Overworked Staff</h5>
                        <p class="small mb-0">
                            Automate repetitive coaching so teams can focus on real conversations.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-3 p-lg-4 h-100 rounded-4 border border-opacity-25 border-light bg-transparent">
                        <h5 class="mb-2">For Communities &amp; Economy</h5>
                        <p class="small mb-0">
                            Improve job outcomes and show measurable impact to funders.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TARGET CLIENT CAROUSEL -->
    <section class="section section-dark-2 targets-section">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
                <div>
                    <h5 class="mb-1">Our Clients</h5>
                    <p class="mb-0 text-muted" style="color: rgba(255,255,255,0.7) !important;">
                        HiredHeroAI is focused on workforce and career-readiness programs.
                    </p>
                </div>
                <div class="small text-md-end" style="color: rgba(255,255,255,0.65);">
                    Swipe → to view all segments
                </div>
            </div>

            <div class="swiper targets-swiper">
                <div class="swiper-wrapper">

                    <!-- Colleges & Universities -->
                    <div class="swiper-slide">
                        <div class="target-card">
                            <div class="target-pill">
                                <span class="targets-badge-dot"></span>
                                Primary Buyer
                            </div>
                            <h4>Colleges &amp; Universities</h4>
                            <p class="mb-1">
                                Career centers, CTE departments, and continuing ed teams needing scalable practice and feedback.
                            </p>
                            <div class="target-meta">
                                Use cases: career readiness, co-op prep, capstone projects, international student support.
                            </div>
                        </div>
                    </div>

                    <!-- Workforce Development Boards -->
                    <div class="swiper-slide">
                        <div class="target-card">
                            <div class="target-pill">
                                <span class="targets-badge-dot"></span>
                                Workforce
                            </div>
                            <h4>Workforce Development Boards</h4>
                            <p class="mb-1">
                                Workforce boards and providers that must prove skill gains across funded cohorts.
                            </p>
                            <div class="target-meta">
                                Use cases: youth employment, reskilling, upskilling, employment services.
                            </div>
                        </div>
                    </div>

                    <!-- Training Providers & Bootcamps -->
                    <div class="swiper-slide">
                        <div class="target-card">
                            <div class="target-pill">
                                <span class="targets-badge-dot"></span>
                                Training Partners
                            </div>
                            <h4>Training Providers &amp; Bootcamps</h4>
                            <p class="mb-1">
                                Bootcamps and private training institutes that promise job placement and need evidence.
                            </p>
                            <div class="target-meta">
                                Use cases: job placement pipelines, career services, employer demos.
                            </div>
                        </div>
                    </div>

                    <!-- Nonprofits & Community Agencies -->
                    <div class="swiper-slide">
                        <div class="target-card">
                            <div class="target-pill">
                                <span class="targets-badge-dot"></span>
                                Community Programs
                            </div>
                            <h4>Nonprofits &amp; Community Agencies</h4>
                            <p class="mb-1">
                                Agencies serving newcomers, women, youth, and other equity-seeking groups.
                            </p>
                            <div class="target-meta">
                                Use cases: newcomer employment, women’s programs, community workforce projects.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>


    <!-- STATS -->
    <section class="section section-dark">
        <div class="container">
            <h5 class="fw-semibold mb-4 text-center">
                Powering <span style="color: var(--brand-blue);">workforce readiness</span> across learners and programs
            </h5>

            <div class="row justify-content-center">
                <div class="col-6 col-md-4">
                    <div class="stats-item">
                        <strong>1,000+</strong>
                        <span>Learners Trained</span>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="stats-item">
                        <strong>5,000+</strong>
                        <span>AI-Scored Sessions</span>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="stats-item">
                        <strong>50+</strong>
                        <span>Job Roles & Pathways</span>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mt-3">
                <div class="col-12 col-sm-4">
                    <div class="stats-item">
                        <strong>+2.1</strong>
                        <span>avg gain on 4-point rubric</span>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="stats-item">
                        <strong>94%</strong>
                        <span>report higher interview confidence</span>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="stats-item">
                        <strong>2–4 hrs</strong>
                        <span>practice per learner, trackable</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BUILT FOR INSTITUTIONS -->
    <section class="section section-mid" id="colleges">
        <div class="container">
            <div class="row align-items-center gy-4">

                <div class="col-lg-6">
                    <h2 class="fw-semibold mb-3">Built for Colleges, Workforce Boards & Training Providers</h2>
                    <p class="mb-3">
                        Turn “we help students with resumes and mock interviews” into a measurable,
                        reportable workforce-readiness program — without hiring more staff.
                    </p>

                    <ul class="list-unstyled mb-4">
                        <li class="mb-2">✔ Mock interviews tied to real job roles</li>
                        <li class="mb-2">✔ Skills quiz scoring & exportable reports</li>
                        <li class="mb-2">✔ Resume feedback tracked per learner</li>
                        <li class="mb-2">✔ Program dashboards for cohorts & campuses</li>
                        <li>✔ Certificates & evidence for funders & accreditation</li>
                    </ul>

                    <a href="mailto:info@hiredheroai.com?subject=Institutional%20Demo"
                        class="btn btn-warning btn-lg px-4">
                        Book Institutional Demo
                    </a>
                </div>

                <div class="col-lg-6 text-center">
                    <div class="p-3 p-md-4 rounded-4 border border-opacity-25 border-light bg-transparent d-inline-block w-100" style="max-width:520px;">
                        <div class="text-start small text-muted mb-2 d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-layout-text-window me-1"></i> Dashboard Preview</span>
                            <span class="small">Sample View</span>
                        </div>
                        <div class="ratio ratio-16x9 rounded-3 overflow-hidden">
                            <img src="<?php echo e(asset('assets/images/landing/Dashboard.png')); ?>"
                                alt="Institutional Dashboard"
                                class="img-fluid">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- REPORTING / DASHBOARDS -->
    <section class="section section-dark-2">
        <div class="container">
            <h3 class="fw-semibold mb-3">What the Platform Includes</h3>
            <p class="text-white-50 mb-4">Everything your team needs, in one portal.</p>

            <div class="row g-3 g-md-4">

                <div class="col-md-6 col-lg-3">
                    <div class="h-100 p-3 p-md-4 rounded-4 border border-opacity-25 border-light bg-transparent">
                        <h5 class="mb-1">AI Mock Interviews</h5>
                        <p class="small mb-0">Practice behavioral & situational questions with instant scoring.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="h-100 p-3 p-md-4 rounded-4 border border-opacity-25 border-light bg-transparent">
                        <h5 class="mb-1">Soft Skills & Job-Readiness</h5>
                        <p class="small mb-0">Assess communication, clarity, and overall job-readiness.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="h-100 p-3 p-md-4 rounded-4 border border-opacity-25 border-light bg-transparent">
                        <h5 class="mb-1">Progress Insights</h5>
                        <p class="small mb-0">Simple dashboards show strengths & gaps per learner.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="h-100 p-3 p-md-4 rounded-4 border border-opacity-25 border-light bg-transparent">
                        <h5 class="mb-1">Admin Dashboards</h5>
                        <p class="small mb-0">Track cohorts, programs, credit usage & completions.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- WHAT SETS US APART -->
    <section class="section section-light" id="differentiators">
        <div class="container">
            <div class="text-center mb-4">
                <h3 class="fw-semibold mb-2">What Sets HiredHeroAI Apart</h3>
                <p class="text-muted mb-0">
                    It’s not “another AI tool” or “just mock interviews” — it’s built to make
                    institutional programs look strong on outcomes, not anecdotes.
                </p>
            </div>

            <div class="row g-4">
                <!-- 1. Institution-first -->
                <div class="col-md-6 col-lg-3">
                    <div class="simple-card h-100">
                        <h5 class="mb-2">Institution-First Design</h5>
                        <p class="text-muted mb-2">
                            Custom portals, roles, and dashboards built for colleges, workforce boards,
                            training providers, and nonprofits.
                        </p>
                        <p class="small text-muted mb-0">
                            One URL, your branding, your cohorts, your reporting.
                        </p>
                    </div>
                </div>

                <!-- 2. Full stack -->
                <div class="col-md-6 col-lg-3">
                    <div class="simple-card h-100">
                        <h5 class="mb-2">Full Employability Stack</h5>
                        <p class="text-muted mb-2">
                            Portals combine mock interviews, skills quizzes, resume feedback, certificates,
                            and reports, so you’re not juggling multiple tools or spreadsheets.
                        </p>
                        <p class="small text-muted mb-0">
                            One learner login, four core job-readiness activities.
                        </p>
                    </div>
                </div>

                <!-- 3. Proof -->
                <div class="col-md-6 col-lg-3">
                    <div class="simple-card h-100">
                        <h5 class="mb-2">Proof, Not Just Practice</h5>
                        <p class="text-muted mb-2">
                            Every mock, quiz, and resume review feeds into dashboards and exports that
                            leadership and funders can actually use in reports and audits.
                        </p>
                        <p class="small text-muted mb-0">
                            Hours practiced, scores, completions, all traceable.
                        </p>
                    </div>
                </div>

                <!-- 4. Low lift -->
                <div class="col-md-6 col-lg-3">
                    <div class="simple-card h-100">
                        <h5 class="mb-2">Low Lift for Staff</h5>
                        <p class="text-muted mb-2">
                            Learners practice asynchronously. Staff assign activities and pull reports,
                            instead of spending hours running 1:1 mock interviews manually.
                        </p>
                        <p class="small text-muted mb-0">
                            Less “hand-holding”, more scalable support.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- INDIVIDUAL USERS (B2C) -->
    <section class="section section-dark" id="individual-users">
        <div class="container">
            <div class="row align-items-center gy-4">
                <div class="col-lg-6">
                    <h6 class="text-uppercase text-success mb-2">For Individual Job Seekers & Students</h6>
                    <h3 class="mb-3">Mock interviews and presentations, without waiting on your school.</h3>
                    <p class="text-white-50">
                        Even if your college or workforce program isn’t using HiredHeroAI yet, you can still train on your own.
                        Paste a job description, answer timed mock interview questions, or rehearse a presentation and get
                        instant feedback on your structure, clarity, and confidence.
                    </p>
                    <ul class="list-unstyled text-white-50 mb-4">
                        <li>✔ Mock interview practice with AI scoring and tips</li>
                        <li>✔ Presentation rehearsal with feedback on clarity and pacing</li>
                    </ul>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?php echo e($trialUrl); ?>" class="btn btn-primary">
                            Start Mock Interview
                        </a>
                        <a href="<?php echo e(route('presentation.create')); ?>" class="btn btn-success">
                            Practice a Presentation
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="image-container">
                        <img src="<?php echo e(asset('assets/images/landing/Interview Prep.png')); ?>"
                            alt="Individual learner using HiredHeroAI for mock interview and presentation practice">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3 STEP FLOWS -->
    <section class="section section-dark-2">
        <div class="container">
            <div class="text-center mb-4">
                <h3 class="mb-2">How Individuals Use HiredHeroAI in 3 Simple Steps</h3>
            </div>

            <div class="row text-center mb-3">
                <div class="col-12 mb-2">
                    <h5>MOCK INTERVIEWS</h5>
                </div>
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-number">01</div>
                        <p class="mb-0">Sign up or log in</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-number">02</div>
                        <p class="mb-0">Paste a job description or choose a role</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-number">03</div>
                        <p class="mb-0">Answer timed questions and get scored with feedback</p>
                    </div>
                </div>
            </div>

            <div class="row text-center mt-4">
                <div class="col-12 mb-2">
                    <h5>PRESENTATIONS</h5>
                </div>
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-number">01</div>
                        <p class="mb-0">Sign up or log in</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-number">02</div>
                        <p class="mb-0">Type a brief overview of your topic</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-number">03</div>
                        <p class="mb-0">Click start and rehearse with feedback</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CHATGPT VS HIREDHEROAI -->
    <section class="section section-light" id="compare">
        <div class="container">
            <div class="chatgpt-compare simple-card">
                <h2 class="mb-3 text-center fw-bold">
                    We Asked ChatGPT:
                    <span class="text-success">“What makes HiredHeroAI different from you?”</span>
                </h2>
                <p class="text-muted text-center mb-4">
                    In other words: why does a college or workforce program need a platform, not just a chatbot?
                </p>

                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Feature</th>
                                <th>ChatGPT</th>
                                <th>HiredHeroAI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Admin dashboards for schools/orgs</td>
                                <td>❌</td>
                                <td>✅</td>
                            </tr>
                            <tr>
                                <td>Program / cohort-level reporting</td>
                                <td>❌</td>
                                <td>✅</td>
                            </tr>
                            <tr>
                                <td>Mock interview tracking per learner</td>
                                <td>❌</td>
                                <td>✅</td>
                            </tr>
                            <tr>
                                <td>Skills quiz scores & exports</td>
                                <td>❌</td>
                                <td>✅</td>
                            </tr>
                            <tr>
                                <td>Resume feedback history by learner</td>
                                <td>❌</td>
                                <td>✅</td>
                            </tr>
                            <tr>
                                <td>Portal branding for funded programs / partners</td>
                                <td>❌</td>
                                <td>✅</td>
                            </tr>
                            <tr>
                                <td>Certificates & reports for funders</td>
                                <td>❌</td>
                                <td>✅</td>
                            </tr>
                            <tr>
                                <td>Built-in credit usage & program control</td>
                                <td>❌</td>
                                <td>✅</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-center">
                    <p class="fs-5 mb-1">This isn’t another generic AI tool.</p>
                    <p class="fw-bold text-success mb-0">
                        It’s an institutional-grade workforce readiness platform that proves outcomes to deans, directors, and funders.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- WHY IT WORKS -->
    <section class="section section-light" id="why-hired-hero-ai">
        <div class="container">
            <div class="row align-items-center gy-4">
                <div class="col-md-5 text-center">
                    <img src="<?php echo e(asset('assets/images/landing/HappyLady.png')); ?>"
                        alt="Professional woman confidently prepared for her job interview"
                        class="img-fluid rounded-circle shadow"
                        width="400" height="400">
                </div>
                <div class="col-md-7">
                    <div class="simple-card">
                        <h3 class="fs-4 mb-3 text-primary fw-semibold">Why HiredHeroAI Works for Institutions</h3>
                        <p class="text-muted mb-4">
                            Workshops and ad-hoc mock interviews don’t scale. HiredHeroAI lets you deliver consistent,
                            trackable interview practice, skills quizzes, and resume feedback; With the certificates
                            and reports leadership and funders actually care about.
                        </p>
                        <a href="mailto:info@hiredheroai.com?subject=Workforce%20Readiness%20Platform%20Demo"
                            class="btn btn-warning btn-lg px-4">
                            Book a Demo With Our Team →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script src="<?php echo e(asset('assets/libs/swiper/swiper.min.js')); ?>"></script>


<script src="<?php echo e(asset('assets/js/home-swiper.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('front.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\hired-hero\resources\views/front/org.blade.php ENDPATH**/ ?>