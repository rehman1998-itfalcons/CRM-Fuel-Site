
@extends('layouts.layout.main')
@section('title','Products')
@section('css')

    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/dt-global_style.css') }}">
    <style>
        .badge {
            cursor: pointer;
        }
		.table > thead > tr > th {
    		font-weight: 700 !important;
        }
        .color:hover{
             color: #2bbc4a;
        }
    </style>

@endsection
@section('contents')

{{-- <div class="container-fluid p-10">
    @if($errors->any())
    <ul class="alert alert-warning"
        style="background: #eb5a46; color: #fff; font-weight: 300; line-height: 1.7; font-size: 16px; list-style-type: circle;">
        {!! implode('', $errors->all('<li>:message</li>')) !!}
    </ul>
@endif --}}
@if (session('error'))
                <div class="alert alert-error">
                    <p style="background: #eb5a46; color: #fff; font-weight: 300; line-height: 1.7; font-size: 16px; list-style-type: circle;">{{ session('error') }}</p>
                </div>
 @endif
    <div class="row justify-content-center">
        <div class="white_box mb_20">
        <div class="col-sm-12 ">
            <div class="QA_section">
                <div class="white_box_tittle list_header">
                    <h4>Products</h4>
                    <div class="box_right d-flex lms_block">
                        {{-- <div class="serach_field_2">
                            <div class="search_inner">
                                <form Active="#">
                                    <div class="search_field">
                                        <input type="text" placeholder="Search content here...">
                                    </div>
                                    <button type="submit"> <i class="ti-search"></i> </button>
                                </form>
                            </div>
                        </div> --}}
                        <div class="add_button ms-2">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#addProduct"
                                class="btn btn-md btn-primary">Add Product</a>
                        </div>
                    </div>
                </div>
                <div class="QA_table mb_30">

                    <div class="table-responsive mb-4 mt-4">
                        <div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                            <table id="zero-config" class="table lms_table_active table-hover dataTable" style="width:100%" role="grid"
                                   aria-describedby="zero-config_info">
                                <thead>
                                <tr role="row">
                                    <th class="sorting">#</th>
                                    <th class="sorting">Product</th>
                                    <th class="sorting">Change Status</th>
                                    <th class="sorting">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1; ?>
                                @foreach ($products as $product)
                                    <tr role="row">
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $product->name }}</td>
                                      <td>
                                                  @if($product->status == 0)
                                                      <span class="badge badge-success" data-bs-toggle="modal" data-bs-target="#changeMainStatus" onclick="changeStatus('{{ $product->id }}','1')">Enable</span>
                                                  @else
                                                      <span class="badge badge-danger" data-bs-toggle="modal" data-bs-target="#changeMainStatus" onclick="changeStatus('{{ $product->id }}','0')">Disable</span>
                                                  @endif
                                                  </td>

                                        <td>
                                            <button data-bs-toggle='modal' data-bs-target='#editProduct' onclick="editProduct('{{ $product->id }}','{{ $product->name }}')" class="btn btn-sm btn-primary color btn-custom">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="25"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-settings  color">
                                                    <circle cx="12" cy="12" r="3" class="color"></circle>
                                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th class="sorting">#</th>
                                    <th class="sorting">Product</th>
                                    <th class="sorting">Change Status</th>
                                    <th class="sorting">Action</th>
                                </tr>
                                </tfoot>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
    <div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Product</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <form action="{{ route('products.store') }}" method="POST" onsubmit="return loginLoadingBtn(this)">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Product Name</label>
                            <small style="color: red;"> *</small>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Product Name" value="{{old('name')}}"
                                   required>
                            @error('name')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
                        </div>
                    </div>
                    <div class="modal-footer" id="loading-btn">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Product</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <form action="{{ route('products.update','role') }}" method="POST" onsubmit="return loginLoadingBtn1(this)">
                    @csrf
                  	@method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Product Name</label>
                            <small style="color: red;"> *</small>
                            <input type="text" name="name" id="product_name" class="form-control @error('name') is-invalid @enderror" required>
                           @error('name')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
                          	<input type="hidden" name="id" id="product_id">
                        </div>
                    </div>
                    <div class="modal-footer" id="loading-btn1">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



<div class="modal fade" id="changeMainStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Status</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
          	<form action="{{ url('products-change-status') }}" method="POST" onsubmit="return loginLoadingBtn3(this)">
              @csrf
              <input type="hidden" name="id" id="products_id" value="">
              <input type="hidden" name="status" id="status" value="">
              <div class="modal-body">
                <div class="form-group">
                  <p>Are you sure to change status to <strong id="status-current"></strong></p>
                </div>
              </div>
              <div class="modal-footer" id="loading-btn3">
                  <button type="submit" class="btn btn-primary">Change Status</button>
              </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('scripts')

    <script>
        function loginLoadingBtn() {
            document.getElementById('loading-btn').innerHTML = '<button class="btn btn-primary disabled" style="width: auto !important;">Creating <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
            return true;
        }

        function loginLoadingBtn1() {
            document.getElementById('loading-btn1').innerHTML = '<button class="btn btn-primary disabled" style="width: auto !important;">Updating <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
            return true;
        }

       function loginLoadingBtn3() {
          document.getElementById('loading-btn3').innerHTML = '<button class="btn btn-primary disabled" style="width: auto !important;">Changing Status <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
          return true;
      }

        function editProduct(id,name) {
            $('#product_id').val(id);
            $('#product_name').val(name);
        }

	</script>
    <script src="{{ URL::asset('plugins/table/datatable/datatables.js') }}"></script>
    <script>
        $('#zero-config').DataTable({
            "oLanguage": {
                "oPaginate": {
                    "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                },
                "sInfo": "Showing page _PAGE_ of _PAGES_",

                "sSearchPlaceholder": "",
                "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [10, 20, 30, 50],
            "pageLength": 10
        });
    </script>

<script>
 function changeStatus(id,status)
      {
        $('#products_id').val(id);
        $('#status').val(status);
        if (status == 1)
          $('#status-current').html('Enable?');
        else
          $('#status-current').html('Disable?');
      }
</script>

@endsection
