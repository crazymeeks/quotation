@extends('admin.layout.main')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label for="">POR No.:</label>
                    </div>
                    <div class="col-md-4">
                        <label><strong>{{$pullOut->por_no}}</strong></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="type">Pull-Out Type:</label>
                    </div>
                    <div class="col-md-4">
                        <label for="">{{$pullOut->type}}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="type">Client Business Name:</label>
                    </div>
                    <div class="col-md-4">
                        <label for="">{{$pullOut->business_name}}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="type">Address:</label>
                    </div>
                    <div class="col-md-4">
                        <label for="">{{$pullOut->address ? $pullOut->address : 'n/a'}}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="type">Contact Person:</label>
                    </div>
                    <div class="col-md-4">
                        <label for="">{{$pullOut->contact_person ? $pullOut->contact_person : 'n/a'}}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="type">Phone:</label>
                    </div>
                    <div class="col-md-4">
                        <label for="">{{$pullOut->phone ? $pullOut->phone : 'n/a'}}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="type">Salesman:</label>
                    </div>
                    <div class="col-md-4">
                        <label for="">{{$pullOut->salesman ? $pullOut->salesman : 'n/a'}}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="type">Requested by:</label>
                    </div>
                    <div class="col-md-4">
                        <label for="">{{$pullOut->requested_by ? $pullOut->requested_by : 'n/a'}}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="type">Approved by:</label>
                    </div>
                    <div class="col-md-4">
                        <label for="">{{$pullOut->approved_by ? $pullOut->approved_by : 'n/a'}}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="type">Returned by:</label>
                    </div>
                    <div class="col-md-4">
                        <label for="">{{$pullOut->returned_by ? $pullOut->returned_by : 'n/a'}}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label for="type">Counter-checked by:</label>
                    </div>
                    <div class="col-md-4">
                        <label for="">{{$pullOut->counter_checked_by ? $pullOut->counter_checked_by : 'n/a'}}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div>Pulled-out items</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <span id="pull-out-list">

                                        <table class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Size</th>
                                                    <th>Color</th>
                                                    <th>Quantity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $subTotal = 0;
                                                ?>
                                                @foreach($pullOut->pull_out_request_products as $item)
                                                
                                                <tr>
                                                    <td>{{$item->product_name}}</td>
                                                    <td>{{$item->size}}</td>
                                                    <td>{{$item->color}}</td>
                                                    <td>{{$item->quantity}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>                                            
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection

@section('js')

@endsection