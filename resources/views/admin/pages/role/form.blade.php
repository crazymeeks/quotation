@extends('admin.layout.main')

@section('content')
<!-- end page title end breadcrumb -->
<div class="row">
    <div class="col-12">
    <div class="card">
        <div class="card-body">

            <h4 class="mt-0 header-title">{{$header_title}}</h4>
            <p class="text-muted mb-4 font-13">Create or Update system role.</p>

            <form id="frm">
                @if($role->id !== null)
                <input type="hidden" id="id" value="{{$role->id}}">
                @endif
                <div class="form-group">
                    <label>Permission</label>
                    <select id="permission" name="permission" class="form-control">
                        <option selected disabled>--select--</option>
                        @foreach($permissions as $permission)
                            <option @if($role->permission_id === $permission->id) selected @endif value="{{$permission->id}}">{{$permission->title}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Title</label>
                    <div>
                        <input type="text" id="title" name="title" class="form-control" required="" value="{{$role->title}}" placeholder="Enter role title">
                    </div>
                </div>

                <div class="form-group mb-0">
                    <div>
                        <button type="submit" id="submit" class="btn btn-primary waves-effect waves-light">
                            Submit
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
    
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection

@section('js')
<script src="/assets/js/jquery-validation-1.19.5/dist/jquery.validate.min.js"></script>
<script>
    (function($){
        $('#frm').validate({
            rules: {
                permission: "required",
                title: "required"
            },
            submitHandler: function(form){
                let data = {
                    permission: $('#permission').val(),
                    title: $('#title').val(),
                };

                if ($('#id').length > 0) {
                    data.id = $('#id').val();
                }
                $.ajax({
                    url: "{{route('admin.role.post.save')}}",
                    method: "POST",
                    data: data,
                    success: function(response){
                        const {message} = response;
                        toastr.success(message);
                        setTimeout(() => {
                            window.location.href = "{{route('admin.role.get.index')}}";
                        }, 2000);
                    }
                });
            }
        });
    })(jQuery);
</script>
@endsection