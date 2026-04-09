<footer class="custom-footer g2z-bg-cover2 pt-5 pb-4 position-relative">
    <div class="container">
        <div class="row">
            {{-- Brand / Description --}}
            <div class="col-lg-4 mt-4">
                <div class="footer-logo">
                    <img src="{{ asset('assets/images/landing/BrainOnlyLogo.png') }}"
                        alt="HiredHeroAI footer logo"
                        style="height:45px; width:auto;">
                </div>
                <div class="mt-4 fs-13">
                    <p>HiredHeroAI – Workforce Readiness Platform</p>
                    <p class="ff-secondary">
                        Helping colleges, workforce boards, nonprofits, and training programs
                        build job-ready candidates using AI-powered interviews, skills assessments,
                        and progress analytics.
                    </p>
                </div>
            </div>

            {{-- Platform --}}
            <div class="col-sm-6 col-lg-2 mt-4">
                <h5 class="text-white mb-0">Platform</h5>
                <div class="text-muted mt-3">
                    <ul class="list-unstyled ff-secondary footer-list">
                        <li><a href="/platform-overview">Platform Overview</a></li>
                        <li><a href="/platform-organizations">For Organizations</a></li>
                        <li><a href="/platform-individuals">For Individuals</a></li>
                        <li><a href="/trends">Job Trends</a></li>
                    </ul>
                </div>
            </div>

            {{-- Solutions --}}
            <div class="col-sm-6 col-lg-2 mt-4">
                <h5 class="text-white mb-0">Solutions</h5>
                <div class="text-muted mt-3">
                    <ul class="list-unstyled ff-secondary footer-list">
                        <li><a href="/ai-interview-training-colleges">Colleges &amp; Universities</a></li>
                        <li><a href="/skills-for-success-soft-skills-platform">Skills for Success</a></li>
                        <li><a href="/digital-career-readiness-workforce-boards">Workforce Boards</a></li>
                        <li><a href="/ai-job-readiness-nonprofits-community-organizations">Nonprofits</a></li>
                    </ul>
                </div>
            </div>

            {{-- Resources --}}
            <div class="col-sm-6 col-lg-2 mt-4">
                <h5 class="text-white mb-0">Resources</h5>
                <div class="text-muted mt-3">
                    <ul class="list-unstyled ff-secondary footer-list">
                        <li><a href="/resources/organizations">For Institutions</a></li>
                        <li><a href="/resources/individuals">For Job Seekers</a></li>
                        <li><a href="/job-fairs">Job Fairs (Canada &amp; US)</a></li>
                        <li><a href="/trends">Job Trends</a></li>
                    </ul>
                </div>
            </div>

            {{-- Company (keeps your dynamic pages) --}}
            <div class="col-sm-6 col-lg-2 mt-4">
                <h5 class="text-white mb-0">Company</h5>
                <div class="text-muted mt-3">
                    <ul class="list-unstyled ff-secondary footer-list">
                        @foreach(DB::table('pages')->where('status','=',1)->get() as $data)
                        <li>
                            <a href="{{ route('front.page',$data->slug) }}">{{ $data->title }}</a>
                        </li>
                        @endforeach
                        <li>
                            <a href="/org">Organization</a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Contact --}}
            <div class="col-sm-6 col-lg-2 mt-4">
                <h5 class="text-white mb-3">Contact Us</h5>
                <div class="text-muted">
                    <ul class="list-unstyled ff-secondary footer-list">
                        <li>
                            <a href="mailto:info@hiredheroai.com" style="color: #9ba7b3;">
                                Contact Us: info@hiredheroai.com
                            </a>
                        </li>
                        {{-- <li><a href="/faq">FAQ</a></li> --}}
                    </ul>
                </div>
            </div>
        </div>

        <hr>

        <div class="row text-center text-sm-start align-items-center mt-2">
            <div class="col-sm-6">
                <p class="copy-rights mb-0">
                    <script>
                        document.write(new Date().getFullYear())
                    </script>
                    © Workforce Readiness &amp; Skills Intelligence Platform
                </p>
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end mt-3 mt-sm-0">
                    <ul class="list-inline mb-0 footer-social-link">
                        @if($sociallinks->facebook)
                        <li class="list-inline-item">
                            <a target="_blank" href="{{ $sociallinks->facebook }}" class="avatar-xs d-block">
                                <div class="avatar-title rounded-circle">
                                    <i class="ri-facebook-fill"></i>
                                </div>
                            </a>
                        </li>
                        @endif

                        @if($sociallinks->twitter)
                        <li class="list-inline-item">
                            <a target="_blank" href="{{ $sociallinks->twitter }}" class="avatar-xs d-block">
                                <div class="avatar-title rounded-circle">
                                    <i class="ri-twitter-fill"></i>
                                </div>
                            </a>
                        </li>
                        @endif

                        @if($sociallinks->linkedin)
                        <li class="list-inline-item">
                            <a target="_blank" href="{{ $sociallinks->linkedin }}" class="avatar-xs d-block">
                                <div class="avatar-title rounded-circle">
                                    <i class="ri-linkedin-fill"></i>
                                </div>
                            </a>
                        </li>
                        @endif

                        @if($sociallinks->instagram)
                        <li class="list-inline-item">
                            <a target="_blank" href="{{ $sociallinks->instagram }}" class="avatar-xs d-block">
                                <div class="avatar-title rounded-circle">
                                    <i class="ri-instagram-fill"></i>
                                </div>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>