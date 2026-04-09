@extends('placement.resume-builder.layout')

@section('resume-content')
<div class="resume-builder-form-container">
    <div class="form-header mb-5">
        <h2 class="step-title mb-3">Edit Your Professional Resume</h2>
        <p class="step-description">Update your information and regenerate your resume with enhanced content</p>
    </div>

    <form action="{{ route('resume-builder.update', ['resume' => $resume->id]) }}" method="POST" id="resumeForm">
        @csrf
        @method('PATCH')

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
                        <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                               id="full_name" name="full_name" 
                               value="{{ old('full_name', $resume->data['personal_info']['full_name'] ?? '') }}" required>
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" 
                               value="{{ old('email', $resume->data['personal_info']['email'] ?? '') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" 
                               value="{{ old('phone', $resume->data['personal_info']['phone'] ?? '') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="professional_title" class="form-label">Professional Title</label>
                        <input type="text" class="form-control @error('professional_title') is-invalid @enderror" 
                               id="professional_title" name="professional_title" 
                               placeholder="e.g., Senior Software Engineer"
                               value="{{ old('professional_title', $resume->data['personal_info']['professional_title'] ?? '') }}">
                        @error('professional_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="professional_summary" class="form-label">Professional Summary</label>
                        <textarea class="form-control @error('professional_summary') is-invalid @enderror" 
                                  id="professional_summary" name="professional_summary" rows="4"
                                  placeholder="Brief overview of your professional background and goals">{{ old('professional_summary', $resume->data['personal_info']['summary'] ?? '') }}</textarea>
                        <small class="text-muted">Our AI will enhance this with professional language</small>
                        @error('professional_summary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                @if($resume->data['work_experience'] ?? null)
                    @foreach($resume->data['work_experience'] as $index => $exp)
                        <div class="form-card mb-3">
                            <div class="card-header">
                                <h5>Work Experience</h5>
                                <button type="button" class="btn btn-sm btn-danger removeExperience waves-effect waves-light">-</button>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Job Title</label>
                                        <input type="text" class="form-control" name="work_experience[{{ $index }}][job_title]" 
                                               value="{{ $exp['job_title'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Company</label>
                                        <input type="text" class="form-control" name="work_experience[{{ $index }}][company]" 
                                               value="{{ $exp['company'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" class="form-control" name="work_experience[{{ $index }}][start_date]" 
                                               value="{{ $exp['start_date'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">End Date</label>
                                        <input type="date" class="form-control" name="work_experience[{{ $index }}][end_date]" 
                                               value="{{ $exp['end_date'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Location</label>
                                        <input type="text" class="form-control" name="work_experience[{{ $index }}][location]" 
                                               value="{{ $exp['location'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mt-4">
                                            <input type="checkbox" class="form-check-input" 
                                                   id="currently_working_{{ $index }}" 
                                                   name="work_experience[{{ $index }}][currently_working]" value="1"
                                                   {{ ($exp['currently_working'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="currently_working_{{ $index }}">
                                                Currently Working Here
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Description/Responsibilities</label>
                                        <textarea class="form-control" name="work_experience[{{ $index }}][description]" rows="3"
                                                  placeholder="Brief description of your responsibilities and achievements">{{ $exp['description'] ?? '' }}</textarea>
                                        <small class="text-muted">Our AI will enhance this with professional language</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
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
                @if($resume->data['education'] ?? null)
                    @foreach($resume->data['education'] as $index => $edu)
                        <div class="form-card mb-3">
                            <div class="card-header">
                                <h5>Education</h5>
                                <button type="button" class="btn btn-sm btn-danger removeEducation waves-effect waves-light">-</button>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Degree</label>
                                        <input type="text" class="form-control" name="education[{{ $index }}][degree]" 
                                               value="{{ $edu['degree'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Institution</label>
                                        <input type="text" class="form-control" name="education[{{ $index }}][institution]" 
                                               value="{{ $edu['institution'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Field of Study</label>
                                        <input type="text" class="form-control" name="education[{{ $index }}][field_of_study]" 
                                               value="{{ $edu['field'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Graduation Date</label>
                                        <input type="date" class="form-control" name="education[{{ $index }}][graduation_date]" 
                                               value="{{ $edu['graduation_date'] ?? '' }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Description (Optional)</label>
                                        <textarea class="form-control" name="education[{{ $index }}][description]" rows="2">{{ $edu['description'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
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
                          placeholder="e.g., JavaScript, React, Node.js, MongoDB, Git, REST APIs">{{ old('skills', implode(', ', $resume->data['skills'] ?? [])) }}</textarea>
                <small class="text-muted">Add your technical and soft skills separated by commas</small>
            </div>

            <div id="skillsPreview" class="skills-preview">
                @foreach($resume->data['skills'] ?? [] as $skill)
                    <span class="skill-tag"><span>{{ $skill }}</span></span>
                @endforeach
            </div>
        </div>

        <!-- Section 5: Resume Template Selection -->
        <div class="form-section mb-5">
            <h4 class="section-header">
                <i class="ri-palette-line"></i>
                Choose Your Resume Template <span class="text-danger">*</span>
            </h4>

            <div class="templates-grid">
                @foreach($templates as $templateKey => $templateName)
                    <label class="template-card">
                        <input type="radio" class="template-radio" name="template" value="{{ $templateKey }}" 
                               {{ ($resume->template_name === $templateKey) ? 'checked' : '' }} required>
                        <div class="template-preview">
                            <div class="template-icon">
                                @switch($templateKey)
                                    @case('modern')
                                        <i class="ri-layout-grid-fill"></i>
                                        @break
                                    @case('classic')
                                        <i class="ri-file-text-fill"></i>
                                        @break
                                    @case('professional')
                                        <i class="ri-briefcase-fill"></i>
                                        @break
                                    @case('minimalist')
                                        <i class="ri-align-left"></i>
                                        @break
                                    @case('creative')
                                        <i class="ri-palette-fill"></i>
                                        @break
                                @endswitch
                            </div>
                            <span class="template-name">{{ $templateName }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
            @error('template')
                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit -->
        <div class="form-actions">
            <a href="{{ route('resume-builder.view', ['resume' => $resume->id]) }}" class="btn btn-outline-secondary waves-effect waves-light">
                <i class="ri-arrow-left-line"></i>
                Back
            </a>
            <button type="submit" class="btn btn-primary waves-effect waves-light" id="submitBtn">
                <i class="ri-file-text-line"></i>
                Update Resume
            </button>
        </div>
    </form>
</div>

<!-- Work Experience Template -->
<template id="experienceTemplate">
    <div class="form-card mb-3">
        <div class="card-header">
            <h5>Work Experience</h5>
            <button type="button" class="btn btn-sm btn-danger removeExperience waves-effect waves-light">-</button>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Job Title</label>
                    <input type="text" class="form-control" name="work_experience[INDEX][job_title]" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Company</label>
                    <input type="text" class="form-control" name="work_experience[INDEX][company]" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Start Date</label>
                    <input type="date" class="form-control" name="work_experience[INDEX][start_date]" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">End Date</label>
                    <input type="date" class="form-control" name="work_experience[INDEX][end_date]">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Location</label>
                    <input type="text" class="form-control" name="work_experience[INDEX][location]">
                </div>
                <div class="col-md-6">
                    <div class="form-check mt-4">
                        <input type="checkbox" class="form-check-input" 
                               id="currently_working_INDEX" 
                               name="work_experience[INDEX][currently_working]" value="1">
                        <label class="form-check-label" for="currently_working_INDEX">
                            Currently Working Here
                        </label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Description/Responsibilities</label>
                    <textarea class="form-control" name="work_experience[INDEX][description]" rows="3"
                              placeholder="Brief description of your responsibilities and achievements"></textarea>
                    <small class="text-muted">Our AI will enhance this with professional language</small>
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
            <button type="button" class="btn btn-sm btn-danger removeEducation waves-effect waves-light">-</button>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Degree</label>
                    <input type="text" class="form-control" name="education[INDEX][degree]" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Institution</label>
                    <input type="text" class="form-control" name="education[INDEX][institution]" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Field of Study</label>
                    <input type="text" class="form-control" name="education[INDEX][field_of_study]">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Graduation Date</label>
                    <input type="date" class="form-control" name="education[INDEX][graduation_date]">
                </div>
                <div class="col-12">
                    <label class="form-label">Description (Optional)</label>
                    <textarea class="form-control" name="education[INDEX][description]" rows="2"></textarea>
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

    .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-check-input {
        width: auto;
        margin: 0;
        cursor: pointer;
        border: 1.5px solid #cbd5e0;
        border-radius: 0.25rem;
    }

    .form-check-input:checked {
        background: #3b82f6;
        border-color: #3b82f6;
    }

    .form-check-label {
        cursor: pointer;
        margin-bottom: 0;
        font-size: 0.95rem;
        color: #374151;
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
        
        const html = new XMLSerializer().serializeToString(clone);
        const updated = html.replace(/\[INDEX\]/g, `[${experienceCount}]`);
        
        const div = document.createElement('div');
        div.innerHTML = updated;
        div.style.animation = 'slideIn 0.3s ease';
        container.appendChild(div);
        experienceCount++;
        
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
        
        const html = new XMLSerializer().serializeToString(clone);
        const updated = html.replace(/\[INDEX\]/g, `[${educationCount}]`);
        
        const div = document.createElement('div');
        div.innerHTML = updated;
        div.style.animation = 'slideIn 0.3s ease';
        container.appendChild(div);
        educationCount++;
        
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

    // Form submission
    document.getElementById('resumeForm').addEventListener('submit', function(e) {
        const form = this;
        
        const skillsInput = document.getElementById('skills').value;
        const skills = skillsInput.split(',').map(s => s.trim()).filter(s => s.length > 0);
        
        const existingInputs = form.querySelectorAll('input[name^="skills"]');
        existingInputs.forEach(input => input.remove());
        
        skills.forEach(skill => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'skills[]';
            input.value = skill;
            form.appendChild(input);
        });

        const workExperienceCheckboxes = form.querySelectorAll('input[type="checkbox"][name*="currently_working"]');
        workExperienceCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                checkbox.value = '1';
                checkbox.setAttribute('value', '1');
            } else {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = checkbox.name;
                hiddenInput.value = '0';
                checkbox.parentNode.appendChild(hiddenInput);
                checkbox.remove();
            }
        });
    });

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
@endsection
