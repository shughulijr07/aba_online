    <fieldset>
        <legend></legend>
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-md-12">
                <div class="form-row">

                    <div class="col-md-8">
                        <div class="position-relative form-group">
                            <label for="staff_id" class="">
                                <span>Employee Number | Name</span>
                                <span class="text-danger">*</span>
                            </label>
                            <select name="staff_id" id="staff_id" class="form-control @error('staff_id') is-invalid @enderror">
                                <option value="">Select Employee</option>
                                @foreach($all_staff as $staff)
                                    <option value="{{$staff->id}}" @if(($staff->id == old('staff_id')) || ($staff->id == $staff_id)) selected @endif>
                                        {{$staff->no.'     '.ucwords($staff->first_name.' '.$staff->last_name)}}
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

                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label for="staff_id" class="">
                                <span>Year</span>
                                <span class="text-danger">*</span>
                            </label>
                            <select name="year" id="year" class="form-control">
                                <option value="0">Select Year</option>
                                <?php $varying_year=date("Y");?>
                                @while( $varying_year >= $initial_year )
                                    <option value="{{$varying_year}}" @if($varying_year == $year) selected @endif>
                                        {{$varying_year}}
                                    </option>

                                    <?php $varying_year--;?>
                                @endwhile
                            </select>
                            @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                             </span>
                            @enderror
                        </div>
                    </div>

                    @foreach( $leave_entitlement->lines as $line)
                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label for="{{$line->type_of_leave}}" class=""> {{ $leave_types['arrays'][$line->type_of_leave]['name'] }} </label>
                            <input name="{{$line->type_of_leave}}" id="{{$line->type_of_leave}}" type="text" class="form-control @error($line->type_of_leave) is-invalid @enderror" value="{{ old($line->type_of_leave) ?? $line->number_of_days}}">

                            @error('{{$line->type_of_leave}}')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                             </span>
                            @enderror
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>

    </fieldset>


    <script type="text/javascript">

        $("#staff_id,#year").select2({
            width: 'resolve',
        });

    </script>