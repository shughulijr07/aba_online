@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">List Of Numbered Items</div>
                    <div class="page-title-subheading">
                        Below is a list of ITEMS whose numbering generated and controlled by system
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

                @foreach($numbered_items as $item)
                <tr class='clickable-row' data-href="/numbered_items/{{ $item->id }}">
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                </tr>
                @endforeach

                </tbody>
                <tfoot>
                <tr class="invisible">
                    <th>Id</th>
                    <th>Name</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
