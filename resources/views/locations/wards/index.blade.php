@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">List Of Wards</div>
                    <div class="page-title-subheading">
                        Below is a list of all wards covered by GS1 Tanzania
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
                    <th>Ward Name</th>
                    <th>District Name</th>
                    <th>Region Name</th>
                    <th>Country Name</th>
                </tr>
                </thead>
                <tbody>

                @foreach($wards as $ward)

                    <tr class='clickable-row' data-href="/wards/{{ $ward->id }}">
                        <td>{{ $ward->id }}</td>
                        <td>{{ $ward->name }}</td>
                        <td>{{ $ward->district->name }}</td>
                        <td>{{ $ward->district->region->name }}</td>
                        <td>{{ $ward->district->region->country->name }}</td>
                    </tr>

                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>Id</th>
                    <th>Ward Name</th>
                    <th>District Name</th>
                    <th>Region Name</th>
                    <th>Country Name</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
