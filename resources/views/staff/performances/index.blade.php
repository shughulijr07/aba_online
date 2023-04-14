@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">Employee Performance</div>
                    <div class="page-title-subheading">
                        Below is a list of performances in all years. Click on the button
                        <strong><span class="text-danger">View</span></strong> to see the more information for
                        the performance in a particular year.
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
                    <th>Employee Name</th>
                    <th>Year</th>
                    <th>1st Quoter</th>
                    <th>2nd Quoter</th>
                    <th>3rd Quoter</th>
                    <th>4th Quoter</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <?php $n=1; ?>
                @foreach($staff_performances as $staff_performance)
                <tr class='clickable-row' data-href="/staff_performances/{{ $staff_performance->id }}">
                    <td>{{ $n }}</td>
                    <td>{{ ucwords(strtolower($staff_performance->staff->first_name.' '.$staff_performance->staff->last_name))}}</td>
                    <td>{{ $staff_performance->year  }}</td>
                    <td>{{ $staff_performance->first_quoter_spv_marks  }}</td>
                    <td>{{ $staff_performance->second_quoter_spv_marks  }}</td>
                    <td>{{ $staff_performance->third_quoter_spv_marks  }}</td>
                    <td>{{ $staff_performance->fourth_quoter_spv_marks}}</td>
                    <td>
                        <a href="/staff_performances/{{ $staff_performance->id }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true">View</a>
                    </td>
                    <?php $n++; ?>
                </tr>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Employee Name</th>
                    <th>Year</th>
                    <th>1st Quoter</th>
                    <th>2nd Quoter</th>
                    <th>3rd Quoter</th>
                    <th>4th Quoter</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
