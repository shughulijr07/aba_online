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
                       Requisition Request Submitted By <span class="text-danger font-weight-bold">{{$employee_name}}</span>
                    </div>
                    <div class="page-title-subheading">Below is information for the requisition request</div>
                </div>
            </div>

        <!--actions' menu starts here -->
        <!--actions' menu ends here -->

        </div>
    </div>


    <!-- data 1 -->
    <div class="container" style="min-width: 100%">
        <div class="row">

            <!-- Travel Request Section -->
            <div class="col-md-12 col-lg-9 col-xl-9" id="travel_request_section">
                @include('requisition_requests.form')
            </div>

            <!-- Message & Actions Section -->
            <div class="col-md-12 col-lg-3 col-xl-3">
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
                            Requisition Request Status
                        </div>
                    </div>
                    <div class="p-0 card-body">
                        <div class="dropdown-menu-header mt-0 mb-0">
                            <div class="dropdown-menu-header-inner bg-heavy-rain">
                                <div class="menu-header-image opacity-1" style="background-image: url('assets/images/dropdown-header/city3.jpg');"></div>
                                <div class="menu-header-content text-dark">
                                    <h6 class="menu-header-title">{{$travel_request_statuses[$travel_request->status]}}</h6>
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
                                @if( ( $user_role == 1 && $travel_request->status <= 99 ) || ($user_role == 2 && $travel_request->status == 40 ) ||
                                     ( $user_role == 9 && $travel_request->status == 30 ) || ($user_role == 4 && $travel_request->status == 20 ) ||
                                     ( $user_role == 5 && $travel_request->status == 10 ) ||
                                     ( auth()->user()->staff->id == $travel_request->responsible_spv &&  $travel_request->status == 10      )
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
                                                        @if($travel_request->status == '0')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                            <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                        </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">RETURNED FOR CORRECTIONS</h4>
                                                                <p>
                                                                    This Requisition Request have been returned for corrections by
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
                                                                <h4 class="timeline-title text-danger">NEW REQUISITION REQUEST SUBMITTED</h4>
                                                                <p>
                                                                    This Requisition Request have been submitted by employee. Currently it is still waiting for Supervisor's Approval.<br>
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
                                                                <h4 class="timeline-title text-danger">REQUISITION REQUEST APPROVED BY SUPERVISOR</h4>
                                                                <p>
                                                                    This Requisition Request have been Approved by Supervisor, currently it is waiting for Accountant Approval.<br>
                                                                    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}
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
                                                                <h4 class="timeline-title text-danger">REQUISITION REQUEST APPROVED BY ACCOUNTANT</h4>
                                                                <p>
                                                                    This Requisition Request have been Approved by Accountant, currently it is waiting for
                                                                    Finance Director's Approval.<br>
                                                                    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}
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
                                                                <h4 class="timeline-title text-danger">REQUISITION REQUEST APPROVED BY FINANCE DIRECTOR</h4>
                                                                <p>
                                                                    This RequisitionT Request have been Approved by Finance Director, currently it is waiting for
                                                                    Managing Director's Approval.<br>
                                                                    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}
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
                                                                <h4 class="timeline-title text-danger">REQUISITION REQUEST APPROVED</h4>
                                                                <p>
                                                                    This Requisition Request have been Approved successfully.<br>
                                                                    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}
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
                                                                <h4 class="timeline-title text-danger">REQUISITION REQUEST REJECTED</h4>
                                                                <p>
                                                                    This Travel Request have been rejected by
                                                                    @if($travel_request->rejection->level == 'spv') Supervisor
                                                                    @elseif($travel_request->rejection->level == 'hrm') Human Resource Manager
                                                                    @elseif($travel_request->rejection->level == 'acc') Accountant
                                                                    @elseif($travel_request->rejection->level == 'fd') Finance Director
                                                                    @elseif($travel_request->rejection->level == 'md') Managing Director
                                                                    @else Administrator
                                                                    @endif
                                                                    .<br><br>
                                                                    - <span class="text-danger">Reason Of Rejection</span> <br>
                                                                    <span class="ml-2">{{$travel_request->rejection->reason}}</span> <br><br>
                                                                    - <span class="text-danger">Time of Rejection</span> <br>
                                                                    <span class="ml-2">{{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}</span>
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
                            @if( ( $user_role == 1 && $travel_request->status <= 99 ) || ($user_role == 2 && $travel_request->status == 40 ) ||
                                 ( $user_role == 9 && $travel_request->status == 30 ) || ($user_role == 4 && $travel_request->status == 20 ) ||
                                 ( $user_role == 5 && $travel_request->status == 10 ) ||
                                 ( auth()->user()->staff->id == $travel_request->responsible_spv &&  $travel_request->status == 10      )
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

                                                    <button class="mb-2 mr-2 btn-pill btn btn-outline-danger" id="reject_switch">Reject</button>
                                                </div>
                                            </div>
                                            <div class="p-3" id="approve_travel_request_div">
                                                <form action="/approve_requisition_request" method="POST" enctype="multipart/form-data" id="approve_travel_request_form">
                                                    @csrf
                                                    {{ csrf_field() }}

                                                    <fieldset>
                                                        <legend class="text-primary">Approve Requisition Request</legend>
                                                        <input name="travel_request_id" value="{{$travel_request->id}}" type="text" style="display: none;" readonly>
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

                                                    <button class="mt-2 btn btn-primary" id="approve_travel_request_btn">Approve Requisition Request</button>
                                                </form>
                                            </div>
                                            <div class="p-3" id="return_travel_request_div" style="display: none;">
                                                <form action="/return_requisition_request" method="POST" enctype="multipart/form-data" id="return_travel_request_form">
                                                    @csrf
                                                    {{ csrf_field() }}

                                                    <fieldset>
                                                        <legend class="text-primary">Return Travel Request For Correction</legend>
                                                        <input name="travel_request_id" value="{{$travel_request->id}}" type="text" style="display: none;" readonly>
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

                                                    <button class="mt-2 btn btn-primary" id="return_travel_request_btn">Return</button>
                                                </form>
                                            </div>

                                            @if($supervisors_mode == 2)
                                                <div class="p-3" id="change_spv_div" style="display: none;">
                                                    <form action="/change_supervisor" method="POST" enctype="multipart/form-data" id="change_spv_form">
                                                        @csrf
                                                        {{ csrf_field() }}

                                                        <fieldset>
                                                            <legend class="text-primary">Change Responsible Supervisor</legend>
                                                            <input name="leave_id" value="{{$travel_request->id}}" type="text" style="display: none;" readonly>
                                                            <div class="form-row mt-2">
                                                                <div class="col-md-12">
                                                                    <div class="position-relative form-group">
                                                                        <label for="responsible_spv" class="">
                                                                            <span>Supervisor</span>
                                                                        </label>
                                                                        <select name="responsible_spv" id="responsible_spv" class="form-control @error('country') is-invalid @enderror">
                                                                            <option value="">Select Supervisor</option>
                                                                            @foreach($supervisors as $supervisor)
                                                                                <option value="{{$supervisor->id}}" @if(($supervisor->id == old('responsible_spv')) || ($supervisor->id == $travel_request->responsible_spv)) selected @endif>
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

                                            <div class="p-3" id="reject_travel_request_div" style="display: none;">
                                                <form action="/reject_requisition_request" method="POST" enctype="multipart/form-data" id="reject_form">
                                                    @csrf
                                                    {{ csrf_field() }}

                                                    <fieldset>
                                                        <legend class="text-danger">Reject Travel Request Request</legend>
                                                        <input name="travel_request_id" value="{{$travel_request->id}}" type="text" style="display: none;" readonly>
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

                                                    <button class="mt-2 btn btn-danger" id="reject_travel_request_btn">Reject Travel Request</button>
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

            <!-- some useful values -->
            <div style="display: none;">
                <span id="staff_id">{{$travel_request->staff_id}}</span>
                <span id="travel_request_year">{{$travel_request->year}}</span>
                <span>{{ csrf_field() }}</span>
            </div>

        </div>
    </div>

    <script type="text/javascript">
        $(document).ready( function(){
            $('#notifications-div').delay(5000).fadeOut('slow');
        });

        $("#approve_switch").on("click",function(){
            $("#return_travel_request_div").hide();
            $("#change_spv_div").hide();
            $("#reject_travel_request_div").hide();
            $("#approve_travel_request_div").show("slow");
        });

        $("#return_switch").on("click",function(){
            $("#approve_travel_request_div").hide();
            $("#change_spv_div").hide();
            $("#reject_travel_request_div").hide();
            $("#return_travel_request_div").show("slow");
        });

        $("#change_spv_switch").on("click",function(){
            $("#return_travel_request_div").hide();
            $("#approve_travel_request_div").hide();
            $("#reject_travel_request_div").hide();
            $("#change_spv_div").show("slow");
        });

        $("#reject_switch").on("click",function(){
            $("#return_travel_request_div").hide();
            $("#change_spv_div").hide();
            $("#approve_travel_request_div").hide();
            $("#reject_travel_request_div").show("slow");
        });


    </script>

    <!--scripts -->
    <script type="text/javascript">


        /*********************** Leave && Travel Request Link Checking Script  ************************/

        $(function () {

            $("#travel_request_section  :input").prop("readonly",true);

        });



        /******************** alerts ********************/

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
