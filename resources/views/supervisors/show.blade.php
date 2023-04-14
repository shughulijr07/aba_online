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
                    <div class="text-primary">Leave Plan For The Year {{ date('Y') }} </div>
                    <div class="page-title-subheading">Below is the leave plan you have submitted for the year {{date('Y')}}</div>
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
                            <a class="nav-link" href="{{url('/leave_plan_summary/normal')}}" target="_blank">
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
    <div class="row">
        <div class="col-md-12 col-lg-8 col-xl-8">
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

                    <!-- Plan Lines Start Here-->
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
                                <td>{{ $plan->id }}</td>
                                <td>{{ $leave_types[$plan->type_of_leave]['name']}}</td>
                                <td>{{ \App\MyFunctions::calculate_no_of_days_btn_dates($plan->starting_date,$plan->ending_date) }}</td>
                                <td>{{ date('d-m-Y', strtotime($plan->starting_date)) }}</td>
                                <td>{{ date('d-m-Y', strtotime($plan->ending_date))  }}</td>
                                <td>{{ $plan->status }}</td>
                            </tr>
                            <?php $n++;?>
                        @endforeach

                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                    @if($leave_plan->status == '0' || $leave_plan->status == '10')
                        <div class="text-center">
                            <a href="/leave_plan_submit/{{ $leave_plan->id }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true">Submit My Leave Plan</a>
                        </div>
                    @endif
                <!-- Plan Lines Ends Here-->


                </div>
            </div>
        </div>


        <div class="col-md-12 col-lg-4 col-xl-4">
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
                                <h5 class="menu-header-title">{{$leave_plan_statuses[$leave_plan->status]}}</h5>
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
                                                    @if($leave_plan->status == '0')
                                                        <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                        <div class="vertical-timeline-element-content bounce-in">
                                                            <h4 class="timeline-title text-danger">LEAVE PLAN RETURNED FOR CORRECTIONS</h4>
                                                            <p>
                                                                Hello, your Leave Plan have been returned for corrections by
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
                                                            <h4 class="timeline-title text-danger">LEAVE PLAN STILL IN DRAFTS</h4>
                                                            <p>
                                                                This Leave Plan is not yet submitted, it is still saved in Drafts.<br>
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
                                                            <h4 class="timeline-title text-danger">LEAVE PLAN SUBMITTED</h4>
                                                            <p>
                                                                Hello, your Leave Plan have been submitted successfully, please wait for Supervisor & HRM Approval.<br>
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
                                                                Hello, your Leave Plan have been approve by Supervisor, currently it is waiting for
                                                                Human Resource Manager Approval.<br>
                                                                - Time of Approving | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}
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
                                                                Hello, your Leave Plan have been approve by Human Resource Manager, currently it is waiting for
                                                                Managing Director's Approval.<br>
                                                                - Time of Approving | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}
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
                                                                Hello, your Leave Plan have been Approved.<br>
                                                                - Time of Approving | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}
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
                                                                Hello, this Leave Plan have been rejected by
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
