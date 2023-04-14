@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <div class="text-primary">Add New Staff Dependent</div>
                    <div class="page-title-subheading">
                        Add new Staff Dependent by completing the form below
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
                    <h5 class="card-title">Staff Dependent Form</h5>
                    <form action="/staff_dependents" method="POST" enctype="multipart/form-data">
                        @include('staff.dependents.form')
                        <button class="mt-2 btn btn-primary">Add Staff Dependent</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection