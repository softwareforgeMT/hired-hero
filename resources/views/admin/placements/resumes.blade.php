@extends('admin.layouts.master')
@section('title') Manage Resumes @endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{ route('admin.placements.index') }}">Placement Profiles</a> @endslot
@slot('li_2') <a href="{{ route('admin.placements.show', $placement->id) }}">{{ $user->name }}</a> @endslot
@slot('title') Manage Resumes @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Resumes for {{ $user->name }}</h5>
            </div>
            <div class="card-body">
                @if (session('message'))
                    <div class="alert alert-{{ session('alert-class') == 'alert-success' ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($placement->has_resume && $placement->resume_path)
                    <div class="alert alert-info">
                        <h5 class="alert-heading">
                            <i class="ri-file-pdf-line me-2"></i>Resume Available
                        </h5>
                        <p class="mb-0">
                            <strong>File:</strong> {{ basename($placement->resume_path) }}<br>
                            <strong>Upload Date:</strong> {{ $placement->updated_at ? $placement->updated_at->format('Y-m-d H:i') : 'N/A' }}
                        </p>
                    </div>

                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.placements.resume.download', $placement->id) }}" class="btn btn-primary">
                                    <i class="ri-download-line me-1"></i> Download Resume
                                </a>
                                <form action="{{ route('admin.placements.resume.delete', $placement->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="file_path" value="{{ $placement->resume_path }}">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this resume?');">
                                        <i class="ri-delete-bin-line me-1"></i> Delete Resume
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    @if($placement->resume_data)
                        <hr class="my-4">
                        <h6 class="mb-3">Extracted Resume Data</h6>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Skills</h6>
                                        @if(is_array($placement->resume_data['skills'] ?? []))
                                            <ul class="mb-0">
                                                @foreach($placement->resume_data['skills'] as $skill)
                                                    <li>{{ $skill }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-muted mb-0">No skills extracted</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Experience</h6>
                                        @if(!empty($placement->resume_data['experience'] ?? []))
                                            <ul class="mb-0">
                                                @foreach($placement->resume_data['experience'] as $exp)
                                                    <li>{{ $exp }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-muted mb-0">No experience data extracted</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                @else
                    <div class="alert alert-warning">
                        <i class="ri-alert-line me-2"></i>
                        <strong>No Resume Uploaded</strong>
                        <p class="mb-0">This user has not uploaded a resume yet.</p>
                    </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('admin.placements.show', $placement->id) }}" class="btn btn-secondary">
                        <i class="ri-arrow-left-line me-1"></i> Back to Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
