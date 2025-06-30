@php
    if (auth()->user()->type == 7) {
        $extend = 'studentPortal.layouts.app2';
    } elseif (auth()->user()->type == 9) {
        $extend = 'parentsportal.layouts.app2';
    }
@endphp

@extends($extend)


@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
        }
    </style>
@endsection


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Student Ledger</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student Ledger</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content pt-0">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-filter"></i></span>
                        <div class="info-box-content">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="info-box-text">Enrollment</label>
                                    <span class="info-box-number">
                                        <select class="form-control form-control-sm select2" id="filter_sy"></select>
                                    </span>
                                </div>
                                <div class="col-md-6" hidden>
                                    <span class="info-box-text">Semester</span>
                                    <span class="info-box-number">
                                        <select class="form-control" id="filter_sem">
                                            @php
                                                $sy = DB::table('semester')->get();
                                            @endphp
                                            @foreach ($sy as $item)
                                                @php
                                                    $selected = '';
                                                    if ($item->isactive == 1) {
                                                        $selected = 'selected="selected"';
                                                    }
                                                @endphp
                                                <option value="{{ $item->id }}" {{ $selected }}
                                                    value="{{ $item->id }}">{{ $item->semester }}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-2" hidden>
                    <div class="info-box">
                        <div class="info-box-content">
                            <span class="info-box-text">Total Annual Fee</span>
                            <span class="info-box-number" id="tuition">
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <div class="info-box mb-3">
                        <div class="info-box-content">
                            <span class="info-box-text">Balance</span>
                            <span class="info-box-number" id="balance"></span>
                        </div>
                    </div>
                </div>
                <div class="clearfix hidden-md-up"></div>
                <div class="col-12 col-sm-6 col-md-2">
                    <div class="info-box mb-3">
                        <div class="info-box-content">
                            <span class="info-box-text">Amount Paid</span>
                            <span class="info-box-number" id="paid"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-book"></i> Student Ledger</h3>
                        </div>


                        <div class="card-body p-0 table-responsive">
                            <table class="table table-sm font-sm table-head-fixed table-striped" width="100%"
                                style="font-size:.rem; min-width:500px !important">
                                <thead>
                                    <tr>
                                        <th width="15%">DATE</th>
                                        <th width="40%"class="">PARTICULARS</th>
                                        <th width="15%"class="text-center">CHARGES</th>
                                        <th width="15%"class="text-center">PAYMENT</th>
                                        <th width="15%"class="text-center">BALANCE</th>
                                        {{-- <th width="35%" class="bg-warning">Particulars</th>
                                        <th width="25%" class="text-right bg-warning">Charges</th>
                                        <th width="25%" class="text-right bg-warning">Payment</th>
                                        <th width="25%" class="text-right bg-warning">Balance</th> --}}
                                    </tr>
                                </thead>
                                <tbody id="student_fees">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <br>
            <div class="row">
                <div class="col-12">

                    <div class="card-header bg-white">
                        <h3 class="card-title"><i class="fas fa-layer-group"></i> Payment History</h3>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <input type="text" id="student_id" hidden>
                        <div id="payment_history" class="table-responsive">
                            <table width="100%" class="table table-striped table-sm text-sm" style="table-layout: fixed;">
                                <thead class="bg-info">
                                    <tr>
                                        <th width="15%">DATE</th>
                                        <th width="40%"class="">PARTICULARS</th>
                                        <th width="15%"class="text-center">CHARGES</th>
                                        <th width="15%"class="text-center">PAYMENT</th>
                                        <th width="15%"class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody id="history_list">

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>


            <div class="row" hidden>
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-layer-group"></i> One Time Payables</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm font-sm table-head-fixed  table-striped" style="font-size:.9rem;">
                                <thead>
                                    <tr>
                                        <th width="70%">PARTICULARS</th>
                                        <th width="30%" class="text-right">BALANCE</th>
                                    </tr>
                                </thead>
                                <tbody id="student_onetime">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-calendar-check"></i> Monthly Payables</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm font-sm table-head-fixed table-striped" style="font-size:.9rem;">
                                <thead>
                                    <tr>
                                        <th width="70%">PARTICULARS</th>
                                        <th width="30%" class="text-right">BALANCE</th>
                                    </tr>
                                </thead>
                                <tbody id="student_monthly">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            var enrollmentinfo = []

            $(document).on('change', '#filter_sy', function() {
                load_billing()
                get_ledger()
                // get_history_ledger()
            })


            get_enrollment_history()

            function get_enrollment_history() {
                $.ajax({
                    type: 'GET',
                    url: '/payment/balanceinfo',
                    success: function(data) {

                        var active_sy = null
                        $.each(data, function(a, b) {
                            if (b.isactive == 1) {
                                active_sy = b
                            }
                        })


                        $('#filter_sy').empty()
                        $('#filter_sy').select2({
                            data: data,
                            placeholder: 'Select Enrollment',
                        })

                        enrollmentinfo = data
                        if (active_sy) {
                            $('#filter_sy').val(active_sy.id).change()
                        }

                        console.log('ACTIVE SY', $('#filter_sy').val());
                        get_ledger()
                        // get_history_ledger()
                    }

                })
            }




            function load_billing() {
                $('#student_monthly').empty();
                $('#student_onetime').empty();
                $('#tuition').empty();


                if ($('#filter_sy').val() != null && $('#filter_sy').val() != '') {
                    var val_info = $('#filter_sy').val().split('-');
                } else {
                    return false;
                }

                var temp_sy = val_info[0]
                var temp_sem = val_info[1]

                var tempinfo = enrollmentinfo.filter(x => x.syid == temp_sy && x.semid == temp_sem)

                console.log(tempinfo)

                $.ajax({
                    type: 'GET',
                    url: '/current/billingassesment',
                    data: {
                        syid: temp_sy,
                        semid: temp_sem,
                        levelid: tempinfo[0].levelid
                    },
                    success: function(data) {
                        console.log('Billing', data);
                        var monthly = data.filter(x => x.duedate != null)
                        var onetime = data.filter(x => x.duedate == null)
                        var total = 0
                        var balance = 0
                        var overall_total = 0
                        $.each(monthly, function(a, b) {
                            total = parseFloat(total) + parseFloat(b.amountdue.replace(",", ""))
                            balance = parseFloat(balance) + parseFloat(b.balance.replace(",",
                                ""))
                            $('#student_monthly').append('<tr><td >' + b.particulars +
                                '</td><td class="text-right align-middle">&#8369; ' + b
                                .balance +
                                '</td></tr>')
                        })

                        $('#student_monthly').append(
                            '<tr class="bg-info"><td>TOTAL BALANCE</td><td class="text-right">&#8369; ' +
                            balance.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,") + '</td></tr>')

                        overall_total = parseFloat(overall_total) + parseFloat(total)

                        var total = parseFloat(0)
                        var balance = parseFloat(0)

                        $.each(onetime, function(a, b) {
                            total = parseFloat(total) + parseFloat(b.amountdue.replace(",", ""))
                            balance = parseFloat(balance) + parseFloat(b.balance.replace(",",
                                ""))
                            $('#student_onetime').append('<tr><td >' + b.particulars +
                                '</td><td class="text-right align-middle">&#8369; ' + b
                                .balance.replace(/(\d)(?=(\d{3})+\.)/g, "$1,") +
                                '</td></tr>')
                        })

                        $('#student_onetime').append(
                            '<tr class="bg-info"><td>TOTAL BALANCE</td><td class="text-right align-middle">&#8369; ' +
                            balance.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,") + '</td></tr>')

                        overall_total = parseFloat(overall_total) + parseFloat(total)

                        $('#tuition')[0].innerHTML = '&#8369; ' + overall_total.toFixed(2).replace(
                            /(\d)(?=(\d{3})+\.)/g, "$1,")

                    }
                })
            }

            // get_ledger()

            //working v2 code
            // function get_ledger() {
            //     $('#balance').empty();
            //     $('#paid').empty();
            //     $('#student_ledger').empty();
            //     $('#balance')[0].innerHTML = '&#8369; 0.00'
            //     $('#paid')[0].innerHTML = '&#8369; 0.00'

            //     if ($('#filter_sy').val() != null && $('#filter_sy').val() != '') {
            //         var val_info = $('#filter_sy').val().split('-');
            //     } else {
            //         $('#balance')[0].innerHTML = '&#8369; ' + '0.00';
            //         return false;
            //     }

            //     var temp_sy = val_info[0]
            //     var temp_sem = val_info[1]

            //     var tempinfo = enrollmentinfo.filter(x => x.syid == temp_sy && x.semid == temp_sem)

            //     if (tempinfo.length == 0) {
            //         $('#balance')[0].innerHTML = '&#8369; ' + '0.00';
            //         return false;
            //     }

            //     $.ajax({
            //         type: 'GET',
            //         url: '/ledger',
            //         data: {
            //             syid: temp_sy,
            //             semid: temp_sem,
            //             levelid: tempinfo[0].levelid
            //         },
            //         success: function(data) {
            //             console.log('Ledger', data);

            //             if (data.length > 0) {
            //                 $('#student_ledger').empty()
            //                 var total_amount = 0;
            //                 var total_payment = 0;
            //                 var total_balance = 0
            //                 var abalance = parseFloat(0).toFixed(2)

            //                 var total_payment = parseFloat(0).toFixed(2)

            //                 var tolal_charges_ledger = parseFloat(0).toFixed(2)
            //                 var tolal_payment_ledger = parseFloat(0).toFixed(2)
            //                 var runbal = 0;

            //                 $.each(data, function(a, b) {

            //                     var ornum = ''
            //                     if (b.ornum != '') {
            //                         ornum = b.ornum
            //                     }
            //                     runbal += parseFloat(b.amount)
            //                     runbal -= parseFloat(b.payment)

            //                     if (b.amount > 0) {
            //                         aamount = '&#8369;' + parseFloat(b.amount).toFixed(2)
            //                             .replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
            //                     } else {
            //                         aamount = ''
            //                     }

            //                     if (b.payment > 0) {
            //                         apayment = '&#8369;' + parseFloat(b.payment).toFixed(2)
            //                             .replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
            //                     } else {
            //                         apayment = ''
            //                     }

            //                     if (b.ornum != null) {
            //                         total_payment = parseFloat(total_payment) + parseFloat(b
            //                             .payment)
            //                     }

            //                     tolal_charges_ledger = parseFloat(tolal_charges_ledger) +
            //                         parseFloat(b.amount)
            //                     tolal_payment_ledger = parseFloat(tolal_payment_ledger) +
            //                         parseFloat(b.payment)

            //                     $('#student_ledger').append('<tr><td >' + b.particulars +
            //                         '</td><td class="text-right  align-middle">' + aamount +
            //                         '</td><td class="text-right  align-middle">' +
            //                         apayment + '</td></tr>')

            //                 })

            //                 $('#student_ledger').append(
            //                     '<tr><td class="text-right">TOTAL</td><td class="text-right  align-middle">&#8369; ' +
            //                     tolal_charges_ledger.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g,
            //                         "$1,") + '</td><td class="text-right  align-middle">&#8369; ' +
            //                     tolal_payment_ledger.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g,
            //                         "$1,") + '</td></tr>')

            //                 if (data.length != 0 || runbal != 0) {
            //                     runbal = parseFloat(runbal).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g,
            //                         "$1,")
            //                     $('#student_ledger').append(
            //                         '<tr class="bg-info"><td class="text-right">REMAINING BALANCE</td><td class="text-right">&#8369; ' +
            //                         runbal + '</td><td></td></tr>')
            //                 } else {
            //                     $('#balance')[0].innerHTML = '&#8369; ' + '0.00';
            //                     runbal = parseFloat(0).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
            //                     $('#student_ledger').append(
            //                         '<tr class="bg-info"><td class="text-right">REMAINING BALANCE</td><td class="text-right">&#8369; ' +
            //                         runbal + '</td><td></td></tr>')
            //                 }

            //                 $('#balance')[0].innerHTML = '&#8369; ' + runbal
            //                 $('#paid')[0].innerHTML = '&#8369; ' + parseFloat(total_payment).toFixed(2)
            //                     .replace(/(\d)(?=(\d{3})+\.)/g, "$1,")

            //             } else {

            //                 $('#balance')[0].innerHTML = '&#8369; 0.00'
            //                 $('#paid')[0].innerHTML = '&#8369; 0.00'
            //                 runbal = parseFloat(0).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
            //                 $('#student_ledger').append(
            //                     '<tr class="bg-info"><td class="text-right">REMAINING BALANCE</td><td class="text-right">&#8369; 0.00</td><td></td></tr>'
            //                 )
            //             }
            //         }
            //     })
            // }

            get_ledger()

            function get_ledger() {
                $('#balance').empty();
                $('#paid').empty();
                $('#student_ledger').empty();
                $('#balance')[0].innerHTML = '&#8369; 0.00';
                $('#paid')[0].innerHTML = '&#8369; 0.00';

                if ($('#filter_sy').val() != null && $('#filter_sy').val() != '') {
                    var val_info = $('#filter_sy').val().split('-');
                } else {
                    $('#balance')[0].innerHTML = '&#8369; 0.00';
                    return false;
                }

                var temp_sy = val_info[0];
                var temp_sem = val_info[1];
                var tempinfo = enrollmentinfo.filter(x => x.syid == temp_sy && x.semid == temp_sem);

                if (tempinfo.length == 0) {
                    $('#balance')[0].innerHTML = '&#8369; 0.00';
                    return false;
                }

                $.ajax({
                    type: 'GET',
                    url: '/ledger',
                    data: {
                        syid: temp_sy,
                        semid: temp_sem,
                        levelid: tempinfo[0].levelid
                    },
                    success: function(data) {
                        console.log('Ledger', data);

                        if (Array.isArray(data) && data.length > 0) {
                            $('#student_ledger').empty();

                            var total_charges = 0;
                            var total_payment = 0;
                            var runbal = 0;

                            data.forEach(function(entry) {
                                var amount = parseFloat(entry.amount) || 0;
                                var payment = parseFloat(entry.payment) || 0;
                                runbal += amount - payment;
                                total_charges += amount;
                                total_payment += payment;
                                $('#student_id').val(entry.studid);

                                var formattedAmount = amount > 0 ? '&#8369; ' + amount.toFixed(
                                    2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") : '';
                                var formattedPayment = payment > 0 ? '&#8369; ' + payment
                                    .toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") : '';

                                $('#student_ledger').append(
                                    '<tr>' +
                                    '<td>' + entry.particulars + '</td>' +
                                    '<td class="text-right">' + formattedAmount + '</td>' +
                                    '<td class="text-right">' + formattedPayment + '</td>' +
                                    '<td class="text-right">&#8369; ' + runbal.toFixed(2)
                                    .replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</td>' +
                                    '</tr>'
                                );
                            });

                            $('#student_ledger').append(
                                '<tr class="bg-warning font-weight-bold">' +
                                '<td class="text-right"></td>' +
                                '<td class="text-right"><p>TOTAL:<span style="margin-left: 20%;">&#8369; </span>' +
                                total_charges.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") +
                                '</td>' +
                                '<td class="text-right">&#8369; ' + total_payment.toFixed(2)
                                .replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '</td>' +
                                '<td class="text-right">&#8369; ' + runbal.toFixed(2).replace(
                                    /\B(?=(\d{3})+(?!\d))/g, ",") + '</td>' +
                                '</tr>'
                            );

                            $('#balance')[0].innerHTML = '&#8369; ' + runbal.toFixed(2).replace(
                                /\B(?=(\d{3})+(?!\d))/g, ",");
                            $('#paid')[0].innerHTML = '&#8369; ' + total_payment.toFixed(2).replace(
                                /\B(?=(\d{3})+(?!\d))/g, ",");

                            if ($('#filter_sy').val() != null && $('#filter_sy').val() != '') {
                                var val_info = $('#filter_sy').val().split('-');
                            } else {
                                $('#balance')[0].innerHTML = '&#8369; 0.00';
                                return false;
                            }

                            var studid = $('#student_id').val();
                            var batchid = $('#tvlbatch').val();

                            $.ajax({
                                type: "GET",
                                url: "/finance/getStudLedgerV2_student",
                                data: {
                                    syid: temp_sy,
                                    studid: studid,
                                    semid: temp_sem,
                                    batchid: batchid
                                },
                                success: function(data) {
                                    if (Array.isArray(data)) {
                                        var tbody = $("#student_fees");
                                        tbody.empty(); // Clear previous rows

                                        let totalCharges = 0;
                                        let totalPayments = 0;
                                        let totalBalance =
                                            0; // Sum of all (charge - payment)

                                        data.forEach(item => {
                                            let date = new Date(item
                                                .createddatetime);
                                            let formattedDate = date
                                                .toLocaleDateString("en-US", {
                                                    year: "numeric",
                                                    month: "short",
                                                    day: "2-digit"
                                                });

                                            let charge = parseFloat(item.amount) ||
                                                0;
                                            let totalpaid = parseFloat(item
                                                .totalpaid) || 0;
                                            if (item.payment > 0 && totalpaid ==
                                                0 &&
                                                item.particulars.includes(
                                                    "OLD ACCOUNTS FORWARDED TO")) {
                                                totalpaid = parseFloat(item
                                                    .payment) || 0;
                                            }

                                            let balance = charge - totalpaid;
                                            totalCharges += charge;
                                            totalPayments += totalpaid;
                                            totalBalance += balance;

                                            tbody.append(`
                                                <tr>
                                                    <td>${formattedDate}</td>
                                                    <td style="cursor:pointer" data-syid="${item.syid}" data-studid="${item.studid}" data-semid="${item.semid}" class="view_ledger">
                                                        ${item.particulars}
                                                        ${item.particular_items.length > 0 ? `
                                                                                    <a href="javascript:void(0)" class="view-items" data-items='${JSON.stringify(item.particular_items)}' data-bs-toggle="tooltip" data-bs-html="true" title="Breakdown">
                                                                                        <i class="fas fa-caret-down"></i>
                                                                                    </a>` : ''}
                                                        ${item.history && item.history.length > 0 ? `
                                                                                    <a href="javascript:void(0)" class="view-items2" data-items2='${JSON.stringify(item.history)}' data-bs-toggle="tooltip" data-bs-html="true" title="Breakdown">
                                                                                        <i class="fas fa-caret-down"></i>
                                                                                    </a>` : ''}
                                                    </td>
                                                    <td class="text-center">${charge.toFixed(2)}</td>
                                                    <td class="text-center">${totalpaid.toFixed(2)}</td>
                                                    <td class="text-center">${balance.toFixed(2)}</td>
                                                </tr>
                                            `);
                                        });

                                        tbody.append(`
                                            <tr class="bg-warning" style="font-weight: bold;">
                                                <td colspan="2" class="text-right">TOTAL:</td>
                                                <td class="text-center">${totalCharges.toFixed(2)}</td>
                                                <td class="text-center">${totalPayments.toFixed(2)}</td>
                                                <td class="text-center">${totalBalance.toFixed(2)}</td>
                                            </tr>
                                        `);
                                    }
                                }
                            });

                            $.ajax({
                                type: "GET",
                                url: "/finance/history_list_student",
                                data: {
                                    syid: temp_sy,
                                    studid: studid,
                                    semid: temp_sem,
                                    batchid: batchid
                                },
                                success: function(historyData) {
                                    console.log(historyData, 'historyData');

                                    if (Array.isArray(historyData)) {
                                        var tbody = $("#history_list");
                                        tbody.empty();

                                        let totalCharge = 0;
                                        let totalPayment = 0;

                                        historyData.forEach(item => {
                                            let date = new Date(item
                                                .createddatetime);
                                            let formattedDate = date
                                                .toLocaleDateString("en-US", {
                                                    year: "numeric",
                                                    month: "short",
                                                    day: "2-digit"
                                                });

                                            let charge = parseFloat(item.amount) ||
                                                0;
                                            let payment = parseFloat(item
                                                .payment) || 0;

                                            totalCharge += charge;
                                            totalPayment += payment;

                                            let chargeDisplay = charge === 0 ? "" :
                                                charge.toFixed(2);
                                            let paymentDisplay = payment === 0 ?
                                                "" : payment.toFixed(2);

                                            tbody.append(`
                                    <tr style="font-size: 1.2em;">
                                        <td>${formattedDate}</td>
                                        <td>
                                            ${item.particulars} 
                                           
                                        </td>
                                        <td class="text-center" style="vertical-align: middle">${chargeDisplay}</td>
                                        <td class="text-center" style="vertical-align: middle">${paymentDisplay}</td>
                                        <td class="text-center"></td>
                                    </tr>
                                `);
                                        });

                                        tbody.append(`
                                <tr class="font-weight-bold bg-info" style="font-size: 1.2em;">
                                    <td colspan="2" class="text-right"><b>Total:</b></td>
                                    <td class="text-center">${totalCharge.toFixed(2)}</td>
                                    <td class="text-center">${totalPayment.toFixed(2)}</td>
                                    <td></td>
                                </tr>
                            `);
                                    }
                                }
                            });
                        } else {
                            $('#balance')[0].innerHTML = '&#8369; 0.00';
                            $('#paid')[0].innerHTML = '&#8369; 0.00';
                            $('#student_ledger').append(
                                '<tr class="bg-info text-white">' +
                                '<td class="text-right">REMAINING BALANCE</td>' +
                                '<td class="text-right">&#8369; 0.00</td>' +
                                '<td></td>' +
                                '</tr>'
                            );
                        }
                    }
                });
            }

            $(document).on("click", ".view-items", function() {
                var row = $(this).closest("tr"); // Get the parent row
                var nextRow = row.next(); // Check if the next row is already a breakdown row

                if (nextRow.hasClass("breakdown-row")) {
                    nextRow.remove(); // Remove the breakdown row if already opened
                    return;
                }

                var items = JSON.parse($(this).attr("data-items")); // Get items from attribute
                var breakdownHTML = `
                <tr class="breakdown-row bg-light">
                    <td colspan="5" class="p-0">
                    <table width="100%" class="table table-sm text-sm" style="background-color: white;">
                        <thead hidden>
                        <tr>
                            <th width="15%"></th>
                            <th width="40%">PARTICULARS</th>
                            <th width="15%" class="text-center">CHARGES</th>
                            <th width="15%" class="text-center">PAYMENT</th>
                            <th width="15%" class="text-center">BALANCE</th>
                        </tr>
                        </thead>
                        <tbody style="background-color: white;">
                        ${items.map(item => {
                            // Ensure all numeric values are properly converted
                            let charge = parseFloat(item.ledger_classid === 1 ? item.amount : item.itemamount) || 0;
                            let payment = parseFloat(item.ledger_classid === 1 ? item.amountpay : item.totalamount) || 0;
                            let balance = (item.ledger_classid === 1 ? parseFloat(item.balance) : (charge - payment)) || 0;
                            
                            return `
                                        <tr style="background-color: white;">
                                            <td width="15%"></td>
                                            <td width="40%">${item.ledger_classid === 1 ? item.tuition_month : item.description}</td>
                                            <td width="15%" class="text-center">${charge.toFixed(2)}</td>
                                            <td width="15%" class="text-center">${payment.toFixed(2)}</td>
                                            <td width="15%" class="text-center">${balance.toFixed(2)}</td>
                                        </tr>
                                    `;
                        }).join("")}
                        </tbody>
                    </table>
                    </td>
                </tr>
                `;



                row.after(breakdownHTML); // Insert breakdown row below main row
            });

            $(document).on("click", ".view-items2", function() {
                var row = $(this).closest("tr"); // Get the parent row
                var nextRow = row.next(); // Check if the next row is already a breakdown row

                if (nextRow.hasClass("breakdown-row")) {
                    nextRow.remove(); // Remove the breakdown row if already opened
                    return;
                }

                var items = JSON.parse($(this).attr("data-items2")); // Get items from attribute
                var breakdownHTML = `
                <tr class="breakdown-row bg-light">
                    <td colspan="5" class="p-0">
                    <table width="100%" class="table table-sm text-sm" style="background-color: white;">
                        <thead hidden>
                        <tr>
                            <th width="15%"></th>
                            <th width="40%">PARTICULARS</th>
                            <th width="15%" class="text-center">CHARGES</th>
                            <th width="15%" class="text-center">PAYMENT</th>
                            <th width="15%" class="text-center">BALANCE</th>
                        </tr>
                        </thead>
                        <tbody style="background-color: white;">
                        ${items.map(item => {
                            // Ensure all numeric values are properly converted
                            let charge = parseFloat(item.amount) || 0;
                            let payment = parseFloat(item.payment) || 0;
                            let balance = 0;

                            return `
                                                                <tr style="background-color: white;">
                                                                    <td width="15%"></td>
                                                                    <td width="40%">${item.particulars}</td>
                                                                    <td width="15%" class="text-center">${charge.toFixed(2)}</td>
                                                                    <td width="15%" class="text-center">${payment.toFixed(2)}</td>
                                                                    <td width="15%" class="text-center">${balance.toFixed(2)}</td>
                                                                </tr>
                                                            `;
                        }).join("")}
                        </tbody>
                    </table>
                    </td>
                </tr>
                `;

                row.after(breakdownHTML); // Insert breakdown row below main row
            });
            // get_history_ledger()

            // function get_history_ledger() {

            //     if ($('#filter_sy').val() != null && $('#filter_sy').val() != '') {
            //         var val_info = $('#filter_sy').val().split('-');
            //     } else {
            //         $('#balance')[0].innerHTML = '&#8369; 0.00';
            //         return false;
            //     }

            //     var temp_sy = val_info[0];
            //     var temp_sem = val_info[1];

            //     var studid = $('#student_id').val();

            //     console.log("studid niiii", studid);
            //     console.log("temp_sy niiii", temp_sy);
            //     console.log("temp_sem niiii", temp_sem);

            //     // var syid = $('#sy').val();
            //     // var semid = $('#sem').val();
            //     var batchid = $('#tvlbatch').val();

            //     $.ajax({
            //         type: "GET",
            //         url: "/finance/history_list",
            //         data: {
            //             syid: temp_sy,
            //             studid: studid,
            //             semid: temp_sem,
            //             batchid: batchid
            //         },
            //         success: function(data) {
            //             console.log(data, 'eheheeheh');

            //             var tbody = $("#history_list");
            //             tbody.empty(); // Clear previous rows

            //             let totalCharge = 0;
            //             let totalPayment = 0;

            //             data.forEach(item => {
            //                 // Format date
            //                 let date = new Date(item.createddatetime);
            //                 let formattedDate = date.toLocaleDateString("en-US", {
            //                     year: "numeric",
            //                     month: "short",
            //                     day: "2-digit"
            //                 }); // Example: "Jul 16, 2024"

            //                 // Convert amounts to float for calculation
            //                 let charge = parseFloat(item.amount) || 0;
            //                 let payment = parseFloat(item.payment) || 0;

            //                 totalCharge += charge;
            //                 totalPayment += payment;

            //                 // Display empty string if 0, otherwise format to 2 decimal places
            //                 let chargeDisplay = charge === 0 ? "" : charge.toFixed(2);
            //                 let paymentDisplay = payment === 0 ? "" : payment.toFixed(2);

            //                 // Append row to table
            //                 tbody.append(`
        //                 <tr>
        //                     <td>${formattedDate}</td>
        //                     <td>
        //                         ${item.particulars} 
        //                         ${item.classid !== null ? `
            //                                                                                             <span class="text-sm text-danger adj_delete" style="cursor:pointer" data-id="${item.ornum}">
            //                                                                                                 <i class="far fa-trash-alt"></i>
            //                                                                                             </span>
            //                                                                                             <span class="text-sm text-info adj_view" style="cursor:pointer" data-id="${item.ornum}" data-toggle="tooltip" title="View Adjustment">
            //                                                                                                 <i class="fas fa-archive"></i>
            //                                                                                             </span>
            //                                                                                         ` : ''}

        //                         ${item.classid === null && !item.particulars.includes('DISCOUNT:') ? `
            //                                                                                             <a href="javascript:void(0)" transid="${item.transid}" id="view_receipts">
            //                                                                                                 view receipts
            //                                                                                             </a>
            //                                                                                         ` : ''}

        //                         ${item.particulars.includes('DISCOUNT:') ? `
            //                                                                                             <span class="text-sm text-danger discount_delete" studid="${studid}" style="cursor:pointer" data-id="${item.ornum}">
            //                                                                                                 <i class="far fa-trash-alt"></i>
            //                                                                                             </span>
            //                                                                                         ` : ''}
        //                     </td>
        //                     <td class="text-center" style="vertical-align: middle">${chargeDisplay}</td>
        //                     <td class="text-center" style="vertical-align: middle">${paymentDisplay}</td>
        //                     <td class="text-center"></td>
        //                 </tr>
        //             `);


            //             });

            //             // Append total row
            //             tbody.append(`
        //         <tr class="font-weight-bold bg-info">
        //             <td colspan="2" class="text-right"><b>Total:</b></td>
        //             <td class="text-center">${totalCharge.toFixed(2)}</td>
        //             <td class="text-center">${totalPayment.toFixed(2)}</td>
        //             <td></td>
        //         </tr>
        //         `);
            //         }
            //     });
            // }




        })
    </script>
@endsection
