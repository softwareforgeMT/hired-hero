@extends('front.layouts.app')

@section('content')
<div class="container py-5 mt-12">
    <div class="row">
        <div class="col-lg-8">
            <h2 class="mb-4">Edit Your Tailored Resume</h2>

            <form id="resumeEditForm" class="resume-edit-form">
                @csrf
                <input type="hidden" name="job_id" value="{{ $job_id }}">
                <input type="hidden" name="template" value="{{ $template }}">

                <!-- Personal Information Section -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Personal Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="resume_data[name]"
                                    value="{{ $resume_data['name'] ?? '' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Professional Title</label>
                                <input type="text" class="form-control" name="resume_data[title]"
                                    value="{{ $resume_data['title'] ?? '' }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="resume_data[email]"
                                    value="{{ $resume_data['email'] ?? '' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" name="resume_data[phone]"
                                    value="{{ $resume_data['phone'] ?? '' }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" name="resume_data[location]"
                                value="{{ $resume_data['location'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Professional Summary</label>
                            <textarea class="form-control" name="resume_data[summary]" rows="4">{{ $resume_data['summary'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Skills Section -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Skills</h5>
                    </div>
                    <div class="card-body">
                        <div id="skillsContainer">
                            @php $skills = is_array($resume_data['skills'] ?? null) ? $resume_data['skills'] : []; @endphp
                            @forelse($skills as $i => $skill)
                            <div class="skill-row mb-2">
                                <input type="text" class="form-control" name="resume_data[skills][]"
                                    value="{{ $skill }}" placeholder="Enter skill">
                                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeSkill(this)">Remove</button>
                            </div>
                            @empty
                            <div class="skill-row mb-2">
                                <input type="text" class="form-control" name="resume_data[skills][]" placeholder="Enter skill">
                                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeSkill(this)">Remove</button>
                            </div>
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-outline-primary mt-2" onclick="addSkill()">+ Add Skill</button>
                    </div>
                </div>

                <!-- Work Experience Section -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Work Experience</h5>
                    </div>
                    <div class="card-body">
                        <div id="experienceContainer">
                            @php $experience = is_array($resume_data['experience'] ?? null) ? $resume_data['experience'] : []; @endphp
                            @forelse($experience as $i => $exp)
                            <div class="experience-item mb-4 p-3 border rounded">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Job Title</label>
                                        <input type="text" class="form-control" name="resume_data[experience][{{ $i }}][job_title]"
                                            value="{{ $exp['job_title'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Company</label>
                                        <input type="text" class="form-control" name="resume_data[experience][{{ $i }}][company]"
                                            value="{{ $exp['company'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" class="form-control" name="resume_data[experience][{{ $i }}][start_date]"
                                            value="{{ isset($exp['start_date']) ? ($exp['start_date'] instanceof \Carbon\Carbon ? $exp['start_date']->format('Y-m-d') : str_replace(' ', 'T', substr($exp['start_date'], 0, 10))) : '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">End Date</label>
                                        <input type="date" class="form-control" name="resume_data[experience][{{ $i }}][end_date]"
                                            value="{{ isset($exp['end_date']) ? ($exp['end_date'] instanceof \Carbon\Carbon ? $exp['end_date']->format('Y-m-d') : str_replace(' ', 'T', substr($exp['end_date'], 0, 10))) : '' }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-check">
                                        <input type="checkbox" class="form-check-input" name="resume_data[experience][{{ $i }}][currently_working]"
                                            value="1" {{ ($exp['currently_working'] ?? false) ? 'checked' : '' }}>
                                        Currently working here
                                    </label>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description / Achievements</label>
                                    <textarea class="form-control" name="resume_data[experience][{{ $i }}][description]" rows="3">{{ $exp['description'] ?? '' }}</textarea>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeExperience(this)">Remove This Experience</button>
                            </div>
                            @empty
                            <div class="experience-item mb-4 p-3 border rounded">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Job Title</label>
                                        <input type="text" class="form-control" name="resume_data[experience][0][job_title]">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Company</label>
                                        <input type="text" class="form-control" name="resume_data[experience][0][company]">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeExperience(this)">Remove</button>
                            </div>
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-outline-primary" onclick="addExperience()">+ Add Experience</button>
                    </div>
                </div>

                <!-- Education Section -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Education</h5>
                    </div>
                    <div class="card-body">
                        <div id="educationContainer">
                            @php $education = is_array($resume_data['education'] ?? null) ? $resume_data['education'] : []; @endphp
                            @forelse($education as $i => $edu)
                            <div class="education-item mb-3 p-3 border rounded">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Degree</label>
                                        <input type="text" class="form-control" name="resume_data[education][{{ $i }}][degree]"
                                            value="{{ $edu['degree'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Field of Study</label>
                                        <input type="text" class="form-control" name="resume_data[education][{{ $i }}][field]"
                                            value="{{ $edu['field'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Institution</label>
                                        <input type="text" class="form-control" name="resume_data[education][{{ $i }}][institution]"
                                            value="{{ $edu['institution'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Graduation Date</label>
                                        <input type="date" class="form-control" name="resume_data[education][{{ $i }}][graduation_date]"
                                            value="{{ isset($edu['graduation_date']) ? ($edu['graduation_date'] instanceof \Carbon\Carbon ? $edu['graduation_date']->format('Y-m-d') : str_replace(' ', 'T', substr($edu['graduation_date'], 0, 10))) : '' }}">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeEducation(this)">Remove</button>
                            </div>
                            @empty
                            <div class="education-item mb-3 p-3 border rounded">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Degree</label>
                                        <input type="text" class="form-control" name="resume_data[education][0][degree]">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Institution</label>
                                        <input type="text" class="form-control" name="resume_data[education][0][institution]">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeEducation(this)">Remove</button>
                            </div>
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-outline-primary" onclick="addEducation()">+ Add Education</button>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mb-4">
                    <a href="{{ route('placement.jobs.index') }}" class="btn btn-outline-secondary me-2">Back to Jobs</a>
                    <button type="button" class="btn btn-secondary me-2" onclick="previewResume()">Preview</button>
                    <button type="button" class="btn btn-info me-2" onclick="updatePreviewLive()">Update Preview</button>
                    <button type="submit" class="btn btn-success">Download as PDF</button>
                </div>
            </form>
        </div>

        <!-- Preview Section -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 20px;">
                <div id="previewContainer" class="border rounded p-3" style="background: #f8f9fa; max-height: 100vh; overflow-y: auto;">
                    <p class="text-muted text-center">Click "Preview" to see your resume</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .resume-edit-form .form-label {
        font-weight: 500;
        color: #333;
    }

    .skill-row {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .skill-row input {
        flex: 1;
    }

    .skill-row .btn {
        flex-shrink: 0;
    }

    #previewContainer {
        border: 1px solid #ddd;
        background: white;
    }

    .resume-preview {
        font-family: Arial, sans-serif;
        font-size: 12px;
        line-height: 1.4;
        color: #333;
    }
</style>

<script>
    let skillCount = {{ count(is_array($resume_data['skills'] ?? null) ? $resume_data['skills'] : []) }};
    let experienceCount = {{ count(is_array($resume_data['experience'] ?? null) ? $resume_data['experience'] : []) }};
    let educationCount = {{ count(is_array($resume_data['education'] ?? null) ? $resume_data['education'] : []) }};

    function addSkill() {
        const container = document.getElementById('skillsContainer');
        const html = `
            <div class="skill-row mb-2">
                <input type="text" class="form-control" name="resume_data[skills][]" placeholder="Enter skill">
                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeSkill(this)">Remove</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        skillCount++;
    }

    function removeSkill(button) {
        button.parentElement.remove();
        skillCount--;
    }

    function addExperience() {
        const container = document.getElementById('experienceContainer');
        const html = `
            <div class="experience-item mb-4 p-3 border rounded">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Job Title</label>
                        <input type="text" class="form-control" name="resume_data[experience][${experienceCount}][job_title]">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Company</label>
                        <input type="text" class="form-control" name="resume_data[experience][${experienceCount}][company]">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" name="resume_data[experience][${experienceCount}][start_date]">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" name="resume_data[experience][${experienceCount}][end_date]">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-check">
                        <input type="checkbox" class="form-check-input" name="resume_data[experience][${experienceCount}][currently_working]" value="1">
                        Currently working here
                    </label>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description / Achievements</label>
                    <textarea class="form-control" name="resume_data[experience][${experienceCount}][description]" rows="3"></textarea>
                </div>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeExperience(this)">Remove This Experience</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        experienceCount++;
    }

    function removeExperience(button) {
        button.closest('.experience-item').remove();
        experienceCount--;
    }

    function addEducation() {
        const container = document.getElementById('educationContainer');
        const html = `
            <div class="education-item mb-3 p-3 border rounded">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Degree</label>
                        <input type="text" class="form-control" name="resume_data[education][${educationCount}][degree]">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Field of Study</label>
                        <input type="text" class="form-control" name="resume_data[education][${educationCount}][field]">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Institution</label>
                        <input type="text" class="form-control" name="resume_data[education][${educationCount}][institution]">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Graduation Date</label>
                        <input type="date" class="form-control" name="resume_data[education][${educationCount}][graduation_date]">
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeEducation(this)">Remove</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        educationCount++;
    }

    function removeEducation(button) {
        button.closest('.education-item').remove();
        educationCount--;
    }

    function previewResume() {
        const formData = new FormData(document.getElementById('resumeEditForm'));
        
        // Extract data properly
        const name = formData.get('resume_data[name]') || 'Your Name';
        const title = formData.get('resume_data[title]') || '';
        const email = formData.get('resume_data[email]') || '';
        const phone = formData.get('resume_data[phone]') || '';
        const location = formData.get('resume_data[location]') || '';
        const summary = formData.get('resume_data[summary]') || '';
        
        // Get arrays
        const skills = formData.getAll('resume_data[skills][]').filter(s => s.trim());
        
        // Extract experience items
        const expElements = document.querySelectorAll('.experience-item');
        const experience = [];
        expElements.forEach((el) => {
            experience.push({
                job_title: el.querySelector('[name*="[job_title]"]')?.value || '',
                company: el.querySelector('[name*="[company]"]')?.value || '',
                location: el.querySelector('[name*="[location]"]')?.value || '',
                start_date: el.querySelector('[name*="[start_date]"]')?.value || '',
                end_date: el.querySelector('[name*="[end_date]"]')?.value || '',
                currently_working: el.querySelector('[name*="[currently_working]"]')?.checked ? 1 : 0,
                description: el.querySelector('[name*="[description]"]')?.value || ''
            });
        });
        
        // Extract education items
        const eduElements = document.querySelectorAll('.education-item');
        const education = [];
        eduElements.forEach((el) => {
            education.push({
                degree: el.querySelector('[name*="[degree]"]')?.value || '',
                field: el.querySelector('[name*="[field]"]')?.value || '',
                institution: el.querySelector('[name*="[institution]"]')?.value || '',
                graduation_date: el.querySelector('[name*="[graduation_date]"]')?.value || ''
            });
        });

        // Build preview HTML
        let preview = `
            <div class="resume-preview-box" style="font-family: Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.5; padding: 15px; background: white;">
                <div style="border-bottom: 2px solid #333; padding-bottom: 12px; margin-bottom: 15px;">
                    <h3 style="margin: 0 0 3px 0; font-size: 16px; color: #000;">${name}</h3>
                    <p style="margin: 0 0 5px 0; font-size: 12px; color: #555;">${title}</p>
                    <div style="font-size: 10px; color: #777;">
                        ${email ? email : ''} ${phone ? ' | ' + phone : ''} ${location ? ' | ' + location : ''}
                    </div>
                </div>
        `;

        // Summary
        if (summary) {
            preview += `
                <div style="margin-bottom: 12px;">
                    <div style="font-weight: bold; font-size: 11px; color: #000; margin-bottom: 6px; padding: 4px 0; border-bottom: 1px solid #333; text-transform: uppercase;">Summary</div>
                    <p style="margin: 0; font-size: 11px; color: #555; line-height: 1.4;">${summary}</p>
                </div>
            `;
        }

        // Experience
        if (experience.length > 0 && experience[0].job_title) {
            preview += `
                <div style="margin-bottom: 12px;">
                    <div style="font-weight: bold; font-size: 11px; color: #000; margin-bottom: 6px; padding: 4px 0; border-bottom: 1px solid #333; text-transform: uppercase;">Experience</div>
            `;
            experience.forEach(exp => {
                if (exp.job_title) {
                    let dates = '';
                    if (exp.start_date) {
                        dates = new Date(exp.start_date).toLocaleDateString('en-US', {month: 'short', year: 'numeric'}) + ' - ';
                        if (exp.currently_working) {
                            dates += 'Present';
                        } else if (exp.end_date) {
                            dates += new Date(exp.end_date).toLocaleDateString('en-US', {month: 'short', year: 'numeric'});
                        }
                    }
                    preview += `
                        <div style="margin-bottom: 8px;">
                            <div style="display: flex; justify-content: space-between;">
                                <span style="font-weight: bold; font-size: 11px;">${exp.job_title}</span>
                                <span style="font-size: 10px; color: #777;">${dates}</span>
                            </div>
                            <div style="font-size: 11px; color: #666;">${exp.company}${exp.location ? ' • ' + exp.location : ''}</div>
                            ${exp.description ? '<div style="font-size: 10px; color: #555; margin-top: 2px;">' + exp.description + '</div>' : ''}
                        </div>
                    `;
                }
            });
            preview += '</div>';
        }

        // Education
        if (education.length > 0 && education[0].degree) {
            preview += `
                <div style="margin-bottom: 12px;">
                    <div style="font-weight: bold; font-size: 11px; color: #000; margin-bottom: 6px; padding: 4px 0; border-bottom: 1px solid #333; text-transform: uppercase;">Education</div>
            `;
            education.forEach(edu => {
                if (edu.degree) {
                    preview += `
                        <div style="margin-bottom: 6px;">
                            <div style="display: flex; justify-content: space-between;">
                                <span style="font-weight: bold; font-size: 11px;">${edu.degree}${edu.field ? ' in ' + edu.field : ''}</span>
                                ${edu.graduation_date ? '<span style="font-size: 10px; color: #777;">' + new Date(edu.graduation_date).getFullYear() + '</span>' : ''}
                            </div>
                            <div style="font-size: 11px; color: #666;">${edu.institution}</div>
                        </div>
                    `;
                }
            });
            preview += '</div>';
        }

        // Skills
        if (skills.length > 0) {
            preview += `
                <div style="margin-bottom: 12px;">
                    <div style="font-weight: bold; font-size: 11px; color: #000; margin-bottom: 6px; padding: 4px 0; border-bottom: 1px solid #333; text-transform: uppercase;">Skills</div>
                    <div style="font-size: 11px; color: #555; line-height: 1.6;">
                        ${skills.map(s => '• ' + s).join('<br>')}
                    </div>
                </div>
            `;
        }

        preview += '</div>';
        document.getElementById('previewContainer').innerHTML = preview;
    }

    function updatePreviewLive() {
        previewResume();
    }

    // Submit form to save and download PDF
    document.getElementById('resumeEditForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        // Convert form data to proper structure
        const resumeData = {
            name: formData.get('resume_data[name]'),
            title: formData.get('resume_data[title]'),
            email: formData.get('resume_data[email]'),
            phone: formData.get('resume_data[phone]'),
            location: formData.get('resume_data[location]'),
            summary: formData.get('resume_data[summary]'),
            job_id: formData.get('job_id'),
            template: formData.get('template'),
            skills: formData.getAll('resume_data[skills][]'),
            experience: [],
            education: []
        };

        // Collect experience
        const expElements = document.querySelectorAll('.experience-item');
        expElements.forEach((el, idx) => {
            resumeData.experience.push({
                job_title: el.querySelector('[name*="[job_title]"]')?.value || '',
                company: el.querySelector('[name*="[company]"]')?.value || '',
                location: el.querySelector('[name*="[location]"]')?.value || '',
                start_date: el.querySelector('[name*="[start_date]"]')?.value || '',
                end_date: el.querySelector('[name*="[end_date]"]')?.value || '',
                currently_working: el.querySelector('[name*="[currently_working]"]')?.checked ? 1 : 0,
                description: el.querySelector('[name*="[description]"]')?.value || ''
            });
        });

        // Collect education
        const eduElements = document.querySelectorAll('.education-item');
        eduElements.forEach((el, idx) => {
            resumeData.education.push({
                degree: el.querySelector('[name*="[degree]"]')?.value || '',
                field: el.querySelector('[name*="[field]"]')?.value || '',
                institution: el.querySelector('[name*="[institution]"]')?.value || '',
                graduation_date: el.querySelector('[name*="[graduation_date]"]')?.value || ''
            });
        });

        // Submit via AJAX
        fetch('{{ route("placement.resume.save-download") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(resumeData)
            })
            .then(response => response.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'Tailored-Resume.pdf';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error downloading resume. Please try again.');
            });
    });
</script>
@endsection