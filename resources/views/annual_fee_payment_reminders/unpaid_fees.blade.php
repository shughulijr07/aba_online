@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">Annual Fee Payment Reminder List For The Month <span class="orange-text">{{date('F, Y')}}</span></div>
                    <div class="page-title-subheading">
                        Use the list below to remind GS1 Tanzania Members about annual fee payment
                    </div>
                </div>
            </div>

            <!--actions' menu starts here -->
            @include('layouts.administrator.page-title-actions')
            <!--actions' menu ends here -->

        </div>
    </div>

    <!-- not reminded starts here -->
    <div class="row">
        <div class="col-sm-7">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Members Who Have Not Paid Within 30 Days From Their Payment Deadline &<span class="text-danger"> Are Not Yet Reminded ({{count($unattended_reminders)}})</span></h5>
                    <table style="width: 100%;" id="not-reminded-table"  class="table table-hover table-striped table-bordered ">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Company Name</th>
                            <th>Fee Expiring Date</th>
                            <th>Assigned To</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php $n=1; ?>
                        @foreach($unattended_reminders as $reminder)
                            <tr id="{{$reminder->id}}">
                                <td>{{ $n }}</td>
                                <td class="member-name">{{ $reminder->member->company_name }}</td>
                                <?php $separated_date = explode('/', $reminder->member->registration_date) ?>
                                <td>{{ $separated_date[0].'-'.$separated_date[1].'-'.date('Y') }}</td>
                                <td class="staff-name">{{ ucwords($reminder->staff->first_name.' '.$reminder->staff->last_name )}}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm reminder-btn" role="button" aria-pressed="true" id="remind_btn_{{$reminder->id}}">Remind</button>
                                    @if(auth()->user()->role_id !=3)
                                        <button class="btn btn-danger btn-sm config-btn" role="button" aria-pressed="true" id="config_btn_{{$reminder->id}}">
                                            <i class="pe-7s-config"> </i>
                                        </button>
                                    @endif
                                </td>

                                <!-- office number -->
                                <td style="display: none;" class="membership-no">{{ $reminder->member->membership_no }}</td>
                                <td style="display: none;" class="registration-date">{{ $reminder->member->registration_date }}</td>
                                <td style="display: none;" class="amount">{{ number_format($reminder->annual_fee_amount) }}</td>
                                <td style="display: none;" class="email">{{ $reminder->member->email }}</td>
                                <td style="display: none;" class="fax-no">{{ $reminder->member->fax_no }}</td>
                                <td style="display: none;" class="office-no1">{{ $reminder->member->office_phone_no1 }}</td>
                                <td style="display: none;" class="office-no2">{{ $reminder->member->office_phone_no2 }}</td>
                                <td style="display: none;" class="contact-person-no">{{ $reminder->member->contact_person_phone_no }}</td>
                                <td style="display: none;" class="first-director-no">{{ $reminder->member->director1_phone_no }}</td>
                                <td style="display: none;" class="second-director-no">{{ $reminder->member->director2_phone_no }}</td>
                                <td style="display: none;" class="staff-id">{{ $reminder->responsible_staff_id }}</td>
                            </tr>
                            <?php $n++; ?>
                        @endforeach

                        </tbody>
                        <tfoot>
                        <tr>
                            <th>No.</th>
                            <th>Company Name</th>
                            <th>Office No.1</th>
                            <th>Assigned To</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-sm-5">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <span id="title1">Reminding Member Instructions</span>
                        <span class="text-danger" id="title2"></span>
                    </h5>
                    <div id="instructions">
                        <p>
                            To remind GS1 Tanzania Member about Annual Fee Payment, click the remind button from on the action column in the row of the member
                            you wan't to remind.
                        </p>
                        <p>
                            Member's contact information such as phone numbers and email will be displayed.
                            Contact the Member using this information to remind him/her to pay Annual Fee.
                        </p>
                        <p> Once you have contacted
                            the Member, you will place "Mark As Reminded" button to remove member from the list of Members who have
                            not been Reminded, you can also fill in the date which member has promised to pay.
                        </p>
                    </div>

                    <table style="width: 100%; display: none;" class="table table-hover table-striped table-bordered" id="member_info">
                        <tbody>
                        <tr>
                            <td><i class="pe-7s-ribbon mr-1"> </i>Registration No</td>
                            <td id="membership_no" class="text-primary"></td>
                        </tr>
                        <tr>
                            <td><i class="pe-7s-date mr-1"> </i>Date Of Registration</td>
                            <td id="registration_date" class="text-primary"></td>
                        </tr>
                        <tr>
                            <td><i class="pe-7s-cash mr-1"> </i>Amount</td>
                            <td id="amount" class="text-primary"></td>
                        </tr>
                        <tr>
                            <td><i class="pe-7s-mail mr-1"> </i>Email</td>
                            <td id="email" class="text-primary"></td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-fw mr-1" aria-hidden="true" title="Copy to use fax"></i>Fax</td>
                            <td id="fax_no" class="text-primary"></td>
                        </tr>
                        <tr>
                            <td><i class="lnr-phone mr-1"> </i>Office No.1</td>
                            <td id="office_no1" class="text-primary"></td>
                        </tr>
                        <tr>
                            <td><i class="lnr-phone mr-1"> </i>Office No.2</td>
                            <td id="office_no2" class="text-primary"></td>
                        </tr>
                        <tr>
                            <td><i class="lnr-smartphone mr-1"> </i>Contact Person No.</td>
                            <td id="contact_person_no" class="text-primary"></td>
                        </tr>
                        <tr>
                            <td><i class="lnr-smartphone mr-1"> </i>First Director No.</td>
                            <td id="first_director_no" class="text-primary"></td>
                        </tr>
                        <tr>
                            <td><i class="lnr-smartphone mr-1"> </i>Second Director No.</td>
                            <td id="second_director_no" class="text-primary"></td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="col-md-12 pt-3 pb-4 mb-3" style="border:1px solid #EFEFEF; display: none;" id="marking_form_div">
                        <form action="/annual_fee_payment_reminders" method="POST" enctype="multipart/form-data" id="marking_form">
                            @csrf
                            {{ csrf_field() }}
                            <div class="position-relative form-group">
                                <label for="date_promised_to_pay" class="">
                                    <span>Date Promised To Pay</span>
                                </label>
                                <input name="date_promised_to_pay" id="date_promised_to_pay" type="text" class="form-control" value="{{str_replace('-','/',$date_promised_to_pay)}}" data-toggle="datepicker-year" autocomplete="off">
                                <input name="reason_for_delaying_payment" id="reason_for_delaying_payment" value="" style="display: none;" autocomplete="off">
                                <input name="reminder_id" id="reminder_id" value="" style="display: none;" autocomplete="off">
                                <input name="reminder_type" id="reminder_type" value="unpaid_fees" style="display: none;" autocomplete="off">
                            </div>
                            <div id="">
                                <button class="mt-2 btn btn-primary col-md-12">Mark As Reminded</button>
                            </div>
                        </form>
                    </div>

                    <!-- for non-membership officers only | change annual officer who is assigned to a reminder-->
                    @if(auth()->user()->role_id != 3)
                    <div class="col-md-12 pt-3 pb-4" style="border:1px solid #EFEFEF; display: none;" id="change_officer_div">
                        <div class="position-relative form-group">
                            <label for="responsible_staff_id" class="">
                                <span>Select Responsible Officer</span>
                            </label>
                            <select name="responsible_staff_id" id="responsible_staff_id" class="form-control">
                                @foreach($officers as $officer)
                                    <option value="{{$officer->id}}">
                                        {{ucwords($officer->first_name.' '.$officer->last_name)}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button class="mt-2 btn btn-danger col-md-12" id="change_officer">Change Officer</button>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <!-- not reminded ends here -->


    <!-- have been reminded starts here -->
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title mb-3">Members Who Have Not Paid Within 30 Days From Their Payment Deadline & <span class="text-danger">Have Already Been Reminded ({{count($attended_reminders)}})</span></h5>
            <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Membership No.</th>
                    <th>Company Name</th>
                    <th>Office No.1</th>
                    <th>Contact Person No.</th>
                    <th>Assigned To</th>
                </tr>
                </thead>
                <tbody>

                <?php $n=1; ?>
                @foreach($attended_reminders as $reminder)
                    <tr class='clickable-row' data-href="/members/{{ $reminder->member->id }}">
                        <td>{{ $n }}</td>
                        <td>{{ $reminder->member->membership_no }}</td>
                        <td>{{ $reminder->member->company_name }}</td>
                        <td>{{ $reminder->member->office_phone_no1 }}</td>
                        <td>{{ $reminder->member->contact_person_phone_no }}</td>
                        <td>{{ ucwords($reminder->staff->first_name.' '.$reminder->staff->last_name )}}</td>
                    </tr>
                    <?php $n++; ?>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Membership No.</th>
                    <th>Company Name</th>
                    <th>Office No.1</th>
                    <th>Contact Person No.</th>
                    <th>Assigned To</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- have been reminded ends here -->


    <script type="text/javascript">

        $(document).ready(function(){
            $('#not-reminded-table').dataTable({searching: false, paging: false, info: false});
        });

        $('.reminder-btn').on('click', function(){
            //get parent element of this row
            var btn_id = $(this).prop('id');
            var reminder_id = (btn_id.split('_'))[2];
            var selected_row_id = '#'+reminder_id;
            var selected_row = $(selected_row_id);

            //get all data from child elements
            var member_name = selected_row.find('.member-name').text();
            var membership_no = selected_row.find('.membership-no').text();
            var registration_date = selected_row.find('.registration-date').text();
            var amount = selected_row.find('.amount').text();
            var email = selected_row.find('.email').text();
            var fax_no = selected_row.find('.fax-no').text();
            var office_no1 = selected_row.find('.office-no1').text();
            var office_no2 = selected_row.find('.office-no2').text();
            var contact_person_no = selected_row.find('.contact-person-no').text();
            var first_director_no = selected_row.find('.first-director-no').text();
            var responsible_staff_id = selected_row.find('.staff-id').text();

            //add details to reminding panel
            $("#reminder_id").val(reminder_id);
            $("#membership_no").text(membership_no);
            $("#registration_date").text(registration_date);
            $("#amount").text(amount + ' Tzs');
            $("#email").text(email);
            $("#fax_no").text(fax_no);
            $("#office_no1").text(office_no1);
            $("#office_no2").text(office_no2);
            $("#contact_person_no").text(contact_person_no);
            $("#first_director_no").text(first_director_no);
            $("#responsible_staff_id").val(responsible_staff_id);

            //chang title and the right card
            $("#title1").text('Remind ');
            $("#title2").text(member_name);
            $("#instructions").hide();
            $("#change_officer_div").hide();
            $("#member_info").show('slow');
            $("#marking_form_div").show('slow');

        });

        $('.config-btn').on('click', function(){
            //get parent element of this row
            var btn_id = $(this).prop('id');
            var reminder_id = (btn_id.split('_'))[2];
            var selected_row_id = '#'+reminder_id;
            var selected_row = $(selected_row_id);

            //get data from child elements
            var responsible_staff_id = selected_row.find('.staff-id').text();
            var member_name = selected_row.find('.member-name').text();

            //add details to reminding panel
            $("#reminder_id").val(reminder_id);
            $("#responsible_staff_id").val(responsible_staff_id);

            //chang title and the right card
            $("#title1").text('Change Officer Responsible For ');
            $("#title2").text(member_name);
            $("#instructions").hide();
            $("#member_info").hide();
            $("#marking_form_div").hide();
            $("#change_officer_div").show('slow');

        });

        $('#change_officer').on('click',function(){

            var responsible_staff_id = $('#responsible_staff_id').val();
            var reminder_id = $("#reminder_id").val();
            var _token = $('input[name="_token"]').val();

            ajax_change_officer(reminder_id,responsible_staff_id,_token);

        });

        function ajax_change_officer(reminder_id,responsible_staff_id,_token){
            Swal.fire({
                title: 'Are you sure? Yo want to change the officer for this reminder ? ',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#213368',
                cancelButtonColor: '#F15A24',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {

                //ajax request
                $.ajax({
                    url:"{{ route('reminder_officer.ajax_update') }}",
                    method:"POST",
                    data:{reminder_id:reminder_id,responsible_staff_id:responsible_staff_id,_token:_token},
                    success:function(data)
                    {
                        //add the list to selection option
                        //console.log(data);
                        data = JSON.parse(data);

                        if (data.length > 0) {
                            ajax_change_officer_response(data);
                        }else{

                        }
                    }
                });

            }
        })
        }

        function ajax_change_officer_response(data){


            if (data =='success') {

                $(document).off(".firstCall");//stop ajaxStart call
                var staff_id = $('#responsible_staff_id').val();
                var staff_name = $('#responsible_staff_id option:selected').text();
                var reminder_id = $("#reminder_id").val();

                //get selected row
                var selected_row = $('#'+reminder_id);

                //update staff name and id
                selected_row.find('.staff-name').html(staff_name);
                selected_row.find('.staff-id').html(staff_id);

                sweet_alert_success('Officer Changed Successfully');

                //setTimeout(function(){ //window.location.reload(); }, 1000);

            }

            else{
                sweet_alert_error('Failed to change officer, please try again');
            }

        }



        /******************* sweet alerts *******************************/

        function sweet_alert_success(success_message){
            Swal.fire({
                type: "success",
                text: success_message,
                confirmButtonColor: '#213368',
            })
        }


        function sweet_alert_error(error_message){
            Swal.fire({
                type: "error",
                text: error_message,
                confirmButtonColor: '#213368',
            })
        }

        function sweet_alert_warning(warning_message){
            Swal.fire({
                type: "warning",
                text: warning_message,
                confirmButtonColor: '#213368',
            })
        }


    </script>
@endsection
