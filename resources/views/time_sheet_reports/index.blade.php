@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="pe-7s-news-paper"></i>
                </div>
                <div>
                    <div class="text-primary">Time Sheet Reports</div>
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
                    <h5 class="card-title text-danger">Time Sheet Reports Options</h5>

                    <form action="/generate_time_sheet_report" method="POST" enctype="multipart/form-data" id="valid-form" target="_blank">
                        @csrf
                        {{ csrf_field() }}

                        <fieldset>
                            <legend class="text-danger"></legend>
                            <div class="form-row">

                                <div class="col-md-3" id="report_type_div">
                                    <div class="position-relative form-group">
                                        <label for="report_type" class="">
                                            <span>Type Of Report</span>
                                        </label>
                                        <select name="report_type" id="report_type" class="form-control ">
                                            <option value="overview">Overview</option>
                                            <option value="detailed">Detailed</option>
                                            <option value="submitted">Submitted & Approved</option>
                                            <option value="submitted-loe">Submitted & Approved (LOE)</option>
                                            <option value="not-submitted">Not Submitted</option>
                                            <option value="late-submitted">Late Submitted</option>
                                            <option value="ontime-submitted">On Time Submitted</option>
                                            <option value="waiting-spv-approval">Waiting For SPV Approval</option>
                                            <option value="waiting-hrm-approval">Waiting For HRM Approval</option>
                                            <option value="in-drafts">Saved In Drafts</option>
                                            <option value="returned-for-correction">Returned For Correction</option>
                                            <option value="rejected">Rejected</option>
                                            <option value="not-filled-at-all">Not Filled At All</option>
                                            <option value="project-hrs-ratio">Project's Hours Ratio</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3" id="employee_selection_div">
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

                                <div class="col-md-3" id="year_div">
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

                                <div class="col-md-3" id="month_div">
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

                            </div>
                        </fieldset>

                        <button class="mt-2 btn btn-primary" id="generate_report_btn">Generate Report</button>
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

    </script>

@endsection

