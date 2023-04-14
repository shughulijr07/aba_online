@csrf
<div class="form-row">
    <div class="col-md-3">
        <div class="position-relative form-group">
            <label for="permission_name" class="">Permission Name</label>
            <span class="text-danger">*</span>
            <input name="permission_name" id="permission_name" type="text" class="form-control @error('permission_name') is-invalid @enderror" value="{{ old('permission_name') ?? $permission->permission_name}}">

            @error('permission_name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
             </span>
            @enderror
        </div>
    </div>
    <div class="col-md-2">
        <div class="position-relative form-group">
            <label for="category" class="">
                <span>Category</span>
                <span class="text-danger">*</span>
            </label>
            <select name="category" id="category" class="form-control @error('category') is-invalid @enderror">
                <option>
                    Select Category
                </option>
                <option value="class" @if( old('category')  == 'class' || $permission->category == 'class') selected @endif>
                    Class
                </option>
                <option value="menu" @if( old('category') == 'menu' || $permission->category == 'menu') selected @endif>
                    Menu
                </option>
                <option value="menu-item" @if( old('category') == 'menu-item' || $permission->category == 'menu-item') selected @endif>
                    Menu Item
                </option>
                <option value="sub-menu-item" @if( old('category') == 'sub-menu-item' || $permission->category == 'sub-menu-item') selected @endif>
                    Sub-Menu Item
                </option>
                <option value="action" @if( old('category') == 'action' || $permission->category == 'action') selected @endif>
                    Action
                </option>
                <option value="process" @if( old('category') == 'process' || $permission->category == 'process') selected @endif>
                    Process
                </option>
            </select>

            @error('category')
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-7">
        <div class="position-relative form-group">
            <label for="description" class="">Description</label>
            <input name="description" id="description" type="text" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') ?? $permission->description}}">

            @error('description')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
             </span>
            @enderror
        </div>
    </div>
</div>