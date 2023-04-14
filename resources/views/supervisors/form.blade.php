<div class="row">

    <!-- Supervisor Selection Form -->


    <div class="col-md-4" id="validation-div">

        <!-- message section -->
        @if(session()->has('message'))
            <div  class="mb-3 card alert alert-primary" id="notifications-div">
                <div class="p-3 card-body ">
                    <div class="text-center">
                        <h5 class="" id="message">{{session()->get('message')}}</h5>
                    </div>
                </div>
            </div>
        @endif
        <!-- end of message section -->

        <!-- Leave Planner Form -->
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title text-danger"><span id="leave_type_title1"></span>Supervisor Form</h5>



                <form action="/supervisors" method="POST" enctype="multipart/form-data" id="valid-form">
                    @csrf
                    {{ csrf_field() }}

                    <fieldset>
                        <legend class="text-danger"></legend>
                        <div class="form-row">
                            <div class="col-md-12" id="employee_selection_div">
                                <div class="position-relative form-group">
                                    <label for="staff_id" class="">
                                        <span>New Supervisor</span>
                                    </label>
                                    <select name="staff_id" id="staff_id" class="form-control">
                                        <option value="">Select Supervisor</option>
                                        @foreach($all_staff as $staff)
                                            <option value="{{$staff->id}}" >
                                                {{  ucwords($staff->first_name.' '.$staff->last_name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <button class="mt-2 btn btn-primary" id="request_leave_btn">Add To Supervisors</button>
                </form>

            </div>
        </div>



    </div>
    <!-- end of Supervisor Selection Form-->




    <!-- List of Supervisors -->
    <div class="col-md-8">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">
                    List Of Supervisors
                </h5>

                <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Supervisor Name</th>
                        <th>Employee No.</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php $n=1; ?>
                    @foreach($supervisors as $supervisor)
                        <tr>
                            <td>{{ $n }}</td>
                            <td>{{ ucwords(strtolower($supervisor->staff->first_name.' '.$supervisor->staff->last_name))}}</td>
                            <td>{{ $supervisor->staff->staff_no }}</td>
                            <td>
                                <a href="/staff/{{ $supervisor->staff_id }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true">View</a>
                                <a href="/supervisors/delete/{{ $supervisor->id }}" class="btn btn-danger btn-sm" role="button" aria-pressed="true">Remove</a>
                            </td>
                        </tr>
                        <?php $n++; ?>
                    @endforeach

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Supervisor Name</th>
                        <th>Staff No.</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <!--  List of Supervisors Ends Here -->


</div>


<!--scripts -->
<script type="text/javascript">

    $(document).ready(function(){

        $('#notifications-div').delay(5000).fadeOut('slow');

        $("#staff_id").select2({
            width: 'resolve',
        });

    });


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