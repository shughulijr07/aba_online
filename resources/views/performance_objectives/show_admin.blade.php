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
    <div class="container" style="min-width: 100%">
        <div class="row">

            <!-- Performance Objective Section -->
            <div class="col-md-12 col-lg-9 col-xl-9" id="performance_objective_section">
                <fieldset disabled>
                @include('performance_objectives.form')
                </fieldset>
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
                            Objectives Status
                        </div>
                    </div>
                    <div class="p-0 card-body">
                        <div class="dropdown-menu-header mt-0 mb-0">
                            <div class="dropdown-menu-header-inner bg-heavy-rain">
                                <div class="menu-header-image opacity-1" style="background-image: url('assets/images/dropdown-header/city3.jpg');"></div>
                                <div class="menu-header-content text-dark">
                                    <h6 class="menu-header-title">{{$performance_objective_statuses[$performance_objective->status]}}</h6>
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
                                @if( ( $user_role == 1 && $performance_objective->status <=99) || ($user_role == 2 && $performance_objective->status ==40 ) ||
                                     ( $user_role == 3 && $performance_objective->status ==30) || ($user_role == 5 && $performance_objective->status ==20 ) ||
                                     ( auth()->user()->staff->id == $performance_objective->responsible_spv &&  $performance_objective->status ==20       )
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
                                                        @if($performance_objective->status == '0')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                            <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                        </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">RETURNED FOR CORRECTIONS</h4>
                                                                <p>
                                                                    Objectives have been returned for corrections by
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
                                                                <h4 class="timeline-title text-danger">OBJECTIVES SAVED IN DRAFTS</h4>
                                                                <p>
                                                                    Objectives are still in Drafts and have not been submitted by employee yet.<br>
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
                                                                <h4 class="timeline-title text-danger">NEW OBJECTIVES SUBMITTED</h4>
                                                                <p>
                                                                    Objectives have been submitted by employee. Currently it is still waiting for Supervisor's Approval.<br>
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
                                                                    Objectives have been Approved by Supervisor.<br>
                                                                    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($performance_objective->updated_at))}}
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
                                                                    Objectives have been Approved by Human Resource Manager.<br>
                                                                    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($performance_objective->updated_at))}}
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
                                                                    Objectives have been Approved successfully.<br>
                                                                    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($performance_objective->updated_at))}}
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
                                                                    Objectives have been rejected by
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
                            @if( ( $user_role == 1 && $performance_objective->status <=99) || ($user_role == 2 && $performance_objective->status ==40 ) ||
                                 ( $user_role == 3 && $performance_objective->status ==30) || ($user_role == 5 && $performance_objective->status ==20 ) ||
                                 ( auth()->user()->staff->id == $performance_objective->responsible_spv &&  $performance_objective->status ==20       )
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
                                            <div class="p-3" id="approve_objectives_div">
                                                <form action="/approve_objectives" method="POST" enctype="multipart/form-data" id="approve_objectives_form">
                                                    @csrf
                                                    {{ csrf_field() }}

                                                    <fieldset>
                                                        <legend class="text-primary">Approve Performance Objective</legend>
                                                        <input name="performance_objective_id" value="{{$performance_objective->id}}" type="text" style="display: none;" readonly>
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

                                                    <button class="mt-2 btn btn-primary" id="approve_objectives_btn">Approve Performance Objective</button>
                                                </form>
                                            </div>
                                            <div class="p-3" id="return_objectives_div" style="display: none;">
                                                <form action="/return_objectives" method="POST" enctype="multipart/form-data" id="return_objectives_form">
                                                    @csrf
                                                    {{ csrf_field() }}

                                                    <fieldset>
                                                        <legend class="text-primary">Return Performance Objective For Correction</legend>
                                                        <input name="performance_objective_id" value="{{$performance_objective->id}}" type="text" style="display: none;" readonly>
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

                                                    <button class="mt-2 btn btn-primary" id="return_objectives_btn">Return</button>
                                                </form>
                                            </div>
                                            <div class="p-3" id="change_spv_div" style="display: none;">
                                                <form action="/change_objectives_spv" method="POST" enctype="multipart/form-data" id="change_spv_form">
                                                    @csrf
                                                    {{ csrf_field() }}

                                                    <fieldset>
                                                        <legend class="text-primary">Change Responsible Supervisor</legend>
                                                        <input name="performance_objective_id" value="{{$performance_objective->id}}" type="text" style="display: none;" readonly>
                                                        <div class="form-row mt-2">
                                                            <div class="col-md-12">
                                                                <div class="position-relative form-group">
                                                                    <label for="responsible_spv" class="">
                                                                        <span>Supervisor</span>
                                                                    </label>
                                                                    <select name="responsible_spv" id="responsible_spv" class="form-control @error('country') is-invalid @enderror">
                                                                        <option value="">Select Supervisor</option>
                                                                        @foreach($supervisors as $supervisor)
                                                                            <option value="{{$supervisor->id}}" @if(($supervisor->id == old('responsible_spv')) || ($supervisor->id == $performance_objective->responsible_spv)) selected @endif>
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
                                            <div class="p-3" id="reject_performance_objective_div" style="display: none;">
                                                <form action="/reject_objectives" method="POST" enctype="multipart/form-data" id="reject_form">
                                                    @csrf
                                                    {{ csrf_field() }}

                                                    <fieldset>
                                                        <legend class="text-danger">Reject Performance Objective Request</legend>
                                                        <input name="performance_objective_id" value="{{$performance_objective->id}}" type="text" style="display: none;" readonly>
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

                                                    <button class="mt-2 btn btn-danger" id="reject_performance_objective_btn">Reject Performance Objective</button>
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
                <span id="staff_id">{{$performance_objective->staff_id}}</span>
                <span id="objectives_year">{{$performance_objective->year}}</span>
                <span id="objectives_month">{{$performance_objective->month}}</span>
                <span>{{ csrf_field() }}</span>
            </div>

        </div>
    </div>

    <script type="text/javascript">
        $(document).ready( function(){
            $('#notifications-div').delay(5000).fadeOut('slow');
        });

        $("#approve_switch").on("click",function(){
            $("#return_objectives_div").hide();
            $("#change_spv_div").hide();
            $("#reject_performance_objective_div").hide();
            $("#approve_objectives_div").show("slow");
        });

        $("#return_switch").on("click",function(){
            $("#approve_objectives_div").hide();
            $("#change_spv_div").hide();
            $("#reject_performance_objective_div").hide();
            $("#return_objectives_div").show("slow");
        });

        $("#change_spv_switch").on("click",function(){
            $("#return_objectives_div").hide();
            $("#approve_objectives_div").hide();
            $("#reject_performance_objective_div").hide();
            $("#change_spv_div").show("slow");
        });

        $("#reject_switch").on("click",function(){
            $("#return_objectives_div").hide();
            $("#change_spv_div").hide();
            $("#approve_objectives_div").hide();
            $("#reject_performance_objective_div").show("slow");
        });


    </script>

    <!--scripts -->
    <script type="text/javascript">


        /*********************** Leave && Performance Objective Link Checking Script  ************************/

        $(function () {

            $("#performance_objective_section  :input").prop("readonly",true);

        });


        //when input in vacation, sick or other is clicked
        $("input").on('click',function(){

            var row = $(this).parent().parent();
            var row_type = row.attr('class');

            if( row_type == 'vacation-row bg-light-dark' ||
                row_type == 'sick-row' ||
                row_type == 'holiday-row bg-light-dark' ||
                row_type == 'other-row'){

                var input_hrs = $(this).val();

                if(input_hrs != ''){
                    var year = $('#objectives_year').text();
                    var month = $('#objectives_month').text();
                    var staff_id = $('#staff_id').text();
                    var _token = $('input[name="_token"]').val();

                    var date_info = $(this).attr('id');
                        date_info = date_info.split('--');
                    var selected_date = date_info[2];

                    var leave_date = selected_date + '-' + month + '-' + year;

                    //alert( leave_date );

                    //send ajax request
                    check_if_date_exist_in_staff_leaves(staff_id,year,leave_date,_token);
                }

            }

        });




        function check_if_date_exist_in_staff_leaves(staff_id,year,leave_date,_token)
        {


            $.ajax({
                url:"{{ route('leaves.ajax_check_date') }}",
                method:"POST",
                data:{staff_id:staff_id,year:year,leave_date:leave_date,_token:_token},
                success:function(data)
                {
                    //add the list to selection option
                    //console.log(data);
                    leave_id = JSON.parse(data);

                    if(leave_id>0){
                        //window.location = "leave/"+leave_id;
                        window.open("leave_admin/"+leave_id, '_blank');
                    }else{
                        sweet_alert_error("This Date was not found in any of Employee's Approved Leaves");
                    }

                }
            })


        }



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
