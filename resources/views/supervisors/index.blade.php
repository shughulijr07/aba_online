@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">List Of Supervisors</div>
                    <div class="page-title-subheading"> </div>
                </div>
            </div>

            <!--actions' menu starts here -->
            @include('layouts.administrator.page-title-actions')
            <!--actions' menu ends here -->

        </div>
    </div>
    <div class="main-card mb-3 card">
        <div class="card-body">
            <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Supervisor Name</th>
                    <th>Employee No.</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <?php $n=1; ?>
                @foreach($supervisors as $supervisor)
                    <tr>
                        <td>{{ $n }}</td>
                        <td>{{ ucwords(strtolower($supervisor->staff->first_name.' '.$supervisor->staff->last_name))}}</td>
                        <td>{{ $supervisor->staff->staff_no }}</td>
                        <td>
                            <a href="/staff/{{ $supervisor->staff_id }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true">View</a>
                            <a href="/supervisors/delete/{{ $supervisor->id }}" class="btn btn-danger btn-sm" role="button" aria-pressed="true">Remove</a>
                        </td>
                    </tr>
                    <?php $n++; ?>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>#</th>
                    <th>Supervisor Name</th>
                    <th>Staff No.</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
