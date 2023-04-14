
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
                    <h5 class="card-title text-danger"></h5>

                    @if( !isset($travel_request->status))
                        <form action="/travel_request" method="POST" enctype="multipart/form-data" id="valid-form">
                    @elseif( isset($travel_request->status) && $travel_request->status == 0 && $travel_request->staff_id == auth()->user()->staff->id)
                        <form action="/travel_request_update/{{$travel_request->id}}" method="POST" enctype="multipart/form-data" id="valid-form">
                            @method('PATCH')
                    @else
                        <form id="valid-form">
                    @endif
                        @csrf

                        <!-- Header-->
                        <fieldset>
                            <legend class="text-danger"></legend>

                            <div class="form-row">

                                <div class="col">
                                    <div class="position-relative form-group">
                                        <label for="employee_name" class="">
                                            <span>Employee Name</span>
                                        </label>
                                        <input name="employee_name" id="employee_name" type="text" class="form-control" value="{{ $employee_name }}" autocomplete="off" {{$disabled}}>

                                    </div>
                                </div>

                                <div class="col">
                                    <div class="position-relative form-group">
                                        <label for="project_code" class="">
                                            <span>Project Name</span>
                                        </label>
                                        <select name="project_code" id="project_code" class="form-control" {{$disabled}}>
                                            <option value="">Select Project</option>
                                            @foreach($projects as $code=>$name)
                                                <option value="{{$code}}" @if( $code == $travel_request->project_code) selected @endif>
                                                    {{$name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="position-relative form-group">
                                        <label for="departure_date" class="">
                                            <span>Departure Date</span>
                                        </label>
                                        <input name="departure_date" id="departure_date" type="text" class="form-control" value="{{ $travel_request->departure_date}}" autocomplete="off" {{$disabled}}>

                                    </div>
                                </div>
                                <div class="col">
                                    <div class="position-relative form-group">
                                        <label for="returning_date" class="">
                                            <span>Returning Date</span>
                                        </label>
                                        <input name="returning_date" id="returning_date" type="text" class="form-control" value="{{ $travel_request->returning_date}}" autocomplete="off" {{$disabled}}>

                                    </div>
                                </div>
                                <div class="col-sm-12" id="description_div">
                                    <div class="position-relative form-group">
                                        <label for="purpose_of_trip" class="">
                                            Purpose Of Trip
                                        </label>
                                        <textarea name="purpose_of_trip" id="purpose_of_trip" class="form-control" autocomplete="off" {{$disabled}}>{{ $travel_request->purpose_of_trip}}</textarea>

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
                                            <th style="width: 5%;" >#</th>
                                            <th style="width: 10%;" >Account Codes.</th>
                                            <th style="width: 20%;" >Activity</th>
                                            <th style="width: 15%;" class="text-right">Rate (TZS)</th>
                                            <th style="width: 10%;" >Measure</th>
                                            <th style="width: 10%;" class="text-right">Units</th>
                                            <th style="width: 10%;" class="text-right">Percentage (%)</th>
                                            <th style="width: 15%;" class="text-right">Total Cost (TZS)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($travel_request->lines) == 0)
                                            <tr class="data-row last-row" id="1">
                                                <td class="data-column no">
                                                    <input name="line_1[]" class="column-input line-no text-left" autocomplete="off" value="1" readonly>
                                                </td>
                                                <td class="data-column time">
                                                    <input name="line_1[]" class="column-input account-code" autocomplete="off">
                                                </td>
                                                <td class="data-column time">
                                                    <input name="line_1[]" class="column-input activity" autocomplete="off">
                                                </td>
                                                <td class="data-column time">
                                                    <input name="line_1[]" class="column-input rate text-right" autocomplete="off" readonly>
                                                </td>
                                                <td class="data-column">
                                                    <input name="line_1[]" class="column-input measure" autocomplete="off" readonly>
                                                </td>
                                                <td class="data-column text-center">
                                                    <input name="line_1[]" class="column-input unit text-right" readonly>
                                                </td>
                                                <td class="data-column text-center">
                                                    <input name="line_1[]" class="column-input percentage text-right" readonly>
                                                </td>
                                                <td class="data-column text-center">
                                                    <input name="line_1[]" class="column-input total-cost text-right" readonly>
                                                </td>
                                            </tr>
                                        @else
                                            <?php $n = 1;?>
                                            @foreach($lines as $line)
                                                <tr class="data-row @if($n == count($lines)) last-row @endif" id="{{$n}}">
                                                    <td class="data-column no">
                                                        <input name="line_{{$n}}[]" class="column-input line-no" autocomplete="off" value="{{$n}}" readonly>
                                                    </td>
                                                    <td class="data-column time">
                                                        <input name="line_{{$n}}[]" class="column-input account-code" autocomplete="off" value="{{$line[1]}}">
                                                    </td>
                                                    <td class="data-column time">
                                                        <input name="line_{{$n}}[]" class="column-input activity" autocomplete="off" value="{{$line[2]}}">
                                                    </td>
                                                    <td class="data-column time">
                                                        <input name="line_{{$n}}[]" class="column-input rate text-right" autocomplete="off" value="{{$line[3]}}" readonly>
                                                    </td>
                                                    <td class="data-column">
                                                        <input name="line_{{$n}}[]" class="column-input measure" autocomplete="off" value="{{$line[4]}}" readonly>
                                                    </td>
                                                    <td class="data-column text-center">
                                                        <input name="line_{{$n}}[]" class="column-input unit text-right"  value="{{$line[5]}}" readonly>
                                                    </td>
                                                    <td class="data-column text-center">
                                                        <input name="line_{{$n}}[]" class="column-input percentage text-right"  value="{{$line[6]}}" readonly>
                                                    </td>
                                                    <td class="data-column text-center">
                                                        <input name="line_{{$n}}[]" class="column-input total-cost text-right"  value="{{$line[7]}}" readonly>
                                                    </td>
                                                </tr>
                                                <?php $n++;?>
                                            @endforeach
                                        @endif
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th colspan="7" class="text-right">Total Request</th>
                                            <th style="width: 15%;" class="text-right" id="grand_total" ></th>
                                        </tr>
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
                                <div class="col-md-12 mt-3">
                                    <div class="position-relative form-group">
                                        <input type="hidden" name="terms[]" value="no">
                                        <input type="checkbox" name="terms[]" id="terms" value="yes" @if( (is_array(old('terms')) &&  in_array('yes',old('terms'))) || in_array('yes',$terms )) checked @endif >
                                        <span class="text-primary">
                                            I <span class="text-danger">{{$employee_name}}</span>  declare by accepting funds from T-MARC that I will retire these funds according to
                                            T-MARC  policies and procedures with genuine receipts.
                                        </span>

                                        @error('terms')
                                        <span class="invalid-feedback" role="alert" style="display: block">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <!-- End Of Lines -->
                        <button class="mt-2 btn btn-primary {{$visibility}}" type="submit" id="submit_btn">Submit Travel Request</button>

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


            <div class="main-card mb-3 card summary-div" id="travel_request_summary_div">
                <div class="card-body">
                    <h5 class="card-title">
                        <span class="text-primary" id="current_year">{{date('Y')}}</span> Travel Requests Summary
                    </h5>
                    <table style="width: 100%;"  class="table table-hover table-striped table-bordered">
                        <tbody>
                        <tr class='data-row'>
                            <td>Total Traveled Days</td>
                            <td>{{ $travel_requests_summary['approved']['total_days'] }}</td>
                        </tr>
                        <tr class='data-row'>
                            <td>Total Requests</td>
                            <td>{{
                                  count( $travel_requests_summary['returned-for-correction']['travel_requests'])
                                + count( $travel_requests_summary['waiting-spv-approval']['travel_requests'])
                                + count( $travel_requests_summary['waiting-acc-approval']['travel_requests'])
                                + count( $travel_requests_summary['waiting-fd-approval']['travel_requests'])
                                + count( $travel_requests_summary['waiting-md-approval']['travel_requests'])
                                + count( $travel_requests_summary['approved']['travel_requests'])
                                + count( $travel_requests_summary['rejected']['travel_requests'])
                             }}
                            </td>
                        </tr>
                        <tr class='data-row' >
                            <td>Waiting SPV Approval</td>
                            <td>{{ count( $travel_requests_summary['waiting-spv-approval']['travel_requests'] ) }}</td>
                        </tr>
                        <tr class='data-row' >
                            <td>Waiting ACC Approval</td>
                            <td>{{ count( $travel_requests_summary['waiting-acc-approval']['travel_requests'] ) }}</td>
                        </tr>
                        <tr class='data-row' >
                            <td>Waiting FD Approval</td>
                            <td>{{ count( $travel_requests_summary['waiting-fd-approval']['travel_requests'] ) }}</td>
                        </tr>
                        <tr class='data-row' >
                            <td>Waiting MD Approval</td>
                            <td>{{ count( $travel_requests_summary['waiting-md-approval']['travel_requests'] ) }}</td>
                        </tr>
                        <tr class='data-row'>
                            <td>Approved</td>
                            <td>{{ count( $travel_requests_summary['approved']['travel_requests']) }}</td>
                        </tr>
                        <tr class='data-row'>
                            <td>Rejected</td>
                            <td>{{ count( $travel_requests_summary['rejected']['travel_requests'] ) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <!-- end of  travel requests summary-->

    </div>


<script src="{{ asset('js/bootstrap-datetimepicker.js') }}"></script>
<script type="text/javascript">

    $(function () {


        $('#notifications-div').delay(7000).fadeOut('slow');

        calculate_grand_total();

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

</script>