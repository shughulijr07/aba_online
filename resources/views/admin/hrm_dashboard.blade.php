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
        @include('admin.dashboard_sections.my_items')
    </div>


    <div class="row" id="spv_items">
        @include('admin.dashboard_sections.spv_items')
    </div>


    <div class="row" id="special_items">


        <div  class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/admin_leaves/20')}}">
            <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-warning border-primary card">
                <div class="widget-chat-wrapper-outer">
                    <div class="widget-chart-content">
                        <div class="widget-title opacity-5 text-uppercase">
                            ({{ $leaveRequests['waitingForHRMApproval2'] }})
                            Waiting For HRM Approval
                        </div>
                        <div class="widget-numbers mt-2 fsize-3 mb-0 w-100">
                            <div class="widget-chart-flex align-items-center">
                                <div>
                                    Approve Leave Request (HRM)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div  class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/admin_time_sheets/30')}}">
            <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-success border-primary card">
                <div class="widget-chat-wrapper-outer">
                    <div class="widget-chart-content">
                        <div class="widget-title opacity-5 text-uppercase">
                            ({{ $timeSheets['waitingForHRMApproval2'] }})
                            Waiting For HRM Approval
                        </div>
                        <div class="widget-numbers mt-2 fsize-3 mb-0 w-100">
                            <div class="widget-chart-flex align-items-center">
                                <div>
                                    Approve Time Sheet (HRM)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div  class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/admin_leave_plans/30')}}">
            <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-warning border-primary card">
                <div class="widget-chat-wrapper-outer">
                    <div class="widget-chart-content">
                        <div class="widget-title opacity-5 text-uppercase">({{$leavePlans['waitingForHRMApproval2']}}) Waiting For HRM Approval</div>
                        <div class="widget-numbers mt-2 fsize-3 mb-0 w-100">
                            <div class="widget-chart-flex align-items-center">
                                <div>
                                    Approve Leave Plan (HRM)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div  class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/admin_performance_objectives/30')}}">
            <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-warning border-danger card">
                <div class="widget-chat-wrapper-outer">
                    <div class="widget-chart-content">
                        <div class="widget-title opacity-5 text-uppercase">({{ $performanceObjectives['waitingForHRMApproval2'] }}) Waiting For HRM Approval</div>
                        <div class="widget-numbers mt-2 fsize-3 mb-0 w-100">
                            <div class="widget-chart-flex align-items-center">
                                <div>
                                    Approve Objectives (HRM)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
