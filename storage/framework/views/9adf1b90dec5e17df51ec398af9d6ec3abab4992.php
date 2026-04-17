<?php $__env->startSection('title'); ?> Presentation Setup <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="page__content">
    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h1 class="text-center">Setup Your Presentation</h1>
                    <p class="text-muted text-center">Enter the details of your presentation topic to begin.</p>
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="<?php echo e(route('presentation.record')); ?>">
                                <?php echo csrf_field(); ?>
                                <div class="mb-3">
                                    <label for="presentation_topic" class="form-label"><?php echo e(__('Presentation Topic Details')); ?></label>
                                    <textarea id="presentation_topic" class="form-control <?php $__errorArgs = ['presentation_topic'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              name="presentation_topic" rows="5" required 
                                              maxlength="255"><?php echo e(old('presentation_topic')); ?></textarea>
                                    <span class="text-sm">Max 255 characters.</span>
                                    <?php $__errorArgs = ['presentation_topic'];
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
                                <div class="form-group mb-0 text-center">
                                    <button type="submit" class="btn btn-primary"><?php echo e(__('Proceed')); ?></button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>

<script type="text/javascript">
    var presentation_topic=`Cybersecurity in the Age of IoT: Challenges and Solutions`;
    $('#presentation_topic').val(presentation_topic);    
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('front.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Herd-Projects\HiredHero-Job-Match\resources\views/front/presentation/create.blade.php ENDPATH**/ ?>