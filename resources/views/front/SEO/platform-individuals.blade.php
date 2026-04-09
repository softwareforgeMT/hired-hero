@extends('front.layouts.app')

@section('title', 'HiredHeroAI for Individuals | Practice Interviews & Build Confidence')
@section('meta_description', 'Use HiredHeroAI to practice interviews, get feedback on your answers, and build the communication skills you need to feel confident in real job interviews.')

@section('content')
<section id="individuals" class="section section-dark">
    <div class="container" style="padding-top:20px; padding-bottom:70px; max-width:1100px;">

        <!-- TOP: Copy + Visual -->
        <div class="row align-items-center mb-4">
            <!-- LEFT: Text -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="mb-3">Interview practice that feels real.</h1>

                <p class="lead mb-2">
                    Answer real interview questions out loud and get instant, clear feedback.
                </p>
                <p class="mb-3">
                    Short, focused sessions to tighten your answers, calm your nerves, and walk into interviews prepared.
                </p>

                <ul class="small mb-3 ps-3">
                    <li>Pick a role or industry.</li>
                    <li>Record your answers in your own words.</li>
                    <li>See concrete suggestions to improve.</li>
                </ul>

                <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3 mt-2">
                    <a href="{{ route('user.register') }}" class="btn btn-primary">
                        Start Practicing in 2 Minutes
                    </a>
                    <div class="small text-muted">
                        No long setup. Just sign up, choose a practice set, and hit start.
                    </div>
                </div>
            </div>

            <!-- RIGHT: Screenshot / Illustration -->
            <div class="col-lg-6 text-center">
                <div class="p-3 p-md-4 rounded-4 shadow-lg bg-dark bg-opacity-25">
                    <img
                        src="{{ asset('assets/images/bg-auth.jpg') }}"
                        alt="HiredHeroAI interview practice screen"
                        class="img-fluid rounded-3"
                    >
                </div>
                <p class="small text-muted mt-2 mb-0">
                    See your answers, feedback, and progress in one place.
                </p>
            </div>
        </div>

        <!-- WHY / WHAT / WHO -->
        <div class="row g-4 mb-4">
            <div class="col-lg-4">
                <div class="h-100 p-3 p-md-4 rounded-4 shadow-sm bg-dark bg-opacity-25">
                    <div class="d-flex align-items-center mb-2">
                        <span class="fs-3 me-2">🤖</span>
                        <h2 class="h5 mb-0">Why Practice With AI First</h2>
                    </div>
                    <ul class="mb-0 small">
                        <li>Practice anytime – no scheduling or awkward calls.</li>
                        <li>Get written feedback, not “you’ll be fine.”</li>
                        <li>Repeat answers until they feel natural.</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="h-100 p-3 p-md-4 rounded-4 shadow-sm bg-dark bg-opacity-25">
                    <div class="d-flex align-items-center mb-2">
                        <span class="fs-3 me-2">🎯</span>
                        <h2 class="h5 mb-0">What You Can Work On</h2>
                    </div>
                    <ul class="mb-0 small">
                        <li>Common & behavioural questions.</li>
                        <li>Role- and industry-specific answers.</li>
                        <li>How you explain gaps, pivots, and strengths.</li>
                        <li>Storytelling using your real experience.</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="h-100 p-3 p-md-4 rounded-4 shadow-sm bg-dark bg-opacity-25">
                    <div class="d-flex align-items-center mb-2">
                        <span class="fs-3 me-2">🧑‍🏫</span>
                        <h2 class="h5 mb-0">Who It’s For</h2>
                    </div>
                    <ul class="mb-0 small">
                        <li>Students & new grads.</li>
                        <li>Career changers.</li>
                        <li>Newcomers practicing in English.</li>
                        <li>People returning after a break or health issues.</li>
                        <li>Anyone who feels rusty or anxious in interviews.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- HOW TO GET STARTED -->
        <div class="mt-4">
            <h2 class="h4 mb-3">Get Started in Three Short Sessions</h2>
            <p class="small mb-3">
                You don’t need a full mock interview on day one. Use quick reps to warm up and improve.
            </p>

            <div class="row g-4 mt-2">
                <div class="col-md-4">
                    <div class="h-100 p-3 p-md-4 rounded-4 border border-secondary border-opacity-50">
                        <div class="small text-muted mb-1">Session 1</div>
                        <h3 class="h6 mb-2">Pick a role & answer 2–3 questions</h3>
                        <p class="small mb-0">
                            Choose the job you’re aiming for and just get your first answers out.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="h-100 p-3 p-md-4 rounded-4 border border-secondary border-opacity-50">
                        <div class="small text-muted mb-1">Session 2</div>
                        <h3 class="h6 mb-2">Apply the feedback</h3>
                        <p class="small mb-0">
                            Read your feedback, tighten your main stories, and fix the obvious gaps.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="h-100 p-3 p-md-4 rounded-4 border border-secondary border-opacity-50">
                        <div class="small text-muted mb-1">Session 3</div>
                        <h3 class="h6 mb-2">Run a short mock interview</h3>
                        <p class="small mb-0">
                            Do a full 6–8 question run so answering under pressure starts to feel normal.
                        </p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('user.register') }}" class="btn btn-primary btn-lg">
                    Create Your Free Account
                </a>
                <p class="small text-muted mt-2 mb-0">
                    Start with 10–15 minute sessions and build from there.
                </p>
            </div>
        </div>

    </div>
</section>
@endsection
