

<?php $__env->startSection('step-content'); ?>

<div class="wizard-step-content">
    <?php
    $extractedSkills = $extractedSkills ?? [];
    $hasSkills = count($extractedSkills) > 0;
    ?>

    <!-- Skills Success Modal -->
    <?php if($hasSkills): ?>
    <div class="skills-modal-overlay" id="skillsModal">
        <div class="skills-modal-content">
            <div class="modal-icon-wrapper">
                <div class="modal-icon-bg"></div>
                <i class="ri-checkbox-circle-line modal-icon-check"></i>
            </div>
            
            <h3 class="modal-title">You already have the skills!</h3>
            <p class="modal-subtitle"><?php echo e(count($extractedSkills)); ?> skills extracted from your resume</p>
            
            <div class="modal-skills-preview">
                <?php $__currentLoopData = $extractedSkills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="skill-badge"><?php echo e($skill); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <p class="modal-message">You can add more skills or remove any you don't need below.</p>
            
            <button type="button" class="modal-btn-okay" onclick="dismissSkillsModal()">Okay, Got it!</button>
            
            <div class="modal-sparkles">
                <span class="sparkle"></span>
                <span class="sparkle"></span>
                <span class="sparkle"></span>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Form Content (Hidden if modal shows) -->
    <div class="form-content" id="formContent" style="display: <?php echo e($hasSkills ? 'none' : 'block'); ?>;">
        <h2 class="step-title mb-2">Your Skills Profile</h2>
        <p class="step-description mb-4">
            <?php if($hasSkills): ?>
                <?php echo e(count($extractedSkills)); ?> skills extracted. Add more or remove any you don't need.
            <?php else: ?>
                Add your key skills to improve job matching.
            <?php endif; ?>
        </p>

        <form action="<?php echo e(route('placement.wizard.submit', ['step' => 7])); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <!-- Extracted Skills -->
            <?php if($hasSkills): ?>
            <div class="form-group mb-4">
                <label class="form-label fw-semibold mb-2">Extracted Skills</label>
                <div class="skills-container">
                    <?php $__currentLoopData = $extractedSkills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="skill-tag extracted-skill" data-skill="<?php echo e($skill); ?>">
                        <span><?php echo e($skill); ?></span>
                        <button type="button" class="skill-remove" onclick="skillsManager.removeSkill('<?php echo e($skill); ?>', 'extracted')">✕</button>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Years of Experience -->
            <div class="form-group mb-4">
                <label for="years_experience" class="form-label fw-semibold mb-2">Years of Experience</label>
                <div class="experience-input-group">
                    <input type="number" name="years_experience" id="years_experience"
                        class="form-control <?php $__errorArgs = ['years_experience'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        min="0" max="70" step="0.5"
                        value="<?php echo e(old('years_experience', $profile->years_experience ?? '')); ?>"
                        placeholder="e.g., 5">
                    <span class="input-suffix">years</span>
                </div>
                <?php $__errorArgs = ['years_experience'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Add Skills -->
            <div class="form-group mb-4">
                <label for="manual_skills" class="form-label fw-semibold mb-2">
                    <?php if($hasSkills): ?> Add More Skills <?php else: ?> Add Skills <?php endif; ?>
                </label>
                <input type="text" id="skills_input" class="form-control"
                    placeholder="Type skill and press Enter"
                    data-role="tagsinput">
                <div id="addedSkillsList" class="skills-container mt-2"></div>
            </div>

            <!-- Navigation -->
            <div class="d-flex gap-2 justify-content-between align-items-center pt-3 border-top">
                <span class="text-muted" style="font-size: 0.9rem;">88%</span>
                <div>
                    <a href="<?php echo e(route('placement.wizard.step', ['step' => 6])); ?>" class="btn btn-sm btn-outline-secondary">Back</a>
                    <button type="submit" class="btn btn-sm btn-primary">Next</button>
                </div>
            </div>
        </form>
    </div><!-- form-content -->
</div>

<style>
    @keyframes modalSlideUp {
        from {
            opacity: 0;
            transform: translateY(50px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes pulseIcon {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 0 0 15px rgba(16, 185, 129, 0);
        }
    }

    @keyframes checkSlide {
        from {
            transform: scale(0) rotate(-45deg);
            opacity: 0;
        }
        to {
            transform: scale(1) rotate(0);
            opacity: 1;
        }
    }

    @keyframes floatSparkle {
        0%, 100% {
            opacity: 0;
            transform: translateY(0) scale(0);
        }
        50% {
            opacity: 1;
            transform: translateY(-40px) scale(1);
        }
    }

    /* Modal Overlay */
    .skills-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        backdrop-filter: blur(5px);
    }

    .skills-modal-content {
        position: relative;
        background: linear-gradient(135deg, #0f766e 0%, #14919b 50%, #0d9488 100%);
        border-radius: 1.5rem;
        padding: 3rem 2.5rem;
        max-width: 500px;
        width: 90%;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        animation: modalSlideUp 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    }

    .skills-modal-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.15) 0%, transparent 60%);
        border-radius: 1.5rem;
        pointer-events: none;
    }

    .modal-icon-wrapper {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto 2rem;
        flex-shrink: 0;
    }

    .modal-icon-bg {
        position: absolute;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
        border-radius: 50%;
        top: 0;
        left: 0;
    }

    .modal-icon-check {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 3.5rem;
        color: #fbbf24;
        animation: pulseIcon 2s infinite, checkSlide 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .modal-title {
        color: white;
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .modal-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.95rem;
        margin: 0 0 1.5rem 0;
    }

    .modal-skills-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
        margin: 1.5rem 0;
    }

    .skill-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.4rem 0.875rem;
        border-radius: 1.5rem;
        font-size: 0.85rem;
        font-weight: 500;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .modal-message {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.95rem;
        margin: 1.5rem 0 2rem 0;
        line-height: 1.5;
    }

    .modal-btn-okay {
        position: relative;
        z-index: 10;
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: #1a1f2e;
        border: none;
        padding: 0.875rem 2.5rem;
        border-radius: 0.75rem;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(251, 191, 36, 0.4);
    }

    .modal-btn-okay:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(251, 191, 36, 0.6);
    }

    .modal-btn-okay:active {
        transform: translateY(-1px);
    }

    .modal-sparkles {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
    }

    .modal-sparkles .sparkle {
        position: absolute;
        display: inline-block;
        width: 10px;
        height: 10px;
        background: rgba(251, 191, 36, 0.9);
        border-radius: 50%;
    }

    .modal-sparkles .sparkle:nth-child(1) {
        top: 20px;
        right: 30px;
        animation: floatSparkle 2s ease-in-out infinite;
    }

    .modal-sparkles .sparkle:nth-child(2) {
        top: 60%;
        right: 20px;
        animation: floatSparkle 2.5s ease-in-out infinite 0.3s;
    }

    .modal-sparkles .sparkle:nth-child(3) {
        bottom: 30px;
        left: 40px;
        animation: floatSparkle 3s ease-in-out infinite 0.6s;
    }

    /* Form Content */
    .form-content {
        animation: modalSlideUp 0.6s ease-out 0.3s backwards;
        opacity: 1;
        transition: opacity 0.4s ease-out;
    }
    .wizard-step-content {
        padding: 1rem 0;
    }

    .step-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1f2e;
        margin: 0;
    }

    .step-description {
        color: #6b7280;
        font-size: 0.95rem;
    }

    .form-control {
        padding: 0.6rem 0.875rem;
        font-size: 0.95rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
    }

    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }

    .experience-input-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .experience-input-group .form-control {
        padding-right: 3rem;
    }

    .input-suffix {
        position: absolute;
        right: 0.875rem;
        color: #6b7280;
        font-size: 0.9rem;
        pointer-events: none;
    }

    .form-label {
        margin-bottom: 0.5rem;
        color: #1f2937;
        font-size: 0.95rem;
    }

    .skills-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        padding: 0.875rem;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        min-height: 2.5rem;
    }

    .skill-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.75rem;
        background: #3b82f6;
        color: white;
        border-radius: 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        white-space: nowrap;
    }

    .skill-tag.extracted-skill {
        background: #10b981;
    }

    .skill-tag.added-skill {
        background: #8b5cf6;
    }

    .skill-remove {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        padding: 0;
        margin-left: 0.25rem;
        font-size: 0.9rem;
        font-weight: bold;
        opacity: 0.8;
    }

    .skill-remove:hover {
        opacity: 1;
    }

    .border-top {
        border-color: #e5e7eb !important;
        margin-top: 1.5rem;
    }

    .btn-sm {
        padding: 0.4rem 0.875rem;
        font-size: 0.85rem;
    }

    .btn-outline-secondary {
        background: transparent;
        border: 1px solid #d1d5db;
        color: #4b5563;
    }

    .btn-outline-secondary:hover {
        background: #f3f4f6;
        border-color: #9ca3af;
    }

    .btn-primary {
        background: #3b82f6;
        border: 1px solid #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background: #2563eb;
        border-color: #2563eb;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    @media (max-width: 768px) {
        .step-title {
            font-size: 1.25rem;
        }

        .d-flex {
            flex-direction: column;
            gap: 0.75rem;
        }

        .btn {
            width: 100%;
        }
    }
</style>

<script>
    const skillsManager = {
        extractedSkills: new Set(),
        addedSkills: new Set(),
        
        init() {
            document.querySelectorAll('.extracted-skill').forEach(el => {
                const skillName = el.textContent.trim().replace('✕', '').trim();
                this.extractedSkills.add(skillName);
            });
            
            const skillsInput = document.getElementById('skills_input');
            if (skillsInput) {
                skillsInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const skill = skillsInput.value.trim();
                        if (skill && !this.extractedSkills.has(skill) && !this.addedSkills.has(skill)) {
                            this.addSkill(skill);
                            skillsInput.value = '';
                        }
                    }
                });
            }
            
            document.querySelector('form').addEventListener('submit', (e) => {
                let skillsInput = document.getElementById('skills_json');
                if (!skillsInput) {
                    skillsInput = document.createElement('input');
                    skillsInput.type = 'hidden';
                    skillsInput.id = 'skills_json';
                    skillsInput.name = 'skills_json';
                    e.currentTarget.appendChild(skillsInput);
                }
                
                skillsInput.value = JSON.stringify({
                    extracted: Array.from(this.extractedSkills),
                    added: Array.from(this.addedSkills),
                    all: Array.from(this.extractedSkills).concat(Array.from(this.addedSkills))
                });
            });
        },
        
        addSkill(skillName) {
            this.addedSkills.add(skillName);
            const container = document.getElementById('addedSkillsList');
            const skillTag = document.createElement('div');
            skillTag.className = 'skill-tag added-skill';
            skillTag.dataset.skill = skillName;
            skillTag.innerHTML = `<span>${skillName}</span><button type="button" class="skill-remove" onclick="skillsManager.removeSkill('${skillName}', 'added')">✕</button>`;
            container.appendChild(skillTag);
        },
        
        removeSkill(skillName, type) {
            const selector = type === 'extracted' 
                ? `.extracted-skill[data-skill="${skillName}"]` 
                : `.added-skill[data-skill="${skillName}"]`;
            const el = document.querySelector(selector);
            if (el) {
                el.remove();
                if (type === 'extracted') this.extractedSkills.delete(skillName);
                else this.addedSkills.delete(skillName);
            }
        }
    };
    
    function dismissSkillsModal() {
        const modal = document.getElementById('skillsModal');
        const formContent = document.getElementById('formContent');
        
        if (modal && formContent) {
            // Fade out modal
            modal.style.opacity = '0';
            modal.style.transition = 'opacity 0.3s ease-out';
            
            // Show form with fade-in
            setTimeout(() => {
                modal.style.display = 'none';
                formContent.style.display = 'block';
                formContent.style.opacity = '0';
                
                // Trigger reflow and then animate in
                setTimeout(() => {
                    formContent.style.transition = 'opacity 0.4s ease-out';
                    formContent.style.opacity = '1';
                }, 10);
            }, 300);
        }
    }
    
    document.addEventListener('DOMContentLoaded', () => skillsManager.init());
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('placement.wizard.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Herd-Projects\HiredHero-Job-Match\resources\views/placement/wizard/step-7.blade.php ENDPATH**/ ?>