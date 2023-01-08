<style>
    .icon-button__badge {
        position: absolute;
        top: -12px;
        right: -10px;
        width: 27px;
        height: 27px;
        background: red;
        color: #ffffff;
        display: flex;
        font-size: 10px;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
    }

    .scrollable-menu {
        height: auto;
        max-height: 200px;
        overflow-x: hidden;
    }
</style>

<div class="container-fluid g-0">
    <div class="row">
        <div class="col-lg-12 p-0">
            <div class="header_iner d-flex justify-content-between align-items-center">
                <div class="sidebar_icon d-lg-none">
                    <i class="ti-menu"></i>
                </div>
                <div class="serach_field-area">

                </div>
                @php
                    $notifications = \App\Notification::orderBy('created_at', 'desc')->get();
                @endphp
                <div class="header_right d-flex justify-content-between align-items-center">
                    <div class="header_notification_warp d-flex align-items-center">

                        <li>
                            <a href="{{ route('trash') }}"  id="trash_btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                    </path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg> <span
                                    class="icon-button__badge">{{ \DB::table('records')->where('deleted_status', 1)->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item dropdown notification-dropdown">
                            <a href="javascript:void(0);" id="notificationDropdown"
                                data-bs-toggle="dropdown"aria-haspopup="true" aria-expanded="true">

                                <img src="{{ URL::asset('admin/img/icon/bell.svg') }}" alt="">
                                <sup class="icon-button__badge"
                                    id="new-notification-count">{{ $notifications->count() }}</sup>
                            </a>
                            <div class="dropdown-menu position-absolute animated fadeInUp scrollable-menu "
                                id="notification-area" aria-labelledby="notificationDropdown">
                                <div class="notification-scroll">
                                    @forelse ($notifications->slice(0, 100) as $notification)
                                        <a href="{{ $notification->url }}" class="dropdown-item">
                                            <div class="media file-upload">
                                                <div class="media-body">
                                                    <div class="data-info">
                                                        <h6 class="">{{ $notification->user->name }}</h6>
                                                        <p class="">{{ $notification->comment }}</p>
                                                        <small class="text-primary"
                                                            style="float: right; font-size: 11px;">{{ $notification->created_at->diffForHumans() }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <p style="text-align: center; margin-top: 10%;">No New Notifications</p>
                                    @endforelse
                                </div>
                            </div>
                        </li>

                    </div>
                    <div class="profile_info">
                        <img src="{{ URL::asset('admin/img/client_img.png') }}" alt="#">
                        <div class="profile_info_iner">

                            <div class="profile_info_details">
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">Log
                                    Out <i class="ti-shift-left"></i></a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
