@extends('placement.resume-builder.layout')

@php
use Illuminate\Support\Str;
@endphp

@section('resume-content')
@if(!$resume || !$resume->id)
<div class="alert alert-danger">
    <h4>Resume Not Found</h4>
    <p>Unable to load the resume. Please try again or go back to Step 6.</p>
    <a href="{{ route('placement.wizard.step', ['step' => 6]) }}" class="btn btn-primary">Back to Step 6</a>
</div>
@else
<div class="resume-view-container">
    <div class="resume-header">
        <div class="header-content">
            <div>
                <h2 class="resume-title">{{ $resume->title ?? 'Untitled Resume' }}</h2>
                <p class="resume-meta">
                    {{ $resume->template_name ?? 'Professional' }} Template
                    @if($resume->created_at)
                    • Created {{ $resume->created_at->format('M d, Y') }}
                    @endif
                </p>
            </div>
            <div class="header-actions">
                <a href="{{ route('resume-builder.download', ['resume' => $resume->id]) }}" class="btn btn-primary" target="_blank">
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
            <iframe src="{{ route('resume-builder.preview', ['resume' => $resume->id]) }}"
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
                        <li><strong>Full Name:</strong> {{ $resume->data['personal_info']['full_name'] ?? $resume->data['full_name'] ?? 'N/A' }}</li>
                        <li><strong>Professional Title:</strong> {{ $resume->data['personal_info']['professional_title'] ?? 'N/A' }}</li>
                        <li><strong>Email:</strong> {{ $resume->data['personal_info']['email'] ?? $resume->data['email'] ?? 'N/A' }}</li>
                        @if($resume->data['personal_info']['phone'] ?? $resume->data['phone'] ?? null)
                        <li><strong>Phone:</strong> {{ $resume->data['personal_info']['phone'] ?? $resume->data['phone'] }}</li>
                        @endif
                        @if($resume->data['personal_info']['location'] ?? $resume->data['location'] ?? null)
                        <li><strong>Location:</strong> {{ $resume->data['personal_info']['location'] ?? $resume->data['location'] }}</li>
                        @endif
                        @if($resume->data['personal_info']['summary'] ?? null)
                        <li><strong>Professional Summary:</strong> {{ Str::limit($resume->data['personal_info']['summary'], 150) }}</li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="col-12">
                <div class="info-card">
                    <h4>Skills ({{ count($resume->data['skills'] ?? []) }})</h4>
                    <div class="skills-preview">
                        @forelse($resume->data['skills'] ?? [] as $skill)
                        <span class="skill-badge">{{ $skill }}</span>
                        @empty
                        <p class="text-muted">No skills added</p>
                        @endforelse
                    </div>
                </div>
            </div>

            @if(!empty($resume->data['work_experience']))
            <div class="col-12">
                <div class="info-card">
                    <h4>Work Experience ({{ count($resume->data['work_experience']) }})</h4>
                    <ul class="info-list">
                        @foreach($resume->data['work_experience'] as $exp)
                        <li>
                            <strong>{{ $exp['job_title'] ?? 'N/A' }}</strong><br>
                            <small class="text-muted">{{ $exp['company'] ?? 'N/A' }}@if($exp['location'] ?? null) • {{ $exp['location'] }}@endif</small>
                            @if($exp['description'] ?? null)
                            <div style="font-size: 0.9rem; margin-top: 5px;">{{ Str::limit($exp['description'], 200) }}</div>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if(!empty($resume->data['education']))
            <div class="col-12">
                <div class="info-card">
                    <h4>Education ({{ count($resume->data['education']) }})</h4>
                    <ul class="info-list">
                        @foreach($resume->data['education'] as $edu)
                        <li>
                            <strong>{{ $edu['degree'] ?? 'N/A' }}@if($edu['field'] ?? null) in {{ $edu['field'] }}@endif</strong><br>
                            <small class="text-muted">{{ $edu['institution'] ?? 'N/A' }}@if($edu['graduation_date'] ?? null) • {{ $edu['graduation_date'] }}@endif</small>
                            @if($edu['description'] ?? null)
                            <div style="font-size: 0.9rem; margin-top: 5px;">{{ Str::limit($edu['description'], 150) }}</div>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if(!empty($resume->data['certifications']))
            <div class="col-12">
                <div class="info-card">
                    <h4>Certifications ({{ count($resume->data['certifications']) }})</h4>
                    <ul class="info-list">
                        @foreach($resume->data['certifications'] as $cert)
                        <li>
                            <strong>{{ $cert['name'] ?? 'N/A' }}</strong>
                            @if($cert['issuer'] ?? null)
                            <br><small class="text-muted">{{ $cert['issuer'] }}@if($cert['issue_date'] ?? null) • {{ $cert['issue_date'] }}@endif</small>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if(!empty($resume->data['languages']))
            <div class="col-12">
                <div class="info-card">
                    <h4>Languages ({{ count($resume->data['languages']) }})</h4>
                    <div class="skills-preview">
                        @foreach($resume->data['languages'] as $lang)
                        <span class="skill-badge">{{ $lang }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="actions-section mt-5 pt-4 border-top">
        <div class="row g-2 mb-3">
            <div class="col-auto">
                <a href="{{ route('resume-builder.download', ['resume' => $resume->id]) }}" class="btn btn-primary" download>
                    <i class="ri-download-line"></i>
                    Download as PDF
                </a>
            </div>
            <div class="col-auto">
                <a href="{{ route('resume-builder.edit', ['resume' => $resume->id]) }}" class="btn btn-outline-primary">
                    <i class="ri-edit-line"></i>
                    Edit Resume
                </a>
            </div>
            <div class="col-auto">
                <form action="{{ route('resume-builder.destroy', ['resume' => $resume->id]) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
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
                    <a href="{{ route('placement.wizard.step', ['step' => 6]) }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line"></i>
                        Back to Step 6
                    </a>
                    <form action="{{ route('placement.wizard.submit', ['step' => 6]) }}" method="POST" enctype="multipart/form-data" style="display: inline;" id="builtResumeForm">
                        @csrf
                        <input type="hidden" name="resume_option" value="existing">
                        <input type="hidden" name="email" id="resumeEmail" value="{{ auth()->user()->email }}">
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
                const downloadUrl = '{{ route('resume-builder.download', ['resume' => $resume->id]) }}';
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
                const file = new File([blob], `resume-{{ $resume->id }}-${timestamp}.pdf`, { type: 'application/pdf' });

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
@endif

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
@endsection