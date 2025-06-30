@extends('finance.layouts.app')

@section('content')
    <section class="content">
        <div class="row mb-2 ml-2">
            <h1 class="m-0 text-dark">Online Payments</h1>
        </div>
        <div class="row">
            <div class="col-3">
            </div>
            <div class="col-2">
                <select class="form-control select2bs4" name="status" id="ol_filter_status">
                    <option value="all">ALL</option>
                    <option value="0">PENDING</option>
                    <option value="1">APPROVED</option>
                    <option value="2">DISAPPROVED</option>
                    <option value="5">COMPLETED</option>
                </select>
            </div>
            <div class="col-md-2">
                <select id="ol_syid" class="select2bs4 filters" style="width: 100%;">
                    <option value="0">SCHOOL YEAR</option>
                    @foreach (db::table('sy')->orderBy('sydesc')->get() as $sy)
                        @if ($sy->isactive == 1)
                            <option value="{{ $sy->id }}" selected>{{ $sy->sydesc }}</option>
                        @else
                            <option value="{{ $sy->id }}">{{ $sy->sydesc }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            {{-- <div class="col-md-2">
                <select id="ol_semid" class="select2bs4 filters" style="width: 100%;">
                <option value="0">SEMESTER</option>
                @foreach (db::table('semester')->where('deleted', 0)->get() as $sem)
                    @if ($sem->isactive == 1)
                    <option value="{{$sem->id}}" selected>{{$sem->semester}}</option>
                    @else
                    <option value="{{$sem->id}}">{{$sem->semester}}</option>
                    @endif
                @endforeach
                </select>
            </div> --}}
            <div class="col-3">
                <div class="input-group mb-3">
                    <input id="ol_search" type="text" class="form-control filters" placeholder="Search"
                        onkeyup="this.value = this.value.toUpperCase();">
                    <div class="input-group-append">
                        <span class="input-group-text ol_filter"><i class="fas fa-search"></i></span>
                    </div>

                </div>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-primary btn-block" id="ol_print"><i class="fas fa-print"></i>
                    Print all</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-file-invoice-dollar mr-1"></i> Pending Payments</h3>
                        <button type="button" class="btn btn-danger btn-sm float-right" id="ol_print_pending"><i
                                class="fas fa-file-pdf"></i> Export</button>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-sm text-sm" wdith="100%">
                            <thead class="">
                                <th>DATE</th>
                                <th>STUDENT NAME</th>
                                <th>ACADEMIC LEVEL</th>
                                <th>PAYMENT TYPE</th>
                                <th>REFERENCE No.</th>
                                <th>AMOUNT</th>
                                <th>STATUS</th>
                            </thead>
                            <tbody id="item-list">

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4">

                                    </td>
                                    <td> <span> TOTAL : </span></td>
                                    <td id="totalAmount1" data-value="0" class="text-right text-bold">0.00</td>
                                    <td></td>
                                </tr>

                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        {{-- Processed Payments --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-file-invoice-dollar mr-1"></i> Processed Payments</h3>
                        <button type="button" class="btn btn-danger btn-sm float-right" id="ol_print_processed"><i
                                class="fas fa-file-pdf"></i> Export</button>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-sm text-sm">
                            <thead class="">
                                <th>DATE</th>
                                <th>STUDENT NAME</th>
                                <th>ACADEMIC LEVEL</th>
                                <th>PAYMENT TYPE</th>
                                <th>REFERENCE No.</th>
                                <th>AMOUNT</th>
                                <th>STATUS</th>
                            </thead>
                            <tbody id="item-list-processed">

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4">

                                    </td>
                                    <td> <span> TOTAL : </span></td>
                                    <td id="totalAmount2" data-value="0" class="text-right text-bold">0.00</td>
                                    <td></td>
                                </tr>

                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </section>
@endsection
@section('modal')
    <div class="modal fade show" id="modal-approve" aria-modal="true" style="display: none; margin-top: -25px;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gray">
                    <h4 class="modal-title">
                        <span id="_status"></span>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="font-weight-bold">Students Name</label>
                            <input type="text" class="form-control" id="studentname" value="Cagasan, Bernadette"
                                readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="font-weight-bold">Grade Level</label>
                            <input type="text" class="form-control" id="levelname" value="1st Year College" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="font-weight-bold">Section</label>
                            <input type="text" class="form-control" id="section" value="AB-AI 1a" readonly>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label class="font-weight-bold">Transaction Date <i
                                    class="fas fa-edit text-primary e-transdate" style="cursor:pointer;"></i></label>
                            <input type="text" class="form-control" id="transdate" value="11 - 20 - 2024" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="font-weight-bold">Payment Type <i class="fas fa-edit text-primary e-paymenttype"
                                    style="cursor:pointer;"></i></label>
                            <input type="text" class="form-control" id="paymenttype" disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="font-weight-bold">Reference No. <i class="fas fa-edit text-primary e-refnum"
                                    style="cursor:pointer;"></i></label>
                            <input type="text" class="form-control" id="refnum" disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="font-weight-bold">Amount <i class="fas fa-edit text-primary e-amount"
                                    style="cursor:pointer;"></i></label>
                            <input type="text" class="form-control" id="amount" disabled>
                        </div>
                    </div>
                    <hr>

                    <div class="row text-center">
                        <div class="col-md-12">
                            <img id="picurl" src="{{ asset('') }}" class="img-fluid rounded shadow-sm"
                                style="max-width: 35%; ">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div>
                        <button id="btnapprove" class="btn btn-success mr-2" data-toggle="tooltip" title="Approve"
                            data-id="0">Approve</button>
                        <button id="btndisapprove" class="btn btn-danger mr-2" data-toggle="tooltip"
                            title="Disapprove">Disapprove</button>
                        <button id="btnprocesspayment" class="btn btn-primary mr-2" data-toggle="modal"
                            data-target="#processPaymentsModal" title="Process Payment" data-stud-id = "0"
                            disabled>Process
                            Payment</button>
                    </div>
                    <div>
                        {{-- <button id="is_print" type="button" class="btn btn-primary" data-dismiss="modal"><i
                                class="fas fa-print"></i> Print</button> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade show" id="amountchange" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Change Amount</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <input type="number" class="form-control" name="" id="txtamount" placeholder="0.00"
                            onkeyup="this.value = this.value.toUpperCase();">
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    {{--  --}}

                    <div class="float-left">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    <div class="float-right">
                        <button id="savechangeamount" type="button" class="btn btn-primary" style="width: 90px"><i
                                class="fas fa-save"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>

    <div class="modal fade show" id="datechange" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Change Transaction Date</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <input type="date" class="form-control" name="" id="txtdate"
                            onkeyup="this.value = this.value.toUpperCase();">
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    {{--  --}}

                    <div class="float-left">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    <div class="float-right">
                        <button id="savechangedate" type="button" class="btn btn-primary" style="width: 90px"><i
                                class="fas fa-save"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>

    <div class="modal fade show" id="paytypechange" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Change Payment Type</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <select class="form-control select2bs4" name="" id="txtpaytype">
                            @foreach (App\FinanceModel::paymenttype() as $paytype)
                                <option value="{{ $paytype->id }}">{{ $paytype->description }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    {{--  --}}

                    <div class="float-left">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    <div class="float-right">
                        <button id="savechangepaytype" type="button" class="btn btn-primary" style="width: 90px"><i
                                class="fas fa-save"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>

    <div class="modal fade show" id="refnumchange" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Change Reference Number</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <input type="text" name="" id="txtrefnum" class="form-control"
                            placeholder="Reference Number">
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    {{--  --}}

                    <div class="float-left">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    <div class="float-right">
                        <button id="savechangerefnum" type="button" class="btn btn-primary" style="width: 90px"><i
                                class="fas fa-save"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>

    <div class="modal fade show" id="modal-remarks" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">Remarks - Disapprove</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row"></div>
                    <div class="col-12">
                        <div class="form-group">
                            <textarea id="txtremarks" class="form-control" placeholder="Remarks"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    {{--  --}}

                    <div class="float-left">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    <div class="float-right">
                        <button id="saveDisapprove" type="button" class="btn btn-danger" style="width: 190px"
                            disabled=""><i class="fas fa-thumbs-down"></i> Disapprove</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>

    <!-- Process Payments Modal -->
    <div class="modal fade" id="processPaymentsModal" tabindex="-1" role="dialog"
        aria-labelledby="processPaymentsLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-gray">
                    <h5 class="modal-title" id="processPaymentsLabel">Process Payments</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Student Name</label>
                            <input type="text" class="form-control" id="studentName" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>Grade Level</label>
                            <input type="text" class="form-control" id="gradeLevel" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>Section</label>
                            <input type="text" class="form-control" id="sectionName" readonly>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label>Payment Type</label>
                            <select class="form-control" id="paymentType" disabled>
                                <option>GCASH</option>
                                <option>Bank Transfer</option>
                                <option>Cash</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Amount</label>
                            <input type="text" class="form-control" id="paymentAmount" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>Receipt No.</label>
                            <input type="text" class="form-control" id="paymentRefNum" value="OP :">

                        </div>
                        <div class="col-md-3">
                            <label>Date</label>
                            <input type="text" class="form-control" id="paymentDate" readonly>
                        </div>
                    </div>


                    <div class="row mt-4">
                        <div class="col-md-6" style="overflow-y: scroll; height: 700px;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="position: sticky; top: 0; background-color: white;">Classification</th>
                                        <th style="position: sticky; top: 0; background-color: white;">Balance</th>
                                    </tr>
                                </thead>
                                <tbody id="balanceTableBody">
                                    <tr>
                                        <td>Tuition</td>
                                        <td>9,852.00</td>
                                    </tr>
                                    <tr>
                                        <td>Miscellaneous</td>
                                        <td>6,520.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6" style="overflow-y: scroll; height: 700px;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="2">
                                            Amount to Process (<span id="amountToProcess">2,150.00</span>)
                                        </th>
                                    </tr>
                                </thead>

                                <tbody id="amountTableBody">
                                    <!-- Dynamic rows will be appended here -->
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th style="text-align: right">Total Selected:</th>
                                        <td><span id="totalAmountSelected">₱0.00</span></td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: right">Remaining:</th>
                                        <td><span id="remainingAmount">₱0.00</span></td>
                                    </tr>
                                </tfoot>
                            </table>

                            <p class="text-muted" style="color: #007bff;"><small>Note: This transaction does not provide
                                    OR. Please proceed to Cashier's Portal.</small></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="postPayment" disabled>POST</button>
                    <button type="button" class="btn btn-primary d-none" id="btnPrintReceipt">Print Acknowledgement
                        Receipt</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">CANCEL</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        var olpaycounter;

        var totalamounOP = 0;


        $(document).ready(function() {

            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });
            onlinepaymentlist();

            function onlinepaymentlist(action = "") {
                var syid = $('#ol_syid').val();
                var semid = $('#ol_semid').val();
                var filter = $('#ol_search').val();
                var status = $('#ol_filter_status').val()

                $.ajax({
                    url: "{{ route('onlinepaymentlist') }}",
                    method: 'GET',
                    data: {
                        syid: syid,
                        semid: semid,
                        filter: filter,
                        status: status,
                        action: action
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#item-list').html(data.list);

                        var total = 0;
                        $('#item-list tr').each(function() {
                            var status = $(this).find('td:last-child').text().trim();
                            if (status === 'COMPLETED') {
                                $(this).hide();
                            }
                            if (status != 'COMPLETED') {
                                var amount = $(this).find('td:nth-last-child(2)').text().trim()
                                    .replace('₱', '').replace(',', '');
                                console.log('amount..', amount);
                                total += parseFloat(amount);
                            }

                        });

                        $('#totalAmount1').text('₱' + total.toLocaleString());

                    }
                });

                $.ajax({
                    url: "{{ route('onlinepaymentlist') }}",
                    method: 'GET',
                    data: {
                        syid: syid,
                        semid: semid,
                        filter: filter,
                        status: 5,
                        action: action
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#item-list-processed').html(data.list);

                        var total = 0;
                        $('#item-list-processed tr').each(function() {
                            var amount = $(this).find('td:nth-last-child(2)').text().trim()
                                .replace('₱', '').replace(',', '');
                            console.log('amount..', amount);
                            total += parseFloat(amount);

                        });

                        $('#totalAmount2').text('₱' + total.toLocaleString());

                    }
                });
            }

            $(document).on('click', '#item-list tr', function() {
                var dataid = $(this).attr('data-id');
                $('#chknodp').prop('checked', false);

                $.ajax({
                    url: "{{ route('paydata') }}",
                    method: 'GET',
                    data: {
                        dataid: dataid
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data, 'AJAX Response');

                        let status = '';

                        if (data.isapproved == 1) {
                            status = 'APPROVED';
                        } else if (data.isapproved == 2) {
                            status = 'DISAPPROVED';
                        } else if (data.isapproved == 0) {
                            status = 'PENDING';
                        } else if (data.isapproved == 5) {
                            status = 'COMPLETED';
                        }

                        // if (status == 'PENDING') {
                        //     $('.payment_control').show();
                        //     $('#btnapprove, #btndisapprove').prop('disabled',
                        //         false);
                        // } else {
                        //     $('.payment_control').hide();
                        // }

                        if (data.isapproved == 1) {
                            $('#btnapprove, #btndisapprove').prop('disabled', true);
                            $('#btnprocesspayment').prop('disabled', false).removeClass(
                                'btn-secondary').addClass(
                                'btn-primary');
                        } else {
                            $('#btnapprove, #btndisapprove').prop('disabled', false);
                            $('#btnprocesspayment').prop('disabled',
                                true);
                        }

                        // changeButton(status);

                        // Populate fields
                        $('#_status').text(data.studname);
                        $('#studentname').val(data.studname);
                        $('#contactno').text(data.contactno);
                        $('#levelname').val(data.levelname);
                        $('#section').val(data.section);
                        $('#paymenttype').val(data.paymenttype);
                        $('#amount').val(data.amount);
                        $('#picurl').attr('src', data.picurl);
                        $('#btnapprove').attr('data-id', data.id).attr('data-stud-id', data
                            .studid);
                        $('#btndisapprove').attr('data-id', data.id);
                        $('#btnprocesspayment').attr('data-id', dataid).attr('data-stud-id',
                            data.studid);
                        $('#btnPrintReceipt').attr('data-id', dataid).attr('data-stud-id',
                            data.studid);
                        $('#postPayment').attr('data-id', dataid).attr('data-stud-id', data
                            .studid);
                        $('#transdate').val(data.transdate);
                        $('#refnum').val(data.refnum);

                        $('#modal-approve').modal('show');
                    }
                });
            });


            $(document).on('mouseenter', '#item-list tr', function() {
                $(this).addClass('bg-gray');
            });

            $(document).on('mouseout', '#item-list tr', function() {
                $(this).removeClass('bg-gray');
            });

            $('#btnprocesspayment').prop('disabled', true).removeClass('btn-primary').addClass('btn-secondary');

            $(document).on('click', '#btnapprove', function() {
                var dataid = $(this).attr('data-id');
                var nodp = $('#chknodp').prop('checked') ? 1 : 0;
                console.log(dataid, nodp, 'as');


                // Disable Approve and Disapprove buttons immediately to prevent multiple clicks
                $('#btnapprove, #btndisapprove').prop('disabled', true);

                Swal.fire({
                    title: nodp == 0 ? 'Approve Online Payment?' : 'Approve No Downpayment?',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '<i class="fas fa-thumbs-up"></i> Approve'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "{{ route('approvepay') }}",
                            method: 'GET',
                            data: {
                                dataid: dataid,
                                nodp: nodp
                            },
                            success: function(data) {
                                if (nodp == 0 || data == 1) {
                                    onlinepaymentlist();
                                    olpaycounter -= 1;
                                    $('#olpayCount').text(olpaycounter);

                                    Swal.fire(
                                        'Approved',
                                        nodp == 0 ?
                                        'Payment successfully approved' :
                                        'No DP successfully approved',
                                        'success'
                                    );

                                    // Enable "Process Payment" button and change color to blue
                                    $('#btnprocesspayment').prop('disabled', false)
                                        .removeClass('btn-secondary').addClass(
                                            'btn-primary');
                                } else if (data == 2) {
                                    Swal.fire('Warning', 'No student found.', 'error');
                                }
                            },
                            complete: function() {
                                // Ensure buttons remain disabled after the process
                                $('#btnapprove, #btndisapprove').prop('disabled', true);
                            },
                            error: function() {
                                // If an error occurs, re-enable the buttons
                                $('#btnapprove, #btndisapprove').prop('disabled',
                                    false);
                                Swal.fire('Error',
                                    'Something went wrong. Please try again.',
                                    'error');
                            }
                        });
                    } else {
                        // If the user cancels, re-enable the buttons
                        $('#btnapprove, #btndisapprove').prop('disabled', false);
                    }
                });
            });



            function callEditreturn(status) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });

                Toast.fire({
                    type: "warning",
                    title: "Online payment already " + status
                });
            }

            $(document).on('click', '.e-amount', function() {
                status = $('#_status').text()
                if (status == 'PENDING') {
                    $('#amountchange').modal('show');
                    $('#txtamount').val('');
                } else {
                    callEditreturn(status)
                }
            });

            $(document).on('click', '#savechangeamount', function() {
                var amount = $('#txtamount').val();
                var dataid = $('#btnapprove').attr('data-id');
                $.ajax({
                    url: "{{ route('saveolAmount') }}",
                    method: 'GET',
                    data: {
                        dataid: dataid,
                        amount: amount
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#amountchange').modal('hide');
                        $('#amount').text(data.amount);


                        Swal.fire(
                            'Saved',
                            'Amount successfully saved',
                            'success'
                        );
                    }
                });
            });

            $(document).on('click', '.e-transdate', function() {
                status = $('#_status').text();

                if (status == 'PENDING') {
                    $('#datechange').modal('show');
                } else {
                    callEditreturn(status)
                }

            });

            $(document).on('click', '#savechangedate', function() {
                var curdate = $('#txtdate').val();
                var dataid = $('#btnapprove').attr('data-id');
                $.ajax({
                    url: "{{ route('saveolDate') }}",
                    method: 'GET',
                    data: {
                        dataid: dataid,
                        curdate: curdate
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#datechange').modal('hide');
                        $('#transdate').text(data.date);


                        Swal.fire(
                            'Saved',
                            'Date successfully saved',
                            'success'
                        );
                    }
                });
            });

            $(document).on('click', '.e-paymenttype', function() {
                var payid;
                status = $('#_status').text()

                if (status == 'PENDING') {
                    $('#paytypechange').modal('show');

                    $('#txtpaytype option').each(function() {
                        payid = $(this).val();
                        if ($(this).text() == $('#paymenttype').text()) {
                            $('#txtpaytype').val(payid);
                            $('#txtpaytype').trigger('change');
                        }
                    });
                } else {
                    callEditreturn(status)
                }
            });

            $(document).on('click', '#savechangepaytype', function() {
                var paytypeid = $('#txtpaytype').val();
                var dataid = $('#btnapprove').attr('data-id');

                $.ajax({
                    url: "{{ route('saveolpaytype') }}",
                    method: 'GET',
                    data: {
                        dataid: dataid,
                        paytypeid: paytypeid
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#paytypechange').modal('hide');
                        $('#paymenttype').text(data.paymenttype);


                        Swal.fire(
                            'Saved',
                            'Payment Type successfully saved',
                            'success'
                        );
                    }
                });
            });

            $(document).on('click', '#btndisapprove', function() {
                $('#modal-remarks').modal('show');
            });

            $(document).on('click', '#saveDisapprove', function() {
                var dataid = $('#btndisapprove').attr('data-id');
                var remarks = $('#txtremarks').val();

                // console.log(dataid + ' ' + remarks);

                $.ajax({
                    url: "{{ route('saveoldisapprove') }}",
                    method: 'GET',
                    data: {
                        dataid: dataid,
                        remarks: remarks
                    },
                    dataType: '',
                    success: function(data) {
                        onlinepaymentlist();
                        $('#modal-approve').modal('hide');
                        $('#modal-remarks').modal('hide');
                        $('#paytypechange').modal('hide');
                        $('#paymenttype').text(data.paymenttype);


                        Swal.fire(
                            'Disapprove',
                            'Online payment has been disapproved',
                            'success'
                        );
                    }
                });
            });

            $(document).on('keyup', '#txtremarks', function() {
                if ($(this).val() != '') {
                    $('#saveDisapprove').prop('disabled', false);
                } else {
                    $('#saveDisapprove').prop('disabled', true);
                }
            });

            $(document).on('click', '.e-refnum', function() {
                status = $('#_status').text()
                if (status == 'PENDING') {
                    $('#refnumchange').modal('show');
                    $('#txtrefnum').val('');
                } else {
                    callEditreturn(status)
                }
            });

            $(document).on('click', '#savechangerefnum', function() {
                var refnum = $('#txtrefnum').val();
                var dataid = $('#btnapprove').attr('data-id');

                $.ajax({
                    url: "{{ route('saveolrefnum') }}",
                    method: 'GET',
                    data: {
                        dataid: dataid,
                        refnum: refnum
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data.stat);
                        if (data.stat == 0) {
                            // $('#btnapprove').prop('disabled', false);
                            $('#refnumchange').modal('hide');
                            $('#refnum').text(data.refnum);

                            Swal.fire(
                                'Saved',
                                'Reference Number successfully saved',
                                'success'
                            );
                        } else {
                            $('#refnumchange').modal('hide');
                            // $('#btnapprove').prop('disabled', true);
                            Swal.fire(
                                'Error',
                                'Reference Number already exist',
                                'warning'
                            );
                        }
                    }
                });
            });

            $(document).on('change', '.filters', function() {
                onlinepaymentlist();
            })

            $(document).on('click', '.ol_filter', function() {
                onlinepaymentlist();
            })

            $(document).on('click', '#ol_print', function() {
                var syid = $('#ol_syid').val();
                var semid = $('#ol_semid').val();
                var filter = $('#ol_search').val();
                var status = $('#ol_filter_status').val()
                var action = 'print'

                window.open('/finance/onlinepaymentlist?action=' + action + '&filter=' + filter +
                    '&status=' + status + '&syid=' + syid + '&semid=' + semid, '_blank');
            })

            $(document).on('click', '#ol_print_pending', function() {
                var syid = $('#ol_syid').val();
                var semid = $('#ol_semid').val();
                var filter = $('#ol_search').val();
                var status = 1
                var action = 'print'

                window.open('/finance/onlinepaymentlist?action=' + action + '&filter=' + filter +
                    '&status=' + status + '&syid=' + syid + '&semid=' + semid, '_blank');
            })

            $(document).on('click', '#ol_print_processed', function() {
                var syid = $('#ol_syid').val();
                var semid = $('#ol_semid').val();
                var filter = $('#ol_search').val();
                var status = 5
                var action = 'print'

                window.open('/finance/onlinepaymentlist?action=' + action + '&filter=' + filter +
                    '&status=' + status + '&syid=' + syid + '&semid=' + semid, '_blank');
            })

            $(document).on('change', '#ol_filter_status', function() {
                onlinepaymentlist();
            })

            function processPayment(dataid, syid, studid) {
                console.log("Data ID:", dataid, "SY ID:", syid, "Student ID:", studid);

                if (!dataid || !syid || !studid) {
                    console.error("Error: Missing required parameters!");
                    return;
                }

                $.ajax({
                    url: '/finance/processpayment',
                    type: "GET",
                    data: {
                        dataid: dataid,
                        syid: syid,
                        studid: studid
                    },
                    success: function(response) {
                        console.log("Response received:", response);

                        if (response.error) {
                            console.error("Error:", response.error);
                            return;
                        }

                        let paymentLogs = response.paymentLogs || [];
                        changeButton(paymentLogs);

                        let studentInfo = response.info?.original || {};
                        $("#studentName").val(studentInfo.studname || '');
                        $("#contactno").val(studentInfo.contactno || '');
                        $("#gradeLevel").val(studentInfo.levelname || '');
                        $("#sectionName").val(studentInfo.section || '');
                        $("#paymentType").val(studentInfo.paymenttype || '');
                        $("#paymentAmount").val(studentInfo.amount || '');
                        $("#paymentDate").val(studentInfo.transdate || '');
                        // $("#paymentRefNum").val(studentInfo.refnum || '');
                        $("#picurl").attr('src', studentInfo.picurl || '');
                        totalamounOP = studentInfo.amount;

                        var amountToProcess = parseFloat((studentInfo.amount || "0").replace(/,/g, ""))
                            .toFixed(2);
                        $("#amountToProcess").text('₱' + amountToProcess);

                        var balanceTableBody = $("#balanceTableBody");
                        balanceTableBody.empty();

                        if (response.ledger && response.ledger.length > 0) {
                            $.each(response.ledger, function(index, classification) {
                                var details = classification.details?.length ? classification
                                    .details : classification.details;

                                balanceTableBody.append(`
                                    <tr class="classification-row">
                                        <td><strong>${classification.classification}</strong></td>
                                        <td><strong>${parseFloat(details[0]?.amount || 0).toFixed(2)}</strong></td>
                                    </tr>
                                `);

                                $.each(details, function(index, detail) {
                                    if (detail.isadj) {
                                        balanceTableBody.append(`
                                                <tr class="item-row" data-isadj="1" data-desc="${detail.particulars}" data-classification="${classification.classificationid}" data-item-id="${detail.id}" data-amount="${detail.totalamount}" data-itemizedid="${detail.id}">
                                                    <td style="padding-left: 50px; cursor: pointer;" class="clickable-item">${detail.particulars}</td>
                                                    <td>${parseFloat(detail.remaining_amount).toFixed(2)}</td>
                                                </tr>
                                            `);
                                    } else {
                                        $.each(detail.items, function(itemIndex, item) {
                                            balanceTableBody.append(`
                                                <tr class="item-row" data-isadj="0" data-desc="${item.item_description}" data-classification="${classification.classificationid}" data-item-id="${item.item_id}" data-amount="${item.totalamount}" data-itemizedid="${item.itemizedid}">
                                                    <td style="padding-left: 50px; cursor: pointer;" class="clickable-item">${item.item_description}</td>
                                                    <td>${parseFloat(item.remaining_amount).toFixed(2)}</td>
                                                </tr>
                                            `);
                                        });
                                    }
                                });
                            });
                        } else {
                            balanceTableBody.append('<tr><td colspan="2">No balances found</td></tr>');
                        }

                        var amountTableBody = $("#amountTableBody");
                        amountTableBody.empty();

                        let totalSelected = 0;

                        if (response.paymentLogs && response.paymentLogs.length > 0) {
                            $.each(response.paymentLogs, function(index, log) {
                                totalSelected += parseFloat(log.totalamount || 0);

                                amountTableBody.append(`
                                    <tr data-itemizedid="${log.itemizedid}">
                                        <td>${log.description}</td>
                                        <td>${parseFloat(log.totalamount).toFixed(2)}</td>
                                    </tr>
                                `);
                            });
                        } else {
                            amountTableBody.append(
                                '<tr><td colspan="3">No payment logs found</td></tr>');
                        }

                        // Update total selected and remaining amounts
                        $("#totalAmountSelected").text('₱' + totalSelected.toFixed(2));
                        let remainingAmount = parseFloat(amountToProcess - totalSelected).toFixed(2);
                        $("#remainingAmount").text('₱' + remainingAmount);
                    },
                    error: function(xhr) {
                        console.error("Error fetching payment data:", xhr.responseText);
                    }
                });
            }

            $(document).on("click", "#btnprocesspayment", function() {
                var dataid = $(this).attr('data-id');
                var syid = $('#ol_syid').val();
                var studid = $(this).attr('data-stud-id');

                processPayment(dataid, syid, studid);
            });

            $(document).on("click", ".item-row", function() {
                var totalamount = parseFloat($(this).find("td:last").text().trim());
                if (totalamount <= 0) {
                    Swal.fire(
                        'Error',
                        'No amount to process',
                        'error'
                    );

                    return;
                }

                var itemDesc = $(this).find("td:first").text().trim();
                var amountToProcess = parseFloat($("#amountToProcess").text().replace(/[^0-9.]/g, ""));
                var amountTableBody = $("#amountTableBody");
                var totalCurrent = calculateTotal();
                var itemid = $(this).attr('data-item-id');
                var isadj = $(this).attr('data-isadj');
                var classid = $(this).attr('data-classification');
                var itemizedid = $(this).attr('data-itemizedid');

                var existingRow = amountTableBody.find(`tr[data-desc="${itemDesc}"]`);

                if (existingRow.length) {
                    existingRow.remove();
                    $(this).removeClass("bg-primary");
                    updateTotalAmount();
                    return;
                }

                var remainingAmount = amountToProcess - totalCurrent;

                if (remainingAmount <= 0) {
                    Swal.fire({
                        title: 'Limit Reached',
                        text: "Total amount already matches " + amountToProcess,
                        type: 'warning'
                    });
                    return;
                }

                if (totalamount > remainingAmount) {
                    totalamount = remainingAmount;
                }

                $(this).addClass("bg-primary");

                amountTableBody.append(`
                    <tr data-desc="${itemDesc}" data-itemizedid="${itemizedid}">
                        <td>${itemDesc}</td>
                        <td><input type="text" class="form-control payment-amount"  data-isadj="${isadj}"  data-classification="${classid}" data-item-id="${itemid}" value="${totalamount.toFixed(2)}"></td>
                    </tr>
                `);

                updateTotalAmount();
            });

            $(document).on("input", ".payment-amount", function() {
                var amountToProcess = parseFloat($("#amountToProcess").text().replace(/[^0-9.]/g, "")) || 0;
                var totalCurrent = calculateTotal();
                var inputAmount = parseFloat($(this).val().replace(/[^0-9.]/g, "")) || 0;

                if (totalCurrent > amountToProcess) {
                    var remainingAmount = amountToProcess - (totalCurrent - inputAmount);
                    $(this).val(remainingAmount.toFixed(2));
                }

                updateTotalAmount();
            });

            function updateTotalAmount() {
                var totalAmount = calculateTotal();
                var amountToProcess = parseFloat($("#amountToProcess").text().replace(/[^0-9.]/g, "")) || 0;
                var remainingAmount = amountToProcess - totalAmount;

                $("#totalAmountSelected").text(`₱${totalAmount.toFixed(2)}`);
                $("#remainingAmount").text(`₱${remainingAmount.toFixed(2)}`);

                console.log("Remaining Amount:", remainingAmount);

                if (remainingAmount <= 0) {
                    $('#postPayment').prop('disabled', false);
                } else {
                    $('#postPayment').prop('disabled', true);
                }

                // Update the table with new totals
                $("#totalSelectedCell").text(`₱${totalAmount.toFixed(2)}`);
                $("#remainingCell").text(`₱${remainingAmount.toFixed(2)}`);
            }

            function calculateTotal() {
                var total = 0;
                $(".payment-amount").each(function() {
                    var amount = parseFloat($(this).val().replace(/[^0-9.]/g, "")) || 0;
                    total += amount;
                });
                return total;
            }

            function changeButton(paymentLogs) {
                if (paymentLogs && paymentLogs.length > 0) {
                    $("#postPayment").addClass("d-none");
                    $("#btnPrintReceipt").removeClass("d-none");
                    $(".modal-footer .btn-danger").addClass("d-none");
                } else {
                    $("#postPayment").removeClass("d-none");
                    $("#btnPrintReceipt").addClass("d-none");
                    $(".modal-footer .btn-danger").removeClass("d-none");
                }
            }

            $(document).on("click", ".clickable-item", function() {
                var itemizedid = $(this).closest(".item-row").data("itemizedid");
                $("#postPayment").attr("data-itemizedid", itemizedid);
            });

            $("#postPayment").on("click", function() {
                var itemizedid = $(this).data("itemizedid");
                var syid = $("#syid").val();
                var studid = $("#studid").val();

                processPayment(itemizedid, syid, studid);
            });

            // Handle post payment button
            $(document).on("click", "#postPayment", function() {
                var studid = $(this).attr('data-stud-id');
                var payid = $(this).attr('data-id');

                // Calculate total amount
                var totalAmount = calculateTotal();
                var amountToProcess = parseFloat($("#amountToProcess").text().replace(/[^0-9.]/g, "")) || 0;
                var remainingAmount = amountToProcess - totalAmount;

                console.log("Computed Total Amount:", totalAmount);
                console.log("Remaining Amount:", remainingAmount);
                console.log("Remaining Amount:", remainingAmount);

                var items = [];
                $("#amountTableBody tr").each(function() {
                    var descElement = $(this).find("td:first");
                    var amountInput = $(this).find("td:last input");
                    var payschedid = $(this).attr("data-itemizedid");

                    if (descElement.length === 0 || amountInput.length === 0) {
                        console.warn("Skipping row due to missing elements:", $(this));
                        return; // Skip this iteration if elements are missing
                    }

                    var desc = descElement.text().trim();
                    var amount = parseFloat(amountInput.val()?.trim()?.replace(/[^0-9.]/g, '')) ||
                        0;
                    var itemid = amountInput.attr('data-item-id') || null;
                    var isadj = amountInput.attr('data-isadj') || null;
                    var classid = amountInput.attr('data-classification') || null;
                    var itemizedid = $(this).closest("tr").data("itemizedid");

                    // Only push valid items
                    if (desc && amount > 0 && itemid && classid) {
                        items.push({
                            payschedid,
                            desc,
                            amount,
                            itemid,
                            classid,
                            itemizedid,
                            isadj
                        });
                    } else {
                        console.warn("Invalid row skipped:", {
                            desc,
                            amount,
                            itemid,
                            classid
                        });
                    }
                });


                var formData = {
                    studentID: studid,
                    paymentType: $("#paymentType").val(),
                    refnum: $("#paymentRefNum").val(),
                    paymentDate: $("#paymentDate").val(),
                    paymentNo: payid,
                    items: items,
                    remainingAmount: remainingAmount,
                    totalAmount: totalAmount
                };

                console.log("Form Data:", formData);

                $.ajax({
                    url: '/finance/postPayment',
                    method: "POST",
                    data: JSON.stringify(formData),
                    contentType: "application/json",
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === "success") {
                            Swal.fire({
                                type: 'success',
                                title: 'Payment Successful',
                                text: 'Payment has been successfully posted.',
                            }).then(() => {
                                changeButton(true);

                                $("#btnprocesspayment").prop("disabled", true);
                                processPayment(payid, $('#ol_syid').val(), studid);
                            });
                        } else {
                            Swal.fire({
                                type: 'error',
                                title: 'Error Posting Payment',
                                text: response.message,
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            type: 'error',
                            title: 'Server Error',
                            text: 'Error posting payment. Please try again.',
                        });
                        console.error("Error posting payment:", xhr.responseText);
                    }
                });
            });

            $(document).on("click", "#btnPrintReceipt", function() {
                var paymentID = $(this).attr("data-id");
                var studid = $(this).attr("data-stud-id");

                window.open("/finance/onlinepayment/receipt/" + paymentID + "/" + studid, "_blank");
            });

        });
    </script>
@endsection
