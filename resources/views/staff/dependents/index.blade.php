@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">List Of {{ucwords($staff->first_name.' '.$staff->last_name)}}'s Dependents</div>
                    <div class="page-title-subheading">
                        Below is a list of Staff Dependents
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
                    <th>#</th>
                    <th>Name Of Dependent</th>
                    <th>Date Of Birth</th>
                    <th>Sex</th>
                    <th>Relationship</th>
                    <th>Birth Certificate No.</th>
                    <th>To be on medical</th>
                </tr>
                </thead>
                <tbody>

                <?php $n=1; ?>
                @foreach($dependents as $dependent)
                <tr class='clickable-row' data-href="/staff_dependents/{{ $dependent->id }}">
                    <td>{{ $n }}</td>
                    <td>{{ $dependent->full_name }}</td>
                    <td>{{ date('d-m-Y',strtotime($dependent->date_of_birth)) }}</td>
                    <td>{{ $dependent->gender }}</td>
                    <td>{{ $dependent->relationship}}</td>
                    <td>{{ $dependent->birth_certificate_no }}</td>
                    <td>{{ $dependent->to_be_on_medical }}</td>
                </tr>
                <?php $n++; ?>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>#</th>
                    <th>Name Of Dependent</th>
                    <th>Date Of Birth</th>
                    <th>Sex</th>
                    <th>Relationship</th>
                    <th>Birth Certificate No.</th>
                    <th>To be on medical</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
