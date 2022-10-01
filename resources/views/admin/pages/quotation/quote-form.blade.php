<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-12">
                        <form id="myform" autocomplete="off">
                            @if($quotation->id !== null)
                            <input type="hidden" id="id" value="{{$quotation->id}}">
                            @endif
                            <div class="form-group row">
                                <label for="contact_no" class="col-sm-2 col-form-label">Code: </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" readonly id="code" name="code" value="{{$code}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="customer" class="col-sm-2 col-form-label">Customer</label>
                                <div class="col-sm-10 type-ahead-div" style="position: relative;">
                                    <input class="typeahead form-control" id="customer" type="text" value="{{$quotation->customer !== null ? $quotation->customer->customer_name : null}}" placeholder="Enter customer here...">
                                    <div class="autocomplete-items">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="address" class="col-sm-2 col-form-label">Address</label>
                                <div class="col-sm-10">
                                <input class="form-control" name="address" id="address" type="text" value="{{$quotation->customer !== null ? $quotation->customer->address : null}}" placeholder="Enter address here...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="contact_no" class="col-sm-2 col-form-label">Contact No.</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="contact_no" id="contact_no" type="text" value="{{$quotation->customer !== null ? $quotation->customer->contact_no : null}}" placeholder="Enter contact number here...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="discount" class="col-sm-2 col-form-label">Discount</label>
                                <div class="col-sm-10">
                                    <div class="input-discount-box">
                                        <input class="form-control" name="discount" id="discount" type="number" value="{{$quotation->percent_discount ? $quotation->percent_discount : 0.00}}" min="0" oninput="this.value = Math.abs(this.value)" placeholder="Enter discount here...">
                                    </div>
                                    <div id="percent-discount-box" class="clearfix">
                                        <span>%</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>                                            
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->