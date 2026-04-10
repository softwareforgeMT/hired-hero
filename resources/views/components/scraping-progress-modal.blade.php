<div id="scrapingProgressModal" class="scraping-progress-modal" style="display: none;">
    <div class="scraping-progress-container">
        <div class="scraping-progress-header">
            <div class="progress-title">
                <i class="ri-checkbox-blank-circle-line progress-spinner"></i>
                <span id="progressTitle">Searching Jobs</span>
            </div>
            <button type="button" class="btn-close-progress" id="closeProgressModal" style="display: none;"></button>
        </div>

        <div class="scraping-progress-body">
            <p id="progressMessage" class="progress-message">Initializing job scraper...</p>

            <div class="progress-bar-container">
                <div class="progress-bar">
                    <div id="progressBarFill" class="progress-bar-fill" style="width: 0%"></div>
                </div>
                <span id="progressPercent" class="progress-percent">0%</span>
            </div>

            <div id="progressDetails" class="progress-details">
                <p><i class="ri-checkbox-blank-circle-line"></i> <span id="jobsFound">0</span> jobs found</p>
            </div>
        </div>

        <div class="scraping-progress-footer" id="progressFooter" style="display: none;">
            <button type="button" class="btn btn-primary btn-sm" id="closeModal">
                <i class="ri-check-line"></i> Close
            </button>
        </div>
    </div>
</div>

<style>
    .scraping-progress-modal {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        animation: slideInRight 0.3s ease-out;
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }

    .scraping-progress-modal.hiding {
        animation: slideOutRight 0.3s ease-out forwards;
    }

    .scraping-progress-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        padding: 20px;
        width: 350px;
        max-width: 90vw;
    }

    .scraping-progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .progress-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .progress-spinner {
        font-size: 20px;
        animation: spin 2s linear infinite;
        color: #3b82f6;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    .progress-title.completed .progress-spinner {
        animation: none;
        color: #10b981;
    }

    .progress-title.completed::before {
        content: '\ea10';
        font-family: 'Remix Icon';
        font-size: 20px;
        color: #10b981;
        margin-right: -20px;
        margin-left: -20px;
    }

    .btn-close-progress {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 20px;
        color: #9ca3af;
        padding: 0;
    }

    .btn-close-progress:hover {
        color: #6b7280;
    }

    .scraping-progress-body {
        margin-bottom: 16px;
    }

    .progress-message {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 12px;
        line-height: 1.5;
    }

    .progress-bar-container {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .progress-bar {
        flex: 1;
        height: 6px;
        background: #e5e7eb;
        border-radius: 3px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6, #2563eb);
        border-radius: 3px;
        transition: width 0.3s ease;
    }

    .progress-percent {
        font-size: 12px;
        font-weight: 600;
        color: #3b82f6;
        min-width: 35px;
        text-align: right;
    }

    .progress-details {
        font-size: 13px;
        color: #6b7280;
        line-height: 1.6;
    }

    .progress-details p {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .progress-details i {
        color: #9ca3af;
        font-size: 14px;
    }

    .scraping-progress-footer {
        text-align: center;
        border-top: 1px solid #e5e7eb;
        padding-top: 12px;
    }

    .scraping-progress-footer .btn {
        width: 100%;
    }

    /* Error state */
    .scraping-progress-modal.error .progress-title {
        color: #dc2626;
    }

    .scraping-progress-modal.error .progress-spinner {
        animation: none;
        color: #dc2626;
    }

    .scraping-progress-modal.error .progress-bar-fill {
        background: #dc2626;
    }

    .scraping-progress-modal.error .progress-percent {
        color: #dc2626;
    }

    /* Completed state */
    .scraping-progress-modal.completed .progress-title {
        color: #10b981;
    }

    .scraping-progress-modal.completed .progress-spinner {
        animation: none;
        color: #10b981;
    }

    .scraping-progress-modal.completed .progress-bar-fill {
        background: #10b981;
    }

    .scraping-progress-modal.completed .progress-percent {
        color: #10b981;
    }

    @media (max-width: 576px) {
        .scraping-progress-modal {
            bottom: 10px;
            right: 10px;
        }

        .scraping-progress-container {
            width: calc(100vw - 20px);
            padding: 16px;
        }

        .progress-title {
            font-size: 14px;
        }

        .progress-message {
            font-size: 12px;
        }
    }
</style>

<script>
    class ScrapingProgressManager {
        constructor() {
            this.modal = document.getElementById('scrapingProgressModal');
            this.progressBarFill = document.getElementById('progressBarFill');
            this.progressPercent = document.getElementById('progressPercent');
            this.progressMessage = document.getElementById('progressMessage');
            this.progressTitle = document.getElementById('progressTitle');
            this.jobsFound = document.getElementById('jobsFound');
            this.progressFooter = document.getElementById('progressFooter');
            this.closeBtn = document.getElementById('closeModal');
            this.closeBtnHeader = document.getElementById('closeProgressModal');
            this.pollingInterval = null;
            this.maxAttempts = 0;
            this.currentAttempts = 0;

            this.setupEventListeners();
        }

        setupEventListeners() {
            if (this.closeBtn) {
                this.closeBtn.addEventListener('click', () => this.hide());
            }
            if (this.closeBtnHeader) {
                this.closeBtnHeader.addEventListener('click', () => this.hide());
            }
        }

        show() {
            if (this.modal) {
                this.modal.style.display = 'block';
                this.modal.classList.remove('error', 'completed', 'hiding');
            }
        }

        hide() {
            if (this.modal) {
                this.modal.classList.add('hiding');
                setTimeout(() => {
                    this.modal.style.display = 'none';
                    this.modal.classList.remove('hiding');
                }, 300);
            }
            this.stopPolling();
        }

        updateProgress(data) {
            if (!data) return;

            const progress = data.progress || 0;
            const message = data.message || 'Processing...';
            const status = data.status || 'processing';
            const totalJobs = data.total_jobs || 0;

            // Update progress bar
            if (this.progressBarFill) {
                this.progressBarFill.style.width = progress + '%';
            }
            if (this.progressPercent) {
                this.progressPercent.textContent = progress + '%';
            }

            // Update message
            if (this.progressMessage) {
                this.progressMessage.textContent = message;
            }

            // Update jobs found
            if (this.jobsFound) {
                this.jobsFound.textContent = totalJobs;
            }

            // Handle completed state
            if (status === 'completed') {
                this.setCompleted(totalJobs);
            }

            // Handle failed state
            if (status === 'failed') {
                this.setError(message);
            }
        }

        setCompleted(totalJobs) {
            if (this.modal) {
                this.modal.classList.remove('error');
                this.modal.classList.add('completed');
            }
            if (this.progressTitle) {
                this.progressTitle.textContent = 'Jobs Found! Ready to Preview';
            }
            if (this.progressPercent) {
                this.progressPercent.textContent = '100%';
            }
            if (this.progressBarFill) {
                this.progressBarFill.style.width = '100%';
            }
            if (this.progressFooter) {
                this.progressFooter.style.display = 'block';
            }
            if (this.closeBtnHeader) {
                this.closeBtnHeader.style.display = 'block';
            }
            this.stopPolling();
        }

        setError(message) {
            if (this.modal) {
                this.modal.classList.add('error');
                this.modal.classList.remove('completed');
            }
            if (this.progressTitle) {
                this.progressTitle.textContent = 'Scraping Failed';
            }
            if (this.progressMessage) {
                this.progressMessage.textContent = message || 'An error occurred during job scraping';
            }
            if (this.progressFooter) {
                this.progressFooter.style.display = 'block';
            }
            if (this.closeBtnHeader) {
                this.closeBtnHeader.style.display = 'block';
            }
            this.stopPolling();
        }

        startPolling(interval = 2000, maxAttempts = 1800) {
            this.maxAttempts = maxAttempts;
            this.currentAttempts = 0;
            this.pollProgress();
            this.pollingInterval = setInterval(() => this.pollProgress(), interval);
        }

        async pollProgress() {
            try {
                this.currentAttempts++;

                if (this.currentAttempts > this.maxAttempts) {
                    this.setError('Job scraping timed out. Please try again.');
                    return;
                }

                const response = await fetch('/api/scraping/progress', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                this.updateProgress(data);

            } catch (error) {
                console.error('Error polling progress:', error);
                // Continue polling even on error
            }
        }

        stopPolling() {
            if (this.pollingInterval) {
                clearInterval(this.pollingInterval);
                this.pollingInterval = null;
            }
        }
    }

    // Export for use in views
    window.scrapingProgressManager = new ScrapingProgressManager();
</script>
