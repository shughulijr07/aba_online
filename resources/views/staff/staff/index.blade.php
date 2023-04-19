@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">List Of ABA Alliance Staff</div>
                    <div class="page-title-subheading">
                        Below is a list of ABA Alliance Staff
                    </div>
                </div>
            </div>

            <!--actions' menu starts here -->
            <div class="page-title-actions">
                <button type="button" data-toggle="tooltip" title="Print" data-placement="bottom" class="btn-shadow mr-3 btn btn-info invisible">
                    <i class="lnr-printer"></i>
                </button>
                <div class="d-inline-block dropdown">
                    <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-tasks" aria-hidden="true"></i>
                        </span>
                        Actions
                    </button>
                    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('/'.$controller_name.'/create')}}">
                                    <i class="nav-link-icon fas fa-plus"></i>
                                    <span>New {{ucwords( str_ireplace('_', ' ', $model_name) )}}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('/staff/import_from_excel')}}">
                                    <i class="nav-link-icon fas fa-plus"></i>
                                    <span>Import From Excel</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--actions' menu ends here -->

        </div>
    </div>
    <div class="main-card mb-3 card">
        <div class="card-body">
            <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Staff No.</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Title</th>
                    <th>Email</th>
                    <th>Phone No.</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>

                <?php $n=1; ?>
                @foreach($staff as $staff)
                <tr class='clickable-row' data-href="/staff/{{ $staff->id }}">
                    <td>{{ $n }}</td>
                    <td>{{ $staff->staff_no }}</td>
                    <td>{{ $staff->first_name }}</td>
                    <td>{{ $staff->last_name }}</td>
                    <td>{{ $staff->gender }}</td>
                    <td>{{ $staff->jobTitle->title ?? null}}</td>
                    <td>{{ $staff->official_email }}</td>
                    <td>{{ $staff->phone_no }}</td>
                    <td>{{ $staff->staff_status }}</td>
                </tr>
                <?php $n++; ?>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>Id</th>
                    <th>Staff No.</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Title</th>
                    <th>Email</th>
                    <th>Phone No.</th>
                    <th>Status</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
