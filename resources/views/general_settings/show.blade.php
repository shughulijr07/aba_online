@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="pe-7s-culture"></i>
                </div>
                <div>
                    <div class="text-primary">General Settings</div>
                    <div class="page-title-subheading">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">

            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <h5 class="card-title">Settings Form</h5>
                            <form action="/general_settings" method="POST" enctype="multipart/form-data">
                                @include('general_settings.form')
                                <button class="mt-2 btn btn-primary">Update Settings</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection