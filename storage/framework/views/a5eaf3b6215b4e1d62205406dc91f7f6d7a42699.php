<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="logo logo-dark">
            <span class="logo-sm">
                <img src="<?php echo e(URL::asset('assets/images/logo-sm.png')); ?>" alt="" height="22">
            </span>
            <span class="logo-lg adm-top-logo">
                <img src="<?php echo Helpers::image($gs->logo_dark, 'logo/'); ?>" alt="" height="" width="100%">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="logo logo-light">
            <span class="logo-sm">
                <img src="<?php echo e(asset('logo/'.$gs->logo_dark)); ?>" alt="Logo" width="100%">
            </span>
            <span class="logo-lg adm-top-logo">
                <img src="<?php echo e(asset('logo/'.$gs->logo_light)); ?>" alt="Logo" width="100%">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?php echo e(route('admin.dashboard')); ?>" >
                        <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
                    </a>
                    
                </li> <!-- end Dashboard Menu -->


                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?php echo e(route('admin.users.index')); ?>" >
                        <i class="ri-user-line"></i> <span>Users</span>
                    </a>                    
                </li>
                
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?php echo e(route('admin.orders.index')); ?>" >
                        <i class="ri-stack-line"></i> <span>Orders</span>
                    </a>                    
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?php echo e(route('admin.placements.index')); ?>" >
                        <i class="ri-briefcase-line"></i> <span>Placement Profiles</span>
                    </a>                    
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?php echo e(route('admin.subscriptions.index')); ?>" >
                        <i class="ri-file-list-line"></i> <span>Subscriptions</span>
                    </a>                    
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?php echo e(route('admin.promo-codes.index')); ?>" >
                        <i class="ri-ticket-2-line"></i> <span>Promo Codes</span>
                    </a>                    
                </li>


                <li class="menu-title"><span>Settings</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?php echo e(route('admin.profile')); ?>" >
                        <i class="ri-user-settings-fill"></i> <span>Profile </span>
                    </a>                    
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?php echo e(route('admin.custompage.index')); ?>" >
                        <i class="ri-user-settings-fill"></i> <span>Custom Pages </span>
                    </a>                    
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?php echo e(route('admin.social')); ?>" >
                        <i class="ri-team-fill"></i> <span>Profile Social </span>
                    </a>                    
                </li>
                <?php if($gs->dev_check==1): ?>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?php echo e(route('admin.social-login')); ?>" >
                        <i class="ri-team-fill"></i> <span>Social Login </span>
                    </a>                    
                </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?php echo e(route('admin.generalsettings')); ?>" >
                        <i class=" ri-settings-2-fill"></i> <span>General</span>
                    </a>                    
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
<?php /**PATH D:\Herd-Projects\HiredHero-Job-Match\resources\views/admin/layouts/sidebar.blade.php ENDPATH**/ ?>