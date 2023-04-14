@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">Performance Objectives Which Are <span class="orange-text">{{$performance_objective_statuses[$performance_objective_status]}}</span></div>
                    <div class="page-title-subheading">
                        Below is a list of performance objectives. Click on the button <strong><span class="text-danger">View</span></strong> to see more information.
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
                    <th>For The Year</th>
                    <th>Submission Date</th>
                    <th>Status</th>
                    <th>Supervisor</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <?php $n = 1; ?>
                @foreach($performance_objectives as $performance_objective)
                <?php $supervisor = \App\Staff::find($performance_objective->responsible_spv); ?>
                <tr class='clickable-row' data-href="/performance_objective_admin/{{ $performance_objective->id }}">
                    <td>{{ $n }}</td>
                    <td>{{ ucwords($performance_objective->staff->first_name.' '.$performance_objective->staff->last_name) }}</td>
                    <td>{{ $performance_objective->year }}</td>
                    <td>{{ date("d-m-Y H:i:s", strtotime($performance_objective->created_at)) }}</td>
                    <td>{{ $performance_objective_statuses[$performance_objective_status]}}</td>
                    <td>{{ ucwords($supervisor->first_name.' '.$supervisor->last_name) }}</td>
                    <td>
                        <a href="/performance_objective_admin/{{ $performance_objective->id }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true">View</a>
                    </td>
                </tr>
                <?php $n++; ?>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Submitted By</th>
                    <th>For The Year</th>
                    <th>Submission Date</th>
                    <th>Status</th>
                    <th>Supervisor</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
