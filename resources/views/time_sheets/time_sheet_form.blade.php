
<!-- Timesheet  -->
<div id="main-div" class="main-card mb-3 card">
    <div class="card-body">
        <h5 class="card-title text-danger" style="display: none;">Time Sheet Form</h5>
        
        <!-- Header -->
        <div class="row mb-3">
            <div class="col-md-12">
                <fieldset>
                    <legend class="text-danger"></legend>
                    <div class="formt-row"  style="display: none;">
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="time_sheet_id" class="">
                                    <span>Time Sheet ID</span>
                                </label>
                                <input name="time_sheet_id" id="time_sheet_id" type="text" class="form-control" value="{{$time_sheet->id}}" readonly>
                                <input name="action" id="action" type="text" class="form-control" value="save-to-draft" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-3">
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
                                    <span class="text-danger">*</span>
                                </label>
                                {{-- <select name="responsible_spv" id="responsible_spv" class="form-control @error('country') is-invalid @enderror" @if($supervisors_mode == '1') readonly @endif>
                                    @if($supervisors_mode == '2')<option value="">Select Supervisor</option>@endif
                                    @foreach($timeSheetSupervisors as $timeSheetSupervisor)
                                        <option value="{{$timeSheetSupervisor->id}}" @if(($timeSheetSupervisor->id == old('responsible_spv')) || ($timeSheetSupervisor->id == $responsible_spv)) selected @endif>
                                            {{ucwords($timeSheetSupervisor->first_name.' '.$timeSheetSupervisor->last_name)}}
                                        </option>
                                    @endforeach
                                </select> --}}
                                 <input name="responsible_spv" id="responsible_spv" type="text" class="form-control" value="{{$spv_name}}" readonly>
                                @error('responsible_spv')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="position-relative form-group">
                                <label for="year" class="">
                                    <span>Year</span>
                                </label>
                                <input name="year" id="year" type="text" class="form-control" value="{{$time_sheet->year}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="month" class="">
                                    <span>Month</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="month" id="month" class="form-control @error('month') is-invalid @enderror" readonly>
                                    @foreach($months as $value => $month_name)
                                        @if($value == $time_sheet->month) <option value="{{$value}}" selected>{{$month_name}}</option> @endif
                                    @endforeach
                                </select>

                                @error('month')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <!-- Header Ends Here-->

        {{-- Task assigned by Supervisor Form --}}
        <div class="row d-none px-3" id="timesheet-tasks">
            <div><h5><strong> Activities on <span id="timesheet-tasks-heading"></span></strong></h4></div>
            <table style="width: 100%; !important;" class="table table-hover table-striped  table-bordered" id="timesheet-task-table">
                <thead>
                    <th>Sn</th>
                    <th>Activity</th>
                    <th>Hours</th>
                    <th>Status</th>
                </thead>
                <tbody>
                </tbody>
            </table>
            <br>
            <div class="row justify-content-between">
                    <div class="col">
                        <button class="btn btn-primary" id="tasks-submit">
                            @if ($my_staff_id == $responsible_spv)
                            Back   
                            @else
                            Save
                            @endif
                        </button>
                        {{-- <button class="btn btn-danger" style="" id="cancel-form">Cancel</button> --}}
                </div>
            </div>
        </div>
        <!-- End of Task assigned by Supervisor Form  -->

        {{-- Development Task assigned by Supervisor Form --}}
        <div class="row d-none mb-3" id="development-tasks">
            <div class="ml-3"><h5><strong> Personal Development Tasks <span id="timesheet-dev-tasks-heading"></span></strong></h4></div>
            <hr>
            <div class="col-md-12">
                    <form action="" method="get">
                        <legend class="text-danger"></legend>
                    <div class="row justify-content-between">
                        <div class="col-md-8">
                            <div class="position-relative form-group">
                                <label for="employee_name" class="">
                                    <span>Task Name</span>
                                </label>
                                <input name="dev_task_name" id="dev_task_name" type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="position-relative form-group">
                                <label for="dev_hrs" class="">
                                    <span>Hours</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <input name="dev_hrs" id="dev_hrs" type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="position-relative form-group">
                                <label for="add" class="ml-4">
                                    <span></span>
                                </label>
                                <button class="btn btn-secondary btn-block btn-lg mt-2" id="add_dev_button" type="button" onclick="addToList()">Add</button>
                            </div>
                        </div>
                    </div>
                    </form>
                {{-- </fieldset> --}}
            </div>
        </div>
        <!-- End of Development Task assigned by Supervisor Form  -->

        {{-- Table List ofDevelopment Task   --}}
        <div class="row d-none px-3" id="dev-tasks-list">
            <table style="width: 100%; !important;" class="table table-hover table-striped  table-bordered" id="dev-task-table">
                <thead>
                    <th>Sn</th>
                    <th>Task Name</th>
                    <th>Hours</th>
                    <th class="text-center">Action</th>
                </thead>
                <tbody>
                
                </tbody>
            </table>
            <div class="justify-content-end">
                 <button class="btn btn-success text-center" type="button" id="dev-task-save" onclick="saveDevTask()">
                    Save Details
                </button>
            </div>
            <br>
        </div>
        {{-- End of Table Development Task List --}}

        <div class="row" id="timesheet-table">
            <!-- Left Part -->
            <div class="col-sm-4 mr-0 pr-0">
                <div class="table-responsive">
                    <table style="width: 100%; !important;" id="timesheet_lines" class="table table-hover table-striped table-bordered ">
                        <thead>
                        <tr >
                            <th class="no-wrap ctitle" colspan="2" style="border-color: #ffffff !important;"></th>
                        </tr>
                        <tr class="bg-primary text-white">
                            <th class="no-wrap ctitle">Client Name</th>
                            <th class="no-wrap">Total Hrs</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{-- @foreach($projects as $project_number=>$project_name)
                            <tr>
                                <td class="no-wrap ctitle">{{$project_name}}</td>
                                <td class="no-wrap total-one-project" id="total--project--{{$project_number}}">0</td>
                            </tr>
                        @endforeach --}}
                        @foreach($projects as $client_data2)
                            <tr>
                                <td class="no-wrap ctitle">{{$client_data2->name ?? null}}</td>
                                <td class="no-wrap total-one-project" id="total--project--{{$client_data2->number ?? null}}">0</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr class="bg-danger text-white">
                            <th class="no-wrap ctitle">Total Chargable Hours</td>
                            <th class="no-wrap" id="total_project_hrs">0</th>
                        </tr>


                        <tr style="border: none;">
                            <td  style="border: none;"></td>
                            <th  style="border: none;"></th>
                        </tr>


                        <tr class="bg-light-dark">
                            <td class="no-wrap ctitle">Personal Development</td>
                            <td class="no-wrap total-one-development" id="total--development--1">0</td>
                        </tr>

                        <tr class="bg-light-dark">
                            <td class="no-wrap ctitle">Vacation</td>
                            <td class="no-wrap total-one-vacation" id="total--vacation--1">0</td>
                        </tr>
                        <tr>
                            <td class="no-wrap ctitle">Sick</td>
                            <td class="no-wrap total-one-sick" id="total--sick--1">0</td>
                        </tr>
                        <tr class="bg-light-dark">
                            <td class="no-wrap ctitle">Holiday</td>
                            <td class="no-wrap total-one-holiday" id="total--holiday--1">0</td>
                        </tr>
                        <tr>
                            <td class="no-wrap ctitle">Other</td>
                            <td class="no-wrap total-one-other" id="total--other--1">0</td>
                        </tr>
                        <tr class=" bg-danger text-white">
                            <th class="no-wrap ctitle">Grand Total Hours</th>
                            <th class="no-wrap" id="grand_total_hrs">0</th>
                        </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
            <!-- Left Part Ends Here -->

            <!-- Right Part -->
            <div class="col-sm-8">
                <div class="table-responsive">
                    <table style="width: 100%; !important;" id="timesheet_lines" class="table table-hover table-striped table-bordered ">

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


                        <!-- (DAYBOX) Table Body Starts Here, All Projects Entries Are Found Here -->
                        <tbody>

                         @foreach($projects as $client_data2)
                            <tr class="project-row" id="project--{{$client_data2->number}}">
                                @for( $d=1 ; $d<=$days_in_month; $d++)
                                    <?php
                                    $day_name = date('D',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                    $full_date = date('d-m-Y',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                    ?>
                                    <td class="project-column project--{{$client_data2->number}} date--{{$d}}  @if($day_name == 'Sat' || $day_name == 'Sun') bg-weekend @endif   @if( in_array($full_date,array_keys($holidays))) bg-holiday @endif">
                                        <input
                                            readonly
                                            class="daybox column-input column-day-{{$d}} project--{{$d}}"
                                            name="project--{{$client_data2->number}}--{{$d}}"
                                            id="project--{{$client_data2->number}}--{{$d}}"
                                            value="@if( in_array( 'project--'.$client_data2->number.'--'.$d ,array_keys($time_sheet_lines))){{ $time_sheet_lines['project--'.$client_data2->number.'--'.$d]}}@endif"
                                            autocomplete="off"
                                            tasks="@if( in_array( 'tasks--'.$client_data2->number.'--'.$d ,array_keys($time_sheet_lines))){{ $time_sheet_lines['tasks--'.$client_data2->number.'--'.$d]}}@endif"
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
                                <th class="timesheet-date  @if($day_name == 'Sat' || $day_name == 'Sun') bg-weekend @endif   @if( in_array($full_date,array_keys($holidays))) bg-holiday @endif" id="projects--day--total--{{$d}}">0</th>
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

                        <!-- Development Starts Here -->
                        <tr class="vacation-row bg-light-dark" id="development--1">
                            @for( $d=1 ; $d<=$days_in_month; $d++)
                                <?php
                                $day_name = date('D',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                $full_date = date('d-m-Y',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                ?>
                                <td 
                                style="padding: 0px; margin: 0px; border: 0px;"
                                class="development-column development--1 date--{{$d}}  @if($day_name == 'Sat' || $day_name == 'Sun') bg-weekend @endif   @if( in_array($full_date,array_keys($holidays))) bg-holiday @endif">
                                    <input
                                            class="dayBox2 column-input column-day-{{$d}} develop--{{$d}}"
                                        
                                            name="develop--1--{{$d}}" 
                                            pointer="development--1--{{$d}}"
                                            id="develop--1--{{$d}}" onclick="showDevForm(event)"

                                            @if($time_sheet->status == 10 && $leave_timesheet_link_mode == 2 &&
                                               ( in_array($full_date,$staff_annual_leave_dates) ||
                                                 in_array($full_date,$staff_paternity_leave_dates) ||
                                                 in_array($full_date,$staff_maternity_leave_dates) )
                                            )
                                            value="24"
                                            @else
                                            value="@if( in_array( 'develop--1--'.$d ,array_keys($time_sheet_lines))){{ $time_sheet_lines['develop--1--'.$d]}}@endif"
                                            @endif

                                            developments="@if( in_array( 'development--1--'.$d ,array_keys($time_sheet_lines))){{ $time_sheet_lines['development--1--'.$d]}}
                                            @endif"
                                            autocomplete="off"
                                    >


                                </td>
                            @endfor
                        </tr>
                        <!-- Development Ends Here -->

                        <!-- Vacation Starts Here -->
                        <tr class="vacation-row bg-light-dark" id="project--1">
                            @for( $d=1 ; $d<=$days_in_month; $d++)
                                <?php
                                $day_name = date('D',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                $full_date = date('d-m-Y',strtotime($d.'-'.$time_sheet->month.'-'.$time_sheet->year));
                                ?>
                                <td class="vacation-column vacation--1 date--{{$d}}  @if($day_name == 'Sat' || $day_name == 'Sun') bg-weekend @endif   @if( in_array($full_date,array_keys($holidays))) bg-holiday @endif">
                                    <input
                                            class="column-input column-day-{{$d}} vacation--{{$d}}"
                                            name="vacation--1--{{$d}}" id="vacation--1--{{$d}}"

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
                                            class="column-input column-day-{{$d}} sick--{{$d}}"
                                            name="sick--1--{{$d}}" id="sick--1--{{$d}}"
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
                                            class="column-input column-day-{{$d}} holiday--{{$d}}"
                                            name="holiday--1--{{$d}}" id="holiday--1--{{$d}}"
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
                                            class="column-input column-day-{{$d}} other--{{$d}}"
                                            name="other--1--{{$d}}" id="other--1--{{$d}}"
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
                                <th class="timesheet-date  @if($day_name == 'Sat' || $day_name == 'Sun') bg-weekend @endif   @if( in_array($full_date,array_keys($holidays))) bg-holiday @endif" id="grand-total-day-{{$d}}">0</th>
                            @endfor
                        </tr>
                        <!-- Grand Total Ends Here -->

                        </tfoot>
                        <!-- Table Foot Ends Here -->
                    </table>
                </div>

                @if( auth()->user()->staff->id == $time_sheet->staff_id)
                    @if( $time_sheet->status == '0' )
                        <div class="text-right mt-2">
                            <input class="mt-2 btn btn-primary" type="submit" id="submit_time_sheet_btn1" name="action" value="Submit Time Sheet">
                        </div>
                    @endif

                    @if( $time_sheet->status == '10' )
                        <div class="text-right mt-2">
                            <input class="mt-2 btn btn-secondary" type="submit"  id="save_to_draft_btn1" name="action" value="Save To Drafts">
                            <input class="mt-2 btn btn-primary" type="submit" id="submit_time_sheet_btn1" name="action" value="Submit Time Sheet">
                        </div>
                    @endif
                @endif

                @if( ($time_sheet->status > 10 && $time_sheet->status <= 40)  && in_array(auth()->user()->role_id, [1,3]) && $view_type != 'edit_admin')
                    <div class="text-right mt-2">
                        <a class="btn btn-primary mt-2" href="{{url('/time_sheet_edit_admin/'.$time_sheet->id)}}" role="button">Edit Time Sheet</a>
                    </div>
                @endif

                @if( ($time_sheet->status > 10 && $time_sheet->status <= 40)  && in_array(auth()->user()->role_id, [1,3]) && $view_type == 'edit_admin')
                    <div class="text-right mt-2">
                        <input class="mt-2 btn btn-primary" type="submit" id="save_edited_timesheet" name="action" value="Save Time Sheet">
                    </div>
                @endif

            </div>
            <!-- Right Part Ends Here-->

        </div>
        <!-- Lines Ends Here-->

    </div>
</div>
<!-- Timesheet Ends Here -->


<!--scripts -->
<script type="text/javascript">

    var timesheet_records = {};

    var pusherList = [];
    var dev_task_list = {};
    var database_dev_task_list = @json($time_sheet_lines);

    console.log( database_dev_task_list );
    var initialInput;
    var currentElementId;

    var totalDev = $("#total--development--1");

    let supervisor_id = {!! $responsible_spv !!};
    let my_staff_id = {!! $my_staff_id !!};
    let timesheet_id = {!! $time_sheet->id !!};

    $(function () {

        $('#notifications-div').delay(7000).fadeOut('slow');

        $(".column-input").each(function(){
            //get details of this column
            var selected_column = $(this);
            update_totals(selected_column);
        });

        //update total hours for each project in percentage
        //calculate_project_hrs_percentage();
    });

    function showDevForm(event){
        initialInput = event.target;
        currentElementId = $(initialInput).attr('pointer');
            console.log( currentElementId );
        if( currentElementId in database_dev_task_list ){
            if (database_dev_task_list[currentElementId].length > 0){
                dev_task_list[currentElementId] = JSON.parse( database_dev_task_list[currentElementId] );
            }
         }
    
        // clean pusher
        pusherList = [];
        // assign for the current selected
        if( dev_task_list[currentElementId]){
            if( dev_task_list[currentElementId].length > 0){
                $("#dev-tasks-list").removeClass("d-none");
                addTableRow();
                dev_task_list[currentElementId].forEach( el => {
                    pusherList.push(el);
                    console.log('imaingia hapa jamani');
                });
            }
        }
        // console.log( event.target );  
        $("#timesheet-table").toggleClass("d-none");
        $("#development-tasks").toggleClass("d-none");
    }

    function addToList(){
        $("#dev-tasks-list").removeClass("d-none");
        var task_name_selector = $("#dev_task_name");
        var task_hr_selector = $("#dev_hrs");

        var dev_task_name = task_name_selector.val();
        var task_hrs = task_hr_selector.val();

        pusherList.push({'dev_task_name' : dev_task_name, 'dev_hrs':task_hrs});

        dev_task_list[currentElementId] = pusherList;

        console.log( pusherList );
        console.log( dev_task_list );

        addTableRow();
        task_name_selector.val('');
        task_hr_selector.val('');

        // findDevSum( dev_task_list );
    }

    function findDevSum(arr){
        var total = 0;
        arr.forEach( el => {
            el.forEach( task => {
                total += parseInt( task.dev_hrs );
            })
        });
        return total;
    }

   
    function addTableRow(){
            var table = $("#dev-task-table tbody");
           // delete all rows
           table.html("");
            console.log( dev_task_list[currentElementId].length );
            var count = 0;
            dev_task_list[currentElementId].forEach( el => {
                count += 1;
                var row =
                    '<tr>' +
                        `<td>${ count }</td>`
                        + `<td>${el.dev_task_name}</td>`
                        + `<td>${el.dev_hrs}</td>`
                        + `<td class="text-center">
                            <button class="btn btn-danger" type="button" onclick="removeDevTask(event, ${dev_task_list[currentElementId].length - 1})">
                                Remove
                            </button>
                        </td>`
                    + '</tr>'
                table.append(row);
            });

    }

    function removeDevTask(event,index){
        var targetRow = $(event.target).parent().parent();
        var table = $("#dev-task-table tbody");
        targetRow.remove();
        dev_task_list[currentElementId].splice(index, 1)  //remove this row from the list
    }

    function saveDevTask(){
        // dev_task_list.push({'dev_task_name' : dev_task_name, 'dev_hrs': task_hrs});
        var total_hours = 0;
        dev_task_list[currentElementId].forEach(el => {
            total_hours += parseInt( el.dev_hrs );
        });
        $(initialInput).val( total_hours );
        $("#timesheet-table").toggleClass("d-none");
        $("#development-tasks").toggleClass("d-none");
        $("#dev-tasks-list").addClass("d-none");
    }


    function toggleContainers() {
        $("#timesheet-table").toggleClass("d-none");
        $("#timesheet-tasks").toggleClass("d-none");
    }

    var dayBox = document.querySelectorAll(".daybox");

    var dayBox2 = document.querySelectorAll(".daybox2");

    dayBox.forEach(el => {
        $(el).on('click',function (e) { 
        e.preventDefault();
        toggleContainers();
        var selected_column = $(el);
        var column_id = selected_column.attr('id');
        var id_parts = column_id.split('--');
        var project_id = id_parts[1]
        var day = id_parts[2]

        var clientName = "Client Name" // get client details (name)
        var month = $("#month").text();
        var year = $("#year").val();
        var dateString = `${month} ${day} ${year}`
        var timesheet_tasks_heading = dateString + " - " + clientName

        $("#timesheet-tasks-heading").text(timesheet_tasks_heading);
        $("#timesheet-tasks-heading").attr("meta-id", column_id);

        $.ajax({
            type: "post",
            url: `/api/project_activities`,
            dataType: "json",
            data: {'project_id': project_id, 'timesheet_id':timesheet_id},
            success: function (response) {
                    
                var activities = response
                
                var task_id = `tasks--${id_parts[1]}--${id_parts[2]}`

                // capture previous local hours
                var prev = timesheet_records[task_id];
                var prev_hours = []
                if(prev) {
                    for(var i = 0; i < prev.length; i++) {
                        var hr = prev[i].hours
                        if(hr) {
                            prev_hours.push(hr)
                        }
                    }
                }
                if(prev_hours.length == 0){
                    // capture previous database hours
                    var db_tasks = $(`#${column_id}`).attr("tasks");
                    if(db_tasks != '') {
                        db_tasks = JSON.parse(db_tasks)
                        for(var i = 0; i < db_tasks.length; i++) {
                            prev_hours.push(db_tasks[i].hours)
                        }
                    }
                }
                timesheet_records[task_id] = activities

                var table = $("#timesheet-task-table tbody");
                var counter = 0;
                var hoursIndex = 0;
                var rows = [];

                activities.forEach(activity => {
                    let reader = '';
                    if(my_staff_id == supervisor_id){
                        reader = 'disabled';
                    }

                rows.push(
                    '<tr>' +
                        `<td>${++counter}</td>`
                        + `<td>${activity.task_name}</td>`
                        + `<td><input class="form-control form-control-sm"`
                        + ` ${reader} value='${getPrevHours(prev_hours, hoursIndex++)}'></td>`
                        + `<td>Complete</td>`
                    + '</tr>'
                )
                });

                // for(var i = 0; i < rows.length; i++) {
                //     var hour_input = rows[i].children[2]
                //     var hours = hour_input.children[0].value
                //     timesheet_records[task_id][i]['hours'] = hours
                // }

                table.html(rows)
            }
        });

    });       
    });
 

    function getPrevHours(hours, index) {
        return hours[index] ?? ''
    }
      
    $("#cancel-form").click(function (e) { 
        e.preventDefault();

        // var table = $("#timesheet-task-table tbody");
        // var rows = table.find('tr');

        // var column_id = $("#timesheet-tasks-heading").attr("meta-id");
        // var id_parts = column_id.split('--');
        // var task_id = `tasks--${id_parts[1]}--${id_parts[2]}`
        // // retain the values
        // for(var i = 0; i < rows.length; i++) {
        //     var hour_input = rows[i].children[2]
        //     var hours = hour_input.children[0].value
        //     timesheet_records[task_id][i]['hours'] = hours
        // }

        toggleContainers()
    });

    $("#tasks-submit").click(function (e) { 
        e.preventDefault();

        var column_id = $("#timesheet-tasks-heading").attr("meta-id");

        var id_parts = column_id.split('--');
        var project_id = id_parts[1]

        var table = $("#timesheet-task-table tbody");
        var total_hours = 0;
        var rows = table.find('tr');
        var index = 0;

        var task_id = `tasks--${id_parts[1]}--${id_parts[2]}`

        for(var i = 0; i < rows.length; i++) {
            var hour_input = rows[i].children[2]
            var hours = hour_input.children[0].value
            timesheet_records[task_id][i]['hours'] = hours
            if(hours != "") {
                total_hours += Number(hours)
            }
        }
        
        $(`#${column_id}`).val(total_hours)
        $(`#${column_id}`).trigger("change");
        toggleContainers();
    });


    var form = $("#main-div").parent('form');
    $('#save_to_draft_btn1').click(function(e){
        e.preventDefault();


        var inputElement = `<input type='hidden' name='whichBtn' value='Save To Drafts'>`;

        $(form).append( inputElement );
        for(key in timesheet_records) {
            var strData = JSON.stringify( timesheet_records[key] );
            var inputElement = `<input type='hidden' name='${key}' value='${strData}'>`;
            $(form).append( inputElement );
        }

        for(const key in dev_task_list) {
            var id_parts = key.split('--');
            var development_id = `development--${id_parts[1]}--${id_parts[2]}`
            var secondStrData = JSON.stringify( dev_task_list[key] );
            var secondInputElement = `<input type='hidden' name='${development_id}' value='${secondStrData}'>`;
            console.log( secondInputElement );
            $(form).append( secondInputElement );
        }

        // resubmit the tasks of NON-EDITED boxes, otherwise they will be lost because the json is going to be replaced with the new one. 
        // you will know non-edited boxes if they do no exist in timesheet_records variable

        dayBox.forEach(el => {
            var selected_column = $(el);
            var column_id = selected_column.attr('id');
            var db_tasks = $(`#${column_id}`).attr("tasks");
            // if item is not in timesheet_records variable, and has tasks then include it. use id to check
    
            var id_parts = column_id.split('--');
            var task_id = `tasks--${id_parts[1]}--${id_parts[2]}`
            var has_tasks = db_tasks != '';
            var is_edited = timesheet_records[task_id] != undefined;

            if(has_tasks && !is_edited) {
                var inputElement = `<input type='hidden' name='${task_id}' value='${db_tasks}'>`;
                $(this).append( inputElement );
            }
        })


        dayBox2.forEach(el => {
            var selected_column = $(el);
            var column_id = selected_column.attr('id');
            var db_tasks = $(`#${column_id}`).attr("developments");
            // if item is not in timesheet_records variable, and has tasks then include it. use id to check
    
            var id_parts = column_id.split('--');
            var task_id = `development--${id_parts[1]}--${id_parts[2]}`
            var has_tasks = db_tasks != '';
            var is_edited = dev_task_list[task_id] != undefined;

            if(has_tasks && !is_edited) {
                var secInputElement = `<input type='hidden' name='${task_id}' value='${db_tasks}'>`;
                $(this).append( secInputElement );
            }
        })
        $(form).submit();
    });

    $('#submit_time_sheet_btn1').click(function(e){
        e.preventDefault();
         var inputElement = `<input type='hidden' name='whichBtn' value='Submit Time Sheet'>`;
        $(this).append( inputElement );
        for(key in timesheet_records) {
            var strData = JSON.stringify( timesheet_records[key] );
            var inputElement = `<input type='hidden' name='${key}' value='${strData}'>`;
            $(this).append( inputElement );
        }

        // resubmit the tasks of NON-EDITED boxes, otherwise they will be lost because the json is going to be replaced with the new one. 
        // you will know non-edited boxes if they do no exist in timesheet_records variable

        dayBox.forEach(el => {
            var selected_column = $(el);
            var column_id = selected_column.attr('id');
            var db_tasks = $(`#${column_id}`).attr("tasks");
            // if item is not in timesheet_records variable, and has tasks then include it. use id to check
    
            var id_parts = column_id.split('--');
            var task_id = `tasks--${id_parts[1]}--${id_parts[2]}`
            var has_tasks = db_tasks != '';
            var is_edited = timesheet_records[task_id] != undefined;

            if(has_tasks && !is_edited) {
                var inputElement = `<input type='hidden' name='${task_id}' value='${db_tasks}'>`;
                $(this).append( inputElement );
            }
        })
        $(form).submit();
    })

    function submitForm(timesheet_records, form){
        
    }

    $(".column-input").on('change',function(){
        //get details of this column
        var selected_column = $(this);
        update_totals(selected_column);
    });


    function update_totals(selected_column){

        var column_id = selected_column.attr('id');
        var id_parts = column_id.split('--');
        var row_type = id_parts[0];
        var row_type_no = id_parts[1];
        var column_date = id_parts[2];


        //get parent row of this column and its details
        var row = selected_column.parent().parent();
        var row_id = row.attr('id');

        //calculate total number of hrs  for this row
        var row_columns = row.children("td").children(".column-input");
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
        $(".project--"+column_date).each(function () {
            var day_hrs_for_one_projects = $(this).val();

            if(day_hrs_for_one_projects == ''){ day_hrs_for_one_projects = 0 }

            day_hrs_for_one_projects = Number(day_hrs_for_one_projects);
            if( day_hrs_for_one_projects >=0 ){
                total_day_hrs_for_all_projects += day_hrs_for_one_projects;
            }else{
                $(this).val('');
            }

        });
        $("#projects--day--total--"+column_date).text(total_day_hrs_for_all_projects);


        //calculate grand total hours for this day (the day in which this column is)
        var grand_total_hrs_in_one_day = 0;
        $(".column-day-"+column_date).each(function () {
            var day_hrs_for_one_cell = $(this).val();

            if( day_hrs_for_one_cell == '' ){ day_hrs_for_one_cell = 0; }

            day_hrs_for_one_cell = Number(day_hrs_for_one_cell);
            if(day_hrs_for_one_cell >= 0){
                grand_total_hrs_in_one_day += day_hrs_for_one_cell;
            }else{
                $(this).val('');
            }

        });
        $("#grand-total-day-"+column_date).text(grand_total_hrs_in_one_day);



        //calculate total project hrs for all projects for all days
        var total_hrs_for_all_projects = 0;
        $(".total-one-project").each(function () {
            var total_for_one_project = Number($(this).text());
            total_hrs_for_all_projects += total_for_one_project;
        });
        $("#total_project_hrs").text(total_hrs_for_all_projects);


        //calculate grand total hrs for everything for all days
        var grand_total_hrs = 0;

        var total_project_hrs = $("#total_project_hrs").text();
        var total_vacation_hrs = $("#total--vacation--1").text();
        var total_sick_hrs = $("#total--sick--1").text();
        var total_holiday_hrs = $("#total--holiday--1").text();
        var total_other_hrs = $("#total--other--1").text();

        grand_total_hrs = Number(total_project_hrs) + Number(total_vacation_hrs) + Number(total_sick_hrs);
        grand_total_hrs = grand_total_hrs + Number(total_holiday_hrs) + Number(total_other_hrs);

        $("#grand_total_hrs").text(grand_total_hrs);

        calculate_project_hrs_percentage1();

    }



    /******************** Level Of Effort (LOE) calculations for projects *******************/


    function calculate_project_hrs_percentage1(){

        var total_hrs_for_each_project = $(".total-one-project");
        var total_hrs_for_all_projects = $("#total_project_hrs").text();

        total_hrs_for_each_project.each(function(){
            var total_hrs_for_one_project = $(this).text();
            var project_id = $(this).attr('id');
            var total_hrs_for_one_project_in_percentage;

            var project_id_parts = project_id.split('--');
            var project_no = project_id_parts[2];

            if( total_hrs_for_all_projects == 0){
                total_hrs_for_one_project_in_percentage = 0;
            }else{
                total_hrs_for_one_project_in_percentage = (total_hrs_for_one_project/total_hrs_for_all_projects)*100;
            }


            //update LOE on summary section
            $("#hrs--project--"+project_no).text(total_hrs_for_one_project);
            $("#percentage--project--"+project_no).text(total_hrs_for_one_project_in_percentage.toFixed(0));

            //alert(project_no);

            //$(this).text(project_total_hrs_text);
        })

    }

    /******************** Level Of Effort (LOE) calculations for projects *******************/


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