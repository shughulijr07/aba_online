<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('js/report.js') }}" defer></script>

    <!-- Fonts -->

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">

    <!-- Styles -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/time_sheet_statements1.css') }}" rel="stylesheet">
</head>

<body>


<!-- LOE Detailed-->
<?php $n=1; ?>
@foreach($statements as $statement)
    <?php //if($n==2){break;} ?>
    <?php

    $time_sheet = $statement['time_sheet'];
    $responsible_spv = $statement['responsible_spv'];
    $spv_name = $statement['spv_name'];
    $employee_name = $statement['employee_name'];
    $time_sheet_lines = $statement['time_sheet_lines'];
    $holidays = $statement['holidays'];
    $user_role = $statement['user_role'];
    $leave_timesheet_link_mode = $statement['leave_timesheet_link_mode'];
    $staff_annual_leave_dates = $statement['staff_annual_leave_dates'];
    $staff_sick_leave_dates = $statement['staff_sick_leave_dates'];
    $staff_maternity_leave_dates = $statement['staff_maternity_leave_dates'];
    $staff_paternity_leave_dates = $statement['staff_paternity_leave_dates'];
    $staff_compassionate_leave_dates = $statement['staff_compassionate_leave_dates'];
    $staff_other_off_dates = $statement['staff_other_off_dates'];
    $supervisors_mode = $statement['supervisors_mode'];
    $days_in_month = $statement['days_in_month'];
    $projects = $statement['projects'];
    $rejection_reason = $statement['rejection_reason'];
    $time_sheet_modification_reason = $statement['time_sheet_modification_reason'];
    $supervisor_change_reason = $statement['supervisor_change_reason'];
    $comments = $statement['comments'];
    $generated_by = $statement['generated_by'];
    $generation_date = $statement['generation_date'];
    $md = $statement['md'];
    $md_approval = $statement['md_approval'];
    $spv_approval = $statement['spv_approval'];
    $spv = $statement['spv'];
    $hrm_approval = $statement['hrm_approval'];
    $hrm_approval_date = $statement['hrm_approval_date'];
    $hrm = $statement['hrm'];
    ?>

    <div  class="statements ml-4 mr-4" style="display: none;">
        <div class="toolbar hidden-print invisible">
            <div class="text-right">
                <button id="printReport" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
                <button class="btn btn-info"><i class="fa fa-file-pdf-o"></i> Export as PDF</button>
            </div>
            <hr>
        </div>
        <div class="report overflow-auto pt-0" style="min-width: 18cm; min-height: 29.7cm; margin-left: 1cm; padding-top: 0.7cm;">
            <div style="min-width: 600px">
                <header>
                    <div class="row pb-2">
                        <div class="col text-center" >
                            <a target="_blank" href="https://lobianijs.com">
                                <img  src="/images/tmarc_logo.png" data-holder-rendered="true" style="max-height: 150px; width: auto;"/>
                            </a>
                        </div>
                        <div class="col company-details" style="display: none">
                            <div>Plot No. 215/217 Block D</div>
                            <div>Kuringa Drive, Tegeta</div>
                            <div>P.O.Box 63266</div>
                            <div>Dar es Salaam, Tanzania</div>
                            <div>Tel : +255 22 2780870</div>
                            <div>Fax : +255 22 2781296</div>
                            <div>Email: info@tmarc.or.tz</div>
                        </div>
                    </div>
                </header>
                <main>

                    <div class="row pl-3 pr-3" >
                        <div class="col text-center pt-3 pb-2 " style="background-color: #ddd;">
                            <h3>EMPLOYEE TIME SHEET</h3>
                        </div>
                    </div>

                    <div class="row contacts mt-2" style="display: none;">
                        <div class="col report-to">
                            <div class="address">Leave No. : <strong>{{strtoupper($time_sheet->id)}}</strong></div>
                            <div class="staff-id" id="staff-{{$n}}">{{$time_sheet->staff->id}}</div>
                            <div>Employee Name : <strong>{{ucwords($time_sheet->staff->first_name.' '.$time_sheet->staff->last_name)}}</strong></div>
                            <div class="date">Department : <strong>{{ucwords($time_sheet->staff->department->name)}}</strong></div>
                            <div class="date">Designation : <strong>{{ucwords($time_sheet->staff->jobTitle->title)}}</strong></div>
                        </div>
                        <div class="col report-details">
                            <div class="date">Generated By : <strong>{{$generated_by}}</strong></div>
                            <div class="date">Generation Date : <strong>{{$generation_date}}</strong></div>
                        </div>
                    </div>

                    <!-- Time Sheet Form -->
                    <div class="row">
                        <div class="col-md-12 mt-3">

                            <!-- Timesheet  -->
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <h5 class="card-title text-danger" style="display: none;">Time Sheet Form</h5>

                                    <!-- Header -->
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <fieldset>
                                                <legend class="text-danger"></legend>
                                                <div class="form-row"  style="display: none;">
                                                    <div class="col-3">
                                                        <div class="position-relative form-group">
                                                            <label for="time_sheet_id" class="">
                                                                <span>Time Sheet ID</span>
                                                            </label>
                                                            <input name="time_sheet_id" id="time_sheet_id" type="text" class="form-control" value="{{$time_sheet->id}}" disabled>
                                                            <input name="action" id="action" type="text" class="form-control" value="save-to-draft" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="col-3">
                                                        <div class="position-relative form-group">
                                                            <label for="employee_name" class="">
                                                                <span>Employee Name</span>
                                                            </label>
                                                            <input name="employee_name" id="employee_name" type="text" class="form-control" value="{{$employee_name}}" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="position-relative form-group">
                                                            <label for="responsible_spv" class="">
                                                                <span>Supervisor</span>
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                            <select name="responsible_spv" id="responsible_spv" class="form-control @error('country') is-invalid @enderror" @if($supervisors_mode == '1') disabled @endif>
                                                                @if($supervisors_mode == '2')<option value="">Select Supervisor</option>@endif
                                                                @foreach($timeSheetSupervisors as $timeSheetSupervisor)
                                                                    <option value="{{$timeSheetSupervisor->id}}" @if(($timeSheetSupervisor->id == old('responsible_spv')) || ($timeSheetSupervisor->id == $responsible_spv)) selected @endif>
                                                                        {{ucwords($timeSheetSupervisor->first_name.' '.$timeSheetSupervisor->last_name)}}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="position-relative form-group">
                                                            <label for="year" class="">
                                                                <span>Year</span>
                                                            </label>
                                                            <input name="year" id="year" type="text" class="form-control" value="{{$time_sheet->year}}" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="position-relative form-group">
                                                            <label for="month" class="">
                                                                <span>Month</span>
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                            <select name="month" id="month" class="form-control @error('month') is-invalid @enderror" disabled>
                                                                @foreach($months as $value => $month_name)
                                                                    @if($value == $time_sheet->month) <option value="{{$value}}" selected>{{$month_name}}</option> @endif
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <!-- Header Ends Here-->

                                    <!-- Lines -->
                                    <div class="row">

                                        <!-- Left Part -->
                                        <div class="col-2 mr-0 pr-0">
                                            <div class="table-responsive">
                                                <table style="width: 100%; !important;"  class="table table-hover table-striped table-bordered ">
                                                    <thead>
                                                    <tr >
                                                        <th class="no-wrap ctitle" colspan="2" style="border-color: #ffffff !important;"></th>
                                                    </tr>
                                                    <tr class="bg-primary text-white">
                                                        <th class="no-wrap ctitle">Project Name</th>
                                                        <th class="no-wrap">Total Hrs</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($projects as $project_number=>$project_name)
                                                        <tr>
                                                            <td class="no-wrap ctitle">{{$project_name}}</td>
                                                            <td class="no-wrap total-one-project{{$n}}" id="total--project{{$n}}--{{$project_number}}">0</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                    <tr class="bg-danger text-white">
                                                        <th class="no-wrap ctitle">Total Project Hours</td>
                                                        <th class="no-wrap" id="total_project_hrs{{$n}}">0</th>
                                                    </tr>


                                                    <tr style="border: none;">
                                                        <td  style="border: none;"></td>
                                                        <th  style="border: none;"></th>
                                                    </tr>


                                                    <tr class="bg-light-dark">
                                                        <td class="no-wrap ctitle">Vacation</td>
                                                        <td class="no-wrap total-one-vacation" id="total--vacation{{$n}}--1">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-wrap ctitle">Sick</td>
                                                        <td class="no-wrap total-one-sick" id="total--sick{{$n}}--1">0</td>
                                                    </tr>
                                                    <tr class="bg-light-dark">
                                                        <td class="no-wrap ctitle">Holiday</td>
                                                        <td class="no-wrap total-one-holiday" id="total--holiday{{$n}}--1">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-wrap ctitle">Other</td>
                                                        <td class="no-wrap total-one-other" id="total--other{{$n}}--1">0</td>
                                                    </tr>
                                                    <tr class=" bg-danger text-white">
                                                        <th class="no-wrap ctitle">Grand Total Hours</th>
                                                        <th class="no-wrap" id="grand_total_hrs{{$n}}">0</th>
                                                    </tr>
                                                    </tfoot>

                                                </table>
                                            </div>
                                        </div>
                                        <!-- Left Part Ends Here -->

                                        <!-- Right Part -->
                                        <div class="col-10">
                                            <div class="table-responsive">
                                                <table style="width: 100%; !important;"  class="table table-hover table-striped table-bordered ">

                                                    <!-- Table Head Starts Here -->
                                                    <thead>
                                                    <tr  class="bg-danger text-white">
                                                        @for( $d=1 ; $d<=$days_in_month; $d++)
                                                            <?php
                                                            $day_name = date('D',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                                            $full_date = date('d-m-Y',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                                            ?>
                                                            <th class="timesheet-date @if($day_name == 'Sat' || $day_name == 'Sun') bg-weekend  @endif   @if( in_array($full_date,array_keys($holidays))) bg-holiday @endif" >{{$day_name}}</th>
                                                        @endfor
                                                    </tr>
                                                    <tr  class="bg-primary text-white">
                                                        @for( $d=1 ; $d<=$days_in_month; $d++)
                                                            <?php
                                                            $day_name = date('D',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                                            $full_date = date('d-m-Y',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                                            ?>
                                                            <th class="timesheet-date @if($day_name == 'Sat' || $day_name == 'Sun') bg-weekend @endif   @if( in_array($full_date,array_keys($holidays))) bg-holiday @endif">{{$d}}</th>
                                                        @endfor
                                                    </tr>
                                                    </thead>
                                                    <!-- Table Head Ends Here -->


                                                    <!-- Table Body Starts Here, All Projects Entries Are Found Here -->
                                                    <tbody>
                                                    @foreach($projects as $project_number=>$project_name)
                                                        <tr class="project-row" id="project{{$n}}--{{$project_number}}">
                                                            @for( $d=1 ; $d<=$days_in_month; $d++)
                                                                <?php
                                                                $day_name = date('D',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                                                $full_date = date('d-m-Y',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                                                ?>
                                                                <td class="project-column project{{$n}}--{{$project_number}} date--{{$d}}  @if($day_name == 'Sat' || $day_name == 'Sun') bg-weekend @endif   @if( in_array($full_date,array_keys($holidays))) bg-holiday @endif">
                                                                    <input
                                                                            class="special-column-input column-input{{$n}} column-day{{$n}}-{{$d}} project{{$n}}--{{$d}}"
                                                                            name="project{{$n}}--{{$project_number}}--{{$d}}"
                                                                            id="project{{$n}}--{{$project_number}}--{{$d}}"
                                                                            value="@if( in_array( 'project--'.$project_number.'--'.$d ,array_keys($time_sheet_lines))){{ $time_sheet_lines['project--'.$project_number.'--'.$d]}}@endif"
                                                                            autocomplete="off"
                                                                    >
                                                                </td>
                                                            @endfor
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                    <!-- Table Body Ends Here -->


                                                    <!-- Table Foot Starts Here -->
                                                    <tfoot>

                                                    <!-- Daily Total For All Projects (Each column contains total for all hours worked for each project in a day)-->
                                                    <tr class="total-project-hrs-row bg-danger text-white">
                                                        @for( $d=1 ; $d<=$days_in_month; $d++)
                                                            <?php
                                                            $day_name = date('D',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                                            $full_date = date('d-m-Y',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                                            ?>
                                                            <th class="timesheet-date  @if($day_name == 'Sat' || $day_name == 'Sun') bg-weekend @endif   @if( in_array($full_date,array_keys($holidays))) bg-holiday @endif" id="projects{{$n}}--day--total--{{$d}}">0</th>
                                                        @endfor
                                                    </tr>
                                                    <!-- Daily Total For All Projects Ends Here -->



                                                    <!-- Gape Starts Here -->
                                                    <tr class="gape" style="border: none;">
                                                        @for( $d=1 ; $d<=$days_in_month; $d++)
                                                            <td  style="border: none;"></td>
                                                        @endfor
                                                    </tr>
                                                    <!-- Gape Ends Here -->



                                                    <!-- Vacation Starts Here -->
                                                    <tr class="vacation-row bg-light-dark" id="project--1">
                                                        @for( $d=1 ; $d<=$days_in_month; $d++)
                                                            <?php
                                                            $day_name = date('D',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                                            $full_date = date('d-m-Y',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                                            ?>
                                                            <td class="vacation-column vacation--1 date--{{$d}}  @if($day_name == 'Sat' || $day_name == 'Sun') bg-weekend @endif   @if( in_array($full_date,array_keys($holidays))) bg-holiday @endif">
                                                                <input
                                                                        class="special-column-input column-input{{$n}} column-day{{$n}}-{{$d}} vacation--{{$d}}"
                                                                        name="vacation{{$n}}--1--{{$d}}" id="vacation{{$n}}--1--{{$d}}"

                                                                        @if($time_sheet->status == 10 && $leave_timesheet_link_mode == 2 &&
                                                                           ( in_array($full_date,$staff_annual_leave_dates) ||
                                                                             in_array($full_date,$staff_paternity_leave_dates) ||
                                                                             in_array($full_date,$staff_maternity_leave_dates) )
                                                                        )
                                                                        value="24"
                                                                        @else
                                                                        value="@if( in_array( 'vacation--1--'.$d ,array_keys($time_sheet_lines))){{ $time_sheet_lines['vacation--1--'.$d]}}@endif"
                                                                        @endif
                                                                        autocomplete="off"
                                                                >
                                                            </td>
                                                        @endfor
                                                    </tr>
                                                    <!-- Vacation Ends Here -->


                                                    <!-- Sick Starts Here -->
                                                    <tr class="sick-row">
                                                        @for( $d=1 ; $d<=$days_in_month; $d++)
                                                            <?php
                                                            $day_name = date('D',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                                            $full_date = date('d-m-Y',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                                            ?>
                                                            <td class="sick-column sick--1 date--{{$d}}  @if($day_name == 'Sat' || $day_name == 'Sun') bg-weekend @endif   @if( in_array($full_date,array_keys($holidays))) bg-holiday @endif">
                                                                <input
                                                                        class="special-column-input column-input{{$n}} column-day{{$n}}-{{$d}} sick--{{$d}}"
                                                                        name="sick{{$n}}--1--{{$d}}" id="sick{{$n}}--1--{{$d}}"
                                                                        value="@if( in_array( 'sick--1--'.$d ,array_keys($time_sheet_lines))){{ $time_sheet_lines['sick--1--'.$d]}}@endif"
                                                                        autocomplete="off"
                                                                >
                                                            </td>
                                                        @endfor
                                                    </tr>
                                                    <!-- Sick Ends Here -->



                                                    <!-- Holiday Starts Here -->
                                                    <tr class="holiday-row bg-light-dark">
                                                        @for( $d=1 ; $d<=$days_in_month; $d++)
                                                            <?php
                                                            $day_name = date('D',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                                            $full_date = date('d-m-Y',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                                            ?>
                                                            <td class="holiday-column holiday--1 date--{{$d}}  @if($day_name == 'Sat' || $day_name == 'Sun') bg-weekend @endif   @if( in_array($full_date,array_keys($holidays))) bg-holiday @endif">
                                                                <input
                                                                        class="special-column-input column-input{{$n}} column-day{{$n}}-{{$d}} holiday--{{$d}}"
                                                                        name="holiday{{$n}}--1--{{$d}}" id="holiday{{$n}}--1--{{$d}}"
                                                                        value="@if( in_array( 'holiday--1--'.$d ,array_keys($time_sheet_lines))){{ $time_sheet_lines['holiday--1--'.$d]}}@endif"
                                                                        autocomplete="off"
                                                                >
                                                            </td>
                                                        @endfor
                                                    </tr>
                                                    <!-- Holiday Ends Here -->



                                                    <!-- Other Starts Here -->
                                                    <tr class="other-row">
                                                        @for( $d=1 ; $d<=$days_in_month; $d++)
                                                            <?php
                                                            $day_name = date('D',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                                            $full_date = date('d-m-Y',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                                            ?>
                                                            <td class="other-column other--1 date--{{$d}}  @if($day_name == 'Sat' || $day_name == 'Sun') bg-weekend @endif   @if( in_array($full_date,array_keys($holidays))) bg-holiday @endif">
                                                                <input
                                                                        class="special-column-input column-input{{$n}} column-day{{$n}}-{{$d}} other--{{$d}}"
                                                                        name="other{{$n}}--1--{{$d}}" id="other{{$n}}--1--{{$d}}"
                                                                        value="@if( in_array( 'other--1--'.$d ,array_keys($time_sheet_lines))){{ $time_sheet_lines['other--1--'.$d]}}@endif"
                                                                        autocomplete="off"
                                                                >
                                                            </td>
                                                        @endfor
                                                    </tr>
                                                    <!-- Other Ends Here -->



                                                    <!-- Grand Total Starts Here -->
                                                    <tr class="grand-total-row bg-danger text-white">
                                                        @for( $d=1 ; $d<=$days_in_month; $d++)
                                                            <?php
                                                            $day_name = date('D',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                                            $full_date = date('d-m-Y',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                                            ?>
                                                            <th class="timesheet-date  @if($day_name == 'Sat' || $day_name == 'Sun') bg-weekend @endif   @if( in_array($full_date,array_keys($holidays))) bg-holiday @endif" id="grand-total-day{{$n}}-{{$d}}">0</th>
                                                        @endfor
                                                    </tr>
                                                    <!-- Grand Total Ends Here -->



                                                    </tfoot>
                                                    <!-- Table Foot Ends Here -->




                                                </table>
                                            </div>

                                        </div>
                                        <!-- Right Part Ends Here-->

                                    </div>
                                    <!-- Lines Ends Here-->

                                </div>
                            </div>
                            <!-- Timesheet Ends Here -->



                            <!--scripts -->
                            <script type="text/javascript">

                                $(function () {

                                    $(".column-input{{$n}}").each(function(){
                                        //get details of this column
                                        var selected_column = $(this);
                                        update_totals{{$n}}(selected_column);
                                    });


                                    calculate_project_hrs_percentage{{$n}}();


                                });


                                function update_totals{{$n}}(selected_column){

                                    var column_id = selected_column.attr('id');
                                    var id_parts = column_id.split('--');
                                    var row_type = id_parts[0];
                                    var row_type_no = id_parts[1];
                                    var column_date = id_parts[2];


                                    //get parent row of this column and its details
                                    var row = selected_column.parent().parent();
                                    var row_id = row.attr('id');

                                    //calculate total number of hrs  for this row
                                    var row_columns = row.children("td").children(".column-input{{$n}}");
                                    var row_total_hrs = 0;

                                    row_columns.each(function(index){
                                        var work_hrs = $(this).val();
                                        if(work_hrs == ''){ work_hrs = 0; }
                                        work_hrs = Number(work_hrs);
                                        if( work_hrs >= 0){
                                            row_total_hrs = row_total_hrs + work_hrs;
                                        }else{
                                            $(this).val('');
                                        }

                                    });

                                    //record total for this row
                                    var row_total_id = "#total--"+row_type+'--'+row_type_no;
                                    $(row_total_id).text(row_total_hrs);


                                    //calculate total number of hours for all projects in this day (the day in which this column is)
                                    var total_day_hrs_for_all_projects = 0;
                                    $(".project{{$n}}--"+column_date).each(function () {
                                        var day_hrs_for_one_projects = $(this).val();

                                        if(day_hrs_for_one_projects == ''){ day_hrs_for_one_projects = 0 }

                                        day_hrs_for_one_projects = Number(day_hrs_for_one_projects);
                                        if( day_hrs_for_one_projects >=0 ){
                                            total_day_hrs_for_all_projects += day_hrs_for_one_projects;
                                        }else{
                                            $(this).val('');
                                        }

                                    });
                                    $("#projects{{$n}}--day--total--"+column_date).text(total_day_hrs_for_all_projects);


                                    //calculate grand total hours for this day (the day in which this column is)
                                    var grand_total_hrs_in_one_day = 0;
                                    $(".column-day{{$n}}-"+column_date).each(function () {
                                        var day_hrs_for_one_cell = $(this).val();

                                        if( day_hrs_for_one_cell == '' ){ day_hrs_for_one_cell = 0; }

                                        day_hrs_for_one_cell = Number(day_hrs_for_one_cell);
                                        if(day_hrs_for_one_cell >= 0){
                                            grand_total_hrs_in_one_day += day_hrs_for_one_cell;
                                        }else{
                                            $(this).val('');
                                        }

                                    });
                                    $("#grand-total-day{{$n}}-"+column_date).text(grand_total_hrs_in_one_day);



                                    //calculate total project hrs for all projects for all days
                                    var total_hrs_for_all_projects = 0;
                                    $(".total-one-project{{$n}}").each(function () {
                                        var total_for_one_project = Number($(this).text());
                                        total_hrs_for_all_projects += total_for_one_project;
                                    });
                                    $("#total_project_hrs{{$n}}").text(total_hrs_for_all_projects);


                                    //calculate grand total hrs for everything for all days
                                    var grand_total_hrs = 0;

                                    var total_project_hrs = $("#total_project_hrs{{$n}}").text();
                                    var total_vacation_hrs = $("#total--vacation{{$n}}--1").text();
                                    var total_sick_hrs = $("#total--sick{{$n}}--1").text();
                                    var total_holiday_hrs = $("#total--holiday{{$n}}--1").text();
                                    var total_other_hrs = $("#total--other{{$n}}--1").text();

                                    grand_total_hrs = Number(total_project_hrs) + Number(total_vacation_hrs) + Number(total_sick_hrs);
                                    grand_total_hrs = grand_total_hrs + Number(total_holiday_hrs) + Number(total_other_hrs);

                                    $("#grand_total_hrs{{$n}}").text(grand_total_hrs);

                                }



                                function calculate_project_hrs_percentage{{$n}}(){

                                    var staff_id = $('#staff-{{$n}}').text();
                                    var total_hrs_for_each_project = $(".total-one-project{{$n}}");
                                    var total_hrs_for_all_projects = $("#total_project_hrs{{$n}}").text();

                                    total_hrs_for_each_project.each(function(){
                                        var total_hrs_for_one_project = $(this).text();
                                        var total_hrs_for_one_project_in_percentage;
                                        var project_id = $(this).attr('id');
                                        var id_parts = project_id.split('--');
                                        var project_no = id_parts[2];
                                        var emp_project_hrs_id = "#"+ staff_id + '--' + project_no + '--hrs';
                                        var emp_project_prc_id = "#"+ staff_id + '--' + project_no + '--prc';
                                        //alert(emp_project_id);

                                        total_hrs_for_one_project_in_percentage = (total_hrs_for_one_project/total_hrs_for_all_projects)*100;
                                        var project_total_hrs_text = total_hrs_for_one_project + " " + "(" + total_hrs_for_one_project_in_percentage.toFixed(0) +"%)";

                                        $("#total_project_hrs{{$n}}").text(total_hrs_for_all_projects);
                                        $("#"+staff_id+"--Total").text(total_hrs_for_all_projects);

                                        $(this).text(project_total_hrs_text);
                                        $(emp_project_hrs_id).text(total_hrs_for_one_project);
                                        $(emp_project_prc_id).text(total_hrs_for_one_project_in_percentage.toFixed(0)+'%');

                                    })

                                }


                            </script>

                        </div>
                    </div>
                    <!-- -->

                    <div class="row mb-5 mt-5">
                        @if( $time_sheet->status == 50)
                            @if( isset($spv_approval->done_by) && isset($hrm_approval->done_by) )
                                <div class="col-3 approval-info">
                                    <div class="text-secondary font-weight-bold">SUPERVISOR APPROVAL</div>
                                    <div>Approved By : <span class="font-weight-bold">{{ucwords($spv->first_name.' '.$spv->last_name)}}</span></div>
                                    <div>Department : <span class="font-weight-bold">{{ucwords($spv->department->name)}}</span></div>
                                    <div>Designation : <span class="font-weight-bold">{{ucwords($spv->jobTitle->title)}}</span></div>
                                    <div>Date Of Approval : <span class="font-weight-bold">{{date('d-m-Y H:m A',strtotime($spv_approval->created_at))}}</span></div>
                                    @if($spv_approval->comments != '')<div>Comments : <span class="font-weight-bold">{{$spv_approval->comments}}</span></div>@endif
                                </div>

                                <div class="col-3 approval2-info">
                                    <div class="text-secondary font-weight-bold">HUMAN RESOURCE MANAGER APPROVAL</div>
                                    <div>Approved By : <span class="font-weight-bold">{{ucwords($hrm->first_name.' '.$hrm->last_name)}}</span></div>
                                    <div>Department : <span class="font-weight-bold">{{ucwords($hrm->department->name)}}</span></div>
                                    <div>Designation : <span class="font-weight-bold">{{ucwords($hrm->jobTitle->title)}}</span></div>
                                    <div>Date Of Approval : <span class="font-weight-bold">{{date('d-m-Y H:m A',strtotime($hrm_approval->created_at))}}</span></div>
                                    @if($hrm_approval->comments != '')<div>Comments : <span class="font-weight-bold">{{$hrm_approval->comments}}</span></div>@endif
                                </div>
                            @endif
                        @endif
                    </div>
                </main>
                <footer>
                </footer>
            </div>
            <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
            <!-- <div></div> -->
        </div>
    </div>
    <!-- <div style="page-break-before: always;"></div> -->
    <?php $n++; ?>
@endforeach


<!-- LOE Summary-->


<div  class="statements ml-4 mr-4 pt-0 mt-0">
    <div class="toolbar hidden-print invisible">
        <div class="text-right">
            <button id="printReport" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
            <button class="btn btn-info"><i class="fa fa-file-pdf-o"></i> Export as PDF</button>
        </div>
        <hr>
    </div>
    <div class="report overflow-auto pt-0" style="min-width: 18cm; min-height: 29.7cm; margin-left: 1cm; padding-top: 0.1cm;">
        <div style="min-width: 600px">
            <header>
                <div class="row pb-2">
                    <div class="col text-center" >
                        <a target="_blank" href="#">
                            <img  src="/images/tmarc_logo.png" data-holder-rendered="true" style="max-height: 150px; width: auto;"/>
                        </a>
                    </div>
                    <div class="col company-details" style="display: none">
                        <div>Plot No. 215/217 Block D</div>
                        <div>Kuringa Drive, Tegeta</div>
                        <div>P.O.Box 63266</div>
                        <div>Dar es Salaam, Tanzania</div>
                        <div>Tel : +255 22 2780870</div>
                        <div>Fax : +255 22 2781296</div>
                        <div>Email: info@tmarc.or.tz</div>
                    </div>
                </div>
            </header>
            <main>

                <div class="row pl-3 pr-3" >
                    <div class="col text-center pt-3 pb-2 " style="background-color: #ddd;">
                        <h3>EMPLOYEES' TIME SHEETS (With LOE)</h3>
                    </div>
                </div>

                <div class="row contacts mt-2" style="display: none;">
                    <div class="col report-to">
                        <div class="address">Leave No. : <strong>{{strtoupper($time_sheet->id)}}</strong></div>
                        <div>Employee Name : <strong>{{ucwords($time_sheet->staff->first_name.' '.$time_sheet->staff->last_name)}}</strong></div>
                        <div class="date">Department : <strong>{{ucwords($time_sheet->staff->department->name)}}</strong></div>
                        <div class="date">Designation : <strong>{{ucwords($time_sheet->staff->jobTitle->title)}}</strong></div>
                    </div>
                    <div class="col report-details">
                        <div class="date">Generated By : <strong>{{$generated_by}}</strong></div>
                        <div class="date">Generation Date : <strong>{{$generation_date}}</strong></div>
                    </div>
                </div>

                <!-- Time Sheet Form -->
                <div class="row">
                    <div class="col-md-12 mt-3">


                        <table class="table table-hover table-striped table-bordered" border="0" cellspacing="0" cellpadding="0">
                            <thead>
                            <tr>
                                <th>#.</th>
                                <th>Submitted By</th>
                                <th>Year</th>
                                <th>Month</th>
                                <th>Supervisor</th>
                                <th>Status</th>
                                <th>Date Of Approval</th>
                                <?php $n=1; ?>
                                @foreach($statements as $statement)
                                    <?
                                    $projects = $statement['projects'];
                                    ?>
                                    @foreach($projects as $project_number=>$project_name)
                                        <th class="ctitle" colspan="1">{{$project_name}}</th>
                                    @endforeach
                                    <?php break; ?>
                                @endforeach
                                <th>Total Hrs</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $n=1; ?>
                            @foreach($statements as $statement)
                                <?php

                                $time_sheet = $statement['time_sheet'];
                                $responsible_spv = $statement['responsible_spv'];
                                $spv_name = $statement['spv_name'];
                                $employee_name = $statement['employee_name'];
                                $hrm_approval_date = $statement['hrm_approval_date'];
                                ?>
                                <tr class='clickable-row-new-tab' data-href="{{url('/time_sheet_statement')}}/{{ $time_sheet->id }}">
                                    <td >{{$n}}</td>
                                    <td >{{$time_sheet->staff->first_name.' '.$time_sheet->staff->last_name}}</td>
                                    <td >{{$time_sheet->year}}</td>
                                    <td >{{$time_sheet->month}}</td>

                                    <?php  $spv = \App\Staff::find($time_sheet->responsible_spv); ?>

                                    <td >{{$spv->first_name.' '.$spv->last_name}}</td>
                                    <td >{{$time_sheet_statuses[$time_sheet->status]}}</td>
                                    <td >{{date('d-m-Y h:m',strtotime($hrm_approval_date))}}</td>
                                    @foreach($projects as $project_number=>$project_name)
                                        <td>
                                            <div class="row text-right">
                                                <div class="col-6 project-total-hrs" id="{{$time_sheet->staff->id}}--{{$project_number}}--hrs"></div>
                                                <div class="col-6 text-danger" id="{{$time_sheet->staff->id}}--{{$project_number}}--prc"></div>
                                            </div>
                                        </td>
                                    @endforeach
                                    <th class="text-right total-hrs-one-staff-one-project" id="{{$time_sheet->staff->id}}--Total">0</th>
                                </tr>
                                <?php $n++; ?>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr class="bg-dark text-white">
                                <th></th>
                                <th>Grand Total Hrs</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <?php $n=1; ?>
                                @foreach($statements as $statement)
                                    <?
                                    $projects = $statement['projects'];
                                    ?>
                                    @foreach($projects as $project_number=>$project_name)
                                        <th class="ctitle">
                                            <div class="row text-right">
                                                <div class="col-6 grand-total-one-project" id="{{$project_number}}--total--hrs">0</div>
                                                <div class="col-6 text-danger">
                                                    <span id="{{$project_number}}--total--prc">0</span>
                                                    <span>%</span>
                                                </div>
                                            </div>
                                        </th>
                                    @endforeach
                                    <?php break; ?>
                                @endforeach
                                <th class="text-right" id="grand-total-project-hrs">0</th>
                            </tr>
                            </tfoot>
                        </table>



                        <!--scripts -->
                        <script type="text/javascript">

                            $( function(){
                                calculate_total_hrs_for_one_project();
                            });

                            function calculate_total_hrs_for_one_project(){
                                var grand_total_hrs = 0;

                                $(".total-hrs-one-staff-one-project").each( function(){
                                    grand_total_hrs += +$(this).text();
                                });

                                $('#grand-total-project-hrs').text(grand_total_hrs);


                                $(".project-total-hrs").each( function(){
                                    var id = $(this).attr('id');
                                    var id_parts = id.split('--');
                                    var project_no = id_parts[1];

                                    var total_for_project_id = '#'+ project_no + '--total--hrs';

                                    var total_hrs_for_one_project = $(total_for_project_id);

                                    var current_total = total_hrs_for_one_project.text();
                                    var new_total = +current_total + +$(this).text();

                                    total_hrs_for_one_project.text(new_total);
                                });

                                $('.grand-total-one-project').each(function(){
                                    var grand_total_hrs_for_one_project = $(this);
                                    var prc = (grand_total_hrs_for_one_project.text()/grand_total_hrs)*100;

                                    var id = $(this).attr('id');
                                    var id_parts = id.split('--');
                                    var prc_id = '#' + id_parts[0] + '--' + id_parts[1] + '--prc';


                                    $(prc_id).text(prc.toFixed(0));
                                });


                            }

                        </script>

                    </div>
                </div>
                <!-- -->


            </main>
            <footer>
            </footer>
        </div>
        <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
        <div></div>
    </div>
</div>
<div style="page-break-before: always;"></div>



</body>
