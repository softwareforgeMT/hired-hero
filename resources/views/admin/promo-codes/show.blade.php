@extends('admin.layouts.master')
@section('title') Promo Code Details @endsection
@section('css')
<style>
    .details-card {
        border-left: 4px solid #667eea;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .promo-code-badge {
        font-family: 'Courier New', monospace;
        font-weight: bold;
        letter-spacing: 1px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px 16px;
        border-radius: 4px;
        display: inline-block;
        font-size: 18px;
    }

    .discount-badge {
        background: #ffc107;
        color: #000;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
        display: inline-block;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 12px;
    }

    .status-active {
        background: #d4edda;
        color: #155724;
    }

    .status-inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .recipients-table {
        margin-top: 30px;
    }

    .recipients-section {
        margin-top: 40px;
    }

    .recipient-item {
        display: flex;
        align-items: center;
        padding: 12px;
        border-bottom: 1px solid #eee;
        background: #f8f9fa;
        margin-bottom: 8px;
        border-radius: 4px;
    }

    .recipient-item:last-child {
        border-bottom: none;
    }

    .recipient-item-content {
        flex: 1;
    }

    .recipient-email {
        font-size: 14px;
        color: #333;
        margin: 0;
    }

    .recipient-type {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 600;
        margin-left: 10px;
    }

    .recipient-type-user {
        background: #cfe2ff;
        color: #084298;
    }

    .recipient-type-email {
        background: #d1e7dd;
        color: #0f5132;
    }

    .no-recipients {
        text-align: center;
        padding: 40px;
        color: #999;
        background: #f8f9fa;
        border-radius: 4px;
        margin-top: 20px;
    }

    .info-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .info-row {
            grid-template-columns: 1fr;
        }
    }

    .info-item {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 4px;
    }

    .info-label {
        font-size: 12px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        margin-bottom: 8px;
        display: block;
    }

    .info-value {
        font-size: 16px;
        color: #333;
        font-weight: 500;
    }
</style>
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{ route('admin.dashboard') }}">Dashboard</a> @endslot
@slot('li_2') <a href="{{ route('admin.promo-codes.index') }}">Promo Codes</a> @endslot
@slot('title') Promo Code Details @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <!-- Main Details Card -->
        <div class="card details-card">
            <div class="card-header" style="background: #f8f9fa; border-bottom: 1px solid #ddd;">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 style="margin: 0; margin-bottom: 10px;">
                            <span class="promo-code-badge">{{ $promoCode->code }}</span>
                        </h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('admin.promo-codes.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bx bx-arrow-back"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Promo Code Information -->
                <div class="info-row">
                    <div class="info-item">
                        <span class="info-label">Discount Percentage</span>
                        <span class="discount-badge">{{ $promoCode->discount_percentage }}%</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status</span>
                        <span class="status-badge {{ $promoCode->active ? 'status-active' : 'status-inactive' }}">
                            {{ $promoCode->active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-item">
                        <span class="info-label">Max Usage</span>
                        <span class="info-value">{{ $promoCode->max_usage }} usage(s)</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Used Count</span>
                        <span class="info-value">{{ $promoCode->used_count }} / {{ $promoCode->max_usage }}</span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-item">
                        <span class="info-label">Expires At</span>
                        <span class="info-value">
                            @if($promoCode->expires_at)
                                {{ $promoCode->expires_at->format('M d, Y') }}
                                @if($promoCode->expires_at->isPast())
                                    <span class="badge bg-danger ms-2">Expired</span>
                                @else
                                    <span class="badge bg-success ms-2">{{ $promoCode->getDaysRemaining() }} days remaining</span>
                                @endif
                            @else
                                <span class="text-muted">No expiration date</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Type</span>
                        <span class="info-value">
                            @if($promoCode->is_bulk)
                                <span class="badge bg-info">Bulk</span>
                            @else
                                <span class="badge bg-secondary">Single User</span>
                            @endif
                        </span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-item" style="grid-column: 1 / -1;">
                        <span class="info-label">Description</span>
                        <span class="info-value">{{ $promoCode->description ?? 'No description' }}</span>
                    </div>
                </div>

                <div class="info-row" style="margin-bottom: 0;">
                    <div class="info-item">
                        <span class="info-label">Created At</span>
                        <span class="info-value">{{ $promoCode->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Last Updated</span>
                        <span class="info-value">{{ $promoCode->updated_at->format('M d, Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recipients Section -->
        <div class="recipients-section">
            <h4 class="mb-4">
                <i class="bx bxs-user-circle"></i> Recipients
                <small class="text-muted">({{ $assignedUsers->count() + count($customEmails) }} total)</small>
            </h4>

            @if($assignedUsers->count() > 0 || count($customEmails) > 0)
                <div class="card">
                    <div class="card-body">
                        <!-- Platform Users -->
                        @if($assignedUsers->count() > 0)
                            <div class="mb-4">
                                <h5 class="mb-3">
                                    <i class="bx bx-user"></i>
                                    Platform Users
                                    <span class="badge bg-primary">{{ $assignedUsers->count() }}</span>
                                </h5>
                                @foreach($assignedUsers as $user)
                                    <div class="recipient-item">
                                        <div class="recipient-item-content">
                                            <p class="recipient-email">
                                                <i class="bx bxs-envelope"></i> {{ $user->email }}
                                                <span class="recipient-type recipient-type-user">PLATFORM USER</span>
                                            </p>
                                            <small class="text-muted d-block" style="margin-top: 5px;">
                                                ID: {{ $user->id }} | Name: {{ $user->name ?? 'N/A' }}
                                                @if($user->pivot->used)
                                                    | <span class="badge bg-success">Used on {{ $user->pivot->used_at->format('M d, Y') }}</span>
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Custom Emails -->
                        @if(count($customEmails) > 0)
                            <div>
                                <h5 class="mb-3">
                                    <i class="bx bx-mail-send"></i>
                                    Custom Emails
                                    <span class="badge bg-success">{{ count($customEmails) }}</span>
                                </h5>
                                @foreach($customEmails as $email)
                                    <div class="recipient-item">
                                        <div class="recipient-item-content">
                                            <p class="recipient-email">
                                                <i class="bx bxs-envelope"></i> {{ $email }}
                                                <span class="recipient-type recipient-type-email">CUSTOM EMAIL</span>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="no-recipients">
                    <i class="bx bx-inbox" style="font-size: 48px; color: #ccc;"></i>
                    <p style="margin-top: 15px; font-size: 16px;">No recipients yet</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
