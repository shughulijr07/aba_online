@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">Type Of Leaves</div>
                    <div class="page-title-subheading"></div>
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
                    <th>Name</th>
                    <th>Key</th>
                    <th>Days</th>
                    <th>Period (Years)</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>

                <?php $n = 1;?>
                @foreach($leave_types as $leave_type)
                <tr class='clickable-row' data-href="/leave_types/{{ $leave_type->id }}">
                    <td>{{ $n}}</td>
                    <td>{{ $leave_type->name }}</td>
                    <td>{{ $leave_type->key }}</td>
                    <td>{{ $leave_type->days }}</td>
                    <td>{{ $leave_type->period }}</td>
                    <td>{{ $leave_type->status }}</td>
                </tr>
                <?php $n++;?>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Key</th>
                    <th>Days</th>
                    <th>Period (Years)</th>
                    <th>Status</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
