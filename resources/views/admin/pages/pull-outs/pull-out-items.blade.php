<table class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
        <tr>
            <th>Product</th>
            <th>Size</th>
            <th>Color</th>
            <th>Quantity</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $subTotal = 0;
        ?>
        @foreach($items as $item)
        
        <tr>
            <td>{{$item->name}}</td>
            <td>{{$item->size}}</td>
            <td>{{$item->color}}</td>
            <td>{{$item->quantity}}</td>
            <td>
                <a class="badge badge-pill badge-outline-danger badge-medium delete-pullout-item" data-id="{{$item->item_id}}" href="javascript:void(0);">Delete</a>&nbsp;&nbsp;
            </td>
        </tr>
        @endforeach
    </tbody>
</table>