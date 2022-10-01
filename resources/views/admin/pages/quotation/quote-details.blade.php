<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div id="quote-header" class="clearfix">
                            <h4>
                                Quote Details
                            </h4>
                            @if($quotation->status == null || $quotation->status == 'pending')
                            <span>
                                <a href="javascript:void(0);" data-toggle="modal" data-target=".quote-modal"><i class="dripicons-plus"></i>Add Product(s)</a>
                            </span>
                            @endif
                        </div>
                        <div id="quote-table">
                            @if(count($quoteProducts) > 0)
                                @include('admin.pages.quotation.quote-details-products', ['quoteProducts' => $quoteProducts, 'quotation' => $quotation])
                            @endif
                        </div>
                        @if($quotation->status == null || $quotation->status == 'pending')
                        <div id="quote-footer">
                            <div class="quote-footer__addproductbtn">
                                <button id="btn-save-quote" class="btn btn-primary">Save Quote</button>
                            </div>
                            <div class="quote-footer__convertbtn">
                                <button id="btn-convert-to-order" class="btn btn-primary">Convert to Order</button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>                                            
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->