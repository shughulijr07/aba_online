@csrf

<fieldset>
    <legend>Employee Information</legend>
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-md-3">
            <div class="form-row">
                <div class="col-md-12">
                    <div class="position-relative form-group">
                        <label>Staff Image</label>
                        <div class="staff-image-container">
                            <img src="@if($staff->image != '') /storage/{{$staff->image}} @else /images/staff-image.png @endif" alt="Staff Image" style="width: 100%; height: auto; " id="staff-image">
                        </div>
                        <div class="text-center" style="margin-bottom: 10px;">
                            <div style="padding: 5px; ">
                                <span id="file-name"></span>
                            </div>
                            <label for="image" class="upload-label" type="button">
                                <i class="pe-7s-upload"></i>
                                <span style="padding-left: 7px; padding-right: 5px;">
                                    Upload Staff Image
                                </span>
                            </label>
                            <input name="image" id="image" type="file"  accept="image/*" class="form-control-file" style="display:none;" >
                        </div>

                        @error('image')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror

                    </div>
                    <!--script for uploading images -->
                    <script type="text/javascript">

                        $('#image').on('change', function(){

                            if (this.files && this.files[0]) {
                                var reader = new FileReader();

                                reader.onload = function (e) {
                                    $('#staff-image').attr('src', e.target.result);
                                };

                                reader.readAsDataURL(this.files[0]);
                            }
                        });

                    </script>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-row">
                        <div class="col-md-4"  style="display: none;">
                            <div class="position-relative form-group">
                                <label for="staff_id" class="">Staff Id</label>
                                <span class="text-danger">*</span>
                                <input name="staff_id" id="staff_id" type="text" class="form-control" value="{{old('staff_id') ?? $staff->id}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="first_name" class="">First Name</label>
                                <span class="text-danger">*</span>
                                <input name="first_name" id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') ?? $staff_biographical_data_sheet->first_name}}">

                                @error('first_name')
                                <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="middle_name" class="">Middle Name</label>
                                <span class="text-danger">*</span>
                                <input name="middle_name" id="middle_name" type="text" class="form-control @error('middle_name') is-invalid @enderror" value="{{ old('middle_name') ?? $staff_biographical_data_sheet->middle_name}}">

                                @error('middle_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                 </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="last_name" class="">Last Name</label>
                                <span class="text-danger">*</span>
                                <input name="last_name" id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') ?? $staff_biographical_data_sheet->last_name}}">

                                @error('last_name')
                                <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="gender" class="">
                                    <span>Gender</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                                    <option value="">Select Gender</option>
                                    <option value="Female" @if( old('gender')  == 'Female' || $gender == 'Female') selected @endif>
                                        Female
                                    </option>
                                    <option value="Male" @if( old('gender') == 'Male' || $gender == 'Male') selected @endif>
                                        Male
                                    </option>
                                </select>

                                @error('gender')
                                <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="position-relative form-group">
                                <label for="address" class="">Applicant/Employee Address</label>
                                <span class="text-danger">*</span>
                                <input name="address" id="address" type="text" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') ?? $staff_biographical_data_sheet->address}}">

                                @error('address')
                                <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="phone_no" class="">Phone No.</label>
                                <span class="text-danger">*</span>
                                <input name="phone_no" id="phone_no" type="text" class="form-control @error('phone_no') is-invalid @enderror" value="{{ old('phone_no') ?? $staff_biographical_data_sheet->phone_no}}">

                                @error('phone_no')
                                <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="place_of_birth" class="">Place Of Birth</label>
                                <span class="text-danger">*</span>
                                <input name="place_of_birth" id="place_of_birth" type="text" class="form-control @error('place_of_birth') is-invalid @enderror" value="{{ old('place_of_birth') ?? $staff_biographical_data_sheet->place_of_birth}}">

                                @error('place_of_birth')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="date_of_birth" class="">Date Of Birth</label>
                                <span class="text-danger">*</span>
                                <input name="date_of_birth" id="date_of_birth" type="text" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth') ?? $staff_biographical_data_sheet->date_of_birth}}"  data-toggle="datepicker-year" autocomplete="off">

                                @error('date_of_birth')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="citizenship" class="">Citizenship</label>
                                <span class="text-danger">*</span>
                                <input name="citizenship" id="citizenship" type="text" class="form-control @error('citizenship') is-invalid @enderror" value="{{ old('citizenship') ?? $staff_biographical_data_sheet->citizenship}}">

                                @error('citizenship')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="home_region" class="">Home Region</label>
                                <span class="text-danger">*</span>
                                <input name="home_region" id="home_region" type="text" class="form-control @error('home_region') is-invalid @enderror" value="{{ old('home_region') ?? $staff_biographical_data_sheet->home_region}}">

                                @error('home_region')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="home_district" class="">Home District</label>
                                <span class="text-danger">*</span>
                                <input name="home_district" id="home_district" type="text" class="form-control @error('home_district') is-invalid @enderror" value="{{ old('home_district') ?? $staff_biographical_data_sheet->home_district}}">

                                @error('home_district')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</fieldset>

<?php
$disabled = '';
$readonly = '';
$visibility = '';

if( !in_array(auth()->user()->role_id, [1,3]) ){
    $disabled = 'disabled';
    $readonly = 'readonly';
    $visibility = 'visible';
}

?>
<fieldset {{$disabled}}>
    <legend>Contractor</legend>
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-md-12">
            <div class="form-row">
                <div class="col-md-5">
                    <div class="position-relative form-group">
                        <label for="contractor_name" class="">Contractor Name</label>
                        <span class="text-danger">*</span>
                        <input name="contractor_name" id="contractor_name" type="text" class="form-control @error('contractor_name') is-invalid @enderror" value="{{ old('contractor_name') ?? $staff_biographical_data_sheet->contractor_name}}">

                        @error('contractor_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="position-relative form-group">
                        <label for="contractor_no" class="">Contractor No.</label>
                        <span class="text-danger">*</span>
                        <input name="contractor_no" id="contractor_no" type="text" class="form-control @error('contractor_no') is-invalid @enderror" value="{{ old('contractor_no') ?? $staff_biographical_data_sheet->contractor_no}}">

                        @error('contractor_no')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="position_under_contract" class="">Position Under Contract</label>
                        <span class="text-danger">*</span>
                        <input name="position_under_contract" id="position_under_contract" type="text" class="form-control @error('position_under_contract') is-invalid @enderror" value="{{ old('position_under_contract') ?? $staff_biographical_data_sheet->position_under_contract}}">

                        @error('position_under_contract')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="position-relative form-group">
                        <label for="proposed_salary_lcy" class="">Proposed Salary</label>
                        <span class="text-danger">(In Local Currency) *</span>
                        <input name="proposed_salary_lcy" id="proposed_salary_lcy" type="text" class="form-control @error('proposed_salary_lcy') is-invalid @enderror" value="{{ old('proposed_salary_lcy') ?? $staff_biographical_data_sheet->proposed_salary_lcy}}">

                        @error('proposed_salary_lcy')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="position-relative form-group">
                        <label for="proposed_salary_frc" class="">Proposed Salary</label>
                        <span class="text-danger">(In Dollars)  *</span>
                        <input name="proposed_salary_frc" id="proposed_salary_frc" type="text" class="form-control @error('proposed_salary_frc') is-invalid @enderror" value="{{ old('proposed_salary_frc') ?? $staff_biographical_data_sheet->proposed_salary_frc}}">

                        @error('proposed_salary_frc')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="position-relative form-group">
                        <label for="assigned_country" class="">Country of Assignment/Hiring</label>
                        <span class="text-danger">*</span>
                        <input name="assigned_country" id="assigned_country" type="text" class="form-control @error('assigned_country') is-invalid @enderror" value="{{ old('assigned_country') ?? $staff_biographical_data_sheet->assigned_country}}">

                        @error('assigned_country')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="position-relative form-group">
                        <label for="date_of_employment" class="">Date Of Employment</label>
                        <span class="text-danger">*</span>
                        <input name="date_of_employment" id="date_of_employment" type="text" class="form-control @error('date_of_employment') is-invalid @enderror" value="{{ old('date_of_employment') ?? $staff_biographical_data_sheet->date_of_employment}}"  data-toggle="datepicker-year" autocomplete="off">

                        @error('date_of_employment')
                        <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="position-relative form-group">
                        <label for="employment_duration" class="">Duration of Assignment/Employment</label>
                        <span class="text-danger">*</span>
                        <input name="employment_duration" id="employment_duration" type="text" class="form-control @error('employment_duration') is-invalid @enderror" value="{{ old('employment_duration') ?? $staff_biographical_data_sheet->employment_duration}}">

                        @error('employment_duration')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</fieldset>


<fieldset class="mt-3">
    <legend>
        <div class="row">
            <div class="col-md-6">
                Employee Education
            </div>
            <div class="col-md-6">
                @if( in_array($view_type,['create','edit']))
                    <div class="text-right {{$visibility}}">
                        <button class="btn btn-secondary btn-xs add_row_after">
                            <i class="fa fa-fw" aria-hidden="true" title="Copy to use plus"></i>
                            Add Row After
                        </button>
                        <button class="btn btn-secondary btn-xs add_row_before">
                            <i class="fa fa-fw" aria-hidden="true" title="Copy to use plus"></i>
                            Add Row Before
                        </button>
                        <button class="btn btn-secondary btn-xs remove_row">
                            <i class="fa fa-fw" aria-hidden="true" title="Copy to use minus"></i>
                            Remove Line
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </legend>
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <table style="width: 100%;" id="education_table" class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 5%;" >#</th>
                            <th style="width: 30%;">Name & Location Of Institution</th>
                            <th style="width: 25%;">Major</th>
                            <th style="width: 25%;">Degree</th>
                            <th style="width: 10%;">Year</th>
                            <th style="width: 5%;" class="text-center">
                                <input type="checkbox" name="checkbox_0" class="select-multiple-rows" value="0">
                            </th>
                        </tr>
                        </thead>
                        <tbody id="education_table_body">
                        @if(count($education) == 0)
                            <tr class="data-row" id="education_table_row_1">
                                <td class="data-column">
                                    <input name="education_table_row_1[]" class="column-input row-no" autocomplete="off" value="1" readonly>
                                </td>
                                <td class="data-column">
                                    <input name="education_table_row_1[]" class="column-input name" value="{{old('education_table_row_1')[1] ?? ''}}" autocomplete="off">
                                </td>
                                <td class="data-column">
                                    <input name="education_table_row_1[]" class="column-input major" value="{{old('education_table_row_1')[2] ?? ''}}" autocomplete="off">
                                </td>
                                <td class="data-column">
                                    <input name="education_table_row_1[]" class="column-input degree" value="{{old('education_table_row_1')[3] ?? ''}}" autocomplete="off">
                                </td>
                                <td class="data-column">
                                    <input name="education_table_row_1[]" class="column-input year" value="{{old('education_table_row_1')[4] ?? ''}}" autocomplete="off">
                                </td>
                                <td class="data-column text-center">
                                    <input type="checkbox" name="education_table_row_1[]" class="select-one-row-checkbox" value="1">
                                </td>
                            </tr>
                            <?php $m=2;?>
                            @while(!is_null( old('education_table_row_'.$m)))
                                <tr class="data-row" id="education_table_row_{{$m}}">
                                    <td class="data-column">
                                        <input name="education_table_row_{{$m}}[]" class="column-input row-no" autocomplete="off" value="{{$m}}" readonly>
                                    </td>
                                    <td class="data-column">
                                        <input name="education_table_row_{{$m}}[]" class="column-input name" value="{{old('education_table_row_'.$m)[1]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="education_table_row_{{$m}}[]" class="column-input major" value="{{old('education_table_row_'.$m)[2]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="education_table_row_{{$m}}[]" class="column-input degree" value="{{old('education_table_row_'.$m)[3]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="education_table_row_{{$m}}[]" class="column-input year" value="{{old('education_table_row_'.$m)[4]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column text-center">
                                        <input type="checkbox" name="education_table_row_{{$m}}[]" class="select-one-row-checkbox" value="{{$m}}">
                                    </td>
                                </tr>
                                <?php  $m++;?>
                            @endwhile
                        @else
                            <?php $n = 1;?>
                            @foreach($education as $education_line)
                                @if(count($education_line) >= 5)
                                <tr class="data-row" id="education_table_row_{{$n}}">
                                    <td class="data-column">
                                        <input name="education_table_row_{{$n}}[]" class="column-input row-no" autocomplete="off" value="{{$n}}" readonly>
                                    </td>
                                    <td class="data-column">
                                        <input name="education_table_row_{{$n}}[]" class="column-input name" autocomplete="off" value="{{ old('education_table_row_'.$n)[1] ?? $education_line[1]}}">
                                    </td>
                                    <td class="data-column">
                                        <input name="education_table_row_{{$n}}[]" class="column-input major" autocomplete="off" value="{{ old('education_table_row_'.$n)[2] ?? $education_line[2]}}">
                                    </td>
                                    <td class="data-column">
                                        <input name="education_table_row_{{$n}}[]" class="column-input degree" autocomplete="off" value="{{ old('education_table_row_'.$n)[3] ?? $education_line[3]}}">
                                    </td>
                                    <td class="data-column text-center">
                                        <input name="education_table_row_{{$n}}[]" class="column-input year"  value="{{ old('education_table_row_'.$n)[4] ?? $education_line[4]}}">
                                    </td>
                                    <td class="data-column text-center">
                                        <input type="checkbox" name="education_table_row_{{$n}}[]" class="select-one-row-checkbox" value="{{$n}}">
                                    </td>
                                </tr>
                                @endif
                                <?php $n++;?>
                            @endforeach

                            <?php $m=$n;?>
                            @while(!is_null( old('education_table_row_'.$m)))
                                <tr class="data-row" id="education_table_row_{{$m}}">
                                    <td class="data-column">
                                        <input name="education_table_row_{{$m}}[]" class="column-input row-no" autocomplete="off" value="{{$m}}" readonly>
                                    </td>
                                    <td class="data-column">
                                        <input name="education_table_row_{{$m}}[]" class="column-input name" value="{{old('education_table_row_'.$m)[1]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="education_table_row_{{$m}}[]" class="column-input major" value="{{old('education_table_row_'.$m)[2]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="education_table_row_{{$m}}[]" class="column-input degree" value="{{old('education_table_row_'.$m)[3]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="education_table_row_{{$m}}[]" class="column-input year" value="{{old('education_table_row_'.$m)[4]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column text-center">
                                        <input type="checkbox" name="education_table_row_{{$m}}[]" class="select-one-row-checkbox" value="{{$m}}">
                                    </td>
                                </tr>
                                <?php  $m++;?>
                            @endwhile

                        @endif
                        </tbody>
                        <tfoot>
                        </tfoot>

                    </table>

                </div>
            </div>
        </div>
    </div>

</fieldset>


<fieldset class="mt-3">
    <legend>
        <div class="row">
            <div class="col-md-6">
                Employee Language Proficiency
            </div>
            <div class="col-md-6">
                @if( in_array($view_type,['create','edit']))
                    <div class="text-right {{$visibility}}">
                        <button class="btn btn-secondary btn-xs add_row_after">
                            <i class="fa fa-fw" aria-hidden="true" title="Copy to use plus"></i>
                            Add Row After
                        </button>
                        <button class="btn btn-secondary btn-xs add_row_before">
                            <i class="fa fa-fw" aria-hidden="true" title="Copy to use plus"></i>
                            Add Row Before
                        </button>
                        <button class="btn btn-secondary btn-xs remove_row">
                            <i class="fa fa-fw" aria-hidden="true" title="Copy to use minus"></i>
                            Remove Line
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </legend>
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <table style="width: 100%;" id="language_table" class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 5%;" >#</th>
                            <th style="width: 40%;">Language Name</th>
                            <th style="width: 25%;">Proficiency Speaking</th>
                            <th style="width: 25%;">Proficiency Reading</th>
                            <th style="width: 5%;" class="text-center">
                                <input type="checkbox" name="checkbox_10" class="select-multiple-rows" value="0">
                            </th>
                        </tr>
                        </thead>
                        <tbody id="language_table_body">
                        @if(count($language) == 0)
                            <tr class="data-row" id="language_table_row_1">
                                <td class="data-column">
                                    <input name="language_table_row_1[]" class="column-input row-no" autocomplete="off" value="1" readonly>
                                </td>
                                <td class="data-column">
                                    <input name="language_table_row_1[]" class="column-input language-name" value="{{old('language_table_row_1')[1] ?? ''}}" autocomplete="off">
                                </td>
                                <td class="data-column">
                                    <input name="language_table_row_1[]" class="column-input proficiency-speaking" value="{{old('language_table_row_1')[2] ?? ''}}" autocomplete="off">
                                </td>
                                <td class="data-column">
                                    <input name="language_table_row_1[]" class="column-input proficiency-writing" value="{{old('language_table_row_1')[3] ?? ''}}" autocomplete="off">
                                </td>
                                <td class="data-column text-center">
                                    <input type="checkbox" name="language_table_row_1[]" class="select-one-row-checkbox" value="1">
                                </td>
                            </tr>
                            <?php $m=2;?>
                            @while(!is_null( old('language_table_row_'.$m)))
                                <tr class="data-row" id="language_table_row_{{$m}}">
                                    <td class="data-column">
                                        <input name="language_table_row_{{$m}}[]" class="column-input row-no" autocomplete="off" value="{{$m}}" readonly>
                                    </td>
                                    <td class="data-column">
                                        <input name="language_table_row_{{$m}}[]" class="column-input language-name" value="{{old('language_table_row_'.$m)[1]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="language_table_row_{{$m}}[]" class="column-input proficiency-speaking" value="{{old('language_table_row_'.$m)[2]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="language_table_row_{{$m}}[]" class="column-input proficiency-writing" value="{{old('language_table_row_'.$m)[3]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column text-center">
                                        <input type="checkbox" name="language_table_row_{{$m}}[]" class="select-one-row-checkbox" value="{{$m}}">
                                    </td>
                                </tr>
                                <?php  $m++;?>
                            @endwhile
                        @else
                            <?php $n = 1;?>
                            @foreach($language as $language_line)
                                @if( count($language_line) >= 4)
                                    <tr class="data-row" id="language_table_row_{{$n}}">
                                        <td class="data-column">
                                            <input name="language_table_row_{{$n}}[]" class="column-input row-no" autocomplete="off" value="{{$n}}" readonly>
                                        </td>
                                        <td class="data-column">
                                            <input name="language_table_row_{{$n}}[]" class="column-input language-name" autocomplete="off" value="{{ old('language_table_row_'.$n)[1] ?? $language_line[1]}}">
                                        </td>
                                        <td class="data-column">
                                            <input name="language_table_row_{{$n}}[]" class="column-input proficiency-speaking" autocomplete="off" value="{{ old('language_table_row_'.$n)[2] ?? $language_line[2]}}">
                                        </td>
                                        <td class="data-column">
                                            <input name="language_table_row_{{$n}}[]" class="column-input proficiency-writing" autocomplete="off" value="{{ old('language_table_row_'.$n)[3] ?? $language_line[3]}}">
                                        </td>
                                        <td class="data-column text-center">
                                            <input type="checkbox" name="language_table_row_{{$n}}[]" class="select-one-row-checkbox" value="{{$n}}">
                                        </td>
                                    </tr>
                                @endif
                                <?php $n++;?>
                            @endforeach

                            <?php $m=$n;?>
                            @while(!is_null( old('language_table_row_'.$m)))
                                <tr class="data-row" id="language_table_row_{{$m}}">
                                    <td class="data-column">
                                        <input name="language_table_row_{{$m}}[]" class="column-input row-no" autocomplete="off" value="{{$m}}" readonly>
                                    </td>
                                    <td class="data-column">
                                        <input name="language_table_row_{{$m}}[]" class="column-input language-name" value="{{old('language_table_row_'.$m)[1]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="language_table_row_{{$m}}[]" class="column-input proficiency-speaking" value="{{old('language_table_row_'.$m)[2]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="language_table_row_{{$m}}[]" class="column-input proficiency-writing" value="{{old('language_table_row_'.$m)[3]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column text-center">
                                        <input type="checkbox" name="language_table_row_{{$m}}[]" class="select-one-row-checkbox" value="{{$m}}">
                                    </td>
                                </tr>
                                <?php  $m++;?>
                            @endwhile
                        @endif
                        </tbody>
                        <tfoot>
                        </tfoot>

                    </table>

                </div>
            </div>
        </div>
    </div>

</fieldset>


<fieldset class="mt-3">
    <legend>
        <div class="row">
            <div class="col-md-6">
                Employee Employment History
            </div>
            <div class="col-md-6">
                @if( in_array($view_type,['create','edit']))
                    <div class="text-right {{$visibility}}">
                        <button class="btn btn-secondary btn-xs add_row_after">
                            <i class="fa fa-fw" aria-hidden="true" title="Copy to use plus"></i>
                            Add Row After
                        </button>
                        <button class="btn btn-secondary btn-xs add_row_before">
                            <i class="fa fa-fw" aria-hidden="true" title="Copy to use plus"></i>
                            Add Row Before
                        </button>
                        <button class="btn btn-secondary btn-xs remove_row">
                            <i class="fa fa-fw" aria-hidden="true" title="Copy to use minus"></i>
                            Remove Line
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </legend>
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <table style="width: 100%;" id="employment_history_table" class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 5%;" ></th>
                            <th style="width: 45%;" colspan="2"></th>
                            <th style="width: 45%;" colspan="3">Employment Period/Salary: (Most recent first/in currency paid)</th>
                            <th style="width: 5%;" class="text-center">
                            </th>
                        </tr>
                        <tr>
                            <th style="width: 5%;" >#</th>
                            <th style="width: 45%;" colspan="2">Give last three (3) years. List salaries separate for each year</th>
                            <th style="width: 15%;">From</th>
                            <th style="width: 15%;">To</th>
                            <th style="width: 15%;">Amount</th>
                            <th style="width: 5%;" class="text-center">
                                <input type="checkbox" name="checkbox_10" class="select-multiple-rows" value="0">
                            </th>
                        </tr>
                        </thead>
                        <tbody id="employment_history_table_body">
                        @if(count($employment_history) == 0)
                            <tr class="data-row" id="employment_history_table_row_1">
                                <td class="data-column">
                                    <input name="employment_history_table_row_1[]" class="column-input row-no" autocomplete="off" value="1" readonly>
                                </td>
                                <td class="data-column">
                                    <input name="employment_history_table_row_1[]" class="column-input company-name" value="{{old('employment_history_table_row_1')[1] ?? ''}}" autocomplete="off">
                                </td>
                                <td class="data-column">
                                    <input name="employment_history_table_row_1[]" class="column-input position" value="{{old('employment_history_table_row_1')[2] ?? ''}}" autocomplete="off">
                                </td>
                                <td class="data-column">
                                    <input name="employment_history_table_row_1[]" class="column-input from" value="{{old('employment_history_table_row_1')[3] ?? ''}}" autocomplete="off">
                                </td>
                                <td class="data-column">
                                    <input name="employment_history_table_row_1[]" class="column-input to" value="{{old('employment_history_table_row_1')[4] ?? ''}}" autocomplete="off">
                                </td>
                                <td class="data-column">
                                    <input name="employment_history_table_row_1[]" class="column-input amount" value="{{old('employment_history_table_row_1')[5] ?? ''}}" autocomplete="off">
                                </td>
                                <td class="data-column text-center">
                                    <input type="checkbox" name="employment_history_table_row_1[]" class="select-one-row-checkbox" value="1">
                                </td>
                            </tr>
                            <?php $m=2;?>
                            @while(!is_null( old('employment_history_table_row_'.$m)))
                                <tr class="data-row" id="employment_history_table_row_{{$m}}">
                                    <td class="data-column">
                                        <input name="employment_history_table_row_{{$m}}[]" class="column-input row-no" autocomplete="off" value="{{$m}}" readonly>
                                    </td>
                                    <td class="data-column">
                                        <input name="employment_history_table_row_{{$m}}[]" class="column-input company-name" value="{{old('employment_history_table_row_'.$m)[1]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="employment_history_table_row_{{$m}}[]" class="column-input position" value="{{old('employment_history_table_row_'.$m)[2]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="employment_history_table_row_{{$m}}[]" class="column-input from" value="{{old('employment_history_table_row_'.$m)[3]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="employment_history_table_row_{{$m}}[]" class="column-input to" value="{{old('employment_history_table_row_'.$m)[4]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="employment_history_table_row_{{$m}}[]" class="column-input amount" value="{{old('employment_history_table_row_'.$m)[5]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column text-center">
                                        <input type="checkbox" name="employment_history_table_row_{{$m}}[]" class="select-one-row-checkbox" value="{{$m}}">
                                    </td>
                                </tr>
                                <?php  $m++;?>
                            @endwhile
                        @else
                            <?php $n = 1;?>
                            @foreach($employment_history as $employment_history_line)
                                @if( count($employment_history_line) >= 6)
                                    <tr class="data-row" id="employment_history_table_row_{{$n}}">
                                        <td class="data-column">
                                            <input name="employment_history_table_row_{{$n}}[]" class="column-input row-no" autocomplete="off" value="{{$n}}" readonly>
                                        </td>
                                        <td class="data-column">
                                            <input name="employment_history_table_row_{{$n}}[]" class="column-input company-name" autocomplete="off" value="{{ old('employment_history_table_row_'.$n)[1] ?? $employment_history_line[1]}}">
                                        </td>
                                        <td class="data-column">
                                            <input name="employment_history_table_row_{{$n}}[]" class="column-input position" autocomplete="off" value="{{ old('employment_history_table_row_'.$n)[2] ?? $employment_history_line[2]}}">
                                        </td>
                                        <td class="data-column">
                                            <input name="employment_history_table_row_{{$n}}[]" class="column-input from" autocomplete="off" value="{{ old('employment_history_table_row_'.$n)[3] ?? $employment_history_line[3]}}">
                                        </td>
                                        <td class="data-column">
                                            <input name="employment_history_table_row_{{$n}}[]" class="column-input to" autocomplete="off" value="{{ old('employment_history_table_row_'.$n)[4] ?? $employment_history_line[4]}}">
                                        </td>
                                        <td class="data-column">
                                            <input name="employment_history_table_row_{{$n}}[]" class="column-input amount" autocomplete="off" value="{{ old('employment_history_table_row_'.$n)[5] ?? $employment_history_line[5]}}">
                                        </td>
                                        <td class="data-column text-center">
                                            <input type="checkbox" name="employment_history_checkbox_{{$n}}" class="select-one-row-checkbox" value="{{$n}}">
                                        </td>
                                    </tr>
                                @endif
                                <?php $n++;?>
                            @endforeach


                            <?php $m=$n;?>
                            @while(!is_null( old('employment_history_table_row_'.$m)))
                                <tr class="data-row" id="employment_history_table_row_{{$m}}">
                                    <td class="data-column">
                                        <input name="employment_history_table_row_{{$m}}[]" class="column-input row-no" autocomplete="off" value="{{$m}}" readonly>
                                    </td>
                                    <td class="data-column">
                                        <input name="employment_history_table_row_{{$m}}[]" class="column-input company-name" value="{{old('employment_history_table_row_'.$m)[1]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="employment_history_table_row_{{$m}}[]" class="column-input position" value="{{old('employment_history_table_row_'.$m)[2]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="employment_history_table_row_{{$m}}[]" class="column-input from" value="{{old('employment_history_table_row_'.$m)[3]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="employment_history_table_row_{{$m}}[]" class="column-input to" value="{{old('employment_history_table_row_'.$m)[4]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="employment_history_table_row_{{$m}}[]" class="column-input amount" value="{{old('employment_history_table_row_'.$m)[5]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column text-center">
                                        <input type="checkbox" name="employment_history_table_row_{{$m}}[]" class="select-one-row-checkbox" value="{{$m}}">
                                    </td>
                                </tr>
                                <?php  $m++;?>
                            @endwhile

                        @endif
                        </tbody>
                        <tfoot>
                        </tfoot>

                    </table>

                </div>
            </div>
        </div>
    </div>

</fieldset>


<fieldset class="mt-3">
    <legend>
        <div class="row">
            <div class="col-md-6">
                Specific Consultant Services (Give last three (3) years)
            </div>
            <div class="col-md-6">
                @if( in_array($view_type,['create','edit']))
                    <div class="text-right {{$visibility}}">
                        <button class="btn btn-secondary btn-xs add_row_after">
                            <i class="fa fa-fw" aria-hidden="true" title="Copy to use plus"></i>
                            Add Row After
                        </button>
                        <button class="btn btn-secondary btn-xs add_row_before">
                            <i class="fa fa-fw" aria-hidden="true" title="Copy to use plus"></i>
                            Add Row Before
                        </button>
                        <button class="btn btn-secondary btn-xs remove_row">
                            <i class="fa fa-fw" aria-hidden="true" title="Copy to use minus"></i>
                            Remove Line
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </legend>
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <table style="width: 100%;" id="consultant_service_table" class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 5%;"></th>
                            <th style="width: 25%;"></th>
                            <th style="width: 25%;"></th>
                            <th style="width: 20%;" colspan="2">Dates Of Service (MM/DD/YY)</th>
                            <th style="width: 10%;"></th>
                            <th style="width: 10%;"></th>
                            <th style="width: 5%;" class="text-center"></th>
                        </tr>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 25%;">Services Performed</th>
                            <th style="width: 25%;">Company's Name & Address <br>Point Of Contact & Telephone#</th>
                            <th style="width: 10%;">From</th>
                            <th style="width: 10%;">To</th>
                            <th style="width: 10%;">Daily Rate (Local Currency)</th>
                            <th style="width: 10%;">Days At Rate</th>
                            <th style="width: 5%;" class="text-center">
                                <input type="checkbox" name="checkbox_10" class="select-multiple-rows" value="0">
                            </th>
                        </tr>
                        </thead>
                        <tbody id="consultant_service_table_body">
                        @if(count($specific_consultant_services) == 0)
                            <tr class="data-row" id="consultant_service_table_row_1">
                                <td class="data-column">
                                    <input name="consultant_service_table_row_1[]" class="column-input row-no" autocomplete="off" value="1" readonly>
                                </td>
                                <td class="data-column">
                                    <input name="consultant_service_table_row_1[]" class="column-input service-performed" value="{{old('consultant_service_table_row_1')[1] ?? ''}}" autocomplete="off">
                                </td>
                                <td class="data-column">
                                    <input name="consultant_service_table_row_1[]" class="column-input company-name" value="{{old('consultant_service_table_row_1')[2] ?? ''}}" autocomplete="off">
                                </td>
                                <td class="data-column">
                                    <input name="consultant_service_table_row_1[]" class="column-input from" value="{{old('consultant_service_table_row_1')[3] ?? ''}}" autocomplete="off">
                                </td>
                                <td class="data-column">
                                    <input name="consultant_service_table_row_1[]" class="column-input to" value="{{old('consultant_service_table_row_1')[4] ?? ''}}" autocomplete="off">
                                </td>
                                <td class="data-column">
                                    <input name="consultant_service_table_row_1[]" class="column-input daily-rate" value="{{old('consultant_service_table_row_1')[5] ?? ''}}" autocomplete="off">
                                </td>
                                <td class="data-column">
                                    <input name="consultant_service_table_row_1[]" class="column-input days-at-rate" value="{{old('consultant_service_table_row_1')[6] ?? ''}}" autocomplete="off">
                                </td>
                                <td class="data-column text-center">
                                    <input type="checkbox" name="consultant_service_table_row_1[]" class="select-one-row-checkbox" value="1">
                                </td>
                            </tr>

                            <?php $m=2;?>
                            @while(!is_null( old('consultant_service_table_row_'.$m)))
                                <tr class="data-row" id="consultant_service_table_row_{{$m}}">
                                    <td class="data-column">
                                        <input name="consultant_service_table_row_{{$m}}[]" class="column-input row-no" autocomplete="off" value="{{$m}}" readonly>
                                    </td>
                                    <td class="data-column">
                                        <input name="consultant_service_table_row_{{$m}}[]" class="column-input service-performed" value="{{old('consultant_service_table_row_'.$m)[1]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="consultant_service_table_row_{{$m}}[]" class="column-input company-name" value="{{old('consultant_service_table_row_'.$m)[2]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="consultant_service_table_row_{{$m}}[]" class="column-input from" value="{{old('consultant_service_table_row_'.$m)[3]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="consultant_service_table_row_{{$m}}[]" class="column-input to" value="{{old('consultant_service_table_row_'.$m)[4]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="consultant_service_table_row_{{$m}}[]" class="column-input daily-rate" value="{{old('consultant_service_table_row_'.$m)[5]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="consultant_service_table_row_{{$m}}[]" class="column-input days-at-rate" value="{{old('consultant_service_table_row_'.$m)[6]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column text-center">
                                        <input type="checkbox" name="consultant_service_table_row_{{$m}}[]" class="select-one-row-checkbox" value="{{$m}}">
                                    </td>
                                </tr>
                                <?php  $m++;?>
                            @endwhile
                        @else
                            <?php $n = 1;?>
                            @foreach($specific_consultant_services as $specific_consultant_services_line)
                                @if( count($specific_consultant_services_line) >= 7)
                                    <tr class="data-row" id="consultant_service_table_row_{{$n}}">
                                        <td class="data-column">
                                            <input name="consultant_service_table_row_{{$n}}[]" class="column-input row-no" autocomplete="off" value="{{$n}}" readonly>
                                        </td>
                                        <td class="data-column">
                                            <input name="consultant_service_table_row_{{$n}}[]" class="column-input service-performed" autocomplete="off" value="{{ old('consultant_service_table_row_'.$n)[1] ?? $specific_consultant_services_line[1]}}">
                                        </td>
                                        <td class="data-column">
                                            <input name="consultant_service_table_row_{{$n}}[]" class="column-input company-name" autocomplete="off" value="{{ old('consultant_service_table_row_'.$n)[2] ?? $specific_consultant_services_line[2]}}">
                                        </td>
                                        <td class="data-column">
                                            <input name="consultant_service_table_row_{{$n}}[]" class="column-input from" autocomplete="off" value="{{ old('consultant_service_table_row_'.$n)[3] ?? $specific_consultant_services_line[3]}}">
                                        </td>
                                        <td class="data-column">
                                            <input name="consultant_service_table_row_{{$n}}[]" class="column-input to" autocomplete="off" value="{{ old('consultant_service_table_row_'.$n)[4] ?? $specific_consultant_services_line[4]}}">
                                        </td>
                                        <td class="data-column">
                                            <input name="consultant_service_table_row_{{$n}}[]" class="column-input daily-rate" autocomplete="off" value="{{ old('consultant_service_table_row_'.$n)[5] ?? $specific_consultant_services_line[5]}}">
                                        </td>
                                        <td class="data-column">
                                            <input name="consultant_service_table_row_{{$n}}[]" class="column-input days-at-rate" autocomplete="off" value="{{ old('consultant_service_table_row_'.$n)[6] ?? $specific_consultant_services_line[6]}}">
                                        </td>
                                        <td class="data-column text-center">
                                            <input type="checkbox" name="consultant_service_table_row_{{$n}}" class="select-one-row-checkbox" value="{{$n}}">
                                        </td>
                                    </tr>
                                @endif
                                <?php $n++;?>
                            @endforeach

                            <?php $m=$n;?>
                            @while(!is_null( old('consultant_service_table_row_'.$m)))
                                <tr class="data-row" id="consultant_service_table_row_{{$m}}">
                                    <td class="data-column">
                                        <input name="consultant_service_table_row_{{$m}}[]" class="column-input row-no" autocomplete="off" value="{{$m}}" readonly>
                                    </td>
                                    <td class="data-column">
                                        <input name="consultant_service_table_row_{{$m}}[]" class="column-input service-performed" value="{{old('consultant_service_table_row_'.$m)[1]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="consultant_service_table_row_{{$m}}[]" class="column-input company-name" value="{{old('consultant_service_table_row_'.$m)[2]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="consultant_service_table_row_{{$m}}[]" class="column-input from" value="{{old('consultant_service_table_row_'.$m)[3]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="consultant_service_table_row_{{$m}}[]" class="column-input to" value="{{old('consultant_service_table_row_'.$m)[4]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="consultant_service_table_row_{{$m}}[]" class="column-input daily-rate" value="{{old('consultant_service_table_row_'.$m)[5]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column">
                                        <input name="consultant_service_table_row_{{$m}}[]" class="column-input days-at-rate" value="{{old('consultant_service_table_row_'.$m)[6]}}" autocomplete="off">
                                    </td>
                                    <td class="data-column text-center">
                                        <input type="checkbox" name="consultant_service_table_row_{{$m}}[]" class="select-one-row-checkbox" value="{{$m}}">
                                    </td>
                                </tr>
                                <?php  $m++;?>
                            @endwhile
                        @endif
                        </tbody>
                        <tfoot>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
    </div>


</fieldset>





<!-- Common Script-->
<script type="text/javascript">


    $('.add_row_after,.add_row_before').on('click',function(event){
        event.preventDefault();

        var all_checkboxes = $('.select-one-row-checkbox');
        var checked_boxes = 0;
        var position = 'after';

        if($(this).hasClass('add_row_before')){
            position = 'before';
        }else{
            position = 'after';
        }

        all_checkboxes.each(function(){

            if($(this).is(":checked")){
                checked_boxes += 1;
                var current_row = $(this).parent().parent();

                //add another row after this row
                add_another_row(current_row,position);
            }
        });

        if(checked_boxes == 0){
            sweet_alert_warning('There is no any row selected, please select a row to add another row below it');
        }

    });

    $('.remove_row').on('click',function(event){
        event.preventDefault();

        var all_checkboxes = $('.select-one-row-checkbox');
        var checked_boxes = 0;

        all_checkboxes.each(function(){

            if($(this).is(":checked")){
                checked_boxes += 1;
                var current_row = $(this).parent().parent();
                var current_row_id = current_row.attr('id');
                var id_parts = current_row_id.split('_');
                var id_no = id_parts[id_parts.length-1];
                var id_base = current_row_id.replace('_'+id_no,'');

                //remove row
                if(id_no > 1){ current_row.remove(); }

                //rearrange items in a table (id & names)
                var table_name = id_base.replace('_row','');
                rearrange_items_in_a_table(table_name);
            }

        });


        if(checked_boxes == 0){
            sweet_alert_warning('There is no any line selected, please select a line first, then delete it');
        }

    });



    //when select-multiple-rows checkbox is clicked
    $(".select-multiple-rows").on("click",function(){

        var select_all = '';
        $(this).is(":checked") ? select_all = 'true' : select_all = 'false';

        //get all check boxes in a table
        var current_table = $(this).parent().parent().parent().parent();
        var all_checkboxes_in_a_table = current_table.find('.select-one-row-checkbox');

        //select all checkboxes in a table
        all_checkboxes_in_a_table.each(function(index){

            select_all == 'true' ? $(this).prop('checked',true) :  $(this).prop('checked',false);
        });
    });


    /******************* sweet alerts *******************************/



    function add_another_row(current_row,position){

        var current_row_id = current_row.attr('id');
        var id_parts = current_row_id.split('_');
        var id_no = id_parts[id_parts.length-1];
        var id_base = current_row_id.replace('_'+id_no,'');
        var new_row = current_row.clone();

        //reset new row values
        var new_row_id = id_base+'_x';
        new_row.attr('id',new_row_id);

        var new_row_columns = new_row.children('td');

        $(new_row_columns).each(function(){
            var column_input= $(this).children('input');
            if( column_input.attr('name') == current_row_id+'[]' ){
                //change input name & reset values
                column_input.attr('name',new_row_id+'[]');
                column_input.val('');

            }
            if( column_input.hasClass('select-one-row-checkbox')){
                //un-check the selection box
                column_input.prop('checked',false);
                column_input.val('x');
            }
        });

        //add the row to the table
        if( position == 'after'){
            //append new row to the last row
            $('#'+current_row_id).after(new_row);
        }
        if( position == 'before'){
            //append new row to the last row
            $('#'+current_row_id).before(new_row);
        }


        //rearrange items in a table (id & names)
        var table_name = id_base.replace('_row','');
        rearrange_items_in_a_table(table_name);

    }


    function rearrange_items_in_a_table(table_name){
        var current_table = $('#'+table_name);
        var rows = current_table.children('tbody').children('tr');

        var n = 1;
        rows.each(function(){

            //rearrange row ids
            var current_row = $(this);
            var row_id = table_name+'_row_'+n;
            current_row.attr('id',row_id);

            //rename inputs in a row

            var row_columns = current_row.children('td');

            $(row_columns).each(function(){
                var column_input= $(this).children('input');

                if( column_input.hasClass('select-one-row-checkbox') ){
                    column_input.val(n);
                }
                else if( column_input.hasClass('row-no')){
                    column_input.attr('name',row_id+'[]');
                    column_input.val(n);
                }
                else{
                    column_input.attr('name',row_id+'[]');
                }

            });

            n++;
        });

    }

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
