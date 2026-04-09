@extends('front.layouts.app')
@section('title') Orders Purchased @endsection
@section('css')
<style>
    table.dataTable>thead .sorting:after, table.dataTable>thead .sorting_asc:after, table.dataTable>thead .sorting_asc_disabled:after, table.dataTable>thead .sorting_desc:after, table.dataTable>thead .sorting_desc_disabled:after,table.dataTable>thead .sorting:before, table.dataTable>thead .sorting_asc:before, table.dataTable>thead .sorting_asc_disabled:before, table.dataTable>thead .sorting_desc:before, table.dataTable>thead .sorting_desc_disabled:before{
        content:unset !important;
    }
</style>


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
                        <div class="card" id="orderList">
                            <div class="card-header  border-0">
                                <div class="d-flex align-items-center">
                                    <h5 class="card-title mb-0 flex-grow-1">Order History</h5>
                                    <div class="flex-shrink-0">

                                        {{-- <button type="button" class="btn btn-info"><i
                                                class="ri-file-download-line align-bottom me-1"></i> Export</button> --}}

                                    </div>
                                </div>
                            </div>
                            

                            <div class="card-body pt-0  table-responsive">
                                <div>
                                    <div class=" mb-1">
                                        {{-- @if($datas->count()>0) --}}
                                        
                <table id="geniustable" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">

                                            <thead class="">
                                                <tr class="text-uppercase">
                                                    <th>Plan Name</th>
                                                    <th>Amount</th>
                                                    <th>Activities</th>
                                                    <th>Expiry Date</th>
                                                    {{-- <th>Action</th> --}}

                                                </tr>
                                            </thead>

                                        </table>
                                       
                                       
                                    </div>

                                </div>

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
<script>
    $(function() {
        $('#geniustable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('user.orders.purchased.datatables') !!}',
            columns: [
                { data: 'plan_name', name: 'plan_name' },
                { data: 'amount', name: 'amount' },
                { data: 'activities', name: 'activities' },
                { data: 'expires_at', name: 'expires_at' },
                // { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>

@endsection