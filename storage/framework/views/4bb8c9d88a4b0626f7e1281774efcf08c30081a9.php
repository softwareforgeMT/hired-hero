 

<?php $__env->startSection('title', 'Resources for Organizations'); ?>

<?php $__env->startSection('css'); ?>
<style>
    .hh-org-hero {
        background: linear-gradient(135deg, #0b1120, #020617);
        color: #ffffff;
        padding: 72px 0 48px;
    }
    .hh-org-eyebrow {
        text-transform: uppercase;
        letter-spacing: .14em;
        font-size: .75rem;
        opacity: .7;
    }
    .hh-org-title {
        font-size: clamp(2rem, 3vw, 2.4rem);
        font-weight: 700;
        margin-bottom: .75rem;
    }
    .hh-org-subtitle {
        max-width: 640px;
        opacity: .85;
    }
    .hh-org-section {
        padding: 48px 0 56px;
        background: var(--light-bg, #f7f9fc);
    }

    .hh-org-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        padding: 0;
        height: 100%;
        box-shadow: 0 14px 35px rgba(15,23,42,.06);
        overflow: hidden;
    }

    .hh-org-card-header {
        padding: 14px 18px;
        display: flex;
        flex-direction: column;
        cursor: pointer;
    }

    .hh-org-card-topline {
        text-transform: uppercase;
        font-size: .7rem;
        letter-spacing: .08em;
    }

    .hh-org-card-title {
        font-size: .9rem;
        font-weight: 600;
        margin: 2px 0 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .hh-org-card-chevron {
        font-size: .9rem;
        opacity: .6;
    }

    .hh-org-card-body {
        padding: 0 18px 14px;
        font-size: .85rem;
    }

    .hh-org-card-body.collapse:not(.show) {
        display: none;
    }

    .hh-org-card-body.collapse.show {
        display: block;
    }

    .hh-org-card-divider {
        border-top: 1px solid #e2e8f0;
        margin: 0 18px 8px;
    }
    
    /* strengthen titles */
.hh-org-card-title span {
    color: #0F172A; /* slate-900 style */
    font-weight: 600;
}

/* tone down muted but keep readable */
.hh-org-card-body,
.hh-org-card-body p,
.hh-org-card-body ul li {
    color: #475569; /* slate-600 */
}

/* header category label (muted but not washed) */
.hh-org-card-topline {
    color: #64748B; /* slate-500 */
    font-weight: 600;
}

/* hover affordance */
.hh-org-card:hover .hh-org-card-title span {
    color: #1E293B; /* slate-700 */
}

/* chevron darken */
.hh-org-card-chevron i {
    color: #64748B;
}

</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="hh-org-hero">
        <div class="container">
            <div class="row align-items-center gy-3">
                <div class="col-lg-7">
                    <div class="hh-org-eyebrow">For Organizations</div>
                    <h1 class="hh-org-title">
                        Outcomes-first resources for colleges, workforce boards &amp; nonprofits.
                    </h1>
                    <p class="hh-org-subtitle">
                        Use these guides, checklists, and playbooks to support job-readiness,
                        document outcomes, and make funding conversations easier.
                    </p>
                </div>
                <div class="col-lg-5 text-lg-end">
                    
                    <a href="mailto:info@hiredheroai.com?subject=Book%20a%20Demo&body=Hello%2C%0A%0AI%20would%20like%20to%20book%20a%20demo."
                       class="btn btn-primary btn-lg mb-2">
                        Book a platform walkthrough
                    </a>
                    <div class="text-muted small">
                        Ideal for colleges, workforce programs, and community organizations.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="hh-org-section">
        <div class="container">
            <h2 class="h5 mb-3">Starter resource categories</h2>

            <div class="row g-3">
                
                <div class="col-md-4">
                    <div class="hh-org-card">
                        <div class="hh-org-card-header"
                             data-bs-toggle="collapse"
                             data-bs-target="#orgPlaybook"
                             aria-expanded="false"
                             aria-controls="orgPlaybook">
                            <div class="hh-org-card-topline text-muted fw-semibold">
                                Playbook
                            </div>
                            <div class="hh-org-card-title">
                                <span>Career Services Modernization</span>
                                <span class="hh-org-card-chevron">
                                    <i class="mdi mdi-chevron-down"></i>
                                </span>
                            </div>
                        </div>

                        <div id="orgPlaybook" class="collapse hh-org-card-body">
                            <div class="hh-org-card-divider"></div>
                            <p class="text-muted mb-2">
                                Most career programs still rely on workshops, drop-ins, and 1:1 appointments.
                                It works for motivated learners, but many stall between sessions and never build
                                enough momentum to be interview-ready.
                            </p>
                            <p class="text-muted mb-2">
                                The gaps tend to be the same:
                            </p>
                            <ul class="text-muted mb-2">
                                <li>Learners procrastinate between appointments.</li>
                                <li>Staff time is spent reacting, not coaching strategically.</li>
                                <li>Progress is hard to measure beyond attendance and case notes.</li>
                                <li>Funders ask for outcomes that are difficult to evidence.</li>
                            </ul>
                            <p class="text-muted mb-2">
                                Modernized career services build in:
                            </p>
                            <ul class="text-muted mb-2">
                                <li>Continuous practice between sessions.</li>
                                <li>Confidence and readiness tracking over time.</li>
                                <li>Skills-based insights (not just “participation”).</li>
                                <li>Clean data to support renewals and new funding.</li>
                            </ul>
                            <p class="text-muted mb-0">
                                HiredHeroAI supports this model by giving learners structured interview and
                                job-readiness practice while giving staff a clear picture of who is practicing,
                                what they are working on, and where coaching time will have the most impact.
                            </p>
                        </div>
                    </div>
                </div>

                
                <div class="col-md-4">
                    <div class="hh-org-card">
                        <div class="hh-org-card-header"
                             data-bs-toggle="collapse"
                             data-bs-target="#orgTemplate"
                             aria-expanded="false"
                             aria-controls="orgTemplate">
                            <div class="hh-org-card-topline text-muted fw-semibold">
                                Template
                            </div>
                            <div class="hh-org-card-title">
                                <span>Outcomes &amp; Reporting Snapshot</span>
                                <span class="hh-org-card-chevron">
                                    <i class="mdi mdi-chevron-down"></i>
                                </span>
                            </div>
                        </div>

                        <div id="orgTemplate" class="collapse hh-org-card-body">
                            <div class="hh-org-card-divider"></div>
                            <p class="text-muted mb-2">
                                Funders and leadership want more than “we ran workshops.” They want to see that
                                learners are actually building skills and moving closer to employment.
                            </p>
                            <p class="text-muted mb-2">
                                An effective outcomes snapshot highlights:
                            </p>
                            <ul class="text-muted mb-2">
                                <li>Engagement: how many learners are practicing and how often.</li>
                                <li>Focus areas: communication, interview skills, employability, and soft skills.</li>
                                <li>Confidence shifts: before/after self-ratings across key competencies.</li>
                                <li>Readiness indicators: learners flagged as “interview-ready” vs. “needs support.”</li>
                                <li>Downstream activity: interviews secured or job search activity where available.</li>
                            </ul>
                            <p class="text-muted mb-2">
                                With HiredHeroAI, this information is generated from actual learner activity:
                                completed mock interviews, question-level feedback, and confidence ratings.
                            </p>
                            <p class="text-muted mb-0">
                                Programs can use this to turn qualitative progress into clear, funder-friendly visuals
                                and talking points for renewals, new grants, and internal reporting.
                            </p>
                        </div>
                    </div>
                </div>

                
                <div class="col-md-4">
                    <div class="hh-org-card">
                        <div class="hh-org-card-header"
                             data-bs-toggle="collapse"
                             data-bs-target="#orgCases"
                             aria-expanded="false"
                             aria-controls="orgCases">
                            <div class="hh-org-card-topline text-muted fw-semibold">
                                Case studies
                            </div>
                            <div class="hh-org-card-title">
                                <span>Pilots &amp; Implementations</span>
                                <span class="hh-org-card-chevron">
                                    <i class="mdi mdi-chevron-down"></i>
                                </span>
                            </div>
                        </div>

                        <div id="orgCases" class="collapse hh-org-card-body">
                            <div class="hh-org-card-divider"></div>
                            <p class="text-muted mb-2">
                                HiredHeroAI is designed to plug into existing workflows rather than replace staff
                                or existing services. Pilot programs typically start small and scale once the team
                                sees how it supports their goals.
                            </p>
                            <p class="text-muted mb-2">
                                Common implementation patterns:
                            </p>
                            <ul class="text-muted mb-2">
                                <li>
                                    <strong>Workforce boards:</strong>
                                    learners complete mock interviews and skills practice between case management
                                    sessions, giving staff insight into gaps before each appointment.
                                </li>
                                <li>
                                    <strong>Colleges &amp; institutes:</strong>
                                    used in career centers, co-op programs, or job-readiness courses to prep students
                                    for internship and job interviews, with dashboards for faculty or advisors.
                                </li>
                                <li>
                                    <strong>Nonprofit employment services:</strong>
                                    supports clients who struggle with procrastination, anxiety, or low confidence by
                                    giving them a safe space to rehearse and build momentum between meetings.
                                </li>
                            </ul>
                            <p class="text-muted mb-2">
                                Programs typically report higher learner engagement, more focused appointments,
                                and cleaner data for reporting and renewals once the platform is embedded into
                                their orientation, workshop, or coaching flow.
                            </p>
                            <p class="text-muted mb-0">
                                During a walkthrough, you can explore which pilot model makes the most sense for
                                your team and how to structure a small rollout before scaling.
                            </p>
                        </div>
                    </div>
                </div>

            </div> 
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('front.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\hired-hero\resources\views/front/resources/organizations.blade.php ENDPATH**/ ?>