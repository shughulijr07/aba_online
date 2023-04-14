


    <div  class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/admin_leaves/10')}}">
        <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-warning border-primary card">
            <div class="widget-chat-wrapper-outer">
                <div class="widget-chart-content">
                    <div class="widget-title opacity-5 text-uppercase">
                        ({{ $leaveRequests['waitingForSPVApproval2'] }})
                        Waiting For SPV Approval
                    </div>
                    <div class="widget-numbers mt-2 fsize-3 mb-0 w-100">
                        <div class="widget-chart-flex align-items-center">
                            <div>
                                Approve Leave Request (SPV)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div  class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/admin_time_sheets/20')}}">
        <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-success border-primary card">
            <div class="widget-chat-wrapper-outer">
                <div class="widget-chart-content">
                    <div class="widget-title opacity-5 text-uppercase">({{$timeSheets['waitingForSPVApproval2']}}) Waiting For SPV Approval</div>
                    <div class="widget-numbers mt-2 fsize-3  mb-0 w-100">
                        <div class="widget-chart-flex align-items-center">
                            <div>
                                Approve Time Sheet (SPV)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div  class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/admin_performance_objectives/20')}}">
        <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-warning border-primary card">
            <div class="widget-chat-wrapper-outer">
                <div class="widget-chart-content">
                    <div class="widget-title opacity-5 text-uppercase">
                        ({{ $performanceObjectives['waitingForSPVApproval2'] }})
                        Waiting For SPV Approval
                    </div>
                    <div class="widget-numbers mt-2 fsize-3 mb-0 w-100">
                        <div class="widget-chart-flex align-items-center">
                            <div>
                                Approve Objectives (SPV)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div  class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/admin_travel_requests/10')}}">
        <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-success border-primary card">
            <div class="widget-chat-wrapper-outer">
                <div class="widget-chart-content">
                    <div class="widget-title opacity-5 text-uppercase">({{ $travelRequests['waitingForSPVApproval2'] }}) Waiting For SPV Approval</div>
                    <div class="widget-numbers mt-2 fsize-3  mb-0 w-100">
                        <div class="widget-chart-flex align-items-center">
                            <div>
                                Approve Travel Request (SPV)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div  class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/admin_leave_plans/20')}}">
        <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-warning border-primary card">
            <div class="widget-chat-wrapper-outer">
                <div class="widget-chart-content">
                    <div class="widget-title opacity-5 text-uppercase">({{$leavePlans['waitingForSPVApproval2']}}) Waiting For SPV Approval</div>
                    <div class="widget-numbers mt-2 fsize-3 mb-0 w-100">
                        <div class="widget-chart-flex align-items-center">
                            <div>
                                Approve Leave Plan (SPV)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div  class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/admin_requisition_requests/10')}}">
        <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-success border-primary card">
            <div class="widget-chat-wrapper-outer">
                <div class="widget-chart-content">
                    <div class="widget-title opacity-5 text-uppercase">({{ $requisitionRequests['waitingForSPVApproval2'] }}) Waiting For SPV Approval</div>
                    <div class="widget-numbers mt-2 fsize-3  mb-0 w-100">
                        <div class="widget-chart-flex align-items-center">
                            <div>
                                Approve Requisition Request (SPV)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div  class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/admin_payment_requests/10')}}">
        <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-success border-primary card">
            <div class="widget-chat-wrapper-outer">
                <div class="widget-chart-content">
                    <div class="widget-title opacity-5 text-uppercase">({{ $paymentRequests['waitingForSPVApproval2'] }}) Waiting For SPV Approval</div>
                    <div class="widget-numbers mt-2 fsize-3  mb-0 w-100">
                        <div class="widget-chart-flex align-items-center">
                            <div>
                                Approve Payment Request (SPV)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div  class="col-md-6 col-lg-6 clickable-row" data-href="{{url('/admin_retirement_requests/10')}}">
        <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-success border-primary card">
            <div class="widget-chat-wrapper-outer">
                <div class="widget-chart-content">
                    <div class="widget-title opacity-5 text-uppercase">({{ $retirementRequests['waitingForSPVApproval2'] }}) Waiting For SPV Approval</div>
                    <div class="widget-numbers mt-2 fsize-3  mb-0 w-100">
                        <div class="widget-chart-flex align-items-center">
                            <div>
                                Approve Retirement Request (SPV)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}


