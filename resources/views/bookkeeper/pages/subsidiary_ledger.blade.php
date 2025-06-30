@extends('bookkeeper.layouts.app')
@section('pagespecificscripts')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<style>
    .select2-container--default .select2-selection--single {
        font-size: 14px !important;
        box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);
        height: auto;
    }

    .select2-selection__rendered {
        padding-top: 10px;
    }

    .select2-container--default .select2-results__option {
        font-size: 14px;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #444;
        line-height: 11px !important;
    }

    /* Parent accounts */
    .select2-container--default .select2-results__option[aria-selected="true"] {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    /* General option style */
    .select2-container--default .select2-results__option {
        color: #212529; /* Make sure text is visible */
    }

    /* Child accounts - indented */
    .select2-container--default .select2-results__option .child-account {
        padding-left: 20px;
        display: block;
    }

    /* Orphaned accounts */
    .select2-container--default .select2-results__option .orphan-account {
        color: #999;
        font-style: italic;
    }

    /* Selected item display */
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 28px;
        color: #212529; /* Ensure selected text is visible */
    }

    /* Child account */
    .select2-results__option .child-account {
        padding-left: 20px;
        display: block;
        color: #212529;
        opacity: 1;
    }

    /* Parent account */
    .select2-results__option .parent-account {
        font-weight: bold;
        color: #212529;
        opacity: 1;
    }
</style>
@endsection

@section('content')
    @php
        $fiscal = DB::table('bk_fiscal_year')->where('deleted', 0)->get();
        $activeFiscalYear = DB::table('bk_fiscal_year')->where('isactive', 1)->where('deleted', 0)->first();
        $activeFiscalYearId = isset($activeFiscalYear) ? $activeFiscalYear->id : null;

        $coa = DB::table('chart_of_accounts')->get();
    @endphp

    <body style="font-family: 'Arial', sans-serif;">
        <div class="container-fluid ml-3">
            <div>
                <div class="d-flex align-items-center">
                    <i class="fas fa-landmark fa-2x mr-2 mt-3"></i>
                    <div style="display: flex; align-items: center;" class="mt-3">
                        <h3 style="margin: 0;">Subsidiary Ledger</h3>
                    </div>
                </div>
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item" style="font-size: 0.8rem;"><a href="#"
                                style="color:rgba(0,0,0,0.5);">Home</a></li>
                        <li class="breadcrumb-item active" style="font-size: 0.8rem; color:rgba(0,0,0,0.5);">Subsidiary
                            Ledger
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="card p-3" style="border-radius: 5px; background-color:#f1f1f1; border:none; width: 98%;">
                <h6 style="font-size: 13px; font-weight:600;"><i class="fas fa-filter"></i> Filter</h6>
                <div class="row" style="margin-left: 12px;">
                    <div class="col-md-3">
                        <label style="font-size: 13px; font-weight: 500;">Date Range</label>
                        <div class="input-group" style="width: 100%;">
                            <div class="input-group" style="width: 100%;">
                                <div class="input-group" style="width: 100%;">
                                    <input
                                        style="font-size: 11px; height: 30px; border-radius: 5px; border:none; padding-right: 30px; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);"
                                        type="text" id="date_range" name="date_range" value=""
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
                    </div>
                    <div class="col-md-3">
                        <label style="font-size: 13px; font-weight: 500;">Fiscal Year</label>
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
                    </div>
                    <div class="col-md-3">
                        <label style="font-size: 13px; font-weight: 500;">Accounts</label>
                        {{-- <select id="account_select" class="form-control"
                            style="width: 100%; height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                            <option value="">All</option>
                            @foreach ($coa as $item)
                                <option value="{{ $item->id }}">{{ $item->code }} - {{ $item->account_name }}
                                </option>
                            @endforeach
                        </select> --}}
                        <select class="form-control form-control-sm select2" id="account_select"></select>
                    </div>
                    <div class="col-md-3">
                        <label style="font-size: 13px; font-weight: 500;">Cost Center</label>
                        <select class="form-control form-control-sm select2" id="costcenter_select"></select>
                    </div>
                </div>
            </div>

            <div class="card mt-4" style="background-color: #f1f1f1; border:none; width: 98%;">
                <div class="card-header d-flex justify-content-between align-items-center" style="font-size: 12.5px;">
                    <!-- Left Section: Buttons -->
                    <div>
                        <button type="button" class="btn btn-outline-secondary rounded d-flex align-items-center"
                            style="height: 25px; font-size: 12px; font-family: Arial, sans-serif; border:none; background-color: #043b90; color: white; font-weight:  normal;">
                            <i class="fas fa-print mr-1"></i>
                            <span class="d-flex align-items-center" id="print_subsidiary_ledger">PRINT</span>
                        </button>
                    </div>

                    <!-- Right Section: Select & Search -->
                    {{-- <div class="d-flex align-items-center ml-auto">
                        <!-- Search Input -->
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search" id="ledgerSearch"
                                style="font-size: 12px; height: 30px; width: 200px; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42); border:none;">
                            <div class="input-group-append">
                                <span class="input-group-text"
                                    style="background-color: white; border: none; margin-left: -30px;">
                                    <i class="fas fa-search"
                                        style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);"></i>
                                </span>
                            </div>
                        </div>
                    </div> --}}
                </div>

                <div class="card-body">
                    {{-- <table class="table table-bordered table-sm" style="border-right: 1px solid #7d7d7d;">
                        <thead style="font-size: 12px; background-color: #b9b9b9;">
                            <tr>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 9% ">Voucher No.</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 9%">Date</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 26%">Explanation</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 9%">Code</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 20%">Account </th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width:9%">Debit</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d;; width:9%">Credit</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d;; width:9%">Balance</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 12px;" id = "subsidiary_ledger">

                        </tbody>
                    </table>
                    <div id="pagination_controls" class="mt-3 text-right"></div> --}}

                    <table id="subsidiary_ledger_table" class="table table-striped table-bordered" style="width:100%; border: 1px solid #7d7d7d;">
                        <thead width="100%" style="font-size: 12px; background-color: #b9b9b9;">
                            <tr>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 9%">Voucher No.</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 12%">Date</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 26%">Explanation</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 9%">Code</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 20%">Account</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width:8%">Debit</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d;; width:8%">Credit</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d;; width:8%">Balance</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 12px;"></tbody>
                        <tfoot></tfoot>
                    </table>
                </div>
            </div>
        </div>
    </body>
@endsection
@section('footerjavascript')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script>
    $(document).ready(function() {
        select_accounts()

        $('#date_range').daterangepicker({
            opens: 'right',
            autoUpdateInput: false,
            locale: {
                format: 'MM/DD/YYYY'
                
            }
        });

        const $dateFilter = $('input[name="date_range"]');

        let allRows = [];
        let totalDebit = 0;
        let totalCredit = 0;
        let totalRow = '';

        const itemsPerPage = 25;
        let currentPage = 1;

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
                loadSubsidiaryLedger();
            });

            $dateFilter.on('cancel.daterangepicker', function () {
                $(this).val('');
                loadSubsidiaryLedger();
            });

            $dateFilter.val(fiscalStartDate.format('YYYY-MM-DD') + ' - ' + today.format('YYYY-MM-DD'));
            loadSubsidiaryLedger();
        });
        // const formatAmount = (val) => {
        //     const num = parseFloat(val) || 0;
        //     return num < 0 ?
        //         `(${Math.abs(num).toLocaleString(undefined, { minimumFractionDigits: 2 })})` :
        //         num.toLocaleString(undefined, {
        //             minimumFractionDigits: 2
        //         });
        // };

        // const createRow = (date, ref, desc, code, account, debit, credit) => {
        //     const balance = debit - credit;
        //     totalDebit += debit;
        //     totalCredit += credit;

        //     return `
        //         <tr>
        //             <td style="border: 1px solid #7d7d7d;">${ref}</td>
        //             <td style="border: 1px solid #7d7d7d;">${date}</td>
        //             <td style="border: 1px solid #7d7d7d;">${desc}</td>
        //             <td style="border: 1px solid #7d7d7d;">${code}</td>
        //             <td style="border: 1px solid #7d7d7d;">${account}</td>
        //             <td style="border: 1px solid #7d7d7d; text-align: right;">${formatAmount(debit)}</td>
        //             <td style="border: 1px solid #7d7d7d; text-align: right;">${formatAmount(credit)}</td>
        //             <td style="border: 1px solid #7d7d7d; text-align: right;">${formatAmount(balance)}</td>
        //         </tr>
        //     `;
        //             };

        //             const addSectionHeader = (label) => {
        //                 return `
        //         <tr style="background-color: #e9ecef; font-weight: bold;">
        //             <td colspan="8" style="border: 1px solid #7d7d7d;">${label}</td>
        //         </tr>
        //     `;
        // };
        // function createRow(date, voucher, remarks, code, account, debit, credit) {
        //     const searchText = `${date} ${voucher} ${remarks} ${code} ${account}`.toLowerCase().replace(/\s+/g, ' ');
        //     totalDebit += debit;
        //     totalCredit += credit;
        //     const balance = debit - credit;
        //     return `
        //         <tr data-search="${searchText}">
        //             <td style="border: 1px solid #7d7d7d;">${date}</td>
        //             <td style="border: 1px solid #7d7d7d;">${voucher}</td>
        //             <td style="border: 1px solid #7d7d7d;">${remarks}</td>
        //             <td style="border: 1px solid #7d7d7d;">${code}</td>
        //             <td style="border: 1px solid #7d7d7d;">${account}</td>
        //             <td style="border: 1px solid #7d7d7d; text-align: right;">${formatAmount(debit)}</td>
        //             <td style="border: 1px solid #7d7d7d; text-align: right;">${formatAmount(credit)}</td>
        //             <td style="border: 1px solid #7d7d7d; text-align: right;">${formatAmount(balance)}</td>
        //         </tr>`;
        // }

        // function renderPage(data = allRows) {
        //     const tbody = $('#subsidiary_ledger');
        //     const pagination = $('#pagination_controls');
        //     tbody.empty();
        //     pagination.empty(); // Hide/remove pagination controls

        //     // Render all rows
        //     tbody.append(data.join(''));

        //     // Append the total row
        //     tbody.append(totalRow);
        // }


        // function loadSubsidiaryLedger() {
        //     const fiscalYear = $('#fiscal_year_select').val();
        //     const accountId = $('#account_select').val();

        //     $.ajax({
        //         url: '/bookkeeper/subsidiary-ledger',
        //         type: 'GET',
        //         data: {
        //             fiscal_year: fiscalYear,
        //             account_id: accountId
        //         },
        //         success: function(response) {
        //             const ledger = response.ledger || [];
        //             const cashtrans = response.cashtrans || [];
        //             const adjustments = response.adjustments || [];
        //             const studledger = response.studledger || [];

        //             allRows = [];
        //             totalDebit = 0;
        //             totalCredit = 0;

        //             if (ledger.length > 0) {
        //                 // allRows.push(addSectionHeader('LEDGER ENTRIES'));
        //                 ledger.forEach(item => {
        //                     allRows.push(createRow(
        //                         item.date ? moment(item.date).format('YYYY-MM-DD') : '',
        //                         item.voucherNo || '',
        //                         item.remarks || '',
        //                         item.code || '',
        //                         item.account_name || '',
        //                         parseFloat(item.debit_amount) || 0,
        //                         parseFloat(item.credit_amount) || 0
        //                     ));
        //                 });
        //             }

        //             if (cashtrans.length > 0) {
        //                 // allRows.push(addSectionHeader('CASH TRANSACTIONS'));
        //                 cashtrans.forEach(item => {
        //                     allRows.push(createRow(
        //                         item.transdate,
        //                         item.ornum || '',
        //                         `${item.studname} - ${item.particulars}` || '',
        //                         '',
        //                         item.accountname || 'CASH TRANSACTION',
        //                         parseFloat(item.totalamount) || 0,
        //                         parseFloat(item.amountpaid) || 0
        //                     ));
        //                 });
        //             }

        //             if (adjustments.length > 0) {
        //                 // allRows.push(addSectionHeader('ADJUSTMENTS'));
        //                 adjustments.forEach(item => {
        //                     allRows.push(createRow(
        //                         item.createddatetime,
        //                         item.refnum || '',
        //                         item.description || '',
        //                         '',
        //                         'ADJUSTMENT',
        //                         parseFloat(item.debit_amount) || 0,
        //                         parseFloat(item.credit_amount) || 0
        //                     ));
        //                 });
        //             }

        //             if (studledger.length > 0) {
        //                 // allRows.push(addSectionHeader('STUDENT LEDGER'));
        //                 studledger.forEach(item => {
        //                     allRows.push(createRow(
        //                         item.date ? item.date.split(' ')[0] : '',
        //                         item.voucherNo || '',
        //                         item.remarks || '',
        //                         '',
        //                         'STUDENT LEDGER',
        //                         parseFloat(item.debit_amount) || 0,
        //                         parseFloat(item.credit_amount) || 0
        //                     ));
        //                 });
        //             }

        //             totalRow = `
        //                 <tr style="font-weight: bold; background-color: #e8e8e8; position: sticky; bottom: 0; z-index: 3;">
        //                     <th colspan="5" style="border: 1px solid #7d7d7d; text-align: right;">Total</th>
        //                     <th style="border: 1px solid #7d7d7d; text-align: right;">${formatAmount(totalDebit)}</th>
        //                     <th style="border: 1px solid #7d7d7d; text-align: right;">${formatAmount(totalCredit)}</th>
        //                     <th style="border: 1px solid #7d7d7d; text-align: right;">${formatAmount(totalDebit - totalCredit)}</th>
        //                 </tr>
        //             `;

        //             renderPage();
        //         },
        //         error: function(xhr) {
        //             alert('Failed to load subsidiary ledger data.');
        //             console.error(xhr.responseText);
        //         }
        //     });
        // }

        
        // Attach search functionality
        // $('#ledgerSearch').off('input').on('input', function() {
        //     const keyword = $(this).val().toLowerCase();

        //     let filteredDebit = 0;
        //     let filteredCredit = 0;

        //     // Filter rows that are data rows (ignore section headers)
        //     const filteredRows = allRows.filter(row => {
        //         if (row.includes('<tr') && !row.includes('LEDGER ENTRIES') && !row.includes(
        //                 'CASH TRANSACTIONS')) {
        //             const tempDiv = $('<div>').html(row);
        //             const columns = tempDiv.find('td');
        //             if (columns.length > 0) {
        //                 const debit = parseFloat(columns.eq(5).text().replace(/,/g, '')) || 0;
        //                 const credit = parseFloat(columns.eq(6).text().replace(/,/g, '')) || 0;

        //                 const rowText = tempDiv.text().toLowerCase();
        //                 if (rowText.includes(keyword)) {
        //                     filteredDebit += debit;
        //                     filteredCredit += credit;
        //                     return true;
        //                 }
        //                 return false;
        //             }
        //             return false;
        //         }
        //         return row.toLowerCase().includes(keyword);
        //     });

        //     // Append recalculated total row
        //     const totalRow = `
        //         <tr>
        //             <th colspan="5" style="border: 1px solid #7d7d7d; text-align: right;">Total</th>
        //             <th style="border: 1px solid #7d7d7d; text-align: right;">${formatAmount(filteredDebit)}</th>
        //             <th style="border: 1px solid #7d7d7d; text-align: right;">${formatAmount(filteredCredit)}</th>
        //             <th style="border: 1px solid #7d7d7d; text-align: right;">${formatAmount(filteredDebit - filteredCredit)}</th>
        //         </tr>`;

        //     filteredRows.push(totalRow);
        //     renderPage(filteredRows);
        // });
        $(document).on('input', '#ledgerSearch', function () {
            const value = $(this).val().toLowerCase().trim().replace(/\s+/g, ' ');

            let totalDebit = 0;
            let totalCredit = 0;

            $('#subsidiary_ledger tr').each(function () {
                const row = $(this);
                const isTotalRow = row.attr('id') === 'total_row';
                const searchable = (row.data('search') || '').toLowerCase().replace(/\s+/g, ' ');
                const isMatch = searchable.includes(value);

                // Show/hide only if not the total row
                if (!isTotalRow) {
                    row.toggle(isMatch);

                    if (isMatch) {
                        const debit = parseFloat(row.find('td').eq(5).text().replace(/,/g, '')) || 0;
                        const credit = parseFloat(row.find('td').eq(6).text().replace(/,/g, '')) || 0;
                        totalDebit += debit;
                        totalCredit += credit;
                    }
                }
            });

            // Remove any existing total row
            $('#total_row').remove();

            // Only append new total if there's input
            if (value !== '') {
                $('#subsidiary_ledger').append(`
                    <tr  style="font-weight: bold; background-color: #e8e8e8; position: sticky; bottom: 0; z-index: 3;" id="total_row">
                        <td colspan="5" style="border: 1px solid #7d7d7d; text-align: right;">Total</td>
                        <td style="border: 1px solid #7d7d7d; text-align: right;">${formatAmount(totalDebit)}</td>
                        <td style="border: 1px solid #7d7d7d; text-align: right;">${formatAmount(totalCredit)}</td>
                        <td style="border: 1px solid #7d7d7d; text-align: right;">${formatAmount(totalDebit - totalCredit)}</td>
                    </tr>
                `);
            }
        });

        // loadSubsidiaryLedger();

        $(document).on('change', '#fiscal_year_select, #account_select, #costcenter_select', function () {
            loadSubsidiaryLedger();
        });

        $('#print_subsidiary_ledger').on('click', function() {
            const fiscalYear = $('#fiscal_year_select').val();
            const accountId = $('#account_select').val();

            window.open('/bookkeeper/subsidiary-ledger-print?fiscal_year=' + fiscalYear +
                '&account_id=' + accountId, '_blank');
        });

        // function select_accounts(){
           
        //     $.ajax({
        //         type: "GET",
        //         url: "/bookkeeper/subsidiary_account_fetch",
        //         success: function (data) {
        //             $('#account_select').empty()
        //             $('#account_select').append('<option value="">Select Accounts</option>')
        //             $('#account_select').select2({
        //                 data: data.results,
        //                 allowClear : true,
        //                 placeholder: 'Select Accounts'
        //             });
        //         }
        //     });
        // }

        let globalParentMap = {}; // Make parentMap available globally

        function select_accounts() {
            $.ajax({
                type: "GET",
                url: "/bookkeeper/subsidiary_account_fetch",
                success: function(response) {
                    $('#account_select').empty().append('<option value="">Select Accounts</option>');

                    const allAccounts = response.results.concat(response.missing_parents);
                    const processedData = processAccounts(allAccounts);

                    $('#account_select').select2({
                        data: processedData,
                        allowClear: true,
                        placeholder: 'Select Accounts',
                        templateResult: formatAccount,
                        templateSelection: formatAccountSelection,
                        escapeMarkup: function(markup) {
                            return markup; // Allow HTML in formatting
                        }
                    });
                },
                error: function(xhr) {
                    console.error('Error fetching accounts:', xhr.responseText);
                }
            });
        }

        function processAccounts(accounts) {
            const parentMap = {};
            globalParentMap = parentMap; // Store globally for selection formatting

            // First pass: identify parents
            accounts.forEach(account => {
                if (account.sub === 0) {
                    parentMap[account.id] = {
                        ...account,
                        children: []
                    };
                }
            });

            // Second pass: assign children to parents
            accounts.forEach(account => {
                if (account.sub === 1 && parentMap[account.fkid]) {
                    parentMap[account.fkid].children.push(account);
                }
            });

            const result = [];

            // Add parents and children
            Object.values(parentMap).forEach(parent => {
                result.push({
                    id: parent.id,
                    text: parent.text,
                    isParent: true,
                    original: parent
                });

                parent.children.forEach(child => {
                    result.push({
                        id: child.id,
                        text: child.text,
                        isChild: true,
                        parentId: parent.id,
                        original: child
                    });
                });
            });

            // Add orphaned children (not in parentMap)
            accounts.forEach(account => {
                if (account.sub === 1 && !parentMap[account.fkid]) {
                    result.push({
                        id: account.id,
                        text: `${account.text} (Orphan)`,
                        isOrphan: true,
                        original: account
                    });
                }
            });

            return result;
        }

        function formatAccount(account) {
            if (!account.id) return account.text;

            const $wrapper = $('<span></span>');

            if (account.isChild) {
                $wrapper.append(`<span class="child-account">↳ ${account.text}</span>`);
            } else if (account.isOrphan) {
                $wrapper.append(`<span class="orphan-account">${account.text}</span>`);
            } else {
                $wrapper.append(`<strong class="parent-account">${account.text}</strong>`);
            }

            return $wrapper;
        }

        function formatAccountSelection(account) {
            if (!account.id) return account.text;

            if (account.isChild) {
                const parent = globalParentMap[account.parentId];
                return parent ? `${parent.text} → ${account.original.account_name}` : account.original.account_name || account.text;
            }

            return account.original.account_name || account.text;
        }

        select_costcenter()

        function select_costcenter() {
            $.ajax({
                type: "GET",
                url: "/bookkeeper/subsidiary_costcenter_fetch",
                success: function(data) {
                    $('#costcenter_select').empty();

                    $('#costcenter_select').append('<option></option>');

                    $('#costcenter_select').select2({
                        data: data,
                        placeholder: 'Select Accounts',
                        allowClear: true,
                        width: '100%'
                    });
                }
            });
        }

        
    });


    var table;

    function formatAmount(amount) {
        return parseFloat(amount).toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function initFooter(debit, credit, balance) {
        $('#subsidiary_ledger_table tfoot').html(`
            <tr style="font-weight: bold; background-color: #e8e8e8; position: sticky; bottom: 0; z-index: 3;">
                <th colspan="5" style="border: 1px solid #7d7d7d; text-align: right;">Total</th>
                <th id="total-debit" style="border: 1px solid #7d7d7d; text-align: right;">${formatAmount(debit || 0)}</th>
                <th id="total-credit" style="border: 1px solid #7d7d7d; text-align: right;">${formatAmount(credit || 0)}</th>
                <th id="total-balance" style="border: 1px solid #7d7d7d; text-align: right;">${formatAmount(balance || 0)}</th>
            </tr>
        `);
    }

    function updateTotals(debit, credit, balance) {
        $('#total-debit').text(formatAmount(debit || 0));
        $('#total-credit').text(formatAmount(credit || 0));
        $('#total-balance').text(formatAmount(balance || 0));
    }

    function ensureTableStructure() {
        if (!$('#subsidiary_ledger_table thead').length) {
            $('#subsidiary_ledger_table').html(`
                <thead>
                    <tr>
                        <th>JE No.</th>
                        <th>Date</th>
                        <th>Remarks</th>
                        <th>Code</th>
                        <th>Account Name</th>
                        <th>Debit Amount</th>
                        <th>Credit Amount</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot></tfoot>
            `);
        }
    }

    function loadSubsidiaryLedger() {
        // Destroy existing table if it exists
        if ($.fn.DataTable.isDataTable('#subsidiary_ledger_table')) {
            table.destroy();
        }
        
        // Ensure table structure exists
        ensureTableStructure();
        
        // Initialize DataTable
        table = $('#subsidiary_ledger_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/bookkeeper/subsidiary-ledger',
                type: 'GET',
                data: function(d) {
                    return {
                        fiscal_year: $('#fiscal_year_select').val(),
                        account_id: $('#account_select').val(),
                        date_range: $('#date_range').val(),
                        costcenter_id: $('#costcenter_select').val(),
                        costcenter_desc: $('#costcenter_select option:selected').text(),
                        draw: d.draw,
                        start: d.start,
                        length: d.length,
                        search: d.search,
                        order: d.order
                    };
                },
                dataSrc: function(json) {
                    initFooter(json.totalDebit, json.totalCredit, json.totalBalance);
                    return json.data;
                }
            },
            columns: [
                { data: 'voucherNo', name: 'voucherNo' },
                { 
                    data: 'date', 
                    name: 'date',
                    render: function(data) {
                        return data ? moment(data).format('YYYY-MM-DD') : '';
                    }
                },
                { data: 'remarks', name: 'remarks' },
                { data: 'code', name: 'code' },
                { data: 'account_name', name: 'account_name' },
                { 
                    data: 'debit_amount', 
                    name: 'debit_amount',
                    className: 'text-right',
                    render: function(data) {
                        return formatAmount(parseFloat(data) || 0);
                    }
                },
                { 
                    data: 'credit_amount', 
                    name: 'credit_amount',
                    className: 'text-right',
                    render: function(data) {
                        return formatAmount(parseFloat(data) || 0);
                    }
                },
                { 
                    data: null,
                    name: 'balance',
                    className: 'text-right',
                    render: function(data, type, row) {
                        return formatAmount((parseFloat(row.debit_amount) || 0) - (parseFloat(row.credit_amount) || 0));
                    }
                }
            ],
            drawCallback: function() {
                // Update totals if no data
                if (this.api().rows().count() === 0) {
                    updateTotals(0, 0, 0);
                }
            },
            dom: '<"top"lf>rt<"bottom"ip>',
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            responsive: true,
            stateSave: true,
            initComplete: function() {
                // Force initial load if needed
                if (!this.api().data().any()) {
                    this.api().ajax.reload();
                }
            }
        });
    }
</script>
@endsection
