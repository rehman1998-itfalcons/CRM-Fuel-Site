@extends('layouts.layout.main')
@section('title','Add Record')
@section('css')
 <link href="{{asset('assets/css/file-upload-with-preview.min.css')}}" rel="stylesheet" type="text/css" />
	<style>
       .close {
                cursor: pointer;
                position: relative;
                top: 50%;
                transform: translate(0%, -50%);
                opacity: 1;
                float:right;
                color:red;
            }

      .file_input{
        padding: 8px;
      }
      .btn-danger {
	  	border-radius: 25px !important;
        box-shadow: 0px 5px 20px 0 rgba(0, 0, 0, 0.1);
      }
      .custom-file-container__image-preview {
       height: 160px !important;
      }
	</style>
  <link href="{{ URL::asset('plugins/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ URL::asset('plugins/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
  <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"  />-->
  <!--  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">-->
  <!--  <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>-->
@endsection
@section('contents')

<div class="container-fluid p-10">
    <div class="row justify-content-center">
        <div class="white_box mb_20">
        <div class="col-sm-12 ">
            <div class="QA_section">
                <div class="white_box_tittle list_header">
                    <h4>Myob Settings</h4>

                </div>


                <table style="width:100%;margin-right:0px; margin-left:auto;">
            <thead>

            </thead>

                  <tbody>
               @foreach ($myobsettings as $myob)
                  <tr>
                     <td style="text-align:right">
                         <div class="form-check form-switch">
                          <input class="form-check-input" name="toggle" type="checkbox" role="switch" id="flexSwitchCheckDefault" @if ($myob->status == 1) checked  @else unchecked @endif>
                          <label class="form-check-label" for="flexSwitchCheckDefault">Myob Setting</label>
                        </div>

                     </td>
                  </tr>


            </tbody>
        </table>




                <div class="widget-content widget-content-area">
                    <form action="{{ route('update.myob.settings') }}" method="POST" onsubmit="return loginLoadingBtn(this)" enctype="multipart/form-data">
                        @csrf


                        <input type="hidden" name="id" value="{{$myob->status}}">
                          <div class="form-row mb-4">
                            <div class="col-md-6">
                                <label>Myob GUID</label>
                                <small style="color: red;"> *</small>
                                <input type="text" name="api_key" class="form-control @error('api_key') is-invalid @enderror" required value="{{$myob->api_key }}">
                                @error('api_key')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label>Secret Key</label>
                                <small style="color: red;"> *</small>
                                <input type="text" name="secret_key" class="form-control @error('secret_key') is-invalid @enderror" required value="{{$myob->secret_key }}">
                                @error('secret_key')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                          </div>



                      <div class="form-row mb-4">
                             <div class="col-md-6">
                                <label>Myob Callback URI</label>
                                <small style="color: red;"> *</small>
                                <input type="text" name="callback" class="form-control @error('callback') is-invalid @enderror" required value="{{$myob->callback }}">
                                @error('callback')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                          </div>
                    <div class="form-row mb-4">
                            <div class="form-group col-md-12" id="loading-btn">
                                <button type="submit" class="btn btn-primary btn-md mr-3" id="submit-btn">
                                    UPDATE SETTINGS
                                </button>
                            </div>
                        </div>




                    </form>
                </div>
            @endforeach
            </div>
        </div>
    </div>
    </div>
</div>


@endsection
@section('scripts')


	<script>

     $('input[name=toggle]').change(function(){
         //alert('checked');
    var mode= $(this).prop('checked');

    var id=$( this ).val();
    console.log(mode);
    console.log(id);

        var myobObj = {};
        myobObj.mode = $(this).prop('checked');
        myobObj.myob_id = $( this ).val();
        myobObj._token = '{{csrf_token()}}';

    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{ url('/updateMyobStatus') }}",
      data:myobObj,
      success:function(data)
      {
      }
    });
  });

	</script>
    <script src="{{ URL::asset('plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ URL::asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
    <script src="{{asset('assets/js/file-upload-with-preview.min.js')}}"></script>
    <script src="{{ asset('loadingoverlay.min.js') }}"></script>

@endsection
