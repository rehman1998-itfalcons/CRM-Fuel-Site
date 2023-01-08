@extends('layouts.layout.main')
@section('title', 'Invoice Settings')
@section('contents')
    <style>
        .image {
            width: 110px;
            border-radius: 62px;
            height: 96px;
        }

        .close {
            cursor: pointer;
            position: relative;
            top: 50%;
            transform: translate(0%, -50%);
            opacity: 1;
            float: right;
            color: red;
        }

        .images {
            width: 104px;
            height: 58px;
        }

        .deletebtn {
            height: 27px;
            color: red;
            border: none;
        }
    </style>
    <!-- Contents -->
    <div class="container-fluid p-10">
        <div class="row justify-content-center">
            <div class="white_box mb_20">
            <div class="col-sm-12 ">
                <div class="QA_section">
                    <div class="white_box_tittle list_header">
                        <h4>Invoice Settings</h4>
                        <div class="box_right d-flex lms_block">
                            <div class="serach_field_2">
                                {{-- <div class="search_inner">
                                    <form Active="#">
                                        <div class="search_field">
                                            <input type="text" placeholder="Search content here...">
                                        </div>
                                        <button type="submit"> <i class="ti-search"></i> </button>
                                    </form>
                                </div> --}}
                            </div>
                            {{-- <div class="add_button ms-2">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#addcategory"
                                    class="btn-primary">Add New</a>
                            </div> --}}
                        </div>
                    </div>
                    <div class="">
                        <form action="{{ route('invoice.setting..update') }}" method="POST" enctype="multipart/form-data" onsubmit="return loginLoadingBtn(this)">
                                @csrf
                                 <strong>Selected Logo:</strong>
                                   <img src="{{($invoice) ? URL::asset('/public/uploads/siteinvoice/'.$invoice->invoice_logo) : '' }}" alt="" class="image"><br>
                                <div class="form-row mb-4">
                                    <div class="form-group col-md-6">
                                        <label>Invoice Logo</label>
                                      	<span style="color: red;"> *</span>
                                        <input type="file" name="invoice_logo" class="form-control @error('invoice_logo') is-invalid @enderror">
                                        @error('invoice_logo')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Invoice ABN</label>
                                      	<span style="color: red;"> *</span>
                                        <input type="text" name="invoice_abn" placeholder="Invoice ABN" class="form-control @error('invoice_abn') is-invalid @enderror" value="{{($invoice) ? $invoice->invoice_abn : ''}}">
                                        @error('invoice_abn')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                  </div>
                                 <h5>Company Details Area</h5>
                                  <div class="form-row mb-4">
                                    <div class="form-group col-md-6">
                                        <label>Invoice Bank</label>
                                      	<span style="color: red;"> *</span>
                                        <input type="text" name="invoice_bank" placeholder="Invoice Bank" class="form-control @error('invoice_bank') is-invalid @enderror" value="{{($invoice) ? $invoice->invoice_bank : ''}}">
                                        @error('invoice_bank')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Name</label>
                                      	<span style="color: red;"> *</span>
                                        <input type="text" name="name" placeholder="Name" class="form-control @error('name') is-invalid @enderror" value="{{($invoice) ? $invoice->name : ''}}">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Invoice BSB</label>
                                      	<span style="color: red;"> *</span>
                                        <input type="text" name="invoice_bsb" placeholder="Invoice BSB" value="{{($invoice) ? $invoice->invoice_bsb : ''}}" class="form-control @error('invoice_bsb') is-invalid @enderror">
                                        @error('invoice_bsb')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Invoice Account No</label>
                                      	<span style="color: red;"> *</span>
                                        <input type="text" name="invoice_account_no" placeholder="Invoice Account No" value="{{($invoice) ? $invoice->invoice_account_no : ''}}" class="form-control @error('invoice_account_no') is-invalid @enderror">
                                        @error('invoice_account_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Invoice Website Url</label>
                                      	<span style="color: red;"> *</span>
                                        <input type="text" name="invoice_web_url" placeholder="Invoice Website Url" value="{{($invoice) ? $invoice->invoice_web_url : ''}}" class="form-control @error('invoice_web_url') is-invalid @enderror">
                                        @error('invoice_web_url')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Invoice Phone No</label>
                                      	<span style="color: red;"> *</span>
                                        <input type="text" name="invoice_phone_no" placeholder="Invoice Phone No" value="{{($invoice) ? $invoice->invoice_phone_no : ''}}" class="form-control @error('invoice_phone_no') is-invalid @enderror">
                                        @error('invoice_phone_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Invoice Email</label>
                                      	<span style="color: red;"> *</span>
                                        <input type="email" name="invoice_email" placeholder="Invoice Email" value="{{($invoice) ? $invoice->invoice_email : ''}}" class="form-control @error('invoice_email') is-invalid @enderror">
                                        @error('invoice_email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Invoice Address</label>
                                      	<span style="color: red;"> *</span>
                                        <input type="text" name="invoice_address" placeholder="Invoice Address" value="{{($invoice) ? $invoice->invoice_address : ''}}" class="form-control @error('invoice_address') is-invalid @enderror">
                                        @error('invoice_address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Powerd By Text</label>
                                      	<span style="color: red;"> *</span>
                                        <input type="text" name="powerd_text" placeholder="Powerd Text" value="{{($invoice) ? $invoice->powerd_text : ''}}" class="form-control @error('powerd_text') is-invalid @enderror">
                                        @error('powerd_text')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                           <div class="form-row mb-4">
                            <h5>Pay Online Area</h5>
                                    <div class="form-group col-md-12">
                                        <label>Pay Online Text</label>
                                      	<span style="color: red;"> *</span>
                                      <textarea name="pay_online_text" placeholder="Pay Online Text" rows="2" class="form-control @error('pay_online_text') is-invalid @enderror">{{($invoice) ? $invoice->pay_online_text : ''}}</textarea>
                                        @error('pay_online_text')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                      </div>
                                   </div>
                          <div class="form-row mb-4">
                                    <div class="form-group col-md-6">
                                        <label>Pay Online Images</label>
                                      	<span style="color: red;"> *</span>
                                      <input type="file" name="pay_online_images[]" id="pay_online_images" class="form-control @error('pay_online_images.*') is-invalid @enderror">
                                        @error('pay_online_images.*')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                      </div>
                             <div class="form-group col-md-6">
                            <strong>Selected Images:</strong>
                                                <?php
                                                $files=[];
                                                if (isset($invoice->pay_online_imges)){
                                                    $files = explode("::",$invoice->pay_online_imges);
                                                    }
                                                ?>
                                 @if(isset($invoice->pay_online_imges))
                                 @foreach ($files as $file)
                                 <img src="{{($invoice) ? URL::asset('/public/uploads/siteinvoice/'.$file) : '' }}" alt="" class="images">
                               <button type="button" class="deletebtn" data-bs-toggle="modal" data-bs-target="#delete" onclick="deleteAttachment('{{ $file }}')"><svg style="margin-bottom: 60px;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                                 @endforeach
                               @endif
                              </div>
                          </div>
                          <h5>Telephone Area</h5>
                          <div class="form-row mb-4">
                                    <div class="form-group col-md-6">
                                        <label>Telephone Image1</label>
                                       <strong class="ml-4">Selected Image:</strong>
                                       @if(isset($invoice->telephone_header_img1) && $invoice->telephone_header_img1 != 'NULL')
                                       <img src="{{($invoice) ? URL::asset('/public/uploads/siteinvoice/'.$invoice->telephone_header_img1) : '' }}" alt="" class="images">
                                      <button type="button" class="deletebtn" data-bs-toggle="modal" data-bs-target="#deleteImage" onclick="deleteImage('{{ $invoice->telephone_header_img1 }}')"><svg style="margin-bottom: 60px;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                                      @endif
                                      <br>
                                        <input type="file" name="telephone_header_img1" class="form-control @error('telephone_header_img1') is-invalid @enderror">
                                        @error('telephone_header_img1')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                      <div class="form-group col-md-6">
                                        <label>Telephone Image2</label>
                                        <strong class="ml-4">Selected Image:</strong>
                                        @if(isset($invoice->telephone_header_img2) && $invoice->telephone_header_img2 != 'NULL')
                                         <img src="{{($invoice) ? URL::asset('/public/uploads/siteinvoice/'.$invoice->telephone_header_img2) : '' }}" alt="" class="images">
                                        <button type="button" class="deletebtn" data-bs-toggle="modal" data-bs-target="#deleteImage" onclick="deleteImage('{{ $invoice->telephone_header_img2 }}')"><svg style="margin-bottom: 60px;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                                        @endif
                                        <br>
                                        <input type="file" name="telephone_header_img2" class="form-control @error('telephone_header_img2') is-invalid @enderror">
                                        @error('telephone_header_img2')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    </div>

                                    <div class="form-row mb-4">
                                    <div class="form-group col-md-12">
                                        <label>Telephone Header</label>
                                      	<span style="color: red;"> *</span>
                                        <input type="text" name="telephone_header" placeholder="Telephone Header" value="{{($invoice) ? $invoice->telephone_header : ''}}" class="form-control @error('telephone_header') is-invalid @enderror">
                                        @error('telephone_header')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    </div>
                                  <div class="form-row mb-4">
                                    <div class="form-group col-md-12">
                                        <label>Telephone Text</label>
                                      	<span style="color: red;"> *</span>
                                      	<textarea name="telephone_text" placeholder="Telephone Text" rows="5" class="form-control @error('telephone_text') is-invalid @enderror">{{($invoice) ? $invoice->telephone_text : ''}}</textarea>
                                        @error('telephone_text')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    </div>
                          		<div class="row">
                                  <div class="col-md-12" id="loading-btn">
                                    <button type="submit" class="btn btn-md btn-primary">
                                      Update Settings
                                    </button>
                                  </div>
                             </div>
                       </form>
        		  </div>
        	 </div>
        </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="display:inline;">
                    <h6 class="modal-title">Delete Image</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="margin-left: 385px;color:black;">x</button>
                </div>
                <form action="{{ url('/delete-payonline_image') }}" method="POST">
                    @csrf
                    <input type="hidden" name="attachment" id="attachment" value="">
                    <div class="modal-body">
                        <div class="form-group">
                            <p>Are you sure to delete attachment?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Confirm Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteImage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="display:inline;">
                    <h6 class="modal-title">Delete Image</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="margin-left: 385px;color:black;">x</button>
                </div>
                <form action="{{ url('/delete-header_img') }}" method="POST">
                    @csrf
                    <input type="hidden" name="header_image" id="header_image" value="">
                    <div class="modal-body">
                        <div class="form-group">
                            <p>Are you sure to delete attachment?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Confirm Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script src="{{ URL::asset('assets/js/jquery.multifile.js') }}"></script>
    <script>
        function loginLoadingBtn() {
            document.getElementById('loading-btn').innerHTML =
                '<button class="btn btn-primary disabled" style="width: auto !important;">Updating <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
            return true;
        }
    </script>

    <script type="text/javascript">
        jQuery(function($) {
            $('#pay_online_images').multifile();
        });
    </script>
    <script>
        function deleteAttachment(id) {
            $('#attachment').val(id);
        }

        function deleteImage(id) {
            $('#header_image').val(id);
        }
    </script>

@endsection
