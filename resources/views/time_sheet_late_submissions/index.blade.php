@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">List Of Staff Who Have Not Submitted Their Time Sheets Before Deadline</div>
                </div>
            </div>


        </div>
    </div>
    <div class="main-card mb-3 card">
        <div class="card-body">
            <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                <thead>
                <tr>
                    <th>Id.</th>
                    <th>Employee Name</th>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Delaying Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach($time_sheet_late_submissions as $time_sheet_late_submission)
                <tr class='clickable-row' data-href="/time_sheet_late_submission/{{ $time_sheet_late_submission->id }}">
                    <td>{{ $time_sheet_late_submission->id }}</td>
                    <td>{{ ucwords(strtolower($time_sheet_late_submission->staff->first_name.' '.$time_sheet_late_submission->staff->last_name))}}</td>
                    <td>{{ $time_sheet_late_submission->year}}</td>
                    <td>{{ $months[$time_sheet_late_submission->month] }}</td>
                    <td>{{ $time_sheet_late_submission->reason }}</td>
                    <td>{{ $time_sheet_late_submission->status  }}</td>
                    <td>
                        @if( !in_array($time_sheet_late_submission->reason,[null,'']) && in_array(auth()->user()->role_id,[1,3]) )
                            <a href="/unlock_time_sheet_submission/{{ $time_sheet_late_submission->id }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true">Unlock</a>
                        @else
                            <a href="/time_sheet_late_submissions/{{ $time_sheet_late_submission->id }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true">View</a>
                        @endif
                    </td>
                </tr>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>Id.</th>
                    <th>Employee Name</th>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Delaying Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
