<!doctype html >
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>"  data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-layout-mode="dark" data-layout-width="fluid" data-layout-position="scrollable" data-layout-style="default">

<head>
    <meta charset="utf-8" />
    <title><?php echo $__env->yieldContent('title'); ?>| <?php echo e($gs->name); ?> - Admin Dashboard </title>
    <?php if($gs->ngrok==1): ?>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    <?php endif; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Development" name="description" />
    <meta content="MM" name="author" />

     <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(URL::asset('assets/front/favicons/favicon-16x16.png')); ?>">
    <link rel="manifest" href="<?php echo e(URL::asset('assets/front/favicons/site.webmanifest')); ?>">
    <link rel="mask-icon" href="<?php echo e(URL::asset('assets/front/favicons/safari-pinned-tab.svg')); ?>" color="#5bbad5">
    <link rel="shortcut icon" href="<?php echo e(URL::asset('assets/front/favicons/favicon.ico')); ?>">

    <?php echo $__env->make('admin.layouts.head-css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>

<?php $__env->startSection('body'); ?>
    <?php echo $__env->make('admin.layouts.body', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->yieldSection(); ?>
    <!-- Demo Mode Banner -->
    <?php if(app('App\CentralLogics\Helpers')::demo_mode()): ?>
        <div class="alert alert-warning alert-dismissible fade show mb-0 rounded-0" role="alert" style="position: sticky; top: 0; z-index: 1020;">
            <div class="d-flex align-items-center">
                <i class="ri-alert-fill me-2 fs-18"></i>
                <strong>⚠️ DEMO MODE ACTIVE</strong> - This is a demonstration environment with simulated data. Settings changes are disabled.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php echo $__env->make('admin.layouts.topbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('admin.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            <?php echo $__env->make('admin.layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    

    <!-- JAVASCRIPT -->
    <?php echo $__env->make('admin.layouts.vendor-scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>

</html>
<?php /**PATH D:\Herd-Projects\HiredHero-Job-Match\resources\views/admin/layouts/master.blade.php ENDPATH**/ ?>