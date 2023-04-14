@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">User Activities In The System</div>
                    <div class="page-title-subheading">
                        Below is a list of all activities done by user in the system
                    </div>
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
                    <th>No</th>
                    <th>User Name</th>
                    <th>Action</th>
                    <th>Item Name</th>
                    <th>Item ID</th>
                    <th>Description</th>
                    <th>Time</th>
                </tr>
                </thead>
                <tbody>

                <?php $n = 1; ?>
                @foreach($user_activities as $activity)
                <tr class='clickable-row' data-href="/user_activities/{{ $activity->id }}">
                    <td>{{ $n }}</td>
                    <td>{{ $activity->user->name ?? ''}}</td>
                    <td>{{ $activity->action }}</td>
                    <td>{{ $activity->item }}</td>
                    <td>{{ $activity->item_id }}</td>
                    <td>{{ $activity->description }}</td>
                    <td>{{ $activity->created_at }}</td>
                </tr>
                <?php $n++; ?>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>No</th>
                    <th>User Name</th>
                    <th>Action</th>
                    <th>Item Name</th>
                    <th>Item ID</th>
                    <th>Description</th>
                    <th>Time</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
