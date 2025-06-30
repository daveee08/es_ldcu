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
        $coa = DB::table('chart_of_accounts')->get();
    @endphp
    <div class="container-fluid ml-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="fas fa-file-alt ml-2 mt-2 mb-2" style="font-size: 33px;"></i> <i
                    class="fas fa-pencil-alt mt-2 mr-3 mb-2" style="font-size: 18px;"></i>
                <h1 class="text-black m-0">Purchase Order</h1>

            </div>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Purchase Order</li>
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
                    <a class="nav-link active" href="/bookkeeper/purchase_order"
                        class="nav-link {{ Request::url() == url('/purchase_order') ? 'active' : '' }}"
                        style="color: black; font-weight: 600; background-color: #d9d9d9; border-top-left-radius: 10px; border-top-right-radius: 10px;">Purchase
                        Order</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/bookkeeper/receiving"
                        class="nav-link {{ Request::url() == url('/bookkeeper/receiving') ? 'active' : '' }}"
                        style="color: black;">Receiving
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
                            {{-- @foreach (DB::table('purchasing_supplier')->join('chart_of_accounts', 'purchasing_supplier.coaid', '=', 'chart_of_accounts.id')->where('purchasing_supplier.deleted', 0)->select('purchasing_supplier.id as supplierid', 'purchasing_supplier.suppliername')->get() as $supplier)
                                <option value="{{ $supplier->suppliername }}">{{ $supplier->suppliername }}</option>
                            @endforeach --}}

                            @foreach (DB::table('purchasing_supplier')->leftjoin('chart_of_accounts', 'purchasing_supplier.coaid', '=', 'chart_of_accounts.id')->leftjoin('bk_sub_chart_of_accounts', 'purchasing_supplier.coaid', '=', 'bk_sub_chart_of_accounts.id')->where('purchasing_supplier.deleted', 0)->select('purchasing_supplier.id as supplierid', 'purchasing_supplier.suppliername')->get() as $supplier)
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
                            <option value="POSTED">POSTED</option>
                            <option value="CANCELLED">CANCELLED</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>



        <div class="row bg-white py-3">
            <div class="col-lg-12 col-md-12">
                <div class="table-responsive w-100">
                    <div class="row py-3">
                        <div class="col-md-12">
                            <button class="btn btn-success btn-sm" id="add_suppliers" style="background-color: #015918;"
                                data-toggle="modal" data-target="#add_purchase_order">
                                + Add Purchase Order <i class="fas fa-grip-horizontal"></i>
                            </button>
                        </div>
                    </div>
                    <table id="po_accounting_supplier_table" class="table table-sm w-100">
                        <thead class="table-secondary">
                            <tr>
                                <th class="text-left">Reference No.</th>
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
            style="display: none; z-index: 1060;" aria-hidden="true">
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
                                <input type="text" class="form-control" name="po_number" id="poRefNumber"
                                    placeholder="Enter Ref. No.">
                            </div>
                            <div class="col-md-4">
                                <label for="supplier">Supplier</label>
                                <select class="form-control" id="select_supplier">
                                    {{-- <option selected>Select Supplier</option>
                                    <option>Supplier 1</option>
                                    <option>Supplier 2</option> --}}
                                    <option value="" selected disabled>Select Suppliers</option>
                                    @foreach (DB::table('purchasing_supplier')->leftjoin('chart_of_accounts', 'purchasing_supplier.coaid', '=', 'chart_of_accounts.id')->leftjoin('bk_sub_chart_of_accounts', 'purchasing_supplier.coaid', '=', 'bk_sub_chart_of_accounts.id')->where('purchasing_supplier.deleted', 0)->select('purchasing_supplier.id as supplierid', 'purchasing_supplier.suppliername')->get() as $item)
                                        <option value="{{ $item->supplierid }}">{{ $item->suppliername }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="date">Date</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" id="podate"
                                        value="{{ date('Y-m-d') }}">
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
                    <div class="modal-footer" style="display: flex;justify-content: center;align-items: center;">
                        <button type="button" class="btn btn-success savePurchaseOrder"
                            id="savePurchaseOrder">SAVE</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Item Modal -->
        <div class="modal fade" id="addItemModal" role="dialog" style="display: none; z-index: 1060;"
            aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                    <div class="modal-header " style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                        <h5 class="modal-title" id="addItemModalLabel">Add Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            id="close_add_item_purchase_modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Item Selection -->
                        <div class="form-group">
                            <label for="selectItem">Item</label>
                            <select class="form-control" id="selectItem">
                                {{-- <option value="0" selected>Select Items</option> --}}
                                {{-- @foreach (DB::table('bk_expenses_items')->where('deleted', 0)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->description }}</option>
                                @endforeach --}}
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

        <!-- New Expense Item Modal -->
        <div class="modal fade" id="newExpenseItemModal" style="display: none; z-index: 1070;" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content overflow-hidden" style="border-radius: 16px;">
                    <div class="modal-header" style="background-color:#cac9c9; border-top--radius: 16px !important;">
                        <h5 class="modal-title" id="newExpenseItemModalLabel">New Expenses Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Item Name -->
                        <div class="form-group">
                            <label>Item Name</label>
                            <input type="text" class="form-control " placeholder="Item Name" id="item_name">
                        </div>

                        <!-- Item Code & Quantity -->
                        <div class="form-row">
                            <div class="col-md-6">
                                <label>Item Code</label>
                                <input type="text" class="form-control" placeholder="Code" id="item_code">
                            </div>
                            <div class="col-md-6">
                                <label>Quantity</label>
                                <input type="number" class="form-control" placeholder="QTY" id="item_quantity">
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="form-group mt-3">
                            <label>Amount</label>
                            <input type="text" class="form-control" id="item_amount">
                        </div>

                        <!-- Item Type -->
                        <div class="form-group row">
                            <label class="col-3">Item Type</label>
                            <div class="form-check col-6">
                                <input class="form-check-input" type="radio" name="itemType" id="nonInventory"
                                    value="non-inventory">
                                <label class="form-check-label" for="nonInventory">Non Inventory Item</label>
                            </div>
                            <div class="form-check col-3">
                                <input class="form-check-input" type="radio" name="itemType" id="inventory"
                                    value="inventory" checked>
                                <label class="form-check-label" for="inventory">Inventory</label>
                            </div>
                        </div>

                        <!-- Debit Account -->
                        <div class="form-group">
                            <label>Debit Account</label>
                            <select class="form-control" id="debit_account">
                                <option value="" selected disabled>Assign Debit Account</option>
                                {{-- @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                    <option value="{{ $coa->id }}">{{ $coa->account_name }}</option>
                                @endforeach --}}
                                @foreach (DB::table('chart_of_accounts')->get() as $coa)
                                    <option value="{{ $coa->id }}">{{ $coa->code }} -
                                        {{ $coa->account_name }}</option>
                                    @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->get() as $subcoa)
                                        <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            {{ $subcoa->sub_code }} - {{ $subcoa->sub_account_name }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Footer with Save Button -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success stock_in">Save</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Purchase Order Modal -->
        <div class="modal fade" id="editpurchaseOrderModal" data-backdrop="static" role="dialog"
            style="display: none; z-index: 1060;" aria-hidden="true">

            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                    <div class="modal-header " style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                        <h5 class="modal-title" id="purchaseOrderModalLabel">Purchase Order</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <input type="text" id="po_id" hidden>
                                <label for="poReferenceNumber">PO Reference Number</label>
                                <input type="text" class="form-control" id="poReferenceNumber_edit">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="supplier">Supplier</label>
                                <select class="form-control" id="supplier_edit">
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="poDate">Date</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" id="poDate_edit">

                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between p-3">
                            <div></div> <!-- Empty div to push the button -->
                            {{-- <button class="btn btn-primary"><i class="fas fa-print"></i> Print</button> --}}
                        </div>
                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm purchase_items_table_edit"
                                id="purchase_items_table_edit">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Amount</th>
                                        <th>QTY</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right font-weight-bold">Total</th>
                                        <th id="grand_total_amount_edit" class="font-weight-bold"></th>

                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right font-weight-bold">Disbursed Amount</th>
                                        <th id="disbursed_balance" class="font-weight-bold"></th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right text-primary font-weight-bold">Disbursed
                                            Balance</th>
                                        <th id="disbursed_balance_balance" class="font-weight-bold text-primary"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Remarks -->
                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <input type="text" class="form-control" id="remarks_edit" placeholder="Enter Remarks">
                        </div>
                        <input type="text" class="form-control" id="PO_status_disburse" hidden>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">

                            <div>
                                <button class="btn btn-success approved_PO_status" id="approved_PO_status"
                                    value="POSTED">Approved</button>
                                <button class="btn btn-danger disapproved_PO_status" id="disapproved_PO_status"
                                    value="CANCELLED">Disapproved</button>
                                <button class="btn btn-primary" id="process_disbursement_btn" data-id="0"
                                    data-toggle="modal" data-target="#add_disbursement_po" disabled>Process
                                    Disbursement</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}

        <div class="modal fade" id="edit_editpurchaseOrderModal" data-backdrop="static" role="dialog"
            style="display: none; z-index: 1060;" aria-hidden="true">

            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                    <div class="modal-header " style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                        <h5 class="modal-title" id="purchaseOrderModalLabel">Purchase Order</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <input type="text" id="po_id_edit_edit" hidden>
                                <label for="poReferenceNumber">PO Reference Number</label>
                                <input type="text" class="form-control" id="poReferenceNumber_edit_edit">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="supplier">Supplier</label>
                                <select class="form-control" id="supplier_edit_edit">
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="poDate">Date</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" id="poDate_edit_edit">

                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <div>
                                <button type="button" class="btn btn-success btn-xsm" data-toggle="modal"
                                    data-target="#addItemModalforSelectedItem_edit"><i class="fa fa-plus"></i> Add
                                    Item</button>
                            </div> <!-- Empty div to push the button -->
                            {{-- <button class="btn btn-primary"><i class="fas fa-print"></i> Print</button> --}}
                        </div>
                        <!-- Table -->
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-sm purchase_items_table_edit_edit"
                                id="purchase_items_table_edit_edit">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Amount</th>
                                        <th>QTY</th>
                                        <th>Total</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right font-weight-bold">Total</th>
                                        <th colspan="2" style="text-align:left;" id="grand_total_amount_edit_edit"
                                            class="font-weight-bold"></th>
                                        {{-- <th></th> --}}

                                    </tr>


                                </tfoot>
                            </table>
                        </div>

                        <!-- Remarks -->
                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <input type="text" class="form-control" id="remarks_edit_edit"
                                placeholder="Enter Remarks">
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-success update_PO" id="update_PO">SAVE</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ////////////////////////////////////////////////////////////////// --}}



        <div class="modal fade" id="add_disbursement_po" role="dialog" style="display: none; z-index: 1060;"
            aria-hidden="true">
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
                                                style=" width: 90%; height: 30px; font-size: 12px; border-radius: 5px;  text-align: right"
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
                        <button type="button" class="btn btn-success saveDisbursement"
                            id="saveDisbursement_disburse_po">SAVE</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addItemModalforSelectedItem_edit"role="dialog" style="display: none; z-index: 1060;"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content overflow-hidden" style="border-radius: 16px;">
                    <div class="modal-header" style="background-color:#cac9c9; border-top--radius: 16px !important;">
                        <h5 class="modal-title" id="addItemModalLabel">Add Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="itemName">Item Name</label>
                            <select class="form-control select2" id="itemName_selected_edit_new" name="itemName">
                                {{-- <option value="">Select Item</option>
                                <!-- The "Add New Item" option -->
                                @foreach (DB::table('bk_expenses_items')->where('deleted', 0)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->description }}</option>
                                @endforeach
                                <option style="color:blue;  text-decoration: underline;" value="add">+ Add New
                                    Item</option> --}}
                            </select>

                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" id="amount_selected_edit_new" name="amount"
                                placeholder="Enter Amount" required>
                        </div>
                        <div class="form-group">
                            <label for="qty">Quantity</label>
                            <input type="number" class="form-control" id="qty_selected_edit_new" name="qty"
                                placeholder="Enter QTY" required>
                        </div>
                        <div class="form-group">
                            <label for="totalAmount">Total Amount</label>
                            <input type="number" class="form-control" id="totalAmount_selected_edit_new"
                                name="totalAmount" placeholder="0.00" disabled>
                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                            <button class="btn btn-success" id="addItemBtn_selected_edit_new">Save</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addItemModalforSelectedItem"role="dialog" style="display: none; z-index: 1060;"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content overflow-hidden" style="border-radius: 16px;">
                    <div class="modal-header" style="background-color:#cac9c9; border-top--radius: 16px !important;">
                        <h5 class="modal-title" id="addItemModalLabel">Add Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="itemName">Item Name</label>
                            <select class="form-control select2" id="itemName_selected" name="itemName">
                                <option value="">Select Item</option>
                                <!-- The "Add New Item" option -->
                                @foreach (DB::table('bk_expenses_items')->where('deleted', 0)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->description }}</option>
                                @endforeach
                                <option style="color:blue;  text-decoration: underline;" value="add">+ Add New
                                    Item</option>
                            </select>

                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" id="amount_selected" name="amount"
                                placeholder="Enter Amount" required>
                        </div>
                        <div class="form-group">
                            <label for="qty">Quantity</label>
                            <input type="number" class="form-control" id="qty_selected" name="qty"
                                placeholder="Enter QTY" required>
                        </div>
                        <div class="form-group">
                            <label for="totalAmount">Total Amount</label>
                            <input type="number" class="form-control" id="totalAmount_selected" name="totalAmount"
                                placeholder="0.00" disabled>
                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                            <button class="btn btn-success" id="addItemBtn_selected">Save</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editItemModal_selected" role="dialog" style="display: none; z-index: 1060;"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content overflow-hidden" style="border-radius: 16px;">
                    <div class="modal-header" style="background-color:#cac9c9; border-top--radius: 16px !important;">
                        <h5 class="modal-title" id="addItemModalLabel">Edit Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addItemForm">
                            <div class="form-group">
                                <label for="itemName">Item Name</label>
                                <select class="form-control select2" id="itemName_edit_selected_selected" name="itemName"
                                    required>
                                    <option value="">Select Item</option>
                                    <!-- The "Add New Item" option -->
                                    @foreach (DB::table('bk_expenses_items')->where('deleted', 0)->get() as $item)
                                        <option value="{{ $item->id }}">{{ $item->description }}</option>
                                    @endforeach
                                    <option style="color:blue;  text-decoration: underline;" value="add">+ Add New
                                        Item</option>

                                </select>

                            </div>
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="number" class="form-control" id="amount_edit_selected_selected"
                                    name="amount" placeholder="Enter Amount" required>
                            </div>
                            <div class="form-group">
                                <label for="qty">Quantity</label>
                                <input type="number" class="form-control" id="qty_edit_selected_selected"
                                    name="qty" placeholder="Enter QTY" required>
                            </div>
                            <div class="form-group">
                                <label for="totalAmount">Total Amount</label>
                                <input type="number" class="form-control" id="totalAmount_edit_selected_selected"
                                    name="totalAmount" placeholder="0.00" disabled>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <button class="btn btn-primary" id="updateItemBtn_selected_selected">Update</button>
                            </div>
                        </form>
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


            // function getNextRefNo() {
            //     @php
                //         // Get the highest PO reference number from the database
                //         $purchasing = DB::table('purchasing')->where('deleted', 0)->select('refno')->orderBy('refno', 'desc')->first();

                //         // If no records exist, start with PO0001, otherwise increment the highest found
                //         if ($purchasing) {
                //             $refno = $purchasing->refno;
                //             $num = (int) substr($refno, 2); // Extract the numeric part
                //             $nextRefNo = 'PO' . str_pad($num + 1, 4, '0', STR_PAD_LEFT);
                //         } else {
                //             $nextRefNo = 'PO0001';
                //         }
                //
            @endphp
            //     return $nextRefNo;
            // }

            // $(document).on('click', '#add_suppliers', function() {
            //     $('#poRefNumber').val(getNextRefNo());
            // });





            $('#debit_account').select2({
                placeholder: "Select Credit Account",
                allowClear: true,
                theme: 'bootstrap4'
            }).on('select2:open', function(e) {
                // Force high z-index for dropdown
                $('.select2-container').css('z-index', 99999);
                $('.select2-dropdown').css('z-index', 99999);
            });

            // $('#selectItem').change(function() {
            $(document).on('change', '#selectItem', function() {
                let itemId = $(this).val();
                if ($(this).val() === "add") {
                    $('#newExpenseItemModal').modal();
                } else {
                    $.ajax({
                        url: '/bookkeeper/selected_item/fetch',
                        type: 'GET',
                        data: {
                            itemId: itemId
                        },
                        success: function(data) {

                            var selected_inventory_account = data
                                .chart_of_accounts_default_inventory;
                            var coa_all = data.chart_of_accounts;
                            var bk_sub_chart_of_accounts = data.bk_sub_chart_of_accounts;

                            $("#debit_account").empty().trigger('change');
                            $("#debit_account").append(
                                `<option value="" selected disabled>Select Debit Account</option>`
                            );
                            coa_all.forEach(coa => {
                                let selected = selected_inventory_account &&
                                    selected_inventory_account.id === coa.id ?
                                    'selected' : '';
                                $("#debit_account").append(
                                    `<option value="${coa.id}" ${selected}>${coa.code} - ${coa.account_name}</option>`
                                );
                                bk_sub_chart_of_accounts.forEach(subcoa => {
                                    if (subcoa.coaid == coa.id) {
                                        $("#debit_account").append(
                                            `<option value="${subcoa.id}">&nbsp;&nbsp;${subcoa.sub_code} - ${subcoa.sub_account_name}</option>`
                                        );
                                    }
                                });
                            });

                            if (data.selected_item.length > 0) {
                                let item = data.selected_item[0];
                                $('#amount').val(item.amount);
                                $('#quantity').val(item.qty);
                                let total = item.amount * item.qty;
                                $('#total').val(total);
                            }

                        }
                    });
                }
            });
            $(document).on('change', '#itemName_selected_edit_new', function() {
                let itemId = $(this).val();
                if ($(this).val() === "add") {
                    $('#newExpenseItemModal').modal();
                } else {
                    $.ajax({
                        url: '/bookkeeper/selected_item/fetch',
                        type: 'GET',
                        data: {
                            itemId: itemId
                        },
                        success: function(data) {

                            if (data.length > 0) {
                                let item = data[0];
                                // $('#amount').val(item.amount);
                                // $('#quantity').val(item.qty);
                                // let total = item.amount * item.qty;
                                // $('#total').val(total);
                            }
                        }
                    });
                }
            });


            $('#amount, #quantity').on('input', function() {
                var amount = $('#amount').val();
                var qty = $('#quantity').val();
                let total = amount * qty;
                $('#total').val(total);
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

                            // Get the next reference number after successful save
                            $.ajax({
                                type: 'GET',
                                url: '/bookkeeper/purchase_order/get_next_refno',
                                success: function(response) {
                                    $("#poRefNumber").val(response.refno);
                                }
                            });

                            $("#poRefNumber").val("PO");
                            $("#select_supplier").val("").trigger('change');
                            $("#podate").val("");
                            $("#poremarks").val("");
                            $("#grand_total_amount").text('0.00');
                            po_supplierTable()
                            $("#add_purchase_order").modal('hide');
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop')
                                .remove(); // Manually removes lingering backdrop
                            // getNextRefNo()


                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });

            });


            $(document).on('click', '#add_suppliers', function() {
                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/purchase_order/get_next_refno',
                    success: function(response) {
                        $("#poRefNumber").val(response.refno);
                    }
                });
            });

            $("#close_purchase_modal").click(function() {
                var table = $("#purchase_items_table");
                table.find("tbody .appended-row").remove();
                // $("#select_supplier").val("").trigger('change');
                // $("#podate").val("");
                // $("#poremarks").val("");
                $("#grand_total_amount").text('0.00');

                var hasData =
                    // $("#podate").val().trim() !== "" ||
                    // $("#poRefNumber").val().trim() !== "PO0001" ||
                    $("#poremarks").val().trim() !== "";


                if ($('#select_supplier').val()) {
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

                            $('#podate').val("");
                            $('#poRefNumber').val("PO");
                            $('#poremarks').val("");

                            $('#select_supplier').val("").trigger('change');

                        } else {

                            $('#add_purchase_order').modal('show');
                        }
                    });
                } else {

                    $('#add_purchase_order').modal('hide');
                }


            });

            po_supplierTable()

            function po_supplierTable() {

                $("#po_accounting_supplier_table").DataTable({
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
                        url: '/bookkeeper/purchase_order/fetch',
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
                        {
                            "data": null
                        },

                    ],
                    columnDefs: [

                        {
                            'targets': 0,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).addClass('clickable-td edit_purchase_order').attr('id',
                                    'edit_supplier').attr('data-id', rowData.id).attr(
                                    'data-toggle', 'modal').attr('data-target',
                                    '#editpurchaseOrderModal').html(rowData.refno).css('cursor',
                                    'pointer');
                            }
                        },


                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).addClass('clickable-td edit_purchase_order').attr('id',
                                    'edit_supplier').attr('data-id', rowData.id).attr(
                                    'data-toggle', 'modal').attr('data-target',
                                    '#editpurchaseOrderModal').html(rowData.suppliername).css(
                                    'cursor', 'pointer');
                            }
                        },

                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                // $(td).html('');
                                $(td).addClass('clickable-td edit_purchase_order').attr('id',
                                    'edit_supplier').attr('data-id', rowData.id).attr(
                                    'data-toggle', 'modal').attr('data-target',
                                    '#editpurchaseOrderModal').html(rowData.remarks).css(
                                    'cursor', 'pointer');

                            }
                        },

                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).addClass('clickable-td edit_purchase_order').attr('id',
                                    'edit_supplier').attr('data-id', rowData.id).attr(
                                    'data-toggle', 'modal').attr('data-target',
                                    '#editpurchaseOrderModal').html(rowData.totalamount).css(
                                    'cursor', 'pointer');
                            }
                        },

                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).addClass('clickable-td edit_purchase_order').attr('id',
                                    'edit_supplier').attr('data-id', rowData.id).attr(
                                    'data-toggle', 'modal').attr('data-target',
                                    '#editpurchaseOrderModal').html(rowData.postdatetime.split(
                                    ' ')[0]).css('cursor', 'pointer');

                            }
                        },

                        {
                            'targets': 5,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var statusText = rowData.dstatus;
                                var statusColor = statusText === 'POSTED' ? 'blue' : (statusText ===
                                    'SUBMITTED' ? 'green' : 'initial');
                                $(td).addClass('clickable-td edit_purchase_order').attr('id',
                                    'edit_supplier').attr('data-id', rowData.id).attr(
                                    'data-toggle', 'modal').attr('data-target',
                                    '#editpurchaseOrderModal').html(statusText).css({
                                    'cursor': 'pointer',
                                    'color': statusColor
                                });
                            }
                        },


                        {
                            'targets': 6,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var buttons =
                                    '<a href="javascript:void(0)" class="edit_edit_purchase_order" id="edit_supplier" data-id="' +
                                    rowData.id +
                                    '" data-toggle="modal" data-target="#edit_editpurchaseOrderModal"><i class="far fa-edit text-primary"></i></a>' +
                                    '&nbsp;&nbsp;' +
                                    '<a href="javascript:void(0)" class="delete_purchase_order" id="delete_supplier" data-id="' +
                                    rowData.id +
                                    '"><i class="far fa-trash-alt text-danger"></i></a>';
                                $(td)[0].innerHTML = buttons;
                                $(td).addClass('text-center align-middle');
                            }
                        }

                    ],
                    createdRow: function(row, data, dataIndex) {
                        $(row).addClass('hoverable-row');
                    },
                    order: [
                        [4, "desc"]
                    ],
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

            $(document).on('click', '.edit_edit_purchase_order', function() {

                var po_id = $(this).attr('data-id')

                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/purchase_order/edit',
                    data: {
                        po_id: po_id
                    },
                    success: function(response) {

                        var po_selected = response.PO;
                        var po_items = response.PO_items;
                        var all_supplier = response.purchasing_supplier;
                        // var total_disbursed_bal = response.PO_disbursements_balance;
                        // console.log('total_disbursed_bal:', total_disbursed_bal);

                        // if (po_selected[0].dstatus == "POSTED") {
                        //     $("#process_disbursement_btn").removeAttr('disabled');

                        // } else {
                        //     $("#process_disbursement_btn").attr('disabled', 'disabled');

                        // }



                        $("#po_id_edit_edit").val(po_selected[0].id);

                        $("#process_disbursement_btn").attr("data-id", po_selected[0].id);


                        $("#poReferenceNumber_edit_edit").val(po_selected[0].refno);
                        // $("#poDate_edit").val(po_selected[0].postdatetime.split(' ')[0]);
                        // $("#poDate_edit").val(po_selected[0].postdatetime.split(' ')[0]);
                        $("#poDate_edit_edit").val(po_selected[0].postdatetime.split(' ')[0]);
                        $("#remarks_edit_edit").val(po_selected[0].remarks);

                        $("#supplier_edit_edit").empty().trigger('change');
                        $("#supplier_edit_edit").append(
                            '<option value="" selected disabled>Select Supplier</option>'
                        );
                        all_supplier.forEach(all_supplier => {
                            if (all_supplier.id == po_selected[0]
                                .supplierid) {
                                $("#supplier_edit_edit").append(
                                    `<option value="${all_supplier.id}" selected>${all_supplier.suppliername}</option>`
                                );
                            } else {
                                $("#supplier_edit_edit").append(
                                    `<option value="${all_supplier.id}">${all_supplier.suppliername}</option>`
                                );
                            }
                        });

                        // var grand_total_amount = 0
                        // po_items.forEach(item => {
                        //     grand_total_amount += parseFloat(item.totalamount)
                        // })

                        // $("#grand_total_amount_edit").text(grand_total_amount.toFixed(2))

                        // var disbursedBalance = total_disbursed_bal[0] ? parseFloat(total_disbursed_bal[0].totalamount.replace(/,/g, '')) : 0;
                        // console.log('totalDisbursedBalance: ', disbursedBalance);


                        // $("#disbursed_balance").text(disbursedBalance.toFixed(2));




                        po_itemsTable_edit_edit()

                        function po_itemsTable_edit_edit() {
                            $("#purchase_items_table_edit_edit").DataTable({
                                destroy: true,
                                autoWidth: false,
                                searching: false,
                                paging: false,
                                info: false,
                                data: po_items,
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
                                        "data": "totalamount"
                                    },
                                    {
                                        "data": null
                                    }
                                ],
                                columnDefs: [{
                                        'targets': 0,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html(
                                                    `<span data-expensesid='1' data-id="${rowData.itemid}">${rowData.description}</span>`
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
                                            $(td).html(rowData.totalamount)
                                                .addClass(
                                                    'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 4,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html(
                                                    `<a href="javascript:void(0)" class="ml-2 remove-append-row-selected-record text-danger" data-id="${rowData.id} data-poid="${rowData.headerid}">Cancel</a>`
                                                )
                                                .addClass(
                                                    'align-middle text-center');
                                        }
                                    }
                                ],
                                drawCallback: function() {
                                    var api = this.api();
                                    var total = api.column(3).data().reduce(
                                        function(a, b) {
                                            return parseFloat(a) + parseFloat(
                                                b);
                                        }, 0);

                                    $("#grand_total_amount_edit_edit").text(total
                                        .toLocaleString());
                                }
                            });
                        }

                        $(document).on('click', '.remove-append-row-selected-record',
                            function() {
                                var deletepoId = $(this).attr('data-id')
                                var delete_mainpoId = $(this).attr('data-poid')
                                console.log(deletepoId);
                                console.log(delete_mainpoId);
                                Swal.fire({
                                    text: 'Are you sure you want to remove this item?',
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Remove'
                                }).then((result) => {
                                    if (result.value) {
                                        delete_po_item(deletepoId, delete_mainpoId)

                                    }
                                })
                            });

                        function delete_po_item(deletepoId, delete_mainpoId) {

                            $.ajax({
                                type: 'GET',
                                url: '/bookkeeper/purchase_order/delete_item',
                                data: {
                                    deletepoId: deletepoId

                                },
                                success: function(data) {
                                    if (data[0].status == 1) {
                                        Toast.fire({
                                            type: 'success',
                                            title: 'Successfully deleted'
                                        })

                                        // $('#edit_editpurchaseOrderModal').modal(
                                        //     'hide')
                                        // $('body').removeClass('modal-open');
                                        // $('.modal-backdrop')
                                        //     .remove(); // Manually removes lingering backdrop 
                                        // $('#edit_editpurchaseOrderModal').modal(
                                        //     'show')
                                        po_supplierTable()
                                        // $('.edit_edit_purchase_order').click();
                                        $('#edit_editpurchaseOrderModal').modal(
                                            'hide');
                                        // $('body').removeClass('modal-open');
                                        $('.modal-backdrop')
                                            .remove(); // Manually removes lingering backdrop 
                                        // setTimeout(() => {
                                        //     $('#edit_editpurchaseOrderModal').data('id', delete_mainpoId).modal('show');
                                        // }, 500);

                                    } else {
                                        Toast.fire({
                                            type: 'error',
                                            title: data[0].message
                                        })
                                    }
                                }
                            });
                        }




                    }
                });

            });


            // $(document).on('click', '.edit_purchase_order', function() {

            //     var po_id = $(this).attr('data-id')

            //     $.ajax({
            //         type: 'GET',
            //         url: '/bookkeeper/purchase_order/edit',
            //         data: {
            //             po_id: po_id
            //         },
            //         success: function(response) {

            //             var po_selected = response.PO;
            //             var po_items = response.PO_items;
            //             var all_supplier = response.purchasing_supplier;
            //             var total_disbursed_bal = response.PO_disbursements_balance;

            //             // if (po_selected[0].dstatus == "POSTED") {
            //             //     $("#process_disbursement_btn").removeAttr('disabled');

            //             // } else {
            //             //     $("#process_disbursement_btn").attr('disabled', 'disabled');

            //             // }

            //             $("#po_id").val(po_selected[0].id);

            //             $("#process_disbursement_btn").attr("data-id", po_selected[0].id);


            //             $("#poReferenceNumber_edit").val(po_selected[0].refno);
            //             // $("#poDate_edit").val(po_selected[0].postdatetime.split(' ')[0]);
            //             // $("#poDate_edit").val(po_selected[0].postdatetime.split(' ')[0]);
            //             $("#poDate_edit").val(po_selected[0].postdatetime.split(' ')[0]);
            //             $("#remarks_edit").val(po_selected[0].remarks);

            //             $("#supplier_edit").empty().trigger('change');
            //             $("#supplier_edit").append(
            //                 '<option value="" selected disabled>Select Supplier</option>'
            //             );
            //             all_supplier.forEach(all_supplier => {
            //                 if (all_supplier.id == po_selected[0]
            //                     .supplierid) {
            //                     $("#supplier_edit").append(
            //                         `<option value="${all_supplier.id}" selected>${all_supplier.suppliername}</option>`
            //                     );
            //                 } else {
            //                     $("#supplier_edit").append(
            //                         `<option value="${all_supplier.id}">${all_supplier.suppliername}</option>`
            //                     );
            //                 }
            //             });

            //             // $("#grand_total_amount_edit").text(po_selected[0].totalamount);

            //             var disbursedBalance = total_disbursed_bal[0]?.totalamount ? parseFloat(
            //                 total_disbursed_bal[0].totalamount.replace(/,/g, '')) : 0;
                     

            //             $("#disbursed_balance").text(disbursedBalance >= 1000 ? disbursedBalance
            //                 .toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",") :
            //                 disbursedBalance.toFixed(0));


            //             // $("#disbursed_balance").text(disbursedBalance.toFixed(2));

            //             var po_status = po_selected[0].dstatus;
            //             $("#PO_status_disburse").val(po_status);

            //             // setInterval(function() {
                      
            //             if (po_status == "POSTED") {
            //                 $('#approved_PO_status').prop('disabled', true);
            //                 $('#disapproved_PO_status').prop('disabled', true);
                           
            //             } else {
            //                 $('#approved_PO_status').prop('disabled', false);
            //                 $('#disapproved_PO_status').prop('disabled', false);
                           
            //             }

            //             // setInterval(function() {

            //             const po_total_items = $('#grand_total_amount_edit').text().replace(/,/g, '');
            //             const po_disburse_total_items = $('#disbursed_balance').text().replace(/,/g, '');


            //             const totalItems = parseFloat(po_total_items);
            //             const disbursedItems = parseFloat(po_disburse_total_items);


            //             if (disbursedItems < totalItems && po_status == "POSTED") {
            //                 $('#process_disbursement_btn').prop('disabled', false);
            //             }
            //              else {
            //                 $('#process_disbursement_btn').prop('disabled', true);
            //             }


            //             // }, 500);

            //             var disbursedBalanceValue = parseFloat($("#disbursed_balance").text().replace(/,/g, ''));
            //             console.log('Disbursed Balance Value:', disbursedBalanceValue);
                        
            //             var totalAmountReceivingReceivedValue = parseFloat($("#grand_total_amount_edit").text().replace(/,/g, ''));
            //             console.log('Total Amount Receiving Received Value:', totalAmountReceivingReceivedValue);

            //             var result = disbursedBalanceValue - totalAmountReceivingReceivedValue;
            //             console.log('Result:', result);

            //             $("#disbursed_balance_balance").text(Math.abs(result).toFixed(0).replace(/[^\d]/g, ""));
            //             console.log('Disbursed Balance Balance Text:', $("#disbursed_balance_balance").text());

                    




            //             po_itemsTable_edit()

            //             function po_itemsTable_edit() {

            //                 $("#purchase_items_table_edit").DataTable({
            //                     destroy: true,
            //                     autoWidth: false,
            //                     searching: false,
            //                     paging: false,
            //                     info: false,
            //                     data: po_items,
            //                     columns: [{
            //                             "data": "description"
            //                         },
            //                         {
            //                             "data": "amount"
            //                         },
            //                         {
            //                             "data": "qty"
            //                         },
            //                         {
            //                             "data": "totalamount"
            //                         }
            //                     ],
            //                     columnDefs: [{
            //                             'targets': 0,
            //                             'orderable': false,
            //                             'createdCell': function(td, cellData,
            //                                 rowData, row, col) {
            //                                 $(td).html(
            //                                         `<span data-id="${rowData.itemid}">${rowData.description}</span>`
            //                                     )
            //                                     .addClass('align-middle');
            //                             }
            //                         },
            //                         {
            //                             'targets': 1,
            //                             'orderable': false,
            //                             'createdCell': function(td, cellData,
            //                                 rowData, row, col) {
            //                                 $(td).html(rowData.amount).addClass(
            //                                     'align-middle');
            //                             }
            //                         },
            //                         {
            //                             'targets': 2,
            //                             'orderable': false,
            //                             'createdCell': function(td, cellData,
            //                                 rowData, row, col) {
            //                                 $(td).html(rowData.qty).addClass(
            //                                     'align-middle');
            //                             }
            //                         },
            //                         {
            //                             'targets': 3,
            //                             'orderable': false,
            //                             'createdCell': function(td, cellData,
            //                                 rowData, row, col) {
            //                                 $(td).html(rowData.totalamount)
            //                                     .addClass(
            //                                         'align-middle');
            //                             }
            //                         }
            //                     ],
            //                     drawCallback: function() {
            //                         var api = this.api();
            //                         var total = api.column(3).data().reduce(
            //                             function(a, b) {
            //                                 return parseFloat(a) + parseFloat(
            //                                     b);
            //                             }, 0);
            //                         $("#grand_total_amount_edit").text(total
            //                             .toLocaleString());
            //                     }
            //                 });
            //             }




            //         }
            //     });

            // });

            $(document).on('click', '.edit_purchase_order', function() {
                var po_id = $(this).attr('data-id');

                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/purchase_order/edit',
                    data: {
                        po_id: po_id
                    },
                    success: function(response) {
                        var po_selected = response.PO;
                        var po_items = response.PO_items;
                        var all_supplier = response.purchasing_supplier;
                        var total_disbursed_bal = response.PO_disbursements_balance;

                        $("#po_id").val(po_selected[0].id);
                        $("#process_disbursement_btn").attr("data-id", po_selected[0].id);

                        $("#poReferenceNumber_edit").val(po_selected[0].refno);
                        $("#poDate_edit").val(po_selected[0].postdatetime.split(' ')[0]);
                        $("#remarks_edit").val(po_selected[0].remarks);

                        $("#supplier_edit").empty().trigger('change');
                        $("#supplier_edit").append(
                            '<option value="" selected disabled>Select Supplier</option>'
                        );
                        all_supplier.forEach(all_supplier => {
                            if (all_supplier.id == po_selected[0].supplierid) {
                                $("#supplier_edit").append(
                                    `<option value="${all_supplier.id}" selected>${all_supplier.suppliername}</option>`
                                );
                            } else {
                                $("#supplier_edit").append(
                                    `<option value="${all_supplier.id}">${all_supplier.suppliername}</option>`
                                );
                            }
                        });

                        // Calculate and display disbursed balance
                        var disbursedBalance = total_disbursed_bal[0]?.totalamount ? 
                            parseFloat(total_disbursed_bal[0].totalamount.replace(/,/g, '')) : 0;
                        
                        $("#disbursed_balance").text(
                            disbursedBalance >= 1000 ? 
                            disbursedBalance.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",") :
                            disbursedBalance.toFixed(0)
                        );

                        var po_status = po_selected[0].dstatus;
                        $("#PO_status_disburse").val(po_status);

                        if (po_status == "POSTED") {
                            $('#approved_PO_status').prop('disabled', true);
                            $('#disapproved_PO_status').prop('disabled', true);
                        } else {
                            $('#approved_PO_status').prop('disabled', false);
                            $('#disapproved_PO_status').prop('disabled', false);
                        }

                        // Initialize DataTable and calculate totals
                        po_itemsTable_edit();

                        function po_itemsTable_edit() {
                            $("#purchase_items_table_edit").DataTable({
                                destroy: true,
                                autoWidth: false,
                                searching: false,
                                paging: false,
                                info: false,
                                data: po_items,
                                columns: [
                                    { "data": "description" },
                                    { "data": "amount" },
                                    { "data": "qty" },
                                    { "data": "totalamount" }
                                ],
                                columnDefs: [
                                    {
                                        'targets': 0,
                                        'orderable': false,
                                        'createdCell': function(td, cellData, rowData, row, col) {
                                            $(td).html(
                                                `<span data-id="${rowData.itemid}">${rowData.description}</span>`
                                            ).addClass('align-middle');
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
                                            $(td).html(rowData.qty).addClass('align-middle');
                                        }
                                    },
                                    {
                                        'targets': 3,
                                        'orderable': false,
                                        'createdCell': function(td, cellData, rowData, row, col) {
                                            $(td).html(rowData.totalamount).addClass('align-middle');
                                        }
                                    }
                                ],
                                drawCallback: function() {
                                    var api = this.api();
                                    var total = api.column(3).data().reduce(function(a, b) {
                                        return parseFloat(a) + parseFloat(b);
                                    }, 0);
                                    
                                    // Format and display the total amount
                                    var formattedTotal = total.toLocaleString(undefined, {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                    $("#grand_total_amount_edit").text(formattedTotal);
                                    
                                    // Calculate the balance after DataTable is fully loaded
                                    calculateDisbursedBalanceBalance();
                                }
                            });
                        }

                        function calculateDisbursedBalanceBalance() {
                            // Wait a brief moment to ensure all values are updated
                            setTimeout(function() {
                                var disbursedBalanceText = $("#disbursed_balance").text().replace(/,/g, '');
                                var totalAmountText = $("#grand_total_amount_edit").text().replace(/,/g, '');
                                
                                var disbursedBalanceValue = parseFloat(disbursedBalanceText) || 0;
                                var totalAmountValue = parseFloat(totalAmountText) || 0;
                                
                                console.log('Disbursed Balance Value:', disbursedBalanceValue);
                                console.log('Total Amount Value:', totalAmountValue);
                                
                                var result = totalAmountValue - disbursedBalanceValue;
                                console.log('Result:', result);
                                
                                // Format the result with 2 decimal places and proper commas
                                var formattedResult = Math.abs(result).toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                                
                                $("#disbursed_balance_balance").text(formattedResult);
                                console.log('Disbursed Balance Balance Text:', formattedResult);

                                // Enable/disable disbursement button based on balance
                                if (result > 0 && po_status == "POSTED") {
                                    $('#process_disbursement_btn').prop('disabled', false);
                                } else {
                                    $('#process_disbursement_btn').prop('disabled', true);
                                }
                            }, 100);
                        }
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
                $('#po_accounting_supplier_table').DataTable().draw(); // trigger redraw
            });

            $('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                minDateFilter = '';
                maxDateFilter = '';
                $('#po_accounting_supplier_table').DataTable().draw(); // trigger redraw
            });

            //supplier name filter
            var selectedSupplier = '0';

            $('#supplierName').on('change', function() {
                selectedSupplier = $(this).val();
                $('#po_accounting_supplier_table').DataTable().draw(); // trigger redraw
            });

            var selectedPO_status = '0';

            $('#poStatus').on('change', function() {
                selectedPO_status = $(this).val();
                $('#po_accounting_supplier_table').DataTable().draw(); // trigger redraw
            });



            // Custom date filter
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                if (settings.nTable.id !== 'po_accounting_supplier_table')
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

            $('.approved_PO_status').on('click', function(event) {
                event.preventDefault();
                // console.log('Approved PO Status: ', $(this).val());

                var approve_po = $(this).val();
                update_po_status_to_approved(approve_po)

                function update_po_status_to_approved(approve_po) {

                    var po_id_edit = $('#po_id').val();

                    var poReferenceNumber_edit = $('#poReferenceNumber_edit').val();
                    var remarks_edit = $('#remarks_edit').val();
                    var poDate_edit = $('#poDate_edit').val();
                    var supplier_edit = $('#supplier_edit').val();

                    // Get the DataTable instance
                    var table = $("#purchase_items_table_edit").DataTable();

                    // Get all the data from the DataTable
                    var tableData = table.data().toArray().map(function(row) {
                        return {
                            itemId: row.itemid, // Assuming your data has itemid property
                            poItem: row.description,
                            amount: row.amount,
                            quantity: row.qty,
                            disbursed_qty: row.qty,
                            disbursed_total: row.totalamount,
                            total: row.totalamount
                        };
                    });

                    $.ajax({
                        type: "GET",
                        url: '/bookkeeper/purchase_order/update_approved',
                        data: {

                            po_id_edit: po_id_edit,
                            poReferenceNumber_edit: poReferenceNumber_edit,
                            remarks_edit: remarks_edit,
                            poDate_edit: poDate_edit,
                            supplier_edit: supplier_edit,

                            po_items: tableData,

                            approve_po: approve_po
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
                                    title: 'Successfully Approved'
                                })

                                po_supplierTable()
                                $("#process_disbursement_btn").removeAttr('disabled');

                                $("#editpurchaseOrderModal").modal('hide');
                                $('.modal-backdrop')
                                .remove();



                            } else if (data[0].status == 3) {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Successfully Approved'
                                })

                                po_supplierTable()
                                $("#process_disbursement_btn").removeAttr('disabled');

                                $("#editpurchaseOrderModal").modal('hide');
                                $('.modal-backdrop')
                                .remove();



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






            $('.disapproved_PO_status').on('click', function(event) {
                event.preventDefault();
                // console.log('Approved PO Status: ', $(this).val());

                var disapproved_po = $(this).val();
                update_po_status_to_disapproved(disapproved_po)

            });


            function update_po_status_to_disapproved(disapproved_po) {

                var po_id_edit = $('#po_id').val();

                var remarks_edit = $('#remarks_edit').val();
                var poDate_edit = $('#poDate_edit').val();
                var supplier_edit = $('#supplier_edit').val();

                $.ajax({
                    type: "GET",
                    url: '/bookkeeper/purchase_order/update_approved',
                    data: {

                        po_id_edit: po_id_edit,
                        remarks_edit: remarks_edit,
                        poDate_edit: poDate_edit,
                        supplier_edit: supplier_edit,

                        approve_po: disapproved_po
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
                                title: 'Successfully Approved'
                            })

                            po_supplierTable()
                            $("#process_disbursement_btn").attr('disabled', 'disabled');


                        } else if (data[0].status == 3) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully Approved'
                            })

                            po_supplierTable()
                            $("#process_disbursement_btn").attr('disabled', 'disabled');


                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }



            $(document).on('click', '#process_disbursement_btn', function() {

                var po_id = $(this).attr('data-id')

                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/purchase_order/edit',
                    data: {
                        po_id: po_id
                    },
                    success: function(response) {

                        var po_selected = response.PO;
                        var po_items = response.PO_items;
                        var all_supplier = response.purchasing_supplier;



                        $("#expenses_disburse_po_id").val(po_selected[0].id);
                        $("#expenses_date_disburse_po").val(po_selected[0].postdatetime.split(
                            ' ')[0]);


                        // $("#poDate_edit").val(po_selected[0].postdatetime.split(' ')[0]);
                        // $("#poDate_edit").val(po_selected[0].postdatetime.split(' ')[0]);

                        $("#expenses_ref_no_disburse_po").val(po_selected[0].refno);

                        $("#expenses_disbursement_to_disburse_po").empty().trigger('change');
                        $("#expenses_disbursement_to_disburse_po").append(
                            '<option value="" selected disabled>Select Supplier</option>'
                        );
                        all_supplier.forEach(all_supplier => {
                            if (all_supplier.id == po_selected[0]
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
                        po_items.forEach(po_item => {
                            total_amount += parseFloat(po_item.totalamount) || 0;
                        });
                        // $("#expenses_amount_disburse_po").val(total_amount.toFixed(2));
                        $("#expenses_amount_disburse_po").val(parseFloat($("#disbursed_balance_balance").text().replace(/,/g, '')).toFixed(2));
                        $("#grand_total_amount_edit").text(po_selected[0].totalamount);

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
                                data: po_items,
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
                                        "data": "totalamount"
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
                                            $(td).html(rowData.qty).addClass(
                                                'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 3,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            // $(td).html(rowData.totalamount)
                                            //     .addClass(
                                            //         'align-middle');
                                            var total = 0;
                                            $.each(po_items, function(i, item) {
                                                total += parseFloat(item
                                                    .totalamount);
                                            });
                                            $('#grand_total_amount__disburse_po')
                                                .html(total.toLocaleString());
                                            $(td).html(parseFloat(rowData
                                                    .totalamount)
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

            $('#amount_selected_edit_new, #qty_selected_edit_new').on('input', function() {
                var amount = $("#amount_selected_edit_new").val();
                var quantity = $("#qty_selected_edit_new").val();
                let total = amount * quantity;
                $('#totalAmount_selected_edit_new').val(total);
            });
            $('#totalAmount_selected_edit_new').val(total);

            // working unta ni hays
            $("#addItemBtn_selected_edit_new").click(function(e) {
                e.preventDefault();
                var selectItemID = $("#itemName_selected_edit_new").val();
                var selectItem = $("#itemName_selected_edit_new option:selected").text();
                var amount = $("#amount_selected_edit_new").val();
                var quantity = $("#qty_selected_edit_new").val();
                var total = $("#totalAmount_selected_edit_new").val();




                if (selectItem.trim() == "" || amount.trim() == "" || quantity.trim() == "" || total
                    .trim() == "") {
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: 'All fields are required',
                    });
                    return;
                }

                var table = $("#purchase_items_table_edit_edit");
                var row = $("<tr class='appended-row'>");

                row.append($("<td data-id='" + selectItemID + "' data-expensesid='0'>").text(selectItem));
                row.append($("<td>").text(amount));
                row.append($("<td>").text(quantity));
                row.append($("<td>").text(total));

                row.append($("<td class='text-center'>").html(

                    '<a href="javascript:void(0)" class="ml-2 remove-append-row-selected text-danger">Cancel</a>'
                ));

                table.find("tbody").append(row);

                let rows = table.find("tbody tr");
                // var grand_total = 0;
                // rows.each(function(index) {
                //     var rowTotal = parseFloat($(this).find("td:eq(3)").text().replace(/,/g, ""));
                //     if (!isNaN(rowTotal)) {
                //         grand_total += rowTotal;
                //     }
                // });
                // $("#grand_total_amount_edit_edit").text(parseFloat(grand_total).toLocaleString());

                table.find("tbody").on('click', '.remove-append-row-selected', function() {
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
                    $("#grand_total_amount_edit_edit").text(parseFloat(grand_total)
                        .toLocaleString());
                });

                Toast.fire({
                    type: 'success',
                    title: 'New Item added successfully'
                });

                $("#itemName_selected_edit_new").val("").trigger('change');
                $("#amount_selected_edit_new").val("");
                $("#qty_selected_edit_new").val("");
                $("#totalAmount_selected_edit_new").val("");

                //////////////////////////////////

                let currentAppendRow; // Variable to store the currently selected row

                var totalAmount = 0;
                // expensesItemsTable
                $("#purchase_items_table_edit_edit tbody tr").each(function() {
                    var rowTotal = parseFloat($(this).find("td:eq(3)").text()
                        .replace(/,/g, ""));
                    if (!isNaN(rowTotal)) {
                        totalAmount += rowTotal;
                    }
                });
                $("#grand_total_amount_edit_edit").text(parseFloat(totalAmount).toLocaleString());

                $('.edit-append-row-selected').on('click', function() {
                    // Get the current row
                    currentAppendRow = $(this).closest('tr');

                    // Extract data from the row
                    const disbursement_item = currentAppendRow.find('td:eq(0)')
                        .text()
                        .trim();
                    const item_amount = currentAppendRow.find('td:eq(1)').text()
                        .trim();
                    const item_quantity = currentAppendRow.find('td:eq(2)').text()
                        .trim();
                    const item_total = currentAppendRow.find('td:eq(3)').text()
                        .trim();

                    // Populate modal inputs
                    $('#itemName_edit_selected').find('option').each(function() {
                        if ($(this).val() == currentAppendRow
                            .find('td:eq(0)').data('id')) {
                            $(this).prop('selected', true);
                            $(this).text(currentAppendRow
                                .find('td:eq(0)').text().trim());
                        }
                    });

                    $('#amount_edit_selected').val(item_amount);
                    $('#qty_edit_selected').val(item_quantity);
                    $('#totalAmount_edit_selected').val(item_total);

                    // Handle update button click
                    $('#updateItemBtn_selected').on('click', function(e) {
                        e.preventDefault();

                        if (currentAppendRow) {
                            const itemName_id = $('#itemName_edit_selected').val()
                                .trim();
                            const itemName_edit = $(
                                    '#itemName_edit_selected option:selected')
                                .text()
                                .trim();
                            const amount_edit = $('#amount_edit_selected').val()
                                .trim();
                            const qty_edit = $('#qty_edit_selected').val().trim();
                            const totalAmount_edit = $(
                                '#totalAmount_edit_selected').val().trim();

                            currentAppendRow.find('td:eq(0)')
                                .text(itemName_edit)
                                .css('color', '')
                                .attr('data-id', itemName_id);
                            currentAppendRow.find('td:eq(1)').text(amount_edit).css('color',
                                '');
                            currentAppendRow.find('td:eq(2)').text(qty_edit).css('color',
                                '');
                            currentAppendRow.find('td:eq(3)').text(totalAmount_edit).css(
                                'color',
                                '');
                        }
                    });
                });

                $('#updateItemBtn_selected').on('click', function(e) {
                    e.preventDefault();

                    if (currentAppendRow) {
                        const itemName_id = $('#itemName_edit_selected').val().trim();
                        const itemName_edit = $('#itemName_edit_selected option:selected').text()
                            .trim();
                        const amount_edit = $('#amount_edit_selected').val().trim();
                        const qty_edit = $('#qty_edit_selected').val().trim();
                        const totalAmount_edit = $('#totalAmount_edit_selected').val().trim();

                        currentAppendRow.find('td:eq(0)')
                            .text(itemName_edit)
                            .css('color', '')
                            .attr('data-expensesitemid', 0).attr('data-id', itemName_id);
                        currentAppendRow.find('td:eq(1)').text(amount_edit).css('color', '');
                        currentAppendRow.find('td:eq(2)').text(qty_edit).css('color', '');
                        currentAppendRow.find('td:eq(3)').text(totalAmount_edit).css('color', '');
                    }
                });
            });

            // $(document).on('change', '#itemName_selected', function() {
            //     var itemId = $(this).val();
            //     if ($(this).val() === "add") {
            //         $('#newExpenseItemModal').modal();
            //     } else {

            //         $.ajax({
            //             url: '/bookkeeper/selected_item/fetch',
            //             type: 'GET',
            //             data: {
            //                 itemId: itemId
            //             },
            //             success: function(data) {

            //                 if (data.length > 0) {
            //                     let item = data[0];
            //                     $('#amount_selected').val(item.amount);
            //                     $('#qty_selected').val(item.qty);
            //                     let total = item.amount * item.qty;
            //                     $('#totalAmount_selected').val(total);
            //                 }
            //             }
            //         });
            //     }
            // });

            // $("#itemName_edit_selected_selected").on("change", function(e) {
            //     e.preventDefault();
            //     var itemId = $(this).val();
            //     if ($(this).val() === "add") {
            //         // Reset dropdown to default
            //         $(this).val("").trigger("change");
            //         // Open the modal
            //         $('#newExpenseItemModal').modal();

            //     } else {

            //         $.ajax({
            //             url: '/bookkeeper/selected_item/fetch',
            //             type: 'GET',
            //             data: {
            //                 itemId: itemId
            //             },
            //             success: function(data) {

            //                 if (data.length > 0) {
            //                     let item = data[0];
            //                     $('#amount_edit_selected_selected').val(item.amount);
            //                     $('#qty_edit_selected_selected').val(item.qty);
            //                     let total = item.amount * item.qty;
            //                     $('#totalAmount_edit_selected_selected').val(total);
            //                 }
            //             }
            //         });
            //     }
            // });

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
                        entries: entries,
                        // payment_type: payment_type,
                        bank: bank,
                        cheque_no: cheque_no,
                        cheque_date: cheque_date,
                        expenses_remarks: expenses_remarks,
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
                            $('#editpurchaseOrderModal').modal('hide');
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop')
                                .remove();

                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }



            // const poInput = $('input[name="po_number"]');
            const poInput = $('#poRefNumber');
            poInput.on('keydown', function(e) {
                const value = $(this).val();
                if (value === 'PO' && (e.key === 'Delete' || e.key === 'Backspace')) {
                    e.preventDefault(); // Block delete and backspace
                }
            });
            poInput.on('input', function() {
                const value = $(this).val();
                if (value === 'PO') {
                    $(this).val('PO');
                }
            });


            // const poInput = $('input[name="po_number"]');
            const poInput_edit = $('#poReferenceNumber_edit');
            poInput_edit.on('keydown', function(e) {
                const value = $(this).val();
                if (value === 'PO' && (e.key === 'Delete' || e.key === 'Backspace')) {
                    e.preventDefault(); // Block delete and backspace
                }
            });
            poInput_edit.on('input', function() {
                const value = $(this).val();
                if (value === 'PO') {
                    $(this).val('PO');
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

            //////////////////////////////////////////
            let grandTotalDisplayed_disburse = false;

            $('#add_disbursement_po').on('hidden.bs.modal', function(e) {
                $('.account-entry_po_disburse').remove();
                grandTotalDisplayed_disburse = false; // Reset the flag when modal is hidden
            });

            // $('#add_disbursement_po').on('hidden.bs.modal', function(e) {
            //     $('.account-entry_po_disburse').empty();
            //     grandTotalDisplayed_disburse = false; // Reset the flag when modal is hidden
            // });
            $('.btn-success[data-target="#addaccountsetupModal_po_disburse"]').click(function() {
                let accountSelectId = 'account-select-po-disburse-add-' + Date.now();
                const grand_total_amount__disburse_po = parseFloat($('#grand_total_amount__disburse_po')
                    .text().replace(/,/g, ''));
                const newEntryHtml = `
                <div class="form-row account-entry_po_disburse mt-4">
                    <div class="form-group col-md-4">
                        <label style="font-weight: 600; font-size: 13px;">Account</label>
                        <select class="form-control account-select-po-disburse" id="${accountSelectId}"
                            style="width: 90%; height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
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
                            style="width: 90%; height: 40px; font-size: 12px; border-radius: 5px; ">
                    </div>
                    <div class="form-group col-md-4 d-flex align-items-center">
                        <div style="width: 100%;">
                            <label style="font-weight: 600; font-size: 13px;">Credit Account</label>
                            <div class="input-group">
                                <input type="text" class="form-control credit-input-po-disburse" value="0.00"
                                    style="height: 40px; font-size: 12px; border-radius: 5px; ">
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

                // $('#account_select_po_disburse_add').select2({
                //     placeholder: "Select Account",
                //     allowClear: true,
                //     theme: 'bootstrap4',
                //     dropdownParent: $('#account_select_po_disburse_add').closest('.modal') // Attach to modal
                // }).on('select2:open', function(e) {
                //     // Force high z-index for dropdown
                //     $('.select2-container').css('z-index', 99999);
                //     $('.select2-dropdown').css('z-index', 99999);
                // });
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
                // console.log('po_total_items:', po_total_items);
                // console.log('po_disburse_total_items:', po_disburse_total_items);
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


            setInterval(function() {

                const po_total_items = $('#grand_total_amount_edit').text().replace(/,/g, '');
                const po_disburse_total_items = $('#disbursed_balance').text().replace(/,/g, '');


                const totalItems = parseFloat(po_total_items);
                const disbursedItems = parseFloat(po_disburse_total_items);

                const POStatus = $('#PO_status_disburse').val();
              


                // Corrected condition for enabling the button
                if (disbursedItems < totalItems && POStatus == 'POSTED') {
                    $('#process_disbursement_btn').prop('disabled', false);
                } else {
                    $('#process_disbursement_btn').prop('disabled', true);
                }


            }, 500);



            $('.stock_in').on('click', function(event) {
                // event.preventDefault();
                stock_in()

            });

            function stock_in() {
                var item_name = $('#item_name').val();
                var item_code = $('#item_code').val();
                var item_quantity = $('#item_quantity').val();
                var item_amount = $('#item_amount').val();
                var itemType = $('input[name="itemType"]:checked').val();
                var debit_account = $('#debit_account').val();

                var hasError = false;

                if (!item_name || !item_code || !item_quantity || !item_amount || !itemType || !debit_account) {
                    hasError = true;
                }

                if (hasError) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Please fill all fields correctly!'
                    });
                    return;
                }

                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/item/create',
                    data: {
                        item_name: item_name,
                        item_code: item_code,
                        item_quantity: item_quantity,
                        item_amount: item_amount,
                        itemType: itemType,
                        debit_account: debit_account

                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully created'
                            })
                            $("#item_name").val("");
                            $("#item_code").val("");
                            $("#item_quantity").val("");
                            $("#item_amount").val("");
                            $("#debit_account").val(null).trigger('change');
                            po_expensesItems()


                        } else if (data[0].status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: 'Item with this description already exists'
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

            po_expensesItems()

            function po_expensesItems() {
                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/all_expenses_items',

                    success: function(response) {
                        console.log(response);

                        var all_items = response;

                        $("#selectItem").empty().trigger('change');
                        $("#selectItem").append(
                            '<option value="" selected disabled>Select Item</option>'
                        );
                        $("#selectItem").append(
                            '<option style="color:blue;  text-decoration: underline;" value="add">+ Add New                                         Item</option>'
                        );
                        all_items.forEach(items => {
                            $("#selectItem").append(
                                `<option value="${items.id}">${items.description}</option>`
                            );
                        });

                        ///////////////////////////////////

                        $("#itemName_selected").empty().trigger('change');
                        $("#itemName_selected").append(
                            '<option value="" selected disabled>Select Item</option>'
                        );
                        $("#itemName_selected").append(
                            '<option style="color:blue;  text-decoration: underline;" value="add">+ Add New                                         Item</option>'
                        );
                        all_items.forEach(items => {
                            $("#itemName_selected").append(
                                `<option value="${items.id}">${items.description}</option>`
                            );
                        });

                        ///////////////////////////////////

                        $("#itemName_selected_edit_new").empty().trigger('change');
                        $("#itemName_selected_edit_new").append(
                            '<option value="" selected disabled>Select Item</option>'
                        );
                        $("#itemName_selected_edit_new").append(
                            '<option style="color:blue;  text-decoration: underline;" value="add">+ Add New                                         Item</option>'
                        );
                        all_items.forEach(items => {
                            $("#itemName_selected_edit_new").append(
                                `<option value="${items.id}">${items.description}</option>`
                            );
                        });
                    }

                });
            }

            $("#close_add_item_purchase_modal").click(function() {
                $("#selectItem").val("").trigger('change');
                $("#amount").val("");
                $("#quantity").val("");
                $("#total").val("");
            });


            $('.update_PO').on('click', function(event) {
                event.preventDefault();
                // console.log('Approved PO Status: ', $(this).val());

                var approve_po = $(this).val();
                update_po_status_to_approved(approve_po)

                function update_po_status_to_approved(approve_po) {

                    var po_id_edit = $('#po_id_edit_edit').val();

                    var poReferenceNumber_edit = $('#poReferenceNumber_edit_edit').val();
                    var remarks_edit = $('#remarks_edit_edit').val();
                    var poDate_edit = $('#poDate_edit_edit').val();
                    var supplier_edit = $('#supplier_edit_edit').val();

                    // Get the DataTable instance
                    var table = $("#purchase_items_table_edit_edit").DataTable();

                    // Get all the data from the DataTable
                    // var tableData = table.data().toArray().map(function(row) {
                    //     return {
                    //         itemId: row.itemid, // Assuming your data has itemid property
                    //         poItem: row.description,
                    //         amount: row.amount,
                    //         quantity: row.qty,
                    //         total: row.totalamount
                    //     };
                    // });

                    // var tableData = $("#purchase_items_table_edit_edit").DataTable().data().toArray().map(
                    //     function(row) {
                    //         return {
                    //             itemId: row.itemid,
                    //             po_newly_addedid: $(`#purchase_items_table_edit_edit tbody tr:eq(${row.index}) td:eq(0)`).data("expensesid"),
                    //             poItem: row.description,
                    //             amount: row.amount,
                    //             quantity: row.qty,
                    //             total: row.totalamount
                    //         };
                    //     });
                    var tableData = $("#purchase_items_table_edit_edit").DataTable().rows().data().toArray()
                        .map(
                            function(row, index) {
                                return {
                                    itemId: row.itemid,
                                    po_newly_addedid: 1,
                                    poItem: row.description,
                                    amount: row.amount,
                                    quantity: row.qty,
                                    total: row.totalamount
                                };
                            });

                    // For manually appended rows (those added via the button click)
                    $("#purchase_items_table_edit_edit tbody tr.appended-row").each(function() {
                        tableData.push({
                            itemId: $(this).find("td:eq(0)").data("id"),
                            po_newly_addedid: $(this).find("td:eq(0)").data("expensesid"),
                            poItem: $(this).find("td:eq(0)").text(),
                            amount: $(this).find("td:eq(1)").text(),
                            quantity: $(this).find("td:eq(2)").text(),
                            total: $(this).find("td:eq(3)").text()
                        });
                    });

                    $.ajax({
                        type: "GET",
                        url: '/bookkeeper/purchase_order/po_update',
                        data: {

                            po_id_edit: po_id_edit,
                            poReferenceNumber_edit: poReferenceNumber_edit,
                            remarks_edit: remarks_edit,
                            poDate_edit: poDate_edit,
                            supplier_edit: supplier_edit,

                            po_items: tableData,

                            // approve_po: approve_po
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
                                    title: 'Successfully Approved'
                                })

                                po_supplierTable()
                                $("#process_disbursement_btn").removeAttr('disabled');
                                $('.edit_edit_purchase_order').click();
                                $('#edit_editpurchaseOrderModal').modal('hide');
                                setTimeout(() => {
                                    $('#edit_editpurchaseOrderModal').data('id',
                                        po_id_edit).modal('show');
                                }, 500);


                            } else if (data[0].status == 3) {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Successfully Approved'
                                })

                                po_supplierTable()
                                $("#process_disbursement_btn").removeAttr('disabled');



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









        });
    </script>
@endsection
