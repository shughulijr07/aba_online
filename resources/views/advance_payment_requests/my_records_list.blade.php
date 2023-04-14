@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">My Advance Payment Records</div>
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
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <?php $n = 1; ?>
                @foreach($advance_payment_requests as $advance_payment_request)
                <tr class='clickable-row-new-tab' data-href="{{url('/advance_payment_request_statement')}}/{{ $advance_payment_request->id }}">
                    <td>{{ $n }}</td>
                    <td>{{ ucwords($advance_payment_request->staff->first_name.' '.$advance_payment_request->staff->last_name) }}</td>
                    <td>{{ date("d-m-Y", strtotime($advance_payment_request->request_date))  }}</td>
                    <td>{{ $request_statuses[$advance_payment_request->status]}}</td>
                    <td>
                        <a href="/advance_payment_request_statement/{{ $advance_payment_request->id }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true" target="_blank">View</a>
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
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
