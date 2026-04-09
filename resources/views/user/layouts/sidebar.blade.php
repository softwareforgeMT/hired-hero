<div class="card">
    <div class="card-body">
        <span class="d-flex align-items-center">
            <img class="rounded-circle header-profile-user" src="{!! Helpers::image(Auth::user()->photo, 'user/avatar/') !!}" alt="Header Avatar">
            <span class="text-start ms-xl-2">
                <span class="d-xl-inline-block ms-1 fw-medium user-name-text">{{Auth::user()->name}}</span>
                <span class="d-block ms-1 fs-12 text-muted user-name-sub-text">{{Auth::user()->email}}</span>
            </span>
        </span>
        <hr>
        <div class="user-sidebar-menu">
            <ul class="list-unstyled" id="usersidebarlist">
                <li>
                    <a class="nav--link" href="{{route('user.dashboard')}}"><i class="ri-dashboard-2-line "></i> Dashboard</a>
                </li>


                <li>
                    <a class="nav--link" href="{{route('user.order.purchased.index')}}"><i class="ri-shopping-cart-2-line "></i> Orders</a>
                </li>

                <li>
                    <a class="nav--link {{ request()->routeIs('user.results.*') ? 'active' : '' }}"
                        href="{{ route('user.results.index') }}">
                        <i class="ri-file-list-3-line"></i> Results
                    </a>
                </li>

                <li>
                    <a class="nav--link {{ request()->routeIs('placement.*') ? 'active' : '' }}"
                        href="{{ route('placement.start') }}">
                        <i class="ri-briefcase-4-line"></i> Job Matches
                    </a>
                </li>

                <li>
                    <a class="nav--link" href="{{route('user.earnings')}}"><i class=" ri-wallet-3-fill"></i> Earnings</a>
                </li>

                <li>
                    <a class="nav--link" href="{{route('user.subscriptions.index')}}"><i class="ri-file-list-line"></i> Subscriptions</a>
                </li>

                <li>
                    <a class="nav--link" href="{{route('user.profile')}}"><i class="ri-settings-4-fill"></i> Settings</a>
                </li>

            </ul>
            <hr>
            <a href="" class="btn g2z-btn-primary w-100 waves-effect waves-light"> <i class=" ri-logout-box-r-line align-middle fs-16 me-2"></i> Logout </a>
        </div>
    </div>
</div>