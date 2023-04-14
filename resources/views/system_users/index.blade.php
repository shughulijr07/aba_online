@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">List Of User</div>
                    <div class="page-title-subheading">
                        Below is a list of User
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
                            @include('actions.add')
                        </ul>
                    </div>
                </div>
            </div>
            <!--actions' menu ends here -->

        </div>
    </div>


    <!-- Data Table -->
    <div>
        <div class="main-card mb-3 card">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-bold">
                    <i class="header-icon lnr-users mr-3 text-primary opacity-6"> </i>
                    <span class="text-primary">System Users</span>
                </div>
                <div class="btn-actions-pane-right actions-icon-btn">
                    <div>
                        <button type="button" class="btn mr-2 mb-2 mt-2 btn-primary" data-toggle="modal" data-target="#filtersModal">
                            Show Filters
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <table style="width: 100%;" id="systemUsersTable" class="table table-hover table-striped table-bordered table-responsive-sm table-sm">
                    <thead>
                    <tr>
                        <!--<th>#</th>-->
                        <th>Id</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Company</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>

    {{ csrf_field() }}

@endsection
