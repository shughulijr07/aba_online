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
                    <div class="text-primary">Retirement Request Submitted By {{$employee_name}}</div>
                    <div class="page-title-subheading">Below is information of Travel Request</div>
                </div>
            </div>

        <!--actions' menu starts here -->
        <!--actions' menu ends here -->

        </div>
    </div>


    <!-- data 1 -->
    <div class="container" style="min-width: 90%">
        <div class="row">

            <!-- Travel Request Section -->
            <div class="col-md-12 col-lg-9 col-xl-9">
                @include('retirement_requests.form')
            </div>
            <!-- End Of Travel Request Section -->

            <!-- Message Section -->
            <div class="col-md-12 col-lg-3 col-xl-3">
                @if(session()->has('message'))
                    <div  class="mb-3 card alert alert-primary" id="notifications-div">
                        <div class="p-3 card-body ">
                            <div class="text-center">
                                <h5 class="text-white" id="message">{{session()->get('message')}}</h5>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="mb-3 card">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                            <i class="header-icon pe-7s-menu icon-gradient bg-happy-itmeo"> </i>
                            Retirement Request Status
                        </div>
                    </div>
                    <div class="p-0 card-body">
                        <div class="dropdown-menu-header mt-0 mb-0">
                            <div class="dropdown-menu-header-inner bg-heavy-rain">
                                <div class="menu-header-image opacity-1" style="background-image: url('assets/images/dropdown-header/city3.jpg');"></div>
                                <div class="menu-header-content text-dark">
                                    <h5 class="menu-header-title">{{$travel_request_statuses[$travel_request->status]}}</h5>
                                    <h6 class="menu-header-subtitle"></h6>
                                </div>
                            </div>
                        </div>
                        <ul class="tabs-animated-shadow tabs-animated nav nav-justified tabs-shadow-bordered p-3">
                            <li class="nav-item">
                                <a role="tab" class="nav-link show active" id="tab-c-0" data-toggle="tab" href="#tab-animated-0" aria-selected="true">
                                    <span>Message</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" style="padding-bottom: 30px;">
                            <div class="tab-pane show active" id="tab-animated-0" role="tabpanel">
                                <div class="">
                                    <div class="">
                                        <div class="p-3">
                                            <div class="vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                                <div class="vertical-timeline-item vertical-timeline-element">
                                                    <div>
                                                        @if($travel_request->status == '0')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">RETIREMENT REQUEST RETURNED FOR CORRECTIONS</h4>
                                                                <p>
                                                                    Hello, your time sheet have been returned for corrections by
                                                                    @if($travel_request->returns->last()->level == 'spv') Supervisor
                                                                    @elseif($travel_request->returns->last()->level == 'hrm') Human Resource Manager
                                                                    @elseif($travel_request->returns->last()->level == 'acc') Accountant
                                                                    @elseif($travel_request->returns->last()->level == 'fd')  Finance Director
                                                                    @elseif($travel_request->returns->last()->level == 'md')  Managing Director
                                                                    @else Administrator
                                                                    @endif
                                                                    .<br><br>
                                                                    - <span class="text-danger">Reason & Correction Instruction</span> <br>
                                                                    <span class="ml-2">{{$travel_request->returns->last()->comments}}</span> <br><br>
                                                                    - <span class="text-danger">Time of Returning</span> <br>
                                                                    <span class="ml-2">{{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}</span>
                                                                </p>

                                                                <p>
                                                                    <br><br>
                                                                    <span class="text-danger">For Assistance</span><br>
                                                                    - Please contact system Administrator or Human Resource Manager
                                                                </p>
                                                            </div>
                                                        @endif
                                                        @if($travel_request->status == '10')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                            <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                        </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">RETIREMENT REQUEST SUBMITTED</h4>
                                                                <p>
                                                                    Hello, your retirement Request have been submitted successfully, please wait for Supervisor Approval.<br>
                                                                    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}
                                                                </p>

                                                                <p>
                                                                    <br><br>
                                                                    <span class="text-danger">For Assistance</span><br>
                                                                    - Please contact system Administrator or Human Resource Manager
                                                                </p>
                                                            </div>
                                                        @endif

                                                        @if($travel_request->status == '20')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">RETIREMENT REQUEST APPROVED BY SUPERVISOR</h4>
                                                                <p>
                                                                    Hello, your retirement Request have been approve by Supervisor, currently it is waiting for Accountant
                                                                     Approval.<br>
                                                                    - Time of Approving | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}
                                                                </p>

                                                                <p>
                                                                    <br><br>
                                                                    <span class="text-danger">For Assistance</span><br>
                                                                    - Please contact system Administrator or Human Resource Manager
                                                                </p>
                                                            </div>
                                                        @endif



                                                            @if($travel_request->status == '30')
                                                                <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                                <div class="vertical-timeline-element-content bounce-in">
                                                                    <h4 class="timeline-title text-danger">PAYMENT REQUEST APPROVED BY ACCOUNTANT</h4>
                                                                    <p>
                                                                        Hello, your Retirement Request have been approve by Accountant, currently it is waiting for Finance Director's
                                                                        Approval.<br>
                                                                        - Time of Approving | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}
                                                                    </p>

                                                                    <p>
                                                                        <br><br>
                                                                        <span class="text-danger">For Assistance</span><br>
                                                                        - Please contact system Administrator or Human Resource Manager
                                                                    </p>
                                                                </div>
                                                            @endif


                                                            @if($travel_request->status == '40')
                                                                <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                                <div class="vertical-timeline-element-content bounce-in">
                                                                    <h4 class="timeline-title text-danger">RETIREMENT REQUEST APPROVED BY FINANCE DIRECTOR</h4>
                                                                    <p>
                                                                        Hello, your Retirement Request have been approve by Finance Director, currently it is waiting for Managing Director's
                                                                        Approval.<br>
                                                                        - Time of Approving | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}
                                                                    </p>

                                                                    <p>
                                                                        <br><br>
                                                                        <span class="text-danger">For Assistance</span><br>
                                                                        - Please contact system Administrator or Human Resource Manager
                                                                    </p>
                                                                </div>
                                                            @endif



                                                            @if($travel_request->status == '50')
                                                                <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                                <div class="vertical-timeline-element-content bounce-in">
                                                                    <h4 class="timeline-title text-danger">RETIREMENT REQUEST APPROVED</h4>
                                                                    <p>
                                                                        Hello, your retirement Request have been approved successfully.<br>
                                                                        - Time of Approving | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}
                                                                    </p>

                                                                    <p>
                                                                        <br><br>
                                                                        <span class="text-danger">For Assistance</span><br>
                                                                        - Please contact system Administrator or Human Resource Manager
                                                                    </p>
                                                                </div>
                                                            @endif



                                                            @if($travel_request->status == '99')
                                                                <span class="vertical-timeline-element-icon bounce-in">
                                                                    <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                                </span>
                                                                <div class="vertical-timeline-element-content bounce-in">
                                                                    <h4 class="timeline-title text-danger">RETIREMENT REQUEST REJECTED</h4>
                                                                    <p>
                                                                        Hello, this time retirement have been rejected by
                                                                        @if($travel_request->rejection->level == 'spv') Supervisor
                                                                        @elseif($travel_request->rejection->level == 'hrm') Human Resource Manager
                                                                        @elseif($travel_request->rejection->level == 'acc') Accountant
                                                                        @elseif($travel_request->rejection->level == 'fd') Finance Director
                                                                        @elseif($travel_request->rejection->level == 'md')  Managing Director
                                                                        @else Administrator
                                                                        @endif
                                                                        .<br><br>
                                                                        - <span class="text-danger">Reason Of Rejection</span> <br>
                                                                          <span class="ml-2">{{$travel_request->rejection->reason}}</span> <br><br>
                                                                        - <span class="text-danger">Time of Rejection</span> <br>
                                                                          <span class="ml-2">{{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}</span>
                                                                    </p>

                                                                    <p>
                                                                        <br>
                                                                        - <span class="text-danger">For Assistance</span><br>
                                                                          <span class="ml-2">Please contact system Administrator or Human Resource Manager</span>
                                                                    </p>
                                                                </div>
                                                            @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Of Message Section -->


        </div>
    </div>

    <script type="text/javascript">
        $(document).ready( function(){
            $('#notifications-div').delay(5000).fadeOut('slow');
        });

        $("#approve_switch").on("click",function(){
            $("#return_timesheet_div").hide();
            $("#change_spv_div").hide();
            $("#reject_travel_request_div").hide();
            $("#approve_timesheet_div").show("slow");
        });

        $("#return_switch").on("click",function(){
            $("#approve_timesheet_div").hide();
            $("#change_spv_div").hide();
            $("#reject_travel_request_div").hide();
            $("#return_timesheet_div").show("slow");
        });

        $("#change_spv_switch").on("click",function(){
            $("#return_timesheet_div").hide();
            $("#approve_timesheet_div").hide();
            $("#reject_travel_request_div").hide();
            $("#change_spv_div").show("slow");
        });

        $("#reject_switch").on("click",function(){
            $("#return_timesheet_div").hide();
            $("#change_spv_div").hide();
            $("#approve_timesheet_div").hide();
            $("#reject_travel_request_div").show("slow");
        });

    </script>

    <!--scripts -->
    <script type="text/javascript">


    </script>


@endsection
