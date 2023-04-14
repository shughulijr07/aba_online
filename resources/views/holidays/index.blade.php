@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">Holidays In <span class="text-danger">@if($year == 'All') 'All Years' @else {{$year}}  @endif</span></div>
                    <div class="page-title-subheading"></div>
                </div>
            </div>

            <!--actions' menu starts here -->
            @include('layouts.administrator.page-title-actions')
            <!--actions' menu ends here -->

        </div>
    </div>
    <div class="main-card mb-3 card">
        <div class="card-body">
            <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Holiday Name</th>
                    <th>Holiday Date</th>
                </tr>
                </thead>
                <tbody>

                <?php $n = 1; ?>
                @foreach($holidays as $holiday)
                <tr class='clickable-row' data-href="/holidays/{{ $holiday->id }}">
                    <td>{{ $n }}</td>
                    <td>{{ $holiday->holiday_year }}</td>
                    <td>{{ date('F', strtotime($holiday->holiday_date)) }}</td>
                    <td>{{ $holiday->name }}</td>
                    <td>{{ date('d-m-Y', strtotime($holiday->holiday_date)) }}</td>
                </tr>
                <?php $n++; ?>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Holiday Name</th>
                    <th>Holiday Date</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
