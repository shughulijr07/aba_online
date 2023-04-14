@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">List Of Countries</div>
                    <div class="page-title-subheading">
                        Below is a list of all Countries covered by GS1 Tanzania
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
                    <th>Name</th>
                </tr>
                </thead>
                <tbody>

                @foreach($countries as $country)
                <tr class='clickable-row' data-href="/countries/{{ $country->id }}">
                    <td>{{ $country->id }}</td>
                    <td>{{ $country->name }}</td>
                </tr>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
