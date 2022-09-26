<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div id="quote-header">
                            <h4>Quote Details</h4>
                        </div>
                        <div id="quote-table">
                            @if(count($quoteProducts) > 0)
                                @include('admin.pages.quotation.quote-details-products', ['quoteProducts' => $quoteProducts])
                            @endif
                        </div>
                        <div id="quote-footer">
                            <div class="quote-footer__addproductbtn">
                                <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target=".bs-example-modal-lg">Add Product(s)</button>
                            </div>
                            <div class="quote-footer__convertbtn">
                                <button class="btn btn-primary">Convert to Order</button>
                            </div>
                        </div>
                    </div>
                </div>                                            
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->