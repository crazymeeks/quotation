@if(count($histories) <= 0)
<div>No histories found.</div>
@else
<table id="quotationHistoriesDatatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>Version</th>
        <th>Product</th>
        <th>Quantity</th>
    </tr>
    </thead>
    <tbody>
        @foreach($histories as $history)
        <tr>
            <td>{{$history->version}}</td>
            <td>{{$history->product_name}}</td>
            <td>{{$history->quantity}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif