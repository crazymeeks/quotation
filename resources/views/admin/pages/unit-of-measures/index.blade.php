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

                <h4 class="mt-0 header-title">Unit of measures</h4>
                <p class="text-muted mb-4 font-13">
                    Manage Unit of measures.
                    <a href="{{route('admin.uom.add.new')}}" class="btn btn-outline-primary btn-round waves-effect waves-light">Add new</a>
                </p>

                <div class="table-responsive">
                    <table id="uom-datatable" class="table mb-0 table-centered">
                        <thead>
                        <tr>
                            <th>Title</th>
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
        $('#uom-datatable').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{route('admin.uom.datatable')}}",
                data: function(data){
    
                }
            },
    
            columns: [
                {data: 'title'},
                {data: 'id'},
            ],
            columnDefs: [
    
                {
                    targets: [1],
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta){
                        let a = `<a class="badge badge-pill badge-outline-primary badge-medium" href="/unit-of-measures/edit/${row.uuid}">View</a>&nbsp;&nbsp;`;
                        a += `<a class="badge badge-pill badge-outline-danger badge-medium icon-delete" class="icon-delete" data-id="${row.uuid}" href="javascript:void(0);">Delete</a>&nbsp;&nbsp;`;
                        return a;
                    }
                },
    
            ]
        });

        $('body').on('click', '.icon-delete', function(evt){
            let uuid = $(this).data('id');
            
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
                        url: "{{route('admin.uom.delete')}}",
                        method: "DELETE",
                        data: {
                            uuid: uuid
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