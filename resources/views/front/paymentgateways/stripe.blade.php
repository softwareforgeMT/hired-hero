@extends('front.layouts.app')
@section('title') Pay with Stripe @endsection
<script src="https://js.stripe.com/v3/"></script>
@section('css')

<link href="{{ URL::asset('common_assets/css/payment.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')


<section class="section page__content">
    <div class="container">
        <div class="checkout-section mt-5">
            <div class="row">
                <div class="col-md-8">
                    <form id="stripeForm" method="post" action="{{route('front.checkout.store')}}">
                        @csrf
                        @include('includes.alerts')
                        <div class="card">
                            <div class="card-body">
                                <h4>Billing details</h4>
                                <p><strong>Credit card</strong><br>
                                </p>
                                <input type="hidden" name="game_username" value="{{$request->game_username}}">
                                <input type="hidden" name="payment_gateway_name"
                                    value="{{$request->payment_gateway_name}}">


                                @if (auth()->user()->hasStripeId())
                                <input type="hidden" name="payment_method" value="{{$paymentMethod}}">
                                <div class="card mb-4 " id="savedCard"
                                    style="border: 1px solid #2a2f34;border-radius: 5px;">
                                    <div class="card-body d-flex justify-content-between align-items-start">
                                        <p class="card-text">

                                            <img src="{{ asset('/images/payments/brands/'.strtolower(auth()->user()->pm_type).'.svg')}}"
                                                class="mr-1">
                                            <strong class="text-capitalize">{{ auth()->user()->pm_type }}</strong> <br>
                                            •••• •••• •••• {{ auth()->user()->pm_last_four }}
                                        </p>
                                        <a href="javascript:;" class="btn g2z-btn-primary"
                                            id="changesavedCard">Change</a>
                                    </div>
                                </div>
                                @endif
                                <div class="{{auth()->user()->hasStripeId()?'d-done':''}}" id="addnewCard">
                                    <div class="mb-3">

                                        <div class="col-lg-12 col-md-12">
                                            <div id="card-element"></div>
                                        </div>
                                        <div id="card-errors" role="alert"></div>

                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" value="1" id="save_card_details"
                                            name="save_card_details">
                                        <label class="form-check-label" for="save_card_details"><small>Save Card For
                                                Future Purchases</small></label>
                                    </div>
                                </div>


                                <div class="mt-4 w-100 d-flex">
                                    <a href="{{route('front.checkout')}}"
                                        class="btn btn-light waves-effect w-md">Back</a>

                                    <button id="{{auth()->user()->hasStripeId()?'':'card-button'}}" type="submit"
                                        class="btn g2z-btn-primary  w-90 ms-3 waves-effect waves-light paybtnn">Pay
                                        <span class="totalpricebtn">{{Helpers::setCurrency($totalprice)}}</span>
                                    </button>
                                </div>

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
    var full_name_user = "{{Auth::user()->name}}";
    var key_stripe = "{{ env('STRIPE_KEY') }}";
    var payment_card_error = "An error has occurred, try again";

    $(document).ready(function () {
        $("#changesavedCard").click(function () {
            $('#savedCard').hide();
            $('#addnewCard').show();
            $("input[name='payment_method']").remove();
            $('.paybtnn').attr('id', 'card-button');
            // Show new card fields
        });
    })
</script>
<script src="{{ asset('common_assets/js/add-payment-card.js') }}"></script>
@endsection