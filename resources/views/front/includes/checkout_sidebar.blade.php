<div class="checkout_sidebar">
    <div class="card">
        <div class="card-body">
            @if($product)
            <span class="d-flex align-items-center">
                    <img class="header-profile-user" src="{!! Helpers::image($product->user->photo, 'user/avatar/') !!}">
                    <span class="text-start ms-xl-2">
                        <span class="d-xl-inline-block ms-1 fw-medium user-name-text">{{$product->user?$product->user->name:''}}</span>
                    </span>
            </span>
            <hr>
            <p>{{$product->user?$product->user->seller_description:''}} </p>
            <p class="d-flex justify-content-between mb-0"> Guaranteed delivery time 
                <strong>{{Helpers::deliveryTime($product)}}</strong>
            </p>
            @endif
            
            <hr class="mt-2 mb-2">
            <p class="d-flex justify-content-between"> Average delivery time
                <strong>20 min</strong>
            </p>
            <hr>
        </div>
    </div>

     <div class="card">
        <div class="card-body">
            <p class="d-flex justify-content-between mb-0"> Order Price 
                <strong>{{Helpers::setCurrency($initialprice)}}</strong>
            </p>
            
            <hr class="mt-2 mb-2">
            <p class="d-flex justify-content-between mb-0 checkout_fee"> Payment fees
                <strong>{{Helpers::setCurrency($checkoutfee)}}</strong>
            </p>
            <hr class="mt-2 mb-2">
            <p class="d-flex justify-content-between mb-0 totalprice"> Need to pay
                <strong>{{Helpers::setCurrency($totalprice)}}</strong>
            </p>

        </div>
    </div>
</div>    