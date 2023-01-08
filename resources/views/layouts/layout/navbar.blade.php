{{-- <nav class="sidebar">
    <div class="logo d-flex justify-content-between">
    <a href="index_3997808.html"><img src="img/logo.png" alt=""></a>
    <div class="sidebar_close_icon d-lg-none">
    <i class="ti-close"></i>
    </div>
    </div>
    <ul id="sidebar_menu">
    <li class="side_menu_title">
    <span>Dashboard</span>
    </li>
    <li class="">
    <a class="has-arrow" href="#" aria-expanded="false">

    <img src="img/menu-icon/1.svg" alt="">
    <span>Dashboard</span>
    </a>
    <ul>
    <li><a class="active" href="index_3997808.html">Dashboard 1</a></li>
    <li><a href="index_2.html">Dashboard 2</a></li>
    </ul>
    </li>
    <li class="side_menu_title">
    <span>Applications</span>
    </li>
    <li class="">
    <a class="has-arrow" href="#" aria-expanded="false">
    <img src="img/menu-icon/2.svg" alt="">
    <span>Pages</span>
    </a>
    <ul>
    <li><a href="login.html">Login</a></li>
    <li><a href="resister.html">Register</a></li>
    <li><a href="forgot_pass.html">Forgot Password</a></li>
    </ul>
    </li>
    <li class="">
    <a class="has-arrow" href="#" aria-expanded="false">
    <img src="img/menu-icon/3.svg" alt="">
    <span>Applications</span>
    </a>
    <ul>
    <li><a href="mail_box.html">Mail Box</a></li>
    <li><a href="chat.html">Chat</a></li>
    <li><a href="faq.html">FAQ</a></li>
    </ul>
    </li>
    <li class="side_menu_title">
    <span>Components</span>
    </li>
    <li class="">
    <a class="has-arrow" href="#" aria-expanded="false">
    <img src="img/menu-icon/4.svg" alt="">
     <span>UI Component</span>
    </a>
    <ul>
    <li><a href="#">Elements</a>
    <ul>
    <li><a href="buttons.html">Buttons</a></li>
    <li><a href="dropdown.html">Dropdowns</a></li>
    <li><a href="Badges_1835033.html">Badges</a></li>
    <li><a href="Loading_Indicators_2687025.html">Loading Indicators</a></li>
    </ul>
    </li>
    <li><a href="#">Components</a>
    <ul>
    <li><a href="notification.html">Notifications</a></li>
    <li><a href="progress.html">Progress Bar</a></li>
    <li><a href="carousel.html">Carousel</a></li>
    <li><a href="cards.html">cards</a></li>
    <li><a href="Pagination_458776.html">Pagination</a></li>
    </ul>
    </li>
    </ul>
    </li>
    <li class="">
    <a class="has-arrow" href="#" aria-expanded="false">
    <img src="img/menu-icon/5.svg" alt="">
    <span>Widgets</span>
    </a>
    <ul>
    <li><a href="chart_box_1.html">Chart Boxes 1</a></li>
    <li><a href="profilebox.html">Profile Box</a></li>
    </ul>
    </li>
    <li class="">
    <a class="has-arrow" href="#" aria-expanded="false">
    <img src="img/menu-icon/6.svg" alt="">
    <span>Forms</span>
    </a>
    <ul>
    <li><a href="#">Elements</a>
    <ul>
    <li><a href="data_table.html">Data Tables</a></li>
    <li><a href="bootstrap_table.html">Grid Tables</a></li>
    <li><a href="datepicker.html">Date Picker</a></li>
    </ul>
    </li>
    <li><a href="#">Widgets</a>
    <ul>
    <li><a href="Input_Selects_393319.html">Input Selects</a></li>
    <li><a href="Input_Mask_2555945.html">Input Mask</a></li>
    </ul>
    </li>
    </ul>
    </li>
    <li class="mm-active">
    <a class="has-arrow" href="#" aria-expanded="false">
    <img src="img/menu-icon/7.svg" alt="">
    <span>Charts</span>
    </a>
    <ul>
    <li><a href="chartjs.html">ChartJS</a></li>
    <li><a class="active" href="apex_chart.html">Apex Charts</a></li>
    <li><a href="chart_sparkline.html">Chart sparkline</a></li>
    </ul>
    </li>
    </ul>
    </nav> --}}


    <nav class="sidebar">
        <div class="logo d-flex justify-content-between">
            <span class="navbar-brand-name" style="font-weight: bolder;"><a href="{{ url('/dashboard') }}"><img src="{{URL::asset('assets/img/90x90-icon.png')}}" alt=""></a></span>

            <div class="sidebar_close_icon d-lg-none">
                <i class="ti-close"></i>
            </div>
        </div>
        <ul id="sidebar_menu">
            <li class="side_menu_title">
                <li>
                    <a href="{{ url('home') }}" aria-expanded="false">

                        <img src="{{URL::asset('admin/img/menu-icon/1.svg')}}" alt="">
                        <span>Dashboard</span>

                    </a>
                {{-- <ul>
                    <li><a class="active" href="{{ url('home') }}">Dashboard </a></li>


                </ul> --}}
            </li>
            <li>
                <a class="has-arrow" href="#" aria-expanded="false">
                <!-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg> -->
                <i class="fa fa-clock-o" style="width:24px;height: 24px;" aria-hidden="true"></i>
                    <span>Pending</span>
                </a>
                <?php

                use App\Record;
                    //for Recent Activity
                    $records = Record::where('supervisor_status', 0)
                        ->where('deleted_status', 0)
                        ->get();
                    $accounts = Record::where('supervisor_status', 1)
                        ->where('status', 0)
                        ->where('deleted_status', 0)
                        ->get();
                    $sal = Record::where('deleted_status', 1)->get();

                    ?>

                <ul>
                    <li><a class="active" href="{{ url('supervisor/dashboard') }}">Supervisor
                        <span class="position-absolute  start-50  badge rounded-pill bg-danger"  style="    margin-left: -25px;">
                            {{ $records->count() }}
                            <span class="visually-hidden">unread messages</span>
                          </span>
                         </a>

                    </li>
                    <li><a class="active" href="{{ url('accountant/dashboard') }}">Accountant
                        <span class="position-absolute start-50   badge rounded-pill bg-danger"  style="    margin-left: -25px;">
                            {{ $accounts->count() }}
                        <span class="visually-hidden">unread messages</span>
                      </span>  </a>
                    </li>

                </ul>
            </li>
            <li>
                <a class="has-arrow" href="#" aria-expanded="false">

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                    <span>Sales</span>
                </a>
                <ul>
                    <li><a class="active" href="{{ url('records/create') }}"> Add sales </a></li>
                    <li><a class="active" href="{{ url('records') }}"> View Sales </a></li>

                </ul>
            </li>
            <li>
                <a class="has-arrow" href="#" aria-expanded="false">

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    <span>Purchase</span>
                </a>
                <ul>

                    <li><a class="active" href="{{ url('add/purchase') }}">Add Purchase Order </a></li>
                    <li><a class="active" href="{{ url('purchases') }}">View Purchase Order </a></li>

                </ul>
            </li>
            <li>
                <a class="has-arrow" href="#" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-bar-chart" viewBox="0 0 16 16">
                        <path d="M4 11H2v3h2v-3zm5-4H7v7h2V7zm5-5v12h-2V2h2zm-2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1h-2zM6 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm-5 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3z"/>
                      </svg>
                {{-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="dice-4-fill"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> --}}
                    <span>Mass Match</span>
                </a>
                <ul>
                    {{-- <li><a class="active" href="#">Mass Match </a></li>
                    <li><a class="active" href="#">View Mass Match </a></li> --}}
                    <li><a class="active" href="{{ url('mass-match') }}">Mass Match </a></li>
                    <li><a class="active" href="{{ url('mass-match/all') }}">View Mass Match </a></li>


                </ul>
            </li>
            <li>
                <a class="has-arrow" href="#" aria-expanded="false">

                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-cast"><path d="M2 16.1A5 5 0 0 1 5.9 20M2 12.05A9 9 0 0 1 9.95 20M2 8V6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-6"/><line x1="2" y1="20" x2="2.01" y2="20"/></svg>
                    <span>Sale Invoices
                    </span>
                </a>
                <ul>
                    <li><a class="active" href="{{ url('all-invoices') }}">All Invoices </a></li>
                    <li><a class="active" href="{{ url('paid-invoices') }}">Paid Invoices </a></li>
                    <li><a class="active" href="{{ url('unpaid-invoices') }}">Unpaid Invoices </a></li>
                    <li><a class="active" href="{{ url('overdue-invoices') }}">Overdue Invoices </a></li>

                </ul>
            </li>
            <li>
                <a class="has-arrow" href="#" aria-expanded="false">

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-archive"><polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line></svg>
                    <span>Purchase Invoice</span>
                </a>
                <ul>
                    <li><a class="active" href="{{ url('purchase-all-invoices') }}">All Invoices </a></li>
                    <li><a class="active" href="{{ url('purchase-paid-invoices') }}">Paid Invoices </a></li>
                    <li><a class="active" href="{{ url('purchase-unpaid-invoices') }}">Unpaid Invoices </a></li>

                </ul>
            </li>
            <li>
                <a class="has-arrow" href="#" aria-expanded="false">

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    <span>Reports</span>
                </a>
                <ul>
                    <li><a class="active" href="{{ url('purchases-vs-sales') }}"> Purchase Vs Sales </a></li>
                    <li><a class="active" href="{{ url('manual-purchases-vs-sales') }}">Manual Purchase Vs Sales </a></li>
                    <li><a class="active" href="{{ url('purchases-vs-sales-company') }}">Purchase Vs Sales Company </a>
                    </li>
                    <li><a class="active" href="{{ url('client-statement') }}">Client Statment </a></li>
                    <li><a class="active" href="{{ url('account-statement') }}">Client Statment for Bank </a></li>
                    <li><a class="active" href="{{ url('purchase-statement') }}"> Purchase Statement </a></li>
                    <li><a class="active" href="{{ url('supplier-companies-report') }}">Supplier Report</a></li>
                    <li><a class="active" href="{{ url('income-report') }}"> Sales Report</a></li>
                    <li><a class="active" href="{{ url('purchase-report') }}"> Purchase Report </a></li>
                    <li><a class="active" href="{{ url('accounting-summary-report') }}"> Accountant Summary</a></li>
                    <li><a class="active" href="{{ url('client-detail-report') }}"> Client Detail Report</a></li>
                    <li><a class="active" href="{{ url('company-detail-report') }}"> Company Detail Report </a></li>
                    <li><a class="active" href="{{ url('delivery-detail-view') }}">Delivery Detail Report</a></li>
                    <li><a class="active" href="{{ url('income-statistics-report') }}"> Income Statistics Report </a></li>
                    <li><a class="active" href="{{ url('deliveries-summary') }}"> Deliveries Summary </a></li>

                </ul>
            </li>


            <li>
                <a class="has-arrow" href="#" aria-expanded="false">

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    <span>Myob Reports</span>
                </a>
                <ul>
                    <li><a class="active" href="#"> Sales Report </a></li>
                    <li><a class="active" href="#"> Purchases Report </a></li>



                </ul>
            </li>
            {{-- <li>
                <a class="has-arrow" href="#" aria-expanded="false">

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-down"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg>
                    <span>Expense</span>
                </a>
                <ul>
                    <li><a class="active" href="{{ url('expenses-create') }}"> Add Expenses </a></li>
                    <li><a class="active" href="{{ url('expenses') }}">View Expense </a></li>


                </ul>
            </li>
            <li>
                <a class="has-arrow" href="#" aria-expanded="false">

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-cpu"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line></svg>
                    <span>COA</span>
                </a>
                <ul>
                <li><a class="active" href="{{ url('manage-chart-accounts') }}">Manage Chart Of Accounts </a></li>
                <li><a class="active" href="{{ url('manage-subAccounts') }}">Manage Sub Accounts </a></li>
                <li><a class="active" href="{{ url('add-account') }}">Add New Bank Account </a></li>
                <li><a class="active" href="{{ url('manage-account') }}">Manage Bank Account </a></li>
                <li><a class="active" href="{{ url('transactions') }}">Manage Transactions </a></li>

                </ul>
            </li> --}}
            <li>
                <a class="has-arrow" href="#" aria-expanded="false">

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>Users</span>
                </a>
                <ul>
                    {{-- <li><a class="active" href="{{ url('admin/dashboard') }}">Dashboard </a></li> --}}
                    <li class="">
                        <a href="/roles">Roles</a>
                    </li>
                    <li class="">
                        <a href="/users">Users</a>
                    </li>
                    <li class="">
                        <a href="/logs">Logs Activity</a>
                    </li>

                </ul>
            </li>
            <li>
                <a class="has-arrow" href="#" aria-expanded="false">

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    <span>Setting</span>
                </a>
                <ul>
                    {{-- <li><a class="active" href="{{ url('admin/dashboard') }}">Dashboard </a></li> --}}
                    <li class="">
                        <a href="/categories">Categories</a>
                    </li>
                    <li class="">
                        <a href="/companies">Companies</a>
                    </li>
                    <li class="">
                        <a href="/supplier-companies">Supplier Companies</a>
                    </li>
                    <li class="">
                        <a href="/products">Products</a>
                    </li>
                    <li class="">
                        <a href="/smtp-settings">SMTP Settings</a>
                    </li>
                    <li class="">
                        <a href="/invoice-settings">Invoice Settings</a>
                    </li>
                    <!-- <li class="">-->
                    <!--    <a href="/myob-setting">Myob Settings</a>-->
                    <!--</li>-->
                    <!--<li class="">-->
                    <!--    <a href="/sync-to-myob">Sync to Myob </a>-->
                    <!--</li>-->
                    {{-- <li class="">
                        <a href="/delete-data">Delete Data</a>
                    </li> --}}

                </ul>
            </li>
            <li>

                <a class="has-arrow" href="#" aria-expanded="false">

                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-cpu"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line></svg>

                    <span>Myob</span>
                </a>
                <ul>
                    <li><a class="active" href="{{ url('admin/dashboard') }}">.Sync Sales Record
                        </a></li>
                    <li><a class="active" href="{{ url('admin/dashboard') }}">.Sync Purchase Record

                        </a></li>
                    <li><a class="active" href="{{ url('admin/dashboard') }}">.Sync Customers
                        </a></li>

                </ul>
            </li>
            </li>
            {{-- <li class="side_menu_title">
    <span>Applications</span>
    </li> --}}

            <!-- <li class="">
    <a class="has-arrow" href="#" aria-expanded="false">
    <img src="img/menu-icon/3.svg" alt="">
    <span>Applications</span>
    </a>
    <ul>
    <li><a href="mail_box.html">Mail Box</a></li>
    <li><a href="chat.html">Chat</a></li>
    <li><a href="faq.html">FAQ</a></li>
    </ul>
    </li>  -->
            <!-- <li class="side_menu_title">
    <span>Components</span>
    </li>  -->
            <!-- <li class="">
    <a class="has-arrow" href="#" aria-expanded="false">
    <img src="img/menu-icon/4.svg" alt="">
    <span>UI Component</span>
    </a>
    <ul>
    <li><a href="#">Elements</a>
    <ul>
    <li><a href="buttons.html">Buttons</a></li>
    <li><a href="dropdown.html">Dropdowns</a></li>
    <li><a href="Badges_1835033.html">Badges</a></li>
    <li><a href="Loading_Indicators_2687025.html">Loading Indicators</a></li>
    </ul>
    </li>
    <li><a href="#">Components</a>
    <ul>
    <li><a href="notification.html">Notifications</a></li>
    <li><a href="progress.html">Progress Bar</a></li>
    <li><a href="carousel.html">Carousel</a></li>
    <li><a href="cards.html">cards</a></li>
    <li><a href="Pagination_458776.html">Pagination</a></li>
    </ul>
    </li>
    </ul>
    </li>  -->
            {{-- <li class="">
    <a class="has-arrow" href="#" aria-expanded="false">
    <img src="img/menu-icon/5.svg" alt="">
    <span>Widgets</span>
    </a>
    <ul>
    <li><a href="{{ url('admin/chartbox')}}">Chart Boxes </a></li>
    <!-- <li><a href="profilebox.html">Profile Box</a></li> -->
    </ul>
    </li> --}}
            {{-- <li class="">
                <a class="has-arrow" href="#" aria-expanded="false">
                    <img src="img/menu-icon/6.svg" alt="">
                    <span>Forms</span>
                </a>
                <ul>
                    <li><a href="#">Elements</a>
                        <ul>
                            <li><a href="{{ url('admin/datatable') }}">Data Tables</a></li>
                            <!-- <li><a href="bootstrap_table.html">Grid Tables</a></li> -->
                            <li><a href="{{ url('admin/datapick') }}">Date Picker</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Widgets</a>
                        <ul>
                            <!-- <li><a href="Input_Selects_393319.html">Input Selects</a></li>
    <li><a href="Input_Mask_2555945.html">Input Mask</a></li> -->
                        </ul>
                    </li>
                </ul>
            </li> --}}
            {{-- <li class="">
                <a class="has-arrow" href="#" aria-expanded="false">
                    <img src="img/menu-icon/7.svg" alt="">
                    <span>Charts</span>
                </a>
                <ul>
                    <li><a href="{{ url('admin/chartjs') }}">ChartJS</a></li>
                    <!-- <li><a href="apex_chart.html">Apex Charts</a></li>
    <li><a href="chart_sparkline.html">Chart sparkline</a></li> -->
                </ul>
            </li> --}}
        </ul>
    </nav>
