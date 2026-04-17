
<?php $__env->startSection('title', 'Resume Builder'); ?>

<?php $__env->startSection('css'); ?>
<style>
    .resume-builder-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 3rem 0;
    }

    .resume-builder-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-top: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .btn-primary {
        background: linear-gradient(90deg, #00A3FF 0%, #00D4A8 100%);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: #e9ecef;
        border: none;
        color: #333;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .btn-secondary:hover {
        background: #dee2e6;
        transform: translateY(-2px);
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="section page__content">
    <div class="container">
        <div class="resume-builder-container">
            <!-- Resume Builder Content -->
            <div class="resume-builder-card">
                <?php echo $__env->yieldContent('resume-content'); ?>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Processing...';
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('front.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Herd-Projects\HiredHero-Job-Match\resources\views/placement/resume-builder/layout.blade.php ENDPATH**/ ?>