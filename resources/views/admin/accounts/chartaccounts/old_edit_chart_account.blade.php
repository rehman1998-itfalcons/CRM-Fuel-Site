@extends('layouts.template')
@section('title','Edit Chart Account')
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

@endsection
@section('contents')

    <div class="row layout-top-spacing" id="cancel-row">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content widget-content-area br-6">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>
                                Edit Chart Of Account
                                <a href="{{ route('manage.chart.accounts') }}" class="btn btn-md btn-primary" style="float: right;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left close-message"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                    Back
                                </a>
                            </h4>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="">
                    <form action="{{ route('update.chart.account',$chart_account->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return loginLoadingBtn(this)">
                        @csrf
                        <div class="form-row mb-4">
                            <div class="form-group col-md-6">
                                <label>Title</label>
                                <span style="color: red;"> *</span>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" placeholder="enter title" required  value="{{ $chart_account->title }}">
                                @error('title')
                                <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label>Code</label>
                                <span style="color: red;" id="name-error"> *</span>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" placeholder="enter account code" required  value="{{ $chart_account->code }}">
                                @error('code')
                                <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
                                @enderror
                            </div>
                      </div>
                        <div class="form-row mb-4">
                            <div class="form-group col-md-6">
                                <label>Account Type</label>
                                <span style="color: red;" id=""> *</span>
                              <select class="form-control @error('name') is-invalid @enderror" name="account_type" required>
                                @foreach($chartof_account_types as $account_type)
                                <option value="{{$account_type->id}}" {{($chart_account->account_type == $account_type->id) ? 'selected' : ''}}>{{$account_type->title}}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                        <div class="form-row mb-4">
                            <div class="form-group col-md-12">
                                <label>Description</label>
                                <span style="color: red;" id="bank_name-error"> *</span>
                                <textarea class="form-control" name="description" id="description" rows="5">{{$chart_account->description}}</textarea>
                            </div>
                        </div>
                        <div class="form-row mb-4">
                            <div class="form-group col-md-12" id="loading-btn">
                                <button type="submit" class="btn btn-primary btn-lg mr-3" id="submit-btn">Update Chart Account</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')

    <script>
        function loginLoadingBtn() {
            document.getElementById('loading-btn').innerHTML = '<button class="btn btn-primary btn-lg mr-3 disabled" style="width: auto !important;">Please wait <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
            return true;
        }

    </script>

@endsection