@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <div class="text-primary">Add New System</div>
                    <div class="page-title-subheading">
                        Add new System Role by completing the form below
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
                    <h5 class="card-title">System Role Form</h5>
                    <form action="/system_roles" method="POST">
                        @include('system_roles.form')
                        <button class="mt-2 btn btn-primary">Add Role</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection