@extends('admin.layouts.master')
@section('title') Edit Placement Profile @endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{ route('admin.placements.index') }}">Placement Profiles</a> @endslot
@slot('li_2') <a href="{{ route('admin.placements.show', $placement->id) }}">{{ $user->name }}</a> @endslot
@slot('title') Edit Placement Profile @endslot
@endcomponent

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Placement Profile - {{ $user->name }}</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h4 class="alert-heading">Validation Errors</h4>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('message'))
                    <div class="alert alert-{{ session('alert-class') == 'alert-success' ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('admin.placements.update', $placement->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="job_type" class="form-label">Job Type</label>
                                <input type="text" class="form-control @error('job_type') is-invalid @enderror" 
                                       id="job_type" name="job_type" value="{{ old('job_type', $placement->job_type) }}"
                                       placeholder="e.g., Full-time, Part-time">
                                @error('job_type')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="job_level" class="form-label">Job Level</label>
                                <input type="text" class="form-control @error('job_level') is-invalid @enderror" 
                                       id="job_level" name="job_level" value="{{ old('job_level', $placement->job_level) }}"
                                       placeholder="e.g., Junior, Senior, Lead">
                                @error('job_level')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="salary_min" class="form-label">Minimum Salary ($)</label>
                                <input type="number" class="form-control @error('salary_min') is-invalid @enderror" 
                                       id="salary_min" name="salary_min" value="{{ old('salary_min', $placement->salary_min) }}"
                                       placeholder="0" min="0" step="1000">
                                @error('salary_min')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="salary_max" class="form-label">Maximum Salary ($)</label>
                                <input type="number" class="form-control @error('salary_max') is-invalid @enderror" 
                                       id="salary_max" name="salary_max" value="{{ old('salary_max', $placement->salary_max) }}"
                                       placeholder="0" min="0" step="1000">
                                @error('salary_max')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                       id="country" name="country" value="{{ old('country', $placement->country) }}"
                                       placeholder="e.g., United States">
                                @error('country')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city', $placement->city) }}"
                                       placeholder="e.g., New York">
                                @error('city')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="work_permit_status" class="form-label">Work Permit Status</label>
                                <input type="text" class="form-control @error('work_permit_status') is-invalid @enderror" 
                                       id="work_permit_status" name="work_permit_status" value="{{ old('work_permit_status', $placement->work_permit_status) }}"
                                       placeholder="e.g., Citizen, Work Visa, Sponsorship">
                                @error('work_permit_status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="years_experience" class="form-label">Years of Experience</label>
                                <input type="number" class="form-control @error('years_experience') is-invalid @enderror" 
                                       id="years_experience" name="years_experience" value="{{ old('years_experience', $placement->years_experience) }}"
                                       placeholder="0" min="0" max="70">
                                @error('years_experience')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('admin.placements.show', $placement->id) }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
