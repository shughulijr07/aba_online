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
                <h5 class="card-title text-danger">retirement Request Form</h5>

                @if( !isset($travel_request->status))
                    <form action="/retirement_request" method="POST" enctype="multipart/form-data" id="valid-form">
                        @elseif( isset($travel_request->status) && $travel_request->status == 0 && $travel_request->staff_id == auth()->user()->staff->id)
                            <form action="/retirement_request_update/{{$travel_request->id}}" method="POST" enctype="multipart/form-data" id="valid-form">
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
                                                        <label for="departure_date" class="">
                                                            <span>Request Date</span>
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input name="requested_date" id="departure_date" type="text" class="form-control @error('departure_date') is-invalid @enderror" value="{{ old('requested_date')?? $travel_request->requested_date}}" autocomplete="off" {{$disabled}}>

                                                        @error('departure_date')
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
                                                <div class="col-md-12" id="description_div">
                                                    <div class="position-relative form-group">
                                                        <label for="purpose_of_trip" class="">
                                                            Purpose Of retirement
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <textarea name="purpose_of_payment" id="purpose_of_trip" class="form-control @error('purpose_of_trip') is-invalid @enderror" autocomplete="off" {{$disabled}}>{{ old('purpose_of_payment') ?? $travel_request->purpose_of_payment}}</textarea>

                                                        @error('purpose_of_trip')
                                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        @if($travel_requests_summary)
                                        <input type="hidden" name="payment_id" value="{{$payment_id}}">
                                        @endif
                                        <!-- End Of Header -->
                                        <input type="file" name="file">
                                        @if( $user_role == 5 || $user_role == 4 || $user_role == 9 || $user_role == 2 )
                                        <a href="{{url('/retirementFilePreview',$travel_request->id)}}" class="btn btn-secondary btn-s" role="button"><i class="fa fa-file" aria-hidden="true"></i>View attached file</a>
                                        @endif

                                        <!-- Lines -->
                                       
                                        <button class="mt-2 btn btn-primary {{$visibility}}" type="submit" id="submit_btn">Submit Retirement Request</button>

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


    


  


</script>



<!-- Common Script-->
<script type="text/javascript">







    //when select-multiple-rows checkbox is clicked
    

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

