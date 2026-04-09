@extends('admin.layouts.master')
@section('title') Dashboard @endsection
@section('css')

    <link href="{{ URL::asset('assets/libs/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet">

@endsection
@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Dashboards @endslot
        @slot('title')  @endslot
    @endcomponent

    <div class="row">
        <div class="col-xxl-5">
            <div class="d-flex flex-column h-100">
                <div class="row h-100">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body p-0">
                               
                                <div class="row align-items-center">
                                    <div class="col-sm-8">
                                        <div class="p-3">
                                            <p class="fs-16 lh-base">Hi {{Auth::guard('admin')->user()->name }}, Welcome To <strong> {{$gs->name}} </strong> Admin Dashboard</p>
                                            
                                        </div>
                                    </div>
                                    <div class="col-sm-4 p-3">
                                        <div class="px-3">
                                            <img src="{{$gs->admin_logo?asset('/assets/images/logo/'.$gs->admin_logo):URL::asset('assets/images/users/user-dummy-img.jpg')}}"
                                                class="img-fluid" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end card-body-->
                        </div>
                    </div> <!-- end col-->
                </div> <!-- end row-->

               
            </div>
        </div> <!-- end col-->

    </div> <!-- end row-->

@endsection
@section('script')

@endsection
