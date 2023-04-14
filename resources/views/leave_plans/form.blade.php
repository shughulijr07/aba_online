<div class="row">

    <!-- This is Planner -->
    <div class="col-md-4" id="validation-div">

        <!-- Leave Planner Form -->
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title text-danger"><span id="leave_type_title1"></span> Planning Form</h5>

                <!-- This is leave entitlement summary-->
                <div id="suggestion-div">

                    <div class="summary-div" id="annual_leave_summary_div">
                        <table style="width: 100%;"  class="table table-hover table-striped table-bordered">
                            <tbody>
                            <tr class='data-row'>
                                <td class="text-danger">
                                    <span class="mr-3">
                                        Entitled Days :
                                        <span id="entitled_annul_leave_days">{{$leave_summary['annual_leave']['entitled-days']}}</span>
                                    </span>

                                    <span class="mr-3">
                                        Days Taken :
                                        <span id="annual_leave_days_taken">{{$leave_summary['annual_leave']['days-taken']}}</span>
                                    </span>

                                    <span class="mr-3">
                                        Balance :
                                        <span id="annual_leave_days_left">{{$leave_summary['annual_leave']['days-left']}}</span>
                                    </span>
                                </td>
                            </tr>
                            <tr class='data-row' style="display: none;">
                                <td>Employee No.</td>
                                <td id="employee_no">{{auth()->user()->staff->staff_no}}</td>
                            </tr>
                            <tr class='data-row' style="display: none;">
                                <td>Employee Email</td>
                                <td id="email">{{auth()->user()->staff->official_email}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="summary-div" id="sick_leave_summary_div" style="display: none;">
                        <table style="width: 100%;"  class="table table-hover table-striped table-bordered">
                            <tbody>
                            <tr class='data-row'>
                                <td class="text-danger">
                                    <span class="mr-3">
                                        Entitled Days :
                                        <span id="entitled_sick_leave_days">{{$leave_summary['sick_leave']['entitled-days']}}</span>
                                    </span>

                                    <span class="mr-3">
                                        Days Taken :
                                        <span id="sick_leave_days_taken">{{$leave_summary['sick_leave']['days-taken']}}</span>
                                    </span>

                                    <span class="mr-3">
                                        Balance :
                                        <span id="sick_leave_days_left">{{$leave_summary['sick_leave']['days-left']}}</span>
                                    </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="summary-div" id="maternity_leave_summary_div" style="display: none;">
                        <table style="width: 100%;"  class="table table-hover table-striped table-bordered">
                            <tbody>
                            <tr class='data-row'>
                                <td class="text-danger">
                                    <span class="mr-3">
                                        Entitled Days :
                                        <span id="entitled_maternity_leave_days">{{$leave_summary['maternity_leave']['entitled-days']}}</span>
                                    </span>

                                    <span class="mr-3">
                                        Days Taken :
                                        <span id="maternity_leave_days_taken">{{$leave_summary['maternity_leave']['days-taken']}}</span>
                                    </span>

                                    <span class="mr-3">
                                        Balance :
                                        <span id="maternity_leave_days_left">{{$leave_summary['maternity_leave']['days-left']}}</span>
                                    </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="summary-div" id="paternity_leave_summary_div" style="display: none;">
                        <table style="width: 100%;"  class="table table-hover table-striped table-bordered">
                            <tbody>
                            <tr class='data-row'>
                                <td class="text-danger">
                                    <span class="mr-3">
                                        Entitled Days :
                                        <span id="entitled_paternity_leave_days">{{$leave_summary['paternity_leave']['entitled-days']}}</span>
                                    </span>

                                    <span class="mr-3">
                                        Days Taken :
                                        <span id="paternity_leave_days_taken">{{$leave_summary['paternity_leave']['days-taken']}}</span>
                                    </span>

                                    <span class="mr-3">
                                        Balance :
                                        <span id="paternity_leave_days_left">{{$leave_summary['paternity_leave']['days-left']}}</span>
                                    </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="summary-div" id="compassionate_leave_summary_div" style="display: none;">
                        <table style="width: 100%;"  class="table table-hover table-striped table-bordered">
                            <tbody>
                            <tr class='data-row'>
                                <td class="text-danger">
                                    <span class="mr-3">
                                        Total Entitled Days :
                                        <span id="entitled_compassionate_leave_days">{{$leave_summary['compassionate_leave']['entitled-days']}}</span>
                                    </span>

                                    <span class="mr-3">
                                        Days Taken :
                                        <span id="compassionate_leave_days_taken">{{$leave_summary['compassionate_leave']['days-taken']}}</span>
                                    </span>

                                    <span class="mr-3">
                                        Balance :
                                        <span id="compassionate_leave_days_left">{{$leave_summary['compassionate_leave']['days-left']}}</span>
                                    </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="summary-div" id="other_summary_div" style="display: none;">
                        <table style="width: 100%;"  class="table table-hover table-striped table-bordered">
                            <tbody>
                            <tr class='data-row'>
                                <td class="text-danger">
                                    <span class="mr-3">
                                        Entitled Days :
                                        <span id="entitled_other_days">{{$leave_summary['other']['entitled-days']}}</span>
                                    </span>

                                    <span class="mr-3">
                                        Days Taken :
                                        <span id="other_days_taken">{{$leave_summary['other']['days-taken']}}</span>
                                    </span>

                                    <span class="mr-3">
                                        Balance :
                                        <span id="other_days_left">{{$leave_summary['other']['days-left']}}</span>
                                    </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                <!-- end of leave entitlement summary-->


                <form action="/leave_plans" method="POST" enctype="multipart/form-data" id="valid-form">
                    @csrf
                    {{ csrf_field() }}

                    <fieldset>
                        <legend class="text-danger"></legend>
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="leave_type" class="">
                                        <span>Type Of Leave</span>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="leave_type" id="leave_type" class="form-control @error('country') is-invalid @enderror">
                                        <!--<option value="">Select Leave</option>-->
                                        @foreach($leave_types as $key=>$value)
                                            <option value="{{$key}}" @if(($key == old('leave_type')) || ($key == $leave_type)) selected @endif>
                                                {{$value['name']}}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('leave_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="starting_date" class="">
                                        <span>Leave Starting Date</span>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input name="starting_date" id="starting_date" type="text" class="form-control @error('starting_date') is-invalid @enderror" value="{{ old('starting_date') ?? $leave_plan->starting_date}}" autocomplete="off">

                                    @error('starting_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                     </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="ending_date" class="">
                                        <span>Leave Ending Date</span>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input name="ending_date" id="ending_date" type="text" class="form-control @error('ending_date') is-invalid @enderror" value="{{ old('ending_date') ?? $leave_plan->ending_date}}" autocomplete="off">

                                    @error('ending_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                     </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="responsible_spv" class="">
                                        <span>Supervisor</span>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="responsible_spv" id="responsible_spv" class="form-control @error('country') is-invalid @enderror">
                                        @if($supervisors_mode == '2')<option value="">Select Supervisor</option>@endif
                                        @foreach($leaveSupervisors as $supervisor)
                                            <option value="{{$supervisor->id}}" @if(($supervisor->id == old('responsible_spv')) || ($supervisor->id == $responsible_spv)) selected @endif>
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
                            <div class="col-md-12" id="description_div" style="display: none;">
                                <div class="position-relative form-group">
                                    <label for="description" class="">
                                        Description <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') ?? $leave_plan->description}}" autocomplete="off"></textarea>

                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                     </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12" id="document_div" style="display: none;">
                                <div class="position-relative form-group">
                                    <div class="text-center" style="margin-bottom: 10px;">
                                        <label for="supporting_document" class="upload-label col-md-12" type="button">
                                            <i class="pe-7s-upload"></i>
                                            <span style="padding-left: 7px; padding-right: 5px;">
                                            Supporting Document
                                        </span>
                                        </label>
                                        <input name="supporting_document" id="supporting_document" type="file"  accept=".jpeg,.jpg,.png,.pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" class="form-control-file" style="display:none;" >
                                    </div>


                                    @error('barcodes_file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-12" style="display: none; " id="file-name-div">
                                <div class="position-relative form-group">
                                    <div style="padding: 5px;">
                                        <i class="pe-7s-news-paper" id="file-icon" ></i>
                                        <span id="file-name"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2" id="include_payment_div" style="display: none;">
                                <div class="position-relative form-group">
                                    <input type="hidden" name="include_payment[]" value="no">
                                    <input type="checkbox" name="include_payment[]" id="include_payment" value="yes" @if( (is_array(old('include_payment')) &&  in_array('yes',old('include_payment'))) || in_array('yes',$include_payment )) checked @endif >
                                    <span class="text-primary">Include Leave Payment</span>

                                    @error('include_payment')
                                    <span class="invalid-feedback" role="alert" style="display: block">
                                        <strong>{{ $message }}</strong>
                                     </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="position-relative form-group">
                                    <span class="text-danger">Number Of Days Requested</span>
                                    <span class="badge badge-pill badge-danger ml-2 mr-2" id="days_requested">0</span>
                                </div>
                            </div>
                            @if($include_weekends_in_leave ==2 || $include_holidays_in_leave==2)
                            <div class="col-md-12 mt-2">
                                <div class="position-relative form-group">
                                    <span class="text-primary" id="excluded_text">Number Of Days Excluded</span>
                                    <span class="badge badge-pill badge-primary ml-2 mr-2" id="days_excluded">0</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </fieldset>

                    <button class="mt-2 btn btn-primary" id="request_leave_btn">Add To My Leave Plan</button>
                </form>

            </div>
        </div>



    </div>
    <!-- end of leave Planner -->




    <!-- This is plans list-->
    <div class="col-md-8">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">
                    Leave Plan For The Year <span class="text-primary" id="current_year">{{date('Y')}}</span>
                </h5>

                <!-- Plans Start Here-->
                <table style="width: 100%;" id="example1" class="table table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Leave Type</th>
                        <th>Days</th>
                        <th>Starting Date</th>
                        <th>Ending Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php $n=1;?>
                    @foreach($leave_plan_lines as $plan)
                        <tr>
                            <td>{{ $plan->id }}</td>
                            <td>{{ $leave_types[$plan->type_of_leave]['name']}}</td>
                            <td>{{ \App\Models\MyFunctions::calculate_no_of_days_btn_dates($plan->starting_date,$plan->ending_date) }}</td>
                            <td>{{ date('d-m-Y', strtotime($plan->starting_date)) }}</td>
                            <td>{{ date('d-m-Y', strtotime($plan->ending_date))  }}</td>
                            <td>
                                <a href="/leave_plan_remove_line/{{ $plan->id }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true">Remove</a>
                            </td>
                        </tr>
                        <?php $n++;?>
                    @endforeach

                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
                @if($leave_plan->status == '0' || $leave_plan->status == '10')
                    <div class="text-center">
                        <a href="/leave_plan_submit/{{ $leave_plan->id }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true">Submit My Leave Plan</a>
                    </div>
                @endif
                <!-- Plans Ends Here-->


            </div>
        </div>
    </div>
    <!-- end of leave plans list -->




    <!-- useful hidden values are stored here-->
    <div style="display: none;">
        <span id="staff_gender">{{$staff_gender}}</span>

        @if($staff_gender == 'Female')
            <span id="normal_maternity_leave">{{$unfiltered_leave_types['maternity_leave']['days']}}</span>
            <span id="twins_maternity_leave">{{$unfiltered_leave_types['maternity_leave_2']['days']}}</span>
        @endif

        <span id="include_weekends">@if($include_weekends_in_leave == 1) yes @else no @endif</span>
        <span id="include_holidays">@if($include_holidays_in_leave == 1) yes @else no @endif</span>

        <div id="holidays">
            @foreach($holidays1 as $holiday_date1=>$holiday_name1)
                <span class="holiday-date" id="{{date('j-n-Y',strtotime($holiday_date1))}}">{{$holiday_name1}}</span>
            @endforeach
            @foreach($holidays2 as $holiday_date2=>$holiday_name2)
                <span class="holiday-date" id="{{date('j-n-Y',strtotime($holiday_date2))}}">{{$holiday_name2}}</span>
            @endforeach
        </div>
    </div>

</div>


<!--scripts -->
<script type="text/javascript">

    $(document).ready(function(){
        $('#starting_date,#ending_date').datepicker({
            format: 'dd/mm/yyyy'
        });

        var annual_leave_payment_status = $("#annual_leave_payment_status").text();
        if(annual_leave_payment_status == 'Have Been Issued'){
            $("#include_payment_div").hide();
        };

        //update number of days requested
        var starting_date =  $('#starting_date').val();
        var ending_date   =  $('#ending_date').val();
        var days_requested = calculate_no_of_leave_days(starting_date,ending_date);
        $("#days_requested").text(days_requested);
        $('#leave_type_title,#leave_type_title1').text('Annual Leave');

    });

    $('#supporting_document').on('change', function(){
        var file_name = $(this).val().split('\\').pop();

        if(file_name.length >0){
            $('#file-name').text(file_name);
            $('#file-name-div').show();
        }
    })

    //when Request Leave button is clicked
    $("form").submit(function(event){
        event.preventDefault();

        var starting_date = $("#starting_date").val().trim();
        var ending_date = $("#ending_date").val().trim();
        var leave_type = $("#leave_type").val();
        var leave_balance = $("#"+leave_type+"_days_left").text();
        var no_days = calculate_no_of_leave_days(starting_date,ending_date);


        //validate these values, we will do it letter
        if ( starting_date === '' || ending_date === '' ) {

            var error_message = "Please select starting date and ending date first !";
            sweet_alert_error(error_message);

        }
        else if(no_days <= 0){

            var error_message = "Ending date must be ahead of starting date. Please select starting date correctly !";
            sweet_alert_error(error_message);

        }
        else if(leave_type == ''){

            var error_message = "Please select Type Of Leave you want to request!";
            sweet_alert_error(error_message);

        }
        else if( no_days > leave_balance ){

            var error_message = "The number of days you are requesting for a leave is more than your leave balance, please reduce number of days for your request to be accepted !";
            sweet_alert_error(error_message);

        } //check if leave payment have been issued before submitting the form
        else{
            //submit the form
            $(this).unbind('submit').submit();

        }


    });

    $("#ending_date").on('change', function (e) {

        var starting_date =  $('#starting_date').val();
        var ending_date = $(this).val();


        //validate these values, we will do it letter
        if ( starting_date === '' ) {
            var error_message = "Please select starting date !";
            sweet_alert_error(error_message);
            $(this).val('');

        }else{

            var days_requested = calculate_no_of_leave_days(starting_date,ending_date);
            if(days_requested <= 0){

                $("#days_requested").text('0');

                var error_message = "Ending date must be ahead of starting date. Please select ending date correctly !";
                sweet_alert_error(error_message);
                $(this).val('');

            }
            else{
                //display the number of days
                $("#days_requested").text(days_requested);
            }

        }

    });

    $("#leave_type").on('change',function(){
        var leave_type = $(this).val();

        if(leave_type == ''){
            $(".summary-div").hide();
            $("#include_payment_div").hide();
            $("#description_div").hide();
            $("#document_div").hide();
            $("#file-name-div").hide();
            $("#annual_leave_summary_div").show("fast");
        }

        if(leave_type == 'sick_leave'){
            $(".summary-div").hide();
            $("#include_payment_div").hide();
            $("#description_div").show('fast');
            $("#document_div").show('fast');
            $("#file-name-div").hide();
            $("#sick_leave_summary_div").show("fast");
            $('#leave_type_title,#leave_type_title1').text('Sick Leave');
        }

        if(leave_type == 'annual_leave'){
            $(".summary-div").hide();
            $("#description_div").hide();
            $("#document_div").hide();
            $("#file-name-div").hide();
            $("#annual_leave_summary_div").show("fast");
            $('#leave_type_title,#leave_type_title1').text('Annual Leave');


            if($("#annual_leave_payment_status").text() == 'Have Been Issued'){
                $("#include_payment_div").hide();
            }else{
                $("#include_payment_div").hide();
            };
        }

        if(leave_type == 'maternity_leave'){

            $(".summary-div").hide();
            $("#include_payment_div").hide();
            $("#description_div").show('fast');
            $("#document_div").hide();
            $("#file-name-div").hide();
            $("#maternity_leave_summary_div").show("fast");
            $('#leave_type_title,#leave_type_title1').text('Maternity Leave');
        }

        if(leave_type == 'paternity_leave'){
            $(".summary-div").hide();
            $("#include_payment_div").hide();
            $("#description_div").hide();
            $("#document_div").hide();
            $("#file-name-div").hide();
            $("#paternity_leave_summary_div").show("fast");
            $('#leave_type_title,#leave_type_title1').text('Paternity Leave');
        }

        if(leave_type == 'compassionate_leave'){
            $(".summary-div").hide();
            $("#include_payment_div").hide();
            $("#description_div").show('fast');
            $("#document_div").hide();
            $("#file-name-div").hide();
            $("#compassionate_leave_summary_div").show("fast");
            $('#leave_type_title,#leave_type_title1').text('Compassionate Leave');
        }

        if(leave_type == 'other'){
            $(".summary-div").hide();
            $("#include_payment_div").hide();
            $("#description_div").show('fast');
            $("#document_div").show('fast');
            $("#file-name-div").hide();
            $("#other_summary_div").show("fast");
            $('#leave_type_title,#leave_type_title1').text('Other Leave');
        }

    });



    /************* my functions goes here *********/

    function calculate_no_of_leave_days(starting_date,ending_date){

        var no_of_leave_days = 0;

        if(starting_date == '' && ending_date == '')
        {
            no_of_leave_days = 0;
        }
        else{

            //format dates to format of MM/DD/YYYY
            splited_date = starting_date.split('/');
            starting_date = splited_date[1] + '/' + splited_date[0] + '/'  +splited_date[2];

            splited_date = ending_date.split('/');
            ending_date = splited_date[1] + '/' + splited_date[0] + '/'  +splited_date[2];

            //convert to date object
            starting_date = new Date(starting_date);
            ending_date = new Date(ending_date);

            var time_interval = ending_date - starting_date;
            var secs_time_interval = time_interval/1000; //time interval in seconds
            var days_time_interval = Math.floor(secs_time_interval/(60*60*24));

            no_of_leave_days = ++days_time_interval;

            //exclude weekends and holidays according to settings
            var weekends_and_holidays = count_weekend_and_holidays(starting_date,ending_date);
            var number_of_weekend_days_in_leave = weekends_and_holidays[0];
            var number_of_holidays_in_leave   = weekends_and_holidays[1];
            var number_of_weekends_and_holidays_in_leave = weekends_and_holidays[2];

            var include_weekends = $('#include_weekends').text().trim();
            var include_holidays = $('#include_holidays').text().trim();

            if( include_weekends == 'no' && include_holidays == 'no' ){
                no_of_leave_days -= number_of_weekends_and_holidays_in_leave;
                $("#days_excluded").text(number_of_weekends_and_holidays_in_leave);

                if(number_of_weekend_days_in_leave == 0 && number_of_holidays_in_leave>0){
                    $("#excluded_text").text('Number Of Holidays Excluded');
                }
                else if(number_of_weekend_days_in_leave > 0 && number_of_holidays_in_leave == 0){
                    $("#excluded_text").text('Number Of  Weekend Days Excluded');
                }
                else if(number_of_weekend_days_in_leave > 0 && number_of_holidays_in_leave>0){
                    $("#excluded_text").text('Number Of Weekend Days & Holidays Excluded');
                }
                else{
                    $("#excluded_text").text('Number Of Days Excluded');
                }
            }
            else if( include_weekends == 'no' && include_holidays == 'yes' ){
                no_of_leave_days -= number_of_weekend_days_in_leave;
                $("#days_excluded").text(number_of_weekend_days_in_leave);
                $("#excluded_text").text('Number Of Weekend Days Excluded');
            }
            else if( include_weekends == 'yes' && include_holidays == 'no' ){
                no_of_leave_days -= number_of_holidays_in_leave;
                $("#days_excluded").text(number_of_holidays_in_leave);
                $("#excluded_text").text('Number Of Holidays Excluded');
            }




        }

        return no_of_leave_days;

    }

    function count_weekend_and_holidays(starting_date,ending_date){

        var no_of_weekend_days_in_time_interval = 0;
        var no_of_holidays_in_time_interval = 0;
        var no_of_weekend_and_holidays_in_time_interval = 0;
        var currentDate = new Date(starting_date.getTime());

        while (currentDate <= ending_date) {


            var needed_date = new Date(currentDate);
            var day_position_in_week = needed_date.getUTCDay();//saturday = 5, sunday = 6, monday = 0

            if( day_position_in_week == 5 || day_position_in_week == 6  ){
                no_of_weekend_days_in_time_interval++;
                no_of_weekend_and_holidays_in_time_interval++;
            }
            else{
                //check if there is any holiday in week days
                var full_date = needed_date.getDate() + '-' + (needed_date.getMonth()+1) + '-' + needed_date.getFullYear();

                $(".holiday-date").each( function(index){
                    var holiday_date = $(this).attr('id');

                    if(holiday_date == full_date){
                        no_of_weekend_and_holidays_in_time_interval++;
                    }
                });

                //alert(full_date);
            }

            //just in case, count number of holidays
            var full_date = needed_date.getDate() + '-' + (needed_date.getMonth()+1) + '-' + needed_date.getFullYear();

            $(".holiday-date").each( function(index){
                var holiday_date = $(this).attr('id');

                if(holiday_date == full_date){
                    no_of_holidays_in_time_interval++;
                    //alert( 'we have got a holiday => ' + holiday_date);
                }
            });





            currentDate.setDate(currentDate.getDate() + 1) ;
        }

        //alert(no_of_weekend_days_in_time_interval);

        var holiday_and_weekends = new Array(3);
        holiday_and_weekends[0] = no_of_weekend_days_in_time_interval;
        holiday_and_weekends[1] = no_of_holidays_in_time_interval;
        holiday_and_weekends[2] = no_of_weekend_and_holidays_in_time_interval;

        return holiday_and_weekends;
    }


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