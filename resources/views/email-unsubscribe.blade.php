@extends('front.layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center p-5">
                    @if ($success)
                        <div class="mb-3">
                            <i class="ri-check-line" style="font-size: 48px; color: #28a745;"></i>
                        </div>
                        <h3 class="card-title mb-3">Unsubscribe Successful</h3>
                        <p class="text-muted mb-3">{{ $message }}</p>
                        @if (isset($email))
                            <p class="text-muted small">
                                <strong>Email:</strong> {{ $email }}
                            </p>
                        @endif
                        <p class="text-muted small mt-4">
                            You will no longer receive promotional emails from us. If you change your mind, you can
                            update your preferences in your account settings.
                        </p>
                    @else
                        <div class="mb-3">
                            <i class="ri-error-warning-line" style="font-size: 48px; color: #dc3545;"></i>
                        </div>
                        <h3 class="card-title mb-3">Unsubscribe Error</h3>
                        <p class="text-muted mb-3">{{ $message }}</p>
                        <p class="text-muted small mt-4">
                            If you continue to have issues, please contact our support team.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
