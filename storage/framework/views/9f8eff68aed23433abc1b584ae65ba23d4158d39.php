
<?php $__env->startSection('title'); ?> Email <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

           
           <section class="section  page__content">
                    <div class="container"> 
                        <div class="row justify-content-center">
                            <div class="col-md-8 col-lg-6 col-xl-5">
                                <div class="card mt-4">

                                    <div class="card-body p-4">
                                        <div class="text-center mt-2">
                                            <h5 class="text-primary">Forgot Password?</h5>
                                            <p class="text-muted">Reset password with <?php echo e($gs->name); ?></p>

                                            <lord-icon src="https://cdn.lordicon.com/rhvddzym.json" trigger="loop"
                                                colors="primary:#0ab39c" class="avatar-xl">
                                            </lord-icon>

                                        </div>

                                        <div class="alert alert-borderless alert-warning text-center mb-2 mx-2" role="alert">
                                            Enter your email and instructions will be sent to you!
                                        </div>
                                        <div class="p-2">
                                             <form class="form-horizontal" method="POST"
                                                action="<?php echo e(route('user.forgot.submit')); ?>">
                                                <?php echo $__env->make('includes.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                <?php echo csrf_field(); ?>
                                                <div class="mb-3">
                                                    <label for="useremail" class="form-label">Email</label>
                                                    <input type="email"
                                                        class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                        id="useremail" name="email" placeholder="Enter email"
                                                        value="<?php echo e(old('email')); ?>" id="email">
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
                                                </div>

                                                <div class="text-end">
                                                    <button class="btn  g2z-btn-primary w-md waves-effect waves-light"
                                                        type="submit">Reset</button>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->

                                <div class="mt-4 text-center">
                                    <p class="mb-0">Wait, I remember my password... <a href="<?php echo e(route('user.login')); ?>"
                                            class="fw-semibold text-primary text-decoration-underline"> Click here </a> </p>
                                </div>

                            </div>
                        </div>
                    </div><!--end col-->
           </section>
            <!-- end footer -->
            <div class="ellipse"></div>

    <?php $__env->stopSection(); ?>
    <?php $__env->startSection('script'); ?>

    <?php $__env->stopSection(); ?>

<?php echo $__env->make('front.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\hired-hero\resources\views/user/auth/passwords/email.blade.php ENDPATH**/ ?>