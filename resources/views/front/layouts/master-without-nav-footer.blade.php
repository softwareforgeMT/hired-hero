<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-topbar="dark" data-layout-mode="dark" data-sidebar-image="none">

    <head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{$gs->name}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{$gs->name}} Buying & selling " name="description" />
    <meta content="HiredheroAi" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico')}}">
        @include('front.layouts.head-css')
  </head>
  
  <body data-bs-spy="scroll" data-bs-target="#navbar-example">
    <div class="layout-wrapper landing g2z-bg-cover">

        @if (session('referral_banner'))
            <div class="alert alert-success text-center mt-3" style="max-width:600px;margin:0 auto;">
                🎉 Discount for first-time users <strong>applied at checkout</strong>.
            </div>
        @endif

        @yield('content')

        <!-- Start footer -->
        <!-- end footer -->
        <audio id="notification-sound" preload="auto" autoplay="true"></audio>
    </div>
    </body>
    
    <!--jquery cdn-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
   
     
    @include('front.layouts.vendor-scripts')
    <script src="{{ URL::asset('/assets/custom.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/landing.init.js') }}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>



  
    </body>
</html>
