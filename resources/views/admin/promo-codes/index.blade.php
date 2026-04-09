@extends('admin.layouts.master')
@section('title') Promo Codes @endsection
@section('css')
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<style>
    .promo-code-badge {
        font-family: 'Courier New', monospace;
        font-weight: bold;
        letter-spacing: 1px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 12px;
        border-radius: 4px;
        display: inline-block;
        white-space: nowrap;
    }
    
    .discount-badge {
        background: #ffc107;
        color: #000;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 12px;
        white-space: nowrap;
    }

    /* Table responsiveness */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    #promosTable {
        min-width: 1200px;
    }

    /* Small screens */
    @media (max-width: 768px) {
        #promosTable {
            font-size: 13px;
        }

        #promosTable th, 
        #promosTable td {
            padding: 8px 6px !important;
        }

        .promo-code-badge {
            padding: 5px 8px;
            font-size: 11px;
        }

        .discount-badge {
            padding: 4px 8px;
            font-size: 10px;
        }

        .btn-sm {
            padding: 5px 8px;
            font-size: 12px;
        }
    }
</style>
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{ route('admin.dashboard') }}">Dashboard</a> @endslot
@slot('title') Promo Codes Management @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center" style="margin-top: 20px; margin-bottom: 0;">
                <h5 class="card-title mb-0">Promo Codes</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generatePromoModal">
                        <i class="ri-add-line"></i> Generate New Promo Code
                    </button>
                    <button id="bulkSendBtn" class="btn btn-success" style="display:none;">
                        <i class="ri-mail-send-line"></i> Bulk Send to Users
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="promosTable" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAllPromos" title="Select All">
                                </th>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Discount</th>
                                <th>Max Usage</th>
                                <th>Used Count</th>
                                <th>Status</th>
                                <th>Expires</th>
                                <th>Description</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Generate Promo Code Modal -->
<div class="modal fade" id="generatePromoModal" tabindex="-1" aria-labelledby="generatePromoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generatePromoModalLabel">Generate New Promo Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="generatePromoForm">
                    <div class="mb-3">
                        <label for="discountPercentage" class="form-label">Discount Percentage (%)</label>
                        <input type="number" class="form-control" id="discountPercentage" 
                               min="0" max="100" step="0.01" placeholder="e.g., 10.50" required>
                        <small class="form-text text-muted">Enter discount between 0-100%</small>
                    </div>

                    <div class="mb-3">
                        <label for="maxUsage" class="form-label">Max Usage</label>
                        <input type="number" class="form-control" id="maxUsage" 
                               min="1" placeholder="e.g., 100" required>
                        <small class="form-text text-muted">How many times this code can be used</small>
                    </div>

                    <div class="mb-3">
                        <label for="expiryDate" class="form-label">Expiry Date (Optional)</label>
                        <input type="date" class="form-control" id="expiryDate">
                        <small class="form-text text-muted">Leave empty for no expiry</small>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" rows="2" 
                                  placeholder="e.g., Spring Sale, New User Offer"></textarea>
                    </div>
                </form>

                <!-- Code Generated Result Section -->
                <div id="codeGeneratedSection" style="display: none;">
                    <div class="alert alert-success">
                        <h5>Promo Code Generated Successfully!</h5>
                        <p class="mb-2"><strong>Code:</strong> <span id="generatedCodeDisplay" class="promo-code-badge"></span></p>
                        <p class="mb-2"><strong>Discount:</strong> <span id="generatedDiscountDisplay"></span>%</p>
                        <p class="mb-0"><strong>Max Usage:</strong> <span id="generatedMaxUsageDisplay"></span></p>
                    </div>

                    <!-- Recipient Type Selection -->
                    <div class="mb-4">
                        <label class="form-label"><strong>Send To:</strong></label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="generateRecipientType" id="recipientGenPlatform" value="platform" checked>
                                <label class="form-check-label" for="recipientGenPlatform">
                                    Platform Users
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="generateRecipientType" id="recipientGenCustom" value="custom">
                                <label class="form-check-label" for="recipientGenCustom">
                                    Custom Emails
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="generateRecipientType" id="recipientGenAll" value="all">
                                <label class="form-check-label" for="recipientGenAll">
                                    Both
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Platform Section -->
                    <div id="genPlatformSection" class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="includeUnsubscribeGen" name="include_unsubscribe">
                            <label class="form-check-label" for="includeUnsubscribeGen">
                                Include unsubscribe link in email
                            </label>
                        </div>
                    </div>

                    <!-- Custom Emails Section -->
                    <div id="genCustomSection" style="display: none;" class="mb-4">
                        <label class="form-label"><strong>Enter Email Addresses:</strong></label>
                        <small class="form-text text-muted d-block mb-2">Enter one email per line. These users will receive special onboarding instructions.</small>
                        <textarea id="genCustomEmails" class="form-control" rows="4" placeholder="user1@example.com&#10;user2@example.com&#10;user3@example.com"></textarea>
                        <small class="form-text text-muted mt-2">
                            <strong>Onboarding instructions sent to external users:</strong>
                        </small>
                        <div class="alert alert-light border mt-2" style="background-color: #f9f9f9;">
                            <ol class="mb-0" style="margin-left: 1.5rem;">
                                <li>Create an account on our platform</li>
                                <li>Enter the promo code at checkout</li>
                                <li>Enjoy your <span id="genDiscountPreview">10</span>% discount on all services</li>
                            </ol>
                        </div>
                    </div>

                    <!-- All Section -->
                    <div id="genAllSection" style="display: none;" class="mb-4">
                        <div class="alert alert-warning">
                            <i class="ri-alert-line"></i> <strong>Sending to All:</strong> Promo code will be sent to:
                            <ul class="mb-0" style="margin-top: 0.5rem;">
                                <li>All platform users with promotional emails enabled</li>
                                <li>Any additional custom emails you provide</li>
                            </ul>
                        </div>

                        <label class="form-label"><strong>Custom Emails (Optional):</strong></label>
                        <small class="form-text text-muted d-block mb-2">Enter additional email addresses for non-platform users</small>
                        <textarea id="genCustomEmailsAll" class="form-control" rows="3" placeholder="user1@example.com&#10;user2@example.com"></textarea>
                        
                        <small class="form-text text-muted mt-2">
                            <strong>Onboarding instructions sent to external users:</strong>
                        </small>
                        <div class="alert alert-light border mt-2" style="background-color: #f9f9f9;">
                            <ol class="mb-0" style="margin-left: 1.5rem;">
                                <li>Create an account on our platform</li>
                                <li>Enter the promo code at checkout</li>
                                <li>Enjoy your <span id="genDiscountPreviewAll">10</span>% discount on all services</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="generatePromoBtn" style="display: block;">
                    <i class="ri-magic-line"></i> Generate Code
                </button>
                <button type="button" class="btn btn-success" id="sendGeneratedPromoBtn" style="display: none;">
                    <i class="ri-mail-send-line"></i> Send to Users Now
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Send Modal -->
<div class="modal fade" id="bulkSendPromoModal" tabindex="-1" aria-labelledby="bulkSendPromoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkSendPromoModalLabel">Send Promo Code to Users</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label"><strong>Selected Promo Codes:</strong></label>
                    <p><span id="selectedPromoCount">0</span> code(s) selected</p>
                    <div id="selectedPromosList" style="max-height: 150px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 4px; background: #f8f9fa;">
                    </div>
                </div>

                <!-- Recipient Type Selection -->
                <div class="mb-4">
                    <label class="form-label"><strong>Send To:</strong></label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input recipient-type" type="radio" name="recipientType" id="recipientPlatform" value="platform" checked>
                            <label class="form-check-label" for="recipientPlatform">
                                Platform Users
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input recipient-type" type="radio" name="recipientType" id="recipientCustom" value="custom">
                            <label class="form-check-label" for="recipientCustom">
                                Custom Emails
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input recipient-type" type="radio" name="recipientType" id="recipientAll" value="all">
                            <label class="form-check-label" for="recipientAll">
                                All (Platform + Custom)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Platform Users Section -->
                <div id="platformSection" class="mb-4">
                    <form id="bulkSendForm">
                        <div class="mb-3">
                            <label class="form-label">
                                <input type="checkbox" id="includeUnsubscribeBulk" checked>
                                Include unsubscribe link in email
                            </label>
                            <small class="form-text text-muted">
                                Users will be able to unsubscribe from promotional emails
                            </small>
                        </div>

                        <div class="alert alert-info">
                            <i class="ri-information-line"></i> Promo codes will be sent only to users with promotional emails enabled.
                        </div>
                    </form>
                </div>

                <!-- Custom Emails Section -->
                <div id="customSection" style="display: none;" class="mb-4">
                    <label class="form-label"><strong>Enter Email Addresses:</strong></label>
                    <small class="form-text text-muted d-block mb-2">Enter one email per line. These users will receive special onboarding instructions.</small>
                    <textarea id="customEmails" class="form-control" rows="4" placeholder="user1@example.com&#10;user2@example.com&#10;user3@example.com"></textarea>
                    <small class="form-text text-muted mt-2">
                        <strong>Note:</strong> External users will receive an email with instructions on how to:
                    </small>
                    <div class="alert alert-light border mt-2" style="background-color: #f9f9f9;">
                        <ol class="mb-0" style="margin-left: 1.5rem;">
                            <li><strong>Create an Account:</strong> Sign up on the platform with their email address</li>
                            <li><strong>Subscribe to a Plan:</strong> Choose and purchase a subscription plan</li>
                            <li><strong>Use the Promo Code:</strong> Apply the promo code during checkout to get your discounted price</li>
                        </ol>
                    </div>
                </div>

                <!-- All Section -->
                <div id="allSection" style="display: none;" class="mb-4">
                    <div class="alert alert-warning">
                        <i class="ri-alert-line"></i> <strong>Sending to All:</strong> Promo code will be sent to:
                        <ul class="mb-0" style="margin-top: 0.5rem;">
                            <li>All platform users with promotional emails enabled</li>
                            <li>Custom email addresses (external users will receive onboarding instructions)</li>
                        </ul>
                    </div>

                    <label class="form-label"><strong>Custom Emails (Optional):</strong></label>
                    <small class="form-text text-muted d-block mb-2">Enter additional email addresses for non-platform users</small>
                    <textarea id="customEmailsAll" class="form-control" rows="3" placeholder="user1@example.com&#10;user2@example.com"></textarea>
                    
                    <small class="form-text text-muted mt-2">
                        <strong>Onboarding instructions sent to external users:</strong>
                    </small>
                    <div class="alert alert-light border mt-2" style="background-color: #f9f9f9;">
                        <ol class="mb-0" style="margin-left: 1.5rem;">
                            <li><strong>Create an Account:</strong> Sign up on the platform with their email address</li>
                            <li><strong>Subscribe to a Plan:</strong> Choose and purchase a subscription plan</li>
                            <li><strong>Use the Promo Code:</strong> Apply the promo code during checkout to get your discounted price</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmBulkSendBtn">
                    <i class="ri-mail-send-line"></i> Send to Users
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Delete Promo Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this promo code?</p>
                <p><strong>Code:</strong> <span id="deletePromoCode"></span></p>
                <div class="alert alert-warning">
                    <i class="ri-alert-line"></i> This action cannot be undone. Users will no longer be able to use this code.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            let promosTable;
            let bulkSendModalInstance = new bootstrap.Modal(document.getElementById('bulkSendPromoModal'));
            let deleteConfirmModalInstance = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            let selectedPromoIds = new Set();
            let deletePromoId = null;
            let generatedPromoCodeId = null;
            let generatedIsBulk = false;

            // Initialize DataTable
            promosTable = $('#promosTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: '{{ route('admin.promo-codes.datatables') }}',
                columns: [
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `<input type="checkbox" class="promo-checkbox" value="${data}">`;
                        }
                    },
                    { 
                        data: 'code',
                        render: function(data) {
                            return `<span class="promo-code-badge">${data}</span>`;
                        }
                    },
                    {
                        data: 'is_bulk',
                        render: function(data) {
                            if (data) {
                                return '<span class="badge bg-info">Bulk</span>';
                            } else {
                                return '<span class="badge bg-secondary">Individual</span>';
                            }
                        }
                    },
                    { 
                        data: 'discount_percentage',
                        render: function(data) {
                            return `<span class="discount-badge">${data} OFF</span>`;
                        }
                    },
                    { data: 'max_usage' },
                    { data: 'used_count' },
                    {
                        data: 'active',
                        render: function(data) {
                            return data ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                        }
                    },
                    { 
                        data: 'expires_at',
                        render: function(data) {
                            return data ? data : '<span class="text-muted">No expiry</span>';
                        }
                    },
                    { 
                        data: 'description',
                        render: function(data) {
                            return data ? `<small>${data}</small>` : '<span class="text-muted">—</span>';
                        }
                    },
                    { data: 'created_at' },
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let sendButton = '';
                            if (row.is_bulk) {
                                sendButton = `<button class="btn btn-sm btn-info send-bulk-btn" data-id="${data}" data-code="${row.code}">
                                    <i class="ri-mail-send-line"></i> Send
                                </button>`;
                            }
                            
                            return `
                                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                    <a href="{{ route('admin.promo-codes.show', '') }}/${data}" class="btn btn-sm btn-primary" title="View Details">
                                        <i class="ri-eye-line"></i> View
                                    </a>
                                    ${sendButton}
                                    <button class="btn btn-sm btn-warning toggle-status-btn" data-id="${data}" data-status="${row.active}">
                                        <i class="ri-${row.active ? 'eye-off' : 'eye'}-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="${data}" data-code="${row.code}">
                                        <i class="ri-delete-bin-line"></i> Delete
                                    </button>
                                </div>
                            `;
                        }
                    }
                ]
            });

            // Handle promo checkbox selection
            $(document).on('change', '.promo-checkbox', function() {
                const promoId = $(this).val();
                if ($(this).is(':checked')) {
                    selectedPromoIds.add(promoId);
                } else {
                    selectedPromoIds.delete(promoId);
                }
                updateBulkSendButtonVisibility();
            });

            // Handle select all checkbox
            $('#selectAllPromos').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('#promosTable tbody tr').each(function() {
                    const checkbox = $(this).find('.promo-checkbox');
                    checkbox.prop('checked', isChecked);
                    const promoId = checkbox.val();
                    if (isChecked) {
                        selectedPromoIds.add(promoId);
                    } else {
                        selectedPromoIds.delete(promoId);
                    }
                });
                updateBulkSendButtonVisibility();
            });

            // Update bulk send button visibility
            function updateBulkSendButtonVisibility() {
                if (selectedPromoIds.size > 0) {
                    $('#bulkSendBtn').show();
                } else {
                    $('#bulkSendBtn').hide();
                }
            }

            // Generate new promo code
            $('#generatePromoBtn').click(function() {
                const form = $('#generatePromoForm');
                if (!form[0].checkValidity()) {
                    form[0].reportValidity();
                    return;
                }

                const data = {
                    _token: '{{ csrf_token() }}',
                    discount_percentage: parseInt($('#discountPercentage').val()),
                    max_usage: parseInt($('#maxUsage').val()),
                    expires_at: $('#expiryDate').val() || null,
                    description: $('#description').val() || null,
                    is_bulk: 1
                };

                $.ajax({
                    url: '{{ route('admin.promo-codes.generate-for-user') }}',
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            generatedPromoCodeId = response.promo_code_id;
                            generatedIsBulk = true;

                            // Show generated code info
                            $('#generatedCodeDisplay').text(response.code);
                            $('#generatedDiscountDisplay').text(response.discount_percentage);
                            $('#generatedMaxUsageDisplay').text(response.max_usage);
                            
                            // Update discount previews
                            $('#genDiscountPreview').text(response.discount_percentage);
                            $('#genDiscountPreviewAll').text(response.discount_percentage);
                            
                            // Hide form, show generated result
                            $('#generatePromoForm').hide();
                            $('#codeGeneratedSection').show();
                            
                            // Initialize recipient type to 'platform'
                            $('#recipientGenPlatform').prop('checked', true);
                            $('#genPlatformSection').show();
                            $('#genCustomSection').hide();
                            $('#genAllSection').hide();
                            $('#genCustomEmails').val('');
                            $('#genCustomEmailsAll').val('');
                            $('#includeUnsubscribeGen').prop('checked', false);
                            
                            // Show send button
                            $('#generatePromoBtn').hide();
                            $('#sendGeneratedPromoBtn').show();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Error generating promo code');
                        console.log(xhr);
                    }
                });
            });

            // Handle recipient type selection in generate modal
            $('input[name="generateRecipientType"]').on('change', function() {
                const recipientType = $(this).val();
                
                // Hide all sections
                $('#genPlatformSection').hide();
                $('#genCustomSection').hide();
                $('#genAllSection').hide();
                
                // Show selected section
                if (recipientType === 'platform') {
                    $('#genPlatformSection').show();
                } else if (recipientType === 'custom') {
                    $('#genCustomSection').show();
                } else if (recipientType === 'all') {
                    $('#genAllSection').show();
                }
            });

            // Send generated bulk promo code to users
            $('#sendGeneratedPromoBtn').click(function() {
                if (!generatedPromoCodeId || !generatedIsBulk) return;

                const recipientType = $('input[name="generateRecipientType"]:checked').val();
                const includeUnsubscribe = $('#includeUnsubscribeGen').is(':checked');
                
                let customEmails = [];
                
                // Validate and collect emails based on recipient type
                if (recipientType === 'custom') {
                    const emailsText = $('#genCustomEmails').val().trim();
                    customEmails = emailsText ? emailsText.split('\n').map(e => e.trim()).filter(e => e) : [];
                    
                    if (customEmails.length === 0) {
                        alert('Please enter at least one email address');
                        return;
                    }
                } else if (recipientType === 'all') {
                    const emailsText = $('#genCustomEmailsAll').val().trim();
                    customEmails = emailsText ? emailsText.split('\n').map(e => e.trim()).filter(e => e) : [];
                    // For 'all', custom emails are optional
                }
                
                // Show confirmation based on recipient type
                let confirmMsg = '';
                if (recipientType === 'platform') {
                    confirmMsg = 'Send promo code to all platform users with promotional emails enabled?';
                } else if (recipientType === 'custom') {
                    confirmMsg = `Send promo code to ${customEmails.length} email address(es)?`;
                } else if (recipientType === 'all') {
                    confirmMsg = `Send promo code to all platform users + ${customEmails.length} custom email(s)?`;
                }
                
                if (!confirm(confirmMsg)) {
                    return;
                }
                
                sendGeneratedPromoCodeDirect(generatedPromoCodeId, recipientType, includeUnsubscribe, customEmails);
            });

            // Send bulk promo code from table actions
            $(document).on('click', '.send-bulk-btn', function() {
                const promoId = $(this).data('id');
                
                generatedPromoCodeId = promoId;
                generatedIsBulk = true;
                
                showBulkSendOptions(promoId, true);
            });

            // Show bulk send options
            function showBulkSendOptions(promoCodeId, isFromGeneration = false) {
                $('#selectedPromoCount').text('1');
                $('#selectedPromosList').html(`
                    <div class="alert alert-info mb-0">
                        <i class="ri-information-line"></i> This promo code will be sent to selected recipients.
                    </div>
                `);
                
                // Reset form
                $('#recipientPlatform').prop('checked', true);
                $('#platformSection').show();
                $('#customSection').hide();
                $('#allSection').hide();
                $('#customEmails').val('');
                $('#customEmailsAll').val('');
                
                // Reset button
                $('#confirmBulkSendBtn').html('<i class="ri-mail-send-line"></i> Send to Users').prop('disabled', false);
                
                bulkSendModalInstance.show();
            }

            // Handle recipient type selection
            $('input[name="recipientType"]').on('change', function() {
                const recipientType = $(this).val();
                
                // Hide all sections
                $('#platformSection').hide();
                $('#customSection').hide();
                $('#allSection').hide();
                
                // Show selected section
                if (recipientType === 'platform') {
                    $('#platformSection').show();
                } else if (recipientType === 'custom') {
                    $('#customSection').show();
                } else if (recipientType === 'all') {
                    $('#allSection').show();
                }
            });

            // Confirm bulk send
            $('#confirmBulkSendBtn').click(function() {
                const recipientType = $('input[name="recipientType"]:checked').val();
                const includeUnsubscribe = $('#includeUnsubscribeBulk').is(':checked');
                
                let customEmails = [];
                
                // Validate and collect emails based on recipient type
                if (recipientType === 'custom') {
                    const emailsText = $('#customEmails').val().trim();
                    customEmails = emailsText ? emailsText.split('\n').map(e => e.trim()).filter(e => e) : [];
                    
                    if (customEmails.length === 0) {
                        alert('Please enter at least one email address');
                        return;
                    }
                } else if (recipientType === 'all') {
                    const emailsText = $('#customEmailsAll').val().trim();
                    customEmails = emailsText ? emailsText.split('\n').map(e => e.trim()).filter(e => e) : [];
                    // For 'all', custom emails are optional
                }
                
                // Show confirmation based on recipient type
                let confirmMsg = '';
                if (recipientType === 'platform') {
                    confirmMsg = 'Send promo code to all platform users with promotional emails enabled?';
                } else if (recipientType === 'custom') {
                    confirmMsg = `Send promo code to ${customEmails.length} email address(es)?`;
                } else if (recipientType === 'all') {
                    confirmMsg = `Send promo code to all platform users + ${customEmails.length} custom email(s)?`;
                }
                
                if (!confirm(confirmMsg)) {
                    return;
                }
                
                // If sending from generation modal
                if (generatedPromoCodeId && generatedIsBulk) {
                    sendBulkPromoCode(generatedPromoCodeId, recipientType, includeUnsubscribe, customEmails);
                } else {
                    // If sending from table selection
                    const promoIds = Array.from(selectedPromoIds);
                    sendMultipleBulkPromoCodes(promoIds, recipientType, includeUnsubscribe, customEmails);
                }
            });

            // Send generated bulk promo code directly from generate modal
            function sendGeneratedPromoCodeDirect(promoCodeId, recipientType, includeUnsubscribe, customEmails) {
                // Show spinner and disable button
                $('#sendGeneratedPromoBtn').html('<span class="spinner-border spinner-border-sm me-2"></span>Sending...').prop('disabled', true);
                
                $.ajax({
                    url: '{{ route('admin.promo-codes.bulk-send-promos') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        promo_code_ids: [promoCodeId],
                        recipient_type: recipientType,
                        include_unsubscribe: includeUnsubscribe ? 1 : 0,
                        custom_emails: customEmails
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show detailed success message
                            let successMsg = response.message;
                            if (response.sent_count !== undefined) {
                                successMsg = `✓ Promo code(s) sent successfully!\n\nTotal emails sent: ${response.sent_count}`;
                                if (response.platform_count !== undefined && response.custom_count !== undefined) {
                                    successMsg += `\n• Platform users: ${response.platform_count}`;
                                    successMsg += `\n• Custom emails: ${response.custom_count}`;
                                }
                            }
                            alert(successMsg);
                            
                            // Reset modal
                            $('#generatePromoForm').show();
                            $('#codeGeneratedSection').hide();
                            $('#generatePromoBtn').show();
                            $('#sendGeneratedPromoBtn').hide();
                            $('#generatePromoForm')[0].reset();
                            
                            // Close generate modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById('generatePromoModal'));
                            modal.hide();
                            
                            // Reload table
                            promosTable.ajax.reload();
                        } else {
                            alert('Error: ' + response.message);
                            // Reset button
                            $('#sendGeneratedPromoBtn').html('<i class="ri-mail-send-line"></i> Send to Users Now').prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        alert('Error sending promo code');
                        console.log(xhr);
                        // Reset button
                        $('#sendGeneratedPromoBtn').html('<i class="ri-mail-send-line"></i> Send to Users Now').prop('disabled', false);
                    }
                });
            }

            // Send single bulk promo code
            function sendBulkPromoCode(promoCodeId, recipientType, includeUnsubscribe, customEmails) {
                // Show spinner and disable button
                $('#confirmBulkSendBtn').html('<span class="spinner-border spinner-border-sm me-2"></span>Sending...').prop('disabled', true);
                
                $.ajax({
                    url: '{{ route('admin.promo-codes.bulk-send-promos') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        promo_code_ids: [promoCodeId],
                        recipient_type: recipientType,
                        include_unsubscribe: includeUnsubscribe ? 1 : 0,
                        custom_emails: customEmails
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show detailed success message
                            let successMsg = response.message;
                            if (response.sent_count !== undefined) {
                                successMsg = `✓ Promo code(s) sent successfully!\n\nTotal emails sent: ${response.sent_count}`;
                                if (response.platform_count !== undefined && response.custom_count !== undefined) {
                                    successMsg += `\n• Platform users: ${response.platform_count}`;
                                    successMsg += `\n• Custom emails: ${response.custom_count}`;
                                }
                            }
                            alert(successMsg);
                            bulkSendModalInstance.hide();
                            
                            // Reset modal
                            $('#generatePromoForm').show();
                            $('#codeGeneratedSection').hide();
                            $('#generatePromoBtn').show();
                            $('#sendGeneratedPromoBtn').hide();
                            $('#generatePromoForm')[0].reset();
                            
                            // Close generate modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById('generatePromoModal'));
                            modal.hide();
                            
                            // Reload table
                            promosTable.ajax.reload();
                        } else {
                            alert('Error: ' + response.message);
                            // Reset button
                            $('#confirmBulkSendBtn').html('<i class="ri-mail-send-line"></i> Send to Users').prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        alert('Error sending promo code');
                        console.log(xhr);
                        // Reset button
                        $('#confirmBulkSendBtn').html('<i class="ri-mail-send-line"></i> Send to Users').prop('disabled', false);
                    }
                });
            }

            // Send multiple bulk promo codes
            function sendMultipleBulkPromoCodes(promoIds, recipientType, includeUnsubscribe, customEmails) {
                // Show spinner and disable button
                $('#confirmBulkSendBtn').html('<span class="spinner-border spinner-border-sm me-2"></span>Sending...').prop('disabled', true);
                
                $.ajax({
                    url: '{{ route('admin.promo-codes.bulk-send-promos') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        promo_code_ids: promoIds,
                        recipient_type: recipientType,
                        include_unsubscribe: includeUnsubscribe ? 1 : 0,
                        custom_emails: customEmails
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show detailed success message
                            let successMsg = response.message;
                            if (response.sent_count !== undefined) {
                                successMsg = `✓ Promo code(s) sent successfully!\n\nTotal emails sent: ${response.sent_count}`;
                                if (response.platform_count !== undefined && response.custom_count !== undefined) {
                                    successMsg += `\n• Platform users: ${response.platform_count}`;
                                    successMsg += `\n• Custom emails: ${response.custom_count}`;
                                }
                            }
                            alert(successMsg);
                            bulkSendModalInstance.hide();
                            selectedPromoIds.clear();
                            $('#selectAllPromos').prop('checked', false);
                            updateBulkSendButtonVisibility();
                            promosTable.ajax.reload();
                        } else {
                            alert('Error: ' + response.message);
                            // Reset button
                            $('#confirmBulkSendBtn').html('<i class="ri-mail-send-line"></i> Send to Users').prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        alert('Error sending promo codes');
                        console.log(xhr);
                        // Reset button
                        $('#confirmBulkSendBtn').html('<i class="ri-mail-send-line"></i> Send to Users').prop('disabled', false);
                    }
                });
            }

            // Bulk send button click (from table)
            $('#bulkSendBtn').click(function() {
                generatedPromoCodeId = null;
                generatedIsBulk = false;
                showBulkSendOptions(null);
            });

            // Toggle status (activate/deactivate)
            $(document).on('click', '.toggle-status-btn', function() {
                const promoId = $(this).data('id');
                const currentStatus = $(this).data('status');
                const route = currentStatus ? '{{ route('admin.promo-codes.deactivate', ':id') }}' : '{{ route('admin.promo-codes.activate', ':id') }}';

                $.ajax({
                    url: route.replace(':id', promoId),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            promosTable.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        alert('Error updating promo code status');
                    }
                });
            });

            // Delete promo code
            $(document).on('click', '.delete-btn', function() {
                deletePromoId = $(this).data('id');
                const code = $(this).data('code');
                $('#deletePromoCode').text(code);
                deleteConfirmModalInstance.show();
            });

            // Confirm delete
            $('#confirmDeleteBtn').click(function() {
                if (!deletePromoId) return;

                $.ajax({
                    url: '{{ route('admin.promo-codes.delete', ':id') }}'.replace(':id', deletePromoId),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            deleteConfirmModalInstance.hide();
                            promosTable.ajax.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Error deleting promo code');
                    }
                });
            });
        });
    </script>
@endsection
