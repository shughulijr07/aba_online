@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title ">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">Leave Requests Which Are <span class="orange-text">Overlapping</span></div>
                    <div class="page-title-subheading">
                        Below is a list of requests with dates overlapping with Leave Request No.{{$leave->id}} which <span class="text-danger">starts on
                        {{date('d-m-Y',strtotime($leave->starting_date))}} and ends on {{date('d-m-Y',strtotime($leave->ending_date))}}</span>
                        made by {{ ucwords(strtolower($leave->staff->first_name.' '.$leave->staff->last_name))}}
                        <span class="text-danger">from {{ $leave->staff->department->name }} Department</span>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="main-card mb-3 card">
        <div class="card-header-tab card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal text-danger">
                <i class="header-icon  pe-7s-photo-gallery mr-3 "> </i>
                Overlapping Exactly On Same Dates
            </div>
        </div>
        <div class="card-body">
            <div>
                <h7>FROM THE SAME DEPARTMENT</h7>
                <table style="width: 100%; margin-top: 5px !important;" id="example1" class="table table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Employee Name</th>
                        <th>Leave Type</th>
                        <th>Starting Date</th>
                        <th>Ending Date</th>
                        <th>Days Requested</th>
                        <th>Department</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($overlapping_exactly_on_same_dates['same-department'] as $leave)
                        <tr class='clickable-row' data-href="/leave/{{ $leave->id }}">
                            <td>{{ $leave->id }}</td>
                            <td>{{ ucwords(strtolower($leave->staff->first_name.' '.$leave->staff->last_name))}}</td>
                            <td>{{ $leave_types[$leave->type]['name']}}</td>
                            <td>{{ date('d-m-Y',strtotime($leave->starting_date)) }}</td>
                            <td>{{ date('d-m-Y',strtotime($leave->ending_date ))}}</td>
                            <td>{{ $leave->calculate_no_of_days_btn_dates($leave->starting_date,$leave->ending_date) }}</td>
                            <td>{{ $leave->staff->department->name }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>

            <div style="margin-top: 30px;"></div>
            <div>
                <h7 style="margin-bottom: 0 !important;">FROM OTHER DEPARTMENTS</h7>
                <table style="width: 100%; margin-top: 5px !important;" id="example2" class="table table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Employee Name</th>
                        <th>Leave Type</th>
                        <th>Starting Date</th>
                        <th>Ending Date</th>
                        <th>Days Requested</th>
                        <th>Department</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($overlapping_exactly_on_same_dates['all-departments'] as $leave)
                        <tr class='clickable-row' data-href="/leave/{{ $leave->id }}">
                            <td>{{ $leave->id }}</td>
                            <td>{{ ucwords(strtolower($leave->staff->first_name.' '.$leave->staff->last_name))}}</td>
                            <td>{{ $leave_types[$leave->type]['name']}}</td>
                            <td>{{ date('d-m-Y',strtotime($leave->starting_date)) }}</td>
                            <td>{{ date('d-m-Y',strtotime($leave->ending_date ))}}</td>
                            <td>{{ $leave->calculate_no_of_days_btn_dates($leave->starting_date,$leave->ending_date) }}</td>
                            <td>{{ $leave->staff->department->name }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-header-tab card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal text-danger">
                <i class="header-icon  pe-7s-photo-gallery mr-3 "> </i>
                Overlapping On Starting Date
            </div>
        </div>
        <div class="card-body">
            <div>
                <h7>FROM THE SAME DEPARTMENT</h7>
                <table style="width: 100%; margin-top: 5px !important;" id="example1" class="table table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Employee Name</th>
                        <th>Leave Type</th>
                        <th>Starting Date</th>
                        <th>Ending Date</th>
                        <th>Days Requested</th>
                        <th>Department</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($overlapping_starting_date['same-department'] as $leave)
                        <tr class='clickable-row' data-href="/leave/{{ $leave->id }}">
                            <td>{{ $leave->id }}</td>
                            <td>{{ ucwords(strtolower($leave->staff->first_name.' '.$leave->staff->last_name))}}</td>
                            <td>{{ $leave_types[$leave->type]['name']}}</td>
                            <td>{{ date('d-m-Y',strtotime($leave->starting_date)) }}</td>
                            <td>{{ date('d-m-Y',strtotime($leave->ending_date ))}}</td>
                            <td>{{ $leave->calculate_no_of_days_btn_dates($leave->starting_date,$leave->ending_date) }}</td>
                            <td>{{ $leave->staff->department->name }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>

            <div style="margin-top: 30px;"></div>
            <div>
                <h7 style="margin-bottom: 0 !important;">FROM OTHER DEPARTMENTS</h7>
                <table style="width: 100%; margin-top: 5px !important;" id="example2" class="table table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Employee Name</th>
                        <th>Leave Type</th>
                        <th>Starting Date</th>
                        <th>Ending Date</th>
                        <th>Days Requested</th>
                        <th>Department</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($overlapping_starting_date['all-departments'] as $leave)
                        <tr class='clickable-row' data-href="/leave/{{ $leave->id }}">
                            <td>{{ $leave->id }}</td>
                            <td>{{ ucwords(strtolower($leave->staff->first_name.' '.$leave->staff->last_name))}}</td>
                            <td>{{ $leave_types[$leave->type]['name']}}</td>
                            <td>{{ date('d-m-Y',strtotime($leave->starting_date)) }}</td>
                            <td>{{ date('d-m-Y',strtotime($leave->ending_date ))}}</td>
                            <td>{{ $leave->calculate_no_of_days_btn_dates($leave->starting_date,$leave->ending_date) }}</td>
                            <td>{{ $leave->staff->department->name }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-header-tab card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal text-danger">
                <i class="header-icon  pe-7s-photo-gallery mr-3 "> </i>
                Overlapping On Ending Date
            </div>
        </div>
        <div class="card-body">
            <div>
                <h7>FROM THE SAME DEPARTMENT</h7>
                <table style="width: 100%; margin-top: 5px !important;" id="example1" class="table table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Employee Name</th>
                        <th>Leave Type</th>
                        <th>Starting Date</th>
                        <th>Ending Date</th>
                        <th>Days Requested</th>
                        <th>Department</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($overlapping_ending_date['same-department'] as $leave)
                        <tr class='clickable-row' data-href="/leave/{{ $leave->id }}">
                            <td>{{ $leave->id }}</td>
                            <td>{{ ucwords(strtolower($leave->staff->first_name.' '.$leave->staff->last_name))}}</td>
                            <td>{{ $leave_types[$leave->type]['name']}}</td>
                            <td>{{ date('d-m-Y',strtotime($leave->starting_date)) }}</td>
                            <td>{{ date('d-m-Y',strtotime($leave->ending_date ))}}</td>
                            <td>{{ $leave->calculate_no_of_days_btn_dates($leave->starting_date,$leave->ending_date) }}</td>
                            <td>{{ $leave->staff->department->name }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>

            <div style="margin-top: 30px;"></div>
            <div>
                <h7 style="margin-bottom: 0 !important;">FROM OTHER DEPARTMENTS</h7>
                <table style="width: 100%; margin-top: 5px !important;" id="example2" class="table table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Employee Name</th>
                        <th>Leave Type</th>
                        <th>Starting Date</th>
                        <th>Ending Date</th>
                        <th>Days Requested</th>
                        <th>Department</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($overlapping_ending_date['all-departments'] as $leave)
                        <tr class='clickable-row' data-href="/leave/{{ $leave->id }}">
                            <td>{{ $leave->id }}</td>
                            <td>{{ ucwords(strtolower($leave->staff->first_name.' '.$leave->staff->last_name))}}</td>
                            <td>{{ $leave_types[$leave->type]['name']}}</td>
                            <td>{{ date('d-m-Y',strtotime($leave->starting_date)) }}</td>
                            <td>{{ date('d-m-Y',strtotime($leave->ending_date ))}}</td>
                            <td>{{ $leave->calculate_no_of_days_btn_dates($leave->starting_date,$leave->ending_date) }}</td>
                            <td>{{ $leave->staff->department->name }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-header-tab card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal text-danger">
                <i class="header-icon  pe-7s-photo-gallery mr-3 "> </i>
                Overlapping Within
            </div>
        </div>
        <div class="card-body">
            <div>
                <h7>FROM THE SAME DEPARTMENT</h7>
                <table style="width: 100%; margin-top: 5px !important;" id="example1" class="table table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Employee Name</th>
                        <th>Leave Type</th>
                        <th>Starting Date</th>
                        <th>Ending Date</th>
                        <th>Days Requested</th>
                        <th>Department</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($overlapping_in['same-department'] as $leave)
                        <tr class='clickable-row' data-href="/leave/{{ $leave->id }}">
                            <td>{{ $leave->id }}</td>
                            <td>{{ ucwords(strtolower($leave->staff->first_name.' '.$leave->staff->last_name))}}</td>
                            <td>{{ $leave_types[$leave->type]['name']}}</td>
                            <td>{{ date('d-m-Y',strtotime($leave->starting_date)) }}</td>
                            <td>{{ date('d-m-Y',strtotime($leave->ending_date ))}}</td>
                            <td>{{ $leave->calculate_no_of_days_btn_dates($leave->starting_date,$leave->ending_date) }}</td>
                            <td>{{ $leave->staff->department->name }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>

            <div style="margin-top: 30px;"></div>
            <div>
                <h7 style="margin-bottom: 0 !important;">FROM OTHER DEPARTMENTS</h7>
                <table style="width: 100%; margin-top: 5px !important;" id="example2" class="table table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Employee Name</th>
                        <th>Leave Type</th>
                        <th>Starting Date</th>
                        <th>Ending Date</th>
                        <th>Days Requested</th>
                        <th>Department</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($overlapping_in['all-departments'] as $leave)
                        <tr class='clickable-row' data-href="/leave/{{ $leave->id }}">
                            <td>{{ $leave->id }}</td>
                            <td>{{ ucwords(strtolower($leave->staff->first_name.' '.$leave->staff->last_name))}}</td>
                            <td>{{ $leave_types[$leave->type]['name']}}</td>
                            <td>{{ date('d-m-Y',strtotime($leave->starting_date)) }}</td>
                            <td>{{ date('d-m-Y',strtotime($leave->ending_date ))}}</td>
                            <td>{{ $leave->calculate_no_of_days_btn_dates($leave->starting_date,$leave->ending_date) }}</td>
                            <td>{{ $leave->staff->department->name }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-header-tab card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal text-danger">
                <i class="header-icon  pe-7s-photo-gallery mr-3 "> </i>
                Overlapping Outside
            </div>
        </div>
        <div class="card-body">
            <div>
                <h7>FROM THE SAME DEPARTMENT</h7>
                <table style="width: 100%; margin-top: 5px !important;" id="example1" class="table table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Employee Name</th>
                        <th>Leave Type</th>
                        <th>Starting Date</th>
                        <th>Ending Date</th>
                        <th>Days Requested</th>
                        <th>Department</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($overlapping_out['same-department'] as $leave)
                        <tr class='clickable-row' data-href="/leave/{{ $leave->id }}">
                            <td>{{ $leave->id }}</td>
                            <td>{{ ucwords(strtolower($leave->staff->first_name.' '.$leave->staff->last_name))}}</td>
                            <td>{{ $leave_types[$leave->type]['name']}}</td>
                            <td>{{ date('d-m-Y',strtotime($leave->starting_date)) }}</td>
                            <td>{{ date('d-m-Y',strtotime($leave->ending_date ))}}</td>
                            <td>{{ $leave->calculate_no_of_days_btn_dates($leave->starting_date,$leave->ending_date) }}</td>
                            <td>{{ $leave->staff->department->name }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>

            <div style="margin-top: 30px;"></div>
            <div>
                <h7 style="margin-bottom: 0 !important;">FROM OTHER DEPARTMENTS</h7>
                <table style="width: 100%; margin-top: 5px !important;" id="example2" class="table table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Employee Name</th>
                        <th>Leave Type</th>
                        <th>Starting Date</th>
                        <th>Ending Date</th>
                        <th>Days Requested</th>
                        <th>Department</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($overlapping_out['all-departments'] as $leave)
                        <tr class='clickable-row' data-href="/leave/{{ $leave->id }}">
                            <td>{{ $leave->id }}</td>
                            <td>{{ ucwords(strtolower($leave->staff->first_name.' '.$leave->staff->last_name))}}</td>
                            <td>{{ $leave_types[$leave->type]['name']}}</td>
                            <td>{{ date('d-m-Y',strtotime($leave->starting_date)) }}</td>
                            <td>{{ date('d-m-Y',strtotime($leave->ending_date ))}}</td>
                            <td>{{ $leave->calculate_no_of_days_btn_dates($leave->starting_date,$leave->ending_date) }}</td>
                            <td>{{ $leave->staff->department->name }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

@endsection
