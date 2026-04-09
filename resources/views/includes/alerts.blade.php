<div class="alert alert-info alert-dismissible alert-label-icon rounded-label fade show" role="alert" style="display:none;">
<i class="ri-user-smile-line label-icon"></i>
<p class="mb-0"></p> 
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<div class="alert alert-danger alert-dismissible alert-label-icon rounded-label fade show" role="alert" style="display:none;">
<i class="ri-user-smile-line label-icon"></i>
<p class="mb-0"></p> 
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<div class="alert alert-success alert-dismissible alert-label-icon rounded-label fade show" role="alert" style="display:none;">
<i class="ri-user-smile-line label-icon"></i>
<p class="mb-0"></p> 
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<!-- Success Alert -->
@if (session('message'))
<div class="alert {{session('alert-class')}} alert-dismissible alert-label-icon rounded-label fade show" role="alert">
<i class="ri-notification-off-line label-icon"></i>
<p class="mb-0">{{ session('message') }}</p> 
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if (count($errors) > 0)
<div class="alert alert-danger alert-dismissible alert-label-icon rounded-label fade show " role="alert">
<i class="ri-error-warning-line label-icon"></i>
@foreach ($errors->all() as $error)
 <p class="mb-0"> {{$error}}</p> 
@endforeach
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif