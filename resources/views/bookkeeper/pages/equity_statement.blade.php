<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Equity Statement</title>
    @extends('bookkeeper.layouts.app')

    @section('pagespecificscripts')
        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    @endsection
    <style>
        select,
        input,
        th,
        td {
            font-size: 13px !important;
            font-weight: normal !important;
        }

        .form-control {
            box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42) !important;
            border: none !important;
            height: 30px !important;


        }

        ::placeholder {
            font-size: 12px !important;
        }

        label {
            font-weight: normal !important;
            font-size: 13px !important;
        }

        #table_header {
            font-weight: 600 !important;
            border: 1px solid #7d7d7d !important;
        }

        /* td {
            border: 1px solid #7d7d7d !important;
        }  */
    </style>


</head>

<body>
    @section('content')
        @php
            $fiscal = DB::table('bk_fiscal_year')->where('deleted', 0)->get();
            $activeFiscalYear = DB::table('bk_fiscal_year')->where('isactive', 1)->where('deleted', 0)->first();
            $activeFiscalYearId = isset($activeFiscalYear) ? $activeFiscalYear->id : null;

            $coa = DB::table('chart_of_accounts')->get();
        @endphp
        <div class="container-fluid ml-3">
            <div>
                <div class="d-flex align-items-center">
                    <i class="fas fa-landmark fa-2x mr-2 mt-3"></i>
                    <div style="display: flex; align-items: center;" class="mt-3">
                        <h3 style="margin: 0;">Statement of Changes in Equity</h3>
                    </div>
                </div>
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item" style="font-size: 0.8rem;"><a href="#"
                                style="color:rgba(0,0,0,0.5);">Home</a></li>
                        <li class="breadcrumb-item active" style="font-size: 0.8rem; color:rgba(0,0,0,0.5);">Statement of
                            Changes in Equity
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="mb-3" style="color: black;  font-size: 13px;">
                <ul class="nav nav-tabs" style="border-bottom: 4px solid #d9d9d9;;">
                    <li class="nav-item">
                        <a class="nav-link" href="/bookkeeper/income_statement"
                            class="nav-link {{ Request::url() == url('bookkeeper/income_statement') ? 'active' : '' }}"
                            style="color: black;">Income
                            Statement</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/bookkeeper/balance_sheet"
                            class="nav-link {{ Request::url() == url('bookkeeper/balance_sheet') ? 'active' : '' }}"
                            style="color: black;">Balance Sheet</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/bookkeeper/cashflow_statement"
                            class="nav-link {{ Request::url() == url('bookkeeper/cashflow_statement') ? 'active' : '' }}"
                            style="color: black;">Cashflow Statement</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/bookkeeper/equity_statement"
                            class="nav-link {{ Request::url() == url('bookkeeper/equity_statement') ? 'active' : '' }}"
                            style="color: black;">Statement of Changes in Equity</a>
                    </li>
                </ul>
            </div>
            <hr style="border-top: 2px solid #d9d9d9;">

            <div class="card p-3" style="border-radius: 5px; background-color:#f1f1f1; border:none; width: 98%;">
                <h6 style="font-size: 13px;"><i class="fas fa-filter"></i> Filter</h6>
                <div class="row" style="margin-left: 12px;">
                    <div class="col-md-3">
                        <label style="font-size: 13px; font-weight: 500;">Date Range</label>
                        <div class="input-group" style="width: 100%;">
                            <div class="input-group" style="width: 100%;">
                                <input
                                    style="font-size: 11px; height: 30px; border-radius: 5px; border:none; padding-right: 30px; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);"
                                    type="text" name="dateRange" id="dateRange" value=""
                                    placeholder="mm/dd/yyyy - mm/dd/yyyy" class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text"
                                        style="background-color: white; border: none; margin-left: -30px;">
                                        <i class="far fa-calendar-alt"
                                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 ml-5">
                        <label style="font-size: 13px; font-weight: 500;">Fiscal Year</label>
                        <div class="row align-items-center">
                            <select
                                style="width: 100%; height: 30px; font-size: 12.5px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);"
                                class="form-control" id="fiscal_year_select">
                                <option value="">Select Fiscal Year</option>
                                @foreach ($fiscal as $item)
                                    <option value="{{ $item->id }}" @if ($item->id == $activeFiscalYearId) selected @endif>
                                        {{ $item->description }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- <i class="fas fa-sync-alt"
                                style="position: absolute; right: -25px; cursor: pointer; color: #00581f;"></i> --}}

                        </div>

                    </div>
                </div>
            </div>

            <div class="row bg-white py-3">
                <div class="col-lg-12 col-md-12">
                    <div class="table-responsive w-100">
                        <div class="row py-2">
                            <div class="col-md-12">
                                <button class="btn btn-sm px-2" id="print_equity_statement"
                                    style="background-color: #143893; color: white;">
                                    <i class="fas fa-print"></i> PRINT
                                </button>
                            </div>
                        </div>
                        <table class="table table-sm w-100">
                            <thead class="table-secondary">
                                <tr>
                                    <th class="text-left" id="table_header">Particulars</th>
                                    <th class="text-left" id="table_header">Common Stock</th>
                                    <th class="text-left" id="table_header">Retained Earnings</th>
                                    <th class="text-left" id="table_header">Total Equity</th>
                                </tr>
                            </thead>
                            <tbody id="equity_table">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    @endsection
    @section('footerjavascript')
        <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('plugins/fullcalendar-v5-11-3/main.js') }}"></script>
        <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
        <script src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
        <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
        <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
        <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
        <script>
            $(document).ready(function() {

                $('#dateRange').daterangepicker({
                    autoUpdateInput: false,
                    locale: {
                        cancelLabel: 'Clear'
                    }
                });

                $('#dateRange').on('apply.daterangepicker', function(ev,
                    picker) {
                    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker
                        .endDate
                        .format(
                            'MM/DD/YYYY'));
                });

                // $('#dateRange').on('cancel.daterangepicker', function(ev,
                //     picker) {
                //     $(this).val('');
                // });

                $('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    picker.setStartDate(moment());
                    picker.setEndDate(moment());
                    loadSubsidiaryLedger('');
                });


                loadSubsidiaryLedger();

                $('#fiscal_year_select').change(function() {
                    const dateRange = $('#dateRange').val();
                    loadSubsidiaryLedger(dateRange);
                });

                $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
                    const dateRange = $(this).val();
                    console.log('apply.daterangepicker:', dateRange);
                    loadSubsidiaryLedger(dateRange);
                });


                function loadSubsidiaryLedger(dateRange) {
                    const fiscalYear = $('#fiscal_year_select').val();
                    const fiscalYearText = $('#fiscal_year_select option:selected').text();

                    const accountId = $('#account_select').val();

                    $.ajax({
                        url: '/bookkeeper/equity-statement-fetch',
                        type: 'GET',
                        data: {
                            fiscal_year: fiscalYear,
                            fiscalYearText: fiscalYearText,
                            date_range: dateRange
                        },
                        success: function(data) {
                            let tbody = $('#equity_table');
                            tbody.empty();

                            // Process current year data
                            let currentYear = data.currentYear;
                            let previousYear = data.previousYear;

                            // Current year calculations
                            let totalBeginningBalance = currentYear.beginningBalance.reduce((sum, item) =>
                                sum + parseFloat(item.net_amount) || 0, 0).toLocaleString(undefined, {
                                minimumFractionDigits: 2
                            });
                            let totalIncomeStatement = currentYear.incomeStatement.reduce((sum, item) =>
                                sum + (parseFloat(item.net_amount) || 0), 0).toLocaleString(undefined, {
                                minimumFractionDigits: 2
                            });
                            let totalWithdrawal = currentYear.withdrawals.reduce((sum, item) => sum + (
                                parseFloat(item.net_amount) || 0), 0).toLocaleString(undefined, {
                                minimumFractionDigits: 2
                            });

                            let totalBeginningBalance_cal = currentYear.beginningBalance.reduce((sum,
                                item) => sum + parseFloat(item.net_amount) || 0, 0);
                            let totalIncomeStatement_cal = currentYear.incomeStatement.reduce((sum, item) =>
                                sum + (parseFloat(item.net_amount) || 0), 0);
                            let totalWithdrawal_cal = currentYear.withdrawals.reduce((sum, item) => sum + (
                                parseFloat(item.net_amount) || 0), 0);

                            let totalEquity_cal = totalBeginningBalance_cal + totalIncomeStatement_cal;
                            // let totalEndingBalance = (totalEquity_cal - totalWithdrawal_cal).toLocaleString(
                            //     undefined, {
                            //         minimumFractionDigits: 2
                            //     });

                            // let totalEndingBalance_cal = parseFloat(totalEquity_cal - totalWithdrawal_cal)
                            //     .toLocaleString(undefined, {
                            //         minimumFractionDigits: 2
                            //     });

                            let totalEndingBalance_cal = parseFloat(totalEquity_cal - totalWithdrawal_cal)
                                .toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });

                            // Previous year calculations (if available)
                            let prevYearBeginningBalance = previousYear ? previousYear.beginningBalance
                                .reduce((sum, item) => sum + parseFloat(item.net_amount) || 0, 0)
                                .toLocaleString(undefined, {
                                    minimumFractionDigits: 2
                                }) : '0.00';
                            let prevYearIncomeStatement = previousYear ? previousYear.incomeStatement
                                .reduce((sum, item) => sum + (parseFloat(item.net_amount) || 0), 0)
                                .toLocaleString(undefined, {
                                    minimumFractionDigits: 2
                                }) : '0.00';
                            let prevYearWithdrawal = previousYear ? previousYear.withdrawals.reduce((sum,
                                item) => sum + (parseFloat(item.net_amount) || 0), 0).toLocaleString(
                                undefined, {
                                    minimumFractionDigits: 2
                                }) : '0.00';

                            let prevYearBeginningBalance_cal = previousYear ? previousYear.beginningBalance
                                .reduce((sum, item) => sum + parseFloat(item.net_amount) || 0, 0) : 0;
                            let prevYearIncomeStatement_cal = previousYear ? previousYear.incomeStatement
                                .reduce((sum, item) => sum + (parseFloat(item.net_amount) || 0), 0) : 0;
                            let prevYearWithdrawal_cal = previousYear ? previousYear.withdrawals.reduce((
                                sum, item) => sum + (parseFloat(item.net_amount) || 0), 0) : 0;

                            let prevYearEquity_cal = prevYearBeginningBalance_cal +
                                prevYearIncomeStatement_cal;
                            // let prevYearEndingBalance = previousYear ? (prevYearEquity_cal -
                            //     prevYearWithdrawal_cal).toLocaleString(undefined, {
                            //     minimumFractionDigits: 2
                            // }) : '0.00';


                            let prevYearEndingBalance = previousYear ? parseFloat(prevYearEquity_cal -
                                prevYearWithdrawal_cal).toFixed(2) : '0.00';

                            // let prevYearEndingBalance = previousYear ? parseFloat(prevYearEquity_cal -
                            //     prevYearWithdrawal_cal).toFixed(2) : '0.00';

                            let totalEquity_beginning_bal = (totalBeginningBalance_cal +
                                    prevYearBeginningBalance_cal)
                                .toLocaleString(undefined, {
                                    minimumFractionDigits: 2
                                });



                            let totalEquity_net_income = (totalIncomeStatement_cal +
                                    prevYearIncomeStatement_cal)
                                .toLocaleString(undefined, {
                                    minimumFractionDigits: 2
                                });

                            let totalEquity_withdrawal = (totalWithdrawal_cal +
                                    prevYearWithdrawal_cal)
                                .toLocaleString(undefined, {
                                    minimumFractionDigits: 2
                                });


                            // let totalEquity_ending_bal = (totalEndingBalance_cal +
                            // prevYearEquity_cal)
                            //     .toLocaleString(undefined, {
                            //         minimumFractionDigits: 2
                            //     });

                            console.log('totalEndingBalance_cal:', totalEndingBalance_cal);
                            console.log('prevYearEndingBalance:', prevYearEndingBalance);

                            let totalEquity_ending_bal = (parseFloat(totalEquity_cal -
                                    totalWithdrawal_cal) +
                                parseFloat(prevYearEndingBalance)).toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            // .toLocaleString(undefined, {
                            //     minimumFractionDigits: 2
                            // });
                            console.log('totalEquity_ending_bal:', totalEquity_ending_bal);


                            // Create table rows
                            let row = `
                                <tr>
                                    <td style="border: 1px solid #7d7d7d;">Beginning Balance</td>
                                    
                                    <td style="border: 1px solid #7d7d7d; text-align: left;">${totalBeginningBalance}</td>
                                    <td style="border: 1px solid #7d7d7d; text-align: left;">${prevYearBeginningBalance}</td>
                                    <td style="border: 1px solid #7d7d7d; text-align: left;">${totalEquity_beginning_bal}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #7d7d7d;">Net Income</td>
                                    
                                    <td style="border: 1px solid #7d7d7d; text-align: left;">${totalIncomeStatement}</td>
                                    <td style="border: 1px solid #7d7d7d; text-align: left;">${prevYearIncomeStatement}</td>
                                    <td style="border: 1px solid #7d7d7d; text-align: left;">${totalEquity_net_income}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #7d7d7d;">Withdrawals</td>
                                
                                    <td style="border: 1px solid #7d7d7d; text-align: left;">${totalWithdrawal}</td>
                                    <td style="border: 1px solid #7d7d7d; text-align: left;">${prevYearWithdrawal}</td>
                                    <td style="border: 1px solid #7d7d7d; text-align: left;">${totalEquity_withdrawal}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #7d7d7d;">Ending Balance</td>
                                    
                                    <td style="border: 1px solid #7d7d7d; text-align: left;">${totalEndingBalance_cal}</td>
                                    <td style="border: 1px solid #7d7d7d; text-align: left;">${prevYearEndingBalance}</td>
                                    <td style="border: 1px solid #7d7d7d; text-align: left;">${totalEquity_ending_bal}</td>
                                </tr>
                            `;
                            tbody.append(row);

                        },
                        error: function() {
                            alert('Failed to load subsidiary ledger data.');
                        }
                    });
                }

                ////////////////////////////////////////////////////////////////////////

                $('#print_equity_statement').on('click', function() {
                    const dateRange = $('#dateRange').val();
                    const fiscal_year_select = $('#fiscal_year_select').val();
                    const fiscalYearText = $('#fiscal_year_select option:selected').text();


                    window.open('/bookkeeper/equity_statement_print?date_range=' + dateRange +
                        '&fiscal_year=' + fiscal_year_select +
                        '&fiscalYearText=' + fiscalYearText, '_blank');
                });



                // function loadSubsidiaryLedger() {
                //     const fiscalYear = $('#fiscal_year_select').val();
                //     const fiscalYearText = $('#fiscal_year_select option:selected').text();

                //     const accountId = $('#account_select').val();

                //     $.ajax({
                //         url: '/bookkeeper/equity-statement-fetch',
                //         type: 'GET',
                //         data: {
                //             fiscal_year: fiscalYear,
                //             fiscalYearText: fiscalYearText
                //         },
                //         success: function(data) {
                //             let tbody = $('#equity_table');
                //             tbody.empty();

                //             let totalDebit = 0;
                //             let totalCredit = 0;

                //             let totalBeginningBalance = data.beginningBalance.reduce((sum, item) => sum + parseFloat(item.net_amount)|| 0, 0).toLocaleString(undefined, { minimumFractionDigits: 2 });
                //             let totalIncomeStatement = data.incomeStatement.reduce((sum, item) => sum + (parseFloat(item.net_amount) || 0), 0).toLocaleString(undefined, { minimumFractionDigits: 2 });
                //             let totalWithdrawal = data.withdrawals.reduce((sum, item) => sum + (parseFloat(item.net_amount) || 0), 0).toLocaleString(undefined, { minimumFractionDigits: 2 });

                //             let totalBeginningBalance_cal = data.beginningBalance.reduce((sum, item) => sum + parseFloat(item.net_amount)|| 0, 0);
                //             let totalIncomeStatement_cal = data.incomeStatement.reduce((sum, item) => sum + (parseFloat(item.net_amount) || 0), 0);
                //             let totalWithdrawal_cal = data.withdrawals.reduce((sum, item) => sum + (parseFloat(item.net_amount) || 0), 0);

                //             let totalEquity_cal = totalBeginningBalance_cal + totalIncomeStatement_cal;
                //             let totalEndingBalance = (totalEquity_cal - totalWithdrawal_cal).toLocaleString(undefined, { minimumFractionDigits: 2 });

                //             let row = `
        //                 <tr>
        //                     <td style="border: 1px solid #7d7d7d;">Beginning Balance</td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;" id="beginning_balance_common_stock">
        //                         ${totalBeginningBalance}
        //                     </td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;"></td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;"></td>
        //                 </tr>
        //                 <tr>
        //                     <td style="border: 1px solid #7d7d7d;">Net Income</td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;" id="net_income_common_stock">
        //                         ${totalIncomeStatement}
        //                     </td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;"></td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;"></td>
        //                 </tr>
        //                 <tr>
        //                     <td style="border: 1px solid #7d7d7d;">Withdrawals</td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;">
        //                         ${totalWithdrawal}
        //                     </td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;"></td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;"></td>
        //                 </tr>
        //                 <tr>
        //                     <td style="border: 1px solid #7d7d7d;">Ending Balance</td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;">
        //                         ${totalEndingBalance}
        //                     </td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;"></td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;"></td>
        //                 </tr>
        //             `;
                //             tbody.append(row);
                //         },
                //         error: function() {
                //             alert('Failed to load subsidiary ledger data.');
                //         }
                //     });
                // }

                // function loadSubsidiaryLedger() {
                //     const fiscalYear = $('#fiscal_year_select').val();
                //     const fiscalYearText = $('#fiscal_year_select option:selected').text();

                //     const accountId = $('#account_select').val();

                //     $.ajax({
                //         url: '/bookkeeper/equity-statement-fetch',
                //         type: 'GET',
                //         data: {
                //             fiscal_year: fiscalYear,
                //             fiscalYearText: fiscalYearText
                //         },
                //         success: function(data) {
                //             let tbody = $('#equity_table');
                //             tbody.empty();

                //             // Process current year data
                //             let currentYear = data.currentYear;
                //             let previousYear = data.previousYear;

                //             // Current year calculations
                //             let totalBeginningBalance = currentYear.beginningBalance.reduce((sum, item) =>
                //                 sum + parseFloat(item.net_amount) || 0, 0).toLocaleString(undefined, {
                //                 minimumFractionDigits: 2
                //             });
                //             let totalIncomeStatement = currentYear.incomeStatement.reduce((sum, item) =>
                //                 sum + (parseFloat(item.net_amount) || 0), 0).toLocaleString(undefined, {
                //                 minimumFractionDigits: 2
                //             });
                //             let totalWithdrawal = currentYear.withdrawals.reduce((sum, item) => sum + (
                //                 parseFloat(item.net_amount) || 0), 0).toLocaleString(undefined, {
                //                 minimumFractionDigits: 2
                //             });

                //             let totalBeginningBalance_cal = currentYear.beginningBalance.reduce((sum,
                //                 item) => sum + parseFloat(item.net_amount) || 0, 0);
                //             let totalIncomeStatement_cal = currentYear.incomeStatement.reduce((sum, item) =>
                //                 sum + (parseFloat(item.net_amount) || 0), 0);
                //             let totalWithdrawal_cal = currentYear.withdrawals.reduce((sum, item) => sum + (
                //                 parseFloat(item.net_amount) || 0), 0);

                //             let totalEquity_cal = totalBeginningBalance_cal + totalIncomeStatement_cal;
                //             // let totalEndingBalance = (totalEquity_cal - totalWithdrawal_cal).toLocaleString(
                //             //     undefined, {
                //             //         minimumFractionDigits: 2
                //             //     });

                //             // let totalEndingBalance_cal = parseFloat(totalEquity_cal - totalWithdrawal_cal)
                //             //     .toLocaleString(undefined, {
                //             //         minimumFractionDigits: 2
                //             //     });

                //             let totalEndingBalance_cal = parseFloat(totalEquity_cal - totalWithdrawal_cal)
                //                 .toLocaleString(undefined, {
                //                     minimumFractionDigits: 2,
                //                     maximumFractionDigits: 2
                //                 });

                //             // Previous year calculations (if available)
                //             let prevYearBeginningBalance = previousYear ? previousYear.beginningBalance
                //                 .reduce((sum, item) => sum + parseFloat(item.net_amount) || 0, 0)
                //                 .toLocaleString(undefined, {
                //                     minimumFractionDigits: 2
                //                 }) : '0.00';
                //             let prevYearIncomeStatement = previousYear ? previousYear.incomeStatement
                //                 .reduce((sum, item) => sum + (parseFloat(item.net_amount) || 0), 0)
                //                 .toLocaleString(undefined, {
                //                     minimumFractionDigits: 2
                //                 }) : '0.00';
                //             let prevYearWithdrawal = previousYear ? previousYear.withdrawals.reduce((sum,
                //                 item) => sum + (parseFloat(item.net_amount) || 0), 0).toLocaleString(
                //                 undefined, {
                //                     minimumFractionDigits: 2
                //                 }) : '0.00';

                //             let prevYearBeginningBalance_cal = previousYear ? previousYear.beginningBalance
                //                 .reduce((sum, item) => sum + parseFloat(item.net_amount) || 0, 0) : 0;
                //             let prevYearIncomeStatement_cal = previousYear ? previousYear.incomeStatement
                //                 .reduce((sum, item) => sum + (parseFloat(item.net_amount) || 0), 0) : 0;
                //             let prevYearWithdrawal_cal = previousYear ? previousYear.withdrawals.reduce((
                //                 sum, item) => sum + (parseFloat(item.net_amount) || 0), 0) : 0;

                //             let prevYearEquity_cal = prevYearBeginningBalance_cal +
                //                 prevYearIncomeStatement_cal;
                //             // let prevYearEndingBalance = previousYear ? (prevYearEquity_cal -
                //             //     prevYearWithdrawal_cal).toLocaleString(undefined, {
                //             //     minimumFractionDigits: 2
                //             // }) : '0.00';


                //             let prevYearEndingBalance = previousYear ? parseFloat(prevYearEquity_cal -
                //                 prevYearWithdrawal_cal).toFixed(2) : '0.00';

                //             // let prevYearEndingBalance = previousYear ? parseFloat(prevYearEquity_cal -
                //             //     prevYearWithdrawal_cal).toFixed(2) : '0.00';

                //             let totalEquity_beginning_bal = (totalBeginningBalance_cal +
                //                     prevYearBeginningBalance_cal)
                //                 .toLocaleString(undefined, {
                //                     minimumFractionDigits: 2
                //                 });



                //             let totalEquity_net_income = (totalIncomeStatement_cal +
                //                     prevYearIncomeStatement_cal)
                //                 .toLocaleString(undefined, {
                //                     minimumFractionDigits: 2
                //                 });

                //             let totalEquity_withdrawal = (totalWithdrawal_cal +
                //                     prevYearWithdrawal_cal)
                //                 .toLocaleString(undefined, {
                //                     minimumFractionDigits: 2
                //                 });


                //             // let totalEquity_ending_bal = (totalEndingBalance_cal +
                //             // prevYearEquity_cal)
                //             //     .toLocaleString(undefined, {
                //             //         minimumFractionDigits: 2
                //             //     });

                //             console.log('totalEndingBalance_cal:', totalEndingBalance_cal);
                //             console.log('prevYearEndingBalance:', prevYearEndingBalance);

                //             let totalEquity_ending_bal = (parseFloat(totalEquity_cal -
                //                 totalWithdrawal_cal) +
                //                 parseFloat(prevYearEndingBalance)).toLocaleString('en-US', {
                //                 minimumFractionDigits: 2,
                //                 maximumFractionDigits: 2
                //             });
                //             // .toLocaleString(undefined, {
                //             //     minimumFractionDigits: 2
                //             // });
                //             console.log('totalEquity_ending_bal:', totalEquity_ending_bal);


                //             // Create table rows
                //             let row = `
        //                 <tr>
        //                     <td style="border: 1px solid #7d7d7d;">Beginning Balance</td>

        //                     <td style="border: 1px solid #7d7d7d; text-align: right;">${totalBeginningBalance}</td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;">${prevYearBeginningBalance}</td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;">${totalEquity_beginning_bal}</td>
        //                 </tr>
        //                 <tr>
        //                     <td style="border: 1px solid #7d7d7d;">Net Income</td>

        //                     <td style="border: 1px solid #7d7d7d; text-align: right;">${totalIncomeStatement}</td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;">${prevYearIncomeStatement}</td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;">${totalEquity_net_income}</td>
        //                 </tr>
        //                 <tr>
        //                     <td style="border: 1px solid #7d7d7d;">Withdrawals</td>

        //                     <td style="border: 1px solid #7d7d7d; text-align: right;">${totalWithdrawal}</td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;">${prevYearWithdrawal}</td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;">${totalEquity_withdrawal}</td>
        //                 </tr>
        //                 <tr>
        //                     <td style="border: 1px solid #7d7d7d;">Ending Balance</td>

        //                     <td style="border: 1px solid #7d7d7d; text-align: right;">${totalEndingBalance_cal}</td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;">${prevYearEndingBalance}</td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;">${totalEquity_ending_bal}</td>
        //                 </tr>
        //             `;
                //             tbody.append(row);

                //         },
                //         error: function() {
                //             alert('Failed to load subsidiary ledger data.');
                //         }
                //     });
                // }


                //                 // Add column headers if needed
                //                 $('#equity_table').before(`
        //     <thead>
        //         <tr>
        //             <th style="border: 1px solid #7d7d7d;">Description</th>
        //             <th style="border: 1px solid #7d7d7d; text-align: right;">Previous Year</th>
        //             <th style="border: 1px solid #7d7d7d; text-align: right;">Current Year</th>
        //             <th style="border: 1px solid #7d7d7d; text-align: right;">Difference</th>
        //         </tr>
        //     </thead>
        // `);


            });
        </script>
    @endsection
</body>
