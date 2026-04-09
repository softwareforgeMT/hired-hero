@extends('front.layouts.app')
@section('title') Login @endsection
@section('css')
<!-- Add any custom CSS here -->
@endsection
@section('content')
@if ($gs->microsoft_login == 1)
<a href="{{ url('oauth/microsoft') }}" class="btn btn-info btn-label w-100">
    <div class="d-flex">
        <div class="flex-shrink-0">
            <i class="fab fa-microsoft label-icon align-middle fs-16 me-2"></i>
        </div>
        <div class="flex-grow-1">
            Microsoft
        </div>
    </div>
</a>
@endif

<section class="section page__content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card mt-4">
                    <div class="card-body p-4">
                        <div class="text-center mt-2">
                            <h5 class="text-primary">Welcome Back !</h5>
                            <p class="text-muted">Sign in to continue to {{ $website_title ?? 'our website' }}.</p>
                        </div>

                        <div class="p-2 mt-4">
                            <form action="{{ route('user.login') }}" method="POST">
                                @include('includes.alerts')
                                @csrf
                                <!-- Preserve redirect parameters -->
                                @if(request()->has('redirect'))
                                    <input type="hidden" name="redirect" value="{{ request()->query('redirect') }}">
                                    @if(request()->has('step'))
                                        <input type="hidden" name="step" value="{{ request()->query('step') }}">
                                    @endif
                                @endif

                                <!-- Email input -->
                                <div class="mb-3">
                                    <label for="username" class="form-label">Email</label>
                                    <input type="text" class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" id="username" name="email"
                                        placeholder="Enter email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <!-- Password input -->
                                <div class="mb-3">
                                    <div class="float-end">
                                        <a href="{{ route('user.forgot') }}" class="text-muted">Forgot password?</a>
                                    </div>
                                    <label class="form-label" for="password-input">Password</label>
                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                        <input type="password"
                                            class="form-control pe-5 @error('password') is-invalid @enderror password-input"
                                            name="password" placeholder="Enter password" id="password-input" value="">
                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                            type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Remember me -->
                                <div class="form-check">
                                    <input name="remember" class="form-check-input" type="checkbox" value="1"
                                        id="auth-remember-check">
                                    <label class="form-check-label" for="auth-remember-check">Remember me</label>
                                </div>

                                <!-- Submit button -->
                                <div class="mt-4">
                                    <button class="btn g2z-btn-primary w-100" type="submit">Sign In</button>
                                </div>

                                <!-- Social login buttons -->
                                <div class="mt-4 text-center">
                                    <div class="signin-other-title">
                                        <h5 class="fs-13 mb-4 title">Sign In with</h5>
                                    </div>
                                    <div class="d-flex gap-2 justify-content-center flex-wrap">

                                        {{-- Check if $gs exists before accessing properties --}}
                                        @if(isset($gs) && $gs->facebook_login == 1)
                                        <a href="{{ url('oauth/facebook') }}" class="btn btn-primary btn-label">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-facebook-fill label-icon align-middle fs-16 me-2"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    Facebook
                                                </div>
                                            </div>
                                        </a>
                                        @endif

                                        @if ($gs->google_login == 1)
                                            @php
                                                $googleUrl = route('user.google-redirect');
                                                if(request()->has('redirect')) {
                                                    $googleUrl .= '?redirect=' . request()->query('redirect');
                                                    if(request()->has('step')) {
                                                        $googleUrl .= '&step=' . request()->query('step');
                                                    }
                                                }
                                            @endphp
                                            <a href="{{ $googleUrl }}" class="btn btn-danger btn-label w-100">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i
                                                                class="ri-google-fill label-icon align-middle fs-16 me-2"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        Google
                                                    </div>
                                                </div>
                                            </a>
                                        @endif

                                        @if(isset($gs) && $gs->microsoft_login == 1)
                                        <a href="{{ url('oauth/microsoft') }}" class="btn btn-info btn-label">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="fab fa-microsoft label-icon align-middle fs-16 me-2"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    Microsoft
                                                </div>
                                            </div>
                                        </a>
                                        @endif

                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                <div class="mt-4 text-center">
                    <p class="mb-0">Don't have an account ? <a href="{{ route('user.register') }}"
                            class="fw-semibold text-primary text-decoration-underline"> Signup </a> </p>
                </div>

            </div>
        </div>
    </div><!--end container-->
</section>

@include('includes.modals')

<div class="ellipse"></div>

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/password-addon.init.js') }}"></script>
<!-- Make sure FontAwesome is loaded in your main layout for Microsoft icon -->
@endsection
