<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/png" href="/images/icon.png"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/datepicker.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2@8.js') }}"></script>
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>

    <!-- Fonts -->

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">

    <!-- Styles -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css')}}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">

    <link href="{{ asset('css/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('css/buttons.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/colReorder.dataTables.min.css') }}" rel="stylesheet">
</head>
<body>
<div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
    <div class="app-header header-shadow">
        <div class="app-header__logo">
            <div class="logo-src"></div>
            <div class="header__pane ml-auto">
                <div>
                    <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                            data-class="closed-sidebar">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="app-header__mobile-menu">
            <div>
                <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
        <div class="app-header__menu">
            <span>
                <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                    <span class="btn-icon-wrapper">
                        <i class="fa fa-ellipsis-v fa-w-6"></i>
                    </span>
                </button>
            </span>
        </div>
        <div class="app-header__content">
            <div class="app-header-left">
                <ul class="header-megamenu nav">
                    <li class="dropdown nav-item">
                        <a aria-haspopup="true" data-toggle="dropdown" class="nav-link" aria-expanded="false">
                            <span class="badge badge-pill badge-danger ml-0 mr-2">
                                {{-- {{ $leaveRequests['waitingForSPVApproval2'] + $leaveRequests['waitingForHRMApproval2'] + $leaveRequests['approvedWaitingPayment2']}} --}}
                            </span>
                            Leave Requests
                            <i class="fa fa-angle-down ml-2 opacity-5"></i>
                        </a>
                        <div tabindex="-1" role="menu" aria-hidden="true"
                             class="dropdown-menu-rounded dropdown-menu-lg rm-pointers dropdown-menu">
                            <div class="dropdown-menu-header">
                                <div class="dropdown-menu-header-inner bg-success">
                                    <div class="menu-header-image opacity-0"
                                         style="background-image: url('/images/dropdown-header/city2.jpg');"></div>
                                    <div class="menu-header-content text-left">
                                        <h5 class="menu-header-title">Leave Requests</h5>
                                        <h6 class="menu-header-subtitle">Summary Of Requests To Be Processed</h6>
                                    </div>
                                </div>
                            </div>
                            <button type="button" tabindex="0" class="dropdown-item">
                                <span class="badge badge-pill badge-danger ml-0 mr-2">
                                    {{-- {{ $leaveRequests['waitingForSPVApproval2'] }} --}}
                                </span>  Waiting SPV Approval
                            </button>
                            <button type="button" tabindex="0" class="dropdown-item">
                                <span class="badge badge-pill badge-danger ml-0 mr-2">
                                    {{-- {{ $leaveRequests['waitingForHRMApproval2'] }} --}}
                                </span>  Waiting HRM Approval
                            </button>
                            <button type="button" tabindex="0" class="dropdown-item">
                                <span class="badge badge-pill badge-danger ml-0 mr-2">
                                    {{-- {{ $leaveRequests['approvedWaitingPayment2'] }} --}}
                                </span>  Waiting For Payment
                            </button>
                            <div tabindex="-1" class="dropdown-divider"></div>
                            <button type="button" tabindex="0" class="dropdown-item">
                                <span class="badge badge-pill badge-danger ml-0 mr-2">
                                    {{-- {{ $leaveRequests['approved2'] }} --}}
                                </span>  Approved
                            </button>
                            <button type="button" tabindex="0" class="dropdown-item">
                                <span class="badge badge-pill badge-danger ml-0 mr-2">
                                    {{-- {{ $leaveRequests['rejected2'] }} --}}
                                </span>  Rejected
                            </button>
                        </div>
                    </li>
                    <li class="dropdown nav-item">
                        <a aria-haspopup="true" data-toggle="dropdown" class="nav-link" aria-expanded="false">
                            <span class="badge badge-pill badge-danger ml-0 mr-2">
                                {{-- {{ $timeSheets['waitingForSPVApproval2'] + $timeSheets['waitingForHRMApproval2'] }} --}}
                            </span>
                            Time Sheets
                            <i class="fa fa-angle-down ml-2 opacity-5"></i>
                        </a>
                        <div tabindex="-1" role="menu" aria-hidden="true"
                             class="dropdown-menu-rounded dropdown-menu-lg rm-pointers dropdown-menu">
                            <div class="dropdown-menu-header">
                                <div class="dropdown-menu-header-inner bg-success">
                                    <div class="menu-header-image opacity-0"
                                         style="background-image: url('/images/dropdown-header/city2.jpg');"></div>
                                    <div class="menu-header-content text-left">
                                        <h5 class="menu-header-title">Time Sheets</h5>
                                        <h6 class="menu-header-subtitle">Time Sheets Summary</h6>
                                    </div>
                                </div>
                            </div>
                            <button type="button" tabindex="0" class="dropdown-item">
                                <span class="badge badge-pill badge-danger ml-0 mr-2">
                                    {{-- {{ $timeSheets['returnedForCorrection'] }} --}}
                                </span>  Returned For Correction
                            </button>
                            <button type="button" tabindex="0" class="dropdown-item">
                                <span class="badge badge-pill badge-danger ml-0 mr-2">{{ $timeSheets['waitingForSPVApproval2'] }}</span>  Waiting SPV Approval
                            </button>
                            <button type="button" tabindex="0" class="dropdown-item">
                                <span class="badge badge-pill badge-danger ml-0 mr-2">{{ $timeSheets['waitingForHRMApproval2'] }}</span>  Waiting HRM Approval
                            </button>
                            <div tabindex="-1" class="dropdown-divider"></div>
                            <button type="button" tabindex="0" class="dropdown-item">
                                <span class="badge badge-pill badge-danger ml-0 mr-2">{{ $timeSheets['approved2'] }}</span>  Approved
                            </button>
                            <button type="button" tabindex="0" class="dropdown-item">
                                <span class="badge badge-pill badge-danger ml-0 mr-2">{{ $timeSheets['rejected2'] }}</span>  Rejected
                            </button>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="app-header-right">

                <div class="header-btn-lg pr-0">
                    <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="btn-group">
                                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                       class="p-0 btn">
                                        <img width="42" class="rounded-circle" src="@if(isset(auth()->user()->staff->image))/storage/{{auth()->user()->staff->image}}@else asset('images/avatars/general.png') @endif" alt="">
                                        <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                    </a>
                                    <div tabindex="-1" role="menu" aria-hidden="true"
                                         class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
                                        <div class="dropdown-menu-header">
                                            <div class="dropdown-menu-header-inner bg-info">
                                                <div class="menu-header-image opacity-2"
                                                     style="background-image: url('/images/dropdown-header/city3.jpg');"></div>
                                                <div class="menu-header-content text-left">
                                                    <div class="widget-content p-0">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left mr-3">
                                                                <img width="42" class="rounded-circle" src="@if(isset(auth()->user()->staff->image))/storage/{{auth()->user()->staff->image}}@else /images/avatars/general.png @endif" alt="">
                                                            </div>
                                                            <div class="widget-content-left">
                                                                <div class="widget-heading">
                                                                    {{ Auth::user()->name }}
                                                                </div>
                                                                <div class="widget-subheading opacity-8">
                                                                   {{ ucwords(str_replace('-',' ',Auth::user()->system_role->role_name))  }}
                                                                </div>
                                                            </div>
                                                            <div class="widget-content-right mr-2">
                                                                <button class="btn-pill btn-shadow btn-shine btn btn-focus"
                                                                        onclick="document.getElementById('logout-form').submit();">
                                                                    Logout
                                                                </button>

                                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                                    @csrf
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="scroll-area-xs" style="height: 150px;">
                                            <div class="scrollbar-container ps">
                                                <ul class="nav flex-column">
                                                    <li class="nav-item-header nav-item">My Account</li>
                                                    <li class="nav-item" style="display: none;">
                                                        <a href="{{url('/account_information')}}" class="nav-link" >Account Information
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="{{url('/change_password')}}" class="nav-link">Change Password
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content-left  ml-3 header-user-info">
                                <div class="widget-heading">
                                    {{ Auth::user()->name }}
                                </div>
                                <div class="widget-subheading">
                                      {{ ucwords(str_replace('-',' ',Auth::user()->system_role->role_name))  }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="app-main">

        <!-- Left Sidebar Starts Here -->
        <div class="app-sidebar sidebar-shadow">
            <div class="app-header__logo">
                <div class="logo-src"></div>
                <div class="header__pane ml-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                                data-class="closed-sidebar">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                    </button>
                </div>
            </div>
            <div class="app-header__menu">
                    <span>
                        <button type="button"
                                class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                            <span class="btn-icon-wrapper">
                                <i class="fa fa-ellipsis-v fa-w-6"></i>
                            </span>
                        </button>
                    </span>
            </div>

            <!-- Menu Section Starts Here -->
            <div class="scrollbar-sidebar">
                <div class="app-sidebar__inner">

                    <!-- Menu Starts Here -->
                    @include('layouts.administrator.admin-menu')
                    <!-- Menu Ends Here -->

                    <!-- Add some space before reaching the bottom of the page -->
                    <div style="padding-bottom: 50px;"></div>

                </div>
            </div>
            <!-- Menu Section Ends Here -->

        </div>
        <!-- Left Sidebar Ends Here -->


        <!-- Content & Footer Section Starts Here -->
        <div class="app-main__outer">

            <!-- Main Content Starts Here-->
            <div class="app-main__inner">
                @yield('content')
            </div>
            <!-- Main Content Ends Here-->


            <!-- Footer Starts Here-->
            <div class="app-wrapper-footer">
                <div class="app-footer">
                    @include('layouts.administrator.admin-footer')
                </div>
            </div>
            <!-- Footer Ends Here-->

        </div>
        <!-- Content & Footer Section Ends Here -->


    </div>
</div>



<!--Modals -->
@if($view_type ?? '' == 'index')
    @include('layouts.administrator.modal')
@endif

@if($model_name ?? '' == 'project' && $view_type ?? '' == 'index')
    @include('projects.projects.filter-modal')
@endif

@if($model_name ?? '' == 'activity' && $view_type ?? '' == 'index')
    @include('projects.activities.filter-modal')
@endif

@if($model_name ?? '' == 'gl_account' && $view_type ?? '' == 'index')
    @include('gl_accounts.filter-modal')
@endif
@yield('script')
</body>
</html>
