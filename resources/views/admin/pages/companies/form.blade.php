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
                        <form id="myform" autocomplete="off">
                            @if($company->id !== null)
                            <input type="hidden" id="id" value="{{$company->id}}">
                            @endif
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Name <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input class="form-control" style="text-transform: uppercase;" name="name" id="name" type="text" value="{{$company->name}}" placeholder="Enter company name here...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="address" class="col-sm-2 col-form-label">Address</label>
                                <div class="col-sm-10">
                                    <input class="form-control" style="text-transform: uppercase;" id="address" type="text" value="{{$company->address}}" placeholder="Enter address here...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <button type="submit" id="btn-save" disabled class="btn btn-primary waves-effect waves-light">Save</button>
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

        if ($('#id').length > 0) {
            $('#btn-save').prop('disabled', false);
        }


        $('#name').on('keyup', debounce((e) => {
            let $this = $(e.currentTarget);
            $('body').find('.error').html('');
            let data = {
                name: $this.val(),
            };
            if ($('#id').length > 0) {
                data.id = $('#id').val();
            }
            
            $.ajax({
                url: "{{route('admin.company.post.validate')}}",
                method: "POST",
                data: data,
                success: function(response){
                    $('#btn-save').prop('disabled', false);
                },
                error: function(xhr){
                    const {message} = xhr.responseJSON;
                    $(`<p class="text-danger error">${message}</p>`).insertAfter($this);
                    $('#btn-save').prop('disabled', true);
                }
            });
        }, 200));


        $('#myform').validate({
            rules: {
                name: "required",
            },
            submitHandler: function(form){
                let data = {
                    name: $('#name').val(),
                    address: $('#address').val() || null,
                };

                if ($('#id').length > 0) {
                    data.id = $('#id').val();
                }
                
                $.ajax({
                    url: "{{route('admin.company.post.save')}}",
                    method: "POST",
                    data: data,
                    success: function(response){
                        const {message} = response;
                        toastr.success(message);
                        setTimeout(() => {
                            window.location.href = "{{route('admin.company.index')}}";
                        }, 2000);
                    }
                });
            }
        });
    })(jQuery);
</script>
@endsection