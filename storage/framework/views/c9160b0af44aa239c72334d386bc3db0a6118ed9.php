

<?php $__env->startSection('resume-content'); ?>
<div class="resume-builder-form-container">
    <div class="form-header mb-5">
        <h2 class="step-title mb-3">Build Your Professional Resume</h2>
        <p class="step-description">Fill in your information and we'll create a beautiful, ATS-optimized resume</p>
    </div>

    <form action="<?php echo e(route('resume-builder.store')); ?>" method="POST" id="resumeForm">
        <?php echo csrf_field(); ?>

        <!-- Section 1: Personal Information -->
        <div class="form-section mb-5">
            <h4 class="section-header">
                <i class="ri-user-line"></i>
                Personal Information
            </h4>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" id="full_name" class="form-control <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('full_name', $profile->user->name ?? '')); ?>" required>
                        <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('email', $profile->email ?? '')); ?>" required>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" name="phone" id="phone" class="form-control"
                               value="<?php echo e(old('phone')); ?>" placeholder="+1 (555) 000-0000">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="professional_title" class="form-label">Professional Title</label>
                        <input type="text" name="professional_title" id="professional_title" class="form-control"
                               value="<?php echo e(old('professional_title')); ?>" placeholder="e.g., Senior Software Engineer">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="professional_summary" class="form-label">Professional Summary</label>
                        <textarea name="professional_summary" id="professional_summary" class="form-control" rows="4"
                                  placeholder="Write a brief summary of your professional background and goals..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Work Experience -->
        <div class="form-section mb-5">
            <div class="section-header-with-action">
                <h4 class="section-header">
                    <i class="ri-briefcase-line"></i>
                    Work Experience
                </h4>
                <button type="button" class="btn btn-sm btn-outline-primary waves-effect waves-light" id="addExperience">
                    <i class="ri-add-line"></i>
                    Add Experience
                </button>
            </div>

            <div id="workExperienceContainer">
                <!-- Experience items will be added here -->
            </div>
        </div>

        <!-- Section 3: Education -->
        <div class="form-section mb-5">
            <div class="section-header-with-action">
                <h4 class="section-header">
                    <i class="ri-graduation-cap-line"></i>
                    Education
                </h4>
                <button type="button" class="btn btn-sm btn-outline-primary waves-effect waves-light" id="addEducation">
                    <i class="ri-add-line"></i>
                    Add Education
                </button>
            </div>

            <div id="educationContainer">
                <!-- Education items will be added here -->
            </div>
        </div>

        <!-- Section 4: Skills -->
        <div class="form-section mb-5">
            <h4 class="section-header">
                <i class="ri-flashlight-line"></i>
                Skills
            </h4>

            <div class="form-group">
                <label for="skills" class="form-label">Enter Your Skills (comma-separated)</label>
                <textarea id="skills" class="form-control" rows="3"
                          placeholder="e.g., JavaScript, React, Node.js, MongoDB, Git, REST APIs"></textarea>
                <small class="text-muted">
                    <i class="ri-lightbulb-line"></i>
                    We'll split these by comma automatically
                </small>
            </div>

            <div id="skillsPreview" class="skills-preview">
                <!-- Skills will be displayed here as tags -->
            </div>
        </div>

        <!-- Section 5: Resume Template Selection -->
        <div class="form-section mb-5">
            <h4 class="section-header">
                <i class="ri-palette-line"></i>
                Choose Your Resume Template <span class="text-danger">*</span>
            </h4>

            <div class="templates-grid">
                <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $templateKey => $templateName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="template-card">
                        <input type="radio" name="template" value="<?php echo e($templateKey); ?>" class="template-radio" required>
                        <div class="template-preview">
                            <div class="template-icon">
                                <?php switch($templateKey):
                                    case ('modern'): ?>
                                        <i class="ri-layout-4-line"></i>
                                        <?php break; ?>
                                    <?php case ('classic'): ?>
                                        <i class="ri-file-list-3-line"></i>
                                        <?php break; ?>
                                    <?php case ('minimalist'): ?>
                                        <i class="ri-layout-2-line"></i>
                                        <?php break; ?>
                                    <?php case ('professional'): ?>
                                        <i class="ri-layout-column-line"></i>
                                        <?php break; ?>
                                    <?php case ('creative'): ?>
                                        <i class="ri-brush-line"></i>
                                        <?php break; ?>
                                <?php endswitch; ?>
                            </div>
                            <span class="template-name"><?php echo e($templateName); ?></span>
                        </div>
                    </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php $__errorArgs = ['template'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block mt-2"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Submit -->
        <div class="form-actions">
            <a href="<?php echo e(route('placement.wizard.step', ['step' => 6])); ?>" class="btn btn-outline-secondary waves-effect waves-light">
                <i class="ri-arrow-left-line"></i>
                Back
            </a>
            <button type="submit" class="btn btn-primary waves-effect waves-light" id="submitBtn">
                <i class="ri-file-text-line"></i>
                Generate Resume
            </button>
        </div>
    </form>
</div>

<!-- Work Experience Template -->
<template id="experienceTemplate">
    <div class="form-card mb-3">
        <div class="card-header">
            <h5>Work Experience</h5>
            <button type="button" class="btn btn-sm btn-danger removeExperience waves-effect waves-light">
                -
            </button>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="work_experience[INDEX][job_title]" class="form-control" placeholder="Job Title" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="work_experience[INDEX][company]" class="form-control" placeholder="Company Name" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="work_experience[INDEX][location]" class="form-control" placeholder="Location">
                </div>
                <div class="col-md-6">
                    <input type="date" name="work_experience[INDEX][start_date]" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <input type="date" name="work_experience[INDEX][end_date]" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-check">
                        <input type="checkbox" name="work_experience[INDEX][currently_working]" class="form-check-input" value="1">
                        <span class="form-check-label">Currently Working Here</span>
                    </label>
                </div>
                <div class="col-12">
                    <textarea name="work_experience[INDEX][description]" class="form-control" placeholder="Job Description & Achievements" rows="3"></textarea>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Education Template -->
<template id="educationTemplate">
    <div class="form-card mb-3">
        <div class="card-header">
            <h5>Education</h5>
            <button type="button" class="btn btn-sm btn-danger removeEducation waves-effect waves-light">
                -
            </button>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="education[INDEX][degree]" class="form-control" placeholder="Degree (e.g., Bachelor of Science)" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="education[INDEX][institution]" class="form-control" placeholder="University/School" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="education[INDEX][field_of_study]" class="form-control" placeholder="Field of Study">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Graduation Date</label>
                    <input type="date" name="education[INDEX][graduation_date]" class="form-control">
                </div>
                <div class="col-12">
                    <textarea name="education[INDEX][description]" class="form-control" placeholder="Additional details (optional)" rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .resume-builder-form-container {
        padding: 2rem 0;
    }

    .form-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .step-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: #1a1a1a;
        line-height: 1.3;
        margin-bottom: 0.75rem;
    }

    .step-description {
        font-size: 1rem;
        color: #6c757d;
        line-height: 1.6;
    }

    .form-section {
        background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
        padding: 2rem;
        border-radius: 0.75rem;
        border: 1.5px solid #e5e7eb;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    .form-section:hover {
        border-color: #cbd5e0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .section-header {
        font-size: 1.15rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 1.75rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-header i {
        color: #3b82f6;
        font-size: 1.25rem;
    }

    .section-header-with-action {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.75rem;
    }

    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-control {
        border: 2px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.85rem 1.25rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background-color: #ffffff;
        color: #1f2937;
        font-weight: 500;
    }

    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        background-color: #f8faff;
        color: #1f2937;
        outline: none;
    }

    .form-control::placeholder {
        color: #6b7280;
        font-weight: 400;
    }

    .form-control:hover:not(:focus) {
        border-color: #9ca3af;
        background-color: #f9fafb;
    }

    .form-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
        border: 2px solid #3b82f6;
        border-radius: 0.75rem;
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1);
    }

    .form-card:hover {
        border-color: #2563eb;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.15);
        transform: translateY(-2px);
    }

    .card-header {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        padding: 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: none;
    }

    .card-header h5 {
        margin: 0;
        font-weight: 700;
        color: #ffffff;
        font-size: 1.05rem;
    }

    .card-body {
        padding: 1.75rem;
        background: #ffffff;
    }

    .skills-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .skill-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
        border: 1px solid #93c5fd;
        transition: all 0.3s ease;
    }

    .skill-tag:hover {
        background: linear-gradient(135deg, #bfdbfe 0%, #93c5fd 100%);
        transform: translateY(-2px);
    }

    .templates-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .template-card {
        position: relative;
        cursor: pointer;
    }

    .template-radio {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .template-preview {
        border: 2px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 2rem 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    .template-radio:checked + .template-preview {
        border-color: #3b82f6;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.15);
        transform: translateY(-4px);
    }

    .template-preview:hover {
        border-color: #3b82f6;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.08);
    }

    .template-icon {
        font-size: 2.5rem;
        color: #3b82f6;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .template-name {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: #1a1a1a;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 1.5px solid #e5e7eb;
    }

    .btn {
        padding: 0.75rem 2rem;
        font-weight: 700;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: 2px solid #2563eb;
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(59, 130, 246, 0.4);
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
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

    .btn-outline-primary {
        border: 2px solid #3b82f6;
        color: #3b82f6;
        background: white;
        box-shadow: 0 2px 6px rgba(59, 130, 246, 0.2);
        font-weight: 600;
    }

    .btn-outline-primary:hover {
        background: #eff6ff;
        border-color: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.25);
        color: #2563eb;
    }

    .btn-sm {
        padding: 0.6rem 1.25rem;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: 2px solid #dc2626;
        color: white;
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3);
        font-weight: 700;
        padding: 0.6rem 1rem;
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.4);
    }

    .text-danger {
        color: #dc2626;
    }

    .invalid-feedback {
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
    }

    .is-invalid {
        border-color: #dc2626 !important;
    }

    .text-muted {
        color: #6b7280;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    @media (max-width: 768px) {
        .step-title {
            font-size: 1.5rem;
        }

        .resume-builder-form-container {
            padding: 1rem 0;
        }

        .form-section {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .templates-grid {
            grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
            gap: 1rem;
        }

        .form-actions {
            flex-direction: column-reverse;
            gap: 0.75rem;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .card-body {
            padding: 1rem;
        }

        .template-preview {
            padding: 1.5rem 1rem;
        }
    }

    @media (max-width: 480px) {
        .step-title {
            font-size: 1.25rem;
        }

        .form-section {
            padding: 1.25rem;
        }

        .templates-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .btn {
            padding: 0.65rem 1.5rem;
            font-size: 0.9rem;
        }

        .section-header {
            font-size: 1rem;
        }
    }
</style>

<script>
    let experienceCount = 0;
    let educationCount = 0;

    // Add work experience
    document.getElementById('addExperience').addEventListener('click', function(e) {
        e.preventDefault();
        const container = document.getElementById('workExperienceContainer');
        const template = document.getElementById('experienceTemplate');
        const clone = template.content.cloneNode(true);
        
        // Replace INDEX with actual count
        const html = new XMLSerializer().serializeToString(clone);
        const updated = html.replace(/\[INDEX\]/g, `[${experienceCount}]`);
        
        const div = document.createElement('div');
        div.innerHTML = updated;
        div.style.animation = 'slideIn 0.3s ease';
        container.appendChild(div);
        experienceCount++;
        
        // Add remove listener
        div.querySelector('.removeExperience').addEventListener('click', function(e) {
            e.preventDefault();
            div.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => div.remove(), 300);
        });
    });

    // Add education
    document.getElementById('addEducation').addEventListener('click', function(e) {
        e.preventDefault();
        const container = document.getElementById('educationContainer');
        const template = document.getElementById('educationTemplate');
        const clone = template.content.cloneNode(true);
        
        // Replace INDEX with actual count
        const html = new XMLSerializer().serializeToString(clone);
        const updated = html.replace(/\[INDEX\]/g, `[${educationCount}]`);
        
        const div = document.createElement('div');
        div.innerHTML = updated;
        div.style.animation = 'slideIn 0.3s ease';
        container.appendChild(div);
        educationCount++;
        
        // Add remove listener
        div.querySelector('.removeEducation').addEventListener('click', function(e) {
            e.preventDefault();
            div.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => div.remove(), 300);
        });
    });

    // Skills preview
    document.getElementById('skills').addEventListener('input', function(e) {
        const skills = e.target.value.split(',').map(s => s.trim()).filter(s => s.length > 0);
        const preview = document.getElementById('skillsPreview');
        preview.innerHTML = '';
        
        skills.forEach(skill => {
            const tag = document.createElement('span');
            tag.className = 'skill-tag';
            tag.innerHTML = `<span>${skill}</span>`;
            tag.style.animation = 'popIn 0.3s ease';
            preview.appendChild(tag);
        });
    });

    // Form submission - clean and prepare data
    document.getElementById('resumeForm').addEventListener('submit', function(e) {
        const form = this;
        
        // Process skills
        const skillsInput = document.getElementById('skills').value;
        const skills = skillsInput.split(',').map(s => s.trim()).filter(s => s.length > 0);
        
        // Remove any existing skills inputs
        const existingInputs = form.querySelectorAll('input[name^="skills"]');
        existingInputs.forEach(input => input.remove());
        
        // Create array inputs for skills
        skills.forEach(skill => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'skills[]';
            input.value = skill;
            form.appendChild(input);
        });

        // Handle checkboxes - convert to proper values
        const workExperienceCheckboxes = form.querySelectorAll('input[type="checkbox"][name*="currently_working"]');
        workExperienceCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                checkbox.value = '1';
                checkbox.setAttribute('value', '1');
            } else {
                // Remove unchecked checkboxes from form submission
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = checkbox.name;
                hiddenInput.value = '0';
                checkbox.parentNode.appendChild(hiddenInput);
                checkbox.remove();
            }
        });
    });

    // Add animations to style
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes slideOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }
        @keyframes popIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    `;
    document.head.appendChild(style);
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('placement.resume-builder.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Herd-Projects\HiredHero-Job-Match\resources\views/placement/resume-builder/form.blade.php ENDPATH**/ ?>