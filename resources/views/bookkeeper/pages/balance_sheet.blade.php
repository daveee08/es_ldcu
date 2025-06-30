@extends('bookkeeper.layouts.app')

@section('pagespecificscripts')

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Balance Sheet</title>

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
                        <h3 style="margin: 0;">Balance Sheet</h3>
                    </div>
                </div>
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item" style="font-size: 0.8rem;"><a href="#"
                                style="color:rgba(0,0,0,0.5);">Home</a></li>
                        <li class="breadcrumb-item active" style="font-size: 0.8rem; color:rgba(0,0,0,0.5);">Balance Sheet
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="mb-3" style="color: black;  font-size: 13px;">
                <ul class="nav nav-tabs" style="border-bottom: 4px solid #d9d9d9;;">
                    <li class="nav-item">
                        <a class="nav-link " href="/bookkeeper/income_statement"
                            class="nav-link {{ Request::url() == url('bookeeper/income_statement') ? 'active' : '' }}"
                            style="color: black; ">Income
                            Statement</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/bookkeeper/balance_sheet"
                            class="nav-link {{ Request::url() == url('bookeeper/balance_sheet') ? 'active' : '' }}"
                            style="color: black; font-weight: 600; background-color: #d9d9d9; border-top-left-radius: 10px; border-top-right-radius: 10px;">Balance
                            Sheet</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/bookkeeper/cashflow_statement"
                            class="nav-link {{ Request::url() == url('bookeeper/cashflow_statement') ? 'active' : '' }}"
                            style="color: black;">Cashflow Statement</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/bookkeeper/equity_statement"
                            class="nav-link {{ Request::url() == url('bookeeper/equity_statement') ? 'active' : '' }}"
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
                    <div class="col-md-6 ml-5">
                        <label style="font-size: 13px; font-weight: 500;">Fiscal Year</label>
                        <div class="row align-items-center">
                            <select id="fiscal_year_select" name="fiscal_year[]"
                                style="width: 90%; height: 60px; font-size: 12.5px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);"
                                class="form-control">
                                @foreach ($fiscal as $item)
                                    <option value="{{ $item->id }}" @if ($item->id == $activeFiscalYearId) selected @endif>
                                        {{ $item->description }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- 
                            <i class="fas fa-sync-alt"
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
    
                        <div id="balance_sheet_container" class="row g-4 p-3">
    
                            <!-- ASSETS TABLE -->
                            <div class="col-md-6">
                                <h5><strong>ASSETS</strong></h5>
                                <table class="table table-sm w-100">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th>Classification</th>
                                            <th>Account</th>
                                            <th>Amount</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="assets_table_body"></tbody>
                                </table>
                            </div>
    
                            <!-- LIABILITIES & EQUITY TABLE -->
                            <div class="col-md-6">
                                <h5><strong>LIABILITIES & EQUITY</strong></h5>
                                <table class="table table-sm w-100">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th>Classification</th>
                                            <th>Account</th>
                                            <th>Amount</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="liabilities_equity_table_body"></tbody>
                                </table>
                            </div>
    
                        </div>
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
        // Initialize date picker
        // $('input[name="date_filter"]').daterangepicker({
        //     autoUpdateInput: false,
        //     locale: {
        //         cancelLabel: 'Clear'
        //     }
        // });

        // $('input[name="date_filter"]').on('apply.daterangepicker', function(ev, picker) {
        //     $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
        //         'MM/DD/YYYY'));
        //     loadBalanceSheet(); // Reload when date selected
        // });

        // $('input[name="date_filter"]').on('cancel.daterangepicker', function(ev, picker) {
        //     $(this).val('');
        //     loadBalanceSheet(); // Reload when cleared
        // });

        // Limit fiscal year selection
        $('#fiscal_year_select').on('change', function() {
            let selected = $(this).val();
            if (selected.length > 2) {
                alert('You can only select up to 2 fiscal years.');
                selected.pop();
                $(this).val(selected).trigger('change');
            }
            loadBalanceSheet();
        });

        $('#fiscal_year_select').select2({
            placeholder: "Select up to 2 fiscal years",
            maximumSelectionLength: 2,
            width: 'resolve'
        });

        // Load data on page load
        

        // function loadBalanceSheet() {
        //     const fiscalYearIds = $('#fiscal_year_select').val();
        //     const dateRange = $('input[name="date_filter"]').val();
        //     const assetsContainer = $('#assets_table_body');
        //     const liabilitiesEquityContainer = $('#liabilities_equity_table_body');

        //     $.ajax({
        //         url: '/bookkeeper/balance-sheet',
        //         type: 'GET',
        //         data: {
        //             fiscal_year: fiscalYearIds,
        //             date_range: dateRange
        //         },
        //         success: function(response) {
        //             console.log(response, 'response');

        //             // Clear existing content
        //             assetsContainer.empty();
        //             liabilitiesEquityContainer.empty();

        //             let assetsRows = '';
        //             let liabilitiesEquityRows = '';
        //             let totalAssets = 0;
        //             let totalLiabilitiesEquity = 0;

        //             // Asset classifications
        //             const assetClassifications = [
        //                 'Current Assets', 'Non-Current Assets', 'Fixed Assets', 'Cash', 
        //                 'Accounts Receivable', 'Inventory', 'Prepaid Expenses'
        //             ];

        //             // Liability and Equity classifications
        //             const liabilityEquityClassifications = [
        //                 'Current Liabilities', 'Non-Current Liabilities', 'Accounts Payable',
        //                 'Accrued Expenses', 'Long-term Debt', 'Owner\'s Equity', 'Retained Earnings',
        //                 'Capital', 'Equity'
        //             ];

        //             response.forEach((result) => {
        //                 const fiscalYearLabel = result.fiscal_year_description || 'N/A';

        //                 // Process General Ledger Entries
        //                 result.data.forEach(item => {
        //                     const amount = parseFloat(item.ending_balance);
        //                     const classification = item.classification || 'Unclassified';
                            
        //                     const row = `
        //                         <tr>
        //                             <td>${classification}</td>
        //                             <td>${item.code} - ${item.account_name}</td>
        //                             <td class="text-end">${amount.toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
        //                             <td class="text-end">${amount.toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
        //                         </tr>
        //                     `;

        //                     // Classify based on classification name
        //                     if (assetClassifications.some(assetClass => 
        //                         classification.toLowerCase().includes(assetClass.toLowerCase()))) {
        //                         assetsRows += row;
        //                         totalAssets += amount;
        //                     } else if (liabilityEquityClassifications.some(liabClass => 
        //                         classification.toLowerCase().includes(liabClass.toLowerCase()))) {
        //                         liabilitiesEquityRows += row;
        //                         totalLiabilitiesEquity += amount;
        //                     } else {
        //                         // Default: if amount is positive, likely an asset; if negative, likely a liability
        //                         if (amount >= 0) {
        //                             assetsRows += row;
        //                             totalAssets += amount;
        //                         } else {
        //                             liabilitiesEquityRows += row;
        //                             totalLiabilitiesEquity += Math.abs(amount); // Convert to positive for display
        //                         }
        //                     }
        //                 });

        //                 // Process Fixed Assets (always go to Assets)
        //                 result.fixed_assets.forEach(asset => {
        //                     const amount = parseFloat(asset.asset_value);
        //                     const row = `
        //                         <tr>
        //                             <td>Fixed Assets</td>
        //                             <td>${asset.asset_name}</td>
        //                             <td class="text-end">${amount.toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
        //                             <td class="text-end">${amount.toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
        //                         </tr>
        //                     `;
        //                     assetsRows += row;
        //                     totalAssets += amount;
        //                 });

        //                 // Process Cash Transactions (classify based on account type)
        //                 result.cash_transactions.forEach(tx => {
        //                     const amount = parseFloat(tx.amountpaid || 0);
        //                     const accountName = tx.accountname || tx.paytype || 'Cash Transaction';
                            
        //                     const row = `
        //                         <tr>
        //                             <td>Cash</td>
        //                             <td>${accountName}</td>
        //                             <td class="text-end">${amount.toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
        //                             <td class="text-end">${amount.toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
        //                         </tr>
        //                     `;

        //                     // Cash transactions are typically assets if positive
        //                     if (amount >= 0) {
        //                         assetsRows += row;
        //                         totalAssets += amount;
        //                     } else {
        //                         liabilitiesEquityRows += row;
        //                         totalLiabilitiesEquity += Math.abs(amount);
        //                     }
        //                 });

        //                 // Process Adjustments (classify based on debit/credit account)
        //                 result.adjustments.forEach(adj => {
        //                     const amount = parseFloat(adj.amount || 0);
        //                     const label = adj.debit_account || adj.credit_account || 'Adjustment';
                            
        //                     const row = `
        //                         <tr>
        //                             <td>Adjustment</td>
        //                             <td>${label}</td>
        //                             <td class="text-end">${amount.toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
        //                             <td class="text-end">${amount.toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
        //                         </tr>
        //                     `;

        //                     // Classify adjustments based on account name or amount
        //                     if (adj.debit_account && (
        //                         adj.debit_account.toLowerCase().includes('asset') ||
        //                         adj.debit_account.toLowerCase().includes('cash') ||
        //                         adj.debit_account.toLowerCase().includes('receivable'))) {
        //                         assetsRows += row;
        //                         totalAssets += amount;
        //                     } else if (adj.credit_account && (
        //                         adj.credit_account.toLowerCase().includes('liability') ||
        //                         adj.credit_account.toLowerCase().includes('payable') ||
        //                         adj.credit_account.toLowerCase().includes('equity'))) {
        //                         liabilitiesEquityRows += row;
        //                         totalLiabilitiesEquity += amount;
        //                     } else {
        //                         // Default classification based on amount
        //                         if (amount >= 0) {
        //                             assetsRows += row;
        //                             totalAssets += amount;
        //                         } else {
        //                             liabilitiesEquityRows += row;
        //                             totalLiabilitiesEquity += Math.abs(amount);
        //                         }
        //                     }
        //                 });

        //                 // Process Discounts (typically reduce assets or increase expenses)
        //                 result.discounts.forEach(discount => {
        //                     const amount = parseFloat(discount.amount || 0);
        //                     const label = discount.debit_account || discount.credit_account || 'Discount';
                            
        //                     const row = `
        //                         <tr>
        //                             <td>Discount</td>
        //                             <td>${label}</td>
        //                             <td class="text-end">${Math.abs(amount).toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
        //                             <td class="text-end">${Math.abs(amount).toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
        //                         </tr>
        //                     `;

        //                     // Discounts typically reduce assets or are contra-accounts
        //                     // Place in appropriate section based on account name
        //                     if (label.toLowerCase().includes('liability') || 
        //                         label.toLowerCase().includes('payable') ||
        //                         label.toLowerCase().includes('equity')) {
        //                         liabilitiesEquityRows += row;
        //                         totalLiabilitiesEquity += Math.abs(amount);
        //                     } else {
        //                         assetsRows += row;
        //                         totalAssets += Math.abs(amount);
        //                     }
        //                 });
        //             });

        //             // Add total rows
        //             assetsRows += `
        //                 <tr class="table-secondary">
        //                     <td colspan="3" class="text-end"><strong>TOTAL ASSETS</strong></td>
        //                     <td class="text-end"><strong>${totalAssets.toLocaleString('en-US', { minimumFractionDigits: 2 })}</strong></td>
        //                 </tr>
        //             `;

        //             liabilitiesEquityRows += `
        //                 <tr class="table-secondary">
        //                     <td colspan="3" class="text-end"><strong>TOTAL LIABILITIES & EQUITY</strong></td>
        //                     <td class="text-end"><strong>${totalLiabilitiesEquity.toLocaleString('en-US', { minimumFractionDigits: 2 })}</strong></td>
        //                 </tr>
        //             `;

        //             // Populate the tables
        //             assetsContainer.html(assetsRows);
        //             liabilitiesEquityContainer.html(liabilitiesEquityRows);

        //             // Check if balance sheet balances
        //             if (Math.abs(totalAssets - totalLiabilitiesEquity) > 0.01) {
        //                 console.warn('Balance Sheet does not balance:', {
        //                     assets: totalAssets,
        //                     liabilitiesEquity: totalLiabilitiesEquity,
        //                     difference: totalAssets - totalLiabilitiesEquity
        //                 });
        //             }
        //         },
        //         error: function(err) {
        //             assetsContainer.html(
        //                 `<tr><td colspan="4" class="alert alert-danger">Failed to load assets data.</td></tr>`
        //             );
        //             liabilitiesEquityContainer.html(
        //                 `<tr><td colspan="4" class="alert alert-danger">Failed to load liabilities & equity data.</td></tr>`
        //             );
        //         }
        //     });
        // }

        function loadBalanceSheet() {
            const fiscalYearIds = $('#fiscal_year_select').val();
            const dateRange = $dateFilter.val();
            const assetsContainer = $('#assets_table_body');
            const liabilitiesEquityContainer = $('#liabilities_equity_table_body');
         
            $.ajax({
                url: '/bookkeeper/balance-sheet',
                type: 'GET',
                data: {
                    fiscal_year: fiscalYearIds,
                    date_range: dateRange
                },
                success: function(response) {
                    // Clear existing content
                    assetsContainer.empty();
                    liabilitiesEquityContainer.empty();

                    let assetsRows = '';
                    let liabilitiesEquityRows = '';
                    let totalAssets = 0;
                    let totalLiabilitiesEquity = 0;

                    // Asset classifications
                    const assetClassifications = [
                        'Current Assets', 'Non-Current Assets', 'Fixed Assets', 'Cash', 
                        'Accounts Receivable', 'Inventory', 'Prepaid Expenses'
                    ];

                    // Liability and Equity classifications
                    const liabilityEquityClassifications = [
                        'Current Liabilities', 'Non-Current Liabilities', 'Accounts Payable',
                        'Accrued Expenses', 'Long-term Debt', 'Owner\'s Equity', 'Retained Earnings',
                        'Capital', 'Equity'
                    ];

                    const currentAssets = response[0].group_assets.filter(item => item.data?.[0]?.account_type === 6);
                    const noncurrentAssets = response[0].group_assets.filter(item => item.data?.[0]?.account_type === 7);

                    const currentLiabilities = response[0].group_liablities_equity.filter(item => item.data?.[0]?.account_type === 6);
                    const othercurrentLiabilities = response[0].group_liablities_equity.filter(item => item.data?.[0]?.account_type === 6);
                    const fundBalance = response[0].group_liablities_equity.filter(item => item.data?.[0]?.account_type === 6);
                    

                    response.forEach((result) => {
                        const fiscalYearLabel = result.fiscal_year_description || 'N/A';

                        // Add "FIXED ASSETS" header row if there are fixed assets
                        if (result.fixed_assets.length > 0) {
                            assetsRows += `
                                <tr class="table-group-header">
                                    <td colspan="4"><strong>CURRENT ASSETS</strong></td>
                                </tr>
                            `;
                        }
                        result.group_assets.forEach(tx => {
                            const amount = parseFloat(tx.total_amount || 0);
                            const accountName = tx.account_name;
                            
                            const row = `
                                <tr>
                                    <td></td>
                                    <td>${accountName}</td>
                                    <td class="text-end">${amount.toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                                    <td class="text-end">${amount.toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                                </tr>
                            `;

                            if (amount >= 0) {
                                assetsRows += row;
                                totalAssets += amount;
                            } else {
                                assetsRows += row;
                                totalAssets += Math.abs(amount);
                            }
                        });

                        // Add "FIXED ASSETS" header row if there are fixed assets
                        if (result.fixed_assets.length > 0) {
                            assetsRows += `
                                <tr class="table-group-header">
                                    <td colspan="4"><strong>FIXED ASSETS</strong></td>
                                </tr>
                            `;
                        }
                        result.fixed_assets.forEach(asset => {
                            const amount = parseFloat(asset.asset_value);
                            const row = `
                                <tr>
                                    <td></td>
                                    <td>${asset.asset_name}</td>
                                    <td class="text-end">${amount.toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                                    <td class="text-end">${amount.toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                                </tr>
                            `;
                            assetsRows += row;
                            totalAssets += amount;
                        });


                        if (result.group_liablities_equity.length > 0) {
                            liabilitiesEquityRows += `
                                <tr class="table-group-header">
                                    <td colspan="4"><strong>Current Liabilities</strong></td>
                                </tr>
                            `;
                        }
                        result.group_liablities_equity.forEach(tx => {
                            const amount = parseFloat(tx.total_amount || 0);
                            const accountName = tx.account_name;
                            
                            const row = `
                                <tr>
                                    <td></td>
                                    <td>${accountName}</td>
                                    <td class="text-end">${amount.toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                                    <td class="text-end">${amount.toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                                </tr>
                            `;

                            if (amount >= 0) {
                                liabilitiesEquityRows += row;
                                totalLiabilitiesEquity += amount;
                            } else {
                                liabilitiesEquityRows += row;
                                totalLiabilitiesEquity += Math.abs(amount);
                            }
                        });
                    });

                    // Add total rows
                    assetsRows += `
                        <tr class="table-secondary">
                            <td colspan="3" class="text-end"><strong>TOTAL ASSETS</strong></td>
                            <td class="text-end"><strong>${totalAssets.toLocaleString('en-US', { minimumFractionDigits: 2 })}</strong></td>
                        </tr>
                    `;

                    liabilitiesEquityRows += `
                        <tr class="table-secondary">
                            <td colspan="3" class="text-end"><strong>TOTAL LIABILITIES & EQUITY</strong></td>
                            <td class="text-end"><strong>${totalLiabilitiesEquity.toLocaleString('en-US', { minimumFractionDigits: 2 })}</strong></td>
                        </tr>
                    `;

                    // Populate the tables
                    assetsContainer.html(assetsRows);
                    liabilitiesEquityContainer.html(liabilitiesEquityRows);

                    // Check if balance sheet balances
                    if (Math.abs(totalAssets - totalLiabilitiesEquity) > 0.01) {
                        console.warn('Balance Sheet does not balance:', {
                            assets: totalAssets,
                            liabilitiesEquity: totalLiabilitiesEquity,
                            difference: totalAssets - totalLiabilitiesEquity
                        });
                    }
                },
                error: function(err) {
                    assetsContainer.html(
                        `<tr><td colspan="4" class="alert alert-danger">Failed to load assets data.</td></tr>`
                    );
                    liabilitiesEquityContainer.html(
                        `<tr><td colspan="4" class="alert alert-danger">Failed to load liabilities & equity data.</td></tr>`
                    );
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
                loadBalanceSheet();
            });

            $dateFilter.on('cancel.daterangepicker', function () {
                $(this).val('');
                loadBalanceSheet();
            });

            $dateFilter.val(fiscalStartDate.format('YYYY-MM-DD') + ' - ' + today.format('YYYY-MM-DD'));
            loadBalanceSheet();
        });

        // Print PDF
        $('#print_income_statement').on('click', function() {
            const fiscalYearId = $('#fiscal_year_select').val();
            const dateRange = $('input[name="date_filter"]').val();

            let pdfUrl = `/bookkeeper/balance-sheet/pdf?fiscal_year_id=${fiscalYearId}`;
            if (dateRange) {
                pdfUrl += `&date_range=${encodeURIComponent(dateRange)}`;
            }

            window.open(pdfUrl, '_blank');
        });
    });
</script>
@endsection
