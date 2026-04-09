@extends('admin.layouts.master')
@section('title') Placement Profile Details @endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{ route('admin.placements.index') }}">Placement Profiles</a> @endslot
@slot('title') Profile Details @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <!-- User Information -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">User Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Name:</strong> {{ $user->name }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Registered:</strong> {{ $user->created_at->format('Y-m-d H:i') }}</p>
                        <p><strong>Status:</strong> 
                            @if($user->status == 1)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Placement Profile Details -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Placement Profile Details</h5>
                <a href="{{ route('admin.placements.edit', $placement->id) }}" class="btn btn-sm btn-warning">Edit Profile</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Job Type:</strong> {{ $placement->job_type ?? 'N/A' }}</p>
                        <p><strong>Job Level:</strong> {{ $placement->job_level ?? 'N/A' }}</p>
                        <p><strong>Salary Range:</strong> ${{ $placement->salary_min ?? 'N/A' }} - ${{ $placement->salary_max ?? 'N/A' }}</p>
                        <p><strong>Years Experience:</strong> {{ $placement->years_experience ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Country:</strong> {{ $placement->country ?? 'N/A' }}</p>
                        <p><strong>City:</strong> {{ $placement->city ?? 'N/A' }}</p>
                        <p><strong>Work Permit Status:</strong> {{ $placement->work_permit_status ?? 'N/A' }}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Current Step:</strong> {{ $placement->current_step }} / 7</p>
                        <p><strong>Profile Status:</strong> 
                            @if($placement->is_completed)
                                <span class="badge bg-success">Completed</span>
                            @else
                                <span class="badge bg-warning">In Progress</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Has Resume:</strong> 
                            @if($placement->has_resume)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-danger">No</span>
                            @endif
                        </p>
                        <p><strong>Profile Created:</strong> {{ $placement->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscriptions -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Resume Subscriptions</h5>
            </div>
            <div class="card-body">
                @if($subscriptions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Plan Type</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Started</th>
                                    <th>Expires</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscriptions as $sub)
                                <tr>
                                    <td><strong>{{ ucfirst($sub->plan_type) }}</strong></td>
                                    <td>
                                        @if($sub->isActive())
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">{{ ucfirst($sub->status) }}</span>
                                        @endif
                                    </td>
                                    <td>${{ $sub->amount }}</td>
                                    <td>{{ $sub->started_at ? $sub->started_at->format('Y-m-d') : 'N/A' }}</td>
                                    <td>{{ $sub->expires_at ? $sub->expires_at->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No subscriptions found for this user.</p>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.placements.resumes', $placement->id) }}" class="btn btn-primary">
                        <i class="ri-file-pdf-line me-1"></i> Manage Resumes
                    </a>
                    <a href="{{ route('admin.placements.job-matches', $placement->id) }}" class="btn btn-info">
                        <i class="ri-briefcase-line me-1"></i> View Job Matches
                    </a>
                    <a href="{{ route('admin.placements.index') }}" class="btn btn-secondary">
                        <i class="ri-arrow-left-line me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
