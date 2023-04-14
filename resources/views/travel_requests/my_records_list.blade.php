@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">My Travelling Records</div>
                    <div class="page-title-subheading">
                        Applied Filters <strong><span class="text-danger">Year : {{$year}}</span></strong>
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
                    <th>Requested By</th>
                    <th>Requested On</th>
                    <th>Departure Date</th>
                    <th>Returning Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <?php $n = 1; ?>
                @foreach($travel_requests as $travel_request)
                <tr class='clickable-row-new-tab' data-href="{{url('/travel_request_statement')}}/{{ $travel_request->id }}">
                    <td>{{ $n }}</td>
                    <td>{{ ucwords($travel_request->staff->first_name.' '.$travel_request->staff->last_name) }}</td>
                    <td>{{ date("d-m-Y", strtotime($travel_request->departure_date))  }}</td>
                    <td>{{ date("d-m-Y", strtotime($travel_request->returning_date))  }}</td>
                    <td>{{ date("d-m-Y H:i:s", strtotime($travel_request->created_at)) }}</td>
                    <td>{{ $travel_request_statuses[$travel_request->status]}}</td>
                    <td>
                        <a href="/travel_request_statement/{{ $travel_request->id }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true" target="_blank">View</a>
                    </td>
                </tr>
                <?php $n++; ?>
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
