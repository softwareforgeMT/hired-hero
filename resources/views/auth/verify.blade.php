@extends('layouts.master-without-nav')

@section('title')
    @lang('translation.password-reset')
@endsection

@section('content')
<div class="auth-page-wrapper pt-5">
    <!-- auth page bg -->
    <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
        <div class="bg-overlay"></div>
        <div class="shape">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" 
                 xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                <path d="M 0,36 C 144,53.6 432,123.2 720,124 
                         C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
            </svg>
        </div>
    </div>

    <!-- auth page content -->
    <div class="auth-page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mt-sm-5 mb-4 text-white-50">
                        <div>
                            <a href="{{ url('/') }}" class="d-inline-block auth-logo">
                                <img src="{{ URL::asset('assets/images/logo-light.png') }}" alt="Logo" height="20">
                            </a>
                        </div>
                        <p class="mt-3 fs-15 fw-medium">Premium Admin & Dashboard Template</p>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4">
                        <div class="card-body p-4">
                            <div class="mb-4 text-center">
                                <div class="avatar-lg mx-auto">
                                    <div class="avatar-title bg-light text-primary display-5 rounded-circle">
                                        <i class="ri-mail-line"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="p-2 mt-4">
                                <div class="text-muted text-center mb-4 mx-lg-3">
                                    <h4>Verify Your Email</h4>
                                    <p>Please enter the 4 digit code sent to 
                                        <span class="fw-semibold">{{ session('email_for_verification', 'example@abc.com') }}</span>
                                    </p>
                                </div>

                                <form method="POST" action="{{ route('verify.email.submit') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-3">
                                            <input type="text" name="digit1" maxlength="1"
                                                   class="form-control form-control-lg bg-light border-light text-center"
                                                   onkeyup="moveToNext(this, 2)" required autofocus>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" name="digit2" maxlength="1"
                                                   class="form-control form-control-lg bg-light border-light text-center"
                                                   onkeyup="moveToNext(this, 3)" required>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" name="digit3" maxlength="1"
                                                   class="form-control form-control-lg bg-light border-light text-center"
                                                   onkeyup="moveToNext(this, 4)" required>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" name="digit4" maxlength="1"
                                                   class="form-control form-control-lg bg-light border-light text-center"
                                                   required>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-success w-100">Confirm</button>
                                    </div>

                                    @if ($errors->any())
                                        <div class="alert alert-danger mt-3">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if(session('info'))
                                        <div class="alert alert-info mt-3">
                                            {{ session('info') }}
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->

                    <div class="mt-4 text-center">
                        <p class="mb-0">Didn't receive a code? 
                            <a href="{{ route('resend.verify', ['email' => session('email_for_verification')]) }}" 
                               class="fw-semibold text-primary text-decoration-underline">Resend</a> 
                        </p>
                    </div>

                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end auth page content -->

    <!-- footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <p class="mb-0 text-muted">&copy; <script>document.write(new Date().getFullYear())</script> Velzon. Crafted
