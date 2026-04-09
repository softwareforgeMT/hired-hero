@if(session('impersonating') && Auth::check() && Auth::user())
<div class="alert alert-warning alert-dismissible fade show d-flex align-items-center justify-content-between" role="alert">
    <div>
        <i class="ri-shield-warning-line"></i>
        <strong>Impersonation Active!</strong> You are logged in as 
        <strong>{{ Auth::user()->name ?? 'Unknown User' }}</strong>. 
        This session is being managed by admin: 
        <strong>{{ session('impersonating_by_name', 'Admin') }}</strong>
    </div>
    <a href="{{ route('admin.users.stop-impersonate') }}" class="btn btn-sm btn-outline-warning">
        <i class="ri-logout-box-line"></i> Revert to Admin
    </a>
</div>
@endif
