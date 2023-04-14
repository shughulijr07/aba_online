@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">Number Series List</div>
                    <div class="page-title-subheading">
                        Below is a list of number series set to be used for some items in the system
                    </div>
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
                    <th>Id</th>
                    <th>Item Name</th>
                    <th>Abbreviation</th>
                    <th>Include Year</th>
                    <th>Starting Number</th>
                    <th>Last Number Used</th>
                </tr>
                </thead>
                <tbody>

                @foreach($number_series as $no_series)
                <tr class='clickable-row' data-href="/number_series/{{ $no_series->id }}">
                    <td>{{ $no_series->id }}</td>
                    <td>{{ $no_series->numbered_item->name }}</td>
                    <td>{{ $no_series->abbreviation }}</td>
                    <td>{{ $no_series->include_year }}</td>
                    <td>{{ $no_series->starting_no }}</td>
                    <td>{{ $no_series->last_no_used }}</td>
                </tr>
                @endforeach

                </tbody>
                <tfoot>
                <tr class="invisible">
                    <th>Id</th>
                    <th>Item Name</th>
                    <th>Abbreviation</th>
                    <th>Include Year</th>
                    <th>Starting Number</th>
                    <th>Last Number Used</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
