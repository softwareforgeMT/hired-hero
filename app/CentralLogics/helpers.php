<?php

namespace App\CentralLogics;

use App\Classes\GeniusMailer;
use App\Models\GameItem;
use App\Models\GeneralSetting;
use App\Models\Product;
use App\Models\Rating;
use App\Models\User;
use Chatify\Facades\ChatifyMessenger as Chatify;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;

use App\Models\Order;
use App\Models\UserActivity;

use App\Models\SubPlan;


class Helpers
{


     public static function upload(string $dir, string $format, $file = null)
    {
        if($file == null){
            return 'def.png';
        }
        if (!$file->isValid()) {
            throw new \Exception('Invalid file.');
        }
        if (!in_array(strtolower($file->getClientOriginalExtension()), explode('|', $format))) {
            throw new \Exception('Invalid file format. Allowed formats: '.$format);
        }
        
        if (!file_exists(public_path('assets/dynamic/images/' . $dir))) {
            mkdir(public_path('assets/dynamic/images/' . $dir), 0777, true);
        }
        $name = time().$file->getClientOriginalName();
        $name=str_replace(' ','',$name);        
        $file->move(public_path('assets/dynamic/images/'.$dir), $name);
        return $name;
    }
    public static function update(string $dir,string $old_image=null, string $format, $file = null)
    {   
        if($file == null){
            return $old_image;
        }
        if($old_image != null && file_exists(public_path('assets/dynamic/images/' . $dir . $old_image))) {
                unlink(public_path('assets/dynamic/images/' . $dir . $old_image));
        }
        return Helpers::upload($dir, $format, $file);
    }
    public static function unlink(string $dir,string $old_image)
    {   

        if($old_image != null && file_exists(public_path('assets/dynamic/images/' . $dir . $old_image))) {
                unlink(public_path('assets/dynamic/images/' . $dir . $old_image));
        };
    }   
    public static function image($file, $dir, $default = 'def.png')
    {
        $image = ($file && file_exists(public_path('/assets/dynamic/images/'.$dir.$file)) )  ? asset('/assets/dynamic/images/'.$dir.$file) : asset('/images/'.$default);
        return $image;
    }
    public static function slug(string $slug)
    {
        //return $slug=Str::slug($slug,'-').'-'.strtolower(Str::random(3));
         return $slug=Str::slug($slug,'-');
    }
    

    
    public static function send_verification_otp($email)
    {   
     
        $token=rand(1000, 9999);
        $autopass = Str::random(64);
            DB::table('email_verifications')->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            try {
                $gs = GeneralSetting::findOrFail(1);
                if($gs->is_smtp == 1)
                {   
                   $to = $email;
                   $subject = "Verify your email address.";
                   $msg = "Dear Customer,<br> We noticed that you need to verify your email address.This is your  Verification Code " .$token;

                   $data = [
                                'to' => $to,
                                'subject' => $subject,
                                'body' => $msg,
                            ];
                        $mailer = new GeniusMailer();
                        $mailer->sendCustomMail($data);              
                }
                else
                {
                    $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                    mail($request->email,$subject,$msg,$headers);            
                }
                return $response=array('success' =>'Code Resend SuccessFully');

            } catch (\Exception $e) {
                 self::logError($e);
                return $response=array('error'=>$e->getMessage());   
            }

    }
    public static function send_hiredhero_welcome($user)
    {
        try {
 
            $gs = GeneralSetting::findOrFail(1);

            $to = $user->email;
            $subject = "Welcome to HiredHero AI 🎉";

            $msg = "Hi {$user->name},<br><br>"
                . "Welcome to <b>HiredHero AI</b> — we’re excited to have you on board! 🎉<br><br>"
                . "✅ Start your first mock interview — get real-time feedback and improve instantly.<br><br>"
                . "✅ Track your progress with insights designed to help you succeed.<br><br>"
                . "✅ Your journey to career success starts here — and we’ll be with you every step of the way.<br><br>"
                . "<a href='".route('user.login')."' style='background:#4CAF50;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;'>Access Your Dashboard</a><br><br>"
                . "Thanks again for joining HiredHero AI. We’re excited to see you crush your next interview!<br><br>"
                . "Best,<br>The HiredHero AI Team";

            if ($gs->is_smtp == 1) {
                $data = [
                    'to'      => $to,
                    'subject' => $subject,
                    'body'    => $msg,
                ];

                $mailer = new GeniusMailer();
                $mailer->sendCustomMail2($data);
            } else {
                $headers = "From: ".$gs->from_name."<".$gs->from_email.">";

                mail($to, $subject, $msg, $headers);
            }

            return ['success' => 'Welcome Email sent successfully'];

        } catch (\Exception $e) {
            // \Log::error('Error in send_hiredhero_welcome', ['message' => $e->getMessage()]);
            return ['error' => $e->getMessage()];
        }
    }


    public static function envUpdate($key, $value, $comma = false)
    {
      $path = base_path('.env');
            $value = trim($value);
            $env = $comma ? '"'.env($key).'"' : env($key);

      if (file_exists($path)) {

          file_put_contents($path, str_replace(
              $key . '=' . $env,
                            $key . '=' . $value,
                            file_get_contents($path)
          ));
      }
    }

    
    public static function setCurrency($price) {
        $gs = GeneralSetting::findOrFail(1);
        $price = number_format($price,2);
        if($gs->currency_format == 1){
            return '$'.$price;
        }
        else{
            return $price.'$';
        }
    }
    public static function offerPrice($price) {
        $gs = GeneralSetting::findOrFail(1);
        // $price = number_format($price,2);
        if($gs->currency_format == 1){
            return '$'.$price;
        }
        else{
            return $price.'$';
        }
    }
    
    public static function getPrice($price,$number_format='') {
        if($number_format==1){
          return $price=number_format($price,2);
        }else{
          return $price=round($price,2);  
        }
        
    }

    public static function logError(\Throwable $e) {
        $appName = url('/'); // Your application's URL or name

        $errorDetails = [
            'Application' => $appName,
            'Exception Type' => get_class($e),
            'Error Code' => $e->getCode(),
            'Message' => $e->getMessage(),
            'File' => $e->getFile(),
            'Line' => $e->getLine(),
            'Time' => now()->toDateTimeString(),
        ];

        $errorMessage = "An error occurred in {$appName}:\n" . collect($errorDetails)->map(function ($value, $key) {
            return "{$key}: {$value}";
        })->implode("\n");

        \Log::channel('slack')->error($errorMessage);
        \Log::error($errorMessage);
    }


    public static function hasActivePlan($userId)
    {
       $activeOrder = Order::where('user_id', $userId)
                        ->where(function ($query) {
                            $query->where('expires_at', '>', Carbon::now())
                                  ->orWhere('payment_id', 'free-plan');
                        })
                        ->orderBy('id', 'desc')
                        ->first();
    return $activeOrder ? true : false;
    }


    public static function getActivePlan($userId)
    {
        // return Order::where('user_id', $userId)
        //             ->where('expires_at', '>', Carbon::now())
        //             ->orderBy('id', 'desc')
        //             ->first();

        return Order::where('user_id', $userId)
                ->where(function ($query) {
                    $query->where('expires_at', '>', Carbon::now())
                          ->orWhere('payment_id', 'free-plan');
                })
                ->orderBy('id', 'desc')
                ->first();
    }

    public static function getUsedInterviews($userId, $orderId)
    {
        $userActivity = UserActivity::where('user_id', $userId)
                                    ->where('order_id', $orderId)
                                    ->first();
                                    if (!$userActivity) return 0;
                                    
                                    $activities = is_string($userActivity->activities)
                                    ? json_decode($userActivity->activities, true)
                                    : $userActivity->activities;
                                    
        // dd($activities);
        return $activities['interviewAccess'] ?? 0;
    }

    public static function hasAccess($userId, $feature)
    {
        $activeOrder = self::getActivePlan($userId);
        //($activeOrder);
        if (!$activeOrder) {
            return false;
        }

        // $accessSection = json_decode($activeOrder->access_section);
       
        $accessSection = is_array($activeOrder->access_section) ? $activeOrder->access_section : json_decode($activeOrder->access_section, true);

        $activity = UserActivity::where('user_id', $userId)
                                ->where('order_id', $activeOrder->id)
                                ->first();
        if ($activity && is_string($activity->activities)) {
            $activity->activities = json_decode($activity->activities, true);
        }
        
        $usedCount = $activity->activities[$feature] ?? 0;
        $limit = $accessSection[$feature]['limit'] ?? 0;

        return $usedCount < $limit;
    }



    public static function recordActivity($userId, $feature)
    {
        try{
        $activeOrder = self::getActivePlan($userId);

        if (!$activeOrder) {
            return;
        }

        $userActivity = UserActivity::firstOrNew([
            'user_id' => $userId,
            'order_id' => $activeOrder->id,
        ]);

       if (is_string($userActivity->activities)) {
            $activities = json_decode($userActivity->activities, true);
        } else {
            $activities = $userActivity->activities ?? [];
        }
        $activities[$feature] = ($activities[$feature] ?? 0) + 1;

        $userActivity->activities = json_encode($activities);

        $userActivity->save();
        }catch(\Exception $e){
    self::logError($e);
}
    }


    public static function welcomeEmailToAdmin($user){
        try {
            $gs = GeneralSetting::findOrFail(1);            
           
            $to = config('mail.admin_notification_address');
            $subject = "New User Registration";
            $msg = "A new user has registered on the site.<br><br>Name: {$user->name}<br><br>Email: {$user->email}";

           $data = [
                        'to' => $to,
                        'subject' => $subject,
                        'body' => $msg,
                    ];
            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data); 
        } catch (\Exception $e) {
            self::logError($e);
            // return ['error' => $e->getMessage()]; // Return error response
        }        
        
      
    }
    public static function welcomeEmailToUser($user){
        try {
            $gs = GeneralSetting::findOrFail(1);            
           
            $to = $user->email;
            $subject = "Welcome to {$gs->name}";
            $msg ="
                <h1>Welcome to {$gs->name}, {$user->name}!</h1>
                <p>Thank you for registering at the Hired Hero AI Skills Enhancement Platform! We are thrilled to have you join our community of professionals dedicated to mastering their interview and presentation skills.</p>
                <p>Here at Hired Hero, we provide you with the tools and feedback needed to excel in your career. Whether you are preparing for interviews or refining your presentation skills, our AI-driven system offers personalized insights to help you succeed.</p>
            ";

           $data = [
                        'to' => $to,
                        'subject' => $subject,
                        'body' => $msg,
                    ];
            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data); 
        } catch (\Exception $e) {
            self::logError($e);
            // return ['error' => $e->getMessage()]; // Return error response
        }        
        
      
    }

    public static function subscriptionEmailToAdmin($user, $package)
    {
        try {
            $gs = GeneralSetting::findOrFail(1);

            $to = env('ADMIN_NOTIFICATION_ADDRESS');
            $subject = "New Subscription";
            $msg = "A user has subscribed to a package.<br><br>Name: {$user->name}<br><br>Email: {$user->email}<br><br>Package: {$package->name}";

            $data = [
                'to' => $to,
                'subject' => $subject,
                'body' => $msg,
            ];

            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data); 

            // Dispatch job to send email
            // SendSubscriptionEmailJob::dispatch($data);

        } catch (\Exception $e) {
            self::logError($e);
            // return ['error' => $e->getMessage()]; // Return error response
        }
    }

        public static function extractTextFromResume($file)
        {
            $ext = strtolower($file->getClientOriginalExtension());
            $path = $file->getRealPath();

            if ($ext === 'pdf') {
                $parser = new Parser();
                $pdf    = $parser->parseFile($path);
                return $pdf->getText();
            }

            if (in_array($ext, ['doc', 'docx'])) {
                $doc = \PhpOffice\PhpWord\IOFactory::load($path);
                $text = '';
                foreach ($doc->getSections() as $section) {
                    foreach ($section->getElements() as $el) {
                        if (method_exists($el, 'getText')) {
                            $text .= $el->getText().' ';
                        }
                    }
                }
                return $text;
            }

            return '';
        }

    /**
     * Check if demo mode is enabled
     */
    public static function isDemo(): bool
    {
        try {
            $setting = GeneralSetting::find(1);
            return $setting ? (bool) $setting->demo_mode : false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get demo mode status (alias for isDemo)
     */
    public static function demo_mode(): bool
    {
        return self::isDemo();
    }

    /**
     * Get general settings (with demo mode support)
     */
    public static function getGeneralSettings()
    {
        // In demo mode, return merged data (demo overrides real)
        if (self::demo_mode()) {
            $realSettings = GeneralSetting::find(1);
            $demoData = \App\Models\DemoSettings::getBySection('general_settings');
            
            if (!empty($demoData)) {
                // Merge demo data over real settings
                foreach ($demoData as $key => $value) {
                    $realSettings->$key = $value;
                }
            }
            return $realSettings;
        }

        return GeneralSetting::find(1);
    }

    /**
     * Get social settings (with demo mode support)
     */
    public static function getSocialSettings()
    {
        // In demo mode, return merged data
        if (self::demo_mode()) {
            $realSettings = \App\Models\SocialSetting::find(1);
            $demoData = \App\Models\DemoSettings::getBySection('social_settings');
            
            if (!empty($demoData)) {
                foreach ($demoData as $key => $value) {
                    $realSettings->$key = $value;
                }
            }
            return $realSettings;
        }

        return \App\Models\SocialSetting::find(1);
    }

    /**
     * Get profile data (with demo mode support) 
     */
    public static function getDemoProfileData()
    {
        if (self::demo_mode()) {
            return \App\Models\DemoSettings::getBySection('profile_settings');
        }
        return [];
    }

    /**
     * Get pages (with demo mode support)
     */
    public static function getDemoPages()
    {
        if (self::demo_mode()) {
            return \App\Models\DemoSettings::getBySection('pages');
        }
        return [];
    }

    // ========== PROMO CODE HELPERS ==========

    /**
     * Validate promo code for a user
     */
    public static function validatePromoCode(string $code, $user = null)
    {
        if (!$user) {
            $user = \Auth::user();
        }

        $promoCodeService = app(\App\Services\PromoCodeService::class);
        return $promoCodeService->validatePromoCode($code, $user);
    }

    /**
     * Calculate discount for an amount with a promo code
     */
    public static function calculatePromoDiscount(float $amount, \App\Models\PromoCode $promoCode)
    {
        $promoCodeService = app(\App\Services\PromoCodeService::class);
        return $promoCodeService->calculateDiscount($amount, $promoCode);
    }

    /**
     * Apply promo code to user for an amount
     */
    public static function applyPromoCode(\App\Models\PromoCode $promoCode, $user = null, float $amount = 0)
    {
        if (!$user) {
            $user = \Auth::user();
        }

        $promoCodeService = app(\App\Services\PromoCodeService::class);
        return $promoCodeService->applyPromoCode($promoCode, $user, $amount);
    }

    /**
     * Check if promo code exists
     */
    public static function promoCodeExists(string $code): bool
    {
        $promoCodeService = app(\App\Services\PromoCodeService::class);
        return $promoCodeService->codeExists($code);
    }

    /**
     * Get promo code by code
     */
    public static function getPromoCode(string $code): ?\App\Models\PromoCode
    {
        $promoCodeService = app(\App\Services\PromoCodeService::class);
        return $promoCodeService->getByCode($code);
    }

    /**
     * Get user's active promo codes
     */
    public static function getUserPromoCodes($user = null)
    {
        if (!$user) {
            $user = \Auth::user();
        }

        $promoCodeService = app(\App\Services\PromoCodeService::class);
        return $promoCodeService->getUserActivePromoCodes($user);
    }
}