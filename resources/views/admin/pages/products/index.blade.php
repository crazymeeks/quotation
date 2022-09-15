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

                <h4 class="mt-0 header-title">Products</h4>
                <p class="text-muted mb-4 font-13">
                    Manage products.
                    <a href="{{route('product.add.new')}}" class="btn btn-info waves-effect waves-light">Add new</a>
                </p>

                <div class="table-responsive">
                    <table id="product-datatable" class="table mb-0 table-centered">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Cost</th>
                            <th>Inventory</th>
                            <th>Percent Discount</th>
                            <th>Unit of measure</th>
                            <th>Company</th>
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
        $('#product-datatable').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{route('product.datatable')}}",
                data: function(data){
    
                }
            },
    
            columns: [
                {data: 'name'},
                {data: 'cost'},
                {data: 'inventory'},
                {data: 'percent_discount'},
                {data: 'measure'},
                {data: 'company_name'},
                {data: 'status'},
                {data: 'id'},
            ],
            columnDefs: [
    
                {
                    targets: [7],
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta){
                        let buttons = `<a style="color: #33cdff;" href="/products/edit/${row.uuid}"><i class="dripicons-document-edit"></i></a>`;;
                        buttons += `&nbsp;&nbsp;&nbsp;&nbsp;`;
                        buttons += `<a style="color: red;" href="javascript:void(0);" data-id="${row.id}" class="icon-delete"><i class="far fa-times-circle"></i></a>`;;
                        
                        return buttons;
                    }
                },
    
            ]
        });

        $('body').on('click', '.icon-delete', function(evt){
            let id = $(this).data('id');
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{route('product.delete')}}",
                        method: "DELETE",
                        data: {
                            id: id
                        },
                        success: function(response){
                            const {message} = response;
                            Swal.fire(
                                'Deleted!',
                                message,
                                'success'
                            ).then(() => {
                                window.location.href = window.location.href;
                            });
                            
                        }
                    });
                }
            });
        });
    })(jQuery);
</script>
@endsection