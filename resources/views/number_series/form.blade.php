@csrf
<fieldset>
    <legend class="text-danger"></legend>
    <div class="form-row">
        <div class="col-md-6">
            <div class="position-relative form-group">
                <label for="numbered_item_id" class="">
                    <span>Item</span>
                    <span class="text-danger">*</span>
                </label>
                <select name="numbered_item_id" id="numbered_item_id" class="form-control @error('numbered_item_id') is-invalid @enderror">
                    <option value="">Select Name</option>
                    @foreach($numbered_items as $item)
                        <option value="{{$item->id}}" @if(($item->id == old('numbered_item_id')) || ($item->id == $number_series->numbered_item_id )) selected @endif>
                            {{$item->name}}
                        </option>
                    @endforeach
                </select>

                @error('numbered_item_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="position-relative form-group">
                <label for="abbreviation" class="">
                    <span>Abbreviation</span>
                    <span class="text-danger">*</span>
                </label>
                <input name="abbreviation" id="abbreviation" type="text" class="form-control @error('abbreviation') is-invalid @enderror" value="{{old('abbreviation') ?? $number_series->abbreviation}}">

                @error('abbreviation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                 </span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="position-relative form-group">
                <label for="include_year" class="">
                    <span>Include Year</span>
                    <span class="text-danger">*</span>
                </label>
                <select name="include_year" id="include_year" class="form-control @error('include_year') is-invalid @enderror">
                    <option value="Yes" @if( old('include_year')  == 'Yes' || $include_year == 'Yes') selected @endif>Yes</option>
                    <option value="No" @if( old('include_year') == 'No' || $include_year == 'No') selected @endif>No</option>
                </select>

                @error('include_year')
                <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="position-relative form-group">
                <label for="include_month" class="">
                    <span>Include Month</span>
                    <span class="text-danger">*</span>
                </label>
                <select name="include_month" id="include_month" class="form-control @error('include_month') is-invalid @enderror">
                    <option value="Yes" @if( old('include_month')  == 'Yes' || $include_month == 'Yes') selected @endif>Yes</option>
                    <option value="No" @if( old('include_month') == 'No' || $include_month == 'No') selected @endif>No</option>
                </select>

                @error('include_month')
                <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="position-relative form-group">
                <label for="starting_no" class="">
                    <span>Starting Number</span>
                    <span class="text-danger">*</span>
                </label>
                <input name="starting_no" id="starting_no" type="text" class="form-control @error('starting_no') is-invalid @enderror" value="{{old('starting_no') ?? $starting_no}}">

                @error('starting_no')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                 </span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="position-relative form-group">
                <label for="increment_by" class="">
                    <span>Increment By</span>
                    <span class="text-danger">*</span>
                </label>
                <input name="increment_by" id="increment_by" type="text" class="form-control @error('increment_by') is-invalid @enderror" value="{{old('increment_by') ?? $increment_by}}">

                @error('increment_by')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                 </span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="position-relative form-group">
                <label for="reset_on" class="">
                    <span>Reset Number Series On</span>
                    <span class="text-danger">*</span>
                </label>
                <select name="reset_on" id="reset_on" class="form-control @error('reset_on') is-invalid @enderror">
                    <option value="Year" @if( old('reset_on')  == 'Year' || $reset_on == 'Year') selected @endif>Beginning of the Year</option>
                    <option value="Month" @if( old('reset_on') == 'Month' || $reset_on == 'Month') selected @endif>Beginning of the Month</option>
                    <option value="Do Not Reset" @if( old('reset_on') == 'Do Not Reset' || $reset_on == 'Do Not Reset') selected @endif>Do Not Reset</option>
                </select>

                @error('reset_on')
                <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="position-relative form-group">
                <label for="number_of_digits" class="">
                    <span>Number Of Digits</span>
                    <span class="text-danger">*</span>
                </label>
                <input name="number_of_digits" id="number_of_digits" type="text" class="form-control @error('number_of_digits') is-invalid @enderror" value="{{old('number_of_digits') ?? $number_of_digits}}">

                @error('number_of_digits')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                 </span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="position-relative form-group">
                <label for="separator" class="">
                    <span>Separation Character</span>
                    <span class="text-danger">*</span>
                </label>
                <select name="separator" id="separator" class="form-control @error('separator') is-invalid @enderror">
                    <option value="blank" @if( old('separator') == 'blank' || $separator == 'blank') selected @endif>Blank</option>
                    <option value="-" @if( old('separator') == '-' || $separator == '-') selected @endif>-</option>
                    <option value="/" @if( old('separator')  == '/' || $separator == '/') selected @endif>/</option>
                    <option value="~" @if( old('separator') == '~' || $separator == '~') selected @endif>~</option>
                    <option value="~" @if( old('separator') == '~' || $separator == '~') selected @endif>:</option>
                    <option value="~" @if( old('separator') == '~' || $separator == '~') selected @endif>\</option>
                    <option value="~" @if( old('separator') == '~' || $separator == '~') selected @endif>#</option>
                </select>

                @error('separator')
                <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                @enderror
            </div>
        </div>
    </div>
</fieldset>
