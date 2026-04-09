@extends('admin.layouts.master')
@section('title')
   {{--  @lang('translation.basic-elements') --}}
   General Settings
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
              <a href="{{ route('admin.generalsettings') }}"> General  </a>
        @endslot
        @slot('title')
            Settings
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">General Settings </h4>
                    <div class="flex-shrink-0">
                       @if(app('App\CentralLogics\Helpers')::demo_mode())
                            <span class="badge bg-warning">DEMO MODE - CHANGES SAVED TO DEMO DB</span>
                       @endif
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="live-preview">
                         @include('admin.includes.alerts')
                        @php
                            $displaySettings = app('App\CentralLogics\Helpers')::getGeneralSettings();
                        @endphp
                        <form action="{{ route('admin.generalsettings.update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row gy-4">
                                @if($gs->dev_check==1)
                                <div class="col-lg-4"> 
                                    <h5 class="fw-semibold mb-3">Website Favicon</h5>                       
                                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                        <img src="{!! Helpers::image($displaySettings->favicon, '/') !!} "
                                            class="  rounded-circle avatar-xl img-thumbnail user-profile-image"
                                            alt="user-profile-image">
                                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                            <input id="profile-img-file-input" type="file" class="profile-img-file-input" name="favicon" accept="image/png, image/gif, image/jpeg" 
                                                @if(app('App\CentralLogics\Helpers')::demo_mode()) disabled @endif />
                                            <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                                <span class="avatar-title rounded-circle bg-light text-body">
                                                    <i class="ri-camera-fill"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4"> 
                                    <h5 class="fw-semibold mb-3">Logo Light</h5>
                                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                         <img src="{!! Helpers::image($displaySettings->logo_light, 'logo/') !!}"
                                            class="img-thumbnail image-previewable"
                                            alt="user-profile-image">
                                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                            <input id="img-file-input1" style="display: none;" type="file" class="img-file-input" name="logo_light" accept="image/png, image/gif, image/jpeg" />
                                            <label for="img-file-input1" class="profile-photo-edit avatar-xs">
                                                <span class="avatar-title rounded-circle bg-light text-body">
                                                    <i class="ri-camera-fill"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4"> 
                                    <h5 class="fw-semibold mb-3"> Logo Dark</h5>
                                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                         <img src="{!! Helpers::image($displaySettings->logo_dark, 'logo/') !!}"
                                            class=" img-thumbnail image-previewable"
                                            alt="user-profile-image">
                                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                            <input id="img-file-input" style="display: none;" type="file" class="img-file-input" name="logo_dark" accept="image/png, image/gif, image/jpeg" />
                                            <label for="img-file-input" class="profile-photo-edit avatar-xs">
                                                <span class="avatar-title rounded-circle bg-light text-body">
                                                    <i class="ri-camera-fill"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4"> 
                                    <h5 class="fw-semibold mb-3">Logo light 2</h5>
                                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                         <img src="{!! Helpers::image($displaySettings->logo_light2, 'logo/') !!}"
                                            class=" img-thumbnail image-previewable"
                                            alt="user-profile-image">
                                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                            <input id="img-file-input3" style="display: none;" type="file" class="img-file-input" name="logo_light2" accept="image/png, image/gif, image/jpeg" />
                                            <label for="img-file-input3" class="profile-photo-edit avatar-xs">
                                                <span class="avatar-title rounded-circle bg-light text-body">
                                                    <i class="ri-camera-fill"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4"> 
                                    <h5 class="fw-semibold mb-3">Logo Dark 2</h5>
                                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                         <img src="{!! Helpers::image($displaySettings->logo_dark2, 'logo/') !!}"
                                            class=" img-thumbnail image-previewable"
                                            alt="user-profile-image">
                                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                            <input id="img-file-input4" style="display: none;" type="file" class="img-file-input" name="logo_dark2" accept="image/png, image/gif, image/jpeg" />
                                            <label for="img-file-input4" class="profile-photo-edit avatar-xs">
                                                <span class="avatar-title rounded-circle bg-light text-body">
                                                    <i class="ri-camera-fill"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                {{-- <div class="col-xl-6 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label"> Intro Video</label>
                                        <input type="file" class="form-control" id="basiInput" name="intro_video"  >
                                    </div>
                                    @if($data->intro_video)
                                    <a href="{{$data->intro_video?asset('/assets/dynamic/images/'.$data->intro_video):URL::asset('assets/dynamic/images/users/user-dummy-img.jpg')}}">Click Here to Download</a>
                                    @endif
                                </div> --}}


                                <div class="col-xl-6 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label"> Website Name</label>
                                        <input type="text" class="form-control" id="basiInput" name="name" value="{{ $displaySettings->name }}" required>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label"> Website Slogan</label>
                                        <input type="text" class="form-control" id="basiInput" value="{{ $displaySettings->slogan }}"  name="slogan" required>
                                    </div>
                                </div>


                              
                               
                                <!--end col-->
                                <div class="col-12">
                                    <button class="btn btn-primary" type="submit">
                                        @if(app('App\CentralLogics\Helpers')::demo_mode())
                                            <i class="ri-database-2-line me-1"></i> Save to Demo Database
                                        @else
                                            Submit form
                                        @endif
                                    </button>
                                </div>
                                <!--end col-->
                            </div>
                        </form>
                        <!--end row-->
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection
@section('script')
    
@endsection
