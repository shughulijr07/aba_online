<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('js/report.js') }}" defer></script>

    <!-- Fonts -->

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">

    <!-- Styles -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/time_sheet_statement.css') }}" rel="stylesheet">
</head>

<body>
<div id="report" class="ml-4 mr-4">
    <div class="toolbar hidden-print">
        <div class="text-right">
            <button id="printReport" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
            <button class="btn btn-info"><i class="fa fa-file-pdf-o"></i> Export as PDF</button>
        </div>
        <hr>
    </div>
    <div class="report overflow-auto pt-0" style="min-width: 18cm; min-height: 29.7cm; margin-left: 1cm; padding-top: 0.7cm;">
        <div style="min-width: 600px">
            <header>
                <div class="row pb-2">
                    <div class="col text-center" >
                        <a target="_blank" href="https://lobianijs.com">
                            <img  src="/images/tmarc_logo.png" data-holder-rendered="true" style="max-height: 150px; width: auto;"/>
                        </a>
                    </div>
                    <div class="col company-details" style="display: none">
                        <div>Plot No. 215/217 Block D</div>
                        <div>Kuringa Drive, Tegeta</div>
                        <div>P.O.Box 63266</div>
                        <div>Dar es Salaam, Tanzania</div>
                        <div>Tel : +255 22 2780870</div>
                        <div>Fax : +255 22 2781296</div>
                        <div>Email: info@tmarc.or.tz</div>
                    </div>
                </div>
            </header>
            <main>

                <div class="row pl-3 pr-3" >
                    <div class="col text-center pt-3 pb-2 " style="background-color: #ddd;">
                        <h3>EMPLOYEE TIME SHEET</h3>
                    </div>
                </div>

                <div class="row contacts mt-2" style="display: none;">
                    <div class="col report-to">
                        <div class="address">Leave No. : <strong>{{strtoupper($time_sheet->id)}}</strong></div>
                        <div>Employee Name : <strong>{{ucwords($time_sheet->staff->first_name.' '.$time_sheet->staff->last_name)}}</strong></div>
                        <div class="date">Department : <strong>{{ucwords($time_sheet->staff->department->name)}}</strong></div>
                        <div class="date">Designation : <strong>{{ucwords($time_sheet->staff->jobTitle->title)}}</strong></div>
                    </div>
                    <div class="col report-details">
                        <div class="date">Generated By : <strong>{{$generated_by}}</strong></div>
                        <div class="date">Generation Date : <strong>{{$generation_date}}</strong></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mt-3">
                        @include('time_sheets.statement_form')
                    </div>
                </div>

                <div class="row mb-5">
                    @if( $time_sheet->status == 50)
                        @if( isset($spv_approval->done_by) && isset($hrm_approval->done_by) )
                        <div class="col-4 approval-info">
                            <div class="text-secondary font-weight-bold">SUPERVISOR APPROVAL</div>
                            <div>Approved By : <span class="font-weight-bold">{{ucwords($spv->first_name.' '.$spv->last_name)}}</span></div>
                            <div>Department : <span class="font-weight-bold">{{ucwords($spv->department->name)}}</span></div>
                            <div>Designation : <span class="font-weight-bold">{{ucwords($spv->jobTitle->title)}}</span></div>
                            <div>Date Of Approval : <span class="font-weight-bold">{{date('d-m-Y H:m A',strtotime($spv_approval->created_at))}}</span></div>
                            @if($spv_approval->comments != '')<div>Comments : <span class="font-weight-bold">{{$spv_approval->comments}}</span></div>@endif
                        </div>

                        <div class="col-4 approval2-info">
                            <div class="text-secondary font-weight-bold">HUMAN RESOURCE MANAGER APPROVAL</div>
                            <div>Approved By : <span class="font-weight-bold">{{ucwords($hrm->first_name.' '.$hrm->last_name)}}</span></div>
                            <div>Department : <span class="font-weight-bold">{{ucwords($hrm->department->name)}}</span></div>
                            <div>Designation : <span class="font-weight-bold">{{ucwords($hrm->jobTitle->title)}}</span></div>
                            <div>Date Of Approval : <span class="font-weight-bold">{{date('d-m-Y H:m A',strtotime($hrm_approval->created_at))}}</span></div>
                            @if($hrm_approval->comments != '')<div>Comments : <span class="font-weight-bold">{{$hrm_approval->comments}}</span></div>@endif
                        </div>
                        @endif
                    @endif
                </div>
            </main>
            <footer>
            </footer>
        </div>
        <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
        <div></div>
    </div>
</div>
</body>
