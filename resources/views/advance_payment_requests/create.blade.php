@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <div class="text-primary">New Advance Payment Request</div>
                    <div class="page-title-subheading">
                        Please Fill The Form Below & Submit It
                    </div>
                </div>
            </div>

            <!--actions' menu starts here -->
            <!--actions' menu ends here -->

        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
            <div class="row">

                <div class="col-sm-12" >
                    <div class="main-card mb-3 card">
                        <div class="card-header-tab card-header">
                            <div class="card-header-title font-size-lg text-capitalize font-weight-bold">
                                <i class="header-icon lnr-list mr-3 text-primary opacity-6"> </i>
                                <span class="text-danger text-uppercase">Advance Payment Request Form</span>
                            </div>
                        </div>
                        <div class="card-body">
                            @include('advance_payment_requests.form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
