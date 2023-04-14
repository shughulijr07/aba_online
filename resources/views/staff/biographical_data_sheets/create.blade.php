@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <div class="text-primary">Staff Biographical Data</div>
                    <div class="page-title-subheading"></div>
                </div>
            </div>

            <!--actions' menu starts here -->
            <div class="page-title-actions">
                <button type="button" data-toggle="tooltip" title="Print" data-placement="bottom" class="btn-shadow mr-3 btn btn-info">
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
                                <a class="nav-link" href="{{url('/staff/'.$staff->id)}}">
                                    <i class="nav-link-icon pe-7s-note2"></i>
                                    <span>Staff Information</span>
                                </a>
                            </li>

                            @if($view_type == 'index')
                            @endif

                            @if($view_type == 'create')
                            @endif

                            @if($view_type == 'edit')
                                @include('actions.delete')
                            @endif

                            @if($view_type == 'show')
                                @include('actions.edit')
                                @include('actions.delete')
                            @endif

                        </ul>
                    </div>
                </div>
            </div>
            <!--actions' menu ends here -->
    
        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Biographical Data Form</h5>
                    <form action="/staff_biographical_data_sheets" method="POST" enctype="multipart/form-data">
                        @include('staff.biographical_data_sheets.form')
                        <button class="mt-2 btn btn-primary">Save Bio Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection