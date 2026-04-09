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
                    <h4 class="card-title mb-0 flex-grow-1">Social Settings </h4>
                    <div class="flex-shrink-0">
                       @if(app('App\CentralLogics\Helpers')::demo_mode())
                            <span class="badge bg-warning">DEMO MODE - CHANGES SAVED TO DEMO DB</span>
                       @endif
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="live-preview">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h4 class="alert-heading">Validation Errors</h4>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('message'))
                            <div class="alert alert-{{ session('alert-class') == 'alert-success' ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
                                {{ session('message') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @php
                            $displaySettings = app('App\CentralLogics\Helpers')::getSocialSettings();
                        @endphp
                        <form action="{{ route('admin.social.update') }}" method="post">
                           @csrf
                           <div class="row g-2">
                                    <div class="card">
                                        <div class="card-body">
                                            
                                            <div class="mb-3 d-flex">
                                                <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                    <span class="avatar-title rounded-circle fs-16 bg-primary">
                                                        <i class="ri-facebook-fill"></i>
                                                    </span>
                                                </div>
                                                <div class="w-100">
                                                    <input type="text" class="form-control @error('facebook') is-invalid @enderror" placeholder="Facebook URL (e.g., https://facebook.com/yourpage)"
                                                        value="{{ old('facebook', $displaySettings->facebook) }}" name="facebook">
                                                    @error('facebook')
                                                        <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3 d-flex">
                                                <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                    <span class="avatar-title rounded-circle fs-16 bg-dark text-light">
                                                        <i class="ri-twitter-fill"></i>
                                                    </span>
                                                </div>
                                                <div class="w-100">
                                                    <input type="text" class="form-control @error('twitter') is-invalid @enderror" placeholder="Twitter URL (e.g., https://twitter.com/yourhandle)"
                                                        value="{{ old('twitter', $displaySettings->twitter) }}" name="twitter">
                                                    @error('twitter')
                                                        <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3 d-flex">
                                                <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                    <span class="avatar-title rounded-circle fs-16 instagram-bg">
                                                        <i class="ri-instagram-fill"></i>
                                                    </span>
                                                </div>
                                                <div class="w-100">
                                                    <input type="text" class="form-control @error('instagram') is-invalid @enderror" placeholder="Instagram URL (e.g., https://instagram.com/yourprofile)"
                                                        value="{{ old('instagram', $displaySettings->instagram) }}" name="instagram">
                                                    @error('instagram')
                                                        <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3 d-flex">
                                                <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                    <span class="avatar-title rounded-circle fs-16 youtube-bg">
                                                        <i class="ri-youtube-fill"></i>
                                                    </span>
                                                </div>
                                                <div class="w-100">
                                                    <input type="text" class="form-control @error('youtube') is-invalid @enderror" placeholder="YouTube URL (e.g., https://youtube.com/c/yourchannel)"
                                                        value="{{ old('youtube', $displaySettings->youtube) }}" name="youtube">
                                                    @error('youtube')
                                                        <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="d-flex">
                                                <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                    <span class="avatar-title rounded-circle fs-16 linkedin-bg">
                                                        <i class="ri-linkedin-fill"></i>
                                                    </span>
                                                </div>
                                                <div class="w-100">
                                                    <input type="text" class="form-control @error('linkedin') is-invalid @enderror" placeholder="LinkedIn URL (e.g., https://linkedin.com/company/yourcompany)"
                                                        value="{{ old('linkedin', $displaySettings->linkedin) }}" name="linkedin">
                                                    @error('linkedin')
                                                        <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-success">
                                                @if(app('App\CentralLogics\Helpers')::demo_mode())
                                                    <i class="ri-database-2-line me-1"></i> Save to Demo Database
                                                @else
                                                    Update
                                                @endif
                                            </button>
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
