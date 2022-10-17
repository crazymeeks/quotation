@extends('admin.layout.main')
@section('css')
@include('admin.layout.datatable-css')
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <h4 class="mt-0 header-title">Orders</h4>
                <p class="text-muted mb-4 font-13">
                    Manage orders.
                </p>

                <div class="table-responsive">
                    <table id="orders-datatable" class="table mb-0 table-centered">
                        <thead>
                        <tr>
                            <th>Reference #</th>
                            <th>Customer</th>
                            <!-- <th>Grand Total</th> -->
                            <!-- <th>Percent Discount</th> -->
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
</div>
@endsection

@section('js')
@include('admin.layout.datatable-js')
<script>
    (function($){
        $('#orders-datatable').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{route('admin.orders.get.datatable')}}",
                data: function(data){
    
                }
            },
    
            columns: [
                {data: 'reference_no'},
                {data: 'customer'},
                // {data: 'grand_total'},
                // {data: 'percent_discount'},
                {data: 'status'},
                {data: 'id'},
            ],
            columnDefs: [
                {
                    targets: [2],
                    searchable: true,
                    orderable: true,
                    render: function(data, type, row, meta){
                        let status = row.status;
                        let css_class = 'badge-warning';
                        if (status == 'paid') {
                            css_class = 'badge-success';
                        }

                        return `<span class="badge-small badge ${css_class}">${status}</span>`;
                    }
                },
                {
                    targets: [3],
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta){
                        let a = `<a class="badge badge-pill badge-outline-primary badge-medium" href="/orders/${row.uuid}/view">View</a>&nbsp;&nbsp;`;
                        
                        return a;
                    }
                },
    
            ]
        });
    })(jQuery);
</script>
@endsection