@extends('front.layouts.app')
@section('title', 'Job Placement Wizard')

@section('css')
<style>
    .wizard-container {
        max-width: 700px;
        margin: 0 auto;
        padding: 3rem 0;
    }

    .progress-bar {
        height: 6px;
        background: #e9ecef;
        border-radius: 10px;
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #00A3FF 0%, #00D4A8 100%);
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    .step-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-top: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .btn-next {
        background: linear-gradient(90deg, #00A3FF 0%, #00D4A8 100%);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .btn-next:hover {
        transform: translateY(-2px);
    }

    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        gap: 1rem;
    }

    .step-dot {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
        color: #666;
    }

    .step-dot.active {
        background: #00A3FF;
        color: white;
    }

    .step-dot.completed {
        background: #00D4A8;
        color: white;
    }

    /* Loading Modal Styles */
    .page-loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    .page-loader-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }

    .loader-content {
        text-align: center;
    }

    .loader-spinner {
        width: 60px;
        height: 60px;
        margin: 0 auto 2rem;
        border: 4px solid #f0f0f0;
        border-top: 4px solid #00A3FF;
        border-right: 4px solid #00D4A8;
        border-radius: 50%;
        animation: spinLoader 1s linear infinite;
    }

    @keyframes spinLoader {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    .loader-text {
        font-size: 1.1rem;
        color: #333;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .loader-subtext {
        font-size: 0.9rem;
        color: #666;
    }

    .loader-progress {
        width: 200px;
        height: 3px;
        background: #e9ecef;
        border-radius: 2px;
        margin: 1.5rem auto 0;
        overflow: hidden;
    }

    .loader-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #00A3FF 0%, #00D4A8 100%);
        border-radius: 2px;
        animation: loadingBar 1.5s ease-in-out infinite;
    }

    @keyframes loadingBar {
        0% {
            width: 0%;
        }
        50% {
            width: 80%;
        }
        100% {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<!-- Page Loading Overlay -->
<div class="page-loader-overlay" id="pageLoaderOverlay">
    <div class="loader-content">
        <div class="loader-spinner"></div>
        <div class="loader-text">Loading...</div>
        <div class="loader-subtext">Please wait while we process your information</div>
        <div class="loader-progress">
            <div class="loader-progress-bar"></div>
        </div>
    </div>
</div>

<section class="section page__content">
    <div class="container">
        <div class="wizard-container">
            <!-- Progress Bar -->
            @if(isset($step))
            <div class="progress-bar">
                <div class="progress-bar-fill" style="width: {{ $progressPercentage ?? ($step / 8 * 100) }}%"></div>
            </div>

            <!-- Step Indicators -->
            <div class="step-indicator">
                @php
                    $stepTitles = [
                        1 => 'Job Type',
                        2 => 'Location',
                        3 => 'Industries',
                        4 => 'Job Level',
                        5 => 'Language',
                        6 => 'Resume',
                        7 => 'Skills',
                        8 => 'Roles'
                    ];
                @endphp
                @for ($i = 1; $i <= 8; $i++)
                    <div class="step-dot {{ $i === $step ? 'active' : ($i < $step ? 'completed' : '') }}" 
                         title="{{ $stepTitles[$i] ?? 'Step ' . $i }}">
                        {{ $i }}
                    </div>
                @endfor
            </div>
            @endif

            <!-- Step Content -->
            <div class="step-card">
                @yield('step-content')
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
    // Page Loading Overlay
    const pageLoaderOverlay = document.getElementById('pageLoaderOverlay');

    function showLoadingOverlay() {
        pageLoaderOverlay.classList.add('active');
    }

    function hideLoadingOverlay() {
        pageLoaderOverlay.classList.remove('active');
    }

    // Show loading overlay on form submission (except step 8)
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            // Skip loading overlay for step 8 (task has custom handling)
            if (form.dataset.step === '8') {
                return;
            }
            
            showLoadingOverlay();
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = '{{ __("placement.loading") }}...';
            }
        });
    });

    // Hide loading overlay when page is fully loaded
    window.addEventListener('load', function() {
        // Add a small delay to ensure smooth transition
        setTimeout(hideLoadingOverlay, 500);
    });

    // Also hide if page is already loaded (for cached pages)
    if (document.readyState === 'complete') {
        hideLoadingOverlay();
    }
</script>
@endsection
