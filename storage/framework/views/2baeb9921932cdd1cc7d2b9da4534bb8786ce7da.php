

<?php
use Illuminate\Support\Str;
?>

<?php $__env->startSection('resume-content'); ?>
<?php if(!$resume || !$resume->id): ?>
<div class="alert alert-danger">
    <h4>Resume Not Found</h4>
    <p>Unable to load the resume. Please try again or go back to Step 6.</p>
    <a href="<?php echo e(route('placement.wizard.step', ['step' => 6])); ?>" class="btn btn-primary">Back to Step 6</a>
</div>
<?php else: ?>
<div class="resume-view-container">
    <div class="resume-header">
        <div class="header-content">
            <div>
                <h2 class="resume-title"><?php echo e($resume->title ?? 'Untitled Resume'); ?></h2>
                <p class="resume-meta">
                    <?php echo e($resume->template_name ?? 'Professional'); ?> Template
                    <?php if($resume->created_at): ?>
                    • Created <?php echo e($resume->created_at->format('M d, Y')); ?>

                    <?php endif; ?>
                </p>
            </div>
            <div class="header-actions">
                <a href="<?php echo e(route('resume-builder.download', ['resume' => $resume->id])); ?>" class="btn btn-primary" target="_blank">
                    <i class="ri-download-line"></i>
                    Download PDF
                </a>
            </div>
        </div>
    </div>

    <div class="resume-preview-section">
        <div class="preview-header">
            <h3>Resume Preview</h3>
            <p class="text-muted">This is how your resume will appear to employers</p>
        </div>

        <div class="resume-iframe-container">
            <iframe src="<?php echo e(route('resume-builder.preview', ['resume' => $resume->id])); ?>"
                frameborder="0"
                width="100%"
                height="800px"
                style="border: 1px solid #e5e7eb; border-radius: 0.5rem;">
            </iframe>
        </div>
    </div>

    <!-- Resume Data Summary -->
    <div class="resume-data-section mt-5">
        <div class="row g-4">
            <div class="col-12">
                <div class="info-card">
                    <h4>Resume Information</h4>
                    <ul class="info-list">
                        <li><strong>Full Name:</strong> <?php echo e($resume->data['personal_info']['full_name'] ?? $resume->data['full_name'] ?? 'N/A'); ?></li>
                        <li><strong>Professional Title:</strong> <?php echo e($resume->data['personal_info']['professional_title'] ?? 'N/A'); ?></li>
                        <li><strong>Email:</strong> <?php echo e($resume->data['personal_info']['email'] ?? $resume->data['email'] ?? 'N/A'); ?></li>
                        <?php if($resume->data['personal_info']['phone'] ?? $resume->data['phone'] ?? null): ?>
                        <li><strong>Phone:</strong> <?php echo e($resume->data['personal_info']['phone'] ?? $resume->data['phone']); ?></li>
                        <?php endif; ?>
                        <?php if($resume->data['personal_info']['location'] ?? $resume->data['location'] ?? null): ?>
                        <li><strong>Location:</strong> <?php echo e($resume->data['personal_info']['location'] ?? $resume->data['location']); ?></li>
                        <?php endif; ?>
                        <?php if($resume->data['personal_info']['summary'] ?? null): ?>
                        <li><strong>Professional Summary:</strong> <?php echo e(Str::limit($resume->data['personal_info']['summary'], 150)); ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="col-12">
                <div class="info-card">
                    <h4>Skills (<?php echo e(count($resume->data['skills'] ?? [])); ?>)</h4>
                    <div class="skills-preview">
                        <?php $__empty_1 = true; $__currentLoopData = $resume->data['skills'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <span class="skill-badge"><?php echo e($skill); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted">No skills added</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if(!empty($resume->data['work_experience'])): ?>
            <div class="col-12">
                <div class="info-card">
                    <h4>Work Experience (<?php echo e(count($resume->data['work_experience'])); ?>)</h4>
                    <ul class="info-list">
                        <?php $__currentLoopData = $resume->data['work_experience']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <strong><?php echo e($exp['job_title'] ?? 'N/A'); ?></strong><br>
                            <small class="text-muted"><?php echo e($exp['company'] ?? 'N/A'); ?><?php if($exp['location'] ?? null): ?> • <?php echo e($exp['location']); ?><?php endif; ?></small>
                            <?php if($exp['description'] ?? null): ?>
                            <div style="font-size: 0.9rem; margin-top: 5px;"><?php echo e(Str::limit($exp['description'], 200)); ?></div>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <?php if(!empty($resume->data['education'])): ?>
            <div class="col-12">
                <div class="info-card">
                    <h4>Education (<?php echo e(count($resume->data['education'])); ?>)</h4>
                    <ul class="info-list">
                        <?php $__currentLoopData = $resume->data['education']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $edu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <strong><?php echo e($edu['degree'] ?? 'N/A'); ?><?php if($edu['field'] ?? null): ?> in <?php echo e($edu['field']); ?><?php endif; ?></strong><br>
                            <small class="text-muted"><?php echo e($edu['institution'] ?? 'N/A'); ?><?php if($edu['graduation_date'] ?? null): ?> • <?php echo e($edu['graduation_date']); ?><?php endif; ?></small>
                            <?php if($edu['description'] ?? null): ?>
                            <div style="font-size: 0.9rem; margin-top: 5px;"><?php echo e(Str::limit($edu['description'], 150)); ?></div>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <?php if(!empty($resume->data['certifications'])): ?>
            <div class="col-12">
                <div class="info-card">
                    <h4>Certifications (<?php echo e(count($resume->data['certifications'])); ?>)</h4>
                    <ul class="info-list">
                        <?php $__currentLoopData = $resume->data['certifications']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <strong><?php echo e($cert['name'] ?? 'N/A'); ?></strong>
                            <?php if($cert['issuer'] ?? null): ?>
                            <br><small class="text-muted"><?php echo e($cert['issuer']); ?><?php if($cert['issue_date'] ?? null): ?> • <?php echo e($cert['issue_date']); ?><?php endif; ?></small>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <?php if(!empty($resume->data['languages'])): ?>
            <div class="col-12">
                <div class="info-card">
                    <h4>Languages (<?php echo e(count($resume->data['languages'])); ?>)</h4>
                    <div class="skills-preview">
                        <?php $__currentLoopData = $resume->data['languages']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="skill-badge"><?php echo e($lang); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Actions -->
    <div class="actions-section mt-5 pt-4 border-top">
        <div class="row g-2 mb-3">
            <div class="col-auto">
                <a href="<?php echo e(route('resume-builder.download', ['resume' => $resume->id])); ?>" class="btn btn-primary" download>
                    <i class="ri-download-line"></i>
                    Download as PDF
                </a>
            </div>
            <div class="col-auto">
                <a href="<?php echo e(route('resume-builder.edit', ['resume' => $resume->id])); ?>" class="btn btn-outline-primary">
                    <i class="ri-edit-line"></i>
                    Edit Resume
                </a>
            </div>
            <div class="col-auto">
                <form action="<?php echo e(route('resume-builder.destroy', ['resume' => $resume->id])); ?>" method="POST" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this resume?')">
                        <i class="ri-delete-line"></i>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="row g-2 navigation-buttons">
            <div class="col-12">
                <div class="d-flex gap-2 flex-wrap justify-content-between">
                    <a href="<?php echo e(route('placement.wizard.step', ['step' => 6])); ?>" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line"></i>
                        Back to Step 6
                    </a>
                    <form action="<?php echo e(route('placement.wizard.submit', ['step' => 6])); ?>" method="POST" enctype="multipart/form-data" style="display: inline;" id="builtResumeForm">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="resume_option" value="existing">
                        <input type="hidden" name="email" id="resumeEmail" value="<?php echo e(auth()->user()->email); ?>">
                        <input type="hidden" name="terms_agreed" value="1">
                        <input type="file" name="resume" id="builtResume" class="d-none" accept=".pdf">
                        <button type="button" class="btn btn-success" onclick="submitBuiltResume()">
                            <i class="ri-arrow-right-line"></i>
                            Continue to Step 7
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function submitBuiltResume() {
            try {
                // Fetch the resume PDF
                const downloadUrl = '<?php echo e(route('resume-builder.download', ['resume' => $resume->id])); ?>';
                const response = await fetch(downloadUrl);
                
                if (!response.ok) {
                    throw new Error('Failed to fetch resume PDF');
                }

                const blob = await response.blob();
                
                // Get the form
                const form = document.getElementById('builtResumeForm');
                const fileInput = document.getElementById('builtResume');

                // Create a File object from the blob
                const timestamp = new Date().getTime();
                const file = new File([blob], `resume-<?php echo e($resume->id); ?>-${timestamp}.pdf`, { type: 'application/pdf' });

                // Create DataTransfer and set files
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;

                console.log('Resume file prepared for submission');

                // Submit the form
                form.submit();
            } catch (error) {
                console.error('Error submitting resume:', error);
                alert('Failed to submit resume. Please try again.');
            }
        }
    </script>
</div>
<?php endif; ?>

<style>
    .resume-view-container {
        padding: 2rem 0;
    }

    .resume-header {
        background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
        border: 2px solid #3b82f6;
        padding: 2rem;
        border-radius: 0.75rem;
        margin-bottom: 2rem;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 2rem;
    }

    .resume-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
    }

    .resume-meta {
        font-size: 0.9rem;
        color: #6b7280;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    .preview-header {
        margin-bottom: 1.5rem;
    }

    .preview-header h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
    }

    .resume-iframe-container {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .info-card {
        background: white;
        border: 1px solid #e5e7eb;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .info-card h4 {
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #3b82f6;
    }

    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .info-list li {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
        color: #4b5563;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .info-list li:last-child {
        border-bottom: none;
    }

    .info-list strong {
        color: #1a1a1a;
        display: block;
        margin-bottom: 0.25rem;
    }

    .skills-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .skill-badge {
        display: inline-block;
        background: #dbeafe;
        color: #1e40af;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .actions-section {
        border-color: #e5e7eb !important;
    }

    .actions-section .row.mb-3 {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
    }

    .actions-section .col-auto {
        display: flex;
        align-items: center;
    }

    .actions-section .col-auto form {
        display: inline;
    }

    @media (max-width: 768px) {
        .actions-section .row.mb-3 {
            flex-direction: column;
        }

        .actions-section .col-auto {
            width: 100%;
        }

        .actions-section .btn {
            width: 100%;
            justify-content: center;
        }
    }

    .navigation-buttons {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 2px solid #e5e7eb;
    }

    .navigation-buttons .d-flex {
        width: 100%;
        display: flex !important;
        gap: 1rem !important;
        flex-wrap: wrap;
        align-items: center;
    }

    .navigation-buttons .d-flex>div {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }

    @media (max-width: 768px) {
        .navigation-buttons .d-flex {
            flex-direction: column;
        }

        .navigation-buttons .d-flex>div {
            width: 100%;
        }

        .navigation-buttons .d-flex>div .btn {
            width: 100%;
            justify-content: center;
        }
    }

    .btn {
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        border-radius: 0.375rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
    }

    .btn-outline-primary {
        border: 1.5px solid #3b82f6;
        color: #3b82f6;
        background: white;
    }

    .btn-outline-primary:hover {
        background: #eff6ff;
    }

    .btn-outline-danger {
        border: 1.5px solid #ef4444;
        color: #ef4444;
        background: white;
    }

    .btn-outline-danger:hover {
        background: #fef2f2;
    }

    .btn-secondary {
        background: #6b7280;
        border: none;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }

    .btn-outline-secondary {
        border: 2px solid #9ca3af;
        color: #374151;
        background: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        font-weight: 600;
    }

    .btn-outline-secondary:hover {
        background: #f9fafb;
        border-color: #6b7280;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: 2px solid #059669;
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        font-weight: 700;
    }

    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(16, 185, 129, 0.4);
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    .btn-secondary:hover {
        background: #4b5563;
    }

    .text-muted {
        color: #6b7280;
    }

    .border-top {
        border-color: #e5e7eb !important;
    }

    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .btn {
            width: 100%;
            justify-content: center;
        }

        .actions-section {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .resume-iframe-container iframe {
            height: 600px;
        }
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('placement.resume-builder.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Herd-Projects\HiredHero-Job-Match\resources\views/placement/resume-builder/view.blade.php ENDPATH**/ ?>