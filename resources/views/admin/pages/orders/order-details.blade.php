@extends('admin.layout.main')
@section('css')
<!-- DataTables -->
<link href="/assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="/assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

<style>
    .customer-section-header {
        border: 1px solid #dee2e6;
        background: #dee2e6;
        padding: 20px 12px;
        text-align: center;
        font-weight: bold;
        text-transform: uppercase;
    }
    .order-number {
        text-align: center;
        font-size: 30px;
        margin-bottom: 10px;
        font-weight: bold;
    }
    .order-section--action {
        margin-bottom: 10px;
    }
</style>

@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="order-section">
                    <div class="order-number {{$order->order->status === 'paid' ? 'text-success' : 'text-normal'}}">Order #{{$order->order->reference_no}}</div>
                    @if($order->order->status === 'pending')
                    <div class="order-section--action">
                        <button type="button" data-uuid="{{$order->order->uuid}}" id="btn-paid-action" class="btn btn-outline-secondary waves-effect">SET THIS ORDER AS PAID</button>
                    </div>
                    @endif
                </div>

                <div class="customer-section">
                    <div class="customer-section-header">Customer Information</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div>Name: {{$order->customer->name}}</div>
                            <div>Contact #: {{$order->customer->contact}}</div>
                            <div>Address: {{$order->customer->address}}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 header-title">Order details</h4>
                <div class="table-responsive">
                    <table id="order-details" class="table mb-0 table-centered">
                        <thead>
                        <tr>
                            <th>Product Name #</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Company</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            ?>
                            @foreach($order->items as $o)
                                <?php
                                $total += $o->final_price * $o->quantity;
                                ?>
                                <tr>
                                    <td>{{$o->name}}</td>
                                    <td>PHP{{$o->final_price}}</td>
                                    <td>{{$o->quantity}}</td>
                                    <td>{{$o->company}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row" style="background: #F1F6F7; padding: 4px 4px; box-sizing: border-box; margin-left: 0px; margin-bottom: 10px; width: 100%;">
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <div>SubTotal: <strong>PHP {{number_format($total, 2)}}</strong></div>
                            <div>Discount: <strong>{{$order->order->discount}}%</strong></div>
                            <div>Total: <strong>PHP {{number_format((get_discount_price($total, $order->order->discount)), 2)}}</strong></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
</div>
@endsection

@section('js')
<!-- Required datatable js -->
<script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<!-- Buttons examples -->
<script src="/assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="/assets/plugins/datatables/buttons.bootstrap4.min.js"></script>
<script src="/assets/plugins/datatables/jszip.min.js"></script>
<script src="/assets/plugins/datatables/pdfmake.min.js"></script>
<script src="/assets/plugins/datatables/vfs_fonts.js"></script>
<script src="/assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="/assets/plugins/datatables/buttons.print.min.js"></script>
<script src="/assets/plugins/datatables/buttons.colVis.min.js"></script>
<!-- Responsive examples -->
<script src="/assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="/assets/plugins/datatables/responsive.bootstrap4.min.js"></script>

<script>
    (function($){
        $('#btn-paid-action').on('click', function(e){
            
            let uuid = $(this).data('uuid');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{route('admin.orders.post.paid')}}",
                        method: "PUT",
                        data: {
                            uuid: uuid,
                        },
                        success: function(response){
                            const {message} = response;
                            toastr.success(message);
                            setTimeout(() => {
                                window.location.href = "{{route('admin.orders.get.index')}}";
                            }, 1500);
                        },
                        error: function(xhr){
                            const response = xhr.responseJSON;
                            const {message} = response;
                            toastr.error(message);
                        }
                    });
                }
            });
        });
    })(jQuery);
</script>
@endsection