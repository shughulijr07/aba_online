@extends('layouts.administrator.admin')


@section('content')

    <!-- title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div>
                    <div class="text-primary">Dashboard</div>
                </div>
            </div>

        <!--actions' menu starts here -->
        <!--actions' menu ends here -->

        </div>
    </div>

    <div class="row" id="my_items">
        {{-- @include('admin.dashboard_sections.my_items') --}}
        {{-- @include('admin.dashboard_sections.spv_items') --}}

        <div class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/time-sheets')}}">
            <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-primary border-danger card">
                <div class="widget-chat-wrapper-outer">
                    <div class="widget-chart-content">
                        <div class="widget-title opacity-5 text-uppercase">List of Timesheets</div>
                        <div class="widget-numbers mt-2 fsize-3  mb-0 w-100">
                            <div class="widget-chart-flex align-items-center">
                                <div>
                                    Timesheets
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/admin_time_sheets/0')}}">
            <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-primary border-danger card">
                <div class="widget-chat-wrapper-outer">
                    <div class="widget-chart-content">
                        <div class="widget-title opacity-5 text-uppercase">Returned For Correction </div>
                        <div class="widget-numbers mt-2 fsize-3  mb-0 w-100">
                            <div class="widget-chart-flex align-items-center">
                                <div>
                                    Returned For 
                                    Correction
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/admin_time_sheets/20')}}">
            <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-primary border-danger card">
                <div class="widget-chat-wrapper-outer">
                    <div class="widget-chart-content">
                        <div class="widget-title opacity-5 text-uppercase">Waiting for SPV Approval </div>
                        <div class="widget-numbers mt-2 fsize-3  mb-0 w-100">
                            <div class="widget-chart-flex align-items-center">
                                <div>
                                    Waiting for SPV Approval
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/admin_time_sheets/50')}}">
            <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-primary border-danger card">
                    <div class="widget-chat-wrapper-outer">
                        <div class="widget-chart-content">
                            <div class="widget-title opacity-5 text-uppercase">Approved Timesheet </div>
                            <div class="widget-numbers mt-2 fsize-3  mb-0 w-100">
                                <div class="widget-chart-flex align-items-center">
                                    <div>
                                        Approved Timesheets
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
        </div>


        <div class="row">
            {{-- Approved Timesheets --}}
            <div  class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/admin_time_sheets/99')}}">
                <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-success border-danger card">
                    <div class="widget-chat-wrapper-outer">
                        <div class="widget-chart-content">
                            <div class="widget-title opacity-5 text-uppercase">
                                {{-- ({{ $requisitionRequests['waitingForMDApproval2'] }}) --}}
                                Rejected Timesheets
                            </div>
                            <div class="widget-numbers mt-2 fsize-3  mb-0 w-100">
                                <div class="widget-chart-flex align-items-center">
                                    <div>
                                        Rejected Timesheets
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End of Approved Timesheets --}}

            {{-- Delayed Timesheets --}}
            <div  class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/time_sheet_late_submissions')}}">
                <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-success border-danger card">
                    <div class="widget-chat-wrapper-outer">
                        <div class="widget-chart-content">
                            <div class="widget-title opacity-5 text-uppercase">
                                {{-- ({{ $requisitionRequests['waitingForMDApproval2'] }}) --}}
                                Delayed Timesheets
                            </div>
                            <div class="widget-numbers mt-2 fsize-3  mb-0 w-100">
                                <div class="widget-chart-flex align-items-center">
                                    <div>
                                        Delayed Timesheets
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End of Delayed Timesheets --}}


        </div>


@endsection
