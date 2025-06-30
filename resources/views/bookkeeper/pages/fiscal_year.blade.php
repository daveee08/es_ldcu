@extends('bookkeeper.layouts.app')

@section('pagespecificscripts')

    <head>
        <title>Fiscal Year</title>
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
    </head>
@endsection

@section('content')
    <style>
        #incomeStatementContainer table td {
            font-size: 12px;
        }

        #incomeStatementContainer .font-weight-bold {
            font-weight: bold;
        }

        #balanceSheetContainer table td {
            font-size: 12px;
        }
        .align-middle {
            vertical-align: middle !important;
        }
    </style>

    <body>
        <div class="container-fluid top-2 ml-3">
            <div>
                <!-- Page Header -->
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-link fa-lg mr-2" style="font-size: 33px;"></i>
                    <h1>Fiscal Year</h1>
                </div>
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item" style="font-size: 0.8rem;"><a href="/home"
                                style="color: #007bff;">Home</a></li>
                        <li class="breadcrumb-item active" style="font-size: 0.8rem; ">Fiscal Year
                        </li>
                    </ol>
                </nav>

                <div class="mb-3" style="color: black;  font-size: 13px;">
                    <ul class="nav nav-tabs" style="border-bottom: 4px solid #d9d9d9; font-weight: 600;">
                        <li class="nav-item">
                            <a href="/bookkeeper/chart_of_accounts" class="nav-link "
                                {{ Request::url() == url('/bookkeeper/chart_of_accounts') ? 'active' : '' }}
                                style="color: black;">Chart
                                Of Account</a>
                        </li>
                        <li class="nav-item">
                            <a href="/bookkeeper/fiscal_year" class="nav-link active"
                                {{ Request::url() == url('/bookkeeper/fiscal_year') ? 'active' : '' }}
                                style="color: black;  font-weight: 600; background-color: #d9d9d9; border-top-left-radius: 10px; border-top-right-radius: 10px;">Fiscal
                                Year</a>
                        </li>
                        <li class="nav-item">
                            <a href="/bookkeeper/enrollment_setup" class="nav-link"
                                {{ Request::url() == url('/bookkeeper/enrollment_setup') ? 'active' : '' }}
                                style="color: black;">Enrollment
                                Setup</a>
                        </li>
                        <li class="nav-item">
                            <a href="/bookkeeper/other_setup" class="nav-link"
                                {{ Request::url() == url('/bookkeeper/other_setup') ? 'active' : '' }}
                                style="color: black;">Other Setup</a>
                        </li>
                    </ul>
                </div>
                <hr style="border-top: 2px solid #d9d9d9;">
                <div class="card mt-4" style="border-color: white;">
                    <div class="card-header d-flex justify-content-between align-items-center" style="font-size: 12.5px;">

                        <!-- Left Section: Buttons -->
                        <div>
                            <button type="button" class="btn btn-success rounded" data-toggle="modal"
                                data-target="#addfiscalyearModal"
                                style="background-color: #00581f; height: 30px; font-size: 12.5px; font-family: Arial, sans-serif; ">
                                <i class="fas fa-plus-circle mr-1"></i> Add Fiscal year
                            </button>

                            &nbsp;&nbsp;&nbsp; <span><i class="fas fa-square text-success"></i> Active </span>
                            &nbsp; <span><i class="fas fa-square text-danger"></i> Inactive </span>
                            &nbsp; <span><i class="fas fa-square text-warning"></i> Ended </span>
                            &nbsp; <span><i class="fas fa-square text-primary"></i> Ready for Activation </span>
                        </div>

                        <!-- Right Section: Select & Search -->
                        <div class="d-flex align-items-center ml-auto">
                            <!-- Search Input -->
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search" id="fiscal_search"
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

                    <div class="card-body">
                        <table width="100%" class="table table-bordered table-sm" style="border-right: 1px solid #7d7d7d;">
                            <thead style="font-size: 13px; background-color: #b9b9b9;">
                                <tr>
                                    <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 2% "></th>
                                    <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 33% ">Fiscal year
                                        Description</th>
                                    <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 15%">Start Date</th>
                                    <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 20%">End Date</th>
                                    <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 15%; text-align: center;">Status
                                    </th>
                                    <th style="font-weight: 600; border: 1px solid #7d7d7d; text-align: center; width:15%"
                                        colspan="2">Action</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 12.5px; font-style:" id="fiscalyeartable">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Fiscal Year Modal -->
        <div class="modal fade" id="addfiscalyearModal" tabindex="-1" role="dialog"
            aria-labelledby="addfiscalyearModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="padding: 10px; background-color: #d9d9d9;">
                        <h5 class="modal-title ml-2" id="addfiscalyearModalLabel" style="font-size: 14px;">Add Fiscal
                            Year</h5>
                        <button type="button" class="close" id="closeModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" id="fiscalYearId" hidden>
                            <label style="font-weight: normal; font-size: 13px;">Fiscal Year Description</label>
                            <input type="text" id="fiscalYearDescription" class="form-control"
                                placeholder="Enter Description Here"
                                style="height: 40px; font-size: 12px; border-radius: 10px;">
                        </div>
                        <div class="form-row mb-3 mt-3">
                            <div class="form-group col-md-6">
                                <label style="font-weight: normal; font-size: 13px;">Start Date</label>
                                <input type="date" id="startDateFiscal" class="form-control"
                                    style="height: 40px; font-size: 12px; border-radius: 10px;">
                            </div>
                            <div class="form-group col-md-6">
                                <label style="font-weight: normal; font-size: 13px;">End Date</label>
                                <input type="date" id="endDateFiscal" class="form-control"
                                    style="height: 40px; font-size: 12px; border-radius: 10px;">
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="isActive" checked>
                                <label class="form-check-label" for="isActive"
                                    style="font-weight: normal; font-size: 13px;">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-success"
                            style="background-color: #00581f; height: 30px; border: none; font-size: 12.5px;"
                            id="savefiscalyear">Save</button>
                        <button type="button" class="btn btn-success" id="updatedfiscalyear"
                            style="background-color: #00581f; height: 30px; border: none; font-size: 12.5px; display: none;">
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fiscal Year Modal -->
        <div class="modal fade" id="fiscalYearModal" tabindex="-1" aria-labelledby="fiscalYearModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fiscalYearModalLabel">Close Fiscal Year</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="fiscalYearModalBody" style="font-size: 12px;">
                        <div class="row">
                            <div class="col-md-6">
                                <div id="incomeStatementContainer"
                                    style="border: 1px solid #ccc; padding: 10px; border-radius: 5px;">
                                    <div class="bg-dark text-white p-2 text-center">
                                        <h6>Income Statement</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="balanceSheetContainer"
                                    style="border: 1px solid #ccc; padding: 10px; border-radius: 5px;">
                                    <div class="bg-dark text-white p-2 text-center">
                                        <h6>Balance Sheet</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"
                                id="closeFiscalYear">Close Fiscal Year</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </body>
@endsection

@section('footerjavascript')
    <script>
        $(document).ready(function() {

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            });

            // displayfiscal();

            // function displayfiscal() {
            //     $.ajax({
            //         url: '/bookkeeper/fiscal-year',
            //         type: 'GET',
            //         success: function(response) {
            //             var rows = '';

            //             response.forEach(function(item) {
            //                 var activeStatus = item.isactive == 1 ?
            //                     `<a href="#" class="text-primary" onclick="viewFiscalYearDetails(${item.id})">View Details</a>` :
            //                     `<button type="button" class="btn btn-success btn-sm" onclick="viewFiscalYearDetails(${item.id})">Close Fiscal Year</button>`;

            //                 rows += `
        //         <tr>
        //             <td style="border: 1px solid #7d7d7d;">${item.description}</td>
        //             <td style="border: 1px solid #7d7d7d;">${formatDate(item.stime)}</td>
        //             <td style="border: 1px solid #7d7d7d;">${formatDate(item.etime)}</td>
        //             <td style="border: 1px solid #7d7d7d;">${activeStatus}</td>
        //             <td style="border: 1px solid #7d7d7d; text-align: center;">
        //                 <i class="far fa-edit text-primary editFiscalYear" data-id="${item.id}" style="cursor: pointer;"></i>
        //             </td>
        //             <td style="border: 1px solid #7d7d7d; text-align: center;">
        //                 <i class="fas fa-trash-alt text-danger deleteFiscalYear" data-id="${item.id}" style="cursor: pointer;"></i>
        //             </td>
        //         </tr>
        //     `;
            //             });

            //             $('#fiscalyeartable').html(rows);
            //         },
            //         error: function(xhr) {
            //             console.error(xhr.responseText);
            //         }
            //     });
            // }

            // Add event listener for search input
            $('#fiscal_search').on('input', function() {
                var searchQuery = $(this).val().toLowerCase();
                filterFiscalYears(searchQuery);
            });

            $(document).on('click', '.viewdetails', function() {
                $('#fiscalYearModal').modal('show');
                var fiscalYearId = $(this).attr('data-id');
                const isActive = $(this).data('isactive');

                $('#fiscalYearModal').data('fiscalYearId', fiscalYearId);
                $('#fiscalYearModal').data('isActive', isActive);

                $('#incomeStatementContainer').html('<p>Loading data...</p>');
                $('#balanceSheetContainer').html('<p>Loading data...</p>');

                if (isActive == 1) {
                    $('#closeFiscalYear').show(); // Show close button if active
                } else {
                    $('#closeFiscalYear').hide(); // Hide close button if not active
                }

                $.ajax({
                    url: `/bookkeeper/closed/${fiscalYearId}`,
                    method: 'GET',
                    success: function(response) {
                        const incomeStatement = response.income_statement || [];
                        const balanceSheet = response.balance_sheet || {};

                        const groupedIncome = {};
                        let netOperatingProfit = 0;
                        let totalRevenue = 0;
                        let totalExpenses = 0;

                        incomeStatement.forEach(item => {
                            const classification = item.classification;
                            if (!groupedIncome[classification]) groupedIncome[
                                classification] = [];
                            groupedIncome[classification].push(item);
                        });

                        let incomeHtml = `<h5 class="mb-3">INCOME STATEMENT</h5>`;
                        incomeHtml += `<table class="table table-sm table-borderless"><tbody>`;

                        for (const [classification, items] of Object.entries(groupedIncome)) {
                            let classTotal = 0;

                            incomeHtml +=
                                `<tr><td colspan="2"><strong>${classification}</strong></td></tr>`;

                            items.forEach(item => {
                                const amount = parseFloat(item.total_amount);
                                incomeHtml += `
                                            <tr>
                                                <td style="padding-left: 20px;">${item.account_name}</td>
                                                <td class="text-right">${amount.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                            </tr>
                                        `;
                                                    classTotal += amount;
                                                });

                                                incomeHtml += `
                                        <tr class="font-weight-bold border-top">
                                            <td>Total ${classification}</td>
                                            <td class="text-right">${classTotal.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                        </tr>
                                    `;

                            if (classification === 'Revenue' || classification ===
                                'Other Income') {
                                netOperatingProfit += classTotal;
                                totalRevenue += classTotal;
                            } else {
                                netOperatingProfit -= classTotal;
                                totalExpenses += classTotal;
                            }
                        }

                        // Add totals section for Income Statement
                        incomeHtml += `
                                    <tr class="border-top" style="height: 10px;"><td colspan="2"></td></tr>
                                    <tr class="font-weight-bold">
                                        <td>Total Revenue</td>
                                        <td class="text-right text-primary">${totalRevenue.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                    </tr>
                                    <tr class="font-weight-bold">
                                        <td>Total Expenses</td>
                                        <td class="text-right text-warning">${totalExpenses.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                    </tr>
                                    <tr class="font-weight-bold border-top border-bottom" style="border-width: 2px !important;">
                                        <td>Net Income</td>
                                        <td class="text-right ${netOperatingProfit >= 0 ? 'text-success' : 'text-danger'}">${netOperatingProfit.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                    </tr>
                                `;
                        incomeHtml += `</tbody></table>`;

                        const allBalanceItems = [];

                        if (balanceSheet.data) {
                            balanceSheet.data.forEach(item => {
                                allBalanceItems.push({
                                    classification: item.classification,
                                    label: `${item.classification} - ${item.code}`,
                                    amount: parseFloat(item.ending_balance)
                                });
                            });
                        }

                        if (balanceSheet.studledger_data) {
                            balanceSheet.studledger_data.forEach(item => {
                                allBalanceItems.push({
                                    classification: item.classification,
                                    label: `${item.account_name} (${item.code})`,
                                    amount: parseFloat(item.total_amount)
                                });
                            });
                        }

                        if (balanceSheet.show_fixed_assets && balanceSheet.assets_details) {
                            balanceSheet.assets_details.forEach(item => {
                                allBalanceItems.push({
                                    classification: item.classification ||
                                        'Fixed Assets',
                                    label: `${item.name} (Purchased: ${item.purchased_date})`,
                                    amount: parseFloat(item.value)
                                });
                            });
                        }

                        const groupedBalance = {};
                        let totalAssets = 0;
                        let totalLiabilities = 0;
                        let totalEquity = 0;
                        let grandTotalBalance = 0;

                        allBalanceItems.forEach(item => {
                            if (!groupedBalance[item.classification]) groupedBalance[
                                item.classification] = [];
                            groupedBalance[item.classification].push(item);
                        });

                        let balanceHtml = `<h5 class="mb-3">BALANCE SHEET</h5>`;
                        balanceHtml += `<table class="table table-sm table-borderless"><tbody>`;

                        for (const [classification, items] of Object.entries(groupedBalance)) {
                            let total = 0;
                            balanceHtml +=
                                `<tr><td colspan="2"><strong>${classification}</strong></td></tr>`;

                            items.forEach(item => {
                                balanceHtml += `
                                        <tr>
                                            <td style="padding-left: 20px;">${item.label}</td>
                                            <td class="text-right">${item.amount.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                        </tr>
                                    `;
                                total += item.amount;
                            });

                            balanceHtml += `
                                    <tr class="font-weight-bold border-top">
                                        <td>Total ${classification}</td>
                                        <td class="text-right">${total.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                    </tr>
                                `;

                            // Categorize totals (you may need to adjust these classifications based on your data)
                            const assetCategories = ['Assets', 'Current Assets', 'Fixed Assets',
                                'Non-Current Assets'
                            ];
                            const liabilityCategories = ['Liabilities', 'Current Liabilities',
                                'Long-term Liabilities', 'Non-Current Liabilities'
                            ];
                            const equityCategories = ['Equity', 'Owner\'s Equity',
                                'Shareholders\' Equity', 'Capital'
                            ];

                            if (assetCategories.some(cat => classification.toLowerCase()
                                    .includes(cat.toLowerCase()))) {
                                totalAssets += total;
                            } else if (liabilityCategories.some(cat => classification
                                    .toLowerCase().includes(cat.toLowerCase()))) {
                                totalLiabilities += total;
                            } else if (equityCategories.some(cat => classification.toLowerCase()
                                    .includes(cat.toLowerCase()))) {
                                totalEquity += total;
                            }

                            grandTotalBalance += total;
                        }

                        // Add totals section for Balance Sheet
                        balanceHtml += `
                                    <tr class="border-top" style="height: 10px;"><td colspan="2"></td></tr>
                                    <tr class="font-weight-bold">
                                        <td>Total Assets</td>
                                        <td class="text-right text-info">${totalAssets.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                    </tr>
                                    <tr class="font-weight-bold">
                                        <td>Total Liabilities</td>
                                        <td class="text-right text-warning">${totalLiabilities.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                    </tr>
                                    <tr class="font-weight-bold">
                                        <td>Total Equity</td>
                                        <td class="text-right text-success">${totalEquity.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                    </tr>
                                    <tr class="font-weight-bold border-top border-bottom" style="border-width: 2px !important;">
                                        <td>Grand Total</td>
                                        <td class="text-right text-primary">${grandTotalBalance.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                    </tr>
                                `;

                        // Add balance check
                        const balanceCheck = totalAssets - (totalLiabilities + totalEquity);
                        if (Math.abs(balanceCheck) >
                            0.01) { // Allow for small rounding differences
                            balanceHtml += `
                            <tr class="font-weight-bold text-danger">
                                <td>Balance Check (Assets - Liabilities - Equity)</td>
                                <td class="text-right">${balanceCheck.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                            </tr>
                        `;
                        }

                        balanceHtml += `</tbody></table>`;

                        $('#incomeStatementContainer').html(incomeHtml);
                        $('#balanceSheetContainer').html(balanceHtml);
                    },
                    error: function(xhr) {
                        $('#incomeStatementContainer').html(
                            `<p class="text-danger">Error loading income statement.</p>`);
                        $('#balanceSheetContainer').html(
                            `<p class="text-danger">Error loading balance sheet.</p>`);
                        console.error(xhr.responseText);
                    }
                });
            });


            function filterFiscalYears(query) {
                $.ajax({
                    url: '/bookkeeper/fiscal-year',
                    type: 'GET',
                    success: function(response) {
                        console.log('response', response);

                        var rows = '';

                        response.forEach(function(item) {
                            var action = '';

                            // Skip items that don't match the search query
                            if (query && !item.description.toLowerCase().includes(query)) {
                                return;
                            }

                            var activeStatus = '';
                            let bgColor = '';

                            if (item.isactive == 0 && item.ended == 0 && item.withactive > 0 ) {
                                activeStatus = `<span>Inactive</span>`;
                                bgColor = 'bg-danger';
                            } else if (item.isactive == 0 && item.ended == 0 && item.withactive == 0) {
                                activeStatus = `<button type="button" class="btn btn-success btn-sm" id="activateFiscalYear" data-id="${item.id}">Activate</button>`;
                                bgColor = 'bg-primary'
                            } else if (item.isactive == 1) {
                                activeStatus = `<button type="button" class="btn btn-danger btn-sm viewdetails" onclick="viewFiscalYearDetails(${item.id})" id="viewFiscalYearDetails" data-id="${item.id}" data-isactive="${item.isactive}">Close Fiscal Year</button>`;
                                bgColor = 'bg-success';
                            } else if (item.ended == 1) {
                                activeStatus = `<span>Ended</span>`;
                                bgColor = 'bg-warning';
                            } else {
                                activeStatus = '';
                            }


                            if (item.isactive == 0 && item.overisactive != 1 && item.ended != 0) {
                                action += `
                                    <td class="align-middle" colspan="2" style="border: 1px solid #7d7d7d; text-align: center;">
                                        <a href="#" class="text-primary viewdetails" onclick="viewFiscalYearDetailsClosed(${item.id})" id="viewFiscalYearDetailsClosed" data-id="${item.id}"  data-isactive="${item.isactive}">View Details</a>
                                    </td>
                                `;
                            } else {
                                action += `
                                    <td class="align-middle" style="border: 1px solid #7d7d7d; text-align: center;">
                                        <i class="far fa-edit text-primary editFiscalYear" data-id="${item.id}" style="cursor: pointer;"></i>
                                    </td>
                                    <td class="align-middle" style="border: 1px solid #7d7d7d; text-align: center;">
                                        <i class="fas fa-trash-alt text-danger deleteFiscalYear" data-id="${item.id}" style="cursor: pointer;"></i>
                                    </td>
                                `;
                            }

                            rows += `
                                <tr>
                                    <td class="align-middle ${bgColor}" style="border: 1px solid #7d7d7d;"></td>
                                    <td class="align-middle" style="border: 1px solid #7d7d7d;">${item.description}</td>
                                    <td class="align-middle" style="border: 1px solid #7d7d7d;">${formatDate(item.stime)}</td>
                                    <td class="align-middle" style="border: 1px solid #7d7d7d;">${formatDate(item.etime)}</td>
                                    <td class="align-middle" style="border: 1px solid #7d7d7d; text-align: center;">${activeStatus}</td>
                                    ${action}
                                </tr>
                            `;
                        });

                        $('#fiscalyeartable').html(rows);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            }

            // Initial display
            displayfiscal();

            function displayfiscal() {
                // Just call filter with empty query to show all
                filterFiscalYears('');
            }

            function formatDate(datetime) {
                const date = new Date(datetime);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }

            let isFiscalFormDirty = false;

            $(document).on('change input', '#addfiscalyearModal :input', function() {
                isFiscalFormDirty = true;
            });


            // $(document).on('click', '#savefiscalyear', function() {
            //     var fiscalYearDescription = $('#fiscalYearDescription').val();
            //     var startDateFiscal = $('#startDateFiscal').val();
            //     var endDateFiscal = $('#endDateFiscal').val();
            //     var isActive = $('#isActive').is(':checked') ? 1 : 0;

            //     $.ajax({
            //         url: '/bookkeeper/save-fiscal-year',
            //         type: 'POST',
            //         data: {
            //             fiscalYearDescription: fiscalYearDescription,
            //             startDateFiscal: startDateFiscal,
            //             endDateFiscal: endDateFiscal,
            //             isActive: isActive,
            //             _token: $('meta[name="csrf-token"]').attr('content')
            //         },
            //         success: function(response) {
            //             if (response) {
            //                 Swal.fire({
            //                     icon: 'success',
            //                     title: 'Success!',
            //                     text: 'Fiscal year saved successfully!',
            //                     confirmButtonText: 'OK'
            //                 }).then(() => {
            //                     $('#addfiscalyearModal').modal('hide');
            //                     // Clear input fields
            //                     $('#fiscalYearDescription').val('');
            //                     $('#startDateFiscal').val('');
            //                     $('#endDateFiscal').val('');
            //                     $('#isActive').prop('checked', false);
            //                     $('.modal-backdrop').removeClass('show');
            //                     $('.modal-backdrop').addClass('hide');

            //                     isFiscalFormDirty = false; // ✅ Reset flag here
            //                     displayfiscal(); // Reload table
            //                 });
            //             } else {
            //                 Swal.fire('Error', 'Failed to save fiscal year.', 'error');
            //             }
            //         },

            //         error: function(xhr) {
            //             console.error(xhr.responseText);
            //             Swal.fire('Error', 'An error occurred while saving fiscal year.',
            //                 'error');
            //         }
            //     });
            // });

            $(document).on('click', '#savefiscalyear', function() {
                var fiscalYearDescription = $('#fiscalYearDescription').val();
                var startDateVal = $('#startDateFiscal').val();
                var endDateVal = $('#endDateFiscal').val();
                var isActive = $('#isActive').is(':checked') ? 1 : 0;

                // Validate presence of dates
                if (!startDateVal || !endDateVal) {
                    Swal.fire('Missing Dates', 'Please provide both start and end dates.', 'warning');
                    return;
                }

                // Validate date range
                var startDate = new Date(startDateVal);
                var endDate = new Date(endDateVal);
                var diffTime = endDate - startDate;
                var diffDays = diffTime / (1000 * 60 * 60 * 24) + 1;

                if (diffDays !== 365 && diffDays !== 366) {
                    Swal.fire('Invalid Date Range', 'Fiscal year must be exactly 365 or 366 days.',
                        'warning');
                    return;
                }

                $.ajax({
                    url: '/bookkeeper/save-fiscal-year',
                    type: 'POST',
                    data: {
                        fiscalYearDescription,
                        startDateFiscal: startDateVal,
                        endDateFiscal: endDateVal,
                        isActive,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (!response || typeof response !== 'object') {
                            Swal.fire('Error', 'Invalid server response.', 'error');
                            return;
                        }

                        if (response.status == 1) {
                            Swal.fire({
                                type: 'success',
                                title: 'Success!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $('#addfiscalyearModal').modal('hide');
                                $('body').removeClass('modal-open');
                                $('.modal-backdrop').remove();
                                $('#fiscalYearDescription').val('');
                                $('#startDateFiscal').val('');
                                $('#endDateFiscal').val('');
                                $('#isActive').prop('checked', false);
                                isFiscalFormDirty = false;
                                displayfiscal();
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire('Error', 'An error occurred while saving fiscal year.',
                            'error');
                    }
                });
            });

            $('#closeModal').on('click', function(e) {
                e.preventDefault();
                if (isFiscalFormDirty) {
                    // stop modal from closing

                    Swal.fire({
                        title: 'Unsaved Changes',
                        text: 'You have unsaved changes. Do you want to discard them?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Discard',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.value) {
                            isFiscalFormDirty = false;

                            // close modal manually
                            setTimeout(() => {
                                $('#addfiscalyearModal').modal('hide');
                                $('body').removeClass('modal-open');
                                $('.modal-backdrop')
                                    .remove(); // Manually removes lingering backdrop 

                                $('.modal-backdrop').removeClass('show');
                                $('.modal-backdrop').addClass('hide');
                            }, 200);
                        }
                    });
                } else {
                    $('#addfiscalyearModal').modal('hide');
                    // $('.modal-backdrop').removeClass('show');
                    // $('.modal-backdrop').addClass('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop')
                        .remove(); // Manually removes lingering backdrop 
                    // Clear input fields

                }
            });


            $('#addfiscalyearModal').on('hidden.bs.modal', function() {
                $('#fiscalYearDescription').val('');
                $('#startDateFiscal').val('');
                $('#endDateFiscal').val('');
                $('#isActive').prop('checked', true);
                $('#fiscalYearId').val('');
                // isFormDirty = false;

            });

            $('[data-target="#addfiscalyearModal"]').on('click', function() {
                $('#fiscalYearDescription').val('');
                $('#startDateFiscal').val('');
                $('#endDateFiscal').val('');
                $('#isActive').prop('checked', true);
                $('#fiscalYearId').val('');

                $('#savefiscalyear').show();
                $('#updatedfiscalyear').hide();

                // Optional: update modal title to "Add Fiscal Year"
                $('#addfiscalyearModalLabel').text('Add Fiscal Year');
            });


            $(document).on('click', '.editFiscalYear', function() {
                let fiscalYearId = $(this).data('id');

                $.ajax({
                    url: `/bookkeeper/edit-fiscal-year/${fiscalYearId}`,
                    type: 'GET',
                    success: function(item) {
                        console.log(item, 'whatatatat');

                        $('#fiscalYearDescription').val(item.description);
                        $('#startDateFiscal').val(item.stime.split(' ')[0]);
                        $('#endDateFiscal').val(item.etime.split(' ')[0]);
                        $('#isActive').prop('checked', item.isactive == 1);
                        $('#fiscalYearId').val(item.id);


                        $('#addfiscalyearModal').modal('show');
                        $('#savefiscalyear').hide();
                        $('#updatedfiscalyear').show();

                        $('#updatedfiscalyear').attr('data-id', item.id);

                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });

            });

            // $(document).on('click', '.deleteFiscalYear', function() {
            //     var id = $(this).data('id');
            //     $.ajax({
            //         url: '/bookkeeper/delete-fiscal-year/' + id,
            //         type: 'DELETE',
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         success: function(response) {
            //             Swal.fire({
            //                 type: 'success',
            //                 title: 'Deleted!',
            //                 text: 'Fiscal year deleted successfully!',
            //                 confirmButtonText: 'OK'
            //             }).then(() => {
            //                 displayfiscal();
            //             });
            //         },
            //         error: function(xhr) {
            //             console.error(xhr.responseText);
            //             Swal.fire('Error', 'Failed to delete fiscal year.', 'error');
            //         }
            //     });
            // });

            $(document).on('click', '.deleteFiscalYear', function() {
                var id = $(this).data('id');

                Swal.fire({
                    text: 'Are you sure you want to remove fiscal year?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Remove'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/bookkeeper/delete-fiscal-year/' + id,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire({
                                    type: 'success',
                                    title: 'Deleted!',
                                    text: 'Fiscal year deleted successfully!',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    displayfiscal();
                                });
                            },
                            error: function(xhr) {
                                console.error(xhr.responseText);
                                Swal.fire('Error', 'Failed to delete fiscal year.',
                                    'error');
                            }
                        });
                    }
                });
            });



            // $(document).on('click', '#updatedfiscalyear', function() {
            //     var fiscalYearId = $(this).data('id');
            //     var fiscalYearDescription = $('#fiscalYearDescription').val();
            //     var startDateFiscal = $('#startDateFiscal').val();
            //     var endDateFiscal = $('#endDateFiscal').val();
            //     var isActive = $('#isActive').is(':checked') ? 1 : 0;

            //     $.ajax({
            //         url: '/bookkeeper/update-fiscal-year/' + fiscalYearId,
            //         type: 'PUT',
            //         data: {
            //             fiscalYearDescription: fiscalYearDescription,
            //             startDateFiscal: startDateFiscal,
            //             endDateFiscal: endDateFiscal,
            //             isActive: isActive,
            //             _token: $('meta[name="csrf-token"]').attr('content')
            //         },
            //         success: function(response) {
            //             if (response.status == 1) {
            //                 Swal.fire({
            //                     type: 'success',
            //                     title: 'Success!',
            //                     text: 'Fiscal year updated successfully!',
            //                     confirmButtonText: 'OK'
            //                 }).then(() => {
            //                     $('#addfiscalyearModal').modal('hide');
            //                     // Clear input fields
            //                     $('#fiscalYearDescription').val('');
            //                     $('#startDateFiscal').val('');
            //                     $('#endDateFiscal').val('');
            //                     $('#isActive').prop('checked', false);

            //                     // Reload the table
            //                     displayfiscal();
            //                 });
            //             } else if (response.status == 2) {
            //                 Swal.fire('Error', response.message, 'error');
            //             } else if (response.status == 3) {
            //                 Swal.fire('Error', response.message, 'error');
            //             }
            //         },
            //         error: function(xhr) {
            //             console.error(xhr.responseText);
            //             Swal.fire('Error', 'An error occurred while updating fiscal year.',
            //                 'error');
            //         }
            //     });
            // });

            $(document).on('click', '#closeFiscalYear', function() {
                const fiscalYearId = $('#fiscalYearModal').data('fiscalYearId');
                const isActive = 0;

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You want to close this fiscal year?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, close it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/bookkeeper/update-status',
                            type: 'PUT',
                            data: {
                                fiscalYearId: fiscalYearId,
                                isActive: isActive,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.status == 1) {
                                    Swal.fire(
                                        'Success!',
                                        'Fiscal year status updated successfully!',
                                        'success'
                                    ).then(() => {
                                        $('#fiscalYearModal').modal('hide');
                                        displayfiscal();
                                    });
                                } else {
                                    Swal.fire('Error', response.message, 'error');
                                }
                            },
                            error: function(xhr) {
                                console.error(xhr.responseText);
                                Swal.fire(
                                    'Error',
                                    'An error occurred while updating fiscal year status.',
                                    'error'
                                );
                            }
                        });
                    }
                    $('#fiscalYearModal').modal('show');
                });
            });

            $(document).on('click', '#updatedfiscalyear', function() {
                // var fiscalYearId = $(this).data('id');
                var fiscalYearId = $('#fiscalYearId').val();
                var fiscalYearDescription = $('#fiscalYearDescription').val();
                var startDateFiscal = $('#startDateFiscal').val();
                var endDateFiscal = $('#endDateFiscal').val();
                var isActive = $('#isActive').is(':checked') ? 1 : 0;
                var startDate = new Date($('#startDateFiscal').val());
                var endDate = new Date($('#endDateFiscal').val());
                var days = (endDate - startDate) / (1000 * 60 * 60 * 24) + 1;

                if (days !== 365 && days !== 366) {
                    Swal.fire('Invalid Date Range', 'Fiscal year must be 365 or 366 days.', 'warning');
                    return;
                }

                $.ajax({
                    url: '/bookkeeper/update-fiscal-year',
                    type: 'PUT',
                    data: {
                        fiscalYearId: fiscalYearId,
                        fiscalYearDescription: fiscalYearDescription,
                        startDateFiscal: startDateFiscal,
                        endDateFiscal: endDateFiscal,
                        isActive: isActive,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status == 1) {
                            Swal.fire({
                                type: 'success',
                                title: 'Success!',
                                text: 'Fiscal year updated successfully!',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $('#addfiscalyearModal').modal('hide');
                                // Clear input fields
                                $('#fiscalYearDescription').val('');
                                $('#startDateFiscal').val('');
                                $('#endDateFiscal').val('');
                                $('#isActive').prop('checked', false);

                                // Reload the table
                                displayfiscal();
                            });
                        } else if (response.status == 2) {
                            Swal.fire('Error', response.message, 'error');
                        } else if (response.status == 3) {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire('Error', 'An error occurred while updating fiscal year.',
                            'error');
                    }
                });
            });

            $(document).on('click', '#activateFiscalYear', function() {
                var id = $(this).data('id');
                
                $.ajax({
                    type: "POST",
                    url: "/bookkeeper/activate-fiscal-year",
                    data: {
                        id: id,
                        _token: $('meta[name="csrf-token"]').attr('content') // For CSRF protection
                    },
                    success: function(response) {
                        displayfiscal();
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                    },
                    error: function(xhr) {
                        let error = xhr.responseJSON?.message || 'An error occurred';
                        Toast.fire({
                            icon: 'error',
                            title: error
                        });
                    }
                });
            });

        });
    </script>
@endsection
