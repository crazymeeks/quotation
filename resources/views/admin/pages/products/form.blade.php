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
                            @if($product->id !== null)
                            <input type="hidden" id="id" value="{{$product->id}}">
                            @endif
                            <div class="form-group row">
                                <label for="unit_of_measure" class="col-sm-2 col-form-label">Unit of measure</label>
                                <div class="col-sm-10">
                                    <select name="unit_of_measure" class="form-control" id="unit_of_measure">
                                        <option selected disabled>--select--</option>
                                        @foreach($units as $unit)
                                        <option value="{{$unit->id}}">{{$unit->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">Company</label>
                                <div class="col-sm-10">
                                    <select name="company" class="form-control" id="company">
                                        <option selected disabled>--select--</option>
                                        @foreach($companies as $company)
                                        <option value="{{$company->id}}">{{$company->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Product name</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="name" id="name" type="text" value="{{$product->name}}" placeholder="Product name here...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="manufacturer_part_number" class="col-sm-2 col-form-label">Manufacturer's part number</label>
                                <div class="col-sm-10">
                                    <input class="form-control" id="manufacturer_part_number" type="text" value="{{$product->manufacturer_part_number}}" placeholder="Manufacture part number here...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="purchase_description" class="col-sm-2 col-form-label">Purchase description</label>
                                <div class="col-sm-10">
                                    <textarea name="purchase_description" id="purchase_description" cols="102" rows="4">{{$product->purchase_description}}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="sales_description" class="col-sm-2 col-form-label">Sales description</label>
                                <div class="col-sm-10">
                                    <textarea name="sales_description" id="sales_description" cols="102" rows="4">{{$product->purchase_description}}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="cost" class="col-sm-2 col-form-label">Cost</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="cost" id="cost" type="number" value="{{$product->cost}}" placeholder="Enter cost here...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="percent_discount" class="col-sm-2 col-form-label">Percent discount</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="percent_discount" id="percent_discount" type="number" value="{{$product->percent_discount}}" placeholder="Enter discount percentage here...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inventory" class="col-sm-2 col-form-label">Stocks/Inventory</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="inventory" id="inventory" type="number" value="{{$product->inventory}}" placeholder="Enter stocks here...">
                                </div>
                            </div>
                            
                            <div class="checkbox">
                                
                                <div class="custom-control custom-checkbox">
                                    <?php
                                    if($product->status === null || $product->status === 'active'):
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