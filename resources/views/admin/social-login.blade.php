@extends('admin.layouts.master')
@section('title')
   {{--  @lang('translation.basic-elements') --}}
   Social Settings
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
              <a href="{{ route('admin.password') }}"> Social  </a>
        @endslot
        @slot('title')
              Settings
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Social Login </h4>
                    <div class="flex-shrink-0">
                       
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="live-preview">
                        @include('admin.includes.alerts')
                        <form action="{{ route('admin.social-login.update') }}" method="post" >
                           @csrf
                           <div class="row g-2">
                                    <div class="card">
                                        <div class="card-body">

                                            <div class="row mb-3">
                                              <label class="col-sm-2 col-form-label text-lg-end">Facebook Client ID</label>
                                              <div class="col-sm-10">
                                                <input value="{{ env('FACEBOOK_CLIENT_ID') }}" name="FACEBOOK_CLIENT_ID" type="password" class="form-control">
                                              </div>
                                            </div>
                                            <div class="row mb-3">
                                              <label class="col-sm-2 col-form-label text-lg-end">Facebook Client Secret</label>
                                              <div class="col-sm-10">
                                                <input value="{{ env('FACEBOOK_CLIENT_SECRET') }}" name="FACEBOOK_CLIENT_SECRET" type="password" class="form-control">
                                                            <small class="d-block text-muted">URL Callback: <strong>{{url('oauth/facebook/callback')}}</strong></small>
                                              </div>
                                            </div>
                                            <fieldset class="row mb-3">
                                                  <legend class="col-form-label col-sm-2 pt-0 text-lg-end">Status</legend>
                                                  <div class="col-sm-10">
                                                    <div class="form-check form-switch form-switch-md">
                                                    <input class="form-check-input code-switcher" type="checkbox" name="facebook_login" @if ($gs->facebook_login == 1) checked="checked" @endif value="1" id="facebook_check" role="switch">
                                                   </div>
                                                  </div>
                                            </fieldset><!-- end row -->
                                            <hr>

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label text-lg-end">Twitter Client ID</label>
                  <div class="col-sm-10">
                    <input value="{{ env('TWITTER_CLIENT_ID') }}" name="TWITTER_CLIENT_ID" type="password" class="form-control">
                  </div>
                </div>

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label text-lg-end">Twitter Client Secret</label>
                  <div class="col-sm-10">
                    <input value="{{ env('TWITTER_CLIENT_SECRET') }}" name="TWITTER_CLIENT_SECRET" type="password" class="form-control">
                                <small class="d-block text-muted">URL Callback: <strong>{{url('oauth/twitter/callback')}}</strong></small>
                  </div>
                </div>

                        <fieldset class="row mb-3">
                  <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('Status') }}</legend>
                  <div class="col-sm-10">
                    <div class="form-check form-switch form-switch-md">
                     <input class="form-check-input code-switcher" type="checkbox" name="twitter_login" @if ($gs->twitter_login == 1) checked="checked" @endif value="1" role="switch">
                   </div>
                  </div>
                </fieldset><!-- end row -->

                        <hr />

                        <div class="row mb-3">
                  <label class="col-sm-2 col-form-label text-lg-end">Google Client ID</label>
                  <div class="col-sm-10">
                    <input value="{{ env('GOOGLE_CLIENT_ID') }}" name="GOOGLE_CLIENT_ID" type="password" class="form-control">
                  </div>
                </div>

               <div class="row mb-3">
                  <label class="col-sm-2 col-form-label text-lg-end">Google Client Secret</label>
                  <div class="col-sm-10">
                    <input value="{{ env('GOOGLE_CLIENT_SECRET') }}" name="GOOGLE_CLIENT_SECRET" type="password" class="form-control">
                                <small class="d-block text-muted">Get Credentials : <a target="_blank" href=" https://console.cloud.google.com/apis/credentials">Google console</a>
                               </small>
                                <small class="d-block text-muted">URL Callback: <strong>{{url('oauth/google/callback')}}</strong></small>
                  </div>
                </div>

                <fieldset class="row mb-3">
                  <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('Status') }}</legend>
                  <div class="col-sm-10">
                    <div class="form-check form-switch form-switch-md">
                     <input class="form-check-input code-switcher" type="checkbox" name="google_login" @if ($gs->google_login == 1) checked="checked" @endif value="1" role="switch">
                   </div>
                  </div>
                </fieldset><!-- end row -->


                                           
                                        </div>
                                    </div>

                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-success">Update</button>
                                        </div>
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
    <script src="{{ URL::asset('/assets/libs/prismjs/prismjs.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
