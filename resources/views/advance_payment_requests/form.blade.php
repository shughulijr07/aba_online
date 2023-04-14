<?php
$read_only = '';
$disabled = '';
$visibility = '';
if( isset($advance_payment_request->status) && isset($advance_payment_request->staff_id) ){
    if( !in_array($advance_payment_request->status, ['0','10']) ){
        $read_only = 'readonly';
        $disabled = 'disabled';
        $visibility = 'invisible';
    }
}
?>



<div class="col-md-12" style="display: none">
    <span id="advance_payment_request">{{$advance_payment_request}}</span>
    <span id="advance_payment_request_id">{{$advance_payment_request->id}}</span>
    <span id="advance_payment_request_details">{{ old('details') ?? $advance_payment_request->details}}</span>
    <span id="is_old_form">@if(old('project_id') != null) true @else false @endif</span>
</div>

@if( !isset($advance_payment_request->status))
<form action="/advance_payment_request" method="POST" enctype="multipart/form-data" id="form">
@elseif( isset($advance_payment_request->status) && in_array($advance_payment_request->status, [0,10]) && $advance_payment_request->staff_id == auth()->user()->staff->id)
<form action="/advance_payment_request_update/{{$advance_payment_request->id}}" method="POST" enctype="multipart/form-data" id="form">
@method('PATCH')
@else
<form id="form">
@endif

@csrf

    <!-- Header-->
    <fieldset>
        <legend class="text-danger"></legend>
        <div class="form-row">
            <div class="col-md-3">
                <div class="position-relative form-group">
                    <label for="no" class="">
                        <span>Request No.</span>
                    </label>
                    <input name="no" id="no" type="text" class="form-control" value="{{$advance_payment_request->no}}" autocomplete="off" disabled>
                </div>
            </div>
            <div class="col-md-3">
                <div class="position-relative form-group">
                    <label for="project_id" class="">
                        <span>Project Name</span>
                        <span class="text-danger">*</span>
                    </label>
                    <select name="project_id" id="project_id" class="form-control @error('project_id') is-invalid @enderror" {{$disabled}}>
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{$project->id}}" @if(($project->id == old('project_id')) || ($project->id == $advance_payment_request->project_id)) selected @endif>
                                {{$project->number." : ".$project->name}}
                            </option>
                        @endforeach
                    </select>

                    @error('project_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-3" id="supervisor_div">
                <div class="position-relative form-group">
                    <label for="responsible_spv" class="">
                        <span>Supervisor</span>
                        <span class="text-danger">*</span>
                    </label>
                    <select name="responsible_spv" id="responsible_spv" class="form-control @error('responsible_spv') is-invalid @enderror" {{$disabled}}>
                        @if($supervisors_mode == '2')<option value="">Select Supervisor</option>@endif
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
            <div class="col-md-3">
                <div class="position-relative form-group">
                    <label for="request_date" class="">
                        <span>Request Date</span>
                        <span class="text-danger">*</span>
                    </label>
                    <input name="request_date" id="request_date" type="text" class="form-control @error('request_date') is-invalid @enderror" value="{{ old('request_date') ?? $advance_payment_request->request_date}}" autocomplete="off" {{$disabled}}>

                    @error('request_date')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                     </span>
                    @enderror
                </div>
            </div>
            <div class="col-md-12" id="description_div">
                <div class="position-relative form-group">
                    <label for="purpose" class="">
                        Purpose Of Payment
                        <span class="text-danger">*</span>
                    </label>
                    <textarea name="purpose" id="purpose" class="form-control @error('purpose') is-invalid @enderror" autocomplete="off" {{$disabled}}>{{ old('purpose') ?? $advance_payment_request->purpose}}</textarea>

                    @error('purpose')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>



            <div class="col-md-3 {{$visibility}}" id="attachments_div">
                <div class="form-group">

                    <div class="form-group">
                        <label>
                            <span>Attachments</span>
                        </label>
                        <div class="text-left" style="margin-bottom: 5px;">
                            <label for="attachments" class="upload-label" type="button">
                                <i class="pe-7s-upload"></i>
                                <span style="padding-left: 7px; padding-right: 5px;" id="attachments_text">
                                    Click Here To Upload Attachments
                                </span>
                            </label>
                            <input name="attachments[]" id="attachments" type="file"  accept="image/*,.pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" class="form-control-file" style="display:none;" multiple >
                        </div>

                        @error('attachments')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                    </div>

                </div>
            </div>
            <div class="col-md-9 pt-4" style="display: none; " id="file-name-div">
                <div class="position-relative form-group">
                    <div class="text-primary" style="padding: 5px; ">
                        <span id="file-count"></span>
                        <span id="file-separator" class="text-danger"></span>
                        <span id="file-name2"></span>
                    </div>
                </div>
            </div>

        </div>
    </fieldset>
    <!-- End Of Header -->

    <!-- Lines -->
    <fieldset  {{$disabled}}>
        <legend class="text-secondary mt-5">Advance Payment Request Details</legend>
        <div class="row">
            <div class="col-md-12">
                <table style="width: 100%; font-size: 12px;" id="details_table" class="table table-hover table-striped table-bordered" >
                    <thead>
                    <tr class="font-weight-bold">
                        <th style="width: 5%;" class="font-weight-bold">#</th>
                        <th style="width: 15%;" class="font-weight-bold">Account Code</th>
                        <th style="width: 15%;" class="text-left font-weight-bold">Activity</th>
                        <th style="width: 35%;" class="text-left font-weight-bold">Description</th>
                        <th style="width: 10%;" class="text-left font-weight-bold">Rate</th>
                        <th style="width: 5%;" class="text-left font-weight-bold">Unit</th>
                        <th style="width: 10%;" class="text-left font-weight-bold">Total</th>
                        <th style="width: 5%;" class="text-center">
                            <input type="checkbox" name="checkbox_0" class="select-multiple-rows" value="0">
                        </th>
                    </tr>
                    </thead>

                    <tbody id="details_table_body">
                    <tr class="sample-row" id="details_table_row_sample"  style="display: none;" >
                        <td class="data-column details-no">
                            <input class="column-input no text-left" value="1" autocomplete="off" readonly>
                        </td>
                        <td class="data-column details-account">
                            <select class="column-input selection-input account_id" style="display: none;" {{$disabled}}>
                                @foreach($accounts_codes as $accounts_code)
                                    <option value="{{$accounts_code->id}}">
                                        {{$accounts_code->number." : ".$accounts_code->name}}
                                    </option>
                                @endforeach
                            </select>
                            <input class="column-input display-input account_number text-left" value="" autocomplete="off" readonly>
                        </td>
                        <td class="data-column details-activity">
                            <select class="column-input selection-input activity_id" style="display: none;" {{$disabled}}>
                            </select>
                            <input class="column-input display-input activity_code text-left" value="" autocomplete="off" readonly>
                        </td>
                        <td class="data-column details-description">
                            <input class="column-input description text-left" value="" autocomplete="off" {{$disabled}}>
                        </td>
                        <td class="data-column details-rate">
                            <input class="column-input rate text-right" value="" autocomplete="off" {{$disabled}}>
                        </td>
                        <td class="data-column text-center details-unit">
                            <input class="column-input unit text-right" value=""  autocomplete="off" {{$disabled}}>
                        </td>
                        <td class="data-column text-center details-total">
                            <input class="column-input line_total text-right" value=""  autocomplete="off" readonly {{$disabled}}>
                        </td>

                        <td class="data-column text-center">
                            <input type="checkbox" class="select-one-row-checkbox" value="1" {{$disabled}}>
                        </td>
                    </tr>
                    </tbody>

                    <tfoot>
                    <tr style="display: none;">
                        <th colspan="2" class="data-column text-right pr-2"><span class="pr-2">Details Json</span></th>
                        <th  colspan="6" class="data-column text-left">
                            <input name="details" class="column-input text-right" value=""  id="details" readonly>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="6" class="data-column text-right pr-2"><span class="pr-2">Total Requested</span></th>
                        <th style="width: 15%;" class="data-column text-right">
                            <input name="total" class="column-input text-right" value=""  id="total" readonly>
                        </th>
                        <th style="width: 5%;" class="text-center">
                        </th>
                    </tr>
                    </tfoot>

                </table>

                <div class="text-center  {{$visibility}}">
                    <button type="button"  class="btn btn-secondary btn-xs add_row">
                        <i class="fa fa-fw" aria-hidden="true" title="Copy to use plus"></i>
                        Add Row
                    </button>
                    <button type="button"  class="btn btn-secondary btn-xs add_row_after">
                        <i class="fa fa-fw" aria-hidden="true" title="Copy to use plus"></i>
                        Add Row After
                    </button>
                    <button type="button"  class="btn btn-secondary btn-xs add_row_before">
                        <i class="fa fa-fw" aria-hidden="true" title="Copy to use plus"></i>
                        Add Row Before
                    </button>
                    <button type="button"  class="btn btn-secondary btn-xs remove_row">
                        <i class="fa fa-fw" aria-hidden="true" title="Copy to use minus"></i>
                        Remove Line
                    </button>
                </div>

                <div class="col-md-12 mt-3">
                    <div class="position-relative form-group">
                        <input type="hidden" name="terms[]" value="no">
                        <input type="checkbox" name="terms[]" id="terms" value="yes" @if( (is_array(old('terms')) &&  in_array('yes',old('terms'))) || in_array('yes',$terms )) checked @endif {{$disabled}}>
                        <span class="text-primary">
                            I <span class="text-danger">{{$employee_name}}</span>  declare by accepting funds from SHDEPHA that I will retire these funds according to
                            SHDEPHA  policies and procedures with genuine receipts.
                        </span>

                        @error('terms')
                        <span class="invalid-feedback" role="alert" style="display: block">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

            </div>
        </div>
    </fieldset>
    <!-- End Of Lines -->

    <button class="mt-2 btn btn-primary {{$visibility}}" type="submit" id="submit_btn">Submit Advance Payment Request</button>

</form>


<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/numberToWords.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>



<!-- Classes Declarations -->
<script type="text/javascript">


    class AdvancePaymentRequest {
        constructor( header, details ) {
            this.header = header;
            this.details = details;
        }
    }


    class Header {
        constructor(
            id, no, staff_id, project_id, project_code, year, request_date, responsible_spv, purpose,
            attachments, terms, status, transferred_to_nav, nav_id, total
        ) {
            this.id = id;
            this.no = no;
            this.staff_id = staff_id;
            this.project_id = project_id;
            this.project_code = project_code;
            this.year = year;
            this.request_date = request_date;
            this.responsible_spv = responsible_spv;
            this.purpose = purpose;
            this.attachments = attachments;
            this.terms = terms;
            this.status = status;
            this.transferred_to_nav = transferred_to_nav; // no | yes
            this.nav_id = nav_id;
            this.total = total;
        }
    }


    class Detail {
        constructor(no, account_id, account_number, activity_id, activity_code, description, rate, unit, line_total) {
            this.no = no;
            this.account_id = account_id;
            this.account_number = account_number;
            this.activity_id = activity_id;
            this.activity_code = activity_code;
            this.description = description;
            this.rate = rate;
            this.unit = unit;
            this.line_total = line_total;
        }
    }

</script>


<!-- Initialization -->
<script type="text/javascript">

    var header = new Header('', '', '', '', '', '', '', '', '','', '', '', '', '', '');
    var details = [];
    var advancePaymentRequest = new AdvancePaymentRequest(header, details);
    var originalAdvancePaymentRequest = advancePaymentRequest;
    var projectListStatus = "load";

    $(function () {
        //initialize form
        if($("#advance_payment_request").text() !== ""){

            //Initialize Header
            let advancePaymentRequestHeader = JSON.parse($("#advance_payment_request").text());
            if(advancePaymentRequestHeader.id != undefined){
                //Initialize Header
                header.id = advancePaymentRequestHeader.id;
                header.no = advancePaymentRequestHeader.no;
                header.staff_id = advancePaymentRequestHeader.staff_id;
                header.project_id = advancePaymentRequestHeader.project_id;
                header.project_code = advancePaymentRequestHeader.project_code;
                header.year = advancePaymentRequestHeader.year;
                header.request_date = advancePaymentRequestHeader.request_date;
                header.responsible_spv = advancePaymentRequestHeader.responsible_spv;
                header.purpose = advancePaymentRequestHeader.purpose;
                header.attachments = advancePaymentRequestHeader.attachments;
                header.terms = advancePaymentRequestHeader.terms;
                header.status = advancePaymentRequestHeader.status;
                header.transferred_to_nav = advancePaymentRequestHeader.transferred_to_nav;
                header.nav_id = advancePaymentRequestHeader.nav_id;
                header.total = total;

                //Initialize Details
                //details = advancePaymentRequestHeader.details;
            }else{
                header.no = advancePaymentRequestHeader.no;
                header.responsible_spv = advancePaymentRequestHeader.responsible_spv;
            }


            //Initialize Details
            let advancePaymentRequestDetailsText = $("#advance_payment_request_details").text().trim();
            if( advancePaymentRequestDetailsText.length > 0 ){
                details = JSON.parse(advancePaymentRequestDetailsText);
            }

            advancePaymentRequest.header = header;
            advancePaymentRequest.details = details;
            originalAdvancePaymentRequest = advancePaymentRequest;

            displayForm();
        }

    });

    $(function () {

        $('#notifications-div').delay(7000).fadeOut('slow');

        $('#request_date').datepicker({
            format: 'dd/mm/yyyy'
        });


        @if( in_array($view_type,['create','edit']))
        $('#project_id').select2({width: '100%'});


        let project_id = $("#project_id").val().trim();
        if(project_id.length> 0){
            get_activities(project_id);
        }
        @endif

        calculate_grand_total();

    });

</script>



<!-- Attachments Script -->
<script type="text/javascript">


    $('#attachments').on('change', function(){
        let file_name = $(this).val().split('\\').pop();

        if(file_name.length >0){
            $('#file-name2').text(file_name);
            $('#file-name-div').show();
        }
    });

    $('#attachments').on('change', function(){
        //alert( this.files.length );


        if (this.files.length > 0) {

            let file_names = ''; let file_sizes = 0;
            for (let i = 0; i <= this.files.length - 1; i++) {

                let file_name = this.files.item(i).name;      // THE NAME OF THE FILE.
                let file_size = this.files.item(i).size;      // THE SIZE OF THE FILE.

                file_sizes += Number(file_size);

                if( i == 0 ){
                    file_names += file_name;
                }
                else if( i == this.files.length - 1){
                    file_names += ' ' + file_name;
                }else{
                    file_names += ', ' + file_name;
                }

            }

            let fileText = this.files.length > 1 ? ' Files' : ' File';
            $('#file-name2').text(file_names);
            $('#file-separator').text(' | ');
            $('#file-count').text(this.files.length + fileText + ' Uploaded');
            $('#file-name-div').show();

        }

    });

</script>


<!-- Form Manipulation Scrip -->
<script type="text/javascript">


    $("#project_id").on("change",function(){
        advancePaymentRequest.header.project_id = $("#project_id").val();
    });

    $("#responsible_spv").on("change",function(){
        advancePaymentRequest.header.responsible_spv = $("#responsible_spv").val();
    });

    $("#request_date").on("change",function(){
        advancePaymentRequest.header.request_date = $("#request_date").val();
    });

    $("#purpose").on("change",function(){
        advancePaymentRequest.header.purpose = $("#purpose").val();
    });


    $("tbody").on('change','tr>td>select.account_id, tr>td>select.activity_id',function(event){
        let selectionInput = $(this);
        displayInput(selectionInput);
    });


    $("tbody").on('focusout','tr>td>select.account_id, tr>td>select.activity_id',function(event){
        let selectionInput = $(this);
        displayInput(selectionInput);
    });


    $("tbody").on('mouseout','tr>td>span.select2',function(event){
        let currentElement = $(this);
        let parentElement = currentElement.parent();
        let selectionInput = (parentElement.children("select"));
        displayInput(selectionInput);
    });

    function displayInput(selectionInput){
        let text = selectionInput.find(":selected").text();
        let code =  ((text.split(":"))[0]).trim();

        let parentElement = selectionInput.parent();
        let display_input = parentElement.children(".display-input");
        let select2Container = parentElement.children(".select2-container");

        //Show Codes Only Instead Of Whole Text
        display_input.val(code);

        if(code.length > 0){
            selectionInput.hide();
            select2Container.hide();
            display_input.show();
        }else{
            selectionInput.show();
            select2Container.show();
            display_input.hide();
        }
    }


    $("tbody").on('mouseover1','tr>td>input.display-input',function(event){

        let displayInput = $(this);

        let parentElement = displayInput.parent();
        let selectionInput = parentElement.children(".selection-input");
        let select2Container = parentElement.children(".select2-container");

        //Show Codes Only Instead Of Whole Text
        displayInput.hide();
        selectionInput.show();
        select2Container.show();
    });


    $("tbody").on('click','tr>td>input.display-input',function(event){

        let displayInput = $(this);

        let parentElement = displayInput.parent();
        let  selectionInput = parentElement.children(".selection-input");
        let select2Container = parentElement.children(".select2-container");

        //Show Codes Only Instead Of Whole Text
        displayInput.hide();
        selectionInput.show();
        select2Container.show();
    });


    $("tbody").on('change','tr>td>input,tr>td>select',function(event){
        saveForm();
    });


    $("tbody").on('focusin','tr>td>input.rate,tr>td>input.unit',function(event){
        //Just remove commas on input values

        let row = $(this).parent().parent();
        let rate = row.children("td").children(".rate");
        let unit =  row.children("td").children(".unit");

        if(rate.val().trim() != ""){
            let rate_value = Number(rate.val().replace(/\,/g,''));
            if( $(this).hasClass('rate') && rate_value >= 0 ){
                $(this).val(rate_value);
            }
        }

        if(unit.val().trim() != ""){
            let unit_value = Number(unit.val().replace(/\,/g,''));
            if( $(this).hasClass('unit') && unit_value >= 0 ){
                $(this).val(unit_value);
            }
        }


    });


    $("tbody").on('focusout','tr>td>input.rate,tr>td>input.unit',function(event){
        //Just number format input values

        let row = $(this).parent().parent();
        let rate = row.children("td").children(".rate");
        let unit =  row.children("td").children(".unit");

        if(rate.val().trim() != ""){
            let rate_value = Number(rate.val().replace(/\,/g,''));
            if( $(this).hasClass('rate') && rate_value >= 0  ){
                let rate_value_string = formatNumberWithCommaSeparation(rate_value);
                $(this).val(rate_value_string);
            }
        }

        if(unit.val().trim() != ""){
            let unit_value = Number(unit.val().replace(/\,/g,''));
            if( $(this).hasClass('unit') && unit_value >= 0  ){
                let unit_string = formatNumberWithCommaSeparation(unit_value);
                $(this).val(unit_string);
            }
        }


    });


    $("tbody").on('click','tr>td>input.measure,tr>td>input.unit,tr>td>input.percentage',function(event){
        process_input_data($(this));
    });


    $("tbody").on('input','tr>td>input.rate,tr>td>input.unit',function(event){
        process_input_data($(this));
    });


    function process_input_data(currentInput){

        let row = currentInput.parent().parent();
        let rate = row.children("td").children(".rate");
        let unit =  row.children("td").children(".unit");
        let line_total =  row.children("td").children(".line_total");

        let total = $('#total');

        let rate_value = Number(rate.val().replace(/\,/g,''));
        let unit_value = Number(unit.val().replace(/\,/g,''));

        if( currentInput.hasClass('rate') && !( rate_value >= 0 ) ){
            currentInput.val('');
            line_total.val('');
            total.text(0);
        }

        if( currentInput.hasClass('unit') && !( unit_value >= 0 ) ){
            currentInput.val('');
            line_total.val('');
            total.text(0);
        }


        if( rate_value >= 0 && unit_value >= 0 ){

            let line_total_value = ( rate_value*unit_value);
            let line_total_string = formatNumberWithCommaSeparation(line_total_value);
            line_total.val(line_total_string);

            calculate_grand_total();
        }

    }


    function calculate_grand_total(){//In future calculate total from Object not from elements

        let line_totals = $('.line_total');
        let total = 0;

        line_totals.each(function(){
            let line_total_text = $(this).val();
            let line_total = Number(line_total_text.replace(/\,/g,''));
            if(line_total === ''){ line_total = 0;}

            total += line_total;
        });

        let total_string = formatNumberWithCommaSeparation(total);
        $('#total').val(total_string);

    }


    function displayForm(){
        let formHeader = advancePaymentRequest.header;
        let formDetails = advancePaymentRequest.details;

        //Because Header are filled automatically by laravel blade, then reload then only when form is fresh
        let isFormReturnedAfterBackendValidation = $("#is_old_form").text().trim();
        if(isFormReturnedAfterBackendValidation === "false"){
            loadFormHeader(formHeader);
        }

        loadFormDetails(formDetails);
    }


    function loadFormHeader(formHeader){
        $("#project_id").val(formHeader.project_id);
        $("#responsible_spv").val(formHeader.responsible_spv);
        $("#request_date").val(formHeader.request_date);
        $("#purpose").val(formHeader.purpose);
        $("#total").val(formHeader.total);
    }


    function loadFormDetails(formDetails){

        const sampleDetailRow = document.getElementById("details_table_row_sample");
        let sampleDetailRowClone = sampleDetailRow.cloneNode(true);
        let detailsTableBody = document.getElementById("details_table_body");

        if(formDetails.length > 0){
            //Remove All Rows
            detailsTableBody.innerHTML = '';

            //Put back sample row
            detailsTableBody.appendChild(sampleDetailRowClone);
        }

        //add details to details-div
        if(formDetails.length > 0){
            for (let m = 0; m < formDetails.length; m++) {
                let detail = formDetails[m];
                let rowNumber = m +1;
                let newDetailRow = createRowAndLoadDetails(sampleDetailRowClone, detail, rowNumber);
                detailsTableBody.appendChild(newDetailRow);
            }
        }else{
            //put starting row
            let detail = new Detail("1", "", "", "", "", "", "", "", "");
            let rowNumber = 1;
            let newDetailRow = createRowAndLoadDetails(sampleDetailRowClone, detail, rowNumber);
            detailsTableBody.appendChild(newDetailRow);
        }

        $(".searchable-selection").select2({width: '100%', dropdownAutoWidth : false});
        $(".select2-container").hide();

        //load summary/json of details
        $("#details").val(JSON.stringify(formDetails));

    }

    function createRowAndLoadDetails(sampleDetailRowClone, detail, rowNumber){

        let newDetailRow = sampleDetailRowClone.cloneNode(true);
        newDetailRow.removeAttribute('id');
        newDetailRow.classList.remove("sample-row");
        newDetailRow.classList.add("data-row");

        console.log(detail.no);

        newDetailRow.querySelector(".no").value = detail.no;
        newDetailRow.querySelector(".account_id").value = detail.account_id;
        newDetailRow.querySelector(".account_number").value = detail.account_number;
        newDetailRow.querySelector(".activity_id").value = detail.activity_id;
        newDetailRow.querySelector(".activity_code").value = detail.activity_code;
        newDetailRow.querySelector(".description").value = detail.description;
        newDetailRow.querySelector(".rate").value = formatNumberWithCommaSeparation(detail.rate);
        newDetailRow.querySelector(".unit").value = formatNumberWithCommaSeparation(detail.unit);
        newDetailRow.querySelector(".line_total").value = formatNumberWithCommaSeparation(detail.line_total);
        newDetailRow.id = "details_table_row_" + rowNumber;
        newDetailRow.style.removeProperty("display");

        //add select2 class for searchable selection inputs
        newDetailRow.querySelector(".account_id").classList.add("searchable-selection");
        newDetailRow.querySelector(".activity_id").classList.add("searchable-selection");

        if(detail.account_number.length > 0){
            newDetailRow.querySelector(".account_id").style.display = 'none';
        }

        if(detail.activity_code.length > 0){
            newDetailRow.querySelector(".activity_id").style.display = 'none';
        }


        return newDetailRow;

    }


    function saveForm(){
        saveFormHeader();
        saveFormDetails();
    }


    function saveFormHeader(){
        advancePaymentRequest.header.project_id = $("#project_id").val();
        advancePaymentRequest.header.responsible_spv = $("#responsible_spv").val();
        advancePaymentRequest.header.request_date = $("#request_date").val();
        advancePaymentRequest.header.purpose = $("#purpose").val();
        advancePaymentRequest.header.total = Number($("#total").val().replace(/\,/g,''))
    }


    function saveFormDetails(){
        let formDetails = [];

        //get all details rows
        let all_rows = $('#details_table_body').children(".data-row");

        all_rows.each(function(){
            let row_id = $(this).attr('id');
            let row = $('#'+row_id);

            let no = row.children("td").children(".no").val();
            let account_id = row.children("td").children(".account_id").val();
            let account_number = row.children("td").children(".account_number").val();
            let activity_id = row.children("td").children(".activity_id").val();
            let activity_code = row.children("td").children(".activity_code").val();
            let description = row.children("td").children(".description").val();
            let rate = row.children("td").children(".rate").val();
            let unit = row.children("td").children(".unit").val();
            let line_total = row.children("td").children(".line_total").val();

            rate = Number(rate.replace(/\,/g,''));
            unit = Number(unit.replace(/\,/g,''));
            line_total = Number(line_total.replace(/\,/g,''));


            let detail = new Detail(no, account_id, account_number, activity_id, activity_code, description, rate, unit, line_total);
            formDetails.push(detail);

        });

        advancePaymentRequest.details = formDetails;

        //load summary/json of details
        $("#details").val(JSON.stringify(advancePaymentRequest.details));

    }


    function clearForm(){
        advancePaymentRequest = originalAdvancePaymentRequest;
        displayForm(advancePaymentRequest);
    }


    function check_if_columns_are_filled_in_the_row(row_id){

        let row = $('#'+row_id);
        let is_filled = false;

        let no = row.children("td").children(".no").val().trim();
        let account_id = row.children("td").children(".account_id").val()?.trim();
        let account_number = row.children("td").children(".account_number").val().trim();
        let activity_id = row.children("td").children(".activity_id").val()?.trim();
        let activity_code = row.children("td").children(".activity_code").val().trim();
        let description = row.children("td").children(".description").val().trim();
        let rate = row.children("td").children(".rate").val().trim();
        let unit = row.children("td").children(".unit").val().trim();
        let line_total = row.children("td").children(".line_total").val().trim();

        if(
            (undefined !== no && no.length) &&
            (undefined !== account_id && account_id.length) &&
            (undefined !== account_number && account_number.length)  &&
            (undefined !== activity_id && activity_id.length) &&
            (undefined !== activity_code && activity_code.length) &&
            (undefined !== description && description.length) &&
            (undefined !== rate && rate.length) &&
            (undefined !== unit && unit.length) &&
            (undefined !== line_total && line_total.length)
        ){
            if(
                no.length>0 &&
                account_id.length>0 &&
                account_number.length>0 &&
                activity_id.length>0 &&
                activity_code.length>0 &&
                description.length>0 &&
                rate.length>0 &&
                unit.length>0 &&
                line_total.length>0
            ){
                is_filled = true;
            }

        }

        return is_filled;

    }


    function check_if_required_columns_are_filled_in_all_rows(){

        let all_rows = $('#details_table_body').children(".data-row");
        let are_rows_filled = true;

        all_rows.each(function(){
            let row_id = $(this).attr('id');

            let is_row_filled = check_if_columns_are_filled_in_the_row(row_id);

            are_rows_filled = are_rows_filled && is_row_filled;
        });

        return are_rows_filled;
    }


    function validateForm(){
        let error_message = "";
        console.log(advancePaymentRequest);

        if(advancePaymentRequest.header.project_id.length < 1){
            error_message = "Please select Project before submitting the Request";
            sweet_alert_error(error_message);
            return false;
        }else if(advancePaymentRequest.header.responsible_spv.length < 1){
            error_message = "Please specify Supervisor before submitting the Request";
            sweet_alert_error(error_message);
            return false;
        }else if(advancePaymentRequest.header.request_date.length < 1){
            error_message = "Please provide Request Date";
            sweet_alert_error(error_message);
            return false;
        }else if(advancePaymentRequest.header.purpose.length < 1){
            error_message = "Please specify the Purpose of Advance Payment";
            sweet_alert_error(error_message);
            return false;
        }
        else if (!check_if_required_columns_are_filled_in_all_rows()){
            error_message = "Blank Details are not allowed, make sure all Details columns' are filled";
            sweet_alert_error(error_message);
            return false;
        }else if(advancePaymentRequest.details.length < 1){
            error_message = "You can't submit Advance Payment Request with empty Details, please add some details then submit";
            sweet_alert_error(error_message);
            return false;
        }else if(advancePaymentRequest.header.total <= 0){
            error_message = "You can't submit Advance Payment Request with empty Details, please add some details then submit";
            sweet_alert_error(error_message);
            return false;
        }

        return true;

    }


</script>


<!-- Script for fetching data -->
<script type="text/javascript">


    $("#project_id").on('change',function () {
        let project_name = $(this).find(":selected").text();
        let project_id = $(this).find(":selected").val();

        //clear all activities selections
        $(".activity_id option").remove();
        $(".activity_code").val("");

        get_activities(project_id);
        projectListStatus = "re-load";
    });


    function get_activities(project_id){
        let _token = $('input[name="_token"]').val();

        $.ajax({
            url:"{{ route('activities.ajaxGetByProject') }}",
            method:"POST",
            data:{project_id:project_id,_token:_token},
            success:function(data)
            {
                //add the list to selection option
                data = JSON.parse(data);
                add_to_activities_selections(data);
            }
        });
    }


    function add_to_activities_selections(activities){

        //add selection options
        $('.activity_id').append($("<option></option>").attr("value",'').text('Select Activity'));

        //Reset all codes selected
        $('.activity_code').val("");

        //Populate options
        if (activities.length > 0) {
            $.each(activities,function(i,activity){
                $('.activity_id').append($("<option></option>").attr("value",activity.id).text(activity.code + ' : ' +activity.name));
            });
        }

        loadSelections();

    }


    function loadSelections(){

        if(projectListStatus === "load"){

            let dataRows = $(".data-row");
            let details = advancePaymentRequest.details;

            if(details.length > 0){
                for (let m = 0; m < dataRows.length; m++) {
                    let dataRow = dataRows.eq(m);
                    let detail = details[m];

                    if(detail.account_id !== undefined){
                        let accountIdElement = dataRow.find("td.details-account>select.account_id").first();
                        let select2Container = dataRow.find("td.details-account>span.select2-container").first();

                        //accountIdElement.val(detail.account_id);
                        accountIdElement.hide();
                        select2Container.hide();

                        let accountCodeElement = dataRow.find("td.details-account>input.account_number").first();
                        accountCodeElement.val(detail.account_number);
                        accountCodeElement.show();
                    }



                    if(detail.activity_id !== undefined){
                        let activityIdElement = dataRow.find("td.details-activity>select.activity_id").first();
                        let select2Container = dataRow.find("td.details-activity>span.select2-container").first();

                        //console.log(select2Container);

                        activityIdElement.val(detail.activity_id.length > 0 ? detail.activity_id : "");
                        detail.activity_code.length > 0 ?  activityIdElement.hide() : activityIdElement.show() ;
                        detail.activity_code.length > 0 ?  select2Container.hide() : select2Container.show() ;

                        let activityCodeElement = dataRow.find("td.details-activity>input.activity_code").first();
                        activityCodeElement.val(detail.activity_code.length > 0 ? detail.activity_code : "");
                        detail.activity_code.length > 0 ? activityCodeElement.show() : activityCodeElement.hide();
                    }


                }
            }

        }

        else if(projectListStatus === "re-load"){
            $(".data-row").each(function (){
                let activityIdElement = $(this).children(".activity_id");
                activityIdElement.val("");
                activityIdElement.show();

                let activityCodeElement = $(this).children(".activity_code");
                activityCodeElement.val("");
                activityCodeElement.hide();

                let select2Container = activityCodeElement.children(".select2-container");
                select2Container.show();
            });
        }

    }


</script>


<!-- Script for Submitting the form -->
<script type="text/javascript">

@if( $responseType == "quick-create" || $responseType == "quick-edit")
//Ajax Form Submission

    let responseType = "{{$responseType}}";
    let form = $("#form");
    let submit_btn = $("#submit_btn");
    let formUrl = "{{ url('advance_payment_request') }}";
    let formMethod = "POST";

    if(responseType === "quick-edit"){
        formUrl = "{{ url('advance_payment_request_update/'.$advance_payment_request->id) }}";
        formMethod = "PATCH";
    }

    form.submit(function(event){
        //for now we are using custom validation. we will set proper jquery validation later

        saveForm();
        let isFormValid = validateForm();
        if(isFormValid){
            //If we set jquery form validation properly we will just use this inner piece of code
            form.validate({
                rules: {
                    project_id: {
                        required: true,
                    },
                    responsible_spv: {
                        required: true,
                    },
                    request_date: {
                        required: true,
                    },
                    purpose: {
                        required: true,
                    },
                    total: {
                        required: true,
                    },
                    terms: {
                        required: true,
                    },
                },
                messages: {
                    project_id: {
                        required: "Please Select Project",
                    },
                    responsible_spv: {
                        required: "Please Specify Your Supervisor",
                    },
                    request_date: {
                        required: "Please Provide Request Date",
                    },
                    purpose: {
                        required: "Please Specify the Purpose of Advance Payment Request",
                    },
                    total: {
                        required: "Total should be greater than zero",
                    },
                    terms: {
                        required: "You must agree to terms and conditions",
                    },
                },

                submitHandler: function(form) {
                    $.ajaxSetup({
                        headers: {
                            "X-CSRF-TOKEN" : '{{ csrf_token() }}',
                            "Authorization": "Bearer " + "{{ Cookie::get('access_token') }}",
                        }
                    });

                    submit_btn.html('Please Wait...');
                    submit_btn.attr("disabled", true);

                    $.ajax({
                        url: formUrl,
                        type: formMethod, // POST, GET, PATCH
                        data: form.serialize(),
                        success: function( response ) {
                            submit_btn.html('Submit Advance Payment Request');
                            submit_btn. attr("disabled", false);

                            sweet_alert_success('Advance Payment Request has been submitted successfully');
                            document.getElementById("form").reset();
                        }
                    });
                }
            })

        }
    });

@else
//Normal Form Submission by POST

    $("form").submit(function(event){
        event.preventDefault();

        saveForm();
        let isFormValid = validateForm();

        if(isFormValid){
            //submit the form
            $(this).unbind('submit').submit();

        }
    });

@endif

</script>



<!-- Rows Editing Script-->
<script type="text/javascript">


    $('.add_row,.add_row_after,.add_row_before').on('click',function(event){
        event.preventDefault();

        let all_checkboxes = $('.select-one-row-checkbox');
        let checked_boxes = 0;
        let position = 'after';

        if($(this).hasClass('add_row_before')){
            position = 'before';
        }else if($(this).hasClass('add_row_after')){
            position = 'after';
        }

        all_checkboxes.each(function(){
            if($(this).is(":checked")){
                checked_boxes += 1;
                let current_row = $(this).parent().parent();

                //add another row after/before this row
                add_another_row(current_row,position);
            }
        });

        if(checked_boxes == 0){
            //sweet_alert_warning('There is no any row selected, please select a row to add another row below it');
            //add new row after last row
            let current_row = $('#details_table_body tr').last();
            position = 'after';
            add_another_row(current_row,position);
        }

    });


    $('.remove_row').on('click',function(event){
        event.preventDefault();

        let all_checkboxes = $('.select-one-row-checkbox');
        let checked_boxes = 0;

        all_checkboxes.each(function(){

            if($(this).is(":checked")){
                checked_boxes += 1;
                let current_row = $(this).parent().parent();
                let current_row_id = current_row.attr('id');
                let id_parts = current_row_id.split('_');
                let id_no = id_parts[id_parts.length-1];
                let id_base = current_row_id.replace('_'+id_no,'');

                //remove row
                if(id_no > 1){ current_row.remove(); }

                //rearrange items in a table (id & names)
                let table_name = id_base.replace('_row','');
                rearrange_items_in_a_table(table_name);
            }

        });


        if(checked_boxes == 0){
            sweet_alert_warning('There is no any item selected, please select a item first, then delete it');
        }

    });


    //when select-multiple-rows checkbox is clicked
    $(".select-multiple-rows").on("click",function(){

        let select_all = '';
        $(this).is(":checked") ? select_all = 'true' : select_all = 'false';

        //get all check boxes in a table
        let current_table = $(this).parent().parent().parent().parent();
        let all_checkboxes_in_a_table = current_table.find('.select-one-row-checkbox');

        //select all checkboxes in a table
        all_checkboxes_in_a_table.each(function(index){

            select_all == 'true' ? $(this).prop('checked',true) :  $(this).prop('checked',false);
        });
    });


    function add_another_row(current_row,position){
        const sample_row = $("#details_table_row_sample");

        let current_row_id = current_row.attr('id');
        let id_parts = current_row_id.split('_');
        let id_no = id_parts[id_parts.length-1];
        let id_base = current_row_id.replace('_'+id_no,'');
        let new_row = sample_row.clone();

        new_row.removeAttr('id');
        new_row.show();
        new_row.removeClass("sample-row");
        new_row.addClass("data-row");

        //reset new row values
        let new_row_id = id_base+'_x';
        new_row.attr('id',new_row_id);

        let new_row_columns = new_row.children('td');

        $(new_row_columns).each(function(){
            //Clear Inputs
            let column_input= $(this).children('input');
            let column_select= $(this).children('select');

            column_input.val('');
            column_select.val('');

            column_select?.addClass("searchable-selection");
            column_select?.select2({width: '100%', dropdownAutoWidth : false});

            if( column_input.hasClass('select-one-row-checkbox')){
                //un-check the selection box
                column_input.prop('checked',false);
                column_input.val('x');
            }
        });



        let is_filled = check_if_columns_are_filled_in_the_row(current_row_id);

        if(is_filled){

            //add the row to the table
            if( position === 'after' ){
                //append new row to the last row
                $('#'+current_row_id).after(new_row);
            }
            if( position === 'before' ){
                //append new row to the last row
                $('#'+current_row_id).before(new_row);
            }

            //rearrange items in a table (id & names)
            let table_name = id_base.replace('_row','');
            rearrange_items_in_a_table(table_name);
            $(".data-column>.select2-container").hide();



        }else {
            sweet_alert_warning('In order to add another row please fill the row above first');
        }


    }


    function rearrange_items_in_a_table(table_name){
        let current_table = $('#'+table_name);
        let rows = current_table.children('tbody').children('tr.data-row');

        let n = 1;
        rows.each(function(){

            //rearrange row ids
            let current_row = $(this);
            let row_id = table_name+'_row_'+n;
            current_row.attr('id',row_id);

            //rename inputs in a row

            let row_columns = current_row.children('td');

            $(row_columns).each(function(){

                //deal with inputs and selection
                let column_input= $(this).children('input,select');

                if( column_input.hasClass('select-one-row-checkbox') ){
                    column_input.val(n);
                }
                else if( column_input.hasClass('no')){
                    column_input.attr('name',row_id+'[]');
                    column_input.val(n);
                }
                else{
                    column_input.attr('name',row_id+'[]');
                }


            });

            n++;
        });

    }


</script>



<!-- Sweet Alerts & Other Common Functions-->
<script type="text/javascript">



    function formatNumberWithCommaSeparation(value)  {

        let formattedNumber = '0.00';
        //if(!value) return '0.00';
        let intPart = (value.toString().split('.'))[0];
        let intPartFormat = intPart.toString().replace(/(\d)(?=(?:\d{3})+$)/g, '$1,');
        let floatPart = ".00";
        let modifiedValue = Number(value).toFixed(2).toString() + '';

        //let value2Array = value.split(".");
        let value2Array = modifiedValue.split(".");

        if(value2Array.length == 2) {
            floatPart = value2Array[1].toString();
            if(floatPart.length == 1) {
                formattedNumber = intPartFormat + "." + floatPart + '0';
            } else {
                formattedNumber = intPartFormat + "." + floatPart;
            }
        } else {
            formattedNumber = intPartFormat + floatPart;
        }

        return formattedNumber;
    }


    function convert_numbers_to_words(amount){

        var num2words = new NumberToWords();
        var converted_amount = num2words.numberToWords(amount);
        var full_amount = converted_amount[0];
        var cents = converted_amount[1];

        var currency = $("#currency option:selected").html();
        var currency_text = (currency.split('('))[0];
        currency_text = currency_text.trim();


        var total_amount_in_words = '';
        if( full_amount != '' ){
            total_amount_in_words = full_amount + ' ' + currency_text;

            if( cents != '' ){
                total_amount_in_words += ' and ' + cents + ' cents.'
            }
        }

        $('#total_amount_in_words').text(total_amount_in_words.toUpperCase());

    }


    function sweet_alert_success(success_message){
        Swal.fire({
            type: "success",
            text: success_message,
            confirmButtonColor: '#008FDC',
        })
    }


    function sweet_alert_error(error_message){
        Swal.fire({
            type: "error",
            text: error_message,
            confirmButtonColor: '#008FDC',
        })
    }


    function sweet_alert_warning(warning_message){
        Swal.fire({
            type: "warning",
            text: warning_message,
            confirmButtonColor: '#008FDC',
        })
    }


</script>
