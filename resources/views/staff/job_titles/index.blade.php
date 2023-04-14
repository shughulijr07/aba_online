@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">List Of Staffs' Job Titles</div>
                    <div class="page-title-subheading">
                        Below is a list of GS1 Tanzania Staffs' Job Titles
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
                    <th>Title</th>
                </tr>
                </thead>
                <tbody>

                @foreach($staff_job_titles as $staff_job_title)
                <tr class='clickable-row' data-href="/staff_job_titles/{{ $staff_job_title->id }}">
                    <td>{{ $staff_job_title->id }}</td>
                    <td>{{ $staff_job_title->title }}</td>
                </tr>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>Id</th>
                    <th>Title</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
