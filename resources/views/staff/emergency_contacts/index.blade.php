@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">List Of {{ucwords($staff->first_name.' '.$staff->last_name)}}'s Emergency Contacts</div>
                    <div class="page-title-subheading">
                        Below is a list of Staff's Emergency Contacts
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
                    <th>Name</th>
                    <th>Relationship</th>
                    <th>Physical Address</th>
                    <th>Email</th>
                    <th>Phone</th>
                </tr>
                </thead>
                <tbody>

                <?php $n=1; ?>
                @foreach($staff_emergency_contacts as $emergency_contact)
                <tr class='clickable-row' data-href="/staff_emergency_contacts/{{ $emergency_contact->id }}">
                    <td>{{ $n }}</td>
                    <td>{{ $emergency_contact->full_name }}</td>
                    <td>{{ $emergency_contact->relationship}}</td>
                    <td>{{ $emergency_contact->physical_address }}</td>
                    <td>{{ $emergency_contact->email }}</td>
                    <td>{{ $emergency_contact->cell_phone }}</td>
                </tr>
                <?php $n++; ?>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Relationship</th>
                    <th>Physical Address</th>
                    <th>Email</th>
                    <th>Phone</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
