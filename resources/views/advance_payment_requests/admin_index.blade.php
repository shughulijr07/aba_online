@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">Advance Payment Requests Which Are <span class="orange-text">{{$request_statuses[$filtered_requests_status]}}</span></div>
                    <div class="page-title-subheading">
                        Below is a list of Advance Payment request.
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="main-card mb-3 card">
        <div class="card-header-tab card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                <i class="header-icon lnr-list mr-3 text-muted opacity-6"> </i>Advance Payment Requests
            </div>
            <div class="btn-actions-pane-right actions-icon-btn mt-2 mb-2 pt-2 pb-2">
                <div class="btn-group dropdown">

                    <button type="button" class="btn btn-outline-secondary btn-xs mr-2 pl-3 pr-3" id="cancel-bulk-actions-btn" style="display: none;">
                        <i class="dropdown-icon lnr-cross"> </i><span>Cancel</span>
                    </button>
                    <button type="button" class="btn btn-outline-success btn-xs mr-2 pl-3 pr-3" id="approve-multiple-btn" style="display: none;">
                        <i class="dropdown-icon lnr-pencil"> </i><span>Approve</span>
                    </button>
                    <button type="button" class="btn btn-outline-warning btn-xs mr-2 pl-3 pr-3" id="delete-multiple-btn" style="display: none;">
                        <i class="dropdown-icon lnr-trash"> </i><span>Delete</span>
                    </button>

                    <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link ml-2" id="bulk-actions-panel-toggle-btn">
                        <i class="pe-7s-menu btn-icon-wrapper"></i>
                    </button>

                    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(28px, 36px, 0px);">
                        <h6 tabindex="-1" class="dropdown-header text-orange">Bulk Actions</h6>
                        <button type="button" tabindex="0" class="dropdown-item" id="bulk-approve-btn">
                            <i class="dropdown-icon lnr-pencil"> </i><span>Bulk Approve</span>
                        </button>
                        <button type="button" tabindex="0" class="dropdown-item" id="bulk-delete-btn">
                            <i class="dropdown-icon lnr-trash"> </i><span>Bulk Delete</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                <thead>
                <tr id="bulk-actions-selection-header-row" style="background-color: #00838F; color: white; display: none;">
                    <td colspan="1"></td>
                    <td colspan="7">Select Multiple</td>
                    <td colspan="1" class="approving-column invisible"><input type="checkbox" class="column-permissions" value="approve"></td>
                    <td colspan="1" class="deleting-column invisible"><input type="checkbox" class="column-permissions" value="delete"></td>
                </tr>
                <tr>
                    <th>#</th>
                    <th>No.</th>
                    <th>Requested By</th>
                    <th>Request Date</th>
                    <th>Project Code</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                    <td class="approving-column invisible"></td>
                    <td class="deleting-column invisible"></td>
                </tr>
                </thead>
                <tbody>

                <?php $n = 1; ?>
                @foreach($advance_payment_requests as $advance_payment_request)
                    <?php
                        $supervisor = \App\Staff::find($advance_payment_request->responsible_spv);
                        $isUserAllowedToApproveThisRequest = App\AdvancePaymentRequest::checkIfCurrentUserIsAllowedToApproveRequest($advance_payment_request->status, $currentUserRoleId, $currentUserStaffId, $advance_payment_request->responsible_spv);
                        $isUserAllowedToDeleteThisRequest = in_array($advance_payment_request->status, $statusesAllowedForDeletingForOtherStaff) && in_array($currentUserRoleId, $rolesAllowedDeletingForOtherStaff);
                    ?>
                    <tr
                        data-id="{{ $advance_payment_request->id }}"
                        data-no="{{ $advance_payment_request->no }}"
                        data-name="{{ ucwords($advance_payment_request->staff->first_name.' '.$advance_payment_request->staff->last_name) }}"
                        data-request_date="{{ $advance_payment_request->request_date }}"
                        data-total="{{number_format($advance_payment_request->total, 2, "." , ",")}}"
                    >
                        <td>{{ $n }}</td>
                        <td>{{ $advance_payment_request->no }}</td>
                        <td>{{ ucwords($advance_payment_request->staff->first_name.' '.$advance_payment_request->staff->last_name) }}</td>
                        <td>{{ date("Y-m-d", strtotime($advance_payment_request->request_date))  }}</td>
                        <td>{{ $advance_payment_request->project_code }}</td>
                        <td>{{number_format($advance_payment_request->total, 2, "." , ",")}}</td>
                        <td>{{ $request_statuses[$filtered_requests_status]}}</td>
                        <td>
                            <div class="btn-group dropdown">
                                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link actions-panel-toggle-btn">
                                    <i class="pe-7s-menu btn-icon-wrapper"></i>
                                </button>
                                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-825px, 36px, 0px); padding: 0;">
                                    <h6 tabindex="-1" class="dropdown-header mb-1"  style="background-color: #00838F; color: #ffffff; padding: 20px;">{{ $advance_payment_request->no }}</h6>

                                    <button type="button" tabindex="0" class="dropdown-item quick-view">
                                        <i class="dropdown-icon lnr-file-empty"> </i><span>Quick View</span>
                                    </button>

                                    <a type="button" tabindex="0" class="dropdown-item" href="{{ url("advance_payment_requests_admin/".$advance_payment_request->id) }}" >
                                        <i class="dropdown-icon lnr-book"> </i><span>Detailed View</span>
                                    </a>

                                    @if($isUserAllowedToApproveThisRequest)
                                    <button type="button" tabindex="0" class="dropdown-item approve-btn" >
                                        <i class="dropdown-icon lnr-file-add"> </i><span>Approve</span>
                                    </button>
                                    @endif

                                    @if($isUserAllowedToDeleteThisRequest)
                                        <button type="button" tabindex="0" class="dropdown-item delete-btn" >
                                            <i class="dropdown-icon lnr-trash"> </i><span>Delete</span>
                                        </button>
                                    @endif

                                    <div tabindex="-1" class="dropdown-divider"></div>
                                    <div class="pl-3 pt-1 pr-3 pb-3 text-right">
                                        <button class="mr-2 btn-shadow btn-sm btn btn-primary close-actions-panel-btn">Close</button>
                                    </div>
                                </div>
                            </div>

                        </td>

                        <td class="approving-column invisible">@if($isUserAllowedToApproveThisRequest)<input type="checkbox" class="approve">@endif</td>
                        <td class="deleting-column invisible">@if($isUserAllowedToDeleteThisRequest)<input type="checkbox" class="delete">@endif</td>
                    </tr>
                    <?php $n++; ?>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>#</th>
                    <th>No.</th>
                    <th>Requested By</th>
                    <th>Request Date</th>
                    <th>Project Code</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                    <td class="approving-column invisible"></td>
                    <td class="deleting-column invisible"></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>


    <div id="modal-actions-buttons-div" style="display: none;">
        <div id="modal-actions-buttons">
            @if(in_array($filtered_requests_status, $statusesAllowedForDeletingForOtherStaff) && in_array($currentUserRoleId, $rolesAllowedDeletingForOtherStaff))
                <button type="button" class="btn btn-danger btn-sm delete-button" onclick="deleteItem()">Delete</button>
            @endif
            <button type="button" class="btn btn-secondary btn-sm close-modal-button" onclick="closeMainModal()">Close</button>
        </div>
    </div>



    <!-- script for displaying information & approving panels -->
    <script type="text/javascript">
        let title = "";
        let itemId = "";
        let itemDetails = "";
        let actionButtons = "";
        let viewType = "quick-view-with-actions"; //There are three options "quick-view" | "quick-view-with-actions" | message-and-actions
        let modalSize = "modal-xl"; //Modal Sizes | modal-sm | modal-lg | modal-xl
        let applyCustomModalWidth = false;
        let customModalWidth = "80%";
        let currentSelectedRow = null;
        let selectedRows = [];

        //Information Modal Section
        $(".quick-view").on("click", function(){

            closeMainModal();//Close the modal just incase it is open
            clearModalParameters();

            currentSelectedRow = $(this).parent().parent().parent().parent();

            itemId = currentSelectedRow.attr("data-id");
            title  = "<span class='text-white-50'>Adv. Pmt Request # </span>" + " <span class='text-white'>" + currentSelectedRow.attr("data-no") + "</span> ";
            title += "<span class='text-white-50'>Submitted By : </span>" +" <span class='text-white'>" + currentSelectedRow.attr("data-name") + "</span> ";
            title += "<span class='text-white-50'> Date : </span>" + " <span class='text-white'>" + currentSelectedRow.attr("data-request_date") + "</span> ";

            actionButtons = $("#modal-actions-buttons");
            viewType = "quick-view-with-actions";
            customModalWidth = "80%";
            applyCustomModalWidth = true;


            getItemDetails(itemId);
        });

        $(".approve-btn").on("click", function(){
            closeMainModal();//Close the modal just incase it is open
            clearModalParameters();

            currentSelectedRow = $(this).parent().parent().parent().parent();

            itemId = currentSelectedRow.attr("data-id");
            title  = "<span class='text-white-50'>Request # </span>" + " <span class='text-white'>" + currentSelectedRow.attr("data-no") + "</span><br>";
            title += "<span class='text-white-50'>Total : </span>" + " <span class='text-white'>" + currentSelectedRow.attr("data-total") + "</span><br>";
            title += "<span class='text-white-50'>By : </span>" +" <span class='text-white'>" + currentSelectedRow.attr("data-name") + "</span><br>";
            title += "<span class='text-white-50'>Date : </span>" + " <span class='text-white'>" + currentSelectedRow.attr("data-request_date") + "</span><br>";

            actionButtons = "";
            modalSize = "modal-lg";
            viewType = "message-and-actions";
            applyCustomModalWidth = true;
            customModalWidth = "25%";

            let windowWidth = $(window).width();
            if(windowWidth > 0) customModalWidth = "95%";
            if(windowWidth > 400) customModalWidth = "80%";
            if(windowWidth > 600) customModalWidth = "70%";
            if(windowWidth > 800) customModalWidth = "50%";
            if(windowWidth > 1000) customModalWidth = "40%";
            if(windowWidth > 1200) customModalWidth = "30%";
            if(windowWidth > 1400) customModalWidth = "25%";
            if(windowWidth > 1600) customModalWidth = "20%";

            getItemDetails(itemId);
        });

        function getItemDetails(itemId){

            $.ajax({
                url: "{{ url('advance_payment_requests_admin') }}" + "/" + itemId + "/" +viewType,
                headers: {
                    "X-CSRF-TOKEN" : '{{ csrf_token() }}',
                    "Authorization": "Bearer " + "{{ Cookie::get('access_token') }}",
                },
                method: 'GET',
                success:function(data)
                {
                    details = data;
                    displayQuickView();
                }
            });
        }

        function displayQuickView(){
            //Set Modal Title
            $("#modalTitle").html(title);

            //Set Modal Body
            $("#modalBody").html(details);

            //Set Modal Footer
            $("#modalFooter").html(actionButtons);

            //Set Modal Size
            $("#modal-dialog").removeClass("modal-lg").addClass(modalSize);

            if(applyCustomModalWidth){
                $("#modal-dialog").css("max-width", customModalWidth);
            }

            //Then display the Modal : this function is called from --layouts.administrator.modal
            displayModal();

        }

        $(".close-modal-button").on("click", function(){
            closeMainModal();
        });


        function closeMainModal(){

            //move actions button back to storage div
            $("#modal-actions-buttons-div").html($("#modal-actions-buttons"));

            //Then close the Modal : this function is called from --layouts.administrator.modal
            closeModal();

        }

        $(".close-actions-panel-btn").on("click", function (){
            $(this).parent().parent().parent().find("button.actions-panel-toggle-btn").first().trigger({ type: "click" });
        });


    </script>


    <!-- script for multiple approval & Deleting -->
    <script type="text/javascript">


        //when all-permission in a column checkbox is clicked
        $(".column-permissions").on("click",function(){
            $("#save-changes-btn").show("slow");

            let class_name = $(this).val();
            let permission_selected = '';

            $(this).is(":checked") ? permission_selected = 'true' : permission_selected = 'false';

            //select all permissions with specified class name
            let permissions = $('.'+class_name);

            //Change the values in all permissions and check or un-check the permission box accordingly
            permissions.each(function(index){
                permission_selected === 'true' ? $(this).prop('checked',true) :  $(this).prop('checked',false);
            });
        });

        $("#bulk-approve-btn").on("click", function(){
            //Close Panel
            $("#bulk-actions-panel-toggle-btn").trigger({ type: "click" });

            //display bulk actions header row
            $("#bulk-actions-selection-header-row").show();

            //hide all deleting columns
            $(".deleting-column").hide("fast");
            $(".deleting-column").addClass("invisible");

            //display all approving columns
            $(".approving-column").removeClass("invisible");
            $(".approving-column").show("slow");

            $("#cancel-bulk-actions-btn").show("slow");
            $("#approve-multiple-btn").show("slow");
            $("#delete-multiple-btn").hide("fast");

        });

        $("#bulk-delete-btn").on("click", function(){
            //Close Panel
            $("#bulk-actions-panel-toggle-btn").trigger({ type: "click" });

            //display bulk actions header row
            $("#bulk-actions-selection-header-row").show();

            //hide all approving columns
            $(".approving-column").hide("fast");
            $(".approving-column").addClass("invisible");

            //show all deleting columns
            $(".deleting-column").removeClass("invisible");
            $(".deleting-column").show("slow");

            $("#cancel-bulk-actions-btn").show("slow");
            $("#approve-multiple-btn").hide("fast");
            $("#delete-multiple-btn").show("slow");
        });

        $("#cancel-bulk-actions-btn").on("click", function(){
            //clear all selections
            $('input:checkbox').prop('checked', false);

            $("#cancel-bulk-actions-btn").hide("slow");
            $("#approve-multiple-btn").hide("slow");
            $("#delete-multiple-btn").hide("slow");

            //hide bulk actions header row
            $("#bulk-actions-selection-header-row").hide("slow");

            //hide all approving columns
            $(".approving-column").hide("slow");
            $(".approving-column").addClass("invisible");

            //hide all deleting columns
            $(".deleting-column").hide("slow");
            $(".deleting-column").addClass("invisible");
        });

        $(".delete-btn").on("click",function(){
            $(".close-actions-panel-btn").first().trigger({ type: "click" });

            currentSelectedRow = $(this).parent().parent().parent().parent();
            deleteItem();
        });

        $("#delete-multiple-btn").on("click", function(){
            selectedRows = [];
            $('input:checkbox.delete').each(function(){
                if($(this).is(":checked")){
                    let checkedRow = $(this).parent().parent();
                    selectedRows.push(checkedRow);
                }
            });

            if(selectedRows.length > 0){
                deleteSelectedRows();
            }
        });

        $("#approve-multiple-btn").on("click", function(){
            selectedRows = [];
            $('input:checkbox.approve').each(function(){
                if($(this).is(":checked")){
                    let checkedRow = $(this).parent().parent();
                    selectedRows.push(checkedRow);
                }
            });

            if(selectedRows.length > 0){
                approveSelectedRows();
            }
        });

        function deleteItem(){
            selectedRows = [];
            selectedRows.push(currentSelectedRow);
            deleteSelectedRows();
        }


        function deleteSelectedRows(){

            if(selectedRows.length > 0){
                let itemsIds = [];
                let confirmationTitle = "Deleting " + (selectedRows.length === 1 ? "Request" : "Requests");
                confirmationTitle = "<span class='text-danger'>"+confirmationTitle+"</span>" //00838F
                let confirmationMessage = "Are you sure you want to delete ";
                confirmationMessage += selectedRows.length === 1 ? " This Request ?" : "These Requests ?";
                confirmationMessage += "<br>";

                for(let n=0; n < selectedRows.length; n++ ){
                    let selectedRow = selectedRows[n];

                    let itemId = selectedRow.attr("data-id");
                    let no = selectedRow.attr("data-no");
                    let total = selectedRow.attr("data-total");
                    let name = selectedRow.attr("data-name");

                    itemsIds.push(itemId);

                    confirmationMessage += "<br>";
                    confirmationMessage += "<span class='text-danger'>"+"No. : " + no + " ~ "+"</span>";
                    confirmationMessage += "Total : " + total + " | ";
                    confirmationMessage += "By : " + name;
                    confirmationMessage += "<br>";
                }

                confirmationMessage += "<br>";
                confirmationMessage += "<br>";

                let url = "{{ route('advance_payment_requests.ajaxDeleteMultiple') }}";
                let data = {
                    "itemsIds": JSON.stringify(itemsIds),
                };

                sendAjaxRequest(url, data, confirmationTitle, confirmationMessage);

            }
        }


        function approveSelectedRows(){

            if(selectedRows.length > 0){
                let itemsIds = [];
                let confirmationTitle = "Approving " + (selectedRows.length === 1 ? "Request" : "Requests");
                confirmationTitle = "<span class='text-danger'>"+confirmationTitle+"</span>" //00838F
                let confirmationMessage = "Are you sure you want to approve ";
                confirmationMessage += selectedRows.length === 1 ? " This Request ?" : "These Requests ?";
                confirmationMessage += "<br>";

                for(let n=0; n < selectedRows.length; n++ ){
                    let selectedRow = selectedRows[n];

                    let itemId = selectedRow.attr("data-id");
                    let no = selectedRow.attr("data-no");
                    let total = selectedRow.attr("data-total");
                    let name = selectedRow.attr("data-name");

                    itemsIds.push(itemId);

                    confirmationMessage += "<br>";
                    confirmationMessage += "<span class='text-danger'>"+"No. : " + no + " ~ "+"</span>";
                    confirmationMessage += "Total : " + total + " | ";
                    confirmationMessage += "By : " + name;
                    confirmationMessage += "<br>";
                }

                confirmationMessage += "<br>";
                confirmationMessage += "<br>";

                let url = "{{ route('advance_payment_requests.ajaxApproveMultiple') }}";
                let data = {
                    "itemsIds": JSON.stringify(itemsIds),
                };

                sendAjaxRequest(url, data, confirmationTitle, confirmationMessage);

            }
        }


        function sendAjaxRequest(url, data, confirmationTitle, confirmationMessage){

            Swal.fire({
                title: confirmationTitle,
                html: confirmationMessage,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#00838F',
                cancelButtonColor: '#FF0000',
                confirmButtonText: 'Yes',
                width: '600px'
            }).then((result) => {
                if (result.value) {

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

                                if (data['status'] == 'success') {
                                    let messages = data['message'];
                                    let htmlMessage = "";

                                    for(let n=0; n < messages.length; n++ ){
                                        let messageParts = messages[n].split(":");
                                        if(messageParts.length === 2){
                                            htmlMessage += "<span class='text-danger'>" + messageParts[0] + " : </span>";
                                            htmlMessage += " " + messageParts[1] + "<br>";
                                        }else{
                                            htmlMessage += messages[n];
                                            htmlMessage += "<br>";
                                        }
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
            });
        }

    </script>

    <!-- Sweet Alerts -->
    <script type="text/javascript">

        function sweet_alert_success(success_message) {
            Swal.fire({
                type: "success",
                text: success_message,
                confirmButtonColor: '#008FDC',
            })
        }


        function sweet_alert_error(error_message) {
            Swal.fire({
                type: "error",
                text: error_message,
                confirmButtonColor: '#008FDC',
            })
        }

        function sweet_alert_warning(warning_message) {
            Swal.fire({
                type: "warning",
                text: warning_message,
                confirmButtonColor: '#008FDC',
            })
        }
    </script>
@endsection



