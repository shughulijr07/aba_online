<ul class="tabs-animated-shadow tabs-animated nav nav-justified tabs-shadow-bordered p-3">
    <li class="nav-item">
        <a role="tab" class="nav-link show active" id="tab-c-0" data-toggle="tab" href="#tab-animated-0" aria-selected="true">
            <span>Message</span>
        </a>
    </li>
</ul>
<div class="tab-content" style="padding-bottom: 30px;">
    <div class="tab-pane show active" id="tab-animated-0" role="tabpanel">
        <div class="">
            <div class="">
                <div class="p-3">
                    <div class="vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                        <div class="vertical-timeline-item vertical-timeline-element">
                            <div>
                                @if($advance_payment_request->status == '0')
                                    <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                    <div class="vertical-timeline-element-content bounce-in">
                                        <h4 class="timeline-title text-danger">ADVANCE PAYMENT REQUEST RETURNED FOR CORRECTIONS</h4>
                                        <p>
                                            Hello, your Advance Payment have been returned for corrections by {{$returnedByTitle}}
                                            .<br><br>
                                            .<br><br>
                                            - <span class="text-danger">Reason & Correction Instruction</span> <br>
                                            <span class="ml-2">{{$advance_payment_request->comments}}</span> <br><br>
                                            - <span class="text-danger">Time of Returning</span> <br>
                                            <span class="ml-2">{{date("d-m-Y H:i:s", strtotime($advance_payment_request->updated_at))}}</span>
                                        </p>

                                        <p>
                                            <br><br>
                                            <span class="text-danger">For Assistance</span><br>
                                            - Please contact system Administrator or Human Resource Manager
                                        </p>
                                    </div>
                                @endif
                                @if($advance_payment_request->status == '10')
                                    <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                    <div class="vertical-timeline-element-content bounce-in">
                                        <h4 class="timeline-title text-danger">ADVANCE PAYMENT REQUEST SUBMITTED</h4>
                                        <p>
                                            Hello, your payment Request have been submitted successfully, please wait for Supervisor Approval.<br>
                                            - Time of Submission | {{date("d-m-Y H:i:s", strtotime($advance_payment_request->updated_at))}}
                                        </p>

                                        <p>
                                            <br><br>
                                            <span class="text-danger">For Assistance</span><br>
                                            - Please contact system Administrator or Human Resource Manager
                                        </p>
                                    </div>
                                @endif

                                @if($advance_payment_request->status == '20')
                                    <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                    <div class="vertical-timeline-element-content bounce-in">
                                        <h4 class="timeline-title text-danger">ADVANCE PAYMENT REQUEST APPROVED BY SUPERVISOR</h4>
                                        <p>
                                            Hello, your payment Request have been approve by Supervisor, currently it is waiting for Accountant
                                            Approval.<br>
                                            - Time of Approving | {{date("d-m-Y H:i:s", strtotime($advance_payment_request->updated_at))}}
                                        </p>

                                        <p>
                                            <br><br>
                                            <span class="text-danger">For Assistance</span><br>
                                            - Please contact system Administrator or Human Resource Manager
                                        </p>
                                    </div>
                                @endif



                                @if($advance_payment_request->status == '30')
                                    <span class="vertical-timeline-element-icon bounce-in">
                                                                    <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                                </span>
                                    <div class="vertical-timeline-element-content bounce-in">
                                        <h4 class="timeline-title text-danger">ADVANCE PAYMENT REQUEST APPROVED BY ACCOUNTANT</h4>
                                        <p>
                                            Hello, your Advance Payment Request have been approve by Accountant, currently it is waiting for Finance Director's
                                            Approval.<br>
                                            - Time of Approving | {{date("d-m-Y H:i:s", strtotime($advance_payment_request->updated_at))}}
                                        </p>

                                        <p>
                                            <br><br>
                                            <span class="text-danger">For Assistance</span><br>
                                            - Please contact system Administrator or Human Resource Manager
                                        </p>
                                    </div>
                                @endif


                                @if($advance_payment_request->status == '40')
                                    <span class="vertical-timeline-element-icon bounce-in">
                                                                    <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                                </span>
                                    <div class="vertical-timeline-element-content bounce-in">
                                        <h4 class="timeline-title text-danger">ADVANCE PAYMENT REQUEST APPROVED BY FINANCE DIRECTOR</h4>
                                        <p>
                                            Hello, your Advance Payment Request have been approve by Finance Director, currently it is waiting for Managing Director's
                                            Approval.<br>
                                            - Time of Approving | {{date("d-m-Y H:i:s", strtotime($advance_payment_request->updated_at))}}
                                        </p>

                                        <p>
                                            <br><br>
                                            <span class="text-danger">For Assistance</span><br>
                                            - Please contact system Administrator or Human Resource Manager
                                        </p>
                                    </div>
                                @endif



                                @if($advance_payment_request->status == '50')
                                    <span class="vertical-timeline-element-icon bounce-in">
                                                                    <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                                </span>
                                    <div class="vertical-timeline-element-content bounce-in">
                                        <h4 class="timeline-title text-danger">ADVANCE PAYMENT REQUEST APPROVED</h4>
                                        <p>
                                            Hello, your Advance Payment Request have been approved successfully.<br>
                                            - Time of Approving | {{date("d-m-Y H:i:s", strtotime($advance_payment_request->updated_at))}}
                                        </p>

                                        <p>
                                            <br><br>
                                            <span class="text-danger">For Assistance</span><br>
                                            - Please contact system Administrator or Human Resource Manager
                                        </p>
                                    </div>
                                @endif



                                @if($advance_payment_request->status == '99')
                                    <span class="vertical-timeline-element-icon bounce-in">
                                                                    <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                                </span>
                                    <div class="vertical-timeline-element-content bounce-in">
                                        <h4 class="timeline-title text-danger">ADVANCE PAYMENT REQUEST REJECTED</h4>
                                        <p>
                                            Hello, this Advance Payment have been rejected by {{$rejectedByTitle}}
                                            .<br><br>
                                            - <span class="text-danger">Reason Of Rejection</span> <br>
                                            <span class="ml-2">{{$advance_payment_request->comments}}</span> <br><br>
                                            - <span class="text-danger">Time of Rejection</span> <br>
                                            <span class="ml-2">{{date("d-m-Y H:i:s", strtotime($advance_payment_request->updated_at))}}</span>
                                        </p>

                                        <p>
                                            <br>
                                            - <span class="text-danger">For Assistance</span><br>
                                            <span class="ml-2">Please contact system Administrator or Human Resource Manager</span>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
