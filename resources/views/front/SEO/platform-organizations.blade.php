@extends('front.layouts.app')

@section('title', 'HiredHeroAI for Organizations | Colleges, Workforce, Nonprofits')

@section('meta')
<meta name="description" content="Give your students or clients structured interview and soft skills practice, while your staff gets clear data and keeps their time for higher-value coaching.">
@endsection

@section('css')
<style>
  /* Page + sections */
  .page__content {
    background: #e9ecf5; /* slightly darker, still light + clean */
    color: #0f172a;
  }

  .section {
    padding-top: 1.2rem;   /* tighter vertical spacing */
    padding-bottom: 1.2rem;
  }

  /* HERO */
  .org-hero {
    padding-top: 110px;
    padding-bottom: 28px;
    /* darker, more serious blue gradient */
    background: radial-gradient(circle at 20% 0%, #c4d4ea 0%, #aebfdd 55%, #9bacd1 100%);
  }

  .org-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0.75rem;
    border-radius: 999px;
    background: rgba(15, 23, 42, 0.12);
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #0f172a;
    margin-bottom: 0.6rem;
  }
  .org-hero-dot {
    width: 8px;
    height: 8px;
    border-radius: 999px;
    background: linear-gradient(90deg, #2563eb, #00d4a8);
  }
  .org-highlight {
    background: linear-gradient(90deg, #2563eb, #00d4a8);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .org-hero-list {
    list-style: none;
    padding-left: 0;
    margin-bottom: 0.75rem;
  }
  .org-hero-list li {
    display: flex;
    gap: 0.5rem;
    font-size: 0.95rem;
    margin-bottom: 0.2rem;
  }
  .org-hero-list i {
    color: #22c55e;
    margin-top: 2px;
  }

  .org-subtle {
    font-size: 0.85rem;
    color: #64748b;
  }

  /* Image */
  .image-container {
    position: relative;
    width: 100%;
    aspect-ratio: 16/9;
    border-radius: 14px;
    overflow: hidden;
    background: #0f172a;
    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.18);
  }
  .image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  /* Cards */
  .org-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 1rem 1.05rem;   /* slightly tighter inside */
    height: 100%;
    border: 1px solid #e5e7eb;
    box-shadow: 0 5px 16px rgba(15, 23, 42, 0.04);
  }
  .org-card h3 {
    font-size: 1.02rem;
    margin-bottom: 0.35rem;
  }
  .org-card p {
    font-size: 0.9rem;
    margin-bottom: 0.15rem;
    color: #4b5563;
  }
  .org-card ul {
    margin-bottom: 0;
  }
  .org-card ul li {
    font-size: 0.86rem;
    margin-bottom: 0.12rem;
    color: #4b5563;
  }
  .org-card .org-tag {
    display: inline-block;
    font-size: 0.78rem;
    color: #6b7280;
    margin-top: 0.2rem;
  }

  /* Section band */
  .section-band {
    background: #dde9fb;
  }

  @media (max-width: 768px) {
    .org-hero {
      padding-top: 100px;
      text-align: center;
    }
    .org-hero-list {
      justify-content: center;
    }
  }
  
  /* Stronger text hierarchy */
.section h1,
.section h2,
.section h3 {
  color: #0b1325 !important; /* darker slate */
  font-weight: 600;          /* slight bump */
}

/* Subhead copy (like “Same platform…”) */
.org-subtle {
  color: #373f53 !important; /* darker neutral instead of washed gray */
  opacity: 0.95;             /* readable, not ghosted */
}

/* Card text */
.org-card p,
.org-card ul li {
  color: #2e3644 !important; /* darker than #4b5563 */
}

/* Who It’s For tags */
.org-tag {
  color: #475569 !important;
}

  
  
</style>
@endsection

@section('content')
<div class="page__content">

  <!-- HERO -->
  <section class="section org-hero">
    <div class="container">
      <div class="row align-items-center gy-4">
        <div class="col-lg-6">
          <div class="org-hero-badge">
            <span class="org-hero-dot"></span>
            For Colleges, Workforce & Nonprofits
          </div>

          <h1 class="mb-3">
            HiredHeroAI for <span class="org-highlight">Organizations</span>
          </h1>

          <p class="mb-3">
            Give every learner structured interview and soft skills practice — while your staff keeps their time for real coaching.
          </p>

          <ul class="org-hero-list">
            <li>
              <i class="bi bi-check-circle-fill"></i>
              <span>AI mock interviews and skills checks, on-demand.</span>
            </li>
            <li>
              <i class="bi bi-check-circle-fill"></i>
              <span>Staff dashboards that show who actually needs 1:1 time.</span>
            </li>
            <li>
              <i class="bi bi-check-circle-fill"></i>
              <span>Clean evidence of “job-readiness” work for funders and leadership.</span>
            </li>
          </ul>

          <div class="d-flex flex-wrap gap-2 mb-1">
            <a href="mailto:info@hiredheroai.com?subject=Book%20an%20Institutional%20Demo"
               class="btn btn-primary btn-lg px-4">
              Book an Organizational Demo
            </a>
            <a href="mailto:info@hiredheroai.com?subject=Program%20Fit%20Questions"
               class="btn btn-outline-dark">
              Ask About Program Fit
            </a>
          </div>

          <p class="org-subtle mt-1 mb-0">
            Built for career centres, workforce boards, and community employment programs.
          </p>
        </div>

        <div class="col-lg-6">
          <div class="image-container">
            <img src="{{ asset('assets/images/landing/insights4.png') }}"
                 alt="HiredHeroAI dashboard showing cohort interview and skills performance">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- WHO IT'S FOR -->
  <section class="section">
    <div class="container" style="max-width:1000px;">
      <div class="row mb-2">
        <div class="col-lg-8">
          <h2 class="mb-1">Who It’s For</h2>
          <p class="org-subtle mb-0">
            Same platform, tailored to the way your program runs.
          </p>
        </div>
      </div>

      <div class="row g-2"><!-- tighter gaps between boxes -->
        <div class="col-md-4">
          <div class="org-card">
            <h3>Colleges & Universities</h3>
            <p>Warm students up before appointments, fairs, and co-op interviews.</p>
            <span class="org-tag">Career services • Co-op</span>
          </div>
        </div>
        <div class="col-md-4">
          <div class="org-card">
            <h3>Workforce & Employment</h3>
            <p>Assign practice between meetings and see who’s actually ready.</p>
            <span class="org-tag">Workforce boards • Agencies</span>
          </div>
        </div>
        <div class="col-md-4">
          <div class="org-card">
            <h3>Nonprofits & Training</h3>
            <p>Support newcomers, youth, and other groups with repeatable practice.</p>
            <span class="org-tag">Community orgs • Bootcamps</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- WHAT YOUR PROGRAM GETS -->
  <section class="section section-band">
    <div class="container" style="max-width:1000px;">
      <div class="row mb-2">
        <div class="col-lg-8">
          <h2 class="mb-1">What Your Program Gets</h2>
          <p class="org-subtle mb-0">
            Short, focused tools for learners. Clear visibility for staff.
          </p>
        </div>
      </div>

      <div class="row g-2"><!-- tighter gaps -->
        <div class="col-md-4">
          <div class="org-card">
            <h3>For Learners</h3>
            <ul class="ps-3">
              <li>AI mock interviews by role or sector.</li>
              <li>Soft skills / communication checks.</li>
              <li>Written feedback they can act on.</li>
            </ul>
          </div>
        </div>

        <div class="col-md-4">
          <div class="org-card">
            <h3>For Staff</h3>
            <ul class="ps-3">
              <li>Assign practice sets between workshops.</li>
              <li>Dashboards showing usage and gaps.</li>
              <li>Quick triage: who needs 1:1 time.</li>
            </ul>
          </div>
        </div>

        <div class="col-md-4">
          <div class="org-card">
            <h3>For Leadership & Funders</h3>
            <ul class="ps-3">
              <li>Evidence of interview and skills work.</li>
              <li>Program-level engagement trends.</li>
              <li>Exportable summaries for reports.</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- HOW IT FITS INTO YOUR PROGRAMS -->
  <section class="section">
    <div class="container" style="max-width:1000px;">
      <div class="row mb-2">
        <div class="col-lg-8">
          <h2 class="mb-1">How Programs Use HiredHeroAI</h2>
          <p class="org-subtle mb-0">
            Plug it in where practice is missing. No full redesign needed.
          </p>
        </div>
      </div>

      <div class="row g-2"><!-- tighter gaps -->
        <div class="col-md-4">
          <div class="org-card">
            <h3>Between Workshops</h3>
            <p>Required practice between resume / interview sessions so learners don’t show up cold.</p>
          </div>
        </div>

        <div class="col-md-4">
          <div class="org-card">
            <h3>Before Employer Events</h3>
            <p>Short practice sets before job fairs, employer panels, and mock interviews.</p>
          </div>
        </div>

        <div class="col-md-4">
          <div class="org-card">
            <h3>For High-Need Clients</h3>
            <p>Extra reps for anxious, new-to-market, or career-changing clients — without more staff hours.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- INSTITUTIONAL PORTAL -->
  <section class="section" id="portal-features">
    <div class="container">
      <div class="row align-items-center gy-4">
        <div class="col-lg-6">
          <h2 class="mb-1">Your Institutional Portal</h2>
          <p class="org-subtle mb-2">
            Branded for your organization, set up around your programs and sectors.
          </p>

          <div class="row g-2">
            <div class="col-12">
              <div class="org-card">
                <h3>For learners</h3>
                <ul class="ps-3">
                  <li>Mock interview practice (timed, structured).</li>
                  <li>Short skills quizzes tied to employability skills.</li>
                  <li>Resume / answer feedback they can apply immediately.</li>
                </ul>
              </div>
            </div>
            <div class="col-12">
              <div class="org-card">
                <h3>For your team</h3>
                <ul class="ps-3">
                  <li>Dashboards by learner, cohort, and program.</li>
                  <li>Completion and engagement summaries.</li>
                  <li>Downloads you can attach to case notes or reports.</li>
                </ul>
              </div>
            </div>
          </div>

          <a href="mailto:info@hiredheroai.com?subject=Custom%20Portal%20Demo"
             class="btn btn-primary btn-lg px-4 mt-3">
            Get a Portal Walkthrough
          </a>
        </div>

        <div class="col-lg-6">
          <div class="image-container">
            <img src="{{ asset('assets/images/landing/insights6.png') }}"
                 alt="Institutional portal showing learner progress and modules">
          </div>
        </div>
      </div>
    </div>
  </section>

</div>
@endsection
