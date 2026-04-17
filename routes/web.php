<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Front\TrendsController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Front\MockInterviewController;
use App\Http\Controllers\Front\PresentationController;
use App\Http\Controllers\Front\StripeController;
use App\Http\Controllers\User\Auth\SocialAuthController;
use App\Http\Controllers\Auth\MicrosoftController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\LoginController as UserLoginController;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\User\ForgotController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\PaypalController;
use App\Http\Controllers\Front\StripeWebhookController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\PlacementController;
use App\Http\Controllers\Admin\PromoCodeController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\User\OrderPurchasedController;
use App\Http\Controllers\User\EarningController;
use App\Http\Controllers\User\ResultHistoryController;
use App\Http\Controllers\Placement\PlacementWizardController;
use App\Http\Controllers\Placement\JobMatchController;
use App\Http\Controllers\Placement\ApplicationTrackerController;
use App\Http\Controllers\Placement\ResumeBuilderController;
use App\Http\Controllers\Placement\StripeWebhookResumeController;
use App\Http\Controllers\Placement\TailoredResumeController;
use App\Http\Controllers\Placement\CoverLetterController;
use App\Http\Controllers\EmailUnsubscribeController;


use App\Http\Controllers\ScraperController;

Route::post('/fetch-html', [ScraperController::class, 'scrape']);
Route::get('/scrape-search', [ScraperController::class, 'scrape'])->name('placement.jobs.search-scrape');
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Register your web routes here.
|
*/

/**
 * Admin Routes
 */
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

    // Admin Login & Authentication
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('/forgot', [LoginController::class, 'showForgotForm'])->name('forgot');
    Route::post('/forgot', [LoginController::class, 'forgot'])->name('forgot.submit');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Admin Dashboard & Profile
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    
    // Handle profile updates - route based on demo mode
    Route::post('/profile/update', function (\Illuminate\Http\Request $request) {
        if (Helpers::demo_mode()) {
            return app('App\Http\Controllers\Admin\DemoController')->updateProfileDemo($request);
        }
        return app('App\Http\Controllers\Admin\AdminController')->profileupdate($request);
    })->name('profile.update');

    // Demo Mode Toggle (not protected, always allowed)
    Route::post('/toggle-demo-mode', [AdminController::class, 'toggleDemoMode'])->name('toggle-demo-mode');

    // Settings Routes (protected by demo mode middleware)
    Route::middleware('demo.lock.settings')->group(function () {
        // Social Profile Settings
        Route::get('/social', [AdminController::class, 'social'])->name('social');
        Route::post('/social/update', function (\Illuminate\Http\Request $request) {
            if (Helpers::demo_mode()) {
                return app('App\Http\Controllers\Admin\DemoController')->updateSocialSettings($request);
            }
            return app('App\Http\Controllers\Admin\AdminController')->socialupdate($request);
        })->name('social.update');

        // Social Login Settings
        Route::view('/social-login', 'admin.social-login')->name('social-login');
        Route::post('/social-login', [AdminController::class, 'updateSocialLogin'])->name('social-login.update');

        // General Settings
        Route::get('/generalsettings', [AdminController::class, 'generalsettings'])->name('generalsettings');
        Route::post('/generalsettings', function (\Illuminate\Http\Request $request) {
            if (Helpers::demo_mode()) {
                return app('App\Http\Controllers\Admin\DemoController')->updateGeneralSettings($request);
            }
            return app('App\Http\Controllers\Admin\AdminController')->generalsettingsupdate($request);
        })->name('generalsettings.update');

        // Custom Pages CRUD
        Route::group(['prefix' => 'custompage', 'as' => 'custompage.'], function () {
            Route::get('/datatables', [PageController::class, 'datatables'])->name('datatables');
            Route::get('/', [PageController::class, 'index'])->name('index');
            Route::get('/create', [PageController::class, 'create'])->name('create');
            Route::post('/create', function (\Illuminate\Http\Request $request) {
                if (Helpers::demo_mode()) {
                    return app('App\Http\Controllers\Admin\DemoController')->storePageDemo($request);
                }
                return app('App\Http\Controllers\Admin\PageController')->store($request);
            })->name('store');
            Route::get('/edit/{id}', [PageController::class, 'edit'])->name('edit');
            Route::post('/edit/{id}', function (\Illuminate\Http\Request $request, $id) {
                if (Helpers::demo_mode()) {
                    return app('App\Http\Controllers\Admin\DemoController')->updatePageDemo($request, $id);
                }
                return app('App\Http\Controllers\Admin\PageController')->update($request, $id);
            })->name('update');
            Route::get('/delete/{id}', function ($id) {
                if (Helpers::demo_mode()) {
                    return app('App\Http\Controllers\Admin\DemoController')->deletePageDemo($id);
                }
                return app('App\Http\Controllers\Admin\PageController')->destroy($id);
            })->name('delete');
            Route::get('/status/{id1}/{id2}', function ($id1, $id2) {
                if (Helpers::demo_mode()) {
                    return app('App\Http\Controllers\Admin\DemoController')->updatePageStatus($id1, $id2);
                }
                return app('App\Http\Controllers\Admin\PageController')->status($id1, $id2);
            })->name('status');
        });
    });

    Route::get('/referrals', [AdminController::class, 'referrals'])->name('referrals.index');
    Route::get('/referrals/datatables', [AdminController::class, 'referralsDatatables'])->name('referrals.datatables');

    // Password Reset
    Route::get('/password', [AdminController::class, 'passwordreset'])->name('password');
    Route::post('/password/update', [AdminController::class, 'changepass'])->name('password.update');

    // User Management
    Route::get('/users/datatables', [UserController::class, 'usersDataTables'])->name('users.datatables');
    Route::get('/users', [UserController::class, 'users'])->name('users.index');
    Route::get('/users/status/{id1}/{id2}', [UserController::class, 'status'])->name('users.status');
    Route::get('/users/secret/login/{id}', [UserController::class, 'secret'])->name('user.secret');
    Route::get('/users/stop-impersonate', [UserController::class, 'stopImpersonate'])->name('users.stop-impersonate');

    // Promo Code Management
    Route::group(['prefix' => 'promo-codes', 'as' => 'promo-codes.'], function () {
        Route::get('/', [PromoCodeController::class, 'index'])->name('index');
        Route::get('/datatables', [PromoCodeController::class, 'datatables'])->name('datatables');
        Route::post('/generate-for-user', [PromoCodeController::class, 'generateForUser'])->name('generate-for-user');
        Route::post('/send-to-user', [PromoCodeController::class, 'sendToUser'])->name('send-to-user');
        Route::post('/batch-send-to-users', [PromoCodeController::class, 'batchSendToUsers'])->name('batch-send-to-users');
        Route::post('/bulk-send-promos', [PromoCodeController::class, 'bulkSendPromos'])->name('bulk-send-promos');
        Route::post('/{id}/deactivate', [PromoCodeController::class, 'deactivate'])->name('deactivate');
        Route::post('/{id}/activate', [PromoCodeController::class, 'activate'])->name('activate');
        Route::post('/{id}/delete', [PromoCodeController::class, 'destroy'])->name('delete');
        Route::get('/user/{userId}/codes', [PromoCodeController::class, 'userPromoCodes'])->name('user-codes');
        Route::get('/{id}', [PromoCodeController::class, 'show'])->name('show');
    });

    // User Subscriptions
    Route::group(['prefix' => 'subscriptions', 'as' => 'subscriptions.'], function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('index');
        Route::get('/datatables', [SubscriptionController::class, 'datatables'])->name('datatables');
        Route::get('/{id}', [SubscriptionController::class, 'show'])->name('show');
        Route::post('/{id}/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
    });

    // Admin Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/datatables', [AdminOrderController::class, 'datatables'])->name('orders.datatables');

    // Placement Profiles Management
    Route::group(['prefix' => 'placements', 'as' => 'placements.'], function () {
        Route::get('/datatables', [PlacementController::class, 'datatables'])->name('datatables');
        Route::get('/', [PlacementController::class, 'index'])->name('index');
        Route::get('/{id}', [PlacementController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PlacementController::class, 'edit'])->name('edit');
        Route::post('/{id}/update', [PlacementController::class, 'update'])->name('update');
        Route::get('/{id}/resumes', [PlacementController::class, 'resumes'])->name('resumes');
        Route::get('/{id}/resumes/download', [PlacementController::class, 'downloadResume'])->name('resume.download');
        Route::post('/{id}/resumes/delete', [PlacementController::class, 'deleteResume'])->name('resume.delete');
        Route::get('/{id}/job-matches', [PlacementController::class, 'jobMatches'])->name('job-matches');
    });
});


/**
 * User Routes (authenticated and guest)
 */

Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => ['auth:sanctum,web']], function () {

    // Dashboard & Profile
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/account-settings', [DashboardController::class, 'profile'])->name('profile');
    Route::post('/account-settings', [DashboardController::class, 'profileUpdate'])->name('profile.update');

    // Password Reset
    Route::post('/reset', [DashboardController::class, 'reset'])->name('reset.submit');

    // Orders
    Route::get('/orders/datatables', [OrderPurchasedController::class, 'datatables'])->name('orders.purchased.datatables');
    Route::get('/order/purchased/{order_status?}', [OrderPurchasedController::class, 'index'])->name('order.purchased.index');
    
    // =================== RESULTS HISTORY ===================
    Route::get('/results', [ResultHistoryController::class, 'index'])->name('results.index');
    Route::get('/results/{attempt}', [ResultHistoryController::class, 'show'])->name('results.show');
    // =======================================================

    // Media Upload
    Route::post('add/media', [DashboardController::class, 'storeMedia'])->name('storeMedia');

    // Earnings
    Route::get('/earnings/datatables', [EarningController::class, 'datatables'])->name('earnings.datatables');
    Route::get('/earnings', [EarningController::class, 'index'])->name('earnings');

    // Subscriptions
    Route::get('/subscriptions', [\App\Http\Controllers\User\SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/datatables', [\App\Http\Controllers\User\SubscriptionController::class, 'datatables'])->name('subscriptions.datatables');

    // Stripe Payouts Gateway
    Route::get('/add/payment/gateway', [EarningController::class, 'addPayGateway'])->name('addpayment.gateway');
    Route::get('/return/payment/gateway/status', [EarningController::class, 'returnConnectStatus'])->name('returnpayment.gateway.status');
    Route::get('/send/payment/user', [EarningController::class, 'sendPayUser'])->name('sendpayment');
});


/**
 * User Authentication Routes
 */
Route::group(['as' => 'user.'], function () {
    Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserLoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [UserLoginController::class, 'logout'])->name('logout');

    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

    Route::get('/forgot', [ForgotController::class, 'showForgotForm'])->name('forgot');
    Route::post('/forgot', [ForgotController::class, 'forgot'])->name('forgot.submit');

    Route::get('/reset-password/{token}', [ForgotController::class, 'getPassword'])->name('password.reset');
    Route::post('/reset-password', [ForgotController::class, 'updatePassword'])->name('password.reset.update');

    // Email verification routes
    Route::post('/verify/email', [UserLoginController::class, 'authenticationToken'])->name('verify.email');
    Route::get('resend/verify/email/{email}', [UserLoginController::class, 'newAuthenticationToken'])->name('resend.verify');


    Route::get('oauth/google', [RegisterController::class, 'redirectToGoogle'])->name('google-redirect');
    Route::get('oauth/google/callback', [RegisterController::class, 'handleGoogleCallback'])->name('google-callback');
    Route::post('/google/referral/submit', [RegisterController::class, 'submitGoogleReferral'])->name('google-referral-submit');
    Route::get('auth/google/confirm-duplicate', [RegisterController::class, 'confirmGoogleDuplicate'])->name('google-confirm-duplicate');

    Route::get('/google/referral/skip', [RegisterController::class, 'skipGoogleReferral'])->name('google-skip-referral');

});


/**
 * Social Login Routes (Guest Only)
 */
Route::group(['middleware' => 'guest'], function () {

    // Facebook, Google, Twitter
    Route::get('oauth/{provider}', [SocialAuthController::class, 'redirect'])
        ->where('provider', '(facebook|google|twitter)$');
    Route::get('oauth/{provider}/callback', [SocialAuthController::class, 'callback'])
        ->where('provider', '(facebook|google|twitter)$');

    // Microsoft
    Route::get('auth/microsoft', [MicrosoftController::class, 'redirectToMicrosoft']);
    Route::get('auth/microsoft/callback', [MicrosoftController::class, 'handleMicrosoftCallback']);
});


/**
 * Frontend / Public Routes
 */
Route::get('/', [HomeController::class, 'home'])->name('front.index');
Route::get('/coming-soon', [HomeController::class, 'ComingSoon'])->name('front.comingsoon');
// Route::get('/how-to-sell', [HomeController::class, 'how_to_sell'])->name('front.sell');
Route::get('/book-demo', function () {return redirect('mailto:info@hiredheroai.com?subject=Book%20an%20Institutional%20Demo');
});

// Institutions / Organizations resources page
Route::get('/resources/organizations', [HomeController::class, 'resourcesOrganizations'])
    ->name('front.resources.organizations');

/**
 * Frontend / Trend Pages / Blogs
 */

Route::get('/trends', [TrendsController::class, 'index'])->name('trends.index');
Route::post('/webhooks/brevo/trends', function () {
    // Brevo just needs a 200 OK
    return response()->json(['ok' => true]);
});

// Resources - For Individuals
Route::view('/resources/individuals', 'front.resources.individuals')
    ->name('resources.individuals');

// Resources - For Organizations
Route::view('/resources/organizations', 'front.resources.organizations')
    ->name('resources.organizations');

// Job Fairs Hub
Route::view('/job-fairs', 'front.resources.job-fairs')
    ->name('jobfairs.index');

/**
 * SEO Content Pages
 * --------------------------------
 */

Route::view('/ai-mock-interview-mediators', 'front.ai-mock-interview-mediators')->name('front.mediators');
Route::view('/mock-interview-instant-feedback', 'front.mock-interview-instant-feedback')->name('front.instantfeedback');
Route::view('/ai-presentation-practice-tool', 'front.SEO.ai-presentation-practice-tool')->name('front.ai-presentation-practice-tool');
Route::view('/product-manager-interview-simulator', 'front.SEO.product-manager-interview-simulator')->name('front.pm-simulator');
Route::view('/behavioral-interview-practice-ai','front.SEO.behavioral-interview-practice-ai')->name('front.behavioral-interview-practice-ai');
Route::view('/slide-presentation-rehearsal-online', 'front.SEO.slide-presentation-rehearsal-online');
Route::view('/ai-interview-training-colleges', 'front.SEO.ai-interview-training-colleges');
Route::view('/skills-for-success-soft-skills-platform','front.SEO.skills-for-success-soft-skills-platform');
Route::view('/digital-career-readiness-workforce-boards','front.SEO.digital-career-readiness-workforce-boards');
Route::view('/ai-job-readiness-nonprofits-community-organizations','front.SEO.ai-job-readiness-nonprofits-community-organizations');

// Platform pages
Route::view('/platform-overview', 'front.SEO.platform-overview');
Route::view('/platform-organizations', 'front.SEO.platform-organizations');
Route::view('/platform-individuals', 'front.SEO.platform-individuals');


Route::group(['prefix' => 'mock', 'as' => 'mock.'], function () {
    Route::get('/add-job-details', [MockInterviewController::class, 'addJobDetails'])->name('job-details.create');
    Route::post('/interview', [MockInterviewController::class, 'startInterview'])->name('interview.index');
    Route::get('/interview', function () {
        return redirect()->route('mock.job-details.create');
    })->name('interview.get');
    Route::post('/upload-audio', [MockInterviewController::class, 'uploadAudio'])->name('upload.audio');
    Route::get('/result', [MockInterviewController::class, 'showResult'])->name('result.index');
});

Route::group(['prefix' => 'presentation', 'as' => 'presentation.'], function () {
    Route::get('/create', [PresentationController::class, 'createPresentation'])->name('create');
    Route::post('/record', [PresentationController::class, 'recordPresentation'])->name('record');
    Route::get('/record', function () {
        return redirect()->route('presentation.create');
    })->name('record.get');
    Route::post('/upload-audio', [PresentationController::class, 'uploadAudio'])->name('upload.audio');
    Route::get('/feedback', [PresentationController::class, 'provideFeedback'])->name('feedback');
});

Route::get('/pricing', [HomeController::class, 'pricing'])->name('front.pricing');

Route::get('/create-checkout-session/{slug}', [StripeController::class, 'processPayment'])->name('stripe.process');
Route::get('/success', [StripeController::class, 'success'])->name('stripe.success');
Route::get('/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');

// Stripe Webhook - MUST NOT have auth middleware
Route::post('stripe/webhook', [StripeController::class, 'handleStripeWebhook']);

// Resume Builder Stripe Webhook
Route::post('webhooks/stripe/resume-builder', [StripeWebhookResumeController::class, 'handle']);

/**
 * Placement/Job Matching Module Routes
 */
Route::group(['prefix' => 'placement', 'as' => 'placement.'], function () {
    // PUBLIC WIZARD ROUTES (Steps 1-5 - No Authentication Required)
    Route::group([], function () {
        // Start wizard (show start page)
        Route::get('/start', [PlacementWizardController::class, 'start'])->name('start');
        
        // Create profile or session (user confirms to create)
        Route::post('/create', [PlacementWizardController::class, 'create'])->name('create');
        
        // Public wizard steps (1-5)
        Route::get('/wizard/step/{step}', [PlacementWizardController::class, 'showStep'])->name('wizard.step');
        Route::post('/step/{step}/submit', [PlacementWizardController::class, 'submitStep'])->name('wizard.submit');
    });
    
    // AUTHENTICATED WIZARD ROUTES (Steps 6+ - Authentication Required)
    Route::group(['middleware' => 'auth:sanctum,web'], function () {
        // Resume upload and job matches (step 6+)
        // Get suggested roles
        Route::get('/profile/{profileId}/suggested-roles', [PlacementWizardController::class, 'getSuggestedRoles'])->name('profile.suggested-roles');
        
        // Submit built resume to continue to step 7
        Route::post('/wizard/submit-built-resume/{resumeId}', [PlacementWizardController::class, 'submitStep6WithBuiltResume'])->name('wizard.submit-built-resume');
    
        // Job Scraping Routes (Background Job API)
        Route::prefix('scraping')->group(function () {
            Route::post('/start', 'App\Http\Controllers\Api\JobScrapingController@startScraping')->name('scraping.start');
            Route::get('/progress', 'App\Http\Controllers\Api\JobScrapingController@getProgress')->name('scraping.progress');
            Route::get('/is-complete', 'App\Http\Controllers\Api\JobScrapingController@isScrappingComplete')->name('scraping.is-complete');
            Route::get('/queue-status', 'App\Http\Controllers\Api\JobScrapingController@checkQueueStatus')->name('scraping.queue-status');
            Route::get('/job-matches', 'App\Http\Controllers\Api\JobScrapingController@getJobMatches')->name('scraping.job-matches');
        });

        // Job matches
        Route::get('/jobs', [JobMatchController::class, 'index'])->name('jobs.index');
        Route::get('/jobs/results', [JobMatchController::class, 'index'])->name('results.index');
        Route::get('/jobs/filter', [JobMatchController::class, 'filter'])->name('jobs.filter');
        Route::get('/jobs/{jobMatch}', [JobMatchController::class, 'show'])->name('jobs.show');
        Route::post('/jobs/{jobMatch}/apply', [JobMatchController::class, 'applyForJob'])->name('jobs.apply');
        Route::delete('/job-matches/{jobMatch}', [JobMatchController::class, 'destroy'])->name('job-matches.destroy');
        Route::get('/jobs/{jobMatch}/quality', [JobMatchController::class, 'getMatchQuality'])->name('jobs.quality');
        
        // Tailored resume generation
        Route::post('/resumes/generate', [TailoredResumeController::class, 'generate'])->name('resumes.generate');
        Route::post('/resumes/preview', [TailoredResumeController::class, 'preview'])->name('resume.preview');
        Route::get('/resumes/{jobId}/edit', [TailoredResumeController::class, 'edit'])->name('resume.edit');
        Route::post('/resumes/save-download', [TailoredResumeController::class, 'saveAndDownload'])->name('resume.save-download');
        Route::get('/resumes/{file}/download', [TailoredResumeController::class, 'download'])->name('resume.download');
        
        // Cover letter generation
        Route::get('/cover-letter/generate', [CoverLetterController::class, 'generate'])->name('covers.generate');
        Route::post('/cover-letter/generate', [CoverLetterController::class, 'store'])->name('covers.store');
        Route::post('/cover-letter/finalize', [CoverLetterController::class, 'finalize'])->name('covers.finalize');
        Route::get('/cover-letter/download', [CoverLetterController::class, 'download'])->name('covers.download');
        Route::get('/cover-letters', [CoverLetterController::class, 'index'])->name('covers.index');
        Route::get('/cover-letters/{coverLetter}', [CoverLetterController::class, 'show'])->name('covers.show');
        Route::delete('/cover-letters/{coverLetter}', [CoverLetterController::class, 'destroy'])->name('covers.destroy');
        Route::post('/cover-letters/{coverLetter}/duplicate', [CoverLetterController::class, 'duplicate'])->name('covers.duplicate');
        
        // Application tracker
        Route::get('/applications', [ApplicationTrackerController::class, 'index'])->name('applications.index');
        Route::get('/applications/create', [ApplicationTrackerController::class, 'create'])->name('applications.create');
        Route::post('/applications', [ApplicationTrackerController::class, 'store'])->name('applications.store');
        Route::get('/applications/{application}/edit', [ApplicationTrackerController::class, 'edit'])->name('applications.edit');
        Route::put('/applications/{application}', [ApplicationTrackerController::class, 'update'])->name('applications.update');
        Route::delete('/applications/{application}', [ApplicationTrackerController::class, 'destroy'])->name('applications.destroy');
        Route::get('/applications/tracker', [ApplicationTrackerController::class, 'index'])->name('applications.tracker');
        Route::get('/applications/filter', [ApplicationTrackerController::class, 'filter'])->name('applications.filter');
        Route::patch('/applications/{application}/status', [ApplicationTrackerController::class, 'updateStatus'])->name('applications.update-status');
        Route::get('/applications/{application}', [ApplicationTrackerController::class, 'show'])->name('applications.show');
        Route::post('/applications/archive-old', [ApplicationTrackerController::class, 'archiveOld'])->name('applications.archive-old');
        Route::get('/applications/stats/pipeline', [ApplicationTrackerController::class, 'getPipelineStats'])->name('applications.stats');
    });
});

/**
 * Resume Builder Routes
 */


Route::post('/validate-promo-code', [ResumeBuilderController::class, 'validatePromoCode'])->name('validate-promo-code');

Route::group(['prefix' => 'resume-builder', 'as' => 'resume-builder.', 'middleware' => 'auth:sanctum,web'], function () {
    Route::get('/', [ResumeBuilderController::class, 'form'])->name('form');
    Route::get('/pricing', [ResumeBuilderController::class, 'pricing'])->name('pricing');
    
    Route::post('/checkout', [ResumeBuilderController::class, 'checkout'])->name('checkout');
    Route::get('/checkout/success', [ResumeBuilderController::class, 'checkoutSuccess'])->name('checkout-success');
    // Route::get('/form', [ResumeBuilderController::class, 'form'])->name('form');
    Route::post('/store', [ResumeBuilderController::class, 'store'])->name('store');
    Route::get('/{resume}', [ResumeBuilderController::class, 'view'])->name('view');
    Route::get('/{resume}/preview', [ResumeBuilderController::class, 'preview'])->name('preview');
    Route::get('/{resume}/download', [ResumeBuilderController::class, 'download'])->name('download');
    Route::get('/{resume}/edit', [ResumeBuilderController::class, 'edit'])->name('edit');
    Route::patch('/{resume}', [ResumeBuilderController::class, 'update'])->name('update');
    Route::delete('/{resume}', [ResumeBuilderController::class, 'destroy'])->name('destroy');
});

Route::get('/resume/{id}/download', [ResumeBuilderController::class, 'download'])->name('resume.download')->middleware('auth:sanctum,web');

// Email Unsubscribe
Route::get('/email/unsubscribe/{token}', [EmailUnsubscribeController::class, 'handle'])->name('email.unsubscribe');

// Mock Audio Function
Route::get('/mock/audio/{file}', function ($file) {
    $path = public_path("mock/audio/{$file}");
    if (file_exists($path)) {
        return response()->file($path, ['Content-Type' => 'audio/mpeg']);
    }
    return abort(404);
});
Route::get('/org', function () {
    return view('front.org');
});
Route::get('{slug}', [HomeController::class, 'page'])->name('front.page');
