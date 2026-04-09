{{-- <!-- Primary Alert -->
<div class="alert alert-primary alert-dismissible alert-label-icon rounded-label fade show" role="alert">
<i class="ri-user-smile-line label-icon"></i><strong>Primary</strong> - Rounded label alert
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<!-- Secondary Alert -->
<div class="alert alert-secondary alert-dismissible alert-label-icon rounded-label fade show" role="alert">
<i class="ri-check-double-line label-icon"></i><strong>Secondary</strong> - Rounded label alert
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div> --}}

<!-- Success Alert -->
@if (session('message'))
<div class="alert {{session('alert-class')}} alert-dismissible alert-label-icon rounded-label fade show" role="alert">
<i class="ri-notification-off-line label-icon"></i>
<p class="mb-0">{!! session('message') !!}</p> 
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if (count($errors) > 0)
<div class="alert alert-danger alert-dismissible alert-label-icon rounded-label fade show " role="alert">
<i class="ri-error-warning-line label-icon"></i>
@foreach ($errors->all() as $error)
 <p class="mb-0"> {!! $error !!}</p> 
@endforeach
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
<script type="text/javascript">

      $(document).ready(function() {
        toastr.options.timeOut = 3000; // 3s
        @foreach ($errors->all() as $error)
          toastr.error('{!! $error !!}');
        @endforeach       
      });
</script>
@endsection
@endif




<!-- Danger Alert -->
{{-- <div class="alert alert-danger alert-dismissible alert-label-icon rounded-label fade show d-done" role="alert">
<i class="ri-error-warning-line label-icon"></i>
<p class="mb-0"></p> 
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<!-- Warning Alert -->
<div class="alert alert-warning alert-dismissible alert-label-icon rounded-label show fade d-done" role="alert">
<i class="ri-alert-line label-icon"></i>
<p class="mb-0"></p> 
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<!-- Info Alert -->
<div class="alert alert-info alert-dismissible alert-label-icon rounded-label fade show d-done" role="alert">
<i class="ri-airplay-line label-icon"></i>
<p class="mb-0"></p> 
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div> --}}



{{-- <!-- Light Alert -->
<div class="alert alert-light alert-dismissible alert-label-icon rounded-label fade show" role="alert">
<i class="ri-mail-line label-icon"></i><strong>Light</strong> - Rounded label alert
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<!-- Dark Alert -->
<div class="alert alert-dark alert-dismissible alert-label-icon rounded-label fade show" role="alert">
<i class="ri-refresh-line label-icon"></i><strong>Dark</strong> - Rounded label alert
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div> --}}