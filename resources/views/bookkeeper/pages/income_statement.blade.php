@extends('bookkeeper.layouts.app')

@section('pagespecificscripts')

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Income Statement</title>


        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
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
        </style>
    </head>
@endsection

@section('content')
    @php
        $fiscal = DB::table('bk_fiscal_year')->where('deleted', 0)->get();
        $activeFiscalYear = DB::table('bk_fiscal_year')->where('isactive', 1)->where('deleted', 0)->first();
        $activeFiscalYearId = isset($activeFiscalYear) ? $activeFiscalYear->id : null;
    @endphp

    <body>
        <div class="container-fluid ml-3">
            <div>
                <div class="d-flex align-items-center">
                    <i class="fas fa-landmark fa-2x mr-2 mt-3"></i>
                    <div style="display: flex; align-items: center;" class="mt-3">
                        <h3 style="margin: 0;">Income Statement</h3>
                    </div>
                </div>
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item" style="font-size: 0.8rem;"><a href="#"
                                style="color:rgba(0,0,0,0.5);">Home</a></li>
                        <li class="breadcrumb-item active" style="font-size: 0.8rem; color:rgba(0,0,0,0.5);">Income
                            Statement
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="mb-3" style="color: black;  font-size: 13px;">
                <ul class="nav nav-tabs" style="border-bottom: 4px solid #d9d9d9;;">
                    <li class="nav-item">
                        <a class="nav-link active" href="/bookkeeper/income_statement"
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
                        <a class="nav-link" href="/bookkeeper/equity_statement"
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
                                    type="text" name="date_filter" value="" placeholder="mm/dd/yyyy - mm/dd/yyyy"
                                    class="form-control">
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
                                style="width: 90%; height: 30px; font-size: 12.5px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);"
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
                                <button class="btn btn-sm px-2" id="print_income_statement"
                                    style="background-color: #143893; color: white;">
                                    <i class="fas fa-print"></i> PRINT
                                </button>
                            </div>
                        </div>
                        <table id="accounting_supplier_table" class="table table-sm table-bordered w-100">
                            <thead class="table-secondary">
                                <tr>
                                    <th class="text-start">Classification</th>
                                    <th class="text-start">Account</th>
                                    <th class="text-start">Amount</th>
                                    <th class="text-start">Total</th>
                                </tr>
                            </thead>
                            <tbody id="IncomeStatementBody">
                                <!-- Dynamic rows go here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
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

        const $dateFilter = $('input[name="date_filter"]');
        // // Define default start and end dates for current month
        // const startOfMonth = moment().startOf('month');
        // const endOfMonth = moment().endOf('month');

        // // Initialize daterangepicker
        // $dateFilter.daterangepicker({
        //     autoUpdateInput: false,
        //     startDate: startOfMonth,
        //     endDate: endOfMonth,
        //     locale: {
        //         format: 'YYYY-MM-DD',
        //         cancelLabel: 'Clear'
        //     }
        // });

        // // Set input value and load data on apply
        // $dateFilter.on('apply.daterangepicker', function(ev, picker) {
        //     $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
        //         'YYYY-MM-DD'));
        //     loadIncomeStatement();
        // });

        // // Clear input and reload data on cancel
        // $dateFilter.on('cancel.daterangepicker', function() {
        //     $(this).val('');
        //     loadIncomeStatement();
        // });

        // // On page load: set initial value and load income statement
        // $dateFilter.val(startOfMonth.format('YYYY-MM-DD') + ' - ' + endOfMonth.format('YYYY-MM-DD'));

        $('#fiscal_year_select').on('change', loadIncomeStatement);
        $dateFilter.on('change', loadIncomeStatement);

        $('#print_income_statement').on('click', function() {
            const fiscalYear = $('#fiscal_year_select').val();
            const dateRange = $('input[name="date_filter"]').val();

            window.open('/bookkeeper/print/income-statement?fiscal_year=' + fiscalYear +
                '&date_range=' + encodeURIComponent(dateRange), '_blank');
        });


        function loadIncomeStatement() {
            const fiscalYearId = $('#fiscal_year_select').val();
            const dateRange = $dateFilter.val();

            $.ajax({
                url: '/bookkeeper/income-statement',
                method: 'GET',
                data: {
                    fiscal_year_id: fiscalYearId,
                    date_range: dateRange
                },
                success: function (data) {
                    const tbody = $('#IncomeStatementBody');
                    tbody.empty();

                    const revenues = data.revenues || [];
                    const expenses = data.expenses || [];
                    const totals = data.totals || {};

                    let totalRevenue = 0;
                    let totalExpenses = 0;

                    // --- Revenues ---
                    tbody.append(`
                        <tr class="fw-bold">
                            <td colspan="4" class="text-start">REVENUE</td>
                        </tr>
                    `);
                    revenues.forEach(item => {
                        const amount = parseFloat(item.amount || 0);
                        totalRevenue += amount;

                        const formattedAmount = amount < 0
                            ? `(${Math.abs(amount).toLocaleString(undefined, { minimumFractionDigits: 2 })})`
                            : amount.toLocaleString(undefined, { minimumFractionDigits: 2 });

                        tbody.append(`
                            <tr>
                                <td></td>
                                <td>${item.code ?? ''} - ${item.account_name ?? 'N/A'}</td>
                                <td>${formattedAmount}</td>
                                <td></td>
                            </tr>
                        `);
                    });

                    tbody.append(`
                        <tr class="fw-bold bg-light">
                            <td colspan="3">Total Revenue</td>
                            <td>${totalRevenue.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                        </tr>
                    `);

                    // --- Expenses ---
                    tbody.append(`
                        <tr class="fw-bold mt-3">
                            <td colspan="4" class="text-start">EXPENSES</td>
                        </tr>
                    `);
                    expenses.forEach(item => {
                        const amount = parseFloat(item.amount || 0);
                        totalExpenses += amount;

                        const formattedAmount = amount < 0
                            ? `(${Math.abs(amount).toLocaleString(undefined, { minimumFractionDigits: 2 })})`
                            : amount.toLocaleString(undefined, { minimumFractionDigits: 2 });

                        tbody.append(`
                            <tr>
                                <td></td>
                                <td>${item.code ?? ''} - ${item.account_name ?? 'N/A'}</td>
                                <td>${formattedAmount}</td>
                                <td></td>
                            </tr>
                        `);
                    });

                    tbody.append(`
                        <tr class="fw-bold bg-light">
                            <td colspan="3">Total Expenses</td>
                            <td>${totalExpenses.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                        </tr>
                    `);

                    // --- Net Income ---
                    const netIncome = parseFloat(totals.net_income || (totalRevenue - totalExpenses));
                    const formattedNetIncome = netIncome < 0
                        ? `(${Math.abs(netIncome).toLocaleString(undefined, { minimumFractionDigits: 2 })})`
                        : netIncome.toLocaleString(undefined, { minimumFractionDigits: 2 });

                    tbody.append(`
                        <tr class="fw-bold text-success">
                            <td colspan="3" class="text-end">NET INCOME</td>
                            <td>${formattedNetIncome}</td>
                        </tr>
                    `);
                },
                error: function () {
                    alert('Failed to load income statement.');
                }
            });
        }

        var fiscalyear = [];
        loadFiscalActive();

        function loadFiscalActive(callback) {
            $.ajax({
                type: "GET",
                url: '/bookkeeper/loadFiscal',
                success: function (data) {
                    fiscalyear = data;
                    console.log(fiscalyear);
                    callback(); // run after fiscal data is loaded
                }
            });
        }

        loadFiscalActive(function () {
            const fiscalStartDate = moment(fiscalyear[0].stime);
            const today = moment();

            $dateFilter.daterangepicker({
                autoUpdateInput: false,
                startDate: fiscalStartDate,
                endDate: today,
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Clear'
                }
            });

            $dateFilter.on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                loadIncomeStatement();
            });

            $dateFilter.on('cancel.daterangepicker', function () {
                $(this).val('');
                loadIncomeStatement();
            });

            $dateFilter.val(fiscalStartDate.format('YYYY-MM-DD') + ' - ' + today.format('YYYY-MM-DD'));
            loadIncomeStatement();
        });

    });
</script>
@endsection
