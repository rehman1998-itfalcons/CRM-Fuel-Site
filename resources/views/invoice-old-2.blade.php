<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="http://smj.uni.keydevsdemo.com/public/bootstrap/css/bootstrap.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/css/plugins.css') }}" rel="stylesheet" type="text/css"/>
    <link href="http://smj.uni.keydevsdemo.com/public/assets/css/dashboard/dash_2.css" rel="stylesheet"
          type="text/css"/>
    <style>
        body {
            background-color: #fff !important;
            font-family: 'Quicksand' !important;
         /* 	font-size: 12px !important;*/
        }

        .table > thead > tr > th {
            color: #000;
        }


        h1, h2, h3, h4, h5, h6, p, span {
            font-family: 'Quicksand' !important;
        }

        .widget-content-area {
            -webkit-box-shadow: 0 4px 6px 0 rgba(85, 85, 85, 0.0901961), 0 1px 20px 0 rgba(0, 0, 0, 0.08), 0px 1px 11px 0px rgba(0, 0, 0, 0.06);
            -moz-box-shadow: 0 4px 6px 0 rgba(85, 85, 85, 0.0901961), 0 1px 20px 0 rgba(0, 0, 0, 0.08), 0px 1px 11px 0px rgba(0, 0, 0, 0.06);
            box-shadow: 0 4px 6px 0 rgba(85, 85, 85, 0.0901961), 0 1px 20px 0 rgba(0, 0, 0, 0.08), 0px 1px 11px 0px rgba(0, 0, 0, 0.06);
        }

        table {
            /*table-layout: fixed;*/
            /*width: 300px;*/
        }
    </style>
</head>
<body>
<div style="container">

        <div class="row justify-content-center">
            <div class="col-sm-2" style="padding: 0px;">
                <img src="{{ asset('assets/img/atlas_logo.png') }}" class="img" style="width: 150px;">
                <h4 class="text-center" style="font-weight: 800; font-size: 18px;">ABN 141 628 616 90</h4>
            </div>
        </div>

       <div  style="padding-left:30px; height: 200px;">
          
      
            <div class="col-sm-4 float-right" style="">
                <div style="background: #f3f3f3; padding: 15px; height: 200px;">
                    <p style="font-size: 16px;">Bill To:</p>
                    <h4 style="font-weight: bolder; font-size: 18px;">{{ $record->subCompany->company->name }}</h4>
                    @php
                        $email = $record->subCompany->company->emails->first();
                    @endphp
                    <p style="font-size: 16px;">{{ ($email) ? $email->email_address : '' }}</p>
                </div>
            </div>
            <div class="col-sm-4 float-right">
                <div style="background: #f3f3f3; padding: 15px; height: 200px;">
                    <p style="font-size: 18px;">Ship To:</p>
                    <h4 style="font-weight: bolder; font-size: 18px;">{{ $record->subCompany->name }}</h4>
                    <p style="font-size: 16px;">{{ $record->subCompany->address }}</p>
                    @php
                        $email = $record->subCompany->emails->first();
                        $sno = 1;
                        $sub_total = 0;
                    @endphp
                    <p style="font-size: 16px;">{{ ($email) ? $email->email_address : '' }}</p>
                </div>
            </div>
            <div class="col-sm-4 float-right">
                <div style="background: #f3f3f3; padding: 15px; height: 200px;">
                    <h4 style="font-weight: bolder; font-size: 18px;">Tax Invoice #{{ $record->invoice_number }}</h4>
                    <br>
                    @php
                        if($record->subCompany->inv_due_days > 0)
                        {
                            $date = substr($record->datetime,0,10);
                            $due = date('d-m-Y', strtotime($date. ' + '.$record->subCompany->inv_due_days.' days'));
                             }
                            elseif($record->subCompany->inv_due_days < 0){

                              $timestamp = strtotime($record->datetime);
                              $daysRemaining = (int)date('t', $timestamp) - (int)date('j', $timestamp);
                              $positive_value =  abs($record->subCompany->inv_due_days);
                              $origional_date = $positive_value+$daysRemaining;

                              $date = substr($record->datetime,0,10);
                                $due = date('d-m-Y', strtotime($date. ' + '.$origional_date.' days'));
                          }
                    @endphp
                    <p style="font-size: 16px;">Created Date: {{ $date }}</p>
                    <p style="font-size: 16px;">Due Date: {{ $due }}</p>
                </div>
            </div>
       </div>
<br>
        <div>
            <div class="col-sm-12">
                <div>
                    <table class="table table-bordered">
                        <thead class="" style="background: #f3f3f3;">
                        <tr>
                            @php
                                $category = $record->category;
                            @endphp
                            <th scope="col" style="width: 60px;">S.No</th>
                            <th scope="col" style="width: 176px;">Item</th>
                            <th scope="col">Quantity</th>
                            @if($category->invoice_whole_sale != '')
                                <th>Whole Sale</th>
                            @endif
                            @if($category->invoice_discount != '')
                                <th>Discount</th>
                            @endif
                            @if($category->invoice_delivery_rate != '')
                                <th>Delivery Rate</th>
                            @endif
                            @if($category->invoice_brand_charges != '')
                                <th>Brand Charges</th>
                            @endif
                            @if($category->invoice_cost_of_credit != '')
                                <th>COC/limit</th>
                            @endif
                            <th scope="col">Unit Price (IN GST)</th>
                            <th scope="col">Amount (IN GST)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($record->products as $product)
                            @if((float)$product->qty > 0)
                                <tr>
                                    <td>{{ $sno++ }}</td>
                                    <td>{{ $product->product->name }}</td>
                                    <td>{{ $product->qty }}</td>

                                    @if($category->invoice_whole_sale != '')
                                        <td>
                                            {{$product->whole_sale}}
                                        </td>
                                    @endif
                                    @if($category->invoice_discount != '')
                                        <td>
                                            {{$product->discount}}
                                        </td>
                                    @endif
                                    @if($category->invoice_delivery_rate != '')
                                        <td>
                                            {{$product->delivery_rate}}
                                        </td>
                                    @endif
                                    @if($category->invoice_brand_charges != '')
                                        <td>
                                            {{$product->brand_charges}}
                                        </td>
                                    @endif
                                    @if($category->invoice_cost_of_credit != '')
                                        <td>
                                            {{$product->cost_of_credit}}
                                        </td>
                                    @endif

                                    @php
                                        $unit = ($product->whole_sale + $product->delivery_rate + $product->brand_charges + $product->cost_of_credit) - $product->discount;
                                        $amount = $unit * $product->qty;
                                        $sub_total = $sub_total + $amount;
                                    @endphp
                                    <td class="text-right">${{ number_format(round($unit,2),2) }}</td>
                                    <td class="text-right">${{ number_format(round($amount,2),2) }}</td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-sm-7"></div>
            <div class="col-sm-1"></div>
            <div class="col-sm-4">
                <p>Sub Total: <span style="float: right;">${{ number_format(round($sub_total,2),2) }}</span></p>
                @if($record->split_load_charges)
                    <hr>
                    <p>Split Load Charges: <span
                                style="float: right;">${{number_format(round($record->split_load_charges,2),2) }}</span>
                    </p>
                @endif
                <hr>
                <p>Tax: <span style="float: right;">${{ number_format(round($record->gst,2),2) }}</span></p>
                <hr>
                @php
                    $grand = $sub_total + $record->split_load_charges + $record->gst;
                @endphp
                <h4>Grand Total : <span style="float: right;">${{ number_format(round($grand,2),2) }}</span></h4>
            </div>
        </div>


        <div style="text-align: center; font-style: italic;margin-left: 5px;"> This is computer generated invoice, hence
            does
            not require signature. - All Prices in Australian Dollar <span style="font-size: 15px;">&#36;</span></div>


        <div class="row">

            <div class="col-sm-4">
                <div style="background: #f3f3f3; padding: 15px; height: 270px;">
                    <img src="{{ asset('uploads/bpay.png') }}" width="40px">
                    <img src="{{ asset('uploads/bpay2.jpg') }}" width="180px">
                    <h5> Telephone & Internet Banking -BPAY </h5>
                    <span style="font-size:10px;">Telephone & Internet Banking - BPAY
Contact your bank or financial institution to make this payment from your cheque, saving or transaction account.
More info:www.bpay.com.au </span>

                    <span style="font-size:10px;">Any payment must be for the exact amount of this invoice.
Otherwise , any amount paid will not be accepted and will be returned</span>
                </div>
            </div>


            <div class="col-sm-4">
                <div style="background: #f3f3f3; padding: 15px; height: 270px;">
                    <img src="{{ asset('uploads/mastercard.jpg') }}" width="60px">
                    <img src="{{ asset('uploads/visa.png') }}" width="60px">
                    <br/>
                    <p>Pay online by clicking on Pay Now in
                        your invoice email.</p>
                </div>
            </div>


            <div class="col-sm-4">
                <div style="background: #f3f3f3; padding: 15px; height: 270px;">
                    <table class="table">
                        <tbody>

                        <tr>
                            <th style="font-size: 12px;">Bank:</th>
                            <td style="font-size: 12px;">COMMONWEALTH BANK AUSTRALIA</td>
                        </tr>

                        <tr>
                            <th style="font-size: 12px;">Name:</th>
                            <td style="font-size: 12px;">ATLAS FUEL AUSTRALIA PTY LTD</td>
                        </tr>

                        <tr>
                            <th style="font-size: 12px;">BSB:</th>
                            <td style="font-size: 12px;">066115</td>
                        </tr>

                        <tr>
                            <th style="font-size: 12px;">AC#:</th>
                            <td style="font-size: 12px;">10985324</td>
                        </tr>

                        <tr>
                            <th style="font-size: 12px;">Ref#:</th>
                            <td style="font-size: 12px;">4234242</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>


        <div class="row text-center">
            <div class="col-sm-12">
                Powered By <a href="https://smjunited.com/" target="_blank" style="color:black; font-weight:bold;">
                    ATLAS FUEL</a>
                <div style="float: right; display: contents;">
                    <img src="{{ asset('uploads/line-bar.jpg') }}" width="30%">
                    <span style="border:1px solid #fff; letter-spacing: 6px; color: #02a043; font-size:14px; font-weight:bold; text-align:right;">www.atlasfuel.com.au</span>
                </div>
            </div>
        </div>
        <div class="row" style="background: #111; color: #fff; padding: 20px;">
            <div class="col-sm-4">
                <img src="{{ asset('uploads/call.jpg') }}" width="40px" height="" width="40px"> (+61) 437 485 565
            </div>
            <div class="col-sm-4">
                <img src="{{ asset('uploads/mail.jpg') }}" width="40px"> logistics@smjunited.com
            </div>
            <div class="col-sm-4">
                <img src="{{ asset('uploads/location.jpg') }}" width="40px"> 2095 Toodyay rd -Gidgegannup WA 6083
            </div>
        </div>
    </div>

  </div>
  
</body>
</html>