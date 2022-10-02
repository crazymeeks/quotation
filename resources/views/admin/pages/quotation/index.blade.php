@extends('admin.layout.main')

@section('css')
@include('admin.layout.datatable-css')
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="mt-0 header-title">Quotations</h4>
                <p class="text-muted mb-4 font-13">List of quotations.
                <span>
                    <a href="{{route('admin.quotation.get.new')}}" class="btn btn-outline-primary btn-round waves-effect waves-light"><i class="dripicons-plus"></i>Create quotation</a>
                </span>
                </p>

                <table id="quotationsDatatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@include('admin.pages.quotation.modal.quotation-histories-container')
@endsection

@section('js')
@include('admin.layout.datatable-js')
<script type="text/javascript">
    (function($){
        $('#quotationsDatatable').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{route('admin.quotation.get.datatable')}}",
            },
            columns: [
                {data: 'code'},
                {data: 'customer'},
                {data: 'status'},
                {data: 'id'}
            ],
            columnDefs: [
                {
                    targets: [2],
                    searchable: true,
                    orderable: true,
                    render: function(data, type, row, meta){
                        let status = row.status;
                        let css_class = 'badge-warning';
                        if (status == 'converted to order') {
                            css_class = 'badge-success';
                        }

                        return `<span class="badge badge-small ${css_class}">${status}</span>`;
                    }
                },
                {
                    targets: [3],
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta){
                        let a = `<a class="badge badge-pill badge-outline-primary badge-medium" href="/quotations/${row.uuid}/edit">View</a>&nbsp;&nbsp;`;
                        if (row.status == 'pending') {
                            a += `<a class="badge badge-pill badge-outline-danger badge-medium delete-quotation-history" data-code="${row.code}" href="javascript:void(0);">Delete</a>&nbsp;&nbsp;`;
                        }
                        a += `<a class="badge badge-pill badge-outline-info badge-medium view-quotation-history" data-code="${row.code}" href="javascript:void(0);">Quotation History</a>`;

                        return a;
                    }
                }
            ]
        });

        // View quotation history
        $('body').on('click', '.view-quotation-history', function(e){
            let code = $(this).data('code');

            $.ajax({
                url: "{{route('admin.quotation.histories.post.show.versions')}}",
                method: "POST",
                data: {
                    code: code
                },
                success: function(response){
                    const {html} = response;
                    $('.quote-history-modal').modal('show');
                    setTimeout(() => {
                        $('.quote-history-modal').find('.modal-body').html(html);
                    }, 2000);
                }
            });
        });

        // delete quotation history
        $('body').on('click', '.delete-quotation-history', function(e){
            let code = $(this).data('code');

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
                        url: "{{route('admin.quotation.delete')}}",
                        method: "DELETE",
                        data: {
                            code: code
                        },
                        success: function(response){
                            const {html} = response;
                            toastr.success("Quote item removed.");
                            setTimeout(() => {
                                window.location.href = window.location.href;
                            }, 1500);
                        }
                    });
                }
            });
            
        });
    })(jQuery);
</script>
@endsection