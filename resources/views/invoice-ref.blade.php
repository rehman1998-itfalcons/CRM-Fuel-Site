<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Invoice</title>
    <meta charset="utf-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #fff !important;
            font-family: 'Quicksand' !important;
            font-weight: 600 !important;
        }
        .cs-h6 {
            background: #192b91;
            padding: 8px;
            color: #fff;
            font-weight: bolder;
        }

        .table > thead > tr > th {
            background: #192b91 !important;
            color: #fff !important;
        }

        h1, h2, h3, h4, h5, h6, p, span {
            font-family: 'Quicksand' !important;
            font-weight: 600 !important;
        }
        table{
            table-layout: fixed;
            width: 300px;
        }
    
    </style>
</head>
<body style="background-color: #fff;">

<div class="container">
    <div class="panel">

        <div class="row" style="margin-top: 8px;">
            <div class="col-xs-4">
                <h3 style="font-size: 24px;">{{ $record->subCompany->name }}</h3>
                <p>{{$record->subCompany->address}}</p>
            </div>
            <div class="col-xs-4"></div>
            <div class="col-xs-4">
                <h1 style="font-weight: 800; margin-top: 0px;">PRO FORMA INVOICE</h1>
                <p>Date: <span class="text-right">{{$record->datetime}}</span></p>
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

                <p>Expiration Date: <span class="text-right">{{$due}}</span></p>
                <p>Invoice #: <span class="text-right">{{$record->invoice_number}}</span></p>
                <p>Customer ID: <span class="text-right">{{$record->user_id}}</span></p>
            </div>
        </div>
        <br><br>

        <div class="row" style="margin: 5px;">
            <div class="col-xs-4">
                <h6 class="cs-h6">CUSTOMER</h6>
                <p>{{ $record->subCompany->company->name }}</p>
                <p>{{$record->subCompany->company->address}}</p>
                @php
                    $email = $record->subCompany->company->emails->first();
                @endphp
                <p>{{ ($email) ? $email->email_address : '' }}</p>
            </div>
            <div class="col-xs-4">
                <h6 class="cs-h6">SHIP TO</h6>
                <p>{{ $record->subCompany->name }}</p>
                <p>{{$record->subCompany->address}}</p>
                @php
                    $email = $record->subCompany->emails->first();
                @endphp
                <p>{{ ($email) ? $email->email_address : '' }}</p>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="" style="background: #192b91; color: #fff;">
                        <tr>
                            @php
                                $category = $record->category;
                                   $sno = 1;
                                 $sub_total = 0;
                            @endphp
                            <th scope="col" style="width: 5%;">S.No</th>
                            <th scope="col" style="width: 18%;">Item</th>
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
        <br><br>

        <div class="row">
            <div class="col-xs-7">
                <h6 class="cs-h6" style="margin-top: 0px; margin-bottom: 0px;">TERMS OF SALE AND OTHER COMMENTS</h6>
                <p style="border: 3px solid #000; border-top: 0px; height: 200px;"></p>
            </div>
            <div class="col-xs-1"></div>
            <div class="col-xs-2">
                <p class="">Sub Total:</p>
                @if($record->split_load_charges)
                    <p class="">Split Load Charges:</p>
                @endif
                <p class="">Tax:</p>
                @php
                    $grand = $sub_total + $record->split_load_charges + $record->gst;
                @endphp
              <h3 style="color:black;">Grand Total :</h3>
            </div>
            <div class="col-xs-2">
                <p>${{ number_format(round($sub_total,2),2) }}</p>
                @if($record->split_load_charges)
                    <p>${{number_format(round($record->split_load_charges,2),2) }}</p>
                @endif
                <p>${{ number_format(round($record->gst,2),2) }}</p>
                <h3 style="color:black;">${{ number_format(round($grand,2),2) }}</h3>

            </div>
        </div>
    </div>
</div>
</body>
</html>