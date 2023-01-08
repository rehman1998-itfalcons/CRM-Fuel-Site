<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 7pt;
            position
        }
      
       @page { 
           margin: 0px;
       }
       header { 
           position: fixed;
           top: 0px;
       }
       #firsttable{
         position: relative;
            top: 160px;      
       }
       #secondtable{
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
    <table width="100%" >
        <tr>
            <td height="35px" style="background-color:black">&nbsp;</td>
        </tr>
    </table>
    <table width="100%" >
        <thead>
        <tr>
            <td width="40%" style="  background-color: white;border: 0px; vertical-align: middle;">
                <img src="{{URL::asset('assets/img/line.jpg')}}" height="84px" width="100%">
            </td>
            <td width="20%" style=" text-align:center;  background-color: white;border: 0px;">
                <img src="{{URL:: asset('assets/img/atlas_logo.png') }}" style=" height: 120px;margin-top: -20px;">
            </td>
            <td  width="40%" style="background-color: white;border: 0px; vertical-align: middle;">
                <img src="{{URL::asset('assets/img/line.jpg')}}" height="84px"  width="100%">
            </td>
        </tr>
        </thead>
    </table>
</header>

<?php
      $invoice_address = \DB::table('invoice_settings')->select('invoice_address','name')->first();
  ?>

<table width="100%" style="font-family: gothic;" cellpadding="05" id="firsttable">
    <tr>
        <td width="49%" style="  border: 0.1mm solid #eee;background-color:#eee ">
            <h3 class="to"> Company Detail Report </h3>
            <span style="font-size: 10pt; color: #555555; font-family: gothic; ">{{ $invoice_address->name }}</span>
            <br/>
            <p class="address" style="font-size: 14px;">{{ $invoice_address->invoice_address }}</p>
        </td>
        <td width="1%">&nbsp;</td>
        <td width="49%" style="border: 0.1mm solid #eee;background-color:#eee">
            <table width="100%" style="float:right ; text-align: right;">
                <tr>
                    <td colspan="2">
                        <h5>
                          @if($type == 'Custom Range')
                            {{date("d M Y", strtotime($from))}} -
                            {{date("d M Y", strtotime($to))}}
                          @else
                          {{ $type }}
                          @endif
                        </h5>
                        <p style="font-size: 14px;">ABN: 33634907538 </p>
                        <span style="font-size: 8pt; color: #555555; font-family: gothic;"> Generated on : <?php echo date("d-M-Y"); ?></span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<br>
  @php
	$sales_count = count($records);
	$purchases_count = count($purchases);
	$expenses_count = count($expenses);
	$count = max([0 => $sales_count,1 => $purchases_count,2 => $expenses_count]);
  	$total = 0;
  @endphp
    <table cellspacing="0" width="100%" style="border: 0.1mm solid #888888" cellpadding="5" id="secondtable">
    <tr style="background-color:#515e6d;color:white;line-height: 14px;">
         <td width="13%" style="border: 0.1mm solid #888888;color:white;">
            <h4 class="to">Type</h4>
         </td>
         <td width="13%" style="border: 0.1mm solid #888888;color:white;">
            <h4 class="to">Discription</h4>
         </td>
         <td width="13%" style="border: 0.1mm solid #888888;color:white;">
            <h4 class="to">Debit</h4>
         </td>
         <td width="13%" style="border: 0.1mm solid #888888;color:white;">
            <h4 class="to">Credit</h4>
         </td>
        <td width="6%" style="border: 0.1mm solid #888888;color:white;">
            <h4 class="to">Balance</h4>
        </td>
    </tr>
    <?php
    	for($i = 0; $i < $count;$i++)  {
    ?>
    @if(isset($records[$i]))
      	<tr>
          	<td colspan="1" height="15px" width="13%" style="border: 0.1mm solid #888888;">Sales</td>
          	<td colspan="1" height="15px" width="13%" style="border: 0.1mm solid #888888;">
            	{{ \DB::table('sub_companies')->where('id',$records[$i]->sub_company_id)->first()->name }}
        	</td>
          	<td colspan="1" height="15px" width="6%" style="border: 0.1mm solid #888888;"></td>
          	<td colspan="1" height="15px" width="6%" style="border: 0.1mm solid #888888;">
              	${{number_format($records[$i]->total_amount, 2)}}
          	</td>
          	<?php $total = $total + $records[$i]->total_amount; ?>
          	<td colspan="1" height="15px" width="6%" style="border: 0.1mm solid #888888;">
              	${{number_format($total, 2)}}
          	</td>
      	</tr>
    @endif
    @if(isset($purchases[$i]))
      	<tr>
          	<td colspan="1" height="15px" width="13%" style="border: 0.1mm solid #888888;">Purchase</td>
          	<td colspan="1" height="15px" width="13%" style="border: 0.1mm solid #888888;">
        		{{ \DB::table('supplier_companies')->where('id',$purchases[$i]->supplier_company_id)->first()->name }}
			</td>
          	<td colspan="1" height="15px" width="6%" style="border: 0.1mm solid #888888;">
              	${{number_format($purchases[$i]->total_amount, 2)}}
          	</td>
          	<td colspan="1" height="15px" width="6%" style="border: 0.1mm solid #888888;"></td>
          	<?php $total = $total - $purchases[$i]->total_amount; ?>
          	<td colspan="1" height="15px" width="6%" style="border: 0.1mm solid #888888;">
            	${{number_format($total, 2)}}
          	</td>
      	</tr>
    @endif
    @if(isset($expenses[$i]))
      	<tr>
          	<td colspan="1" height="15px" width="13%" style="border: 0.1mm solid #888888;">Expence</td>
          	<td colspan="1" height="15px" width="13%" style="border: 0.1mm solid #888888;">
          		{{ $expenses[$i]->description }}
          	</td>
          	<td colspan="1" height="15px" width="6%" style="border: 0.1mm solid #888888;">
              	${{number_format($expenses[$i]->amount, 2)}}
          	</td>
          	<td colspan="1" height="15px" width="6%" style="border: 0.1mm solid #888888;"></td>
          	<?php $total = $total - $expenses[$i]->amount; ?>
          	<td colspan="1" height="15px" width="6%" style="border: 0.1mm solid #888888;">
            	${{number_format($total, 2)}}
          	</td>
      	</tr>
    @endif
    <?php
	}   
   ?>
</table>
<br>
<br>
<br>
</body>
</html>