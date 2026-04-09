<nav class="navbar navbar-expand-lg navbar-landing fixed-top" id="navbar">
    <div class="container">
        <div class="d-inline-flex">
            {{-- Mobile toggle --}}
            <button class="navbar-toggler py-0 fs-20 text-body" type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasWithBothOptions"
                aria-controls="offcanvasWithBothOptions">
                <i class="mdi mdi-menu"></i>
            </button>

            {{-- Logo --}}
            <a class="navbar-brand" href="{{ route('front.index') }}">
                <img src="{{ asset('assets/images/landing/hiredhero_brain.png') }}"
                    class="card-logo card-logo-dark"
                    alt="HiredHeroAI logo"
                    style="height:40px;width:auto;">
                <img src="{{ asset('assets/images/landing/hiredhero_brain.png') }}"
                    class="card-logo card-logo-light"
                    alt="HiredHeroAI logo"
                    style="height:40px;width:auto;">
            </a>
        </div>

        {{-- Desktop nav --}}
        <div class="collapse navbar-collapse" id="navbarSupportedContent" style="justify-content: space-between;">
            <ul class="navbar-nav mx-auto mt-2 mt-lg-0" id="navbar-example">

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}"
                        href="{{ route('front.index') }}">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('plans') ? 'active' : '' }}"
                        href="{{ route('front.pricing') }}">
                        Pricing
                    </a>
                </li>

                {{-- Platform dropdown --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle
                   {{ request()->is('platform-overview')
            || request()->is('platform-organizations')
            || request()->is('platform-individuals')
            || request()->is('trends')
            ? 'active' : '' }}"
                        href="#"
                        id="navbarPlatform"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Platform
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="navbarPlatform">
                        <li>
                            <a class="dropdown-item" href="/platform-overview">Platform Overview</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/platform-organizations">For Organizations</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/platform-individuals">For Individuals</a>
                        </li>

                    </ul>
                </li>

                {{-- Resources dropdown --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle
        {{ request()->is('resources/organizations')
            || request()->is('resources/individuals')
            || request()->is('job-fairs')
            ? 'active' : '' }}"
                        href="#"
                        id="navbarResources"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Resources
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="navbarResources">
                        <li>
                            <a class="dropdown-item" href="{{ route('resources.organizations') }}">
                                For Institutions
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('resources.individuals') }}">
                                For Job Seekers
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('jobfairs.index') }}">
                                Job Fairs (Canada &amp; US)
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="/trends">Job Trends</a>
                        </li>

                    </ul>
                </li>


                {{-- Solutions dropdown --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle
        {{ request()->is('ai-interview-training-colleges')
            || request()->is('skills-for-success-soft-skills-platform')
            || request()->is('digital-career-readiness-workforce-boards')
            || request()->is('ai-job-readiness-nonprofits-community-organizations')
            ? 'active' : '' }}"
                        href="#"
                        id="navbarSolutions"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Solutions
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="navbarSolutions">
                        <li>
                            <a class="dropdown-item" href="/ai-interview-training-colleges">
                                For Colleges &amp; Universities
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/skills-for-success-soft-skills-platform">
                                Skills for Success &amp; Soft Skills
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/digital-career-readiness-workforce-boards">
                                For Workforce Boards &amp; Employment Programs
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/ai-job-readiness-nonprofits-community-organizations">
                                For Nonprofits &amp; Community Organizations
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link"
                        href="https://mail.google.com/mail/?view=cm&fs=1&to=info@hiredheroai.com&su=Book%20a%20Demo&body=Hello%2C%0A%0AI%20would%20like%20to%20book%20a%20demo.">
                        Book a Demo
                    </a>
                </li>

                @auth
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('user/order*') ? 'active' : '' }}"
                        href="{{ route('user.order.purchased.index') }}">
                        Orders
                    </a>
                </li>
                @endauth

            </ul>
        </div>

        {{-- Right side: user menu / auth buttons --}}
        @if(Auth::check())
        <div class="dropdown ms-sm-3 header-item g2z-front topbar-user">
            <button type="button" class="btn"
                id="page-header-user-dropdown"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
                <span class="d-flex align-items-center">
                    <img class="rounded-circle header-profile-user"
                        src="{!! Helpers::image(Auth::user()->photo, 'user/avatar/') !!}"
                        alt="Header Avatar">
                    <span class="text-start ms-xl-2">
                        <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">
                            {{ Auth::user()->name }}
                        </span>
                        <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">
                            User
                        </span>
                    </span>
                </span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <h6 class="dropdown-header">Welcome {{ Auth::user()->name }}!</h6>
                <a class="dropdown-item" href="{{ route('user.dashboard') }}">
                    <i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i>
                    <span class="align-middle">Dashboard</span>
                </a>
                <a class="dropdown-item" href="{{ route('user.order.purchased.index') }}">
                    <i class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i>
                    <span class="align-middle">Orders</span>
                </a>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="{{ route('user.profile') }}">
                    <span class="badge bg-soft-success text-success mt-1 float-end">New</span>
                    <i class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i>
                    <span class="align-middle">Settings</span>
                </a>

                @if(session('impersonating'))
                <a class="dropdown-item disabled text-muted" href="javascript:void(0);" title="Cannot logout while being impersonated. Click 'Revert to Admin' button above.">
                    <i class="bx bx-power-off font-size-16 align-middle me-1"></i>
                    <span key="t-logout">Logout (Disabled)</span>
                </a>
                @else
                <a class="dropdown-item" href="javascript:void(0);"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bx bx-power-off font-size-16 align-middle me-1"></i>
                    <span key="t-logout">Logout</span>
                </a>
                @endif
                <form id="logout-form"
                    action="{{ route('user.logout') }}"
                    method="POST"
                    style="display:none;">
                    @csrf
                </form>
            </div>
        </div>
        @else
        <div>
            <a href="{{ route('user.login') }}"
                class="btn g2z-outline-dark waves-effect waves-light me-3 nomobdisplay">
                Sign in
            </a>
            <a href="{{ route('user.register') }}"
                class="btn g2z-btn-primary nomobdisplay">
                Sign up
            </a>
        </div>
        @endif

    </div>
</nav>

{{-- Mobile Nav sidebar --}}
<div class="offcanvas offcanvas-start"
    data-bs-scroll="true"
    tabindex="-1"
    id="offcanvasWithBothOptions"
    aria-labelledby="offcanvasWithBothOptionsLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Menu</h5>
        <button type="button"
            class="btn-close text-reset"
            data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">
        <div class="mobile-navbar">
            <ul class="list-unstyled mb-0">

                {{-- Main --}}
                <li class="nav-item mb-1">
                    <a class="nav-link" href="{{ route('front.index') }}">Home</a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link" href="{{ route('front.pricing') }}">Plans</a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link" href="{{ route('front.page', \App\Models\Page::find(1)->slug) }}">About Us</a>
                </li>
                <li class="nav-item mb-3">
                    <a class="nav-link" href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $gs->from_email }}&su=Book%20a%20Demo&body=Hello%2C%0A%0AI%20would%20like%20to%20book%20a%20demo.">
                        Book a Demo
                    </a>
                </li>

                <hr class="my-2">

                {{-- Platform group --}}
                <li class="mt-2 mb-1 text-uppercase small text-muted fw-semibold">
                    Platform
                </li>
                <li class="nav-item mb-1 ps-3">
                    <a class="nav-link" href="/platform-overview">Platform Overview</a>
                </li>
                <li class="nav-item mb-1 ps-3">
                    <a class="nav-link" href="/platform-organizations">For Organizations</a>
                </li>
                <li class="nav-item mb-3 ps-3">
                    <a class="nav-link" href="/platform-individuals">For Individuals</a>
                </li>

                <hr class="my-2">

                {{-- Resources group --}}
                <li class="mt-2 mb-1 text-uppercase small text-muted fw-semibold">
                    Resources
                </li>
                <li class="nav-item mb-1 ps-3">
                    <a class="nav-link" href="{{ route('resources.organizations') }}">
                        For Institutions
                    </a>
                </li>
                <li class="nav-item mb-1 ps-3">
                    <a class="nav-link" href="{{ route('resources.individuals') }}">
                        For Job Seekers
                    </a>
                </li>
                <li class="nav-item mb-1 ps-3">
                    <a class="nav-link" href="{{ route('jobfairs.index') }}">
                        Job Fairs (Canada &amp; US)
                    </a>
                </li>
                <li class="nav-item mb-3 ps-3">
                    <a class="nav-link" href="{{ route('trends.index') }}">
                        Job Trends
                    </a>
                </li>

                <hr class="my-2">

                {{-- Solutions group --}}
                <li class="mt-2 mb-1 text-uppercase small text-muted fw-semibold">
                    Solutions
                </li>
                <li class="nav-item mb-1 ps-3">
                    <a class="nav-link" href="/ai-interview-training-colleges">
                        For Colleges &amp; Universities
                    </a>
                </li>
                <li class="nav-item mb-1 ps-3">
                    <a class="nav-link" href="/skills-for-success-soft-skills-platform">
                        Skills for Success &amp; Soft Skills
                    </a>
                </li>
                <li class="nav-item mb-1 ps-3">
                    <a class="nav-link" href="/digital-career-readiness-workforce-boards">
                        For Workforce Boards &amp; Employment Programs
                    </a>
                </li>
                <li class="nav-item mb-1 ps-3">
                    <a class="nav-link" href="/ai-job-readiness-nonprofits-community-organizations">
                        For Nonprofits &amp; Community Organizations
                    </a>
                </li>

            </ul>

            @if(!Auth::check())
            <div class="mt-4 d-flex flex-wrap" style="gap: 0.75rem;">
                <a href="{{ route('user.login') }}"
                    class="btn g2z-outline-dark waves-effect waves-light">
                    Sign in
                </a>

                <a href="{{ route('user.register') }}"
                    class="btn btn-primary waves-effect waves-light">
                    Sign up
                </a>
            </div>
            @endif
        </div>
    </div>
</div>


<style>
    .collapse {
        visibility: visible;
    }
</style>