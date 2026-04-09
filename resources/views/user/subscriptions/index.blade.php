@extends('front.layouts.app')
@section('title') My Subscriptions @endsection
@section('css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<style>
    .subscription-card {
        border-left: 4px solid #007bff;
        transition: all 0.3s ease;
    }
    .subscription-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .subscription-card.active {
        border-left-color: #28a745;
    }
    .subscription-card.expired {
        border-left-color: #dc3545;
        opacity: 0.8;
    }
</style>
@endsection
@section('content')

<section class="section page__content">
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-3">
                @include('user.layouts.sidebar')
            </div>
            <div class="col-md-9">
                <!-- Active Subscription Section -->
                @if($activeSubscription)
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card subscription-card active">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="card-title mb-2">
                                            <i class="ri-check-double-line text-success"></i> Active Subscription
                                        </h5>
                                        <p class="mb-2">
                                            <strong>Plan:</strong> 
                                            <span class="badge bg-primary">{{ $activeSubscription->plan->name ?? 'Unknown' }}</span>
                                        </p>
                                        <p class="mb-2">
                                            <strong>Amount Paid:</strong> {{ Helpers::setCurrency($activeSubscription->amount) }}
                                        </p>
                                        <p class="mb-0">
                                            <strong>Expires On:</strong> 
                                            @if($activeSubscription->expires_at)
                                                {{ $activeSubscription->expires_at->format('F d, Y') }}
                                                <br>
                                                <small class="text-muted">{{ $activeSubscription->expires_at->diffInDays(now()) }} days remaining</small>
                                            @else
                                                <span class="badge bg-info">Lifetime Access</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <div class="display-6 text-success">
                                            <i class="ri-shield-check-line"></i>
                                        </div>
                                        <p class="text-muted mt-2">Status: <strong class="text-success">Active</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card border-warning">
                            <div class="card-body text-center py-5">
                                <i class="ri-inbox-line display-5 text-warning mb-3"></i>
                                <h5>No Active Subscription</h5>
                                <p class="text-muted mb-3">You don't currently have an active Resume Builder subscription.</p>
                                <a href="{{ route('front.pricing') }}" class="btn btn-primary">
                                    <i class="ri-add-line align-middle me-1"></i> Get a Subscription
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- All Subscriptions Table -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header end-t-end">
                                <h5 class="card-title mb-0">
                                    <i class="ri-list-check-2 align-middle me-1"></i> All Subscriptions History
                                </h5>
                            </div>
                            <div class="card-body">
                                @include('includes.alerts')
                                <table id="subscriptionTable" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Plan</th>
                                            <th>Amount</th>
                                            <th>Discount</th>
                                            <th>Status</th>
                                            <th>Subscription Expires</th>
                                            <th>Purchased On</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#subscriptionTable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: '{{ route('user.subscriptions.datatables') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'plan_slug', name: 'plan_slug' },
                { data: 'amount', name: 'amount' },
                { data: 'discount_amount', name: 'discount_amount' },
                { data: 'status', name: 'status' },
                { data: 'subscription_expires', name: 'subscription_expires' },
                { data: 'created_at', name: 'created_at' }
            ],
            pageLength: 10,
            order: [[6, 'desc']]
        });
    });
</script>
@endsection
