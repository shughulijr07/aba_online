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
                    <div class="page-title-subheading"></div>
                </div>
            </div>

        <!--actions' menu starts here -->
        <!--actions' menu ends here -->

        </div>
    </div>







    <div class="row" id="my_items">

        <div class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/request_leave')}}">
            <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-primary border-danger card">
                <div class="widget-chat-wrapper-outer">
                    <div class="widget-chart-content">
                        <div class="widget-title opacity-5 text-uppercase">My Leaves</div>
                        <div class="widget-numbers mt-2 fsize-3  mb-0 w-100">
                            <div class="widget-chart-flex align-items-center">
                                <div>
                                    Request Leave
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div  class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/new_time_sheet')}}">
            <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-danger border-danger card">
                <div class="widget-chat-wrapper-outer">
                    <div class="widget-chart-content">
                        <div class="widget-title opacity-5 text-uppercase">My Time Sheets</div>
                        <div class="widget-numbers mt-2 fsize-3  mb-0 w-100">
                            <div class="widget-chart-flex align-items-center">
                                <div>
                                    Create Time Sheet
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/set_objectives')}}">
            <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-primary border-danger card">
                <div class="widget-chat-wrapper-outer">
                    <div class="widget-chart-content">
                        <div class="widget-title opacity-5 text-uppercase">My Objectives</div>
                        <div class="widget-numbers mt-2 fsize-3  mb-0 w-100">
                            <div class="widget-chart-flex align-items-center">
                                <div>
                                    Set Objectives
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div  class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/new_travel_request')}}">
            <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-danger border-danger card">
                <div class="widget-chat-wrapper-outer">
                    <div class="widget-chart-content">
                        <div class="widget-title opacity-5 text-uppercase">My Travel Requests</div>
                        <div class="widget-numbers mt-2 fsize-3  mb-0 w-100">
                            <div class="widget-chart-flex align-items-center">
                                <div>
                                    Make A Travel Request
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/leave_plans/create')}}">
            <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-primary border-danger card">
                <div class="widget-chat-wrapper-outer">
                    <div class="widget-chart-content">
                        <div class="widget-title opacity-5 text-uppercase">My Leave Plans</div>
                        <div class="widget-numbers mt-2 fsize-3  mb-0 w-100">
                            <div class="widget-chart-flex align-items-center">
                                <div>
                                    Create A Leave Plan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


@endsection
