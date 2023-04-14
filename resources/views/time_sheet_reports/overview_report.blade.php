@extends('time_sheet_reports.base_report')

@section('table')
    <table class="table table-hover table-striped table-bordered" border="0" cellspacing="0" cellpadding="0">
        <thead>
        </thead>
        <tbody>
        <tr>
            <th>Timesheets Submitted & Approved</th>
            <td class="text-center">{{count($overview['submitted'])}}</td>
        </tr>
        <tr>
            <th>Timesheets Not Submitted</th>
            <td class="text-center">{{count($overview['not-submitted'])}}</td>
        </tr>
        <tr>
            <th>Timesheets Late Submitted</th>
            <td class="text-center">{{count($overview['late-submitted'])}}</td>
        </tr>
        <tr>
            <th>Timesheets On Time Submitted</th>
            <td class="text-center">{{count($overview['ontime-submitted'])}}</td>
        </tr>
        <tr>
            <th>Timesheets Waiting For SPV Approval</th>
            <td class="text-center">{{count($overview['waiting-spv-approval'])}}</td>
        </tr>
        <tr>
            <th>Timesheets Waiting For HRM Approval</th>
            <td class="text-center">{{count($overview['waiting-hrm-approval'])}}</td>
        </tr>
        <tr>
            <th>Timesheets Saved In Drafts</th>
            <td class="text-center">{{count($overview['in-drafts'])}}</td>
        </tr>
        <tr>
            <th>Timesheets Returned For Correction</th>
            <td class="text-center">{{count($overview['returned-for-correction'])}}</td>
        </tr>
        <tr>
            <th>Timesheets Rejected</th>
            <td class="text-center">{{count($overview['rejected'])}}</td>
        </tr>
        <tr>
            <th>Timesheets Not Filled At All</th>
            <td class="text-center">{{count($overview['not-filled-at-all'])}}</td>
        </tr>

        </tbody>
        <tfoot>
        </tfoot>
    </table>
@endsection