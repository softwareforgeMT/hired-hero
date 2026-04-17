<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="<?php echo e(URL::asset('assets/images/logo-sm.png')); ?>" alt="" height="22">
                        </span>
                        <span class="logo-lg adm-top-logo">
                            <img src="<?php echo Helpers::image($gs->logo_dark, 'logo/'); ?>" alt="" height="" width="100%">
                        </span>
                    </a>

                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="<?php echo e(URL::asset('assets/images/logo-sm.png')); ?>" alt="" height="22">
                        </span>
                        <span class="logo-lg adm-top-logo">
                            <img src="<?php echo Helpers::image($gs->logo_light, 'logo/'); ?>" alt="" height="" width="100%">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                <!-- App Search-->
               
            </div>

            <div class="d-flex align-items-center">

                <!-- Demo Mode Toggle Button -->
                <div class="ms-2 header-item">
                    <form action="<?php echo e(route('admin.toggle-demo-mode')); ?>" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <?php if(app('App\CentralLogics\Helpers')::demo_mode()): ?>
                            <button type="submit" class="btn btn-sm btn-danger waves-effect waves-light">
                                <i class="ri-alert-line me-1"></i> DEMO MODE
                            </button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-sm btn-success waves-effect waves-light">
                                <i class="ri-play-circle-line me-1"></i> LIVE MODE
                            </button>
                        <?php endif; ?>
                    </form>
                </div>
              
                
                

                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" src="<?php echo e(Auth::guard('admin')->user()->photo?asset('assets/images/admins/'.Auth::guard('admin')->user()->photo):URL::asset('assets/images/users/user-dummy-img.jpg')); ?>"
                                alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text"><?php echo e(Auth::guard('admin')->user()->name); ?></span>
                                <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">Founder</span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <h6 class="dropdown-header">Welcome <?php echo e(Auth::guard('admin')->user()->name); ?>!</h6>
                        <a class="dropdown-item" href="<?php echo e(route('admin.profile')); ?>"><i
                                class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Profile</span></a>
                       
                        <div class="dropdown-divider"></div>
                    
                        <a class="dropdown-item" href="<?php echo e(route('admin.generalsettings')); ?>"><span
                                class="badge bg-soft-success text-success mt-1 float-end">New</span><i
                                class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Settings</span></a>
                        <a class="dropdown-item" href="<?php echo e(route('admin.password')); ?>"><i
                                class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Change Password</span></a>
                        <a class="dropdown-item " href="javascript:void();"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                class="bx bx-power-off font-size-16 align-middle me-1"></i> <span
                                key="t-logout">Logout</span></a>
                        <form id="logout-form" action="<?php echo e(route('admin.logout')); ?>" method="POST" style="display: none;">
                            <?php echo csrf_field(); ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<?php /**PATH D:\Herd-Projects\hired-hero\resources\views/admin/layouts/topbar.blade.php ENDPATH**/ ?>