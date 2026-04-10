@extends('placement.wizard.layout')

@section('step-content')
<div class="wizard-step-content">
    @php
    // Check user's active subscription
    $user = Auth::user();
    $activeSubscription = $user->getActiveSubscription();
    
    // Check if user has active subscription and it's not the free plan
    $hasActiveSubscription = $activeSubscription && 
                             $activeSubscription->isActive() && 
                             $activeSubscription->plan && 
                             $activeSubscription->plan->slug !== 'free-plan';
    @endphp

    @if(!$hasActiveSubscription)
    <!-- Upsell Banner for Free Plan Users -->
    <div class="upsell-banner mb-5">
        <div class="upsell-banner-content">
            <div class="upsell-icon">
                <i class="ri-flashlight-line"></i>
            </div>
            <div class="upsell-text">
                <h5 class="upsell-banner-title">Boost Your Resume's Performance</h5>
                <p class="upsell-banner-description">
                    We think your resume could convert better with our AI Resume Builder and Cover Letter tools. Get access for just <strong>$5.99/week</strong> – try a 2-week pass risk-free.
                </p>
            </div>
            <a href="{{ route('front.pricing') }}" class="btn btn-sm btn-warning">
                <i class="ri-arrow-right-line"></i>
                View Plans
            </a>
        </div>
    </div>
    @endif

    <div class="mb-5">
        <h2 class="step-title mb-3">Select Your Target Roles</h2>
        <p class="step-description">Choose up to 4 roles you're interested in. Our AI suggests roles based on your profile.</p>
    </div>
    <form id="step8Form" method="POST" data-step="8">
        @csrf

        <!-- AI Suggested Roles -->
        <div class="form-group mb-5">
            <label class="form-label fw-semibold mb-3 d-flex justify-content-between align-items-center">
                <span><i class="ri-sparkles-line me-2" style="color: #fbbf24;"></i> AI Suggested Roles</span>
                <small class="text-muted fw-normal">Based on your profile</small>
            </label>

            <div class="roles-grid">
                @php
                $suggestedRoles = $suggestedRoles ?? [
                ['title' => 'Senior Software Engineer', 'match' => 98],
                ['title' => 'Full Stack Developer', 'match' => 95],
                ['title' => 'Technical Lead', 'match' => 92],
                ['title' => 'Engineering Manager', 'match' => 88],
                ];
                @endphp

                @foreach ($suggestedRoles as $role)
                <div class="role-card">
                    <div class="role-match-badge">{{ $role['match'] }}% Match</div>
                    <input type="checkbox" name="selected_roles[]"
                        id="role_{{ str_replace(' ', '_', strtolower($role['title'])) }}"
                        value="{{ $role['title'] }}"
                        class="role-checkbox"
                        {{ in_array($role['title'], old('selected_roles', $profile?->selected_roles ?? [])) ? 'checked' : '' }}>
                    <label for="role_{{ str_replace(' ', '_', strtolower($role['title'])) }}" class="role-label">
                        <div class="role-title">{{ $role['title'] }}</div>
                        <div class="role-match-info">
                            <i class="ri-check-double-line"></i>
                            AI Recommended
                        </div>
                    </label>
                </div>
                @endforeach
            </div>

            <small class="text-muted d-block mt-3">
                <i class="ri-information-line"></i>
                Select at least one role to continue. You can select up to 4 roles.
            </small>
        </div>

        <!-- Custom Role Input -->
        <div class="form-group mb-5">
            <label for="custom_role" class="form-label fw-semibold mb-3">
                <i class="ri-pencil-line me-2" style="color: #6366f1;"></i> Add Custom Role (Optional)
            </label>
            <div class="custom-role-wrapper">
                <input type="text" id="custom_role" class="form-control form-control-lg"
                    placeholder="e.g., DevOps Engineer, Data Scientist, Product Manager">
                <button type="button" class="btn btn-sm btn-primary" id="addCustomRoleBtn" onclick="addCustomRole()">
                    <i class="ri-add-line"></i> Add Role
                </button>
            </div>
            <small class="text-muted d-block mt-2">
                <i class="ri-lightbulb-line"></i>
                If your target role isn't listed above, you can add it here.
            </small>

            <!-- Custom Roles Grid -->
            <div id="customRolesContainer" class="mt-4">
                <div id="customRolesLabel" class="form-label fw-semibold mb-3" style="display: none;">
                    <i class="ri-star-line me-2" style="color: #6366f1;"></i> Your Custom Roles
                </div>
                <div id="customRolesList" class="roles-grid"></div>
            </div>
        </div>

        <!-- Selection Counter -->
        <div class="selection-counter">
            <div class="counter-display">
                <span id="selectedCount">0</span>
                <span id="selectedLabel" class="ms-1">roles selected</span>
            </div>
            <div class="counter-progress">
                <div class="progress" style="height: 4px;">
                    <div id="counterProgress" class="progress-bar" role="progressbar" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- Benefits Section -->
        <div class="benefits-section mt-5">
            <h6 class="benefits-title mb-3">
                <i class="ri-star-smile-line me-2"></i> What You'll Get
            </h6>
            <div class="benefits-grid">
                <div class="benefit-item">
                    <i class="ri-briefcase-4-line"></i>
                    <div>
                        <h6>Targeted Matches</h6>
                        <p>Only see jobs matching your selected roles</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <i class="ri-checkbox-circle-line"></i>
                    <div>
                        <h6>Smart Filtering</h6>
                        <p>AI filters jobs by seniority and requirements</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <i class="ri-mail-send-line"></i>
                    <div>
                        <h6>Daily Updates</h6>
                        <p>Get new matching jobs sent to your inbox</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center pt-4 border-top">
            <span class="progress-text">
                Progress: <strong>100%</strong>
            </span>
            <div>
                <a href="{{ route('placement.wizard.step', ['step' => 7]) }}" class="btn btn-outline-secondary me-2 waves-effect waves-light">
                    Back
                </a>
                <button type="submit" class="btn btn-primary waves-effect waves-light" id="submitBtn" disabled>Complete Setup</button>
            </div>
        </div>
    </form>

    <!-- Background Job Scraping Progress Modal -->
    <div class="modal fade" id="scrapingProgressModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="scrapingProgressLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="scrapingProgressLabel">
                        <i class="ri-loader-4-line me-2 spinning"></i>
                        <span id="modalTitle">Finding Jobs For You</span>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="scraping-status">
                        <p id="statusMessage" class="text-muted mb-3">Initializing job search...</p>
                    </div>
                    
                    <!-- Main Progress Bar -->
                    <div class="mb-4">
                        <label class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold">Overall Progress</span>
                            <span id="progressPercent" class="badge bg-primary">0%</span>
                        </label>
                        <div class="progress" style="height: 8px;">
                            <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Job Count -->
                    <div class="alert alert-info mt-3 mb-0" id="jobCountAlert" style="display: none;">
                        <i class="ri-information-line me-2"></i>
                        <span><strong id="totalJobs">0</strong> job matches found so far</span>
                    </div>

                    <!-- Error Message -->
                    <div class="alert alert-danger mt-3 mb-0" id="errorAlert" style="display: none;">
                        <i class="ri-error-warning-line me-2"></i>
                        <span id="errorMessage">An error occurred</span>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-primary" id="previewMatchesBtn" style="display: none;" onclick="goToJobMatches()">
                        <i class="ri-arrow-right-line me-2"></i> Preview Matches
                    </button>
                    <button type="button" class="btn btn-secondary" id="dashboardBtn" style="display: none;" onclick="goToDashboard()">
                        <i class="ri-home-line me-2"></i> Go to Dashboard
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .wizard-step-content {
        padding: 2rem 0;
    }

    .step-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: #1a1a1a;
        line-height: 1.3;
    }

    .step-description {
        font-size: 1rem;
        color: #6c757d;
        line-height: 1.6;
    }

    .roles-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .role-card {
        position: relative;
        cursor: pointer;
    }

    .role-checkbox {
        display: none;
    }

    .role-label {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 1.5rem;
        border: 2px solid #e5e7eb;
        border-radius: 0.75rem;
        background: #f9fafb;
        cursor: pointer;
        transition: all 0.3s ease;
        margin: 0;
        height: 140px;
    }

    .role-checkbox:checked+.role-label {
        border-color: #3b82f6;
        background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .role-label:hover {
        border-color: #3b82f6;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
    }

    .role-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
    }

    .role-match-info {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.85rem;
        color: #6b7280;
    }

    .role-match-info i {
        color: #3b82f6;
        font-size: 1rem;
    }

    .role-match-badge {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 1;
    }

    .custom-role-wrapper {
        display: flex;
        gap: 0.75rem;
        align-items: stretch;
    }

    .custom-role-wrapper .form-control {
        flex: 1;
        padding: 0.75rem 1rem;
        border: 1.5px solid #cbd5e0;
        border-radius: 0.5rem;
        font-size: 1rem;
    }

    .custom-role-wrapper .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .custom-role-wrapper .btn {
        border-radius: 0.5rem;
        font-weight: 600;
    }

    .btn-remove-role {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: #ef4444;
        border: none;
        color: white;
        cursor: pointer;
        padding: 0.35rem;
        border-radius: 50%;
        width: 1.75rem;
        height: 1.75rem;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
        font-size: 1rem;
    }

    .role-card:hover .btn-remove-role {
        display: flex;
    }

    .btn-remove-role:hover {
        background: #dc2626;
    }

    .selection-counter {
        padding: 1.5rem;
        background: linear-gradient(135deg, #f0f4ff 0%, #f3f4f6 100%);
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        margin: 2rem 0;
    }

    .counter-display {
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    #selectedCount {
        font-size: 1.75rem;
        font-weight: 700;
        color: #3b82f6;
    }

    #selectedLabel {
        font-size: 1rem;
        color: #6b7280;
        font-weight: 500;
    }

    .benefits-section {
        padding: 2rem;
        background: linear-gradient(135deg, #fef3c7 0%, #fef9e7 100%);
        border-left: 4px solid #f59e0b;
        border-radius: 0.75rem;
    }

    .benefits-title {
        color: #b45309;
        font-weight: 600;
        margin: 0;
    }

    .benefits-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .benefit-item {
        display: flex;
        gap: 1rem;
        color: #92400e;
    }

    .benefit-item i {
        font-size: 1.5rem;
        color: #f59e0b;
        flex-shrink: 0;
    }

    .benefit-item h6 {
        font-weight: 600;
        color: #b45309;
        margin: 0 0 0.25rem 0;
    }

    .benefit-item p {
        margin: 0;
        font-size: 0.9rem;
    }

    .progress-text {
        font-size: 0.95rem;
        color: #4a5568;
    }

    .progress-text strong {
        color: #2d3748;
        font-weight: 600;
    }

    .border-top {
        border-color: #e2e8f0 !important;
        margin-top: 1rem;
    }

    #submitBtn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    #submitBtn:not(:disabled):hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
    }

    /* Loading spinner animation */
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    .spinning {
        animation: spin 1s linear infinite;
    }

    /* Upsell Banner */
    .upsell-banner {
        background: linear-gradient(135deg, #fef3c7 0%, #fef9e7 100%);
        border: 1.5px solid #fbbf24;
        border-radius: 0.75rem;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .upsell-banner-content {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        width: 100%;
    }

    .upsell-icon {
        font-size: 2.5rem;
        color: #f59e0b;
        flex-shrink: 0;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(245, 158, 11, 0.1);
        border-radius: 0.75rem;
    }

    .upsell-text {
        flex: 1;
    }

    .upsell-banner-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #b45309;
        margin: 0 0 0.5rem 0;
    }

    .upsell-banner-description {
        font-size: 0.95rem;
        color: #92400e;
        margin: 0;
        line-height: 1.5;
    }

    .upsell-banner .btn {
        flex-shrink: 0;
        min-width: 120px;
    }

    @media (max-width: 768px) {
        .upsell-banner-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .upsell-icon {
            margin: 0 auto;
        }

        .upsell-banner .btn {
            width: 100%;
            min-width: unset;
        }
    }
        .roles-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .step-title {
            font-size: 1.5rem;
        }

        .wizard-step-content {
            padding: 1rem 0;
        }

        .roles-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .custom-role-wrapper {
            flex-direction: column;
        }

        .custom-role-wrapper .btn {
            width: 100%;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }

        .btn-outline-secondary,
        button[type="submit"] {
            width: 100%;
        }

        .progress-text {
            order: -1;
            text-align: center;
        }

        .benefits-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    const form = document.getElementById('step8Form');
    const submitBtn = document.getElementById('submitBtn');
    const selectedCountDisplay = document.getElementById('selectedCount');
    const selectedLabel = document.getElementById('selectedLabel');
    const counterProgress = document.getElementById('counterProgress');

    function updateSelectionCount() {
        const checkedRoles = document.querySelectorAll('input[name="selected_roles[]"]:checked').length;
        selectedCountDisplay.textContent = checkedRoles;
        selectedLabel.textContent = `${checkedRoles === 1 ? 'role' : 'roles'} selected`;

        // Update progress bar
        const progress = (checkedRoles / 4) * 100;
        counterProgress.style.width = progress + '%';

        // Enable submit button if at least one role is selected
        submitBtn.disabled = checkedRoles === 0;
    }

    // Update count when role checkboxes change
    document.querySelectorAll('input[name="selected_roles[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionCount);
    });

    // Add custom role
    function addCustomRole() {
        const input = document.getElementById('custom_role');
        const roleName = input.value.trim();

        if (!roleName) {
            input.focus();
            return;
        }

        const list = document.getElementById('customRolesList');
        const roleId = 'custom_' + roleName.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
        
        // Check if role already exists
        if (document.getElementById(roleId)) {
            alert('This role has already been added');
            input.focus();
            return;
        }

        const roleCard = document.createElement('div');
        roleCard.className = 'role-card';
        roleCard.innerHTML = `
            <input type="checkbox" name="selected_roles[]"
                id="${roleId}"
                value="${roleName}"
                class="role-checkbox"
                checked>
            <label for="${roleId}" class="role-label">
                <div class="role-title">${roleName}</div>
                <div class="role-match-info">
                    <i class="ri-pencil-line"></i>
                    Custom Role
                </div>
            </label>
            <button type="button" class="btn-remove-role" onclick="removeCustomRole('${roleId}')" title="Remove role">
                <i class="ri-close-line"></i>
            </button>
        `;

        list.appendChild(roleCard);
        input.value = '';
        input.focus();
        
        // Show the custom roles label
        document.getElementById('customRolesLabel').style.display = 'block';
        
        // Add change listener to the new checkbox
        document.getElementById(roleId).addEventListener('change', updateSelectionCount);
        
        updateSelectionCount();
    }

    function removeCustomRole(roleId) {
        const roleCard = document.getElementById(roleId).closest('.role-card');
        roleCard.remove();
        
        // Hide label if no custom roles left
        if (document.getElementById('customRolesList').children.length === 0) {
            document.getElementById('customRolesLabel').style.display = 'none';
        }
        
        updateSelectionCount();
    }

    // Allow adding custom role with Enter key
    document.getElementById('custom_role').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addCustomRole();
        }
    });

    // Initialize count
    updateSelectionCount();

    // Form validation and background job submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        e.stopPropagation();

        console.log('Form submit event triggered');

        const checkedRoles = document.querySelectorAll('input[name="selected_roles[]"]:checked');
        const selectedRoles = Array.from(checkedRoles).map(checkbox => checkbox.value);

        if (selectedRoles.length === 0) {
            alert('Please select at least one role to continue');
            return false;
        }

        // Show the progress modal
        const modal = new bootstrap.Modal(document.getElementById('scrapingProgressModal'), {
            backdrop: 'static',
            keyboard: false
        });
        modal.show();

        try {
            // First, save the roles to the profile via the controller
            console.log('Saving roles to profile...');
            
            const csrfToken = document.querySelector('input[name="_token"]').value;
            
            const saveResponse = await fetch('{{ route("placement.wizard.submit", ["step" => 8]) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    selected_roles: selectedRoles
                })
            });

            if (!saveResponse.ok) {
                throw new Error(`Failed to save roles: ${saveResponse.status}`);
            }

            const saveData = await saveResponse.json();
            console.log('Save response:', saveData);
            
            if (!saveData.success) {
                throw new Error(saveData.error || 'Failed to save roles');
            }

            // Now start the background scraping job
            console.log('Starting background scraping job...');
            
            const response = await fetch('{{ route("placement.scraping.start") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    selected_roles: selectedRoles
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('API response:', data);
            
            if (!data.success) {
                throw new Error(data.message || 'Failed to start job scraping');
            }

            console.log('Scraping job initiated. Starting progress polling...');
            
            // Update initial message
            document.getElementById('statusMessage').textContent = 'Initializing job scraper...';
            
            // Start polling for progress
            startPollingProgress();

        } catch (error) {
            console.error('Error during setup:', error);
            document.getElementById('errorAlert').style.display = 'block';
            document.getElementById('errorMessage').textContent = error.message || 'Failed to complete setup';
            document.getElementById('progressBar').style.width = '0%';
            document.getElementById('progressPercent').textContent = '0%';
        }
    });

    // Poll for scraping progress
    let pollingInterval = null;
    let maxPollingAttempts = 1800; // 30 minutes with 2 second intervals
    let currentAttempts = 0;
    let lastProgress = 0;
    let noProgressCount = 0; // Track how many times we see no progress change

    function startPollingProgress() {
        currentAttempts = 0;
        lastProgress = 0;
        noProgressCount = 0;
        pollingInterval = setInterval(pollProgress, 2000); // Poll every 2 seconds
        // Also do first poll immediately
        pollProgress();
    }

    async function pollProgress() {
        try {
            currentAttempts++;

            if (currentAttempts > maxPollingAttempts) {
                clearInterval(pollingInterval);
                document.getElementById('errorAlert').style.display = 'block';
                document.getElementById('errorMessage').textContent = 'Job scraping timed out. Please try again.';
                document.getElementById('statusMessage').textContent = 'Timeout occurred.';
                return;
            }

            const response = await fetch('{{ route("placement.scraping.progress") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                console.warn(`Progress fetch failed: ${response.status}`);
                return;
            }

            const data = await response.json();
            console.log(`Poll attempt ${currentAttempts}: Progress=${data.progress}%, Status=${data.status}, Message=${data.message}`);
            
            updateProgressDisplay(data);

            // Track if progress is updating
            if (data.progress === lastProgress) {
                noProgressCount++;
            } else {
                noProgressCount = 0;
                lastProgress = data.progress;
            }

            // Check if completed or failed
            if (data.status === 'completed') {
                clearInterval(pollingInterval);
                console.log('Scraping completed successfully');
                showCompletedState(data);
            } else if (data.status === 'failed') {
                clearInterval(pollingInterval);
                console.log('Scraping failed');
                showFailedState(data);
            }

        } catch (error) {
            console.error('Error polling progress:', error);
            // Continue polling even on error unless too many in a row
        }
    }

    function updateProgressDisplay(data) {
        // Update progress bar
        document.getElementById('progressBar').style.width = data.progress + '%';
        document.getElementById('progressPercent').textContent = data.progress + '%';

        // Update status message with more details
        let messageText = data.message || 'Processing...';
        if (data.status === 'processing') {
            messageText = `${messageText} (${data.progress}%)`;
        }
        document.getElementById('statusMessage').textContent = messageText;

        // Update job count if available
        if (data.total_jobs > 0) {
            document.getElementById('jobCountAlert').style.display = 'block';
            document.getElementById('totalJobs').textContent = data.total_jobs;
        }
    }

    function showCompletedState(data) {
        // Update modal title
        document.getElementById('modalTitle').textContent = 'Jobs Found!';
        
        // Show job count in completion message
        const jobCountText = data.total_jobs > 0 ? ` Found ${data.total_jobs} matching jobs!` : '';
        document.getElementById('statusMessage').textContent = 'Job search completed successfully!' + jobCountText;
        document.getElementById('progressBar').style.width = '100%';
        document.getElementById('progressPercent').textContent = '100%';

        // Hide message and show action buttons
        document.getElementById('statusMessage').style.fontWeight = '500';
        document.getElementById('statusMessage').style.color = '#10b981';

        // Remove spinning animation
        const spinner = document.querySelector('.spinning');
        if (spinner) {
            spinner.style.animation = 'none';
            spinner.textContent = '\uea10'; // checkmark icon
            spinner.style.color = '#10b981';
        }

        // Show preview button
        document.getElementById('previewMatchesBtn').style.display = 'inline-block';
        document.getElementById('dashboardBtn').style.display = 'inline-block';
    }

    function showFailedState(data) {
        // Update modal title
        document.getElementById('modalTitle').textContent = 'Scraping Failed';
        document.getElementById('statusMessage').textContent = data.message || 'An error occurred while scraping jobs';
        document.getElementById('statusMessage').style.color = '#dc2626';

        // Remove spinning animation
        const spinner = document.querySelector('.spinning');
        if (spinner) {
            spinner.style.animation = 'none';
            spinner.style.color = '#dc2626';
        }

        // Show error
        document.getElementById('errorAlert').style.display = 'block';
        document.getElementById('errorMessage').textContent = data.message || 'Failed to scrape jobs';
    }

    function goToJobMatches() {
        window.location.href = '{{ route("placement.jobs.index") }}';
    }

    function goToDashboard() {
        window.location.href = '{{ route("user.dashboard") }}';
    }
</script>
@endsection