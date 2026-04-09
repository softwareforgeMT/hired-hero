@extends('admin.layouts.master')
@section('title') Orders @endsection
@section('css')
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') <a href="{{ route('admin.orders.index') }}"> Orders</a> @endslot
    @slot('title') All Orders @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header end-t-end">
                <h5 class="card-title mb-0">Orders</h5>
            </div>
            <div class="card-body">
                <table id="geniustable" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Plan Name</th>
                            <th>User Name</th>
                            <th>Amount</th>
                            <th>Expires At</th>
                            <th>Activities</th>
                           
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!--end col-->
</div>
<!--end row-->


@endsection

@section('script')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

{{-- DATA TABLE --}}
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#geniustable').DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.orders.datatables') }}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'plan_name', name: 'plan_name' },
                { data: 'user_name', name: 'user_name' },
                { data: 'amount', name: 'amount' },
                { data: 'expires_at', name: 'expires_at' },
                { data: 'activities', name: 'activities', orderable: false, searchable: false },
                
            ],
            language: {
                // Add any custom language options here
            }
        });
    });
</script>
{{-- DATA TABLE ENDS --}}
@endsection
