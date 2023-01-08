@extends('layouts.template')
@section('title','Chart Of Account Types')
@section('contents')

	<style>
		.btn-cust {
          padding: 2px 6px !important;
          font-size: 12px !important;
          box-shadow: 0px 5px 20px 0 rgba(0, 0, 0, 0.2) !important;
        }
        .table > tbody:before {
            content: "" !important;
        }
		.table > thead > tr > th {
    		font-weight: 700 !important;
        }      
	</style>

	<div class="row layout-top-spacing" id="cancel-row">
      <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
      	<div class="widget-content widget-content-area br-6" >
           <h4>Chart Of Account Types</h4>
          <div class="table-responsive mb-4 mt-4">
            <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Title</th>
                </tr>
              </thead>
              <tbody>
                <?php $sno = 1; ?>
                @foreach ($chartaccounttypes as $account)
                  <tr>
                    <td>{{ $sno++ }}</td>
                    <td>{{$account->title}}</td>
                   </tr>
                 @endforeach
               </tbody>
            </table>
          </div>
        </div>
      </div>
	</div>

@endsection