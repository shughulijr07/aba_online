@extends('leave_reports.base_report')

@section('table')
    <table class="table table-hover table-striped table-bordered" border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th>No.</th>
            <th>Employee No.</th>
            <th>Employee Name</th>
            <th>Department</th>
            <th>Job Title</th>
            <th class="text-right">BCF <br>({{date('Y')-1}}) </th>
            <th class="text-right">Days Taken <br>({{date('Y')}} )</th>
            <th class="text-right">Accrued Days <br>({{date('d-m-Y')}} )</th>
            <th class="text-right">Leave balance <br>({{date('d-m-Y')}} )</th>
            <th class="text-right">Leave balance <br>({{'31-12-'.date('Y')}} )</th>
        </tr>
        </thead>
        <tbody>
        <?php $n = 1; ?>
        @foreach($leave_summaries as $leave_summary)
            <?php
                $date_parts = [];
                $day_of_employment = '';
                $month_of_employment = '';
                $year_of_employment = '';
                $starting_month = '';

                $staff = $leave_summary['staff-info'];
                $date_of_employment = $staff->date_of_employment;
                $date_parts = explode('/', $date_of_employment);

                if( count($date_parts) == 3){
                    $day_of_employment = $date_parts[0];
                    $month_of_employment = $date_parts[1];
                    $year_of_employment = $date_parts[2];
                }

                if( $year_of_employment != '' && $year_of_employment < date('Y')){
                    $starting_month = 1;
                }

                else if( $year_of_employment != '' && $year_of_employment == date('Y')){
                    $starting_month = intval($month_of_employment);

                }else{
                    $starting_month = 1; //we will change this logic letter if needed
                }

            ?>
            <tr>
            <td >{{$n}}</td>
            <td >{{($leave_summary['staff-info'])->staff_no}}</td>
            <td >{{ucwords(($leave_summary['staff-info'])->first_name.' '.($leave_summary['staff-info'])->last_name)}}</td>
            <td >{{ucwords(($leave_summary['staff-info'])->department->name)}}</td>
            <td >{{ucwords(($leave_summary['staff-info'])->jobTitle->title)}}</td>

            <td class="text-right">{{$leave_summary[$selected_leave_type]['entitled-days'] - 21}}</td>

            <td class="text-right">{{$leave_summary[$selected_leave_type]['days-taken']}}</td>
            <td class="text-right">{{ round((21/12)*(date('m')-$starting_month),0 ) }}</td>
            <td class="text-right">{{ round((21/12)*(date('m')-$starting_month),0) + ($leave_summary[$selected_leave_type]['entitled-days'] - 21) - $leave_summary[$selected_leave_type]['days-taken'] }}</td>
            <td class="text-right">{{$leave_summary[$selected_leave_type]['days-left']}}</td>
            </tr>
            <?php $n++; ?>
        @endforeach
        </tbody>
        <tfoot>
        </tfoot>
    </table>
@endsection