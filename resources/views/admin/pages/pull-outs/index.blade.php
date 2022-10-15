@extends('admin.layout.main')
@section('css')
@include('admin.layout.datatable-css')
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <h4 class="mt-0 header-title">Pull-Out Requests</h4>
                <p class="text-muted mb-4 font-13">
                    Manage Pull-Out Requests.
                    <a href="{{route('admin.pullout.get.add.new')}}" class="btn btn-outline-primary btn-round waves-effect waves-light">Add new</a>
                </p>

                <div class="table-responsive">
                    <table id="product-datatable" class="table mb-0 table-centered">
                        <thead>
                        <tr>
                            <th>POR No</th>
                            <th>Business Name</th>
                            <th>Salesman</th>
                            <th>Requested By</th>
                            <th>Approved By</th>
                            <th>Returned By</th>
                            <th>Counter Checked By</th>
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
        $('#product-datatable').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{route('admin.pullout.get.datatable')}}",
                data: function(data){
    
                }
            },
    
            columns: [
                {data: 'por_no'},
                {data: 'business_name'},
                {data: 'salesman'},
                {data: 'requested_by'},
                {data: 'approved_by'},
                {data: 'returned_by'},
                {data: 'counter_checked_by'},
                {data: 'id'},
            ],
            columnDefs: [
    
                {
                    targets: [7],
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta){
                        let a = `<a class="badge badge-pill badge-outline-primary badge-medium" href="/products/edit/${row.uuid}">View</a>&nbsp;&nbsp;`;
                        return a;
                    }
                },
    
            ]
        });
    })(jQuery);
</script>
@endsection