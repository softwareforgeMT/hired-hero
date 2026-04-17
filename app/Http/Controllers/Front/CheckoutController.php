<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Games;
use App\Models\Product;
use App\Models\PaymentGateway;
use App\CentralLogics\ProductLogic;
use App\CentralLogics\Helpers;
class CheckoutController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth:sanctum,web');
        $this->request = $request;
    }
    public function cartStore(Request $request)
    {
      $validatedData = $request->validate([
        'product_sku' => 'required|exists:products,sku',
        'quantity' => 'required|integer|min:1',
      ]);
      $product_sku = $request->input('product_sku');
      $quantity = $request->input('quantity');
      $product = Product::where('sku', $product_sku)->active()->firstOrFail();

        if ($quantity < $product->min_order_quantity) {
            return redirect()->back()->with('error', 'Invalid quantity.');
        }

        if ($quantity > $product->stock) {
            return redirect()->back()->with('error', 'Requested quantity is not available in stock.');
        }
      $cart = [
        'product_sku' => $product->sku,
        'quantity' => $quantity,
      ];
      session()->put('cart', $cart);
    //   return redirect()->route('front.checkout');
      return response()->json(['message' => 'Product added to cart successfully.']);
    }

    public function checkout()
    {
        $cartValidate = ProductLogic::cartValidate();
        if (is_array($cartValidate)) {
            $initialprice = $cartValidate['initialprice'];
            $checkoutfee = $cartValidate['checkoutfee'];
            $totalprice = $cartValidate['totalprice'];
            $product = $cartValidate['product'];
            $quantity = $cartValidate['quantity'];
            $paymentgateways=PaymentGateway::where('enabled',1)->get();

            return view('front.checkout', compact('initialprice','product','totalprice','checkoutfee','paymentgateways'));
        }
        else{
            return $cartValidate;
        }

    }

    public function processPayment(Request $request)
    {   
        
        $validatedData = $request->validate([
            // 'game_username' => 'required',
            'payment_gateway_name' => 'required',
        ]);
        $paymentGateway=$request->payment_gateway_name;
        switch ($paymentGateway) {
            case 'Wallet':                           
                return app(WalletController::class)->processPayment($request);
            case 'Paypal':
                return app(PaypalController::class)->processPayment($request);
            case 'Stripe':
                return app(StripeController::class)->processPayment($request);
            // Add cases for other payment methods here
            default:
                return back()->with('error', 'Invalid payment method.');
        }
    }

    // public function calculateCheckoutFee(Request $request)
    // {
    //    $checkoutfee=ProductLogic::calculateCheckoutFee($request->totalprice,$request->paymentMethod);      
    //    $totalPrice=Helpers::setCurrency($request->totalprice+$checkoutfee);
    //    $checkoutfee=Helpers::setCurrency($checkoutfee);
    //    return response()->json(['checkoutfee' => $checkoutfee,'totalPrice' => $totalPrice]);
    // }

}
