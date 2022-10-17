@extends('admin.layout.main')
@section('css')
@include('admin.layout.datatable-css')
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <h4 class="mt-0 header-title">Products</h4>
                <p class="text-muted mb-4 font-13">
                    Manage products.
                    <a href="{{route('product.add.new')}}" class="btn btn-outline-primary btn-round waves-effect waves-light">Add new</a>
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
@include('admin.layout.datatable-js')
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
                    targets: [1],
                    searchable: true,
                    orderable: true,
                    render: function(data, type, row, meta){
                        return `Php${row.cost}`;
                    }
                },
                {
                    targets: [7],
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta){
                        let a = `<a class="badge badge-pill badge-outline-primary badge-medium" href="/products/edit/${row.uuid}">View</a>&nbsp;&nbsp;`;
                        a += `<a class="badge badge-pill badge-outline-danger badge-medium icon-delete" class="icon-delete" data-id="${row.id}" href="javascript:void(0);">Delete</a>&nbsp;&nbsp;`;
                        return a;
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