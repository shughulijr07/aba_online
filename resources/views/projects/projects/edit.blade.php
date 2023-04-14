@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-edit"></i>
                </div>
                <div>
                    <div class="text-primary">Edit Client</div>
                    <div class="page-title-subheading">
                        Please complete the form below to update Client
                    </div>
                </div>
            </div>

            <!--actions' menu starts here -->
            @include('layouts.administrator.page-title-actions')
            <!--actions' menu ends here -->
    
        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Client Form</h5>
                    <form action="/projects/{{$project->id}}" method="POST">
                        @method('PATCH')
                        @include('projects.projects.form')
                        <button class="mt-2 btn btn-primary">Update Client</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection