@extends('front.layouts.app') 

@section('title', 'HiredHeroAI Platform Overview | AI Interview & Career Readiness')
@section('meta_description', 'See how the HiredHeroAI platform brings together AI mock interviews, soft skills assessments, resume support, and insights dashboards for organizations and individuals.')

@section('css')
<style>
    /* General typography on dark sections */
    .section-dark h1,
    .section-dark h2,
    .section-dark h3,
    .section-dark h4,
    .section-dark h5,
    .section-dark h6,
    .section-dark p,
    .section-dark li,
    .section-dark small {
        color: #e5edff;
    }

    .section-dark .text-muted {
        color: #cbd5e1 !important;
    }

    /* Subtle badges on dark background (top chips + Who Uses row) */
    .section-dark .badge.bg-primary-subtle {
        background-color: rgba(59, 130, 246, 0.12); /* soft blue */
        color: #bfdbfe; /* light blue text */
    }

    .section-dark .badge.bg-secondary-subtle {
        background-color: rgba(148, 163, 184, 0.18); /* slate pill */
        color: #e5edff !important;                  /* bright text */
        border: 1px solid rgba(148, 163, 184, 0.6);
    }

    /* Keep primary brand buttons and outline button readable */
    .section-dark .btn-outline-light {
        color: #e5edff;
        border-color: rgba(226, 232, 240, 0.85);
    }

    .section-dark .btn-outline-light:hover {
        background-color: #e5edff;
        color: #020617;
    }
</style>
@endsection

@section('content')
<section id="platform-overview" class="section section-dark py-5" style="padding-top:110px;">
    <div class="container">

        <!-- TOP: Intro + Screenshot -->
        <div class="row align-items-center mb-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="fw-bold mb-3">HiredHeroAI Platform Overview</h1>
                <p class="lead mb-3">
                    HiredHeroAI is an AI-powered career readiness platform that combines mock interviews,
                    soft skills assessments, resume feedback, and communication coaching in one place.
                </p>
                <p class="mb-3">
                    It’s built for both organizations and individual job seekers who need structured,
                    scalable support to become truly job-ready.
                </p>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    <span class="badge rounded-pill bg-primary-subtle text-primary small">AI Mock Interviews</span>
                    <span class="badge rounded-pill bg-primary-subtle text-primary small">Soft Skills &amp; “Skills for Success”</span>
                    <span class="badge rounded-pill bg-primary-subtle text-primary small">Resume &amp; Answer Support</span>
                    <span class="badge rounded-pill bg-primary-subtle text-primary small">Progress Insights</span>
                    <span class="badge rounded-pill bg-primary-subtle text-primary small">Admin Dashboards</span>
                </div>
            </div>

            <div class="col-lg-6 text-center">
                <div class="p-3 p-md-4 rounded-4 border border-opacity-25 border-light bg-transparent d-inline-block w-100" style="max-width:520px;">
                    <div class="text-start small text-muted mb-2 d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-layout-text-window me-1"></i> Platform Dashboard Preview</span>
                        <span class="small">Sample View</span>
                    </div>
                    <div class="ratio ratio-16x9 rounded-3 overflow-hidden">
                        <img src="{{ asset('assets/images/landing/Dashboard.png') }}"
                             alt="HiredHeroAI Dashboard"
                             class="img-fluid">
                    </div>
                </div>
            </div>
        </div>

        <!-- WHAT THE PLATFORM INCLUDES -->
        <div class="mb-5">
            <h2 class="fw-semibold mb-3">What the Platform Includes</h2>
            <p class="text-muted mb-4">
                Everything lives in one place, so staff and learners don’t have to jump between tools.
            </p>

            <div class="row g-3 g-md-4">
                <div class="col-md-6 col-lg-4">
                    <div class="h-100 p-3 p-md-4 rounded-4 border border-opacity-25 border-light bg-transparent">
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-2">
                                <i class="bi bi-mic-fill fs-4 text-primary"></i>
                            </div>
                            <h5 class="mb-0">AI Mock Interviews</h5>
                        </div>
                        <p class="small mb-0">
                            Realistic practice for behavioural, situational, and job-specific questions with instant written feedback.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="h-100 p-3 p-md-4 rounded-4 border border-opacity-25 border-light bg-transparent">
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-2">
                                <i class="bi bi-stars fs-4 text-primary"></i>
                            </div>
                            <h5 class="mb-0">Soft Skills &amp; “Skills for Success”</h5>
                        </div>
                        <p class="small mb-0">
                            Assess communication, problem-solving, confidence, and overall job-readiness with structured scoring.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="h-100 p-3 p-md-4 rounded-4 border border-opacity-25 border-light bg-transparent">
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-2">
                                <i class="bi bi-file-text-fill fs-4 text-primary"></i>
                            </div>
                            <h5 class="mb-0">Resume &amp; Answer Support</h5>
                        </div>
                        <p class="small mb-0">
                            Guidance on how to phrase experience clearly and professionally so learners can update resumes and profiles.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="h-100 p-3 p-md-4 rounded-4 border border-opacity-25 border-light bg-transparent">
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-2">
                                <i class="bi bi-graph-up-arrow fs-4 text-primary"></i>
                            </div>
                            <h5 class="mb-0">Progress Insights</h5>
                        </div>
                        <p class="small mb-0">
                            See where users are strong and where they need more practice with simple, visual insights.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="h-100 p-3 p-md-4 rounded-4 border border-opacity-25 border-light bg-transparent">
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-2">
                                <i class="bi bi-speedometer2 fs-4 text-primary"></i>
                            </div>
                            <h5 class="mb-0">Admin Dashboards</h5>
                        </div>
                        <p class="small mb-0">
                            For organizations: track usage, completions, and trends across cohorts and programs at a glance.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- HOW IT WORKS FOR ORGANIZATIONS -->
        <div class="mb-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="fw-semibold mb-3">How It Works for Organizations</h2>
                    <p class="text-muted mb-4">
                        HiredHeroAI fits into your existing workflows and lets staff spend less time on basic practice and more time on real coaching.
                    </p>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="h-100 p-3 rounded-4 bg-transparent border border-opacity-25 border-light">
                                <div class="badge bg-primary-subtle text-primary mb-2">Step 1</div>
                                <h6 class="mb-1">Admin Portal Access</h6>
                                <p class="small mb-0">
                                    Your organization gets access to a secure admin portal and branded environment.
                                </p>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="h-100 p-3 rounded-4 bg-transparent border border-opacity-25 border-light">
                                <div class="badge bg-primary-subtle text-primary mb-2">Step 2</div>
                                <h6 class="mb-1">Onboard Students &amp; Clients</h6>
                                <p class="small mb-0">
                                    Invite learners in bulk or individually so they can start practicing on their own time.
                                </p>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="h-100 p-3 rounded-4 bg-transparent border border-opacity-25 border-light">
                                <div class="badge bg-primary-subtle text-primary mb-2">Step 3</div>
                                <h6 class="mb-1">AI Practice &amp; Assessments</h6>
                                <p class="small mb-0">
                                    Learners complete mock interviews and skills assessments, generating consistent, structured feedback.
                                </p>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="h-100 p-3 rounded-4 bg-transparent border border-opacity-25 border-light">
                                <div class="badge bg-primary-subtle text-primary mb-2">Step 4</div>
                                <h6 class="mb-1">Use Insights to Coach Smarter</h6>
                                <p class="small mb-0">
                                    Staff use dashboards to target coaching where it matters most and report outcomes to leadership and funders.
                                </p>
                            </div>
                        </div>
                    </div>

                    <p class="small text-muted mt-3 mb-0">
                        This frees staff to focus on employer engagement, barriers to employment, and higher-value support.
                    </p>
                </div>

                <div class="col-lg-6 text-center">
                    <div class="p-3 p-md-4 rounded-4 border border-opacity-25 border-light bg-transparent d-inline-block w-100" style="max-width:480px;">
                        <div class="text-start small text-muted mb-2">
                            <i class="bi bi-people-fill me-1"></i> Organizational View
                        </div>
                        <div class="ratio ratio-16x9 rounded-3 overflow-hidden">
                           <img src="{{ asset('assets/images/landing/UserSide.png') }}"
                                alt="Interview Preparation View"
                                class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HOW IT WORKS FOR INDIVIDUALS -->
        <div class="mb-5">
            <div class="row align-items-center flex-lg-row-reverse">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="fw-semibold mb-3">How It Works for Individuals</h2>
                    <p class="text-muted mb-4">
                        For job seekers using HiredHeroAI on their own, the flow is simple and repeatable.
                    </p>

                    <ol class="small ps-3 mb-3">
                        <li class="mb-2">
                            Sign up and choose the type of interview or assessment you want to practice.
                        </li>
                        <li class="mb-2">
                            Answer questions and receive instant written feedback and suggestions.
                        </li>
                        <li class="mb-2">
                            Repeat as many times as needed until your answers feel confident and natural.
                        </li>
                        <li class="mb-0">
                            Use the feedback to update your resume, cover letter, or LinkedIn profile.
                        </li>
                    </ol>

                    <p class="small text-muted mb-0">
                        The goal is to help you feel prepared, not rehearsed — so you can speak clearly in real interviews.
                    </p>
                </div>

                <div class="col-lg-6 text-center">
                    <div class="p-3 p-md-4 rounded-4 border border-opacity-25 border-light bg-transparent d-inline-block w-100" style="max-width:480px;">
                        <div class="text-start small text-muted mb-2">
                            <i class="bi bi-person-video3 me-1"></i> Learner Practice View
                        </div>
                        <div class="ratio ratio-16x9 rounded-3 overflow-hidden">
                           <img src="{{ asset('assets/images/landing/Interview Prep.png') }}"
                                alt="Interview Preparation View"
                                class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- WHO USES HIREDHEROAI -->
        <div class="mb-5">
            <h2 class="fw-semibold mb-3">Who Uses HiredHeroAI</h2>
            <p class="text-muted mb-4">
                The platform is used by institutions and individuals who need scalable, structured interview and skills support.
            </p>

            <div class="d-flex flex-wrap gap-2">
                <span class="badge rounded-pill bg-secondary-subtle text-light">Colleges &amp; Universities</span>
                <span class="badge rounded-pill bg-secondary-subtle text-light">Workforce Boards &amp; Employment Programs</span>
                <span class="badge rounded-pill bg-secondary-subtle text-light">Nonprofits &amp; Community Organizations</span>
                <span class="badge rounded-pill bg-secondary-subtle text-light">Bootcamps &amp; Training Providers</span>
                <span class="badge rounded-pill bg-secondary-subtle text-light">Individual Job Seekers</span>
            </div>
        </div>

        <!-- CTA: SEE PLATFORM IN ACTION -->
        <div class="mb-4">
            <div class="p-4 p-md-5 rounded-4 border border-opacity-25 border-primary bg-transparent d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                <div>
                    <h3 class="fw-semibold mb-2">See the Platform in Action</h3>
                    <p class="mb-0 small">
                        If you want to see how the platform works for your organization or for yourself,
                        you can book a short walkthrough with our team.
                    </p>
                </div>
                <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="mailto:info@hiredheroai.com?subject=Platform%20Overview%20Demo"
                       class="btn btn-primary btn-lg px-4">
                        Book a Platform Demo
                    </a>
                    <a href="{{ $trialUrl ?? '#' }}" class="btn btn-outline-light btn-lg px-4">
                        Try It as an Individual
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
