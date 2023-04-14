@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">List Of T-MARC Tanzania Staff</div>
                    <div class="page-title-subheading">
                        Below is a list of T-MARC Tanzania Staff
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
                    <th>Id</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Title</th>
                    <th>Email</th>
                    <th>Phone No.</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>

                <?php $n=1; ?>
                @foreach($staff as $staff)
                <tr class='clickable-row' data-href="/staff/{{ $staff->id }}">
                    <td>{{ $n }}</td>
                    <td>{{ $staff->first_name }}</td>
                    <td>{{ $staff->last_name }}</td>
                    <td>{{ $staff->gender }}</td>
                    <td>{{ $staff->jobTitle->title }}</td>
                    <td>{{ $staff->official_email }}</td>
                    <td>{{ $staff->phone_no }}</td>
                    <td>{{ $staff->staff_status }}</td>
                </tr>
                <?php $n++; ?>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>Id</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Title</th>
                    <th>Email</th>
                    <th>Phone No.</th>
                    <th>Status</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
