<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 8pt;
        }

        @page {
            margin: 0px;
        }

        header {
            position: fixed;
            top: 0px;
        }

        #firsttable {
            position: relative;
            top: 160px;
        }

        #secondtable {
            position: relative;
            top: 160px;
        }

        

        body {
            margin-left: 1.8%;
            margin-right: 1.8%;
        }

        p {
            margin: 0pt;
        }

        table.items {
            border: 0.1mm solid #000000;
        }

        td {
            vertical-align: top;
        }

        .items td {
            border-left: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
            padding: 4px;
        }

        table thead td {
            background-color: #EEEEEE;
            text-align: center;
            border: 0.1mm solid #000000;
            font-variant: small-caps;
        }

        .items td.blanktotal {
            background-color: #EEEEEE;
            border: 0.1mm solid #000000;
            background-color: #FFFFFF;
            border: 0mm none #000000;
            border-top: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
        }

        .items td.totals {
            text-align: right;
            border: 0.1mm solid #000000;
        }

        .items td.cost {
            text-align: "."center;
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
    </style>
</head>

<body>
    <header>
        <?php
        $invoice_address = \DB::table('invoice_settings')
            ->select('invoice_address', 'name', 'invoice_abn')
            ->first();
        
        ?>


        <table width="100%">
            <tr>
                <td height="35px" style="background-color:black">&nbsp;</td>
            </tr>
        </table>

        <table width="100%">
            <thead>
                <tr>

                    <td width="40%" style="  background-color: white;border: 0px; vertical-align: middle;">
                        <img src="{{URL::asset('assets/img/line.jpg') }}" height="84px" width="100%">
                    </td>

                    <td width="20%" style=" text-align:center;  background-color: white;border: 0px;">
                        <img src="{{URL::asset('assets/img/atlas_logo.png') }}" style=" height: 120px;margin-top: -20px;">
                    </td>

                    <td width="40%" style="background-color: white;border: 0px; vertical-align: middle;">
                        <img src="{{URL::asset('assets/img/line.jpg') }}" height="84px" width="100%">
                    </td>

                </tr>
            </thead>

        </table>
    </header>
   
    <table width = "100%" style="margin-top: 120px;">
        <table width= "50%" style="float:left;">
            <tr>
            <td>
                <h2> <span
                        style="font-size: 14pt; color: #555555; font-family: gothic; ">{{ $invoice_address->name }}</span>
                </h2>
                 <h3> <span
                        style="font-size: 14pt; color: #555555; font-family: gothic; ">ABN: {{ $invoice_address->invoice_abn }}</span>
                </h3>
                <h3> <span style="font-size: 14pt; color: #555555; font-family: gothic; ">REPORT: CLIENT STATEMENT</span>
                </h3>
            </td>
        </tr>
      

        </table>
        <table width="50%" style="float:right; align:end;margin-top:20px;">
            <tr>
            <!--    <td height="35px">&nbsp;</td>-->
            <!--</tr>-->
            <!--<tr>-->
                <!--<td height="35px">&nbsp;</td>-->
            <!--</tr>-->
            <!--<tr>-->
            <!--    <td height="35px">&nbsp;</td>-->
            <!--</tr>-->
           
            <!--<tr>-->
            <td style="">

             <p > &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    @if ($type == 'Custom Range')
                        {{ date('d M Y', strtotime($from)) }} -
                        {{ date('d M Y', strtotime($to)) }}
                    @else 
                        REPORT FROM: {{ $type }}
                    @endif
                    </p>
                <p style="font-size: 12pt; color: #555555; font-family: gothic;">
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;  Generated on :
                    <?php echo date('d-M-Y'); ?></p>
            </td>
            
        </tr>
     
        </table>
    </table>
{{-- </section> --}}

    <table width="100%" style="font-family: gothic;" cellpadding="05" id="firsttable">
        <tr>
            <td width="49%" style="  border: 0.1mm solid #eee;background-color:#eee ">
                <h3 class="to" style="font-size: 10pt; color: #000; font-family: gothic;"> Client </h3>

                <span style="font-size: 10pt; color: #555555; font-family: gothic; ">{{ $company->name }}</span>
                <br />
                <p class="address" style="font-size: 10pt; color: #555555; font-family: gothic; ">ABN: &nbsp;
                    {{ $company->tax_no }}</span>
                </p><br>
                <p class="address" style="font-size: 10pt; color: #555555; font-family: gothic; ">
                    {{ $company->address }}</span>
                </p><br>

            </td>
            <td width="1%">&nbsp;</td>
            <td width="49%" style="border: 0.1mm solid #eee;background-color:#eee">
                <table width="100%" style="float:right ; text-align: left;">
                   @php
                   $rem_balance = 0;
                   $date1 =0;
                    @endphp
                @foreach ($records as $sub_company_records)
                    <?php
                    $j = 1;
                    $i = 0;
                    
                    $rem_credit = 0;
                    $rem_debit = 0;
                    ?>
                    @foreach ($sub_company_records->where('status', 1)->where('deleted_status', 0) as $record)

                            <?php
                            $date = new DateTime($record->datetime);
                            $date1 = $date->format('d-m-Y');

                            $rem_balance = $rem_balance + $record->total_amount;
                            ?>

                    @endforeach
                   
                @endforeach
                    <tr>
                     <td height="2S5px">&nbsp;</td>
                     </tr>
                
                    <tr>
                        <td>
                            <b style="font-size: 12pt;">Total Amount Due:  <STRONG> ${{ number_format($rem_balance, 2) }}  </STRONG></b>
                            <br>
                            <br>
                           
                        </td>
                    </tr><br>
                    <tr>
                        <td>

                            <b style="font-size: 12pt;">Due Date: <STRONG> {{ \Carbon\Carbon::parse($date1)->format('d-m-Y') }} </STRONG></b> 
                             <br>
                             <br>
                           

                        </td><br>
                    </tr><br>
                    <tr>
                        <td>
                            <b style="font-size: 12pt;">Contact Person: </STRONG> KUNAL </STRONG></b> 
                        </td><br>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <br>
    
    <table cellspacing="0" width="100%" style="border: 0.1mm solid #888888; float:left;" cellpadding="8" id="secondtable">
    <tr style="background-color:#515e6d;color:white;line-height: 1px;">
        <td width="5%" style="border: 0.1mm solid #888888;color:white;">
            <h4 class="to"> S.No </h4>
        </td>
        <td width="25%" style="border: 0.1mm solid #888888;color:white;">
            <h4 class="to"> Client Company </h4>
        </td>
        <td width="5%" style="border: 0.1mm solid #888888;color:white;">
            <h4 class="to"> DateTime </h4>
        </td>
        <td width="5%" style="border: 0.1mm solid #888888;color:white;">
            <h4 class="to"> Inovice No </h4>
        </td>
        <td width="5%" style="border: 0.1mm solid #888888;color:white;">
            <h4 class="to"> Description </h4>
        </td>
        <td width="5%" style="border: 0.1mm solid #888888;color:white;">
            <h4 class="to"> Debit </h4>
        </td>
        <td width="5%" style="border: 0.1mm solid #888888;color:white;">
            <h4 class="to"> Credit</h4>
        </td>
        <td width="5%" style="border: 0.1mm solid #888888;color:white;">
            <h4 class="to"> Balance </h4>
        </td>
    </tr>

  @foreach($records as $sub_company_records)
  	<?php 
		$j = 1; 
		$i = 0;
		$rem_balance = 0;
		$rem_credit = 0;
		$rem_debit = 0;
  	?>
  @foreach ($sub_company_records->where('status',1)->where('deleted_status',0) as $record)
  	@if ($j == 1)
      <?php
  			$date =  new DateTime($record->datetime);
  			$date = $date->format('d-m-Y');
			$debit = 0;
  		   	$total_sales_amount = $sub_company_records->where('datetime','<',$date)->sum('total_amount');
  		   	$total_sales_paid = $sub_company_records->where('datetime','<',$date)->sum('paid_amount');
			$opening_balance = $total_sales_amount - $total_sales_paid;
      ?>
      <tr>
          <td height="30px" width="5%" style="border: 0.1mm solid #888888;">
              <p class="to">{{++$i}}</p>
          </td>
          <td height="30px" width="25%" style="border: 0.1mm solid #888888;">
              <p class="to">{{ ($record->sub_company_id) ? $record->subCompany->name : '-' }}</p>
          </td>
          <td height="30px" width="5%" style="border: 0.1mm solid #888888;">
              <p class="to"> - </p>
          </td>
          <td height="30px" width="5%" style="border: 0.1mm solid #888888;">
              <p class="to"> - </p>
          </td>
          <td height="30px" width="10%" style="border: 0.1mm solid #888888;">
              <p class="to"> Opening Balance </p>
          </td>
          <td height="30px" width="5%" style="border: 0.1mm solid #888888;">
              <p class="to"> - </p>
          </td>
          <td height="30px" width="5%" style="border: 0.1mm solid #888888;">
              <p class="to"> - </p>
          </td>
          <td height="30px" width="5%" style="border: 0.1mm solid #888888;">
              <p class="to"> ${{number_format($opening_balance, 2)}} </p>
          </td>
      </tr>
  	@endif
      <?php $j = 2; ?>
      <tr>
          <td height="30px" width="5%" style="border: 0.1mm solid #888888;">
              <p class="to">{{++$i}}</p>
          </td>
          <td height="30px" width="25%" style="border: 0.1mm solid #888888;">
              <p class="to">{{ ($record->sub_company_id) ? $record->subCompany->name : '-' }}</p>
          </td>
          <td height="30px" width="5%" style="border: 0.1mm solid #888888;">
              <p class="to"> {{\Carbon\Carbon::parse($record->datetime)->format('d-m-Y')}} </p>
          </td>
          <td height="30px" width="5%" style="border: 0.1mm solid #888888;">
              <p class="to"> {{$record->invoice_number}} </p>
          </td>
          <td height="30px" width="10%" style="border: 0.1mm solid #888888;">
              <p class="to">Invoice #{{$record->invoice_number}} </p>
          </td>
          <td height="30px" width="5%" style="border: 0.1mm solid #888888;">
              <p class="to"> ${{number_format($record->total_amount, 2)}} </p>
          </td>
          <td height="30px" width="5%" style="border: 0.1mm solid #888888;">
              <p class="to"> </p>
          </td>
          @php
              	$rem_balance = $rem_balance + $record->total_amount;
          @endphp
          <td height="30px" width="5%" style="border: 0.1mm solid #888888;">
              <p class="to"> ${{number_format($rem_balance, 2)}} </p>
          </td>
      </tr>
  	@foreach ($record->transactionHistory as $history)
      <tr>
          <td height="30px" width="5%" style="border: 0.1mm solid #888888;">
              <p class="to">{{++$i}}</p>
          </td>
          <td height="30px" width="25%" style="border: 0.1mm solid #888888;">
              <p class="to">{{$record->subCompany->name}}</p>
          </td>
          <td height="30px" width="5%" style="border: 0.1mm solid #888888;">
              <p class="to">{{\Carbon\Carbon::parse($history->created_at)->format('d-m-Y')}} </p>
          </td>
          <td height="30px" width="5%" style="border: 0.1mm solid #888888;">
              <p class="to">{{$record->invoice_number}}</p>
          </td>
          <td height="30px" width="10%" style="border: 0.1mm solid #888888;">
              <p class="to">Invoice #{{$record->invoice_number}} </p>
          </td>
          <td height="30px" width="5%" style="border: 0.1mm solid #888888;">
              <p class="to">  </p>
          </td>
          <td height="30px" width="5%" style="border: 0.1mm solid #888888;">
              <p class="to"> ${{number_format($history->amount, 2)}}</p>
          </td>
          @php
              	$rem_balance = $rem_balance - $history->amount;
        		$debit = $debit + $history->amount;
          @endphp
          <td height="30px" width="5%" style="border: 0.1mm solid #888888;">
              <p class="to"> ${{number_format($rem_balance, 2)}} </p>
          </td>
      </tr>
  	@endforeach
  @endforeach

    <tr style="background-color:#515e6d;">
        <td height="15px" width="13%" style="border: 0.1mm solid #888888;color:white;">
            <p class="to"> <b> Total</b> </p>
        </td>
        <td height="15px" width="13%" style="border: 0.1mm solid #888888;color:white;">
        </td>
        <td colspan="1" height="15px" width="13%" style="border: 0.1mm solid #888888;color:white;">
        </td>
        <td height="15px" width="13%" style="border: 0.1mm solid #888888;color:white;">
        </td>
        <td height="15px" width="13%" style="border: 0.1mm solid #888888;color:white;">
        </td>
      	@php
      		$credit = $debit + $rem_balance;
      	@endphp
        <td height="15px" width="10%" style="border: 0.1mm solid #888888;color:white;">
            <p class="to" style="text-align: right;"><b>${{ number_format($credit,2) }}</b> </p>
        </td>
        <td height="15px" width="10%" style="border: 0.1mm solid #888888;text-align:right;color:white;">
            <p class="to"><b>${{ number_format($debit,2) }}</b></p>
        </td>
        <td height="15px" width="10%" style="border: 0.1mm solid #888888;text-align:right;color:white;">
            <p class="to"> <b> ${{number_format($rem_balance, 2)}} </b> </p>
        </td>
    </tr>
  @endforeach
  
</table>
    <br>
    <br />
    <br />

</body>

</html>
