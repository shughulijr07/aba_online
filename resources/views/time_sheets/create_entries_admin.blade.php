@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <div class="text-primary">Timesheet For The Month Of <strong><span class="text-danger">{{$months[$time_sheet->month]}}</span></strong></div>
                    <div class="page-title-subheading">
                        Please Fill The Form Below & Submit It
                    </div>
                </div>
            </div>

            <!--actions' menu starts here -->
            <!--actions' menu ends here -->
    
        </div>
    </div>


    <form action="/create_time_sheet_entries_admin" method="POST" enctype="multipart/form-data" id="timesheet_form">
        @csrf
        {{ csrf_field() }}

        <div class="container" style="min-width: 90%">
        @include('time_sheets.time_sheet_form')
        </div>

    </form>

@endsection