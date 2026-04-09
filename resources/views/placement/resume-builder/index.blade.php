@extends('placement.resume-builder.layout')

@section('resume-content')
<div class="resume-builder-container max-w-4xl">
    <div class="builder-header-section">
        <div class="header-content">
            <div class="header-badge">
                <i class="ri-sparkle-line"></i> Premium Feature
            </div>
            <h2 class="step-title">AI-Powered Resume Builder</h2>
            <p class="step-description">Create a professional, ATS-optimized resume in minutes with our intelligent builder powered by AI.</p>
            <div class="header-stats">
                <div class="stat-item">
                    <i class="ri-file-check-line"></i>
                    <span><strong>5+</strong> Templates</span>
                </div>
                <div class="stat-item">
                    <i class="ri-ai-generate"></i>
                    <span><strong>AI</strong> Powered</span>
                </div>
                <div class="stat-item">
                    <i class="ri-shield-check-line"></i>
                    <span><strong>ATS</strong> Optimized</span>
                </div>
            </div>
        </div>
    </div>

    @if(!$hasActiveSubscription)
        <!-- Promo Code Section -->
        <div class="promo-code-section hidden">
            <div class="promo-card">
                <div class="promo-content">
                    <div class="promo-text-section">
                        <div class="promo-icon-wrapper">
                            <i class="ri-ticket-2-line"></i>
                        </div>
                        <div>
                            <h4 class="promo-title">Have a Discount Code?</h4>
                            <p class="promo-subtitle">Enter your promo code to get an exclusive discount</p>
                        </div>
                    </div>
                    <div class="promo-form-section">
                        <div class="promo-input-wrapper">
                            <input 
                                type="text" 
                                id="promoCodeInput" 
                                class="form-control promo-input" 
                                placeholder="Enter promo code"
                                maxlength="50"
                                autocomplete="off"
                            >
                            <button 
                                type="button" 
                                class="btn btn-promo-validate" 
                                id="validatePromoBtn"
                            >
                                <i class="ri-check-line"></i> Validate Code
                            </button>
                        </div>
                        <div id="promoMessage" class="promo-message mt-3"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing Plans -->
        <div class="pricing-section">
            <div class="pricing-header-section">
                <h3 class="pricing-main-title">Choose Your Plan</h3>
                <p class="pricing-main-subtitle">Get instant access to premium resume builder tools</p>
            </div>
            
            <div class="row g-4">
                @forelse($plans ?? [] as $index => $plan)
                    @if($plan->price > 0)
                    <div class="col-lg-6">
                        <div class="pricing-card {{ $index === 1 ? 'featured' : '' }}">
                            @if($index === 1)
                                <div class="pricing-badge">
                                    <span class="badge-icon">⭐</span> BEST VALUE
                                </div>
                            @endif
                            
                            <div class="pricing-card-header">
                                <div class="pricing-icon">
                                    <i class="{{ $index === 0 ? 'ri-calendar-line' : 'ri-calendar-2-line' }}"></i>
                                </div>
                                <div>
                                    <h3 class="pricing-title">{{ $plan->name }}</h3>
                                    <p class="pricing-subtitle">{{ $plan->description }}</p>
                                </div>
                            </div>
                            
                            <div class="pricing-cost-section">
                                <div class="price-display">
                                    <span class="currency">$</span>
                                    <span class="price plan-price" id="price-{{ $plan->slug }}" data-original-price="{{ $plan->price }}">{{ number_format($plan->price, 2) }}</span>
                                    <span class="period">{{ $plan->interval === 'weekly' ? '/week' : ($plan->interval === 'monthly' ? '/month' : '/' . $plan->interval) }}</span>
                                </div>
                                <div id="discount-{{ $plan->slug }}" class="discount-badge" style="display: none;">
                                    <i class="ri-gift-line"></i>
                                    <span id="discount-text-{{ $plan->slug }}"></span>
                                </div>
                            </div>

                            <ul class="pricing-features {{ $index === 1 ? 'premium' : '' }}">
                                <li><i class="ri-check-double-line"></i> <span>5 Professional Templates</span></li>
                                <li><i class="ri-check-double-line"></i> <span>ATS Optimized</span></li>
                                <li><i class="ri-check-double-line"></i> <span>AI-Powered Suggestions</span></li>
                                <li><i class="ri-check-double-line"></i> <span>Download as PDF</span></li>
                                <li><i class="ri-check-double-line"></i> <span>Unlimited Edits</span></li>
                                <li><i class="ri-check-double-line"></i> <span>
                                    @if($plan->interval === 'weekly')
                                        7 Days Access
                                    @elseif($plan->interval === 'monthly')
                                        30 Days Access
                                    @else
                                        Full Access
                                    @endif
                                </span></li>
                            </ul>

                            <form action="{{ route('stripe.process', $plan->slug) }}" method="GET" class="checkout-form">
                                @csrf
                                <input type="hidden" name="source" value="resume-builder">
                                <input type="hidden" name="promo_code" id="promo-code-{{ $plan->slug }}" value="">
                                <button type="submit" class="btn btn-checkout {{ $index === 1 ? 'btn-checkout-primary' : '' }} w-100">
                                    <i class="ri-secure-payment-line"></i>
                                    Get {{ $plan->name }}
                                    <i class="ri-arrow-right-line"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            <i class="ri-alert-line"></i> No plans available at the moment. Please try again later.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Features Section -->
        <section class="features-section">
            <div class="features-header">
                <h3 class="features-title">Why Our Resume Builder?</h3>
                <p class="features-subtitle">Packed with powerful features to create resumes that get noticed</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon ai-icon">
                            <i class="ri-lightbulb-flash-line"></i>
                        </div>
                        <h5>AI-Powered</h5>
                        <p>Get intelligent suggestions powered by AI to enhance your resume content and formatting instantly</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon ats-icon">
                            <i class="ri-shield-check-line"></i>
                        </div>
                        <h5>ATS Optimized</h5>
                        <p>Designed to pass applicant tracking systems so your resume gets in front of hiring managers</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon design-icon">
                            <i class="ri-palette-line"></i>
                        </div>
                        <h5>Beautiful Templates</h5>
                        <p>Choose from 5 professionally designed, modern templates that stand out to recruiters</p>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon download-icon">
                            <i class="ri-download-line"></i>
                        </div>
                        <h5>Export PDF</h5>
                        <p>Download your resume as a professional PDF file ready to send to employers</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon edit-icon">
                            <i class="ri-edit-line"></i>
                        </div>
                        <h5>Unlimited Edits</h5>
                        <p>Update your resume whenever you want without any limitations during your subscription</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon access-icon">
                            <i class="ri-time-line"></i>
                        </div>
                        <h5>Full Access</h5>
                        <p>Complete access to all features and templates throughout your entire subscription period</p>
                    </div>
                </div>
            </div>
        </section>
    @else
        <!-- User Has Active Subscription -->
        <div class="subscription-active-section">
            <div class="alert alert-success" role="alert">
                <i class="ri-checkbox-circle-line"></i>
                <strong>Subscription Active!</strong> Your Resume Builder subscription is active. Let's create your resume!
            </div>

            @php
                $subscription = \App\Services\Placement\StripePaymentService::class;
            @endphp

            <div class="resume-builder-cta mt-5">
                <div class="cta-card">
                    <h3>Ready to Build Your Resume?</h3>
                    <p>You have access to our premium Resume Builder with 5 professional templates and AI-powered suggestions.</p>
                    
                    <a href="{{ route('resume-builder.form') }}" class="btn btn-primary btn-lg mt-4">
                        <i class="ri-file-text-line"></i>
                        Start Building Your Resume
                    </a>
                </div>
            </div>
        </div>
    @endif

    <div class="navigation-footer">
        <div class="nav-content">
            <div class="nav-label">
                <i class="ri-checkbox-circle-line"></i>
                <span>Step 7 - Resume Builder</span>
            </div>
            <div class="nav-actions">
                <a href="{{ route('placement.wizard.step', ['step' => 6]) }}" class="btn btn-outline-secondary">
                    <i class="ri-arrow-left-line"></i> Back to Step 6
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const promoInput = document.getElementById('promoCodeInput');
    const validateBtn = document.getElementById('validatePromoBtn');
    const promoMessage = document.getElementById('promoMessage');
    
    const weeklyForm = document.querySelector('input[name="plan"][value="weekly"]').closest('.checkout-form');
    const monthlyForm = document.querySelector('input[name="plan"][value="monthly"]').closest('.checkout-form');
    
    let validatedPromo = null;

    // Validate when button is clicked
    validateBtn.addEventListener('click', function() {
        validatePromoCode();
    });

    // Validate when Enter is pressed
    promoInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            validatePromoCode();
        }
    });

    function validatePromoCode() {
        const code = promoInput.value.trim();

        if (!code) {
            showMessage('Please enter a promo code', 'error');
            return;
        }

        // Disable button during validation
        validateBtn.disabled = true;
        validateBtn.innerHTML = '<i class="ri-loader-4-line"></i> Validating...';

        // Get first plan slug for validation
        const firstPlanInput = document.querySelector('input[name="plan"]');
        const planSlug = firstPlanInput ? firstPlanInput.value : 'weekly';
        
        validateForAllPlans(code, planSlug);
    }

    function validateForAllPlans(code, primaryPlan) {
        fetch('{{ route("validate-promo-code") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                promo_code: code,
                plan: primaryPlan,
            }),
        })
        .then(response => response.json())
        .then(data => {
            validateBtn.disabled = false;
            validateBtn.innerHTML = '<i class="ri-check-line"></i> Validate';

            if (data.success) {
                validatedPromo = {
                    code: data.code,
                    discountPercentage: data.discount_percentage,
                    plans: {}
                };

                // Get all plan prices from DOM and calculate discounts
                document.querySelectorAll('[id^="price-"]').forEach(priceElement => {
                    const planSlug = priceElement.id.replace('price-', '');
                    const originalPrice = parseFloat(priceElement.getAttribute('data-original-price'));
                    const discountAmount = (originalPrice * data.discount_percentage / 100);
                    const finalPrice = Math.max(0, originalPrice - discountAmount);
                    
                    validatedPromo.plans[planSlug] = {
                        originalPrice: originalPrice,
                        discountAmount: discountAmount,
                        finalPrice: finalPrice
                    };
                });

                updatePricingDisplay();
                showMessage(`✓ Promo code "${data.code}" applied! Save ${data.discount_percentage}% on your purchase.`, 'success');
                
                // Set promo code in all hidden inputs
                document.querySelectorAll('[id^="promo-code-"]').forEach(input => {
                    input.value = code;
                });
            } else {
                validatedPromo = null;
                resetPricingDisplay();
                
                showMessage(`✗ ${data.message}`, 'error');

                // Clear promo code from all hidden inputs
                document.querySelectorAll('[id^="promo-code-"]').forEach(input => {
                    input.value = '';
                });
            }
        })
        .catch(error => {
            validateBtn.disabled = false;
            validateBtn.innerHTML = '<i class="ri-check-line"></i> Validate';
            console.error('Error:', error);
            showMessage('An error occurred while validating the promo code. Please try again.', 'error');
        });
    }

    function updatePricingDisplay() {
        if (!validatedPromo || !validatedPromo.plans) return;

        // Update all plan prices dynamically
        Object.keys(validatedPromo.plans).forEach(planSlug => {
            const priceElement = document.getElementById(`price-${planSlug}`);
            const discountElement = document.getElementById(`discount-${planSlug}`);
            const discountTextElement = document.getElementById(`discount-text-${planSlug}`);
            
            if (priceElement && discountElement && discountTextElement) {
                const plan = validatedPromo.plans[planSlug];
                const finalPrice = plan.finalPrice.toFixed(2);
                
                priceElement.textContent = finalPrice;
                discountElement.style.display = 'block';
                discountTextElement.textContent = `You save $${plan.discountAmount.toFixed(2)} (${validatedPromo.discountPercentage}% off)`;
            }
        });
    }

    function resetPricingDisplay() {
        // Reset all plan prices to original
        document.querySelectorAll('[id^="price-"]').forEach(priceElement => {
            const originalPrice = parseFloat(priceElement.getAttribute('data-original-price'));
            priceElement.textContent = originalPrice.toFixed(2);
        });
        
        // Hide all discount badges
        document.querySelectorAll('[id^="discount-"]').forEach(discountElement => {
            discountElement.style.display = 'none';
        });
    }

    function showMessage(message, type) {
        promoMessage.textContent = message;
        promoMessage.className = 'promo-message ' + type;
        promoMessage.style.display = 'block';

        // Auto-hide success message after 5 seconds
        if (type === 'success') {
            setTimeout(() => {
                promoMessage.style.display = 'none';
            }, 5000);
        }
    }

    // Clear promo code on input change
    promoInput.addEventListener('input', function() {
        if (promoInput.value === '') {
            validatedPromo = null;
            resetPricingDisplay();
            promoMessage.style.display = 'none';
            document.querySelectorAll('[id^="promo-code-"]').forEach(input => {
                input.value = '';
            });
        }
    });
});
</script>

<style>
    :root {
        --primary-color: #3b82f6;
        --primary-dark: #2563eb;
        --primary-light: #eff6ff;
        --success-color: #10b981;
        --success-light: #d1fae5;
        --danger-color: #ef4444;
        --danger-light: #fee2e2;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-900: #1a1a1a;
        --radius-sm: 0.375rem;
        --radius-md: 0.5rem;
        --radius-lg: 0.75rem;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .resume-builder-container {
        padding: 3rem 0;
    }

    /* Header Section */
    .builder-header-section {
        background: linear-gradient(135deg, #f0f9ff 0%, #eff6ff 100%);
        border: 2px solid var(--primary-light);
        border-radius: var(--radius-lg);
        padding: 3.5rem 2.5rem;
        margin-bottom: 3rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .builder-header-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.08) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .header-content {
        position: relative;
        z-index: 1;
    }

    .header-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--primary-color);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .header-badge i {
        font-size: 1rem;
    }

    .step-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--gray-900);
        line-height: 1.2;
        margin-bottom: 1rem;
        letter-spacing: -0.02em;
    }

    .step-description {
        font-size: 1.1rem;
        color: var(--gray-600);
        line-height: 1.7;
        margin-bottom: 2rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .header-stats {
        display: flex;
        justify-content: center;
        gap: 3rem;
        flex-wrap: wrap;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: var(--gray-600);
        font-size: 0.95rem;
    }

    .stat-item i {
        font-size: 1.5rem;
        color: var(--primary-color);
    }

    .stat-item strong {
        color: var(--gray-900);
        font-weight: 700;
    }

    /* Promo Code Section */
    .promo-code-section {
        margin-bottom: 3.5rem;
    }

    .promo-card {
        background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%);
        border: 2px solid var(--primary-color);
        border-radius: var(--radius-lg);
        padding: 2rem;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        transition: all 0.3s ease;
    }

    .promo-card:hover {
        box-shadow: 0 12px 24px rgba(59, 130, 246, 0.2);
        transform: translateY(-2px);
    }

    .promo-content {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .promo-text-section {
        display: flex;
        gap: 1rem;
    }

    .promo-icon-wrapper {
        display: flex;
        align-items: flex-start;
    }

    .promo-icon-wrapper i {
        font-size: 1.75rem;
        color: var(--primary-color);
        min-width: 40px;
    }

    .promo-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0 0 0.25rem 0;
    }

    .promo-subtitle {
        color: var(--gray-600);
        font-size: 0.95rem;
        margin: 0;
    }

    .promo-form-section {
        max-width: 100%;
    }

    .promo-input-wrapper {
        display: flex;
        gap: 0.75rem;
    }

    .promo-input {
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 500;
        border: 2px solid var(--gray-200);
        transition: all 0.3s ease;
        flex: 1;
        min-width: 0;
    }

    .promo-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn-promo-validate {
        background: var(--primary-color);
        color: white;
        border: none;
        font-weight: 600;
        white-space: nowrap;
        transition: all 0.3s ease;
        padding: 0.625rem 1.5rem;
    }

    .btn-promo-validate:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-promo-validate:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .promo-message {
        font-size: 0.95rem;
        padding: 1rem;
        border-radius: var(--radius-md);
        display: none;
        font-weight: 500;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .promo-message.success {
        display: block;
        background: var(--success-light);
        color: #065f46;
        border: 1px solid var(--success-color);
    }

    .promo-message.error {
        display: block;
        background: var(--danger-light);
        color: #7f1d1d;
        border: 1px solid var(--danger-color);
    }

    /* Pricing Section */
    .pricing-section {
        margin: 3.5rem 0;
    }

    .pricing-header-section {
        text-align: center;
        margin-bottom: 3rem;
    }

    .pricing-main-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--gray-900);
        margin-bottom: 0.75rem;
        letter-spacing: -0.01em;
    }

    .pricing-main-subtitle {
        font-size: 1.1rem;
        color: var(--gray-600);
        margin-bottom: 0;
    }

    .pricing-card {
        background: white;
        border: 2px solid var(--gray-200);
        border-radius: var(--radius-lg);
        padding: 2rem;
        height: 100%;
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .pricing-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 20px 40px rgba(59, 130, 246, 0.15);
        transform: translateY(-8px);
    }

    .pricing-card.featured {
        border: 2px solid var(--primary-color);
        background: linear-gradient(135deg, var(--primary-light) 0%, #ffffff 100%);
        transform: scale(1.02);
    }

    .pricing-card.featured:hover {
        transform: scale(1.02) translateY(-8px);
    }

    .pricing-badge {
        position: absolute;
        top: -15px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 0.5rem 1.25rem;
        border-radius: 2rem;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .badge-icon {
        margin-right: 0.5rem;
        font-size: 1rem;
    }

    .pricing-card-header {
        display: flex;
        gap: 1.25rem;
        margin-bottom: 1.75rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--gray-100);
    }

    .pricing-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-light) 0%, #f0f9ff 100%);
        border-radius: var(--radius-md);
        flex-shrink: 0;
    }

    .pricing-icon i {
        font-size: 1.75rem;
        color: var(--primary-color);
    }

    .pricing-card-header > div:last-child {
        flex: 1;
    }

    .pricing-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0 0 0.25rem 0;
    }

    .pricing-subtitle {
        font-size: 0.95rem;
        color: var(--gray-600);
        margin: 0;
    }

    .pricing-cost-section {
        margin-bottom: 1.75rem;
    }

    .price-display {
        display: flex;
        align-items: baseline;
        justify-content: center;
        gap: 0.25rem;
        margin-bottom: 1rem;
    }

    .currency {
        font-size: 1.25rem;
        color: var(--gray-600);
        font-weight: 600;
    }

    .price {
        font-size: 3rem;
        font-weight: 800;
        color: var(--gray-900);
        line-height: 1;
    }

    .period {
        font-size: 1rem;
        color: var(--gray-600);
        font-weight: 600;
    }

    .discount-badge {
        background: linear-gradient(135deg, var(--success-light) 0%, #ecfdf5 100%);
        border: 1px solid var(--success-color);
        padding: 0.75rem 1rem;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #065f46;
        font-weight: 600;
        font-size: 0.95rem;
        animation: slideDown 0.3s ease;
    }

    .discount-badge i {
        color: var(--success-color);
        font-size: 1.1rem;
    }

    .pricing-features {
        list-style: none;
        padding: 0;
        margin: 0 0 1.75rem 0;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .pricing-features li {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.875rem 0;
        color: var(--gray-700);
        font-size: 0.95rem;
        border-bottom: 1px solid var(--gray-100);
        line-height: 1.5;
    }

    .pricing-features li:last-child {
        border-bottom: none;
    }

    .pricing-features i {
        color: var(--primary-color);
        font-size: 1.25rem;
        flex-shrink: 0;
        margin-top: 0.125rem;
    }

    .pricing-features.premium li {
        color: var(--gray-600);
        font-weight: 500;
    }

    .checkout-form {
        margin-top: auto;
    }

    .btn-checkout {
        background: var(--gray-200);
        color: var(--gray-900);
        border: none;
        font-weight: 700;
        padding: 0.875rem 1.5rem;
        border-radius: var(--radius-md);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        font-size: 1rem;
        cursor: pointer;
    }

    .btn-checkout:hover {
        background: var(--gray-300);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-checkout-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        font-weight: 700;
    }

    .btn-checkout-primary:hover {
        background: linear-gradient(135deg, var(--primary-dark) 0%, #1d4ed8 100%);
        box-shadow: 0 20px 40px rgba(59, 130, 246, 0.3);
    }

    .btn-checkout:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* Features Section */
    .features-section {
        background: linear-gradient(135deg, var(--gray-50) 0%, #ffffff 100%);
        border: 1px solid var(--gray-200);
        padding: 3rem 2.5rem;
        border-radius: var(--radius-lg);
        margin: 4rem 0;
    }

    .features-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .features-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--gray-900);
        margin-bottom: 0.75rem;
        letter-spacing: -0.01em;
    }

    .features-subtitle {
        font-size: 1.1rem;
        color: var(--gray-600);
        margin: 0;
    }

    .feature-card {
        background: white;
        padding: 2rem;
        border-radius: var(--radius-lg);
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid var(--gray-100);
        height: 100%;
    }

    .feature-card:hover {
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        transform: translateY(-8px);
        border-color: var(--gray-200);
    }

    .feature-icon {
        width: 70px;
        height: 70px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        color: white;
        transition: all 0.3s ease;
    }

    .feature-icon.ai-icon {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

    .feature-icon.ats-icon {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .feature-icon.design-icon {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .feature-icon.download-icon {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }

    .feature-icon.edit-icon {
        background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
    }

    .feature-icon.access-icon {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    }

    .feature-card h5 {
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 0.75rem;
        font-size: 1.1rem;
    }

    .feature-card p {
        color: var(--gray-600);
        font-size: 0.95rem;
        line-height: 1.6;
        margin: 0;
    }

    /* Navigation Footer */
    .navigation-footer {
        margin-top: 4rem;
        padding-top: 2rem;
        border-top: 2px solid var(--gray-200);
    }

    .nav-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
    }

    .nav-label {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: var(--gray-600);
        font-weight: 600;
        font-size: 0.95rem;
    }

    .nav-label i {
        color: var(--primary-color);
        font-size: 1.25rem;
    }

    .nav-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-outline-secondary {
        background: transparent;
        color: var(--gray-700);
        border: 2px solid var(--gray-300);
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-outline-secondary:hover {
        background: var(--gray-100);
        border-color: var(--gray-400);
        color: var(--gray-900);
        transform: translateY(-2px);
    }

    /* Subscription Active Section */
    .subscription-active-section {
        text-align: center;
        padding: 2rem 0;
    }

    .alert-success {
        background: linear-gradient(135deg, var(--success-light) 0%, #ecfdf5 100%);
        border: 2px solid var(--success-color);
        color: #065f46;
        font-weight: 600;
        border-radius: var(--radius-lg);
        padding: 1.25rem 1.5rem;
        margin-bottom: 2rem;
    }

    .alert-success i {
        margin-right: 0.75rem;
        font-size: 1.25rem;
    }

    .resume-builder-cta {
        max-width: 600px;
        margin: 0 auto;
    }

    .cta-card {
        background: linear-gradient(135deg, var(--primary-light) 0%, #f0f9ff 100%);
        border: 2px solid var(--primary-color);
        padding: 3rem 2rem;
        border-radius: var(--radius-lg);
        box-shadow: 0 10px 30px rgba(59, 130, 246, 0.15);
    }

    .cta-card h3 {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--gray-900);
        margin-bottom: 1rem;
        letter-spacing: -0.01em;
    }

    .cta-card p {
        font-size: 1rem;
        color: var(--gray-600);
        line-height: 1.7;
        margin-bottom: 0;
    }

    .cta-card .btn {
        margin-top: 1.5rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .resume-builder-container {
            padding: 2rem 0;
        }

        .builder-header-section {
            padding: 2.5rem 1.5rem;
            margin-bottom: 2rem;
        }

        .step-title {
            font-size: 2rem;
        }

        .step-description {
            font-size: 1rem;
        }

        .header-stats {
            gap: 1.5rem;
            font-size: 0.85rem;
        }

        .pricing-card.featured {
            transform: scale(1);
        }

        .pricing-card.featured:hover {
            transform: translateY(-8px);
        }

        .price {
            font-size: 2.5rem;
        }

        .pricing-card {
            padding: 1.5rem;
        }

        .pricing-card-header {
            flex-direction: column;
            gap: 0;
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            text-align: center;
        }

        .pricing-icon {
            margin: 0 auto 1rem;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }

        .features-section {
            padding: 2rem 1.5rem;
        }

        .promo-card {
            padding: 1.5rem;
        }

        .promo-input-wrapper {
            flex-direction: column;
        }

        .promo-input,
        .btn-promo-validate {
            width: 100%;
        }

        .btn-checkout {
            font-size: 0.95rem;
            padding: 0.75rem 1rem;
        }

        .nav-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .nav-actions {
            width: 100%;
        }

        .nav-actions .btn {
            flex: 1;
        }

        .pricing-main-title {
            font-size: 1.5rem;
        }

        .features-title {
            font-size: 1.5rem;
        }

        .cta-card {
            padding: 2rem 1.5rem;
        }

        .cta-card h3 {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .resume-builder-container {
            padding: 1.5rem 0;
        }

        .header-badge {
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
        }

        .step-title {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .step-description {
            font-size: 0.9rem;
        }

        .header-stats {
            gap: 1rem;
            font-size: 0.75rem;
        }

        .stat-item i {
            font-size: 1.25rem;
        }

        .pricing-header-section {
            margin-bottom: 2rem;
        }

        .pricing-main-title {
            font-size: 1.25rem;
        }

        .pricing-main-subtitle {
            font-size: 0.9rem;
        }

        .pricing-title {
            font-size: 1.25rem;
        }

        .price {
            font-size: 2rem;
        }

        .row.g-4 {
            gap: 1rem !important;
        }

        .features-section {
            padding: 1.5rem;
            margin: 2rem 0;
        }

        .feature-card {
            padding: 1.5rem;
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }

        .feature-card h5 {
            font-size: 1rem;
        }

        .feature-card p {
            font-size: 0.85rem;
        }

        .nav-content {
            flex-direction: column;
            gap: 1rem;
        }

        .nav-label {
            width: 100%;
            font-size: 0.85rem;
        }

        .nav-actions {
            width: 100%;
        }

        .nav-actions .btn {
            width: 100%;
            font-size: 0.9rem;
        }
    }
</style>
@endsection
