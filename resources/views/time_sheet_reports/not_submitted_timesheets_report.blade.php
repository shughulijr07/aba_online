@extends('time_sheet_reports.base_report')

@section('table')
    <table class="table table-hover table-striped table-bordered" border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th>#.</th>
            <th>Name</th>
            <th>Department</th>
            <th>Email</th>
            <th>Phone No.</th>
            <th>Supervisor</th>
            <th>SPV Email</th>
            <th>SPV Phone No.</th>
        </tr>
        </thead>
        <tbody>
        <?php $n = 1; ?>
        @foreach($staff_who_have_not_submitted_timesheets as $staff)
            <tr>
                <td >{{$n}}</td>
                <td >{{$staff->first_name.' '.$staff->last_name}}</td>
                <td >{{$staff->department->name}}</td>
                <td >{{$staff->official_email}}</td>
                <td >{{$staff->phone_no}}</td>

                <?php $supervisor = \App\Staff::find($staff->supervisor_id); ?>

                <td >@if(isset($supervisor->id)){{$supervisor->first_name.' '.$supervisor->last_name}}@endif</td>
                <td >@if(isset($supervisor->id)){{$supervisor->official_email}}@endif</td>
                <td >@if(isset($supervisor->id)){{$supervisor->phone_no}}@endif</td>
            </tr>
            <?php $n++;?>
        @endforeach
        </tbody>
        <tfoot>
        </tfoot>
    </table>
@endsection