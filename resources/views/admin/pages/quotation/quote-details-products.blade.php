<table class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>SubTotal</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $subTotal = 0;
        ?>
        @foreach($quoteProducts as $product)
        <?php
        $rowTotal = $product->quote_product_quantity * $product->cost;
        $subTotal += $rowTotal;
        ?>
        <tr>
            <td>{{$product->name}}</td>
            <td>PHP {{number_format($product->cost, 2)}}</td>
            <td>{{$product->quote_product_quantity}}</td>
            <td>PHP {{number_format($rowTotal, 2)}}</td>
            <td>
                <a href="javascript:void(0);" class="item-quote-edit-btn text-success" data-id="{{$product->quote_product_id}}">edit</a> &nbsp;
                <a href="javascript:void(0);" class="item-quoute-delete-btn text-danger" data-id="{{$product->quote_product_id}}">remove</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="row" style="background: #F1F6F7; padding: 4px 4px; box-sizing: border-box; margin-left: 0px; margin-bottom: 10px; width: 100%;">
    <div class="col-md-9"></div>
    <div class="col-md-3">
        <div>SubTotal: <strong>PHP {{number_format($subTotal, 2)}}</strong></div>
        <div>Discount: <strong>{{$discount}}%</strong></div>
        <div>Total: <strong>PHP {{number_format((get_discount_price($subTotal, $discount)), 2)}}</strong></div>
    </div>
</div>