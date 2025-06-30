<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cashflow Statement</title>
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
                        <h3 style="margin: 0;">Cashflow Statement</h3>
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
                        <a class="nav-link active" href="/bookkeeper/cashflow_statement"
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
                                <button class="btn btn-sm px-2" id="print_cashflow_statement"
                                    style="background-color: #143893; color: white;">
                                    <i class="fas fa-print"></i> PRINT
                                </button>
                            </div>
                        </div>
                        <table class="table table-sm w-100">
                            <thead class="table-secondary">
                                <tr>
                                    <th class="text-left" id="table_header">Classification</th>
                                    <th class="text-left" id="table_header">Particulars</th>
                                    <th class="text-left" id="table_header">Amount</th>
                                    <th class="text-left" id="table_header">Total</th>
                                </tr>
                            </thead>
                            <tbody id="cashflow_statement_table">

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
                    console.log('apply.daterangepicker');
                    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker
                        .endDate
                        .format(
                            'MM/DD/YYYY'));
                });

                // $('#dateRange').on('cancel.daterangepicker', function(ev,
                //     picker) {
                //     console.log('cancel.daterangepicker');
                //     $(this).val('');
                // });
                //working code
                // $('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
                //     $(this).val('');
                //     fetchcoa('');
                // });

                $('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    picker.setStartDate(moment());
                    picker.setEndDate(moment());
                    fetchcoa('');
                });

                fetchcoa()

                $('#fiscal_year_select').change(function() {
                    const dateRange = $('#dateRange').val();
                    fetchcoa(dateRange);
                });

                $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
                    const dateRange = $(this).val();
                    console.log('apply.daterangepicker:', dateRange);
                    fetchcoa(dateRange);
                });

                function fetchcoa(dateRange) {
                    const fiscalYear = $('#fiscal_year_select').val();
                    $.ajax({
                        url: "/bookkeeper/fetch_cashflow_statement",
                        type: "GET",
                        // dataType: "json",
                        // headers: {
                        //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        // },
                        data: {
                            fiscal_year: fiscalYear,
                            date_range: dateRange
                        },
                        success: function(response) {
                            const $table = $('#cashflow_statement_table');
                            $table.empty();

                            if (!response.success || !response.data) {
                                $table.append(
                                    '<tr><td colspan="4" class="text-center">No data available</td></tr>'
                                );
                                return;
                            }

                            // Initialize category containers
                            const categories = {
                                'OPERATING': {
                                    accounts: [],
                                    total: 0
                                },
                                'FINANCING': {
                                    accounts: [],
                                    total: 0
                                },
                                'INVESTING': {
                                    accounts: [],
                                    total: 0
                                }
                            };

                            // Process API data and categorize accounts
                            Object.entries(response.data).forEach(([category, accounts]) => {
                                const categoryType = category.includes('Operating') ? 'OPERATING' :
                                    category.includes('Financing') ? 'FINANCING' :
                                    category.includes('Investing') ? 'INVESTING' : null;

                                if (!categoryType) return;

                                accounts.forEach(account => {
                                    const amount = (parseFloat(account.debit_amount) || 0) -
                                        (parseFloat(account.credit_amount) || 0);

                                    // Group by account name and sum amounts
                                    const existingAccount = categories[categoryType]
                                        .accounts.find(a => a.name === account
                                            .account_name);
                                    if (existingAccount) {
                                        existingAccount.amount += amount;
                                    } else {
                                        categories[categoryType].accounts.push({
                                            name: account.account_name,
                                            amount: amount
                                        });
                                    }

                                    categories[categoryType].total += amount;
                                });
                            });

                            // Display the cash flow statement
                            displayCategory($table, 'CASH FLOW FROM OPERATING ACTIVITIES', categories
                                .OPERATING);
                            displayCategory($table, 'CASH FLOW FROM FINANCING ACTIVITIES', categories
                                .FINANCING);
                            displayCategory($table, 'CASH FLOW FROM INVESTING ACTIVITIES', categories
                                .INVESTING);
                        },
                        error: function(xhr) {
                            console.error("Error fetching cash flow statement:", xhr.responseText);
                            $('#cashflow_statement_table').html(
                                '<tr><td colspan="4" class="text-center text-danger">Error loading data</td></tr>'
                            );
                        }
                    });
                }

                function displayCategory($table, header, categoryData) {
                    // Add category header
                    $table.append(`
                        <tr class="category-header">
                            <td><strong>${header}</strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    `);

                    // Add accounts
                    categoryData.accounts.forEach(account => {
                        $table.append(`
                        <tr class="account-row">
                            <td></td>
                            <td>${account.name}</td>
                            <td class="text-left">${formatAmount(account.amount)}</td>
                            <td></td>
                        </tr>
                    `);
                    });

                    // Add category total
                    $table.append(`
                        <tr class="category-total">
                            <td><strong>Net Cash from ${header.replace('CASH FLOW FROM ', '').replace(' ACTIVITIES', '')} ACTIVITIES</strong></td>
                            <td></td>
                            <td></td>
                            <td style="font-size: 1.2em" class="text-left"><strong>${formatAmount(categoryData.total)}</strong></td>
                        </tr>
                        <tr><td colspan="4" style="height: 15px;"></td></tr> <!-- Spacer -->
                    `);
                }

                function formatAmount(amount) {
                    // Format with thousands separators and 2 decimal places
                    return amount.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }

                ////////////////////////////////////////////////////////////////////
                $('#print_cashflow_statement').on('click', function() {
                    const dateRange = $('#dateRange').val();
                    const fiscal_year_select = $('#fiscal_year_select').val();


                    window.open('/bookkeeper/cashflow_print?date_range=' + dateRange +
                        '&fiscal_year=' + fiscal_year_select, '_blank');
                });



                //working code
                // function fetchcoa() {
                //     $.ajax({
                //         url: "/bookkeeper/fetch_cashflow_statement",
                //         type: "GET",
                //         dataType: "json",
                //         headers: {
                //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //         },
                //         success: function(response) {
                //             if (response.success) {
                //                 if (response.data.length == 0) {
                //                     $('#cashflow_statement_table').append(
                //                         '<tr><td colspan="4" class="text-center">No data found.</td></tr>'
                //                     );
                //                     return;
                //                 }

                //                 $('#cashflow_statement_table').empty();

                //                 let grandTotal = 0;

                //                 // Process each cash flow category (Operating, Financing, Investing)
                //                 $.each(response.data, function(key, accounts) {
                //                     // Add the category header row
                //                     console.log(key);
                //                     $('#cashflow_statement_table').append(`<tr> 
        //                         <td><b>${key.toUpperCase()} </b></td> 
        //                         <td></td> 
        //                         <td></td> 
        //                         <td></td>
        //                     </tr>`);

                //                     // Group accounts by name and sum their amounts
                //                     const accountGroups = {};
                //                     let categoryTotal = 0;

                //                     accounts.forEach(element => {
                //                         const accountName = element.account_name;
                //                         const amount = (parseFloat(element.debit_amount) ||
                //                             0) - (parseFloat(element.credit_amount) ||
                //                             0);

                //                         if (!accountGroups[accountName]) {
                //                             accountGroups[accountName] = 0;
                //                         }
                //                         accountGroups[accountName] += amount;
                //                         categoryTotal += amount;
                //                     });

                //                     // Add the grouped accounts to the table
                //                     for (const [accountName, totalAmount] of Object.entries(
                //                             accountGroups)) {
                //                         $('#cashflow_statement_table').append(`
        //                             <tr>
        //                                 <td></td>
        //                                 <td>${accountName}</td>
        //                                 <td>${totalAmount.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
        //                                 <td></td>
        //                             </tr>
        //                         `);
                //                     }

                //                     // Add Net Cash from [Category] Activities for each category
                //                     const categoryName = key.charAt(0).toUpperCase() + key.slice(1)
                //                         .toLowerCase();
                //                     $('#cashflow_statement_table').append(`
        //                         <tr>
        //                             <td><b>Net Cash from ${categoryName} </b></td>
        //                             <td></td>
        //                             <td></td>
        //                             <td>${categoryTotal.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
        //                         </tr>
        //                     `);

                //                     // Add empty row between categories
                //                     $('#cashflow_statement_table').append(`
        //                         <tr>
        //                             <td></td>
        //                             <td></td>
        //                             <td></td>
        //                             <td></td>
        //                         </tr>
        //                     `);

                //                     grandTotal += categoryTotal;
                //                 });

                //                 // Add Grand Total row at the end
                //                 // $('#cashflow_statement_table').append(`
        //         //     <tr>
        //         //         <td><b>Net Increase (Decrease) in Cash</b></td>
        //         //         <td></td>
        //         //         <td></td>
        //         //         <td>${grandTotal.toFixed(2)}</td>
        //         //     </tr>
        //         // `);
                //             } else {
                //                 $('#coaTable').append(
                //                     '<tr><td colspan="4" class="text-center">No data found.</td></tr>');
                //             }
                //         },
                //         error: function(xhr) {
                //             alert("Something went wrong!");
                //             console.error(xhr.responseText);
                //         }
                //     });
                // }

                /////////////////////
                // function fetchcoa() {
                //     $.ajax({
                //         url: "/bookkeeper/fetch_cashflow_statement",
                //         type: "GET",
                //         dataType: "json",
                //         headers: {
                //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //         },
                //         success: function(response) {
                //             if (response.success) {
                //                 if (response.data.length == 0) {
                //                     $('#cashflow_statement_table').append(
                //                         '<tr><td colspan="4" class="text-center">No data found.</td></tr>'
                //                     );
                //                     return;
                //                 }

                //                 $('#cashflow_statement_table').empty();

                //                 // Process each cash flow category (Operating, Financing, Investing)
                //                 $.each(response.data, function(key, accounts) {
                //                     // Add the category header row
                //                     $('#cashflow_statement_table').append(`<tr> 
        //                         <td><b>${key.toUpperCase()}</b></td> 
        //                         <td></td> 
        //                         <td></td> 
        //                         <td></td>
        //                     </tr>`);

                //                     // Group accounts by name and sum their amounts
                //                     const accountGroups = {};

                //                     accounts.forEach(element => {
                //                         const accountName = element.account_name;
                //                         const amount = (parseFloat(element.debit_amount) ||
                //                             0) - (parseFloat(element.credit_amount) ||
                //                             0);

                //                         if (!accountGroups[accountName]) {
                //                             accountGroups[accountName] = 0;
                //                         }
                //                         accountGroups[accountName] += amount;
                //                     });

                //                     // Add the grouped accounts to the table
                //                     for (const [accountName, totalAmount] of Object.entries(
                //                             accountGroups)) {
                //                         $('#cashflow_statement_table').append(`
        //                             <tr>
        //                                 <td></td>
        //                                 <td>${accountName}</td>
        //                                 <td>${totalAmount}</td>
        //                                 <td></td>
        //                             </tr>
        //                         `);
                //                     }
                //                 });
                //             } else {
                //                 $('#coaTable').append(
                //                     '<tr><td colspan="4" class="text-center">No data found.</td></tr>');
                //             }
                //         },
                //         error: function(xhr) {
                //             alert("Something went wrong!");
                //             console.error(xhr.responseText);
                //         }
                //     });
                // }

                // function fetchcoa() {
                //     $.ajax({
                //         url: "/bookkeeper/fetch_cashflow_statement",
                //         type: "GET",
                //         dataType: "json",
                //         headers: {
                //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //         },
                //         success: function(response) {
                //             if (response.success) {

                //                 if (response.data.length == 0) {
                //                     $('#cashflow_statement_table').append(
                //                         '<tr><td colspan="4" class="text-center">No data found.</td></tr>'
                //                     );
                //                     return
                //                 }

                //                 $('#cashflow_statement_table').empty();
                //                 $.each(response.data, function(key, value) {
                //                     $('#cashflow_statement_table').append(`<tr> 
        //                     <td><b>${key.toUpperCase()}</b></td> 
        //                     <td></td> 
        //                     <td></td> 
        //                     <td></td>
        //                     <td></td>  
        //                 </tr>`);

                //                     value.forEach(element => {
                //                         $('#cashflow_statement_table').append(`
        //                         <tr>
        //                             <td></td>
        //                             <td>${element.account_name}</td>
        //                             <td>${(parseFloat(element.debit_amount) || 0) - (parseFloat(element.credit_amount) || 0)}</td>
        //                             <td>${element.cashflow_statement}</td>

        //                         </tr>
        //                     `);
                //                     });

                //                 })
                //             } else {
                //                 $('#coaTable').append(
                //                     '<tr><td colspan="4" class="text-center">No data found.</td></tr>');
                //             }
                //         },
                //         error: function(xhr) {
                //             alert("Something went wrong!");
                //             console.error(xhr.responseText);
                //         }
                //     });
                // }

            });
        </script>
    @endsection
</body>
