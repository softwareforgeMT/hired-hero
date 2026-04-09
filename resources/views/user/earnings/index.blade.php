@extends('front.layouts.app')
@section('title') Earnings @endsection
@section('css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')


           
           <section class="section page__content">
                    <div class="container"> 
                        
                        <div class="row mt-5">
                            <div class="col-md-3">
                               @include('user.layouts.sidebar')
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card p-2">
                                            <div class="card-body text-center">
                                                <h4 class="mb-1">Your available balance is 
                                                    {{  Auth::user()->userbalance(1)}}.
                                                </h4>
                                                @if(Auth::user()->stripe_connect_key && Auth::user()->stripe_connect_status=='completed')
                                                <h4 class="mb-4">
                                                    @if(Auth::user()->userbalance()>0)Click the button below to withdraw
                                                    @endif
                                                </h4>
                                                <div>
                                                    @if(Auth::user()->userbalance()>0)
                                                    <a href="{{ route('user.sendpayment') }}" class="btn btn-primary waves-effect waves-light">
                                                         <i class="ri-bank-card-fill align-middle me-1"></i>
                                                        <span> Withdraw </span>
                                                    </a>
                                                    @else
                                                        <a href="javascript:;" class="btn btn-primary waves-effect waves-light disabled">
                                                             <i class="ri-bank-card-fill align-middle me-1"></i>
                                                            <span> Withdraw </span>
                                                        </a>
                                                    @endif
                                                    {{-- <a href="" class="btn btn-primary waves-effect waves-light">
                                                        <i class="ri-eye-fill align-middle me-1"></i>
                                                        <span> View Transaction History </span>
                                                    </a> --}}
                                                </div>
                                                @else
                                                 <h4>Add Your payment details by clicking on the below button. </h4>
                                                  <a href="{{ route('user.addpayment.gateway') }}" class="btn btn-primary waves-effect waves-light">
                                                        <i class="ri-add-line align-middle me-1"></i>
                                                        Add Payment Gateway 
                                                    </a>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header end-t-end">
                                                <h5 class="card-title mb-0">Earnings </h5>
                                                {{-- <a href="" class="btn btn-primary waves-effect waves-light">Add Listing</a> --}}
                                            </div>
                                            <div class="card-body">
                                                @include('includes.alerts')
                                                <table id="geniustable" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            
                                                            <th data-ordering="false">ID</th>                           
                                                            <th data-ordering="false">Amount</th>
                                                            {{-- <th>Price</th>                           
                                                            <th>Create Date</th>
                                                            <th>Status</th> --}}
                                                            <th>Status</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                   
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>
                        </div>
                    </div><!--end col-->
           </section>


    @endsection
    @section('script')
     <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

{{-- DATA TABLE --}}

    <script type="text/javascript">

        var table = $('#geniustable').DataTable({
               ordering: false,
               processing: true,
               serverSide: true,
               ajax: '{{ route('user.earnings.datatables') }}',
               columns: [
                        // { data: 'id', name: 'id'},
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'earning_net_user', name: 'earning_net_user'},
                        { data: 'status', searchable: false, orderable: false},
                        { data: 'date', searchable: false, orderable: false }
                     ],
                language : {
                   
                }
            });
                      

{{-- DATA TABLE ENDS--}}


</script>
    
    @endsection
