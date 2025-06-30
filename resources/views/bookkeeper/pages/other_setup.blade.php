<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Other Set up</title>
    @extends('bookkeeper.layouts.app')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <style>
        .dataTables_scrollBody {
            overflow: hidden !important;
        }
        .null{
            border: 1px solid #BB2124 !important;
        }
        .null:focus {
            box-shadow: 0 0 5px #BB2124;
        }
    </style>
</head>
@section('content')
    @php
        // Fetch all chart accounts with their subaccounts (including active flags)
        $chartAccounts = DB::table('chart_of_accounts')
            ->where('deleted', 0)
            ->get()
            ->map(function ($coa) {
                $coa->subaccounts = DB::table('bk_sub_chart_of_accounts')
                    ->where('coaid', $coa->id)
                    ->where('deleted', 0)
                    ->get();
                return $coa;
            });

        // Helper function to find active account id for a specific flag
        function findActiveAccountId($accounts, $flagName)
        {
            foreach ($accounts as $coa) {
                if ($coa->$flagName == 1) {
                    return $coa->id;
                }
                foreach ($coa->subaccounts as $sub) {
                    if ($sub->$flagName == 1) {
                        return $sub->id;
                    }
                }
            }
            return null;
        }

        // Find active account/subaccount IDs for each dropdown based on flags
        $d_debit_active = findActiveAccountId($chartAccounts, 'd_adjustmentje_debactive');
        $d_credit_active = findActiveAccountId($chartAccounts, 'd_adjustmentje_credactive');
        $c_debit_active = findActiveAccountId($chartAccounts, 'c_adjustmentje_debactive');
        $c_credit_active = findActiveAccountId($chartAccounts, 'c_adjustmentje_credactive');

        // Fallback: if no active found, select first account or subaccount id for each dropdown
        function fallbackFirstAccountId($accounts)
        {
            foreach ($accounts as $coa) {
                if ($coa) {
                    return $coa->id;
                }
                foreach ($coa->subaccounts as $sub) {
                    if ($sub) {
                        return $sub->id;
                    }
                }
            }
            return null;
        }

        if (!$d_debit_active) {
            $d_debit_active = fallbackFirstAccountId($chartAccounts);
        }
        if (!$d_credit_active) {
            $d_credit_active = fallbackFirstAccountId($chartAccounts);
        }
        if (!$c_debit_active) {
            $c_debit_active = fallbackFirstAccountId($chartAccounts);
        }
        if (!$c_credit_active) {
            $c_credit_active = fallbackFirstAccountId($chartAccounts);
        }

        $acadprog = DB::table('academicprogram')->get();
    @endphp

    <body>
        <div class="container-fluid">
            <div class="container-fluid">
                <!-- Page Header -->
                <div class="d-flex align-items-center ">
                    <i class="fas fa-link fa-lg mr-2"></i>
                    <h1>Other Setup</h1>
                </div>
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item" style="font-size: 0.8rem;"><a href="#"
                                style="color:rgba(0,0,0,0.5);">Home</a></li>
                        <li class="breadcrumb-item active" style="font-size: 0.8rem; color:rgba(0,0,0,0.5);">Other Setup
                        </li>
                    </ol>
                </nav>

                <div class="mb-3" style="color: black;  font-size: 13px;">
                    <ul class="nav nav-tabs" style="border-bottom: 4px solid #d9d9d9; font-weight: 600;">
                        <li class="nav-item">
                            <a href="/bookkeeper/chart_of_accounts" class="nav-link"
                                {{ Request::url() == url('/bookkeeper/chart_of_accounts') ? 'active' : '' }}
                                style="color: black;">Chart
                                Of Account</a>
                        </li>
                        <li class="nav-item">
                            <a href="/bookkeeper/fiscal_year" class="nav-link"
                                {{ Request::url() == url('/bookkeeper/fiscal_year') ? 'active' : '' }}
                                style="color: black;">Fiscal
                                Year</a>
                        </li>
                        <li class="nav-item">
                            <a href="/bookkeeper/enrollment_setup" class="nav-link"
                                {{ Request::url() == url('/bookkeeper/enrollment_setup') ? 'active' : '' }}
                                style="color: black;">Enrollment
                                Setup</a>
                        </li>
                        <li class="nav-item">
                            <a href="/bookkeeper/other_setup" class="nav-link active"
                                {{ Request::url() == url('/bookkeeper/other_setup') ? 'active' : '' }}
                                style="color: black;  font-weight: 600; background-color: #d9d9d9; border-top-left-radius: 10px; border-top-right-radius: 10px;">Other
                                Setup</a>
                        </li>
                    </ul>
                </div>
                <hr style="border-top: 2px solid #d9d9d9;">
                {{-- <div class="card mt-4" style="border-color: white;">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="card card-shadow" role="button"
                                style="background-color: #98d3df; border-radius: 15px; height: 125px; border: none; width: 17.5%; margin-right: 3.33%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#98d3df'">
                                <div class="card-body d-flex align-items-center justify-content-center position-relative">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">Cashier
                                        JE</h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a href="#" id="cashier_je"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">View
                                        more info</a>
                                </div>
                            </div>

                            <div class="card card-shadow" role="button"
                                style="background-color: #d9d9d9; border-radius: 15px; height: 125px; border: none; width: 17.5%; margin-right: 3.33%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#d9d9d9'">
                                <div class="card-body d-flex align-items-center justify-content-center position-relative">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">
                                        Discounts</h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a id="discountModalbtn" href="#"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">View
                                        more info</a>
                                </div>
                            </div>

                            <div class="card card-shadow" role="button"
                                style="background-color: #d9d9d9; border-radius: 15px; height: 125px; border: none; width: 17.5%; margin-right: 3.33%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#d9d9d9'">
                                <div class="card-body d-flex align-items-center justify-content-center position-relative">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">
                                        Adjustment</h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a id="adjustmentModalbtn" href="#"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">View
                                        more info</a>
                                </div>
                            </div>

                            <div class="card card-shadow" role="button"
                                style="background-color: #98d3df; border-radius: 15px; height: 125px; border: none; width: 17.5%; margin-right: 3.33%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#98d3df'">
                                <div class="card-body d-flex align-items-center justify-content-center position-relative">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">OLD
                                        Accounts</h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a href="#"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">View
                                        more info</a>
                                </div>
                            </div>

                            <div class="card card-shadow" role="button"
                                style="background-color: #98d3df; border-radius: 15px; height: 125px; border: none; width: 17.5%; margin-right: 3.33%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#98d3df'">
                                <div class="card-body d-flex align-items-center justify-content-center position-relative">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">Payroll
                                    </h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    data-toggle="modal" data-target="#payrollAccount"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a href="#"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">View
                                        more info</a>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex">
                            <div class="card card-shadow" role="button"
                                style="background-color: #7d9bd6; border-radius: 15px; height: 125px; border: none; width: 17.5%; margin-right: 3.33%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#7d9bd6'">
                                <div class="card-body d-flex align-items-center justify-content-center position-relative">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">
                                        Negative Amounts</h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a href="#"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">View
                                        more info</a>
                                </div>
                            </div>
                            <div class="card card-shadow" role="button"
                                style="background-color: #e1cba4; border-radius: 15px; height: 125px; border: none; width: 17.5%; margin-right: 3.33%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#e1cba4'">
                                <div class="card-body d-flex align-items-center justify-content-center position-relative">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">
                                        Depreciation method</h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a href="#" data-toggle="modal" data-target="#depreciationModal"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">
                                        View more info
                                    </a>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex">
                            <div class="card card-shadow" role="button"
                                style="background-color:#d29393; border-radius: 15px; height: 125px; border: none; width: 17.5%; margin-right: 3.33%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#d29393'">
                                <div class="card-body d-flex align-items-center justify-content-center position-relative">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">
                                        Expenses Items</h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a href="#" id="view_expenses" class="view-expenses-info"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">View
                                        more info</a>
                                </div>
                            </div>

                            <div class="card card-shadow" role="button"
                                style="background-color: #d9d9d9; border-radius: 15px; height: 125px; border: none; width: 17.5%; margin-right: 3.33%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#d9d9d9'">
                                <div class="card-body d-flex align-items-center justify-content-center position-relative">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">Bank
                                        Setup</h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a href="#"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">View
                                        more info</a>
                                </div>
                            </div>

                            <div class="card card-shadow" role="button"
                                style="background-color: #cea5fc; border-radius: 15px; height: 125px; border: none; width: 17.5%; margin-right: 3.33%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#cea5fc'">
                                <div class="card-body d-flex align-items-center justify-content-center position-relative">
                                    <h5 class="card-title m-0" style="color: black; font-weight: 500;">Signatories</h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a href="#" data-toggle="modal" data-target="#signatoriesModal"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">
                                        View more info
                                    </a>
                                </div>
                            </div>

                            <div class="card card-shadow" role="button"
                                style="background-color:#a3ceb0; border-radius: 15px; height: 125px; border: none; width: 17.5%; margin-right: 3.33%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#c7ffd8'">
                                <div class="card-body d-flex align-items-center justify-content-center position-relative">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">
                                        Cost Center</h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a href="#" class="view-expenses-info" data-toggle="modal"
                                        data-target="#CostCenterModal"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">View
                                        more info</a>
                                </div>
                            </div>

                        </div>

                        <hr>






                        <div class="d-flex justify-content-between mb-3">
                            <!-- Card 1 -->
                            
                            <!-- Card 2 -->
                            
                            <!-- Card 3 -->
                           
                            <!-- Card 4 -->
                            
                            <!-- Card 5 -->
                            
                        </div>

                        <div class="d-flex">
                            <!-- Card 6 (aligned under Card 1) -->
                           
                            <!-- Card 7 (aligned under Card 2) -->
                            


                           


                            <!-- Card 8 (aligned under Card 3) -->
                            

                            <!-- Card 9 (aligned under Card 4) -->
                            





                        </div>

                        <div class="d-flex" style="margin-top: 15px;">
                            <!-- Card 6 (aligned under Card 1) -->
                            

                        </div>
                    </div>
                </div> --}}
                <div class="card mt-4" style="border-color: white;">
                    <div class="card-body">
                        <!-- First Row -->
                        <div class="d-flex flex-wrap justify-content-between mb-4">
                            <!-- Cashier JE -->
                            <div class="card card-shadow mb-3" role="button"
                                style="background-color: #98d3df; border-radius: 15px; height: 125px; border: none; width: 18%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#98d3df'">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">Cashier
                                        JE</h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a href="#" id="cashier_je"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">View
                                        more info</a>
                                </div>
                            </div>

                            <!-- Discounts -->
                            <div class="card card-shadow mb-3" role="button"
                                style="background-color: #d9d9d9; border-radius: 15px; height: 125px; border: none; width: 18%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#d9d9d9'">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">Discounts
                                    </h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a id="discountModalbtn" href="#"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">View
                                        more info</a>
                                </div>
                            </div>


                            <!--Adjustment -->
                            {{-- <div class="card card-shadow mb-3" role="button"
                                style="background-color: #d9d9d9; border-radius: 15px; height: 125px; border: none; width: 18%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#d9d9d9'">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">
                                        Adjustment</h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a id="adjustmentModalbtn" href="#"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">View
                                        more info</a>
                                </div>
                            </div> --}}

                            <div class="card card-shadow mb-3" role="button"
                                style="background-color: #d9d9d9; border-radius: 15px; height: 125px; border: none; width: 18%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#d9d9d9'">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">
                                        Adjustment</h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a id="adjustmentModalbtn2" href="#"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">View
                                        more info</a>
                                </div>
                            </div>

                            <!-- OLD Accounts -->
                            <div class="card card-shadow mb-3" role="button"
                                style="background-color: #98d3df; border-radius: 15px; height: 125px; border: none; width: 18%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#98d3df'">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">OLD
                                        Accounts</h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a href="#"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">View
                                        more info</a>
                                </div>
                            </div>

                            <!-- Payroll -->
                            <div class="card card-shadow mb-3" role="button"
                                style="background-color: #98d3df; border-radius: 15px; height: 125px; border: none; width: 18%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#98d3df'">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">Payroll
                                    </h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    data-toggle="modal" data-target="#payrollAccount"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a href="#"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">View
                                        more info</a>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Second Row -->
                        <div class="d-flex flex-wrap justify-content-between mb-4">
                            <!-- Negative Amounts -->
                            <div class="card card-shadow mb-3" role="button"
                                style="background-color: #7d9bd6; border-radius: 15px; height: 125px; border: none; width: 18%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#7d9bd6'">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">
                                        Negative Amounts</h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a href="#"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">View
                                        more info</a>
                                </div>
                            </div>

                            <!-- Depreciation method -->
                            <div class="card card-shadow mb-3" role="button"
                                style="background-color: #e1cba4; border-radius: 15px; height: 125px; border: none; width: 18%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#e1cba4'">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">
                                        Depreciation method</h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a href="#" data-toggle="modal" data-target="#depreciationModal"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">
                                        View more info
                                    </a>
                                </div>
                            </div>

                            <!-- Adjustment2 -->
                            {{-- <div class="card card-shadow mb-3" role="button"
                                style="background-color: #d9d9d9; border-radius: 15px; height: 125px; border: none; width: 18%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#d9d9d9'">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">
                                        Adjustment</h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a id="adjustmentModalbtn2" href="#"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">View
                                        more info</a>
                                </div>
                            </div> --}}

                            <!-- Empty space to maintain alignment (3 cards in this row) -->
                            <div style="width: 18%; visibility: hidden;"></div>
                            <div style="width: 18%; visibility: hidden;"></div>
                            <div style="width: 18%; visibility: hidden;"></div>
                        </div>

                        <hr>

                        <!-- Third Row -->
                        <div class="d-flex flex-wrap justify-content-between">
                            <!-- Expenses Items -->
                            <div class="card card-shadow mb-3" role="button"
                                style="background-color:#d29393; border-radius: 15px; height: 125px; border: none; width: 18%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#d29393'">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">
                                        Expenses Items</h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a href="#" id="view_expenses" class="view-expenses-info"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">View
                                        more info</a>
                                </div>
                            </div>

                            <!-- Bank Setup -->
                            <div class="card card-shadow mb-3" role="button"
                                style="background-color: #d9d9d9; border-radius: 15px; height: 125px; border: none; width: 18%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#d9d9d9'">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">Bank
                                        Setup</h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a href="#"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">View
                                        more info</a>
                                </div>
                            </div>

                            <!-- Signatories -->
                            <div class="card card-shadow mb-3" role="button"
                                style="background-color: #cea5fc; border-radius: 15px; height: 125px; border: none; width: 18%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#cea5fc'">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <h5 class="card-title m-0" style="color: black; font-weight: 500;">Signatories</h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a href="#" data-toggle="modal" data-target="#signatoriesModal"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">
                                        View more info
                                    </a>
                                </div>
                            </div>

                            <!-- Cost Center -->
                            <div class="card card-shadow mb-3" role="button"
                                style="background-color:#a3ceb0; border-radius: 15px; height: 125px; border: none; width: 18%; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#7f7f7f'"
                                onmouseout="this.style.backgroundColor='#c7ffd8'">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <h5 class="card-title m-0 text-center" style="color: black; font-weight: 500;">Cost
                                        Center</h5>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-center"
                                    style="background-color: white; border-radius: 0 0 15px 15px; height: 30px;">
                                    <a href="#" class="view-expenses-info" data-toggle="modal"
                                        data-target="#CostCenterModal"
                                        style="text-decoration: underline; font-style: italic; color: #007bff; font-size: 12px;">View
                                        more info</a>
                                </div>
                            </div>

                            <!-- Empty space to maintain alignment (4 cards in this row) -->
                            <div style="width: 18%; visibility: hidden;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="CostCenterModal" data-bs-backdrop="static" tabindex="-1"
            aria-labelledby="signatoriesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title" id="signatoriesModalLabel">Cost Center</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size: 24px;">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table id="cost_center_table" class="table table-striped table-sm text-sm" style="width:100%; font-size: 14px; table-layout: fixed !important;">
                                    <thead>
                                        <tr>
                                            <th width="30%">Account</th>
                                            <th width="60%">Description</th>
                                            <th width="10%" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="signatoriesModal" data-bs-backdrop="static" tabindex="-1"
            aria-labelledby="signatoriesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title" id="signatoriesModalLabel">Signatories</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size: 24px;">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @include('bookkeeper.pages.signatories')
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="payrollAccount" tabindex="-1" role="dialog" aria-labelledby="payrollAccountLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content shadow">
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title" id="payrollAccountLabel">Payroll Accounts JE</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size: 24px;">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @include('bookkeeper.pages.payroll_acc')
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="cashierJEModal" tabindex="-1" role="dialog" data-backdrop="static"
            aria-labelledby="expensesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content shadow">
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title" id="expensesModalLabel">Cashier Journal Entry</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size: 24px;">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row p-3 shadow-lg rounded bg-white">
                            {{-- <div class="col-md-3">
                                <label class="font-weight-bold">Cashier Journal Entry</label>
                            </div> --}}
                            <div class="col-md-12">
                                <label class="font-weight-bold">Account</label>
                                <select class="form-control" id="debit_account_id">
                                    @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                        <option value="{{ $coa->id }}">{{ $coa->account_name }}</option>
                                        @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->where('deleted', 0)->get() as $subcoa)
                                            <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                {{ $subcoa->sub_account_name }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                                <br>
                                <br>
                                <div class="text-center">
                                    <button class="btn btn-success" id="saveCashierJE">Save</button>
                                </div>
                                {{-- <div class="text-center">
                                    <button class="btn btn-success" id="saveCashierJE">Set as Active Cashier JE</button>
                                </div> --}}
                                <br>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="expensesModal" tabindex="-1" role="dialog" aria-labelledby="expensesModalLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content shadow">
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title" id="expensesModalLabel">Expenses Item Details</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size: 24px;">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- @include('bookkeeper.pages.expenses_items') --}}
                        <div class="container-fluid mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <button class="btn btn-success" id="addItemBtn">
                                    <i class="fas fa-plus"></i> Add Expenses Item
                                </button>

                                <div class="form-inline">
                                    <input type="text" class="form-control mr-2" id="searchBar"
                                        placeholder="Search...">
                                    <i class="fas fa-search" id="searchIcon"></i>
                                </div>
                            </div>

                            <div class="table-responsive" style="max-height: 600px; overflow-y: scroll;">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Item Code</th>
                                            <th>Item Name</th>
                                            <th>QTY</th>
                                            <th>Amount</th>
                                            <th>Inventory Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="expenseTableBody">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Depreciation Method Modal -->
        <div class="modal fade" id="depreciationModal" tabindex="-1" aria-labelledby="depreciationModalLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content shadow">
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title" id="depreciationModalLabel">Depreciation Method</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size: 24px;">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @include('bookkeeper.pages.depreciation_method')
                    </div>
                </div>
            </div>
        </div>
        @php
            $coa = DB::table('acc_coa')->get();
        @endphp
        <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4">
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title" id="addItemModalLabel">New Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size: 24px;">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="itemForm">
                            <div class="mb-3">
                                <label for="itemName" class="form-label">Item Name</label>
                                <input type="text" class="form-control shadow-sm" id="itemName"
                                    placeholder="Item Name">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="itemCode" class="form-label">Item Code</label>
                                    <input type="text" class="form-control shadow-sm" id="itemCode" 
                                        placeholder="Code">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="text" class="form-control shadow-sm" id="quantity" value="0"
                                        placeholder="QTY">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="text" class="form-control shadow-sm" id="amount" placeholder="0.00" value="0.00">
                            </div>
                            <div class="mb-3">
                                <label class="form-label d-block">Item Type</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="itemType" id="nonInventory"
                                        checked>
                                    <label class="form-check-label" for="nonInventory">Non Inventory Item</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="itemType" id="inventory">
                                    <label class="form-check-label" for="inventory">Inventory</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label style="font-size: 13px; color: #333; font-weight: 600;">Debit Account</label>
                                <select id="cashier_debit_account"
                                    style="width: 100%; height: 40px; font-size: 12px; padding: 8px; border-radius: 8px; border:none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                                    <option disabled style="display:none;">Select Account</option>
                                    @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                        <option value="{{ $coa->id }}">{{ $coa->code }} -
                                            {{ $coa->account_name }}</option>
                                        @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->where('deleted', 0)->get() as $subcoa)
                                            <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                {{ $subcoa->sub_code }} - {{ $subcoa->sub_account_name }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success px-4" id="addExpenseItem">Save</button>
                                <button type="submit" class="btn btn-success px-4"
                                    id="updateExpenseItem">Update</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="signatoryListModal" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="signatoryListModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="signatoryModalListLabel">Signatories</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- List of Signatories -->
                            <div class="col-md-6 overflow-auto" style="max-height: 400px;">
                                <h6>List of Signatories</h6>
                                <div id="signatoryLists">
                                    <!-- Signatories will be loaded here -->
                                </div>
                            </div>

                            <!-- Add Signatory Form -->
                            <div class="col-md-6 d-flex justify-content-center">
                                <div style="width: 70%;">
                                    <h6 class="text-center">Add Signatory</h6>
                                    <div id="signatoryContainer" style="width: 100%;">
                                        <div class="signatory-row mb-3 border rounded p-2">
                                            <input type="text"
                                                class="form-control form-control-sm mb-1 signatory-description"
                                                placeholder="Description">
                                            <input type="text" class="form-control form-control-sm mb-1 signatory-name"
                                                placeholder="Name">
                                            <hr>
                                            <input type="text"
                                                class="form-control form-control-sm mb-1 signatory-title"
                                                placeholder="Title">
                                        </div>
                                    </div>
                                    <button id="addSignatory" class="btn btn-success btn-sm mt-2" style="width: 100%;"
                                        data-id="">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="modal fade" id="discountModal" data-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="expensesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content shadow">
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title" id="expensesModalLabel">Discount Journal Entry</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size: 24px;">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row p-3 shadow-lg rounded bg-white">
                          
                            <div class="d-flex">
                                <div class="col-md-12">
                                    <label class="font-weight-bold">Academic Program</label>
                                    <select id="academic_program" class="form-control"
                                        style="width:78%; height: 40px; font-size: 12px; border-radius: 10px;">
                                        <option value="">Select Academic Program</option>
                                        @foreach ($acadprog as $item)
                                            <option value="{{ $item->id }}">{{ $item->progname }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label class="font-weight-bold">Debit Account</label>
                                    <select class="form-control" id="discount_debit_account_id" style="width: 45%;">
                                        @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                            <option value="{{ $coa->id }}" sub="0"
                                                @if ($coa->discountje_debit_isctive == 1) selected @endif>
                                                {{ $coa->code }} - {{ $coa->account_name }}
                                            </option>
                                            @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->where('deleted', 0)->get() as $subcoa)
                                                <option value="{{ $subcoa->id }}" sub="1"
                                                    @if ($subcoa->discountje_debit_isctive == 1) selected @endif>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcoa->sub_code }} -
                                                    {{ $subcoa->sub_account_name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <br>

                            <div class="col-md-12 mt-2">
                                <label class="font-weight-bold">Credit Account</label>
                                <div class="d-flex">
                                    <select class="form-control" id="discount_credit_account_id" style="width: 100%;">
                                        @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                            <option value="{{ $coa->id }}" sub="0"
                                                @if ($coa->discountje_credit_isctive == 1) selected @endif>
                                                {{ $coa->code }} - {{ $coa->account_name }}
                                            </option>
                                            @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->where('deleted', 0)->get() as $subcoa)
                                                <option value="{{ $subcoa->id }}" sub="1"
                                                    @if ($subcoa->discountje_credit_isctive == 1) selected @endif>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcoa->sub_code }} -
                                                    {{ $subcoa->sub_account_name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                    <button class="btn btn-success ml-2" id="saveDiscountJE">Save</button>
                                </div>

                            </div>

                           
                                <table class="table table-bordered table-sm mt-5"
                                    style="border-right: 1px solid #7d7d7d; align-items: left">
                                    <thead style="font-size: 13px; background-color: #b9b9b9;">
                                        <tr>
                                            <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 13% ">Academic
                                                Program</th>
                                            <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 13%">Academic
                                                Level</th>
                                           
                                            <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 20%">Debit
                                                Account</th>
                                            <th
                                                style="font-weight: 600; border: 1px solid #7d7d7d;; text-align: left; width:20%">
                                                Credit
                                                Account</th>
                                            
                                            <th
                                                style="font-weight: 600; border: 1px solid #7d7d7d;; text-align: left; width:20%">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 12.5px; font-style:" id = "discountje_peracad">


                                    </tbody>
                                </table>
                            </div>




                            <br>


                        </div>

                    </div>
                  
                </div>
            </div>
        </div> --}}


        <div class="modal fade" id="discountModal" data-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="expensesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content shadow">
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title" id="expensesModalLabel">Discount Journal Entry</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size: 24px;">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row p-3 shadow-lg rounded bg-white">

                            <div class="form-group" style="width: 100%">
                                <div class="col-md-12 mb-3">
                                    <label class="font-weight-bold">Academic Program</label>
                                    <select id="academic_program_discount" class="form-control"
                                        style="width:40%; height: 40px; font-size: 12px; border-radius: 10px;">
                                        <option value="">Select Academic Program</option>
                                        @foreach ($acadprog as $item)
                                            <option value="{{ $item->id }}">{{ $item->progname }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <label class="font-weight-bold">Debit Account</label>
                                                        <select class="form-control"
                                                            id="discount_debit_account_id_discount" style="width: 100%">
                                                            @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                                                <option value="{{ $coa->id }}" sub="0"
                                                                    @if ($coa->discountje_debit_isctive == 1) selected @endif>
                                                                    {{ $coa->code }} - {{ $coa->account_name }}
                                                                </option>
                                                                @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->where('deleted', 0)->get() as $subcoa)
                                                                    <option value="{{ $subcoa->id }}" sub="1"
                                                                        @if ($subcoa->discountje_debit_isctive == 1) selected @endif>
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcoa->sub_code }}
                                                                        -
                                                                        {{ $subcoa->sub_account_name }}
                                                                    </option>
                                                                @endforeach
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-5">
                                                        <label class="font-weight-bold">Credit Account</label>
                                                        <select class="form-control"
                                                            id="discount_credit_account_id_discount" style="width: 100%">
                                                            @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                                                <option value="{{ $coa->id }}" sub="0"
                                                                    @if ($coa->discountje_credit_isctive == 1) selected @endif>
                                                                    {{ $coa->code }} - {{ $coa->account_name }}
                                                                </option>
                                                                @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->where('deleted', 0)->get() as $subcoa)
                                                                    <option value="{{ $subcoa->id }}" sub="1"
                                                                        @if ($subcoa->discountje_credit_isctive == 1) selected @endif>
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcoa->sub_code }}
                                                                        -
                                                                        {{ $subcoa->sub_account_name }}
                                                                    </option>
                                                                @endforeach
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label class="font-weight-bold">&nbsp;</label>
                                                        <button type="button"
                                                            class="btn btn-success btn-sm w-100 form-contro"
                                                            id="saveDiscountJE">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="col-md-12 d-flex justify-content-end mt-2">
                                    <button class="btn btn-success" id="saveDiscountJE">Save</button>
                                </div> --}}
                            </div>


                            <table class="table table-bordered table-sm mt-5"
                                style="border-right: 1px solid #7d7d7d; align-items: left">
                                <thead style="font-size: 13px; background-color: #b9b9b9;">
                                    <tr>
                                        <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 13% ">Academic
                                            Program</th>
                                        <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 13%">Academic
                                            Level</th>

                                        <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 20%">Debit
                                            Account</th>
                                        <th
                                            style="font-weight: 600; border: 1px solid #7d7d7d;; text-align: left; width:20%">
                                            Credit
                                            Account</th>

                                        <th
                                            style="font-weight: 600; border: 1px solid #7d7d7d; text-align: center; width:20%">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 12.5px; font-style:" id = "discountje_peracad">


                                </tbody>
                            </table>
                        </div>




                        <br>


                    </div>

                </div>

            </div>
        </div>
        </div>


        <div id="edit_discount_je" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                    <div class="modal-header " style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                        <h5 class="modal-title">Edit Discount Journal Entry</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>

                            <!-- Contact Number & Email Address -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <input type="text" id="discount_je_id" hidden>
                                    <label>Academic Program</label>
                                    <select class="form-control" id="academic_program_edit">
                                        <option value="">Select Academic Program</option>

                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Academic Level</label>
                                    <select class="form-control" id="grade_level_edit">
                                        <option value="">Select Academic Level</option>

                                    </select>
                                </div>
                            </div>

                            <!-- Account & Credit Account -->
                            <div class="form-row p-3 shadow-lg rounded bg-white">
                                <div class="col-md-9">
                                    <label class="font-weight-bold">Debit Account</label>
                                    <select class="form-control" id="debit_account_edit">

                                    </select>
                                </div>

                                <div class="col-md-9">
                                    <label class="font-weight-bold">Credit Account</label>
                                    <select class="form-control" id="credit_account_edit">

                                    </select>
                                </div>
                            </div>

                        </form>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer" style="display: flex;justify-content: center;align-items: center;">
                        <button type="submit" class="btn-success btn-save update_discount_je"
                            style="font-size: 19.8px;">Update</button>
                    </div>
                </div>
            </div>

        </div>

        <div id="edit_debadj_je" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog"
            style="z-index: 1055;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                    <div class="modal-header" style="background-color:#d9d9d9; border-top-radius: 16px !important;">
                        <h5 class="modal-title">Edit Debit Adjustment Journal Entry</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <input type="text" id="debadj_je_id" hidden>
                                    <label>Academic Program</label>
                                    <select class="form-control" id="deb_adj_academic_program_edit">
                                        <option value="">Select Academic Program</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Academic Level</label>
                                    <select class="form-control" id="deb_adj_grade_level_edit">
                                        <option value="">Select Academic Level</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row p-3 shadow-lg rounded bg-white">
                                <div class="col-md-9">
                                    <label class="font-weight-bold">Debit Account</label>
                                    <select class="form-control" id="deb_adj_debit_account_edit">
                                    </select>
                                </div>
                                <div class="col-md-9">
                                    <label class="font-weight-bold">Credit Account</label>
                                    <select class="form-control" id="deb_adj_credit_account_edit">
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer" style="display: flex;justify-content: center;align-items: center;">
                        <button type="submit" class="btn-success btn-save update_deb_adj_je"
                            style="font-size: 19.8px;">Update</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="edit_credadj_je" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog"
            style="z-index: 1055;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                    <div class="modal-header" style="background-color:#d9d9d9; border-top-radius: 16px !important;">
                        <h5 class="modal-title">Edit Credit Adjustment Journal Entry</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <input type="text" id="credadj_je_id" hidden>
                                    <label>Academic Program</label>
                                    <select class="form-control" id="cred_adj_academic_program_edit">
                                        <option value="">Select Academic Program</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Academic Level</label>
                                    <select class="form-control" id="cred_adj_grade_level_edit">
                                        <option value="">Select Academic Level</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row p-3 shadow-lg rounded bg-white">
                                <div class="col-md-9">
                                    <label class="font-weight-bold">Debit Account</label>
                                    <select class="form-control" id="cred_adj_debit_account_edit">
                                    </select>
                                </div>
                                <div class="col-md-9">
                                    <label class="font-weight-bold">Credit Account</label>
                                    <select class="form-control" id="cred_adj_credit_account_edit">
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer" style="display: flex;justify-content: center;align-items: center;">
                        <button type="submit" class="btn-success btn-save update_cred_adj_je"
                            style="font-size: 19.8px;">Update</button>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="adjustmentModal" data-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="expensesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content shadow">
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title" id="expensesModalLabel">Adjustment Journal Entry</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size: 24px;">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row p-3 shadow-lg rounded bg-white">
                            {{-- <div class="col-md-3">
                            <label class="font-weight-bold">Discount Journal Entry</label>
                        </div> --}}
                            {{-- <div class="col-md-12">
                                <div class="text-center">
                                    <label class="text-center font-weight-bold">Debit Adjustment Journal Entry</label>
                                </div>
                                <div class="d-block">
                                    <span class="mt-1 mr-5" style="font-weight: 600;">Debit Account</span>
                                    <select class="form-control flex-grow-1 select2" id="d_adjustment_debit_account_id">
                                        @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                            <option value="{{ $coa->id }}" sub="{{ $coa->sub }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
                                            @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->where('deleted', 0)->get() as $subcoa)
                                                <option value="{{ $subcoa->id }}" sub="{{ $subcoa->sub }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    {{ $subcoa->sub_code }} - {{ $subcoa->sub_account_name }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <br>

                                <div class="d-block">
                                    <span class="mt-1 mr-5" style="font-weight: 600;">Credit Account</span>
                                    <select class="form-control flex-grow-1 select2" id="d_adjusdtment_credit_account_id">
                                        @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                            <option value="{{ $coa->id }}" sub="{{ $coa->sub }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
                                            @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->where('deleted', 0)->get() as $subcoa)
                                                <option value="{{ $subcoa->id }}" sub="{{ $subcoa->sub }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    {{ $subcoa->sub_code }} - {{ $subcoa->sub_account_name }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <br>
                            </div>

                            <div class="col-md-12">
                                <hr>
                                <div class="text-center">
                                    <label class="text-center font-weight-bold">Credit Adjustment Journal Entry</label>
                                </div>
                                <div class="d-block">
                                    <span class="mt-1 mr-5" style="font-weight: 600;">Debit Account</span>
                                    <select class="form-control flex-grow select2" id="c_adjustment_debit_account_id">
                                        @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                            <option value="{{ $coa->id }}" sub="{{ $coa->sub }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
                                            @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->where('deleted', 0)->get() as $subcoa)
                                                <option value="{{ $subcoa->id }}" sub="{{ $subcoa->sub }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    {{ $subcoa->sub_code }} - {{ $subcoa->sub_account_name }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <br>
                                <div class="d-block">
                                    <span class="mt-1 mr-5" style="font-weight: 600;">Credit Account</span>
                                    <select class="form-control flex-grow-1  select2" id="c_adjusdtment_credit_account_id">
                                        @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                            <option value="{{ $coa->id }}" sub="{{ $coa->sub }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
                                            @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->where('deleted', 0)->get() as $subcoa)
                                                <option value="{{ $subcoa->id }}" sub="{{ $subcoa->sub }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    {{ $subcoa->sub_code }} - {{ $subcoa->sub_account_name }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <br>


                                <div class="text-center">
                                    <button class="btn btn-success" id="saveAdjustmentJE">Save</button>
                                </div>
                                <br>
                            </div> --}}
                            <div class="col-md-12">
                                <div class="text-center">
                                    <label class="text-center font-weight-bold">Debit Adjustment Journal Entry</label>
                                </div>

                                <div class="d-block">
                                    <span class="mt-1 mr-5" style="font-weight: 600;">Debit Account</span>
                                    <select class="form-control flex-grow-1 select2" id="d_adjustment_debit_account_id">
                                        @foreach ($chartAccounts as $coa)
                                            <option value="{{ $coa->id }}" sub="0"
                                                {{ $coa->id == $d_debit_active ? 'selected' : '' }}>
                                                {{ $coa->code }} - {{ $coa->account_name }}
                                            </option>
                                            @foreach ($coa->subaccounts as $subcoa)
                                                <option value="{{ $subcoa->id }}" sub="1"
                                                    {{ $subcoa->id == $d_debit_active ? 'selected' : '' }}>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcoa->sub_code }} -
                                                    {{ $subcoa->sub_account_name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <br>

                                <div class="d-block">
                                    <span class="mt-1 mr-5" style="font-weight: 600;">Credit Account</span>
                                    <select class="form-control flex-grow-1 select2" id="d_adjusdtment_credit_account_id">
                                        @foreach ($chartAccounts as $coa)
                                            <option value="{{ $coa->id }}" sub="0"
                                                {{ $coa->id == $d_credit_active ? 'selected' : '' }}>
                                                {{ $coa->code }} - {{ $coa->account_name }}
                                            </option>
                                            @foreach ($coa->subaccounts as $subcoa)
                                                <option value="{{ $subcoa->id }}" sub="1"
                                                    {{ $subcoa->id == $d_credit_active ? 'selected' : '' }}>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcoa->sub_code }} -
                                                    {{ $subcoa->sub_account_name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <br>
                            </div>

                            <div class="col-md-12">
                                <hr>
                                <div class="text-center">
                                    <label class="text-center font-weight-bold">Credit Adjustment Journal Entry</label>
                                </div>

                                <div class="d-block">
                                    <span class="mt-1 mr-5" style="font-weight: 600;">Debit Account</span>
                                    <select class="form-control flex-grow select2" id="c_adjustment_debit_account_id">
                                        @foreach ($chartAccounts as $coa)
                                            <option value="{{ $coa->id }}" sub="0"
                                                {{ $coa->id == $c_debit_active ? 'selected' : '' }}>
                                                {{ $coa->code }} - {{ $coa->account_name }}
                                            </option>
                                            @foreach ($coa->subaccounts as $subcoa)
                                                <option value="{{ $subcoa->id }}" sub="1"
                                                    {{ $subcoa->id == $c_debit_active ? 'selected' : '' }}>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcoa->sub_code }} -
                                                    {{ $subcoa->sub_account_name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <br>

                                <div class="d-block">
                                    <span class="mt-1 mr-5" style="font-weight: 600;">Credit Account</span>
                                    <select class="form-control flex-grow-1 select2" id="c_adjusdtment_credit_account_id">
                                        @foreach ($chartAccounts as $coa)
                                            <option value="{{ $coa->id }}" sub="0"
                                                {{ $coa->id == $c_credit_active ? 'selected' : '' }}>
                                                {{ $coa->code }} - {{ $coa->account_name }}
                                            </option>
                                            @foreach ($coa->subaccounts as $subcoa)
                                                <option value="{{ $subcoa->id }}" sub="1"
                                                    {{ $subcoa->id == $c_credit_active ? 'selected' : '' }}>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcoa->sub_code }} -
                                                    {{ $subcoa->sub_account_name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <br>

                                <div class="text-center">
                                    <button class="btn btn-success" id="saveAdjustmentJE">Save</button>
                                </div>
                                <br>
                            </div>
                        </div>
                        {{-- <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="adjustmentModal2" data-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="expensesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content shadow">
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title" id="expensesModalLabel">Adjustment Journal Entry</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size: 24px;">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Tabs Nav -->
                        <ul class="nav nav-tabs" id="adjustmentTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="debit-tab" data-toggle="tab" href="#debit"
                                    role="tab" aria-controls="debit" aria-selected="true">Debit Adjustment</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="credit-tab" data-toggle="tab" href="#credit" role="tab"
                                    aria-controls="credit" aria-selected="false">Credit Adjustment</a>
                            </li>
                        </ul>

                        <!-- Tabs Content -->
                        <div class="tab-content p-3 border border-top-0 shadow-lg bg-white rounded-bottom"
                            id="adjustmentTabsContent">
                            <div class="tab-pane fade show active" id="debit" role="tabpanel"
                                aria-labelledby="debit-tab">
                                <div class="form-row">
                                    <div class="col-md-12 text-right">
                                        {{-- Adding Section --}}
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <div class="form-group" style="width: 100%">
                                            <div class="col-md-12 mb-3">
                                                <label class="font-weight-bold">Academic Program</label>
                                                <select id="academic_program_deb_adj" class="form-control"
                                                    style="width:40%; height: 40px; font-size: 12px; border-radius: 10px;">
                                                    <option value="">Select Academic Program</option>
                                                    @foreach ($acadprog as $item)
                                                        <option value="{{ $item->id }}">{{ $item->progname }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <div class="row">
                                                        <div class="col-md-12 mb-3">
                                                            <div class="row">
                                                                <div class="col-md-5">
                                                                    <label class="font-weight-bold">Debit Account</label>
                                                                    <select class="form-control"
                                                                        id="deb_adj_debit_account_id" style="width: 100%">
                                                                        @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                                                            <option value="{{ $coa->id }}"
                                                                                sub="0"
                                                                                @if ($coa->discountje_debit_isctive == 1) selected @endif>
                                                                                {{ $coa->code }} -
                                                                                {{ $coa->account_name }}
                                                                            </option>
                                                                            @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->where('deleted', 0)->get() as $subcoa)
                                                                                <option value="{{ $subcoa->id }}"
                                                                                    sub="1"
                                                                                    @if ($subcoa->discountje_debit_isctive == 1) selected @endif>
                                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcoa->sub_code }}
                                                                                    -
                                                                                    {{ $subcoa->sub_account_name }}
                                                                                </option>
                                                                            @endforeach
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-5">
                                                                    <label class="font-weight-bold">Credit Account</label>
                                                                    <select class="form-control"
                                                                        id="deb_adj_credit_account_id"
                                                                        style="width: 100%">
                                                                        @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                                                            <option value="{{ $coa->id }}"
                                                                                sub="0"
                                                                                @if ($coa->discountje_credit_isctive == 1) selected @endif>
                                                                                {{ $coa->code }} -
                                                                                {{ $coa->account_name }}
                                                                            </option>
                                                                            @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->where('deleted', 0)->get() as $subcoa)
                                                                                <option value="{{ $subcoa->id }}"
                                                                                    sub="1"
                                                                                    @if ($subcoa->discountje_credit_isctive == 1) selected @endif>
                                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcoa->sub_code }}
                                                                                    -
                                                                                    {{ $subcoa->sub_account_name }}
                                                                                </option>
                                                                            @endforeach
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-2">
                                                                    <label class="font-weight-bold">&nbsp;</label>
                                                                    <button type="button"
                                                                        class="btn btn-success btn-sm w-100 form-contro"
                                                                        id="saveDebAdjJE">Save</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <table class="table table-bordered table-sm" id="debit_je_table"
                                            style="border-right: 1px solid #7d7d7d; align-items: left">
                                            <thead style="font-size: 13px; background-color: #b9b9b9;">
                                                <tr>
                                                    <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 13% ">
                                                        Academic Program</th>
                                                    <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 13%">
                                                        Academic Level</th>
                                                    <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 20%">
                                                        Debit Account</th>
                                                    <th
                                                        style="font-weight: 600; border: 1px solid #7d7d7d;; text-align: left; width:20%">
                                                        Credit
                                                        Account</th>
                                                    <th
                                                        style="font-weight: 600; border: 1px solid #7d7d7d;; text-align: center; width:20%">
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size: 12.5px; font-style:" id = "debitadj_je_table">


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="credit" role="tabpanel" aria-labelledby="credit-tab">
                                <div class="form-row">
                                    <div class="col-md-12 text-right">
                                        {{-- Adding Section --}}
                                    </div>
                                    <div class="col-md-12 mt-3">

                                        <div class="form-group" style="width: 100%">
                                            <div class="col-md-12 mb-3">
                                                <label class="font-weight-bold">Academic Program</label>
                                                <select id="academic_program_cred_adj" class="form-control"
                                                    style="width:40%; height: 40px; font-size: 12px; border-radius: 10px;">
                                                    <option value="">Select Academic Program</option>
                                                    @foreach ($acadprog as $item)
                                                        <option value="{{ $item->id }}">{{ $item->progname }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <div class="row">
                                                        <div class="col-md-12 mb-3">
                                                            <div class="row">
                                                                <div class="col-md-5">
                                                                    <label class="font-weight-bold">Debit Account</label>
                                                                    <select class="form-control"
                                                                        id="cred_adj_debit_account_id"
                                                                        style="width: 100%">
                                                                        @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                                                            <option value="{{ $coa->id }}"
                                                                                sub="0"
                                                                                @if ($coa->discountje_debit_isctive == 1) selected @endif>
                                                                                {{ $coa->code }} -
                                                                                {{ $coa->account_name }}
                                                                            </option>
                                                                            @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->where('deleted', 0)->get() as $subcoa)
                                                                                <option value="{{ $subcoa->id }}"
                                                                                    sub="1"
                                                                                    @if ($subcoa->discountje_debit_isctive == 1) selected @endif>
                                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcoa->sub_code }}
                                                                                    -
                                                                                    {{ $subcoa->sub_account_name }}
                                                                                </option>
                                                                            @endforeach
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-5">
                                                                    <label class="font-weight-bold">Credit Account</label>
                                                                    <select class="form-control"
                                                                        id="cred_adj_credit_account_id"
                                                                        style="width: 100%">
                                                                        @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                                                            <option value="{{ $coa->id }}"
                                                                                sub="0"
                                                                                @if ($coa->discountje_credit_isctive == 1) selected @endif>
                                                                                {{ $coa->code }} -
                                                                                {{ $coa->account_name }}
                                                                            </option>
                                                                            @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->where('deleted', 0)->get() as $subcoa)
                                                                                <option value="{{ $subcoa->id }}"
                                                                                    sub="1"
                                                                                    @if ($subcoa->discountje_credit_isctive == 1) selected @endif>
                                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcoa->sub_code }}
                                                                                    -
                                                                                    {{ $subcoa->sub_account_name }}
                                                                                </option>
                                                                            @endforeach
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-2">
                                                                    <label class="font-weight-bold">&nbsp;</label>
                                                                    <button type="button"
                                                                        class="btn btn-success btn-sm w-100 form-contro"
                                                                        id="saveCredAdjJE">Save</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <table class="table table-bordered table-sm" id="credit_je_table"
                                            style="border-right: 1px solid #7d7d7d; align-items: left">
                                            <thead style="font-size: 13px; background-color: #b9b9b9;">
                                                <tr>
                                                    <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 13% ">
                                                        Academic Program</th>
                                                    <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 13%">
                                                        Academic Level</th>
                                                    <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 20%">
                                                        Debit Account</th>
                                                    <th
                                                        style="font-weight: 600; border: 1px solid #7d7d7d;; text-align: left; width:20%">
                                                        Credit
                                                        Account</th>
                                                    <th
                                                        style="font-weight: 600; border: 1px solid #7d7d7d;; text-align: left; width:20%">
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size: 12.5px; font-style:" id = "creditadj_je_table">


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Tabs Content -->
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade show" id="modal-costcenter_detail" aria-modal="true" style="display: none;">
            <div class="modal-dialog modal-md" style="margin-top: 3em">
                <div class="modal-content">
                    <div class="modal-header bg-secondary">
                        <h5 class="modal-title">Add Cost Center</h5> <input type="hidden" id="costcenter_id">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <label for="">Description</label>
                                <input id="costcenter_description" class="form-control text-sm form-control-sm"
                                    type="text">
                                <span class="text-danger costcenter_description_null" hidden><small>Description is required!</small></span>
                            </div>
                            <div class="col-md-4">
                                <label for="">Code</label>
                                <input id="costcenter_acc_code" class="form-control text-sm form-control-sm"
                                    type="text">
                                <span class="text-danger costcenter_acc_code_null" hidden><small>Code is required!</small></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer float-right">
                        <div class="">
                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">
                                Close
                            </button>
                        </div>
                        <div>
                            <button id="costcenter_save" class="btn btn-primary btn-sm">
                                Save
                            </button>
                            <button id="costcenter_update" class="btn btn-info btn-sm">
                                Update
                            </button>
                        </div>
                    </div>
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
            
            $('#addItemBtn').on('click', function() {
                $('#addItemModal').modal('show');
            })

            $('#cashier_debit_account').select2({
                placeholder: "Select Debit Account",
                allowClear: true,
                theme: 'bootstrap4'
            });

            $('#view_expenses').on('click', function() {
                $('#expensesModal').modal('show');
            })

            $('#cashier_je').on('click', function() {
                $('#cashierJEModal').modal('show');
            })

            $('#debit_account_id').select2({
                placeholder: "Select Debit Account",
                allowClear: true,
                theme: 'bootstrap4',
                width: '100%'
            });

            $('#discountModalbtn').on('click', function() {
                $('#discountModal').modal('show');
            })

            $('#discount_debit_account_id_discount').select2({
                placeholder: "Select Debit Account",
                allowClear: true,
                theme: 'bootstrap4',
                width: '80%'
            });

            $('#discount_credit_account_id_discount').select2({
                placeholder: "Select Credit Account",
                allowClear: true,
                theme: 'bootstrap4',
                width: '80%'
            });
            $('#debit_account_edit').select2({
                placeholder: "Select Debit Account",
                allowClear: true,
                theme: 'bootstrap4',
                width: '80%'
            });
            $('#credit_account_edit').select2({
                placeholder: "Select Credit Account",
                allowClear: true,
                theme: 'bootstrap4',
                width: '80%'
            });

            $('#deb_adj_debit_account_id').select2({
                placeholder: "Select Debit Account",
                allowClear: true,
                theme: 'bootstrap4',
                width: '80%'
            });

            $('#deb_adj_credit_account_id').select2({
                placeholder: "Select Credit Account",
                allowClear: true,
                theme: 'bootstrap4',
                width: '80%'
            });

            $('#deb_adj_debit_account_edit').select2({
                placeholder: "Select Debit Account",
                allowClear: true,
                theme: 'bootstrap4',
                width: '80%'
            }).on('select2:open', function(e) {
                // Force high z-index for dropdown
                $('.select2-container').css('z-index', 99999);
                $('.select2-dropdown').css('z-index', 99999);
            });

            $('#deb_adj_credit_account_edit').select2({
                placeholder: "Select Credit Account",
                allowClear: true,
                theme: 'bootstrap4',
                width: '80%'
            }).on('select2:open', function(e) {
                // Force high z-index for dropdown
                $('.select2-container').css('z-index', 99999);
                $('.select2-dropdown').css('z-index', 99999);
            });

            $('#cred_adj_debit_account_id').select2({
                placeholder: "Select Debit Account",
                allowClear: true,
                theme: 'bootstrap4',
                width: '80%'
            });

            $('#cred_adj_credit_account_id').select2({
                placeholder: "Select Credit Account",
                allowClear: true,
                theme: 'bootstrap4',
                width: '80%'
            });

            $('#cred_adj_debit_account_edit').select2({
                placeholder: "Select Debit Account",
                allowClear: true,
                theme: 'bootstrap4',
                width: '80%'
            }).on('select2:open', function(e) {
                // Force high z-index for dropdown
                $('.select2-container').css('z-index', 99999);
                $('.select2-dropdown').css('z-index', 99999);
            });
            $('#cred_adj_credit_account_edit').select2({
                placeholder: "Select Credit Account",
                allowClear: true,
                theme: 'bootstrap4',
                width: '80%'
            }).on('select2:open', function(e) {
                // Force high z-index for dropdown
                $('.select2-container').css('z-index', 99999);
                $('.select2-dropdown').css('z-index', 99999);
            });

            $('#discountModalbtn').on('click', function() {
                $('#discountModal').modal('show');
            })

            // $('#discount_debit_account_id_discount').select2({
            //     placeholder: "Select Debit Account",
            //     allowClear: true,
            //     theme: 'bootstrap4',
            //     width: '70%'
            // });

            $('#adjustmentModalbtn').on('click', function() {
                $('#adjustmentModal').modal('show');
            })

            $('#adjustmentModalbtn2').on('click', function() {
                $('#adjustmentModal2').modal('show');
            })

            $('#adjustment_debit_account_id').select2({
                placeholder: "Select Debit Account",
                allowClear: true,
                theme: 'bootstrap4',
                width: '100%'
            });

            $('#adjusdtment_credit_account_id').select2({
                placeholder: "Select Credit Account",
                allowClear: true,
                theme: 'bootstrap4',
                width: '100%'
            });


            var debit_account = $('#debit_account_id').val();

            $('#d_adjustment_debit_account_id').select2()
            $('#d_adjusdtment_credit_account_id').select2()
            $('#c_adjustment_debit_account_id').select2()
            $('#c_adjusdtment_credit_account_id').select2()

            $('#d_adjustment_debit_account_id2').select2()
            $('#d_adjusdtment_credit_account_id2').select2()
            $('#c_adjustment_debit_account_id2').select2()
            $('#c_adjusdtment_credit_account_id2').select2()

            $('#saveCashierJE').on('click', function(event) {
                event.preventDefault();
                // Get the current value when button is clicked
                debit_account = $('#debit_account_id').val();
                setactive_cashierJE(debit_account);
            });

            $('#debit_account_id').on('change', function() {
                // Just update the variable when dropdown changes
                debit_account = $(this).val();
            });

            function setactive_cashierJE(debit_account) {
                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/other_setup_cashierJE/setactive',
                    data: {
                        debit_account: debit_account,
                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully updated'
                            })
                            $("#supplier_name").val("");
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }

            fetch_active_cashierJE()

            function fetch_active_cashierJE() {
                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/other_setup_cashierJE/fetch_active',

                    success: function(response) {
                        console.log(response);

                        var active_cashierJE = response.active_cashierJE;
                        var all_cashierJE = response.all_cashierJE;
                        var all_subjeacc = response.all_subjeacc;

                        $("#debit_account_id").empty().trigger('change');
                        $("#debit_account_id").append(
                            '<option value="" selected disabled>Select Cashier JE</option>'
                        );

                        if (active_cashierJE == null) {
                            all_cashierJE.forEach(items => {
                                $("#debit_account_id").append(
                                    `<option value="${items.id}" sub="0">${items.code} - ${items.account_name}</option>`
                                );
                                all_subjeacc.forEach(subitems => {
                                    if (subitems.coaid == items.id) {
                                        $("#debit_account_id").append(
                                            `<option style="font-style:italic;" value="${subitems.id}" sub="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${subitems.sub_code} - ${subitems.sub_account_name}</option>`
                                        );
                                    }
                                });
                            });
                        } else {
                            all_cashierJE.forEach(items => {
                                if (items.id == active_cashierJE.id) {
                                    $("#debit_account_id").append(
                                        `<option value="${items.id}" sub="0" selected>${items.code} - ${items.account_name}</option>`
                                    );
                                } else {
                                    $("#debit_account_id").append(
                                        `<option value="${items.id}" sub="0">${items.code} - ${items.account_name}</option>`
                                    );
                                }

                                all_subjeacc.forEach(subitems => {
                                    if (subitems.coaid == items.id) {
                                        if (subitems.id == active_cashierJE.id) {
                                            $("#debit_account_id").append(
                                                `<option style="font-style:italic;" value="${subitems.id}" sub="1" selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${subitems.sub_code} - ${subitems.sub_account_name}</option>`
                                            );
                                        } else {
                                            $("#debit_account_id").append(
                                                `<option style="font-style:italic;" value="${subitems.id}" sub="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${subitems.sub_code} - ${subitems.sub_account_name}</option>`
                                            );
                                        }
                                    }
                                });
                            });
                        };

                    }

                });
            }

            ///////////////////////////////////////////////////////////////////////////

            var debit_account = $('#discount_debit_account_id').val();
            var credit_account = $('#discount_credit_account_id').val();

            $('#discount_debit_account_id').on('change', function() {
                // Just update the variable when dropdown changes
                discount_debit_account_id = $(this).val();
            });

            // $('#saveDiscountJE').on('click', function(event) {
            //     event.preventDefault();
            //     // Get the current value when button is clicked
            //     discount_debit_account_id = $('#discount_debit_account_id').val();
            //     discount_credit_account_id = $('#discount_credit_account_id').val();
            //     setactive_discountJE(discount_debit_account_id, discount_credit_account_id);
            // });

            // function setactive_discountJE(discount_debit_account_id, discount_credit_account_id) {
            //     $.ajax({
            //         type: 'GET',
            //         url: '/bookkeeper/other_setup_discountJE/setactive',
            //         data: {
            //             discount_debit_account_id: discount_debit_account_id,
            //             discount_credit_account_id: discount_credit_account_id,
            //         },
            //         success: function(data) {
            //             if (data[0].status == 1) {
            //                 Toast.fire({
            //                     type: 'success',
            //                     title: 'Successfully updated'
            //                 })
            //                 $("#supplier_name").val("");
            //             } else {
            //                 Toast.fire({
            //                     type: 'error',
            //                     title: data[0].message
            //                 })
            //             }
            //         }
            //     });
            // }

            $('#saveDiscountJE').on('click', function(event) {
                event.preventDefault();

                const getAccountData = (selector) => {
                    const selectedOption = $(selector).find('option:selected');
                    return {
                        id: selectedOption.val(),
                        sub: selectedOption.attr('sub') // "0" or "1"
                    };
                };

                const academic_program = getAccountData('#academic_program_discount');
                const discount_debit = getAccountData('#discount_debit_account_id_discount');
                const discount_credit = getAccountData('#discount_credit_account_id_discount');

                console.log(discount_debit, 'Discount Debit Account');
                console.log(discount_credit, 'Discount Credit Account');

                setactive_DiscountJE(discount_debit, discount_credit, academic_program);
            });

            function setactive_DiscountJE(discount_debit, discount_credit, academic_program) {
                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/other_setup_discountJE/setactive_discount_discount',
                    data: {
                        academic_program: academic_program.id,
                        discount_debit_account_id: discount_debit.id,
                        discount_debit_is_sub: discount_debit.sub,
                        discount_credit_account_id: discount_credit.id,
                        discount_credit_is_sub: discount_credit.sub
                    },
                    success: function(data) {
                        if (data.status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully updated'
                            })
                            $("#supplier_name").val("");
                            fetchEnrollmentSetup();
                        } else if (data.status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data.message
                            })
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: 'An error occurred while updating'
                            })
                        }
                    }
                });
            }

            ///////////////////////////////////////////////////////

            $('#saveDebAdjJE').on('click', function(event) {
                event.preventDefault();

                const getAccountData_debadj = (selector) => {
                    const selectedOption = $(selector).find('option:selected');
                    return {
                        id: selectedOption.val(),
                        sub: selectedOption.attr('sub') // "0" or "1"
                    };
                };

                const academic_program_deb_adj = getAccountData_debadj('#academic_program_deb_adj');
                const discount_debit_deb_adj = getAccountData_debadj('#deb_adj_debit_account_id');
                const discount_credit_deb_adj = getAccountData_debadj('#deb_adj_credit_account_id');

                console.log(discount_debit_deb_adj, 'Discount Debit Account');
                console.log(discount_credit_deb_adj, 'Discount Credit Account');

                setactive_DebAdjJE(discount_debit_deb_adj, discount_credit_deb_adj,
                    academic_program_deb_adj);
            });

            function setactive_DebAdjJE(discount_debit_deb_adj, discount_credit_deb_adj, academic_program_deb_adj) {
                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/other_setup_debadjJE/setactive_debadj',
                    data: {
                        academic_program: academic_program_deb_adj.id,
                        discount_debit_account_id: discount_debit_deb_adj.id,
                        discount_debit_is_sub: discount_debit_deb_adj.sub,
                        discount_credit_account_id: discount_credit_deb_adj.id,
                        discount_credit_is_sub: discount_credit_deb_adj.sub
                    },
                    success: function(data) {
                        if (data.status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully updated'
                            })
                            $("#supplier_name").val("");
                            fetchDebAdjSetup();
                        } else if (data.status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data.message
                            })
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: 'An error occurred while updating'
                            })
                        }
                    }
                });
            }

            ///////////////////////////////////////////////////////

            $('#saveCredAdjJE').on('click', function(event) {
                event.preventDefault();

                const getAccountData_credadj = (selector) => {
                    const selectedOption = $(selector).find('option:selected');
                    return {
                        id: selectedOption.val(),
                        sub: selectedOption.attr('sub') // "0" or "1"
                    };
                };

                const academic_program_cred_adj = getAccountData_credadj('#academic_program_cred_adj');
                const cred_adj_debit_account_id = getAccountData_credadj('#cred_adj_debit_account_id');
                const cred_adj_credit_account_id = getAccountData_credadj('#cred_adj_credit_account_id');

                console.log(cred_adj_debit_account_id, 'Discount Debit Account');
                console.log(cred_adj_credit_account_id, 'Discount Credit Account');

                setactive_CredAdjJE(cred_adj_debit_account_id, cred_adj_credit_account_id,
                    academic_program_cred_adj);
            });

            function setactive_CredAdjJE(cred_adj_debit_account_id, cred_adj_credit_account_id,
                academic_program_cred_adj) {
                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/other_setup_credadjJE/setactive_credadj',
                    data: {
                        academic_program: academic_program_cred_adj.id,
                        cred_adj_debit_account_id: cred_adj_debit_account_id.id,
                        cred_adj_debit_account_id_is_sub: cred_adj_debit_account_id.sub,
                        cred_adj_credit_account_id: cred_adj_credit_account_id.id,
                        cred_adj_credit_account_id_is_sub: cred_adj_credit_account_id.sub
                    },
                    success: function(data) {
                        if (data.status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully updated'
                            })
                            $("#supplier_name").val("");
                            fetchCredAdjSetup();
                        } else if (data.status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data.message
                            })
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: 'An error occurred while updating'
                            })
                        }
                    }
                });
            }




            //////////////////////////////////////////////////////

            fetch_active_discountJE()

            function fetch_active_discountJE() {
                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/other_setup_discountJE/fetch_active',

                    success: function(response) {
                        console.log(response);

                        var active_cashierJE = response.active_cashierJE;
                        var all_cashierJE = response.all_cashierJE;
                        var all_subjeacc = response.all_subjeacc;

                        $("#discount_debit_account_id").empty().trigger('change');
                        $("#discount_debit_account_id").append(
                            '<option value="" selected disabled>Select Discount JE</option>'
                        );

                        if (active_cashierJE == null) {
                            all_cashierJE.forEach(items => {
                                $("#discount_debit_account_id").append(
                                    `<option value="${items.id}" sub="0">${items.code} - ${items.account_name}</option>`
                                );
                                all_subjeacc.forEach(subitems => {
                                    if (subitems.coaid == items.id) {
                                        $("#discount_debit_account_id").append(
                                            `<option style="font-style:italic;" value="${subitems.id}" sub="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${subitems.sub_code} - ${subitems.sub_account_name}</option>`
                                        );
                                    }
                                });
                            });
                        } else {
                            all_cashierJE.forEach(items => {
                                if (items.id == active_cashierJE.id) {
                                    $("#discount_debit_account_id").append(
                                        `<option value="${items.id}" sub="0" selected>${items.code} - ${items.account_name}</option>`
                                    );
                                } else {
                                    $("#discount_debit_account_id").append(
                                        `<option value="${items.id}" sub="0">${items.code} - ${items.account_name}</option>`
                                    );
                                }

                                all_subjeacc.forEach(subitems => {
                                    if (subitems.coaid == items.id) {
                                        if (subitems.id == active_cashierJE.id) {
                                            $("#discount_debit_account_id").append(
                                                `<option style="font-style:italic;" sub="1" value="${subitems.id}" selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${subitems.sub_code} - ${subitems.sub_account_name}</option>`
                                            );
                                        } else {
                                            $("#discount_debit_account_id").append(
                                                `<option style="font-style:italic;" sub="1" value="${subitems.id}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${subitems.sub_code} - ${subitems.sub_account_name}</option>`
                                            );
                                        }
                                    }
                                });
                            });
                        }

                    }

                });
            }

            ////////////////////////////////////////////////////////////////////////////

            var d_adjustment_debit_account_id = $('#d_adjustment_debit_account_id').val();
            var d_adjusdtment_credit_account_id = $('#d_adjusdtment_credit_account_id').val();
            var c_adjustment_debit_account_id = $('#c_adjustment_debit_account_id').val();
            var c_adjusdtment_credit_account_id = $('#c_adjusdtment_credit_account_id').val();

            // $('#saveAdjustmentJE').on('click', function(event) {
            //     event.preventDefault();
            //     // Get the current value when button is clicked
            //     d_adjustment_debit_account_id = $('#d_adjustment_debit_account_id').val();
            //     d_adjusdtment_credit_account_id = $('#d_adjusdtment_credit_account_id').val();
            //     c_adjustment_debit_account_id = $('#c_adjustment_debit_account_id').val();
            //     c_adjusdtment_credit_account_id = $('#c_adjusdtment_credit_account_id').val();

            //     console.log(d_adjustment_debit_account_id, 'd_adjustment_debit_account_id');
            //     console.log(d_adjusdtment_credit_account_id, 'd_adjusdtment_credit_account_id');
            //     console.log(c_adjustment_debit_account_id, 'c_adjustment_debit_account_id');
            //     console.log(c_adjusdtment_credit_account_id, 'c_adjusdtment_credit_account_id');

            //     setactive_AccountJEdebit(d_adjustment_debit_account_id, d_adjusdtment_credit_account_id, c_adjustment_debit_account_id, c_adjusdtment_credit_account_id);
            // });

            $('#saveAdjustmentJE').on('click', function(event) {
                event.preventDefault();

                const getAccountData = (selector) => {
                    const selectedOption = $(selector).find('option:selected');
                    return {
                        id: selectedOption.val(),
                        sub: selectedOption.attr('sub') // "0" or "1"
                    };
                };

                const d_debit = getAccountData('#d_adjustment_debit_account_id');
                const d_credit = getAccountData('#d_adjusdtment_credit_account_id');
                const c_debit = getAccountData('#c_adjustment_debit_account_id');
                const c_credit = getAccountData('#c_adjusdtment_credit_account_id');

                console.log(d_debit, 'Debit Adjustment Debit');
                console.log(d_credit, 'Debit Adjustment Credit');
                console.log(c_debit, 'Credit Adjustment Debit');
                console.log(c_credit, 'Credit Adjustment Credit');

                // Now you can use the sub value to determine the source table in your backend
                setactive_AccountJEdebit(d_debit, d_credit, c_debit, c_credit);
            });

            $('#adjustment_debit_account_id').on('change', function() {
                // Just update the variable when dropdown changes
                adjustment_debit_account_id = $(this).val();
            });

            $('#adjusdtment_credit_account_id').on('change', function() {
                // Just update the variable when dropdown changes
                adjusdtment_credit_account_id = $(this).val();
            });

            function setactive_AccountJEdebit(d_adjustment_debit_account_id, d_adjusdtment_credit_account_id,
                c_adjustment_debit_account_id, c_adjusdtment_credit_account_id) {
                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/other_setup_AccountJEdebit/setactive',
                    data: {
                        d_adjustment_debit_account_id: d_adjustment_debit_account_id,
                        d_adjusdtment_credit_account_id: d_adjusdtment_credit_account_id,
                        c_adjustment_debit_account_id: c_adjustment_debit_account_id,
                        c_adjusdtment_credit_account_id: c_adjusdtment_credit_account_id,
                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully updated'
                            })
                            $("#supplier_name").val("");
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }

            ////////////////////////////////// debit adjustment//////////////////////////////////

            fetch_active_adjustmentJE()

            function fetch_active_adjustmentJE() {
                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/other_setup_AccountJE/fetch_active_adjustment',

                    success: function(response) {
                        console.log(response);

                        var setactive_jeacc_adj_debit = response.setactive_jeacc_adj_debit;
                        var setactive_jeacc_adj_credit = response.setactive_jeacc_adj_credit;
                        var all_cashierJE = response.all_cashierJE;
                        var all_subjeacc = response.all_subjeacc;

                        $("#adjustment_debit_account_id").empty().trigger('change');
                        $("#adjustment_debit_account_id").append(
                            '<option value="" selected disabled>Select Adjustment Debit JE</option>'
                        );

                        all_cashierJE.forEach(items => {
                            if (items.id == setactive_jeacc_adj_debit.id) {
                                $("#adjustment_debit_account_id").append(
                                    `<option value="${items.id}" sub="0" selected>${items.code} - ${items.account_name}</option>`
                                );
                            } else {
                                $("#adjustment_debit_account_id").append(
                                    `<option value="${items.id}" sub="0">${items.code} - ${items.account_name}</option>`
                                );
                            }

                            all_subjeacc.forEach(subitems => {
                                if (subitems.coaid == items.id) {
                                    if (subitems.id == setactive_jeacc_adj_debit.id) {
                                        $("#adjustment_debit_account_id").append(
                                            `<option style="font-style:italic;" sub="1" value="${subitems.id}" selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${subitems.sub_code} - ${subitems.sub_account_name}</option>`
                                        );
                                    } else {
                                        $("#adjustment_debit_account_id").append(
                                            `<option style="font-style:italic;" sub="1" value="${subitems.id}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${subitems.sub_code} - ${subitems.sub_account_name}</option>`
                                        );
                                    }
                                }
                            });
                        });



                        ///////////////////////////////////////////////////////////

                        $("#adjusdtment_credit_account_id").empty().trigger('change');
                        $("#adjusdtment_credit_account_id").append(
                            '<option value="" selected disabled>Select Adjustment Debit JE</option>'
                        );

                        all_cashierJE.forEach(items => {
                            if (items.id == setactive_jeacc_adj_credit.id) {
                                $("#adjusdtment_credit_account_id").append(
                                    `<option value="${items.id}" sub="0" selected>${items.code} - ${items.account_name}</option>`
                                );
                            } else {
                                $("#adjusdtment_credit_account_id").append(
                                    `<option value="${items.id}" sub="0" >${items.code} - ${items.account_name}</option>`
                                );
                            }

                            all_subjeacc.forEach(subitems => {
                                if (subitems.coaid == items.id) {
                                    if (subitems.id == setactive_jeacc_adj_credit.id) {
                                        $("#adjusdtment_credit_account_id").append(
                                            `<option style="font-style:italic;" value="${subitems.id}" sub="1" selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${subitems.sub_code} - ${subitems.sub_account_name}</option>`
                                        );
                                    } else {
                                        $("#adjusdtment_credit_account_id").append(
                                            `<option style="font-style:italic;" value="${subitems.id}" sub="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${subitems.sub_code} - ${subitems.sub_account_name}</option>`
                                        );
                                    }
                                }
                            });
                        });

                    }

                });
            }



            ////////////////////////////////expenses items////////////////////////

            loadExpenseItems();

            $('#addItemModal').on('hidden.bs.modal', function() {
                $('#expensesModal').modal('show');
            });


            $('#searchBar').on('keyup', function() {
                var value = $(this).val().toLowerCase();

                $('#expenseTableBody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            $(document).on('click', '#addExpenseItem', function(e) {
                e.preventDefault();

                var itemName = $('#itemName').val().trim();
                var itemCode = $('#itemCode').val().trim();
                var quantity = $('#quantity').val().trim();
                var amount = $('#amount').val().trim();
                var itemType = $('input[name="itemType"]:checked').attr('id');
                var debitAccount = $('#cashier_debit_account').val();

                if (!itemName || !itemCode || !quantity || !amount) {
                    alert("Please fill out all fields.");
                    return;
                }

                $.ajax({
                    url: '/bookkeeper/add-expense-item',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        itemName: itemName,
                        itemCode: itemCode,
                        quantity: quantity,
                        amount: amount,
                        itemType: itemType,
                        debitAccount: debitAccount
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Expense item added successfully.',
                            type: 'success'
                        }).then(() => {
                            $('#addItemModal').modal('hide');
                            loadExpenseItems();
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire('Error', 'Failed to add expense item.', 'error');
                    }
                });
            });


            $(document).on('click', '.delete-item', function() {
                var itemId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This item will be marked as deleted.',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#aaa',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/bookkeeper/delete-expense-item',
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                id: itemId
                            },
                            success: function(response) {
                                Swal.fire('Deleted!', response.message, 'success');
                                loadExpenseItems();
                            },
                            error: function(xhr) {
                                console.error(xhr.responseText);
                                Swal.fire('Error', 'Failed to delete the item.',
                                    'error');
                            }
                        });
                    }
                });
            });
            $(document).on('click', '.edit-item', function() {
                var itemId = $(this).data('id');

                $.ajax({
                    url: '/bookkeeper/edit-expense-item',
                    type: 'GET',
                    data: {
                        id: itemId
                    },
                    success: function(item) {
                        $('#itemName').val(item.description);
                        $('#itemCode').val(item.itemcode);
                        $('#quantity').val(item.qty);
                        $('#amount').val(item.amount);

                        // Fixed item type selection
                        // Reset all radio buttons first
                        $('input[name="itemType"]').prop('checked', false);

                        // Set the correct radio button
                        if (item.itemtype.toLowerCase().includes('non-inventory')) {
                            $('#nonInventory').prop('checked', true);
                        } else if (item.itemtype.toLowerCase().includes('inventory')) {
                            $('#inventory').prop('checked', true);
                        }

                        var accountOption = $('<option>', {
                            value: item.coaid,
                            text: item.account_code + ' - ' + item.account_name
                        });

                        if ($('#cashier_debit_account option[value="' + accountOption.val() +
                                '"]').length === 0) {
                            $('#cashier_debit_account').append(accountOption);
                        }

                        setTimeout(() => {
                            $('#cashier_debit_account').val(accountOption.val())
                                .trigger('change');
                        }, 500);

                        $('#addItemModalLabel').text('Edit Expense Item');

                        $('#updateExpenseItem').data('id', item.id);

                        $('#addExpenseItem').hide();
                        $('#updateExpenseItem').show();

                        $('#addItemModal').modal('show');
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire('Error', 'Unable to fetch item details.', 'error');
                    }
                });
            });


            $(document).on('click', '#updateExpenseItem', function(e) {
                e.preventDefault();

                var itemId = $(this).data('id');

                $.ajax({
                    url: '/bookkeeper/update-expense-item',
                    type: 'PUT',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id: itemId,
                        itemCode: $('#itemCode').val(),
                        itemName: $('#itemName').val(),
                        quantity: $('#quantity').val(),
                        amount: $('#amount').val(),
                        itemType: $('input[name="itemType"]:checked').attr('id') === 'inventory' ?
                            'inventory' : 'non-inventory',
                        debitAccount: $('#cashier_debit_account').val()
                    },
                    success: function(response) {
                        Swal.fire('Success', response.message, 'success');
                        $('#addItemModal').modal('hide');
                        $('#itemForm')[0].reset();
                        $('#addExpenseItem').show();
                        $('#updateExpenseItem').hide().removeData('id');
                        loadExpenseItems();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire('Error', 'Failed to update item.', 'error');
                    }
                });
            });

            function loadExpenseItems() {
                $.ajax({
                    url: '/bookkeeper/expense-items',
                    type: 'GET',
                    success: function(items) {
                        var tbody = $('#expenseTableBody');
                        tbody.empty();

                        if (items.length === 0) {
                            tbody.append(`
                    <tr>
                        <td colspan="6" class="text-center text-muted">No expense items found.</td>
                    </tr>
                `);
                            return;
                        }

                        items.forEach((item, index) => {
                            tbody.append(`
                    <tr>
                        <td>${item.itemcode || ''}</td>
                        <td>${item.description}</td>
                        <td>${item.qty || '0'}</td>
                        <td>${item.amount || '0.00'}</td>
                        <td>${item.itemtype}</td>
                        <td style="text-align: center;">
                            <i class="fas fa-edit edit-item text-primary" data-id="${item.id}" style="cursor: pointer; margin-right: 5px;"></i>
                            <i class="fas fa-trash-alt delete-item text-danger" data-id="${item.id}" style="cursor: pointer;"></i>
                        </td>
                    </tr>
                `);
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Failed to load expense items.');
                    }
                });
            }

            fetchEnrollmentSetup();

            function fetchEnrollmentSetup() {
                $.ajax({
                    url: '/bookkeeper/other_setup_discountJE/fetch_discountje',
                    type: 'GET',
                    success: function(data) {
                        var tbody = $('#discountje_peracad');
                        tbody.empty();

                        data.forEach((program) => {
                            if (program.levels && program.levels.length > 0) {
                                let isFirstProg = true;
                                program.levels.forEach((level) => {
                                    level.debitacc = level.debitacc || '';
                                    level.creditacc = level.creditacc || '';
                                    tbody.append(`
                                        <tr class="classification-row" data-classid="${level.classid}" data-id="${level.id}">
                                            <td style="border: 1px solid #7d7d7d;">${isFirstProg ? program.progname : ''}</td>
                                            <td style="border: 1px solid #7d7d7d;">${level.levelname}</td>
                                            <td style="border: 1px solid #7d7d7d;">${level.debitacc}</td>
                                            <td style="border: 1px solid #7d7d7d;">${level.creditacc}</td>
                                            <td style="border: 1px solid #7d7d7d; text-align: center;">
                                                <i class="far fa-edit text-primary editDiscountJE" id="editDiscountJE_modal" data-id="${level.id}" style="cursor: pointer;"></i>
                                                <i class="far fa-trash-alt text-danger deleteDiscountJE" data-id="${level.id}" style="cursor: pointer;"></i>
                                            </td>
                                        </tr>
                                    `);

                                    isFirstProg = false;
                                });
                            }
                        });
                    },
                    error: function(err) {
                        console.error('Failed to fetch classified setup:', err);
                    }
                });
            }

            $(document).on('click', '#editDiscountJE_modal', function() {
                var id = $(this).data('id');
                $('#edit_discount_je').data('id', id).modal('show');
            })

            $(document).on('click', '.editDiscountJE', function() {

                var discountje_id = $(this).attr('data-id')

                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/other_setup_discountJE/edit_discountje',
                    data: {
                        discountje_id: discountje_id
                    },
                    success: function(response) {


                        var discountje_selected = response.discountje;
                        var coa_all = response.chart_of_accounts;
                        var bk_sub_chart_of_accounts = response.bk_sub_chart_of_accounts;
                        var academicprogram = response.academicprogram;
                        var gradelevel = response.gradelevel;

                        $("#discount_je_id").val(discountje_selected[0].id);

                        $("#debit_account_edit").empty().trigger('change');
                        $("#debit_account_edit").append(
                            '<option value="" selected disabled>Select Debit Account</option>'
                        );

                        $("#credit_account_edit").empty().trigger('change');
                        $("#credit_account_edit").append(
                            '<option value="" selected disabled>Select Credit Account</option>'
                        );

                        $("#academic_program_edit").empty().trigger('change');
                        $("#academic_program_edit").append(
                            '<option value="" selected disabled>Select Academic Program</option>'
                        );

                        $("#grade_level_edit").empty().trigger('change');
                        $("#grade_level_edit").append(
                            '<option value="" selected disabled>Select Grade Level</option>'
                        );

                        coa_all.forEach(coa_all => {
                            if (coa_all.id == discountje_selected[0]
                                .debitacc_id) {
                                $("#debit_account_edit").append(
                                    `<option value="${coa_all.id}" selected>${coa_all.code} - ${coa_all.account_name}</option>`
                                );
                            } else {
                                $("#debit_account_edit").append(
                                    `<option value="${coa_all.id}">${coa_all.code} - ${coa_all.account_name}</option>`
                                );
                            }
                            bk_sub_chart_of_accounts.forEach(
                                bk_sub_chart_of_accounts => {
                                    if (bk_sub_chart_of_accounts.coaid == coa_all
                                        .id) {
                                        if (bk_sub_chart_of_accounts.id ==
                                            discountje_selected[0]
                                            .debitacc_id) {
                                            $("#debit_account_edit").append(
                                                `<option value="${bk_sub_chart_of_accounts.id}" selected>&nbsp;&nbsp;${bk_sub_chart_of_accounts.sub_code} - ${bk_sub_chart_of_accounts.sub_account_name}</option>`
                                            );
                                        } else {
                                            $("#debit_account_edit").append(
                                                `<option value="${bk_sub_chart_of_accounts.id}">&nbsp;&nbsp;${bk_sub_chart_of_accounts.sub_code} - ${bk_sub_chart_of_accounts.sub_account_name}</option>`
                                            );
                                        }
                                    }
                                });
                        });

                        coa_all.forEach(coa_all => {
                            if (coa_all.id == discountje_selected[0]
                                .creditacc_id) {
                                $("#credit_account_edit").append(
                                    `<option value="${coa_all.id}" selected>${coa_all.code} - ${coa_all.account_name}</option>`
                                );
                            } else {
                                $("#credit_account_edit").append(
                                    `<option value="${coa_all.id}">${coa_all.code} - ${coa_all.account_name}</option>`
                                );
                            }
                            bk_sub_chart_of_accounts.forEach(
                                bk_sub_chart_of_accounts => {
                                    if (bk_sub_chart_of_accounts.coaid == coa_all
                                        .id) {
                                        if (bk_sub_chart_of_accounts.id ==
                                            discountje_selected[0]
                                            .creditacc_id) {
                                            $("#credit_account_edit").append(
                                                `<option value="${bk_sub_chart_of_accounts.id}" selected>&nbsp;&nbsp;${bk_sub_chart_of_accounts.sub_code} - ${bk_sub_chart_of_accounts.sub_account_name}</option>`
                                            );
                                        } else {
                                            $("#credit_account_edit").append(
                                                `<option value="${bk_sub_chart_of_accounts.id}">&nbsp;&nbsp;${bk_sub_chart_of_accounts.sub_code} - ${bk_sub_chart_of_accounts.sub_account_name}</option>`
                                            );
                                        }
                                    }
                                });
                        });

                        // academicprogram.forEach(academicprogram => {
                        //     if (academicprogram.id == discountje_selected[0]
                        //         .acadprogid) {
                        //         $("#academic_program_edit").append(
                        //             `<option value="${academicprogram.id}" selected>${academicprogram.progname}</option>`
                        //         );
                        //     } else {
                        //         $("#academic_program_edit").append(
                        //             `<option value="${academicprogram.id}">${academicprogram.progname}</option>`
                        //         );
                        //     }
                        // });

                        // gradelevel.forEach(gradelevel => {
                        //     if (gradelevel.id == discountje_selected[0]
                        //         .levelid) {
                        //         $("#grade_level_edit").append(
                        //             `<option value="${gradelevel.id}" selected>${gradelevel.levelname}</option>`
                        //         );
                        //     } else {
                        //         $("#grade_level_edit").append(
                        //             `<option value="${gradelevel.id}">${gradelevel.levelname}</option>`
                        //         );
                        //     }
                        // });

                        // $("#academic_program_edit").on('change', function(){
                        //     var selectedAcadprog = $(this).val();
                        //     $("#grade_level_edit").empty().trigger('change');
                        //     $("#grade_level_edit").append(
                        //         '<option value="" selected disabled>Select Grade Level</option>'
                        //     );
                        //     gradelevel.forEach(gradelevel => {
                        //         if (gradelevel.acadprogid == selectedAcadprog) {
                        //             if (gradelevel.id == discountje_selected[0]
                        //                 .levelid) {
                        //                 $("#grade_level_edit").append(
                        //                     `<option value="${gradelevel.id}" selected>${gradelevel.levelname}</option>`
                        //                 );
                        //             } else {
                        //                 $("#grade_level_edit").append(
                        //                     `<option value="${gradelevel.id}">${gradelevel.levelname}</option>`
                        //                 );
                        //             }
                        //         }
                        //     });
                        // });

                        // Populate academic programs
                        academicprogram.forEach(program => {
                            let selected = program.id == discountje_selected[0]
                                .acadprogid;
                            $("#academic_program_edit").append(
                                `<option value="${program.id}" ${selected ? 'selected' : ''}>${program.progname}</option>`
                            );
                        });

                        // Function to populate grade levels based on selected academic program
                        function populateGradeLevels(acadProgId) {
                            $("#grade_level_edit").empty().append(
                                '<option value="" selected disabled>Select Grade Level</option>'
                            );

                            gradelevel.forEach(level => {
                                if (level.acadprogid == acadProgId) {
                                    let selected = level.id == discountje_selected[0]
                                        .levelid;
                                    $("#grade_level_edit").append(
                                        `<option value="${level.id}" ${selected ? 'selected' : ''}>${level.levelname}</option>`
                                    );
                                }
                            });
                        }

                        // Initially populate grade levels based on the currently selected academic program
                        if (discountje_selected[0].acadprogid) {
                            populateGradeLevels(discountje_selected[0].acadprogid);
                        }

                        // Set up the onChange event for academic program
                        $("#academic_program_edit").off('change').on('change', function() {
                            var selectedAcadprog = $(this).val();
                            populateGradeLevels(selectedAcadprog);
                        });

                    }
                });

            });

            ////////////////////////////////////////////////////////////

            fetchCredAdjSetup();

            function fetchCredAdjSetup() {
                $.ajax({
                    url: '/bookkeeper/other_setup_credadjJE/fetch_credadj',
                    type: 'GET',
                    success: function(data) {
                        var tbody = $('#creditadj_je_table');
                        tbody.empty();

                        data.forEach((program) => {
                            if (program.levels && program.levels.length > 0) {
                                let isFirstProg = true;
                                program.levels.forEach((level) => {
                                    level.debitacc = level.debitacc || '';
                                    level.creditacc = level.creditacc || '';
                                    tbody.append(`
                            <tr class="classification-row" data-classid="${level.classid}" data-id="${level.id}">
                                <td style="border: 1px solid #7d7d7d;">${isFirstProg ? program.progname : ''}</td>
                                <td style="border: 1px solid #7d7d7d;">${level.levelname}</td>
                                <td style="border: 1px solid #7d7d7d;">${level.debitacc}</td>
                                <td style="border: 1px solid #7d7d7d;">${level.creditacc}</td>
                                <td style="border: 1px solid #7d7d7d; text-align: center;">
                                    <i class="far fa-edit text-primary editCredadjJE" id="editCredadjJE_modal" data-id="${level.id}" style="cursor: pointer;"></i>
                                    <i class="far fa-trash-alt text-danger deleteCredadjJE" data-id="${level.id}" style="cursor: pointer;"></i>
                                </td>
                            </tr>
                        `);

                                    isFirstProg = false;
                                });
                            }
                        });
                    },
                    error: function(err) {
                        console.error('Failed to fetch classified setup:', err);
                    }
                });
            }

            //////////////////////////////////////////////////////////////////////

            fetchDebAdjSetup();

            function fetchDebAdjSetup() {
                $.ajax({
                    url: '/bookkeeper/other_setup_debadjJE/fetch_debadjje',
                    type: 'GET',
                    success: function(data) {
                        var tbody = $('#debitadj_je_table');
                        tbody.empty();

                        data.forEach((program) => {
                            if (program.levels && program.levels.length > 0) {
                                let isFirstProg = true;
                                program.levels.forEach((level) => {
                                    level.debitacc = level.debitacc || '';
                                    level.creditacc = level.creditacc || '';
                                    tbody.append(`
                <tr class="classification-row" data-classid="${level.classid}" data-id="${level.id}">
                    <td style="border: 1px solid #7d7d7d;">${isFirstProg ? program.progname : ''}</td>
                    <td style="border: 1px solid #7d7d7d;">${level.levelname}</td>
                    <td style="border: 1px solid #7d7d7d;">${level.debitacc}</td>
                    <td style="border: 1px solid #7d7d7d;">${level.creditacc}</td>
                    <td style="border: 1px solid #7d7d7d; text-align: center;">
                        <i class="far fa-edit text-primary editDebadjJE" id="editDebadjJE_modal" data-id="${level.id}" style="cursor: pointer;"></i>
                        <i class="far fa-trash-alt text-danger deleteDebadjJE" data-id="${level.id}" style="cursor: pointer;"></i>
                    </td>
                </tr>
            `);

                                    isFirstProg = false;
                                });
                            }
                        });
                    },
                    error: function(err) {
                        console.error('Failed to fetch classified setup:', err);
                    }
                });
            }


            ///////////////////////////////////////////////////////////////////////

            /////////////////////// Credit Adjusted Journal Entry //////////////////
            $(document).on('click', '#editCredadjJE_modal', function() {
                var id = $(this).data('id');
                $('#edit_credadj_je').data('id', id).modal('show');
            })

            $(document).on('click', '.editCredadjJE', function() {

                var credadjje_id = $(this).attr('data-id')

                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/other_setup_credadjJE/edit_credadj',
                    data: {
                        credadjje_id: credadjje_id
                    },
                    success: function(response) {


                        var credadjje_selected = response.credadjje_id;
                        var coa_all = response.chart_of_accounts;
                        var bk_sub_chart_of_accounts = response.bk_sub_chart_of_accounts;
                        var academicprogram = response.academicprogram;
                        var gradelevel = response.gradelevel;

                        $("#credadj_je_id").val(credadjje_selected[0].id);

                        $("#cred_adj_debit_account_edit").empty().trigger('change');
                        $("#cred_adj_debit_account_edit").append(
                            '<option value="" selected disabled>Select Debit Account</option>'
                        );

                        $("#cred_adj_credit_account_edit").empty().trigger('change');
                        $("#cred_adj_credit_account_edit").append(
                            '<option value="" selected disabled>Select Credit Account</option>'
                        );

                        $("#cred_adj_academic_program_edit").empty().trigger('change');
                        $("#cred_adj_academic_program_edit").append(
                            '<option value="" selected disabled>Select Academic Program</option>'
                        );

                        $("#cred_adj_grade_level_edit").empty().trigger('change');
                        $("#cred_adj_grade_level_edit").append(
                            '<option value="" selected disabled>Select Grade Level</option>'
                        );

                        coa_all.forEach(coa_all => {
                            if (coa_all.id == credadjje_selected[0]
                                .debitacc_id) {
                                $("#cred_adj_debit_account_edit").append(
                                    `<option value="${coa_all.id}" selected>${coa_all.code} - ${coa_all.account_name}</option>`
                                );
                            } else {
                                $("#cred_adj_debit_account_edit").append(
                                    `<option value="${coa_all.id}">${coa_all.code} - ${coa_all.account_name}</option>`
                                );
                            }
                            bk_sub_chart_of_accounts.forEach(
                                bk_sub_chart_of_accounts => {
                                    if (bk_sub_chart_of_accounts.coaid == coa_all
                                        .id) {
                                        if (bk_sub_chart_of_accounts.id ==
                                            credadjje_selected[0]
                                            .debitacc_id) {
                                            $("#cred_adj_debit_account_edit")
                                                .append(
                                                    `<option value="${bk_sub_chart_of_accounts.id}" selected>&nbsp;&nbsp;${bk_sub_chart_of_accounts.sub_code} - ${bk_sub_chart_of_accounts.sub_account_name}</option>`
                                                );
                                        } else {
                                            $("#cred_adj_debit_account_edit")
                                                .append(
                                                    `<option value="${bk_sub_chart_of_accounts.id}">&nbsp;&nbsp;${bk_sub_chart_of_accounts.sub_code} - ${bk_sub_chart_of_accounts.sub_account_name}</option>`
                                                );
                                        }
                                    }
                                });
                        });

                        coa_all.forEach(coa_all => {
                            if (coa_all.id == credadjje_selected[0]
                                .creditacc_id) {
                                $("#cred_adj_credit_account_edit").append(
                                    `<option value="${coa_all.id}" selected>${coa_all.code} - ${coa_all.account_name}</option>`
                                );
                            } else {
                                $("#cred_adj_credit_account_edit").append(
                                    `<option value="${coa_all.id}">${coa_all.code} - ${coa_all.account_name}</option>`
                                );
                            }
                            bk_sub_chart_of_accounts.forEach(
                                bk_sub_chart_of_accounts => {
                                    if (bk_sub_chart_of_accounts.coaid == coa_all
                                        .id) {
                                        if (bk_sub_chart_of_accounts.id ==
                                            credadjje_selected[0]
                                            .creditacc_id) {
                                            $("#cred_adj_credit_account_edit")
                                                .append(
                                                    `<option value="${bk_sub_chart_of_accounts.id}" selected>&nbsp;&nbsp;${bk_sub_chart_of_accounts.sub_code} - ${bk_sub_chart_of_accounts.sub_account_name}</option>`
                                                );
                                        } else {
                                            $("#cred_adj_credit_account_edit")
                                                .append(
                                                    `<option value="${bk_sub_chart_of_accounts.id}">&nbsp;&nbsp;${bk_sub_chart_of_accounts.sub_code} - ${bk_sub_chart_of_accounts.sub_account_name}</option>`
                                                );
                                        }
                                    }
                                });
                        });

                        // academicprogram.forEach(academicprogram => {
                        //     if (academicprogram.id == credadjje_selected[0]
                        //         .acadprogid) {
                        //         $("#cred_adj_academic_program_edit").append(
                        //             `<option value="${academicprogram.id}" selected>${academicprogram.progname}</option>`
                        //         );
                        //     } else {
                        //         $("#cred_adj_academic_program_edit").append(
                        //             `<option value="${academicprogram.id}">${academicprogram.progname}</option>`
                        //         );
                        //     }
                        // });

                        // gradelevel.forEach(gradelevel => {
                        //     if (gradelevel.id == credadjje_selected[0]
                        //         .levelid) {
                        //         $("#cred_adj_grade_level_edit").append(
                        //             `<option value="${gradelevel.id}" selected>${gradelevel.levelname}</option>`
                        //         );
                        //     } else {
                        //         $("#cred_adj_grade_level_edit").append(
                        //             `<option value="${gradelevel.id}">${gradelevel.levelname}</option>`
                        //         );
                        //     }
                        // });

                        academicprogram.forEach(program => {
                            let selected = program.id == credadjje_selected[0]
                                .acadprogid;
                            $("#cred_adj_academic_program_edit").append(
                                `<option value="${program.id}" ${selected ? 'selected' : ''}>${program.progname}</option>`
                            );
                        });

                        // Function to populate grade levels based on selected academic program
                        function populateGradeLevels(acadProgId) {
                            $("#cred_adj_grade_level_edit").empty().append(
                                '<option value="" selected disabled>Select Grade Level</option>'
                            );

                            gradelevel.forEach(level => {
                                if (level.acadprogid == acadProgId) {
                                    let selected = level.id == credadjje_selected[0]
                                        .levelid;
                                    $("#cred_adj_grade_level_edit").append(
                                        `<option value="${level.id}" ${selected ? 'selected' : ''}>${level.levelname}</option>`
                                    );
                                }
                            });
                        }

                        // Initially populate grade levels based on the currently selected academic program
                        if (credadjje_selected[0].acadprogid) {
                            populateGradeLevels(credadjje_selected[0].acadprogid);
                        }

                        // Set up the onChange event for academic program
                        $("#cred_adj_academic_program_edit").off('change').on('change',
                            function() {
                                var selectedAcadprog = $(this).val();
                                populateGradeLevels(selectedAcadprog);
                            });


                    }
                });

            });



            /////////////////////// Debit Adjusted Journal Entry //////////////////

            $(document).on('click', '#editDebadjJE_modal', function() {
                var id = $(this).data('id');
                $('#edit_debadj_je').data('id', id).modal('show');
            })

            $(document).on('click', '.editDebadjJE', function() {

                var debadjje_id = $(this).attr('data-id')

                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/other_setup_debadjJE/edit_debadj',
                    data: {
                        debadjje_id: debadjje_id
                    },
                    success: function(response) {


                        var debadjje_selected = response.debadjje_id;
                        var coa_all = response.chart_of_accounts;
                        var bk_sub_chart_of_accounts = response.bk_sub_chart_of_accounts;
                        var academicprogram = response.academicprogram;
                        var gradelevel = response.gradelevel;

                        $("#debadj_je_id").val(debadjje_selected[0].id);

                        $("#deb_adj_debit_account_edit").empty().trigger('change');
                        $("#deb_adj_debit_account_edit").append(
                            '<option value="" selected disabled>Select Debit Account</option>'
                        );

                        $("#deb_adj_credit_account_edit").empty().trigger('change');
                        $("#deb_adj_credit_account_edit").append(
                            '<option value="" selected disabled>Select Credit Account</option>'
                        );

                        $("#deb_adj_academic_program_edit").empty().trigger('change');
                        $("#deb_adj_academic_program_edit").append(
                            '<option value="" selected disabled>Select Academic Program</option>'
                        );

                        $("#deb_adj_grade_level_edit").empty().trigger('change');
                        $("#deb_adj_grade_level_edit").append(
                            '<option value="" selected disabled>Select Grade Level</option>'
                        );

                        coa_all.forEach(coa_all => {
                            if (coa_all.id == debadjje_selected[0]
                                .debitacc_id) {
                                $("#deb_adj_debit_account_edit").append(
                                    `<option value="${coa_all.id}" selected>${coa_all.code} - ${coa_all.account_name}</option>`
                                );
                            } else {
                                $("#deb_adj_debit_account_edit").append(
                                    `<option value="${coa_all.id}">${coa_all.code} - ${coa_all.account_name}</option>`
                                );
                            }
                            bk_sub_chart_of_accounts.forEach(
                                bk_sub_chart_of_accounts => {
                                    if (bk_sub_chart_of_accounts.coaid == coa_all
                                        .id) {
                                        if (bk_sub_chart_of_accounts.id ==
                                            debadjje_selected[0]
                                            .debitacc_id) {
                                            $("#deb_adj_debit_account_edit").append(
                                                `<option value="${bk_sub_chart_of_accounts.id}" selected>&nbsp;&nbsp;${bk_sub_chart_of_accounts.sub_code} - ${bk_sub_chart_of_accounts.sub_account_name}</option>`
                                            );
                                        } else {
                                            $("#deb_adj_debit_account_edit").append(
                                                `<option value="${bk_sub_chart_of_accounts.id}">&nbsp;&nbsp;${bk_sub_chart_of_accounts.sub_code} - ${bk_sub_chart_of_accounts.sub_account_name}</option>`
                                            );
                                        }
                                    }
                                });
                        });

                        coa_all.forEach(coa_all => {
                            if (coa_all.id == debadjje_selected[0]
                                .creditacc_id) {
                                $("#deb_adj_credit_account_edit").append(
                                    `<option value="${coa_all.id}" selected>${coa_all.code} - ${coa_all.account_name}</option>`
                                );
                            } else {
                                $("#deb_adj_credit_account_edit").append(
                                    `<option value="${coa_all.id}">${coa_all.code} - ${coa_all.account_name}</option>`
                                );
                            }
                            bk_sub_chart_of_accounts.forEach(
                                bk_sub_chart_of_accounts => {
                                    if (bk_sub_chart_of_accounts.coaid == coa_all
                                        .id) {
                                        if (bk_sub_chart_of_accounts.id ==
                                            debadjje_selected[0]
                                            .creditacc_id) {
                                            $("#deb_adj_credit_account_edit")
                                                .append(
                                                    `<option value="${bk_sub_chart_of_accounts.id}" selected>&nbsp;&nbsp;${bk_sub_chart_of_accounts.sub_code} - ${bk_sub_chart_of_accounts.sub_account_name}</option>`
                                                );
                                        } else {
                                            $("#deb_adj_credit_account_edit")
                                                .append(
                                                    `<option value="${bk_sub_chart_of_accounts.id}">&nbsp;&nbsp;${bk_sub_chart_of_accounts.sub_code} - ${bk_sub_chart_of_accounts.sub_account_name}</option>`
                                                );
                                        }
                                    }
                                });
                        });

                        // academicprogram.forEach(academicprogram => {
                        //     if (academicprogram.id == debadjje_selected[0]
                        //         .acadprogid) {
                        //         $("#deb_adj_academic_program_edit").append(
                        //             `<option value="${academicprogram.id}" selected>${academicprogram.progname}</option>`
                        //         );
                        //     } else {
                        //         $("#deb_adj_academic_program_edit").append(
                        //             `<option value="${academicprogram.id}">${academicprogram.progname}</option>`
                        //         );
                        //     }
                        // });

                        // gradelevel.forEach(gradelevel => {
                        //     if (gradelevel.id == debadjje_selected[0]
                        //         .levelid) {
                        //         $("#deb_adj_grade_level_edit").append(
                        //             `<option value="${gradelevel.id}" selected>${gradelevel.levelname}</option>`
                        //         );
                        //     } else {
                        //         $("#deb_adj_grade_level_edit").append(
                        //             `<option value="${gradelevel.id}">${gradelevel.levelname}</option>`
                        //         );
                        //     }
                        // });

                        // Populate academic programs
                        academicprogram.forEach(program => {
                            let selected = program.id == debadjje_selected[0]
                                .acadprogid;
                            $("#deb_adj_academic_program_edit").append(
                                `<option value="${program.id}" ${selected ? 'selected' : ''}>${program.progname}</option>`
                            );
                        });

                        // Function to populate grade levels based on selected academic program
                        function populateGradeLevels(acadProgId) {
                            $("#deb_adj_grade_level_edit").empty().append(
                                '<option value="" selected disabled>Select Grade Level</option>'
                            );

                            gradelevel.forEach(level => {
                                if (level.acadprogid == acadProgId) {
                                    let selected = level.id == debadjje_selected[0]
                                        .levelid;
                                    $("#deb_adj_grade_level_edit").append(
                                        `<option value="${level.id}" ${selected ? 'selected' : ''}>${level.levelname}</option>`
                                    );
                                }
                            });
                        }

                        // Initially populate grade levels based on the currently selected academic program
                        if (debadjje_selected[0].acadprogid) {
                            populateGradeLevels(debadjje_selected[0].acadprogid);
                        }

                        // Set up the onChange event for academic program
                        $("#deb_adj_academic_program_edit").off('change').on('change',
                        function() {
                            var selectedAcadprog = $(this).val();
                            populateGradeLevels(selectedAcadprog);
                        });

                    }
                });

            });





            ////////////////////////////////////////////////////////////



            $(document).on('click', '.update_discount_je', function() {
                update_discount_je()
            })

            function update_discount_je() {
                var discount_je_id = $('#discount_je_id').val();
                var academic_program_edit = $('#academic_program_edit').val();
                var grade_level_edit = $('#grade_level_edit').val();
                var debit_account_edit = $('#debit_account_edit').val();
                var credit_account_edit = $('#credit_account_edit').val();


                $.ajax({
                    type: 'POST',
                    url: '/bookkeeper/other_setup_discountJE/update_discountje',
                    data: {
                        _token: '{{ csrf_token() }}',
                        discount_je_id: discount_je_id,
                        academic_program_edit: academic_program_edit,
                        grade_level_edit: grade_level_edit,
                        debit_account_edit: debit_account_edit,
                        credit_account_edit: credit_account_edit

                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully updated'
                            })
                            fetchEnrollmentSetup();
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }

            /////////////////////////////////////////////////////////////

            $(document).on('click', '.update_deb_adj_je', function() {
                update_debadj_je()
            })

            function update_debadj_je() {
                var debadj_je_id = $('#debadj_je_id').val();
                var deb_adj_academic_program_edit = $('#deb_adj_academic_program_edit').val();
                var deb_adj_grade_level_edit = $('#deb_adj_grade_level_edit').val();
                var deb_adj_debit_account_edit = $('#deb_adj_debit_account_edit').val();
                var deb_adj_credit_account_edit = $('#deb_adj_credit_account_edit').val();


                $.ajax({
                    type: 'POST',
                    url: '/bookkeeper/other_setup_debadjJE/update_debadj',
                    data: {
                        _token: '{{ csrf_token() }}',
                        debadj_je_id: debadj_je_id,
                        deb_adj_academic_program_edit: deb_adj_academic_program_edit,
                        deb_adj_grade_level_edit: deb_adj_grade_level_edit,
                        deb_adj_debit_account_edit: deb_adj_debit_account_edit,
                        deb_adj_credit_account_edit: deb_adj_credit_account_edit

                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully updated'
                            })
                            fetchDebAdjSetup();
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }

            /////////////////////////////////////////////////////////

            $(document).on('click', '.update_cred_adj_je', function() {
                update_cred_adj_je()
            })

            function update_cred_adj_je() {
                var credadj_je_id = $('#credadj_je_id').val();
                var cred_adj_academic_program_edit = $('#cred_adj_academic_program_edit').val();
                var cred_adj_grade_level_edit = $('#cred_adj_grade_level_edit').val();
                var cred_adj_debit_account_edit = $('#cred_adj_debit_account_edit').val();
                var cred_adj_credit_account_edit = $('#cred_adj_credit_account_edit').val();


                $.ajax({
                    type: 'POST',
                    url: '/bookkeeper/other_setup_credadjJE/update_credadj',
                    data: {
                        _token: '{{ csrf_token() }}',
                        credadj_je_id: credadj_je_id,
                        cred_adj_academic_program_edit: cred_adj_academic_program_edit,
                        cred_adj_grade_level_edit: cred_adj_grade_level_edit,
                        cred_adj_debit_account_edit: cred_adj_debit_account_edit,
                        cred_adj_credit_account_edit: cred_adj_credit_account_edit

                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully updated'
                            })
                            fetchCredAdjSetup();
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }

            /////////////////////////////////////////////////////////

            $(document).on('click', '.deleteDiscountJE', function() {
                var deletesdiscountId = $(this).attr('data-id')
                Swal.fire({
                    text: 'Are you sure you want to remove Discount JE?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Remove'
                }).then((result) => {
                    if (result.value) {
                        delete_discount_je(deletesdiscountId)
                    }
                })
            });

            function delete_discount_je(deletesdiscountId) {
                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/other_setup_discountJE/delete_discountje',
                    data: {
                        deletesdiscountId: deletesdiscountId

                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully deleted'
                            })

                            fetchEnrollmentSetup();
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }

            /////////////////////////////////////////////////////////


            $(document).on('click', '.deleteDebadjJE', function() {
                var deletedebadjId = $(this).attr('data-id')
                Swal.fire({
                    text: 'Are you sure you want to remove Debit Adjustment JE?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Remove'
                }).then((result) => {
                    if (result.value) {
                        deleteDebadjJE(deletedebadjId)
                    }
                })
            });

            function deleteDebadjJE(deletedebadjId) {
                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/other_setup_debadjJE/delete_debadj',
                    data: {
                        deletedebadjId: deletedebadjId

                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully deleted'
                            })

                            fetchDebAdjSetup();
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }


            /////////////////////////////////////////////////////////

            $(document).on('click', '.deleteCredadjJE', function() {
                var deletecredadjId = $(this).attr('data-id')
                Swal.fire({
                    text: 'Are you sure you want to remove Credit Adjustment JE?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Remove'
                }).then((result) => {
                    if (result.value) {
                        deleteCredadjJE(deletecredadjId)
                    }
                })
            });

            function deleteCredadjJE(deletecredadjId) {
                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/other_setup_credadjJE/delete_credadj',
                    data: {
                        deletecredadjId: deletecredadjId

                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully deleted'
                            })

                            fetchCredAdjSetup();
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }


            $(document).on('click', '#costcenter_create', function() {
                costcenter_clearfields()
                $('#costcenter_save').show()
                $('#costcenter_update').hide()
                $('#modal-costcenter_detail').modal('show')
            })

            $('#modal-costcenter_detail').on('shown.bs.modal', function() {
                $('#costcenter_description').focus()
            })

            function costcenter_clearfields() {
                $('#costcenter_description').val('')
                $('#costcenter_acc_code').val('')
                $('#costcenter_id').val('')

                $('#costcenter_description').removeClass('null');
                $('#costcenter_acc_code').removeClass('null');
                $('.costcenter_description_null').attr('hidden', true);
                $('.costcenter_acc_code_null').attr('hidden', true);
            }

            costcenter_read()

            function costcenter_read() {
                $('#cost_center_table').DataTable({
                    destroy: true,
                    lengthMenu: false,
                    info: false,
                    paging: true,
                    searching: true,
                    lengthChange: false,
                    scrollX: false,
                    autoWidth: false, // changed to false for better performance
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('bookkeeper_costcenter_read') }}",
                        type: "GET",
                        error: function (xhr, error, thrown) {
                            console.error('Error loading cost centers:', error);
                        }
                    },
                    columns: [
                        { "data": 'acc_code' },
                        { "data": 'description'},
                        { "data": null } // for action buttons
                    ],
                    columnDefs: [
                        {
                            targets: 0,
                            orderable: false,
                            createdCell: function (td, cellData) {
                                var text = '<span class="text-uppercase">' + cellData + '</span>';
                                $(td).html(text).addClass('align-middle text-left');
                            }
                        },
                        {
                            targets: 1,
                            orderable: false,
                            createdCell: function (td, cellData) {
                                var text = '<span>' + cellData + '</span>';
                                $(td).html(text).addClass('align-middle text-left');
                            }
                        },
                        {
                            targets: 2,
                            orderable: false,
                            createdCell: function (td, cellData, rowData) {
                                var buttons = `
                                    <div class="text-center">
                                        <i class="fas fa-edit text-primary costcenter_edit" data-id="${rowData.id}" style="cursor:pointer;"></i>
                                        <i class="fas fa-trash text-danger costcenter_delete" data-id="${rowData.id}" style="cursor:pointer; margin-left:10px;"></i>
                                    </div>
                                `;
                                $(td).html(buttons).addClass('align-middle text-center');
                            }
                        }
                    ],
                    initComplete: function () {
                        // Append Create Button to DataTable's filter wrapper
                        $('#cost_center_table_wrapper .dataTables_filter').prepend(`
                            <button id="costcenter_create" class="btn btn-sm btn-primary mr-2">Create</button>
                        `);
                    }
                });
            }

            $(document).on('click', '#costcenter_save', function() {
                var valid_data = true;
                let description = $('#costcenter_description').val()
                let code = $('#costcenter_acc_code').val()
                let id = $(this).attr('data-id')

                // Clear previous errors first
                $('#costcenter_description').removeClass('null');
                $('#costcenter_acc_code').removeClass('null');
                $('.costcenter_description_null').attr('hidden', true);
                $('.costcenter_acc_code_null').attr('hidden', true);
                
                if (description.trim() === '') {
                    Toast.fire({
                        type: "warning",
                        title: "Please fill out all required fields!"
                    });

                    $('.costcenter_description_null').prop('hidden', false);
                    $('#costcenter_description').addClass('null');

                    valid_data = false;
                }

                if (code.trim() === '') {
                    Toast.fire({
                        type: "warning",
                        title: "Please fill out all required fields!"
                    });

                    $('.costcenter_acc_code_null').prop('hidden', false);
                    $('#costcenter_acc_code').addClass('null');

                    valid_data = false;
                }

                if(valid_data){
                    $.ajax({
                        type: "GET",
                        url: "{{ route('costcenter_create') }}",
                        data: {
                            description: description,
                            code: code,
                            id: id
                        },
                        success: function(data) {

                            costcenter_read()
                            costcenter_clearfields()

                            Toast.fire({
                                type: "success",
                                title: "Cost Center has been added"
                            });
                        }
                    });
                }
            })

            $(document).on('click', '.costcenter_edit', function() {
                let id = $(this).attr('data-id')

                $.ajax({
                    type: "GET",
                    url: "{{ route('costcenter_edit') }}",
                    data: {
                    id: id
                    },
                    success: function(data) {
                        $('#costcenter_description').val(data.description);
                        $('#costcenter_acc_code').val(data.acc_code);
                        $('#costcenter_id').val(id);

                        $('#costcenter_save').hide()
                        $('#costcenter_update').show()
                        $('#modal-costcenter_detail').modal('show')
                    }
                });

            })

            $(document).on('click', '#costcenter_update', function() {
                var valid_data = true;
                let description = $('#costcenter_description').val()
                let code = $('#costcenter_acc_code').val()
                let id = $('#costcenter_id').val()

                // Clear previous errors first
                $('#costcenter_description').removeClass('null');
                $('#costcenter_acc_code').removeClass('null');
                $('.costcenter_description_null').attr('hidden', true);
                $('.costcenter_acc_code_null').attr('hidden', true);
                
                if (description.trim() === '') {
                    Toast.fire({
                        type: "warning",
                        title: "Please fill out all required fields!"
                    });

                    $('.costcenter_description_null').prop('hidden', false);
                    $('#costcenter_description').addClass('null');

                    valid_data = false;
                }

                if (code.trim() === '') {
                    Toast.fire({
                        type: "warning",
                        title: "Please fill out all required fields!"
                    });

                    $('.costcenter_acc_code_null').prop('hidden', false);
                    $('#costcenter_acc_code').addClass('null');

                    valid_data = false;
                }

                if (valid_data) {
                    Swal.fire({
                            title: 'Are you sure you want to update?',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Proceed'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "GET",
                                url: "{{ route('costcenter_update') }}",
                                data: {
                                    description: description,
                                    code: code,
                                    id: id
                                },
                                success: function(data) {
                                    costcenter_read()
                                    costcenter_clearfields()
                                    $('#modal-costcenter_detail').modal('hide')

                                    if(data[0].status == 0){
                                        Toast.fire({
                                            type: 'error',
                                            title: data[0].message
                                        })
                                    }else{
                                        Toast.fire({
                                            type: 'success',
                                            title: data[0].message
                                        })
                                    }
                                }
                            });   
                        }
                    })
                }
                
            })

            $(document).on('click', '.costcenter_delete', function(){
                var valid_data = true;
                let id = $(this).attr('data-id')
  
                if (valid_data) {
                    Swal.fire({
                            title: 'Are you sure you want to delete?',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes Delete it'
                    }).then((result) => {
                        if (result.value) {
                                $.ajax({
                                    type: "GET",
                                    url: "{{ route('costcenter_delete') }}",
                                    data: {
                                    id: id
                                },
                                success: function(data) {

                                    costcenter_read()
                                    costcenter_clearfields()

                                    if(data[0].status == 0){
                                        Toast.fire({
                                            type: 'error',
                                            title: data[0].message
                                        })
                                    }else{
                                        Toast.fire({
                                            type: 'success',
                                            title: data[0].message
                                        })
                                    }
                                }
                            });
                        }
                    })
                }
                
            })

        });
    </script>
@endsection
