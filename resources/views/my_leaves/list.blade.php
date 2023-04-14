@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">My Leaves</div>
                    <div class="page-title-subheading">
                        Applied Filters <strong><span class="text-danger">Year : {{$year}}</span></strong> |
                        <strong>
                            <span class="text-danger">
                                Type Of Leave : {{ $leave_type == 'all' ? 'All' : $leave_types[$leave_type]['name'] }}
                            </span>
                        </strong>.
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
                    <th>Leave Type</th>
                    <th>Date Of Request</th>
                    <th>Days Requested</th>
                    <th>Starting Date</th>
                    <th>Ending Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach($leaves as $leave)
                <tr class='clickable-row-new-tab' data-href="{{url('/leave_statement')}}/{{ $leave->id }}">
                    <td>{{ $leave->id }}</td>
                    <td>{{ $leave_types[$leave->type]['name']}}</td>
                    <td>{{ date('d-m-Y h:m A',strtotime($leave->created_at))}}</td>
                    <td class="text-center">{{ $leave->calculate_no_of_days_btn_dates($leave->starting_date,$leave->ending_date) }}</td>
                    <td>{{ date('d-m-Y',strtotime($leave->starting_date)) }}</td>
                    <td>{{ date('d-m-Y',strtotime($leave->ending_date)) }}</td>
                    <td>
                        <a href="/leave_statement/{{ $leave->id }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true" target="_blank">View</a>
                    </td>
                </tr>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Leave Type</th>
                    <th>Date Of Request</th>
                    <th>Days Requested</th>
                    <th>Starting Date</th>
                    <th>Ending Date</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
