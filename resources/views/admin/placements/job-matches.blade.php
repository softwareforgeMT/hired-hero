@extends('admin.layouts.master')
@section('title') Job Matches @endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{ route('admin.placements.index') }}">Placement Profiles</a> @endslot
@slot('li_2') <a href="{{ route('admin.placements.show', $placement->id) }}">{{ $placement->user->name }}</a> @endslot
@slot('title') Job Matches @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Job Matches for {{ $placement->user->name }}</h5>
            </div>
            <div class="card-body">
                @if($jobMatches->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Job Title</th>
                                    <th>Company</th>
                                    <th>Location</th>
                                    <th>Match Score</th>
                                    <th>Salary</th>
                                    <th>Posted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jobMatches as $match)
                                <tr>
                                    <td>
                                        <strong>{{ $match->job_title ?? 'N/A' }}</strong>
                                    </td>
                                    <td>{{ $match->company_name ?? 'N/A' }}</td>
                                    <td>{{ $match->location ?? 'N/A' }}</td>
                                    <td>
                                        @if($match->match_score)
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar 
                                                    @if($match->match_score >= 80) bg-success 
                                                    @elseif($match->match_score >= 60) bg-warning 
                                                    @else bg-danger @endif" 
                                                    role="progressbar" style="width: {{ $match->match_score }}%">
                                                    {{ $match->match_score }}%
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($match->salary_min && $match->salary_max)
                                            ${{ number_format($match->salary_min) }} - ${{ number_format($match->salary_max) }}
                                        @elseif($match->salary_min)
                                            From ${{ number_format($match->salary_min) }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $match->created_at ? $match->created_at->format('Y-m-d') : 'N/A' }}</td>
                                    <td>
                                        @if($match->job_url)
                                            <a href="{{ $match->job_url }}" class="btn btn-sm btn-primary" target="_blank">
                                                <i class="ri-external-link-line"></i> View Job
                                            </a>
                                        @else
                                            <span class="text-muted">No URL</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($jobMatches->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                Showing {{ $jobMatches->firstItem() }} to {{ $jobMatches->lastItem() }} of {{ $jobMatches->total() }} results
                            </div>
                            <nav>
                                {{ $jobMatches->links('pagination::bootstrap-4') }}
                            </nav>
                        </div>
                    @endif

                @else
                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>
                        <strong>No Job Matches Found</strong>
                        <p class="mb-0">There are no job matches for this user yet.</p>
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
