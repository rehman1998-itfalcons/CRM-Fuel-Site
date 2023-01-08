@extends('layouts.layout.main')
@section('title', 'Sync Data')
@section('css')
    <link href="{{ asset('assets/css/file-upload-with-preview.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .close {
            cursor: pointer;
            position: relative;
            top: 50%;
            transform: translate(0%, -50%);
            opacity: 1;
            float: right;
            color: red;
        }

        .file_input {
            padding: 8px;
        }

        .btn-danger {
            border-radius: 25px !important;
            box-shadow: 0px 5px 20px 0 rgba(0, 0, 0, 0.1);
        }

        .custom-file-container__image-preview {
            height: 160px !important;
        }

        i {
            color: #47B05A;
            /* background-color: #E7E9EB; */
            padding: 10px all;
        }
    </style>
    <link href="{{ URL::asset('plugins/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('plugins/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">

@endsection
@section('contents')

    <div class="container-fluid p-10">
        <div class="row justify-content-center">
            <div class="white_box mb_20">
                <div class="col-sm-12 ">
                    <div class="QA_section">
                        <div class="white_box_tittle list_header">
                            <h4>Sync Myob Data</h4>

                        </div>


                    </div>
                </div>


                <div class="container">
                    <div style="padding:0px; margin-top:30px; " class="row">
                        <div class="col-sm-6">

                            <form style="line-height: 2.5;">



                                <div class="form-group row">
                                    {{-- <div class="col-sm-11">
                                        <a href="#" id="scan-all" class="btn btn-primary">
                                            <i class="fas fa-sync all-spinner"></i>Sync All Data to Myob</a>
                                    </div> --}}
                                    {{-- <div class="col text left">
                                <button type="submit" class="btn btn-primary" id="ps_submit" onclick="event.preventDefault();f_scan(event)">Sync All to Myob</button>
                            </div> --}}
                                </div>
                            </form>
                        </div>
                        <textarea type="hidden" class="form-control text-dark textarea" name="customePort" value=" " id="ps_port"
                            onclick="clickCounter()" placeholder="21, 22, 80, 443" rows="4" required="" spellcheck="false"
                            data-ms-editor="true" hidden></textarea>
                        <div class="col-sm-6 ">
                            <div class="row">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-11">
                                    <p class="h5 "><b>List of Data</b></p>
                                </div>
                            </div>
                            <div style="justify-content: center;line-height: 2.5;;" class="row">
                                <div class="col-sm-5 mt-0">
                                    <ul class="list-unstyled">



                                        {{-- <li><a href="{{ route('product.sync') }}" class="scan-one" id="ps_submit"
                                                onclick="event.preventDefault();f_scan(event)"><i class="fas fa-sync" id="custom"
                                                    style="color:#47B05A"></i></a> Sync Items </li> --}}
                                        <li><a href="{{ route('product.sync') }}" class="scan-one" id="ps_submit"><i
                                                    class="fas fa-sync" id="custom" style="color:#47B05A"></i></a> Sync
                                            Items </li>


                                        <li><a href="{{ route('subcompany.sync') }}" class="scan-one" id="ps_submit"><i
                                                    class="fas fa-sync " id="custom"></i></a> Sync Customer</li>

                                                    <li><a href="{{ route('customer.coa.sync') }}" class="scan-one" id="ps_submit"><i
                                                        class="fas fa-sync " id="custom"></i></a> Sync Customer COA</li>
                                                        <li><a href="{{ route('purchase.invoices.sync') }}" class="scan-one" id="ps_submit"
                                                            onclick="event.preventDefault();f_scan(event)"><i class="fas fa-sync"
                                                                id="custom"></i></a> Sync Purchases</li>


                                    </ul>
                                </div>

                                <div class="col-sm-5">
                                    <ul class="list-unstyled">

                                        <li><a href="{{ route('sale.invoices.sync') }}" class="scan-one" id="ps_submit"
                                                onclick="event.preventDefault();f_scan(event)"><i class="fas fa-sync"
                                                    id="custom"></i></a> Sync Sales</li>
                                                    <li><a href="{{ route('supplier.sync') }}" class="scan-one" id="ps_submit"><i
                                                    class="fas fa-sync" id="custom"></i></a> Sync Supplier</li>

                                                    <li><a href="{{ route('supplier.coa.sync') }}" class="scan-one" id="ps_submit"><i
                                                        class="fas fa-sync" id="custom"></i></a> Sync Supplier COA</li>


                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <table class="table">
                            <thead id="bodyhead">

                            </thead>
                            <tbody id="here">
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script type="text/javascript">
        //for single spinner
        $(".fas").on('click', function() {
            $(this).replaceWith('<i class="fa fa-spinner fa-spin fa-fw"></i>');
        });


        // for all
        // $(".all-spinner").on('click', function() {
        //     // alert();
        //     $(this).find("#custom").replaceWith('<i class="fa fa-spinner fa-spin fa-fw"></i>');
        // });
    </script>



    <script src="{{ URL::asset('plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ URL::asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
    <script src="{{ asset('assets/js/file-upload-with-preview.min.js') }}"></script>
    <script src="{{ asset('loadingoverlay.min.js') }}"></script>

@endsection
