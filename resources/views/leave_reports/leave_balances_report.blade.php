@extends('leave_reports.base_report')

@section('table')
    <table class="table table-hover table-striped table-bordered" border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th>No.</th>
            <th>Employee No.</th>
            <th>Employee Name</th>
            <th>Type Of Leave</th>
            <th>Entitled Days</th>
            <th>Days Taken</th>
            <th>Balance</th>
        </tr>
        </thead>
        <tbody>
        <?php $n = 1; ?>
        @foreach($leave_summaries as $leave_summary)
                @if( $selected_leave_type == 'all' )
                    @foreach($leave_types as $key=>$leave_type)
                    <tr>
                        <td >{{$n}}</td>
                        <td >{{($leave_summary['staff-info'])->staff_no}}</td>
                        <td >{{ucwords(($leave_summary['staff-info'])->first_name.' '.($leave_summary['staff-info'])->last_name)}}</td>

                        <td >{{$leave_type['name']}}</td>
                        <td >{{$leave_summary[$key]['entitled-days']}}</td>
                        <td >{{$leave_summary[$key]['days-taken']}}</td>
                        <td >{{$leave_summary[$key]['days-left']}}</td>
                    </tr>
                    <?php $n++; ?>
                    @endforeach
                @else
                    <tr>
                    <td >{{$n}}</td>
                    <td >{{($leave_summary['staff-info'])->staff_no}}</td>
                    <td >{{ucwords(($leave_summary['staff-info'])->first_name.' '.($leave_summary['staff-info'])->last_name)}}</td>

                    <td >{{$leave_types[$selected_leave_type]['name']}}</td>
                    <td >{{$leave_summary[$selected_leave_type]['entitled-days']}}</td>
                    <td >{{$leave_summary[$selected_leave_type]['days-taken']}}</td>
                    <td >{{$leave_summary[$selected_leave_type]['days-left']}}</td>
                    </tr>
                    <?php $n++; ?>
                @endif
        @endforeach
        </tbody>
        <tfoot>
        </tfoot>
    </table>
@endsection