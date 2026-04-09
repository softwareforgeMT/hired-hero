@extends('front.layouts.app')
@section('title')
    Register
@endsection
@section('css')
@endsection
@section('content')


    <section class="section bg-light page__content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4">

                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">Create New Account</h5>
                                <p class="text-muted">Get your free {{ $website_title }} account now</p>
                            </div>
                            <div class="p-2 mt-4">
                                <form id="registerForm" class="needs-validation" novalidate method="POST"
                                    action="{{ route('user.register') }}" enctype="multipart/form-data">
                                    @csrf
                                    <!-- Preserve redirect parameters -->
                                    @if(request()->has('redirect'))
                                        <input type="hidden" name="redirect" value="{{ request()->query('redirect') }}">
                                        @if(request()->has('step'))
                                            <input type="hidden" name="step" value="{{ request()->query('step') }}">
                                        @endif
                                    @endif
                                    <div class="mb-3">
                                        <label for="useremail" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email') }}" id="useremail"
                                            placeholder="Enter email address" required>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <div class="invalid-feedback">
                                            Please enter email
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name') }}" id="username"
                                            placeholder="Enter username" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <div class="invalid-feedback">
                                            Please enter username
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label for="userpassword" class="form-label">Password <span
                                                class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            name="password"  id="userpassword" placeholder="Enter password" pattern="(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}" required>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <div class="invalid-feedback password-invalid">
                                            Please enter password
                                        </div>
                                    </div>
                                    <div class=" mb-4">
                                        <label for="input-password">Confirm Password</label>
                                        <input type="password"
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                            name="password_confirmation" id="input-password"
                                            placeholder="Enter Confirm Password" required>

                                        <div class="form-floating-icon">
                                            <i data-feather="lock"></i>
                                        </div>
                                    </div>
                                    <!-- Referral Code (Optional) -->
                                    <div class="mb-3">
                                        <label for="referral_code" class="form-label">Referral Code (Optional)</label>
                                        <input type="text" class="form-control @error('referral_code') is-invalid @enderror"
                                               name="referral_code" id="referral_code"
                                               value="{{ old('referral_code') }}" placeholder="Enter referral code if you have one">
                                        @error('referral_code')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    {{-- <div class=" mb-4">
                                                    <input type="file" class="form-control @error('avatar') is-invalid @enderror"
                                                        name="avatar" id="input-avatar" required>
                                                    @error('avatar')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="">
                                                        <i data-feather="file"></i>
                                                    </div>
                                                </div> --}}


                                    <div class="d-flex justify-content-between mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input ts-focus-1" type="checkbox" value=""
                                                id="defaultCheck1" required="">
                                            <label class="fs-12 form-check-label" for="defaultCheck1">
                                                By registering you agree to the {{ $website_title }} <a target="_blank"
                                                    href="{{ route('front.page', \App\Models\Page::find(2)->slug) }}"
                                                    class="text-primary text-decoration-underline fst-normal fw-medium">Terms
                                                    of Service</a>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="g-recaptcha" 
                                            data-sitekey="6LeUB68rAAAAAFKKZ8LOcY0Hsttu7w0w6npa6Xo9"
                                            data-callback="recaptchaCompleted">
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <button class="btn g2z-btn-primary w-100" type="submit" id="signupBtn" >Sign Up</button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <div class="signin-other-title">
                                            <h5 class="fs-13 mb-4 title text-muted">Create account with</h5>
                                        </div>

                                        <div class="d-flex">
                                            @if ($gs->facebook_login == 1)
                                                <a href="{{ url('oauth/facebook') }}"
                                                    class="btn btn-primary btn-label w-100 me-2">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0">
                                                            <i
                                                                class="ri-facebook-fill label-icon align-middle fs-16 me-2"></i>
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
                                        </div>

                                    </div>
                                </form>

                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->

                    <div class="mt-4 text-center">
                        <p class="mb-0">Already have an account ? <a href="{{ route('user.login') }}"
                                class="fw-semibold text-primary text-decoration-underline"> Signin </a> </p>
                    </div>

                </div>
            </div>
        </div><!--end col-->
    </section>

    @include('includes.modals')
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="{{ URL::asset('assets/js/pages/form-validation.init.js') }}"></script>
<script>
      function recaptchaCompleted() {
         const signupBtn = document.getElementById('signupBtn');
         if(signupBtn){
             signupBtn.disabled = false;
         }
     }
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('.needs-validation');

        form.addEventListener('submit', function (event) {
            const passwordInput = document.getElementById('userpassword');
            const password = passwordInput.value.trim();
            if(password != ''){
            // Custom validation logic
            const hasUpperCase = /[A-Z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecialChar = /[^A-Za-z0-9]/.test(password);

            let passwordValid = true;

            if (!hasUpperCase || !hasNumber || !hasSpecialChar || password.length < 8) {
                passwordValid = false;
                passwordInput.classList.add('is-invalid');
                passwordInput.classList.remove('is-valid');
                const feedback = document.querySelector('.password-invalid');
                if (feedback) {
                    feedback.textContent = 'Password must be at least 8 characters and include an uppercase letter, a number, and a special character.';
                }
            } else {
                passwordInput.classList.remove('is-invalid');
            }

            // If custom validation fails, stop form submission
            if (!form.checkValidity() || !passwordValid) {
                event.preventDefault();
                event.stopPropagation();

            }
        }

        }, false);
    });
</script>

    @if (session('duplicate_found_register'))
        <script>
            // safe print
            console.log(@json(session('temp_password')));

            Swal.fire({
                title: 'Duplicate IP Detected',
                text: "Your Account already exists. If you register again, you will not receive a discount. Do you still want to continue?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Continue',
                cancelButtonText: 'No, Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.querySelector('#registerForm');

                    // restore password into the input before submit
                    const pass = @json(session('temp_password'));
                    if (pass !== null) {
                        const pwField = form.querySelector('input[name="password"]');
                        const pwConfirm = form.querySelector('input[name="password_confirmation"]');
                        if (pwField) pwField.value = pass;
                        if (pwConfirm) pwConfirm.value = pass;
                    }

                    const confirmInput = document.createElement('input');
                    confirmInput.type = 'hidden';
                    confirmInput.name = 'confirm_duplicate';
                    confirmInput.value = '1';
                    form.appendChild(confirmInput);

                    form.submit();
                }
            });
        </script>
    @endif

    @if(session('duplicate_found_google'))

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: "Duplicate Detected",
                    text: "Your Account already exists. If you register again, you will not receive a discount. Do you still want to continue?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Continue Anyway",
                    cancelButtonText: "Cancel",
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ✅ Redirect to confirm duplicate route
                        window.location.href = "{{ route('user.google-confirm-duplicate') }}";
                    }
                });
            });
        </script>
    @endif



@endsection
