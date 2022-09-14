@extends('admin.layout.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <h4 class="mt-0 header-title">Roles</h4>
                <p class="text-muted mb-4 font-13">
                    Manage system roles.
                    <a href="{{route('admin.role.get.new')}}" class="btn btn-info waves-effect waves-light">Add new</a>
                </p>

                <div class="table-responsive">
                    <table class="table mb-0 table-centered">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td>{{$role->title}}</td>
                            <td>
                                <div class="dropdown d-inline-block float-right">
                                    <a class="nav-link dropdown-toggle arrow-none" id="dLabel5" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v font-20 text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel5">
                                        <a class="dropdown-item" href="{{route('admin.role.edit', ['uuid' => $role->uuid])}}">Edit</a>
                                        <a class="dropdown-item btn-delete" href="javascript:void(0);" data-id="{{$role->id}}">Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
</div>
@endsection

@section('js')
<script>
    (function($){
        $('.btn-delete').on('click', function(evt){
            evt.preventDefault();
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
                        url: "",
                        method: "DELETE",
                        data: {
                            id: id
                        },
                        success: function(response){
                            const {message} = response;
                            Swal.fire({
                                icon: 'success',
                                title: message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
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