<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-topbar="dark" data-layout-mode="dark" data-sidebar-image="none">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{$gs->name}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{$gs->slogan}}" name="description" />
    <meta content="Maalik & HiredHeroAi" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    
    <!-- App favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ URL::asset('assets/front/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ URL::asset('assets/front/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('assets/front/favicons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ URL::asset('assets/front/favicons/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ URL::asset('assets/front/favicons/safari-pinned-tab.svg') }}" color="#5bbad5">
    <link rel="shortcut icon" href="{{ URL::asset('assets/front/favicons/favicon.ico') }}">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="{{ URL::asset('assets/front/favicons/browserconfig.xml') }}">
    <meta name="theme-color" content="#ffffff">
    
    @include('front.layouts.head-css')

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

<!-- FontAwesome for icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />

    <script>
      (function(w,d,t,r,u)
      {
        var f,n,i;
        w[u]=w[u]||[],f=function()
        {
          var o={ti:"343138235", enableAutoSpaTracking: true};
          o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")
        },
        n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function()
        {
          var s=this.readyState;
          s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)
        },
        i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)
      })
      (window,document,"script","//bat.bing.com/bat.js","uetq");
    </script>

 <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-899254YK24"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-899254YK24');
    </script>

</head>


<body data-bs-spy="scroll" data-bs-target="#navbar-example">
    <!-- Fixed Note -->
    <div class="layout-wrapper landing">
        @include('front.layouts.navbar')

        {{-- Impersonation Banner --}}
        @if(session('impersonating'))
            <div class="container mt-16">
                @include('components.impersonation-banner')
            </div>
        @endif

        @yield('content')

        <!-- Start footer -->
        @include('front.layouts.footer')
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

   {{-- tawk Customer support--}}
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/6692e5abbecc2fed6924759b/1i2mt29i7';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
<!-- Add this in your <head> or before closing </body> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@stack('js')

</html>
