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
                            @if($uom->id !== null)
                            <input type="hidden" id="id" value="{{$uom->id}}">
                            <input type="hidden" id="uuid" value="{{$uom->uuid}}">
                            @endif
                            <div class="form-group row">
                                <label for="title" class="col-sm-2 col-form-label">Title <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input class="form-control" style="text-transform: lowercase;" name="title" id="title" type="text" value="{{$uom->title}}" placeholder="E.g: piece">
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


        $('#title').on('keyup', debounce((e) => {
            let $this = $(e.currentTarget);
            $('body').find('.error').html('');
            let data = {
                title: $this.val(),
            };
            if ($('#id').length > 0) {
                data.id = $('#id').val();
            }
            
            $.ajax({
                url: "{{route('admin.uom.post.validate')}}",
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
                title: "required",
            },
            submitHandler: function(form){
                let data = {
                    title: $('#title').val(),
                };

                if ($('#uuid').length > 0) {
                    data.uuid = $('#uuid').val();
                }
                
                $.ajax({
                    url: "{{route('admin.uom.post.save')}}",
                    method: "POST",
                    data: data,
                    success: function(response){
                        const {message} = response;
                        toastr.success(message);
                        setTimeout(() => {
                            window.location.href = "{{route('admin.uom.index')}}";
                        }, 2000);
                    },
                    error: function(xhr){
                        console.log('xhr', xhr);
                    }
                });
            }
        });
    })(jQuery);
</script>
@endsection