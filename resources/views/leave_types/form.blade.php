@csrf
<div class="form-row">
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="name" class="">Name Of Leave</label>
            <input name="name" id="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') ?? $leave_type->name}}">

            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
             </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="key" class="">Key</label>
            <input name="key" id="key" type="text" class="form-control @error('key') is-invalid @enderror" value="{{ old('key') ?? $leave_type->key}}">

            @error('key')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
             </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="position-relative form-group">
            <label for="status" class="">
                <span>Status</span>
                <span class="text-danger">*</span>
            </label>
            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                <option value="Active" @if(( old('status') == 'Active' ) || ( $leave_type->status == 'Active' )) selected @endif>Active</option>
                <option value="Inactive" @if(( old('status') == 'Inactive' ) || ( $leave_type->status == 'Inactive' )) selected @endif>Inactive</option>
            </select>

            @error('status')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="position-relative form-group">
            <label for="days" class="">Number Of Days</label>
            <input name="days" id="days" type="text" class="form-control @error('days') is-invalid @enderror" value="{{ old('days') ?? $leave_type->days}}">

            @error('days')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
             </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="position-relative form-group">
            <label for="period" class="">Period (In Years)</label>
            <input name="period" id="period" type="text" class="form-control @error('period') is-invalid @enderror" value="{{ old('period') ?? $leave_type->period}}">

            @error('period')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
             </span>
            @enderror
        </div>
    </div>
</div>