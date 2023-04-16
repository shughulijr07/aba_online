@extends('layouts.administrator.admin')


@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">Time Sheets</div>
                    {{-- <div class="page-title-subheading">
                        Below is a list of timesheets. Click on the button <strong><span class="text-danger">View</span></strong> to see more information in the timesheet.
                    </div> --}}
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
                        <th>Staff</th>
                        @if (Auth::user()->role_id == 1)
                        <th>Supervisor</th>
                        @endif
                        <th>Month</th>
                        <th>Year</th>
                        <th>Status</th>
                        <th>Created On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($time_sheets as $time_sheet)
                        @php
                         $spv = \App\Models\Staff::find($time_sheet->responsible_spv);
                        @endphp
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$time_sheet->first_name}} {{$time_sheet->middle_name}} {{$time_sheet->last_name}}</td>
                            @if (Auth::user()->role_id == 1)
                            <td>{{$spv->first_name}} {{$spv->last_name}}</td>
                            @endif
                            <td>{{ date("F", mktime(0, 0, 0, $time_sheet->month, 10)) }}</td>
                            <td>{{$time_sheet->year}}</td>
                            <td>{{App\Models\TimeSheet::findStatus($time_sheet->status)}}</td>
                            <td>{{$time_sheet->created_at}}</td>
                            <td>
                                <a href="/assign_client_task/{{$time_sheet->id}}" class="btn btn-sm btn-info">Edit</a>
                                {{-- <a href="{{ route('view-sheet', ['sheet' => $time_sheet->id]) }}" class="btn btn-sm btn-success">view</a> --}}
                                <a href="/delete_draft_timesheets/{{$time_sheet->id}}" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>

                        @php
                            $i += 1;
                        @endphp
                    @endforeach

              
                </tbody>
                
            </table>
        </div>
    </div>
@endsection
