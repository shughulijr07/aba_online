@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-edit"></i>
                </div>
                <div>
                    <div class="text-primary">Edit {{$staff->first_name.' '.$staff->last_name}} Information</div>
                    <div class="page-title-subheading">
                        Please complete the form below to update staff's inforamtion
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
                    <h5 class="card-title">Staff Form</h5>
                    <form action="/staff/{{$staff->id}}" method="POST" enctype="multipart/form-data">
                        @method('PATCH')
                        @include('staff.staff.form')
                        <button class="mt-2 btn btn-primary">Update Staff</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection