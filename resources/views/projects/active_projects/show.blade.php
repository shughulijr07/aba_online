@extends('layouts.administrator.admin')


@section('content')

    <!-- title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div>
                    <div class="text-primary">
                        Active Clients For The Month of <span class="text-danger">{{$months[(int) $active_project->month]}}</span>
                        in <span class="text-danger">{{$active_project->year}}</span>
                    </div>
                    <div class="page-title-subheading"></div>
                </div>
            </div>

            <!--actions' menu starts here -->
            @include('layouts.administrator.page-title-actions')
            <!--actions' menu ends here -->

        </div>
    </div>


    <!-- data 1 -->
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title text-danger">Active Client List</h5>
            <div class="row">
                <div class="col-md-12">
                    <table style="width: 100%;" id="example1" class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Client Name.</th>
                            <th>Client No.</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $n = 1;?>
                        {{-- {{dd($all_projects)}} --}}
                        @foreach($all_projects as $project)
                            @if( in_array($project->number,$active_projects) )
                            <tr class="data-row">
                                <td>{{$n}}</td>
                                <td>{{ $project->name }}</td>
                                <td>{{ $project->number }}</td>
                            </tr>
                            @endif
                            <?php $n++;?>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection
