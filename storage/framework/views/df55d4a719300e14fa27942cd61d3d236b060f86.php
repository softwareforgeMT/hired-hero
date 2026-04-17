

<!-- Success Alert -->
<?php if(session('message')): ?>
<div class="alert <?php echo e(session('alert-class')); ?> alert-dismissible alert-label-icon rounded-label fade show" role="alert">
<i class="ri-notification-off-line label-icon"></i>
<p class="mb-0"><?php echo session('message'); ?></p> 
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if(count($errors) > 0): ?>
<div class="alert alert-danger alert-dismissible alert-label-icon rounded-label fade show " role="alert">
<i class="ri-error-warning-line label-icon"></i>
<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
 <p class="mb-0"> <?php echo $error; ?></p> 
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<?php $__env->startSection('script'); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
<script type="text/javascript">

      $(document).ready(function() {
        toastr.options.timeOut = 3000; // 3s
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          toastr.error('<?php echo $error; ?>');
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>       
      });
</script>
<?php $__env->stopSection(); ?>
<?php endif; ?>




<!-- Danger Alert -->




<?php /**PATH D:\Herd-Projects\HiredHero-Job-Match\resources\views/admin/includes/alerts.blade.php ENDPATH**/ ?>