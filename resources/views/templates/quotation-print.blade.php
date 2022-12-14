<!DOCTYPE html>
<html>
    <head>
        <title>QUOTATION</title>
        <style>
        .line-sig{
            /* border-bottom: 1px solid black; */
            width: 100%;
        }
        .line {
            border: 1px solid black;
            width: 100%;
        }
        /* Grid */
        .row {
            margin-right: -20px;
        }

        .col {
            width: 100%;
            float: left;
            padding-right: 20px;
            box-sizing: border-box;
        }

        .col-xs-11 {
            width: 91.662%;
        }
        .col-xs-10 {
            width: 83.332%;
        }
        .col-xs-9 {
            width: 75%;
        }
        .col-xs-8 {
            width: 66.662%;
        }
        .col-xs-7 {
            width: 58.331%;
        }
        .col-xs-6 {
            width: 50%;
        }
        .col-xs-5 {
            width: 41.662%;
        }
        .col-xs-4 {
            width: 33.332%;
        }
        .col-xs-3 {
            width: 25%;
        }
        .col-xs-2 {
            width: 16.662%;
        }
        .col-xs-1 {
            width: 8.332%;
        }

        @media screen and (min-width: 768px) {
            .row {
                margin-right: -40px;
            }

            .col {
                padding-right: 40px;
            }

            .col-sm-11 {
                width: 91.662%;
            }
            .col-sm-10 {
                width: 83.332%;
            }
            .col-sm-9 {
                width: 75%;
            }
            .col-sm-8 {
                width: 66.662%;
            }
            .col-sm-7 {
                width: 58.331%;
            }
            .col-sm-6 {
                width: 50%;
            }
            .col-sm-5 {
                width: 41.662%;
            }
            .col-sm-4 {
                width: 33.332%;
            }
            .col-sm-3 {
                width: 25%;
            }
            .col-sm-2 {
                width: 16.662%;
            }
            .col-sm-1 {
                width: 8.332%;
            }
        }

        @media screen and (min-width: 1024px) {
            .col-md-11 {
                width: 91.662%;
            }
            .col-md-10 {
                width: 83.332%;
            }
            .col-md-9 {
                width: 75%;
            }
            .col-md-8 {
                width: 66.662%;
            }
            .col-md-7 {
                width: 58.331%;
            }
            .col-md-6 {
                width: 50%;
            }
            .col-md-5 {
                width: 41.662%;
            }
            .col-md-4 {
                width: 33.332%;
            }
            .col-md-3 {
                width: 25%;
            }
            .col-md-2 {
                width: 16.662%;
            }
            .col-md-1 {
                width: 8.332%;
            }
        }

        @media screen and (min-width: 1280px) {
            .col-lg-11 {
                width: 91.662%;
            }
            .col-lg-10 {
                width: 83.332%;
            }
            .col-lg-9 {
                width: 75%;
            }
            .col-lg-8 {
                width: 66.662%;
            }
            .col-lg-7 {
                width: 58.331%;
            }
            .col-lg-6 {
                width: 50%;
            }
            .col-lg-5 {
                width: 41.662%;
            }
            .col-lg-4 {
                width: 33.332%;
            }
            .col-lg-3 {
                width: 25%;
            }
            .col-lg-2 {
                width: 16.662%;
            }
            .col-lg-1 {
                width: 8.332%;
            }
        }
        .row:before,
        .row:after,
        .group:before,
        .group:after {
            content: "";
            display: table;
        }

        .row:after,
        .group:after {
            clear: both;
        }

        .row,
        .group {
            zoom: 1;
        }

        .customer-section-row {
            padding: 35px 0;
        }

        table {
            width: 100%;
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
        }

        table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }

        table td, table th {
            padding: 8px;
        }

        .quote-text {
            padding: 60px 0 20px 0;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 50x 0;
        }

        </style>
    </head>
    <body>
        <?php
        $grandTotal = 0;
        ?>
        <div id="header">
            <h3>AURORA PHILS. INC</h3>
            <p>Office Furniture Business</p>
        </div>
        <div class="line"></div>
        <div class="main-content">

            <div class="customer-section">
                <div class="row customer-section-row">
                    <div class="col col-md-6">
                        <div class="group">
                            <label for="">Customer:</label>
                            <label for="">{{$quotation->customer->customer_name}}</label>
                        </div>
                        <div class="group">
                            <label for="">Quotation No:</label>
                            <label for="">{{$quotation->code}}</label>
                        </div>
                    </div>
                    <div class="col col-md-6">
                        <div class="group">
                            <label for="">Date:</label>
                            <label for=""></label>
                        </div>
                        <div class="group">
                            <label for="">Due Date:</label>
                            <label for=""></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="line"></div>

            <div class="quote-section">
                <div class="row">
                    <div class="col col-md-12">
                        <div class="quote-text">In compliance with your request, we are please to submit our price quotation for the supply and installation of the following:</div>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col col-md-12">
                        <table>
                            <thead>
                                <th>Item No.</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Unit Price</th>
                                <th>Amount</th>
                            </thead>
                            <tbody>
                                
                                @foreach($products as $product)
                                <?php
                                $amount = $product->quantity * $product->price;
                                $grandTotal += $amount;
                                ?>
                                <tr>
                                    <td>{{$product->code}}</td>
                                    <td>{{$product->sales_description}}</td>
                                    <td>{{$product->quantity}}</td>
                                    <td>-</td>
                                    <td>Php{{number_format($product->price, 2)}}</td>
                                    <td>Php{{number_format($amount, 2)}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col col-md-9">&nbsp;</div>
                    <div class="col col-md-3">
                        <div>Net Total: Php{{number_format($grandTotal, 2)}}</div>
                        <div>Discount: {{$quotation->percent_discount}}%</div>
                        <?php
                        $grandNet = get_discount_price($grandTotal, $quotation->percent_discount);
                        ?>
                        <div>Grand Net Total: Php{{number_format($grandNet, 2)}}</div>
                    </div>
                </div>
            </div>
            <div class="footer">
                <div class="row">
                    <div class="col col-md-12">
                        <table>
                            <thead>
                                <th>Terms & Conditions</th>
                                <th>
                                    <p>50% DP., 50% C.O.D</p>
                                    <p>Any revision/reconfiguration of layout made after signing of contract shall be billed accordingly.</p>
                                </th>
                            </thead>
                        </table>
                        <table>
                            <thead>
                                <th>Validity</th>
                                <th>
                                    <p>7 days only</p>
                                </th>
                            </thead>
                        </table>

                        <table>
                            <thead>
                                <th>Submitted by:<span class="line-sig"></span></th>
                                <th>Approved by:<span class="line-sig"></span></th>
                                <th>Prepared by:<span class="line-sig"></span></th>
                                <th>Customer Approved:<span class="line-sig"></span></th>
                            </thead>
                        </table>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </body>
</html>