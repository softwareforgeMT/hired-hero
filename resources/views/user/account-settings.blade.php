@extends('front.layouts.app')
@section('title') Account Settings @endsection
@section('css')


@endsection
@section('content')


           
<section class="section page__content">
    <div class="container"> 
        
        <div class="row mt-5">
            <div class="col-md-3">
                @include('user.layouts.sidebar')
            </div>
            <div class="col-md-9">
                 <div class="card mt-xxl-n5">
                    <div class="card-header">
                        <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                    <i class="fas fa-home"></i>
                                    Personal Details
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link changePassword" data-bs-toggle="tab"  href="#changePassword" role="tab">
                                    <i class="far fa-user"></i>
                                    Change Password
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-4">
                        <div class="tab-content">
                            <div class="tab-pane active" id="personalDetails" role="tabpanel">
                                <form action="{{route('user.profile.update')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @include('includes.alerts')
                                    <div class="row">
                                        <div class="col-lg-12">
                                             <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                                <img src="{!! Helpers::image($data->photo, 'user/avatar/') !!}"
                                                    class="  rounded-circle avatar-xl img-thumbnail user-profile-image"
                                                    alt="user-profile-image">
                                                <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                                    <input id="profile-img-file-input" type="file" name="photo" class="profile-img-file-input">
                                                    <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                                        <span class="avatar-title rounded-circle bg-light text-body">
                                                            <i class="ri-camera-fill"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="firstnameInput" class="form-label">
                                                   User Name</label>
                                                <input type="text" class="form-control" id="firstnameInput"
                                                    placeholder="Enter your name" name="name" value="{{$data->name}}">
                                            </div>
                                        </div>
                                       

                                      
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="emailInput" class="form-label">Email
                                                    Address</label>
                                                <input type="email" class="form-control" id="emailInput"
                                                     value="{{$data->email}}" readonly>
                                            </div>
                                        </div>


                                        @if(!$data->country_id)
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label for="phone" class="form-label">
                                                    Select Country </label>
                                                <select name="country_id" class="form-select" required>
                                                   <option value="" selected disabled>Select Country</option>
                                                   @foreach($countries as $country)
                                                   <option {{$country->id==$data->country_id?'selected':''}} value="{{$country->id}}">{{$country->country_name}}</option>
                                                   @endforeach
                                               </select>
                                            </div>
                                        </div> 
                                        @endif

                                        <!--end col-->
                                        <div class="col-lg-12">
                                            <div class="mb-3 pb-2">
                                                <label for="exampleFormControlTextarea"
                                                    class="form-label">Description</label>
                                                <textarea class="form-control" id="exampleFormControlTextarea" placeholder="Enter your description"
                                                    rows="6" name="seller_description">{{$data->seller_description}}</textarea>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-lg-12">
                                            <div class="hstack gap-2 justify-content-end">
                                                <button type="submit" class="btn btn-primary">Updates</button>
                                                <button type="button" class="btn btn-soft-success">Cancel</button>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </form>
                            </div>
                            <!--end tab-pane-->

                             <!--end tab-pane-->
                        <div class="tab-pane" id="changePassword" role="tabpanel">
                            <form action="{{ route('user.reset.submit') }}" method="post">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="oldpasswordInput" class="form-label">Old
                                                Password*</label>
                                            <input type="password" class="form-control" id="oldpasswordInput"
                                                placeholder="Enter current password" name="cpass" required>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="newpasswordInput" class="form-label">New
                                                Password*</label>
                                            <input type="password" class="form-control" id="newpasswordInput"
                                                placeholder="Enter new password" name="newpass" required>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="confirmpasswordInput" class="form-label">Confirm
                                                Password*</label>
                                            <input type="password" class="form-control" id="confirmpasswordInput"
                                                placeholder="Confirm password" name="renewpass" required>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                       {{--  <div class="mb-3">
                                            <a href="javascript:void(0);"
                                                class="link-primary text-decoration-underline">Forgot
                                                Password ?</a>
                                        </div> --}}
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">Change
                                                Password</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>

                        </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end col-->
</section>


@endsection
@section('script')
<script src="{{ URL::asset('assets/js/pages/profile-setting.init.js') }}"></script>
<script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>

@endsection
