@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">List Of Active Clients (By Month)</div>
                    <div class="page-title-subheading">

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
                    <th>#</th>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <?php $n=1;?>
                @foreach($active_projects as $active_project)
                <tr class='clickable-row' data-href="/active_projects/{{ $active_project->id }}">
                    <td>{{ $n }}</td>
                    <td>{{ $active_project->year }}</td>
                    <td>{{ $months[(int)$active_project->month] }}</td>
                    <td>
                        <a href="/active_projects/{{ $active_project->id }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true">View</a>
                    </td>
                </tr>
                <?php $n++;?>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>#</th>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
