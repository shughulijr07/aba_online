@csrf
<div class="form-row">
    <div class="col-md-7">
        <div class="position-relative form-group">
            <label for="name" class="">GL Account Name</label>
            <input name="name" id="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') ?? $gl_account->name}}">

            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
             </span>
            @enderror
        </div>
    </div>
    <div class="col-md-5">
        <div class="position-relative form-group">
            <label for="number" class="">GL Account Number</label>
            <input name="number" id="number" type="text" class="form-control @error('number') is-invalid @enderror" value="{{ old('number') ?? $gl_account->number}}">

            @error('number')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
             </span>
            @enderror
        </div>
    </div>
</div>
