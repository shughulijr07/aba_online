@csrf
<div class="form-row">
    <div class="col-md-12">
        <div class="position-relative form-group">
            <label for="role_name" class="">Role Name</label>
            <input name="role_name" id="role_name" type="text" class="form-control @error('role_name') is-invalid @enderror" value="{{ old('role_name') ?? $system_role->role_name}} ">

            @error('role_name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
             </span>
            @enderror
        </div>
    </div>
</div>