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

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home Accounting</title>
    <style>
        select,
        input,
        th,
        td {
            font-size: 13px !important;
            font-weight: normal !important;
        }

        .form-control {
            /* box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42) !important; */
            /* border: none !important; */
            height: 40px !important;


        }

        ::placeholder {
            font-size: 12px !important;
        }

        label {
            font-weight: normal !important;
            font-size: 13px !important;
        }

        th {
            border: 1px solid #7d7d7d !important;
        }

        td {
            border: 1px solid #7d7d7d !important;
        }

        nav[aria-label="breadcrumb"] .breadcrumb {
            font-size: 14px;
            /* Smaller text */
            padding: 4px 8px;
            /* Smaller padding */
        }

        .breadcrumb-item a {
            font-size: 12px;
            /* Smaller links too */
        }

        #editpurchaseOrderModal .modal-content {
            display: flex;
            flex-direction: column;
            height: 80vh;
            border-radius: 16px !important;
        }

        #editpurchaseOrderModal .modal-header {
            flex-shrink: 0;
            background-color: #d9d9d9;
        }

        #editpurchaseOrderModal .modal-body {
            flex-grow: 1;
            overflow-y: auto;
        }

        #editpurchaseOrderModal .modal-footer {
            flex-shrink: 0;
        }

        #add_disbursement_po .modal-content {
            display: flex;
            flex-direction: column;
            height: 80vh;
            border-radius: 16px !important;
        }

        #add_disbursement_po .modal-header {
            flex-shrink: 0;
            background-color: #d9d9d9;
        }

        #add_disbursement_po .modal-body {
            flex-grow: 1;
            overflow-y: auto;
        }

        #add_disbursement_po .modal-footer {
            flex-shrink: 0;
        }

        .hoverable-row:hover {
            background-color: lightblue;
        }
    </style>

</head>
@section('content')
    @php
        // $fiscal = DB::table('bk_fiscal_year')->where('deleted', 0)->get();
        // $activeFiscalYear = DB::table('bk_fiscal_year')->where('isactive', 1)->where('deleted', 0)->first();
        // $activeFiscalYearId = isset($activeFiscalYear) ? $activeFiscalYear->id : null;

        $coa = DB::table('chart_of_accounts')->get();
    @endphp
    <div class="container-fluid ml-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="fas fa-file-alt ml-2 mt-2 mb-2" style="font-size: 33px;"></i> <i
                    class="fas fa-pencil-alt mt-2 mr-3 mb-2" style="font-size: 18px;"></i>
                <h1 class="text-black m-0">Receiving</h1>

            </div>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Receiving</li>
                </ol>
            </nav>

        </div>

        <div class="mb-3" style="color: black;  font-size: 13px;">
            <ul class="nav nav-tabs" style="border-bottom: 4px solid #d9d9d9;;">
                <li class="nav-item">
                    <a class="nav-link " href="/bookkeeper/supplier"
                        class="nav-link {{ Request::url() == url('/supplier') ? 'active' : '' }}"
                        style="color: black;">Supplier</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/bookkeeper/purchase_order"
                        class="nav-link {{ Request::url() == url('/purchase_order') ? 'active' : '' }}"
                        style="color: black;">Purchase
                        Order</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="/bookkeeper/receiving"
                        class="nav-link {{ Request::url() == url('/bookkeeper/receiving') ? 'active' : '' }}"
                        style="color: black; font-weight: 600; background-color: #d9d9d9; border-top-left-radius: 10px; border-top-right-radius: 10px;">Receiving
                        Setup</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/bookkeeper/stock_card"
                        class="nav-link {{ Request::url() == url('bookkeeper/stock_card') ? 'active' : '' }}"
                        style="color: black;">Stock Card</a>
                </li>
            </ul>
        </div>


        <hr style="border-top: 2px solid #d9d9d9;">

        <div class="container-fluid p-0 mt-3">
            <div class="bg-light p-4 border rounded" id="filter">
                <div class="row align-items-center">
                    <!-- Filter Title -->
                    <div class="col-md-2 d-flex align-items-center">
                        <i class="fas fa-filter mr-2"></i>
                        <span class="font-weight-bold">Filter</span>
                    </div>

                    <!-- Date Range -->
                    <div class="col-md-3">
                        <label for="dateRange" class="font-weight-bold">Date Range</label>
                        <input type="text" id="dateRange" class="form-control">
                    </div>

                    <!-- Supplier Name -->
                    <div class="col-md-3">
                        <label for="supplierName" class="font-weight-bold">Supplier Name</label>
                        <select id="supplierName" class="form-control">
                            <option value="0" selected>Select Supplier</option>
                            @foreach (DB::table('purchasing_supplier')->where('deleted', 0)->get() as $supplier)
                                <option value="{{ $supplier->suppliername }}">{{ $supplier->suppliername }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- PO Status -->
                    <div class="col-md-3">
                        <label for="poStatus" class="font-weight-bold">PO Status</label>
                        <select id="poStatus" class="form-control">
                            <option value="0" selected>Select Status</option>
                            <option value="PENDING">PENDING</option>
                            <option value="RECEIVED">RECEIVED</option>
                            <option value="REJECTED">REJECTED</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>



        <div class="row bg-white py-3">
            <div class="col-lg-12 col-md-12">
                <div class="table-responsive w-100">
                    <div class="row py-3">
                        {{-- <div class="col-md-12">
                            <button class="btn btn-success btn-sm" id="add_suppliers" style="background-color: #015918;"
                                data-toggle="modal" data-target="#add_purchase_order">
                                + Add Purchase Order <i class="fas fa-grip-horizontal"></i>
                            </button>
                        </div> --}}
                    </div>
                    <table id="receiving_accounting_supplier_table" class="table table-sm w-100">
                        <thead class="table-secondary">
                            <tr>
                                <th class="text-left">Reference No.</th>
                                <th class="text-left">Supplier Name</th>
                                <th class="text-left">Remarks</th>
                                <th class="text-left">Amount</th>
                                <th class="text-left">Date</th>
                                <th class="text-left">Status</th>
                                {{-- <th class="text-center" colspan="2">Action</th> --}}
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
        <div class="row bg-white py-3">
            <div class="col-lg-12 col-md-12">
                <h5>Received History</h5>
                <div class="table-responsive w-100">
                    <table id="received_history_table" class="table table-bordered table-sm w-100">
                        <thead class="table-secondary">
                            <tr>
                                <th class="text-left">Invoice No.</th>
                                <th class="text-left">Supplier Name</th>
                                <th class="text-left">Remarks</th>
                                <th class="text-left">Amount</th>
                                <th class="text-left">Date</th>
                                <th class="text-left">Status</th>
                                <th class="text-center" colspan="2">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>




        <!-- New Purchase Order Modal -->
        <div class="modal fade" id="add_purchase_order" data-backdrop="static" role="dialog"
            aria-labelledby="purchaseOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                    <div class="modal-header " style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                        <h5 class="modal-title" id="purchaseOrderModalLabel">New Purchase Order</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            id="close_purchase_modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form Inputs -->
                        <div class="row">
                            <div class="col-md-4">
                                <label for="poRefNumber">PO Reference Number</label>
                                <input type="text" class="form-control" id="poRefNumber"
                                    placeholder="Enter Ref. No.">
                            </div>
                            <div class="col-md-4">
                                <label for="supplier">Supplier</label>
                                <select class="form-control" id="select_supplier">
                                    {{-- <option selected>Select Supplier</option>
                                    <option>Supplier 1</option>
                                    <option>Supplier 2</option> --}}
                                    <option value="" selected disabled>Select Suppliers</option>
                                    @foreach (DB::table('purchasing_supplier')->where('deleted', 0)->get() as $item)
                                        <option value="{{ $item->id }}">{{ $item->suppliername }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="date">Date</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" id="podate">
                                    <div class="input-group-append">

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add Item Button -->
                        <div class="mt-3">
                            <button class="btn btn-success" data-toggle="modal" data-target="#addItemModal"><i
                                    class="fas fa-plus"></i> Add Item</button>
                        </div>

                        <!-- Items Table -->
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-sm purchase_items_table" id="purchase_items_table">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Amount</th>
                                        <th>QTY</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right font-weight-bold">Total</th>
                                        <th id="grand_total_amount" class="font-weight-bold"></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Remarks -->
                        <div class="mt-3">
                            <label for="remarks">Remarks</label>
                            <textarea class="form-control" id="poremarks" placeholder="Enter Remarks"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success savePurchaseOrder"
                            id="savePurchaseOrder">SAVE</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Item Modal -->
        <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" data-backdrop="static"
            aria-labelledby="addItemModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                    <div class="modal-header " style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                        <h5 class="modal-title" id="addItemModalLabel">Add Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Item Selection -->
                        <div class="form-group">
                            <label for="selectItem">Item</label>
                            <select class="form-control" id="selectItem">
                                <option value="0" selected>Select Items</option>
                                @foreach (DB::table('bk_expenses_items')->where('deleted', 0)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->description }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Amount -->
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" id="amount" min="0">
                        </div>

                        <!-- Quantity -->
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" class="form-control" id="quantity" min="0">
                        </div>

                        <!-- Total -->
                        <div class="form-group">
                            <label for="total">Total</label>
                            <input type="text" class="form-control" id="total" value="0" readonly>
                        </div>

                        <!-- Add Item Button -->
                        <button class="btn btn-primary btn-block" id="addItemBtn">+ Add Item</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Purchase Order Modal -->
        <div class="modal fade" id="editpurchaseOrderModal" data-backdrop="static" tabindex="-1"
            aria-labelledby="purchaseOrderModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                    <div class="modal-header " style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                        <h5 class="modal-title" id="purchaseOrderModalLabel">Receive Order</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">

                            <div class="form-group col-md-4">
                                <label for="supplier">Supplier</label>
                                <select class="form-control" id="supplier_edit" disabled>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <input type="text" id="receiving_id" hidden>
                                <label for="poReferenceNumber">PO Reference Number</label>
                                <input type="text" class="form-control" id="receivingReferenceNumber_edit" disabled>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="poDate">PO Date</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" id="receivingDate_edit" disabled>

                                </div>
                            </div>
                        </div>

                        <div class="form-row">

                            <div class="form-group col-md-4">
                                <label for="invoice_no">Invoice No.</label>
                                <input type="text" class="form-control" id="invoice_no_edit"
                                    placeholder="eg. INV-0005" value="INV-0001">
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <input type="text" id="receiving_id" hidden>
                                <label for="poReferenceNumber">Date Received</label>
                                <input type="date" class="form-control" id="Date_received"
                                    value="{{ \Carbon\Carbon::today()->toDateString() }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="remarks">Remarks</label>
                                <input type="text" class="form-control" id="remarks_edit"
                                    placeholder="Enter Remarks">
                            </div>
                        </div>
                        {{-- <div class="d-flex justify-content-between p-3">
                            <div></div> <!-- Empty div to push the button -->
                            <button class="btn btn-primary"><i class="fas fa-print"></i> Print</button>
                        </div> --}}
                        <!-- Table -->
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered table-sm purchase_items_table_edit"
                                id="purchase_items_table_edit">
                                <thead>
                                    <tr>
                                        <th>Particulars</th>
                                        <th>Amount</th>
                                        <th>QTY</th>
                                        <th>Total</th>
                                        <th>Receive QTY</th>
                                        <th>Total Receive</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-right font-weight-bold">Total Receive Amount</th>
                                        <th id="grand_total_amount_edit" class="font-weight-bold">0.00</th>
                                        {{-- <th id="grand_total_amount_edit_2" class="font-weight-bold">0.00</th> --}}

                                    </tr>
                                    <tr hidden>
                                        <th colspan="5" class="text-right font-weight-bold">Total Receive</th>

                                        <th id="grand_total_amount_edit_2" class="font-weight-bold">0.00</th>

                                    </tr>
                                    <tr >
                                        <th colspan="5" class="text-right font-weight-bold">Total Amount to Disburse</th>

                                        <th id="grand_total_amount_edit_3" class="font-weight-bold">0.00</th>

                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-right font-weight-bold">Disbursed
                                            Amount</th>
                                        <th id="disbursed_balance" class="font-weight-bold"></th>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-right text-primary font-weight-bold">Disbursed
                                            Balance</th>
                                        <th id="disbursed_balance_balance" class="font-weight-bold text-primary"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="card mt-5">
                            <div class="card-header bg-secondary" style="color: white">
                                <h5 class="card-title mt-1">Journal Entry</h5> <button type="button"
                                    class="btn btn-success rounded ml-4" data-toggle="modal"
                                    data-target="#addaccountsetupModal"
                                    style="background-color: #00581f; height: 30px; font-size: 14.5px; font-family: Arial, sans-serif; border:none; ">
                                    <i class="fas fa-plus-circle mr-1"></i> Add Account
                                </button>


                            </div>
                            <div class="card-body">

                                {{-- <div style="margin-top: 4%">
                                    <button type="button" class="btn btn-success rounded"
                                        style="background-color: #00581f; height: 30px; font-size: 12.5px; font-family: Arial, sans-serif; border:none; ">
                                        <i class="fas fa-plus-circle mr-1"></i> Add Account
                                    </button>
                                </div> --}}

                                <div id="account-entry-container">
                                    {{-- < --}}
                                </div>

                                <div class="form-row mb-3">
                                    <div class="form-group col-md-4">
                                        <input type="text" class="form-control" placeholder="Total"
                                            style=" width: 90%; height: 30px; font-size: 12px; border-radius: 5px; text-align: right"
                                            readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <input type="text" class="form-control" id="total_debit_amount"
                                            style="width: 90%; height: 30px; font-size: 12px; border-radius: 5px;"
                                            readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <input type="text" class="form-control" id="total_credit_amount"
                                            style="width : 85%; height: 30px; font-size: 12px; border-radius: 5px; "
                                            readonly>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end mt-4">

                            <div>
                                <button class="btn btn-success received_receiving_status" value="RECEIVED"
                                    disabled>Received</button>
                                <button class="btn btn-danger">Cancel Order</button>
                                <button class="btn btn-primary process_disbursement_receiving" data-toggle="modal"
                                    data-target="#add_disbursement_po" disabled>Process Disbursement</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="add_disbursement_po" data-backdrop="static" role="dialog"
            aria-labelledby="purchaseOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                    <div class="modal-header " style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                        <h5 class="modal-title" id="purchaseOrderModalLabel">Disbursement</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            id="close_purchase_modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="checked_expenses">
                        <form>
                            <div class="row mb-3">
                                <div class="d-flex justify-content-between w-100">
                                    <div class="col-md-7 d-flex align-items-center shadow">
                                        <input type="text" id="expenses_disburse_po_id" hidden>
                                        <label class="mr-2">Disbursement Type:</label>
                                        <div style="margin-left:10%;">
                                            <input type="checkbox" id="checked_expenses_expenses_disburse_po" checked>
                                            <label for="expenses">Expenses</label>
                                            <input type="checkbox" id="checked_expenses_refund_disburse_po"
                                                style="margin-left:10px;" disabled>
                                            <label for="refund">Students
                                                Refund</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-center">
                                        <label class="mr-2">Date:</label>
                                        <input type="date" id="expenses_date_disburse_po" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <br>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label>Voucher No.</label>
                                    <input type="text" id="expenses_voucher_no_disburse_po" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Reference No.</label>
                                    <input type="text" class="form-control" id="expenses_ref_no_disburse_po"
                                        value="PO 0000">
                                </div>
                                <div class="col-md-3">
                                    <label>Disburse To</label>
                                    <select class="form-control" id="expenses_disbursement_to_disburse_po">
                                        {{-- <option value="">Select Employee/Supplier Name</option>
                                    <option value="Richard Popera">Richard Popera</option>
                                    <option value="Clydev Bacons">Clydev Bacons</option> --}}
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Company/Department</label>
                                    <select class="form-control" id="expenses_department_disburse_po">
                                        <option>Select Company/Department</option>
                                        <option value="Technical Department">Technical Department</option>
                                        <option value="Finance Department">Finance Department</option>

                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label>Payment Type:</label>
                                    <div>
                                        <input type="radio" name="payment" id="expenses_cash_disburse_po" checked>
                                        <label for="cash">CASH</label>
                                        <input type="radio" name="payment" id="expenses_cheque_disburse_po"
                                            style="margin-left:10px;">
                                        <label for="cheque">CHEQUE</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label>Amount</label>
                                    <input type="text" class="form-control" id="expenses_amount_disburse_po"
                                        value="PHP 0.00">
                                </div>
                                <div class="col-md-2">
                                    <label>Bank</label>
                                    <select class="form-control" id="expenses_bank_disburse_po">
                                        <option>Select Bank</option>
                                        <option value="Union Bank">Union Bank</option>
                                        <option value="BPI">BPI</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Cheque No.</label>
                                    <input type="text" class="form-control" id="expenses_cheque_no_disburse_po"
                                        placeholder="Select Bank">
                                </div>
                                <div class="col-md-3">
                                    <label>Cheque Date.</label>
                                    <input type="date" class="form-control" id="expenses_cheque_date_disburse_po">
                                </div>
                            </div>
                            <div class="row mb-3">
                                {{-- <div class="col-md-6">
                <label>Cheque Date:</label>
                <input type="date" class="form-control">
            </div> --}}
                                <div class="col-md-12">
                                    <label>Remarks/Explanation</label>
                                    <input type="text" class="form-control" id="expenses_remarks_disburse_po"
                                        placeholder="Enter Description Here">
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                <h5 class="m-0">ITEMS</h5>
                                {{-- <button type="button" class="btn btn-outline-success btn-xsm ml-2" id="add_item"
                                data-toggle="modal" data-target="#addItemModalforSelectedItem"><i
                                    class="fas fa-plus"></i></button> --}}
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered mt-2 expensesItemsTable_disburse_po"
                                    id="expensesItemsTable_disburse_po">
                                    <thead>
                                        <tr>
                                            <th>Particulars</th>
                                            <th>Amount</th>
                                            <th>QTY</th>
                                            <th>Total Amount</th>
                                            {{-- <th colspan="2" style="text-align: center">Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-right"><strong>TOTAL</strong></td>
                                            <td colspan="1" id="grand_total_amount__disburse_po"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="card mt-5">
                                <div class="card-header bg-secondary" style="color: white">
                                    <h5 class="card-title mt-1">Journal Entry</h5> <button type="button"
                                        class="btn btn-success rounded ml-4" data-toggle="modal"
                                        data-target="#addaccountsetupModal_po_disburse"
                                        style="background-color: #00581f; height: 30px; font-size: 14.5px; font-family: Arial, sans-serif; border:none; ">
                                        <i class="fas fa-plus-circle mr-1"></i> Add Account
                                    </button>


                                </div>
                                <div class="card-body">

                                    {{-- <div style="margin-top: 4%">
                                        <button type="button" class="btn btn-success rounded"
                                            style="background-color: #00581f; height: 30px; font-size: 12.5px; font-family: Arial, sans-serif; border:none; ">
                                            <i class="fas fa-plus-circle mr-1"></i> Add Account
                                        </button>
                                    </div> --}}

                                    <div id="po-disburse-account-entry-container">
                                        {{-- < --}}
                                    </div>

                                    <div class="form-row mb-3" id="po-disburse-total-row">
                                        <div class="form-group col-md-4">
                                            <input type="text" class="form-control" placeholder="Total"
                                                style=" width: 90%; height: 30px; font-size: 12px; border-radius: 5px; text-align: right"
                                                readonly>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <input type="text" class="form-control" id="po_total_debit_amount"
                                                style="width: 90%; height: 30px; font-size: 12px; border-radius: 5px; "
                                                readonly>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <input type="text" class="form-control" id="po_total_credit_amount"
                                                style="width : 85%; height: 30px; font-size: 12px; border-radius: 5px; "
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success saveDisbursement" id="saveDisbursement_disburse_po"
                            disabled>SAVE</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit_receiving_history_Modal_view" data-backdrop="static" tabindex="-1"
            aria-labelledby="purchaseOrderModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                    <div class="modal-header " style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                        <h5 class="modal-title" id="purchaseOrderModalLabel">Received History</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">

                            <div class="form-group col-md-4">
                                <label for="supplier">Supplier</label>
                                <select class="form-control" id="receiving_history_supplier_view" disabled>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <input type="text" id="receiving_history_id" hidden>
                                <label for="poReferenceNumber">PO Reference Number</label>
                                <input type="text" class="form-control" id="receiving_historyReferenceNumber_edit"
                                    disabled>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="poDate">PO Date</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" id="receiving_historyDate_edit" disabled>

                                </div>
                            </div>
                        </div>

                        <div class="form-row">

                            <div class="form-group col-md-4">
                                <label for="invoice_no">Invoice No.</label>
                                <input type="text" class="form-control" id="receiving_history_invoice_no_edit"
                                    placeholder="eg. INV-0005">
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <input type="text" id="receiving_id" hidden>
                                <label for="poReferenceNumber">Date Received</label>
                                <input type="date" class="form-control" id="receivingDate_received"
                                    value="{{ \Carbon\Carbon::today()->toDateString() }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="remarks">Remarks</label>
                                <input type="text" class="form-control" id="receiving_history_remarks_edit"
                                    placeholder="Enter Remarks">
                            </div>
                        </div>

                        <br>
                        <br>
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered table-sm receiving_history_items_table_view"
                                id="receiving_history_items_table_view">
                                <thead>
                                    <tr>
                                        <th>Particulars</th>
                                        <th>Amount</th>
                                        <th>QTY</th>
                                        <th>Total</th>
                                        <th>Receive QTY</th>
                                        <th>Total Receive</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-right font-weight-bold">Total</th>
                                        <th id="receiving_grand_total_amount_view" class="font-weight-bold"></th>

                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="card mt-5">
                            <div class="card-header bg-secondary" style="color: white">
                                <h5 class="card-title mt-1">Journal Entry</h5>

                            </div>
                            <div class="card-body">

                                {{-- <div style="margin-top: 4%">
                                <button type="button" class="btn btn-success rounded"
                                    style="background-color: #00581f; height: 30px; font-size: 12.5px; font-family: Arial, sans-serif; border:none; ">
                                    <i class="fas fa-plus-circle mr-1"></i> Add Account
                                </button>
                            </div> --}}

                                <div id="po-disburse-account-entry-container-edit">
                                    {{-- < --}}
                                </div>

                              
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit_receiving_history_Modal" data-backdrop="static" tabindex="-1"
            aria-labelledby="purchaseOrderModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                    <div class="modal-header " style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                        <h5 class="modal-title" id="purchaseOrderModalLabel">Confirm Void</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- <div class="form-row"> --}}
                        <div>

                            <div class="form-group col-md-12">
                                <label for="supplier" class="font-weight-bold"
                                    style="font-size: 1rem !important;">Username</label>
                                <input type="text" class="form-control" id="voiduname">
                            </div>

                            <div class="form-group col-md-12">
                                <input type="text" id="receiving_history_id" hidden>
                                <label for="poReferenceNumber" class="font-weight-bold"
                                    style="font-size: 1rem !important;">Password</label>
                                <input type="text" class="form-control" id="voidpword">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="poDate" class="font-weight-bold"
                                    style="font-size: 1rem !important;">Remarks</label>
                                <div class="input-group">
                                    <textarea style="width:100%;" autocomplete="off" id="voidremarks" maxlength="25"></textarea>

                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="table-responsive mt-4" hidden>
                            <table class="table table-bordered table-sm receiving_history_items_table_edit"
                                id="receiving_history_items_table_edit">
                                <thead>
                                    <tr>
                                        <th>Particulars</th>
                                        <th>Amount</th>
                                        <th>QTY</th>
                                        <th>Total</th>
                                        <th>Receive QTY</th>
                                        <th>Total Receive</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-right font-weight-bold">Total</th>
                                        <th id="receiving_grand_total_amount_edit" class="font-weight-bold"></th>

                                    </tr>
                                </tfoot>
                            </table>
                        </div>


                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button id="btnconfirm" type="button" class="btn btn-primary">Confirm</button>
                    </div>
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

            $('#selectItem').change(function() {
                let itemId = $(this).val();
                $.ajax({
                    url: '/bookkeeper/selected_item/fetch',
                    type: 'GET',
                    data: {
                        itemId: itemId
                    },
                    success: function(data) {

                        if (data.length > 0) {
                            let item = data[0];
                            $('#amount').val(item.amount);
                            $('#quantity').val(item.qty);
                            let total = item.amount * item.qty;
                            $('#total').val(total);
                        }
                    }
                });
            });

            $("#addItemBtn").click(function(e) {
                e.preventDefault();
                var selectItemID = $("#selectItem").val();
                var selectItem = $("#selectItem option:selected").text();
                var amount = $("#amount").val();
                var quantity = $("#quantity").val();
                var total = $("#total").val();

                if (selectItem.trim() == "" || amount.trim() == "" || quantity.trim() == "" || total
                    .trim() == "") {
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: 'All fields are required',
                    });

                    $('body').removeClass('modal-open');
                    $('.modal-backdrop')
                        .remove(); // Manually removes lingering backdrop 



                    return;
                }

                var table = $("#purchase_items_table");
                // var row = $("<tr data-id='" + (table.find("tbody tr").length + 1) + "'>");

                var row = $("<tr class='appended-row'>");

                row.append($("<td data-id='" + selectItemID + "'>").text(selectItem));
                row.append($("<td>").text(amount));
                row.append($("<td>").text(quantity));
                row.append($("<td>").text(total));

                table.find("tbody").append(row);

                let rows = table.find("tbody tr");
                var grand_total = 0;
                rows.each(function(index) {
                    var rowTotal = parseFloat($(this).find("td:eq(3)").text().replace(
                        /,/g, ""));
                    if (!isNaN(rowTotal)) {
                        grand_total += rowTotal;
                    }
                });
                $("#grand_total_amount").text(parseFloat(grand_total).toLocaleString());

                // row.append($("<td>").html(

                //     // '<button type="button" class="btn btn-danger btn-xs remove-append-row"><i class="far fa-trash-alt text-danger"></i></button>'
                //     '<a href="javascript:void(0)" class="text-center align-middle ml-2 edit-append-row"><i class="far fa-edit text-primary"></i></a>'
                // ));
                row.append($("<td>").html(

                    // '<button type="button" class="btn btn-danger btn-xs remove-append-row"><i class="far fa-trash-alt text-danger"></i></button>'
                    '<a href="javascript:void(0)" class="text-center text-danger align-middle ml-2 remove-append-row">Cancel</button>'
                ));

                table.find("tbody").on('click', '.remove-append-row', function() {
                    $(this).closest('tr').remove();
                    let rows = table.find("tbody tr");
                    var grand_total = 0;
                    rows.each(function(index) {
                        var rowTotal = parseFloat($(this).find("td:eq(3)").text().replace(
                            /,/g, ""));
                        if (!isNaN(rowTotal)) {
                            grand_total += rowTotal;
                        }
                    });
                    $("#grand_total_amount").text(parseFloat(grand_total).toLocaleString());
                });
                table.find("tbody").append(row);

                Toast.fire({
                    type: 'success',
                    title: 'New Item added successfully'
                })

                $("#selectItem").val("").trigger('change');
                $("#amount").val("");
                $("#quantity").val("");
                $("#total").val("");

                $('#addItemModal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop')
                    .remove(); // Manually removes lingering backdrop 

            });

            $("#savePurchaseOrder").click(function(e) {
                e.preventDefault();

                var poRefNumber = $("#poRefNumber").val();
                var select_supplier = $("#select_supplier").val();
                var podate = $("#podate").val();
                var grand_total_amount = $("#grand_total_amount").text();

                var tableData = [];
                $("#purchase_items_table").find("tbody tr").each(function() {
                    if ($(this).is(":visible")) {
                        var itemId = $(this).find("td:eq(0)").attr('data-id');
                        var poItem = $(this).find("td:eq(0)").text().trim();
                        var amount = $(this).find("td:eq(1)").text().trim();
                        var quantity = $(this).find("td:eq(2)").text().trim();
                        var total = $(this).find("td:eq(3)").text().trim();

                        if (itemId && poItem && amount && quantity && total) {
                            tableData.push({
                                itemId: itemId,
                                poItem: poItem,
                                amount: amount,
                                quantity: quantity,
                                total: total
                            });
                        }
                    }
                });

                var poremarks = $("#poremarks").val();



                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/purchase_order/create',
                    data: {
                        poRefNumber: poRefNumber,
                        select_supplier: select_supplier,
                        podate: podate,
                        po_items: tableData,
                        poremarks: poremarks,
                        grand_total_amount: grand_total_amount


                    },
                    success: function(data) {
                        if (data[0].status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data[0].message
                            })
                        } else if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully created'
                            })

                            var table = $("#purchase_items_table");
                            table.find("tbody .appended-row").remove();
                            $("#poRefNumber").val("");
                            $("#select_supplier").val("").trigger('change');
                            $("#podate").val("");
                            $("#poremarks").val("");
                            $("#grand_total_amount").text('0.00');
                            po_supplierTable()
                            $("#add_purchase_order").modal('hide');
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop')
                                .remove(); // Manually removes lingering backdrop


                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });

            });

            $("#close_purchase_modal").click(function() {
                var table = $("#purchase_items_table");
                table.find("tbody .appended-row").remove();
                $("#select_supplier").val("").trigger('change');
                $("#podate").val("");
                $("#poremarks").val("");
                $("#grand_total_amount").text('0.00');
            });

            po_supplierTable()

            function po_supplierTable() {

                $("#receiving_accounting_supplier_table").DataTable({
                    destroy: true,
                    // data:temp_subj,
                    // bInfo: true,
                    autoWidth: false,
                    paging: false,
                    info: false,
                    // lengthChange: true,
                    // stateSave: true,
                    // serverSide: true,
                    // processing: true,
                    ajax: {
                        url: '/bookkeeper/receiving/fetch',
                        type: 'GET',
                        // dataSrc: function(json) {
                        //     var data = json.filter(item => item.dstatus === 'POSTED');
                        //     return data;
                        // }
                        dataSrc: function(json) {
                            return json;
                        }
                    },
                    columns: [{
                            "data": "refno"
                        },
                        {
                            "data": "suppliername"
                        },
                        {
                            "data": "remarks"
                        },
                        {
                            "data": "totalamount"
                        },
                        {
                            "data": "postdatetime"
                        },
                        {
                            "data": "dstatus"
                        },
                        // {
                        //     "data": null
                        // },

                    ],
                    columnDefs: [

                        {
                            'targets': 0,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                // $(td).html(rowData.refno).addClass(
                                //     'align-middle');

                                $(td).addClass('clickable-td edit_purchase_order').attr('id',
                                    'edit_supplier').attr('data-id', rowData.id).html(rowData
                                    .refno.split(
                                        ' ')[0]).css('cursor', 'pointer');
                            }
                        },


                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.suppliername).addClass(
                                    'align-middle');

                                $(td).addClass('clickable-td edit_purchase_order').attr('id',
                                    'edit_supplier').attr('data-id', rowData.id).html(
                                    rowData.suppliername.split(' ')[0]).css('cursor', 'pointer');
                            }
                        },

                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var text = rowData.remarks === null ? '' : rowData.remarks;
                                $(td).html(text).addClass('align-middle');

                                $(td).addClass('clickable-td edit_purchase_order').attr('id',
                                    'edit_supplier').attr('data-id', rowData.id).html(text
                                    .split(' ')[0]).css('cursor', 'pointer');

                            }
                        },

                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                // $(td).html(rowData.totalamount).addClass(
                                //     'align-middle');


                                $(td).addClass('clickable-td edit_purchase_order').attr('id',
                                    'edit_supplier').attr('data-id', rowData.id).html(rowData
                                    .totalamount.split(
                                        ' ')[0]).css('cursor', 'pointer');
                            }
                        },

                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                // $(td).html(rowData.postdatetime.split(' ')[0]).addClass(
                                //     'align-middle');

                                $(td).addClass('clickable-td edit_purchase_order').attr('id',
                                    'edit_supplier').attr('data-id', rowData.id).html(rowData
                                    .postdatetime.split(' ')[0]).css('cursor', 'pointer');

                            }
                        },

                        {
                            'targets': 5,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                // $(td).html(rowData.dstatus).addClass(
                                //     'align-middle');

                                $(td).addClass('clickable-td edit_purchase_order').attr('id',
                                    'edit_supplier').attr('data-id', rowData.id).html(rowData
                                    .dstatus.split(
                                        ' ')[0]).css('cursor', 'pointer');
                            }
                        },


                        // {
                        //     'targets': 6,
                        //     'orderable': false,
                        //     'createdCell': function(td, cellData, rowData, row, col) {

                        //         var edit_button =
                        //             '<button type="button" class="btn btn-sm btn-primary edit_purchase_order" id="edit_supplier" data-id="' +
                        //             rowData.id +
                        //             '" ><i class="far fa-edit"></i></button>';
                        //         var delete_button =
                        //             '<button type="button" class="btn btn-sm btn-danger delete_purchase_orderss" id="delete_supplier" data-id="' +
                        //             rowData.id +
                        //             '"><i class="far fa-trash-alt"></i></button>';
                        //         $(td)[0].innerHTML = edit_button + ' ' + delete_button;
                        //         $(td).addClass('text-center align-middle');
                        //     }
                        // }

                    ],
                    createdRow: function(row, data, dataIndex) {
                        $(row).addClass('hoverable-row');
                    },
                    // lengthMenu: [
                    //     [10, 25, 50, 100],
                    //     [10, 25, 50, 100]
                    // ],
                    // pageLength: 10,
                    // dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                    //     "<'row'<'col-sm-12'tr>>" +
                    //     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

                });
            }


            $(document).on('click', '.edit_purchase_order', function() {

                var po_id = $(this).attr('data-id')

                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/receiving/edit',
                    data: {
                        po_id: po_id
                    },
                    success: function(response) {

                        var receiving_selected = response.receiving;
                        var receiving_items = response.receiving_items;
                        var all_supplier = response.purchasing_supplier;
                        var total_disbursed_bal = response.receiving_disbursements_balance;
                        var total_amount_receiving = response.total_amount_receiving;
                        var total_amount_receiving_received = response.total_amount_receiving_received;


                        if (receiving_selected[0].rstatus == "RECEIVED") {
                            $(".process_disbursement_receiving").removeAttr('disabled');

                        } else {
                            $(".process_disbursement_receiving").attr('disabled', 'disabled');

                        }


                        $("#receiving_id").val(receiving_selected[0].id);

                        $(".process_disbursement_receiving").attr("data-id", receiving_selected[
                            0].id);

                        $("#receivingReferenceNumber_edit").val(receiving_selected[0].refno);
                        // $("#poDate_edit").val(po_selected[0].postdatetime.split(' ')[0]);
                        // $("#poDate_edit").val(po_selected[0].postdatetime.split(' ')[0]);
                        $("#receivingDate_edit").val(receiving_selected[0].postdatetime.split(
                            ' ')[0]);
                        // $("#remarks_edit").val(receiving_selected[0].remarks);
                        // $("#invoice_no_edit").val(receiving_selected[0].invoiceno);
                        $("#receivingDate_received").val(receiving_selected[0].datedelivered ?
                            receiving_selected[0].datedelivered.split(' ')[0] :
                            '{{ \Carbon\Carbon::today()->toDateString() }}');

                        $("#supplier_edit").empty().trigger('change');
                        $("#supplier_edit").append(
                            '<option value="" selected disabled>Select Supplier</option>'
                        );
                        all_supplier.forEach(all_supplier => {
                            if (all_supplier.id == receiving_selected[0]
                                .supplierid) {
                                $("#supplier_edit").append(
                                    `<option value="${all_supplier.id}" selected>${all_supplier.suppliername}</option>`
                                );
                            } else {
                                $("#supplier_edit").append(
                                    `<option value="${all_supplier.id}">${all_supplier.suppliername}</option>`
                                );
                            }
                        });

                        var disbursedBalance = total_disbursed_bal[0]?.totalamount ? parseFloat(
                            total_disbursed_bal[0].totalamount.replace(/,/g, '')) : 0;
                        console.log('totalDisbursedBalance: ', disbursedBalance);

                        $("#disbursed_balance").text(disbursedBalance >= 1000 ? disbursedBalance
                            .toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",") :
                            disbursedBalance.toFixed(0));

                        //     var grandBalance = total_amount_receiving[0]?.totalamount ? parseFloat(
                        //         total_amount_receiving[0].totalamount.replace(/,/g, '')) : 0;
                        // console.log('totalDisbursedBalance: ', grandBalance);

                        // $("#grand_total_amount_edit_2").text(grandBalance >= 1000 ? grandBalance
                        //     .toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",") :
                        //     grandBalance.toFixed(0));

                        $("#grand_total_amount_edit_2").text(total_amount_receiving >= 1000 ?
                        total_amount_receiving
                            .replace(/\B(?=(\d{3})+(?!\d))/g, ",") :
                            total_amount_receiving);

                        $("#grand_total_amount_edit_3").text(total_amount_receiving_received >= 1000 ?
                        total_amount_receiving_received
                            .replace(/\B(?=(\d{3})+(?!\d))/g, ",") :
                            total_amount_receiving_received);

                        // $("#grand_total_amount_edit").text(receiving_selected[0].totalamount);

                        var disbursedBalanceValue = parseFloat($("#disbursed_balance").text().replace(/,/g, ''));
                        var totalAmountReceivingReceivedValue = parseFloat($("#grand_total_amount_edit_3").text().replace(/,/g, ''));

                        var result = disbursedBalanceValue - totalAmountReceivingReceivedValue;
                        // $("#disbursed_balance_balance").text(result >= 1000 ? result.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",") : `(${Math.abs(result).toFixed(0)})`);

                        $("#disbursed_balance_balance").text(Math.abs(result).toFixed(0).replace(/[^\d]/g, ""));


                        po_itemsTabless()

                        function po_itemsTabless() {

                            $("#purchase_items_table_edit").DataTable({
                                destroy: true,
                                autoWidth: false,
                                data: receiving_items,
                                info: false,
                                lengthChange: false,
                                searching: false,
                                paging: false,
                                columns: [{
                                        "data": "description"
                                    },
                                    {
                                        "data": "amount"
                                    },
                                    {
                                        "data": "qty"
                                    },

                                    {
                                        "data": "total"
                                    },
                                    {
                                        "data": "receivedqty"
                                    },
                                    {
                                        "data": "rtotal"
                                    }
                                ],
                                columnDefs: [{
                                        'targets': 0,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html(
                                                    `<span data-id="${rowData.itemid}">${rowData.description}</span>`
                                                )
                                                .addClass('align-middle');
                                        }
                                    },
                                    {
                                        'targets': 1,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html(rowData.amount).addClass(
                                                'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 2,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html(rowData.qty).addClass(
                                                'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 3,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {

                                            $(td).html(rowData.total)
                                                .addClass(
                                                    'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 4,
                                        'orderable': false,
                                        'width': '14%',

                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html(
                                                '<input type="number" class="form-control" id="receivedqty" style="width: 100%" value="' +
                                                0 +
                                                '" min="0" max="' +
                                                parseInt(rowData.qty) + '">'
                                            );
                                            $(td).find('input').on('input',
                                                function(e) {
                                                    var receivedqty = $(
                                                        this).val();
                                                    if (receivedqty >
                                                        parseInt(rowData
                                                            .qty)) {
                                                        e.preventDefault();
                                                        $(this).val(rowData
                                                            .qty);
                                                    } else {
                                                        // var amount = rowData
                                                        //     .amount;
                                                        // var rtotal =
                                                        //     receivedqty *
                                                        //     amount;
                                                        // $(td).closest('tr')
                                                        //     .find(
                                                        //         'td:eq(5)')
                                                        //     .text(rtotal);

                                                        var amount = rowData
                                                            .amount;
                                                        var rtotal =
                                                            receivedqty *
                                                            amount;
                                                        $(td).closest('tr')
                                                            .find(
                                                                'td:eq(5)')
                                                            .text(rtotal
                                                                .toLocaleString()
                                                            );

                                                        var total = 0;
                                                        $('#purchase_items_table_edit tbody tr')
                                                            .each(
                                                                function() {
                                                                    total +=
                                                                        parseFloat(
                                                                            $(
                                                                                this
                                                                            )
                                                                            .find(
                                                                                'td:eq(5)'
                                                                            )
                                                                            .text()
                                                                            .replace(
                                                                                /,/g,
                                                                                ''
                                                                            )
                                                                        );
                                                                });
                                                        $('#grand_total_amount_edit')
                                                            .text(total
                                                                .toLocaleString()
                                                            );

                                                        // $('#grand_total_amount_edit').text($('#purchase_items_table_edit tbody tr').map(function(){ 
                                                        //     return parseFloat($(this).find('td:eq(5)').text().replace(/,/g, ''));
                                                        // }).get().reduce(function(a, b){
                                                        //     return a + b;
                                                        // }, 0).toLocaleString());
                                                    }
                                                });
                                        }
                                    },
                                    {
                                        'targets': 5,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html('<span id="rtotal">' +
                                                    0 + '</span>')
                                                .addClass(
                                                    'align-middle');
                                        }
                                    }
                                ]
                            });
                        }


                        // po_itemsTabless()

                        // function po_itemsTabless() {

                        //     $("#purchase_items_table_edit").DataTable({
                        //         destroy: true,
                        //         autoWidth: false,
                        //         data: receiving_items,
                        //         info: false,
                        //         lengthChange: false,
                        //         searching: false,
                        //         paging: false,
                        //         columns: [{
                        //                 "data": "description"
                        //             },
                        //             {
                        //                 "data": "amount"
                        //             },
                        //             {
                        //                 "data": "qty"
                        //             },

                        //             {
                        //                 "data": "total"
                        //             },
                        //             {
                        //                 "data": "receivedqty"
                        //             },
                        //             {
                        //                 "data": "rtotal"
                        //             }
                        //         ],
                        //         columnDefs: [{
                        //                 'targets': 0,
                        //                 'orderable': false,
                        //                 'createdCell': function(td, cellData,
                        //                     rowData, row, col) {
                        //                     $(td).html(
                        //                             `<span data-id="${rowData.itemid}">${rowData.description}</span>`
                        //                         )
                        //                         .addClass('align-middle');
                        //                 }
                        //             },
                        //             {
                        //                 'targets': 1,
                        //                 'orderable': false,
                        //                 'createdCell': function(td, cellData,
                        //                     rowData, row, col) {
                        //                     $(td).html(rowData.amount).addClass(
                        //                         'align-middle');
                        //                 }
                        //             },
                        //             {
                        //                 'targets': 2,
                        //                 'orderable': false,
                        //                 'createdCell': function(td, cellData,
                        //                     rowData, row, col) {
                        //                     $(td).html(rowData.qty).addClass(
                        //                         'align-middle');
                        //                 }
                        //             },
                        //             {
                        //                 'targets': 3,
                        //                 'orderable': false,
                        //                 'createdCell': function(td, cellData,
                        //                     rowData, row, col) {
                        //                     $(td).html(rowData.total)
                        //                         .addClass(
                        //                             'align-middle');
                        //                 }
                        //             },
                        //             {
                        //                 'targets': 4,
                        //                 'orderable': false,
                        //                 'width': '14%',

                        //                 'createdCell': function(td, cellData,
                        //                     rowData, row, col) {
                        //                     $(td).html(
                        //                         '<input type="number" class="form-control" id="receivedqty" style="width: 100%" value="' +
                        //                         rowData.receivedqty +
                        //                         '" min="0" max="' +
                        //                         parseInt(rowData.qty) + '">'
                        //                     );
                        //                     $(td).find('input').on('input',
                        //                         function(e) {
                        //                             var receivedqty = $(
                        //                                 this).val();
                        //                             if (receivedqty >
                        //                                 parseInt(rowData
                        //                                     .qty)) {
                        //                                 e.preventDefault();
                        //                                 $(this).val(rowData
                        //                                     .qty);
                        //                             } else {
                        //                                 var amount = rowData
                        //                                     .amount;
                        //                                 var rtotal =
                        //                                     receivedqty *
                        //                                     amount;
                        //                                 $(td).closest('tr')
                        //                                     .find(
                        //                                         'td:eq(5)')
                        //                                     .text(rtotal);
                        //                             }
                        //                         });
                        //                 }
                        //             },
                        //             {
                        //                 'targets': 5,
                        //                 'orderable': false,
                        //                 'createdCell': function(td, cellData,
                        //                     rowData, row, col) {
                        //                     $(td).html('<span id="rtotal">' +
                        //                             rowData.rtotal + '</span>')
                        //                         .addClass(
                        //                             'align-middle');
                        //                 }
                        //             }
                        //         ]
                        //     });
                        // }




                    }
                });

            });

            $(document).on('click', '.delete_purchase_order', function() {
                var deletepoId = $(this).attr('data-id')
                Swal.fire({
                    text: 'Are you sure you want to remove purchase order?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Remove'
                }).then((result) => {
                    if (result.value) {
                        delete_po(deletepoId)
                    }
                })
            });

            function delete_po(deletepoId) {
                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/purchase_order/delete',
                    data: {
                        deletepoId: deletepoId

                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully deleted'
                            })

                            po_supplierTable();
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }

            var minDateFilter = '';
            var maxDateFilter = '';

            // Initialize DateRangePicker
            $('#dateRange').daterangepicker({
                opens: 'right',
                autoUpdateInput: false,
                // startDate: moment(),
                // endDate: moment(),
                locale: {
                    cancelLabel: 'Clear',
                    format: 'YYYY-MM-DD'
                }
            });

            $('#dateRange').attr('placeholder', 'Select date range');

            $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format(
                    'YYYY-MM-DD'));
                minDateFilter = picker.startDate;
                maxDateFilter = picker.endDate;
                $('#receiving_accounting_supplier_table').DataTable().draw(); // trigger redraw
            });

            $('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                minDateFilter = '';
                maxDateFilter = '';
                $('#receiving_accounting_supplier_table').DataTable().draw(); // trigger redraw
            });

            //supplier name filter
            var selectedSupplier = '0';

            $('#supplierName').on('change', function() {
                selectedSupplier = $(this).val();
                $('#receiving_accounting_supplier_table').DataTable().draw(); // trigger redraw
            });

            var selectedPO_status = '0';

            $('#poStatus').on('change', function() {
                selectedPO_status = $(this).val();
                $('#receiving_accounting_supplier_table').DataTable().draw(); // trigger redraw
            });



            // Custom date filter
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                if (settings.nTable.id !== 'receiving_accounting_supplier_table')
                    return true; // filter only the selected table

                const dateStr = data[4]; // date column (already formatted to 'YYYY-MM-DD' by createdCell)
                const date = moment(dateStr, 'YYYY-MM-DD');

                const supplierId = data[1];

                const postatusId = data[5];

                // Date filter logic
                if (minDateFilter && maxDateFilter) {
                    if (!date.isSameOrAfter(minDateFilter) || !date.isSameOrBefore(maxDateFilter)) {
                        return false; // Exclude if the date is out of the range
                    }
                }

                // Supplier filter logic
                if (selectedSupplier !== '0' && supplierId !== selectedSupplier) {
                    return false; // Exclude if the supplier does not match the selected one
                }

                if (selectedPO_status !== '0' && postatusId !== selectedPO_status) {
                    return false;
                }

                return true; // Include the row if it matches both filters


            });

            // var invoice_no_edit = $('#invoice_no_edit').val();

            // if (invoice_no_edit == '') {
            //     $('.received_receiving_status').attr('disabled', true);
            // } else {
            //     $('.received_receiving_status').removeAttr('disabled');
            // }

            // workinggggggggggggg
            // $('.btn-success[data-target="#addaccountsetupModal"]').click(function() {
            //     const newEntryHtml = `
        //             <div class="form-row account-entry mt-4">
        //                 <div class="form-group col-md-4">
        //                     <label style="font-weight: 600; font-size: 13px;">Account</label>
        //                     <select class="form-control account-select"
        //                         style="width: 90%; height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
        //                         <option value="">Select Account</option>
        //                         ${@json($coa).map(item => 
        //                             `<option value="${item.id}">${item.code} - ${item.account_name}</option>`
        //                         ).join('')}
        //                     </select>
        //                 </div>
        //                 <div class="form-group col-md-4">
        //                     <label style="font-weight: 600; font-size: 13px;">Debit Account</label>
        //                     <input type="text" class="form-control debit-input" value="0.00"
        //                         style="width: 90%; height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
        //                 </div>
        //                 <div class="form-group col-md-4 d-flex align-items-center">
        //                     <div style="width: 100%;">
        //                         <label style="font-weight: 600; font-size: 13px;">Credit Account</label>
        //                         <div class="input-group">
        //                             <input type="text" class="form-control credit-input" value="0.00"
        //                                 style="height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
        //                             <div class="input-group-append">
        //                                 <button type="button" class="btn btn-sm ml-3 remove-account-entry" style="background-color: transparent;">
        //                                     <i class="fas fa-trash-alt text-danger"></i>
        //                                 </button>
        //                             </div>
        //                         </div>
        //                     </div>
        //                 </div>
        //             </div>
        //         `;

            //     $('#account-entry-container').append(newEntryHtml);
            //     // Add event listeners to the newly created inputs
            //     const newEntry = $('#account-entry-container .account-entry').last();
            //     newEntry.find('.debit-input, .credit-input').on('input', function() {
            //         const row = $(this).closest('.account-entry');
            //         const debitInput = row.find('.debit-input');
            //         const creditInput = row.find('.credit-input');

            //         // If this input has a value (other than 0.00), clear the other one
            //         if ($(this).val() !== '0.00' && $(this).val() !== '') {
            //             if ($(this).hasClass('debit-input')) {
            //                 creditInput.val('0.00');
            //             } else {
            //                 debitInput.val('0.00');
            //             }
            //         }
            //     });
            // });
            ///////////////////////////////////////////////////

            let grandTotalDisplayed = false;

            $('#editpurchaseOrderModal').on('hidden.bs.modal', function(e) {
                $('.account-entry').remove();
                grandTotalDisplayed = false; // Reset the flag when modal is hidden
                $('#grand_total_amount_edit').text('0.00');
            });

            $('.btn-success[data-target="#addaccountsetupModal"]').click(function() {
                let accountSelectId = 'account-select-po-disburse-add-' + Date.now();
                const grandTotal = parseFloat($('#grand_total_amount_edit').text().replace(/,/g, ''));
                const newEntryHtml = `
                        <div class="form-row account-entry mt-4">
                            <div class="form-group col-md-4">
                                <label style="font-weight: 600; font-size: 13px;">Account</label>
                                <select class="form-control account-select" id="${accountSelectId}"
                                    style="width: 90%; height: 30px; font-size: 12px; border-radius: 5px; ">
                                    <option value="">Select Account</option>
                                    @php
                                        $coa = DB::table('chart_of_accounts')->get();
                                    @endphp
                                    @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                        <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
                                        @foreach (DB::table('bk_sub_chart_of_accounts')->where('deleted', 0)->where('coaid', $coa->id)->get() as $subcoa)
                                            <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcoa->sub_code }} - 
                                                {{ $subcoa->sub_account_name }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label style="font-weight: 600; font-size: 13px;">Debit Account</label>
                                <input type="text" class="form-control debit-input" value="${grandTotalDisplayed ? '0.00' : grandTotal}"
                                    style="width: 90%; height: 30px; font-size: 12px; border-radius: 5px; ">
                            </div>
                            <div class="form-group col-md-4 d-flex align-items-center">
                                <div style="width: 100%;">
                                    <label style="font-weight: 600; font-size: 13px;">Credit Account</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control credit-input" value="0.00"
                                            style="height: 30px; font-size: 12px; border-radius: 5px;">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-sm ml-3 remove-account-entry" style="background-color: transparent;">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                grandTotalDisplayed = true;

                $('#account-entry-container').append(newEntryHtml);
                setTimeout(function() {
                    $(`#${accountSelectId}`).select2({
                        placeholder: "Select Account",
                        allowClear: true,
                        theme: 'bootstrap4',
                        width: '100%'
                    }).on('select2:open', function(e) {
                        // Force high z-index for dropdown
                        $('.select2-container').css('z-index', 99999);
                        $('.select2-dropdown').css('z-index', 99999);
                    });
                }, 100);
                // Add event listeners to the newly created inputs
                const newEntry = $('#account-entry-container .account-entry').last();
                newEntry.find('.debit-input, .credit-input').on('input', function() {
                    const row = $(this).closest('.account-entry');
                    const debitInput = row.find('.debit-input');
                    const creditInput = row.find('.credit-input');

                    // If this input has a value (other than 0.00), clear the other one
                    if ($(this).val() !== '0.00' && $(this).val() !== '') {
                        if ($(this).hasClass('debit-input')) {
                            creditInput.val('0.00');
                        } else {
                            debitInput.val('0.00');
                        }
                    }
                });
            });


            // $('.btn-success[data-target="#addaccountsetupModal"]').click(function() {
            //     let accountSelectId = 'account-select-po-disburse-add-' + Date.now();
            //     const newEntryHtml = `
        //             <div class="form-row account-entry mt-4">
        //                 <div class="form-group col-md-4">
        //                     <label style="font-weight: 600; font-size: 13px;">Account</label>
        //                     <select class="form-control account-select" id="${accountSelectId}"
        //                         style="width: 90%; height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
        //                         <option value="">Select Account</option>
        //                         @php
            //                             $coa = DB::table('chart_of_accounts')->get();
            //
        @endphp
        //                         @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
        //                             <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
        //                             @foreach (DB::table('bk_sub_chart_of_accounts')->where('deleted', 0)->where('coaid', $coa->id)->get() as $subcoa)
        //                                 <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcoa->sub_code }} - 
        //                                     {{ $subcoa->sub_account_name }}</option>
        //                             @endforeach
        //                         @endforeach
        //                     </select>
        //                 </div>
        //                 <div class="form-group col-md-4">
        //                     <label style="font-weight: 600; font-size: 13px;">Debit Account</label>
        //                     <input type="text" class="form-control debit-input" value="0.00"
        //                         style="width: 90%; height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
        //                 </div>
        //                 <div class="form-group col-md-4 d-flex align-items-center">
        //                     <div style="width: 100%;">
        //                         <label style="font-weight: 600; font-size: 13px;">Credit Account</label>
        //                         <div class="input-group">
        //                             <input type="text" class="form-control credit-input" value="0.00"
        //                                 style="height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
        //                             <div class="input-group-append">
        //                                 <button type="button" class="btn btn-sm ml-3 remove-account-entry" style="background-color: transparent;">
        //                                     <i class="fas fa-trash-alt text-danger"></i>
        //                                 </button>
        //                             </div>
        //                         </div>
        //                     </div>
        //                 </div>
        //             </div>
        //         `;

            //     $('#account-entry-container').append(newEntryHtml);
            //     setTimeout(function() {
            //         $(`#${accountSelectId}`).select2({
            //             placeholder: "Select Account",
            //             allowClear: true,
            //             theme: 'bootstrap4',
            //             width: '100%'
            //         }).on('select2:open', function(e) {
            //             // Force high z-index for dropdown
            //             $('.select2-container').css('z-index', 99999);
            //             $('.select2-dropdown').css('z-index', 99999);
            //         });
            //     }, 100);
            //     // Add event listeners to the newly created inputs
            //     const newEntry = $('#account-entry-container .account-entry').last();
            //     newEntry.find('.debit-input, .credit-input').on('input', function() {
            //         const row = $(this).closest('.account-entry');
            //         const debitInput = row.find('.debit-input');
            //         const creditInput = row.find('.credit-input');

            //         // If this input has a value (other than 0.00), clear the other one
            //         if ($(this).val() !== '0.00' && $(this).val() !== '') {
            //             if ($(this).hasClass('debit-input')) {
            //                 creditInput.val('0.00');
            //             } else {
            //                 debitInput.val('0.00');
            //             }
            //         }
            //     });
            // });

            // Also add the event listener for existing rows when the page loads
            // $(document).ready(function() {
            //     $(document).on('input', '.debit-input, .credit-input', function() {
            //         const row = $(this).closest('.account-entry');
            //         const debitInput = row.find('.debit-input');
            //         const creditInput = row.find('.credit-input');

            //         // If this input has a value (other than 0.00), clear the other one
            //         if ($(this).val() !== '0.00' && $(this).val() !== '') {
            //             if ($(this).hasClass('debit-input')) {
            //                 creditInput.val('0.00');
            //             } else {
            //                 debitInput.val('0.00');
            //             }
            //         }
            //     });
            // });

            // Remove entry
            $(document).on('click', '.remove-account-entry', function() {
                if ($('.account-entry').length > 1) {
                    $(this).closest('.account-entry').remove();
                    calculateTotals();
                }
            });


            // Listen to input changes
            $(document).on('input', '.debit-input, .credit-input', function() {
                calculateTotals();
            });

            // Calculate Totals
            function calculateTotals() {
                var debitTotal = 0;
                var creditTotal = 0;

                $('.account-entry').each(function() {
                    var debit = parseFloat($(this).find('.debit-input').val());
                    var credit = parseFloat($(this).find('.credit-input').val());

                    debitTotal += isNaN(debit) ? 0 : debit;
                    creditTotal += isNaN(credit) ? 0 : credit;
                });

                $('.form-row.mb-3 input').each(function(index) {
                    if (index === 0) {
                        $(this).val('Debit: ' + debitTotal.toFixed(2) + ' | Credit: ' + creditTotal.toFixed(
                            2));
                    } else if (index === 1) {
                        $(this).val(debitTotal.toFixed(2));
                    } else {
                        $(this).val(creditTotal.toFixed(2));
                    }
                });
            }


            $(document).on('click', '.remove-account-entry', function() {
                if ($('.account-entry').length > 1) {
                    $(this).closest('.account-entry').remove();
                }
            });

            ///////////////////////////////////////////////////////////////

            // Check every 500ms
            setInterval(function() {
                calculateTotals();
                const debit = parseFloat($('#total_debit_amount').val()) || 0;
                const credit = parseFloat($('#total_credit_amount').val()) || 0;
                const invoiceNo = $('#invoice_no_edit').val();
                // const receiving_account = $('.account-entry').find('.account-select').val();

                if (invoiceNo == '' && debit !== credit) {
                    $('.received_receiving_status').prop('disabled', true);
                } else if (invoiceNo == '' && debit == credit) {
                    $('.received_receiving_status').prop('disabled', true);
                } else if (invoiceNo != '' && debit == credit) {
                    $('.received_receiving_status').prop('disabled', false);
                } else if (invoiceNo != '' && debit !== credit) {
                    $('.received_receiving_status').prop('disabled', true);
                }





                const po_total_items = $('#grand_total_amount_edit_3').text().replace(/,/g, '');
                const po_disburse_total_items = $('#disbursed_balance').text().replace(/,/g, '');


                const totalItems = parseFloat(po_total_items);
                const disbursedItems = parseFloat(po_disburse_total_items);



                // Corrected condition for enabling the button
                if (disbursedItems < totalItems) {
                    $('.process_disbursement_receiving').prop('disabled', false);
                } else {
                    $('.process_disbursement_receiving').prop('disabled', true);
                }





                // const po_total_items = $('#grand_total_amount_edit_2').text().replace(/,/g, '');

                // const totalItems = parseFloat(po_total_items);



                if (totalItems == 0) {
                    $('.received_receiving_status').prop('disabled', true);
                } else {
                    $('.received_receiving_status').prop('disabled', false);
                }
                const po_total_items_edit = $('#grand_total_amount_edit').text().replace(/,/g, '');
                const totalItems_edit = parseFloat(po_total_items_edit);

                if (totalItems_edit != 0) {
                    $('.received_receiving_status').prop('disabled', false);
                } else {
                    $('.received_receiving_status').prop('disabled', true);
                }


            }, 500);






            ///////////////////////////////////////////////////////////////



            $('.received_receiving_status').on('click', function(event) {
                event.preventDefault();
                // console.log('Approved PO Status: ', $(this).val());

                var received_receiving = $(this).val();
                update_po_status_to_approved(received_receiving)

                function update_po_status_to_approved(received_receiving) {

                    var po_id_edit = $('#receiving_id').val();

                    var poReferenceNumber_edit = $('#receivingReferenceNumber_edit').val();
                    var remarks_edit = $('#remarks_edit').val();
                    var poDate_edit = $('#receivingDate_edit').val();
                    var supplier_edit = $('#supplier_edit').val();
                    var datereceived_edit = $('#Date_received').val();
                    var invoice_no_edit = $('#invoice_no_edit').val();
                    var amount = $('#grand_total_amount_edit').text();


                    var tableData = [];
                    // $("#purchase_items_table_edit tbody tr").each(function() {
                    //     var row = $(this).data();
                    //     if (row) {
                    //         tableData.push({
                    //             itemId: $(this).find('td:eq(0) span').data('id'),
                    //             poItem: $(this).find('td:eq(0)').text(),
                    //             amount: $(this).find('td:eq(1)').text(),
                    //             quantity: $(this).find('td:eq(2)').text(),
                    //             total: $(this).find('td:eq(3)').text(),
                    //             receivedqty: $(this).find('input[type="number"]').val(),
                    //             rtotal: $(this).find('td:eq(5)')
                    //                 .text() // Get text from 6th cell
                    //         });
                    //     }
                    // });

                    $("#purchase_items_table_edit tbody tr").each(function() {
                        var row = $(this).data();
                        if (row) {
                            tableData.push({
                                itemId: $(this).find('td:eq(0) span').data('id'),
                                poItem: $(this).find('td:eq(0)').text(),
                                amount: $(this).find('td:eq(1)').text(),
                                old_quantity: $(this).find('td:eq(2)').text(),
                                old_total: (parseFloat($(this).find('td:eq(1)').text()) *
                                        parseFloat($(this).find('td:eq(2)').text()))
                                    .toString(),
                                // old_total: $(this).find('td:eq(3)').text(),

                                quantity: $(this).find('td:eq(2)').text() - $(this).find(
                                    'input[type="number"]').val(),
                                total: parseFloat($(this).find('td:eq(3)').text().replace(
                                    ',', '')) - parseFloat($(this).find(
                                        'td:eq(5)')
                                    .text().replace(',', '')) || 0,
                                receivedqty: $(this).find('input[type="number"]').val(),
                                rtotal: parseFloat($(this).find('td:eq(5)')
                                    .text().replace(',', '')) || 0
                                // Get text from 6th cell
                            });
                        }
                    });

                    ////////////////////////////////////////////////////////////
                    var entries = [];
                    var hasError = false;

                    $('.account-entry').each(function() {
                        var account = $(this).find('.account-select').val();
                        var debit = parseFloat($(this).find('.debit-input').val()) || 0;
                        var credit = parseFloat($(this).find('.credit-input').val()) || 0;

                        if (!account || (debit === 0 && credit === 0)) {
                            hasError = true;
                            return false; // break
                        }

                        entries.push({
                            account: account,
                            debit: debit,
                            credit: credit
                        });
                    });

                    if (hasError) {
                        Toast.fire({
                            icon: 'warning',
                            title: 'Please fill all fields correctly!'
                        });
                        return;
                    }

                    $.ajax({
                        type: "GET",
                        url: '/bookkeeper/receiving/update_received',
                        data: {

                            po_id_edit: po_id_edit,
                            poReferenceNumber_edit: poReferenceNumber_edit,
                            remarks_edit: remarks_edit,
                            poDate_edit: poDate_edit,
                            supplier_edit: supplier_edit,
                            datereceived_edit: datereceived_edit,
                            invoice_no_edit: invoice_no_edit,
                            amount: amount,
                            po_items: tableData,
                            entries: entries,

                            approve_po: received_receiving
                        },
                        success: function(data) {
                            // if (data[0].status == 2) {
                            //     Toast.fire({
                            //         type: 'warning',
                            //         title: data[0].message
                            //     })

                            // } else

                            if (data[0].status == 1) {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Successfully Received'
                                })


                                received_history_table()
                                po_supplierTable()
                                $(".process_disbursement_receiving").removeAttr('disabled');


                                $('#editpurchaseOrderModal').modal('hide');
                                $('.modal-backdrop').remove();
                                $('#remarks_edit').val("");


                            } else if (data[0].status == 3) {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Successfully Received'
                                })

                                received_history_table()
                                po_supplierTable()
                                $(".process_disbursement_receiving").removeAttr('disabled');

                                $('#editpurchaseOrderModal').modal('hide');
                                $('.modal-backdrop').remove()
                                $('#remarks_edit').val("");

                            } else if (data[0].status == 4) {
                                Toast.fire({
                                    type: 'warning',
                                    title: 'Invoice Number Already Exists'
                                })
                            } else {
                                Toast.fire({
                                    type: 'error',
                                    title: data[0].message
                                })
                            }
                        }
                    });
                }

            });

            $(document).on('click', '.process_disbursement_receiving', function() {

                var po_id = $(this).attr('data-id')

                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/receiving/edit',
                    data: {
                        po_id: po_id
                    },
                    success: function(response) {

                        var receiving_selected = response.receiving;
                        var receiving_items = response.receiving_items;
                        var all_supplier = response.purchasing_supplier;



                        $("#expenses_disburse_po_id").val(receiving_selected[0].id);
                        $("#expenses_date_disburse_po").val(receiving_selected[0].postdatetime
                            .split(
                                ' ')[0]);
                        // $("#poDate_edit").val(po_selected[0].postdatetime.split(' ')[0]);
                        // $("#poDate_edit").val(po_selected[0].postdatetime.split(' ')[0]);

                        $("#expenses_ref_no_disburse_po").val(receiving_selected[0].refno);

                        $("#expenses_disbursement_to_disburse_po").empty().trigger('change');
                        $("#expenses_disbursement_to_disburse_po").append(
                            '<option value="" selected disabled>Select Supplier</option>'
                        );
                        all_supplier.forEach(all_supplier => {
                            if (all_supplier.id == receiving_selected[0]
                                .supplierid) {
                                $("#expenses_disbursement_to_disburse_po").append(
                                    `<option value="${all_supplier.id}" selected>${all_supplier.suppliername}</option>`
                                );
                            } else {
                                $("#expenses_disbursement_to_disburse_po").append(
                                    `<option value="${all_supplier.id}">${all_supplier.suppliername}</option>`
                                );
                            }
                        });

                        var total_amount = 0;
                        receiving_items.forEach(receiving_item => {
                            total_amount += parseFloat(receiving_item.total) || 0;
                        });
                        // $("#expenses_amount_disburse_po").val(total_amount.toFixed(2));

                        $("#expenses_amount_disburse_po").val(parseFloat($("#disbursed_balance_balance").text()).toFixed(2));

                        // $("#grand_total_amount_edit").text(receiving_selected[0].totalamount);

                        var isCashChecked = $('#expenses_cash_disburse_po').is(':checked');
                        $('#expenses_bank_disburse_po').prop('disabled', isCashChecked);
                        $('#expenses_cheque_no_disburse_po').prop('disabled', isCashChecked);
                        $('#expenses_cheque_date_disburse_po').prop('disabled', isCashChecked);
                        $('#expenses_voucher_no_disburse_po').val('CV-', isCashChecked);


                        $('#expenses_cheque_disburse_po').change(function() {
                            if ($(this).is(':checked')) {
                                var voucher_no = $("#expenses_voucher_no_disburse_po")
                                    .val();
                                if (voucher_no.includes('CV-')) {
                                    $("#expenses_voucher_no_disburse_po").val(voucher_no
                                        .replace('CV-', 'CHV-'));
                                }
                                $('#expenses_bank_disburse_po').prop('disabled', false);
                                $('#expenses_cheque_no_disburse_po').prop('disabled',
                                    false);
                                $('#expenses_cheque_date_disburse_po').prop('disabled',
                                    false);
                                $('#expenses_voucher_no_disburse_po').val('CHV-');
                            }
                        });

                        $('#expenses_cash_disburse_po').change(function() {
                            if ($(this).is(':checked')) {
                                var voucher_no = $("#expenses_voucher_no_disburse_po")
                                    .val();
                                if (voucher_no.includes('CHV-')) {
                                    $("#expenses_voucher_no_disburse_po").val(voucher_no
                                        .replace('CHV-', 'CV-'));
                                }
                                $('#expenses_bank_disburse_po').prop('disabled', true);
                                $('#expenses_cheque_no_disburse_po').prop('disabled',
                                    true);
                                $('#expenses_cheque_date_disburse_po').prop('disabled',
                                    true);
                                $('#expenses_voucher_no_disburse_po').val('CV-');
                            }
                        });




                        po_itemsTable()

                        function po_itemsTable() {

                            $("#expensesItemsTable_disburse_po").DataTable({
                                destroy: true,
                                autoWidth: false,
                                paging: false,
                                searching: false,
                                info: false,
                                data: receiving_items,
                                columns: [{
                                        "data": "description"
                                    },
                                    {
                                        "data": "amount"
                                    },
                                    {
                                        "data": "disbursed_qty"
                                    },
                                    {
                                        "data": "disbursed_total"
                                    },
                                    // {
                                    //     "data": null
                                    // }
                                ],
                                columnDefs: [{
                                        'targets': 0,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html(
                                                    `<span data-id="${rowData.itemid}">${rowData.description}</span>`
                                                )
                                                .addClass('align-middle');
                                        }
                                    },
                                    {
                                        'targets': 1,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html(rowData.amount).addClass(
                                                'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 2,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html(rowData.disbursed_qty).addClass(
                                                'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 3,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            var total = 0;
                                            $.each(receiving_items, function(i,
                                                item) {
                                                total += parseFloat(item
                                                    .disbursed_total);
                                            });
                                            $('#grand_total_amount__disburse_po')
                                                .html(total.toLocaleString());
                                            $(td).html(parseFloat(rowData.disbursed_total)
                                                .toLocaleString()).addClass(
                                                'align-middle');
                                        }
                                    },
                                    // {
                                    //     'targets': 4,
                                    //     'orderable': false,
                                    //     'createdCell': function(td, cellData,
                                    //         rowData, row, col) {
                                    //         var buttons =
                                    //             '<a href="javascript:void(0)" class="edit_purchase_order" id="edit_selected_expenses_item" data-id="' +
                                    //             rowData.id +
                                    //             '" data-toggle="modal" data-target="#editSelectedExpensesItemModal"><i class="far fa-edit text-primary"></i></a>' +
                                    //             '&nbsp;&nbsp;' +
                                    //             '<a href="javascript:void(0)" class="delete_selected_expenses_item" id="delete_selected_expenses_item" data-id="' +
                                    //             rowData.id +
                                    //             '"><i class="far fa-trash-alt text-danger"></i></a>';
                                    //         $(td)[0].innerHTML = buttons;
                                    //         $(td).addClass(
                                    //             'text-center align-middle');
                                    //     }
                                    // }
                                ]
                            });
                        }




                    }
                });

            });

            $('.saveDisbursement').on('click', function(event) {
                event.preventDefault();
                create_disbursements()
            });

            function create_disbursements() {

                var disbursement_type = '';
                if ($('#checked_expenses_expenses_disburse_po').is(':checked')) {
                    disbursement_type = 'Expenses';
                } else if ($('#checked_expenses_refund_disburse_po').is(':checked')) {
                    disbursement_type = 'Students Refund';
                }

                var expenses_date = $('#expenses_date_disburse_po').val();
                var expenses_voucher_no = $('#expenses_voucher_no_disburse_po').val();
                var expenses_ref_no = $('#expenses_ref_no_disburse_po').val();
                var expenses_disbursement_to = $('#expenses_disbursement_to_disburse_po').val();
                var expenses_department = $('#expenses_department_disburse_po').val();

                var payment_type = '';
                if ($('#expenses_cash_disburse_po').is(':checked')) {
                    payment_type = 'Cash';
                } else if ($('#expenses_cheque_disburse_po').is(':checked')) {
                    payment_type = 'Cheque';
                }

                var expenses_amount = $('#expenses_amount_disburse_po').val();

                var bank = '';
                var cheque_no = '';
                var cheque_date = '';
                if ($('#expenses_cheque_disburse_po').is(':checked')) {
                    bank = $('#expenses_bank_disburse_po').val();
                    cheque_no = $('#expenses_cheque_no_disburse_po').val();
                    cheque_date = $('#expenses_cheque_date_disburse_po').val();
                }

                var expenses_remarks = $('#expenses_remarks_disburse_po').val();
                // var grand_total_amount = $('#grand_total_amount').text();

                // var tableData = [];
                // $("#expensesItemsTable_disburse_po").find("tbody tr").each(function() {
                //     if ($(this).is(":visible")) {
                //         var itemId = $(this).find("td:eq(0)").attr('data-id');
                //         var itemName = $(this).find("td:eq(0)").text().trim();
                //         var itemAmount = $(this).find("td:eq(1)").text().trim();
                //         var itemQuantity = $(this).find("td:eq(2)").text().trim();
                //         var itemTotal = $(this).find("td:eq(3)").text().trim();

                //         if (itemId && itemName && itemAmount && itemQuantity && itemTotal) {
                //             tableData.push({
                //                 itemId: itemId,
                //                 itemName: itemName,
                //                 itemAmount: itemAmount,
                //                 itemQuantity: itemQuantity,
                //                 itemTotal: itemTotal
                //             });
                //         }
                //     }
                // });

                var table = $("#expensesItemsTable_disburse_po").DataTable();
                var tableData = table.rows().data().toArray().map(function(item) {
                    return {
                        itemId: item.itemid,
                        itemName: item.description,
                        itemAmount: item.amount,
                        itemQuantity: item.qty,
                        itemTotal: item.totalamount
                    };
                });



                if (tableData.length === 0) {
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: 'No items found in the table.',
                    });
                    return;
                }
                var entries = [];
                var hasError = false;

                $('.account-entry_po_disburse').each(function() {
                    var account = $(this).find('.account-select-po-disburse').val();
                    var debit = parseFloat($(this).find('.debit-input-po-disburse').val()) || 0;
                    var credit = parseFloat($(this).find('.credit-input-po-disburse').val()) || 0;

                    if (!account || (debit === 0 && credit === 0)) {
                        hasError = true;
                        return false; // break
                    }

                    entries.push({
                        account: account,
                        debit: debit,
                        credit: credit
                    });
                });

                if (hasError) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Please fill all fields correctly!'
                    });
                    return;
                }



                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/disbursement/create',
                    data: {

                        expensesItem: tableData,
                        // expensesItem: JSON.stringify(tableData), // Stringify the array
                        expenses_date: expenses_date,
                        expenses_voucher_no: expenses_voucher_no,
                        expenses_ref_no: expenses_ref_no,
                        expenses_disbursement_to: expenses_disbursement_to,
                        expenses_department: expenses_department,
                        payment_type: payment_type,
                        expenses_amount: expenses_amount,
                        // payment_type: payment_type,
                        bank: bank,
                        cheque_no: cheque_no,
                        cheque_date: cheque_date,
                        expenses_remarks: expenses_remarks,

                        entries: entries,

                        _token: $('meta[name="csrf-token"]').attr('content') // Add CSRF token
                        // grand_total_amount: grand_total_amount
                    },
                    success: function(data) {
                        if (data[0].status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data[0].message
                            })
                        } else if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'PO Disbursements created'
                            })

                            // var table = $("#expensesItemsTable");
                            // table.find("tbody .appended-row").remove();
                            // $("#expenses_date").val("");
                            // $("#expenses_voucher_no").val("");
                            // $("#expenses_ref_no").val("");
                            // $("#expenses_disbursement_to").val("");
                            // $("#expenses_department").val("");
                            // $("#expenses_amount").val("");
                            // $('#grand_total_amount').text("0.0");

                            // expenses_monitoringTable()

                            $("#add_disbursement_po").modal('hide');
                            $('.modal-backdrop').remove();

                            $("#editpurchaseOrderModal").modal('hide');
                            $('.modal-backdrop').remove();

                            po_supplierTable()
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }

            received_history_table()

            function received_history_table() {

                $("#received_history_table").DataTable({
                    destroy: true,
                    autoWidth: false,
                    paging: false,
                    info: false,
                    ajax: {
                        url: '/bookkeeper/received_history/fetch',
                        type: 'GET',
                        dataSrc: function(json) {
                            return json;
                        }
                    },
                    columns: [{
                            "data": "invoiceno"
                        },
                        {
                            "data": "suppliername"
                        },
                        {
                            "data": "remarks"
                        },
                        {
                            "data": "amount"
                        },
                        {
                            "data": "datedelivered"
                        },
                        {
                            "data": "rstatus"
                        },
                        {
                            "data": null
                        },

                    ],
                    columnDefs: [

                        {
                            'targets': 0,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).addClass('clickable-td edit_received_history').attr('id',
                                    'edit_rhistory').attr('data-id', rowData.id).html(rowData
                                    .invoiceno).addClass(
                                    'align-middle').css('cursor', 'pointer');
                            }
                        },
                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).addClass('clickable-td edit_received_history').attr('id',
                                    'edit_rhistory').attr('data-id', rowData.id).html(rowData
                                    .suppliername).addClass(
                                    'align-middle').css('cursor', 'pointer');
                            }
                        },

                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                // $(td).html('');
                                $(td).addClass('clickable-td edit_received_history').attr('id',
                                    'edit_rhistory').attr('data-id', rowData.id).html(rowData
                                    .remarks).addClass('align-middle').css('cursor', 'pointer');

                            }
                        },

                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).addClass('clickable-td edit_received_history').attr('id',
                                    'edit_rhistory').attr('data-id', rowData.id).html(rowData
                                    .amount).addClass(
                                    'align-middle').css('cursor', 'pointer');
                            }
                        },

                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).addClass('clickable-td edit_received_history').attr('id',
                                        'edit_rhistory').attr('data-id', rowData.id).html(moment(
                                        rowData.datedelivered).format('YYYY-MM-DD'))
                                    .addClass('text-left align-middle').css('cursor', 'pointer');
                            }
                        },

                        {
                            'targets': 5,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).addClass('clickable-td edit_received_history').attr('id',
                                    'edit_rhistory').attr('data-id', rowData.id).html(rowData
                                    .rstatus).addClass('text-left align-middle').css('cursor',
                                    'pointer');
                            }
                        },
                        {
                            'targets': 6,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData) {
                                var link = `<a href="javascript:void(0)" 
                                              class="edit_receiving_history_items" 
                                              data-id="${rowData.id}" 
                                              data-toggle="modal" 
                                              data-target="#edit_receiving_history_Modal"
                                              style="color:red;text-decoration: underline;">
                                                Void
                                            </a>`;
                                $(td).html(link).addClass(
                                    'text-center align-middle font-weight-bold');
                            }
                        }

                    ],
                    createdRow: function(row, data, dataIndex) {
                        $(row).addClass('hoverable-row');
                    },
                    // lengthMenu: [
                    //     [10, 25, 50, 100],
                    //     [10, 25, 50, 100]
                    // ],
                    // pageLength: 10,
                    // dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                    //     "<'row'<'col-sm-12'tr>>" +
                    //     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

                });
            }
            $(document).on('click', '#edit_rhistory', function() {
                var id = $(this).data('id');
                $('#edit_receiving_history_Modal_view').data('id', id).modal('show');
            })


            $(document).on('click', '.edit_receiving_history_items', function() {

                var po_id = $(this).attr('data-id')

                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/receiving_history/edit',
                    data: {
                        po_id: po_id
                    },
                    success: function(response) {

                        var receiving_selected = response.receiving;
                        var receiving_items = response.receiving_items;
                        var all_supplier = response.purchasing_supplier;
                     

                        if (receiving_selected[0].rstatus == "RECEIVED") {
                            $(".process_disbursement_receiving").removeAttr('disabled');

                        } else {
                            $(".process_disbursement_receiving").attr('disabled', 'disabled');

                        }


                        $("#receiving_history_id").val(receiving_selected[0].id);

                        // $(".process_disbursement_receiving").attr("data-id", receiving_selected[
                        //     0].id);

                        $("#receiving_historyReferenceNumber_edit").val(receiving_selected[0]
                            .refno);
                        // $("#poDate_edit").val(po_selected[0].postdatetime.split(' ')[0]);
                        // $("#poDate_edit").val(po_selected[0].postdatetime.split(' ')[0]);
                        $("#receiving_historyDate_edit").val(receiving_selected[0].postdatetime
                            .split(
                                ' ')[0]);
                        $("#receiving_history_remarks_edit").val(receiving_selected[0].remarks);
                        $("#receiving_history_invoice_no_edit").val(receiving_selected[0]
                            .invoiceno);
                        $("#receivingDate_received").val(receiving_selected[0].datedelivered ?
                            receiving_selected[0].datedelivered.split(' ')[0] :
                            '{{ \Carbon\Carbon::today()->toDateString() }}');

                        $("#receiving_history_supplier_view").empty().trigger('change');
                        $("#receiving_history_supplier_view").append(
                            '<option value="" selected disabled>Select Supplier</option>'
                        );
                        all_supplier.forEach(all_supplier => {
                            if (all_supplier.id == receiving_selected[0]
                                .supplierid) {
                                $("#receiving_history_supplier_view").append(
                                    `<option value="${all_supplier.id}" selected>${all_supplier.suppliername}</option>`
                                );
                            } else {
                                $("#receiving_history_supplier_view").append(
                                    `<option value="${all_supplier.id}">${all_supplier.suppliername}</option>`
                                );
                            }
                        });

                        $("#grand_total_amount_edit").text(receiving_selected[0].totalamount);




                        po_itemsTabless()

                        function po_itemsTabless() {
                            $("#receiving_history_items_table_edit").DataTable({
                                destroy: true,
                                autoWidth: false,
                                data: receiving_items,
                                info: false,
                                lengthChange: false,
                                searching: false,
                                paging: false,
                                columns: [{
                                        "data": "description"
                                    },
                                    {
                                        "data": "amount"
                                    },
                                    {
                                        "data": "qty"
                                    },
                                    {
                                        "data": "total"
                                    },
                                    {
                                        "data": "receivedqty"
                                    },
                                    {
                                        "data": "rtotal"
                                    }
                                ],
                                columnDefs: [{
                                        'targets': 0,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html(
                                                    `<span data-id="${rowData.itemid}">${rowData.description}</span>`
                                                )
                                                .addClass('align-middle');
                                        }
                                    },
                                    {
                                        'targets': 1,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html(rowData.amount).addClass(
                                                'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 2,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html(rowData.qty).addClass(
                                                'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 3,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html(rowData.total)
                                                .addClass(
                                                    'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 4,
                                        'orderable': false,
                                        'width': '14%',
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html(rowData.receivedqty)
                                                .addClass(
                                                    'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 5,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html('<span id="rtotals">' +
                                                    rowData.rtotal
                                                    .toLocaleString() +
                                                    '</span>')
                                                .addClass(
                                                    'align-middle');
                                        }
                                    }
                                ],
                                drawCallback: function() {
                                    // Calculate totals after the table is fully drawn
                                    var totals = 0.00;
                                    $("#receiving_history_items_table_edit tbody tr")
                                        .each(function() {
                                            var rtotalText = $(this).find(
                                                "td:eq(5) span").text();
                                            totals += parseFloat(rtotalText
                                                .replace(/,/g, '')) || 0.00;
                                        });
                                    $('#receiving_grand_total_amount_edit').text(
                                        totals.toFixed(2).toLocaleString());
                                }
                            });
                        }

                     





                    }
                });

            });

            $(document).on('click', '.edit_received_history', function() {

                var po_id = $(this).attr('data-id')

                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/receiving_history/edit',
                    data: {
                        po_id: po_id
                    },
                    success: function(response) {

                        var receiving_selected = response.receiving;
                        var receiving_items = response.receiving_items;
                        var all_supplier = response.purchasing_supplier;
                        var disbursements_general_ledger = response
                            .bkdisbursements_general_ledger;
                        console.log(receiving_selected);
                        console.log(receiving_items);
                        console.log(all_supplier);
                        console.log(disbursements_general_ledger);

                    

                        if (receiving_selected[0].rstatus == "RECEIVED") {
                            $(".process_disbursement_receiving").removeAttr('disabled');

                        } else {
                            $(".process_disbursement_receiving").attr('disabled', 'disabled');

                        }

                        $("#receiving_history_id").val(receiving_selected[0].id);

                        // $(".process_disbursement_receiving").attr("data-id", receiving_selected[
                        //     0].id);

                        $("#receiving_historyReferenceNumber_edit").val(receiving_selected[0]
                            .refno);
                        // $("#poDate_edit").val(po_selected[0].postdatetime.split(' ')[0]);
                        // $("#poDate_edit").val(po_selected[0].postdatetime.split(' ')[0]);
                        $("#receiving_historyDate_edit").val(receiving_selected[0].postdatetime
                            .split(
                                ' ')[0]);
                        $("#receiving_history_remarks_edit").val(receiving_selected[0].remarks);
                        $("#receiving_history_invoice_no_edit").val(receiving_selected[0]
                            .invoiceno);
                        $("#receivingDate_received").val(receiving_selected[0].datedelivered ?
                            receiving_selected[0].datedelivered.split(' ')[0] :
                            '{{ \Carbon\Carbon::today()->toDateString() }}');

                        $("#receiving_history_supplier_view").empty().trigger('change');
                        $("#receiving_history_supplier_view").append(
                            '<option value="" selected disabled>Select Supplier</option>'
                        );
                        all_supplier.forEach(all_supplier => {
                            if (all_supplier.id == receiving_selected[0]
                                .supplierid) {
                                $("#receiving_history_supplier_view").append(
                                    `<option value="${all_supplier.id}" selected>${all_supplier.suppliername}</option>`
                                );
                            } else {
                                $("#receiving_history_supplier_view").append(
                                    `<option value="${all_supplier.id}">${all_supplier.suppliername}</option>`
                                );
                            }
                        });

                        $("#grand_total_amount_edit").text(receiving_selected[0].totalamount);

                        // $("#receiving_grand_total_amount_view").text(receiving_selected[0].totalamount);

                        po_itemsTabless_view()

                        function po_itemsTabless_view() {
                            $("#receiving_history_items_table_view").DataTable({
                                destroy: true,
                                autoWidth: false,
                                data: receiving_items,
                                info: false,
                                lengthChange: false,
                                searching: false,
                                paging: false,
                                columns: [{
                                        "data": "description"
                                    },
                                    {
                                        "data": "amount"
                                    },
                                    {
                                        "data": "qty"
                                    },
                                    {
                                        "data": "total"
                                    },
                                    {
                                        "data": "receivedqty"
                                    },
                                    {
                                        "data": "rtotal"
                                    }
                                ],
                                columnDefs: [{
                                        'targets': 0,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html(
                                                    `<span data-id="${rowData.itemid}">${rowData.description}</span>`
                                                )
                                                .addClass('align-middle');
                                        }
                                    },
                                    {
                                        'targets': 1,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html(rowData.amount).addClass(
                                                'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 2,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html(rowData.qty).addClass(
                                                'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 3,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html(rowData.total)
                                                .addClass(
                                                    'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 4,
                                        'orderable': false,
                                        'width': '14%',
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html(rowData.receivedqty)
                                                .addClass(
                                                    'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 5,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html('<span id="rtotals">' +
                                                    rowData.rtotal
                                                    .toLocaleString() +
                                                    '</span>')
                                                .addClass(
                                                    'align-middle');
                                        }
                                    }
                                ],
                                drawCallback: function() {
                                    // Calculate totals after the table is fully drawn
                                    var totals = 0.00;
                                    $("#receiving_history_items_table_view tbody tr")
                                        .each(function() {
                                            var rtotalText = $(this).find(
                                                "td:eq(5) span").text();
                                            totals += parseFloat(rtotalText
                                                .replace(/,/g, '')) || 0.00;
                                        });
                                    $('#receiving_grand_total_amount_view').text(
                                        totals.toFixed(2).toLocaleString());
                                }
                            });
                        }

                        // $('#po-disburse-account-entry-container-edit').empty();

                        // disbursements_general_ledger.forEach(entry => {
                        //     // Create the account entry row
                        //     $('#po-disburse-account-entry-container-edit').append(`
                        //         <div class="form-row mb-3 account-entry_po_disburse" data-expensesid="${entry.id}">
                        //             <div class="form-group col-md-4">
                        //                 <label style="font-weight: 600; font-size: 13px;">Account</label>
                        //                 <select class="form-control account-select-po-disburse" id="account_select_po_disburse_edit_${entry.id}" 
                        //                     style="width: 90%; height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);" disabled>
                        //                 </select>
                        //             </div>
                        //             <div class="form-group col-md-4">
                        //                 <label style="font-weight: 600; font-size: 13px;">Debit Account</label>
                        //                 <input type="text" class="form-control" id="debit_input_po_disburse_edit_edit_${entry.id}" value="${entry.debit_amount}"
                        //                 style="width: 86%;height: 38px !important; padding: 10px 15px !important;
                        //                         line-height: 1.5 !important;">
                        //             </div>
                        //             <div class="form-group col-md-4 d-flex align-items-center">
                        //                 <div style="width: 100%;">
                        //                     <label style="font-weight: 600; font-size: 13px;">Credit Account</label>
                        //                     <div class="input-group">
                        //                         <input type="text" class="form-control" id="credit_input_po_disburse_edit_edit_${entry.id}" value="${entry.credit_amount}"
                        //                         style="width: 86%;height: 38px !important; padding: 10px 15px !important;
                        //                         line-height: 1.5 !important;">
                                            
                        //                     </div>
                        //                 </div>
                        //             </div>
                        //         </div>
                        //     `);

                        //     setTimeout(() => {
                        //         const debitVal = entry.debit_amount || 0;
                        //         const creditVal = entry.credit_amount || 0;
                                
                        //         $(`#debit_input_po_disburse_edit_edit_${entry.id}`).val(parseFloat(debitVal).toFixed(2));
                        //         $(`#credit_input_po_disburse_edit_edit_${entry.id}`).val(parseFloat(creditVal).toFixed(2));
                                
                        //         console.log('Forced values:', 
                        //             $(`#debit_input_po_disburse_edit_edit_${entry.id}`).val(), 
                        //             $(`#credit_input_po_disburse_edit_edit_${entry.id}`).val());
                        //     }, 200);

                        //     // Now populate the dropdown for this specific entry
                        //     var $accountSelect = $(
                        //         `#account_select_po_disburse_edit_${entry.id}`);
                        //     $accountSelect.empty();

                        //     setTimeout(function() {
                        //         $(`#account_select_po_disburse_edit_${entry.id}`)
                        //             .select2({
                        //                 placeholder: "Select Account",
                        //                 allowClear: true,
                        //                 theme: 'bootstrap4',
                        //                 width: '100%'
                        //             }).on('select2:open', function(e) {
                        //                 // Force high z-index for dropdown
                        //                 $('.select2-container').css(
                        //                     'z-index', 99999);
                        //                 $('.select2-dropdown').css(
                        //                     'z-index', 99999);
                        //             });
                        //     }, 100);

                        //     // Add options from chart_of_accounts and sub_chart_of_accounts
                        //     response.chart_of_accounts.forEach(item => {
                        //         if (item.id == entry.coaid) {
                        //             $accountSelect.append(
                        //                 `<option value="${item.id}" selected>${item.code} - ${item.account_name}</option>`
                        //             );
                        //         } else {
                        //             $accountSelect.append(
                        //                 `<option value="${item.id}">${item.code} - ${item.account_name}</option>`
                        //             );
                        //         }

                        //         // Add sub-accounts if they exist
                        //         response.sub_chart_of_accounts.forEach(
                        //             subitem => {
                        //                 if (subitem.coaid == item.id) {
                        //                     if (subitem.id == entry.coaid) {
                        //                         $accountSelect.append(
                        //                             `<option style="font-style:italic;" value="${subitem.id}" selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${subitem.sub_code} - ${subitem.sub_account_name}</option>`
                        //                         );
                        //                     } else {
                        //                         $accountSelect.append(
                        //                             `<option style="font-style:italic;" value="${subitem.id}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${subitem.sub_code} - ${subitem.sub_account_name}</option>`
                        //                         );
                        //                     }
                        //                 }
                        //             });
                        //     });
                        // });

                    // $('#po-disburse-account-entry-container-edit').empty();

                    // Loop through each general ledger entry
                    // bkdisbursements_general_ledger.forEach(entry => {
                    //     // Determine if this is a debit or credit entry
                    //     const amountType = parseFloat(entry.debit_amount) > 0 ? 'debit' : 'credit';
                    //     const amount = amountType === 'debit' ? entry.debit_amount : entry.credit_amount;
                        
                    //     // Create the account entry row
                    //     $('#po-disburse-account-entry-container-edit').append(`
                    //         <div class="row mb-2 account-entry-row" data-id="${entry.id}">
                    //             <div class="col-md-5">
                    //                 <select class="form-control account-select" name="account[]" required>
                    //                     <option value="${entry.coaid}" selected>${entry.account_name}</option>
                    //                 </select>
                    //             </div>
                    //             <div class="col-md-2">
                    //                 <select class="form-control amount-type-select" name="amount_type[]" required>
                    //                     <option value="debit" ${amountType === 'debit' ? 'selected' : ''}>Debit</option>
                    //                     <option value="credit" ${amountType === 'credit' ? 'selected' : ''}>Credit</option>
                    //                 </select>
                    //             </div>
                    //             <div class="col-md-4">
                    //                 <input type="number" class="form-control amount-input" name="amount[]" step="0.01" min="0" value="${amount}" required>
                    //             </div>
                    //             <div class="col-md-1">
                    //                 <button type="button" class="btn btn-danger btn-sm remove-account-entry">
                    //                     <i class="fas fa-trash"></i>
                    //                 </button>
                    //             </div>
                    //         </div>
                    //     `);
                    // });

                    // // Add event listener for remove buttons (if needed)
                    // $('#po-disburse-account-entry-container-edit').on('click', '.remove-account-entry', function() {
                    //     $(this).closest('.account-entry-row').remove();
                    // });

                    $('#po-disburse-account-entry-container-edit').empty();

                    // Add header row
                    $('#po-disburse-account-entry-container-edit').append(`
                        <div class="row mb-2 font-weight-bold">
                            <div class="col-md-5">Account</div>
                            <div class="col-md-3 text-right">Debit Amount</div>
                            <div class="col-md-3 text-right">Credit Amount</div>
                            <div class="col-md-1"></div>
                        </div>
                    `);

                    // Initialize totals
                    let totalDebit = 0;
                    let totalCredit = 0;

                    // Loop through each general ledger entry
                    disbursements_general_ledger.forEach(entry => {
                        // Convert amounts to numbers
                        const debitAmount = parseFloat(entry.debit_amount) || 0;
                        const creditAmount = parseFloat(entry.credit_amount) || 0;
                        
                        // Add to totals
                        totalDebit += debitAmount;
                        totalCredit += creditAmount;
                        
                        // Create the account entry row
                        $('#po-disburse-account-entry-container-edit').append(`
                            <div class="row mb-2 account-entry-row" data-id="${entry.id}">
                                <div class="col-md-5">
                                    <select class="form-control account-select" name="account[]" readonly>
                                        <option value="${entry.coaid}" selected>${entry.voucherNo} - ${entry.account_name}</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control debit-amount text-right" 
                                        name="debit_amount[]" step="0.01" min="0" 
                                        value="${debitAmount.toFixed(2)}" ${debitAmount > 0 ? '' : 'readonly'}>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control credit-amount text-right" 
                                        name="credit_amount[]" step="0.01" min="0" 
                                        value="${creditAmount.toFixed(2)}" ${creditAmount > 0 ? '' : 'readonly'}>
                                </div>
                               
                            </div>
                        `);
                    });

                    //  <div class="col-md-1">
                    //     <button type="button" class="btn btn-danger btn-sm remove-account-entry">
                    //         <i class="fas fa-trash"></i>
                    //     </button>
                    // </div>

                    // // Add totals row
                    // $('#po-disburse-account-entry-container-edit').append(`
                    //     <div class="row mt-3 font-weight-bold border-top">
                    //         <div class="col-md-5 text-right">Totals:</div>
                    //         <div class="col-md-3 text-right">${totalDebit.toFixed(2)}</div>
                    //         <div class="col-md-3 text-right">${totalCredit.toFixed(2)}</div>
                    //         <div class="col-md-1"></div>
                    //     </div>
                    // `);

                    // // Add balance check row
                    // const balanceDifference = totalDebit - totalCredit;
                    // $('#po-disburse-account-entry-container-edit').append(`
                    //     <div class="row mb-3 font-weight-bold ${balanceDifference === 0 ? 'text-success' : 'text-danger'}">
                    //         <div class="col-md-8 text-right">Balance:</div>
                    //         <div class="col-md-3 text-right">${balanceDifference.toFixed(2)}</div>
                    //         <div class="col-md-1"></div>
                    //     </div>
                    // `);

                
                    // $('#po-disburse-account-entry-container-edit').on('click', '.remove-account-entry', function() {
                    //     $(this).closest('.account-entry-row').remove();
                
                    // });

                    // Add event listeners for amount changes to enforce single-sided entries
                    $('#po-disburse-account-entry-container-edit').on('change', '.debit-amount', function() {
                        const row = $(this).closest('.account-entry-row');
                        const debitInput = row.find('.debit-amount');
                        const creditInput = row.find('.credit-amount');
                        
                        if (parseFloat($(this).val()) > 0) {
                            creditInput.val('0.00').prop('readonly', true);
                        } else {
                            creditInput.prop('readonly', false);
                        }
                    });

                    $('#po-disburse-account-entry-container-edit').on('change', '.credit-amount', function() {
                        const row = $(this).closest('.account-entry-row');
                        const debitInput = row.find('.debit-amount');
                        const creditInput = row.find('.credit-amount');
                        
                        if (parseFloat($(this).val()) > 0) {
                            debitInput.val('0.00').prop('readonly', true);
                        } else {
                            debitInput.prop('readonly', false);
                        }
                    });

                    



                    }
                });

            });

            $(document).on('click', '.edit_purchase_order', function() {

                var po_id = $(this).attr('data-id')

                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/receiving/edit',
                    data: {
                        po_id: po_id
                    },
                    success: function(response) {

                        var receiving_selected = response.receiving;
                        var receiving_items = response.receiving_items;
                        var all_supplier = response.purchasing_supplier;

                        if (receiving_selected[0].rstatus == "RECEIVED") {
                            $(".process_disbursement_receiving").removeAttr('disabled');

                        } else {
                            $(".process_disbursement_receiving").attr('disabled', 'disabled');

                        }


                        $("#receiving_id").val(receiving_selected[0].id);

                        $(".process_disbursement_receiving").attr("data-id", receiving_selected[
                            0].id);

                        $("#receivingReferenceNumber_edit").val(receiving_selected[0].refno);
                        // $("#poDate_edit").val(po_selected[0].postdatetime.split(' ')[0]);
                        // $("#poDate_edit").val(po_selected[0].postdatetime.split(' ')[0]);
                        $("#receivingDate_edit").val(receiving_selected[0].postdatetime.split(
                            ' ')[0]);
                        // $("#remarks_edit").val(receiving_selected[0].remarks);
                        // $("#invoice_no_edit").val(receiving_selected[0].invoiceno);
                        $("#receivingDate_received").val(receiving_selected[0].datedelivered ?
                            receiving_selected[0].datedelivered.split(' ')[0] :
                            '{{ \Carbon\Carbon::today()->toDateString() }}');

                        $("#supplier_edit").empty().trigger('change');
                        $("#supplier_edit").append(
                            '<option value="" selected disabled>Select Supplier</option>'
                        );
                        all_supplier.forEach(all_supplier => {
                            if (all_supplier.id == receiving_selected[0]
                                .supplierid) {
                                $("#supplier_edit").append(
                                    `<option value="${all_supplier.id}" selected>${all_supplier.suppliername}</option>`
                                );
                            } else {
                                $("#supplier_edit").append(
                                    `<option value="${all_supplier.id}">${all_supplier.suppliername}</option>`
                                );
                            }
                        });

                        // $("#grand_total_amount_edit").text(receiving_selected[0].totalamount);


                        po_itemsTabless()

                        function po_itemsTabless() {
                            $("#purchase_items_table_edit").DataTable({
                                destroy: true,
                                autoWidth: false,
                                data: receiving_items,
                                info: false,
                                lengthChange: false,
                                searching: false,
                                paging: false,
                                columns: [{
                                        "data": "description"
                                    },
                                    {
                                        "data": "amount"
                                    },
                                    {
                                        "data": "qty"
                                    },
                                    {
                                        "data": "total"
                                    },
                                    {
                                        "data": "receivedqty"
                                    },
                                    {
                                        "data": "rtotal"
                                    }
                                ],
                                columnDefs: [{
                                        'targets': 0,
                                        'orderable': false,
                                        'createdCell': function(td, cellData, rowData, row, col) {
                                            $(td).html(
                                                    `<span data-id="${rowData.itemid}">${rowData.description}</span>`
                                                )
                                                .addClass('align-middle');
                                        }
                                    },
                                    {
                                        'targets': 1,
                                        'orderable': false,
                                        'createdCell': function(td, cellData, rowData, row, col) {
                                            $(td).html(rowData.amount).addClass('align-middle');
                                        }
                                    },
                                    {
                                        'targets': 2,
                                        'orderable': false,
                                        'createdCell': function(td, cellData, rowData, row, col) {
                                            if (rowData.qty == 0) {
                                                $(td).closest('tr').css('background-color', '#cfffdc');
                                            }
                                            $(td).html(rowData.qty).addClass('align-middle');
                                        }
                                    },
                                    {
                                        'targets': 3,
                                        'orderable': false,
                                        'createdCell': function(td, cellData, rowData, row, col) {
                                            $(td).html(rowData.total).addClass('align-middle');
                                        }
                                    },
                                    {
                                        'targets': 4,
                                        'orderable': false,
                                        'width': '14%',
                                        'createdCell': function(td, cellData, rowData, row, col) {
                                            var input = $('<input type="number" class="form-control" id="receivedqty" style="width: 100%" value="0" min="0" max="' + parseInt(rowData.qty) + '">');
                                            
                                            // Disable input if quantity is 0
                                            if (rowData.qty == 0) {
                                                input.prop('disabled', true);
                                            }
                                            
                                            $(td).html(input).addClass('align-middle');
                                            
                                            input.on('input', function(e) {
                                                var receivedqty = $(this).val();
                                                if (receivedqty > parseInt(rowData.qty)) {
                                                    e.preventDefault();
                                                    $(this).val(rowData.qty);
                                                } else {
                                                    var amount = rowData.amount;
                                                    var rtotal = receivedqty * amount;
                                                    $(td).closest('tr').find('td:eq(5)').text(rtotal.toLocaleString());

                                                    var total = 0;
                                                    $('#purchase_items_table_edit tbody tr').each(function() {
                                                        total += parseFloat($(this).find('td:eq(5)').text().replace(/,/g, ''));
                                                    });
                                                    $('#grand_total_amount_edit').text(total.toLocaleString());
                                                }
                                            });
                                        }
                                    },
                                    {
                                        'targets': 5,
                                        'orderable': false,
                                        'createdCell': function(td, cellData, rowData, row, col) {
                                            $(td).html('<span id="rtotal">0</span>').addClass('align-middle');
                                        }
                                    }
                                ]
                            });
                        }

                        // function po_itemsTabless() {

                        //     $("#purchase_items_table_edit").DataTable({
                        //         destroy: true,
                        //         autoWidth: false,
                        //         data: receiving_items,
                        //         info: false,
                        //         lengthChange: false,
                        //         searching: false,
                        //         paging: false,
                        //         columns: [{
                        //                 "data": "description"
                        //             },
                        //             {
                        //                 "data": "amount"
                        //             },
                        //             {
                        //                 "data": "qty"
                        //             },

                        //             {
                        //                 "data": "total"
                        //             },
                        //             {
                        //                 "data": "receivedqty"
                        //             },
                        //             {
                        //                 "data": "rtotal"
                        //             }
                        //         ],
                        //         columnDefs: [{
                        //                 'targets': 0,
                        //                 'orderable': false,
                        //                 'createdCell': function(td, cellData,
                        //                     rowData, row, col) {
                        //                     $(td).html(
                        //                             `<span data-id="${rowData.itemid}">${rowData.description}</span>`
                        //                         )
                        //                         .addClass('align-middle');
                        //                 }
                        //             },
                        //             {
                        //                 'targets': 1,
                        //                 'orderable': false,
                        //                 'createdCell': function(td, cellData,
                        //                     rowData, row, col) {
                        //                     $(td).html(rowData.amount).addClass(
                        //                         'align-middle');
                        //                 }
                        //             },
                        //             {
                        //                 'targets': 2,
                        //                 'orderable': false,
                        //                 'createdCell': function(td, cellData,
                        //                     rowData, row, col) {
                        //                     $(td).html(rowData.qty).addClass(
                        //                         'align-middle');
                        //                 }
                        //             },
                        //             {
                        //                 'targets': 3,
                        //                 'orderable': false,
                        //                 'createdCell': function(td, cellData,
                        //                     rowData, row, col) {
                        //                     $(td).html(rowData.total)
                        //                         .addClass(
                        //                             'align-middle');
                        //                 }
                        //             },
                        //             {
                        //                 'targets': 4,
                        //                 'orderable': false,
                        //                 'width': '14%',

                        //                 'createdCell': function(td, cellData,
                        //                     rowData, row, col) {
                        //                     $(td).html(
                        //                         '<input type="number" class="form-control" id="receivedqty" style="width: 100%" value="' +
                        //                         0 +
                        //                         '" min="0" max="' +
                        //                         parseInt(rowData.qty) + '">'
                        //                     );
                        //                     $(td).find('input').on('input',
                        //                         function(e) {
                        //                             var receivedqty = $(
                        //                                 this).val();
                        //                             if (receivedqty >
                        //                                 parseInt(rowData
                        //                                     .qty)) {
                        //                                 e.preventDefault();
                        //                                 $(this).val(rowData
                        //                                     .qty);
                        //                             } else {
                        //                                 // var amount = rowData
                        //                                 //     .amount;
                        //                                 // var rtotal =
                        //                                 //     receivedqty *
                        //                                 //     amount;
                        //                                 // $(td).closest('tr')
                        //                                 //     .find(
                        //                                 //         'td:eq(5)')
                        //                                 //     .text(rtotal);

                        //                                 var amount = rowData
                        //                                     .amount;
                        //                                 var rtotal =
                        //                                     receivedqty *
                        //                                     amount;
                        //                                 $(td).closest('tr')
                        //                                     .find(
                        //                                         'td:eq(5)')
                        //                                     .text(rtotal
                        //                                         .toLocaleString()
                        //                                     );

                        //                                 var total = 0;
                        //                                 $('#purchase_items_table_edit tbody tr')
                        //                                     .each(
                        //                                         function() {
                        //                                             total +=
                        //                                                 parseFloat(
                        //                                                     $(
                        //                                                         this
                        //                                                     )
                        //                                                     .find(
                        //                                                         'td:eq(5)'
                        //                                                     )
                        //                                                     .text()
                        //                                                     .replace(
                        //                                                         /,/g,
                        //                                                         ''
                        //                                                     )
                        //                                                 );
                        //                                         });
                        //                                 $('#grand_total_amount_edit')
                        //                                     .text(total
                        //                                         .toLocaleString()
                        //                                     );

                        //                                 // $('#grand_total_amount_edit').text($('#purchase_items_table_edit tbody tr').map(function(){ 
                        //                                 //     return parseFloat($(this).find('td:eq(5)').text().replace(/,/g, ''));
                        //                                 // }).get().reduce(function(a, b){
                        //                                 //     return a + b;
                        //                                 // }, 0).toLocaleString());
                        //                             }
                        //                         });
                        //                 }
                        //             },
                        //             {
                        //                 'targets': 5,
                        //                 'orderable': false,
                        //                 'createdCell': function(td, cellData,
                        //                     rowData, row, col) {
                        //                     $(td).html('<span id="rtotal">' +
                        //                             0 + '</span>')
                        //                         .addClass(
                        //                             'align-middle');
                        //                 }
                        //             }
                        //         ]
                        //     });
                        // }


                        // po_itemsTabless()

                        // function po_itemsTabless() {

                        //     $("#purchase_items_table_edit").DataTable({
                        //         destroy: true,
                        //         autoWidth: false,
                        //         data: receiving_items,
                        //         info: false,
                        //         lengthChange: false,
                        //         searching: false,
                        //         paging: false,
                        //         columns: [{
                        //                 "data": "description"
                        //             },
                        //             {
                        //                 "data": "amount"
                        //             },
                        //             {
                        //                 "data": "qty"
                        //             },

                        //             {
                        //                 "data": "total"
                        //             },
                        //             {
                        //                 "data": "receivedqty"
                        //             },
                        //             {
                        //                 "data": "rtotal"
                        //             }
                        //         ],
                        //         columnDefs: [{
                        //                 'targets': 0,
                        //                 'orderable': false,
                        //                 'createdCell': function(td, cellData,
                        //                     rowData, row, col) {
                        //                     $(td).html(
                        //                             `<span data-id="${rowData.itemid}">${rowData.description}</span>`
                        //                         )
                        //                         .addClass('align-middle');
                        //                 }
                        //             },
                        //             {
                        //                 'targets': 1,
                        //                 'orderable': false,
                        //                 'createdCell': function(td, cellData,
                        //                     rowData, row, col) {
                        //                     $(td).html(rowData.amount).addClass(
                        //                         'align-middle');
                        //                 }
                        //             },
                        //             {
                        //                 'targets': 2,
                        //                 'orderable': false,
                        //                 'createdCell': function(td, cellData,
                        //                     rowData, row, col) {
                        //                     $(td).html(rowData.qty).addClass(
                        //                         'align-middle');
                        //                 }
                        //             },
                        //             {
                        //                 'targets': 3,
                        //                 'orderable': false,
                        //                 'createdCell': function(td, cellData,
                        //                     rowData, row, col) {
                        //                     $(td).html(rowData.total)
                        //                         .addClass(
                        //                             'align-middle');
                        //                 }
                        //             },
                        //             {
                        //                 'targets': 4,
                        //                 'orderable': false,
                        //                 'width': '14%',

                        //                 'createdCell': function(td, cellData,
                        //                     rowData, row, col) {
                        //                     $(td).html(
                        //                         '<input type="number" class="form-control" id="receivedqty" style="width: 100%" value="' +
                        //                         rowData.receivedqty +
                        //                         '" min="0" max="' +
                        //                         parseInt(rowData.qty) + '">'
                        //                     );
                        //                     $(td).find('input').on('input',
                        //                         function(e) {
                        //                             var receivedqty = $(
                        //                                 this).val();
                        //                             if (receivedqty >
                        //                                 parseInt(rowData
                        //                                     .qty)) {
                        //                                 e.preventDefault();
                        //                                 $(this).val(rowData
                        //                                     .qty);
                        //                             } else {
                        //                                 var amount = rowData
                        //                                     .amount;
                        //                                 var rtotal =
                        //                                     receivedqty *
                        //                                     amount;
                        //                                 $(td).closest('tr')
                        //                                     .find(
                        //                                         'td:eq(5)')
                        //                                     .text(rtotal);
                        //                             }
                        //                         });
                        //                 }
                        //             },
                        //             {
                        //                 'targets': 5,
                        //                 'orderable': false,
                        //                 'createdCell': function(td, cellData,
                        //                     rowData, row, col) {
                        //                     $(td).html('<span id="rtotal">' +
                        //                             rowData.rtotal + '</span>')
                        //                         .addClass(
                        //                             'align-middle');
                        //                 }
                        //             }
                        //         ]
                        //     });
                        // }




                    }
                });

            });

            const poInput = $('#invoice_no_edit');
            poInput.on('keydown', function(e) {
                const value = $(this).val();
                if (value === 'INV-' && (e.key === 'Delete' || e.key ===
                        'Backspace')) {
                    e.preventDefault(); // Block delete and backspace
                }
            });
            poInput.on('input', function() {
                const value = $(this).val();
                if (value === 'INV-') {
                    $(this).val('INV-');
                }
            });

            const poInput_disbursepo = $('#expenses_voucher_no_disburse_po');
            poInput_disbursepo.on('keydown', function(e) {
                const value = $(this).val();
                if (value === 'CV-' && (e.key === 'Delete' || e.key === 'Backspace') || value === 'CHV-' &&
                    (e.key === 'Delete' || e.key === 'Backspace')) {
                    e.preventDefault(); // Block delete and backspace
                }
            });
            poInput_disbursepo.on('input', function() {
                const value = $(this).val();
                if (value === 'CV-' || value === 'CHV-') {
                    $(this).val(value);
                }
            });

            ////////////////////////////////////////////////////
            let grandTotalDisplayed_disburse = false;
            $('#add_disbursement_po').on('hidden.bs.modal', function(e) {
                $('.account-entry_po_disburse').remove();
                grandTotalDisplayed_disburse = false; // Reset the flag when modal is hidden
            });

            $('.btn-success[data-target="#addaccountsetupModal_po_disburse"]').click(function() {
                let accountSelectId = 'account-select-po-disburse-add-' + Date.now();
                const grand_total_amount__disburse_po = parseFloat($('#grand_total_amount__disburse_po')
                    .text().replace(/,/g, ''));
                const newEntryHtml = `
                        <div class="form-row account-entry_po_disburse mt-4">
                            <div class="form-group col-md-4">
                                <label style="font-weight: 600; font-size: 13px;">Account</label>
                                <select class="form-control account-select-po-disburse" id="${accountSelectId}"
                                    style="width: 90%; height: 30px; font-size: 12px; border-radius: 5px; ">
                                    <option value="">Select Account</option>
                                    @php
                                        $coa = DB::table('chart_of_accounts')->get();
                                    @endphp
                                    @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                        <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
                                        @foreach (DB::table('bk_sub_chart_of_accounts')->where('deleted', 0)->where('coaid', $coa->id)->get() as $subcoa)
                                            <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcoa->sub_code }} - 
                                                {{ $subcoa->sub_account_name }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label style="font-weight: 600; font-size: 13px;">Debit Account</label>
                                <input type="text" class="form-control debit-input-po-disburse" value="${grandTotalDisplayed_disburse ? '0.00' : grand_total_amount__disburse_po}"
                                    style="width: 90%; height: 30px; font-size: 12px; border-radius: 5px; ">
                            </div>
                            <div class="form-group col-md-4 d-flex align-items-center">
                                <div style="width: 100%;">
                                    <label style="font-weight: 600; font-size: 13px;">Credit Account</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control credit-input-po-disburse" value="0.00"
                                            style="height: 30px; font-size: 12px; border-radius: 5px; ">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-sm ml-3 remove-account-entry-po-disburse" style="background-color: transparent;">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                grandTotalDisplayed_disburse = true;
                $('#po-disburse-account-entry-container').append(newEntryHtml);

                setTimeout(function() {
                    $(`#${accountSelectId}`).select2({
                        placeholder: "Select Account",
                        allowClear: true,
                        theme: 'bootstrap4',
                        width: '100%'
                    }).on('select2:open', function(e) {
                        // Force high z-index for dropdown
                        $('.select2-container').css('z-index', 99999);
                        $('.select2-dropdown').css('z-index', 99999);
                    });
                }, 100);
                // Add event listeners to the newly created inputs
                const newEntry = $('#po-disburse-account-entry-container .account-entry_po_disburse')
                    .last();
                newEntry.find('.debit-input-po-disburse, .credit-input-po-disburse').on('input',
                    function() {
                        const row = $(this).closest('.account-entry_po_disburse');
                        const debitInput = row.find('.debit-input-po-disburse');
                        const creditInput = row.find('.credit-input-po-disburse');

                        // If this input has a value (other than 0.00), clear the other one
                        if ($(this).val() !== '0.00' && $(this).val() !== '') {
                            if ($(this).hasClass('debit-input-po-disburse')) {
                                creditInput.val('0.00');
                            } else {
                                debitInput.val('0.00');
                            }
                        }
                    });
                // Remove entry
                $(document).on('click', '.remove-account-entry-po-disburse', function() {
                    if ($('.account-entry_po_disburse').length > 1) {
                        $(this).closest('.account-entry_po_disburse').remove();
                        po_disburse_calculateTotals();
                    }
                });
            });

            $(document).on('input', '.debit-input-po-disburse, .credit-input-po-disburse', function() {
                po_disburse_calculateTotals();
            });

            // Calculate Totals
            function po_disburse_calculateTotals() {
                var debitTotal = 0;
                var creditTotal = 0;

                $('.account-entry_po_disburse').each(function() {
                    var debit = parseFloat($(this).find('.debit-input-po-disburse').val());
                    var credit = parseFloat($(this).find('.credit-input-po-disburse').val());

                    debitTotal += isNaN(debit) ? 0 : debit;
                    creditTotal += isNaN(credit) ? 0 : credit;
                });

                // $('.form-row.mb-3 input').each(function(index) {
                $('#po-disburse-total-row input').each(function(index) {
                    if (index === 0) {
                        $(this).val('Debit: ' + debitTotal.toFixed(2) + ' | Credit: ' + creditTotal.toFixed(
                            2));
                    } else if (index === 1) {
                        $(this).val(debitTotal.toFixed(2));
                    } else {
                        $(this).val(creditTotal.toFixed(2));
                    }
                });
            }

            // Check every 500ms
            setInterval(function() {
                po_disburse_calculateTotals()
                const po_disburse_debit = parseFloat($('#po_total_debit_amount').val()) || 0;
                const po_disburse_credit = parseFloat($('#po_total_credit_amount').val()) || 0;
                const expenses_voucher_no_disburse_po = $('#expenses_voucher_no_disburse_po').val();
                // const po_disburse_receiving_account = $('.account-entry_po-disburse .account-select-po-disburse').val();
                // console.log('Selected Receiving Account:', po_disburse_receiving_account);

                if (expenses_voucher_no_disburse_po == '' && po_disburse_debit !== po_disburse_credit) {
                    $('#saveDisbursement_disburse_po').prop('disabled', true);
                } else if (expenses_voucher_no_disburse_po == '' && po_disburse_debit ==
                    po_disburse_credit) {
                    $('#saveDisbursement_disburse_po').prop('disabled', true);
                } else if (expenses_voucher_no_disburse_po != '' && po_disburse_debit ==
                    po_disburse_credit) {
                    $('#saveDisbursement_disburse_po').prop('disabled', false);
                } else if (expenses_voucher_no_disburse_po != '' && po_disburse_debit !==
                    po_disburse_credit) {
                    $('#saveDisbursement_disburse_po').prop('disabled', true);
                }

            }, 500);


        });


        $(document).on('click', '#edit_supplier', function() {
            var id = $(this).data('id');
            $('#editpurchaseOrderModal').data('id', id).modal('show');
        })

        // $(document).on('click', '#btnconfirm', function() {
        //     // var transid = $(this).attr('data-id');
        //     var receiving_history_id = $('#receiving_history_id').val();
        //     var uname = $('#voiduname').val();
        //     var pword = $('#voidpword').val();
        //     var remarks = $('#voidremarks').val();
        //     // console.log(uname)

        //     var tableData = [];

        //     $("#receiving_history_items_table_edit tbody tr").each(function() {
        //         var row = $(this).data();
        //         if (row) {
        //             tableData.push({
        //                 itemId: $(this).find('td:eq(0) span').data('id'),
        //                 poItem: $(this).find('td:eq(0)').text(),
        //                 amount: $(this).find('td:eq(1)').text(),
        //                 old_quantity: $(this).find('td:eq(2)').text(),
        //                 old_total: (parseFloat($(this).find('td:eq(1)').text()) *
        //                         parseFloat($(this).find('td:eq(2)').text()))
        //                     .toString(),
        //                 // old_total: $(this).find('td:eq(3)').text(),

        //                 // quantity:  parseFloat($(this).find('td:eq(2)').text().replace(
        //                 //     ',', '')) +  parseFloat($(this).find('td:eq(4)').text().replace(
        //                 //     ',', '')),
        //                 // total: parseFloat($(this).find('td:eq(3)').text().replace(
        //                 //     ',', '')) + parseFloat($(this).find(
        //                 //         'td:eq(5)')
        //                 //     .text().replace(',', '')) || 0,
        //                 receivedqty: parseFloat($(this).find('td:eq(4)').text().replace(
        //                     ',', '')),
        //                 rtotal: parseFloat($(this).find('td:eq(5)').text().replace(
        //                     ',', ''))
        //                 // Get text from 6th cell
        //             });
        //         }
        //     });

        //     $.ajax({
        //         url: '/bookkeeper/v2/v2_voidtransactions',
        //         method: 'GET',
        //         data: {
        //             receiving_history_id: receiving_history_id,
        //             uname: uname,
        //             pword: pword,
        //             remarks: remarks,
        //             po_items: tableData,
        //         },
        //         dataType: 'json',
        //         success: function(data) {
        //             // console.log(data.feesid);
        //             // if (0 > 1) {
        //             //     $('#v2_btntranssearch').trigger('click');
        //             //     $('#modal-voidpermission').modal('hide');
        //             //     updateledger(data.studid, data.syid, data.semid, data.feesid, data
        //             //         .esURL);

        //             // } else {
        //             const Toast = Swal.mixin({
        //                 toast: true,
        //                 position: 'top',
        //                 showConfirmButton: false,
        //                 timer: 3000,
        //                 timerProgressBar: true,
        //                 onOpen: (toast) => {
        //                     toast.addEventListener('mouseenter', Swal
        //                         .stopTimer)
        //                     toast.addEventListener('mouseleave', Swal
        //                         .resumeTimer)
        //                 }
        //             });

        //             if (data.return == 1) {
        //                 Toast.fire({
        //                     type: 'success',
        //                     title: 'Transaction successfully voided.'
        //                 });

        //                 po_itemsTabless();
        //                 resetForm();

        //                 // Refresh the transaction list or perform any other necessary updates
        //                 $('#v2_btntranssearch').trigger('click');
        //             } 

        //             else if (data.return == 2) {
        //                 Toast.fire({
        //                     type: 'warning',
        //                     title: 'Please fill the remarks to void the transaction.'
        //                 });

        //                 $('#voidremarks').focus();
        //             } else if (data.return == 3) {
        //                 Toast.fire({
        //                     type: 'error',
        //                     title: 'You are not Authorized! Please contact your Finance Admin!'
        //                 });

        //                 $('#voidremarks').focus();
        //             } else if (data.return == 5) {
        //                 Toast.fire({
        //                     type: 'warning',
        //                     title: 'User has no permission to void transactions'
        //                 });
        //             } else if (data.return == 0) {
        //                 Toast.fire({
        //                     type: 'warning',
        //                     title: 'Invalid User or Password.'
        //                 });
        //             }
        //             // }
        //         }
        //     });
        // });

        $(document).on('click', '#btnconfirm', function() {
            // Gather form data
            var receiving_history_id = $('#receiving_history_id').val();
            var uname = $('#voiduname').val();
            var pword = $('#voidpword').val();
            var remarks = $('#voidremarks').val();

            // Prepare item data from table
            var tableData = [];
            $("#receiving_history_items_table_edit tbody tr").each(function() {
                var row = $(this).data();
                if (row) {
                    tableData.push({
                        itemId: $(this).find('td:eq(0) span').data('id'),
                        poItem: $(this).find('td:eq(0)').text(),
                        amount: parseFloat($(this).find('td:eq(1)').text().replace(',', '')),
                        receivedqty: parseFloat($(this).find('td:eq(4)').text().replace(',', '')),
                        rtotal: parseFloat($(this).find('td:eq(5)').text().replace(',', ''))
                    });
                }
            });

            // Make AJAX request
            $.ajax({
                url: '/bookkeeper/v2/v2_voidtransactions',
                method: 'GET',
                data: {
                    receiving_history_id: receiving_history_id,
                    uname: uname,
                    pword: pword,
                    remarks: remarks,
                    po_items: tableData,
                },
                dataType: 'json',
                success: function(data) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });

                    switch (data.return) {
                        case 1:
                            Toast.fire({
                                type: 'success',
                                title: 'Transaction successfully voided.'
                            });
                            $('#edit_receiving_history_Modal').modal('hide');
                            // $('body').removeClass('modal-open');
                            $('.modal-backdrop')
                                .remove(); // Manually removes lingering backdrop 
                            resetForm();
                            // po_itemsTabless();
                            received_history_table();

                            break;
                        case 2:
                            Toast.fire({
                                type: 'warning',
                                title: 'Please fill the remarks to void the transaction.'
                            });
                            $('#voidremarks').focus();
                            break;
                        case 3:
                            Toast.fire({
                                type: 'error',
                                title: 'You are not Authorized! Please contact your Finance Admin!'
                            });
                            break;
                        case 5:
                            Toast.fire({
                                type: 'warning',
                                title: 'User has no permission to void transactions'
                            });
                            break;
                        case 0:
                        default:
                            Toast.fire({
                                type: 'warning',
                                title: 'Invalid User or Password.'
                            });
                    }
                }
            });
        });

        function resetForm() {
            $('#voiduname').val("");
            $('#voidpword').val("");
            $('#voidremarks').val("");
            received_history_table()

            function received_history_table() {

                $("#received_history_table").DataTable({
                    destroy: true,
                    autoWidth: false,
                    paging: false,
                    info: false,
                    ajax: {
                        url: '/bookkeeper/received_history/fetch',
                        type: 'GET',
                        dataSrc: function(json) {
                            return json;
                        }
                    },
                    columns: [{
                            "data": "invoiceno"
                        },
                        {
                            "data": "suppliername"
                        },
                        {
                            "data": "remarks"
                        },
                        {
                            "data": "amount"
                        },
                        {
                            "data": "datedelivered"
                        },
                        {
                            "data": "rstatus"
                        },
                        {
                            "data": null
                        },

                    ],
                    columnDefs: [

                        {
                            'targets': 0,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.invoiceno).addClass(
                                    'align-middle');
                            }
                        },
                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.suppliername).addClass(
                                    'align-middle');
                            }
                        },

                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                // $(td).html('');
                                $(td).html(rowData.remarks).addClass('align-middle');

                            }
                        },

                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.amount).addClass(
                                    'align-middle');
                            }
                        },

                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(moment(rowData.datedelivered).format('YYYY-MM-DD'))
                                    .addClass('text-left align-middle');
                            }
                        },

                        {
                            'targets': 5,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.rstatus).addClass('text-left align-middle');
                            }
                        },
                        {
                            'targets': 6,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData) {
                                var link = `<a href="javascript:void(0)" 
                                              class="edit_receiving_history_items" 
                                              data-id="${rowData.id}" 
                                              data-toggle="modal" 
                                              data-target="#edit_receiving_history_Modal"
                                              style="color:red;text-decoration: underline;">
                                                Void
                                            </a>`;
                                $(td).html(link).addClass(
                                    'text-center align-middle font-weight-bold');
                            }
                        }

                    ],
                    createdRow: function(row, data, dataIndex) {
                        $(row).addClass('hoverable-row');
                    },
                    // lengthMenu: [
                    //     [10, 25, 50, 100],
                    //     [10, 25, 50, 100]
                    // ],
                    // pageLength: 10,
                    // dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                    //     "<'row'<'col-sm-12'tr>>" +
                    //     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

                });
            }

            po_supplierTable()

            function po_supplierTable() {

                $("#receiving_accounting_supplier_table").DataTable({
                    destroy: true,
                    // data:temp_subj,
                    // bInfo: true,
                    autoWidth: false,
                    paging: false,
                    info: false,
                    // lengthChange: true,
                    // stateSave: true,
                    // serverSide: true,
                    // processing: true,
                    ajax: {
                        url: '/bookkeeper/receiving/fetch',
                        type: 'GET',
                        dataSrc: function(json) {

                            return json;
                        }
                    },
                    columns: [{
                            "data": "refno"
                        },
                        {
                            "data": "suppliername"
                        },
                        {
                            "data": "remarks"
                        },
                        {
                            "data": "totalamount"
                        },
                        {
                            "data": "postdatetime"
                        },
                        {
                            "data": "dstatus"
                        },
                        // {
                        //     "data": null
                        // },

                    ],
                    columnDefs: [

                        {
                            'targets': 0,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                // $(td).html(rowData.refno).addClass(
                                //     'align-middle');

                                $(td).addClass('clickable-td edit_purchase_order').attr('id',
                                    'edit_supplier').attr('data-id', rowData.id).html(rowData
                                    .refno.split(
                                        ' ')[0]).css('cursor', 'pointer');
                            }
                        },


                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.suppliername).addClass(
                                    'align-middle');

                                $(td).addClass('clickable-td edit_purchase_order').attr('id',
                                    'edit_supplier').attr('data-id', rowData.id).html(
                                    rowData.suppliername.split(' ')[0]).css('cursor', 'pointer');
                            }
                        },

                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var text = rowData.remarks === null ? '' : rowData.remarks;
                                $(td).html(text).addClass('align-middle');

                                $(td).addClass('clickable-td edit_purchase_order').attr('id',
                                    'edit_supplier').attr('data-id', rowData.id).html(text
                                    .split(' ')[0]).css('cursor', 'pointer');

                            }
                        },

                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                // $(td).html(rowData.totalamount).addClass(
                                //     'align-middle');


                                $(td).addClass('clickable-td edit_purchase_order').attr('id',
                                    'edit_supplier').attr('data-id', rowData.id).html(rowData
                                    .totalamount.split(
                                        ' ')[0]).css('cursor', 'pointer');
                            }
                        },

                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                // $(td).html(rowData.postdatetime.split(' ')[0]).addClass(
                                //     'align-middle');

                                $(td).addClass('clickable-td edit_purchase_order').attr('id',
                                    'edit_supplier').attr('data-id', rowData.id).html(rowData
                                    .postdatetime.split(' ')[0]).css('cursor', 'pointer');

                            }
                        },

                        {
                            'targets': 5,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                // $(td).html(rowData.dstatus).addClass(
                                //     'align-middle');

                                $(td).addClass('clickable-td edit_purchase_order').attr('id',
                                    'edit_supplier').attr('data-id', rowData.id).html(rowData
                                    .dstatus.split(
                                        ' ')[0]).css('cursor', 'pointer');
                            }
                        },


                        // {
                        //     'targets': 6,
                        //     'orderable': false,
                        //     'createdCell': function(td, cellData, rowData, row, col) {

                        //         var edit_button =
                        //             '<button type="button" class="btn btn-sm btn-primary edit_purchase_order" id="edit_supplier" data-id="' +
                        //             rowData.id +
                        //             '" ><i class="far fa-edit"></i></button>';
                        //         var delete_button =
                        //             '<button type="button" class="btn btn-sm btn-danger delete_purchase_orderss" id="delete_supplier" data-id="' +
                        //             rowData.id +
                        //             '"><i class="far fa-trash-alt"></i></button>';
                        //         $(td)[0].innerHTML = edit_button + ' ' + delete_button;
                        //         $(td).addClass('text-center align-middle');
                        //     }
                        // }

                    ],
                    createdRow: function(row, data, dataIndex) {
                        $(row).addClass('hoverable-row');
                    },
                    // lengthMenu: [
                    //     [10, 25, 50, 100],
                    //     [10, 25, 50, 100]
                    // ],
                    // pageLength: 10,
                    // dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                    //     "<'row'<'col-sm-12'tr>>" +
                    //     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

                });
            }
        }

        // function refreshTransactionList() {
        //     $('#v2_btntranssearch').trigger('click');
        // }

        /////////////////////////
    </script>
@endsection
