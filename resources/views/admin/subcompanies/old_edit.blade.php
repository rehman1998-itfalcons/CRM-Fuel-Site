@extends('layouts.template')
@section('title','Update SubCompany')
@section('css')
	<style>
      .btn-danger {
	  	border-radius: 25px !important;
        box-shadow: 0px 5px 20px 0 rgba(0, 0, 0, 0.1);
      }
	</style>
@endsection
@section('contents')


    <div class="row layout-top-spacing">
        <div class="col-md-12 col-sm-12 col-12 layout-spacing">
        	<div id="flFormsGrid" class="col-lg-12 layout-spacing">
        		<div class="statbox widget box box-shadow">
        			<div class="widget-header">
        				<div class="row">
        					<div class="col-xl-12 col-md-12 col-sm-12 col-12">
        						<h4>
                                  Update SubCompany
                                	<a href="{{ route('companies.index') }}" class="btn btn-md btn-primary" style="float: right;">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left close-message"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                      Back
                                    </a>
                              	</h4>
                          </div>
        				</div>
                      <hr>
        			</div>
        			<div class="widget-content widget-content-area">
						<form action="{{ route('subcompanies.update',$subcompany->id) }}" method="POST" id="company-form">
							@csrf
                          	@method('PUT')
						    <div class="form-row mb-4">
                            <div class="col-md-6">
                              <label>Select Category</label>
                              <small style="color: red;"> *</small>
                              <select name="category_id" class="form-control" id="category_id" onchange="updateCategory('{{$subcompany->id}}')" required>
                                <option value="">--Select--</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ ($subcompany->category_id == $category->id) ? 'selected' : '' }}>
                                      {{ $category->name }}
                                	</option>
                                @endforeach
                              </select>
                            </div>
                               <div class="col-md-6">
                                 <label>Main Company</label>
						            <small style="color: red;"> *</small>
						            <select name="company_id" id="company_id" class="form-control">
                                      <option value="">--Select--</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}" {{ ($subcompany->company_id == $company->id) ? 'selected' : '' }}>{{ $company->name }}</option>
                                    @endforeach
                                 </select>
                                 </div>
                          	</div>
                          	<div class="form-row mb-4">
						        <div class="col-md-6">
						            <label>Sub Company Name</label>
						            <small style="color: red;"> *</small>
						            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Sub Company" required autocomplete="off" value="{{ $subcompany->name }}">
	                                @error('name')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
						        </div>

                               <div class="col-md-6">
						            <label>Split Load</label>
						            <small style="color: red;"> *</small>
						            <input type="number" name="split_load" class="form-control @error('split_load') is-invalid @enderror" step="any" placeholder="split load value" required autocomplete="off" value="{{ $subcompany->split_load }}">
	                                @error('split_load')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
						        </div>
                          	</div>
                          @php
                          	$emails = $subcompany->emails;
                          	$i = 1;
                          @endphp
                          <input type="hidden" id="counter" name="counter" value="{{ $emails->count() }}">
                          <div class="form-row mb-4">
                            <div class="col-md-6">
                              <strong style="color: red;">* Note: Delete an e-mail field by clicking "Delete" button.</strong>
                              <a class="btn btn-sm btn-dark" style="border: 1px solid #999; float: right;" onclick="addEmail()">
                              	Add E-Mail
                              </a>
                            </div>
                            <div class="col-md-6"></div>
                          </div>
                          @foreach ($emails as $email)
                            <div class="form-row mb-4" id="add_email_{{ $i }}">
                              <div class="form-group col-md-6">
                                <input type="email" placeholder="E-Mail address" value="{{ $email->email_address }}" name="email_{{ $i }}" id="email_{{ $i }}" class="form-control" autocomplete="off">
                              </div>
                              <div class="form-group col-md-6">
                                <a style="padding: 13px;" data-toggle="tooltip" data-placement="top" data-original-title="Delete" class="btn btn-sm btn-danger" onclick="deleteEmail('{{ $email->id }}','{{ $i }}')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                              </div>
                            </div>
                          	<?php $i++; ?>
                          @endforeach
                          <div id="new-recipient">
                          </div>
                          	<div class="form-row mb-4">
                               <div class="form-group col-md-6">
                                <label> Gst Included/Excluded </label>
                                <small style="color: red;"> *</small>
                                <select class="form-control" name="gst_status">
                                    <option value="Included" {{ ($subcompany->gst_status == 'Included') ? 'selected' : '' }}> Included </option>
                                    <option value="Excluded" {{ ($subcompany->gst_status == 'Excluded') ? 'selected' : '' }}> Excluded </option>
                                </select>
                            </div>

						        <div class="form-group col-md-6">
						            <label>GST ( % )</label>
						            <small style="color: red;"> *</small>
						            <input type="text" class="form-control @error('gst') is-invalid @enderror" name="gst" placeholder="GST" autocomplete="off" value="{{ $subcompany->gst }}">
	                                @error('gst')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
						        </div>
                          </div>
                              <div class="form-row mb-4">
						        <div class="form-group col-md-6">
						            <label>Phone No</label>
						            <small style="color: red;"> *</small>
						            <input type="text" class="form-control @error('phone_no') is-invalid @enderror" name="phone_no" id="phone_no" placeholder="Phone No" autocomplete="off" value="{{ $subcompany->phone_no }}" required>
	                                @error('phone_no')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
						        </div>

                                <div class="form-group col-md-6">
                                <label> <strong>Invoice Type</strong></label>&nbsp;
           <label><input type="radio"  name="inv_type" class="" value="extra" {{ ($subcompany->inv_due_days >= 0) ? 'checked' : '' }}> From Invoice Date </label>
           <label><input type="radio" name="inv_type" class="" value="-1" {{ ($subcompany->inv_due_days < 0) ? 'checked' : '' }}> From End Of Month </label>
           <input type="text" class="form-control @error('due_date') is-invalid @enderror" step="any" id="due_date" name="due_date" value="{{ abs($subcompany->inv_due_days) }}">
                                    @error('due_date')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
                            </div>

						    </div>
						    <div class="form-row mb-4">
                              <div class="form-group col-md-12">
                                <label>Address</label>
                                <small style="color: red;"> *</small>
                                <textarea name="address" rows="4" class="form-control @error('address') is-invalid @enderror">{{ $subcompany->address }}</textarea>
                                 @error('address')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
                              </div>
						    </div>
						    <div class="form-row mb-4">
                              <div class="form-group col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-4">
                                        <thead>
                                            <tr>
                                              @php
                                              $category = $subcompany->category;
                                              @endphp
                                                <th>Products</th>
                                                @if($category->rate_whole_sale != '')
                                                @if($category->whole_sale_display == 2)
                                              	<th>Whole Sale</th>
                                               @endif
                                              @endif
                                              	<th>Discount</th>
                                              	<th>Delivery Rate</th>
                                              	<th>Brand Charges</th>
                                              	<th>Cost of credit/limit</th>
                                            </tr>
                                        </thead>
                                      	<tbody>
                                          @foreach ($products as $product)
                                          	@php
                                          		$result = \App\SubCompanyRateArea::
                                                                where('sub_company_id',$subcompany->id)
                                          						->where('product_id',$product->id)
                                          						->first();
                                          	@endphp
                                          	@if($result)
                                              <tr>
                                                <td>{{ $product->name }}</td>
                                                @if($category->rate_whole_sale != '')
                                                @if($category->whole_sale_display == 2)
                                                <td>
                                                <input type="text" name="whole_sale_{{ $product->id }}" value="{{ $result->whole_sale }}" class="form-control">
                                                </td>
                                                 @endif
                                                 @endif
                                                <td>
                                                  <input type="text" name="discount_{{ $product->id }}" @if($category->rate_discount == '') value="0" style="visibility:hidden;" @else value="{{ $result->discount }}" @endif class="form-control">
                                                </td>
                                                <td>
                                                  <input type="text" name="delivery_rate_{{ $product->id }}" @if($category->rate_delivery_rate == '') value="0" style="visibility:hidden;" @else value="{{ $result->delivery_rate }}" @endif class="form-control">
                                                </td>
                                                <td>
                                                  <input type="text" name="brand_charges_{{ $product->id }}" @if($category->rate_brand_charges == '') value="0" style="visibility:hidden;" @else value="{{ $result->brand_charges }}" @endif class="form-control">
                                                </td>
                                                <td>
                                                  <input type="text" name="cost_of_credit_{{ $product->id }}" @if($category->rate_cost_of_credit == '') value="0" style="visibility:hidden;" @else value="{{ $result->cost_of_credit }}" @endif class="form-control">
                                                </td>
                                              </tr>
                                          	@else
                                              <tr>
                                                <td>{{ $product->name }}</td>
                                                @if($category->rate_whole_sale != '')
                                                @if($category->whole_sale_display == 2)
                                                <td>
                                                <input type="text" name="whole_sale_{{ $product->id }}" value="{{ old('whole_sale_'.$product->id) }}" class="form-control">
                                                </td>
                                                 @endif
                                                @endif
                                                <td>
                                                  <input type="text" name="discount_{{ $product->id }}" @if ($category->rate_discount == '') value="0" style="visibility:hidden;"  @else value="{{ old('discount_'.$product->id) }}" @endif class="form-control">
                                                </td>
                                                <td>
                                                  <input type="text" name="delivery_rate_{{ $product->id }}" @if ($category->rate_delivery_rate == '') value="0" style="visibility:hidden;"  @else value="{{ old('delivery_rate_'.$product->id) }}" @endif class="form-control">
                                                </td>
                                                <td>
                                                  <input type="text" name="brand_charges_{{ $product->id }}" @if ($category->rate_brand_charges == '') value="0" style="visibility:hidden;"  @else value="{{ old('brand_charges_'.$product->id) }}" @endif class="form-control">
                                                </td>
                                                <td>
                                                  <input type="text" name="cost_of_credit_{{ $product->id }}" @if ($category->rate_cost_of_credit == '') value="0" style="visibility:hidden;"  @else value="{{ old('cost_of_credit_'.$product->id) }}" @endif class="form-control">
                                                </td>
                                              </tr>
                                          	@endif
                                          @endforeach
                                      	</tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
						    <div class="form-row mb-4">
						    	<div class="form-group col-md-12" id="loading-btn">
						    		<button type="submit" id="submit-btn" class="btn btn-primary">
						    			Update SubCompany
						    		</button>
                                  	<p id="error" style="color: red; font-weight: 600;"></p>
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

        $('#submit-btn').click(function(e) {
        	var category_id = $('#category_id').val();
          	var company_id = $('#company_id').val();
          	var name = $('#name').val();
          	var phone_no = $('#phone_no').val();
          	var due_date = $('#due_date').val();

          	if (category_id == '')
            	$('#category_id').css('border-color','#E91E63');
          	else
            	$('#category_id').css('border-color','#bfc9d4');

          	if (company_id == '')
            	$('#company_id').css('border-color','#E91E63');
          	else
            	$('#company_id').css('border-color','#bfc9d4');

          	if (name == '')
            	$('#name').css('border-color','#E91E63');
          	else
            	$('#name').css('border-color','#bfc9d4');

          	if (phone_no == '')
            	$('#phone_no').css('border-color','#E91E63');
          	else
            	$('#phone_no').css('border-color','#bfc9d4');

          	if (due_date == '')
            	$('#due_date').css('border-color','#E91E63');
          	else
            	$('#due_date').css('border-color','#bfc9d4');

          	if (category_id != '' && company_id != '' && name != '' && phone_no != '' && due_date != '') {
              	$('#error').html('');
          		document.getElementById('loading-btn').innerHTML = '<button class="btn btn-primary disabled" style="width: auto !important;">Updating <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
              	$('form#company-form').submit();
          		return true;
            } else {
              	$('#error').html('* Please fill all required fields');
              	e.preventDefault();
            	return false;
            }
       });


        function addEmail()
        {
            var total = $('#counter').val();
            var count = parseInt(total);
            if (count > 0) {
                var new_count = count + 1;
                document.getElementById('new-recipient').insertAdjacentHTML('beforeend', '<div class="form-row mb-4" id="add_email_'+new_count+'"><div class="form-group col-md-6"><input type="email" placeholder="E-Mail address" name="email_' + new_count + '" id="email_' + new_count + '" class="form-control" autocomplete="off"></div><div class="form-group col-md-6"><a data-toggle="tooltip" data-placement="top" data-original-title="Delete" style="padding: 13px;" class="btn btn-sm btn-danger" onclick="removeEmail('+new_count+')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></div></div>');
                $('#counter').val(new_count);
          } else {
            var new_count = 1;
            document.getElementById('new-recipient').insertAdjacentHTML('beforeend', '<div class="form-row mb-4" id="add_email_'+new_count+'"><div class="form-group col-md-6"><input type="email" placeholder="E-Mail address" name="email_' + new_count + '" id="email_' + new_count + '" class="form-control" autocomplete="off"></div><div class="form-group col-md-6"><a data-toggle="tooltip" data-placement="top" data-original-title="Delete" style="padding: 13px;" class="btn btn-sm btn-danger" onclick="removeEmail('+new_count+')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></div></div>');
            $('#counter').val(new_count);
         }
      }

      	function removeEmail(id)
      	{
          $('#add_email_'+id).css('display','none');
        }

      	function deleteEmail(id,sno)
      	{
          $.post('{{ url('/delete-subcompany-email') }}',{_token:'{{ csrf_token() }}', id:id}, function(data){
            $('#email_'+sno).val('');
            $('#add_email_'+sno).css('display','none');
          });
        }


	</script>
      <script>
       function updateCategory(subcompany_id)
        {
        var category_id = $("#category").val();
        $.post('{{ url('/update-category') }}',{_token:'{{ csrf_token() }}', category_id:category_id,subcompany_id:subcompany_id}, function(data){
            location.reload();
          });
      }
      </script>

    <script>
        $('[data-toggle="tooltip"]').tooltip()
    </script>
@endsection
