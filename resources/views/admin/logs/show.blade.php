@extends('layouts.layout.main')
@section('title','Logs Activity')
@section('css')

    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/dt-global_style.css') }}">
    <style>
        .badge {
            cursor: pointer;
        }
		.table > thead > tr > th {
		    white-space: nowrap;
    		font-weight: 700 !important;
        }
    </style>

@endsection
@section('contents')

<div class="container-fluid p-10">
    <div class="row justify-content-center">
        <div class="white_box mb_20">
        <div class="col-sm-12 ">
            <div class="QA_section">
                <div class="white_box_tittle list_header">
                    <h4>Logs Activity</h4>
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
                        <div class="add_button ms-2">
                            <a href="{{ route('users.index') }}" class="btn btn-md btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left close-message"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                 Back
                            </a>
                        </div>
                    </div>
                </div>
                <hr>
                {{-- <div class="table-responsive mb-4 mt-4"> --}}
                    <div class="QA_table mb_30">
                    <div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                        <table id="zero-config" class="table table-hover dataTable" style="width:100%" role="grid"
                               aria-describedby="zero-config_info">
                            <thead>
                            <tr role="row">
                                <th class="sorting">#</th>
                                <th class="sorting">User</th>
                                <th class="sorting">IP Address</th>
                                <th class="sorting">Browser Agent</th>
                                <th class="sorting">Location</th>
                                <th class="sorting">Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                            @foreach ($activity_logs as $log)
                                <tr role="row">
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $log->ip_address }}</td>
                                    <td>{{ $log->browser_agent }}</td>
                                  	<td>
                                  	    {{ $log->city_name .', '. $log->region_name }} <br>
                                  	    {{ $log->country_name }}, {{ $log->zip_code }}.
                                  	</td>
                                    <td>{{ $log->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection
@section('scripts')

    <script src="{{ URL::asset('plugins/table/datatable/datatables.js') }}"></script>
    <script>
        $('#zero-config').DataTable({
            "oLanguage": {
                "oPaginate": {
                    "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
                "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [10, 20, 30, 50],
            "pageLength": 10
        });

        $('[data-bs-toggle="tooltip"]').tooltip()
    </script>

@endsection
