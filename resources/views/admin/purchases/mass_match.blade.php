 	@extends('layouts.template')
@section('title','Mass Match')
@section('css')
@endsection
@section('contents')


<style>
        .purchase_order {
            padding: 15px;
            background-color: #efc7c7;
            border-radius: 12px;
            cursor: pointer;
        }

        .purchase_order:hover {

            background-color: #fbaeae;

        }

        .invocie_order {
            padding: 15px;
            background-color: #d4f8fd;
            border-radius: 12px;
            cursor: pointer;
        }

        .invocie_order:hover {
            background-color: #9ddce4;
        }

        .heading_design {
            text-align: center;
            background-color: #5d78ff;
            color: white;
            padding: 6px;
            border-radius: 5px;
            padding-top: 12px;
        }

    </style>

  <div class="row layout-top-spacing" id="cancel-row">
      <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="widget-content widget-content-area br-6" >
          <div class="widget-header">
            <div class="row">
              <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                <h3>List of Purchase orders to Match</h3>
              </div>
            </div>
          </div>
          <hr>
          <div class="row text-center" style="background-color: #5d78ff; padding: 6px; border-radius: 5px;">
            <div class="col-md-6">
              <h4 style="color:white;">Purchase Order</h4>  
            </div>
            <div class="col-md-6">
              <h4 style="color:white;">
                Invoices
              </h4>      
            </div>            
          </div>         
          <div class="row justify-content-center mt-2" id="whole_match_div3">
           <div class="col-md-4 ">
             <div class="purchase_order">
               <div class="row">
                 <div class="col-md-6">
                   <b>Fuel Company</b>
                 </div>
                 <div class="col-md-6">
                   BP for SMJ through United                                                    
                 </div>
                </div>
               <div class="row">
                 <div class="col-md-6">
                   <b>Invoice NO</b>
                 </div>
                 <div class="col-md-6">
                    430247                                                                                                        
                 </div>
                </div>
               <div class="row">
                 <div class="col-md-6">
                   <b> Purchase Order</b>
                 </div>
               <div class="col-md-6">
                       PO-80003
               </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <b> Total Quantities</b>
                </div>
                <div class="col-md-6">
                  6030
                </div>
               </div>
               <div class="row">             
                 <div class="col-md-6">               
                   <b> Total Amount</b>       
                 </div>
                 <div class="col-md-6">             
                   5681.16
                 </div>
                </div>                          
               <div class="row">
                 <div class="col-md-6">                
                   <b> Assign Multiple Invoices </b>                   
                 </div>                 
                 <div class="col-md-6">
                   <a href="#" class="d-sm-inline-block btn btn-sm btn-danger shadow-sm mb-1  waves-effect waves-button waves-light">
                     <i class="fa fa-external-link-square" aria-hidden="true"></i>                     
                   </a>
                 </div>
               </div>
             </div>
            </div>
            <div class="col-md-3 pt-4 text-center">
              
              
              <button type="button" class="match_mark_done text-center btn  btn-primary open_model waves-effect waves-button waves-light" style=" background-color: #5d78ff;
            border-color: #5d78ff;" >                                                
                <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="20px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-up"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg> Done             
              </button>
              </div>
            <div class="col-md-4 text-center ">
              <a href="#" class="d-sm-inline-block btn btn-sm btn-info shadow-sm mb-1  waves-effect waves-button waves-light">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg> Click for manual insertion
              </a>
            </div>
          </div>
          <hr>
        </div>
      </div>
  </div>

@endsection