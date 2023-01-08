<!DOCTYPE html>
<html lang="en">
<head>
  <title>Invoice</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    
  .inerr-div{
    margin-left: 48px;
  }
    .subheading{
      background: #0e095feb;
      color: white;
      font-weight: 600;
    }
    .below_headrer{
      background: #5f5e65eb;
      color: white;
      font-weight: 600;
    }
    .border-top{
    border: 1px solid !important;
    border-top: 1px solid !important;
    }
    .mark
    {
     background-color: #dee4ea;
    }
    .inner_span{
        margin-left: 75px;
    }
    .table td, .table th {
    border-top: none !important;
    }
    table{
       border: 2px solid black;
     }
    tr { 
      border: none; 
    }
   td 
    {
    border-right: solid 2px black; 
    border-left: solid 2px black;
  	}
    hr
    {
    border-top: 2px solid black !important;
    }
    .doller_width
    {
      margin-left: 179px;
    }
    
    .table_td
    {
    border-right: none;
    border-left: none;
    }
    .table_tr
    {
      line-height: 0.5 !important;
    }
  </style>
  
</head>
<body>

  
<div class="container" style="border:1px solid #2b1919;padding: 44px;">
  <div class="row">
    <div class="col-md-6">
      <h3>
        [Company Name]
      </h3>
      <span>[Company Slogen]</span>
      <br><br>
      [stress address]<br>
      [city,st,zip]<br>
      [phone:000-000-000]<br>
      [fax: 000-000-000]
      </div>
      <div class="col-md-6" style="padding-left: 208px;">
      <h2 style="color: #2c51bb;font-weight: 600;">
        PRO FORMA INVOICE
        </h2>
        
        <div class="inerr-div">
          <table class="table" style="border:none;">
          <tbody>
          <tr class="table_tr">
            <td class="table_td">Date</td>
             <td class="table_td"><mark class="mark">12/9/2020</mark></td>
            </tr>
             <tr class="table_tr">
            <td class="table_td">Expiration Date</td>
             <td class="table_td"><mark class="mark">12/9/2020</mark></td>
            </tr>
             <tr class="table_tr">
            <td class="table_td">invoice #</td>
             <td class="table_td border-top" style="border-top: 1px solid !important;">0002345</td>
            </tr>
              <tr class="table_tr">
            <td class="table_td">Customer ID</td>
             <td class="table_td border-top">0002345</td>
            </tr>
         </tbody>
      </table>
       </div>
    </div>
  </div>
 <br>
  
  <div class="row">
    <div class="col-md-4">
      <h4 class="subheading">
        CUSTOMER
      </h4>
      <span>[Name]</span><br>
      [company Name]<br>
      [stress address]<br>
      [city, St zip]<br>
      [phone]
    </div>
      <div class="col-md-4">
      <h4 class="subheading">
        SHIP TO
        </h4>
       <span>[Name]</span><br>
      [company Name]<br>
      [stress address]<br>
      [city, St zip]<br>
      [phone]
    </div>
    
       <div class="col-md-4">
         <h4 class="subheading">
           SHIPPING DETAILS
         </h4>
      Fright Type <span style="margin-left: 115px;">[Air of ocean]</span><br>
      Est Ship Date  <span style="margin-left: 100px;">[date]</span><br>
      Est Gross Weight  <span class="inner_span">[weight][units]</span><br>
      Est Qubic Weight  <span class="inner_span">[weight][units]</span><br>
      Total Padges <span style="margin-left: 107px;">[qty]</span>
    </div>
  </div>
  <br>
  <br>
  
  
  <div class="row">
    <div class="col-md-12">
      <table class="table">
  <thead class="subheading">
    <tr>
       <th scope="col" style="width: 4px;">PART NUMBER</th>
       <th scope="col">UNIT OF MEASURE</th>
       <th scope="col" style="width: 470px;">DESCRIPTION</th>
       <th scope="col">QTY</th>
       <th scope="col">UNIT PRICE</th>
       <th scope="col">TAX</th>
       <th scope="col">TOTAL AMOUNT</th>
    </tr>
  </thead>
  <tbody>
    <tr>
       <th>1</th>
       <td>Mark</td>
       <td>Otto</td>
       <td>@mdo</td>
       <td>@mdo</td>
       <td>@mdo</td>
       <td>@mdo</td>
    </tr>
    <tr>
       <th>2</th>
       <td>Jacob</td>
       <td>Thornton</td>
       <td>@fat</td>
       <td>@mdo</td>
       <td>@mdo</td>
       <td>@mdo</td>
    </tr>
    <tr>
       <th>3</th>
       <td>Larry</td>
       <td>the Bird</td>
       <td>@twitter</td>
       <td>@mdo</td>
       <td>@mdo</td>
       <td>@mdo</td>
    </tr>
  </tbody>
</table>   
    </div>
  </div>
  
  <div class="row">
    <div class="col-md-8">
      <div style="border: 2px solid black;">
      <h4 class="subheading">
       TERMS OF SALE AND OTHER COMMENTS	
        </h4>
        <P>
          INCLUDE TERMS AND SERVICES AS DESCRIBED<br>
           INCLUDE TERMS AND SERVICES AS DESCRIBED
        </P>
    </div>
   </div>
    
     <div class="col-md-4">
        <table class="table" style="border:none;">
          <tbody>
          <tr class="table_tr">
            <td class="table_td">Sub Total</td>
             <td class="table_td"><strong>$</strong></td>
             <td class="table_td">23</td>
            </tr>
             <tr class="table_tr">
            <td class="table_td">Taxable</td>
             <td class="table_td"><strong>$</strong></td>
             <td class="table_td">23</td>
            </tr>
             <tr class="table_tr">
            <td class="table_td">Taxrate</td>
             <td class="table_td"><strong>$</strong></td>
             <td class="table_td">23</td>
            </tr>
            <tr class="table_tr">
            <td class="table_td">Tax</td>
             <td class="table_td"><strong>$</strong></td>
             <td class="table_td">23</td>
            </tr>
            <tr class="table_tr">
            <td class="table_td">Freight</td>
             <td class="table_td"><strong>$</strong></td>
             <td class="table_td">23</td>
            </tr>
            <tr class="table_tr">
            <td class="table_td">Insurance</td>
             <td class="table_td"><strong>$</strong></td>
             <td class="table_td">23</td>
            </tr>
             <tr class="table_tr">
            <td class="table_td">Legal/Inspection</td>
             <td class="table_td"><strong>$</strong></td>
             <td class="table_td">23</td>
            </tr>
            <tr class="table_tr">
            <td class="table_td">Inspeaction/Cart</td>
             <td class="table_td"><strong>$</strong></td>
             <td class="table_td">23</td>
            </tr>
            <tr class="table_tr">
            <td class="table_td">Other (Specify)</td>
             <td class="table_td"><strong>$</strong></td>
             <td class="table_td">23</td>
            </tr>
            <tr class="table_tr">
            <td class="table_td">Other (Specify)</td>
             <td class="table_td"><strong>$</strong></td>
             <td class="table_td">23</td>
            </tr>
            <tr class="" style="border-top: 2px solid black;border-bottom: 2px solid black;line-height: 0.5px;">
              <td style="visibility:hidden;" class="table_td">d</td>
              <td style="visibility:hidden;" class="table_td">d</td>
              <td style="visibility:hidden;" class="table_td">$</td>
            </tr>
             <tr class="table_tr">
             <td class="table_td"><strong>TOTAL</strong></td>
              <td class="table_td"><strong>$</strong></td>
               <td class="table_td"><strong><mark class="mark">447.47</mark></strong></td>
            </tr>
             <tr class="table_tr">
             <td class="table_td">Currency</td>
             <td class="table_td">USD</td>
            </tr>
          </tbody>
       </table>
    </div>
  </div>
  
  <div class="row">
    <div class="col-md-12">
      <h4 class="below_headrer">
        ADDITIONAL DETAILS
      </h4>
      </div>
  </div>
   <div class="row">
    <div class="col-md-4">
       <table class="table" style="border:none;">
          <tbody>
          <tr class="table_tr">
            <td class="table_td">Country Of Origion</td>
             <td class="table_td">[country]</td>
            </tr>
             <tr class="table_tr">
            <td class="table_td">Part Of Embralation</td>
             <td class="table_td">[Name]</td>
            </tr>
             <tr class="table_tr">
            <td class="table_td">Country Of Discharge</td>
             <td class="table_td">[Name]</td>
            </tr>
         </tbody>
      </table>
    </div>
  </div>
  
  <strong>Reason For Export :</strong> <input type="text" style="margin-left: 81px;width: 485px;"><br>
  <br>
  <p> I certify the above to true and correct to the best of my knowledge.</p>
  
  <div class="row">
  <div class="col-md-6">
     <p>X</p>
  <hr>
  <strong>[typed name]</strong><br>
  <strong>[company name]</strong>
  </div> 
    
    <div class="col-md-2">
      <p style="visibility:hidden;">fgh</p>
  <hr>
  <p>[Date]</p>
    </div> 
  </div>
  
  
</div>
</body>
</html>
