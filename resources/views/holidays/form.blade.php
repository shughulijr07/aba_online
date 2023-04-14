@csrf
<div class="form-row">

    <div class="col-md-4" id="year_div">
        <div class="position-relative form-group">
            <label for="holiday_year" class="">
                <span>Year</span>
            </label>
            <select name="holiday_year" id="holiday_year"  class="form-control @error('country') is-invalid @enderror">
                <?php $varying_year=date("Y") + 1; $last_year = date('Y') - 4; ?>
                @while( $varying_year >= $last_year )
                    <option value="{{$varying_year}}" @if( ($varying_year == date("Y") || $varying_year == old('holiday_year')) ||  $varying_year == $holiday->holiday_year) selected @endif>
                        {{$varying_year}}
                    </option>

                    <?php $varying_year--;?>
                @endwhile
            </select>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="position-relative form-group">
            <label for="name" class="">Holiday Name</label>
            <input name="name" id="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') ?? $holiday->name}}">

            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
             </span>
            @enderror
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="position-relative form-group">
            <label for="holiday_date" class="">
                <span>Holiday Date</span>
                <span class="text-danger">*</span>
            </label>
            <input name="holiday_date" id="holiday_date" type="text" class="form-control @error('holiday_date') is-invalid @enderror" value="{{ old('holiday_date') ?? $holiday_date}}" autocomplete="off">

            @error('holiday_date')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
</div>


<script type="text/javascript">

    $(document).ready(function(){
        $('#holiday_date').datepicker({
            format: 'dd/mm/yyyy'
        });
    });
</script>    