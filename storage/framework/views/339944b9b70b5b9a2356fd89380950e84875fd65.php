<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($personal_info['full_name']); ?> - Resume</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            line-height: 1.7;
            color: #2c2c2c;
            background: white;
        }

        .container {
            max-width: 8.5in;
            height: auto;
            margin: 0 auto;
            padding: 0.5in;
            background: white;
        }

        header {
            text-align: center;
            border-bottom: 2px solid #2c2c2c;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        header h1 {
            font-size: 26px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 5px;
            letter-spacing: 2px;
        }

        header p {
            font-size: 13px;
            color: #555;
            margin-bottom: 8px;
        }

        .contact-info {
            font-size: 11px;
            color: #666;
        }

        .contact-info span {
            margin: 0 8px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #2c2c2c;
            border-bottom: 1px solid #2c2c2c;
            padding-bottom: 5px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .item {
            margin-bottom: 12px;
        }

        .item-title {
            font-weight: bold;
            font-size: 13px;
            color: #1a1a1a;
        }

        .item-meta {
            font-size: 11px;
            color: #666;
            font-style: italic;
        }

        .item-description {
            font-size: 11px;
            color: #555;
            margin-top: 4px;
            line-height: 1.5;
        }

        .item-subtitle {
            font-size: 12px;
            color: #555;
            margin-top: 2px;
        }

        .skills-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .skill-tag {
            display: inline-block;
            font-size: 11px;
            color: #555;
        }

        .languages-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 8px;
        }

        .language-item {
            font-size: 12px;
            color: #555;
        }

        .summary-text {
            font-size: 12px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .container {
                box-shadow: none;
                margin: 0;
                padding: 0.5in;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header>
            <h1><?php echo e($personal_info['full_name']); ?></h1>
            <?php if($personal_info['professional_title']): ?>
                <p><?php echo e($personal_info['professional_title']); ?></p>
            <?php endif; ?>
            <div class="contact-info">
                <?php if($personal_info['email']): ?>
                    <span><?php echo e($personal_info['email']); ?></span>
                <?php endif; ?>
                <?php if($personal_info['phone']): ?>
                    <span>•</span>
                    <span><?php echo e($personal_info['phone']); ?></span>
                <?php endif; ?>
                <?php if($personal_info['location']): ?>
                    <span>•</span>
                    <span><?php echo e($personal_info['location']); ?></span>
                <?php endif; ?>
            </div>
        </header>

        <!-- Professional Summary -->
        <?php if($personal_info['summary']): ?>
            <section class="section">
                <div class="section-title">Professional Summary</div>
                <p class="summary-text"><?php echo e($personal_info['summary']); ?></p>
            </section>
        <?php endif; ?>

        <!-- Work Experience -->
        <?php if(!empty($work_experience)): ?>
            <section class="section">
                <div class="section-title">Professional Experience</div>
                <?php $__currentLoopData = $work_experience; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="item">
                        <div style="display: flex; justify-content: space-between;">
                            <span class="item-title"><?php echo e($job['job_title']); ?></span>
                            <span class="item-meta">
                                <?php if($job['start_date'] ?? false): ?>
                                    <?php echo e(\Carbon\Carbon::parse($job['start_date'])->format('M Y')); ?> -
                                    <?php if($job['currently_working'] ?? false): ?>
                                        Present
                                    <?php elseif($job['end_date'] ?? false): ?>
                                        <?php echo e(\Carbon\Carbon::parse($job['end_date'])->format('M Y')); ?>

                                    <?php endif; ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="item-subtitle"><?php echo e($job['company']); ?> <?php if($job['location'] ?? false): ?> • <?php echo e($job['location']); ?> <?php endif; ?></div>
                        <?php if($job['description']): ?>
                            <div class="item-description"><?php echo e($job['description']); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </section>
        <?php endif; ?>

        <!-- Education -->
        <?php if(!empty($education)): ?>
            <section class="section">
                <div class="section-title">Education</div>
                <?php $__currentLoopData = $education; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $edu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="item">
                        <div style="display: flex; justify-content: space-between;">
                            <span class="item-title"><?php echo e($edu['degree']); ?><?php if($edu['field'] ?? false): ?> in <?php echo e($edu['field']); ?><?php endif; ?></span>
                            <?php if($edu['graduation_date'] ?? false): ?>
                                <span class="item-meta"><?php echo e(\Carbon\Carbon::parse($edu['graduation_date'])->format('Y')); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="item-subtitle"><?php echo e($edu['institution']); ?></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </section>
        <?php endif; ?>

        <!-- Skills -->
        <?php if(!empty($skills)): ?>
            <section class="section">
                <div class="section-title">Skills</div>
                <div class="skills-container">
                    <?php $__currentLoopData = $skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="skill-tag">• <?php echo e($skill); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- Languages -->
        <?php if(!empty($languages)): ?>
            <section class="section">
                <div class="section-title">Languages</div>
                <div class="languages-container">
                    <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="language-item"><?php echo e($lang); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- Certifications -->
        <?php if(!empty($certifications)): ?>
            <section class="section">
                <div class="section-title">Certifications</div>
                <?php $__currentLoopData = $certifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="item">
                        <span class="item-title"><?php echo e($cert['name']); ?></span>
                        <?php if($cert['issuer']): ?>
                            <span class="item-meta" style="margin-left: 8px;"><?php echo e($cert['issuer']); ?></span>
                        <?php endif; ?>
                        <?php if($cert['issue_date']): ?>
                            <span class="item-meta" style="margin-left: 8px;"><?php echo e(\Carbon\Carbon::parse($cert['issue_date'])->format('M Y')); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </section>
        <?php endif; ?>
    </div>
</body>
</html>
<?php /**PATH D:\Herd-Projects\HiredHero-Job-Match\resources\views/placement/resume-templates/classic.blade.php ENDPATH**/ ?>