@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">Retirement Requests Which Are <span class="orange-text">{{$travel_request_statuses[$travel_request_status]}}</span></div>
                    <div class="page-title-subheading">
                        Below is a list of Retirement request. Click on the button <strong><span class="text-danger">View</span></strong>to see more information in the payment request.
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
                    <th>Purpose of Payment</th>
                    <th>Status</th>
                    <th>Supervisor</th>
                    <th>Retirement Request</th>
                </tr>
                </thead>
                <tbody>

                <?php $n = 1; ?>
                @foreach($travel_requests as $travel_request)
                    <?php $supervisor = \App\Staff::find($travel_request->responsible_spv); ?>
                    <tr class='clickable-row' data-href="/new_retirement_request/{{ $travel_request->id }}">
                        <td>{{ $n }}</td>
                        <td>{{ ucwords($travel_request->staff->first_name.' '.$travel_request->staff->last_name) }}</td>
                        <td>{{ date("d-m-Y", strtotime($travel_request->requested_date))  }}</td>
                        <td>{{ date("d-m-Y", strtotime($travel_request->purpose_of_payment))  }}</td>
                        <td>{{ $travel_request_statuses[$travel_request_status]}}</td>
                        <td>{{ ucwords($supervisor->first_name.' '.$supervisor->last_name) }}</td>
                        <td>
                            <a href="/new_retirement_request/{{ $travel_request->id }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true">Go</a>
                        </td>
                    </tr>
                    <?php $n++; ?>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Requested By</th>
                    <th>Requested On</th>
                    <th>Purpose of Payment</th>
                    <th>Status</th>
                    <th>Supervisor</th>
                    <th>Retirement Request</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
