<?php $__env->startSection('title'); ?> Users <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>

<?php $__env->slot('li_1'); ?> <a href="<?php echo e(route('admin.users.index')); ?>">  Users</a> <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?>Users <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header end-t-end">
                <h5 class="card-title mb-0"> Users</h5>
                <div class="d-flex gap-2">
                    <button id="batchSendPromoBtn" class="btn btn-success waves-effect waves-light" style="display:none;">
                        <i class="ri-mail-send-line"></i> Batch Send Promo
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table id="geniustable" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width: 30px;">
                                <input type="checkbox" id="selectAllCheckbox" title="Select All">
                            </th>
                            <th data-ordering="false">ID</th>
                            <th >User Name</th>
                            <th >User Email</th>
                            <th >Affiliate</th>


                            <th >Contact</th>
                           
                            <th>Status</th>
                            <th>Promo Code</th>
                            <th>Created At</th>
                            
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
    <!--end col-->
</div>
<!--end row-->



<!-- Delete modal -->
<div class="modal fade" id="confirm-delete" aria-hidden="true" aria-labelledby="..." tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-3">
                <lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop"
                    colors="primary:#f7b84b,secondary:#405189" style="width:130px;height:130px">
                </lord-icon>
                <div class="">
                    <h4>Uh oh, You are about to delete this Data!</h4>
                    <p class="text-muted"> Do you want to proceed?</p>
                    <!-- Toogle to second dialog -->
                    <div class="col-lg-12">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                             <a href="" class="btn btn-danger btn-ok" >
                                Delete
                            </a>                           
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Delete modal ends-->

<!-- Promo Code Modal -->
<div class="modal fade" id="promoModal" tabindex="-1" aria-labelledby="promoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="promoModalLabel">Send Promo Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label"><strong>User:</strong></label>
                    <p><span id="promoUserName"></span> (<span id="promoUserEmail"></span>)</p>
                </div>

                <form id="promoForm">
                    <div class="mb-3">
                        <label for="discountPercentageInput" class="form-label">Discount Percentage (%)</label>
                        <input type="number" class="form-control" id="discountPercentageInput" 
                               min="0" max="100" step="0.01" placeholder="e.g., 10.50" required>
                        <small class="form-text text-muted">Enter discount as percentage (0-100)</small>
                    </div>

                    <div class="mb-3">
                        <label for="expiryDateInput" class="form-label">Expiry Date (Optional)</label>
                        <input type="date" class="form-control" id="expiryDateInput">
                        <small class="form-text text-muted">Leave empty for no expiry</small>
                    </div>

                    <div class="mb-3">
                        <label for="promoDescriptionInput" class="form-label">Description</label>
                        <textarea class="form-control" id="promoDescriptionInput" rows="2" 
                                  placeholder="Optional description for this promo code"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <input type="checkbox" id="includeUnsubscribe" checked>
                            Include unsubscribe link in email
                        </label>
                        <small class="form-text text-muted">
                            User will be able to unsubscribe from promotional emails
                        </small>
                    </div>
                </form>

                <div id="generatedCode"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="generatePromoBtn">
                    <i class="ri-magic-line"></i> Generate Code
                </button>
                <button type="button" class="btn btn-success" id="sendPromoBtn" style="display:none;">
                    <i class="ri-mail-send-line"></i> Send to User
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Promo Code Modal -->

<!-- Batch Promo Code Modal -->
<div class="modal fade" id="batchPromoModal" tabindex="-1" aria-labelledby="batchPromoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="batchPromoModalLabel">Send Promo Code to Multiple Users</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label"><strong>Selected Users:</strong></label>
                    <p><span id="batchSelectedCount">0</span> user(s) selected</p>
                </div>

                <form id="batchPromoForm">
                    <div class="mb-3">
                        <label for="batchDiscountPercentageInput" class="form-label">Discount Percentage (%)</label>
                        <input type="number" class="form-control" id="batchDiscountPercentageInput" 
                               min="0" max="100" step="0.01" placeholder="e.g., 10.50" required>
                        <small class="form-text text-muted">Enter discount as percentage (0-100)</small>
                    </div>

                    <div class="mb-3">
                        <label for="batchExpiryDateInput" class="form-label">Expiry Date (Optional)</label>
                        <input type="date" class="form-control" id="batchExpiryDateInput">
                        <small class="form-text text-muted">Leave empty for no expiry</small>
                    </div>

                    <div class="mb-3">
                        <label for="batchPromoDescriptionInput" class="form-label">Description</label>
                        <textarea class="form-control" id="batchPromoDescriptionInput" rows="2" 
                                  placeholder="Optional description for this promo code"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <input type="checkbox" id="batchIncludeUnsubscribe" checked>
                            Include unsubscribe link in email
                        </label>
                        <small class="form-text text-muted">
                            Users will be able to unsubscribe from promotional emails
                        </small>
                    </div>
                </form>

                <div id="batchGeneratedCode"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="batchGeneratePromoBtn">
                    <i class="ri-magic-line"></i> Generate Code
                </button>
                <button type="button" class="btn btn-success" id="batchSendPromoBtn" style="display:none;">
                    <i class="ri-mail-send-line"></i> Send to All Selected
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Batch Promo Code Modal -->
<?php $__env->startSection('script'); ?>
    <!-- DataTables Responsive -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables core -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <!-- Optional responsive (if used) -->
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>



    

    <script type="text/javascript">

        $(document).ready(function() {
            // Initialize modal instance - store it globally
            let promoModalInstance = new bootstrap.Modal(document.getElementById('promoModal'));
            let batchPromoModalInstance = new bootstrap.Modal(document.getElementById('batchPromoModal'));
            let selectedUserIds = new Set();

            $('#geniustable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: '<?php echo e(route('admin.users.datatables')); ?>',
                columns: [
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `<input type="checkbox" class="user-checkbox" value="${data}">`;
                        }
                    },
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'referralDetails', name: 'referralDetails' },
                    { data: 'phone', name: 'phone' },
                    { data: 'status', name: 'status' },
                    { 
                        data: 'id', 
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let promoButton = '';
                            if (row.promotional_emails) {
                                promoButton = `<button class="btn btn-primary btn-sm promo-code-btn" data-user-id="${data}" title="Send Promo Code">
                                    <i class="ri-gift-line"></i> Promo
                                </button>`;
                            }
                            
                            return `
                                <div style="display: flex; gap: 8px;">
                                    ${promoButton}
                                    <a href="/admin/users/secret/login/${data}" class="btn btn-warning btn-sm" title="Login as this user">
                                        <i class="ri-login-box-line"></i> Login
                                    </a>
                                </div>
                            `;
                        }
                    },
                    { data: 'created_at', name: 'created_at' }
                ]
            });

            // Handle checkbox selection
            $(document).on('change', '.user-checkbox', function() {
                const userId = $(this).val();
                if ($(this).is(':checked')) {
                    selectedUserIds.add(userId);
                } else {
                    selectedUserIds.delete(userId);
                }
                updateBatchButtonVisibility();
            });

            // Handle select all checkbox
            $('#selectAllCheckbox').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('#geniustable tbody tr').each(function() {
                    const checkbox = $(this).find('.user-checkbox');
                    checkbox.prop('checked', isChecked);
                    const userId = checkbox.val();
                    if (isChecked) {
                        selectedUserIds.add(userId);
                    } else {
                        selectedUserIds.delete(userId);
                    }
                });
                updateBatchButtonVisibility();
            });

            // Update batch button visibility
            function updateBatchButtonVisibility() {
                if (selectedUserIds.size > 0) {
                    $('#batchSendPromoBtn').show();
                } else {
                    $('#batchSendPromoBtn').hide();
                }
            }

            // Open batch promo modal
            $('#batchSendPromoBtn').click(function() {
                $('#batchSelectedCount').text(selectedUserIds.size);
                
                // Reset form
                $('#batchPromoForm')[0].reset();
                $('#batchIncludeUnsubscribe').prop('checked', true);
                $('#batchGeneratedCode').html('');
                
                // Reset button visibility
                $('#batchSendPromoBtn').hide();
                $('#batchGeneratePromoBtn').show();

                batchPromoModalInstance.show();
            });

            // Reset batch modal when it's hidden
            document.getElementById('batchPromoModal').addEventListener('hide.bs.modal', function() {
                $('#batchPromoForm')[0].reset();
                $('#batchGeneratedCode').html('');
                $('#batchSendPromoBtn').html('<i class="ri-mail-send-line"></i> Send to All Selected').prop('disabled', false).hide();
                $('#batchGeneratePromoBtn').show();
                $('#batchPromoModal').removeData('promo-code-id');
            });

            // Generate batch promo code
            $('#batchGeneratePromoBtn').click(function() {
                const discountPercentage = $('#batchDiscountPercentageInput').val();
                const expiryDate = $('#batchExpiryDateInput').val();
                const description = $('#batchPromoDescriptionInput').val();

                if (!discountPercentage) {
                    alert('Please enter discount percentage');
                    return;
                }

                $.ajax({
                    url: '<?php echo e(route('admin.promo-codes.generate-for-user')); ?>',
                    type: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        discount_percentage: discountPercentage,
                        expires_at: expiryDate,
                        description: description,
                        is_batch: true
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#batchPromoModal').data('promo-code-id', response.promo_code_id);
                            $('#batchGeneratedCode').html(`
                                <div class="alert alert-success">
                                    <h5>Promo Code Generated!</h5>
                                    <p><strong>Code:</strong> <code>${response.code}</code></p>
                                    <p><strong>Discount:</strong> ${response.discount_percentage}%</p>
                                    <p><strong>Expires:</strong> ${response.expires_at || 'No expiry'}</p>
                                </div>
                            `);
                            $('#batchSendPromoBtn').show();
                            $('#batchGeneratePromoBtn').hide();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Error generating code');
                        console.log(xhr);
                    }
                });
            });

            // Send batch promo code
            $('#batchSendPromoBtn').click(function() {
                const promoCodeId = $('#batchPromoModal').data('promo-code-id');
                const includeUnsubscribe = $('#batchIncludeUnsubscribe').is(':checked');
                const userIds = Array.from(selectedUserIds);

                // Show spinner and disable button
                $('#batchSendPromoBtn').html('<span class="spinner-border spinner-border-sm me-2"></span>Sending...').prop('disabled', true);

                $.ajax({
                    url: '<?php echo e(route('admin.promo-codes.batch-send-to-users')); ?>',
                    type: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        user_ids: userIds,
                        promo_code_id: promoCodeId,
                        include_unsubscribe: includeUnsubscribe ? 1 : 0
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            batchPromoModalInstance.hide();
                            selectedUserIds.clear();
                            $('#selectAllCheckbox').prop('checked', false);
                            $('#geniustable').DataTable().ajax.reload();
                            updateBatchButtonVisibility();
                        } else {
                            alert('Error: ' + response.message);
                            // Reset button
                            $('#batchSendPromoBtn').html('<i class="ri-mail-send-line"></i> Send to All Selected').prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        alert('Error sending codes');
                        console.log(xhr);
                        // Reset button
                        $('#batchSendPromoBtn').html('<i class="ri-mail-send-line"></i> Send to All Selected').prop('disabled', false);
                    }
                });
            });

            // Reset modal when it's hidden
            document.getElementById('promoModal').addEventListener('hide.bs.modal', function() {
                // Reset all form data
                $('#promoForm')[0].reset();
                $('#generatedCode').html('');
                $('#discountPercentageInput').val('');
                $('#expiryDateInput').val('');
                $('#promoDescriptionInput').val('');
                $('#includeUnsubscribe').prop('checked', true);
                
                // Reset button visibility and state
                $('#sendPromoBtn').html('<i class="ri-mail-send-line"></i> Send to User').prop('disabled', false).hide();
                $('#generatePromoBtn').show();
                
                // Clear stored promo code ID
                $('#promoModal').removeData('promo-code-id');
                $('#promoModal').removeData('user-id');
            });

            // Promo Code Button Click Handler
            $(document).on('click', '.promo-code-btn', function() {
                const userId = $(this).data('user-id');
                const userName = $(this).closest('tr').find('td:eq(2)').text();
                const userEmail = $(this).closest('tr').find('td:eq(3)').text();

                // Set user data in modal
                $('#promoModal').data('user-id', userId);
                $('#promoUserName').text(userName);
                $('#promoUserEmail').text(userEmail);
                
                // Reset form
                $('#promoForm')[0].reset();
                $('#expiryDateInput').val('');
                $('#discountPercentageInput').val('');
                $('#promoDescriptionInput').val('');
                $('#includeUnsubscribe').prop('checked', true);
                $('#generatedCode').html('');
                
                // Reset button visibility
                $('#sendPromoBtn').hide();
                $('#generatePromoBtn').show();

                // Show modal using stored instance
                promoModalInstance.show();
            });

            // Generate Promo Code
            $('#generatePromoBtn').click(function() {
                const userId = $('#promoModal').data('user-id');
                const discountPercentage = $('#discountPercentageInput').val();
                const expiryDate = $('#expiryDateInput').val();
                const description = $('#promoDescriptionInput').val();

                if (!discountPercentage) {
                    alert('Please enter discount percentage');
                    return;
                }

                $.ajax({
                    url: '<?php echo e(route('admin.promo-codes.generate-for-user')); ?>',
                    type: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        user_id: userId,
                        discount_percentage: discountPercentage,
                        expires_at: expiryDate,
                        description: description
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#promoModal').data('promo-code-id', response.promo_code_id);
                            $('#generatedCode').html(`
                                <div class="alert alert-success">
                                    <h5>Promo Code Generated!</h5>
                                    <p><strong>Code:</strong> <code>${response.code}</code></p>
                                    <p><strong>Discount:</strong> ${response.discount_percentage}%</p>
                                    <p><strong>Expires:</strong> ${response.expires_at || 'No expiry'}</p>
                                </div>
                            `);
                            $('#sendPromoBtn').show();
                            $('#generatePromoBtn').hide();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Error generating code');
                        console.log(xhr);
                    }
                });
            });

            // Send Promo Code to User
            $('#sendPromoBtn').click(function() {
                const userId = $('#promoModal').data('user-id');
                const promoCodeId = $('#promoModal').data('promo-code-id');
                const includeUnsubscribe = $('#includeUnsubscribe').is(':checked');

                // Show spinner and disable button
                $('#sendPromoBtn').html('<span class="spinner-border spinner-border-sm me-2"></span>Sending...').prop('disabled', true);

                $.ajax({
                    url: '<?php echo e(route('admin.promo-codes.send-to-user')); ?>',
                    type: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        user_id: userId,
                        promo_code_id: promoCodeId,
                        include_unsubscribe: includeUnsubscribe ? 1 : 0
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            // Hide modal using stored instance
                            promoModalInstance.hide();
                            // Optionally reload the table
                            $('#geniustable').DataTable().ajax.reload();
                        } else {
                            alert('Error: ' + response.message);
                            // Reset button
                            $('#sendPromoBtn').html('<i class="ri-mail-send-line"></i> Send to User').prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        alert('Error sending code');
                        console.log(xhr);
                        // Reset button
                        $('#sendPromoBtn').html('<i class="ri-mail-send-line"></i> Send to User').prop('disabled', false);
                    }
                });
            });
        });


        


</script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Herd-Projects\hired-hero\resources\views/admin/users/users.blade.php ENDPATH**/ ?>