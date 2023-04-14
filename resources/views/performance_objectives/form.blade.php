
    <?php

        $read_only = '';
        $disabled = '';
        $visibility = '';
        if( isset($performance_objective->status) && isset($performance_objective->staff_id) ){
            if( !in_array($performance_objective->status, ['0','10'])  ||
                ( in_array($performance_objective->status, ['0','10']) && $view_type2 == 'show_admin')
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
                    <h5 class="card-title text-danger">Objectives Form</h5>

                    @if( !isset($performance_objective->status) )
                        <form action="/submit_objectives" method="POST" enctype="multipart/form-data" id="valid-form">
                    @elseif( isset($performance_objective->status) &&  in_array($performance_objective->status,[0,10]) && $performance_objective->staff_id == auth()->user()->staff->id)
                        <form action="/update_objectives/{{$performance_objective->id}}" method="POST" enctype="multipart/form-data" id="valid-form">
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
                                        <label for="employee_name" class="">
                                            <span>Staff Name</span>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input name="employee_name" id="employee_name" type="text" class="form-control" value="{{ old('employee_name') ?? $employee_name}}" autocomplete="off" readonly>

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="department_name" class="">
                                            <span>Department Name</span>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input name="department_name" id="department_name" type="text" class="form-control" value="{{ old('department_name') ?? $department_name}}" autocomplete="off" readonly>

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
                                            @foreach($supervisors as $supervisor)
                                                <option value="{{$supervisor->id}}" @if(($supervisor->id == old('responsible_spv')) || ($supervisor->id == $performance_objective->responsible_spv)) selected @endif>
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
                                        <label for="year" class="">
                                            <span>Year</span>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input name="year" id="year" type="text" class="form-control" value="{{ old('year') ?? $year}}" autocomplete="off" readonly>

                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <!-- End Of Header -->

                        <!-- Lines -->
                        <fieldset {{$disabled}}>
                            <legend class="text-danger"></legend>
                            <div class="row">
                                <div class="col-md-12">
                                    <table style="width: 100%;" id="travel_request_lines_table" class="table table-hover table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th style="width: 20%;">Strategy</th>
                                            <th style="width: 15%;">Activity</th>
                                            <th style="width: 30%;">KPI/Target</th>
                                            <th style="width: 30%;">Time Frame</th>
                                            <th style="width: 5%;" class="text-center">
                                                <input type="checkbox" name="checkbox_0" class="select-multiple-rows" value="0">
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($performance_objective->lines) == 0)
                                            <tr class="data-row last-row" id="1">
                                                <td class="data-column time">
                                                    <input name="line_1[]" class="column-input strategy" autocomplete="off">
                                                </td>
                                                <td class="data-column time">
                                                    <input name="line_1[]" class="column-input activity" autocomplete="off">
                                                </td>
                                                <td class="data-column time">
                                                    <input name="line_1[]" class="column-input target" autocomplete="off">
                                                </td>
                                                <td class="data-column time">
                                                    <input name="line_1[]" class="column-input time-frame" autocomplete="off">
                                                </td>
                                                <td class="data-column text-center">
                                                    <input type="checkbox" name="checkbox_1" class="select-one-row-checkbox" value="1">
                                                </td>
                                            </tr>
                                        @else
                                            <?php $n = 1;?>
                                            @foreach($lines as $line)
                                                <tr class="data-row @if($n == count($lines)) last-row @endif" id="{{$n}}">
                                                    <td class="data-column time">
                                                        <input name="line_{{$n}}[]" class="column-input strategy" autocomplete="off" value="{{$line[0]}}">
                                                    </td>
                                                    <td class="data-column time">
                                                        <input name="line_{{$n}}[]" class="column-input activity" autocomplete="off" value="{{$line[1]}}">
                                                    </td>
                                                    <td class="data-column">
                                                        <input name="line_{{$n}}[]" class="column-input target" autocomplete="off" value="{{$line[2]}}">
                                                    </td>
                                                    <td class="data-column text-center">
                                                        <input name="line_{{$n}}[]" class="column-input time-frame"  value="{{$line[3]}}">
                                                    </td>
                                                    <td class="data-column text-center">
                                                        <input type="checkbox" name="checkbox_{{$n}}" class="select-one-row-checkbox" value="{{$n}}">
                                                    </td>
                                                </tr>
                                                <?php $n++;?>
                                            @endforeach
                                        @endif
                                        </tbody>
                                        <tfoot>
                                        </tfoot>

                                    </table>

                                    <div class="text-center {{$visibility}}">
                                        <button class="mt-2 btn btn-secondary"  id="add_line">
                                            <i class="fa fa-fw" aria-hidden="true" title="Copy to use plus"></i>
                                            Add Line
                                        </button>
                                        <button class="mt-2 btn btn-secondary"  id="remove_line">
                                            <i class="fa fa-fw" aria-hidden="true" title="Copy to use minus"></i>
                                            Remove Line
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </fieldset>
                        <!-- End Of Lines -->

                        <div class="{{$visibility}}">
                            @if(  in_array($performance_objective->status,[null,10]) )
                                <div class="mt-2">
                                    <input class="mt-2 btn btn-secondary" type="submit"  id="save_in_drafts" name="action" value="Save In Drafts">
                                    <input class="mt-2 btn btn-primary" type="submit" id="submit_btn" name="action" value="Submit Objectives">
                                </div>
                            @endif

                            @if( $performance_objective->status == '0' )
                                <div class="mt-2">
                                    <input class="mt-2 btn btn-primary" type="submit" id="submit_btn" name="action" value="Submit Objectives">
                                </div>
                            @endif

                        </div>

                    </form>

                </div>
            </div>

        </div>
        <!-- end of travel request form section -->

        <!-- This is travel requests summary-->
        <div class="col-sm-4"  id="suggestion-div" style="display: none;">

            @if(session()->has('message'))
                <div  class="mb-3 card alert alert-primary" id="notifications-div">
                    <div class="p-3 card-body ">
                        <div class="text-center">
                            <h5 class="" id="message">{{session()->get('message')}}</h5>
                        </div>
                    </div>
                </div>
            @endif

        </div>
        <!-- end of  travel requests summary-->

    </div>

    <script src="{{ asset('js/bootstrap-datetimepicker.js') }}"></script>
    <script type="text/javascript">

        $(function () {

            $('#departure_date,#returning_date').datepicker({
                format: 'dd/mm/yyyy'
            });

            $('#notifications-div').delay(7000).fadeOut('slow');

        });

        $('#add_line').on('click',function (event) {
            event.preventDefault();
            var all_rows = $('.data-row ');
            var last_row = $('.last-row');

            var new_row = '';
            var new_row_opening = '';
            var column1 = '';
            var column2 = '';
            var column3 = '';
            var column4 = '';
            var column5 = '';
            var new_row_closing = '';

            if(all_rows.length >= 1){//means there is at least one row in the table

                var last_row_id  = parseInt(last_row.attr('id'));
                var new_row_id = last_row_id + 1; //alert(last_row_id);

                //remove last-row class because the line will be no longer the last class
                last_row.removeClass("last-row");

                //the new row will be the last row so add last-row class
                new_row_opening = '<tr class="data-row last-row" id="'+new_row_id+'">';

                column1 += '<td class="data-column">';
                column1 += '<input name="line_'+new_row_id+'[]" class="column-input strategy" autocomplete="off">';
                column1 += '</td>';

                column2 += '<td class="data-column time">';
                column2 += '<input name="line_'+new_row_id+'[]" class="column-input activity" autocomplete="off">';
                column2 += '</td>';

                column3 += '<td class="data-column time">';
                column3 += '<input name="line_'+new_row_id+'[]" class="column-input target" autocomplete="off">';
                column3 += '</td>';

                column4 += '<td class="data-column time">';
                column4 += '<input name="line_'+new_row_id+'[]" class="column-input time-frame" autocomplete="off">';
                column4 += '</td>';

                column5 += '<td class="data-column text-center">';
                column5 += '<input type="checkbox" name="checkbox_'+new_row_id+'" class="select-one-row-checkbox" value="'+new_row_id+'">';
                column5 += '</td>';

                new_row_closing = '</tr>';

                new_row = new_row_opening + column1 + column2 + column3 + column4 + column5 + new_row_closing;

                //append new row to the last row
                $('#'+last_row_id).after(new_row);


            }

            else{//this means there is no even one line in the table

                //the new row will be the last row so add last-row class
                new_row_opening = '<tr class="data-row last-row" id="1">';

                column1 += '<td class="data-column">';
                column1 += '<input name="line_1" class="column-input strategy" autocomplete="off">';
                column1 += '</td>';

                column2 += '<td class="data-column time">';
                column2 += '<input name="line_1" class="column-input activity" autocomplete="off">';
                column2 += '</td>';

                column3 += '<td class="data-column time">';
                column3 += '<input name="line_1" class="column-input target" autocomplete="off">';
                column3 += '</td>';

                column4 += '<td class="data-column time">';
                column4 += '<input name="line_1" class="column-input time-frame" autocomplete="off">';
                column4 += '</td>';

                column5 += '<td class="data-column text-center">';
                column5 += '<input type="checkbox" name="checkbox_1" class="select-one-row-checkbox" value="1">';
                column5 += '</td>';

                new_row_closing = '</tr>';


                new_row = new_row_opening + column1 + column2 + column3 + column4 + column5 + new_row_closing;

                //append new row to the last row
                $('tbody').append(new_row);
            }

            //rearrange_line_no();

        });

        $('#remove_line').on('click',function(event){
            event.preventDefault();

            var all_checkboxes = $('.select-one-row-checkbox');
            var checked_boxes = 0;

            all_checkboxes.each(function(){
                var row_id =  $(this).val();
                var previous_row_id = parseInt(row_id)-1;
                var current_row = $('#'+row_id);
                var before_current_row = $('#'+ previous_row_id);

                if($(this).is(":checked")){
                    checked_boxes += 1;

                    //check if the row is the last row before removing it
                    if(current_row.hasClass('last-row')){

                        //in case the previous row have deleted get the row before it
                        while( before_current_row.length == 0){
                            previous_row_id--;
                            before_current_row = $('#'+ previous_row_id);

                            if(previous_row_id ==0){ break;}
                        }

                        before_current_row.addClass('last-row');
                        current_row.remove();
                    }else{
                        $(current_row).remove();
                    }

                    //rearrange_line_no();
                }
            });


            if(checked_boxes == 0){
                sweet_alert_warning('There is no any line selected, please select a line first to delete it');
            }
        });

        //when select-multiple-rows checkbox is clicked
        $(".select-multiple-rows").on("click",function(){

            var select_all = '';
            $(this).is(":checked") ? select_all = 'true' : select_all = 'false';

            //get all check boxes
            var all_checkboxes = $('.select-one-row-checkbox');

            //select of deselect all checkboxes
            all_checkboxes.each(function(index){

                select_all == 'true' ? $(this).prop('checked',true) :  $(this).prop('checked',false);
            });
        });


        //when Submit Travel Request Button is clicked
      /*  $("form").submit(function(event){
            event.preventDefault();

            //set some conditions
            //submit the form
            $(this).unbind('submit').submit();


        }); */


        /******************* sweet alerts *******************************/

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


    </script>