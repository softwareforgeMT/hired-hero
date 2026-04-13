

<?php $__env->startSection('title', 'Digital Career Readiness Platform for Workforce Boards | HiredHeroAI'); ?>
<?php $__env->startSection('meta_description', 'Give jobseekers interview practice, soft skills assessments, and career readiness tools with an AI-powered platform built for workforce boards, employment programs, and nonprofits in Canada and the US.'); ?>

<?php $__env->startSection('css'); ?>
<style>
/* ========= PAGE WRAPPER ========= */

.page__content.workforce-page {
    background: #f3f4f6;   /* light neutral for sections under hero */
    color: #0f172a;
}

.workforce-page .section {
    padding: 5.6rem 0;
}

/* ========= HERO ========= */

.wf-hero {
    padding-top: 130px;
    padding-bottom: 80px;
    background: radial-gradient(circle at 0% 0%, #111827 0, #020617 55%, #020617 100%);
    position: relative;
    overflow: hidden;
}

/* animated glow behind dashboard image */
.wf-hero::before {
    content: "";
    position: absolute;
    right: -140px;
    top: 50%;
    width: 420px;
    height: 420px;
    background: radial-gradient(circle, rgba(56,189,248,0.22), transparent 65%);
    filter: blur(10px);
    transform: translateY(-50%) scale(0.95);
    animation: wfGlow 12s ease-in-out infinite alternate;
    pointer-events: none;
}

@keyframes wfGlow {
    0% {
        transform: translateY(-52%) scale(0.9);
        opacity: 0.7;
    }
    100% {
        transform: translateY(-48%) scale(1.05);
        opacity: 1;
    }
}

/* make all hero text readable on dark bg */
.wf-hero,
.wf-hero h1,
.wf-hero h2,
.wf-hero h3,
.wf-hero p,
.wf-hero li,
.wf-hero span,
.wf-hero strong {
    color: #e5edff;
}

.wf-hero a {
    color: #e5edff;
}

/* badge above title */
.wf-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .25rem .8rem;
    border-radius: 999px;
    background: rgba(15,23,42,0.8);
    font-size: .78rem;
    letter-spacing: .12em;
    text-transform: uppercase;
}

.wf-hero-dot {
    width: 8px;
    height: 8px;
    border-radius: 999px;
    background: linear-gradient(135deg, #38bdf8, #22c55e);
}

/* heading + copy */
.wf-hero h1 {
    font-size: clamp(2.1rem, 3vw, 2.6rem);
    font-weight: 700;
    margin-bottom: .5rem;
}

.wf-hero-lead {
    font-size: 1.02rem;
    max-width: 34rem;
}

/* bullet list */
.wf-hero-list {
    list-style: none;
    padding-left: 0;
    margin: 0 0 1rem;
}

.wf-hero-list li {
    display: flex;
    gap: .5rem;
    font-size: .94rem;
    margin-bottom: .2rem;
}

.wf-hero-list i {
    margin-top: 2px;
    color: #22c55e;
}

/* subtle line under buttons (hero version) */
.wf-hero .wf-subtle {
    font-size: .9rem;
    color: #cbd5f5;
}

/* hero buttons */
.wf-hero .btn-primary {
    box-shadow: 0 12px 30px rgba(59,130,246,0.35);
}

.wf-hero .btn-outline-dark {
    border-color: rgba(148,163,184,0.9);
    color: #e5edff;
    background: transparent;
}

.wf-hero .btn-outline-dark:hover {
    background: rgba(15,23,42,0.9);
}

/* hero image wrapper */
.wf-hero-visual {
    position: relative;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 18px 45px rgba(15,23,42,0.85);
}

.wf-hero-visual img {
    display: block;
    width: 100%;
    height: auto;
}

/* ========= SECTIONS BELOW HERO ========= */

.wf-section-title {
    font-size: 1.6rem;
    font-weight: 650;
    color: #0f172a;
}

/* generic subtle text for non-hero sections */
.wf-subtle {
    font-size: .9rem;
    color: #4b5563;
}

/* light banded sections */
.section-band {
    background: #e5edf9;
}

/* cards */
.wf-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 1.15rem 1.25rem;
    border: 1px solid #e5e7eb;
    box-shadow: 0 6px 20px rgba(15,23,42,0.04);
    height: 100%;
}

.wf-card h3 {
    font-size: 1.02rem;
    font-weight: 600;
    margin-bottom: .4rem;
    color: #0f172a;
}

.wf-card p,
.wf-card ul li {
    font-size: .9rem;
    color: #1f2933;
}

/* pill label at top of some cards */
.wf-pill {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .2rem .7rem;
    border-radius: 999px;
    background: #eef2ff;
    font-size: .78rem;
    color: #4f46e5;
    margin-bottom: .35rem;
}

.wf-pill i {
    font-size: .9rem;
}

/* numbered steps inside cards */
.wf-step {
    display: flex;
    align-items: flex-start;
    gap: .5rem;
    margin-bottom: .4rem;
}

.wf-step-number {
    width: 22px;
    height: 22px;
    border-radius: 999px;
    background: #111827;
    color: #f9fafb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .78rem;
    flex-shrink: 0;
}

/* ========= RESPONSIVE ========= */

@media (max-width: 991px) {
    .wf-hero {
        padding-top: 120px;
        padding-bottom: 56px;
        text-align: center;
    }

    .wf-hero::before {
        right: -180px;
        top: 65%;
        width: 360px;
        height: 360px;
    }

    .wf-hero-visual {
        margin-top: 1.5rem;
    }

    .wf-hero-list {
        justify-content: center;
        text-align: left;
        margin-inline: auto;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page__content workforce-page">

    <!-- HERO -->
    <section class="section wf-hero">
        <div class="container">
            <div class="row align-items-center gy-4">
                <div class="col-lg-6">
                    <div class="wf-hero-badge">
                        <span class="wf-hero-dot"></span>
                        Workforce Boards • Employment Programs • Nonprofits
                    </div>

                    <h1 class="mb-2">Digital Career Readiness Platform for Workforce Boards</h1>

                    <p class="wf-hero-lead">
                        Give jobseekers structured interview practice and soft skills training, while your team keeps time for
                        real coaching, employer outreach, and placements.
                    </p>

                    <ul class="wf-hero-list">
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>AI-powered mock interviews and soft skills assessments jobseekers can repeat on their own.</span>
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Dashboards that show who is actually ready for employer referrals.</span>
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Reporting you can plug straight into funder and board updates in Canada and the US.</span>
                        </li>
                    </ul>

                    <div class="d-flex flex-wrap gap-2 mb-1">
                        <a href="mailto:info@hiredheroai.com?subject=Workforce%20Programs%20Demo"
                           class="btn btn-primary btn-lg px-4">
                            Book a Workforce Programs Demo
                        </a>
                        <a href="mailto:info@hiredheroai.com?subject=Workforce%20Program%20Questions"
                           class="btn btn-outline-dark">
                            Ask About Program Fit
                        </a>
                    </div>

                    <p class="wf-subtle mb-0">
                        Built for WIOA-style initiatives, Skills for Success employment programs, sector-based training, and
                        community employment services.
                    </p>
                </div>

                <div class="col-lg-6">
                    <div class="wf-hero-visual">
                        <img src="<?php echo e(asset('assets/images/landing/insights4.png')); ?>"
                             alt="HiredHeroAI dashboard for workforce boards showing interview and skills performance">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- WHO IT'S FOR -->
    <section class="section">
        <div class="container" style="max-width:1050px;">
            <div class="row mb-3">
                <div class="col-lg-8">
                    <h2 class="wf-section-title">Who Uses HiredHeroAI</h2>
                    <p class="wf-subtle mb-0">
                        Same platform, different workflows for workforce boards, nonprofits, and sector-based training providers.
                    </p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="wf-card">
                        <span class="wf-pill"><i class="bi bi-diagram-3"></i> Workforce Boards</span>
                        <h3>Pre-employment &amp; job readiness</h3>
                        <p>Make digital practice a requirement between workshops so clients don’t show up cold to employer events.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="wf-card">
                        <span class="wf-pill"><i class="bi bi-people-fill"></i> Nonprofits</span>
                        <h3>Community employment programs</h3>
                        <p>Support newcomers, youth, and career changers with repeatable practice and clear evidence of progress.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="wf-card">
                        <span class="wf-pill"><i class="bi bi-building-check"></i> Training Providers</span>
                        <h3>Sector-based &amp; upskilling</h3>
                        <p>Use industry-specific interview tracks to prepare cohorts for partner employers in IT, healthcare, trades, and more.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- WHAT YOUR PROGRAM GETS -->
    <section class="section section-band">
        <div class="container" style="max-width:1050px;">
            <div class="row mb-3">
                <div class="col-lg-8">
                    <h2 class="wf-section-title">What Your Program Gets</h2>
                    <p class="wf-subtle mb-0">
                        One environment for jobseekers to practice and for staff to see where to focus time.
                    </p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="wf-card">
                        <h3>For Jobseekers</h3>
                        <ul>
                            <li>AI mock interviews by sector or program.</li>
                            <li>Instant feedback on clarity and professionalism.</li>
                            <li>Soft skills / “Skills for Success” style checks.</li>
                            <li>Support on wording for resumes and answers.</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="wf-card">
                        <h3>For Staff</h3>
                        <ul>
                            <li>Dashboards showing usage, strengths, and gaps.</li>
                            <li>Quick view of who is ready for referrals.</li>
                            <li>Program-level trends for cohorts and sites.</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="wf-card">
                        <h3>For Funders &amp; Employers</h3>
                        <ul>
                            <li>Evidence of communication and interview practice.</li>
                            <li>Before / after snapshots over a program cycle.</li>
                            <li>Summaries you can drop into reports and RFPs.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT FITS INTO WORKFLOWS -->
    <section class="section">
        <div class="container" style="max-width:1050px;">
            <div class="row mb-3">
                <div class="col-lg-8">
                    <h2 class="wf-section-title">How Workforce Programs Use HiredHeroAI</h2>
                    <p class="wf-subtle mb-0">
                        You don’t need to rebuild your model – just plug in a digital practice layer where it hurts most.
                    </p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6 col-lg-4">
                    <div class="wf-card">
                        <h3>Pre-employment programs</h3>
                        <div class="wf-step">
                            <span class="wf-step-number">1</span>
                            <p class="mb-0">Enroll clients as part of intake or orientation.</p>
                        </div>
                        <div class="wf-step">
                            <span class="wf-step-number">2</span>
                            <p class="mb-0">Assign practice between workshops and before job fairs.</p>
                        </div>
                        <div class="wf-step">
                            <span class="wf-step-number">3</span>
                            <p class="mb-0">Use dashboards to decide who needs 1:1 time.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="wf-card">
                        <h3>Sector-based training</h3>
                        <div class="wf-step">
                            <span class="wf-step-number">1</span>
                            <p class="mb-0">Set up interview tracks for IT, healthcare, trades, admin, and more.</p>
                        </div>
                        <div class="wf-step">
                            <span class="wf-step-number">2</span>
                            <p class="mb-0">Have participants complete tracks before employer interviews.</p>
                        </div>
                        <div class="wf-step">
                            <span class="wf-step-number">3</span>
                            <p class="mb-0">Share aggregate readiness data with partner employers.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="wf-card">
                        <h3>Government-funded initiatives</h3>
                        <p class="mb-1">
                            Align with WIOA-style outcomes in the US and Skills for Success / employability frameworks in Canada.
                        </p>
                        <ul>
                            <li>Measurable communication and interview scores.</li>
                            <li>Change over time for cohorts and sites.</li>
                            <li>Exports for funder, board, and municipal reports.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- KEY FEATURES + CTA -->
    <section class="section section-band">
        <div class="container" style="max-width:1050px;">
            <div class="row gy-3 align-items-center">
                <div class="col-lg-7">
                    <h2 class="wf-section-title">Key Features for Workforce Boards</h2>
                    <ul class="mb-3">
                        <li><strong>AI mock interviews</strong> that simulate real employer questions in your sectors.</li>
                        <li><strong>Soft skills &amp; communication assessments</strong> aligned with job-readiness goals.</li>
                        <li><strong>Program-specific question banks</strong> – use ours or bring your own content.</li>
                        <li><strong>Client &amp; cohort-level dashboards</strong> for advisors, coordinators, and managers.</li>
                        <li><strong>Progress tracking</strong> across an intake or program cycle.</li>
                        <li><strong>Flexible pricing</strong> that works with grants and limited budgets.</li>
                    </ul>
                </div>
                <div class="col-lg-5">
                    <div class="wf-card">
                        
                        <a href="mailto:info@hiredheroai.com?subject=Workforce%20Programs%20Demo"
                           class="btn btn-primary btn-lg w-100 mb-2">
                            Book a Workforce Programs Demo
                        </a>
                        <div class="wf-subtle text-center">
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('front.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\hired-hero\resources\views/front/SEO/digital-career-readiness-workforce-boards.blade.php ENDPATH**/ ?>