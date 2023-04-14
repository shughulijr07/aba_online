@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">Time Sheets Which Are <span class="orange-text">{{$time_sheet_statuses[$time_sheet_status]}}</span></div>
                    <div class="page-title-subheading">
                        Below is a list of timesheets. Click on the button <strong><span class="text-danger">View</span></strong> to see more information in the timesheet.
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
                    <th>Submitted By</th>
                    <th>Time Sheet For</th>
                    <th>Status</th>
                    <th>Submitted On</th>
                    <th>Supervisor</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <?php $n = 1; ?>
                @foreach($time_sheets as $time_sheet)
                <?php $supervisor = \App\Models\Staff::find($time_sheet->responsible_spv); ?>
                <tr class='clickable-row' data-href="/time_sheet_admin/{{ $time_sheet->id }}">
                    <td>{{ $n }}</td>
                    <td>{{ ucwords($time_sheet->staff->first_name.' '.$time_sheet->staff->last_name) }}</td>
                    <td>{{ $months[$time_sheet->month].', '.$time_sheet->year }}</td>
                    <td>{{ $time_sheet_statuses[$time_sheet_status]}}</td>
                    <td>{{ date("d-m-Y H:i:s", strtotime($time_sheet->created_at)) }}</td>
                    <td>{{ ucwords($supervisor->first_name.' '.$supervisor->last_name) }}</td>
                    <td>
                        <a href="/time_sheet_admin/{{ $time_sheet->id }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true">View</a>
                    </td>
                </tr>
                <?php $n++; ?>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Submitted By</th>
                    <th>Time Sheet For</th>
                    <th>Status</th>
                    <th>Submitted On</th>
                    <th>Supervisor</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
