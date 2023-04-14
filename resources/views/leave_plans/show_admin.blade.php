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
                    <div class="text-primary">
                        <span class="text-danger font-weight-bold">{{$employee_name}}</span>'s Leave Plan For The Year
                        <span class="text-danger font-weight-bold">{{ $leave_plan->year }}</span>
                    </div>
                    <div class="page-title-subheading">Below is information for the timesheet</div>
                </div>
            </div>

            <!--actions' menu starts here -->
            <div class="page-title-actions">
                <a href="/leave_statement/{{ $leave_plan->id }}" target="_blank" type="button" data-toggle="tooltip" title="Print" data-placement="bottom" class="btn-shadow mr-3 btn btn-danger">
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
                                <a class="nav-link" href="{{url('/leave_plan_summary/admin')}}" target="_blank">
                                    <i class="nav-link-icon pe-7s-photo-gallery"></i>
                                    <span>View Leave Plan Summary</span>
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
    <div class="container" style="min-width: 100%">
        <div class="row">

            <!-- Leave Plan Section -->
            <div class="col-md-12 col-lg-9 col-xl-8" id="leave_plan_section">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">
                            Leave Plan For The Year <span class="text-primary" id="current_year">{{date('Y')}}</span>
                        </h5>


                        <!-- Plan Header -->
                        <div class="row mb-1">
                            <div class="col-md-12">
                                <fieldset>
                                    <legend class="text-danger"></legend>
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="position-relative form-group">
                                                <label for="employee_name" class="">
                                                    <span>Employee Name</span>
                                                </label>
                                                <input name="employee_name" id="employee_name" type="text" class="form-control" value="{{$employee_name}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="position-relative form-group">
                                                <label for="responsible_spv" class="">
                                                    <span>Supervisor</span>
                                                </label>
                                                <select name="responsible_spv" id="responsible_spv" class="form-control @error('country') is-invalid @enderror" @if($supervisors_mode == '1') readonly @endif>
                                                    @if($supervisors_mode == '2')<option value="">Select Supervisor</option>@endif
                                                    @foreach($leaveSupervisors as $supervisor)
                                                        <option value="{{$supervisor->id}}" @if(($supervisor->id == old('responsible_spv')) || ($supervisor->id == $responsible_spv)) selected @endif>
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
                                        <div class="col-md-4">
                                            <div class="position-relative form-group">
                                                <label for="year" class="">
                                                    <span>Year</span>
                                                </label>
                                                <input name="year" id="year" type="text" class="form-control" value="{{$leave_plan->year}}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <!-- End Of Plan Header -->

                        <!-- Plans Lines Start Here-->
                        <table style="width: 100%;" id="example1" class="table table-hover table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Leave Type</th>
                                <th>Days</th>
                                <th>Starting Date</th>
                                <th>Ending Date</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $n=1;?>
                            @foreach($leave_plan_lines as $plan)
                                <tr>
                                    <td>{{ $n }}</td>
                                    <td>{{ $leave_types[$plan->type_of_leave]['name']}}</td>
                                    <td>{{ \App\Models\MyFunctions::calculate_no_of_days_btn_dates($plan->starting_date,$plan->ending_date) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($plan->starting_date)) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($plan->ending_date))  }}</td>
                                    <td>{{ $plan->status }}</td>
                                </tr>
                                <?php $n++; ?>
                            @endforeach

                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                        <!-- Plans Lines Ends Here-->

                        <!-- This is leave entitlement summary-->
                        <div id="suggestion-div">

                            <div class="summary-div" id="annual_leave_summary_div">
                                <table style="width: 100%;"  class="table table-hover table-striped table-bordered">
                                    <tbody>
                                    <tr class='data-row'>
                                        <td class="text-danger">
                                    <span class="mr-3">
                                        Entitled Days :
                                        <span id="entitled_annul_leave_days">{{$leave_summary['annual_leave']['entitled-days']}}</span>
                                    </span>

                                            <span class="mr-3">
                                        Days Taken :
                                        <span id="annual_leave_days_taken">{{$leave_summary['annual_leave']['days-taken']}}</span>
                                    </span>

                                            <span class="mr-3">
                                        Balance :
                                        <span id="annual_leave_days_left">{{$leave_summary['annual_leave']['days-left']}}</span>
                                    </span>
                                        </td>
                                    </tr>
                                    <tr class='data-row' style="display: none;">
                                        <td>Employee No.</td>
                                        <td id="employee_no">{{auth()->user()->staff->staff_no}}</td>
                                    </tr>
                                    <tr class='data-row' style="display: none;">
                                        <td>Employee Email</td>
                                        <td id="email">{{auth()->user()->staff->official_email}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="summary-div" id="sick_leave_summary_div" style="display: none;">
                                <table style="width: 100%;"  class="table table-hover table-striped table-bordered">
                                    <tbody>
                                    <tr class='data-row'>
                                        <td class="text-danger">
                                    <span class="mr-3">
                                        Entitled Days :
                                        <span id="entitled_sick_leave_days">{{$leave_summary['sick_leave']['entitled-days']}}</span>
                                    </span>

                                            <span class="mr-3">
                                        Days Taken :
                                        <span id="sick_leave_days_taken">{{$leave_summary['sick_leave']['days-taken']}}</span>
                                    </span>

                                            <span class="mr-3">
                                        Balance :
                                        <span id="sick_leave_days_left">{{$leave_summary['sick_leave']['days-left']}}</span>
                                    </span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="summary-div" id="maternity_leave_summary_div" style="display: none;">
                                <table style="width: 100%;"  class="table table-hover table-striped table-bordered">
                                    <tbody>
                                    <tr class='data-row'>
                                        <td class="text-danger">
                                    <span class="mr-3">
                                        Entitled Days :
                                        <span id="entitled_maternity_leave_days">{{$leave_summary['maternity_leave']['entitled-days']}}</span>
                                    </span>

                                            <span class="mr-3">
                                        Days Taken :
                                        <span id="maternity_leave_days_taken">{{$leave_summary['maternity_leave']['days-taken']}}</span>
                                    </span>

                                            <span class="mr-3">
                                        Balance :
                                        <span id="maternity_leave_days_left">{{$leave_summary['maternity_leave']['days-left']}}</span>
                                    </span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="summary-div" id="paternity_leave_summary_div" style="display: none;">
                                <table style="width: 100%;"  class="table table-hover table-striped table-bordered">
                                    <tbody>
                                    <tr class='data-row'>
                                        <td class="text-danger">
                                    <span class="mr-3">
                                        Entitled Days :
                                        <span id="entitled_paternity_leave_days">{{$leave_summary['paternity_leave']['entitled-days']}}</span>
                                    </span>

                                            <span class="mr-3">
                                        Days Taken :
                                        <span id="paternity_leave_days_taken">{{$leave_summary['paternity_leave']['days-taken']}}</span>
                                    </span>

                                            <span class="mr-3">
                                        Balance :
                                        <span id="paternity_leave_days_left">{{$leave_summary['paternity_leave']['days-left']}}</span>
                                    </span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="summary-div" id="compassionate_leave_summary_div" style="display: none;">
                                <table style="width: 100%;"  class="table table-hover table-striped table-bordered">
                                    <tbody>
                                    <tr class='data-row'>
                                        <td class="text-danger">
                                    <span class="mr-3">
                                        Total Entitled Days :
                                        <span id="entitled_compassionate_leave_days">{{$leave_summary['compassionate_leave']['entitled-days']}}</span>
                                    </span>

                                            <span class="mr-3">
                                        Days Taken :
                                        <span id="compassionate_leave_days_taken">{{$leave_summary['compassionate_leave']['days-taken']}}</span>
                                    </span>

                                            <span class="mr-3">
                                        Balance :
                                        <span id="compassionate_leave_days_left">{{$leave_summary['compassionate_leave']['days-left']}}</span>
                                    </span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="summary-div" id="other_summary_div" style="display: none;">
                                <table style="width: 100%;"  class="table table-hover table-striped table-bordered">
                                    <tbody>
                                    <tr class='data-row'>
                                        <td class="text-danger">
                                    <span class="mr-3">
                                        Entitled Days :
                                        <span id="entitled_other_days">{{$leave_summary['other']['entitled-days']}}</span>
                                    </span>

                                            <span class="mr-3">
                                        Days Taken :
                                        <span id="other_days_taken">{{$leave_summary['other']['days-taken']}}</span>
                                    </span>

                                            <span class="mr-3">
                                        Balance :
                                        <span id="other_days_left">{{$leave_summary['other']['days-left']}}</span>
                                    </span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <!-- end of leave entitlement summary-->


                    </div>
                </div>
            </div>

            <!-- Message & Actions Section -->
            <div class="col-md-12 col-lg-3 col-xl-4">
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
                            Leave Plan Status
                        </div>
                    </div>
                    <div class="p-0 card-body">
                        <div class="dropdown-menu-header mt-0 mb-0">
                            <div class="dropdown-menu-header-inner bg-heavy-rain">
                                <div class="menu-header-image opacity-1" style="background-image: url('assets/images/dropdown-header/city3.jpg');"></div>
                                <div class="menu-header-content text-dark">
                                    <h6 class="menu-header-title">{{$leave_plan_statuses[$leave_plan->status]}}</h6>
                                    <h7 class="menu-header-subtitle"></h7>
                                </div>
                            </div>
                        </div>
                        <ul class="tabs-animated-shadow tabs-animated nav nav-justified tabs-shadow-bordered p-3">
                            <li class="nav-item">
                                <a role="tab" class="nav-link show active" id="tab-c-0" data-toggle="tab" href="#tab-animated-0" aria-selected="true">
                                    <span>Message</span>
                                </a>
                            </li>
                            @can('view-menu','leave_requests_for_approving_menu_item')
                                @if( ( $user_role == 1 && $leave_plan->status <=99) || ($user_role == 2 && $leave_plan->status ==40 ) ||
                                     ( $user_role == 3 && $leave_plan->status ==30) || ($user_role == 5 && $leave_plan->status ==20 ) ||
                                     ( auth()->user()->staff->id == $leave_plan->responsible_spv &&  $leave_plan->status ==20       )
                                     )
                                    <li class="nav-item">
                                        <a role="tab" class="nav-link show" id="tab-c-1" data-toggle="tab" href="#tab-animated-1" aria-selected="false">
                                            <span>Actions</span>
                                        </a>
                                    </li>
                                @endif
                            @endcan
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
                                                        @if($leave_plan->status == '0')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                            <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                        </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">RETURNED FOR CORRECTIONS</h4>
                                                                <p>
                                                                    This Leave Plan have been returned for corrections by
                                                                    @if($leave_plan->returns->last()->level == 'spv') Supervisor
                                                                    @elseif($leave_plan->returns->last()->level == 'hrm') Human Resource Manager
                                                                    @elseif($leave_plan->returns->last()->level == 'md')  Managing Director
                                                                    @else Administrator
                                                                    @endif
                                                                    .<br><br>
                                                                    - <span class="text-danger">Reason & Correction Instruction</span> <br>
                                                                    <span class="ml-2">{{$leave_plan->returns->last()->comments}}</span> <br><br>
                                                                    - <span class="text-danger">Time of Returning</span> <br>
                                                                    <span class="ml-2">{{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}</span>
                                                                </p>

                                                                <p>
                                                                    <br><br>
                                                                    <span class="text-danger">For Assistance</span><br>
                                                                    - Please contact system Administrator or Human Resource Manager
                                                                </p>
                                                            </div>
                                                        @endif
                                                        @if($leave_plan->status == '10')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                            <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                        </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">LEAVE PLAN SAVED IN DRAFTS</h4>
                                                                <p>
                                                                    This Leave Plan is still in Drafts and have not been submitted by employee yet.<br>
                                                                    - Time of Saving | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}
                                                                </p>

                                                                <p>
                                                                    <br><br>
                                                                    <span class="text-danger">For Assistance</span><br>
                                                                    - Please contact system Administrator or Human Resource Manager
                                                                </p>
                                                            </div>
                                                        @endif
                                                        @if($leave_plan->status == '20')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                            <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                        </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">NEW LEAVE PLAN SUBMITTED</h4>
                                                                <p>
                                                                    This Leave Plan have been submitted by employee. Currently it is still waiting for Supervisor's Approval.<br>
                                                                    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}
                                                                </p>

                                                                <p>
                                                                    <br><br>
                                                                    <span class="text-danger">For Assistance</span><br>
                                                                    - Please contact system Administrator or Human Resource Manager
                                                                </p>
                                                            </div>
                                                        @endif

                                                        @if($leave_plan->status == '30')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">LEAVE PLAN APPROVED BY SUPERVISOR</h4>
                                                                <p>
                                                                    This Leave Plan have been Approved by Supervisor, currently it is waiting for Human Resource Manager
                                                                     Approval.<br>
                                                                    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}
                                                                </p>

                                                                <p>
                                                                    <br><br>
                                                                    <span class="text-danger">For Assistance</span><br>
                                                                    - Please contact system Administrator or Human Resource Manager
                                                                </p>
                                                            </div>
                                                        @endif


                                                        @if($leave_plan->status == '40')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">LEAVE PLAN APPROVED BY HUMAN RESOURCE MANAGER</h4>
                                                                <p>
                                                                    This Leave Plan have been Approved by Human Resource Manager, currently it is waiting for
                                                                    Managing Director's Approval.<br>
                                                                    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}
                                                                </p>

                                                                <p>
                                                                    <br><br>
                                                                    <span class="text-danger">For Assistance</span><br>
                                                                    - Please contact system Administrator or Human Resource Manager
                                                                </p>
                                                            </div>
                                                        @endif



                                                        @if($leave_plan->status == '50')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">LEAVE PLAN APPROVED</h4>
                                                                <p>
                                                                    This Leave Plan have been Approved Successfully.<br>
                                                                    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}
                                                                </p>

                                                                <p>
                                                                    <br><br>
                                                                    <span class="text-danger">For Assistance</span><br>
                                                                    - Please contact system Administrator or Human Resource Manager
                                                                </p>
                                                            </div>
                                                        @endif



                                                        @if($leave_plan->status == '99')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">LEAVE PLAN REJECTED</h4>
                                                                <p>
                                                                    This Leave Plan have been rejected by
                                                                    @if($leave_plan->rejection->level == 'spv') Supervisor
                                                                    @elseif($leave_plan->rejection->level == 'hrm') Human Resource Manager
                                                                    @elseif($leave_plan->rejection->level == 'md')  Managing Director
                                                                    @else Administrator
                                                                    @endif
                                                                    .<br><br>
                                                                    - <span class="text-danger">Reason Of Rejection</span> <br>
                                                                    <span class="ml-2">{{$leave_plan->rejection->reason}}</span> <br><br>
                                                                    - <span class="text-danger">Time of Rejection</span> <br>
                                                                    <span class="ml-2">{{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}</span>
                                                                </p>

                                                                <p>
                                                                    <br><br>
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
                            @if( ( $user_role == 1 && $leave_plan->status <=99) || ($user_role == 2 && $leave_plan->status ==40 ) ||
                                 ( $user_role == 3 && $leave_plan->status ==30) || ($user_role == 5 && $leave_plan->status ==20 ) ||
                                 ( auth()->user()->staff->id == $leave_plan->responsible_spv &&  $leave_plan->status ==20       )
                                 )
                                <div class="tab-pane show" id="tab-animated-1" role="tabpanel">
                                    <div class="">
                                        <div class="">
                                            <div class="row text-center">
                                                <div class="col-sm-12 mt-3">
                                                    <button class="mb-2 mr-2 btn-pill btn btn-outline-primary" id="approve_switch">Approve</button>
                                                    <button class="mb-2 mr-2 btn-pill btn btn-outline-danger" id="return_switch">Return</button>

                                                    @if($supervisors_mode == 2)
                                                        <button class="mb-2 mr-2 btn-pill btn btn-outline-primary" id="change_spv_switch">SPV</button>
                                                    @endif

                                                    <button class="mb-2 mr-2 btn-pill btn btn-outline-danger" id="reject_switch" style="display: none;">Reject</button>
                                                </div>
                                            </div>
                                            <div class="p-3" id="approve_leave_plan_div">
                                                <form action="/approve_leave_plan" method="POST" enctype="multipart/form-data" id="approve_leave_plan_form">
                                                    @csrf
                                                    {{ csrf_field() }}

                                                    <fieldset>
                                                        <legend class="text-primary">Approve Leave Plan</legend>
                                                        <input name="leave_plan_id" value="{{$leave_plan->id}}" type="text" style="display: none;" readonly>
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

                                                    <button class="mt-2 btn btn-primary" id="approve_leave_plan_btn">Approve Leave Plan</button>
                                                </form>
                                            </div>
                                            <div class="p-3" id="return_leave_plan_div" style="display: none;">
                                                <form action="/return_leave_plan" method="POST" enctype="multipart/form-data" id="return_leave_plan_form">
                                                    @csrf
                                                    {{ csrf_field() }}

                                                    <fieldset>
                                                        <legend class="text-primary">Return Leave Plan For Correction</legend>
                                                        <input name="leave_plan_id" value="{{$leave_plan->id}}" type="text" style="display: none;" readonly>
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

                                                    <button class="mt-2 btn btn-primary" id="return_leave_plan_btn">Return</button>
                                                </form>
                                            </div>
                                            <div class="p-3" id="change_spv_div" style="display: none;">
                                                <form action="/change_leave_plan_spv" method="POST" enctype="multipart/form-data" id="change_spv_form">
                                                    @csrf
                                                    {{ csrf_field() }}

                                                    <fieldset>
                                                        <legend class="text-primary">Change Responsible Supervisor</legend>
                                                        <input name="leave_plan_id" value="{{$leave_plan->id}}" type="text" style="display: none;" readonly>
                                                        <div class="form-row mt-2">
                                                            <div class="col-md-12">
                                                                <div class="position-relative form-group">
                                                                    <label for="responsible_spv" class="">
                                                                        <span>Supervisor</span>
                                                                    </label>
                                                                    <select name="responsible_spv" id="responsible_spv" class="form-control @error('country') is-invalid @enderror">
                                                                        <option value="">Select Supervisor</option>
                                                                        @foreach($leaveSupervisors as $supervisor)
                                                                            <option value="{{$supervisor->id}}" @if(($supervisor->id == old('responsible_spv')) || ($supervisor->id == $leave_plan->responsible_spv)) selected @endif>
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
                                            <div class="p-3" id="reject_leave_plan_div" style="display: none;">
                                                <form action="/reject_leave_plan" method="POST" enctype="multipart/form-data" id="reject_form">
                                                    @csrf
                                                    {{ csrf_field() }}

                                                    <fieldset>
                                                        <legend class="text-danger">Reject Leave Plan Request</legend>
                                                        <input name="leave_plan_id" value="{{$leave_plan->id}}" type="text" style="display: none;" readonly>
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

                                                    <button class="mt-2 btn btn-danger" id="reject_leave_plan_btn">Reject Leave Plan</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        @endif
                        <!-- actions ends here-->

                        </div>
                    </div>
                </div>
            </div>
            <!-- End Of Message & Actions Section -->


        </div>
    </div>

    <script type="text/javascript">
        $(document).ready( function(){
            $('#notifications-div').delay(5000).fadeOut('slow');
        });

        $("#approve_switch").on("click",function(){
            $("#return_leave_plan_div").hide();
            $("#change_spv_div").hide();
            $("#reject_leave_plan_div").hide();
            $("#approve_leave_plan_div").show("slow");
        });

        $("#return_switch").on("click",function(){
            $("#approve_leave_plan_div").hide();
            $("#change_spv_div").hide();
            $("#reject_leave_plan_div").hide();
            $("#return_leave_plan_div").show("slow");
        });

        $("#change_spv_switch").on("click",function(){
            $("#return_leave_plan_div").hide();
            $("#approve_leave_plan_div").hide();
            $("#reject_leave_plan_div").hide();
            $("#change_spv_div").show("slow");
        });

        $("#reject_switch").on("click",function(){
            $("#return_leave_plan_div").hide();
            $("#change_spv_div").hide();
            $("#approve_leave_plan_div").hide();
            $("#reject_leave_plan_div").show("slow");
        });

    </script>

    <!--scripts -->
    <script type="text/javascript">

        $(function () {

            $("#leave_plan_section  :input").prop("readonly",true);

        });


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
