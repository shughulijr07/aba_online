@extends('time_sheet_reports.base_report')

@section('table')
    <table class="table table-hover table-striped table-bordered" border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th>#.</th>
            <th>Submitted By</th>
            <th>Year</th>
            <th>Month</th>
            <th>Supervisor</th>
            <th>Status</th>
            <th>Date Of Approval</th>
        </tr>
        </thead>
        <tbody>
        <?php $n=1; ?>
        @foreach($time_sheets as $time_sheet)
            <tr class='clickable-row-new-tab' data-href="{{url('/time_sheet_statement')}}/{{ $time_sheet->id }}">
                <td >{{$n}}</td>
                <td >{{$time_sheet->first_name.' '.$time_sheet->last_name}}</td>
                <td >{{$time_sheet->year}}</td>
                <td >{{$time_sheet->month}}</td>

                <?php  $spv = \App\Models\Staff::find($time_sheet->responsible_spv); ?>

                <td >{{$spv->first_name.' '.$spv->last_name}}</td>
                <td >{{$time_sheet_statuses[$time_sheet->status]}}</td>
                <td >{{date('d-m-Y h:m',strtotime($time_sheet->updated_at))}}</td>
            </tr>
            <?php $n++; ?>
        @endforeach
        </tbody>
        <tfoot>
        </tfoot>
    </table>
@endsection