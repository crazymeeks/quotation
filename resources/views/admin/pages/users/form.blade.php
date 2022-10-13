@extends('admin.layout.main')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title">Add new</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    
                    <div class="col-xl-12">
                        <form id="myform">
                            @if($user->id !== null)
                            <input type="hidden" id="id" value="{{$user->id}}">
                            @endif
                            <div class="form-group row">
                                <label for="role" class="col-sm-2 col-form-label">Role <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="role" class="form-control" id="role">
                                        <option selected disabled>--select--</option>
                                        @foreach($roles as $role)
                                        <option {{$user->role_id == $role->id ? 'selected' : null}} value="{{$role->id}}">{{$role->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="firstname" class="col-sm-2 col-form-label">Firstname <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="firstname" id="firstname" type="text" value="{{$user->firstname}}" placeholder="Enter firstname here...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="lastname" class="col-sm-2 col-form-label">Lastname <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="lastname" id="lastname" type="text" value="{{$user->lastname}}" placeholder="Enter lastname here...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="username" class="col-sm-2 col-form-label">Username <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="username" id="username" type="text" value="{{$user->username}}" placeholder="Enter username here...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-sm-2 col-form-label">Password <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="password" id="password" type="password" value="" placeholder="Enter password here...">
                                </div>
                            </div>
                            <div class="checkbox">
                                
                                <div class="custom-control custom-checkbox">
                                    <?php
                                    if($user->status === null || $user->status === 'active'):
                                    ?>
                                    <input type="checkbox" checked class="custom-control-input status" id="customCheck2">
                                    <?php else:?>
                                        <input type="checkbox" class="custom-control-input status" id="customCheck2">
                                    <?php endif;?>
                                    <label class="custom-control-label" for="customCheck2">Set as active</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                            </div>
                        </form>
                    </div>
                </div>                                            
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection

@section('js')
<!--Wysiwig js-->
<script src="/assets/js/jquery-validation-1.19.5/dist/jquery.validate.min.js"></script>
<script type="text/javascript">
    (function($){
        $('#myform').validate({
            rules: {
                role: "required",
                firstname: "required",
                lastname: "required",
                username: "required",
                password: {
                    required: function(){
                        return $('#id').length <= 0;
                    },
                    minlength: 6
                },
            },
            submitHandler: function(form){
                let data = {
                    role: $('#role').val(),
                    firstname: $('#firstname').val(),
                    lastname: $('#lastname').val(),
                    username: $('#username').val(),
                    
                };

                if ($('.status').is(':checked')) {
                    data.status = 'active';
                }

                if ($('#id').length > 0) {
                    data.id = $('#id').val();
                    if (!$('#password').val()) {

                    } else {
                        data.password = $('#password').val();
                    }
                } else {
                    data.password = $('#password').val();
                }
                
                $.ajax({
                    url: "{{route('admin.users.post.save')}}",
                    method: "POST",
                    data: data,
                    success: function(response){
                        const {message} = response;
                        toastr.success(message);
                        setTimeout(() => {
                            window.location.href = "{{route('admin.users.index')}}";
                        }, 2000);
                    }
                });
            }
        });
    })(jQuery);
</script>
@endsection