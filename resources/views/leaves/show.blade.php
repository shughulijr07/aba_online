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
                    <div class="text-primary">Leave No. {{ $leave->id }} Information</div>
                    <div class="page-title-subheading">Below is information about leave request</div>
                </div>
            </div>

        <!--actions' menu starts here -->
        <div class="page-title-actions">
            <a href="/leave_statement/{{ $leave->id }}" target="_blank" type="button" data-toggle="tooltip" title="Print" data-placement="bottom" class="btn-shadow mr-3 btn btn-danger">
                <i class="lnr-printer"></i>
            </a>
            <div class="d-inline-block dropdown">
                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-tasks" aria-hidden="true"></i>
                    </span>Actions
                </button>
                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('/overlapping_leaves/'.$leave->id)}}" target="_blank">
                                <i class="nav-link-icon pe-7s-photo-gallery"></i>
                                <span>View Overlapping</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--actions' menu ends here -->

        </div>
    </div>


    <!-- data 1 -->
    <div class="row">
        <div class="col-md-12 col-lg-6 col-xl-7">
            <div class="mb-3 card">
                <div class="card-header-tab card-header">
                    <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                        <i class="header-icon lnr-dice mr-3 text-muted opacity-6"> </i>
                        Leave Request Information
                    </div>
                </div>
                <div class="card-body">
                    <div class="bootstrap-table">
                        <div class="fixed-table-toolbar"></div>

                        <div class="fixed-table-container" style="padding-bottom: 0px;">
                            <div class="fixed-table-body">
                                <div class="fixed-table-loading" style="top: 42px; display: none;">
                                    Loading, please wait...
                                </div>
                                <table data-toggle="table" data-url="https://api.github.com/users/wenzhixin/repos?type=owner&amp;sort=full_name&amp;direction=asc&amp;per_page=10&amp;page=1" data-sort-name="stargazers_count" data-sort-order="desc" class="table table-hover">
                                    <thead></thead>
                                    <?php

                                        $days_requested = $leave->calculate_no_of_days_btn_dates($leave->starting_date,$leave->ending_date);
                                        $days_taken = '';
                                        $balance = '';

                                        $entitlement = $leave_entitlement[$leave->type];

                                        if($entitlement != ''){
                                            $days_taken = ($leave->get_taken_leaves($leave->employee_id,$leave->type,$leave->year))['days_taken'];
                                            $balance = $entitlement-$days_taken;
                                        }

                                    ?>
                                    <tbody>
                                    <tr class="bg-light-dark">
                                        <th>Type Of Leave Requested</th>
                                        <td>{{$leave_types[$leave->type]['name']}}</td>
                                    </tr>
                                    @if($leave->type == 'maternity_leave')
                                    <tr class="bg-light-dark">
                                        <th>Number Of Babies</th>
                                        <td>{{$leave->number_of_babies}}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>Leave Is Requested By</th>
                                        <td>{{ucwords($leave->staff->first_name.' '.$leave->staff->last_name)}}</td>
                                    </tr>
                                    <tr class="bg-light-dark">
                                        <th>Responsible Supervisor</th>
                                        <td>{{ucwords($responsible_spv->first_name.' '.$responsible_spv->last_name)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Employee No</th>
                                        <td>{{$leave->staff->staff_no}}</td>
                                    </tr>
                                    <tr class="bg-light-dark">
                                        <th>Employee Title</th>
                                        <td>{{$leave->staff->jobTitle->title}}</td>
                                    </tr>
                                    <tr>
                                        <th>Entitled Leave Days</th>
                                        <td>{{$entitlement}}</td>
                                    </tr>
                                    <tr class="bg-light-dark">
                                        <th>Leave Days Already Taken</th>
                                        <td>{{$days_taken}}</td>
                                    </tr>
                                    <tr>
                                        <th>Leave Balance</th>
                                        <td>{{$balance}}</td>
                                    </tr>
                                    <tr class="bg-light-dark">
                                        <th>Total Leave Days Requested</th>
                                        <td>{{$days_requested}}</td>
                                    </tr>
                                    <tr>
                                        <th>Leave Start On</th>
                                        <td>{{date('d-m-Y',strtotime($leave->starting_date))}}</td>
                                    </tr>
                                    <tr class="bg-light-dark">
                                        <th>Leave End On</th>
                                        <td>{{date('d-m-Y',strtotime($leave->ending_date))}}</td>
                                    </tr>
                                    <tr>
                                        <th>Description</th>
                                        <td>{{$leave->description}}</td>
                                    </tr>
                                    @if( isset($leave->supporting_document) )
                                    <tr>
                                        <th>Supporting Document</th>
                                        <td>
                                            <a href="{{url($leave->supporting_document)}}" target="_blank" class="mr-3">
                                                <i class="pe-7s-exapnd2"></i> View Document
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>Leave Payment</th>
                                        <td>{{$leave->payment}}</td>
                                    </tr>
                                    <tr class="bg-light-dark">
                                        <th>Request Was Made On</th>
                                        <td>{{date("d-m-Y H:i:s", strtotime($leave->created_at))}}</td>
                                    </tr>
                                    <tr>
                                        <th>Request Status</th>
                                        <td>{{$leave_statuses[$leave->status]}}</td>
                                    </tr>
                                    <tr>
                                        <th>Planned Leave</th>
                                        <td>
                                            @if( $planned_leave == null )
                                                No
                                            @else
                                                <a href="leave_plans/{{$planned_leave->leave_plan->id}}" target="_blank" class="mr-3">
                                                    <i class="pe-7s-exapnd2"></i> Yes
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>


        <div class="col-md-12 col-lg-6 col-xl-5">
            @if(session()->has('message'))
                <div  class="mb-3 card alert alert-primary" id="notifications-div">
                    <div class="p-3 card-body ">
                        <div class="text-center">
                            <h5 class="" id="message">{{session()->get('message')}}</h5>
                        </div>
                    </div>
                </div>
            @endif
            <div class="mb-3 card">
                <div class="card-header-tab card-header">
                    <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                        <i class="header-icon pe-7s-menu icon-gradient bg-happy-itmeo"> </i>
                        Leave Request Status
                    </div>
                </div>
                <div class="p-0 card-body">
                    <div class="dropdown-menu-header mt-0 mb-0">
                        <div class="dropdown-menu-header-inner bg-heavy-rain">
                            <div class="menu-header-image opacity-1" style="background-image: url('assets/images/dropdown-header/city3.jpg');"></div>
                            <div class="menu-header-content text-dark">
                                <h5 class="menu-header-title">{{$leave_statuses[$leave->status]}}</h5>
                                <h6 class="menu-header-subtitle"><!--
                                    Annual Membership Fee For The Year
                                    <b class="text-danger">{{date('Y')}}</b>
                                    <br>Have Been  -->
                                </h6>
                            </div>
                        </div>
                    </div>

                    <!-- actions title -->
                    <ul class="tabs-animated-shadow tabs-animated nav nav-justified tabs-shadow-bordered p-3">
                        <li class="nav-item">
                            <a role="tab" class="nav-link show active" id="tab-c-0" data-toggle="tab" href="#tab-animated-0" aria-selected="true">
                                <span>Message</span>
                            </a>
                        </li>
                    </ul>
                    <!-- end of actions title -->

                    <div class="tab-content" style="padding-bottom: 30px;">
                        <div class="tab-pane show active" id="tab-animated-0" role="tabpanel">
                            <div class="">
                                <div class="">
                                    <div class="p-3">
                                        <div class="vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                            <div class="vertical-timeline-item vertical-timeline-element">
                                                <div>
                                                    @if($leave->status == '10')
                                                        <span class="vertical-timeline-element-icon bounce-in">
                                                            <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                        </span>
                                                        <div class="vertical-timeline-element-content bounce-in">
                                                            <h4 class="timeline-title text-danger">LEAVE REQUEST RECEIVED</h4>
                                                            <p>
                                                                Hello, your Leave Request have been received, currently it is waiting for Supervisor's Approval.<br>
                                                                - Time of Receiving | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}
                                                            </p>

                                                            <p>
                                                                <br><br>
                                                                <span class="text-danger">For Assistance</span><br>
                                                                - Please contact system Administrator or Human Resource Manager
                                                            </p>
                                                        </div>
                                                    @endif

                                                    @if($leave->status == '20')
                                                        <span class="vertical-timeline-element-icon bounce-in">
                                                            <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                        </span>
                                                        <div class="vertical-timeline-element-content bounce-in">
                                                            <h4 class="timeline-title text-danger">LEAVE APPROVED BY SUPERVISOR</h4>
                                                            <p>
                                                                Hello, Leave Request have been Approved by Supervisor, currently it is waiting for Human Resource Manager
                                                                 & Managing Director Approval.<br>
                                                                - Time of approval | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}
                                                            </p>

                                                            @if($comments != '')
                                                            <p>
                                                                <br>
                                                                <span class="text-danger">Supervisor's Comments</span><br>
                                                                - {{$comments}}
                                                            </p>
                                                            @endif

                                                            <p>
                                                                <br>
                                                                <span class="text-danger">For Assistance</span><br>
                                                                - Please contact system Administrator or Human Resource Manager
                                                            </p>
                                                        </div>
                                                    @endif

                                                    @if($leave->status == '30')
                                                        <span class="vertical-timeline-element-icon bounce-in">
                                                        <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                    </span>
                                                        <div class="vertical-timeline-element-content bounce-in">
                                                            <h4 class="timeline-title text-danger">LEAVE APPROVED BY HUMAN RESOURCE MANAGER</h4>
                                                            <p>
                                                                Hello, your Leave Request have been Approved by Human Resource Manager,
                                                                currently it is waiting for Managing Director's Approval.<br>
                                                                - Time of Approval | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}
                                                            </p>

                                                            @if($comments != '')
                                                                <p>
                                                                    <br>
                                                                    <span class="text-danger">HRM's Comments</span><br>
                                                                    - {{$comments}}
                                                                </p>
                                                            @endif

                                                            <p>
                                                                <br>
                                                                <span class="text-danger">For Assistance</span><br>
                                                                - Please contact system Administrator or Human Resource Manager
                                                            </p>
                                                        </div>
                                                    @endif

                                                    @if($leave->status == '40')
                                                        <span class="vertical-timeline-element-icon bounce-in">
                                                            <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                        </span>
                                                        <div class="vertical-timeline-element-content bounce-in">
                                                            <h4 class="timeline-title text-danger">LEAVE APPROVED BY HRM BUT WAITING FOR PAYMENT</h4>
                                                            <p>
                                                                {!! $approval_message !!}<br>
                                                                - Time of Approval | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}
                                                            </p>

                                                            @if($comments != '')
                                                                <p>
                                                                    <br>
                                                                    <span class="text-danger">HRM's Comments</span><br>
                                                                    - {{$comments}}
                                                                </p>
                                                            @endif

                                                            <p>
                                                                <br><br>
                                                                <span class="text-danger">For Assistance</span><br>
                                                                - Please contact system Administrator or Human Resource Manager
                                                            </p>
                                                        </div>
                                                    @endif

                                                    @if($leave->status == '50')
                                                        <span class="vertical-timeline-element-icon bounce-in">
                                                            <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                        </span>
                                                        <div class="vertical-timeline-element-content bounce-in">
                                                            <h4 class="timeline-title text-danger">LEAVE APPROVED BY MANAGING DIRECTOR</h4>
                                                            <p>
                                                                {!! $approval_message !!}<br>
                                                                - Time of Approval | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}
                                                            </p>

                                                            @if($comments != '')
                                                                <p>
                                                                    <br>
                                                                    <span class="text-danger">HRM's Comments</span><br>
                                                                    - {{$comments}}
                                                                </p>
                                                            @endif

                                                            <p>
                                                                <br><br>
                                                                <span class="text-danger">For Assistance</span><br>
                                                                - Please contact system Administrator or Human Resource Manager
                                                            </p>
                                                        </div>
                                                    @endif

                                                    @if($leave->status == '99')
                                                        <span class="vertical-timeline-element-icon bounce-in">
                                                            <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                        </span>
                                                        <div class="vertical-timeline-element-content bounce-in">
                                                            <h4 class="timeline-title text-danger">LEAVE REQUEST REJECTED</h4>
                                                            <p>
                                                                Hello, your Leave Request have been rejected by
                                                                @if($rejection->level == 'spv') Supervisor. @elseif($rejection->level == 'hrm') Human Resource Manager. @else Managing Director. @endif<br>
                                                                - Time of Rejection | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}
                                                            </p>

                                                            <p>
                                                                <br>
                                                                <span class="text-danger">Reason Of Rejection</span><br>
                                                                - {{$rejection->reason}}
                                                            </p>

                                                            <p>
                                                                <br>
                                                                <span class="text-danger">For Assistance</span><br>
                                                                - Please contact system Administrator or Human Resource Manager
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

                        <!-- actions starts here -->
                        <!-- actions ends here-->

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready( function(){
            $('#notifications-div').delay(5000).fadeOut('slow');
        });
    </script>


@endsection
