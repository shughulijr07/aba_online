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
                    <div class="text-primary">Dashboard</div>
                    <div class="page-title-subheading"></div>
                </div>
            </div>

        <!--actions' menu starts here -->
        <!--actions' menu ends here -->

        </div>
    </div>



    <div class="row" id="my_items">
        @include('admin.dashboard_sections.my_items')
    </div>


    <div class="row" id="spv_items">
        @include('admin.dashboard_sections.spv_items')
    </div>


    <div class="row" id="special_items">

    </div>


@endsection
