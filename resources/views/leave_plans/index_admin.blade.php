@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">Leave Plans Which Are <span class="orange-text">{{$leave_plan_statuses[$leave_plan_status] ?? ''}}</span></div>
                    <div class="page-title-subheading">
                        Below is a list of leave plans. Click on the button <strong><span class="text-danger">View</span></strong> to see more information of the leave plan.
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
                    <th>Leave Plan For</th>
                    <th>Status</th>
                    <th>Submitted On</th>
                    <th>Supervisor</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <?php $n = 1; ?>
                @foreach($leave_plans as $leave_plan)
                    <?php $supervisor = \App\Models\Staff::find($leave_plan->responsible_spv); ?>
                    <tr class='clickable-row' data-href="/leave_plan_admin/{{ $leave_plan->id }}">
                        <td>{{ $n }}</td>
                        <td>{{ ucwords($leave_plan->staff->first_name.' '.$leave_plan->staff->last_name) }}</td>
                        <td>{{ $leave_plan->year }}</td>
                        <td>{{ $leave_plan_statuses[$leave_plan_status]}}</td>
                        <td>{{ date("d-m-Y H:i:s", strtotime($leave_plan->created_at)) }}</td>
                        <td>{{ ucwords($supervisor->first_name.' '.$supervisor->last_name) }}</td>
                        <td>
                            <a href="/leave_plan_admin/{{ $leave_plan->id }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true">View</a>
                        </td>
                    </tr>
                    <?php $n++; ?>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Submitted By</th>
                    <th>Leave For</th>
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
