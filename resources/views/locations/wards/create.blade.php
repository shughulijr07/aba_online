@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <div class="text-primary">Add Ward</div>
                    <div class="page-title-subheading">
                        Please complete the form below to add a ward
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
                    <h5 class="card-title">Ward Form</h5>
                    <form action="/wards" method="POST">
                        @include('locations.wards.form')
                        <button class="mt-2 btn btn-primary">Add Ward</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection