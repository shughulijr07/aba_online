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
                </div>
            </div>

        <!--actions' menu starts here -->
        <!--actions' menu ends here -->

        </div>
    </div>





    <div class="row" id="my_items">
        @include('admin.dashboard_sections.my_items')
    </div>


    <div class="row" id="spv_items" style="display: none">
        @include('admin.dashboard_sections.spv_items')
    </div>


    <div class="row" id="special_items">


        <div  class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/admin_travel_requests/20')}}">
            <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-warning border-primary card">
                <div class="widget-chat-wrapper-outer">
                    <div class="widget-chart-content">
                        <div class="widget-title opacity-5 text-uppercase">
                            ({{ $travelRequests['waitingForACCApproval2']  }})
                            Waiting For ACC Approval
                        </div>
                        <div class="widget-numbers mt-2 fsize-3 mb-0 w-100">
                            <div class="widget-chart-flex align-items-center">
                                <div>
                                    Approve Travel Request (ACC)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div  class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/admin_requisition_requests/20')}}">
            <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-warning border-primary card">
                <div class="widget-chat-wrapper-outer">
                    <div class="widget-chart-content">
                        <div class="widget-title opacity-5 text-uppercase">
                            ({{ $requisitionRequests['waitingForACCApproval2']  }})
                            Waiting For ACC Approval
                        </div>
                        <div class="widget-numbers mt-2 fsize-3 mb-0 w-100">
                            <div class="widget-chart-flex align-items-center">
                                <div>
                                    Approve Requisition Request (ACC)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div  class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/admin_payment_requests/20')}}">
            <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-warning border-primary card">
                <div class="widget-chat-wrapper-outer">
                    <div class="widget-chart-content">
                        <div class="widget-title opacity-5 text-uppercase">
                            ({{ $paymentRequests['waitingForACCApproval2']  }})
                            Waiting For ACC Approval
                        </div>
                        <div class="widget-numbers mt-2 fsize-3 mb-0 w-100">
                            <div class="widget-chart-flex align-items-center">
                                <div>
                                    Approve Payment Request (ACC)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
