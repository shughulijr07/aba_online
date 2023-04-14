@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">Leave Requests Which Are <span class="orange-text">{{$leave_statuses[$leave_status]}}</span></div>
                    <div class="page-title-subheading">
                        Below is a list of requests. Click on the button <strong><span class="text-danger">View</span></strong> to see the summary of the request.
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="main-card mb-3 card">
        <div class="card-body">
            <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Employee Name</th>
                    <th>Leave Type</th>
                    <th>Days Requested</th>
                    <th>Starting Date</th>
                    <th>Ending Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach($leaves as $leave)
                <tr class='clickable-row' data-href="/leave/{{ $leave->id }}">
                    <td>{{ $leave->id }}</td>
                    <td>{{ ucwords(strtolower($leave->staff->first_name.' '.$leave->staff->last_name))}}</td>
                    <td>{{ $leave_types[$leave->type]['name']}}</td>
                    <td>{{ $leave->calculate_no_of_days_btn_dates($leave->starting_date,$leave->ending_date) }}</td>
                    <td>{{ date('d-m-Y', strtotime($leave->starting_date)) }}</td>
                    <td>{{ date('d-m-Y', strtotime($leave->ending_date))  }}</td>
                    <td>
                        <a href="/leave/{{ $leave->id }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true">View</a>
                    </td>
                </tr>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>Leave No.</th>
                    <th>Employee Name</th>
                    <th>Leave Type</th>
                    <th>Days Requested</th>
                    <th>Starting Date</th>
                    <th>Ending Date</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
