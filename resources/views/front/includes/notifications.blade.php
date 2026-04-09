    @if($notifications->count()>0)
    <div data-simplebar style="max-height: 300px;" class="pe-2"> 
        @foreach ($notifications as $notification)
        <div class="text-reset notification-item d-block dropdown-item position-relative">
            <div class="d-flex">
                <div class="avatar-xs me-3">
                    <span class="avatar-title bg-soft-info text-info rounded-circle fs-16">
                        <i class="bx bx-bell"></i>
                    </span>
                </div>
                <div class="flex-1">
                    <a href="{{isset($notification->data['link']) ? $notification->data['link'] : route('user.notifications') }}" class="stretched-link">
                        <h6 class="mt-0 mb-2 lh-base">
                            {{ $notification->data['title'] }}
                        </h6>
                    </a>
                    <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                        <span><i class="mdi mdi-clock-outline"></i> {{ $notification->created_at->diffForHumans() }}</span>
                    </p>
                </div>
                {{-- <div class="px-2 fs-15">
                    <input class="form-check-input" type="checkbox">
                </div> --}}
            </div>
        </div>
        @endforeach

       
        <div class="my-3 text-center">
            <a href="{{route('user.notifications')}}" class="btn btn-soft-success waves-effect waves-light">View
                All Notifications <i class="ri-arrow-right-line align-middle"></i></a>
        </div>
    </div>
    @else
    <div class="w-25 w-sm-50 pt-3 mx-auto">
        <img src="{{ URL::asset('assets/images/svg/bell.svg') }}" class="img-fluid" alt="user-pic">
    </div>
    <div class="text-center pb-5 mt-2">
        <h6 class="fs-18 fw-semibold lh-base">Hey! You have no any notifications </h6>
    </div>
    @endif


    
