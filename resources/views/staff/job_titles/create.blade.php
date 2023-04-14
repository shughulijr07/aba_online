@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <div class="text-primary">Add New New Staff Job Title</div>
                    <div class="page-title-subheading">
                        To add new Staff Job Title, complete the form below
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
                    <h5 class="card-title">Staff Job Title Form</h5>
                    <form action="/staff_job_titles" method="POST">
                        @include('staff.job_titles.form')
                        <button class="mt-2 btn btn-primary">Add Title</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection