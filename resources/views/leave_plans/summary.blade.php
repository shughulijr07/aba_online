@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">Leave Plan Summary For The Year <span class="orange-text">{{date('Y')}}</span></div>
                    <div class="page-title-subheading">
                        Below is a list of Leave Plans made by Employees grouped by months.
                    </div>
                </div>
            </div>


        </div>
    </div>


    <?php $n = 1; ?>
    @foreach($leave_plan_lines_in_all_months as $month=>$lines_in_one_month)
    @if( count($lines_in_one_month) > 0 )
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">{{$months[$month]}}</h5>
            <table style="width: 100%;" id="{{$month}}" class="table table-hover table-striped table-bordered">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Employee Name</th>
                    <th>Department</th>
                    <th>Leave Type</th>
                    <th>Days</th>
                    <th>Starting Date</th>
                    <th>Ending Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>


                    @foreach($lines_in_one_month as $line)
                    @if( $mode == 'admin' )
                    <tr class='clickable-row' data-href="/leave_plan_admin/{{ $line->leave_plan->id }}">
                    @elseif( $mode == 'normal' )
                    <tr class='clickable-row' data-href="/leave_plans/{{ $line->leave_plan->id }}">
                    @endif
                        <td>{{ $n }}</td>
                        <td>{{ ucwords($line->leave_plan->staff->first_name.' '.$line->leave_plan->staff->last_name) }}</td>
                        <td>{{ $line->leave_plan->staff->department->name }}</td>
                        <td>{{ $leave_types[$line->type_of_leave]['name'] }}</td>
                        <td>{{ \App\Models\MyFunctions::calculate_no_of_days_btn_dates($line->starting_date,$line->ending_date) }}</td>
                        <td>{{ date('d-m-Y', strtotime($line->starting_date)) }}</td>
                        <td>{{ date('d-m-Y', strtotime($line->ending_date))  }}</td>
                        <td>{{ $line->status}}</td>
                        <td>
                            @if( $mode == 'admin' )
                                <a href="/leave_plan_admin/{{ $line->leave_plan->id }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true">View</a>
                            @elseif( $mode == 'normal' )
                                <a href="/leave_plans/{{ $line->leave_plan->id }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true">View</a>
                            @endif
                        </td>
                    </tr>
                    <?php $n++; ?>
                    @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Employee Name</th>
                    <th>Department</th>
                    <th>Leave Type</th>
                    <th>Days</th>
                    <th>Starting Date</th>
                    <th>Ending Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif
    @endforeach

@endsection
