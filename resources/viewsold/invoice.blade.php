<!DOCTYPE html>
<html>
<head>
  <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
	<style>
		body {
			font-family: 'Quicksand' !important;
			font-size: 9pt;
		}
       @page {
                margin: 0cm 0cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 4cm;
                margin-left: 2cm;
                margin-right: 2cm;
                margin-bottom: 8cm;
            }

		p {
			margin: 0pt;
		}
      
     h1, h2, h3, h4, h5, h6, p, span {
            font-family: 'Quicksand' !important;
        }
      
		table.items {
			border-top: 1px solid #ccc;
          border-right: 1px solid #ccc;
          border-left: 1px solid #ccc;
		}

		td {
			vertical-align: top;
          
		}

		.items td {
			border-left: 1px solid #ccc;
			border-right: 1px solid #ccc;
          border-bottom: 1px solid #ccc;
			padding: 9px 5px 9px 5px;
		}

		table thead td {
			background-color: #EEEEEE;
			text-align: center;
			border: 1px solid #ccc;
			font-variant: small-caps;
		}

		.items td.blanktotal {

			border: 1px solid #ccc;
			background-color: #FFFFFF;
			border: 0mm none #000000;
			border-top: 1px solid #ccc;
			border-right: 1px solid #ccc;
		}

		.items td.totals {
			text-align: right;
			border: none !important;
		}

		.items td.cost {

			text-align: right;
		}


		#invoice {
			padding: 10px;
		}

		.invoice {
			position: relative;
			background-color: #FFF;
			min-height: 680px;
			padding: 5px
		}

		.invoice header {
			padding: 10px 0;
			margin-bottom: 20px;
			border-bottom: 1px solid #3989c6
		}

		.invoice .company-details {
			text-align: right
		}

		.invoice .company-details .name {
			margin-top: 0;
			margin-bottom: 0
		}

		.invoice .contacts {
			margin-bottom: 20px
		}

		.invoice .invoice-to {
			text-align: left
		}

		.invoice .invoice-to .to {
			margin-top: 0;
			margin-bottom: 0
		}

		.invoice .invoice-details {
			text-align: right
		}

		.invoice .invoice-details .invoice-id {
			margin-top: 0;
			color: #3989c6
		}

		.invoice main {
			padding-bottom: 10px
		}

		.invoice main .thanks {
			margin-top: -100px;
			font-size: 2em;
			margin-bottom: 50px
		}

		.invoice main .notices {
			padding-left: 6px;
			border-left: 6px solid #3989c6
		}

		.invoice main .notices .notice {
			font-size: 1.2em
		}

		.invoice table {
			width: 100%;
			border-collapse: collapse;
			border-spacing: 0;
			margin-bottom: 20px
		}

		.invoice table td,
		.invoice table th {
			padding: 6px;
			background: #eee;
			border-bottom: 1px solid #fff
		}

		.invoice table th {
			white-space: nowrap;
			font-weight: 400;
			font-size: 16px
		}

		.invoice table td h3 {
			margin: 0;
			font-weight: 400;
			color: #3989c6;
			font-size: 1.2em
		}

		.invoice table .qty,
		.invoice table .total,
		.invoice table .unit {
			text-align: right;
			font-size: 1.2em
		}

		.invoice table .no {
			color: #fff;

			background: #3989c6
		}

		.invoice table .unit {
			background: #ddd
		}

		.invoice table .total {
			background: #3989c6;
			color: #fff
		}

		.invoice table tbody tr:last-child td {
			border: none
		}

		.invoice table tfoot td {
			background: 0 0;
			border-bottom: none;
			white-space: nowrap;
			text-align: right;
			padding: 10px 20px;
			font-size: 1.2em;
			border-top: 1px solid #aaa
		}

		.invoice table tfoot tr:first-child td {
			border-top: none
		}

		.invoice table tfoot tr:last-child td {
			color: #3989c6;
			font-size: 1.4em;
			border-top: 1px solid #3989c6
		}

		.invoice table tfoot tr td:first-child {
			border: none
		}

		.invoice footer {
			width: 100%;
			text-align: center;
			color: #777;
			border-top: 1px solid #aaa;
			padding: 8px 0
		}

		@media print {
			.invoice {
				font-size: 11px !important;
				overflow: hidden !important
			}

			.invoice footer {
				position: absolute;
				bottom: 10px;
				page-break-after: always
			}

			.invoice>div:last-child {
				page-break-before: always
			}
		}

		h2 {
			font-size: 12pt;
		}
      
 
      
       header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height: 3cm;
            }

            /** Define the footer rules **/
            footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 9cm;
            }
      
	</style>
</head>
<body>
@php
 $invoicedata = \App\InvoiceSetting::first();  
@endphp
  <header>
<table width="100%">
 
  <tr>
  <td height="35px" style="background-color:black">&nbsp;</td>  
</tr>  
 

</table>
<table width="100%" >
<thead>
<tr>
  
<td width="40%" style="background-color: white;border: 0px; vertical-align: middle;">
<img src="{{url('assets/img/line.jpg')}}" height="84px" width="100%">
</td>
<td width="20%" style="text-align:center;background-color: white; border: 0px;">
   @if($invoicedata->invoice_logo)
 <img src="{{($invoicedata) ? asset('/uploads/siteinvoice/'.$invoicedata->invoice_logo) : '' }}" style="height:80px;"> 
  @else
 <img src="{{ asset('assets/img/atlas_logo.png') }}" style="height:80px;">
  @endif  
</td>
<td  width="40%" style="background-color: white;border: 0px; vertical-align: middle; " >
<img src="{{url('assets/img/line.jpg')}}" height="84px"  width="100%">
</td>  
</tr>
</thead>
</table> 

  <table width="100%" style="font-family: gothic;" cellpadding="05">

		<tr>

			<td width="1%" style="text-align: right;">


			</td>

			<td width="70%" style="text-align: center;">

				<p style="font-size: 15px; font-weight: bold;margin-top: -24px;">ABN {{($invoicedata->invoice_abn) ? $invoicedata->invoice_abn : '33 634 907 538'}}</p>
			</td>
		</tr>

	</table>        
  </header>

        <footer>
           <table class="content custom-contents2" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td>
            <center style="text-align: center; font-style: italic;background: #ddd !important;"> This is computer generated invoice, hence does not require signature. - All Prices in Australian Dollar <span
                        style="font-size: 15px;">&#36;</span></center>
        </td>
    </tr>
</table>
<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tbody>
    <tr>
        <td cellpadding="0" cellspacing="0" width="33%">
            <div style="background: #f3f3f3; padding: 15px; height: 170px; margin: 15px;">
               @if($invoicedata->telephone_header_img1 != 'NULL')
               <img src="{{asset('/uploads/siteinvoice/'.$invoicedata->telephone_header_img1)}}" width="40px">
              @endif
              @if($invoicedata->telephone_header_img2 != 'NULL')
               <img src="{{asset('/uploads/siteinvoice/'.$invoicedata->telephone_header_img2)}}" width="180px">
              @endif
                <h5 style="font-size: 8px !important;"> {{($invoicedata->telephone_header) ? $invoicedata->telephone_header : 'Telephone & Internet Banking -BPAY'}} </h5>
                <p style="font-size: 8px !important;">{{($invoicedata->telephone_text) ? $invoicedata->telephone_text : 'Telephone & Internet Banking - BPAY
              Contact your bank or financial institution to make this payment from your cheque, saving or transaction account.
              More info:www.bpay.com.au Any payment must be for the exact amount of this invoice.
              Otherwise , any amount paid will not be accepted and will be returned'}}</p>
            </div>
        </td>
        <td cellpadding="0" cellspacing="0" width="33%">
            <div style="background: #f3f3f3; padding: 15px; height: 170px; margin: 15px;">
              @php
              $files = explode("::",$invoicedata->pay_online_imges);
              @endphp
              
              @if($invoicedata->pay_online_imges)
              @foreach ($files as $file)
              <img src="{{($invoicedata) ? asset('/uploads/siteinvoice/'.$file) : '' }}" width="60px">
              @endforeach
              @endif
                <br/>
                <p>{{($invoicedata->pay_online_text) ? $invoicedata->pay_online_text : 'Pay online by clicking on Pay Now in
                    your invoice email.'}}</p>
            </div>
        </td>
        <td cellpadding="0" cellspacing="0" width="33%">
            <div style="background: #f3f3f3; padding: 15px; height: 170px; margin: 15px;">
                <span style="font-size: 12px;">Bank:</span>
                <span style="font-size: 12px;">{{($invoicedata->invoice_bank) ? $invoicedata->invoice_bank : 'COMMONWEALTH BANK AUSTRALIA'}}</span>
                <br><br>
                <span style="font-size: 12px;">Name:</span>
                <span style="font-size: 12px;">{{($invoicedata->name) ? $invoicedata->name : 'ATLAS FUEL AUSTRALIA PTY LTD'}}</span>
                <br><br>
                <span style="font-size: 12px;">BSB:</span>
                <span style="font-size: 12px;">{{($invoicedata->invoice_bsb) ? $invoicedata->invoice_bsb : '066115'}}</span>
                <br><br>
                <span style="font-size: 12px;">AC#:</span>
                <span style="font-size: 12px;">{{($invoicedata->invoice_account_no) ? $invoicedata->invoice_account_no : '10985324'}}</span>
                <br><br>
                <span style="font-size: 12px;">Ref#:</span>
              <span style="font-size: 12px;">{{ $record->invoice_number }}</span>
            </div>
        </td>
    </tr>
    </tbody>
</table>
<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tbody>
    <tr>
        <td>
            <div>
                <center>Powered By <a style="color:black; font-weight:bold;">
                        {{($invoicedata->powerd_text) ? $invoicedata->powerd_text : 'ATLAS FUEL'}}</a> <img src="{{ asset('uploads/line-bar.png') }}" width="40%"> <span style="letter-spacing: 6px; color: #02a043; font-size:14px; font-weight:bold; text-align:right;">{{($invoicedata->invoice_web_url) ? $invoicedata->invoice_web_url : 'www.atlasfuel.com.au'}}</span></center></div>
        </td>
    </tr>
    </tbody>
</table>
 
<table width="100%" style="font-size: 9pt; border-collapse: collapse; color:white" cellpadding="8">

<tr style=" border:1px solid #000;">

<td width="8%" style=" text-align:right;  background-color: black;vertical-align: middle;" >

<img src="{{ asset('uploads/call.jpg') }}" width="40px">
</td>
<td width="24%" style="text-align:left; background-color: black;vertical-align: middle;">

{{($invoicedata->invoice_phone_no) ? $invoicedata->invoice_phone_no : '(+61) 437 485 565'}}

</td>

<td width="16%" style=" text-align:right; background-color: black;vertical-align: middle;" >

<img src="{{ asset('uploads/mail.jpg') }}" width="40px">
</td>
<td width="24%" style="text-align:left; background-color: black;vertical-align: middle;">

{{($invoicedata->invoice_email) ? $invoicedata->invoice_email : 'admin@atlasfuel.com.au'}}

</td>

<td width="8%" style=" text-align:right;   background-color: black;vertical-align: middle;" >
<img src="{{ asset('uploads/location.jpg') }}" width="40px" >
</td>
<td width="24%" style="text-align:left;background-color: black;vertical-align: middle;">
{{($invoicedata->invoice_address) ? $invoicedata->invoice_address : '2095 Toodyay rd -Gidgegannup WA 6083'}}
</td>
</tr>


</table>          

          
        </footer> 
  

  
  <table width="100%" style="font-family: gothic;" cellpadding="05">
		<tr>
			<td width="30%" style="border: 0.1mm solid #eee;background-color:#eee ">
				<span style="font-size: 10pt; color: #555555; font-family: gothic; ">Bill To:</span>
				<h2 class="to"> {{ $record->subCompany->company->name }}</h2>
                            @php
                                $email = $record->subCompany->company->emails->first();
                            @endphp
				<p class="email">
					<a href="" style="color:black;">{{ ($email) ? $email->email_address : '' }} </a></p>
			</td>

			<td width="3%">&nbsp;</td>

			<td width="30%" style="border: 0.1mm solid #eee;background-color:#eee">

				<span style="font-size: 10pt; color: #555555; font-family: gothic;"> Ship To:</span>
				<h2 class="to"> {{ $record->subCompany->name }}</h2>
				<p class="address"> {{ $record->subCompany->address }}</p>
                              @php
                                $email = $record->subCompany->emails->first();
                                $sno = 1;
                                $sub_total = 0;
                            @endphp
				<p class="email">
					<a href="" style="color:black;"> {{ ($email) ? $email->email_address : '' }} </a></p>

			</td>
			<td width="3%">&nbsp;</td>
			<td width="30%" style="border: 0.1mm solid #eee;background-color:#eee">
				<span style="font-size: 10pt; color: #555555; font-family: gothic;">TAX INVOICE <strong>#{{ $record->invoice_number }}</strong> </span>
                               @php
                                $date = '-';
                                $due = '-';
                          		if($record->subCompany->inv_due_days > 0)
                                {
                                    $date = \Carbon\Carbon::parse($record->datetime)->format('d-m-Y');
                                    $due = date('d-m-Y', strtotime($date. ' + '.$record->subCompany->inv_due_days.' days'));
                                     }
                                    elseif($record->subCompany->inv_due_days < 0){

                                      $timestamp = strtotime(\Carbon\Carbon::parse($record->datetime)->format('d-m-Y H:i'));
                                      $daysRemaining = (int)date('t', $timestamp) - (int)date('j', $timestamp);
                                      $positive_value =  abs($record->subCompany->inv_due_days);
                                      $origional_date = $positive_value+$daysRemaining;

                                      $date = \Carbon\Carbon::parse($record->datetime)->format('d-m-Y');
                                        $due = date('d-m-Y', strtotime($date. ' + '.$origional_date.' days'));
                                  }
                            @endphp
              <br>
              <br>
                <p class="email" style="font-size: 12px;"> Created Date: {{ \Carbon\Carbon::parse($record->datetime)->format('d-m-Y') }}</p>
				<p class="email" style="font-size: 12px;"> Due Date: {{ ($due == '-') ? \Carbon\Carbon::parse($record->datetime)->format('d-m-Y') : $due }}</p>
			</td>
		</tr>
	</table>
<br><br>
  <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8" style="">
		<thead>
          			 @php
                        $category = $record->category;
          				$col=3;
                    @endphp
			<tr>
				<td width="3%" style="border:1px solid #eee;border-bottom:1px solid #ccc; height:30px; font-size:12px; vertical-align:middle;padding-top:0px;">S.No</td>
				<td width="27%" style="border:1px solid #eee;border-bottom:1px solid #ccc; height:30px; font-size:12px; vertical-align:middle;">Items</td>
              <td width="15%" style="border:1px solid #eee;border-bottom:1px solid #ccc; height:30px; font-size:12px; vertical-align:middle;">Quantity (LTR)</td>
              
              @if($category->invoice_whole_sale != '')
              @php
              $col++;
              @endphp
                        <td width="15%" style="border:1px solid #eee;border-bottom:1px solid #ccc; height:30px; font-size:12px; vertical-align:middle;">Base Price</td>
                    @endif
                    @if($category->invoice_discount != '')
              @php
              $col++;
              @endphp
                        <td width="15%" style="border:1px solid #eee;border-bottom:1px solid #ccc; height:30px; font-size:12px; vertical-align:middle;">Discount</td>
                    @endif
                    @if($category->invoice_delivery_rate != '')
              @php
              $col++;
              @endphp
                        <td width="15%" style="border:1px solid #eee;border-bottom:1px solid #ccc; height:30px; font-size:12px; vertical-align:middle;">Delivery Rate</td>padding-top:0px;
                    @endif
                    @if($category->invoice_brand_charges != '')
              @php
              $col++;
              @endphp
                       <td width="15%" style="border:1px solid #eee;border-bottom:1px solid #ccc; height:30px; font-size:12px; vertical-align:middle;">Brand Charges</td>
                    @endif
                    @if($category->invoice_cost_of_credit != '')
              @php
              $col++;
              @endphp
                        <td width="15%" style="border:1px solid #eee;border-bottom:1px solid #ccc; height:30px; font-size:12px; vertical-align:middle;padding-top:0px;">Credit Charges</td>
                    @endif 
				<td width="20%" style="border:1px solid #eee;border-bottom:1px solid #ccc; height:30px; font-size:12px; vertical-align:middle;padding-top:0px;">Net price</td>

				<td width="20%" style="border:1px solid #eee;border-bottom:1px solid #ccc; height:30px; font-size:12px; vertical-align:middle;padding-top:0px;">Line Total</td>
			</tr>
		</thead>
		<tbody>
          
                    @foreach ($record->products as $product)
                    @if((float)$product->qty > 0)
				<tr style="border:1px solid #ccc;">
					<td> {{ $sno++ }} </td>
					<td> {{ $product->product->name }} </td>
					<td align="center"> {{ $product->qty }} </td>
                   @if($category->invoice_whole_sale != '')
					<td align="center">  {{$product->whole_sale}} </td>
                  @endif
                    @if($category->invoice_discount != '')
					<td align="center">  {{$product->discount}} </td>
                  @endif
                  
                     @if($category->invoice_delivery_rate != '')
					<td align="center">  {{$product->delivery_rate}} </td>
                  @endif
                  
                  @if($category->invoice_brand_charges != '')
					<td align="center">  {{$product->brand_charges}} </td>
                  @endif
                   
                   @if($category->invoice_cost_of_credit != '')
					<td align="center">  {{$product->cost_of_credit}}</td>
                  @endif
                  
                            @php
                                $unit = ($product->whole_sale + $product->delivery_rate + $product->brand_charges + $product->cost_of_credit) - $product->discount;
                                $amount = $unit * $product->qty;
                                $sub_total = $sub_total + $amount;
                            @endphp
					<td align="center"> {{ number_format(round($unit,4),4) }}</td>

					<td align="center"> <span style="font-size: 15px;">&#36;</span> {{ number_format(round($amount,2),2) }}</td>
				</tr>

          @endif
          @endforeach
          
			<!-- END ITEMS HERE -->

			<tr style="border-right: 1px solid transparent;">
				<td class="blanktotal" colspan="{{$col}}" rowspan="6" style="border-right: 1px solid transparent !important;border-left: 1px solid transparent !important;    border-bottom: 1px solid transparent !important;"></td>
				<td class="totals" width="30%" style="background-color: #eee;">Sub-Total @if($record->gst_status == 'Excluded') (Ex. GST) @else (Inc. GST) @endif:  </td>
				<td class="totals cost" width="30%" style="text-align: center;"> <span style="font-size: 15px;">&#36;</span>
					{{ number_format(round($sub_total,2),2) }} </td>
			</tr>
         @if($record->split_load_charges)
          <tr style="border-right: 1px solid transparent;">
				<td class="totals" style="background-color: #eee;"> Split Load Charges :</td>
				<td class="totals cost" width="30%" style="text-align: center;"><span style="font-size: 15px;">&#36;</span> {{number_format(round($record->split_load_charges,2),2) }}</td>
			</tr>
          @endif
			<tr style="border-right: 1px solid transparent;">
				<td class="totals" width="30%" style="background-color: #eee;"> GST :</td>
				<td class="totals cost" width="30%" style="text-align: center;"><span style="font-size: 15px;">&#36;</span> {{ number_format(round($record->gst,2),2) }}</td>
			</tr>
			<tr style="border-right: 1px solid transparent;">
                @php
              		$gst = ($record->gst_status == 'Included') ? 0 : $record->gst;
                    $grand = $sub_total + $record->split_load_charges + $gst;
                @endphp
				<td class="totals" width="30%" style="background-color: #eee;"><b>Total: </b></td>
				<td class="totals cost" width="30%" style="background-color: #fef1f1; text-align: center;"><b><span style="font-size: 15px;">&#36;</span>{{ number_format(round($grand,2),2) }} </b></td>
			</tr>
			
		</tbody>
	</table>
 
           
             
</body>
</html>