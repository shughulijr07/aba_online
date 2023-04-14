@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-edit"></i>
                </div>
                <div>
                    <div class="text-danger">Time Sheet Submission Locked</div>
                    <div class="page-title-subheading">
                        Time sheet submission for the month
                        <span class="text-danger font-weight-bold">{{$months[$time_sheet_late_submission->month].', '.$time_sheet_late_submission->year}}</span>
                        of have been locked because submission deadline have passed. To unlock and submit time sheet
                        fill the form below and send the unlocking request to Human Resource Manager.
                    </div>
                </div>
            </div>

            <!--actions' menu starts here -->
            <!--actions' menu ends here -->
    
        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Late Submission Unlocking Request Form</h5>
                    <form action="/time_sheet_late_submissions/{{$time_sheet_late_submission->id}}" method="POST" enctype="multipart/form-data">
                        @method('PATCH')
                        @include('time_sheet_late_submissions.form')
                        <button class="mt-2 btn btn-primary">Send Late Time Sheet Submission Unlocking Request</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection