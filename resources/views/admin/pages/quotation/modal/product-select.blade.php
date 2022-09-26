<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myLargeModalLabel">Add Product to quote</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="quote-product">Select Product</label>
                        <select id="quote-product" class="select2 form-control mb-3 custom-select" style="width: 100%; height:36px;">
                            <option value="">Select</option>
                            @foreach($products as $product)
                                <option value="{{$product->id}}">{{$product->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6" style="margin-top: 15px;"><button id="btn-add-to-quote" class="btn btn-primary waves-effect waves-light">Add to Quoute</button></div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->