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
                    <div class="text-primary">Performance Objective Submitted By {{$employee_name}}</div>
                    <div class="page-title-subheading"></div>
                </div>
            </div>

            <!--actions' menu starts here -->
            <!--actions' menu ends here -->

        </div>
    </div>


    <!-- data 1 -->
    <div class="container" style="min-width: 90%">
        <div class="row">

            <!-- Performance Objective Section -->
            <div class="col-md-12 col-lg-9 col-xl-9">
                @include('performance_objectives.form')
            </div>
            <!-- End Of Performance Objective Section -->

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
                            Submitted Objectives Status
                        </div>
                    </div>
                    <div class="p-0 card-body">
                        <div class="dropdown-menu-header mt-0 mb-0">
                            <div class="dropdown-menu-header-inner bg-heavy-rain">
                                <div class="menu-header-image opacity-1" style="background-image: url('assets/images/dropdown-header/city3.jpg');"></div>
                                <div class="menu-header-content text-dark">
                                    <h5 class="menu-header-title">{{$performance_objective_statuses[$performance_objective->status]}}</h5>
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
                                                        @if($performance_objective->status == '0')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">OBJECTIVES RETURNED FOR CORRECTIONS</h4>
                                                                <p>
                                                                    Hello, your submitted objectives have been returned for corrections by
                                                                    @if($performance_objective->returns->last()->level == 'spv') Supervisor
                                                                    @elseif($performance_objective->returns->last()->level == 'hrm') Human Resource Manager
                                                                    @elseif($performance_objective->returns->last()->level == 'md')  Managing Director
                                                                    @else Administrator
                                                                    @endif
                                                                    .<br><br>
                                                                    - <span class="text-danger">Reason & Correction Instruction</span> <br>
                                                                    <span class="ml-2">{{$performance_objective->returns->last()->comments}}</span> <br><br>
                                                                    - <span class="text-danger">Time of Returning</span> <br>
                                                                    <span class="ml-2">{{date("d-m-Y H:i:s", strtotime($performance_objective->updated_at))}}</span>
                                                                </p>

                                                                <p>
                                                                    <br><br>
                                                                    <span class="text-danger">For Assistance</span><br>
                                                                    - Please contact system Administrator or Human Resource Manager
                                                                </p>
                                                            </div>
                                                        @endif
                                                        @if($performance_objective->status == '10')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">OBJECTIVES STILL IN DRAFTS</h4>
                                                                <p>
                                                                    Objective are not yet submitted, currently they are still saved in Drafts.<br>
                                                                    - Time of Saving | {{date("d-m-Y H:i:s", strtotime($performance_objective->updated_at))}}
                                                                </p>

                                                                <p>
                                                                    <br><br>
                                                                    <span class="text-danger">For Assistance</span><br>
                                                                    - Please contact system Administrator or Human Resource Manager
                                                                </p>
                                                            </div>
                                                        @endif
                                                        @if($performance_objective->status == '20')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                            <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                        </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">OBJECTIVES SUBMITTED</h4>
                                                                <p>
                                                                    Hello, your Objectives have been submitted successfully, please wait for Approval.<br>
                                                                    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($performance_objective->updated_at))}}
                                                                </p>

                                                                <p>
                                                                    <br><br>
                                                                    <span class="text-danger">For Assistance</span><br>
                                                                    - Please contact system Administrator or Human Resource Manager
                                                                </p>
                                                            </div>
                                                        @endif

                                                        @if($performance_objective->status == '30')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">OBJECTIVES APPROVED BY SUPERVISOR</h4>
                                                                <p>
                                                                    Hello, your Objectives have been approved by Supervisor.<br>
                                                                    - Time of Approving | {{date("d-m-Y H:i:s", strtotime($performance_objective->updated_at))}}
                                                                </p>

                                                                <p>
                                                                    <br><br>
                                                                    <span class="text-danger">For Assistance</span><br>
                                                                    - Please contact system Administrator or Human Resource Manager
                                                                </p>
                                                            </div>
                                                        @endif



                                                        @if($performance_objective->status == '40')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">OBJECTIVES APPROVED BY HUMAN RESOURCE MANAGER</h4>
                                                                <p>
                                                                    Hello, your Objectives have been approve by Human Resource Manager.<br>
                                                                    - Time of Approving | {{date("d-m-Y H:i:s", strtotime($performance_objective->updated_at))}}
                                                                </p>

                                                                <p>
                                                                    <br><br>
                                                                    <span class="text-danger">For Assistance</span><br>
                                                                    - Please contact system Administrator or Human Resource Manager
                                                                </p>
                                                            </div>
                                                        @endif



                                                        @if($performance_objective->status == '50')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">OBJECTIVES APPROVED</h4>
                                                                <p>
                                                                    Hello, your Performance Objective have been approved successfully.<br>
                                                                    - Time of Approving | {{date("d-m-Y H:i:s", strtotime($performance_objective->updated_at))}}
                                                                </p>

                                                                <p>
                                                                    <br><br>
                                                                    <span class="text-danger">For Assistance</span><br>
                                                                    - Please contact system Administrator or Human Resource Manager
                                                                </p>
                                                            </div>
                                                        @endif



                                                        @if($performance_objective->status == '99')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                    <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                                </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">OBJECTIVES REJECTED</h4>
                                                                <p>
                                                                    Hello, your objectives have been rejected by
                                                                    @if($performance_objective->rejection->level == 'spv') Supervisor
                                                                    @elseif($performance_objective->rejection->level == 'hrm') Human Resource Manager
                                                                    @elseif($performance_objective->rejection->level == 'md')  Managing Director
                                                                    @else Administrator
                                                                    @endif
                                                                    .<br><br>
                                                                    - <span class="text-danger">Reason Of Rejection</span> <br>
                                                                    <span class="ml-2">{{$performance_objective->rejection->reason}}</span> <br><br>
                                                                    - <span class="text-danger">Time of Rejection</span> <br>
                                                                    <span class="ml-2">{{date("d-m-Y H:i:s", strtotime($performance_objective->updated_at))}}</span>
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
            $("#reject_performance_objective_div").hide();
            $("#approve_timesheet_div").show("slow");
        });

        $("#return_switch").on("click",function(){
            $("#approve_timesheet_div").hide();
            $("#change_spv_div").hide();
            $("#reject_performance_objective_div").hide();
            $("#return_timesheet_div").show("slow");
        });

        $("#change_spv_switch").on("click",function(){
            $("#return_timesheet_div").hide();
            $("#approve_timesheet_div").hide();
            $("#reject_performance_objective_div").hide();
            $("#change_spv_div").show("slow");
        });

        $("#reject_switch").on("click",function(){
            $("#return_timesheet_div").hide();
            $("#change_spv_div").hide();
            $("#approve_timesheet_div").hide();
            $("#reject_performance_objective_div").show("slow");
        });

    </script>

    <!--scripts -->
    <script type="text/javascript">

        $(function () {
            //$("input").prop("disabled",true);

        });


    </script>


@endsection
