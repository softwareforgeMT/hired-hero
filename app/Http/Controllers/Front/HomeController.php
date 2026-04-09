<?php

namespace App\Http\Controllers\Front;

use App\CentralLogics\Helpers;
use App\Events\GenericEvent;
use App\Events\StatusLiked;
use App\Http\Controllers\Controller;
use App\Models\SubPlan;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\User;
use App\Notifications\GenericNotification;
use Auth;
use DB;
use Illuminate\Http\Request;
use Pusher\Pusher;
use Session;
use App\Models\UserActivity;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth as AuthFacade;

class HomeController extends Controller
{
    public function home(Request $request)
    { 
      if(!empty($request->reff))
      {
          $affiliate_user = User::where('affiliate_code','=',$request->reff)->first();
          if(!empty($affiliate_user))
          {
              $gs = GeneralSetting::findOrFail(1);
              if($gs->is_affilate == 1)
              {
                  Session::put('affilate', $affiliate_user->affiliate_code);
                  Session::put('new_user_banner', true);
                  return redirect()->route('front.index');
              }
          }

      }
        if(AuthFacade::check()){
            if(Session::has('new_user_banner')){
                Session::forget('new_user_banner');
            }      
        }
        
       return view('front.index');
      
    }
    public function testpage($value='')
    {
      $user =Auth::user();
      event(new GenericEvent('hello world Treo', $user->id));   
      $user->notify(new GenericNotification('New Order','success','Notification Mallik11', 'Notification Message','google.com'));
      // return view('forms-select');
      return view('front.index');
    }
    public function how_to_sell($value='')
    {
      return view('front.how-to-sell');
    }
    public function login($value='')
    {
      return view('front.auth.login');
    }
    public function register($value='')
    {
      return view('front.auth.register');
    }
    public function product($value='')
    {
      return view('front.products');
    }

    public function product_detail($value='')
    {
      return view('front.product-detail');
    }

   public function pricing($value = '')
    {
        $plans = SubPlan::active()->get();
        $activePlan = null;
        $showRenew = false; 

        if (Auth::check()) {
            // Get active subscription from UserSubscription table instead of Order
            $activePlan = Auth::user()->getActiveSubscription();

            if ($activePlan) {
                $accessSection = is_array($activePlan->access_section)
                    ? $activePlan->access_section
                    : json_decode($activePlan->access_section, true);

                $userActivity = UserActivity::where('user_id', Auth::id())
                    ->where('order_id', $activePlan->id)
                    ->first();

                $activities = $userActivity && is_string($userActivity->activities)
                    ? json_decode($userActivity->activities, true)
                    : ($userActivity->activities ?? []);

                // calculate remaining for
                $remainingInterviews = max(0, ($accessSection['interviewAccess']['limit'] ?? 0) - ($activities['interviewAccess'] ?? 0));
                $remainingPresentations = max(0, ($accessSection['presentationAccess']['limit'] ?? 0) - ($activities['presentationAccess'] ?? 0));

                if ($remainingInterviews <= 0 || $remainingPresentations <= 0 ) {
                    $showRenew = true;
                }

                // ✅ Check if user has no transaction and apply 20% discount
                $transaction = Transaction::where('user_id', Auth::id())
                    ->where('referrer_link', auth()->user()->referred_by)
                    ->first();

                $discount = 0;
                if (!$transaction) {
                    // No transaction found → give 20% discount
                    $discount = 20;
                }

                // Example: if you want to show discounted price
                $originalPrice = $activePlan->amount ?? 0;
                $discountedPrice = $originalPrice - ($originalPrice * ($discount / 100));

            }

        }

        return view('front.pricing', compact('plans', 'activePlan', 'showRenew'));
    }

    public function page($slug)
    {
        $page =  DB::table('pages')->where('slug',$slug)->where('status',1)->first();
        if(empty($page))
        {
            return view('errors.404');
        }

        return view('front.page',compact('page'));
    }

    public function dashboard($value='')
    {
      return view('user.dashboard');
    }

    public function ComingSoon($value='')
    {
      return view('front.comingsoon');
    }
}
