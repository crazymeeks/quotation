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
                            @if($pullOut->id !== null)
                            <input type="hidden" id="id" value="{{$pullOut->id}}">
                            @endif
                            <div class="form-group row">
                                <label for="type" class="col-sm-2 col-form-label">Pull-Out for <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="type" class="form-control" id="type">
                                        <option selected disabled>--select--</option>
                                        @foreach($types as $type)
                                        <option {{$pullOut->type == $type ? 'selected' : null}} value="{{$type}}">{{$type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="business_name" class="col-sm-2 col-form-label">Client Business Name <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="business_name" id="business_name" type="text" value="{{$pullOut->business_name}}" placeholder="Enter client business name here...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="address" class="col-sm-2 col-form-label">Address</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="address" id="address" type="text" value="{{$pullOut->address}}" placeholder="Enter address here...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="contact_person" class="col-sm-2 col-form-label">Contact Person</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="contact_person" id="contact_person" type="text" value="{{$pullOut->contact_person}}" placeholder="Enter contact person here...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="phone" id="phone" type="text" value="{{$pullOut->phone}}" placeholder="Enter phone/fax here...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="requested_by" class="col-sm-2 col-form-label">Requested by</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="requested_by" id="requested_by" type="text" value="{{$pullOut->requested_by}}" placeholder="Enter here...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="approved_by" class="col-sm-2 col-form-label">Approved by</label>
                                <div class="col-sm-10">
                                    <input class="form-control" id="approved_by" type="text" name="approved_by" value="{{$pullOut->approved_by}}" placeholder="Enter here...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="returned_by" class="col-sm-2 col-form-label">Returned by</label>
                                <div class="col-sm-10">
                                <input class="form-control" id="returned_by" type="text" name="returned_by" value="{{$pullOut->returned_by}}" placeholder="Enter here...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="counter_checked_by" class="col-sm-2 col-form-label">Counter-checked by</label>
                                <div class="col-sm-10">
                                <input class="form-control" id="counter_checked_by" type="text" name="counter_checked_by" value="{{$pullOut->counter_checked_by}}" placeholder="Enter here...">
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <a href="javascript:void(0);" style="color: #605daf;">Click to pull-out items.</a>
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
                unit_of_measure: "required",
                company: "required",
                name: "required",
                cost:{
                    required: true,
                    number: true
                },
                inventory:{
                    required: true,
                    number: true
                },
                percent_discount: {
                    number: true
                },
            },
            submitHandler: function(form){
                let data = {
                    unit_of_measure: $('#unit_of_measure').val(),
                    company: $('#company').val(),
                    name: $('#name').val(),
                    area: $('#area').val() || null,
                    code: $('#code').val() || null,
                    size: $('#size').val() || null,
                    color: $('#color').val() || null,
                    manufacturer_part_number: $('#manufacturer_part_number').val(),
                    sales_description: $('#sales_description').val(),
                    cost: $('#cost').val(),
                    inventory: $('#inventory').val(),
                    percent_discount: $('#percent_discount').val(),
                    status: 'inactive',
                };

                if ($('.status').is(':checked')) {
                    data.status = 'active';
                }

                if ($('#id').length > 0) {
                    data.id = $('#id').val();
                }
                
                $.ajax({
                    url: "{{route('product.save')}}",
                    method: "POST",
                    data: data,
                    success: function(response){
                        const {message} = response;
                        toastr.success(message);
                        setTimeout(() => {
                            window.location.href = "{{route('product.index')}}";
                        }, 2000);
                    }
                });
            }
        });
    })(jQuery);
</script>
@endsection