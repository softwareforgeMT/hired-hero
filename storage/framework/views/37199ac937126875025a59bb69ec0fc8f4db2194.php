

<?php $__env->startSection('title', 'Skills for Success – Soft Skills Assessment Platform | HiredHeroAI'); ?>
<?php $__env->startSection('meta_description', 'Measure and improve communication, problem-solving, and other core Skills for Success with an AI-powered assessment and practice platform for colleges and workforce programs in Canada and the US.'); ?>

<?php $__env->startSection('css'); ?>
<style>
    .page__content {
        background: #eef2f8;
        color: #0b1325;
    }

    .page__content .section {
        padding: 4.4rem 0;
    }

    /* Headings */
    .page__content h1,
    .page__content h2,
    .page__content h3 {
        color: #0b1325 !important;
        font-weight: 650;
    }

    /* Body text + list items */
    .page__content p,
    .page__content ul li,
    .page__content ol li {
        color: #111827 !important;
        font-size: 0.96rem;
        line-height: 1.5;
    }

    .skills-subtle,
    .skills-hero-subtle {
        color: #4b5563 !important;
        font-size: 0.9rem;
    }

    .skills-list-tight li {
        margin-bottom: 0.12rem;
    }

    /* HERO (less padding, same vibe) */
    .skills-hero {
        padding-top: 95px;      /* was 120px */
        padding-bottom: 28px;
        background: radial-gradient(circle at 20% 0%, #d7e5ff 0%, #c3d4f3 45%, #b4c8ec 100%);
    }

    .skills-hero h1,
    .skills-hero p,
    .skills-hero li,
    .skills-hero span {
        color: #0b1325 !important;
    }

    .skills-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.45rem 0.75rem;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.12);
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #0f172a;
        margin-bottom: 0.6rem;
    }

    .skills-hero-dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: linear-gradient(90deg, #2563eb, #00d4a8);
    }

    .skills-hero h1 {
        font-size: 2rem;
        margin-bottom: 0.45rem;
    }

    .skills-hero-lead {
        font-size: 1rem;
        margin-bottom: 0.6rem;
    }

    .skills-hero-list {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0.8rem;
    }

    .skills-hero-list li {
        display: flex;
        gap: 0.45rem;
        font-size: 0.9rem;
        margin-bottom: 0.15rem;
    }

    .skills-hero-list i {
        color: #22c55e;
        margin-top: 2px;
    }

    .skills-image {
        position: relative;
        width: 100%;
        aspect-ratio: 16 / 9;
        border-radius: 16px;
        overflow: hidden;
        background: #0f172a;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.18);
    }

    .skills-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .section-band {
        background: #dde6f7;
    }

    .skills-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 1rem 1.05rem;
        height: 100%;
        border: 1px solid #e5e7eb;
        box-shadow: 0 5px 16px rgba(15, 23, 42, 0.04);
    }

    .skills-card h3 {
        font-size: 1.02rem;
        margin-bottom: 0.35rem;
        color: #0b1325;
    }

    .skills-card p,
    .skills-card ul li,
    .skills-card ol li {
        color: #1f2933 !important;
        font-size: 0.9rem;
        margin-bottom: 0.15rem;
    }

    @media (max-width: 768px) {
        .skills-hero {
            padding-top: 90px;   /* also reduced on mobile */
            text-align: center;
        }

        .skills-hero-list {
            justify-content: center;
        }
    }
</style>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="page__content">

    <!-- HERO -->
    <section class="section skills-hero">
        <div class="container">
            <div class="row align-items-center gy-4">
                <div class="col-lg-6">
                    <div class="skills-hero-badge">
                        <span class="skills-hero-dot"></span>
                        Skills for Success • Soft Skills Assessment
                    </div>

                    <h1 class="mb-2">Skills for Success – Soft Skills Assessment Platform</h1>
                    <p class="skills-hero-lead">
                        HiredHeroAI helps colleges, workforce boards, and training providers across Canada and the United States
                        measure and improve the real skills behind employability
                    </p>

                    <ul class="skills-hero-list">
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>AI-powered assessments mapped to Skills for Success and employability skills.</span>
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Scenario-based interviews instead of self-reported surveys.</span>
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Reports and dashboards your staff and funders can actually use.</span>
                        </li>
                    </ul>

                    <div class="d-flex flex-wrap gap-2 mb-1">
                        <a href="mailto:info@hiredheroai.com?subject=Book%20a%20Skills%20for%20Success%20Demo"
                           class="btn btn-primary btn-lg px-4">
                            Book a Skills for Success Demo
                        </a>
                    
                    </div>

                  
                </div>

                <div class="col-lg-6">
                    <div class="skills-image">
                        <img src="<?php echo e(asset('assets/images/landing/insights4.png')); ?>"
                             alt="Skills for Success dashboard showing communication and problem-solving scores">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- WHY SKILLS FOR SUCCESS MATTER -->
    <section class="section">
        <div class="container" style="max-width: 1050px;">
            <div class="row gy-3 align-items-start">
                <div class="col-md-7">
                    <h2 class="mb-2">Why Skills for Success Matter</h2>
                    <p class="skills-subtle">
                        Employers in both Canada and the US are clear: technical knowledge alone is not enough.
                        Learners also need core, transferable skills to succeed in modern workplaces, communicate clearly,
                        and represent your program well.
                    </p>
                    <p class="skills-subtle mb-0">
                        The challenge for institutions is simple: how do you <strong>measure</strong> these skills at scale and show
                        improvement to employers and funders — without adding hours of manual assessment?
                    </p>
                </div>
                <div class="col-md-5">
                    <div class="skills-card">
                        <h3 class="mb-1">Skills for Success in Canada & US
                        </h3>
                        <p class="mb-1">
                            The federal Skills for Success framework highlights core, transferable skills such as:
                        </p>
                        <ul class="mb-0 skills-list-tight">
                            <li>Communication</li>
                            <li>Collaboration</li>
                            <li>Problem solving</li>
                            <li>Adaptability</li>
                            <li>Creativity and innovation</li>
                            <li>Digital skills and continuous learning</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PRACTICAL LAYER + WHAT WE ASSESS -->
    <section class="section section-band">
        <div class="container" style="max-width: 1050px;">
            <div class="row mb-3">
                <div class="col-lg-8">
                    <h2 class="mb-1">HiredHeroAI: A Practical Skills for Success Layer</h2>
                    <p class="skills-subtle mb-0">
                        HiredHeroAI uses AI-powered interviews and scenario assessments to evaluate how learners demonstrate Skills
                        for Success in realistic situations – not just on self-reported checklists.
                    </p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="skills-card">
                        <h3>What Our Platform Assesses</h3>
                        <ul class="skills-list-tight ps-3">
                            <li><strong>Clarity of communication:</strong> are answers structured, logical, and easy to follow?</li>
                            <li><strong>Relevance:</strong> does the learner actually answer the question being asked?</li>
                            <li><strong>Professional tone:</strong> do they present themselves in a workplace-appropriate way?</li>
                            <li><strong>Problem solving:</strong> how they approach scenarios, trade-offs, and decisions.</li>
                            <li><strong>Reflection and growth mindset:</strong> whether they can learn from past experiences.</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="skills-card">
                        <h3>More Than a Single Test</h3>
                        <p>
                            Because the platform combines <strong>AI interviews, skills assessments, resume review, and career readiness tools</strong>,
                            you get a more complete picture of each learner’s soft skills and job readiness.
                        </p>
                        <p class="mb-0">
                            Programs can use the same environment for interview practice, Skills for Success assessment,
                            and ongoing coaching — instead of juggling multiple tools.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- USE CASES -->
    <section class="section">
        <div class="container" style="max-width: 1050px;">
            <h2 class="mb-2">Use Cases in Canada and the US</h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="skills-card">
                        <h3>Colleges &amp; Training Providers</h3>
                        <ul class="ps-3 skills-list-tight">
                            <li>Baseline assessment at intake to understand soft-skills gaps.</li>
                            <li>Mid-program checks to see whether interventions are working.</li>
                            <li>Pre-graduation assessments to prepare students for job search and co-op placements.</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="skills-card">
                        <h3>Workforce Development &amp; Employment Programs</h3>
                        <ul class="ps-3 skills-list-tight">
                            <li>Assessment for job-readiness and employability workshops.</li>
                            <li>Data for reports to funders, boards, and government partners.</li>
                            <li>Evidence of improvement in communication and interview skills over time.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES + HOW IT FITS -->
    <section class="section section-band">
        <div class="container" style="max-width: 1050px;">
            <div class="row mb-3">
                <div class="col-lg-8">
                    <h2 class="mb-1">Key Features of the Skills for Success Platform</h2>
                    <p class="skills-subtle mb-0">
                        Designed for programs that need scalable, consistent soft skills assessment — not more manual rubrics.
                    </p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="skills-card">
                        <ul class="ps-3 skills-list-tight">
                            <li><strong>Scenario-based interviews</strong> mapped to Skills for Success and soft skills outcomes.</li>
                            <li><strong>Automated scoring</strong> on communication, relevance, and professionalism.</li>
                            <li><strong>Individual learner reports</strong> showing strengths and focus areas.</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="skills-card">
                        <ul class="ps-3 skills-list-tight">
                            <li><strong>Cohort-level dashboards</strong> for instructors, advisors, and program managers.</li>
                            <li><strong>Customizable rubrics</strong> to align with your existing curriculum or local frameworks.</li>
                            <li><strong>Integrated tools</strong> for AI mock interviews, skills assessments, resume review, and certificates.</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="skills-card">
                        <h3>How It Fits Into Existing Programs</h3>
                        <ol class="ps-3 skills-list-tight mb-0">
                            <li>Learners complete short AI-powered interviews or assessments online.</li>
                            <li>They receive written feedback and suggestions for improvement.</li>
                            <li>Staff review dashboards to see where learners are strong or need support.</li>
                            <li>You export data or use it in reports for internal stakeholders and funders.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- WHY HIREDHEROAI VS ONLY WORKSHOPS/SURVEYS -->
    <section class="section">
        <div class="container" style="max-width: 1050px;">
            <div class="row gy-3">
                <div class="col-md-6">
                    <h2 class="mb-2">Why Use HiredHeroAI Instead of Only Workshops or Surveys?</h2>
                    <ul class="ps-3 skills-list-tight">
                        <li><strong>Scalable:</strong> hundreds of learners can be assessed without adding staff time.</li>
                        <li><strong>Consistent:</strong> every learner is evaluated against the same criteria.</li>
                        <li><strong>Realistic:</strong> interview-style responses mirror what employers see.</li>
                        <li><strong>Actionable:</strong> feedback is specific enough to inform coaching and curriculum design.</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h2 class="mb-2">Outcomes You Can Show to Stakeholders</h2>
                    <p class="skills-subtle">
                        Whether you are working with internal leadership, employers, or government funders, HiredHeroAI helps you
                        speak their language:
                    </p>
                    <ul class="ps-3 skills-list-tight mb-0">
                        <li>Improved communication and interview performance over time.</li>
                        <li>Evidence of progress on Skills for Success and employability skills.</li>
                        <li>Better prepared graduates and participants for interviews and workplace communication.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- FINAL CTA -->
    <section class="section section-band">
        <div class="container" style="max-width: 900px;">
            <div class="row align-items-center gy-3">
                <div class="col-lg-8">
                    <h2 class="mb-2">Ready to Talk About Skills for Success?</h2>
                    
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="mailto:info@hiredheroai.com?subject=Book%20a%20Skills%20for%20Success%20Demo"
                       class="btn btn-primary btn-lg px-4 mb-2">
                        Book a Skills for Success Demo
                    </a>
                    
                </div>
            </div>
        </div>
    </section>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('front.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\hired-hero\resources\views/front/SEO/skills-for-success-soft-skills-platform.blade.php ENDPATH**/ ?>