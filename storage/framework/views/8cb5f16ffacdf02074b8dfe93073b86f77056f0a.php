<?php $__env->startSection('title'); ?>
    Register
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>


    <section class="section bg-light page__content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4">

                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">Create New Account</h5>
                                <p class="text-muted">Get your free <?php echo e($website_title); ?> account now</p>
                            </div>
                            <div class="p-2 mt-4">
                                <form id="registerForm" class="needs-validation" novalidate method="POST"
                                    action="<?php echo e(route('user.register')); ?>" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <!-- Preserve redirect parameters -->
                                    <?php if(request()->has('redirect')): ?>
                                        <input type="hidden" name="redirect" value="<?php echo e(request()->query('redirect')); ?>">
                                        <?php if(request()->has('step')): ?>
                                            <input type="hidden" name="step" value="<?php echo e(request()->query('step')); ?>">
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <div class="mb-3">
                                        <label for="useremail" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="email" value="<?php echo e(old('email')); ?>" id="useremail"
                                            placeholder="Enter email address" required>
                                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-feedback" role="alert">
                                                <strong><?php echo e($message); ?></strong>
                                            </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <div class="invalid-feedback">
                                            Please enter email
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="name" value="<?php echo e(old('name')); ?>" id="username"
                                            placeholder="Enter username" required>
                                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-feedback" role="alert">
                                                <strong><?php echo e($message); ?></strong>
                                            </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <div class="invalid-feedback">
                                            Please enter username
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label for="userpassword" class="form-label">Password <span
                                                class="text-danger">*</span></label>
                                        <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="password"  id="userpassword" placeholder="Enter password" pattern="(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}" required>
                                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-feedback" role="alert">
                                                <strong><?php echo e($message); ?></strong>
                                            </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <div class="invalid-feedback password-invalid">
                                            Please enter password
                                        </div>
                                    </div>
                                    <div class=" mb-4">
                                        <label for="input-password">Confirm Password</label>
                                        <input type="password"
                                            class="form-control <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="password_confirmation" id="input-password"
                                            placeholder="Enter Confirm Password" required>

                                        <div class="form-floating-icon">
                                            <i data-feather="lock"></i>
                                        </div>
                                    </div>
                                    <!-- Referral Code (Optional) -->
                                    <div class="mb-3">
                                        <label for="referral_code" class="form-label">Referral Code (Optional)</label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['referral_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               name="referral_code" id="referral_code"
                                               value="<?php echo e(old('referral_code')); ?>" placeholder="Enter referral code if you have one">
                                        <?php $__errorArgs = ['referral_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback" role="alert">
                                                <strong><?php echo e($message); ?></strong>
                                            </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    


                                    <div class="d-flex justify-content-between mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input ts-focus-1" type="checkbox" value=""
                                                id="defaultCheck1" required="">
                                            <label class="fs-12 form-check-label" for="defaultCheck1">
                                                By registering you agree to the <?php echo e($website_title); ?> <a target="_blank"
                                                    href="<?php echo e(route('front.page', \App\Models\Page::find(2)->slug)); ?>"
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
                                            <?php if($gs->facebook_login == 1): ?>
                                                <a href="<?php echo e(url('oauth/facebook')); ?>"
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
                                            <?php endif; ?>
                                            <?php if($gs->google_login == 1): ?>
                                                <?php
                                                    $googleUrl = route('user.google-redirect');
                                                    if(request()->has('redirect')) {
                                                        $googleUrl .= '?redirect=' . request()->query('redirect');
                                                        if(request()->has('step')) {
                                                            $googleUrl .= '&step=' . request()->query('step');
                                                        }
                                                    }
                                                ?>
                                                <a href="<?php echo e($googleUrl); ?>" class="btn btn-danger btn-label w-100">
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
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </form>

                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->

                    <div class="mt-4 text-center">
                        <p class="mb-0">Already have an account ? <a href="<?php echo e(route('user.login')); ?>"
                                class="fw-semibold text-primary text-decoration-underline"> Signin </a> </p>
                    </div>

                </div>
            </div>
        </div><!--end col-->
    </section>

    <?php echo $__env->make('includes.modals', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="<?php echo e(URL::asset('assets/js/pages/form-validation.init.js')); ?>"></script>
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

    <?php if(session('duplicate_found_register')): ?>
        <script>
            // safe print
            console.log(<?php echo json_encode(session('temp_password'), 15, 512) ?>);

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
                    const pass = <?php echo json_encode(session('temp_password'), 15, 512) ?>;
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
    <?php endif; ?>

    <?php if(session('duplicate_found_google')): ?>

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
                        window.location.href = "<?php echo e(route('user.google-confirm-duplicate')); ?>";
                    }
                });
            });
        </script>
    <?php endif; ?>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('front.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Herd-Projects\HiredHero-Job-Match\resources\views/user/auth/register.blade.php ENDPATH**/ ?>