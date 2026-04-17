<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\Transaction;
use App\Models\Withdraw;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Session;
use Stripe\Exception\ApiErrorException;

class EarningController extends Controller
{
    public function __construct(GeneralSetting $settings,Request $request){
        // $this->middleware('vendorhasactiveplan');
        $this->middleware('auth');
        $this->request = $request;
        $this->settings = $settings::first();
        $this->stripe_secret=env('STRIPE_SECRET');
    }

    public function datatables()
    {   
        $datas=Transaction::where('referrer_link',Auth::user()->affiliate_code)->where('status','active')->orderBy('id','desc')->get(); 
         
        return DataTables::of($datas)
                             ->addIndexColumn()
                             ->editColumn('earning_net_user', function(Transaction $data) {
                                return Helpers::setCurrency($data->earning_net_user);
                             })
                             ->addColumn('status', function(Transaction $data) {
                                 $gs=GeneralSetting::find(1);

                                 $datenow=Carbon::now()->subDays($gs->withdrawl_after_days);
                                 if($data->created_at<=$datenow){
                                    if($data->is_cleared==1){
                                        return "<span class='badge text-bg-success'>Approved & Cleared</span>";
                                    }else{
                                        return "<span class='badge badge-soft-success badge-border'>Approved</span>";
                                    }                                   
                                 }else{
                                    return "<span class='badge badge-soft-secondary badge-border'>Pending Clearance</span>";
                                 }
                                 
                             })
                             ->addColumn('date', function(Transaction $data) {
                                $gs=GeneralSetting::find(1);
                                 return Carbon::parse($data->created_at)->addDays($gs->withdrawl_after_days)->format('F d, Y');
                             })
                            ->rawColumns(['status','earning_net_user','date'])
                            ->toJson(); //--- Returning Json Data To Client Side

       
    }

    public function index()
    {  
        return view('user.earnings.index');
    }

    // Stripe Payouts
    public function addPayGateway()
    {
        $user = Auth::user();

        if (!$user->country_id) {
            Session::put('add_payment_gateway', 1);
            return redirect()->route('user.profile')->with('info', 'Please add your country!');
        }

        try {
            \Stripe\Stripe::setApiKey($this->stripe_secret);

            $countryCode = strtoupper($user->country->country_code);
            $connectKey = $user->stripe_connect_key;
            $account = null;

            // If Stripe account exists, retrieve it
            if ($connectKey) {
                try {
                    $account = \Stripe\Account::retrieve($connectKey);
                } catch (\Exception $e) {
                    \Log::warning('Stripe account retrieval failed, creating a new one.', [
                        'user_id' => $user->id,
                        'stripe_connect_key' => $connectKey,
                        'error' => $e->getMessage(),
                    ]);
                    $account = null;
                }
            }

            // Create new account if missing or country changed
            if (!$account || strtoupper($account->country) !== $countryCode) {
                $account = \Stripe\Account::create([
                    'country' => $countryCode,
                    'email' => $user->email,
                    'type' => 'express',
                    'capabilities' => [
                        'card_payments' => ['requested' => true],
                        'transfers' => ['requested' => true],
                    ],
                ]);

                $user->stripe_connect_key = $account->id;
                $user->stripe_connect_status = 'pending';
                $user->save();
            }

            // Re-check latest account state
            $account = \Stripe\Account::retrieve($user->stripe_connect_key);

            $transfersActive = ($account->capabilities->transfers ?? null) === 'active';
            $payoutsEnabled = $account->payouts_enabled ?? false;
            $hasPendingRequirements =
                !empty($account->requirements->currently_due) ||
                !empty($account->requirements->past_due);

            // Mark completed only when Stripe is truly ready
            if ($transfersActive && $payoutsEnabled && !$hasPendingRequirements) {
                $user->stripe_connect_status = 'completed';
                $user->save();

                return redirect()->route('user.earnings')->with('success', 'Payment gateway is already connected.');
            }

            // Otherwise continue onboarding
            $accountLink = \Stripe\AccountLink::create([
                'account' => $user->stripe_connect_key,
                'refresh_url' => route('user.addpayment.gateway'),
                'return_url' => route('user.returnpayment.gateway.status'),
                'type' => 'account_onboarding',
            ]);

            return redirect($accountLink->url);
        } catch (\Throwable $e) {
            \Log::error('Stripe addPayGateway error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('error', 'Unable to start payment onboarding. Please contact support.');
        }
    }
    
    public function returnConnectStatus()
    {
        try {
            $user = Auth::user();

            \Stripe\Stripe::setApiKey($this->stripe_secret);

            if (!$user->stripe_connect_key) {
                toastr()->error('Stripe account not found.');
                return redirect()->route('user.earnings');
            }

            $acc = \Stripe\Account::retrieve($user->stripe_connect_key, []);

            $transfersActive = (($acc->capabilities->transfers ?? null) === 'active');
            $payoutsEnabled = ($acc->payouts_enabled ?? false);
            $detailsSubmitted = ($acc->details_submitted ?? false);
            $chargesEnabled = ($acc->charges_enabled ?? false);

            $currentlyDue = $acc->requirements->currently_due ?? [];
            $pastDue = $acc->requirements->past_due ?? [];
            $hasPendingRequirements = !empty($currentlyDue) || !empty($pastDue);

            if ($detailsSubmitted && $chargesEnabled && $transfersActive && $payoutsEnabled && !$hasPendingRequirements) {
                $user->stripe_connect_status = 'completed';
                $user->save();

                toastr()->success('Payment gateway added successfully.');
                return redirect()->route('user.earnings');
            }

            // optional: mark restricted or pending
            $user->stripe_connect_status = 'pending';
            $user->save();

            \Log::warning('Stripe account not fully ready after onboarding return.', [
                'user_id' => $user->id,
                'stripe_account_id' => $user->stripe_connect_key,
                'details_submitted' => $detailsSubmitted,
                'charges_enabled' => $chargesEnabled,
                'transfers_capability' => $acc->capabilities->transfers ?? null,
                'payouts_enabled' => $payoutsEnabled,
                'currently_due' => $currentlyDue,
                'past_due' => $pastDue,
            ]);

            toastr()->error('Your Stripe account is not fully ready yet. Please complete all required verification details.');
            return redirect()->route('user.earnings');
        } catch (\Exception $e) {
            \Log::error('Stripe returnConnectStatus error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function sendPayUser()
    {
        try{ 
            $user = Auth::user(); 
            \Stripe\Stripe::setApiKey($this->stripe_secret);
            $connect_key = $user->stripe_connect_key;
            $balance = \Stripe\Balance::retrieve();
            $balance = $balance->available[0]->amount;
            $balance = ($balance / 100);

            $current_balance = $user->userbalance();


            if (!$user->stripe_connect_key || $user->stripe_connect_status != 'completed') {
                return redirect()->route('user.earnings')->with('info', 'Invalid Request');
            }

            if ($current_balance <= $this->settings->min_withdraw) {
                return redirect()->route('user.earnings')->with('info', 'Your balance must be greater than ' . Helpers::setCurrency($this->settings->min_withdraw));
            }

            if ($balance >= $current_balance) {
                // Payout to user
                $transfer = \Stripe\Transfer::create([
                    "amount" => $current_balance * 100,
                    "currency" => $this->settings->currency_code,
                    "destination" => $connect_key,
                    'description' => 'Payment from ' . $this->settings->name . ' To User ' . $user->name,
                ]);

                $user_remaining_balance = $user->userbalancedecrement();

                $newwithdraw = new Withdraw();
                $newwithdraw['user_id'] = $user->id;
                $newwithdraw['method'] = "Stripe";
                $newwithdraw['transfer_id'] = $transfer->id;
                $newwithdraw['balance_transaction'] = $transfer->balance_transaction;
                $newwithdraw['destination'] = $transfer->destination;
                $newwithdraw['destination_payment'] = $transfer->destination_payment;
                $newwithdraw['live_mode'] = $transfer->live_mode ? 1 : 0;
                $newwithdraw['amount'] = number_format(($transfer->amount / 100), 2);
                $newwithdraw['fee'] = 0;
                $newwithdraw['type'] = 'vendor';
                $newwithdraw['status'] = 'completed';
                $newwithdraw->save();

                return redirect()->route('user.earnings')->with('info', 'Your withdrawal has been initiated. Please allow 1-2 business days for funding.');
            } else {
                return redirect()->route('user.earnings')->with('info', 'Sorry, Insufficient Funds for Withdrawal');
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    //Stripe Payouts ends

}
