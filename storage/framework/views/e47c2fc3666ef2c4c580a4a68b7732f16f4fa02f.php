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
<?php if(session('message')): ?>
<div class="alert <?php echo e(session('alert-class')); ?> alert-dismissible alert-label-icon rounded-label fade show" role="alert">
<i class="ri-notification-off-line label-icon"></i>
<p class="mb-0"><?php echo e(session('message')); ?></p> 
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if(count($errors) > 0): ?>
<div class="alert alert-danger alert-dismissible alert-label-icon rounded-label fade show " role="alert">
<i class="ri-error-warning-line label-icon"></i>
<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
 <p class="mb-0"> <?php echo e($error); ?></p> 
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\hired-hero\resources\views/includes/alerts.blade.php ENDPATH**/ ?>