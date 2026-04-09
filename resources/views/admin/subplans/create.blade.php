@extends('admin.layouts.master')
@section('title')
   {{--  @lang('translation.basic-elements') --}}
   Subscriptions Plans
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
              <a href="{{ route('admin.subplan.index') }}"> Subscription Plans </a>
        @endslot
        @slot('title')
            Create
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Create Subscription Plan </h4>
                    <div class="flex-shrink-0">
                       
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="live-preview">
                         @include('admin.includes.alerts')
                        <form action="{{ route('admin.subplan.store') }}" method="post" >
                            @csrf
                            <div class="row gy-4">
                                <div class="col-xxl-3 col-md-4">
                                    <div>
                                        <label for="basiInput" class="form-label"> Name</label>
                                        <input type="text" class="form-control" id="basiInput" name="second_name" required>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-md-2">
                                    <div>
                                        <label for="basiInput2" class="form-label"> Price</label>
                                        <input type="number" name="price" step="0.001" class="form-control" id="basiInput2" required>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-md-2">
                                    <div>
                                        <label for="basiInput2" class="form-label"> Free Trial Days</label>
                                        <input type="number" name="free_trial" step="" class="form-control" id="basiInput2" value="0" required>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-md-4">
                                    <div>
                                        <label for="basiInput2" class="form-label"> Time Interval</label>
                                        <select class="form-select" name="interval" required>
                                            <option value=""> Select Interval</option>
                                            <option value="weekly" >Weekly</option>
                                            <option value="monthly" >Monthly</option>
                                            <option value="quarterly">3 Months</option>
                                            <option value="biannually" >6 Months</option>
                                            <option value="yearly">Yearly</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-4"> 
                                             <label for="" class="form-label"> Add Subscription Features</label>                               
                                            
                                                @foreach($subfeatures as $key=>$subfeature)
                                                <div class="col-md-4">
                                                <div class="form-check form-check-outline form-check-primary mb-3">
                                                    <input class="form-check-input" type="checkbox" id="formChecksubfeature{{$key}}" name="features[]" value="{{$subfeature->id}}">
                                                    <label class="form-check-label" for="formChecksubfeature{{$key}}">
                                                       {{$subfeature->name}}
                                                    </label>
                                                </div>
                                              {{--   @if((count($subfeatures)<5))
                                                     </div><div class="col-md-4">
                                                @elseif ( ( ($loop->iteration % 4) == 0 ) && (!$loop->last) )
                                                         </div><div class="col-md-4">
                                                @endif --}}
                                                </div>
                                                @endforeach 
                                            
                                 
                                </div>
                                <!--end col-->
                                <div class="col-xxl-3 col-md-12">
                                    <div>
                                        <label for="exampleFormControlTextarea5" class="form-label">Description</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea5" name="details" rows="3"></textarea>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-12">
                                    <button class="btn btn-primary" type="submit">Submit form</button>
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
