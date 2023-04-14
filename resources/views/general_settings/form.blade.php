@csrf
{{ csrf_field() }}

<fieldset>
    <div class="row">
        <div class="col-md-12"> 

            <fieldset>  
                <legend>Leave</legend>
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="supervisors_mode" class="">
                                <span>Supervisors Mode</span>
                                <span class="text-danger">*</span>
                            </label>
                            <select name="supervisors_mode" id="supervisors_mode" class="form-control @error('country') is-invalid @enderror">
                                @foreach($supervisors_modes as $key=>$mode)
                                    <option value="{{$key}}" @if(($key == old('supervisors_mode')) || ($key == $settings->supervisors_mode)) selected @endif>
                                        {{$mode}}
                                    </option>
                                @endforeach
                            </select>

                            @error('supervisors_mode')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="carry_over_mode" class="">
                                <span>Carry Over Mode</span>
                                <span class="text-danger">*</span>
                            </label>
                            <select name="carry_over_mode" id="carry_over_mode" class="form-control @error('country') is-invalid @enderror">
                                @foreach($carry_over_modes as $key=>$mode)
                                    <option value="{{$key}}" @if(($key == old('carry_over_mode')) || ($key == $settings->carry_over_mode)) selected @endif>
                                        {{$mode}}
                                    </option>
                                @endforeach
                            </select>

                            @error('carry_over_mode')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="leave_timesheet_link" class="">
                                <span>Link Staff Leave With Time Sheet</span>
                                <span class="text-danger">*</span>
                            </label>
                            <select name="leave_timesheet_link" id="leave_timesheet_link" class="form-control @error('country') is-invalid @enderror">
                                @foreach($leave_timesheet_link_modes as $key=>$mode)
                                    <option value="{{$key}}" @if(($key == old('leave_timesheet_link')) || ($key == $settings->leave_timesheet_link)) selected @endif>
                                        {{$mode}}
                                    </option>
                                @endforeach
                            </select>

                            @error('leave_timesheet_link')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="include_holidays_in_leave" class="">
                                <span>Include Holidays In Leave</span>
                                <span class="text-danger">*</span>
                            </label>
                            <select name="include_holidays_in_leave" id="include_holidays_in_leave" class="form-control @error('country') is-invalid @enderror">
                                @foreach($include_holidays_in_leave as $key=>$option)
                                    <option value="{{$key}}" @if(($key == old('include_holidays_in_leave')) || ($key == $settings->include_holidays_in_leave)) selected @endif>
                                        {{$option}}
                                    </option>
                                @endforeach
                            </select>

                            @error('include_holidays_in_leave')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="include_weekends_in_leave" class="">
                                <span>Include Weekends In Leave</span>
                                <span class="text-danger">*</span>
                            </label>
                            <select name="include_weekends_in_leave" id="include_weekends_in_leave" class="form-control @error('country') is-invalid @enderror">
                                @foreach($include_weekends_in_leave as $key=>$option)
                                    <option value="{{$key}}" @if(($key == old('include_weekends_in_leave')) || ($key == $settings->include_weekends_in_leave)) selected @endif>
                                        {{$option}}
                                    </option>
                                @endforeach
                            </select>

                            @error('include_weekends_in_leave')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Time Sheets</legend>
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="time_sheet_data_format" class="">
                                <span>Time Sheet Data Format</span>
                                <span class="text-danger">*</span>
                            </label>
                            <select name="time_sheet_data_format" id="time_sheet_data_format" class="form-control @error('country') is-invalid @enderror">
                                @foreach($time_sheet_data_formats as $key=>$mode)
                                    <option value="{{$key}}" @if(($key == old('time_sheet_data_format')) || ($key == $settings->time_sheet_data_format)) selected @endif>
                                        {{$mode}}
                                    </option>
                                @endforeach
                            </select>

                            @error('time_sheet_data_format')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="time_sheet_submission_deadline" class="">
                                <span>Time Sheet Submission Deadline</span>
                                <span class="text-danger">*</span>
                            </label>
                            <input name="time_sheet_submission_deadline" id="time_sheet_submission_deadline" class="form-control @error('country') is-invalid @enderror"
                                value="{{old('time_sheet_submission_deadline') ?? $settings->time_sheet_submission_deadline}}"
                            >

                            @error('time_sheet_submission_deadline')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Performance Objectives</legend>
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="objectives_submission_deadline" class="">
                                <span>Performance Objectives Submission Deadline</span>
                                <span class="text-danger">*</span>
                            </label>
                            <input name="objectives_submission_deadline" id="objectives_submission_deadline" class="form-control @error('country') is-invalid @enderror"
                                   value="{{old('objectives_submission_deadline') ?? $settings->objectives_submission_deadline}}"
                            >

                            @error('objectives_submission_deadline')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="objectives_marking_opening" class="">
                                <span>Performance Objectives Submission Deadline</span>
                                <span class="text-danger">*</span>
                            </label>
                            <input name="objectives_marking_opening" id="objectives_marking_opening" class="form-control @error('country') is-invalid @enderror"
                                   value="{{old('objectives_marking_opening') ?? $settings->objectives_marking_opening}}"
                            >

                            @error('objectives_marking_opening')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="objectives_marking_closing" class="">
                                <span>Performance Objectives Submission Deadline</span>
                                <span class="text-danger">*</span>
                            </label>
                            <input name="objectives_marking_closing" id="objectives_marking_closing" class="form-control @error('country') is-invalid @enderror"
                                   value="{{old('objectives_marking_closing') ?? $settings->objectives_marking_closing}}"
                            >

                            @error('objectives_marking_closing')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>User Activities</legend>
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="user_activities_recording_mode" class="">
                                <span>User Activities Recording Mode</span>
                                <span class="text-danger">*</span>
                            </label>
                            <select name="user_activities_recording_mode" id="user_activities_recording_mode" class="form-control @error('country') is-invalid @enderror">
                                @foreach($user_activities_recording_modes as $key=>$mode)
                                    <option value="{{$key}}" @if(($key == old('user_activities_recording_mode')) || ($key == $settings->user_activities_recording_mode)) selected @endif>
                                        {{$mode}}
                                    </option>
                                @endforeach
                            </select>

                            @error('user_activities_recording_mode')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </fieldset>

        </div>
    </div>
</fieldset>
