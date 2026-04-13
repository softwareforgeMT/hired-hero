
<?php $__env->startSection('title', 'Home'); ?>
<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page__content">
  <section class="section">
    <div class="container">

      <!-- Header -->
      <div class="row justify-content-center mt-5">
        <div class="col-lg-5">
          <div class="text-center mb-4 pb-2">
            <h4 class="fw-semibold fs-22">Choose the plan that's right for you</h4>
            <p class="text-muted mb-4 fs-15">Simple one-time pricing. No hidden fees. No subscriptions.</p>
            <h3 class="fw-semibold fs-18 text-success">Are you an Organization? Contact Us Directly</h3>
          </div>
        </div>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-11">
          <!-- Promo Code Section -->
          <div class="row mb-5">
            <div class="col-12">
              <div class="card border-0 bg-light">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                      <h5 class="mb-1"><i class="ri-ticket-2-line text-success"></i> Have a Discount Code?</h5>
                      <p class="text-muted mb-0">Enter your promo code to get an exclusive discount on any plan</p>
                    </div>
                    <div style="min-width: 300px;">
                      <div class="d-flex gap-2">
                        <input 
                          type="text" 
                          id="promoCodeInput" 
                          class="form-control" 
                          placeholder="Enter promo code"
                          maxlength="50"
                          autocomplete="off"
                        >
                        <button 
                          type="button" 
                          class="btn btn-success" 
                          id="validatePromoBtn"
                        >
                          <i class="ri-check-line"></i> Validate
                        </button>
                      </div>
                      <div id="promoMessage" class="small mt-2" style="display: none;"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row d-flex justify-content-center">
              
              
          <?php
              $hhOriginalPlanPrices = [
                  'free-plan'     => 0.00,
                  'starter-plan'  => 19.99,
                  'pro-plan'      => 39.99,
                  'premium-plan'  => 52.99,
              ];
          ?>
          

            <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="col-lg-3 col-md-6 mb-4">
                <div class="card pricing-box h-100" data-plan-slug="<?php echo e($plan->slug); ?>" data-original-price="<?php echo e($plan->price); ?>">
                  <div class="card-body p-4 m-2 d-flex flex-column position-relative">

                    
                    <?php if(isset($activePlan) && $activePlan->plan_id == $plan->id): ?>
                      <div class="position-absolute" style="right: -8px; top: 4px;">
                        <span class="badge bg-success mb-2">Active Plan</span>
                      </div>
                    <?php endif; ?>

                    
                    <div class="d-flex align-items-center">
                      <div class="flex-grow-1">
                        <h5 class="mb-1 fw-semibold"><?php echo e($plan->name); ?></h5>
                        <p class="text-muted mb-0"><?php echo e($plan->description); ?></p>
                      </div>
                      
                      
                    </div>

                    
                    <div class="pt-4"> 
                      <?php
  // Only show discount UI to truly eligible LOGGED-IN users.
  $discountPercent = (int) config('referrals.discount_percent', 20);
  $orig            = (float) $plan->price;
  $pricePerUnit    = (float) ($plan->price_per_unit ?? $orig);
  $crossedPrice    = (float) ($plan->crossed_price_per_unit ?? 0);
  $intervalLabel   = $plan->interval === 'weekly'   ? '1-week pass'
                   : ($plan->interval === 'biweekly' ? '2-weeks pass' : $plan->interval);

  // NEW: original "was" price for strike-through (from our static map)
  $displayOriginal = $hhOriginalPlanPrices[$plan->slug] ?? $orig;

  if (auth()->check()) {
      $user       = auth()->user();
      $isReferred = !empty($user->referred_by) && (int) ($user->referral_discount_used ?? 0) === 0;
  } else {
      // HARD DISABLE discount UI for guests to avoid sticky session lies
      $isReferred = false;
  }

  $discounted = $isReferred ? max(0, round($orig * (1 - $discountPercent/100), 2)) : $orig;
?>

                      <div class="pt-4"> 
                        <?php if($isReferred && $orig > 0): ?>
                          <h2 class="mb-1">
                            <span class="me-2" style="text-decoration:line-through;color:#7a7a7a;">
                              $<?php echo e(number_format($orig, 2)); ?>

                            </span>
                            <span class="text-success fw-bold pricing-display-value">
                              $<?php echo e(number_format($discounted, 2)); ?>

                            </span>
                            <span class="badge bg-success ms-2 align-middle">-<?php echo e($discountPercent); ?>%</span>
                            <span class="fs-13 text-muted">/<?php echo e($intervalLabel); ?></span>
                          </h2>
                          <div class="small text-success">Referral discount applied at checkout</div>
                        <?php else: ?>
                          <?php if($displayOriginal > $orig): ?>
                              <h2 class="mb-0">
                                  <span class="me-2 text-muted text-decoration-line-through">
                                      $<?php echo e(number_format($displayOriginal, 2)); ?>

                                  </span>
                                  <span class="fw-bold pricing-display-value">
                                      $<?php echo e(number_format($orig, 2)); ?>

                                  </span>
                                  <span class="fs-13 text-muted">/<?php echo e($intervalLabel); ?></span>
                              </h2>
                          <?php else: ?>
                              <h2 class="mb-0">
                                  <sup><small>$</small></sup><span class="pricing-display-value"><?php echo e(number_format($orig, 2)); ?></span>
                                  <span class="fs-13 text-muted">/<?php echo e($intervalLabel); ?></span>
                              </h2>
                          <?php endif; ?>
                          <?php if($pricePerUnit > 0): ?>
                              <div class="small text-muted mt-2">
                                  $<?php echo e(number_format($pricePerUnit, 2)); ?> per <?php echo e('week' ?? 'unit'); ?>

                                  <?php if($crossedPrice > 0): ?>
                                      <span class="ms-2" style="text-decoration: line-through;">$<?php echo e(number_format($crossedPrice, 2)); ?></span>
                                  <?php endif; ?>
                              </div>
                          <?php endif; ?>
                      <?php endif; ?>
                      </div> 
                    </div> 

                    <hr class="my-4 text-muted">

                    
                    <ul class="list-unstyled text-muted vstack gap-3" style="min-height: 260px;">
                      <?php $__currentLoopData = (array) $plan->access_section; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $featureKey => $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($featureKey !== 'jobMatches'): ?>
                          <li>
                            <div class="d-flex">
                              <div class="flex-shrink-0 text-success me-1">
                                <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                              </div>
                              <div class="flex-grow-1">
                                <?php echo e($feature['description'] ?? ''); ?>

                                <?php if($featureKey === 'interviewAccess' && !empty($feature['questions'])): ?>
                                  <div class="text-muted small"><?php echo e($feature['questions']); ?> questions per interview</div>
                                <?php endif; ?>
                              </div>
                            </div>
                          </li>
                        <?php else: ?>
                          
                          <?php if(!empty($feature['job_search'])): ?>
                            <li>
                              <div class="d-flex">
                                <div class="flex-shrink-0 text-success me-1">
                                  <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                  <?php echo e($feature['job_search'] === 'unlimited' ? 'Unlimited' : $feature['job_search']); ?> job matches
                                </div>
                              </div>
                            </li>
                          <?php endif; ?>
                          <?php if($feature['advanced_job_insights'] ?? false): ?>
                            <li>
                              <div class="d-flex">
                                <div class="flex-shrink-0 text-success me-1">
                                  <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">Advanced job insights</div>
                              </div>
                            </li>
                          <?php endif; ?>
                          <?php if($feature['ai_tailored_resume'] ?? false): ?>
                            <li>
                              <div class="d-flex">
                                <div class="flex-shrink-0 text-success me-1">
                                  <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">AI-tailored resume generation</div>
                              </div>
                            </li>
                          <?php endif; ?>
                          <?php if(!empty($feature['ai_tailored_cover'])): ?>
                            <li>
                              <div class="d-flex">
                                <div class="flex-shrink-0 text-success me-1">
                                  <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                  <?php echo e($feature['ai_tailored_cover'] === 'unlimited' ? 'Unlimited' : $feature['ai_tailored_cover']); ?> AI-tailored cover letters
                                </div>
                              </div>
                            </li>
                          <?php endif; ?>
                          <?php if($feature['job_tracking'] ?? false): ?>
                            <li>
                              <div class="d-flex">
                                <div class="flex-shrink-0 text-success me-1">
                                  <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">Job application tracking</div>
                              </div>
                            </li>
                          <?php endif; ?>
                          <?php if($feature['ats_optimized_covers_resumes'] ?? false): ?>
                            <li>
                              <div class="d-flex">
                                <div class="flex-shrink-0 text-success me-1">
                                  <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">ATS-optimized resumes & cover letters</div>
                              </div>
                            </li>
                          <?php endif; ?>
                        <?php endif; ?>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>

                    
                    <div class="mt-4 mt-auto">
                      <?php if(auth()->check()): ?>
                        <?php if(isset($activePlan) && $activePlan->plan_id == $plan->id): ?>
                          <?php if(empty($showRenew)): ?>
                            <button class="btn btn-success w-100 waves-effect waves-light" disabled>Active Plan</button>
                          <?php else: ?>
                            <button class="btn btn-success w-100 waves-effect waves-light mb-3" disabled>Active Plan</button>
                            <?php if($plan->slug !== 'free-plan'): ?>
                              <a href="<?php echo e(route('stripe.process', $plan->slug)); ?>" class="btn btn-warning w-100 waves-effect waves-light">
                                Renew Plan
                              </a>
                            <?php endif; ?>
                          <?php endif; ?>
                        <?php elseif($plan->slug !== 'free-plan'): ?>
                          <a href="<?php echo e(route('stripe.process', $plan->slug)); ?>" class="btn btn-soft-success w-100 waves-effect waves-light">Get started</a>
                        <?php endif; ?>
                      <?php else: ?>
                        <a href="<?php echo e(route('user.login')); ?>" class="btn btn-soft-success w-100 waves-effect waves-light">Get started</a>
                      <?php endif; ?>
                    </div>

                  </div>
                </div>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <!-- Corporate Plan Column (unchanged sizing) -->
            <div class="col-lg-3 col-md-6 mb-4">
              <div class="card pricing-box border border-success position-relative 98%">
                <div class="position-absolute" style="right: -8px; top: 4px;">
                  <span class="badge bg-secondary mb-2">Enterprise</span>
                </div>

                <div class="card-body p-4 m-2 d-flex flex-column">
                  <h5 class="mb-2 fw-semibold text-success text-center">Corporate Plans</h5>
                  <p class="text-muted text-center mb-2">Tailored for schools, companies, non-profit & training organizations</p>
                  <hr class="text-muted my-2 mx-auto" style="width: 75%;">

                  <ul class="list-unstyled text-muted vstack gap-2 mb-3" style="min-height: 80px;">
                    <li>
                      <div class="d-flex">
                        <div class="flex-shrink-0 text-success me-1">
                          <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                        </div>
                        <div class="flex-grow-1">Custom portal access</div>
                      </div>
                    </li>
                    <li>
                      <div class="d-flex">
                        <div class="flex-shrink-0 text-success me-1">
                          <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                        </div>
                        <div class="flex-grow-1">Flexible usage & pricing</div>
                      </div>
                    </li>
                  </ul>

                  <div class="mt-auto">
                    <p class="text-muted text-center small mb-3">Let’s build a solution for your team.</p>
                    <a href="mailto:info@hiredheroai.com" class="btn btn-success w-100">Contact Us</a>
                  </div>
                </div>
              </div>
            </div>

          </div> <!-- /row -->
        </div>
      </div>

      <!-- Donation Note -->
      <div class="donation-note text-center py-3">
        We donate up to $3* to a charity for every subscription<br>
        <small>$2 for $29.99 & $3 for $38.99 subscription</small>
      </div>

    </div>
  </section>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    const promoInput = document.getElementById('promoCodeInput');
    const validateBtn = document.getElementById('validatePromoBtn');
    const promoMessage = document.getElementById('promoMessage');
    
    let validatedPromo = null;

    validateBtn.addEventListener('click', function() {
        validatePromoCode();
    });

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

        validateBtn.disabled = true;
        validateBtn.innerHTML = '<i class="ri-loader-4-line"></i> Validating...';

        fetch('<?php echo e(route("validate-promo-code")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ code: code }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.valid) {
                validatedPromo = {
                    code: code,
                    discountPercentage: data.discount_percentage,
                };
                showMessage(`Promo code "${code}" applied! ${data.discount_percentage}% discount`, 'success');
                updatePricingDisplay();
                promoInput.disabled = true;
                validateBtn.classList.add('d-none');
            } else {
                showMessage(data.message || 'Invalid promo code', 'error');
                resetPricingDisplay();
                validatedPromo = null;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('An error occurred while validating the promo code. Please try again.', 'error');
        })
        .finally(() => {
            validateBtn.disabled = false;
            validateBtn.innerHTML = '<i class="ri-check-line"></i> Validate';
        });
    }

    function updatePricingDisplay() {
        if (!validatedPromo) return;

        const discountPercent = validatedPromo.discountPercentage;

        // Get all plan cards
        document.querySelectorAll('[data-plan-slug]').forEach(card => {
            const planSlug = card.getAttribute('data-plan-slug');
            
            // Skip free plan - don't show discount on free plans
            if (planSlug === 'free-plan') {
                return;
            }

            const originalPrice = parseFloat(card.getAttribute('data-original-price'));
            const discountedPrice = (originalPrice * (1 - discountPercent / 100)).toFixed(2);
            
            // Update display price
            const priceDisplay = card.querySelector('.pricing-display-value');
            if (priceDisplay) {
                const currentText = priceDisplay.textContent || priceDisplay.innerText;
                const hasDollar = currentText.includes('$');
                priceDisplay.textContent = hasDollar ? `$${discountedPrice}` : discountedPrice;
            }
            
            // Show discount badge
            let discountBadge = card.querySelector('.discount-applied-badge');
            if (!discountBadge) {
                discountBadge = document.createElement('div');
                discountBadge.className = 'discount-applied-badge';
                discountBadge.innerHTML = `<span class="badge bg-success ms-2">-${discountPercent}% OFF</span>`;
                card.querySelector('.pricing-display-value').parentElement.appendChild(discountBadge);
            }
            discountBadge.style.display = 'inline';

            // Update "Get started" link with promo code
            const getStartedLink = card.querySelector('a.btn-soft-success');
            if (getStartedLink) {
                let href = getStartedLink.getAttribute('href');
                // Remove any existing promo_code parameter first
                href = href.split('?promo_code=')[0].split('&promo_code=')[0];
                // Add the promo code parameter
                if (href.includes('?')) {
                    href += '&promo_code=' + encodeURIComponent(validatedPromo.code);
                } else {
                    href += '?promo_code=' + encodeURIComponent(validatedPromo.code);
                }
                getStartedLink.setAttribute('href', href);
            }
        });
    }

    function resetPricingDisplay() {
        // Reset all discount badges
        document.querySelectorAll('.discount-applied-badge').forEach(badge => {
            badge.style.display = 'none';
        });

        // Reset prices to original
        document.querySelectorAll('[data-plan-slug]').forEach(card => {
            const planSlug = card.getAttribute('data-plan-slug');
            
            // Skip free plan
            if (planSlug === 'free-plan') {
                return;
            }

            const originalPrice = parseFloat(card.getAttribute('data-original-price'));
            const priceDisplay = card.querySelector('.pricing-display-value');
            if (priceDisplay) {
                const currentText = priceDisplay.textContent || priceDisplay.innerText;
                const hasDollar = currentText.includes('$');
                priceDisplay.textContent = hasDollar ? `$${originalPrice.toFixed(2)}` : originalPrice.toFixed(2);
            }

            // Remove promo code from payment link
            const getStartedLink = card.querySelector('a.btn-soft-success');
            if (getStartedLink) {
                let href = getStartedLink.getAttribute('href');
                // Remove any promo_code parameter
                href = href.split('?promo_code=')[0].split('&promo_code=')[0];
                getStartedLink.setAttribute('href', href);
            }
        });
    }

    function showMessage(message, type) {
        promoMessage.textContent = message;
        promoMessage.className = 'small mt-2';
        promoMessage.className += type === 'success' ? ' text-success' : ' text-danger';
        promoMessage.style.display = 'block';

        if (type === 'success') {
            setTimeout(() => {
                // Keep success message visible
            }, 5000);
        }
    }

    // Clear promo code on input change
    promoInput.addEventListener('input', function() {
        if (validatedPromo) {
            validatedPromo = null;
            resetPricingDisplay();
            promoMessage.style.display = 'none';
            promoInput.disabled = false;
            validateBtn.classList.remove('d-none');
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('front.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\hired-hero\resources\views/front/pricing.blade.php ENDPATH**/ ?>