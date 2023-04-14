@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-edit"></i>
                </div>
                <div>
                    <div class="text-primary">
                        Update Active Clients For The Month of <span class="text-danger">{{$months[$active_project->month]}}</span>
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
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
            <form action="/active_projects/{{$active_project->id}}" method="POST">
                @method('PATCH')
                @include('projects.active_projects.form')
            </form>
        </div>
    </div>

@endsection