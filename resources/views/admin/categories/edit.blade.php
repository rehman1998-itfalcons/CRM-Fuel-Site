@extends('layouts.layout.main')
@section('title','Update Category')
@section('css')

@endsection
@section('contents')


<div class="container-fluid p-10">
    <div class="row justify-content-center">
		<div class="white_box mb_20">
    <div class="row layout-top-spacing p-10" id="cancel-row">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>
                                  Update Category
                                	<a href="{{ route('categories.index') }}" class="btn btn-sm btn-primary" style="float: right;">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left close-message"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                      Back
                                    </a>
                              	</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <form action="{{ route('categories.update',$category->id) }}" method="POST" onsubmit="return loginLoadingBtn(this)">
                            @csrf
                            @method('PUT')
                          	<input type="hidden" name="type" value="main">
                            <div class="form-row mb-4">
                                <div class="col-md-6">
                                    <label>Category</label>
                                    <small style="color: red;"> *</small>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Company" required autocomplete="off" value="{{ $category->name }}">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
                                    @enderror
                                </div>
                                <div class="col-md-6"></div>
                            </div>
                            <div class="form-row mb-4">
                                <div class="col-md-4">
                                  <h6>Show in Rate Area</h6>
                                  <hr>
                                  <label>
	                                <input type="checkbox" {{ ($category->rate_whole_sale == 1) ? 'checked' : '' }} name="rate_whole_sale" value="1">
                                  	Whole Sale
                                    <span style="float: right;"> &nbsp;&nbsp;
                                    </span>
                                  </label><br>
                                  <label>
                                    Display ON: Frontend <input type="radio" @if($category->whole_sale_display == 1) checked @elseif($category->whole_sale_display == '') checked @endif name="whole_sale_display" value="1"> Backend <input type="radio" {{ ($category->whole_sale_display == 2) ? 'checked' : '' }} name="whole_sale_display" value="2">
                                  </label><br>
                                  <label>
	                                <input type="checkbox" {{ ($category->rate_discount == 1) ? 'checked' : '' }} name="rate_discount" value="1">
                                    Discount
                                  </label><br>
                                  <label>
	                                <input type="checkbox" {{ ($category->rate_delivery_rate == 1) ? 'checked' : '' }} name="rate_delivery_rate" value="1">
                                    Delivery Rate
                                  </label><br>
                                  <label>
	                                <input type="checkbox" {{ ($category->rate_brand_charges == 1) ? 'checked' : '' }} name="rate_brand_charges" value="1">
                                    Brand Charges
                                  </label><br>
                                  <label>
	                                <input type="checkbox" {{ ($category->rate_cost_of_credit == 1) ? 'checked' : '' }} name="rate_cost_of_credit" value="1">
                                    Cost of credit/limit
                                  </label>
                              </div>
                                <div class="col-md-4">
                                  <h6>Show in Report</h6>
                                  <hr>
                                  <label>
	                                <input type="checkbox" {{ ($category->report_whole_sale == 1) ? 'checked' : '' }} name="report_whole_sale" value="1">
                                  	Whole Sale
                                  </label><br>
                                  <label>
	                                <input type="checkbox" {{ ($category->report_discount == 1) ? 'checked' : '' }} name="report_discount" value="1">
                                    Discount
                                  </label><br>
                                  <label>
	                                <input type="checkbox" {{ ($category->report_delivery_rate == 1) ? 'checked' : '' }} name="report_delivery_rate" value="1">
                                    Delivery Rate
                                  </label><br>
                                  <label>
	                                <input type="checkbox" {{ ($category->report_brand_charges == 1) ? 'checked' : '' }} name="report_brand_charges" value="1">
                                    Brand Charges
                                  </label><br>
                                  <label>
	                                <input type="checkbox" {{ ($category->report_cost_of_credit == 1) ? 'checked' : '' }} name="report_cost_of_credit" value="1">
                                    Cost of credit/limit
                                  </label>
                              </div>
                                <div class="col-md-4">
                                  <h6>Show in Invoice</h6>
                                  <hr>
                                  <label>
	                                <input type="checkbox" {{ ($category->invoice_whole_sale == 1) ? 'checked' : '' }} name="invoice_whole_sale" value="1">
                                  	Whole Sale
                                  </label><br>
                                  <label>
	                                <input type="checkbox" {{ ($category->invoice_discount == 1) ? 'checked' : '' }} name="invoice_discount" value="1">
                                    Discount
                                  </label><br>
                                  <label>
	                                <input type="checkbox" {{ ($category->invoice_delivery_rate == 1) ? 'checked' : '' }} name="invoice_delivery_rate" value="1">
                                    Delivery Rate
                                  </label><br>
                                  <label>
	                                <input type="checkbox" {{ ($category->invoice_brand_charges == 1) ? 'checked' : '' }} name="invoice_brand_charges" value="1">
                                    Brand Charges
                                  </label><br>
                                  <label>
	                                <input type="checkbox" {{ ($category->invoice_cost_of_credit == 1) ? 'checked' : '' }} name="invoice_cost_of_credit" value="1">
                                    Cost of credit/limit
                                  </label>
                              </div>
                            </div>
                            <div class="form-row mb-4">
                                <div class="col-md-12" id="loading-btn">
                                    <button type="submit" class="btn btn-md btn-primary">
                                        Update Category
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')

    <script>

        function loginLoadingBtn()
        {
            document.getElementById('loading-btn').innerHTML = '<button class="btn btn-md btn-primary disabled" style="width: auto;">Updating <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
            return true;
        }

    </script>

@endsection
