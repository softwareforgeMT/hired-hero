<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use App\CentralLogics\ProductLogic;
use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\CentralLogics\TransactionLogic;
use Auth;
use App\Models\Order;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
class PaypalController extends Controller
{   
    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }
    public function processPayment(Request $request)
    {   
        $user=Auth::user();
        $cartValidate = ProductLogic::cartValidate($request->payment_gateway_name);
        if (is_array($cartValidate)) {
            $initialprice = $cartValidate['initialprice'];
            $checkoutfee = $cartValidate['checkoutfee'];
            $totalprice = $cartValidate['totalprice'];
            $product = $cartValidate['product'];
            $quantity = $cartValidate['quantity'];
            
            session()->put('payment_gateway_name', $request->input('payment_gateway_name')); 
            session()->put('game_username', $request->input('game_username')); 

             // Init PayPal
            try{
                $provider = new PayPalClient();
                $token = $provider->getAccessToken();
                $provider->setAccessToken($token);         
                $data = json_decode('{
                    "intent": "CAPTURE",
                    "application_context": {
                        "brand_name": "GZ2gamer",
                        "locale": "en-US",
                        "payment_method": {
                            "payer_selected": "PAYPAL",
                            "payee_preferred": "IMMEDIATE_PAYMENT_REQUIRED"
                        },
                        "return_url": "'.route('paypal.success').'",
                        "cancel_url": "'.route('paypal.cancel').'"
                    },
                    "purchase_units": [
                        {
                            "amount": {
                                "currency_code": "USD",
                                "value": "'.$totalprice.'"
                            },
                            "description": "Purchase of game items by'.auth()->user()->name.'"  
                        }
                    ]
                }', true);

                $order = $provider->createOrder($data);          
                return redirect($order['links'][1]['href']);

            } catch (\Exception $e) {
                \Log::debug($e);
                return back()->with('erorr',$e->getMessage());               
            }
            
        }
        else{
            return $cartValidate;
        }


        
    }

    public function verifyTransaction(Request $request)
    {   

        // Init PayPal
        $provider = new PayPalClient();
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

           try {
                // Get PaymentOrder using our transaction ID
                $orderResponse = $provider->capturePaymentOrder($request->token);
                $txn_id = $orderResponse['purchase_units'][0]['payments']['captures'][0]['id'];
                // Parse the custom data parameters
                parse_str($orderResponse['purchase_units'][0]['payments']['captures'][0]['custom_id'] ?? null, $data);

                if ($orderResponse['status'] && $orderResponse['status'] === "COMPLETED") {
                    $payment_status='completed';
                    
                    $request['game_username']=session()->get('game_username');
                    $request['payment_gateway_name']=session()->get('payment_gateway_name');
                    $cartValidate = ProductLogic::cartValidate($request->payment_gateway_name);
                    if (is_array($cartValidate)) {
                        $initialprice = $cartValidate['initialprice'];
                        $checkoutfee = $cartValidate['checkoutfee'];
                        $totalprice = $cartValidate['totalprice'];
                        $product = $cartValidate['product'];
                        $quantity = $cartValidate['quantity'];

                        $payment_status='completed';
                        $createOrder=OrderLogic::createOrder($request, $totalprice, $checkoutfee, $product, $quantity,$payment_status);
                        $currentUser = auth()->user();

                        if ($currentUser->referred_by) {
                            $referrer = User::where('affiliate_code', $currentUser->referred_by)->first();

                            if ($referrer) {
                                $referralBonus = $totalprice * (20 / 100);

                                // Add bonus to referrer's wallet
                                $referrer->increment('wallet', $referralBonus);

                                // Log the wallet transaction
                                Wallet::create([
                                    'user_to'        => $referrer->id,
                                    'user_by'        => $currentUser->id,
                                    'transaction_id' => $createOrder->id,
                                    'amount'         => $referralBonus,
                                    'status'         => 'credit'
                                ]);
                            }
                        }
                        $order= $createOrder;
                        $createtransaction=TransactionLogic::createTransaction($order->id,$order->user_id,$order->payment_gateway, $order->pay_amount,$order->checkout_fee,'checkout',$txn_id);
                    }else{
                         return $cartValidate;
                    }

                    return redirect()->route('user.order.purchased.show',$order->order_number)->with('success','Order placed SuccessFully');
                }
            }catch(\Exception $e){
               return redirect()->route('front.index')->with('erorr',$e->getMessage());
            }
    }

    public function cancelPaypal($value='')
    {
       return redirect()->route('front.checkout')->with('erorr',"Payment Cancelled");
    }
}
