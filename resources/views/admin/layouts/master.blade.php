<!doctype html >
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-layout-mode="dark" data-layout-width="fluid" data-layout-position="scrollable" data-layout-style="default">

<head>
    <meta charset="utf-8" />
    <title>@yield('title')| {{$gs->name}} - Admin Dashboard </title>
    @if($gs->ngrok==1)
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Development" name="description" />
    <meta content="MM" name="author" />

     <link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('assets/front/favicons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ URL::asset('assets/front/favicons/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ URL::asset('assets/front/favicons/safari-pinned-tab.svg') }}" color="#5bbad5">
    <link rel="shortcut icon" href="{{ URL::asset('assets/front/favicons/favicon.ico') }}">

    @include('admin.layouts.head-css')
</head>

@section('body')
    @include('admin.layouts.body')
@show
    <!-- Demo Mode Banner -->
    @if(app('App\CentralLogics\Helpers')::demo_mode())
        <div class="alert alert-warning alert-dismissible fade show mb-0 rounded-0" role="alert" style="position: sticky; top: 0; z-index: 1020;">
            <div class="d-flex align-items-center">
                <i class="ri-alert-fill me-2 fs-18"></i>
                <strong>⚠️ DEMO MODE ACTIVE</strong> - This is a demonstration environment with simulated data. Settings changes are disabled.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('admin.layouts.topbar')
        @include('admin.layouts.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('admin.layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    {{-- @include('admin.layouts.customizer') --}}

    <!-- JAVASCRIPT -->
    @include('admin.layouts.vendor-scripts')
</body>

</html>
