@extends('admin.layouts.master')
@section('title') User Subscriptions @endsection
@section('css')
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<style>
    .subscription-status-active {
        color: #28a745;
    }
    .subscription-status-expired {
        color: #dc3545;
    }
</style>
@endsection
@section('content')
@component('components.breadcrumb')
    @slot('li_1') <a href="{{ route('admin.subscriptions.index') }}">User Subscriptions</a> @endslot
    @slot('title')User Subscriptions @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">All User Subscriptions</h5>
                    <span class="badge bg-primary" id="total-subscriptions">0</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="subscriptionTable" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Plan Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Expires At</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Subscription Details Modal -->
<div class="modal fade" id="subscriptionDetailsModal" tabindex="-1" aria-labelledby="subscriptionDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subscriptionDetailsLabel">Subscription Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailsContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Subscription Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Cancel Subscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this subscription? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep It</button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">Yes, Cancel Subscription</button>
            </div>
        </div>
    </div>
</div>

@section('script')
    <!-- DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            const detailsModal = new bootstrap.Modal(document.getElementById('subscriptionDetailsModal'));
            const cancelModal = new bootstrap.Modal(document.getElementById('cancelModal'));
            let subscriptionToCancel = null;

            // Initialize DataTable
            const table = $('#subscriptionTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.subscriptions.datatables') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'user_id', name: 'user_id' },
                    { data: 'email', name: 'email' },
                    { data: 'plan_type', name: 'plan_type' },
                    { data: 'amount', name: 'amount' },
                    { data: 'status', name: 'status' },
                    { data: 'expires_at', name: 'expires_at' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                pageLength: 25,
                drawCallback: function() {
                    updateTotalCount(table);
                }
            });

            // View subscription details
            window.viewSubscription = function(id) {
                const content = document.getElementById('detailsContent');
                content.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
                detailsModal.show();

                $.ajax({
                    url: '/admin/subscriptions/' + id,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const data = response.data;
                            content.innerHTML = `
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>User:</strong> ${data.user_name}</p>
                                        <p><strong>Email:</strong> ${data.user_email}</p>
                                        <p><strong>Plan Type:</strong> ${data.plan_type}</p>
                                        <p><strong>Amount:</strong> ${data.amount}</p>
                                        <p><strong>Original Amount:</strong> ${data.original_amount}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Discount:</strong> ${data.discount_amount}</p>
                                        <p><strong>Promo Code:</strong> ${data.promo_code}</p>
                                        <p><strong>Status:</strong> <span class="badge bg-info">${data.status}</span></p>
                                        <p><strong>Expires At:</strong> ${data.expires_at}</p>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                        <p><strong>Started At:</strong> ${data.started_at}</p>
                                        <p><strong>Canceled At:</strong> ${data.canceled_at}</p>
                                        <p><strong>Stripe Customer ID:</strong> <small>${data.stripe_customer_id}</small></p>
                                        <p><strong>Stripe Subscription ID:</strong> <small>${data.stripe_subscription_id}</small></p>
                                    </div>
                                </div>
                            `;
                        } else {
                            content.innerHTML = '<div class="alert alert-danger">Failed to load subscription details.</div>';
                        }
                    },
                    error: function() {
                        content.innerHTML = '<div class="alert alert-danger">Error loading subscription details.</div>';
                    }
                });
            };

            // Cancel subscription
            window.cancelSubscription = function(id) {
                subscriptionToCancel = id;
                cancelModal.show();
            };

            // Confirm cancel
            document.getElementById('confirmCancelBtn').addEventListener('click', function() {
                if (!subscriptionToCancel) return;

                $.ajax({
                    url: '/admin/subscriptions/' + subscriptionToCancel + '/cancel',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            showAlert('success', response.message);
                            table.ajax.reload();
                            cancelModal.hide();
                        } else {
                            showAlert('danger', response.message);
                        }
                    },
                    error: function(xhr) {
                        showAlert('danger', 'Failed to cancel subscription');
                    }
                });
            });

            function updateTotalCount(table) {
                const totalRecords = table.page.info().recordsTotal;
                $('#total-subscriptions').text(totalRecords);
            }

            function showAlert(type, message) {
                const alertId = 'alert-' + Date.now();
                const alertHtml = `
                    <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                
                $('div.card-body').first().before(alertHtml);
                
                setTimeout(() => {
                    $(`#${alertId}`).fadeOut(function() {
                        $(this).remove();
                    });
                }, 4000);
            }
        });
    </script>
@endsection
