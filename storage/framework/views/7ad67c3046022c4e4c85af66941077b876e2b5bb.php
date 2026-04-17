
<?php $__env->startSection('title', 'Generate Cover Letter'); ?>
<?php $__env->startSection('content'); ?>

<div class="cover-letter-container">
    <div class="cover-letter-wrapper">
        <!-- Header -->
        <div class="cover-letter-header">
            <a href="<?php echo e(route('placement.jobs.index')); ?>" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Back to Job Matches
            </a>
            <h1 class="header-title">
                <i class="fas fa-file-alt"></i>
                Generate Cover Letter
            </h1>
            <p class="header-subtitle">For <?php echo e($jobTitle); ?> at <?php echo e($companyName); ?></p>
        </div>

        <!-- Main Content -->
        <div class="cover-letter-content">
            <!-- Left Sidebar - Job Details & Settings -->
            <aside class="cover-letter-sidebar">
                <!-- Job Details Card -->
                <div class="detail-card">
                    <h3 class="card-title">
                        <i class="fas fa-briefcase"></i>
                        Job Details
                    </h3>
                    <div class="detail-item">
                        <label>Job Title</label>
                        <p><?php echo e($jobTitle); ?></p>
                    </div>
                    <div class="detail-item">
                        <label>Company</label>
                        <p><?php echo e($companyName); ?></p>
                    </div>
                    <?php if($jobDescription): ?>
                    <div class="detail-item">
                        <label>Description Preview</label>
                        <p class="job-description-preview"><?php echo e(Str::limit($jobDescription, 200)); ?></p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Usage Counter -->
                <div class="detail-card usage-card">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie"></i>
                        Usage Limit
                    </h3>
                    <?php if($hasExceededLimit): ?>
                    <div class="usage-alert alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        You've reached your cover letter limit!
                    </div>
                    <a href="<?php echo e(route('front.pricing')); ?>" class="btn btn-primary w-100 mt-2">
                        Upgrade Plan
                    </a>
                    <?php else: ?>
                    <div class="usage-bar-container">
                        <?php if($coverLetterLimit === 'unlimited'): ?>
                        <div class="usage-info">
                            <span class="usage-label">Covers Generated</span>
                            <span class="usage-value"><?php echo e($coversUsed); ?> <span class="usage-unlimited">∞</span></span>
                        </div>
                        <div class="usage-bar unlimited">
                            <div class="usage-fill" style="width: 100%;"></div>
                        </div>
                        <p class="usage-text">Unlimited covers available</p>
                        <?php else: ?>
                        <div class="usage-info">
                            <span class="usage-label">Covers Used</span>
                            <span class="usage-value"><?php echo e($coversUsed); ?> / <?php echo e($coverLetterLimit); ?></span>
                        </div>
                        <div class="usage-bar">
                            <div class="usage-fill" style="width: <?php echo e(($coversUsed / $coverLetterLimit) * 100); ?>%;"></div>
                        </div>
                        <p class="usage-text"><?php echo e($coversRemaining); ?> cover<?php echo e($coversRemaining !== 1 ? 's' : ''); ?> remaining</p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- AI Prompt Input -->
                <div class="detail-card">
                    <h3 class="card-title">
                        <i class="fas fa-sparkles"></i>
                        AI Prompt (Optional)
                    </h3>
                    <p class="card-description">Provide additional context to enhance your cover letter</p>
                    <textarea
                        id="aiPrompt"
                        class="form-control ai-prompt-input"
                        placeholder="e.g., 'I have experience with IoT projects' or 'Emphasize my leadership skills'..."
                        rows="4"
                        maxlength="1000"></textarea>
                    <div class="char-count">
                        <span id="charCount">0</span>/1000
                    </div>
                </div>
            </aside>

            <!-- Main Content Area - Cover Letter Preview -->
            <main class="cover-letter-main">
                <!-- Tabs -->
                <div class="cover-letter-tabs">
                    <button class="tab-btn active" data-tab="preview">
                        <i class="fas fa-eye"></i>
                        Preview
                    </button>
                    <button class="tab-btn" data-tab="edit">
                        <i class="fas fa-edit"></i>
                        Edit
                    </button>
                </div>

                <!-- Preview Tab -->
                <div class="tab-content active" id="preview-tab">
                    <div class="cover-letter-preview" id="coverLetterPreview">
                        <div class="preview-loading">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Generating...</span>
                            </div>
                            <p>Generating your cover letter...</p>
                        </div>
                    </div>
                </div>

                <!-- Edit Tab -->
                <div class="tab-content" id="edit-tab">
                    <div style="display: flex; flex-direction: column; height: 100%; gap: 0;">
                        <textarea
                            id="coverLetterEdit"
                            class="cover-letter-editor"
                            placeholder="Your cover letter will appear here for editing..."
                            style="flex: 1;"></textarea>
                        <button type="button" class="btn btn-primary" onclick="applyEdits()" style="margin-top: auto; align-self: flex-end;">
                            <i class="fas fa-check"></i>
                            Save & View in Preview
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="cover-letter-actions">
                    <button type="button" class="btn btn-secondary" onclick="resetCoverLetter()">
                        <i class="fas fa-redo"></i>
                        Regenerate
                    </button>
                    <!-- <button type="button" class="btn btn-secondary" onclick="downloadAsPdf()">
                        <i class="fas fa-download"></i>
                        Download
                    </button> -->
                    <a href="<?php echo e(route('placement.jobs.index')); ?>" class="btn btn-primary">
                        Back to Job Matches
                    </a>
                    <?php if(!$hasExceededLimit): ?>
                    <button type="button" class="btn btn-primary" onclick="finalizeCoverLetter()">
                        <i class="fas fa-check-circle"></i>
                        Finalize Cover Letter
                    </button>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle"></i>
                    Cover Letter Finalized!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h6 class="mt-3">Your cover letter has been saved successfully!</h6>
                <p class="text-muted mt-2" id="sucessCounterText"></p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="<?php echo e(route('placement.jobs.index')); ?>" class="btn btn-primary">
                    Back to Job Matches
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Exceeded Limit Modal -->
<div class="modal fade" id="limitExceededModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-warning text-white border-0">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-circle"></i>
                    Cover Letter Limit Reached
                </h5>
            </div>
            <div class="modal-body text-center py-4">
                <div class="alert-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h6 class="mt-3">You've used all your cover letters for this billing period</h6>
                <p class="text-muted mt-2">Upgrade your plan to generate more cover letters</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="<?php echo e(route('front.pricing')); ?>" class="btn btn-warning">
                    View Plans
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Root Variables */
    :root {
        --bg-primary: #1a1f2e;
        --bg-secondary: #242936;
        --bg-tertiary: #2d3444;
        --bg-hover: #353d4f;

        --text-primary: #e5e7eb;
        --text-secondary: #9ca3af;
        --text-muted: #6b7280;

        --border-color: #374151;
        --border-light: #2d3748;

        --accent-primary: #4f5b73;
        --accent-secondary: #5a6679;

        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #3b82f6;
    }

    * {
        box-sizing: border-box;
    }

    body {
        background-color: var(--bg-primary);
        color: var(--text-primary);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .cover-letter-container {
        min-height: 100vh;
        padding: 2rem;
        background-color: var(--bg-primary);
    }

    .cover-letter-wrapper {
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Header */
    .cover-letter-header {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border-light);
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-secondary);
        text-decoration: none;
        margin-bottom: 1rem;
        font-size: 0.9375rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-back:hover {
        color: var(--text-primary);
    }

    .header-title {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .header-subtitle {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0;
    }

    /* Content Layout */
    .cover-letter-content {
        display: grid;
        grid-template-columns: 380px 1fr;
        gap: 2rem;
        margin-top: 2rem;
    }

    /* Sidebar */
    .cover-letter-sidebar {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .detail-card {
        background-color: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.5rem;
    }

    .card-title {
        font-size: 1rem;
        font-weight: 600;
        margin: 0 0 1rem 0;
        display: flex;
        align-items: center;
        gap: 0.625rem;
        color: var(--text-primary);
    }

    .card-title i {
        font-size: 1.125rem;
        opacity: 0.8;
    }

    .card-description {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        margin-bottom: 1rem;
    }

    .detail-item {
        margin-bottom: 1rem;
    }

    .detail-item:last-child {
        margin-bottom: 0;
    }

    .detail-item label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        margin-bottom: 0.375rem;
    }

    .detail-item p {
        font-size: 0.9375rem;
        color: var(--text-primary);
        margin: 0;
        word-wrap: break-word;
    }

    .job-description-preview {
        font-size: 0.8125rem !important;
        color: var(--text-secondary) !important;
        max-height: 100px;
        overflow-y: auto;
        padding: 0.625rem;
        background-color: var(--bg-tertiary);
        border-radius: 6px;
    }

    /* Usage Card */
    .usage-card .card-title {
        margin-bottom: 1.25rem;
    }

    .usage-alert {
        background-color: rgba(239, 68, 68, 0.1);
        border-color: var(--danger);
        color: var(--danger);
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .usage-bar-container {
        margin-top: 1rem;
    }

    .usage-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .usage-label {
        font-size: 0.8125rem;
        text-transform: uppercase;
        color: var(--text-muted);
        font-weight: 600;
        letter-spacing: 0.05em;
    }

    .usage-value {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .usage-unlimited {
        font-size: 1.5rem;
        margin-left: 0.25rem;
        opacity: 0.7;
    }

    .usage-bar {
        height: 8px;
        background-color: var(--bg-tertiary);
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 0.75rem;
    }

    .usage-bar.unlimited .usage-fill {
        background: linear-gradient(90deg, var(--success), var(--info));
    }

    .usage-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--warning), var(--danger));
        transition: width 0.3s ease;
    }

    .usage-text {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin: 0;
    }

    /* AI Prompt Input */
    .ai-prompt-input {
        background-color: var(--bg-tertiary);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        font-size: 0.9375rem;
        padding: 0.875rem;
        border-radius: 8px;
        font-family: 'Segoe UI', sans-serif;
        resize: vertical;
    }

    .ai-prompt-input::placeholder {
        color: var(--text-muted);
    }

    .ai-prompt-input:focus {
        outline: none;
        border-color: var(--accent-secondary);
        background-color: var(--bg-hover);
    }

    .char-count {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.5rem;
        text-align: right;
    }

    /* Main Content */
    .cover-letter-main {
        background-color: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        min-height: 600px;
    }

    /* Tabs */
    .cover-letter-tabs {
        display: flex;
        border-bottom: 1px solid var(--border-light);
        background-color: var(--bg-tertiary);
    }

    .tab-btn {
        flex: 1;
        padding: 1rem;
        background: none;
        border: none;
        color: var(--text-secondary);
        font-size: 0.9375rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border-bottom: 3px solid transparent;
    }

    .tab-btn:hover {
        color: var(--text-primary);
        background-color: var(--bg-secondary);
    }

    .tab-btn.active {
        color: var(--text-primary);
        border-bottom-color: var(--accent-secondary);
    }

    /* Tab Content */
    .tab-content {
        display: none;
        flex: 1;
        padding: 2rem;
        overflow-y: auto;
    }

    .tab-content.active {
        display: block;
    }

    /* Cover Letter Preview */
    .cover-letter-preview {
        background-color: white;
        color: #1f2937;
        padding: 2rem;
        border-radius: 8px;
        line-height: 1.6;
        font-family: 'Georgia', serif;
        min-height: 400px;
        white-space: pre-wrap;
        word-wrap: break-word;
        word-break: break-word;
        overflow-wrap: break-word;
        overflow-y: auto;
        max-width: 100%;
    }

    .preview-loading {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: var(--text-muted);
    }

    .preview-loading .spinner-border {
        width: 3rem;
        height: 3rem;
        border-color: var(--accent-secondary);
        border-right-color: transparent;
        margin-bottom: 1rem;
    }

    /* Cover Letter Editor */
    .cover-letter-editor {
        width: 100%;
        height: 100%;
        background-color: var(--bg-tertiary);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        font-size: 0.9375rem;
        padding: 1rem;
        border-radius: 8px;
        font-family: 'Segoe UI', monospace;
        resize: none;
        min-height: 400px;
    }

    .cover-letter-editor:focus {
        outline: none;
        border-color: var(--accent-secondary);
    }

    /* Actions */
    .cover-letter-actions {
        display: flex;
        gap: 1rem;
        padding: 1.5rem 2rem;
        border-top: 1px solid var(--border-light);
        background-color: var(--bg-tertiary);
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-size: 0.9375rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .btn-primary {
        background-color: var(--info);
        color: white;
    }

    .btn-primary:hover {
        background-color: #2563eb;
    }

    .btn-secondary {
        background-color: var(--accent-primary);
        color: var(--text-primary);
    }

    .btn-secondary:hover {
        background-color: var(--accent-secondary);
    }

    /* Modal Styles */
    .modal-content {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
    }

    .modal-header {
        border-color: var(--border-color);
    }

    .modal-body {
        color: var(--text-primary);
    }

    .modal-body .text-muted {
        color: var(--text-secondary) !important;
    }

    .modal-footer {
        border-color: var(--border-color);
    }

    .success-icon,
    .alert-icon {
        font-size: 3rem;
        color: var(--success);
        margin: 0 auto;
    }

    .alert-icon {
        color: var(--warning);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .cover-letter-content {
            grid-template-columns: 1fr;
        }

        .cover-letter-sidebar {
            order: 2;
        }

        .cover-letter-main {
            order: 1;
        }
    }

    @media (max-width: 768px) {
        .cover-letter-container {
            padding: 1rem;
        }

        .detail-card {
            padding: 1.25rem;
        }

        .tab-content {
            padding: 1.25rem;
        }

        .cover-letter-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .header-title {
            font-size: 1.5rem;
        }
    }
</style>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    // Store data from server
    const jobTitle = <?php echo json_encode($jobTitle, 15, 512) ?>;
    const companyName = <?php echo json_encode($companyName, 15, 512) ?>;
    const jobDescription = <?php echo json_encode($jobDescription ?? '', 15, 512) ?>;
    const hasExceededLimit = <?php echo e($hasExceededLimit ? 'true' : 'false'); ?>;
    let currentCoverLetter = '';

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize
        initializeTabs();
        generateCoverLetter();
        setupCharCounter();

        // Check if limit exceeded
        if (hasExceededLimit) {
            const modal = new bootstrap.Modal(document.getElementById('limitExceededModal'));
            modal.show();
        }
    });

    /**
     * Initialize tabs
     */
    function initializeTabs() {
        const tabBtns = document.querySelectorAll('.tab-btn');
        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const tabName = btn.dataset.tab;
                switchTab(tabName);
            });
        });
    }

    /**
     * Switch tabs
     */
    function switchTab(tabName) {
        // Update active button
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');

        // Update active content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        document.getElementById(`${tabName}-tab`).classList.add('active');

        // Sync editor with preview
        if (tabName === 'edit') {
            document.getElementById('coverLetterEdit').value = currentCoverLetter;
        }
    }

    /**
     * Generate cover letter
     */
    function generateCoverLetter() {
        const preview = document.getElementById('coverLetterPreview');

        // Ensure preview exists before proceeding
        if (!preview) {
            console.error('Preview element not found');
            return;
        }

        // Show loading state
        preview.innerHTML = '<div class="preview-loading"><div class="spinner-border" role="status"><span class="visually-hidden">Generating...</span></div><p>Generating your cover letter...</p></div>';

        const data = {
            job_title: jobTitle,
            company_name: companyName,
            job_description: jobDescription,
            selected_roles: [],
            ai_prompt: document.getElementById('aiPrompt')?.value || null,
        };

        fetch('<?php echo e(route("placement.covers.store")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
                body: JSON.stringify(data),
            })
            .then(response => {
                if (!response.ok) throw new Error('Generation failed');
                return response.json();
            })
            .then(data => {
                currentCoverLetter = data.coverLetter;
                updatePreview(currentCoverLetter);
            })
            .catch(error => {
                console.error('Error:', error);
                preview.innerHTML = '<p style="color: var(--danger); padding: 2rem;">Error generating cover letter. Please try again.</p>';
            });
    }

    /**
     * Update preview display
     */
    function updatePreview(content) {
        const preview = document.getElementById('coverLetterPreview');
        if (!preview) return;

        // Convert newlines to paragraphs for better display
        const paragraphs = content.split('\n\n');
        let html = '';

        paragraphs.forEach(para => {
            if (para.trim()) {
                // Convert newlines to <br> and escape HTML
                const lines = para.trim().split('\n');
                const escapedLines = lines.map(line => escapeHtml(line)).join('<br>');
                html += `<div style="margin-bottom: 1rem; line-height: 1.6;">${escapedLines}</div>`;
            }
        });

        preview.innerHTML = html || '<p style="color: var(--text-muted);">No content</p>';
    }

    /**
     * Reset and regenerate
     */
    function resetCoverLetter() {
        generateCoverLetter();
    }

    /**
     * Apply edits and update preview
     */
    function applyEdits() {
        const editedContent = document.getElementById('coverLetterEdit').value;

        if (!editedContent.trim()) {
            alert('Cover letter cannot be empty');
            return;
        }

        currentCoverLetter = editedContent;
        updatePreview(editedContent);

        // Switch to preview tab
        switchTab('preview');

        // Show confirmation
        const actionBtn = document.getElementById('resumeActionBtn');
        if (actionBtn) {
            const originalText = actionBtn.textContent;
            actionBtn.textContent = '✓ Changes saved!';
            setTimeout(() => {
                actionBtn.textContent = originalText;
            }, 2000);
        }
    }

    /**
     * Download as PDF
     */
    function downloadAsPdf() {
        // Create a temporary div with the cover letter content for PDF generation
        const tempDiv = document.createElement('div');
        tempDiv.style.display = 'none';
        tempDiv.style.backgroundColor = 'white';
        tempDiv.style.color = '#333';
        tempDiv.style.padding = '2rem';
        tempDiv.style.fontFamily = 'Arial, sans-serif';
        tempDiv.style.fontSize = '12px';
        tempDiv.style.lineHeight = '1.6';

        // Convert newlines to paragraphs for better PDF formatting
        const paragraphs = currentCoverLetter.split('\n\n');
        let html = '';
        paragraphs.forEach(para => {
            if (para.trim()) {
                html += `<p style="margin: 12px 0;">${para.trim().replace(/\n/g, '<br>')}</p>`;
            }
        });

        tempDiv.innerHTML = html;
        document.body.appendChild(tempDiv);

        // Generate PDF using html2pdf library
        const element = tempDiv;
        const opt = {
            margin: 10,
            filename: `Cover_Letter_${jobTitle.replace(/\s+/g, '_')}.pdf`,
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4'
            }
        };

        // Check if html2pdf is loaded
        if (typeof html2pdf === 'function') {
            html2pdf().set(opt).from(element).save();
        } else {
            // Fallback: Download as text if html2pdf is not available
            const file = new Blob([currentCoverLetter], {
                type: 'text/plain'
            });
            const elementLink = document.createElement('a');
            elementLink.href = URL.createObjectURL(file);
            elementLink.download = `Cover_Letter_${jobTitle.replace(/\s+/g, '_')}.txt`;
            document.body.appendChild(elementLink);
            elementLink.click();
            document.body.removeChild(elementLink);
        }

        document.body.removeChild(tempDiv);
    }

    /**
     * Finalize cover letter
     */
    function finalizeCoverLetter() {
        if (!currentCoverLetter) {
            alert('Please generate a cover letter first');
            return;
        }

        let finalContent = document.getElementById('coverLetterEdit').value || currentCoverLetter;

        // Normalize line endings
        finalContent = finalContent.replace(/\r\n/g, '\n');

        // Show loading
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Finalizing...';

        fetch('<?php echo e(route("placement.covers.finalize")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
                body: JSON.stringify({
                    cover_letter_content: finalContent,
                    job_title: jobTitle,
                    company_name: companyName,
                }),
            })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 429) {
                        throw new Error('limit_exceeded');
                    }
                    // Try to get error message from response
                    return response.json().then(data => {
                        throw new Error(data.error || 'Finalization failed');
                    });
                }
                return response.json();
            })
            .then(data => {
                // Restore button
                btn.disabled = false;
                btn.innerHTML = originalText;

                // If download URL is available, trigger download
                if (data.downloadUrl) {
                    // Create a temporary link and click it to trigger download
                    const downloadLink = document.createElement('a');
                    downloadLink.href = data.downloadUrl;
                    downloadLink.download = true;
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    document.body.removeChild(downloadLink);
                }

                // Show success modal
                const counterText = document.getElementById('sucessCounterText');
                counterText.textContent = `Covers used: ${data.coversUsed}`;
                const modal = new bootstrap.Modal(document.getElementById('successModal'));
                modal.show();
            })
            .catch(error => {
                // Restore button
                btn.disabled = false;
                btn.innerHTML = originalText;

                console.error('Finalize Error:', error);
                if (error.message === 'limit_exceeded') {
                    const modal = new bootstrap.Modal(document.getElementById('limitExceededModal'));
                    modal.show();
                } else {
                    alert('Error finalizing cover letter: ' + error.message + '. Please try again.');
                }
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
    }

    /**
     * Setup character counter
     */
    function setupCharCounter() {
        const textarea = document.getElementById('aiPrompt');
        if (textarea) {
            textarea.addEventListener('input', function() {
                document.getElementById('charCount').textContent = this.value.length;
            });
        }
    }

    /**
     * Escape HTML
     */
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;',
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('front.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Herd-Projects\hired-hero\resources\views/placement/cover-letter/generate.blade.php ENDPATH**/ ?>