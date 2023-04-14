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
                    <div class="text-primary">Advance Payment Request Submitted By {{$employee_name}}</div>
                    <div class="page-title-subheading">Below is information of Advance Payment Request</div>
                </div>
            </div>

        <!--actions' menu starts here -->
        <!--actions' menu ends here -->

        </div>
    </div>


    <!-- data 1 -->
    <div class="container" style="min-width: 90%">
        <div class="row">


            <!-- Request Form Display Section -->
            <div class="col-md-12 col-lg-9 col-xl-9"><div class="row">
                    <div class="col-sm-12" >
                        <div class="main-card mb-3 card">
                            <div class="card-header-tab card-header">
                                <div class="card-header-title font-size-lg text-capitalize font-weight-bold">
                                    <i class="header-icon lnr-list mr-3 text-primary opacity-6"> </i>
                                    <span class="text-danger text-uppercase">Advance Payment Request Form</span>
                                </div>
                            </div>
                            <div class="card-body">
                                @include('advance_payment_requests.form_display')
                            </div>
                        </div>

                    </div>

                </div>
            </div>
            <!-- End Of Request Form Display Section -->

            <!-- Message Section -->
            <div class="col-md-12 col-lg-3 col-xl-3">
                @if(session()->has('message'))
                    <div  class="mb-3 card alert alert-primary" id="notifications-div">
                        <div class="p-3 card-body ">
                            <div class="text-center">
                                <h5 class="text-white" id="message">{{session()->get('message')}}</h5>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="mb-3 card">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                            <i class="header-icon pe-7s-menu icon-gradient bg-happy-itmeo"> </i>
                            Advance Payment Request Status
                        </div>
                    </div>
                    <div class="p-0 card-body">
                        <div class="dropdown-menu-header mt-0 mb-0">
                            <div class="dropdown-menu-header-inner bg-heavy-rain">
                                <div class="menu-header-image opacity-1" style="background-image: url('assets/images/dropdown-header/city3.jpg');"></div>
                                <div class="menu-header-content text-dark">
                                    <h5 class="menu-header-title">{{$request_statuses[$advance_payment_request->status]}}</h5>
                                    <h6 class="menu-header-subtitle"></h6>
                                </div>
                            </div>
                        </div>
                        @include("advance_payment_requests.messages")
                    </div>
                </div>
            </div>
            <!-- End Of Message Section -->


        </div>
    </div>

    <script type="text/javascript">
        $(document).ready( function(){
            $('#notifications-div').delay(5000).fadeOut('slow');
        });

        $("#approve_switch").on("click",function(){
            $("#return_timesheet_div").hide();
            $("#change_spv_div").hide();
            $("#reject_advance_payment_request_div").hide();
            $("#approve_timesheet_div").show("slow");
        });

        $("#return_switch").on("click",function(){
            $("#approve_timesheet_div").hide();
            $("#change_spv_div").hide();
            $("#reject_advance_payment_request_div").hide();
            $("#return_timesheet_div").show("slow");
        });

        $("#change_spv_switch").on("click",function(){
            $("#return_timesheet_div").hide();
            $("#approve_timesheet_div").hide();
            $("#reject_advance_payment_request_div").hide();
            $("#change_spv_div").show("slow");
        });

        $("#reject_switch").on("click",function(){
            $("#return_timesheet_div").hide();
            $("#change_spv_div").hide();
            $("#approve_timesheet_div").hide();
            $("#reject_advance_payment_request_div").show("slow");
        });

    </script>

    <!--scripts -->
    <script type="text/javascript">


    </script>


@endsection
