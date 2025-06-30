@extends('bookkeeper.layouts.app')
@section('pagespecificscripts')

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Trial Balance</title>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

        <!-- jQuery first -->
        <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

        <!-- Bootstrap 4 -->
        <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
        <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

        <!-- Select2 -->
        <script src="{{ asset('plugins/select2/js/select2.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.css') }}">

        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('plugins/datatables/css/dataTables.bootstrap5.min.css') }}">
        <script src="{{ asset('plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables/js/dataTables.bootstrap5.min.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.css') }}">
        <script src="{{ asset('plugins/datatables-fixedheader/js/fixedHeader.bootstrap4.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
        <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
        </style>

    </head>
@endsection

@section('content')
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            color: white;
            padding-top: 4px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #0b5ed7;
            border-color: #0a58ca;
            color: white;
        }

        .custom-select2-dropdown.select2-dropdown {
            background-color: #0d6efd;
            color: white;
            border: 1px solid #0d6efd;
        }

        .custom-select2-dropdown .select2-results__option {
            color: white;
        }

        .custom-select2-dropdown .select2-results__option--highlighted {
            background-color: #0b5ed7;
            color: white;
        }
    </style>

    @php
        $fiscal = DB::table('bk_fiscal_year')->where('deleted', 0)->get();
        $activeFiscalYear = DB::table('bk_fiscal_year')->where('isactive', 1)->where('deleted', 0)->first();
        $activeFiscalYearId = isset($activeFiscalYear) ? $activeFiscalYear->id : null;

        $coa = DB::table('chart_of_accounts')->get();
    @endphp

    <body>
        <div class="container-fluid ml-3">
            <div>
                <div class="d-flex align-items-center">
                    <i class="fas fa-landmark fa-2x mr-2 mt-3"></i>
                    <div style="display: flex; align-items: center;" class="mt-3">
                        <h3 style="margin: 0;">Trial Balance</h3>
                    </div>
                </div>
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item" style="font-size: 0.8rem;"><a href="#"
                                style="color:rgba(0,0,0,0.5);">Home</a></li>
                        <li class="breadcrumb-item active" style="font-size: 0.8rem; color:rgba(0,0,0,0.5);">Trial Balance
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="card p-3" style="border-radius: 5px; background-color:#f1f1f1; border:none; width: 98%;">
                <h6 style="font-size: 13px;"><i class="fas fa-filter"></i> Filter</h6>
                <div class="row" style="margin-left: 12px;">
                    <div class="col-md-4">
                        <label style="font-size: 13px; font-weight: 500;">Date Range</label>
                        <div class="input-group" style="width: 100%;">
                            <div class="input-group" style="width: 100%;">
                                <input
                                    style="font-size: 11px; height: 30px; border-radius: 5px; border:none; padding-right: 30px; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);"
                                    type="text" name="date_filter" value="" placeholder="mm/dd/yyyy - mm/dd/yyyy"
                                    class="form-control" id="date_range_input">
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
                    <div class="col-md-6 mb-3">
                        <label for="fiscal_year_select" class="form-label fw-semibold small">Fiscal Year</label>
                        <div class="input-group">
                            <select id="fiscal_year_select" name="fiscal_year[]" multiple
                                class="form-select select2 bg-primary text-white" style="width: 90%;">
                                @foreach ($fiscal as $year)
                                    <option value="{{ $year->id }}"
                                        {{ $activeFiscalYearId == $year->id ? 'selected' : '' }}>
                                        {{ $year->description }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button type="button" id="reloadButton" class="btn btn-outline-success"
                                    title="Reload Fiscal Years">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card mt-4" style="background-color: #f1f1f1; border:none; width: 98%;">
                <div class="card-header d-flex justify-content-between align-items-center" style="font-size: 12.5px;">
                    <!-- Left Section: Button -->
                    <div>
                        <button type="button" class="btn btn-outline-secondary rounded d-flex align-items-center"
                            id="printTrialBalance"
                            style="height: 25px; font-size: 12px; font-family: Arial, sans-serif; border:none; background-color: #043b90; color: white; font-weight:  normal;">
                            <i class="fas fa-print mr-1"></i> PRINT
                        </button>
                    </div>
                    <!-- Right Section: Select & Search -->
                    <div class="d-flex align-items-center ml-auto">
                        <!-- Search Input -->
                        <div class="input-group">
                            <input type="text" class="form-control" id="trialBalanceSearch" placeholder="Search"
                                style="font-size: 12px; height: 30px; width: 200px; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42); border:none;">
                            <div class="input-group-append">
                                <span class="input-group-text"
                                    style="background-color: white; border: none; margin-left: -30px;">
                                    <i class="fas fa-search"
                                        style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tables Container -->
                <div class="card-body" id="tableContainer" style="display: flex; gap: 10px;">
                    <!-- First Table -->
                    <table class="table table-bordered table-sm" style="border-right: 1px solid #7d7d7d; flex: 1;">
                        <thead style="font-size: 12px; background-color: #b9b9b9;">
                            <tr>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 8%;">Code</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 60%;">Account</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 10%;">Debit</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 10%;">Credit</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 12%;">Ending Balance</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 12px;" id="firstTableBody">

                        </tbody>
                    </table>

                    <!-- Second Table (Initially Hidden) -->
                    <table id="secondTable" class="table table-bordered table-sm"
                        style="border-right: 1px solid #7d7d7d; flex: 1; display: none;">
                        <thead style="font-size: 12px; background-color: #b9b9b9;">
                            <tr>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 8%;">Code</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 55%;">Account</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 10%;">Debit</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 10%;">Credit</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 1%;">Ending Balance</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 12px;" id="secondTableBody">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
@endsection

@section('footerjavascript')
    <script>
        $(document).ready(function() {
            const $dateFilter = $('input[name="date_filter"]');
            // $('input[name="date_filter"]').daterangepicker({
            //     autoUpdateInput: false,
            //     locale: {
            //         cancelLabel: 'Clear'
            //     }
            // });

            ///////////////////////////////
            // $('input[name="date_filter"]').on('apply.daterangepicker', function(ev, picker) {
            //     $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
            //         'MM/DD/YYYY'));
            // });

            // $('input[name="date_filter"]').on('cancel.daterangepicker', function(ev, picker) {
            //     $(this).val('');
            // });

            // $('#reloadButton').on('click', function(e) {
            //     e.preventDefault();

            //     var fiscalYearIds = $('#fiscal_year_select').val();

            //     if (fiscalYearIds.length === 0) {
            //         Toast.fire({
            //             type: 'warning',
            //             title: 'Please select at least one fiscal year.',
            //             timer: 2000
            //         });
            //         return;
            //     }

            //     if (fiscalYearIds.length >= 2 && fiscalYearIds.length <= 3) {
            //         $.ajax({
            //             url: '/bookkeeper/compare-trial-balance',
            //             method: 'GET',
            //             data: {
            //                 fiscal_year: fiscalYearIds
            //             },
            //             success: function(data) {
            //                 $('#secondTable').show();

            //                 if (fiscalYearIds.length === 3) {
            //                     if ($('#thirdTable').length === 0) {
            //                         var thirdTable = $('#firstTable').clone().attr('id',
            //                             'thirdTable');
            //                         thirdTable.find('tbody').attr('id', 'thirdTableBody');
            //                         $('#secondTable').after(thirdTable);
            //                     } else {
            //                         $('#thirdTable').show();
            //                     }
            //                 } else {
            //                     $('#thirdTable').hide();
            //                 }

            //                 var groupedData = {};
            //                 data.forEach(row => {
            //                     if (!groupedData[row.active_fiscal_year_id]) {
            //                         groupedData[row.active_fiscal_year_id] = [];
            //                     }
            //                     groupedData[row.active_fiscal_year_id].push(row);
            //                 });

            //                 var keys = Object.keys(groupedData);

            //                 renderTrialBalanceTable(groupedData[keys[0]], '#firstTableBody');
            //                 renderTrialBalanceTable(groupedData[keys[1]], '#secondTableBody');

            //                 if (keys.length === 3) {
            //                     renderTrialBalanceTable(groupedData[keys[2]],
            //                         '#thirdTableBody');
            //                 }
            //             }
            //         });
            //     } else if (fiscalYearIds.length === 1) {
            //         $('#secondTable').hide();
            //         $('#thirdTable').hide();

            //         $.ajax({
            //             url: '/bookkeeper/trial-balance',
            //             method: 'GET',
            //             data: {
            //                 fiscal_year: fiscalYearIds
            //             },
            //             success: function(data) {
            //                 renderTrialBalanceTable(data, '#firstTableBody');
            //             }
            //         });
            //     } else {
            //         Toast.fire({
            //             type: 'warning',
            //             title: 'Please select at most 3 fiscal years to compare.',
            //             timer: 2000
            //         });
            //         return;
            //     }
            // });

            // function renderTrialBalanceTable(data, tableSelector) {
            //     var tbody = '';
            //     var totalDebit = 0;
            //     var totalCredit = 0;
            //     var totalEnding = 0;

            //     data.forEach(row => {
            //         var debit = parseFloat(row.debit_amount);
            //         var credit = parseFloat(row.credit_amount);
            //         var ending = parseFloat(row.ending_balance);

            //         totalDebit += debit;
            //         totalCredit += credit;
            //         totalEnding += ending;

            //         tbody += `
        //             <tr>
        //                 <td style="border: 1px solid #7d7d7d;">${row.code}</td>
        //                 <td style="border: 1px solid #7d7d7d;">${row.classification}</td>
        //                 <td style="border: 1px solid #7d7d7d; text-align: right;">${debit.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
        //                 <td style="border: 1px solid #7d7d7d; text-align: right;">${credit.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
        //                 <td style="border: 1px solid #7d7d7d; text-align: right;">${ending.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
        //             </tr>
        //         `;
            //     });

            //     tbody += `
        //             <tr style="font-weight: bold; background-color: #f5f5f5;">
        //                 <td colspan="2" style="border: 1px solid #7d7d7d; text-align: right;">TOTAL</td>
        //                 <td style="border: 1px solid #7d7d7d; text-align: right;">${totalDebit.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
        //                 <td style="border: 1px solid #7d7d7d; text-align: right;">${totalCredit.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
        //                 <td style="border: 1px solid #7d7d7d; text-align: right;">${totalEnding.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
        //             </tr>
        //         `;

            //     $(tableSelector).html(tbody);
            // }

            // $('#fiscal_year_select').select2({
            //     closeOnSelect: false,
            //     templateResult: function(data) {
            //         if (!data.id) return data.text;
            //         var checkbox = $('<span><input type="checkbox" style="margin-right: 6px;" />' + data
            //             .text + '</span>');
            //         return checkbox;
            //     },
            //     templateSelection: function(data) {
            //         return data.length ? data.map(d => d.text).join(', ') : data.text;
            //     }
            // });

            // $('#fiscal_year_select').on('select2:select select2:unselect', function(e) {
            //     setTimeout(function() {
            //         $('#fiscal_year_select').select2('open');
            //     }, 0);
            // });

            // $('#fiscal_year_select, input[name="date_filter"]').on('change', function() {
            //     var fiscalYearIds = $('#fiscal_year_select').val();
            //     var dateRange = $('input[name="date_filter"]').val();

            //     let requestData = {};

            //     if (fiscalYearIds && fiscalYearIds.length > 0) {
            //         requestData.fiscal_year = fiscalYearIds;
            //     }

            //     if (dateRange && dateRange.includes(' - ')) {
            //         requestData.date_range = dateRange;
            //     }

            //     $.ajax({
            //         url: '/bookkeeper/trial-balance',
            //         method: 'GET',
            //         data: requestData,
            //         success: function(data) {
            //             console.log('Returned Data:', data); // Debug
            //             renderTrialBalanceTable(data, '#firstTableBody');
            //         },
            //         error: function(xhr) {
            //             console.error('Error fetching trial balance:', xhr.responseText);
            //         }
            //     });
            // });
            ///////////////////////////

            // Initialize the date range picker
            // $('input[name="date_filter"]').daterangepicker({
            //     autoUpdateInput: false,
            //     locale: {
            //         cancelLabel: 'Clear'
            //     }
            // });

            // // Handle when date range is applied
            // $('input[name="date_filter"]').on('apply.daterangepicker', function(ev, picker) {
            //     $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
            //         'MM/DD/YYYY'));
            //     $(this).trigger('change'); // Trigger change event to reload data
            // });

            // // Handle when date range is cleared
            // $('input[name="date_filter"]').on('cancel.daterangepicker', function(ev, picker) {
            //     $(this).val('');
            //     $(this).trigger('change'); // Trigger change event to reload data
            // });


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
                    loadBalanceSheet();
                });

                $dateFilter.on('cancel.daterangepicker', function () {
                    $(this).val('');
                    loadBalanceSheet();
                });

                $dateFilter.val(fiscalStartDate.format('YYYY-MM-DD') + ' - ' + today.format('YYYY-MM-DD'));
                loadBalanceSheet();
            });


            $('#reloadButton').on('click', function(e) {
                e.preventDefault();

                var fiscalYearIds = $('#fiscal_year_select').val();
                var dateRange = $('input[name="date_filter"]').val();

                if (fiscalYearIds.length === 0) {
                    Toast.fire({
                        type: 'warning',
                        title: 'Please select at least one fiscal year.',
                        timer: 2000
                    });
                    return;
                }

                let requestData = {
                    fiscal_year: fiscalYearIds
                };

                // Add date range to request if specified
                if (dateRange && dateRange.includes(' - ')) {
                    requestData.date_range = dateRange;
                }

                if (fiscalYearIds.length >= 2 && fiscalYearIds.length <= 3) {
                    $.ajax({
                        url: '/bookkeeper/compare-trial-balance',
                        method: 'GET',
                        data: requestData,
                        success: function(data) {
                            $('#secondTable').show();

                            if (fiscalYearIds.length === 3) {
                                if ($('#thirdTable').length === 0) {
                                    var thirdTable = $('#firstTable').clone().attr('id',
                                        'thirdTable');
                                    thirdTable.find('tbody').attr('id', 'thirdTableBody');
                                    $('#secondTable').after(thirdTable);
                                } else {
                                    $('#thirdTable').show();
                                }
                            } else {
                                $('#thirdTable').hide();
                            }

                            var groupedData = {};
                            data.forEach(row => {
                                if (!groupedData[row.active_fiscal_year_id]) {
                                    groupedData[row.active_fiscal_year_id] = [];
                                }
                                groupedData[row.active_fiscal_year_id].push(row);
                            });

                            var keys = Object.keys(groupedData);

                            renderTrialBalanceTable(groupedData[keys[0]], '#firstTableBody');
                            renderTrialBalanceTable(groupedData[keys[1]], '#secondTableBody');

                            if (keys.length === 3) {
                                renderTrialBalanceTable(groupedData[keys[2]],
                                '#thirdTableBody');
                            }
                        }
                    });
                } else if (fiscalYearIds.length === 1) {
                    $('#secondTable').hide();
                    $('#thirdTable').hide();

                    $.ajax({
                        url: '/bookkeeper/trial-balance',
                        method: 'GET',
                        data: requestData,
                        success: function(data) {
                            renderTrialBalanceTable(data, '#firstTableBody');
                        }
                    });
                } else {
                    Toast.fire({
                        type: 'warning',
                        title: 'Please select at most 3 fiscal years to compare.',
                        timer: 2000
                    });
                    return;
                }
            });

            function renderTrialBalanceTable(data, tableSelector) {
                var tbody = '';
                var totalDebit = 0;
                var totalCredit = 0;
                var totalEnding = 0;

                data.forEach(row => {
                    var debit = parseFloat(row.debit_amount);
                    var credit = parseFloat(row.credit_amount);
                    var ending = parseFloat(row.ending_balance);

                    totalDebit += debit;
                    totalCredit += credit;
                    totalEnding += ending;

                    tbody += `
                        <tr>
                            <td style="border: 1px solid #7d7d7d;">${row.code}</td>
                            <td style="border: 1px solid #7d7d7d;">${row.classification}</td>
                            <td style="border: 1px solid #7d7d7d; text-align: right;">${debit.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                            <td style="border: 1px solid #7d7d7d; text-align: right;">${credit.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                            <td style="border: 1px solid #7d7d7d; text-align: right;">${ending.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                        </tr>
                    `;
                });

                tbody += `
                        <tr style="font-weight: bold; background-color: #f5f5f5;">
                            <td colspan="2" style="border: 1px solid #7d7d7d; text-align: right;">TOTAL</td>
                            <td style="border: 1px solid #7d7d7d; text-align: right;">${totalDebit.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                            <td style="border: 1px solid #7d7d7d; text-align: right;">${totalCredit.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                            <td style="border: 1px solid #7d7d7d; text-align: right;">${totalEnding.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                        </tr>
                    `;          

                $(tableSelector).html(tbody);
            }

            $('#fiscal_year_select').select2({
                closeOnSelect: false,
                templateResult: function(data) {
                    if (!data.id) return data.text;
                    var checkbox = $('<span><input type="checkbox" style="margin-right: 6px;" />' + data
                        .text + '</span>');
                    return checkbox;
                },
                templateSelection: function(data) {
                    return data.length ? data.map(d => d.text).join(', ') : data.text;
                }
            });

            $('#fiscal_year_select').on('select2:select select2:unselect', function(e) {
                setTimeout(function() {
                    $('#fiscal_year_select').select2('open');
                }, 0);
            });

            $('#fiscal_year_select, input[name="date_filter"]').on('change', function() {
                var fiscalYearIds = $('#fiscal_year_select').val();
                var dateRange = $('input[name="date_filter"]').val();

                let requestData = {};

                if (fiscalYearIds && fiscalYearIds.length > 0) {
                    requestData.fiscal_year = fiscalYearIds;
                }

                if (dateRange && dateRange.includes(' - ')) {
                    requestData.date_range = dateRange;
                }

                // Only make the request if we have at least one filter
                if (fiscalYearIds && fiscalYearIds.length > 0) {
                    $.ajax({
                        url: fiscalYearIds.length === 1 ? '/bookkeeper/trial-balance' :
                            '/bookkeeper/compare-trial-balance',
                        method: 'GET',
                        data: requestData,
                        success: function(data) {
                            if (fiscalYearIds.length === 1) {
                                $('#secondTable').hide();
                                $('#thirdTable').hide();
                                renderTrialBalanceTable(data, '#firstTableBody');
                            } else {
                                $('#secondTable').show();

                                if (fiscalYearIds.length === 3) {
                                    if ($('#thirdTable').length === 0) {
                                        var thirdTable = $('#firstTable').clone().attr('id',
                                            'thirdTable');
                                        thirdTable.find('tbody').attr('id', 'thirdTableBody');
                                        $('#secondTable').after(thirdTable);
                                    } else {
                                        $('#thirdTable').show();
                                    }
                                } else {
                                    $('#thirdTable').hide();
                                }

                                var groupedData = {};
                                data.forEach(row => {
                                    if (!groupedData[row.active_fiscal_year_id]) {
                                        groupedData[row.active_fiscal_year_id] = [];
                                    }
                                    groupedData[row.active_fiscal_year_id].push(row);
                                });

                                var keys = Object.keys(groupedData);
                                renderTrialBalanceTable(groupedData[keys[0]],
                                '#firstTableBody');
                                renderTrialBalanceTable(groupedData[keys[1]],
                                    '#secondTableBody');

                                if (keys.length === 3) {
                                    renderTrialBalanceTable(groupedData[keys[2]],
                                        '#thirdTableBody');
                                }
                            }
                        },
                        error: function(xhr) {
                            console.error('Error fetching trial balance:', xhr.responseText);
                        }
                    });
                }
            });

            $('#fiscal_year_select').trigger('change');





            $('#fiscal_year_select').trigger('change');

            $('#trialBalanceSearch').on('keyup', function() {
                const value = $(this).val().toLowerCase();

                $('#firstTableBody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });

                if ($('#secondTable').is(':visible')) {
                    $('#secondTableBody tr').filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                }
            });

            $('#printTrialBalance').on('click', function() {
                var fiscalYear = $('#fiscal_year_select').val(); // should be an array (multi-select)
                var dateRange = $('#date_range_input').val(); // e.g. "01/01/2024 - 12/31/2024"

                var query = $.param({
                    fiscal_year: fiscalYear, // Laravel will treat it as array[]
                    date_range: dateRange
                });

                window.open('/bookkeeper/print/trialbalance?' + query, '_blank');
            });

        });
    </script>
@endsection
