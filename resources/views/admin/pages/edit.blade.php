@extends('admin.layouts.master')
@section('title')
   {{--  @lang('translation.basic-elements') --}}
   Page
@endsection
{{-- <style type="text/css">
    .note-editable{
        background-color:white;
    }
</style> --}}
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
           
        <a href="{{ route('admin.custompage.index') }}"> Page </a>
        @endslot
        @slot('title')
            Edit
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Edit Page </h4>
                    <div class="flex-shrink-0">
                       @if(app('App\CentralLogics\Helpers')::demo_mode())
                            <span class="badge bg-warning">DEMO MODE - CHANGES SAVED TO DEMO DB</span>
                       @endif
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="live-preview">
                        @include('admin.includes.alerts')
                        <form action="{{ route('admin.custompage.edit',$data->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row gy-4">
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label"> Page Title</label>
                                        <input type="text" name="title" class="form-control" id="basiInput" value="{{$data->title}}" required>

                                    </div>
                                </div>
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label"> Page slug</label>
                                        <input type="text" name="slug" class="form-control" id="basiInput" value="{{$data->slug}}" required>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div>
                                        <label for="basiInput" class="form-label"> Page slug</label>
                                        <textarea class="mlk-text-editor form-control" name="details">{!! $data->details!!} </textarea>

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
<script src="{{ URL::asset('assets/js/pages/profile-setting.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/prismjs/prismjs.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>


    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
     <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
    $(document).ready(function() {
          $('.mlk-text-editor').summernote({
            height:220,
          });
        
    });
    </script>
@endsection
