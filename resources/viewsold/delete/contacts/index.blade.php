
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

<div class="container-fluid p-10">
    @if($errors->any())
    <ul class="alert alert-warning"
        style="background: #eb5a46; color: #fff; font-weight: 300; line-height: 1.7; font-size: 16px; list-style-type: circle;">
        {!! implode('', $errors->all('<li>:message</li>')) !!}
    </ul>
@endif
    <div class="row justify-content-center">
        <div class="white_box mb_20">
        <div class="col-sm-12 ">
            <div class="QA_section">
                <div class="white_box_tittle list_header">
                    <h4>Delete</h4>
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
                            <a href="{{ route('delete.contacts') }}" data-bs-toggle="modal" data-bs-target="#addProduct"
                                class="btn btn-md btn-primary">Delete Contacts</a>
                        </div>
                        <div class="add_button ms-2">
                            <a href="{{ route('delete.personal') }}" data-bs-toggle="modal" data-bs-target="#addProduct"
                                class="btn btn-md btn-primary">Delete Personal</a>
                        </div>
                        <div class="add_button ms-2">
                            <a href="{{ route('delete.employee') }}" data-bs-toggle="modal" data-bs-target="#addProduct"
                                class="btn btn-md btn-primary">Delete Employees</a>
                        </div>
                        <div class="add_button ms-2">
                            <a href="{{ route('delete.items') }}" data-bs-toggle="modal" data-bs-target="#addProduct"
                                class="btn btn-md btn-primary">Delete Items</a>
                        </div>
                        <div class="add_button ms-2">
                            <a href="{{ route('delete.coa') }}" data-bs-toggle="modal" data-bs-target="#addProduct"
                                class="btn btn-md btn-primary">Delete COA</a>
                        </div>
                        <div class="add_button ms-2">
                            <a href="{{ route('delete.supplier') }}" data-bs-toggle="modal" data-bs-target="#addProduct"
                                class="btn btn-md btn-primary">Delete Supplier</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
</div>










@endsection
