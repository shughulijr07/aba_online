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
                        <span class="text-danger font-weight-bold">{{$employee_name}}</span>'s Time Sheet For The Month Of
                        <span class="text-danger font-weight-bold">{{ $months[$time_sheet->month] }}</span>
                    </div>
                    <div class="page-title-subheading">Below is information for the timesheet</div>
                </div>
            </div>


            <!--actions' menu starts here -->
            @if($time_sheet->status == 50)
                <div class="page-title-actions">
                    <a href="/time_sheet_statement/{{ $time_sheet->id }}" target="_blank" type="button" data-toggle="tooltip" title="Print" data-placement="bottom" class="btn-shadow mr-3 btn btn-danger">
                        <i class="lnr-printer"></i>
                    </a>
                </div>
        @endif
        <!--actions' menu ends here -->

        </div>
    </div>


    <!-- data 1 -->
    <div class="container" style="min-width: 100%">
        <div class="row">

            <!-- Time Sheet Section -->
            <div class="col-md-12 col-lg-9 col-xl-9" id="time_sheet_section">
                @include('time_sheets.time_sheet_form')
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
                            Time Sheet Status
                        </div>
                    </div>
                    <div class="p-0 card-body">
                        <div class="dropdown-menu-header mt-0 mb-0">
                            <div class="dropdown-menu-header-inner bg-heavy-rain">
                                <div class="menu-header-image opacity-1" style="background-image: url('assets/images/dropdown-header/city3.jpg');"></div>
                                <div class="menu-header-content text-dark">
                                    <h6 class="menu-header-title">{{$time_sheet_statuses[$time_sheet->status]}}</h6>
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
                            <li class="nav-item">
                                <a role="tab" class="nav-link show" id="tab-c-2" data-toggle="tab" href="#tab-animated-2" aria-selected="true">
                                    <span>Summary</span>
                                </a>
                            </li>
                            @can('view-menu','leave_requests_for_approving_menu_item')
                                @if( ( $user_role == 1 && $time_sheet->status <=99) || ($user_role == 2 && $time_sheet->status ==40 ) ||
                                     ( $user_role == 3 && $time_sheet->status ==30) || ($user_role == 5 && $time_sheet->status ==20 ) ||
                                     ( auth()->user()->staff->id == $time_sheet->responsible_spv &&  $time_sheet->status ==20       )
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
                                                        @if($time_sheet->status == '0')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                            <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                        </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">RETURNED FOR CORRECTIONS</h4>
                                                                <p>
                                                                    This Time Sheet have been returned for corrections by
                                                                    @if($time_sheet->returns->last()->level == 'spv') Supervisor
                                                                    @elseif($time_sheet->returns->last()->level == 'hrm') Human Resource Manager
                                                                    @elseif($time_sheet->returns->last()->level == 'md')  Managing Director
                                                                    @else Administrator
                                                                    @endif
                                                                    .<br><br>
                                                                    - <span class="text-danger">Reason & Correction Instruction</span> <br>
                                                                    <span class="ml-2">{{$time_sheet->returns->last()->comments}}</span> <br><br>
                                                                    - <span class="text-danger">Time of Returning</span> <br>
                                                                    <span class="ml-2">{{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}</span>
                                                                </p>

                                                                <p>
                                                                    <br><br>
                                                                    <span class="text-danger">For Assistance</span><br>
                                                                    - Please contact system Administrator or Human Resource Manager
                                                                </p>
                                                            </div>
                                                        @endif
                                                        @if($time_sheet->status == '10')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                            <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                        </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">TIME SHEET SAVED IN DRAFTS</h4>
                                                                <p>
                                                                    This Time Sheet is still in Drafts and have not been submitted by employee yet.<br>
                                                                    - Time of Saving | {{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}
                                                                </p>

                                                                <p>
                                                                    <br><br>
                                                                    <span class="text-danger">For Assistance</span><br>
                                                                    - Please contact system Administrator or Human Resource Manager
                                                                </p>
                                                            </div>
                                                        @endif
                                                        @if($time_sheet->status == '20')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                            <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                        </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">NEW TIME SHEET SUBMITTED</h4>
                                                                <p>
                                                                    This Time Sheet have been submitted by employee. Currently it is still waiting for Supervisor's Approval.<br>
                                                                    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}
                                                                </p>

                                                                <p>
                                                                    <br><br>
                                                                    <span class="text-danger">For Assistance</span><br>
                                                                    - Please contact system Administrator or Human Resource Manager
                                                                </p>
                                                            </div>
                                                        @endif

                                                        @if($time_sheet->status == '30')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">TIME SHEET APPROVED BY SUPERVISOR</h4>
                                                                <p>
                                                                    This Time Sheet have been Approved by Supervisor, currently it is waiting for Human Resource Manager
                                                                    & Managing Director's Approval.<br>
                                                                    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}
                                                                </p>

                                                                <p>
                                                                    <br><br>
                                                                    <span class="text-danger">For Assistance</span><br>
                                                                    - Please contact system Administrator or Human Resource Manager
                                                                </p>
                                                            </div>
                                                        @endif


                                                        @if($time_sheet->status == '40')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">TIME SHEET APPROVED BY HUMAN RESOURCE MANAGER</h4>
                                                                <p>
                                                                    This Time Sheet have been Approved by Human Resource Manager, currently it is waiting for
                                                                    Managing Director's Approval.<br>
                                                                    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}
                                                                </p>

                                                                <p>
                                                                    <br><br>
                                                                    <span class="text-danger">For Assistance</span><br>
                                                                    - Please contact system Administrator or Human Resource Manager
                                                                </p>
                                                            </div>
                                                        @endif



                                                        @if($time_sheet->status == '50')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">TIME SHEET APPROVED</h4>
                                                                <p>
                                                                    This Time Sheet have been Approved successfully.<br>
                                                                    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}
                                                                </p>

                                                                <p>
                                                                    <br><br>
                                                                    <span class="text-danger">For Assistance</span><br>
                                                                    - Please contact system Administrator or Human Resource Manager
                                                                </p>
                                                            </div>
                                                        @endif



                                                        @if($time_sheet->status == '99')
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">TIME SHEET REJECTED</h4>
                                                                <p>
                                                                    This Time Sheet have been rejected by
                                                                    @if($time_sheet->rejection->level == 'spv') Supervisor
                                                                    @elseif($time_sheet->rejection->level == 'hrm') Human Resource Manager
                                                                    @elseif($time_sheet->rejection->level == 'md')  Managing Director
                                                                    @else Administrator
                                                                    @endif
                                                                    .<br><br>
                                                                    - <span class="text-danger">Reason Of Rejection</span> <br>
                                                                    <span class="ml-2">{{$time_sheet->rejection->reason}}</span> <br><br>
                                                                    - <span class="text-danger">Time of Rejection</span> <br>
                                                                    <span class="ml-2">{{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}</span>
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

                            <!-- summary content starts here -->
                            <div class="tab-pane show" id="tab-animated-2" role="tabpanel">
                                <div class="">
                                    <div class="">
                                        <div class="p-3">
                                            <div class="vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                                <div class="vertical-timeline-item vertical-timeline-element invisible">
                                                    <div>
                                                        <span class="vertical-timeline-element-icon bounce-in">
                                                            <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                        </span>
                                                        <div class="vertical-timeline-element-content bounce-in">
                                                            <h4 class="timeline-title text-danger">TIME SHEET SUMMARY</h4>
                                                            <p>
                                                                <br>
                                                                <span class="text-danger">For Assistance</span><br>
                                                                - Please contact system Administrator or Human Resource Manager
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="vertical-timeline-item vertical-timeline-element">
                                                    <div>
                                                        <span class="vertical-timeline-element-icon bounce-in">
                                                            <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                        </span>
                                                        <div class="vertical-timeline-element-content bounce-in">
                                                            <h4 class="timeline-title text-danger">LOE For Projects</h4>
                                                            <table class="table table-sm table-bordered">
                                                                <thead>
                                                                <tr class="bg-primary text-white">
                                                                    <th class="">Project</th>
                                                                    <th class="text-right">Hrs</th>
                                                                    <th class="text-right"> % </th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php $n = 1; ?>
@foreach($projects as $client_data2)
    <tr>
        <td >{{$client_data2->name}}</td>
        <td class="no-wrap text-right" id="hrs--project--{{$client_data2->number}}">0</td>
        <td class="no-wrap text-right text-danger" id="percentage--project--{{$client_data2->number}}">0.0</td>
    </tr>
@endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- summary content ends here -->

                            <!-- actions starts here -->
                            @if( ( $user_role == 1 && $time_sheet->status <=99) || ($user_role == 2 && $time_sheet->status ==40 ) ||
                                 ( $user_role == 3 && $time_sheet->status ==30) || ($user_role == 5 && $time_sheet->status ==20 ) ||
                                 ( auth()->user()->staff->id == $time_sheet->responsible_spv &&  $time_sheet->status ==20       )
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
                                            <div class="p-3" id="approve_timesheet_div">
                                                <form action="/approve_timesheet" method="POST" enctype="multipart/form-data" id="approve_timesheet_form">
                                                    @csrf
                                                    {{ csrf_field() }}

                                                    <fieldset>
                                                        <legend class="text-primary">Approve Time Sheet</legend>
                                                        <input name="time_sheet_id" value="{{$time_sheet->id}}" type="text" style="display: none;" readonly>
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

                                                    <button class="mt-2 btn btn-primary" id="approve_timesheet_btn">Approve Time Sheet</button>
                                                </form>
                                            </div>
                                            <div class="p-3" id="return_timesheet_div" style="display: none;">
                                                <form action="/return_timesheet" method="POST" enctype="multipart/form-data" id="return_timesheet_form">
                                                    @csrf
                                                    {{ csrf_field() }}

                                                    <fieldset>
                                                        <legend class="text-primary">Return Time Sheet For Correction</legend>
                                                        <input name="time_sheet_id" value="{{$time_sheet->id}}" type="text" style="display: none;" readonly>
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

                                                    <button class="mt-2 btn btn-primary" id="return_timesheet_btn">Return</button>
                                                </form>
                                            </div>
                                            <div class="p-3" id="change_spv_div" style="display: none;">
                                                <form action="/change_timesheet_spv" method="POST" enctype="multipart/form-data" id="change_spv_form">
                                                    @csrf
                                                    {{ csrf_field() }}

                                                    <fieldset>
                                                        <legend class="text-primary">Change Responsible Supervisor</legend>
                                                        <input name="time_sheet_id" value="{{$time_sheet->id}}" type="text" style="display: none;" readonly>
                                                        <div class="form-row mt-2">
                                                            <div class="col-md-12">
                                                                <div class="position-relative form-group">
                                                                    <label for="responsible_spv" class="">
                                                                        <span>Supervisor</span>
                                                                    </label>
                                                                    <select name="responsible_spv" id="responsible_spv" class="form-control @error('country') is-invalid @enderror">
                                                                        <option value="">Select Supervisor</option>
                                                                        @foreach($timeSheetSupervisors as $supervisor)
                                                                            <option value="{{$supervisor->id}}" @if(($supervisor->id == old('responsible_spv')) || ($supervisor->id == $time_sheet->responsible_spv)) selected @endif>
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
                                            <div class="p-3" id="reject_time_sheet_div" style="display: none;">
                                                <form action="/reject_timesheet" method="POST" enctype="multipart/form-data" id="reject_form">
                                                    @csrf
                                                    {{ csrf_field() }}

                                                    <fieldset>
                                                        <legend class="text-danger">Reject Time Sheet Request</legend>
                                                        <input name="time_sheet_id" value="{{$time_sheet->id}}" type="text" style="display: none;" readonly>
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

                                                    <button class="mt-2 btn btn-danger" id="reject_time_sheet_btn">Reject Time Sheet</button>
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
                <span id="staff_id">{{$time_sheet->staff_id}}</span>
                <span id="timesheet_year">{{$time_sheet->year}}</span>
                <span id="timesheet_month">{{$time_sheet->month}}</span>
                <span>{{ csrf_field() }}</span>
            </div>

        </div>
    </div>

    <script type="text/javascript">
        $(document).ready( function(){
            $('#notifications-div').delay(5000).fadeOut('slow');

            calculate_project_hrs_percentage1();
        });

        $("#approve_switch").on("click",function(){
            $("#return_timesheet_div").hide();
            $("#change_spv_div").hide();
            $("#reject_time_sheet_div").hide();
            $("#approve_timesheet_div").show("slow");
        });

        $("#return_switch").on("click",function(){
            $("#approve_timesheet_div").hide();
            $("#change_spv_div").hide();
            $("#reject_time_sheet_div").hide();
            $("#return_timesheet_div").show("slow");
        });

        $("#change_spv_switch").on("click",function(){
            $("#return_timesheet_div").hide();
            $("#approve_timesheet_div").hide();
            $("#reject_time_sheet_div").hide();
            $("#change_spv_div").show("slow");
        });

        $("#reject_switch").on("click",function(){
            $("#return_timesheet_div").hide();
            $("#change_spv_div").hide();
            $("#approve_timesheet_div").hide();
            $("#reject_time_sheet_div").show("slow");
        });


    </script>

    <!--scripts -->
    <script type="text/javascript">


        /*********************** Leave && Time Sheet Link Checking Script  ************************/

        $(function () {

            $("#time_sheet_section  :input").prop("readonly",true);

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
                    var year = $('#timesheet_year').text();
                    var month = $('#timesheet_month').text();
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
