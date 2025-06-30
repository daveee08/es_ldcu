@extends('bookkeeper.layouts.app')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Other Set up</title>

    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <style>
        /* Table Borders and Font */
        .coa-table th,
        .coa-table td {
            border: 1px solid #7d7d7d;
            font-size: 13px;
        }

        /* Section Headers (e.g., ASSETS, LIABILITIES) */
        .coa-table .section-header {
            font-weight: 600;
            background-color: #eaeaea;
        }

        /* Hover Button Styles */
        .hover-btn {
            display: none;
            border-radius: 5px;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 10px;
            padding: 2px 5px;
            background-color: #00581f;
            color: white;
            font-style: italic;
            box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);
        }

        /* Show button on hover */
        .hoverable-cell:hover .hover-btn,
        td:hover #addSubAccount {
            display: inline-block;
        }

        /* For absolute-positioned button in relative container */
        .hoverable-cell {
            position: relative;
        }

        /* Optional: highlight row on hover */
        .hoverable-row:hover {
            background-color: lightblue !important;
        }

        .hoverable-row-main td {
            vertical-align: middle !important;
        }

        /* Action icon styling */
        .action-icons i {
            cursor: pointer;
        }

        /* Indented sub-accounts */
        .sub-account {
            padding-left: 35px;
        }
    </style>

</head>

@section('content')
    <div class="container top-0 ml-0 col-md-12">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-link fa-lg mr-2" style="font-size: 33px;"></i>
                <h1>Chart Of Accounts</h1>
            </div>
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item" style="font-size: 0.8rem;"><a href="#"
                            style="color:rgba(0,0,0,0.5);">Home</a></li>
                    <li class="breadcrumb-item active" style="font-size: 0.8rem; color:rgba(0,0,0,0.5);">Chart of
                        Accounts</li>
                </ol>
            </nav>

            <div class="mb-3" style="color: black;  font-size: 13px;">
                <ul class="nav nav-tabs" style="border-bottom: 4px solid #d9d9d9; font-weight: 600;">
                    <li class="nav-item">
                        <a href="/bookkeeper/chart_of_accounts" class="nav-link active"
                            {{ Request::url() == url('/bookkeeper/chart_of_accounts') ? 'active' : '' }}
                            style="color: black; font-weight: 600; background-color: #d9d9d9; border-top-left-radius: 10px; border-top-right-radius: 10px;">Chart
                            Of Account</a>
                    </li>
                    <li class="nav-item">
                        <a href="/bookkeeper/fiscal_year" class="nav-link"
                            {{ Request::url() == url('/bookkeeper/fiscal_year') ? 'active' : '' }}
                            style="color: black;">Fiscal Year</a>
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
                            data-target="#addAccountModal"
                            style="background-color: #00581f; height: 30px; font-size: 12.5px; font-family: Arial, sans-serif; ">
                            <i class="fas fa-plus-circle mr-1"></i> Add Accounts
                        </button>
                        <!-- Settings Button -->
                        <button type="button" class="btn btn-outline-secondary rounded" data-toggle="modal"
                            data-target="#accountSettingModal"
                            style="height: 30px; font-size: 12.5px; font-family: Arial, sans-serif;">
                            <i class="fas fa-tools"></i>
                        </button>
                    </div>

                    <!-- Right Section: Select & Search -->
                    <div class="d-flex align-items-center ml-auto">
                        <!-- Classification Label and Select -->
                        <label for="classification" class="mr-2 mb-0"
                            style="font-size: 13px; font-weight: 500; ">Classification:</label>
                        <select id="classification" class="form-control"
                            style="width: 200px; margin-right: 10px; height: 30px; font-size: 12px;">
                            <option value="all" style="font-size:12px;">All</option>
                            @php
                                $classifications = DB::table('chart_of_accounts')
                                    ->where('deleted', 0)
                                    ->groupBy('classification')
                                    ->get()
                                    ->pluck('classification')
                                    ->toArray();
                            @endphp
                            @foreach ($classifications as $classification)
                                <option value="{{ $classification }}">{{ $classification }}</option>
                            @endforeach
                        </select>

                        <!-- Search Input -->
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search" id="coa_search"
                                style="font-size: 12px; height: 30px; width: 100px; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42); border:none;">
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
                    <table class="table table-bordered table-sm" style="border-right: 1px solid #7d7d7d;">
                        <thead style="font-size: 13px; background-color: #b9b9b9;">
                            <tr>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; ">Code</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d;">Account Name</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d;">Type</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d;">Financial Statement</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d;">Normal Balance</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d;">Cash Flow Statement</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d;; text-align: center; width:5%"
                                    colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody id="coaTable" style="font-size: 12.5px;">
                            <!-- ASSETS -->
                            <tr>
                                <td style="border: 1px solid #7d7d7d;"></td>
                                <td style="border: 1px solid #7d7d7d; font-weight: 600">ASSETS</td>
                                <td style="border: 1px solid #7d7d7d;"></td>
                                <td style="border: 1px solid #7d7d7d;"></td>
                                <td style="border: 1px solid #7d7d7d;"></td>
                            </tr>
                            <tr class="hoverable-row-main">
                                <td style="border: 1px solid #7d7d7d;">100200</td>
                                <td style="border: 1px solid #7d7d7d; position: relative;" class="hoverable-cell">
                                    Cash in Bank
                                    <button class="hover-btn btn btn-xs ml-1" data-toggle="modal"
                                        data-target="#addSubAccountModal"
                                        style="border-radius: 5px; position: absolute; top: 50%; transform: translateY(-50%); box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42); font-size: 10px; background-color:#00581f; color: white; padding: 2px 5px; font-style: italic;">
                                        <i class="fas fa-plus-circle" style="margin-right: 2px;"></i> Sub Account
                                    </button>
                                </td>
                                <td style="border: 1px solid #7d7d7d;">Current Assets</td>
                                <td style="border: 1px solid #7d7d7d;">Balance Sheet</td>
                                <td style="border: 1px solid #7d7d7d;">Debit</td>
                                <td style="text-align: center; border:1px solid #7d7d7d">
                                    <button class="btn btn-xs p-0" style="background-color: transparent;"
                                        data-toggle="modal" data-target="#editAccountModal">
                                        <i class="fas fa-edit text-primary"></i>
                                    </button>
                                </td>
                                <td style="text-align: center; border: 1px solid #7d7d7d">
                                    <button class="btn btn-xs p-0" style="background-color: transparent;"
                                        data-toggle="modal" data-target="#deleteWarningModal">
                                        <i class="fas fa-trash-alt text-danger"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="hoverable-row-main">
                                <td style=" border:1px solid #7d7d7d"><em>100200-03</em></td>
                                <td style="border: 1px solid #7d7d7d; position: relative; padding-left:35px;"
                                    class="hoverable-cell">
                                    <em>BDO-3200</em>
                                    <button class="hover-btn btn btn-xs ml-1" data-toggle="modal"
                                        data-target="#addSubAccountModal2"
                                        style="border-radius: 5px; position: absolute; top: 50%; transform: translateY(-50%); box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42); font-size: 10px; background-color:#00581f; color: white; padding: 2px 5px; font-style: italic;">
                                        <i class="fas fa-plus-circle" style="margin-right: 2px;"></i> Sub Account
                                    </button>
                                </td>
                                <td style="border: 1px solid #7d7d7d;">Current Assets</td>
                                <td style="border: 1px solid #7d7d7d;">Balance Sheet</td>
                                <td style="border: 1px solid #7d7d7d;">Debit</td>
                                <td style="text-align: center; border: 1px solid #7d7d7d;"> <button class="btn btn-xs p-0"
                                        style="background-color: transparent;">
                                        <i class="fas fa-edit text-primary"></i>
                                    </button>
                                </td>
                                <td style="text-align: center; border: 1px solid #7d7d7d;">
                                    <button class="btn btn-xs p-0" style="background-color: transparent;"
                                        data-toggle="modal" data-target="#deleteWarningModal">
                                        <i class="fas fa-trash-alt text-danger"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- LIABILITIES -->
                            <tr>
                                <td style="border: 1px solid #7d7d7d;"></td>
                                <td style="border:1px solid #7d7d7d ; font-weight: 600">LIABILITIES</td>
                                <td style="border: 1px solid #7d7d7d;"></td>
                                <td style="border: 1px solid #7d7d7d;"></td>
                                <td style="border: 1px solid #7d7d7d;"></td>
                            </tr>
                            <tr class="hoverable-row-main">
                                <td style="border: 1px solid #7d7d7d;">200200</td>
                                <td style="border: 1px solid #7d7d7d;"> Accrued Expenses</td>
                                <td style="border: 1px solid #7d7d7d;">Current Liabilities</td>
                                <td style="border: 1px solid #7d7d7d;">Balance Sheet</td>
                                <td style="border: 1px solid #7d7d7d;">Credit</td>
                                <td style="text-align: center; border:1px solid #7d7d7d"> <button class="btn btn-xs p-0"
                                        style="background-color: transparent;">
                                        <i class="fas fa-edit text-primary"></i>
                                    </button>
                                </td>
                                <td style="text-align: center; border: 1px solid #7d7d7d"> <button class="btn btn-xs p-0"
                                        style="background-color: transparent;">
                                        <i class="fas fa-trash-alt text-danger"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- EQUITY -->
                            <tr>
                                <td style="border: 1px solid #7d7d7d;"></td>
                                <td style="border: 1px solid #7d7d7d;font-weight: 600"> EQUITY</td>
                                <td style="border: 1px solid #7d7d7d;"></td>
                                <td style="border: 1px solid #7d7d7d;"></td>
                                <td style="border: 1px solid #7d7d7d;"></td>
                            </tr>

                            <tr class="hoverable-row-main">
                                <td style="border: 1px solid #7d7d7d;">300100</td>
                                <td style="border: 1px solid #7d7d7d;">Fund Equity / Capital</td>
                                <td style="border: 1px solid #7d7d7d;">Fund Balance</td>
                                <td style="border: 1px solid #7d7d7d;">Balance Sheet</td>
                                <td style="border: 1px solid #7d7d7d;">Credit</td>
                                <td style="text-align: center; border: 1px solid #7d7d7d;"> <button class="btn btn-xs p-0"
                                        style="background-color: transparent;">
                                        <i class="fas fa-edit text-primary"></i>
                                    </button>
                                </td>
                                <td style="text-align: center; border: 1px solid #7d7d7d;"> <button class="btn btn-xs p-0"
                                        style="background-color: transparent;">
                                        <i class="fas fa-trash-alt text-danger"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Accounts Modal -->
    <div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog" aria-labelledby="addAccountModalLabel"
        aria-hidden="true">
        <div class="modal-dialog mt-0 modal-lg" role="document" style=" width: 40%">
            <div class="modal-content" style="border-radius: 20px 20px 20px 20px">

                <!-- Modal Header -->
                <div class="modal-header" style="padding: 10px; background-color: #cac9c9; border-radius: 20px 20px 0 0">
                    <h6 class="modal-title ml-2" id="addAccountModalLabel" style="font-size: 14px; margin-top: 3px;">
                        Add Account Form</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="coa_modal_close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <form id="addAccountForm">
                    <div class="modal-body">
                        <!-- Classification -->
                        <div class="form-group mb-4 mt-4">
                            <label for="classification_add"
                                style="font-size: 13px; font-weight: normal;">Classification</label>
                            <input type="text" class="form-control" onkeyup="this.value = this.value.toUpperCase()"
                                id="classification_add" name="classification" style="font-size: 12px; height: 40px;"
                                placeholder="Enter Classification Here">
                        </div>

                        <!-- Assets/Liabilities Buttons -->
                        <div class="form-row mt-4 mb-4" id="classificationButtonsContainer">
                            {{-- @php
                                // $classifications = DB::table('chart_of_accounts')
                                //     ->where('deleted', 0)
                                //     ->groupBy('classification')
                                //     ->get()
                                //     ->pluck('classification')
                                //     ->toArray();
                                $classifications = DB::table('bk_classifications')
                                    ->where('deleted', 0)
                                    ->groupBy('desc')
                                    ->get()
                                    ->pluck('desc')
                                    ->toArray();
                            @endphp
                            @foreach ($classifications as $classification)
                                <button type="button" class="btn btn-primary mr-3 float-left class-item"
                                    data-text="{{ $classification }}"
                                    style="background-color: #b4c4e4; border: none; color: black; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42); border-radius: 8px; height: 40px">{{ strtoupper($classification) }}</button>
                            @endforeach --}}
                        </div>

                        <!-- Code and Account Name -->
                        <div class="form-row mb-4">
                            <div class="form-group col-md-4">
                                <label for="code" style="font-weight: normal; font-size: 12.5px;">Code</label>
                                <input type="text" class="form-control" name="code" id="code"
                                    placeholder="Enter Code" style="font-size: 12px; height: 40px; ">
                            </div>
                            <div class="form-group col-md-8 ">
                                <label for="accountName_add" style="font-weight: normal; font-size: 12.5px;">Account
                                    Name</label>
                                <input type="text" class="form-control" id="accountName_add"
                                    placeholder="Enter Account Name" name="account_name"
                                    style="font-size: 12px;height: 40px;">
                            </div>
                        </div>

                        <!-- Account Type Dropdown -->
                        {{-- <div class="form-group mb-4">
                            <label for="accountType_add" style="font-weight: normal; font-size: 12.5px;">Account
                                Type</label>
                            <select class="form-control" id="accountType_add" name="account_type"
                                style="width: 80%; font-size: 12px; height: 30px;border: none;  box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                                <option value="" selected disabled>Assign Account Type</option>
                                @foreach (DB::table('bk_account_type')->where('deleted', 0)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->desc }}</option>
                                @endforeach
                                <option class="select_button" style="color:blue;  text-decoration: underline;"
                                    value="add" data-type="1" data-target="#account-type">+ Add Account
                                    Type</option>
                            </select>
                        </div> --}}

                        <div class="form-group mb-4">
                            <label for="accountType_add" style="font-weight: normal; font-size: 12.5px;">Account
                                Type</label>
                            <select class="form-control" id="accountType_add" name="account_type"
                                style="width: 80%; font-size: 12px; height: 40px;">
                                {{-- <option value="" selected disabled>Assign Account Type</option> --}}
                                {{-- @foreach (DB::table('bk_account_type')->where('deleted', 0)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->desc }}</option>
                                @endforeach --}}
                                {{-- <option class="select_button" style="color:blue; text-decoration: underline;"
                                    value="add" data-type="1" data-target="#account-type">+ Add Account Type
                                </option> --}}
                            </select>
                        </div>

                        <!-- Financial Statement Dropdown -->
                        <div class="form-group mb-4">
                            <label for="financialStatement_add" style="font-weight: normal; font-size: 12.5px;">Financial
                                Statement</label>
                            <select class="form-control" id="financialStatement_add" name="financial_statement"
                                style="width: 80%; font-size: 12px; height: 40px;">
                                {{-- <option value="" selected disabled>Assign Financial Statement</option>
                                @foreach (DB::table('bk_statement_type')->where('deleted', 0)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->desc }}</option>
                                @endforeach
                                <option style="color:blue;  text-decoration: underline;" value="add" data-type="2" data-target="#financial-statement">+ Add
                                    Financial
                                    Statement</option> --}}
                            </select>
                        </div>

                        <!-- Normal Balance Dropdown -->
                        <div class="form-group mb-4">
                            <label for="normalBalance_add"
                                style="font-weight: normal; font-size: 12.5px; padding-bottom: 2px;">Normal
                                Balance</label>
                            <select class="form-control" name="normal_balance" id="normalBalance_add"
                                style="width: 80%; font-size: 12px; height: 40px;">
                                <option value="" selected disabled>Assign Normal Balance</option>
                                @foreach (DB::table('bk_normalbalance_type')->where('deleted', 0)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->desc }}</option>
                                @endforeach
                                <option style="color:blue;  text-decoration: underline;" value="add" data-type="3"
                                    data-target="#normal-balance">+ Add Normal
                                    Balance</option>

                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label for="normalBalance_add"
                                style="font-weight: normal; font-size: 12.5px; padding-bottom: 2px;">Cashflow
                                Statement</label>
                            <select class="form-control" name="cashflow_statement" id="cashflow_statement"
                                style="width: 80%; font-size: 12px; height: 40px;">
                                <option value="" selected disabled>Assign Cashflow Statement</option>
                                <option value="Cash flow from Operating Activities">Cash flow from Operating Activities
                                </option>
                                <option value="Cash flow from Investing Activities">Cash flow from Investing Activities
                                </option>
                                <option value="Cash flow from Financing Activities">Cash flow from Financing Activities
                                </option>
                                <option value="Not Applicable">Not Applicable
                                </option>
                            </select>
                        </div>

                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer  d-flex align-items-center justify-content-center">
                        <button type="submit" class="btn btn-success d-flex align-items-center justify-content-center"
                            style="background-color: #00581f; height: 30px; font-size: 13px;">
                            <span class="padding-bottom: 5px;">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Account Setting Modal -->
    <div class="modal fade" id="accountSettingModal" tabindex="-1" role="dialog"
        aria-labelledby="accountSettingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl mt-5" role="document">
            <div class="modal-content" style="border-radius: 20px 20px 20px 20px">
                <!-- Modal Header -->
                <div class="modal-header" style="padding: 10px; background-color: #cac9c9; border-radius: 20px 20px 0 0 ">
                    <i class="fas fa-hand-pointer mr-2 ml-2"></i>
                    <h5 class="modal-title" id="accountSettingModalLabel" style="font-size:14px">Account Setting</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <!-- Tab Cards (Now functioning as buttons) -->
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <div class="card card-shadow card-button" data-type="1" role="button"
                                data-target="#account-type"
                                style="cursor: pointer; background-color: #98d3df; border-radius: 15px; height: 100px; border: none; width: 90%;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#98d3df'">
                                <div class="card-body d-flex align-items-center justify-content-center position-relative">
                                    <h5 class="card-title m-0" style="color: black; font-weight: 500;">Account Type
                                    </h5>
                                </div>
                                <div class="card-footer"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 15px;"></div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card card-shadow card-button" data-type="2" role="button"
                                data-target="#financial-statement"
                                style="cursor: pointer; background-color: #7b99ce; border-radius: 15px ; height: 100px; border: none; width: 90%;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#7b99ce'">
                                <div class="card-body d-flex align-items-center justify-content-center position-relative">
                                    <h5 class="card-title m-0" style="color: black; font-weight: 500;">Financial
                                        Statement</h5>
                                </div>
                                <div class="card-footer"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 15px;"></div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card card-shadow card-button" data-type="3" role="button"
                                data-target="#normal-balance"
                                style="cursor: pointer; background-color: #e1cba4; border-radius: 15px; height: 100px; border: none; width: 90%;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#e1cba4'">
                                <div class="card-body d-flex align-items-center justify-content-center position-relative">
                                    <h5 class="card-title m-0" style="color: black; font-weight: 500;">Normal Balance
                                    </h5>
                                </div>
                                <div class="card-footer"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 15px;"></div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card card-shadow card-button" data-type="4" role="button"
                                data-target="#classifications_setup"
                                style="cursor: pointer; background-color: #85ffa5; border-radius: 15px; height: 100px; border: none; width: 90%;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#85ffa5'">
                                <div class="card-body d-flex align-items-center justify-content-center position-relative">
                                    <h5 class="card-title m-0" style="color: black; font-weight: 500;">Classifications
                                    </h5>
                                </div>
                                <div class="card-footer"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 15px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Tabs -->
                    <div class="tab-content" id="settingTabsContent">
                        <!-- Account Type Tab -->
                        <div class="tab-pane fade show active" id="account-type" role="tabpanel">
                            <div class="mb-3">
                                <button type="button" class="btn btn-success rounded" data-toggle="modal"
                                    data-target="#SettingsAddModal"
                                    style="background-color: #00581f; height: 30px; font-size: 12.5px; font-family: Arial, sans-serif;">
                                    <i class="fas fa-plus-circle mr-1"></i> Add Account Type
                                </button>
                            </div>
                            <table class="table table-bordered table-sm">
                                <thead style="font-size: 13px; background-color: #b9b9b9;">
                                    <tr>
                                        <th style="font-weight: 600; border: 1px solid #7d7d7d">Account Type</th>
                                        <th colspan="2" style="font-weight: 600; border: 1px solid #7d7d7d; width:5%">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size:12.5px;" id="accountTypeTable">
                                    <tr>
                                        <td style="border: 1px solid #7d7d7d">Income Statement</td>
                                        <td style="text-align: center; border:1px solid #7d7d7d"> <button
                                                class="btn btn-xs p-0" style="background-color: transparent;">
                                                <i class="fas fa-edit text-primary"></i>
                                            </button>
                                        </td>
                                        <td style="text-align: center; border: 1px solid #7d7d7d"> <button
                                                class="btn btn-xs p-0" style="background-color: transparent;">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid #7d7d7d">Balance Sheets</td>
                                        <td style="text-align: center; border:1px solid #7d7d7d"> <button
                                                class="btn btn-xs p-0" style="background-color: transparent;">
                                                <i class="fas fa-edit text-primary"></i>
                                            </button>
                                        </td>
                                        <td style="text-align: center; border: 1px solid #7d7d7d"> <button
                                                class="btn btn-xs p-0" style="background-color: transparent;">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Financial Statement Tab -->
                        <div class="tab-pane fade" id="financial-statement" role="tabpanel">
                            <div class="mb-3">
                                <button type="button" class="btn btn-success rounded" data-toggle="modal"
                                    data-target="#SettingsAddModal"
                                    style="background-color: #00581f; height: 30px; font-size: 12.5px; font-family: Arial, sans-serif;">
                                    <i class="fas fa-plus-circle mr-1"></i> Add Financial Statement
                                </button>

                            </div>
                            <table class="table table-bordered table-sm">
                                <thead style="font-size: 13px; background-color: #b9b9b9;">
                                    <tr>
                                        <th style="font-weight: 600;  border: 1px solid #7d7d7d">Financial Statement
                                        </th>
                                        <th style="font-weight: 600;  border: 1px solid #7d7d7d; width:5%" colspan="2">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size:12.5px;" id="financialStatementTable">
                                    <tr>
                                        <td style="border: 1px solid #7d7d7d">Income Statement</td>
                                        <td style="text-align: center; border:1px solid #7d7d7d"> <button
                                                class="btn btn-xs p-0" style="background-color: transparent;">
                                                <i class="fas fa-edit text-primary"></i>
                                            </button>
                                        </td>
                                        <td style="text-align: center; border: 1px solid #7d7d7d"> <button
                                                class="btn btn-xs p-0" style="background-color: transparent;">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid #7d7d7d">Balance Sheets</td>
                                        <td style="text-align: center; border:1px solid #7d7d7d"> <button
                                                class="btn btn-xs p-0" style="background-color: transparent;">
                                                <i class="fas fa-edit text-primary"></i>
                                            </button>
                                        </td>
                                        <td style="text-align: center; border: 1px solid #7d7d7d"> <button
                                                class="btn btn-xs p-0" style="background-color: transparent;">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Normal Balance Tab -->
                        <div class="tab-pane fade" id="normal-balance" role="tabpanel">
                            <div class="mb-3">
                                <button type="button" class="btn btn-success rounded" data-toggle="modal"
                                    data-target="#SettingsAddModal"
                                    style="background-color: #00581f; height: 30px; font-size: 12.5px; font-family: Arial, sans-serif;">
                                    <i class="fas fa-plus-circle mr-1"></i> Add Normal Balance
                                </button>

                            </div>
                            <table class="table table-bordered table-sm" style="">
                                <thead style="font-size: 13px;background-color: #b9b9b9;">
                                    <tr>
                                        <th style="font-weight: 600; border: 1px solid #7d7d7d;">Normal Balance</th>
                                        <th style="font-weight: 600; border: 1px solid #7d7d7d; width:5%" colspan="2">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 12.5px;" id="normalBalanceTable">
                                    <tr>
                                        <td style="border: 1px solid #7d7d7d;">Debit</td>
                                        <td style="text-align: center; border:1px solid #7d7d7d"> <button
                                                class="btn btn-xs p-0" style="background-color: transparent;">
                                                <i class="fas fa-edit text-primary"></i>
                                            </button>
                                        </td>
                                        <td style="text-align: center; border: 1px solid #7d7d7d"> <button
                                                class="btn btn-xs p-0" style="background-color: transparent;">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid #7d7d7d">Credit</td>
                                        <td style="text-align: center; border:1px solid #7d7d7d"> <button
                                                class="btn btn-xs p-0" style="background-color: transparent;">
                                                <i class="fas fa-edit text-primary"></i>
                                            </button>
                                        </td>
                                        <td style="text-align: center; border: 1px solid #7d7d7d"> <button
                                                class="btn btn-xs p-0" style="background-color: transparent;">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="classifications_setup" role="tabpanel">
                            <div class="mb-3">
                                <button type="button" class="btn btn-success rounded" data-toggle="modal"
                                    data-target="#SettingsAddModal"
                                    style="background-color: #00581f; height: 30px; font-size: 12.5px; font-family: Arial, sans-serif;">
                                    <i class="fas fa-plus-circle mr-1"></i> Add Classifications
                                </button>

                            </div>
                            <table class="table table-bordered table-sm" style="">
                                <thead style="font-size: 13px;background-color: #b9b9b9;">
                                    <tr>
                                        <th style="font-weight: 600; border: 1px solid #7d7d7d;">Classification</th>
                                        <th style="font-weight: 600; border: 1px solid #7d7d7d; width:5%" colspan="2">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 12.5px;" id="classificationTable">
                                    <tr>
                                        <td style="border: 1px solid #7d7d7d;">Debit</td>
                                        <td style="text-align: center; border:1px solid #7d7d7d"> <button
                                                class="btn btn-xs p-0" style="background-color: transparent;">
                                                <i class="fas fa-edit text-primary"></i>
                                            </button>
                                        </td>
                                        <td style="text-align: center; border: 1px solid #7d7d7d"> <button
                                                class="btn btn-xs p-0" style="background-color: transparent;">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid #7d7d7d">Credit</td>
                                        <td style="text-align: center; border:1px solid #7d7d7d"> <button
                                                class="btn btn-xs p-0" style="background-color: transparent;">
                                                <i class="fas fa-edit text-primary"></i>
                                            </button>
                                        </td>
                                        <td style="text-align: center; border: 1px solid #7d7d7d"> <button
                                                class="btn btn-xs p-0" style="background-color: transparent;">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Account Modal in Settings Icon -->
    {{-- <div class="modal fade" id="SettingsAddModal" tabindex="-1" role="dialog"
        aria-labelledby="SettingsAddModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog mt-5" role="document" data-backdrop="static" style="90%;">
            <div class="modal-content" style=" border-radius: 20px  20px 20px 20px;">
                <div class="modal-header p-2" style="background-color: #cac9c9; border-radius: 20px 20px 0 0">
                    <h5 class="modal-title ml-2" id="SettingsAddModalLabel" style="font-size: 14px;">Add Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addAccountForm2">
                        <div class="form-group">
                            <label for="input_type_desc" style="font-weight: normal; font-size: 13px;">Account Type
                                Description</label>
                            <input style="font-size: 12px; border-radius: 15px;" type="text" class="form-control"
                                id="input_type_desc" placeholder="Enter Account Name" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success rounded" id="save_account_type"
                        style="background-color: #00581f; height: 30px; font-size: 12.5px; font-family: Arial, sans-serif;">
                        ADD
                    </button>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="modal fade" id="SettingsAddModal" data-backdrop="static" aria-labelledby="SettingsAddModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content overflow-hidden" style="border-radius: 20px;">
                <div class="modal-header p-2" style="background-color: #cac9c9; border-radius: 20px 20px 0 0">
                    <h5 class="modal-title ml-2" id="SettingsAddModalLabel" style="font-size: 14px;">Add Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addAccountForm2">
                        <div class="form-group">
                            <label for="input_type_desc" style="font-weight: normal; font-size: 13px;">Account Type
                                Description</label>
                            <input style="font-size: 12px; border-radius: 15px;" type="text" class="form-control"
                                id="input_type_desc" placeholder="Enter Account Name" required autocomplete="off">
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success rounded" id="save_account_type"
                        style="background-color: #00581f; height: 30px; font-size: 12.5px; font-family: Arial, sans-serif;">
                        ADD
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Account Modal in Settings Icon -->
    <div class="modal fade" id="EditSettingsAddModal_accounttype" tabindex="-1" role="dialog"
        aria-labelledby="SettingsAddModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog mt-5" role="document" data-backdrop="static" style="90%;">
            <div class="modal-content" style=" border-radius: 20px  20px 20px 20px;">
                <div class="modal-header p-2" style="background-color: #cac9c9; border-radius: 20px 20px 0 0">
                    <h5 class="modal-title ml-2" id="SettingsAddModalLabel" style="font-size: 14px;">Edit Account
                        Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addAccountForm2">
                        <div class="form-group">
                            <label for="input_type_desc" style="font-weight: normal; font-size: 13px;">Account Type
                                Description</label>
                            <input type="text" hidden id="acctype_id_edit">
                            <input style="font-size: 12px; border-radius: 15px;" type="text" class="form-control"
                                id="input_type_desc_edit" placeholder="Enter Account Name" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-primary rounded" id="update_account_type"
                        style="background-color: blue; height: 30px; font-size: 12.5px; font-family: Arial, sans-serif;">
                        UPDATE
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Add Account Modal in Settings Icon -->
    <div class="modal fade" id="EditSettingsAddModal_statementtype" tabindex="-1" role="dialog"
        aria-labelledby="SettingsAddModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog mt-5" role="document" data-backdrop="static" style="90%;">
            <div class="modal-content" style=" border-radius: 20px  20px 20px 20px;">
                <div class="modal-header p-2" style="background-color: #cac9c9; border-radius: 20px 20px 0 0">
                    <h5 class="modal-title ml-2" id="SettingsAddModalLabel" style="font-size: 14px;">Edit Account
                        Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addAccountForm2">
                        <div class="form-group">
                            <label for="input_type_desc" style="font-weight: normal; font-size: 13px;">Statement Type
                                Description</label>
                            <input type="text" hidden id="statementtype_id_edit">
                            <input style="font-size: 12px; border-radius: 15px;" type="text" class="form-control"
                                id="input_type_desc_edit_fst" placeholder="Enter Account Name" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-primary rounded" id="update_statement_type"
                        style="background-color: blue; height: 30px; font-size: 12.5px; font-family: Arial, sans-serif;">
                        UPDATE
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Account Modal in Settings Icon -->
    <div class="modal fade" id="EditSettingsAddModal_normalbalance" tabindex="-1" role="dialog"
        aria-labelledby="SettingsAddModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog mt-5" role="document" data-backdrop="static" style="90%;">
            <div class="modal-content" style=" border-radius: 20px  20px 20px 20px;">
                <div class="modal-header p-2" style="background-color: #cac9c9; border-radius: 20px 20px 0 0">
                    <h5 class="modal-title ml-2" id="SettingsAddModalLabel" style="font-size: 14px;">Edit Account
                        Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addAccountForm2">
                        <div class="form-group">
                            <label for="input_type_desc" style="font-weight: normal; font-size: 13px;">Normal Balance
                                Description</label>
                            <input type="text" hidden id="normalbalance_id_edit">
                            <input style="font-size: 12px; border-radius: 15px;" type="text" class="form-control"
                                id="input_type_desc_edit_normalbal" placeholder="Enter Account Name" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-primary rounded" id="update_normal_balance"
                        style="background-color: blue; height: 30px; font-size: 12.5px; font-family: Arial, sans-serif;">
                        UPDATE
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="EditSettingsAddModal_classifications" tabindex="-1" role="dialog"
        aria-labelledby="SettingsAddModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog mt-5" role="document" data-backdrop="static" style="90%;">
            <div class="modal-content" style=" border-radius: 20px  20px 20px 20px;">
                <div class="modal-header p-2" style="background-color: #cac9c9; border-radius: 20px 20px 0 0">
                    <h5 class="modal-title ml-2" id="SettingsAddModalLabel" style="font-size: 14px;">Edit Classification
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addAccountForm2">
                        <div class="form-group">
                            <label for="input_type_desc" style="font-weight: normal; font-size: 13px;">Classification
                                Description</label>
                            <input type="text" hidden id="cls_id_edit">
                            <input style="font-size: 12px; border-radius: 15px;" type="text" class="form-control"
                                id="input_type_desc_edit_cls" placeholder="Enter Account Name" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-primary rounded" id="update_cls"
                        style="background-color: blue; height: 30px; font-size: 12.5px; font-family: Arial, sans-serif;">
                        UPDATE
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Sub Account Modal -->
    <div class="modal fade" id="addSubAccountModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="addSubAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content ">
                <div class="modal-header" style="padding: 10px; background-color: #d9d9d9; ">
                    <h5 class="modal-title ml-2" id="addSubAccountModalLabel" style="font-size: 14px;">Add Account
                        Form</h5>
                    <button type="button" id="sub_account_close" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" hidden id="sub_coa_id_edit">
                        <label style="font-weight: normal; font-size: 13px;">Classification</label>
                        <input type="text" class="form-control" id="sub_classification_edit" readonly
                            style="height: 40px; font-size: 12px; border-radius: 10px; ">
                    </div>
                    <div class="form-row mt-2 mb-3">
                        <div class="form-group col-md-4">
                            <label style="font-weight: normal; font-size: 13px;">Code</label>
                            <input type="text" class="form-control" id="sub_code_edit" readonly
                                style="height: 40px;font-size: 12px; border-radius: 10px; ">

                        </div>
                        <div class="form-group col-md-8">
                            <label style="font-weight: normal; font-size: 13px;">Account Name</label>
                            <input type="text" class="form-control" id="sub_account_name_edit" readonly
                                style="height: 40px; font-size: 12px; border-radius: 10px; ">

                        </div>
                    </div>
                    <h6 style="font-size: 14px; margin-top: 10px;">Sub Account</h6>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label style="font-weight: normal; font-size: 13px;">Code</label>
                            <input type="text" class="form-control" id="sub_sub_code_edit"
                                style="height: 40px; font-size: 12px; ">
                        </div>
                        <div class="form-group col-md-6">
                            <label style="font-weight: normal; font-size: 13px;">Account Name</label>
                            <input type="text" class="form-control" id="sub_sub_account_name_edit"
                                placeholder="Enter Sub Account Name"
                                style="height: 40px; font-size: 12px; border-radius: 10px;">
                        </div>
                    </div>




                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" id="save_sub_account"
                        style="background-color: #00581f; height: 30px; border: none; font-size: 12.5px;">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Sub Account Modal 2 -->
    <div class="modal fade" id="editSubAccountModal" tabindex="-1" role="dialog"
        aria-labelledby="addSubAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content ">
                <div class="modal-header" style="padding: 10px; background-color: #d9d9d9; ">
                    <h5 class="modal-title ml-2" id="addSubAccountModalLabel" style="font-size: 14px;">Edit Account
                        Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" name="sub_account_edit_edit" id="sub_account_edit_edit" hidden>
                        <label style="font-weight: normal; font-size: 13px;">Classification</label>
                        <input type="text" class="form-control" id="sub_classification_edit_edit" readonly
                            style="height: 40px; font-size: 12px; border-radius: 10px;">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label style="font-weight: normal; font-size: 13px;">Code</label>
                            <input type="text" class="form-control" id="sub_code_edit_edit" readonly
                                style="height: 40px;font-size: 12px; border-radius: 10px;">

                        </div>
                        <div class="form-group col-md-8">
                            <label style="font-weight: normal; font-size: 13px;">Account Name</label>
                            <input type="text" class="form-control" id="sub_account_name_edit_edit" readonly
                                style="height: 40px; font-size: 12px; border-radius: 10px; ">
                        </div>
                    </div>
                    <h6 style="font-size: 14px; margin-top: 10px;">Sub Account</h6>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label style="font-weight: normal; font-size: 13px;">Code</label>
                            <input type="text" class="form-control" id="sub_sub_code_edit_edit"
                                style="height: 40px; font-size: 12px; border-radius: 10px; ">
                        </div>
                        <div class="form-group col-md-6">
                            <label style="font-weight: normal; font-size: 13px;">Account Name</label>
                            <input type="text" class="form-control" id="sub_sub_account_name_edit_edit"
                                placeholder="Enter Sub Account Name"
                                style="height: 40px; font-size: 12px; border-radius: 10px; ">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary" id="update_sub_account"
                        style="height: 30px; border: none; font-size: 12.5px;">Update</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Account Modal -->
    <div class="modal fade" id="editAccountModal" tabindex="-1" role="dialog" aria-labelledby="addAccountModalLabel"
        aria-hidden="true">
        <div class="modal-dialog mt-0 modal-lg" role="document" style="border-radius: 15px 15px 0 0; width: 40%;">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header" style="padding: 10px; background-color: #cac9c9;">
                    <h6 class="modal-title ml-2" id="addAccountModalLabel" style="font-size: 14px; margin-top: 3px;">
                        Edit Account Form</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form>
                        <!-- Classification -->
                        <div class="form-group mt-1 mb-4">
                            <label for="classification"
                                style="font-size: 13px; font-weight: normal;">Classification</label>
                            <input type="text" class="form-control" id="coa_id" hidden>
                            <input type="text" class="form-control" id="classification_edit"
                                style="font-size: 12px; height: 40px;" placeholder="Enter Classification Here">
                        </div>

                        <!-- Code and Account Name -->
                        <div class="form-row mb-4">
                            <div class="form-group col-md-4">
                                <label for="code" style="font-weight: normal; font-size: 12.5px;">Code</label>
                                <input type="text" class="form-control" id="code_edit" placeholder="Enter Code"
                                    style="font-size: 12px; height: 40px; ">
                            </div>
                            <div class="form-group col-md-8">
                                <label for="accountName" style="font-weight: normal; font-size: 12.5px;">Account
                                    Name</label>
                                <input type="text" class="form-control" id="accountName_edit"
                                    placeholder="Enter Account Name" style="font-size: 12px;height: 40px;">
                            </div>
                        </div>

                        <!-- Account Type Dropdown -->
                        <div class="form-group mb-4">
                            <label for="accountType" style="font-weight: normal; font-size: 12.5px;">Account
                                Type</label>
                            <select class="form-control" id="accountType_edit"
                                style="width: 80%; font-size: 12px; height: 40px;">
                                <option selected disabled>Assign Account Type</option>
                                <option>Type 1</option>
                                <option>Type 2</option>
                                <option>Type 3</option>
                            </select>
                        </div>

                        <!-- Financial Statement Dropdown -->
                        <div class="form-group mb-4">
                            <label for="financialStatement" style="font-weight: normal; font-size: 12.5px;">Financial
                                Statement</label>
                            <select class="form-control" id="financialStatement_Edit"
                                style="width: 80%; font-size: 12px; height: 40px;">
                                <option selected disabled>Assign Financial Statement</option>
                                <option>Balance Sheet</option>
                                <option>Income Statement</option>
                                <option>Cash Flow</option>
                            </select>
                        </div>

                        <!-- Normal Balance Dropdown -->
                        <div class="form-group mb-4">
                            <label for="normalBalance" style="font-weight: normal; font-size: 12.5px;">Normal
                                Balance</label>
                            <select class="form-control" id="normalBalance_edit"
                                style="width: 80%; font-size: 12px; height: 40px;">
                                <option selected disabled>Assign Normal Balance</option>
                                <option>Debit</option>
                                <option>Credit</option>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label for="normalBalance_add"
                                style="font-weight: normal; font-size: 12.5px; padding-bottom: 2px;">Cashflow
                                Statement</label>
                            <select class="form-control" name="cashflow_statement_edit" id="cashflow_statement_edit"
                                style="width: 80%; font-size: 12px; height: 40px;">
                                <option value="" selected disabled>Assign Cashflow Statement</option>
                                <option value="Cash flow from Operating Activities">Cash flow from Operating Activities
                                </option>
                                <option value="Cash flow from Investing Activities">Cash flow from Investing Activities
                                </option>
                                <option value="Cash flow from Financing Activities">Cash flow from Financing Activities
                                </option>
                                <option value="Not Applicable">Not Applicable
                                </option>
                            </select>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer d-flex justify-content-center">
                    {{-- <button type="button" class="btn btn-success"
                        style="background-color: #00581f; height: 30px; font-size: 13px; display: flex; align-items: center; justify-content: center;">Update</button> --}}
                    <button type="button" class="btn btn-success coa_update"
                        style="background-color: blue; height: 30px; font-size: 13px; display: flex; align-items: center; justify-content: center;">Update</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Warning Modal -->
    <div class="modal fade" id="deleteWarningModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteWarningModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                <div class="modal-body text-center">
                    <div>
                        <i class="fas fa-hand-paper fa-2x"></i>
                    </div>
                    <p class="mt-2" style="font-size: 1.1rem;">
                        <strong><span style="color: green;">CASH ON HAND</span> (<span style="color: blue;">ASSET</span>)
                            <span style="color: brown;">cannot be
                                Remove</span></strong><br>
                        it has been used already
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <script>
        Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });

        var gtype = '1';
        $(document).ready(function() {
            $('#coaTable').empty();
            fetchcoa();
            fetchTypes();

            $(document).on('click', '.card-button', function() {
                const target = $(this).data('target');
                gtype = $(this).data('type');
                console.log('gtype..', gtype);


                // Hide all views
                $('.tab-pane').removeClass('show active');

                // Show the selected view
                $(target).addClass('show active');
            });

            $(document).on('change', '#accountType_add', function() {
                const selectedValue = $(this).val();
                if (selectedValue === 'add') {
                    const target = $(this).find('option:selected').data('target');
                    gtype = $(this).find('option:selected').data('type');
                    console.log('gtype..', gtype);
                    console.log('target..', target);

                    // Hide all views
                    $('.tab-pane').removeClass('show active');

                    // Show the selected view
                    $(target).addClass('show active');

                    // Clear and enable the input field each time modal opens
                    $('#input_type_desc').val('').prop('disabled', false);

                    // Initialize modal only once, then show it
                    if (!$('#SettingsAddModal').data('bs.modal')) {
                        $('#SettingsAddModal').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                    }
                    // Show the modal
                    $('[data-toggle="modal"][data-target="#SettingsAddModal"]').trigger('click');

                }
            });

            $(document).on('change', '#financialStatement_add', function() {
                const selectedValue = $(this).val();
                if (selectedValue === 'add') {
                    const target = $(this).find('option:selected').data('target');
                    gtype = $(this).find('option:selected').data('type');
                    console.log('gtype..', gtype);
                    console.log('target..', target);

                    // Hide all views
                    $('.tab-pane').removeClass('show active');

                    // Show the selected view
                    $(target).addClass('show active');

                    // Clear and enable the input field each time modal opens
                    $('#input_type_desc').val('').prop('disabled', false);

                    // Initialize modal only once, then show it
                    if (!$('#SettingsAddModal').data('bs.modal')) {
                        $('#SettingsAddModal').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                    }
                    // Show the modal
                    $('[data-toggle="modal"][data-target="#SettingsAddModal"]').trigger('click');

                }
            });

            $(document).on('change', '#normalBalance_add', function() {
                const selectedValue = $(this).val();
                if (selectedValue === 'add') {
                    const target = $(this).find('option:selected').data('target');
                    gtype = $(this).find('option:selected').data('type');
                    console.log('gtype..', gtype);
                    console.log('target..', target);

                    // Hide all views
                    $('.tab-pane').removeClass('show active');

                    // Show the selected view
                    $(target).addClass('show active');

                    // Clear and enable the input field each time modal opens
                    $('#input_type_desc').val('').prop('disabled', false);

                    // Initialize modal only once, then show it
                    if (!$('#SettingsAddModal').data('bs.modal')) {
                        $('#SettingsAddModal').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                    }
                    // Show the modal
                    $('[data-toggle="modal"][data-target="#SettingsAddModal"]').trigger('click');

                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                var hoverButtons = document.querySelectorAll('.hover-btn');
                hoverButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        // Your click functionality here
                        console.log('Button clicked for: Cash in Bank');
                        // For example: alert('Details for Cash in Bank');
                    });
                });
            });

            $("#addAccountForm").submit(function(event) {
                event.preventDefault(); // Stop default form submission

                var code = $("#code").val().trim();
                var accountName = $("#accountName_add").val().trim();
                var accountType = $("#accountType_add").val();
                var classification = $("#classification_add").val();
                var financialStatement = $("#financialStatement_add").val();
                var normalBalance = $("#normalBalance_add").val();
                var cashflow_statement = $("#cashflow_statement").val();

                // Check if any required field is empty
                if (!code || !accountName || !accountType || !classification || !financialStatement || !
                    normalBalance || !cashflow_statement) {
                    Swal.fire({
                        type: 'warning', // Changed from 'type' to 'type' (SweetAlert2 update)
                        title: 'Missing Fields',
                        text: 'Please fill out all required fields before submitting.',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                $.ajax({
                    url: "/bookkeeper/storecoa",
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $("#addAccountModal").modal("hide");
                            $(".modal-backdrop").remove(); // Removes lingering backdrop
                            $("body").removeClass("modal-open"); // Prevents scrolling lock

                            Swal.fire({
                                type: 'success',
                                title: 'Success!',
                                text: 'Account added successfully!',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                fetchcoa(); // Refresh data
                            });
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = "Something went wrong!";

                        // Check for validation errors from Laravel
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors; // Extract validation errors
                            var errorList = "";

                            // Loop through errors and format them
                            $.each(errors, function(key, value) {
                                errorList +=
                                    `<li>${value[0]}</li>`; // Get first error message per field
                            });

                            Swal.fire({
                                type: 'error',
                                title: 'Validation Errors',
                                html: `<ul style="text-align:left;">${errorList}</ul>`, // Display errors in a list
                                confirmButtonText: 'OK'
                            });
                        } else {
                            // Generic error
                            Swal.fire({
                                type: 'error',
                                title: 'Error',
                                text: errorMessage,
                                confirmButtonText: 'OK'
                            });
                        }

                        console.error(xhr.responseText);
                    }
                });
            });

            $(document).on('click', '.class-item', function() {
                var text = $(this).data('text');
                console.log(text);

                $('#classification_add').val(text.toUpperCase());
            })

            $(document).on('click', '#save_account_type', function() {
                $.ajax({
                    url: "/bookkeeper/storetypes",
                    type: "POST",
                    data: {
                        type: gtype,
                        desc: $('#input_type_desc').val(),
                    },
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $("#SettingsAddModal").modal("hide");
                            $(".modal-backdrop").remove(); // Removes lingering backdrop
                            $("body").removeClass("modal-open"); // Prevents scrolling lock
                            Toast.fire({
                                type: 'success',
                                title: "Setup added successfully!"
                            })
                            fetchTypes();
                            fetch_Acctype();
                        }
                    }
                })
            })

            $(document).on('click', '.btn_delete_at, .btn_delete_fst, .btn_delete_nbt, .btn_delete_cls',
                function() {
                    var id = $(this).data('id');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {
                            console.log(id);
                            destroytype(id);
                        }
                    });
                });

            $(document).on('click', '.btn_delete_coa', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        console.log(id);
                        destroycoa(id);
                    }
                });
            });
        });

        $('#coa_search').on('input', function() {
            filterTable($(this).val().toLowerCase());
        });

        function filterTable(searchTerm) {
            if (!searchTerm) {
                // If search term is empty, show all rows
                $('#coaTable tr').show();
                return;
            }

            const searchTermLower = searchTerm.toLowerCase();
            let anyMatchFound = false;

            // First pass: hide all non-matching rows and mark matching ones
            $('#coaTable tr').each(function() {
                const row = $(this);
                let foundMatch = false;

                // Check if this is a classification header row
                const isClassificationHeader = row.find('td:nth-child(2)').has('b').length > 0;

                if (isClassificationHeader) {
                    const classificationText = row.find('td:nth-child(2)').text().toLowerCase();
                    if (classificationText.includes(searchTermLower)) {
                        foundMatch = true;
                        anyMatchFound = true;
                    }
                } else {
                    // Check all columns in regular rows
                    row.find('td').each(function() {
                        const cellText = $(this).text().toLowerCase();
                        if (cellText.includes(searchTermLower)) {
                            foundMatch = true;
                            anyMatchFound = true;
                            return false; // break out of cell loop
                        }
                    });
                }

                row.data('matches-search', foundMatch);
            });

            // Second pass: show/hide rows with proper hierarchy
            $('#coaTable tr').each(function() {
                const row = $(this);
                const isClassificationHeader = row.find('td:nth-child(2)').has('b').length > 0;

                if (isClassificationHeader) {
                    // For classification headers, show if:
                    // 1. It matches the search term itself, or
                    // 2. Any of its child rows match
                    const headerMatches = row.data('matches-search');
                    let childMatches = false;

                    // Check child rows
                    let nextRow = row.next();
                    while (nextRow.length && !nextRow.find('td:nth-child(2)').has('b').length) {
                        if (nextRow.data('matches-search')) {
                            childMatches = true;
                            break;
                        }
                        nextRow = nextRow.next();
                    }

                    if (headerMatches || childMatches) {
                        row.show();
                        // Show all child rows (they'll handle their own visibility)
                        nextRow = row.next();
                        while (nextRow.length && !nextRow.find('td:nth-child(2)').has('b').length) {
                            if (nextRow.data('matches-search')) {
                                nextRow.show();
                            } else {
                                nextRow.hide();
                            }
                            nextRow = nextRow.next();
                        }
                    } else {
                        row.hide();
                    }
                } else {
                    // For regular rows, show if they match and their parent is visible
                    if (row.data('matches-search')) {
                        // Find parent classification header
                        let prevRow = row.prev();
                        while (prevRow.length && !prevRow.find('td:nth-child(2)').has('b').length) {
                            prevRow = prevRow.prev();
                        }

                        if (prevRow.length && prevRow.is(':visible')) {
                            row.show();
                        } else {
                            row.hide();
                        }
                    } else {
                        row.hide();
                    }
                }
            });

            // If no matches found, show a message
            if (!anyMatchFound) {
                $('#coaTable').append(
                    '<tr class="no-results"><td colspan="8" class="text-center">No matching records found.</td></tr>');
            } else {
                $('.no-results').remove();
            }
        }


        $('#classification').change(function() {
            fetchcoa($(this).val());
        });

        function fetchcoa(classification = 'all') {
            $.ajax({
                url: "/bookkeeper/fetchcoa",
                type: "GET",
                dataType: "json",
                data: {
                    classification: classification
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#coaTable').empty();

                        if (Object.keys(response.data).length === 0) {
                            $('#coaTable').append(
                                '<tr><td colspan="8" class="text-center">No data found.</td></tr>');
                            return;
                        }

                        // Process each classification group
                        $.each(response.data, function(currentClassification, accountGroups) {
                            // Only show the classification header if we're showing all or if it matches the filter
                            if (classification === 'all' || currentClassification === classification) {
                                $('#coaTable').append(`<tr> 
                        <td></td> 
                        <td><b>${currentClassification.toUpperCase()}</b></td> 
                        <td></td> 
                        <td></td> 
                        <td></td> 
                        <td></td> 
                        <td></td> 
                        <td></td>  
                    </tr>`);
                            }

                            // Display each account group (main + subs)
                            accountGroups.forEach(function(accountGroup) {
                                const mainAccount = accountGroup.main;
                                const subAccounts = accountGroup.subs;

                                // Only show accounts that match the filter (or all if filter is 'all')
                                if (classification === 'all' || mainAccount.classification ===
                                    classification) {
                                    // Display main account row
                                    $('#coaTable').append(`
                            <tr class="hoverable-row-main">
                                <td style="background-color: #c7f3c7;">${mainAccount.code}</td>
                                <td style="background-color: #c7f3c7;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <span>${mainAccount.account_name}</span>
                                        <a class="ml-1"
                                        data-id="${mainAccount.id}"
                                        data-toggle="modal"
                                        data-target="#addSubAccountModal"
                                        id="addSubAccount"
                                        style="border-radius: 5px; color: #00581f; padding: 2px 5px; font-style: italic; cursor: pointer;"
                                        title="Add Sub Account">
                                            <i class="fas fa-plus-circle" style="margin-right: 2px;"></i>
                                        </a>
                                    </div>
                                </td>
                                <td style="background-color: #c7f3c7;">${mainAccount.at}</td>
                                <td style="background-color: #c7f3c7;">${mainAccount.fst}</td>
                                <td style="background-color: #c7f3c7;">${mainAccount.nbt}</td>
                                <td style="background-color: #c7f3c7;">${mainAccount.cashflow_statement}</td>
                                <td style="background-color: #c7f3c7; vertical-align: middle !important;">
                                    <div class="btn-group" role="group">
                                        <button data-id="${mainAccount.id}" data-toggle="modal"
                                            data-target="#editAccountModal" class="btn btn-xs p-0 btn_edit_coa" style="background-color: transparent;">
                                            <i class="fas fa-edit text-primary"></i>
                                        </button>
                                    </div>
                                </td>
                                <td style="background-color: #c7f3c7;">
                                    <div class="btn-group" role="group">
                                        <button data-id="${mainAccount.id}" class="btn btn-xs p-0 btn_delete_coa" style="background-color: transparent;">
                                            <i class="fas fa-trash-alt text-danger"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `);

                                    // Display sub-accounts if they exist
                                    if (subAccounts.length > 0) {
                                        subAccounts.forEach(subAccount => {
                                            $('#coaTable').append(`
                                    <tr class="hoverable-row">
                                        <td style="padding-left: 2em;">${subAccount.sub_code}</td>
                                        <td style="padding-left: 2em; font-style: italic;">
                                            ${subAccount.sub_account_name}
                                        </td>
                                        <td>${mainAccount.at}</td>
                                        <td>${mainAccount.fst}</td>
                                        <td>${mainAccount.nbt}</td>
                                        <td>${mainAccount.cashflow_statement}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button data-id="${subAccount.id}" id="editSubAccount" data-toggle="modal"
                                                    data-target="#editSubAccountModal" class="btn btn-xs p-0 btn_edit_subcoa" style="background-color: transparent;">
                                                    <i class="fas fa-edit text-info"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button data-id="${subAccount.id}" id="deleteSubAccount" class="btn btn-xs p-0 btn_delete_subcoa" style="background-color: transparent;">
                                                    <i class="fas fa-trash-alt text-danger"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                `);
                                        });
                                    }
                                }
                            });
                        });
                    } else {
                        $('#coaTable').append(
                            '<tr><td colspan="8" class="text-center">No data found.</td></tr>');
                    }
                },
                error: function(xhr) {
                    alert("Something went wrong!");
                    console.error(xhr.responseText);
                }
            });
        }

        // function fetchTypes() {
        //     $.ajax({
        //         url: "/bookkeeper/fetchtypes",
        //         type: "GET",
        //         dataType: "json",
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         success: function(response) {
        //             console.log(response);

        //             if (response.success) {
        //                 $('#accountTypeTable').empty();
        //                 $('#financialStatementTable').empty();
        //                 $('#normalBalanceTable').empty();
        //                 $('#classificationTable').empty();

        //                 if (response.data.at.length == 0) {
        //                     $('#accountTypeTable').append(
        //                         '<tr><td colspan="3" class="text-center">No data found.</td></tr>');
        //                 } else {
        //                     response.data.at.forEach(element => {
        //                         $('#accountTypeTable').append(`<tr>
    //                             <td style="border: 1px solid #7d7d7d">${element.desc}</td>
    //                             <td style="text-align: center; border:1px solid #7d7d7d"> <button data-id="${element.id}"
    //                                     data-toggle="modal" data-target="#EditSettingsAddModal_accounttype" class="btn btn-xs p-0 btn_edit_at" style="background-color: transparent;">
    //                                     <i class="fas fa-edit text-primary"></i>
    //                                 </button>
    //                             </td>
    //                             <td style="text-align: center; border: 1px solid #7d7d7d"> <button data-id="${element.id}"
    //                                     class="btn btn-xs p-0 btn_delete_at" style="background-color: transparent;">
    //                                     <i class="fas fa-trash-alt text-danger"></i>
    //                                 </button>
    //                             </td>
    //                         </tr>`);
        //                     });
        //                 }

        //                 if (response.data.fst.length == 0) {
        //                     $('#financialStatementTable').append(
        //                         '<tr><td colspan="3" class="text-center">No data found.</td></tr>');
        //                 } else {
        //                     response.data.fst.forEach(element => {
        //                         $('#financialStatementTable').append(`<tr>
    //                             <td style="border: 1px solid #7d7d7d">${element.desc}</td>
    //                             <td style="text-align: center; border:1px solid #7d7d7d"> <button  data-id="${element.id}"
    //                                 data-toggle="modal" data-target="#EditSettingsAddModal_statementtype" class="btn btn-xs p-0 btn_edit_fst" style="background-color: transparent;">
    //                                     <i class="fas fa-edit text-primary"></i>
    //                                 </button>
    //                             </td>
    //                             <td style="text-align: center; border: 1px solid #7d7d7d"> <button  data-id="${element.id}"
    //                                     class="btn btn-xs p-0 btn_delete_fst" style="background-color: transparent;">
    //                                     <i class="fas fa-trash-alt text-danger"></i>
    //                                 </button>
    //                             </td>
    //                         </tr>`);
        //                     });
        //                 }

        //                 if (response.data.nbt.length == 0) {
        //                     $('#normalBalanceTable').append(
        //                         '<tr><td colspan="3" class="text-center">No data found.</td></tr>');
        //                 } else {
        //                     response.data.nbt.forEach(element => {
        //                         $('#normalBalanceTable').append(`<tr>
    //                             <td style="border: 1px solid #7d7d7d">${element.desc}</td>
    //                             <td style="text-align: center; border:1px solid #7d7d7d"> <button  data-id="${element.id}"
    //                                 data-toggle="modal" data-target="#EditSettingsAddModal_normalbalance" class="btn btn-xs p-0 btn_edit_nbt" style="background-color: transparent;">
    //                                     <i class="fas fa-edit text-primary"></i>
    //                                 </button>
    //                             </td>
    //                             <td style="text-align: center; border: 1px solid #7d7d7d"> <button  data-id="${element.id}"
    //                                     class="btn btn-xs p-0 btn_delete_nbt" style="background-color: transparent;">
    //                                     <i class="fas fa-trash-alt text-danger"></i>
    //                                 </button>
    //                             </td>
    //                         </tr>`);
        //                     });
        //                 }

        //                 if (response.data.cls.length == 0) {
        //                     $('#normalBalanceTable').append(
        //                         '<tr><td colspan="3" class="text-center">No data found.</td></tr>');
        //                 } else {
        //                     response.data.cls.forEach(element => {
        //                         $('#classificationTable').append(`<tr>
    //                             <td style="border: 1px solid #7d7d7d">${element.desc}</td>
    //                             <td style="text-align: center; border:1px solid #7d7d7d"> <button  data-id="${element.id}"
    //                                 data-toggle="modal" data-target="#EditSettingsAddModal_classifications" class="btn btn-xs p-0 btn_edit_cls" style="background-color: transparent;">
    //                                     <i class="fas fa-edit text-primary"></i>
    //                                 </button>
    //                             </td>
    //                             <td style="text-align: center; border: 1px solid #7d7d7d"> <button  data-id="${element.id}"
    //                                     class="btn btn-xs p-0 btn_delete_cls" style="background-color: transparent;">
    //                                     <i class="fas fa-trash-alt text-danger"></i>
    //                                 </button>
    //                             </td>
    //                         </tr>`);
        //                     });
        //                 }

        //             }
        //         },
        //         error: function(xhr) {
        //             alert("Something went wrong!");
        //             console.error(xhr.responseText);
        //         }
        //     });
        // }

        function fetchTypes() {
            $.ajax({
                url: "/bookkeeper/fetchtypes",
                type: "GET",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);

                    if (response.success) {
                        $('#accountTypeTable').empty();
                        $('#financialStatementTable').empty();
                        $('#normalBalanceTable').empty();
                        $('#classificationTable').empty();

                        if (response.data.at.length == 0) {
                            $('#accountTypeTable').append(
                                '<tr><td colspan="3" class="text-center">No data found.</td></tr>');
                        } else {
                            response.data.at.forEach(element => {
                                $('#accountTypeTable').append(`<tr>
                                    <td style="border: 1px solid #7d7d7d">${element.desc}</td>
                                    <td style="text-align: center; border:1px solid #7d7d7d"> <button data-id="${element.id}"
                                            data-toggle="modal" data-target="#EditSettingsAddModal_accounttype" class="btn btn-xs p-0 btn_edit_at" style="background-color: transparent;">
                                            <i class="fas fa-edit text-primary"></i>
                                        </button>
                                    </td>
                                    <td style="text-align: center; border: 1px solid #7d7d7d"> <button data-id="${element.id}"
                                            class="btn btn-xs p-0 btn_delete_at" style="background-color: transparent;">
                                            <i class="fas fa-trash-alt text-danger"></i>
                                        </button>
                                    </td>
                                </tr>`);
                            });
                        }

                        if (response.data.fst.length == 0) {
                            $('#financialStatementTable').append(
                                '<tr><td colspan="3" class="text-center">No data found.</td></tr>');
                        } else {
                            response.data.fst.forEach(element => {
                                $('#financialStatementTable').append(`<tr>
                                    <td style="border: 1px solid #7d7d7d">${element.desc}</td>
                                    <td style="text-align: center; border:1px solid #7d7d7d"> <button  data-id="${element.id}"
                                        data-toggle="modal" data-target="#EditSettingsAddModal_statementtype" class="btn btn-xs p-0 btn_edit_fst" style="background-color: transparent;">
                                            <i class="fas fa-edit text-primary"></i>
                                        </button>
                                    </td>
                                    <td style="text-align: center; border: 1px solid #7d7d7d"> <button  data-id="${element.id}"
                                            class="btn btn-xs p-0 btn_delete_fst" style="background-color: transparent;">
                                            <i class="fas fa-trash-alt text-danger"></i>
                                        </button>
                                    </td>
                                </tr>`);
                            });
                        }

                        if (response.data.nbt.length == 0) {
                            $('#normalBalanceTable').append(
                                '<tr><td colspan="3" class="text-center">No data found.</td></tr>');
                        } else {
                            response.data.nbt.forEach(element => {
                                $('#normalBalanceTable').append(`<tr>
                                    <td style="border: 1px solid #7d7d7d">${element.desc}</td>
                                    <td style="text-align: center; border:1px solid #7d7d7d"> <button  data-id="${element.id}"
                                        data-toggle="modal" data-target="#EditSettingsAddModal_normalbalance" class="btn btn-xs p-0 btn_edit_nbt" style="background-color: transparent;">
                                            <i class="fas fa-edit text-primary"></i>
                                        </button>
                                    </td>
                                    <td style="text-align: center; border: 1px solid #7d7d7d"> <button  data-id="${element.id}"
                                            class="btn btn-xs p-0 btn_delete_nbt" style="background-color: transparent;">
                                            <i class="fas fa-trash-alt text-danger"></i>
                                        </button>
                                    </td>
                                </tr>`);
                            });
                        }

                        // if (response.data.cls.length == 0) {
                        //     $('#normalBalanceTable').append(
                        //         '<tr><td colspan="3" class="text-center">No data found.</td></tr>');
                        // } else {
                        //     response.data.cls.forEach(element => {
                        //         $('#classificationTable').append(`<tr>
                        //             <td style="border: 1px solid #7d7d7d">${element.desc}</td>
                        //             <td style="text-align: center; border:1px solid #7d7d7d"> <button  data-id="${element.id}"
                        //                 data-toggle="modal" data-target="#EditSettingsAddModal_classifications" class="btn btn-xs p-0 btn_edit_cls" style="background-color: transparent;">
                        //                     <i class="fas fa-edit text-primary"></i>
                        //                 </button>
                        //             </td>
                        //             <td style="text-align: center; border: 1px solid #7d7d7d"> <button  data-id="${element.id}"
                        //                     class="btn btn-xs p-0 btn_delete_cls" style="background-color: transparent;">
                        //                     <i class="fas fa-trash-alt text-danger"></i>
                        //                 </button>
                        //             </td>
                        //         </tr>`);

                        //         // Add classification button
                        //         $('#classificationButtonsContainer').append(
                        //             `<button type="button" class="btn btn-primary mr-3 float-left class-item"
                        //         data-text="${element.desc}"
                        //         style="background-color: #b4c4e4; border: none; color: black; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42); border-radius: 8px; height: 40px">
                        //         ${element.desc.toUpperCase()}
                        //     </button>`
                        //         );
                        //     });


                        // }

                        if (response.data.cls.length == 0) {
                            $('#normalBalanceTable').append(
                                '<tr><td colspan="3" class="text-center">No data found.</td></tr>');
                        } else {
                            response.data.cls.forEach(element => {
                                $('#classificationTable').append(`<tr>
                                    <td style="border: 1px solid #7d7d7d">${element.desc}</td>
                                    <td style="text-align: center; border:1px solid #7d7d7d"> <button  data-id="${element.id}"
                                        data-toggle="modal" data-target="#EditSettingsAddModal_classifications" class="btn btn-xs p-0 btn_edit_cls" style="background-color: transparent;">
                                            <i class="fas fa-edit text-primary"></i>
                                        </button>
                                    </td>
                                    <td style="text-align: center; border: 1px solid #7d7d7d"> <button  data-id="${element.id}"
                                            class="btn btn-xs p-0 btn_delete_cls" style="background-color: transparent;">
                                            <i class="fas fa-trash-alt text-danger"></i>
                                        </button>
                                    </td>
                                </tr>`);

                                if ($(`#classificationButtonsContainer button[data-text="${element.desc}"]`).length === 0) {
                                    addClassificationButton(element);
                                }
                            });


                        }

                        function addClassificationButton(element) {
                            $('#classificationButtonsContainer').append(
                                `<button type="button" class="btn btn-primary mr-3 float-left class-item"
                                data-text="${element.desc}"
                                style="background-color: #b4c4e4; border: none; color: black; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42); border-radius: 8px; height: 40px">
                                ${element.desc.toUpperCase()}
                            </button>`
                            );
                        }

                    }
                },
                error: function(xhr) {
                    alert("Something went wrong!");
                    console.error(xhr.responseText);
                }
            });
        }

        function destroytype(id) {
            $.ajax({
                url: "/bookkeeper/destroytype",
                type: "POST",
                data: {
                    id: id,
                    type: gtype
                },
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Toast.fire({
                            type: 'success',
                            title: 'Type deleted successfully!'
                        })
                        fetchTypes();
                    }
                    console.log(response);
                },
                error: function(xhr) {
                    alert("Something went wrong!");
                    console.error(xhr.responseText);
                }
            })
        }

        function destroycoa(id) {
            $.ajax({
                url: "/bookkeeper/destroycoa",
                type: "POST",
                data: {
                    id: id
                },
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Toast.fire({
                            type: 'success',
                            title: 'Account deleted successfully!'
                        })
                        fetchcoa();
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: 'failed to delete account!'
                        })
                    }
                    console.log(response);
                },
                error: function(xhr) {
                    alert("Something went wrong!");
                    console.error(xhr.responseText);
                }
            })
        }


        $(document).on('click', '.btn_edit_coa', function() {


            var coa_id = $(this).attr('data-id')

            $.ajax({
                type: 'GET',
                url: '/bookkeeper/coa/edit',
                data: {
                    coa_id: coa_id
                },
                success: function(response) {

                    var chart_of_accounts_selected = response.chart_of_accounts;
                    var account_type_all = response.bk_account_type_all;
                    var normalbalance_type_all = response.bk_normalbalance_type_all;
                    var statement_type_all = response.bk_statement_type_all;

                    $("#coa_id").val(chart_of_accounts_selected[0].id);
                    $("#classification_edit").val(chart_of_accounts_selected[0].classification);
                    $("#code_edit").val(chart_of_accounts_selected[0].code);
                    $("#accountName_edit").val(chart_of_accounts_selected[0].account_name);

                    $("#accountType_edit").empty().trigger('change');
                    $("#accountType_edit").append(
                        '<option value="" selected disabled>Select Account Type</option>');
                    account_type_all.forEach(account_type => {
                        if (account_type.id == chart_of_accounts_selected[0].account_type) {
                            $("#accountType_edit").append(
                                `<option value="${account_type.id}" selected>${account_type.desc}</option>`
                            );
                        } else {
                            $("#accountType_edit").append(
                                `<option value="${account_type.id}">${account_type.desc}</option>`
                            );
                        }
                    });

                    $("#normalBalance_edit").empty().trigger('change');
                    $("#normalBalance_edit").append(
                        '<option value="" selected disabled>Select Normal Balance</option>');
                    normalbalance_type_all.forEach(normalbalance_type => {
                        if (normalbalance_type.id == chart_of_accounts_selected[0]
                            .normal_balance) {
                            $("#normalBalance_edit").append(
                                `<option value="${normalbalance_type.id}" selected>${normalbalance_type.desc}</option>`
                            );
                        } else {
                            $("#normalBalance_edit").append(
                                `<option value="${normalbalance_type.id}">${normalbalance_type.desc}</option>`
                            );
                        }
                    });

                    $("#financialStatement_Edit").empty().trigger('change');
                    $("#financialStatement_Edit").append(
                        '<option value="" selected disabled>Select Financial Statement</option>');
                    statement_type_all.forEach(statement_type => {
                        if (statement_type.id == chart_of_accounts_selected[0]
                            .financial_statement) {
                            $("#financialStatement_Edit").append(
                                `<option value="${statement_type.id}" selected>${statement_type.desc}</option>`
                            );
                        } else {
                            $("#financialStatement_Edit").append(
                                `<option value="${statement_type.id}">${statement_type.desc}</option>`
                            );
                        }
                    });

                    $("#cashflow_statement_edit").val(chart_of_accounts_selected[0].cashflow_statement)
                        .trigger('change');


                }
            });

        });

        $(document).on('click', '#update_sub_account', function() {
            update_subcoa()
        })

        function update_subcoa() {
            var sub_account_edit_edit = $('#sub_account_edit_edit').val();
            var sub_classification_edit_edit = $('#sub_classification_edit_edit').val();
            var sub_code_edit_edit = $('#sub_code_edit_edit').val();
            var sub_account_name_edit_edit = $('#sub_account_name_edit_edit').val();
            var sub_sub_code_edit_edit = $('#sub_sub_code_edit_edit').val();
            var sub_sub_account_name_edit_edit = $('#sub_sub_account_name_edit_edit').val();


            $.ajax({
                type: 'POST',
                url: '/bookkeeper/subcoa/update',
                data: {
                    _token: '{{ csrf_token() }}',
                    sub_account_edit_edit: sub_account_edit_edit,
                    sub_classification_edit_edit: sub_classification_edit_edit,
                    sub_code_edit_edit: sub_code_edit_edit,
                    sub_account_name_edit_edit: sub_account_name_edit_edit,
                    sub_sub_code_edit_edit: sub_sub_code_edit_edit,
                    sub_sub_account_name_edit_edit: sub_sub_account_name_edit_edit

                },
                success: function(data) {
                    if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: 'Successfully updated'
                        })

                        fetchcoa();
                        $("#editSubAccountModal").modal("hide");
                        $(".modal-backdrop").remove(); // Removes lingering backdrop
                        $("body").removeClass("modal-open"); // Prevents scrolling lock
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }
                }
            });
        }

        $(document).on('click', '.btn_edit_at', function() {


            var acctype_id = $(this).attr('data-id')

            $.ajax({
                type: 'GET',
                url: '/bookkeeper/accounttype/edit',
                data: {
                    acctype_id: acctype_id
                },
                success: function(response) {

                    var account_type_selected = response.account_type;

                    $("#acctype_id_edit").val(account_type_selected[0].id);
                    $("#input_type_desc_edit").val(account_type_selected[0].desc);



                }
            });

        });

        $(document).on('click', '.btn_edit_fst', function() {


            var statement_type_id = $(this).attr('data-id')

            $.ajax({
                type: 'GET',
                url: '/bookkeeper/statementtype/edit',
                data: {
                    statement_type_id: statement_type_id
                },
                success: function(response) {

                    var statement_type_selected = response.statement_type;

                    $("#statementtype_id_edit").val(statement_type_selected[0].id);
                    $("#input_type_desc_edit_fst").val(statement_type_selected[0].desc);



                }
            });

        });


        $(document).on('click', '.btn_edit_nbt', function() {


            var normal_balance_id = $(this).attr('data-id')

            $.ajax({
                type: 'GET',
                url: '/bookkeeper/normalbalance/edit',
                data: {
                    normal_balance_id: normal_balance_id
                },
                success: function(response) {

                    var normal_balance_selected = response.normal_balance;

                    $("#normalbalance_id_edit").val(normal_balance_selected[0].id);
                    $("#input_type_desc_edit_normalbal").val(normal_balance_selected[0].desc);



                }
            });

        });

        $(document).on('click', '.btn_edit_cls', function() {


            var cls_id = $(this).attr('data-id')

            $.ajax({
                type: 'GET',
                url: '/bookkeeper/cls/edit',
                data: {
                    cls_id: cls_id
                },
                success: function(response) {

                    var classifications_selected = response.classifications;

                    $("#cls_id_edit").val(classifications_selected[0].id);
                    $("#input_type_desc_edit_cls").val(classifications_selected[0].desc);



                }
            });

        });



        $(document).on('click', '#update_account_type', function() {
            update_acctype()
        })

        function update_acctype() {
            var acctype_id = $('#acctype_id_edit').val();
            var acctype_desc = $('#input_type_desc_edit').val();


            $.ajax({
                type: 'POST',
                url: '/bookkeeper/accounttype/update',
                data: {
                    _token: '{{ csrf_token() }}',
                    acctype_id: acctype_id,
                    acctype_desc: acctype_desc,

                },
                success: function(data) {
                    if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: 'Successfully updated'
                        })

                        fetchTypes();
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }
                }
            });
        }

        $(document).on('click', '#update_statement_type', function() {
            update_statement_type()
        })

        function update_statement_type() {
            var statementtype_id = $('#statementtype_id_edit').val();
            var statement_desc = $('#input_type_desc_edit_fst').val();


            $.ajax({
                type: 'POST',
                url: '/bookkeeper/statementtype/update',
                data: {
                    _token: '{{ csrf_token() }}',
                    statementtype_id: statementtype_id,
                    statement_desc: statement_desc,

                },
                success: function(data) {
                    if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: 'Successfully updated'
                        })

                        fetchTypes();
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }
                }
            });
        }

        $(document).on('click', '#update_normal_balance', function() {
            update_normal_balance()
        })

        function update_normal_balance() {
            var normalbalance_id = $('#normalbalance_id_edit').val();
            var normalbalance_desc = $('#input_type_desc_edit_normalbal').val();


            $.ajax({
                type: 'POST',
                url: '/bookkeeper/normalbalance/update',
                data: {
                    _token: '{{ csrf_token() }}',
                    normalbalance_id: normalbalance_id,
                    normalbalance_desc: normalbalance_desc,

                },
                success: function(data) {
                    if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: 'Successfully updated'
                        })

                        fetchTypes();
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }
                }
            });
        }

        $(document).on('click', '#update_cls', function() {
            update_cls()
        })

        function update_cls() {
            var cls_id = $('#cls_id_edit').val();
            var cls_desc = $('#input_type_desc_edit_cls').val();


            $.ajax({
                type: 'POST',
                url: '/bookkeeper/cls/update',
                data: {
                    _token: '{{ csrf_token() }}',
                    cls_id: cls_id,
                    cls_desc: cls_desc,

                },
                success: function(data) {
                    if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: 'Successfully updated'
                        })

                        fetchTypes();
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }
                }
            });
        }


        $('#coa_modal_close').on('click', function() {
            var hasData =
                $("#classification_add").val().trim() !== "" ||
                $("#code").val().trim() !== "" ||
                $("#accountName_add").val().trim() !== "";

            // $(".highest_education_section .highest_education_row:visible").each(function() {
            //     if (
            //         $(this).find("#school_name").val().trim() != "" ||
            //         $(this).find("#year_graduated").val().trim() != "" ||
            //         $(this).find("#course").val().trim() != "" ||
            //         $(this).find("#award").val().trim() != ""
            //     ) {
            //         hasData = true;
            //         return false;
            //     }
            // })

            //employment details

            if ($('#accountType_add').val()) {
                hasData = true;
            }
            if ($('#financialStatement_add').val()) {
                hasData = true;
            }
            if ($('#normalBalance_add').val()) {
                hasData = true;
            }


            if (hasData) {
                // Confirm with the user before deleting all attendance data
                Swal.fire({
                    // title: 'Create Grade Point Equivalency Reset',
                    text: 'You have unsaved changes. Would you like to save your work before leaving?',
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'Save Changes',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Discard Changes',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {

                        // $("#profile_picture").attr('src', "/avatar/S(F) 1.png");
                        // $("#employee_picture").val("");

                        $('#classification_add').val("");
                        $('#code').val("");
                        $('#accountName_add').val("");

                        //employment details
                        $('#accountType_add').val("").trigger('change');
                        $('#financialStatement_add').val("").trigger('change');
                        $('#normalBalance_add').val("").trigger('change');

                    } else {
                        // Save employee
                        // $('#save_employeebtn__employeeInformation').click();
                        $('#addAccountModal').modal('show');

                        // $("#profile_picture").attr('src', "/avatar/S(F) 1.png");
                        // $("#employee_picture").attr('src', "/avatar/S(F) 1.png");

                    }
                });
            } else {
                // Hide modal
                $('#addAccountModal').modal('hide');

                // $("#profile_picture").attr('src', "/avatar/S(F) 1.png");
                // $("#employee_picture").val("");
            }
        });

        $(document).on('click', '#addSubAccount', function() {


            var coa_id = $(this).attr('data-id')

            $.ajax({
                type: 'GET',
                url: '/bookkeeper/coa/edit',
                data: {
                    coa_id: coa_id
                },
                success: function(response) {

                    var chart_of_accounts_selected = response.chart_of_accounts;
                    var account_type_all = response.bk_account_type_all;
                    var normalbalance_type_all = response.bk_normalbalance_type_all;
                    var statement_type_all = response.bk_statement_type_all;

                    $("#sub_coa_id_edit").val(chart_of_accounts_selected[0].id);
                    $("#sub_classification_edit").val(chart_of_accounts_selected[0].classification);
                    $("#sub_code_edit").val(chart_of_accounts_selected[0].code);
                    $("#sub_account_name_edit").val(chart_of_accounts_selected[0].account_name);

                    // $("#sub_sub_code_edit").val(chart_of_accounts_selected[0].code + "-" + String(Math
                    //     .floor(Math.random() * 900) + 100).padStart(3, '0'));

                    // Initialize a counter variable (you might want to define this at a higher scope)
                    let subAccountCounter = parseInt(localStorage.getItem('subAccountCounter')) ||
                        100; // Retrieve counter from localStorage

                    // Increment, use the counter, and store it back
                    $("#sub_sub_code_edit").val(chart_of_accounts_selected[0].code + "-" +
                        String(subAccountCounter).padStart(3, '0'));
                    localStorage.setItem('subAccountCounter', ++subAccountCounter);

                    $("#sub_accountType_edit").empty().trigger('change');
                    $("#sub_accountType_edit").append(
                        '<option value="" selected disabled>Select Account Type</option>');
                    account_type_all.forEach(account_type => {
                        if (account_type.id == chart_of_accounts_selected[0].account_type) {
                            $("#sub_accountType_edit").append(
                                `<option value="${account_type.id}" selected>${account_type.desc}</option>`
                            );
                        } else {
                            $("#sub_accountType_edit").append(
                                `<option value="${account_type.id}">${account_type.desc}</option>`
                            );
                        }
                    });

                    $("#sub_normalBalance_edit").empty().trigger('change');
                    $("#sub_normalBalance_edit").append(
                        '<option value="" selected disabled>Select Normal Balance</option>');
                    normalbalance_type_all.forEach(normalbalance_type => {
                        if (normalbalance_type.id == chart_of_accounts_selected[0]
                            .normal_balance) {
                            $("#sub_normalBalance_edit").append(
                                `<option value="${normalbalance_type.id}" selected>${normalbalance_type.desc}</option>`
                            );
                        } else {
                            $("#sub_normalBalance_edit").append(
                                `<option value="${normalbalance_type.id}">${normalbalance_type.desc}</option>`
                            );
                        }
                    });

                    $("#sub_financialStatement_Edit").empty().trigger('change');
                    $("#sub_financialStatement_Edit").append(
                        '<option value="" selected disabled>Select Financial Statement</option>');
                    statement_type_all.forEach(statement_type => {
                        if (statement_type.id == chart_of_accounts_selected[0]
                            .financial_statement) {
                            $("#sub_financialStatement_Edit").append(
                                `<option value="${statement_type.id}" selected>${statement_type.desc}</option>`
                            );
                        } else {
                            $("#sub_financialStatement_Edit").append(
                                `<option value="${statement_type.id}">${statement_type.desc}</option>`
                            );
                        }
                    });

                    $("#sub_cashflow_statement_edit").val(chart_of_accounts_selected[0]
                            .cashflow_statement)
                        .trigger('change');


                }
            });

        });

        $("#save_sub_account").click(function(e) {
            e.preventDefault();

            var sub_coa_id = $("#sub_coa_id_edit").val().trim();
            var sub_code = $("#sub_sub_code_edit").val().trim();
            var sub_accountName = $("#sub_sub_account_name_edit").val().trim();


            // Check if any required field is empty
            if (!sub_coa_id || !sub_code || !sub_accountName) {
                Swal.fire({
                    type: 'warning', // Changed from 'type' to 'type' (SweetAlert2 update)
                    title: 'Missing Fields',
                    text: 'Please fill out all required fields before submitting.',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            $.ajax({
                url: "/bookkeeper/sub_storecoa",
                type: "GET",
                data: {
                    sub_coa_id: sub_coa_id,
                    sub_code: sub_code,
                    sub_accountName: sub_accountName
                },
                success: function(response) {
                    if (response.success) {
                        $("#addSubAccountModal").modal("hide");
                        $(".modal-backdrop").remove(); // Removes lingering backdrop
                        $("body").removeClass("modal-open"); // Prevents scrolling lock

                        fetchcoa();
                    }
                    Toast.fire({
                        type: 'success',
                        title: 'Sub Account created successfully!'
                    })
                    fetchcoa();

                    $("#addSubAccountModal").modal("hide");
                    $(".modal-backdrop").remove(); // Removes lingering backdrop
                    $("body").removeClass("modal-open"); // Prevents scrolling lock
                    $("#sub_coa_id_edit").val('');
                    $("#sub_sub_account_name_edit").val('');


                },
                error: function(xhr) {
                    var errorMessage = "Something went wrong!";

                    // Check for validation errors from Laravel
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors; // Extract validation errors
                        var errorList = "";

                        // Loop through errors and format them
                        $.each(errors, function(key, value) {
                            errorList +=
                                `<li>${value[0]}</li>`; // Get first error message per field
                        });

                        Swal.fire({
                            type: 'error',
                            title: 'Validation Errors',
                            html: `<ul style="text-align:left;">${errorList}</ul>`, // Display errors in a list
                            confirmButtonText: 'OK'
                        });
                    } else {
                        // Generic error
                        Swal.fire({
                            type: 'error',
                            title: 'Error',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    }

                    console.error(xhr.responseText);
                }
            });

        });

        $(document).on('click', '#editSubAccount', function() {


            var coa_id = $(this).attr('data-id')

            $.ajax({
                type: 'GET',
                url: '/bookkeeper/subcoa/edit',
                data: {
                    coa_id: coa_id
                },
                success: function(response) {

                    var chart_of_accounts_selected = response.chart_of_accounts;
                    var account_type_all = response.bk_account_type_all;
                    var normalbalance_type_all = response.bk_normalbalance_type_all;
                    var statement_type_all = response.bk_statement_type_all;

                    $("#sub_account_edit_edit").val(chart_of_accounts_selected[0].sub_coa_id);
                    $("#sub_classification_edit_edit").val(chart_of_accounts_selected[0]
                        .classification);
                    $("#sub_code_edit_edit").val(chart_of_accounts_selected[0].code);
                    $("#sub_account_name_edit_edit").val(chart_of_accounts_selected[0].account_name);

                    $("#sub_sub_code_edit_edit").val(chart_of_accounts_selected[0].sub_code);
                    $("#sub_sub_account_name_edit_edit").val(chart_of_accounts_selected[0]
                        .sub_account_name);

                    $("#cashflow_statement_edit").val(chart_of_accounts_selected[0].cashflow_statement)
                        .trigger('change');


                }
            });

        });

        $(document).on('click', '#deleteSubAccount', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    console.log(id);
                    destroy_subcoa(id);
                }
            });
        });

        function destroy_subcoa(id) {
            $.ajax({
                url: "/bookkeeper/destroy_subcoa",
                type: "POST",
                data: {
                    id: id
                },
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Toast.fire({
                            type: 'success',
                            title: 'Sub Account deleted successfully!'
                        })
                        fetchcoa();
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: 'failed to delete sub account!'
                        })
                    }
                    console.log(response);


                },
                error: function(xhr) {
                    alert("Something went wrong!");
                    console.error(xhr.responseText);
                }
            })
        }

        $(document).on('click', '.coa_update', function() {
            update_coa()
        })

        function update_coa() {
            var coa_id = $('#coa_id').val();
            var classification_edit = $('#classification_edit').val();
            var code_edit = $('#code_edit').val();
            var accountName_edit = $('#accountName_edit').val();
            var accountType_edit = $('#accountType_edit').val();
            var financialStatement_Edit = $('#financialStatement_Edit').val();
            var normalBalance_edit = $('#normalBalance_edit').val();
            var cashflow_statement_edit = $('#cashflow_statement_edit').val();

            $.ajax({
                type: 'POST',
                url: '/bookkeeper/coa/update',
                data: {
                    _token: '{{ csrf_token() }}',
                    coa_id: coa_id,
                    classification_edit: classification_edit,
                    code_edit: code_edit,
                    accountName_edit: accountName_edit,
                    accountType_edit: accountType_edit,
                    financialStatement_Edit: financialStatement_Edit,
                    normalBalance_edit: normalBalance_edit,
                    cashflow_statement_edit: cashflow_statement_edit

                },
                success: function(data) {
                    if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: 'Successfully updated'
                        })

                        fetchcoa();
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }
                }
            });
        }

        fetch_Acctype()

        function fetch_Acctype() {
            $.ajax({
                type: 'GET',
                url: '/bookkeeper/coa/fetch_types',

                success: function(response) {
                    var account_type_all = response.bk_account_type_all;
                    var normalbalance_type_all = response.bk_normalbalance_type_all;
                    var statement_type_all = response.bk_statement_type_all;

                    $("#accountType_add").empty().trigger('change');
                    $("#accountType_add").append(
                        '<option value="" selected disabled>Select Account Type</option>');
                    account_type_all.forEach(account_type => {
                        $("#accountType_add").append(
                            `<option value="${account_type.id}">${account_type.desc}</option>`
                        );
                    });
                    $("#accountType_add").append(
                        '<option class="select_button" style="color:blue; text-decoration: underline;" value="add" data-type="1" data-target="#account-type">+ Add Account Type</option>'
                    );



                    $("#normalBalance_add").empty().trigger('change');
                    $("#normalBalance_add").append(
                        '<option value="" selected disabled>Select Normal Balance</option>');
                    normalbalance_type_all.forEach(normalbalance_type => {
                        $("#normalBalance_add").append(
                            `<option value="${normalbalance_type.id}">${normalbalance_type.desc}</option>`
                        );
                    });
                    $("#normalBalance_add").append(
                        '<option class="select_button" style="color:blue; text-decoration: underline;" value="add" data-type="3" data-target="#normal-balance">+ Add Normal Balance</option>'
                    );

                    $("#financialStatement_add").empty().trigger('change');
                    $("#financialStatement_add").append(
                        '<option value="" selected disabled>Select Financial Statement</option>');
                    statement_type_all.forEach(statement_type => {
                        $("#financialStatement_add").append(
                            `<option value="${statement_type.id}">${statement_type.desc}</option>`
                        );
                    });
                    $("#financialStatement_add").append(
                        '<option class="select_button" style="color:blue; text-decoration: underline;" value="add" data-type="2" data-target="#financial-statement">+ Add Financial Statement</option>'
                    );

                }
            });
        }

        $('#classification').on('change', function() {
            var classification = $(this).val();

            var table = $('#coaTable > tbody').DataTable();
            table.columns(2).search(classification).draw();
        });

        $('#sub_account_close').on('click', function() {
            var hasData =
                // $("#sub_sub_code_edit").val().trim() !== "" ||
                $("#sub_sub_account_name_edit").val().trim() !== "";

            if (hasData) {
                // Confirm with the user before deleting all attendance data
                Swal.fire({
                    // title: 'Create Grade Point Equivalency Reset',
                    text: 'You have unsaved changes. Would you like to save your work before leaving?',
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'Save Changes',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Discard Changes',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {

                        // $("#profile_picture").attr('src', "/avatar/S(F) 1.png");
                        // $("#employee_picture").val("");

                        // $('#sub_sub_code_edit').val("");
                        $('#sub_sub_account_name_edit').val("");

                    } else {

                        $('#addSubAccountModal').modal('show');

                    }
                });
            } else {

                $('#addSubAccountModal').modal('hide');


            }
        });
    </script>
@endsection
