@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">List Of Leave Plans</div>
                    <div class="page-title-subheading"> </div>
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
                    <th>Year</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <?php $n=1; ?>
                @foreach($leave_plans as $leave_plan)
                <tr class='clickable-row' data-href="/leave_plans/{{ $leave_plan->id }}">
                    <td>{{ $n }}</td>
                    <td>{{ ucwords(strtolower($leave_plan->staff->first_name.' '.$leave_plan->staff->last_name))}}</td>
                    <td>{{ $leave_plan->year }}</td>
                    <td>{{ $leave_plan_statuses[$leave_plan->status] }}</td>
                    <td>
                        <a href="/leave_plans/{{ $leave_plan->id }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true">View</a>
                    </td>
                </tr>
                <?php $n++; ?>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Employee Name</th>
                    <th>Year</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
