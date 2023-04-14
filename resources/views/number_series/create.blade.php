@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <div class="text-primary">Add New Number Series</div>
                    <div class="page-title-subheading">
                        Add new Number Series for an Item by completing the form below
                    </div>
                </div>
            </div>

            <!--actions' menu starts here -->
            @include('layouts.administrator.page-title-actions')
            <!--actions' menu ends here -->

        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
            <div class="row">
                <div class="col-md-6">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <h5 class="card-title">Number Series Form</h5>
                            <form action="/number_series" method="POST">
                                @include('number_series.form')
                                <button class="mt-2 btn btn-primary">Add Number Series</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <h5 class="card-title">Existing Number Series</h5>
                            <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Item Name</th>
                                    <th>Abbreviation</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($existing_number_series as $no_series)
                                    <tr class='clickable-row' data-href="/number_series/{{$no_series->id}}">
                                        <td>{{ $no_series->id }}</td>
                                        <td>{{ $no_series->numbered_item->name }}</td>
                                        <td>{{ $no_series->abbreviation }}</td>
                                    </tr>
                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr class="invisible">
                                    <th>Id</th>
                                    <th>Item Name</th>
                                    <th>Abbreviation</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
