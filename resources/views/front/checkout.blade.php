@extends('front.layouts.app')
@section('title') Checkout @endsection
 
@section('css')

@endsection
@section('content')

           
            <section class="section page__content">
                    <div class="container"> 
                        <div class="checkout-section mt-5">
                            <div class="row">
                                <div class="col-md-8">
                                    <form method="post" action="{{route('front.checkout.store')}}">
                                     @csrf
                                     @include('includes.alerts')
                                        <div class="card">
                                            <div class="card-body">
                                                <h4>Checkout</h4>
                                                @if(in_array($product->game->category_id, [1, 3]))
                                                <p><strong>1. Delivery details</strong><br>
                                                </p>
                                                <div class="mb-3">
                                                        <label for="username" class="form-label">{{$product->game?$product->game->name:''}} username</label>
                                                        <input type="text" class="form-control" id="username" name="game_username" placeholder="Enter {{$product->game?$product->game->name:''}} username" required>
                                                </div>
                                                <p><strong>2. Select Payment Method</strong><br> Other payment methods<br>
                                                </p>
                                                @else
                                                 <p><strong>Select Payment Method</strong><br> Other payment methods<br>
                                                </p>
                                                @endif
                                                
                                                @foreach($paymentgateways as $gateway)
                                                <!-- Payment Gateways -->                                          
                                                <div class="form-check form-radio-danger mb-3">
                                                    <input data-flat-fee="{{$gateway->fee_cents}}" data-percentage-fee="{{$gateway->fee}}" class="form-check-input payment_methods" type="radio" name="payment_gateway_name" id="{{$gateway->name}}"  value="{{$gateway->name}}" {{$gateway->default==1?'checked':''}}>
                                                    <label class="form-check-label" for="{{$gateway->name}}">
                                                        @if($gateway->name=="Paypal")
                                                        <img style="width:100px" src="{{asset('images/'.$gateway->logo)}}">
                                                        @elseif($gateway->name=="Wallet")
                                                        <img style="width:20px" src="{{asset('images/'.$gateway->logo)}}">&nbsp; {{$gateway->name}}
                                                        @else
                                                        <img  src="{{asset('images/'.$gateway->logo)}}">
                                                        @endif  
                                                    </label>
                                                </div>
                                                
                                                @endforeach
                                                <button type="submit" class="btn g2z-btn-primary w-100 waves-effect waves-light">Pay <span class="totalpricebtn">{{Helpers::setCurrency($totalprice)}}</span> </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    @include('front.includes.checkout_sidebar')
                                </div>
                            </div>

                        </div>
                    </div><!--end col-->
            </section>


@endsection
@section('script')

<script type="text/javascript">
$(document).ready(function() {
    $('.payment_methods').change(function() {
        var paymentMethod = $(this).val();
        var totalPrice={{$initialprice}};

        var flat_fee = parseFloat($(this).data('flat-fee'));
        var percentage_fee = parseFloat($(this).data('percentage-fee'));   
        var fee = (totalPrice * (percentage_fee / 100)) + flat_fee;               
        var total = totalPrice + fee;
        var fee = Math.round(fee * 100) / 100;
        var total = Math.round(total * 100) / 100;
        $('.checkout_fee strong').text('$'+fee);
        $('.totalprice strong,.totalpricebtn').text('$'+total);
        

        // Make an AJAX call to retrieve the checkout fee based on the payment method
        // $.ajax({
        //     url: '',//route front.calculateCheckoutfee
        //     type: 'post',
        //     data: {
        //     _token: '{{ csrf_token() }}',
        //     totalprice: totalPrice,
        //     paymentMethod: paymentMethod
        //     },
        //     success: function(data) {
        //         // Update the checkout fee and total price elements with the updated values
        //         console.log(data.totalPrice,data.checkoutfee);
        //         $('.checkout_fee strong').text(data.checkoutfee);
        //         $('.totalprice strong,.totalpricebtn').text(data.totalPrice);
        //     }
        // });
    });
});
</script>

@endsection
