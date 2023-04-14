@extends('leave_reports.base_report')

@section('table')
    <table class="table table-hover table-striped table-bordered" border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th>Leave No.</th>
            <th>Requested By</th>
            <th>Starting Date</th>
            <th>Ending Date</th>
            <th>Type Of Leave</th>
            <th>Status</th>
            <th>Date Of Request</th>
        </tr>
        </thead>
        <tbody>
        @foreach($leaves as $leave)
            <tr class='clickable-row-new-tab' data-href="{{url('/leave_statement')}}/{{ $leave->id }}">
                <td >{{$leave->id}}</td>
                <td >{{$leave->first_name.' '.$leave->last_name}}</td>
                <td >{{date('d-m-Y',strtotime($leave->starting_date))}}</td>
                <td >{{date('d-m-Y',strtotime($leave->ending_date))}}</td>
                <td >{{$leave_types[$leave->type]['name']}}</td>
                <td >{{$leave_statuses[$leave->status]}}</td>
                <td >{{date('d-m-Y h:m',strtotime($leave->created_at))}}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        </tfoot>
    </table>
@endsection