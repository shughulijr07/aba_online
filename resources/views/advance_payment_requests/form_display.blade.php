<!-- Header-->
<fieldset disabled>
    <legend class="text-danger"></legend>
    <div class="form-row">
        <div class="col-3">
            <div class="position-relative form-group">
                <label for="no" class="">
                    <span>Request No.</span>
                </label>
                <input type="text" class="form-control" value="{{$advance_payment_request->no}}" disabled>
            </div>
        </div>
        <div class="col-3">
            <div class="position-relative form-group">
                <label for="project_id" class="">
                    <span>Project Name</span>
                </label>
                <input type="text" class="form-control" value="{{$advance_payment_request->project->number}}" disabled>
            </div>
        </div>

        <div class="col-3" id="supervisor_div">
            <div class="position-relative form-group">
                <label for="responsible_spv" class="">
                    <span>Supervisor</span>
                </label>
                <input type="text" class="form-control" value="{{$supervisor_name}}" disabled>
            </div>
        </div>
        <div class="col-3">
            <div class="position-relative form-group">
                <label for="request_date" class="">
                    <span>Request Date</span>
                </label>
                <input type="text" class="form-control" value="{{$advance_payment_request->request_date}}" disabled>
            </div>
        </div>
        <div class="col-12" id="description_div">
            <div class="position-relative form-group">
                <label for="purpose" class="">
                    Purpose Of Payment
                </label>
                <textarea name="purpose" id="purpose" class="form-control" autocomplete="off" disabled>{{$advance_payment_request->purpose}}</textarea>
            </div>
        </div>

    </div>
</fieldset>
<!-- End Of Header -->

<!-- Lines -->
<fieldset>
    <legend class="text-secondary mt-5">Advance Payment Request Details</legend>
    <div class="row">
        <div class="col-md-12">
            <table style="width: 100%; font-size: 12px;" id="details_table" class="table table-hover table-striped table-bordered" >
                <thead>
                <tr class="font-weight-bold">
                    <th style="width: 5%;" class="font-weight-bold">#</th>
                    <th style="width: 15%;" class="font-weight-bold">Account Code</th>
                    <th style="width: 15%;" class="text-left font-weight-bold">Activity</th>
                    <th style="width: 35%;" class="text-left font-weight-bold">Description</th>
                    <th style="width: 10%;" class="text-right font-weight-bold">Rate</th>
                    <th style="width: 10%;" class="text-right font-weight-bold">Unit</th>
                    <th style="width: 10%;" class="text-right font-weight-bold">Total</th>
                </tr>
                </thead>

                <tbody id="details_table_body">
                @foreach($details as $detail)
                    <tr class="data-row">
                        <td class="details-no">{{$detail->no}}</td>
                        <td class="details-account">{{$detail->account_number}}</td>
                        <td class="details-activity">{{$detail->activity_code}}</td>
                        <td class="details-description">{{$detail->description}}</td>
                        <td class="text-right details-rate">{{number_format($detail->rate, 2, "." , ",")}}</td>
                        <td class="text-right details-unit">{{number_format($detail->unit, 2, "." , ",")}}</td>
                        <td class="text-right details-total">{{number_format($detail->line_total, 2, "." , ",")}}</td>
                    </tr>
                @endforeach
                </tbody>

                <tfoot>
                <tr>
                    <th colspan="6" class="text-right"><span>Total Request</span></th>
                    <th  class="text-right">{{number_format($advance_payment_request->total, 2, "." , ",")}}</th>
                </tr>
                </tfoot>

            </table>

            <div class="col-md-12 mt-3">
                <div class="position-relative form-group">
                    <input type="checkbox" name="terms[]" id="terms" value="yes" @if( in_array('yes',$terms )) checked @endif disabled>
                    <span class="text-primary">
                        I <span class="text-danger">{{$employee_name}}</span>  declare by accepting funds from SHDEPHA that I will retire these funds according to
                        SHDEPHA  policies and procedures with genuine receipts.
                    </span>
                </div>
            </div>


            @if( count($attachments) > 0)
                <div class="col-md-12 mt-2">
                    <div class="widget-content">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading">Attached Documents</div>
                                <div class="widget-subheading mt-2">
                                    <?php $n=1;?>
                                    @foreach($attachments as $attachment)
                                        <span class="mr-2">
                                            <a href="{{url($attachment)}}" target="_blank" class="mr-3 pt-2">
                                                <i class="pe-7s-exapnd2"></i> Attachment {{$n}}
                                            </a>
                                        </span>
                                        <?php $n++;?>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</fieldset>
<!-- End Of Lines -->



