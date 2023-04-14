
    @csrf
    <fieldset>
        <legend class="text-danger"></legend>
        <div class="form-row">
            <div class="col-md-12">
                <div class="position-relative form-group">
                    <label for="reason" class="">
                        <span>Reason For Delayed submission</span>
                        <span class="text-danger description_required">*</span>
                    </label>
                    <textarea name="reason" id="reason" type="text" class="form-control @error('reason') is-invalid @enderror" autocomplete="off">{{ old('reason') ?? $time_sheet_late_submission->reason}}</textarea>

                    @error('reason')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                     </span>
                    @enderror
                </div>
            </div>
        </div>
    </fieldset>

