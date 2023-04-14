<ul class="tabs-animated-shadow tabs-animated nav nav-justified tabs-shadow-bordered p-3">
    <li class="nav-item">
        <a role="tab" class="nav-link show active" id="tab-c-0" data-toggle="tab" href="#tab-animated-0" aria-selected="true">
            <span>Message</span>
        </a>
    </li>
    @can('view-menu','leave_requests_for_approving_menu_item')
        @if($isCurrentUserAllowedToApproveThisRequest)
            <li class="nav-item">
                <a role="tab" class="nav-link show" id="tab-c-1" data-toggle="tab" href="#tab-animated-1" aria-selected="false">
                    <span>Actions</span>
                </a>
            </li>
        @endif
    @endcan
</ul>
<div class="tab-content" style="padding-bottom: 30px;">

    <!-- message content starts here -->
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
                                        <h4 class="timeline-title text-danger">RETURNED FOR CORRECTIONS</h4>
                                        <p>
                                            This Advance Payment Request have been returned for corrections by {{$returnedByTitle}}
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
                                        <h4 class="timeline-title text-danger">NEW ADVANCE PAYMENT REQUEST SUBMITTED</h4>
                                        <p>
                                            This Advance Payment Request have been submitted by employee. Currently it is still waiting for Supervisor's Approval.<br>
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
                                            This Advance Payment Request have been Approved by Supervisor, currently it is waiting for Accountant Approval.<br>
                                            - Time of Approval | {{date("d-m-Y H:i:s", strtotime($advance_payment_request->updated_at))}}
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
                                            This Advance Payment Request have been Approved by Accountant, currently it is waiting for
                                            Finance Director's Approval.<br>
                                            - Time of Approval | {{date("d-m-Y H:i:s", strtotime($advance_payment_request->updated_at))}}
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
                                            This Advance Payment Request have been Approved by Finance Director, currently it is waiting for
                                            Managing Director's Approval.<br>
                                            - Time of Approval | {{date("d-m-Y H:i:s", strtotime($advance_payment_request->updated_at))}}
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
                                            This payment Request have been Approved successfully.<br>
                                            - Time of Approval | {{date("d-m-Y H:i:s", strtotime($advance_payment_request->updated_at))}}
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
                                            This Advance Payment Request have been rejected by {{$rejectedByTitle}}
                                            .<br><br>
                                            - <span class="text-danger">Reason Of Rejection</span> <br>
                                            <span class="ml-2">{{$advance_payment_request->comments}}</span> <br><br>
                                            - <span class="text-danger">Time of Rejection</span> <br>
                                            <span class="ml-2">{{date("d-m-Y H:i:s", strtotime($advance_payment_request->updated_at))}}</span>
                                        </p>

                                        <p>
                                            <br><br>
                                            <span class="text-danger">For Assistance</span><br>
                                            - Please contact system Administrator or Human Resource Manager
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
    <!-- message content ends here -->

    <!-- actions starts here -->
    @if($isCurrentUserAllowedToApproveThisRequest)
        <div class="tab-pane show" id="tab-animated-1" role="tabpanel">
            <div class="">
                <div class="">
                    <div class="row text-center">
                        <div class="col-sm-12 mt-3">
                            <button class="mb-2 mr-2 btn-pill btn btn-outline-primary" id="approve_switch">Approve</button>
                            <button class="mb-2 mr-2 btn-pill btn btn-outline-danger" id="return_switch">Return</button>
                            <button class="mb-2 mr-2 btn-pill btn btn-outline-danger" id="reject_switch">Reject</button>

                            @if($supervisors_mode == 1)
                                <button class="mb-2 mr-2 btn-pill btn btn-outline-primary" id="change_spv_switch">SPV</button>
                            @endif
                        </div>
                    </div>
                    <div class="p-3" id="approve_advance_payment_request_div">
                        <form action="/approve_advance_payment_request" method="POST" enctype="multipart/form-data" id="approve_advance_payment_request_form">
                            @csrf
                            {{ csrf_field() }}

                            <fieldset>
                                <legend class="text-primary">Approve Advance Payment Request</legend>
                                <input name="advance_payment_request_id" value="{{$advance_payment_request->id}}" type="text" style="display: none;" readonly>
                                <div class="form-row mt-2">
                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label for="comments" class="">
                                                Comments <span class="text-danger"></span>
                                            </label>
                                            <textarea name="comments" id="comments" class="form-control @error('comments') is-invalid @enderror" value="{{ old('comments') ?? $comments}}" autocomplete="off"></textarea>

                                            @error('comments')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <button class="mt-2 btn btn-primary" id="approve_advance_payment_request_btn">Approve Advance Payment Request</button>
                        </form>
                    </div>
                    <div class="p-3" id="return_advance_payment_request_div" style="display: none;">
                        <form action="/return_advance_payment_request" method="POST" enctype="multipart/form-data" id="return_advance_payment_request_form">
                            @csrf
                            {{ csrf_field() }}

                            <fieldset>
                                <legend class="text-primary">Return Advance Payment Request For Correction</legend>
                                <input name="advance_payment_request_id" value="{{$advance_payment_request->id}}" type="text" style="display: none;" readonly>
                                <div class="form-row mt-2">
                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label for="comments" class="">
                                                Comments <span class="text-danger"></span>
                                            </label>
                                            <textarea name="comments" id="comments" class="form-control @error('comments') is-invalid @enderror" value="{{ old('comments') ?? $comments}}" autocomplete="off"></textarea>

                                            @error('comments')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <button class="mt-2 btn btn-primary" id="return_advance_payment_request_btn">Return Advance Payment Request</button>
                        </form>
                    </div>
                    <div class="p-3" id="reject_advance_payment_request_div" style="display: none;">
                        <form action="/reject_advance_payment_request" method="POST" enctype="multipart/form-data" id="reject_form">
                            @csrf
                            {{ csrf_field() }}

                            <fieldset>
                                <legend class="text-danger">Reject Advance Payment Request Request</legend>
                                <input name="advance_payment_request_id" value="{{$advance_payment_request->id}}" type="text" style="display: none;" readonly>
                                <div class="form-row mt-2">
                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label for="comments" class="">
                                                Reason For Rejecting <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="comments" id="comments" class="form-control @error('comments') is-invalid @enderror" value="{{ old('comments') ?? $rejection_reason}}" autocomplete="off"></textarea>

                                            @error('comments')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <button class="mt-2 btn btn-danger" id="reject_advance_payment_request_btn">Reject Advance Payment Request</button>
                        </form>
                    </div>

                    @if($supervisors_mode == 1)
                        <div class="p-3" id="change_spv_div" style="display: none;">
                            <form action="/change_advance_payment_request_spv" method="POST" enctype="multipart/form-data" id="change_spv_form">
                                @csrf
                                {{ csrf_field() }}

                                <fieldset>
                                    <legend class="text-primary">Change Responsible Supervisor</legend>
                                    <input name="advance_payment_request_id" value="{{$advance_payment_request->id}}" type="text" style="display: none;" readonly>
                                    <div class="form-row mt-2">
                                        <div class="col-md-12">
                                            <div class="position-relative form-group">
                                                <label for="responsible_spv" class="">
                                                    <span>Supervisor</span>
                                                </label>
                                                <select name="responsible_spv" id="responsible_spv" class="form-control @error('country') is-invalid @enderror">
                                                    <option value="">Select Supervisor</option>
                                                    @foreach($supervisors as $supervisor)
                                                        <option value="{{$supervisor->id}}" @if(($supervisor->id == old('responsible_spv')) || ($supervisor->id == $advance_payment_request->responsible_spv)) selected @endif>
                                                            {{ucwords($supervisor->first_name.' '.$supervisor->last_name)}}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                @error('responsible_spv')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="position-relative form-group">
                                                <label for="comments" class="">
                                                    Reason For Changing Supervisor <span class="text-danger">*</span>
                                                </label>
                                                <textarea name="comments" id="comments" class="form-control @error('comments') is-invalid @enderror" value="{{ old('comments') ?? $supervisor_change_reason}}" autocomplete="off"></textarea>

                                                @error('comments')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                 </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <button class="mt-2 btn btn-primary" id="change_spv_btn">Change Supervisor</button>
                            </form>
                        </div>
                    @endif

                </div>
            </div>
        </div>
@endif
<!-- actions ends here-->

</div>

<script type="text/javascript">

    $("#approve_switch").on("click",function(){
        $("#return_advance_payment_request_div").hide();
        $("#change_spv_div").hide();
        $("#reject_advance_payment_request_div").hide();
        $("#approve_advance_payment_request_div").show("slow");
    });

    $("#return_switch").on("click",function(){
        $("#approve_advance_payment_request_div").hide();
        $("#change_spv_div").hide();
        $("#reject_advance_payment_request_div").hide();
        $("#return_advance_payment_request_div").show("slow");
    });

    $("#change_spv_switch").on("click",function(){
        $("#return_advance_payment_request_div").hide();
        $("#approve_advance_payment_request_div").hide();
        $("#reject_advance_payment_request_div").hide();
        $("#change_spv_div").show("slow");
    });

    $("#reject_switch").on("click",function(){
        $("#return_advance_payment_request_div").hide();
        $("#change_spv_div").hide();
        $("#approve_advance_payment_request_div").hide();
        $("#reject_advance_payment_request_div").show("slow");
    });


</script>

@if( $responseType == "quick-view-with-actions" || $responseType == "message-and-actions")
<!-- This script will be used only if we want to submit request responses via ajax instead of normal forms -->
<script type="text/javascript">


    $("#approve_advance_payment_request_btn").on("click",function(event){
        event.preventDefault();
        let formElement = $(this).parent();
        let advance_payment_request_id = formElement.find("fieldset>input").eq(0).val();
        let comments = formElement.find("fieldset>div>div>div>textarea").eq(0).val();

        if(advance_payment_request_id === undefined || advance_payment_request_id.length < 1){
            sweet_alert_error("You must provide Request Id");
            return;
        }

        let url = "{{ route('advance_payment_requests.ajaxApprove') }}";
        let data = {
            "advance_payment_request_id": advance_payment_request_id,
            "comments": comments,
        };

        sendAjaxRequest1(url, data);
    });


    $("#return_advance_payment_request_btn").on("click",function(event){
        event.preventDefault();
        let formElement = $(this).parent();
        let advance_payment_request_id = formElement.find("fieldset>input").eq(0).val();
        let comments = formElement.find("fieldset>div>div>div>textarea").eq(0).val();

        if(advance_payment_request_id === undefined || advance_payment_request_id.length < 1){
            sweet_alert_error("You must provide Request Id");
            return;
        }
        if(comments === undefined || comments.length < 1){
            sweet_alert_error("You must provide some insight on why this request is being returned for correction");
            return;
        }

        let url = "{{ route('advance_payment_requests.ajaxReturnForCorrection') }}";
        let data = {
            "advance_payment_request_id": advance_payment_request_id,
            "comments": comments,
        };

        sendAjaxRequest1(url, data);
    });


    $("#change_spv_btn").on("click",function(event){
        event.preventDefault();
        let formElement = $(this).parent();
        let advance_payment_request_id = formElement.find("fieldset>input").eq(0).val();
        let responsible_spv = formElement.find("fieldset>div>div>div>select").eq(0).val();
        let comments = formElement.find("fieldset>div>div>div>textarea").eq(0).val();

        if(advance_payment_request_id === undefined || advance_payment_request_id.length < 1){
            sweet_alert_error("You must provide Request Id");
            return;
        }

        if(responsible_spv === undefined || responsible_spv.length < 1){
            sweet_alert_error("You must specify the new Supervisor");
            return;
        }

        if(comments === undefined || comments.length < 1){
            sweet_alert_error("You must specify the reason for changing Supervisor");
            return;
        }

        let url = "{{ route('advance_payment_requests.ajaxChangeSupervisor') }}";
        let data = {
            "advance_payment_request_id": advance_payment_request_id,
            "responsible_spv": responsible_spv,
            "comments": comments,
        };

        sendAjaxRequest1(url, data);
    });


    $("#reject_advance_payment_request_btn").on("click",function(event){
        event.preventDefault();

        let formElement = $(this).parent();
        let advance_payment_request_id = formElement.find("fieldset>input").eq(0).val();
        let comments = formElement.find("fieldset>div>div>div>textarea").eq(0).val();

        if(advance_payment_request_id === undefined || advance_payment_request_id.length < 1){
            sweet_alert_error("You must provide Request Id");
            return;
        }
        if(comments === undefined || comments.length < 1){
            sweet_alert_error("You must specify the reason for Rejecting this request");
            return;
        }

        let url = "{{ route('advance_payment_requests.ajaxReject') }}";
        let data = {
            "advance_payment_request_id": advance_payment_request_id,
            "comments": comments,
        };

        sendAjaxRequest1(url, data);
    });




    function sendAjaxRequest1(url, data){

        //ajax request
        $.ajax({
            url: url,
            headers: {
                "X-CSRF-TOKEN" : '{{ csrf_token() }}',
                "Authorization": "Bearer " + "{{ Cookie::get('access_token') }}",
            },
            method: 'POST',
            data: data,
            success: function (data) {

                if (data['status'] === 'success') {
                    let message = data['message'];
                    let htmlMessage = "";

                    let messageParts = message.split(":");
                    if(messageParts.length === 2){
                        htmlMessage += "<span class='text-danger'>" + messageParts[0] + " : </span>";
                        htmlMessage += " " + messageParts[1] + "<br>";
                    }else{
                        htmlMessage += message;
                        htmlMessage += "<br>";
                    }

                    htmlMessage += "<br>";

                    Swal.fire({
                        title: "<span class='text-danger'>Feedback</span>",
                        html: htmlMessage,
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#00838F',
                        cancelButtonColor: '#FF0000',
                        confirmButtonText: 'Ok',
                        width: '600px'
                    }).then((result) => {
                        window.location.reload();
                    });

                } else if (data['status'] == 'fail') {
                    sweet_alert_error(data['message'])
                }
            }
        });

    }


</script>
@endif

