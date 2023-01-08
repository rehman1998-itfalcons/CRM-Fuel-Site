@extends('layouts.template')
@section('title','Update Account')
@section('css')

    <style>
        .widget-content-area {
            -webkit-box-shadow: 0 4px 6px 0 rgba(85, 85, 85, 0.0901961), 0 1px 20px 0 rgba(0, 0, 0, 0.08), 0px 1px 11px 0px rgba(0, 0, 0, 0.06);
            -moz-box-shadow: 0 4px 6px 0 rgba(85, 85, 85, 0.0901961), 0 1px 20px 0 rgba(0, 0, 0, 0.08), 0px 1px 11px 0px rgba(0, 0, 0, 0.06);
            box-shadow: 0 4px 6px 0 rgba(85, 85, 85, 0.0901961), 0 1px 20px 0 rgba(0, 0, 0, 0.08), 0px 1px 11px 0px rgba(0, 0, 0, 0.06);
        }

        .report {
            margin-right: 5px;
        }

    </style>
  <link href="{{ URL::asset('plugins/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ URL::asset('plugins/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">

@endsection
@section('contents')

    <div class="row layout-top-spacing" id="cancel-row">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            @if($errors->any())
                <ul class="alert alert-warning"
                    style="background: #eb5a46; color: #fff; font-weight: 300; line-height: 1.7; font-size: 16px; list-style-type: circle;">
                    {!! implode('', $errors->all('<li>:message</li>')) !!}
                </ul>
            @endif
            <div class="widget-content widget-content-area br-6">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Update Account</h4>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="">
                    <form action="{{ route('bank.account.show') }}" method="POST" enctype="multipart/form-data" onsubmit="return loginLoadingBtn(this)">
                        @csrf
                      	<input type="hidden" name="id" value="{{ $account->id }}">
                        <div class="form-row mb-4">
                            <div class="form-group col-md-4">
                                <label>Account Name</label>
                                <span style="color: red;" id="name-error"> *</span>
                                <input type="text" class="form-control @error('account_name') is-invalid @enderror" name="account_name" id="account_name" placeholder="Account Name" required autocomplete="off" value="{{ $account->account_name }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Account number</label>
                                <span style="color: red;" id="name-error"> *</span>
                                <input type="text" class="form-control @error('account_number') is-invalid @enderror" name="account_number" id="account_number" placeholder="Account Number" required autocomplete="off" value="{{ $account->account_number }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Parent Account</label>
                                <span style="color: red;" id="name-error"> *</span>
                                <select class="form-control @error('chart_account_id') is-invalid @enderror" name="chart_account_id" id="chart_account_id" required>
                              		<option value="">--Select--</option>
                                  	@foreach($parents as $parent)
                                  		<option value="{{ $parent->id }}" {{ ($account->chart_account_id == $parent->id) ? 'selected' : '' }}>{{ $parent->title }}</option>
                                  	@endforeach
                              	</select>
                            </div>
                      	</div>
                        <div class="form-row mb-4">
                            <div class="form-group col-md-4">
                                <label>Opening Date</label>
                                <span style="color: red;" id="opening_date-error"> *</span>
                                <input type="text" class="form-control" name="opening_date" id="opening_date" required autocomplete="off" value="{{ \Carbon\Carbon::parse($account->opening_date)->format('d-m-Y H:i') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Bank Name</label>
                                <span style="color: red;" id="bank_name-error"> *</span>
                                <input type="text" class="form-control" name="bank_name" id="bank_name" placeholder="Bank Name" required autocomplete="off" value="{{ $account->bank_name }}">
                            </div>

                        </div>
                        <div class="form-row mb-4">

                            <div class="form-group col-md-12">
                                <label>Opening Balance</label>
                                <span style="color: red;" id="opening_balance-error"> *</span>
                                <input type="number" steps="any" class="form-control" name="opening_balance" id="opening_balance" placeholder="Opening Balance" required autocomplete="off" value="{{ $account->opening_balance }}">
                            </div>
                        </div>

                        <div class="form-row mb-4">
                            <div class="form-group col-md-12">
                                <label>Description</label>
                                <span style="color: red;" id="bank_name-error"> *</span>
                                <textarea class="form-control" name="description" id="description" rows="3">{{ $account->description }}</textarea>
                            </div>
                        </div>
                        <div class="form-row mb-4">
                            <div class="form-group col-md-12" id="loading-btn">
                                <button type="submit" class="btn btn-primary" id="submit-btn">Update Account</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')

    <script src="{{ URL::asset('plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ URL::asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
    <script>

        var f1 = flatpickr(document.getElementById('opening_date'), {

             enableTime: true,
             dateFormat: "d-m-Y H:i"
        });

	</script>
    <script>
        function loginLoadingBtn() {
            document.getElementById('loading-btn').innerHTML = '<button class="btn btn-primary disabled" style="width: auto !important;">Updating <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
            return true;
        }

        $('#username').blur(function () {
            var username = $('#username').val();
            if (username != '') {
                $.post('{{ url('/validate-username') }}',{_token:'{{ csrf_token() }}', username:username}, function(data){
                    if (data == '1') {
                        $('#username').css('border-color','#E91E63');
                        $('#username-error').html(' * Username taken');
                        $('#loading-btn').html('<button type="button" disabled class="btn btn-primary">Add User</button>');
                    } else {
                        $('#username').css('border-color','#009688');
                        $('#username-error').html(' *');
                        $('#loading-btn').html('<button type="submit" class="btn btn-primary">Add User</button>');
                    }
                });
            }
        });

    </script>

@endsection
