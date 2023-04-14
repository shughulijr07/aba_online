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
                    <ul class="tabs-animated-shadow tabs-animated nav nav-justified tabs-shadow-bordered p-3">
                        <li class="nav-item">
                            <a role="tab" class="nav-link show active" id="tab-c-0" data-toggle="tab" href="#tab-animated-0" aria-selected="true">
                                <span>Message</span>
                            </a>
                        </li>
                        @if( (  $user_role == 1 && $leave->status <=40) || ($user_role == 2 && $leave->status <=30) ||  ($user_role == 3 && $leave->status <=20) ||
                               ($user_role == 4 && $leave->status ==40) || ($user_role == 5 && $leave->status <= 10) )
                        <li class="nav-item">
                            <a role="tab" class="nav-link show" id="tab-c-1" data-toggle="tab" href="#tab-animated-1" aria-selected="false">
                                <span>Actions</span>
                            </a>
                        </li>
                        @endif

                    </ul>
                    <div class="tab-content" style="padding-bottom: 30px;">

                        <!-- message content starts here -->
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
                                                            <h4 class="timeline-title text-danger">NEW LEAVE REQUEST RECEIVED</h4>
                                                            <p>
                                                                This Leave Request is still waiting for Supervisor's Approval.<br>
                                                                - Time Of Receiving | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}
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
                                                                This Leave Request have been Approved by Supervisor, currently it is waiting for Human Resource Manager
                                                                & Managing Director Approval.<br>
                                                                - Time of Approval | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}
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
                                                                This Leave Request have been Approved by Supervisor & Human Resource Manager,
                                                                currently it waiting for Managing Director Approval.<br>
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
                                                            <h4 class="timeline-title text-danger">LEAVE APPROVED BY MANAGING DIRECTOR BUT WAITING FOR PAYMENT</h4>
                                                            <p>
                                                                This Leave Request have been Approved Completely by Supervisor, Human Resource Manager & Managing Director,
                                                                currently it waiting for payment to be issued<br>
                                                                - Time of Approval | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}
                                                            </p>

                                                            @if($comments != '')
                                                                <p>
                                                                    <br>
                                                                    <span class="text-danger">MD's Comments</span><br>
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
                                                                This Leave Request have been Approved by Managing Director<br>
                                                                - Time of Approval | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}
                                                            </p>

                                                            @if($comments != '')
                                                                <p>
                                                                    <br>
                                                                    <span class="text-danger">MD's Comments</span><br>
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
                                                                This Leave Request have been rejected by
                                                                @if($rejection->level == 'spv') SUPERVISOR. @elseif($rejection->level == 'hrm') Human Resource Manager. @else Managing Director. @endif<br>
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
                        <!-- message content ends here -->

                        <!-- actions starts here -->
                        @if( (  $user_role == 1 && $leave->status <=40) || ($user_role == 2 && $leave->status <=30) ||  ($user_role == 3 && $leave->status <=20) ||
                               ($user_role == 4 && $leave->status ==40) || ($user_role == 5 && $leave->status <= 10) )
                        <div class="tab-pane show" id="tab-animated-1" role="tabpanel">
                            <div class="">
                                <div class="">
                                    <div class="row text-center">
                                        <div class="col-sm-12 mt-3">
                                            @if( ($user_role == 1 && $leave->status < 40) || ($user_role == 2 && $leave->status <=30) || ($user_role == 3 && $leave->status <=20) ||
                                                 ($user_role == 4 && $leave->status <=20) ||  ($user_role == 5 && $leave->status <= 10) )
                                            <button class="mb-2 mr-2 btn-pill btn btn-outline-primary" id="approve_switch">Approve</button>
                                            <button class="mb-2 mr-2 btn-pill btn btn-outline-danger" id="modify_switch">Modify</button>
                                                @if($supervisors_mode == 2)
                                                <button class="mb-2 mr-2 btn-pill btn btn-outline-primary" id="change_spv_switch">Change Supervisor</button>
                                                @endif
                                            <button class="mb-2 mr-2 btn-pill btn btn-outline-danger" id="reject_switch">Reject</button>
                                            @endif

                                            @if( ($user_role == 1 && $leave->status ==40) || ($user_role == 3 && $leave->status ==40) || ($user_role == 4 && $leave->status ==40) )
                                            <button class="mb-2 mr-2 btn-pill btn btn-outline-primary" id="payment_switch">Confirm Payment</button>
                                            @endif
                                        </div>
                                    </div>

                                    @if( ($user_role == 1 && $leave->status <40) || ($user_role == 2 && $leave->status <=40) || ($user_role == 3 && $leave->status <=20) || ($user_role == 4 && $leave->status <=10) ||  ($user_role == 5 && $leave->status <= 10) )
                                    <div class="p-3" id="approve_leave_div">
                                        <form action="/approve_leave" method="POST" enctype="multipart/form-data" id="approve_leave_form">
                                            @csrf
                                            {{ csrf_field() }}

                                            <fieldset>
                                                <legend class="text-primary">Approve Leave Request</legend>
                                                <input name="leave_id" value="{{$leave->id}}" type="text" style="display: none;" readonly>
                                                <div class="form-row mt-2">
                                                    <div class="col-md-12">
                                                        <div class="position-relative form-group">
                                                            <label for="comments" class="">
                                                                Comments <span class="text-danger"></span>
                                                            </label>
                                                            <textarea name="comments" id="comments" class="form-control @error('comments') is-invalid @enderror" value="{{ old('comments') ?? $comments}}" autocomplete="off"></textarea>

                                                            @error('comments')
                                                            <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                 </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>

                                            <button class="mt-2 btn btn-primary" id="approve_leave_btn">Approve Leave Request</button>
                                        </form>
                                    </div>
                                    <div class="p-3" id="modify_leave_div" style="display: none;">
                                        <form action="/modify_leave" method="POST" enctype="multipart/form-data" id="modify_leave_form">
                                            @csrf
                                            {{ csrf_field() }}

                                            <fieldset>
                                                <legend class="text-danger">Modify Leave Request & Approve</legend>
                                                <input name="leave_id" value="{{$leave->id}}" type="text" style="display: none;" readonly>
                                                <div class="form-row mt-2">
                                                    <div class="col-md-6">
                                                        <div class="position-relative form-group">
                                                            <label for="starting_date" class="">
                                                                <span>Leave Starting Date</span>
                                                            </label>
                                                            <input name="starting_date" id="starting_date" type="text" class="form-control @error('starting_date') is-invalid @enderror" value="{{ old('starting_date') ?? date("d/m/Y", strtotime($leave->starting_date))}}" autocomplete="off">

                                                            @error('starting_date')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                             </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="position-relative form-group">
                                                            <label for="ending_date" class="">
                                                                <span>Leave Ending Date</span>
                                                            </label>
                                                            <input name="ending_date" id="ending_date" type="text" class="form-control @error('ending_date') is-invalid @enderror" value="{{ old('ending_date') ?? date('d/m/Y',strtotime($leave->ending_date)) }}" autocomplete="off">

                                                            @error('ending_date')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                             </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12" style="display: none;">
                                                        <div class="position-relative form-group">
                                                            <label for="leave_type" class="">
                                                                <span>Type Of Leave</span>
                                                            </label>
                                                            <select name="leave_type" id="leave_type" class="form-control @error('country') is-invalid @enderror">
                                                                <option value="">Select Leave</option>
                                                                @foreach($leave_types as $key=>$leave_details)
                                                                    <option value="{{$key}}" @if(($key == old('leave_type')) || ($key == $leave->type)) selected @endif>
                                                                        {{$leave_details['name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                            @error('leave_type')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-2" style="display: none;">
                                                        <div class="position-relative form-group">
                                                            <input type="hidden" name="include_payment[]" value="no">
                                                            <input type="checkbox" name="include_payment[]" id="include_payment" value="yes" @if( (is_array(old('include_payment')) &&  in_array('yes',old('include_payment'))) || $leave->payment == 'Include' ) checked @endif >
                                                            <span class="text-primary">Include Leave Payment</span>

                                                            @error('include_payment')
                                                            <span class="invalid-feedback" role="alert" style="display: block">
                                                                <strong>{{ $message }}</strong>
                                                             </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-2">
                                                        <div class="position-relative form-group">
                                                            <span class="text-danger">Days Requested</span>
                                                            <span class="badge badge-pill badge-danger ml-2 mr-2" id="days_requested">0</span>
                                                        </div>
                                                    </div>
                                                    @if($include_weekends_in_leave ==2 || $include_holidays_in_leave==2)
                                                    <div class="col-md-6 mt-2">
                                                        <div class="position-relative form-group">
                                                            <span class="text-primary" id="excluded_text">Number Of Days Excluded</span>
                                                            <span class="badge badge-pill badge-primary ml-2 mr-2" id="days_excluded">0</span>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    <div class="col-md-12">
                                                        <div class="position-relative form-group">
                                                            <label for="leave_modification_reason" class="">
                                                                Reason For Modifying <span class="text-danger">*</span>
                                                            </label>
                                                            <textarea name="reason" id="leave_modification_reason" class="form-control @error('leave_modification_reason') is-invalid @enderror" value="{{ old('leave_modification_reason') ?? $leave_modification_reason}}" autocomplete="off"></textarea>

                                                            @error('leave_modification_reason')
                                                            <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                 </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>

                                            <button class="mt-2 btn btn-danger" id="modify_approve_leave_btn">Modify & Approve Leave</button>
                                        </form>
                                    </div>

                                    @if($supervisors_mode == 2)
                                    <div class="p-3" id="change_spv_div" style="display: none;">
                                        <form action="/change_supervisor" method="POST" enctype="multipart/form-data" id="change_spv_form">
                                            @csrf
                                            {{ csrf_field() }}

                                            <fieldset>
                                                <legend class="text-primary">Change Responsible Supervisor</legend>
                                                <input name="leave_id" value="{{$leave->id}}" type="text" style="display: none;" readonly>
                                                <div class="form-row mt-2">
                                                    <div class="col-md-12">
                                                        <div class="position-relative form-group">
                                                            <label for="responsible_spv" class="">
                                                                <span>Supervisor</span>
                                                            </label>
                                                            <select name="responsible_spv" id="responsible_spv" class="form-control @error('country') is-invalid @enderror">
                                                                <option value="">Select Supervisor</option>
                                                                @foreach($leaveSupervisors as $supervisor)
                                                                    <option value="{{$supervisor->id}}" @if(($supervisor->id == old('responsible_spv')) || ($supervisor->id == $leave->responsible_spv)) selected @endif>
                                                                        {{ucwords($supervisor->first_name.' '.$supervisor->last_name)}}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                            @error('responsible_spv')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="position-relative form-group">
                                                            <label for="reason" class="">
                                                                Reason For Changing Supervisor <span class="text-danger">*</span>
                                                            </label>
                                                            <textarea name="reason" id="supervisor_change_reason" class="form-control @error('supervisor_change_reason') is-invalid @enderror" value="{{ old('supervisor_change_reason') ?? $supervisor_change_reason}}" autocomplete="off"></textarea>

                                                            @error('reason')
                                                            <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                         </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>

                                            <button class="mt-2 btn btn-primary" id="change_spv_btn">Change Supervisor</button>
                                        </form>
                                    </div>
                                    @endif

                                    <div class="p-3" id="reject_leave_div" style="display: none;">
                                        <form action="/reject_leave" method="POST" enctype="multipart/form-data" id="reject_form">
                                            @csrf
                                            {{ csrf_field() }}

                                            <fieldset>
                                                <legend class="text-danger">Reject Leave Request</legend>
                                                <input name="leave_id" value="{{$leave->id}}" type="text" style="display: none;" readonly>
                                                <div class="form-row mt-2">
                                                    <div class="col-md-12">
                                                        <div class="position-relative form-group">
                                                            <label for="rejection_reason" class="">
                                                                Reason For Rejecting <span class="text-danger">*</span>
                                                            </label>
                                                            <textarea name="rejection_reason" id="rejection_reason" class="form-control @error('rejection_reason') is-invalid @enderror" value="{{ old('rejection_reason') ?? $rejection_reason}}" autocomplete="off"></textarea>

                                                            @error('rejection_reason')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                             </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>

                                            <button class="mt-2 btn btn-danger" id="reject_leave_btn">Reject Leave</button>
                                        </form>
                                    </div>

                                    @endif

                                    @if( ($user_role == 1 && $leave->status == 40) || ($user_role == 3 && $leave->status == 40) || ($user_role == 4 && $leave->status ==40) )
                                    <div class="p-3" id="payment_div">
                                            <form action="/confirm_leave_payment" method="POST" enctype="multipart/form-data" id="confirm_payment_form">
                                                @csrf
                                                {{ csrf_field() }}

                                                <fieldset>
                                                    <legend class="text-primary">Confirm Payment</legend>
                                                    <input name="leave_id" value="{{$leave->id}}" type="text" style="display: none;" readonly>
                                                    <div class="form-row mt-2">
                                                        <div class="col-md-12">
                                                            <div class="position-relative form-group">
                                                                <label for="comments" class="">
                                                                    Comments <span class="text-danger"></span>
                                                                </label>
                                                                <textarea name="comments" id="comments" class="form-control @error('comments') is-invalid @enderror" value="{{ old('comments') ?? $comments}}" autocomplete="off"></textarea>

                                                                @error('comments')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                 </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>

                                                <button class="mt-2 btn btn-primary" id="confirm_payment_btn">Confirm Leave Payment</button>
                                            </form>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                        @endif
                        <!-- actions ends here-->

                    </div>
                </div>
            </div>
        </div>



        <!-- useful values are stored here-->
        <div style="display: none;">
            <span id="staff_gender">{{$leave->staff->gender}}</span>

            @if( $leave->staff->gender == 'Female' )
                <span id="normal_maternity_leave">{{$unfiltered_leave_types['maternity_leave']['days']}}</span>
                <span id="twins_maternity_leave">{{$unfiltered_leave_types['maternity_leave_2']['days']}}</span>
            @endif

            <span id="include_weekends">@if($include_weekends_in_leave == 1) yes @else no @endif</span>
            <span id="include_holidays">@if($include_holidays_in_leave == 1) yes @else no @endif</span>

            <div id="holidays">
                @foreach($holidays1 as $holiday_date1=>$holiday_name1)
                    <span class="holiday-date" id="{{date('j-n-Y',strtotime($holiday_date1))}}">{{$holiday_name1}}</span>
                @endforeach
                @foreach($holidays2 as $holiday_date2=>$holiday_name2)
                    <span class="holiday-date" id="{{date('j-n-Y',strtotime($holiday_date2))}}">{{$holiday_name2}}</span>
                @endforeach
            </div>
        </div>



    </div>

    <script type="text/javascript">
        $(document).ready( function(){
            $('#notifications-div').delay(5000).fadeOut('slow');

            $('#starting_date,#ending_date').datepicker({
                format: 'dd/mm/yyyy'
            });

            //update some data in modification form
            var starting_date =  $('#starting_date').val();
            var ending_date   =  $('#ending_date').val();
            var days_requested = calculate_no_of_leave_days(starting_date,ending_date);
            $("#days_requested").text(days_requested);

        });

        $("#approve_switch").on("click",function(){
            $("#modify_leave_div").hide();
            $("#change_spv_div").hide();
            $("#reject_leave_div").hide();
            $("#approve_leave_div").show("slow");
        });

        $("#modify_switch").on("click",function(){
            $("#approve_leave_div").hide();
            $("#change_spv_div").hide();
            $("#reject_leave_div").hide();
            $("#modify_leave_div").show("slow");
        });

        $("#change_spv_switch").on("click",function(){
            $("#modify_leave_div").hide();
            $("#approve_leave_div").hide();
            $("#reject_leave_div").hide();
            $("#change_spv_div").show("slow");
        });

        $("#reject_switch").on("click",function(){
            $("#modify_leave_div").hide();
            $("#change_spv_div").hide();
            $("#approve_leave_div").hide();
            $("#reject_leave_div").show("slow");
        });


        $("#ending_date").on('change', function (e) {

            var starting_date =  $('#starting_date').val();
            var ending_date = $(this).val();


            //validate these values, we will do it letter
            if ( starting_date === '' ) {
                var error_message = "Please select starting date !";
                sweet_alert_error(error_message);
                $(this).val('');

            }else{

                var days_requested = calculate_no_of_leave_days(starting_date,ending_date);
                if(days_requested <= 0){

                    $("#days_requested").text('0');

                    var error_message = "Ending date must be ahead of starting date. Please select ending date correctly !";
                    sweet_alert_error(error_message);
                    $(this).val('');

                }
                else{
                    //display the number of days
                    $("#days_requested").text(days_requested);
                }

            }
        });


        /************* my functions goes here *********/

        function calculate_no_of_leave_days(starting_date,ending_date){

            var no_of_leave_days = 0;

            if(starting_date == '' && ending_date == '')
            {
                no_of_leave_days = 0;
            }
            else{

                //format dates to format of MM/DD/YYYY
                splited_date = starting_date.split('/');
                starting_date = splited_date[1] + '/' + splited_date[0] + '/'  +splited_date[2];

                splited_date = ending_date.split('/');
                ending_date = splited_date[1] + '/' + splited_date[0] + '/'  +splited_date[2];

                //convert to date object
                starting_date = new Date(starting_date);
                ending_date = new Date(ending_date);

                var time_interval = ending_date - starting_date;
                var secs_time_interval = time_interval/1000; //time interval in seconds
                var days_time_interval = Math.floor(secs_time_interval/(60*60*24));

                no_of_leave_days = ++days_time_interval;

                //exclude weekends and holidays according to settings
                var weekends_and_holidays = count_weekend_and_holidays(starting_date,ending_date);
                var number_of_weekend_days_in_leave = weekends_and_holidays[0];
                var number_of_holidays_in_leave   = weekends_and_holidays[1];
                var number_of_weekends_and_holidays_in_leave = weekends_and_holidays[2];

                var include_weekends = $('#include_weekends').text().trim();
                var include_holidays = $('#include_holidays').text().trim();

                if( include_weekends == 'no' && include_holidays == 'no' ){
                    no_of_leave_days -= number_of_weekends_and_holidays_in_leave;
                    $("#days_excluded").text(number_of_weekends_and_holidays_in_leave);

                    if(number_of_weekend_days_in_leave == 0 && number_of_holidays_in_leave>0){
                        $("#excluded_text").text('Number Of Holidays Excluded');
                    }
                    else if(number_of_weekend_days_in_leave > 0 && number_of_holidays_in_leave == 0){
                        $("#excluded_text").text('Number Of  Weekend Days Excluded');
                    }
                    else if(number_of_weekend_days_in_leave > 0 && number_of_holidays_in_leave>0){
                        $("#excluded_text").text('Number Of Weekend Days & Holidays Excluded');
                    }
                    else{
                        $("#excluded_text").text('Number Of Days Excluded');
                    }
                }
                else if( include_weekends == 'no' && include_holidays == 'yes' ){
                    no_of_leave_days -= number_of_weekend_days_in_leave;
                    $("#days_excluded").text(number_of_weekend_days_in_leave);
                    $("#excluded_text").text('Number Of Weekend Days Excluded');
                }
                else if( include_weekends == 'yes' && include_holidays == 'no' ){
                    no_of_leave_days -= number_of_holidays_in_leave;
                    $("#days_excluded").text(number_of_holidays_in_leave);
                    $("#excluded_text").text('Number Of Holidays Excluded');
                }




            }

            return no_of_leave_days;

        }

        function count_weekend_and_holidays(starting_date,ending_date){

            var no_of_weekend_days_in_time_interval = 0;
            var no_of_holidays_in_time_interval = 0;
            var no_of_weekend_and_holidays_in_time_interval = 0;
            var currentDate = new Date(starting_date.getTime());

            while (currentDate <= ending_date) {


                var needed_date = new Date(currentDate);
                var day_position_in_week = needed_date.getUTCDay();//saturday = 5, sunday = 6, monday = 0

                if( day_position_in_week == 5 || day_position_in_week == 6  ){
                    no_of_weekend_days_in_time_interval++;
                    no_of_weekend_and_holidays_in_time_interval++;
                }
                else{
                    //check if there is any holiday in week days
                    var full_date = needed_date.getDate() + '-' + (needed_date.getMonth()+1) + '-' + needed_date.getFullYear();

                    $(".holiday-date").each( function(index){
                        var holiday_date = $(this).attr('id');

                        if(holiday_date == full_date){
                            no_of_weekend_and_holidays_in_time_interval++;
                        }
                    });

                    //alert(full_date);
                }

                //just in case, count number of holidays
                var full_date = needed_date.getDate() + '-' + (needed_date.getMonth()+1) + '-' + needed_date.getFullYear();

                $(".holiday-date").each( function(index){
                    var holiday_date = $(this).attr('id');

                    if(holiday_date == full_date){
                        no_of_holidays_in_time_interval++;
                        //alert( 'we have got a holiday => ' + holiday_date);
                    }
                });





                currentDate.setDate(currentDate.getDate() + 1) ;
            }

            //alert(no_of_weekend_days_in_time_interval);

            var holiday_and_weekends = new Array(3);
            holiday_and_weekends[0] = no_of_weekend_days_in_time_interval;
            holiday_and_weekends[1] = no_of_holidays_in_time_interval;
            holiday_and_weekends[2] = no_of_weekend_and_holidays_in_time_interval;

            return holiday_and_weekends;
        };


        /******************* sweet alerts *******************************/

        function sweet_alert_success(success_message){
            Swal.fire({
                type: "success",
                text: success_message,
                confirmButtonColor: '#213368',
            })
        }


        function sweet_alert_error(error_message){
            Swal.fire({
                type: "error",
                text: error_message,
                confirmButtonColor: '#213368',
            })
        }


        function sweet_alert_warning(warning_message){
            Swal.fire({
                type: "warning",
                text: warning_message,
                confirmButtonColor: '#213368',
            })
        }


    </script>


@endsection
