@extends('leave_reports.base_report')

@section('table')
    <table class="table table-hover table-striped table-bordered" border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th>Type Of Leave</th>
            <th class="text-center">Number Of Requests</th>
            <th class="text-center">Waiting SPV Approval</th>
            <th class="text-center">Waiting HRM Approval</th>
            <th class="text-center">Waiting MD Approval</th>
            <th class="text-center">Waiting Payment</th>
            <th class="text-center">Approved</th>
            <th class="text-center">Rejected</th>
        </tr>
        </thead>
        <tbody>
        <?php $a = $b = $c = $d = $e = $f = $g = 0; ?>
        @foreach($overviews as $key=>$overview)

            @if($type_of_leave == $key)
                <tr>
                    <td >{{$leave_types[$key]['name']}}</td>
                    <td class="text-center">{{$overview['number_of_requests']}}</td>
                    <td class="text-center">{{$overview['waiting_for_spv_approval']}}</td>
                    <td class="text-center">{{$overview['waiting_for_hrm_approval']}}</td>
                    <td class="text-center">{{$overview['waiting_for_md_approval']}}</td>
                    <td class="text-center">{{$overview['waiting_for_payment']}}</td>
                    <td class="text-center">{{$overview['approved']}}</td>
                    <td class="text-center">{{$overview['rejected']}}</td>
                </tr>

                <?php
                $a += $overview['number_of_requests'];
                $b += $overview['waiting_for_spv_approval'];
                $c += $overview['waiting_for_hrm_approval'];
                $d += $overview['waiting_for_md_approval'];
                $e += $overview['waiting_for_payment'];
                $f += $overview['approved'];
                $g += $overview['rejected'];
                ?>
            @endif

            @if($type_of_leave == 'All')
                <tr>
                    <td >{{$leave_types[$key]['name']}}</td>
                    <td class="text-center">{{$overview['number_of_requests']}}</td>
                    <td class="text-center">{{$overview['waiting_for_spv_approval']}}</td>
                    <td class="text-center">{{$overview['waiting_for_hrm_approval']}}</td>
                    <td class="text-center">{{$overview['waiting_for_md_approval']}}</td>
                    <td class="text-center">{{$overview['waiting_for_payment']}}</td>
                    <td class="text-center">{{$overview['approved']}}</td>
                    <td class="text-center">{{$overview['rejected']}}</td>
                </tr>

                <?php
                $a += $overview['number_of_requests'];
                $b += $overview['waiting_for_spv_approval'];
                $c += $overview['waiting_for_hrm_approval'];
                $d += $overview['waiting_for_md_approval'];
                $e += $overview['waiting_for_payment'];
                $f += $overview['approved'];
                $g += $overview['rejected'];
                ?>
            @endif

        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th>Total</th>
            <th class="text-center">{{$a}}</th>
            <th class="text-center">{{$b}}</th>
            <th class="text-center">{{$c}}</th>
            <th class="text-center">{{$d}}</th>
            <th class="text-center">{{$e}}</th>
            <th class="text-center">{{$f}}</th>
            <th class="text-center">{{$g}}</th>
        </tr>
        </tfoot>
    </table>
@endsection