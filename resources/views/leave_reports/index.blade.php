@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="pe-7s-news-paper"></i>
                </div>
                <div>
                    <div class="text-primary">Leave Reports</div>
                    <div class="page-title-subheading">
                        Generate Type Of Report You Want By Selecting From Options Below
                    </div>
                </div>
            </div>

            <!--actions' menu starts here -->
            <!--actions' menu ends here -->

        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title text-danger">Leave Reports Options</h5>

                    <form action="/generate_leave_report" method="POST" enctype="multipart/form-data" id="valid-form" target="_blank">
                        @csrf
                        {{ csrf_field() }}

                        <fieldset>
                            <legend class="text-danger"></legend>
                            <div class="form-row">

                                <div class="col-md-4" id="report_type_div">
                                    <div class="position-relative form-group">
                                        <label for="report_type" class="">
                                            <span>Type Of Report</span>
                                        </label>
                                        <select name="report_type" id="report_type" class="form-control ">
                                            <option value="general">General</option>
                                            <option value="overview">Overview</option>
                                            <option value="leave-taken">Leave Taken</option>
                                            <option value="leave-rejected">Leave Rejected</option>
                                            <option value="leave-balances">Leave Balances</option>
                                            <option value="leave-paid">Leave Paid Out</option>
                                            <option value="leave-not-paid">Leave Not Paid Out</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4" id="type_of_leave_div">
                                    <div class="position-relative form-group">
                                        <label for="leave_type" class="">
                                            <span>Type Of Leave</span>
                                        </label>
                                        <select name="leave_type" id="leave_type" class="form-control">
                                            <option value="all">All</option>
                                            @foreach($leave_types as $key=>$leave_type)
                                                <option value="{{$key}}">{{$leave_type['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4" id="employee_selection_div">
                                    <div class="position-relative form-group">
                                        <label for="staff_id" class="">
                                            <span>Employee Name</span>
                                        </label>
                                        <select name="staff_id" id="staff_id" class="form-control">
                                            <option value="all">All Employees</option>
                                            <option value="Active">Active Employees</option>
                                            @foreach($all_staff as $staff)
                                                <option value="{{$staff->id}}" >
                                                    {{$staff->no.'     '.ucwords($staff->first_name.' '.$staff->last_name)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4" id="year_div" style="display: none;">
                                    <div class="position-relative form-group">
                                        <label for="year" class="">
                                            <span>Year</span>
                                        </label>
                                        <select name="year" id="year" class="form-control">
                                            <?php $varying_year=date("Y");?>
                                            @while( $varying_year >= $initial_year )
                                                <option value="{{$varying_year}}" @if($varying_year == $year) selected @endif>
                                                    {{$varying_year}}
                                                </option>

                                                <?php $varying_year--;?>
                                            @endwhile
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4" id="month_div" style="display: none;">
                                    <div class="position-relative form-group">
                                        <label for="month" class="">
                                            <span>Month</span>
                                        </label>
                                        <select name="month" id="month" class="form-control ">
                                            <option value="all">All</option>
                                            <option value="1">January</option>
                                            <option value="2">February</option>
                                            <option value="3">March</option>
                                            <option value="4">April</option>
                                            <option value="5">May</option>
                                            <option value="6">June</option>
                                            <option value="7">July</option>
                                            <option value="8">August</option>
                                            <option value="9">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4" id="from_date_div">
                                    <div class="position-relative form-group">
                                        <label for="from_date" class="">
                                            <span>From Date</span>
                                        </label>
                                        <input name="from_date" id="from_date" type="text" class="form-control"  autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-4" id="to_date_div">
                                    <div class="position-relative form-group">
                                        <label for="to_date" class="">
                                            <span>To Date</span>
                                        </label>
                                        <input name="to_date" id="to_date" type="text" class="form-control"  autocomplete="off">
                                    </div>
                                </div>

                            </div>
                        </fieldset>

                        <button class="mt-2 btn btn-primary" id="request_leave_btn">Generate Report</button>
                    </form>

                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">

        $(document).ready(function(){
            $('#from_date,#to_date').datepicker({
                format: 'dd/mm/yyyy'
            });

            $("#staff_id").select2({
                width: 'resolve',
            });
        });


        $('#report_type').on('change', function(){
            var selected_val = $(this).val();

            if ( selected_val == 'general' || selected_val == 'leave-taken' || selected_val == 'leave-rejected' ||  selected_val == 'overview' ) {

                //only show required filtering inputs for these types of reports
                $('#year_div').hide();
                $('#month_div').hide();

                $('#report_type_div').show('slow');
                $('#employee_selection_div').show('slow');
                $('#type_of_leave_div').show('slow');
                $('#from_date_div').show('slow');
                $('#to_date_div').show('slow');

            }

            else if ( selected_val == 'leave-paid' || selected_val == 'leave-not-paid'  ) {

                //only show required filtering inputs for these types of reports
                $('#year_div').show('slow');
                $('#month_div').hide();

                $('#report_type_div').show('slow');
                $('#employee_selection_div').show('slow');
                $('#type_of_leave_div').hide();
                $('#from_date_div').hide();
                $('#to_date_div').hide();

            }

            else if ( selected_val == 'leave-balances'  ) {

                //only show required filtering inputs for these types of reports
                $('#year_div').show('slow');
                $('#month_div').hide();

                $('#report_type_div').show('slow');
                $('#employee_selection_div').show('slow');
                $('#type_of_leave_div').show('slow');
                $('#from_date_div').hide();
                $('#to_date_div').hide();

            }/*

            else if ( selected_val == 'overview' ) {

                //only show required filtering inputs for these types of reports
                $('#year_div').show('slow');
                $('#month_div').show('slow');

                $('#report_type_div').show('slow');
                $('#employee_selection_div').show('slow');
                $('#type_of_leave_div').show('slow');
                $('#from_date_div').hide();
                $('#to_date_div').hide();

            }*/

        })


    </script>

@endsection

