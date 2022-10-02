@extends('admin.layout.main')
@section('css')
<!-- DataTables -->
<link href="/assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="/assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />


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
                            <th>Grand Total</th>
                            <th>Percent Discount</th>
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
<!-- Required datatable js -->
<script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<!-- Buttons examples -->
<script src="/assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="/assets/plugins/datatables/buttons.bootstrap4.min.js"></script>
<script src="/assets/plugins/datatables/jszip.min.js"></script>
<script src="/assets/plugins/datatables/pdfmake.min.js"></script>
<script src="/assets/plugins/datatables/vfs_fonts.js"></script>
<script src="/assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="/assets/plugins/datatables/buttons.print.min.js"></script>
<script src="/assets/plugins/datatables/buttons.colVis.min.js"></script>
<!-- Responsive examples -->
<script src="/assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="/assets/plugins/datatables/responsive.bootstrap4.min.js"></script>

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
                {data: 'grand_total'},
                {data: 'percent_discount'},
                {data: 'status'},
                {data: 'id'},
            ],
            columnDefs: [
                {
                    targets: [4],
                    searchable: true,
                    orderable: true,
                    render: function(data, type, row, meta){
                        let status = row.status;
                        let css_class = 'badge-warning';
                        if (status == 'converted to order') {
                            css_class = 'badge-success';
                        }

                        return `<span class="badge ${css_class}">${status}</span>`;
                    }
                },
                {
                    targets: [5],
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta){
                        let a = `<a class="badge badge-pill badge-outline-primary" href="/quotations/${row.uuid}/edit">View</a>&nbsp;&nbsp;`;
                        
                        return a;
                    }
                },
    
            ]
        });
    })(jQuery);
</script>
@endsection