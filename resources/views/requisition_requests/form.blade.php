<?php
$read_only = '';
$disabled = '';
$visibility = '';
if( isset($travel_request->status) && isset($travel_request->staff_id) ){
    if( !in_array($travel_request->status, ['0'])  ||
        ( in_array($travel_request->status, ['0']) && $view_type2 == 'show_admin')
    ){
        $read_only = 'readonly';
        $disabled = 'disabled';
        $visibility = 'invisible';
    }
}
?>

<div class="row">

    <!-- This is travel request form -->
    <div class="col-sm-12" >

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title text-danger">Requisition Request Form</h5>

                @if( !isset($travel_request->status))
                    <form action="/requisition_request" method="POST" enctype="multipart/form-data" id="valid-form">
                        @elseif( isset($travel_request->status) && $travel_request->status == 0 && $travel_request->staff_id == auth()->user()->staff->id)
                            <form action="/requisition_request_update/{{$travel_request->id}}" method="POST" enctype="multipart/form-data" id="valid-form">
                                @method('PATCH')
                                @else
                                    <form id="valid-form">
                                    @endif
                                    @csrf

                                    <!-- Header-->
                                        <fieldset>
                                            <legend class="text-danger"></legend>
                                            <div class="form-row">
                                                <div class="col-md-3">
                                                    <div class="position-relative form-group">
                                                        <label for="project_code" class="">
                                                            <span>Project Name</span>
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <select name="project_code" id="project_code" class="form-control @error('country') is-invalid @enderror" {{$disabled}}>
                                                            <option value="">Select Project</option>
                                                            @foreach($projects as $code=>$name)
                                                                <option value="{{$code}}" @if(($code == old('project_code')) || ($code == $travel_request->project_code)) selected @endif>
                                                                    {{$name}}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        @error('project_code')
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
                                                        <select name="responsible_spv" id="responsible_spv" class="form-control @error('country') is-invalid @enderror" {{$disabled}}>
                                                            @if($supervisors_mode == '2')<option value="">Select Supervisor</option>@endif
                                                            @foreach($supervisors as $spv)
                                                                <option value="{{$spv->id}}" @if(($spv->id == old('responsible_spv')) || ($spv->id == $travel_request->responsible_spv)) selected @endif>
                                                                    {{ucwords($spv->first_name.' '.$spv->last_name)}}
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
                                                        <label for="requested_date" class="">
                                                            <span>Requested Date</span>
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input name="requested_date" id="returning_date" type="text" class="form-control @error('returning_date') is-invalid @enderror" value="{{ old('requested_date') ?? $travel_request->requested_date}}" autocomplete="off" {{$disabled}}>

                                                        @error('returning_date')
                                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12" id="description_div">
                                                    <div class="position-relative form-group">
                                                        <label for="purpose_of_trip" class="">
                                                            Purpose Of Use
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <textarea name="purpose_of_use" id="purpose_of_trip" class="form-control @error('purpose_of_trip') is-invalid @enderror" autocomplete="off" {{$disabled}}>{{ old('purpose_of_use') ?? $travel_request->purpose_of_use}}</textarea>

                                                        @error('purpose_of_trip')
                                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <!-- End Of Header -->
                                        <input type="file" name="file">
                                        @if( $user_role == 5 || $user_role == 4 || $user_role == 9 || $user_role == 2 )
                                        <a href="{{url('/requisitionFilePreview',$travel_request->id)}}" class="btn btn-secondary btn-s" role="button"><i class="fa fa-file" aria-hidden="true"></i>View attached file</a>
                                        @endif

            <!-- Lines -->
            <fieldset {{$disabled}}>
                <legend class="text-danger"></legend>
                <div class="row">
                    <div class="col-md-12">
                        <table style="width: 100%;" id="travel_request_table" class="table table-hover table-striped table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 10%;" >S/N</th>
                                <th style="width: 20%;" >Descriptions</th>
                                <th style="width: 15%;" >Quantity</th>
                                
                                <th style="width: 5%;" class="text-center">
                                    <input type="checkbox" name="checkbox_0" class="select-multiple-rows" value="0">
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($travel_request->lines) == 0)
                                <tr class="data-row" id="travel_request_table_row_1">
                                    <td class="data-column no">
                                        <input name="travel_request_table_row_1[]" class="column-input row-no text-left" autocomplete="off" value="1">
                                    </td>
                                    <td class="data-column time">
                                        <input name="travel_request_table_row_1[]" class="column-input account-code" value="{{old('travel_request_table_row_1')[1] ?? ''}}" autocomplete="off">
                                    </td>                             
                                    <td class="data-column time">
                                        <input name="travel_request_table_row_1[]" class="column-input rate text-right" value="{{old('travel_request_table_row_1')[2] ?? ''}}" autocomplete="off" readonly>
                                    </td>
                                    
                                    <td class="data-column text-center">
                                        <input type="checkbox" name="travel_request_table_row_1[]" class="select-one-row-checkbox" value="1">
                                    </td>
                                </tr>

                                <?php $m=2;?>
                                @while(!is_null(old('travel_request_table_row_'.$m)))
                                    <tr class="data-row" id="travel_request_table_row_{{$m}}">
                                        <td class="data-column no">
                                            <input name="travel_request_table_row_{{$m}}[]" class="column-input row-no" autocomplete="off" value="{{$m}}">
                                        </td>
                                        <td class="data-column time">
                                            <input name="travel_request_table_row_{{$m}}[]" class="column-input account-code" autocomplete="off" value="{{ old('travel_request_table_row_'.$m)[1] }}">
                                        </td>
                                        
                                        <td class="data-column time">
                                            <input name="travel_request_table_row_{{$m}}[]" class="column-input rate text-right" autocomplete="off" value="{{ old('travel_request_table_row_'.$m)[2] }}" readonly>
                                        </td>
                                        
                                        <td class="data-column text-center">
                                            <input type="checkbox" name="travel_request_table_row_{{$m}}[]" class="select-one-row-checkbox" value="{{$m}}">
                                        </td>
                                    </tr>
                                    <?php  $m++;?>
                                @endwhile
                            @else
                                <?php $n = 1;?>
                                @foreach($lines as $line)
                                    <tr class="data-row" id="travel_request_table_row_{{$n}}">
                                        <td class="data-column">
                                            <input name="travel_request_table_row_{{$n}}[]" class="column-input row-no" autocomplete="off" value="{{$n}}">
                                        </td>
                                        <td class="data-column time">
                                            <input name="travel_request_table_row_{{$n}}[]" class="column-input account-code" autocomplete="off" value="{{ old('travel_request_table_row_'.$n)[1] ?? $line[1]}}">
                                        </td>
                                        
                                        <td class="data-column time">
                                            <input name="travel_request_table_row_{{$n}}[]" class="column-input rate text-right" autocomplete="off" value="{{ old('travel_request_table_row_'.$n)[2] ?? $line[2]}}" readonly>
                                        </td>
                                        
                                        <td class="data-column text-center">
                                            <input type="checkbox" name="travel_request_table_row_{{$n}}[]" class="select-one-row-checkbox" value="{{$n}}">
                                        </td>
                                    </tr>
                                    <?php $n++;?>
                                @endforeach

                                <?php $m=$n;?>
                                @while(!is_null(old('travel_request_table_row_'.$m)))
                                    <tr class="data-row" id="travel_request_table_row_{{$m}}">
                                        <td class="data-column no">
                                            <input name="travel_request_table_row_{{$m}}[]" class="column-input line-no" autocomplete="off" value="{{$m}}">
                                        </td>
                                        <td class="data-column time">
                                            <input name="travel_request_table_row_{{$m}}[]" class="column-input account-code" autocomplete="off" value="{{ old('travel_request_table_row_'.$m)[1] }}">
                                        </td>
                                        
                                        <td class="data-column time">
                                            <input name="travel_request_table_row_{{$m}}[]" class="column-input rate text-right" autocomplete="off" value="{{ old('travel_request_table_row_'.$m)[2] }}" readonly>
                                        </td>
                                    
                                        
                                        <td class="data-column text-center">
                                            <input type="checkbox" name="travel_request_table_row_{{$m}}[]" class="select-one-row-checkbox" value="{{$m}}">
                                        </td>
                                    </tr>
                                    <?php  $m++;?>
                                @endwhile
                            @endif
                            </tbody>
                            <tfoot>
                            <th style="width: 10%;" >S/N</th>
                                <th style="width: 20%;" >Descriptions</th>
                                <th style="width: 15%;" >Quantity</th>
                                
                                <th style="width: 5%;" class="text-center">
                                    <input type="checkbox" name="checkbox_0" class="select-multiple-rows" value="0">
                                </th> 
                            </tfoot>
                            

                        </table>

                        <div class="text-center {{$visibility}}">
                            <button class="btn btn-secondary btn-s add_row_after">
                                <i class="fa fa-fw" aria-hidden="true" title="Copy to use plus"></i>
                                Add Row After
                            </button>
                            <button class="btn btn-secondary btn-s add_row_before">
                                <i class="fa fa-fw" aria-hidden="true" title="Copy to use plus"></i>
                                Add Row Before
                            </button>
                            <button class="btn btn-secondary btn-s remove_row">
                                <i class="fa fa-fw" aria-hidden="true" title="Copy to use minus"></i>
                                Remove Line
                            </button>
                        </div>

                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="position-relative form-group">
                            <input type="hidden" name="terms[]" value="no">
                            <input type="checkbox" name="terms[]" id="terms" value="yes" @if( (is_array(old('terms')) &&  in_array('yes',old('terms'))) || in_array('yes',$terms )) checked @endif >
                            <span class="text-primary">
                I <span class="text-danger">{{$employee_name}}</span>  declare by accepting funds from SHDEPHA that I will retire these funds according to
                SHDEPHA  policies and procedures with genuine receipts.
            </span>

                            @error('you must declare')
                            <span class="invalid-feedback" role="alert" style="display: block">
                <strong>{{ $message }}</strong>
            </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </fieldset>
            <!-- End Of Lines -->
                                        <button class="mt-2 btn btn-primary {{$visibility}}" type="submit" id="submit_btn">Submit Requisition Request</button>

                                    </form>

            </div>
        </div>

    </div>
    <!-- end of travel request form section -->

</div>


<script src="{{ asset('js/bootstrap-datetimepicker.js') }}"></script>
<script type="text/javascript">

    $(function () {

        $('#departure_date,#returning_date').datepicker({
            format: 'dd/mm/yyyy'
        });

        $('#notifications-div').delay(7000).fadeOut('slow');

        calculate_grand_total();

    });


    $("tbody").on('click','tr>td>input.rate',function(event){

        var row = $(this).parent().parent();
        var description = row.children("td").children(".account_code");

        if( description.val() == ''){

            sweet_alert_warning('Please fill the Activity field first');
        }
        else{
            $(this).removeAttr('readonly');
        }

    });

    $("tbody").on('click','tr>td>input.measure,tr>td>input.unit,tr>td>input.percentage',function(event){

        var row = $(this).parent().parent();
        var quantity = row.children("td").children(".rate");
        var total_cost =  row.children("td").children(".total-cost"); 
       

           // if(unit.val() == ''){ unit.val('1')}
            //if(percentage.val() == ''){  percentage.val('100')}

            var total = (rate.val());
           // total_cost.val(total.toFixed(2));

            //calculate_grand_total()

            // measure.removeAttr('readonly');
            // unit.removeAttr('readonly');
            // percentage.removeAttr('readonly');

     

    });

    $("tbody").on('change','tr>td>input.rate,tr>td>input.unit,tr>td>input.percentage',function(event){

        var row = $(this).parent().parent();
        var rate = row.children("td").children(".rate");
       
        var total_cost =  row.children("td").children(".total-cost");
        var grand_total = $('#grand_total');

        var rate_value = Number(rate.val());
        var unit_value = Number(unit.val());
        var percentage_value = Number(percentage.val());

        if( $(this).hasClass('rate') && !( rate_value >= 0 ) ){
            $(this).val('');
            total_cost.val('');
            grand_total.text(0);
        }

        if( $(this).hasClass('unit') && !( unit_value >= 0 ) ){
            $(this).val('');
            total_cost.val('');
            grand_total.text(0);
        }

        if( $(this).hasClass('percentage') && !( percentage_value >= 0 ) ){
            $(this).val('');
            total_cost.val('');
            grand_total.text(0);
        }

        if( rate_value >= 0 && unit_value >= 0 && percentage_value >= 0 ){

            var total = ( rate_value*unit_value*percentage_value)/100;
            total_cost.val(total.toFixed(2));

            calculate_grand_total();

        }

    });

    function calculate_grand_total(){
        var totals = $('.total-cost');
        var grand_total = 0;

        totals.each(function(){
            var total = $(this).val();
            if(total == ''){ total = 0;}

            grand_total += Number(total);
        });

        $('#grand_total').text(grand_total.toFixed(2));

    }

    function check_if_columns_are_filled_in_the_row(row_id){

        var row = $('#'+row_id);
        var account_code = row.children("td").children(".account-code").val();
        var activity = row.children("td").children(".activity").val();
        var rate = row.children("td").children(".rate").val();
        var measure = row.children("td").children(".measure").val();
        var unit = row.children("td").children(".unit").val();
        var percentage = row.children("td").children(".percentage").val();
        var total_cost = row.children("td").children(".total-cost").val();
        var is_filled = false;


        if( (undefined !== account_code && account_code.length) && (undefined !== activity && activity.length) &&
            (undefined !== rate && rate.length)  && (undefined !== measure && measure.length) &&
            (undefined !== unit && unit.length)  && (undefined !== unit && unit.length) &&
            (undefined !== percentage && percentage.length)  && (undefined !== total_cost && total_cost.length)
        ){

            if(account_code.length > 0 && activity.length > 0 && rate.length > 0 && measure.length > 0 && unit.length > 0 &&
                percentage.length > 0 && total_cost.length > 0){
                is_filled = true;
            }

        }

        return is_filled;

    }

    function check_if_required_columns_are_filled_in_all_rows(){

        var all_rows = $('#travel_request_table').children(".data-row");
        var are_rows_filled = true;

        all_rows.each(function(){
            var row_id = $(this).attr('id');

            var is_row_filled = check_if_columns_are_filled_in_the_row(row_id);

            are_rows_filled = are_rows_filled && is_row_filled;
        });

        return are_rows_filled;
    }



    //when Submit Travel Request Button is clicked
    $("form").submit(function(event){
        event.preventDefault();

        var project_code = $("#project_code").val().trim();
        var responsible_spv = $("#responsible_spv").val().trim();
        //var departure_date = $("#departure_date").val().trim();
        //var returning_date = $("#returning_date").val().trim();
        var purpose_of_trip = $("#purpose_of_trip").val().trim();


        if ( project_code === '' ) {

            var error_message = "Please select Project before submitting this Travel Request";
            sweet_alert_error(error_message);

        }
        else if ( responsible_spv === '' ) {

            var error_message = "Please select SUPERVISOR before submitting this Travel Request";
            sweet_alert_error(error_message);

        }
        
        else if ( purpose_of_trip === '' ) {

            var error_message = "Please specify the Purpose Of Trip before submitting this Travel Request";
            sweet_alert_error(error_message);

        }
        else if (!check_if_required_columns_are_filled_in_all_rows()){
            var error_message = "You are not allowed to submit incomplete Travel Request, please fill all required fields!";
            sweet_alert_error(error_message);
        }
        else{
            //submit the form
            $(this).unbind('submit').submit();

        }


    });


</script>



<!-- Common Script-->
<script type="text/javascript">


    $('.add_row_after,.add_row_before').on('click',function(event){
        event.preventDefault();

        var all_checkboxes = $('.select-one-row-checkbox');
        var checked_boxes = 0;
        var position = 'after';

        if($(this).hasClass('add_row_before')){
            position = 'before';
        }else{
            position = 'after';
        }

        all_checkboxes.each(function(){

            if($(this).is(":checked")){
                checked_boxes += 1;
                var current_row = $(this).parent().parent();

                //add another row after this row
                add_another_row(current_row,position);
            }
        });

        if(checked_boxes == 0){
            sweet_alert_warning('There is no any row selected, please select a row to add another row below it');
        }

    });


    $('.remove_row').on('click',function(event){
        event.preventDefault();

        var all_checkboxes = $('.select-one-row-checkbox');
        var checked_boxes = 0;

        all_checkboxes.each(function(){

            if($(this).is(":checked")){
                checked_boxes += 1;
                var current_row = $(this).parent().parent();
                var current_row_id = current_row.attr('id');
                var id_parts = current_row_id.split('_');
                var id_no = id_parts[id_parts.length-1];
                var id_base = current_row_id.replace('_'+id_no,'');

                //remove row
                if(id_no > 1){ current_row.remove(); }

                //rearrange items in a table (id & names)
                var table_name = id_base.replace('_row','');
                rearrange_items_in_a_table(table_name);

                calculate_grand_total();
            }

        });


        if(checked_boxes == 0){
            sweet_alert_warning('There is no any line selected, please select a line first, then delete it');
        }

    });


    //when select-multiple-rows checkbox is clicked
    $(".select-multiple-rows").on("click",function(){

        var select_all = '';
        $(this).is(":checked") ? select_all = 'true' : select_all = 'false';

        //get all check boxes in a table
        var current_table = $(this).parent().parent().parent().parent();
        var all_checkboxes_in_a_table = current_table.find('.select-one-row-checkbox');

        //select all checkboxes in a table
        all_checkboxes_in_a_table.each(function(index){

            select_all == 'true' ? $(this).prop('checked',true) :  $(this).prop('checked',false);
        });
    });


    /******************* sweet alerts *******************************/



    function add_another_row(current_row,position){

        var current_row_id = current_row.attr('id');
        var id_parts = current_row_id.split('_');
        var id_no = id_parts[id_parts.length-1];
        var id_base = current_row_id.replace('_'+id_no,'');
        var new_row = current_row.clone();

        //reset new row values
        var new_row_id = id_base+'_x';
        new_row.attr('id',new_row_id);

        var new_row_columns = new_row.children('td');

        $(new_row_columns).each(function(){
            var column_input= $(this).children('input');
            if( column_input.attr('name') == current_row_id+'[]' ){
                //change input name & reset values
                column_input.attr('name',new_row_id+'[]');
                column_input.val('');

            }
            if( column_input.hasClass('select-one-row-checkbox')){
                //un-check the selection box
                column_input.prop('checked',false);
                column_input.val('x');
            }
        });



        var is_filled = check_if_columns_are_filled_in_the_row(current_row_id);

        // if(is_filled){

            //add the row to the table
            if( position == 'after' ){
                //append new row to the last row
                $('#'+current_row_id).after(new_row);
            }
            if( position == 'before' ){
                //append new row to the last row
                $('#'+current_row_id).before(new_row);
            }


            //rearrange items in a table (id & names)
            var table_name = id_base.replace('_row','');
            rearrange_items_in_a_table(table_name);


        // }else {
        //     sweet_alert_warning('In order to add another row please fill the row above first');
        // }


    }


    

    function sweet_alert_success(success_message){
        Swal.fire({
            type: "success",
            text: success_message,
            confirmButtonColor: '#213368',
        })
    }


    function sweet_alert_error(error_message){
        Swal.fire({
            type: "error",
            text: error_message,
            confirmButtonColor: '#213368',
        })
    }

    function sweet_alert_warning(warning_message){
        Swal.fire({
            type: "warning",
            text: warning_message,
            confirmButtonColor: '#213368',
        })
    }

    $(document).ready(function(){
        
       $(document).on('change', '#project_code', function(){
        var projectCode_id = $(this).val();
        //var opt=" ";
        var select=$('#travel_request_table_row_1').parent();
         $.ajax({
           type:'get',
           url:'requisition_activities',
           data:{'id':projectCode_id},
           success:function(response){
            response.forEach(activity => {
                $('#activityrowu').append("<option value='"+activity.name+"'>"+activity.name+"</option>");
            });
            


            
            // opt+='<option value="{{old('travel_request_table_row_1')[2]}">Select Project</option>';
            // for(var i=0; i<response.length; i++){
            //     opt+='<option value="'+response[i].name+'">'+response[i].name+'</option>';
            // }

            // select.find('#activityrowu').html();
            // select.find('#activityrowu').append(opt);

            // console.log(response);


      },
           error:function(){
             console.log('failed');
           }
         });
        });

      });



</script>

