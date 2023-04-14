@extends('layouts.administrator.admin')


@section('content')

    <!-- title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div>
                    <div class="text-primary">{{ $staff->first_name.' '.$staff->last_name }} Biographical Data</div>
                    <div class="page-title-subheading">Below are information of the staff</div>
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
                        @can('view-menu','staff_menu')
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('/staff/'.$staff->id)}}">
                                <i class="nav-link-icon pe-7s-note2"></i>
                                <span>Staff Information</span>
                            </a>
                        </li>
                        @endcan

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


    <!-- data 1 -->
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Biographical Data Sheets Form</h5>
                    <form action="/staff_biographical_data_sheets" method="POST" enctype="multipart/form-data" disabled>
                        <fieldset disabled>
                        @include('staff.biographical_data_sheets.form')
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready( function(){
            $('#notifications-div').delay(5000).fadeOut('slow');
        });
    </script>

@endsection
