@extends('front.layouts.app')
@section('title') Verification @endsection
@section('css')



@endsection
@section('content')


           <section class="section  page__content">
                    <div class="container"> 
                        <div class="row justify-content-center">
                            <div class="col-md-8 col-lg-6 col-xl-5">
                                <div class="card mt-4">

                                    <div class="card-body p-4">
                                        <div class="mb-4">
                                            <div class="avatar-lg mx-auto">
                                                <div class="avatar-title bg-light text-primary display-5 rounded-circle">
                                                    <i class="ri-mail-line"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-2 mt-4">
                                            <div class="text-muted text-center mb-4 mx-lg-3">
                                                <h4 class="">Verify Your Email</h4>
                                                <p>Please enter the 4 digit code sent to <span class="fw-semibold">example@abc.com</span></p>
                                            </div>

                                            <form action="{{ route('user.login') }}" method="POST">
                                                @include('includes.alerts')
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="verification" class="form-label">Verification code</label>
                                                    <input type="text" class="form-control @error('email') is-invalid @enderror" value="" id="verification" name="email" placeholder="Enter Verification Code">
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </form>

                                            <div class="mt-3">
                                                <button type="button" class="btn btn-success w-100">Confirm</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->

                                <div class="mt-4 text-center">
                                    <p class="mb-0">Didn't receive a code ? <a href="auth-pass-reset-basic" class="fw-semibold text-primary text-decoration-underline">Resend</a> </p>
                                </div>

                            </div>
                        </div>
                    </div><!--end col-->
           </section>

            <!-- end footer -->
            <div class="ellipse"></div>

    @endsection
    @section('script')
       <script src="{{ URL::asset('assets/js/pages/two-step-verification.init.js') }}"></script>
    @endsection
