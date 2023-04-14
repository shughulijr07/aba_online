<div class="row">

    <div class="col-sm-12" id="validation-div">

        <!-- Error Message -->
        @if (session()->has('message'))
            <div class="main-card mb-3 card alert alert-primary" id="notifications-div">
                <div class="card-body ">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h5>{{ session()->get('message') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- -->

        <!-- Timesheet  -->
        <div class="main-card mb-1 card">
            <div class="card-body">
                <h5 class="card-title text-danger">New Time Sheet Form</h5>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <form action="/new_create_timesheet_for_another_staff" method="POST" enctype="multipart/form-data"
                            id="timesheet_form">
                            @csrf
                            <fieldset>
                                <legend class="text-danger"></legend>
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="staff_id" class="">
                                                <span>Staff</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="staff_id" id="staff_id"
                                                class="form-control @error('staff_id') is-invalid @enderror">
                                                <option value="">Select Staff</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}"
                                                        @if ($employee->id == old('staff_id')) selected @endif>
                                                        {{ ucwords($employee->first_name . ' ' . $employee->last_name) }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @error('staff_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    @if ($my_staff_id->staff_id == 1)

                                        <div class="col-md-3">
                                            <div class="position-relative form-group">
                                                <label for="responsible_spv" class="">
                                                    <span>Supervisor</span>
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select name="responsible_spv" id="responsible_spv"
                                                    class="form-control @error('responsible_spv') is-invalid @enderror">
                                                    <option value="">Select Supervisor</option>
                                                    @foreach ($timeSheetSupervisors as $supervisor)
                                                        <option value="{{ $supervisor->id }}"
                                                            @if ($supervisor->id == old('responsible_spv')) selected @endif>
                                                            {{ ucwords($supervisor->first_name . ' ' . $supervisor->last_name) }}
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
                                    @endif

                                    <div class="col-md-1">
                                        <div class="position-relative form-group">
                                            <label for="year" class="">
                                                <span>Year</span>
                                            </label>
                                            <select name="year" id="year" class="form-control">
                                                <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="position-relative form-group">
                                            <label for="month" class="">
                                                <span>Month</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="month" id="month"
                                                class="form-control @error('month') is-invalid @enderror">
                                                @foreach ($months as $value => $month)
                                                    <option value="{{ $value }}"
                                                        @if ($value == $current_month) selected @endif>
                                                        {{ $month }}</option>
                                                @endforeach
                                            </select>

                                            @error('month')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <div>
                                <button class="mt-2 btn btn-primary" type="submit" id="create_time_sheet_btn">Create
                                    Time Sheet</button>
                            </div>



                        </form>
                    </div>
                </div>


            </div>
        </div>

    </div>

</div>


<script type="text/javascript">
    $(document).ready(function() {
        $("#staff_id").select2({
            width: 'resolve',
        });
    });

    $(function() {

        $('#notifications-div').delay(7000).fadeOut('slow');
         
    });
</script>
