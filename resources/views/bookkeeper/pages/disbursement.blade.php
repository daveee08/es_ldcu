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
        /* box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42) !important; */
        /* border: none !important; */
        height: 30px !important;


    }

    ::placeholder {
        font-size: 12px !important;
    }

    label {
        font-weight: normal !important;
        font-size: 13px !important;
    }

    .dataTables_empty {
        display: none;
    }

    #add_purchase_order .modal-content {
        display: flex;
        flex-direction: column;
        height: 80vh;
        border-radius: 16px !important;
    }

    #add_purchase_order .modal-header {
        flex-shrink: 0;
        background-color: #d9d9d9;
    }

    #add_purchase_order .modal-body {
        flex-grow: 1;
        overflow-y: auto;
    }

    #add_purchase_order .modal-footer {
        flex-shrink: 0;
    }

    #edit_purchase_order .modal-content {
        display: flex;
        flex-direction: column;
        height: 80vh;
        border-radius: 16px !important;
    }

    #edit_purchase_order .modal-header {
        flex-shrink: 0;
        background-color: #d9d9d9;
    }

    #edit_purchase_order .modal-body {
        flex-grow: 1;
        overflow-y: auto;
    }

    #edit_purchase_order .modal-footer {
        flex-shrink: 0;
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

    <body>
        <div class="card p-3">
            <div class="bg-none">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-wallet nav-icon mr-2" style="font-size: 33px;"></i>
                    <h1 class="text-black m-0">Disbursement</h1>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Disbursement</li>
                    </ol>
                </nav>
            </div>



            <!-- Filter Section -->
            <div class="my-2 rounded-4 py-3 px-3" style="background-color: #F0F0F1;">
                <div class="d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-funnel" viewBox="0 0 16 16">
                        <path
                            d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5z" />
                    </svg>
                    <p class="mx-1 my-1">Filter</p>
                </div>

                <form>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="expenses_date" class="font-weight-bold" class="form-label">Date Range</label>
                                <input type="text" id="dateRange" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="employee_id" class="form-label">Employee</label>
                                <select class="form-control" id="employee_id">
                                    <option value="0">Select Employee</option>
                                    @foreach (DB::table('teacher')->where('deleted', 0)->get() as $employee)
                                        <option value="{{ $employee->firstname }}">{{ $employee->firstname }}
                                            {{ $employee->lastname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="department_id" class="form-label">Department</label>
                                <select class="form-control" id="department_id">
                                    <option value="">Select Department</option>
                                    @foreach (DB::table('hr_departments')->where('deleted', 0)->get() as $department)
                                        <option value="{{ $department->department }}">{{ $department->department }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>



            <div class="row bg-white py-3">
                <div class="col-lg-12 col-md-12">
                    <div class="table-responsive">
                        <div class="row py-3">
                            <div class="col-md-12">
                                <button class="btn btn-success btn-sm" id="add_suppliers" style="background-color: #015918;"
                                    data-toggle="modal" data-target="#add_purchase_order">
                                    + Add Disbursement
                                </button>
                                {{-- <button class="btn btn-info btn-sm" id="add_suppliers" data-toggle="modal"
                                    data-target="#add_purchase_order">
                                    Purchase Order
                                </button> --}}
                                <button class="btn btn-primary btn-sm" id="print_disbursements">
                                    <i class="fas fa-print"></i>
                                    Print
                                </button>
                            </div>
                        </div>
                        <table id="accounting_expense_monitoring" class="table table-bordered table-sm w-100">
                            <thead class="table-secondary">
                                <tr>
                                    <th class="text-left">Reference No.</th>
                                    <th class="text-left">Employee Name</th>
                                    <th class="text-left">Department</th>
                                    <th class="text-left">Remarks</th>
                                    <th class="text-left">Amount</th>
                                    <th class="text-left">Date</th>
                                    <th class="text-left">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>





            <!-- ADD Expenses Modal -->
            {{-- <div class="modal fade show" id="addExpensesModal" tabindex="-1" role="dialog"
                aria-labelledby="addExpensesModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content overflow-hidden" style="border-radius: 16px;">
                        <div class="modal-header" style="background-color:#cac9c9; border-top--radius: 16px !important;">
                            <h5 class="modal-title" id="addExpensesModalLabel">Expenses Monitoring</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="addExpenseForm">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="expenseDate" style="text-align: left;">Date</label>
                                        <input type="date" class="form-control" id="expenseDate" name="expenseDate"
                                            required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="referenceNo" style="text-align: left;">Reference No.</label>
                                        <input type="text" class="form-control" id="referenceNo" name="referenceNo"
                                            value="EXP 0000" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="expenseName">Expenses Name</label>
                                    <input type="text" class="form-control" id="expenseName" name="expenseName"
                                        placeholder="Enter Description Here" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="employeeName">Employee Name</label>
                                        <div class="input-group">
                                            <select class="form-control" id="employeeName" name="employeeName" required>
                                                <option value="0" selected>Select Employee</option>
                                                @foreach (DB::table('teacher')->where('deleted', 0)->get() as $employee)
                                                    <option value="{{ $employee->id }}">{{ $employee->firstname }}
                                                        {{ $employee->lastname }}</option>
                                                @endforeach

                                            </select>
                                            <span class="input-group-text"
                                                style="background-color: #e9ecef; border-left: 0;">
                                                <i class="fa fa-user-plus" style="color: green;"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="department">Department</label>
                                        <select class="form-control" id="department" name="department" required>
                                            <option value="0">Select Department</option>
                                            @foreach (DB::table('hr_departments')->where('deleted', 0)->get() as $department)
                                                <option value="{{ $department->id }}">{{ $department->department }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="remarks">Remarks</label>
                                    <input type="text" class="form-control" id="remarks" name="remarks"
                                        placeholder="Enter Description Here" required>
                                </div>
                                <div class="mb-3">
                                    <label>ITEMS</label>
                                    <button type="button" class="btn btn-outline-success btn-xsm" data-toggle="modal"
                                        data-target="#addItemModal"><i class="fa fa-plus"></i></button>

                                    <table class="table table-bordered" id="expensesItemsTable" style="font-size: 13px;">
                                        <thead class="table-sm table-bordered"
                                            style="background-color: rgb(196, 196, 196);">
                                            <tr>
                                                <th>Particulars</th>
                                                <th>Amount</th>
                                                <th>QTY</th>
                                                <th>Total Amount</th>
                                                <th>Action</th>
                                            <tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-right font-weight-bold">Total</th>
                                                <th id="grand_total_amount" class="font-weight-bold"></th>

                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success addExpensesBtn">Save</button>
                            <button type="button" class="btn btn-dark">Print</button>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- EDIT Expenses Modal -->
            <div class="modal fade show" id="editExpensesModal" tabindex="-1" role="dialog"
                aria-labelledby="addExpensesModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content overflow-hidden" style="border-radius: 16px;">
                        <div class="modal-header" style="background-color:#cac9c9; border-top--radius: 16px !important;">
                            <h5 class="modal-title" id="addExpensesModalLabel">Edit Expenses Monitoring</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="addExpenseForm">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <input type="text" id="expenseID_edit" hidden>
                                        <label for="expenseDate" style="text-align: left;">Date</label>
                                        <input type="date" class="form-control" id="expenseDate_edit"
                                            name="expenseDate" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="referenceNo" style="text-align: left;">Reference No.</label>
                                        <input type="text" class="form-control" id="referenceNo_edit"
                                            name="referenceNo" value="EXP 0000" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="expenseName">Expenses Name</label>
                                    <input type="text" class="form-control" id="expenseName_edit" name="expenseName"
                                        placeholder="Enter Description Here" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="employeeName">Employee Name</label>
                                        <div class="input-group">
                                            <select class="form-control" id="employeeName_edit" name="employeeName_edit"
                                                required>
                                                <option value="0" selected>Select Employee</option>
                                                @foreach (DB::table('teacher')->where('deleted', 0)->get() as $employee)
                                                    <option value="{{ $employee->id }}">{{ $employee->firstname }}
                                                        {{ $employee->lastname }}</option>
                                                @endforeach

                                            </select>
                                            <span class="input-group-text"
                                                style="background-color: #e9ecef; border-left: 0;">
                                                <i class="fa fa-user-plus" style="color: green;"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="department">Department</label>
                                        <select class="form-control" id="department_edit" name="department" required>
                                            <option value="0">Select Department</option>
                                            @foreach (DB::table('hr_departments')->where('deleted', 0)->get() as $department)
                                                <option value="{{ $department->id }}">{{ $department->department }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="remarks">Remarks</label>
                                    <input type="text" class="form-control" id="remarks_Edit" name="remarks"
                                        placeholder="Enter Description Here" required>
                                </div>
                                <div class="mb-3">
                                    <label>ITEMS</label>
                                    <button type="button" class="btn btn-outline-success btn-xsm" data-toggle="modal"
                                        data-target="#addItemModalforSelectedItem"><i class="fa fa-plus"></i></button>


                                    {{-- <table class="table table-bordered" id="expensesItemsTable_edit"
                                        style="font-size: 13px;">
                                        <thead class="table-sm table-bordered"
                                            style="background-color: rgb(196, 196, 196);">
                                            <tr>
                                                <th>Particulars</th>
                                                <th>Amount</th>
                                                <th>QTY</th>
                                                <th>Total Amount</th>
                                                <th>Action</th>
                                            <tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-right font-weight-bold">Total</th>
                                                <th id="grand_total_amount_edit" class="font-weight-bold"></th>

                                            </tr>
                                        </tfoot>
                                    </table> --}}

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm expensesItemsTable_edit_edit"
                                            id="expensesItemsTable_edit_edit">
                                            <thead>
                                                <tr>
                                                    <th>Particulars</th>
                                                    <th>Amount</th>
                                                    <th>QTY</th>
                                                    <th>Total</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            {{-- <tfoot>
                                                <tr>
                                                    <th colspan="3" class="text-right font-weight-bold">Total</th>
                                                    <th id="grand_total_amount_edit" class="font-weight-bold"></th>
                                                    <th></th>

                                                </tr>
                                            </tfoot> --}}
                                        </table>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary updateExpensesBtn">Update</button>
                            <button type="button" class="btn btn-dark">Print</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Add Item Modal -->
            <div class="modal fade" id="addItemModal"role="dialog" style="display: none; z-index: 1060;"
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
                                <select class="form-control select2" id="itemName" name="itemName">
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
                                <input type="number" class="form-control" id="amount" name="amount"
                                    placeholder="Enter Amount" required>
                            </div>
                            <div class="form-group">
                                <label for="qty">Quantity</label>
                                <input type="number" class="form-control" id="qty" name="qty"
                                    placeholder="Enter QTY" value="0" required>
                            </div>
                            <div class="form-group">
                                <label for="totalAmount">Total Amount</label>
                                <input type="number" class="form-control" id="totalAmount" name="totalAmount"
                                    placeholder="0.00" disabled>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <button class="btn btn-success" id="addItemBtn">Save</button>
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
                                <input type="number" class="form-control" id="amount_selected" name="amount"
                                    placeholder="Enter Amount" required>
                            </div>
                            <div class="form-group">
                                <label for="qty">Quantity</label>
                                <input type="number" class="form-control" id="qty_selected" name="qty"
                                    value="0" placeholder="Enter QTY" required>
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

            <div class="modal fade" id="editItemModal" role="dialog" style="display: none; z-index: 1060;"
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
                                    <select class="form-control select2" id="itemName_edit" name="itemName" required>
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
                                    <input type="number" class="form-control" id="amount_edit" name="amount"
                                        placeholder="Enter Amount" required>
                                </div>
                                <div class="form-group">
                                    <label for="qty">Quantity</label>
                                    <input type="number" class="form-control" id="qty_edit" name="qty"
                                        value="0" placeholder="Enter QTY" required>
                                </div>
                                <div class="form-group">
                                    <label for="totalAmount">Total Amount</label>
                                    <input type="number" class="form-control" id="totalAmount_edit" name="totalAmount"
                                        placeholder="0.00" disabled>
                                </div>
                                <div class="modal-footer d-flex justify-content-center">
                                    <button class="btn btn-primary" id="updateItemBtn">Update</button>
                                </div>
                            </form>
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
                                    <select class="form-control select2" id="itemName_edit_selected_selected"
                                        name="itemName" required>
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
                                        name="qty" placeholder="Enter QTY" value="0" required>
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

            <div class="modal fade" id="editSelectedExpensesItemModal" role="dialog"
                style="display: none; z-index: 1060;" aria-hidden="true">
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
                                    <input type="text" id="id_edit_item_selected" hidden>
                                    <label for="itemName">Item Name</label>
                                    <select class="form-control select2" id="itemName_edit_selected" name="itemName"
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
                                    <input type="number" class="form-control" id="amount_edit_selected" name="amount"
                                        placeholder="Enter Amount" required>
                                </div>
                                <div class="form-group">
                                    <label for="qty">Quantity</label>
                                    <input type="number" class="form-control" id="qty_edit_selected" name="qty"
                                        placeholder="Enter QTY" value="0" required>
                                </div>
                                <div class="form-group">
                                    <label for="totalAmount">Total Amount</label>
                                    <input type="number" class="form-control" id="totalAmount_edit_selected"
                                        name="totalAmount" placeholder="0.00" disabled>
                                </div>
                                <div class="modal-footer d-flex justify-content-center">
                                    <button class="btn btn-primary" id="updateItemBtn_selected">Update</button>
                                </div>
                            </form>
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
                                    <input type="number" class="form-control" placeholder="QTY" id="item_quantity"
                                        value="0">
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
                                <select class="form-control" id="cashier_debit_account_edit">
                                    <option value="" selected disabled>Assign Debit Account</option>
                                    {{-- @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                    <option value="{{ $coa->id }}">{{ $coa->account_name }}</option>
                                @endforeach --}}
                                    @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                        <option value="{{ $coa->id }}">{{ $coa->code }} -
                                            {{ $coa->account_name }}</option>
                                        @foreach (DB::table('bk_sub_chart_of_accounts')->where('deleted', 0)->where('coaid', $coa->id)->get() as $subcoa)
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
        </div>

        <div class="modal fade" id="add_purchase_order" data-backdrop="static" role="dialog"
            aria-labelledby="purchaseOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                    <div class="modal-header " style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                        <h5 class="modal-title" id="purchaseOrderModalLabel">Disbursement</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            id="close_disbursement_modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="checked_expenses" style="max-height: 80vh; overflow-y: auto;">
                        <form>
                            <div class="row mb-3">
                                <div class="d-flex justify-content-between w-100">
                                    <div class="col-md-7 d-flex align-items-center shadow">
                                        <label class="mr-2">Disbursement Type:</label>
                                        <div style="margin-left:10%;">
                                            <input type="checkbox" id="checked_expenses_expenses" checked> <label
                                                for="expenses">Expenses</label>
                                            <input type="checkbox" id="checked_expenses_refund" style="margin-left:10px;"
                                                disabled>
                                            <label for="refund">Students
                                                Refund</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-center">
                                        <label class="mr-2">Date:</label>
                                        <input type="date" id="expenses_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <br>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label>Voucher No.</label>
                                    <input type="text" id="expenses_voucher_no" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Reference No.</label>
                                    <input type="text" class="form-control" id="expenses_ref_no" value="0000">
                                </div>
                                <div class="col-md-3">
                                    <label>Disburse To</label>
                                    <select class="form-control" id="expenses_disbursement_to">
                                        <option value="" selected>Select Employee</option>
                                        @foreach (DB::table('teacher')->where('deleted', 0)->get() as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->firstname }}
                                                {{ $employee->lastname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Company/Department</label>
                                    <select class="form-control" id="expenses_department">
                                        <option value="">Select Department</option>
                                        @foreach (DB::table('hr_departments')->where('deleted', 0)->get() as $department)
                                            <option value="{{ $department->id }}">{{ $department->department }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label>Payment Type:</label>
                                    <div>
                                        <input type="radio" name="payment" id="expenses_cash" checked> <label
                                            for="cash">CASH</label>
                                        <input type="radio" name="payment" id="expenses_cheque"
                                            style="margin-left:10px;">
                                        <label for="cheque">CHEQUE</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label>Amount</label>
                                    <input type="text" class="form-control" id="expenses_amount" value="PHP 0.00">
                                </div>
                                <div class="col-md-2">
                                    <label>Bank</label>
                                    <select class="form-control" id="expenses_bank">
                                        <option>Select Bank</option>
                                        <option value="Union Bank">Union Bank</option>
                                        <option value="BPI">BPI</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Cheque No.</label>
                                    <input type="text" class="form-control" id="expenses_cheque_no"
                                        placeholder="Select Bank">
                                </div>
                                <div class="col-md-3">
                                    <label>Cheque Date.</label>
                                    <input type="date" class="form-control" id="expenses_cheque_date">
                                </div>
                            </div>
                            <div class="row mb-3">
                                {{-- <div class="col-md-6">
                        <label>Cheque Date:</label>
                        <input type="date" class="form-control">
                    </div> --}}
                                <div class="col-md-12">
                                    <label>Remarks/Explanation</label>
                                    <input type="text" class="form-control" id="expenses_remarks"
                                        placeholder="Enter Description Here">
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                <h6 class="m-0">ITEMS</h6>
                                <button type="button" class="btn btn-outline-success btn-xsm ml-2" id="add_item"
                                    data-toggle="modal" data-target="#addItemModal"><i class="fas fa-plus"></i></button>
                            </div>
                            <table class="table table-bordered mt-2" id="expensesItemsTable">
                                <thead>
                                    <tr>
                                        <th>Particulars</th>
                                        <th>Amount</th>
                                        <th>QTY</th>
                                        <th>Total Amount</th>
                                        <th colspan="2" style="text-align: center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>TOTAL</strong></td>
                                        <td colspan="2" id="grand_total_amount"></td>
                                    </tr>
                                </tfoot>
                            </table>


                        </form>


                        <div class="card mt-5">
                            <div class="card-header bg-secondary" style="color: white">
                                <h5 class="card-title mt-1">Journal Entry</h5> <button type="button"
                                    class="btn btn-success rounded ml-4" id="add_account_btn_disburseje"
                                    data-toggle="modal" data-target="#addaccountsetupModal_po_disburse"
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

                                <div class="form-row mb-3" id="po-disburse-total-row" style="display: none">
                                    <div class="form-group col-md-4">
                                        <input type="text" class="form-control" placeholder="Total"
                                            style="text-align: right; font-size: 18px !important; 
                                                  height: 40px !important; padding: 10px 15px !important;
                                                  line-height: 1.5 !important; width: 100% !important;"
                                            readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <input type="text" class="form-control" id="po_total_debit_amount"
                                            style="font-size: 18px !important; height: 40px !important;
                                                  padding: 10px 15px !important; line-height: 1.5 !important;
                                                  width: 100% !important; border-radius: 5px;"
                                            readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <input type="text" class="form-control" id="po_total_credit_amount"
                                            style="font-size: 18px !important; height: 40px !important;
                                                  padding: 10px 15px !important; line-height: 1.5 !important;
                                                  width: 100% !important; border-radius: 5px;"
                                            readonly>
                                    </div>
                                </div>
                                {{-- <div class="form-group col-md-4">
                                    <input type="text" class="form-control" id="po_total_credit_amount"
                                        style="width : 85%; height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                                </div> --}}
                                {{-- </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success saveDisbursement"
                            id="saveDisbursement">SAVE</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit_purchase_order" data-backdrop="static" role="dialog"
            aria-labelledby="purchaseOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document" style="max-height: 80vh; overflow-y: auto;">
                <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                    <div class="modal-header " style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                        <h5 class="modal-title" id="purchaseOrderModalLabel">Disbursement</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="checked_expenses_edit">
                        <form>
                            <div class="row mb-3">
                                <div class="d-flex justify-content-between w-100">
                                    <div class="col-md-7 d-flex align-items-center shadow">
                                        <input type="text" id="expenses_ID_edit" hidden>
                                        <label class="mr-2">Disbursement Type:</label>
                                        <div style="margin-left:10%;">
                                            <input type="checkbox" id="checked_expenses_expenses_edit" checked> <label
                                                for="expenses">Expenses</label>
                                            <input type="checkbox" id="checked_expenses_refund_edit"
                                                style="margin-left:10px;" disabled>
                                            <label for="refund">Students
                                                Refund</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-center">
                                        <label class="mr-2">Date:</label>
                                        <input type="date" id="expenses_date_edit" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <br>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label>Voucher No.</label>
                                    <input type="text" id="expenses_voucher_no_edit" class="form-control"
                                        value="CV/CHV 0000">
                                </div>
                                <div class="col-md-3">
                                    <label>Reference No.</label>
                                    <input type="text" class="form-control" id="expenses_ref_no_edit"
                                        value="PO 0000">
                                </div>
                                <div class="col-md-3">
                                    <label>Disburse To</label>
                                    <select class="form-control" id="expenses_disbursement_to_edit">
                                        <option value="" selected>Select Employee</option>
                                        @foreach (DB::table('teacher')->where('deleted', 0)->get() as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->firstname }}
                                                {{ $employee->lastname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Company/Department</label>
                                    <select class="form-control" id="expenses_department_edit">
                                        <option value="">Select Department</option>
                                        @foreach (DB::table('hr_departments')->where('deleted', 0)->get() as $department)
                                            <option value="{{ $department->id }}">{{ $department->department }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label>Payment Type:</label>
                                    <div>
                                        <input type="radio" name="payment" id="expenses_cash_edit" checked> <label
                                            for="cash">CASH</label>
                                        <input type="radio" name="payment" id="expenses_cheque_edit"
                                            style="margin-left:10px;">
                                        <label for="cheque">CHEQUE</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label>Amount</label>
                                    <input type="text" class="form-control" id="expenses_amount_edit"
                                        value="PHP 0.00">
                                </div>
                                <div class="col-md-2">
                                    <label>Bank</label>
                                    <select class="form-control" id="expenses_bank_edit">
                                        <option>Select Bank</option>
                                        <option value="Union Bank">Union Bank</option>
                                        <option value="BPI">BPI</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Cheque No.</label>
                                    <input type="text" class="form-control" id="expenses_cheque_no_edit"
                                        placeholder="Select Bank">
                                </div>
                                <div class="col-md-3">
                                    <label>Cheque Date.</label>
                                    <input type="date" class="form-control" id="expenses_cheque_date_edit">
                                </div>
                            </div>
                            <div class="row mb-3">
                                {{-- <div class="col-md-6">
                    <label>Cheque Date:</label>
                    <input type="date" class="form-control">
                </div> --}}
                                <div class="col-md-12">
                                    <label>Remarks/Explanation</label>
                                    <input type="text" class="form-control" id="expenses_remarks_edit"
                                        placeholder="Enter Description Here">
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                <h6 class="m-0">ITEMS</h6>
                                <button type="button" class="btn btn-outline-success btn-xsm ml-2" id="add_item"
                                    data-toggle="modal" data-target="#addItemModalforSelectedItem"><i
                                        class="fas fa-plus"></i></button>
                            </div>
                            <table class="table table-bordered mt-2" id="expensesItemsTable_edit">
                                <thead>
                                    <tr>
                                        <th>Particulars</th>
                                        <th>Amount</th>
                                        <th>QTY</th>
                                        <th>Total Amount</th>
                                        <th colspan="2" style="text-align: center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                {{-- <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>TOTAL</strong></td>
                                        <td colspan="2" id="grand_total_amount_edit"></td>
                                    </tr>
                                </tfoot> --}}
                            </table>


                        </form>

                        <div class="card mt-5">
                            <div class="card-header bg-secondary" style="color: white">
                                <h5 class="card-title mt-1">Edit Journal Entry</h5> <button type="button"
                                    class="btn btn-success rounded ml-4" data-toggle="modal"
                                    data-target="#addaccountsetupModal_po_disburse_edit"
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

                                <div id="po-disburse-account-entry-container-edit">
                                    {{-- < --}}
                                </div>

                                <div class="form-row mb-3" id="po-disburse-total-row-edit">
                                    <div class="form-group col-md-4">
                                        <input type="text" class="form-control" placeholder="Total"
                                            style="text-align: right; font-size: 18px !important; 
                                    height: 40px !important; padding: 10px 15px !important;
                                    line-height: 1.5 !important; width: 100% !important;"
                                            readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <input type="text" class="form-control" id="po_total_debit_amount_edit"
                                            style="font-size: 18px !important; height: 40px !important;
                                    padding: 10px 15px !important; line-height: 1.5 !important;
                                    width: 100% !important; border-radius: 5px;"
                                            readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <input type="text" class="form-control" id="po_total_credit_amount_edit"
                                            style="font-size: 18px !important; height: 40px !important;
                                    padding: 10px 15px !important; line-height: 1.5 !important;
                                    width: 100% !important; border-radius: 5px;"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success updateDisbursement"
                            id="updateDisbursement">SAVE</button>
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

            var isCashChecked = $('#expenses_cash').is(':checked');
            $('#expenses_bank').prop('disabled', isCashChecked);
            $('#expenses_cheque_no').prop('disabled', isCashChecked);
            $('#expenses_cheque_date').prop('disabled', isCashChecked);
            // $('#expenses_voucher_no').val(isCashChecked ? 'CV-' : 'CHV-' + String(Math.floor(Math.random() * 900) + 100).padStart(3, '0'));
            // $('#expenses_voucher_no').val('CV-', isCashChecked);
            $('#expenses_voucher_no').val('CV-' + String(Math.floor(Math.random() * 900) + 100).padStart(3, '0'),
                isCashChecked);


            $('#expenses_cheque').change(function() {
                if ($(this).is(':checked')) {
                    $('#expenses_bank').prop('disabled', false);
                    $('#expenses_cheque_no').prop('disabled', false);
                    $('#expenses_cheque_date').prop('disabled', false);
                    $('#expenses_voucher_no').val('CHV-' + String(Math.floor(Math.random() * 900) + 100)
                        .padStart(3, '0'));
                }
            });

            $('#expenses_cash').change(function() {
                if ($(this).is(':checked')) {
                    $('#expenses_bank').prop('disabled', true);
                    $('#expenses_cheque_no').prop('disabled', true);
                    $('#expenses_cheque_date').prop('disabled', true);
                    $('#expenses_voucher_no').val('CV-' + String(Math.floor(Math.random() * 900) + 100)
                        .padStart(3, '0'));
                }
            });


            // $("#itemName").on("change", function() {
            $(document).on('change', '#itemName', function() {
                var itemId = $(this).val();
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
                                // $('#qty').val(item.qty);
                                // let total = item.amount * item.qty;
                                // $('#totalAmount').val(total);
                            }
                        }
                    });
                }
            });

            $('#amount_edit_selected_selected, #qty_edit_selected_selected').on('input', function() {
                var amount = $('#amount_edit_selected_selected').val();
                var qty = $('#qty_edit_selected_selected').val();
                let total = amount * qty;
                $('#totalAmount_edit_selected_selected').val(total);
            });

            $('#amount_selected, #qty_selected').on('input', function() {
                var amount = $('#amount_selected').val();
                var qty = $('#qty_selected').val();
                let total = amount * qty;
                $('#totalAmount_selected').val(total);
            });

            $('#amount_edit, #qty_edit').on('input', function() {
                var amount = $('#amount_edit').val();
                var qty = $('#qty_edit').val();
                let total = amount * qty;
                $('#totalAmount_edit').val(total);
            });

            $('#amount, #qty').on('input', function() {
                var amount = $('#amount').val();
                var qty = $('#qty').val();
                let total = amount * qty;
                $('#totalAmount').val(total);
            });

            $('#amount_edit_selected, #qty_edit_selected').on('input', function() {
                var amount = $('#amount_edit_selected').val();
                var qty = $('#qty_edit_selected').val();
                let total = amount * qty;
                $('#totalAmount_edit_selected').val(total);
            });


            $(document).on('change', '#itemName_selected', function() {
                var itemId = $(this).val();
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
                                // $('#amount_selected').val(item.amount);
                                // $('#qty_selected').val(item.qty);
                                // let total = item.amount * item.qty;
                                // $('#totalAmount_selected').val(total);
                            }
                        }
                    });
                }
            });

            // Additional safeguard for modal
            // $('#newExpenseItemModal').on('shown.bs.modal', function() {
            //     isProcessingChange = false;
            // });

            $("#addItemBtn").click(function(e) {
                e.preventDefault();
                var selectItemID = $("#itemName").val();
                var selectItem = $("#itemName option:selected").text();
                var amount = $("#amount").val();
                var quantity = $("#qty").val();
                var total = $("#totalAmount").val();

                if (selectItem.trim() == "" || amount.trim() == "" || quantity.trim() == "" || total
                    .trim() == "") {
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: 'All fields are required',
                    });
                    return;
                }

                var table = $("#expensesItemsTable");
                var row = $("<tr class='appended-row'>");

                row.append($("<td data-id='" + selectItemID + "'>").text(selectItem));
                row.append($("<td>").text(amount));
                row.append($("<td>").text(quantity));
                row.append($("<td>").text(total));

                row.append($("<td class='text-center'>").html(
                    '<a href="javascript:void(0)" class="text-center align-middle ml-2 edit-append-row" data-toggle="modal" data-target="#editItemModal"><i class="far fa-edit text-primary"></i></a>' +
                    '<a href="javascript:void(0)" class="text-center text-danger align-middle ml-2 remove-append-row"><i class="far fa-trash-alt text-danger"></i></a>'
                ));

                table.find("tbody").append(row);

                let rows = table.find("tbody tr");
                var grand_total = 0;
                rows.each(function(index) {
                    var rowTotal = parseFloat($(this).find("td:eq(3)").text().replace(/,/g, ""));
                    if (!isNaN(rowTotal)) {
                        grand_total += rowTotal;
                    }
                });
                $("#grand_total_amount").text(parseFloat(grand_total).toLocaleString());

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

                Toast.fire({
                    type: 'success',
                    title: 'New Item added successfully'
                });

                $("#itemName").val("").trigger('change');
                $("#amount").val("");
                $("#qty").val("");
                $("#totalAmount").val("");

                //////////////////////////////////

                let currentAppendRow; // Variable to store the currently selected row

                var totalAmount = 0;
                // expensesItemsTable
                $("#expensesItemsTable tbody tr").each(function() {
                    var rowTotal = parseFloat($(this).find("td:eq(3)").text()
                        .replace(/,/g, ""));
                    if (!isNaN(rowTotal)) {
                        totalAmount += rowTotal;
                    }
                });
                $("#edit_grand_total").text(parseFloat(totalAmount).toLocaleString());

                $('.edit-append-row').on('click', function() {
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
                    $('#itemName_edit').val(currentAppendRow
                        .find(
                            'td:eq(0)').data(
                            'disburseid'));
                    $('#amount_edit').val(item_amount);
                    $('#qty_edit').val(item_quantity);
                    $('#totalAmount_edit').val(item_total);


                });


            });

            /////////////////////////////////add for selected item /////////////////////////////////
            $("#addItemBtn_selected").click(function(e) {
                e.preventDefault();
                var selectItemID = $("#itemName_selected").val();
                var selectItem = $("#itemName_selected option:selected").text();
                var amount = $("#amount_selected").val();
                var quantity = $("#qty_selected").val();
                var total = $("#totalAmount_selected").val();

                if (selectItem.trim() == "" || amount.trim() == "" || quantity.trim() == "" || total
                    .trim() == "") {
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: 'All fields are required',
                    });
                    return;
                }

                var table = $("#expensesItemsTable_edit");
                var row = $("<tr class='appended-row'>");

                row.append($("<td data-id='" + selectItemID + "' data-expensesid='0'>").text(selectItem));
                row.append($("<td>").text(amount));
                row.append($("<td>").text(quantity));
                row.append($("<td>").text(total));

                row.append($("<td class='text-center'>").html(
                    '<a href="javascript:void(0)" class="edit-append-row-selected" data-toggle="modal" data-target="#editItemModal_selected"><i class="far fa-edit text-primary"></i></a>' +
                    '<a href="javascript:void(0)" class="ml-2 remove-append-row-selected"><i class="far fa-trash-alt text-danger"></i></a>'
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
                // $("#grand_total_amount").text(parseFloat(grand_total).toLocaleString());

                table.find("tbody").on('click', '.remove-append-row-selected', function() {
                    $(this).closest('tr').remove();
                    // let rows = table.find("tbody tr");
                    // var grand_total = 0;
                    // rows.each(function(index) {
                    //     var rowTotal = parseFloat($(this).find("td:eq(3)").text().replace(
                    //         /,/g, ""));
                    //     if (!isNaN(rowTotal)) {
                    //         grand_total += rowTotal;
                    //     }
                    // });
                    // $("#grand_total_amount").text(parseFloat(grand_total).toLocaleString());
                });

                Toast.fire({
                    type: 'success',
                    title: 'New Item added successfully'
                });

                $("#itemName_selected").val("").trigger('change');
                $("#amount_selected").val("");
                $("#qty_selected").val("");
                $("#totalAmount_selected").val("");

                //////////////////////////////////

                let currentAppendRow; // Variable to store the currently selected row

                var totalAmount = 0;
                // expensesItemsTable
                // $("#expensesItemsTable tbody tr").each(function() {
                //     var rowTotal = parseFloat($(this).find("td:eq(3)").text()
                //         .replace(/,/g, ""));
                //     if (!isNaN(rowTotal)) {
                //         totalAmount += rowTotal;
                //     }
                // });
                // $("#edit_grand_total").text(parseFloat(totalAmount).toLocaleString());

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
                    // $('#itemName_edit_selected_selected').val(currentAppendRow
                    //     .find('td:eq(0)').data('id'));

                    console.log("currentAppendRow data-id: ", currentAppendRow.find('td:eq(0)')
                        .data('id'));
                    // $('#itemName_edit_selected_selected').find('option').each(function() {
                    //     if ($(this).val() == currentAppendRow.find('td:eq(0)').data('id')) {
                    //         $(this).prop('selected', true);
                    //     }
                    // });

                    $('#itemName_edit_selected_selected').find('option').each(function() {
                        if ($(this).val() == currentAppendRow
                            .find('td:eq(0)').data('id')) {
                            $(this).prop('selected', true);
                            $(this).text(currentAppendRow
                                .find('td:eq(0)').text().trim());
                        }
                    });

                    $('#amount_edit_selected_selected').val(item_amount);
                    $('#qty_edit_selected_selected').val(item_quantity);
                    $('#totalAmount_edit_selected_selected').val(item_total);

                    // Handle update button click
                    $('#updateItemBtn_selected_selected').on('click', function(e) {
                        e.preventDefault();

                        if (currentAppendRow) {
                            const itemName_id = $('#itemName_edit_selected_selected').val()
                                .trim();
                            const itemName_edit = $(
                                    '#itemName_edit_selected_selected option:selected')
                                .text()
                                .trim();
                            const amount_edit = $('#amount_edit_selected_selected').val()
                                .trim();
                            const qty_edit = $('#qty_edit_selected_selected').val().trim();
                            const totalAmount_edit = $(
                                '#totalAmount_edit_selected_selected').val().trim();

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

                // var currentRow = null; 

                $('#updateItemBtn_selected').on('click', function(e) {
                    e.preventDefault();

                    if (currentAppendRow) {
                        const itemName_id = $('#itemName_edit_selected').val().trim();
                        const itemName_edit = $('#itemName_edit_selected option:selected').text()
                            .trim();
                        const amount_edit = $('#amount_edit_selected').val().trim();
                        const qty_edit = $('#qty_edit_selected').val().trim();
                        const totalAmount_edit = $('#totalAmount_edit_Selected').val().trim();

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



            $("#itemName_edit").on("change", function(e) {
                e.preventDefault();
                var itemId = $(this).val();
                if ($(this).val() === "add") {
                    // Reset dropdown to default
                    $(this).val("").trigger("change");
                    // Open the modal
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
                                // $('#amount_edit').val(item.amount);
                                // $('#qty_edit').val(item.qty);
                                // let total = item.amount * item.qty;
                                // $('#totalAmount_edit').val(total);
                            }
                        }
                    });
                }
            });

            var currentRow = null; // Store the row to be updated

            // Handle edit button click
            $(document).on('click', '.edit-append-row', function(e) {
                e.preventDefault();
                currentRow = $(this).closest('tr');

                const itemParticulars = currentRow.find('td:eq(0)').attr('data-id').trim();
                const itemAmount = currentRow.find('td:eq(1)').text().trim();
                const itemQuantity = currentRow.find('td:eq(2)').text().trim();
                const itemTotalAmount = currentRow.find('td:eq(3)').text().trim();

                // Set modal values
                $('#itemName_edit').find('option').each(function() {
                    $(this).prop('selected', this.value == itemParticulars);
                });
                $('#amount_edit').val(itemAmount);
                $('#qty_edit').val(itemQuantity);
                $('#totalAmount_edit').val(itemTotalAmount);
            });

            // Handle update button click
            $('#updateItemBtn').on('click', function(e) {
                e.preventDefault();

                if (currentRow) {
                    const itemName_id = $('#itemName_edit').val().trim();
                    const itemName_edit = $('#itemName_edit option:selected').text().trim();
                    const amount_edit = $('#amount_edit').val().trim();
                    const qty_edit = $('#qty_edit').val().trim();
                    const totalAmount_edit = $('#totalAmount_edit').val().trim();

                    currentRow.find('td:eq(0)')
                        .text(itemName_edit)
                        .css('color', '')
                        .attr('data-id', itemName_id);
                    currentRow.find('td:eq(1)').text(amount_edit).css('color', '');
                    currentRow.find('td:eq(2)').text(qty_edit).css('color', '');
                    currentRow.find('td:eq(3)').text(totalAmount_edit).css('color', '');

                    var rows = $("#expensesItemsTable tbody tr");
                    var grand_total = 0;
                    rows.each(function(index) {
                        var rowTotal = parseFloat($(this).find("td:eq(3)").text().replace(
                            /,/g, ""));
                        if (!isNaN(rowTotal)) {
                            grand_total += rowTotal;
                        }
                    });
                    $("#grand_total_amount").text(parseFloat(grand_total).toLocaleString());
                }
            });

            $('.saveDisbursement').on('click', function(event) {
                event.preventDefault();
                create_disbursements()
            });

            function create_disbursements() {

                var disbursement_type = '';
                if ($('#checked_expenses_expenses').is(':checked')) {
                    disbursement_type = 'Expenses';
                } else if ($('#checked_expenses_refund').is(':checked')) {
                    disbursement_type = 'Students Refund';
                }

                var expenses_date = $('#expenses_date').val();
                var expenses_voucher_no = $('#expenses_voucher_no').val();
                var expenses_ref_no = $('#expenses_ref_no').val();
                var expenses_disbursement_to = $('#expenses_disbursement_to').val();
                var expenses_department = $('#expenses_department').val();

                var payment_type = '';
                if ($('#expenses_cash').is(':checked')) {
                    payment_type = 'Cash';
                } else if ($('#expenses_cheque').is(':checked')) {
                    payment_type = 'Cheque';
                }

                var expenses_amount = $('#expenses_amount').val();

                var bank = '';
                var cheque_no = '';
                var cheque_date = '';
                if ($('#expenses_cheque').is(':checked')) {
                    bank = $('#expenses_bank').val();
                    cheque_no = $('#expenses_cheque_no').val();
                    cheque_date = $('#expenses_cheque_date').val();
                }

                var expenses_remarks = $('#expenses_remarks').val();
                var grand_total_amount = $('#grand_total_amount').text();

                var tableData = [];
                $("#expensesItemsTable").find("tbody tr").each(function() {
                    if ($(this).is(":visible")) {
                        var itemId = $(this).find("td:eq(0)").attr('data-id');
                        var itemName = $(this).find("td:eq(0)").text().trim();
                        var itemAmount = $(this).find("td:eq(1)").text().trim();
                        var itemQuantity = $(this).find("td:eq(2)").text().trim();
                        var itemTotal = $(this).find("td:eq(3)").text().trim();

                        if (itemId && itemName && itemAmount && itemQuantity && itemTotal) {
                            tableData.push({
                                itemId: itemId,
                                itemName: itemName,
                                itemAmount: itemAmount,
                                itemQuantity: itemQuantity,
                                itemTotal: itemTotal
                            });
                        }
                    }
                });

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
                        expenses_date: expenses_date,
                        expenses_voucher_no: expenses_voucher_no,
                        expenses_ref_no: expenses_ref_no,
                        expenses_disbursement_to: expenses_disbursement_to,
                        expenses_department: expenses_department,
                        payment_type: payment_type,
                        expenses_amount: expenses_amount,
                        payment_type: payment_type,
                        bank: bank,
                        cheque_no: cheque_no,
                        cheque_date: cheque_date,
                        expenses_remarks: expenses_remarks,
                        entries: entries,
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

                            var table = $("#expensesItemsTable");
                            table.find("tbody .appended-row").remove();
                            $("#expenses_date").val("");
                            $("#expenses_voucher_no").val("");
                            $("#expenses_ref_no").val("");
                            $("#expenses_disbursement_to").val("");
                            $("#expenses_department").val("");
                            $("#expenses_amount").val("");
                            $("#expenses_remarks").val("");
                            $('#grand_total_amount').text("0.0");

                            expenses_monitoringTable()
                            $("#addItemModal").modal('hide');
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }

            expenses_monitoringTable()

            function expenses_monitoringTable() {

                $("#accounting_expense_monitoring").DataTable({
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
                        url: '/bookkeeper/disbursement/fetch',
                        type: 'GET',
                        dataSrc: function(json) {
                            return json;
                        }
                    },
                    columns: [{
                            "data": "voucher_no"
                        },
                        {
                            "data": "firstname"
                        },
                        {
                            "data": "company_department"
                        },
                        {
                            "data": "remarks"
                        },
                        {
                            "data": "amount"
                        },
                        {
                            "data": "date"
                        },
                        {
                            "data": null
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
                                $(td).html(rowData.voucher_no).addClass(
                                    'align-middle');
                            }
                        },


                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.firstname + ' ' + rowData.lastname).addClass(
                                    'align-middle');
                            }
                        },

                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                // $(td).html('');
                                $(td).html(rowData.company_department).addClass('align-middle');

                            }
                        },

                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.remarks).addClass(
                                    'align-middle');
                            }
                        },

                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.amount.split(' ')[0]).addClass(
                                    'align-middle');

                            }
                        },

                        {
                            'targets': 5,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.date).addClass(
                                    'align-middle');
                            }
                        },

                        {
                            'targets': 6,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html('').addClass(
                                    'align-middle');
                            }
                        },


                        {
                            'targets': 7,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var buttons =
                                    '<a href="javascript:void(0)" class="edit_expense_monitoring" id="edit_expense_monitoring" data-id="' +
                                    rowData.id +
                                    '" data-toggle="modal" data-target="#edit_purchase_order"><i class="far fa-edit text-primary"></i></a>' +
                                    '&nbsp;&nbsp;' +
                                    '<a href="javascript:void(0)" class="delete_expense_monitoring" id="delete_expense_monitoring" data-id="' +
                                    rowData.id +
                                    '"><i class="far fa-trash-alt text-danger"></i></a>';
                                $(td)[0].innerHTML = buttons;
                                $(td).addClass('text-center align-middle');
                            }
                        }

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

            ///////////////////////////////////////////////////////////////////

            // $(document).on('click', '.edit_expense_monitoring', function() {

            //     var expense_monitoring_id = $(this).attr('data-id')

            //     $.ajax({
            //         type: 'GET',
            //         url: '/bookkeeper/disbursement/edit',
            //         data: {
            //             expense_monitoring_id: expense_monitoring_id
            //         },
            //         success: function(response) {

            //             var expense_monitoring_selected = response.expense_monitoring;
            //             var expense_monitoring_items = response.expense_monitoring_items;


            //             var expenseDate_edit = expense_monitoring_selected[0].date.split(
            //                 ' ')[0];
            //             $("#expenses_date_edit").val(expenseDate_edit);
            //             $("#expenses_ID_edit").val(expense_monitoring_selected[0].id);
            //             $("#expenses_voucher_no_edit").val(expense_monitoring_selected[0]
            //                 .voucher_no);
            //             $("#expenses_ref_no_edit").val(expense_monitoring_selected[0]
            //                 .reference_no);

            //             $("#expenses_disbursement_to_edit").val(expense_monitoring_selected[0]
            //                 .disburse_to);
            //             $("#expenses_department_edit").val(expense_monitoring_selected[0]
            //                 .company_department);

            //             if (expense_monitoring_selected[0].payment_type == 'Cash') {
            //                 $("#expenses_cash_edit").prop('checked', true);
            //                 $("#expenses_bank_edit").val('');
            //                 $("#expenses_cheque_no_edit").val('');
            //                 $("#expenses_cheque_date_edit").val('');
            //                 var isCashChecked = $('#expenses_cash_edit').is(':checked');
            //                 $('#expenses_bank_edit').prop('disabled', isCashChecked);
            //                 $('#expenses_cheque_no_edit').prop('disabled', isCashChecked);
            //                 $('#expenses_cheque_date_edit').prop('disabled', isCashChecked);

            //             } else {
            //                 $("#expenses_cheque_edit").prop('checked', true);
            //                 $("#expenses_bank_edit").val(expense_monitoring_selected[0].bank);
            //                 $("#expenses_cheque_no_edit").val(expense_monitoring_selected[0]
            //                     .cheque_no);
            //                 $("#expenses_cheque_date_edit").val(expense_monitoring_selected[0]
            //                     .cheque_date);
            //             }

            //             // if (expense_monitoring_selected[0].payment_type == 'Cash') {
            //             //     $("#expenses_cash_edit").prop('checked', true);

            //             //     $("#expenses_voucher_no_edit").val('CV ' + expense_monitoring_selected[0]
            //             //         .voucher_no);
            //             // } else {
            //             //     $("#expenses_cheque_edit").prop('checked', true);
            //             //     $("#expenses_voucher_no_edit").val('CHV ' + expense_monitoring_selected[0]
            //             //         .voucher_no);
            //             // }

            //             $("#expenses_amount_edit").val(expense_monitoring_selected[0].amount);


            //             $("#expenses_remarks_edit").val(expense_monitoring_selected[0].remarks);


            //             // $("#grand_total_amount_edit").text(expense_monitoring_selected[0]
            //             //     .amount);

            //             $('#expenses_cash_edit').change(function() {
            //                 if ($(this).is(':checked')) {
            //                     var voucher_no = $("#expenses_voucher_no_edit").val();
            //                     if (voucher_no.includes('CHV-')) {
            //                         $("#expenses_voucher_no_edit").val(voucher_no
            //                             .replace('CHV-', 'CV-'));
            //                     }
            //                     $('#expenses_bank_edit').prop('disabled', true);
            //                     $('#expenses_cheque_no_edit').prop('disabled', true);
            //                     $('#expenses_cheque_date_edit').prop('disabled', true);
            //                     $("#expenses_bank_edit").val('');
            //                     $("#expenses_cheque_no_edit").val('');
            //                     $("#expenses_cheque_date_edit").val('');
            //                 }
            //             });

            //             $('#expenses_cheque_edit').change(function() {
            //                 if ($(this).is(':checked')) {
            //                     var voucher_no = $("#expenses_voucher_no_edit").val();
            //                     if (voucher_no.includes('CV-')) {
            //                         $("#expenses_voucher_no_edit").val(voucher_no
            //                             .replace('CV-', 'CHV-'));
            //                     }
            //                     $('#expenses_bank_edit').prop('disabled', false);
            //                     $('#expenses_cheque_no_edit').prop('disabled', false);
            //                     $('#expenses_cheque_date_edit').prop('disabled', false);
            //                     $("#expenses_bank_edit").val(
            //                         expense_monitoring_selected[0].bank);
            //                     $("#expenses_cheque_no_edit").val(
            //                         expense_monitoring_selected[0]
            //                         .cheque_no);
            //                     $("#expenses_cheque_date_edit").val(
            //                         expense_monitoring_selected[0]
            //                         .cheque_date);
            //                 }
            //             });




            //             expense_monitoring_itemsTable()

            //             function expense_monitoring_itemsTable() {

            //                 $("#expensesItemsTable_edit").DataTable({
            //                     destroy: true,
            //                     autoWidth: false,
            //                     data: expense_monitoring_items,
            //                     info: false,
            //                     language: {
            //                         emptyTable: "" // This will remove the "No matching records found" message
            //                     },
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
            //                         },
            //                         {
            //                             "data": null
            //                         }
            //                     ],
            //                     columnDefs: [{
            //                             'targets': 0,
            //                             'orderable': false,
            //                             'createdCell': function(td, cellData,
            //                                 rowData, row, col) {
            //                                 $(td).html(rowData.description)
            //                                     .addClass(
            //                                         'align-middle').attr(
            //                                         'data-id',
            //                                         rowData
            //                                         .id
            //                                     )
            //                                     .attr(
            //                                         'data-id',
            //                                         rowData
            //                                         .id
            //                                     )
            //                                     .attr(
            //                                         'data-expensesid',
            //                                         rowData
            //                                         .id
            //                                     );
            //                             }
            //                         },
            //                         {
            //                             'targets': 1,
            //                             'orderable': false,
            //                             'createdCell': function(td, cellData,
            //                                 rowData, row, col) {
            //                                 $(td).html(rowData.amount)
            //                                     .addClass(
            //                                         'align-middle');
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
            //                         },
            //                         {
            //                             'targets': 4,
            //                             'orderable': false,
            //                             'createdCell': function(td, cellData,
            //                                 rowData, row, col) {
            //                                 var buttons =
            //                                     '<a href="javascript:void(0)" class="edit_purchase_order" id="edit_selected_expenses_item" data-id="' +
            //                                     rowData.id +
            //                                     '" data-toggle="modal" data-target="#editSelectedExpensesItemModal"><i class="far fa-edit text-primary"></i></a>' +
            //                                     '&nbsp;&nbsp;' +
            //                                     '<a href="javascript:void(0)" class="delete_selected_expenses_item" id="delete_selected_expenses_item" data-id="' +
            //                                     rowData.id +
            //                                     '"><i class="far fa-trash-alt text-danger"></i></a>';
            //                                 $(td)[0].innerHTML = buttons;
            //                                 $(td).addClass(
            //                                     'text-center align-middle');
            //                             }
            //                         }
            //                     ]
            //                 });
            //             }

            //             <div class="form-row account-entry_po_disburse mt-4">
            //                 <div class="form-group col-md-4">
            //                     <label style="font-weight: 600; font-size: 13px;">Account</label>
            //                     <select class="form-control account-select-po-disburse"
            //                         style="width: 90%; height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
            //                         <option value="">Select Account</option>
            //                         @php
                //                             $coa = DB::table('chart_of_accounts')->get();
                //
            @endphp
            //                         @foreach (DB::table('chart_of_accounts')->get() as $coa)
            //                             <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
            //                             @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->get() as $subcoa)
            //                                 <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcoa->sub_code }} - 
            //                                     {{ $subcoa->sub_account_name }}</option>
            //                             @endforeach
            //                         @endforeach
            //                     </select>
            //                 </div>
            //                 <div class="form-group col-md-4">
            //                     <label style="font-weight: 600; font-size: 13px;">Debit Account</label>
            //                     <input type="text" class="form-control debit-input-po-disburse-edit" value="0.00"
            //                         style="width: 90%; height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
            //                 </div>
            //                 <div class="form-group col-md-4 d-flex align-items-center">
            //                     <div style="width: 100%;">
            //                         <label style="font-weight: 600; font-size: 13px;">Credit Account</label>
            //                         <div class="input-group">
            //                             <input type="text" class="form-control credit-input-po-disburse-edit" value="0.00"
            //                                 style="height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
            //                             <div class="input-group-append">
            //                                 <button type="button" class="btn btn-sm ml-3 remove-account-entry-po-disburse" style="background-color: transparent;">
            //                                     <i class="fas fa-trash-alt text-danger"></i>
            //                                 </button>
            //                             </div>
            //                         </div>
            //                     </div>
            //                 </div>
            //             </div>




            //         }
            //     });

            // });

            $(document).on('click', '.edit_expense_monitoring', function() {

                var expense_monitoring_id = $(this).attr('data-id')

                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/disbursement/edit',
                    data: {
                        expense_monitoring_id: expense_monitoring_id
                    },
                    success: function(response) {

                        var expense_monitoring_selected = response.expense_monitoring;
                        var expense_monitoring_items = response.expense_monitoring_items;
                        var disbursements_general_ledger = response
                            .bkdisbursements_general_ledger;


                        var expenseDate_edit = expense_monitoring_selected[0].date.split(
                            ' ')[0];
                        $("#expenses_date_edit").val(expenseDate_edit);
                        $("#expenses_ID_edit").val(expense_monitoring_selected[0].id);
                        $("#expenses_voucher_no_edit").val(expense_monitoring_selected[0]
                            .voucher_no);
                        $("#expenses_ref_no_edit").val(expense_monitoring_selected[0]
                            .reference_no);

                        $("#expenses_disbursement_to_edit").val(expense_monitoring_selected[0]
                            .disburse_to);
                        $("#expenses_department_edit").val(expense_monitoring_selected[0]
                            .company_department);

                        if (expense_monitoring_selected[0].payment_type == 'Cash') {
                            $("#expenses_cash_edit").prop('checked', true);
                            $("#expenses_bank_edit").val('');
                            $("#expenses_cheque_no_edit").val('');
                            $("#expenses_cheque_date_edit").val('');
                            var isCashChecked = $('#expenses_cash_edit').is(':checked');
                            $('#expenses_bank_edit').prop('disabled', isCashChecked);
                            $('#expenses_cheque_no_edit').prop('disabled', isCashChecked);
                            $('#expenses_cheque_date_edit').prop('disabled', isCashChecked);

                        } else {
                            $("#expenses_cheque_edit").prop('checked', true);
                            $("#expenses_bank_edit").val(expense_monitoring_selected[0].bank);
                            $("#expenses_cheque_no_edit").val(expense_monitoring_selected[0]
                                .cheque_no);
                            $("#expenses_cheque_date_edit").val(expense_monitoring_selected[0]
                                .cheque_date);
                        }

                        // if (expense_monitoring_selected[0].payment_type == 'Cash') {
                        //     $("#expenses_cash_edit").prop('checked', true);

                        //     $("#expenses_voucher_no_edit").val('CV ' + expense_monitoring_selected[0]
                        //         .voucher_no);
                        // } else {
                        //     $("#expenses_cheque_edit").prop('checked', true);
                        //     $("#expenses_voucher_no_edit").val('CHV ' + expense_monitoring_selected[0]
                        //         .voucher_no);
                        // }

                        $("#expenses_amount_edit").val(expense_monitoring_selected[0].amount);


                        $("#expenses_remarks_edit").val(expense_monitoring_selected[0].remarks);


                        // $("#grand_total_amount_edit").text(expense_monitoring_selected[0]
                        //     .amount);

                        $('#expenses_cash_edit').change(function() {
                            if ($(this).is(':checked')) {
                                var voucher_no = $("#expenses_voucher_no_edit").val();
                                if (voucher_no.includes('CHV-')) {
                                    $("#expenses_voucher_no_edit").val(voucher_no
                                        .replace('CHV-', 'CV-'));
                                }
                                $('#expenses_bank_edit').prop('disabled', true);
                                $('#expenses_cheque_no_edit').prop('disabled', true);
                                $('#expenses_cheque_date_edit').prop('disabled', true);
                                $("#expenses_bank_edit").val('');
                                $("#expenses_cheque_no_edit").val('');
                                $("#expenses_cheque_date_edit").val('');
                            }
                        });

                        $('#expenses_cheque_edit').change(function() {
                            if ($(this).is(':checked')) {
                                var voucher_no = $("#expenses_voucher_no_edit").val();
                                if (voucher_no.includes('CV-')) {
                                    $("#expenses_voucher_no_edit").val(voucher_no
                                        .replace('CV-', 'CHV-'));
                                }
                                $('#expenses_bank_edit').prop('disabled', false);
                                $('#expenses_cheque_no_edit').prop('disabled', false);
                                $('#expenses_cheque_date_edit').prop('disabled', false);
                                $("#expenses_bank_edit").val(
                                    expense_monitoring_selected[0].bank);
                                $("#expenses_cheque_no_edit").val(
                                    expense_monitoring_selected[0]
                                    .cheque_no);
                                $("#expenses_cheque_date_edit").val(
                                    expense_monitoring_selected[0]
                                    .cheque_date);
                            }
                        });




                        expense_monitoring_itemsTable()

                        function expense_monitoring_itemsTable() {

                            $("#expensesItemsTable_edit").DataTable({
                                destroy: true,
                                autoWidth: false,
                                data: expense_monitoring_items,
                                info: false,
                                language: {
                                    emptyTable: "" // This will remove the "No matching records found" message
                                },
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
                                            $(td).html(rowData.description)
                                                .addClass(
                                                    'align-middle').attr(
                                                    'data-id',
                                                    rowData
                                                    .id
                                                )
                                                .attr(
                                                    'data-id',
                                                    rowData
                                                    .id
                                                )
                                                .attr(
                                                    'data-expensesid',
                                                    rowData
                                                    .id
                                                );
                                        }
                                    },
                                    {
                                        'targets': 1,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row, col) {
                                            $(td).html(rowData.amount)
                                                .addClass(
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
                                            var buttons =
                                                '<a href="javascript:void(0)" class="edit_purchase_order" id="edit_selected_expenses_item" data-id="' +
                                                rowData.id +
                                                '" data-toggle="modal" data-target="#editSelectedExpensesItemModal"><i class="far fa-edit text-primary"></i></a>' +
                                                '&nbsp;&nbsp;' +
                                                '<a href="javascript:void(0)" class="delete_selected_expenses_item" id="delete_selected_expenses_item" data-id="' +
                                                rowData.id +
                                                '"><i class="far fa-trash-alt text-danger"></i></a>';
                                            $(td)[0].innerHTML = buttons;
                                            $(td).addClass(
                                                'text-center align-middle');
                                        }
                                    }
                                ]
                            });
                        }

                        // Clear the container first to prevent duplicates
                        $('#po-disburse-account-entry-container-edit').empty();

                        // disbursements_general_ledger.forEach(entry => {
                        //     $('#po-disburse-account-entry-container-edit').append(`
                    //         <div class="form-row mb-3 account-entry_po_disburse" data-expensesid="${entry.id}" >
                    //             <div class="form-group col-md-4">
                    //                 <label style="font-weight: 600; font-size: 13px;">Account</label>
                    //                 <input type="text" class="form-control account-select-po-disburse" id="account_select_po_disburse_edit" value="${entry.account_name}" disabled
                    //                     style="width: 90%; height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                    //             </div>
                    //             <div class="form-group col-md-4">
                    //                 <label style="font-weight: 600; font-size: 13px;">Debit Account</label>
                    //                 <input type="text" class="form-control debit-input-po-disburse-edit-edit" id="debit_input_po_disburse_edit_edit" value="${entry.debit_amount}"
                    //                     style="width: 90%; height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                    //             </div>
                    //             <div class="form-group col-md-4 d-flex align-items-center">
                    //                 <div style="width: 100%;">
                    //                     <label style="font-weight: 600; font-size: 13px;">Credit Account</label>
                    //                     <div class="input-group">
                    //                         <input type="text" class="form-control credit-input-po-disburse-edit-edit" id="credit_input_po_disburse_edit_edit" value="${entry.credit_amount}"
                    //                             style="height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                    //                         <div class="input-group-append">
                    //                             <button type="button" class="btn btn-sm ml-3 remove-account-entry-po-disburse-edit" style="background-color: transparent;">
                    //                                 <i class="fas fa-trash-alt text-danger"></i>
                    //                             </button>
                    //                         </div>
                    //                     </div>
                    //                 </div>
                    //             </div>
                    //         </div>
                    //     `);
                        // });

                        disbursements_general_ledger.forEach(entry => {
                            // Create the account entry row
                            $('#po-disburse-account-entry-container-edit').append(`
                                <div class="form-row mb-3 account-entry_po_disburse" data-expensesid="${entry.id}">
                                    <div class="form-group col-md-4">
                                        <label style="font-weight: 600; font-size: 13px;">Account</label>
                                        <select class="form-control account-select-po-disburse" id="account_select_po_disburse_edit_${entry.id}" 
                                            style="width: 90%; height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label style="font-weight: 600; font-size: 13px;">Debit Account</label>
                                        <input type="text" class="form-control debit-input-po-disburse-edit-edit" id="debit_input_po_disburse_edit_edit_${entry.id}" value="${entry.debit_amount}"
                                        style="width: 86%;height: 38px !important; padding: 10px 15px !important;
                                                  line-height: 1.5 !important;">
                                    </div>
                                    <div class="form-group col-md-4 d-flex align-items-center">
                                        <div style="width: 100%;">
                                            <label style="font-weight: 600; font-size: 13px;">Credit Account</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control credit-input-po-disburse-edit-edit" id="credit_input_po_disburse_edit_edit_${entry.id}" value="${entry.credit_amount}"
                                                style="width: 86%;height: 38px !important; padding: 10px 15px !important;
                                                  line-height: 1.5 !important;">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-sm ml-3 remove-account-entry-po-disburse-edit-edit" data-id="${entry.id}" style="background-color: transparent;">
                                                        <i class="fas fa-trash-alt text-danger"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `);

                            // Now populate the dropdown for this specific entry
                            var $accountSelect = $(
                                `#account_select_po_disburse_edit_${entry.id}`);
                            $accountSelect.empty();

                            setTimeout(function() {
                                $(`#account_select_po_disburse_edit_${entry.id}`)
                                    .select2({
                                        placeholder: "Select Account",
                                        allowClear: true,
                                        theme: 'bootstrap4',
                                        width: '100%'
                                    }).on('select2:open', function(e) {
                                        // Force high z-index for dropdown
                                        $('.select2-container').css(
                                            'z-index', 99999);
                                        $('.select2-dropdown').css(
                                            'z-index', 99999);
                                    });
                            }, 100);

                            // Add options from chart_of_accounts and sub_chart_of_accounts
                            response.chart_of_accounts.forEach(item => {
                                if (item.id == entry.coaid) {
                                    $accountSelect.append(
                                        `<option value="${item.id}" selected>${item.code} - ${item.account_name}</option>`
                                    );
                                } else {
                                    $accountSelect.append(
                                        `<option value="${item.id}">${item.code} - ${item.account_name}</option>`
                                    );
                                }

                                // Add sub-accounts if they exist
                                response.sub_chart_of_accounts.forEach(
                                    subitem => {
                                        if (subitem.coaid == item.id) {
                                            if (subitem.id == entry.coaid) {
                                                $accountSelect.append(
                                                    `<option style="font-style:italic;" value="${subitem.id}" selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${subitem.sub_code} - ${subitem.sub_account_name}</option>`
                                                );
                                            } else {
                                                $accountSelect.append(
                                                    `<option style="font-style:italic;" value="${subitem.id}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${subitem.sub_code} - ${subitem.sub_account_name}</option>`
                                                );
                                            }
                                        }
                                    });
                            });
                        });

                        $(document).on('input',
                            '.debit-input-po-disburse-edit, .credit-input-po-disburse-edit, .debit-input-po-disburse-edit-edit, .credit-input-po-disburse-edit-edit',
                            function() {
                                po_disburse_calculateTotals_edit();
                            });

                        function po_disburse_calculateTotals_edit() {
                            var debitTotal = 0;
                            var creditTotal = 0;

                            $('.account-entry_po_disburse').each(function() {
                                var debit = parseFloat($(this).find(
                                        '.debit-input-po-disburse-edit').val()) ||
                                    0;
                                var credit = parseFloat($(this).find(
                                        '.credit-input-po-disburse-edit').val()) ||
                                    0;
                                var debitEdit = parseFloat($(this).find(
                                        '.debit-input-po-disburse-edit-edit').val()) ||
                                    0;
                                var creditEdit = parseFloat($(this).find(
                                            '.credit-input-po-disburse-edit-edit')
                                        .val()) ||
                                    0;

                                debitTotal += debit + debitEdit;
                                creditTotal += credit + creditEdit;
                            });

                            $('#po-disburse-total-row-edit input').each(function(index) {
                                if (index === 0) {
                                    $(this).val('Debit: ' + debitTotal.toFixed(2) +
                                        ' | Credit: ' + creditTotal.toFixed(2));
                                } else if (index === 1) {
                                    $(this).val(debitTotal.toFixed(2));
                                } else {
                                    $(this).val(creditTotal.toFixed(2));
                                }
                            });
                        }

                        po_disburse_calculateTotals_edit();


                        /////////////////////////////////

                        $(document).on('input',
                            '#po-disburse-account-entry-container-edit .account-entry_po_disburse:last-child .debit-input-po-disburse-edit, #po-disburse-account-entry-container-edit .account-entry_po_disburse:last-child .credit-input-po-disburse-edit, #po-disburse-account-entry-container-edit .account-entry_po_disburse:last-child .debit-input-po-disburse-edit-edit, #po-disburse-account-entry-container-edit .account-entry_po_disburse:last-child .credit-input-po-disburse-edit-edit',
                            function() {
                                const $this = $(this);
                                const $row = $this.closest('.account-entry_po_disburse');

                                if ($this.val() && $this.val() !== '0.00') {
                                    $row.find($this.hasClass(
                                            'debit-input-po-disburse-edit') || $this
                                        .hasClass('debit-input-po-disburse-edit-edit') ?
                                        '.credit-input-po-disburse-edit, .credit-input-po-disburse-edit-edit' :
                                        '.debit-input-po-disburse-edit, .debit-input-po-disburse-edit-edit'
                                    ).val('0.00').trigger('change');
                                }
                            });

                        $(document).on('click', '.remove-account-entry-po-disburse-edit',
                            function() {
                                if ($('.account-entry_po_disburse').length > 1) {
                                    $(this).closest('.account-entry_po_disburse').remove();
                                    po_disburse_calculateTotals_edit();
                                }
                            });



                        // const newEntry = $(
                        //     '#po-disburse-account-entry-container-edit .account-entry_po_disburse'
                        // ).last();
                        // newEntry.find(
                        //     '.debit-input-po-disburse-edit, .credit-input-po-disburse-edit, .debit-input-po-disburse-edit-edit, .credit-input-po-disburse-edit-edit'
                        // ).on('input', function() {
                        //     const row = $(this).closest('.account-entry_po_disburse');
                        //     const debitInput = row.find(
                        //         '.debit-input-po-disburse-edit, .debit-input-po-disburse-edit-edit'
                        //     );
                        //     const creditInput = row.find(
                        //         '.credit-input-po-disburse-edit, .credit-input-po-disburse-edit-edit'
                        //     );

                        //     // If this input has a value (other than 0.00), clear the other one
                        //     if ($(this).val() !== '0.00' && $(this).val() !== '') {
                        //         if ($(this).is(
                        //                 '.debit-input-po-disburse-edit, .debit-input-po-disburse-edit-edit'
                        //             )) {
                        //             creditInput.val('0.00');
                        //         } else {
                        //             debitInput.val('0.00');
                        //         }
                        //     }
                        // });

                        // // Remove entry
                        // $(document).on('click', '.remove-account-entry-po-disburse-edit',
                        //     function() {
                        //         if ($('.account-entry_po_disburse').length > 1) {
                        //             $(this).closest('.account-entry_po_disburse').remove();
                        //             po_disburse_calculateTotals_edit();
                        //         }
                        //     });


                        // $(document).on('input',
                        //     '.debit-input-po-disburse-edit-edit, .credit-input-po-disburse-edit-edit',
                        //     function() {
                        //         po_disburse_calculateTotals_edit();
                        //     });

                        // // Simplified calculation function that only works with the edit entries
                        // function po_disburse_calculateTotals_edit() {
                        //     var debitTotal = 0;
                        //     var creditTotal = 0;

                        //     // Calculate totals from all edit entries
                        //     $('.account-entry_po_disburse').each(function() {
                        //         var debit = parseFloat($(this).find(
                        //                 '.debit-input-po-disburse-edit-edit').val()) ||
                        //             0;
                        //         var credit = parseFloat($(this).find(
                        //                 '.credit-input-po-disburse-edit-edit')
                        //             .val()) || 0;

                        //         debitTotal += debit;
                        //         creditTotal += credit;
                        //     });

                        //     // Update the totals display
                        //     $('#po-disburse-total-row-edit input').each(function(index) {
                        //         if (index === 0) {
                        //             $(this).val('Debit: ' + debitTotal.toFixed(2) +
                        //                 ' | Credit: ' + creditTotal.toFixed(2));
                        //         } else if (index === 1) {
                        //             $(this).val(debitTotal.toFixed(2));
                        //         } else {
                        //             $(this).val(creditTotal.toFixed(2));
                        //         }
                        //     });
                        // }

                        // // Initial calculation
                        // po_disburse_calculateTotals_edit();




                    }
                });

            });

            $(document).on('click', '#edit_selected_expenses_item', function() {

                var selected_expenses_item_id = $(this).attr('data-id')

                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/disbursement/items/edit_selected',
                    data: {
                        selected_expenses_item_id: selected_expenses_item_id
                    },
                    success: function(response) {

                        var expense_monitoring_items_selected = response
                            .expense_monitoring_items;
                        var expense_items = response.expense_items;


                        $("#id_edit_item_selected").val(expense_monitoring_items_selected[0]
                            .id);


                        $("#itemName_edit_selected").empty().trigger('change');
                        $("#itemName_edit_selected").append(
                            '<option value="" selected disabled>Select Item</option>'
                        );
                        expense_items.forEach(expense_item => {
                            if (expense_item.id == expense_monitoring_items_selected[0]
                                .itemid) {
                                $("#itemName_edit_selected").append(
                                    `<option value="${expense_item.id}" selected>${expense_item.description}</option>`
                                );
                            } else {
                                $("#itemName_edit_selected").append(
                                    `<option value="${expense_item.id}">${expense_item.description}</option>`
                                );
                            }
                        });


                        $("#amount_edit_selected").val(expense_monitoring_items_selected[0]
                            .amount);

                        $("#qty_edit_selected").val(expense_monitoring_items_selected[0]
                            .qty);

                        $("#totalAmount_edit_selected").val(expense_monitoring_items_selected[0]
                            .totalamount);

                    }
                });


                $(document).on('click', '#updateItemBtn_selected', function(e) {
                    e.preventDefault();
                    updateItemBtn_selected()
                })

                function updateItemBtn_selected() {
                    var id_edit_item_selected = $('#id_edit_item_selected').val();
                    var itemName_edit_selected = $('#itemName_edit_selected').val();
                    var amount_edit_selected = $('#amount_edit_selected').val();
                    var qty_edit_selected = $('#qty_edit_selected').val();
                    var totalAmount_edit_selected = $('#totalAmount_edit_selected').val();

                    $.ajax({
                        type: 'POST',
                        url: '/bookkeeper/disbursement/items/update_selected',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id_edit_item_selected: id_edit_item_selected,
                            itemName_edit_selected: itemName_edit_selected,
                            amount_edit_selected: amount_edit_selected,
                            qty_edit_selected: qty_edit_selected,
                            totalAmount_edit_selected: totalAmount_edit_selected

                        },
                        success: function(data) {
                            if (data[0].status == 1) {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Successfully updated'
                                })

                                $.ajax({
                                    type: 'GET',
                                    url: '/bookkeeper/disbursement/edit',
                                    data: {
                                        expense_monitoring_id: $(
                                                "#expenses_ID_edit")
                                            .val()
                                    },
                                    success: function(response) {

                                        var expense_monitoring_items = response
                                            .expense_monitoring_items;

                                        expense_monitoring_itemsTable()

                                        function expense_monitoring_itemsTable() {

                                            $("#expensesItemsTable_edit")
                                                .DataTable({
                                                    destroy: true,
                                                    autoWidth: false,
                                                    data: expense_monitoring_items,
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
                                                            'createdCell': function(
                                                                td,
                                                                cellData,
                                                                rowData,
                                                                row, col
                                                            ) {
                                                                $(td)
                                                                    .html(
                                                                        rowData
                                                                        .description
                                                                    )
                                                                    .addClass(
                                                                        'align-middle'
                                                                    )
                                                                    .attr(
                                                                        'data-id',
                                                                        rowData
                                                                        .id
                                                                    )
                                                                    .attr(
                                                                        'data-expensesid',
                                                                        rowData
                                                                        .id
                                                                    );
                                                            }
                                                        },
                                                        {
                                                            'targets': 1,
                                                            'orderable': false,
                                                            'createdCell': function(
                                                                td,
                                                                cellData,
                                                                rowData,
                                                                row, col
                                                            ) {
                                                                $(td)
                                                                    .html(
                                                                        rowData
                                                                        .amount
                                                                    )
                                                                    .addClass(
                                                                        'align-middle'
                                                                    );
                                                            }
                                                        },
                                                        {
                                                            'targets': 2,
                                                            'orderable': false,
                                                            'createdCell': function(
                                                                td,
                                                                cellData,
                                                                rowData,
                                                                row, col
                                                            ) {
                                                                $(td)
                                                                    .html(
                                                                        rowData
                                                                        .qty
                                                                    )
                                                                    .addClass(
                                                                        'align-middle'
                                                                    );
                                                            }
                                                        },
                                                        {
                                                            'targets': 3,
                                                            'orderable': false,
                                                            'createdCell': function(
                                                                td,
                                                                cellData,
                                                                rowData,
                                                                row, col
                                                            ) {
                                                                $(td)
                                                                    .html(
                                                                        rowData
                                                                        .totalamount
                                                                    )
                                                                    .addClass(
                                                                        'align-middle'
                                                                    );
                                                            }
                                                        },
                                                        {
                                                            'targets': 4,
                                                            'orderable': false,
                                                            'createdCell': function(
                                                                td,
                                                                cellData,
                                                                rowData,
                                                                row, col
                                                            ) {
                                                                var buttons =
                                                                    '<a href="javascript:void(0)" class="edit_selected_expenses_item" id="edit_selected_expenses_item" data-id="' +
                                                                    rowData
                                                                    .id +
                                                                    '" data-toggle="modal" data-target="#editSelectedExpensesItemModal"><i class="far fa-edit text-primary"></i></a>' +
                                                                    '&nbsp;&nbsp;' +
                                                                    '<a href="javascript:void(0)" class="delete_selected_expenses_item" id="delete_selected_expenses_item" data-id="' +
                                                                    rowData
                                                                    .id +
                                                                    '"><i class="far fa-trash-alt text-danger"></i></a>';
                                                                $(td)[0]
                                                                    .innerHTML =
                                                                    buttons;
                                                                $(td)
                                                                    .addClass(
                                                                        'text-center align-middle'
                                                                    );
                                                            }
                                                        }
                                                    ]
                                                });
                                        }




                                    }
                                });



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

            $("#itemName_edit_selected").on("change", function(e) {
                e.preventDefault();
                var itemId = $(this).val();
                if ($(this).val() === "add") {
                    // Reset dropdown to default
                    $(this).val("").trigger("change");
                    // Open the modal
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
                                // $('#amount_edit_selected').val(item.amount);
                                // $('#qty_edit_selected').val(item.qty);
                                // let total = item.amount * item.qty;
                                // $('#totalAmount_edit_selected').val(total);
                            }
                        }
                    });
                }
            });





            $(document).on('click', '.delete_selected_expenses_item', function() {
                var deletedisburse_itemId = $(this).attr('data-id')
                Swal.fire({
                    text: 'Are you sure you want to remove disbursement item?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Remove'
                }).then((result) => {
                    if (result.value) {
                        delete_disburse_item(deletedisburse_itemId)
                    }
                })
            });

            function delete_disburse_item(deletedisburse_itemId) {
                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/disbursement/delete_selected',
                    data: {
                        deletedisburse_itemId: deletedisburse_itemId

                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully deleted'
                            })

                            $.ajax({
                                type: 'GET',
                                url: '/bookkeeper/disbursement/edit',
                                data: {
                                    expense_monitoring_id: $("#expenses_ID_edit")
                                        .val()
                                },
                                success: function(response) {

                                    var expense_monitoring_items = response
                                        .expense_monitoring_items;

                                    expense_monitoring_itemsTable()

                                    function expense_monitoring_itemsTable() {

                                        $("#expensesItemsTable_edit")
                                            .DataTable({
                                                destroy: true,
                                                autoWidth: false,
                                                data: expense_monitoring_items,
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
                                                        'createdCell': function(
                                                            td,
                                                            cellData,
                                                            rowData,
                                                            row, col
                                                        ) {
                                                            $(td)
                                                                .html(
                                                                    rowData
                                                                    .description
                                                                )
                                                                .addClass(
                                                                    'align-middle'
                                                                )
                                                                .attr(
                                                                    'data-id',
                                                                    rowData
                                                                    .id
                                                                )
                                                                .attr(
                                                                    'data-expensesid',
                                                                    rowData
                                                                    .id
                                                                );
                                                        }
                                                    },
                                                    {
                                                        'targets': 1,
                                                        'orderable': false,
                                                        'createdCell': function(
                                                            td,
                                                            cellData,
                                                            rowData,
                                                            row, col
                                                        ) {
                                                            $(td)
                                                                .html(
                                                                    rowData
                                                                    .amount
                                                                )
                                                                .addClass(
                                                                    'align-middle'
                                                                );
                                                        }
                                                    },
                                                    {
                                                        'targets': 2,
                                                        'orderable': false,
                                                        'createdCell': function(
                                                            td,
                                                            cellData,
                                                            rowData,
                                                            row, col
                                                        ) {
                                                            $(td)
                                                                .html(
                                                                    rowData
                                                                    .qty
                                                                )
                                                                .addClass(
                                                                    'align-middle'
                                                                );
                                                        }
                                                    },
                                                    {
                                                        'targets': 3,
                                                        'orderable': false,
                                                        'createdCell': function(
                                                            td,
                                                            cellData,
                                                            rowData,
                                                            row, col
                                                        ) {
                                                            $(td)
                                                                .html(
                                                                    rowData
                                                                    .totalamount
                                                                )
                                                                .addClass(
                                                                    'align-middle'
                                                                );
                                                        }
                                                    },
                                                    {
                                                        'targets': 4,
                                                        'orderable': false,
                                                        'createdCell': function(
                                                            td,
                                                            cellData,
                                                            rowData,
                                                            row, col
                                                        ) {
                                                            var buttons =
                                                                '<a href="javascript:void(0)" class="edit_selected_expenses_item" id="edit_selected_expenses_item" data-id="' +
                                                                rowData
                                                                .id +
                                                                '" data-toggle="modal" data-target="#editSelectedExpensesItemModal"><i class="far fa-edit text-primary"></i></a>' +
                                                                '&nbsp;&nbsp;' +
                                                                '<a href="javascript:void(0)" class="delete_selected_expenses_item" id="delete_selected_expenses_item" data-id="' +
                                                                rowData
                                                                .id +
                                                                '"><i class="far fa-trash-alt text-danger"></i></a>';
                                                            $(td)[0]
                                                                .innerHTML =
                                                                buttons;
                                                            $(td)
                                                                .addClass(
                                                                    'text-center align-middle'
                                                                );
                                                        }
                                                    }
                                                ]
                                            });
                                    }




                                }
                            });
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }

            $("#itemName_edit_selected_selected").on("change", function(e) {
                e.preventDefault();
                var itemId = $(this).val();
                if ($(this).val() === "add") {
                    // Reset dropdown to default
                    $(this).val("").trigger("change");
                    // Open the modal
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
                                // $('#amount_edit_selected_selected').val(item.amount);
                                // $('#qty_edit_selected_selected').val(item.qty);
                                // let total = item.amount * item.qty;
                                // $('#totalAmount_edit_selected_selected').val(total);
                            }
                        }
                    });
                }
            });

            $('.updateExpensesBtn').on('click', function(event) {
                event.preventDefault();
                update_item_selected_selected()

            });


            function update_item_selected_selected() {

                var expenseID_edit = $('#expenseID_edit').val();

                var expenseDate_edit = $('#expenseDate_edit').val();

                var referenceNo_edit = $('#referenceNo_edit').val();

                var expenseName_edit = $('#expenseName_edit').val();

                var employeeName_edit = $('#employeeName_edit').val();

                var department_edit = $('#department_edit').val();

                var remarks_Edit = $('#remarks_Edit').val();

                var tableData = [];
                $("#expensesItemsTable_edit").find("tbody tr").each(function() {
                    if ($(this).is(":visible")) {
                        var expensesid = $(this).find("td:eq(0)").attr('data-expensesid');
                        var expenses_item_id = $(this).find("td:eq(0)").attr('data-id');
                        var expenses_item = $(this).find("td:eq(0)").text().trim();
                        var expenses_amount = $(this).find("td:eq(1)").text().trim();
                        var expenses_quantity = $(this).find("td:eq(2)").text().trim();
                        var expenses_total = $(this).find("td:eq(3)").text().trim();

                        if (expenses_item_id && expenses_item && expenses_amount &&
                            expenses_quantity &&
                            expenses_total) {
                            tableData.push({
                                expenses_item: expenses_item,
                                expenses_amount: expenses_amount,
                                expenses_quantity: expenses_quantity,
                                expenses_total: expenses_total,
                                expensesid: expensesid,
                                expenses_item_id: expenses_item_id

                            });
                        }
                    }
                });

                $.ajax({
                    type: "POST",
                    url: '/bookkeeper/expenses_monitoring/update',
                    data: {

                        expenses_items: tableData,
                        expenseID_edit: expenseID_edit,
                        expenseDate_edit: expenseDate_edit,
                        referenceNo_edit: referenceNo_edit,
                        expenseName_edit: expenseName_edit,
                        employeeName_edit: employeeName_edit,
                        department_edit: department_edit,
                        remarks_Edit: remarks_Edit,
                        _token: '{{ csrf_token() }}'
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
                                title: 'Successfully updated'
                            })



                        } else if (data[0].status == 3) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully updated'
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


            $(document).on('click', '.delete_expense_monitoring', function() {
                var deletedisbursementId = $(this).attr('data-id')
                Swal.fire({
                    text: 'Are you sure you want to remove selected disbursements?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Remove'
                }).then((result) => {
                    if (result.value) {
                        delete_disbursements(deletedisbursementId)
                    }
                })
            });

            function delete_disbursements(deletedisbursementId) {
                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/disbursement/delete',
                    data: {
                        deletedisbursementId: deletedisbursementId

                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully deleted'
                            })

                            expenses_monitoringTable();
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }


            $('.updateDisbursement').on('click', function(event) {
                event.preventDefault();
                update_item_selected_selected()

            });


            function update_item_selected_selected() {

                var expenseID_edit = $('#expenses_ID_edit').val();

                var expenseDate_edit = $('#expenses_date_edit').val();

                var voucherNo_edit = $('#expenses_voucher_no_edit').val();

                var refNo_edit = $('#expenses_ref_no_edit').val();

                var disburse_to_edit = $('#expenses_disbursement_to_edit').val();

                var department_edit = $('#expenses_department_edit').val();

                var paymentType_Edit = '';
                if ($('#expenses_cash_edit').is(':checked')) {
                    paymentType_Edit = 'Cash';
                } else if ($('#expenses_cheque_edit').is(':checked')) {
                    paymentType_Edit = 'Cheque';
                }

                var amount_edit = $('#expenses_amount_edit').val();

                var bank_edit = $('#expenses_bank_edit').val();

                var chequeNo_edit = $('#expenses_cheque_no_edit').val();

                var chequeDate_edit = $('#expenses_cheque_date_edit').val();

                var remarks_edit = $('#expenses_remarks_edit').val();

                var tableData = [];
                $("#expensesItemsTable_edit").find("tbody tr").each(function() {
                    if ($(this).is(":visible")) {
                        var expensesid = $(this).find("td:eq(0)").attr('data-expensesid');
                        var expenses_item_id = $(this).find("td:eq(0)").attr('data-id');
                        var expenses_item = $(this).find("td:eq(0)").text().trim();
                        var expenses_amount = $(this).find("td:eq(1)").text().trim();
                        var expenses_quantity = $(this).find("td:eq(2)").text().trim();
                        var expenses_total = $(this).find("td:eq(3)").text().trim();

                        if (expenses_item_id && expenses_item && expenses_amount &&
                            expenses_quantity &&
                            expenses_total) {
                            tableData.push({
                                expenses_item: expenses_item,
                                expenses_amount: expenses_amount,
                                expenses_quantity: expenses_quantity,
                                expenses_total: expenses_total,
                                expensesid: expensesid,
                                expenses_item_id: expenses_item_id

                            });
                        }
                    }
                });

                var entries = [];
                var hasError = false;

                $('#po-disburse-account-entry-container-edit .account-entry_po_disburse').each(function() {
                    var expensesid = $(this).attr('data-expensesid');
                    var account = $(this).find(`#account_select_po_disburse_edit_${expensesid}`).val();
                    var debit = parseFloat($(this).find(`#debit_input_po_disburse_edit_edit_${expensesid}`)
                        .val()) || 0;
                    var credit = parseFloat($(this).find(
                        `#credit_input_po_disburse_edit_edit_${expensesid}`).val()) || 0;

                    if (!account || (debit === 0 && credit === 0)) {
                        hasError = true;
                        return false; // break
                    }

                    entries.push({
                        expensesid: expensesid,
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
                    type: "POST",
                    url: '/bookkeeper/disbursement/update',
                    data: {

                        expenses_items: tableData,
                        expenseID_edit: expenseID_edit,
                        expenseDate_edit: expenseDate_edit,
                        voucherNo_edit: voucherNo_edit,
                        refNo_edit: refNo_edit,
                        disburse_to_edit: disburse_to_edit,
                        department_edit: department_edit,
                        paymentType_Edit: paymentType_Edit,
                        amount_edit: amount_edit,
                        bank_edit: bank_edit,
                        chequeNo_edit: chequeNo_edit,
                        chequeDate_edit: chequeDate_edit,
                        entries: entries,
                        remarks_edit: remarks_edit,
                        _token: '{{ csrf_token() }}'
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
                                title: 'Successfully updated'
                            })
                            expenses_monitoringTable()



                        } else if (data[0].status == 3) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully updated'
                            })
                            expenses_monitoringTable()



                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }

            $('#print_disbursements').on('click', function() {
                const dateRange = $('#dateRange').val();
                const employee_val = $('#employee_id').val();
                const department = $('#department_id').val();

                window.open('/bookkeeper/disbursement_print?date_range=' + dateRange +
                    '&employee_val=' + employee_val +
                    '&department_val=' + department, '_blank');
            });

            /////////////////////////////////////////////

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
                $('#accounting_expense_monitoring').DataTable().draw(); // trigger redraw
            });

            $('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                minDateFilter = '';
                maxDateFilter = '';
                $('#accounting_expense_monitoring').DataTable().draw(); // trigger redraw
            });

            //supplier name filter
            var selectedSupplier = '0';

            $('#employee_id').on('change', function() {
                selectedSupplier = $(this).val();
                console.log(selectedSupplier);
                $('#accounting_expense_monitoring').DataTable().draw(); // trigger redraw
            });

            var selectedDepartment = '0';

            $('#department_id').on('change', function() {
                selectedDepartment = $(this).val();
                $('#accounting_expense_monitoring').DataTable().draw(); // trigger redraw
            });



            // Custom date filter
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                if (settings.nTable.id !== 'accounting_expense_monitoring')
                    return true; // filter only the selected table

                const dateStr = data[5]; // date column (already formatted to 'YYYY-MM-DD' by createdCell)
                const date = moment(dateStr, 'YYYY-MM-DD');

                const supplierId = data[1];
                console.log('suplierid haysssss', supplierId);

                const department = data[2];

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



                if (selectedDepartment !== '0' && department !== selectedDepartment) {
                    return false;
                }

                return true; // Include the row if it matches both filters


            });


            $('#close_disbursement_modal').on('click', function() {
                var hasData =
                    $("#expenses_date").val().trim() !== "" ||
                    $("#expenses_remarks").val().trim() !== "";

                if ($('#expenses_disbursement_to').val()) {
                    hasData = true;
                }
                if ($('#expenses_department').val()) {
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

                            $('#expenses_date').val("");
                            $('#expenses_remarks').val("");

                            $('#expenses_disbursement_to').val("").trigger('change');
                            $('#expenses_department').val("").trigger('change');

                        } else {

                            $('#add_purchase_order').modal('show');

                        }
                    });
                } else {

                    $('#add_purchase_order').modal('hide');


                }
            });

            // const poInput = $('#expenses_voucher_no');
            // poInput.on('keydown', function(e) {
            //     const value = $(this).val();
            //     if (value === 'CV-' && (e.key === 'Delete' || e.key === 'Backspace') || value === 'CHV-' &&
            //         (e.key === 'Delete' || e.key === 'Backspace')) {
            //         e.preventDefault(); // Block delete and backspace
            //     }
            // });

            // poInput.on('input', function() {
            //     const value = $(this).val();
            //     if (value === 'CV-' || value === 'CHV-') {
            //         $(this).val(value);
            //     }
            // });

            // WORKING CODE
            // const poInput = $('#expenses_voucher_no');

            // poInput.on('keydown', function(e) {
            //     const value = $(this).val();
            //     const selectionStart = this.selectionStart;
            //     const selectionEnd = this.selectionEnd;
            //     const prefix = value.startsWith('CHV-') ? 'CHV-' : 'CV-';
            //     const prefixLength = prefix === 'CHV-' ? 5 : 4;

            //     // Prevent prefix from being modified
            //     if ((selectionStart < prefixLength || selectionEnd < prefixLength) &&
            //         (e.key === 'Delete' || e.key === 'Backspace' ||
            //             (e.key.length === 1 && !e.ctrlKey && !e.metaKey))) {
            //         e.preventDefault();
            //     }
            // });

            // poInput.on('input', function() {
            //     let value = $(this).val().toUpperCase();
            //     const prefix = value.startsWith('CHV-') ? 'CHV-' : 'CV-';

            //     // Ensure the prefix remains intact
            //     if (prefix === 'CHV-') {
            //         value = 'CHV-' + value.substring(4).replace(/[^A-Z0-9]/g, '');
            //     } else {
            //         value = 'CV-' + value.substring(3).replace(/[^A-Z0-9]/g, '');
            //     }

            //     $(this).val(value);
            // });
            ///////////////////////////////////

            const poInput = $('#expenses_voucher_no');

            poInput.on('keydown', function(e) {
                const value = $(this).val();
                const selectionStart = this.selectionStart;
                const selectionEnd = this.selectionEnd;
                const prefix = value.startsWith('CHV-') ? 'CHV-' : 'CV-';
                const prefixLength = prefix.length;

                // Prevent prefix from being modified when selection includes prefix characters
                if (selectionStart < prefixLength && 
                    (e.key === 'Delete' || e.key === 'Backspace' ||
                    (e.key.length === 1 && !e.ctrlKey && !e.metaKey))) {
                    // Allow if cursor is at the end and Backspace is pressed (normal behavior)
                    if (!(selectionStart === prefixLength && e.key === 'Backspace' && selectionStart === selectionEnd)) {
                        e.preventDefault();
                    }
                }
            });

            poInput.on('input', function() {
                let value = $(this).val().toUpperCase();
                const originalPrefix = value.startsWith('CHV-') ? 'CHV-' : 'CV-';
                const newPrefix = value.startsWith('CHV') ? 'CHV-' : 'CV-';
                
                // Remove all hyphens first to avoid multiple hyphens
                value = value.replace(/-/g, '');
                
                // Reconstruct the value with proper prefix
                if (newPrefix === 'CHV-') {
                    value = 'CHV-' + value.substring(3); // Remove "CHV" from the remaining part
                } else {
                    value = 'CV-' + value.substring(2); // Remove "CV" from the remaining part
                }
                
                // Ensure we don't end up with just "CHV" or "CV" without hyphen
                if (value === 'CHV') value = 'CHV-';
                if (value === 'CV') value = 'CV-';
                
                $(this).val(value);
            });

            

            // const poInput_edit = $('#expenses_voucher_no_edit');
            // poInput_edit.on('keydown', function(e) {
            //     const value = $(this).val();
            //     if (value === 'CV-' && (e.key === 'Delete' || e.key === 'Backspace') || value === 'CHV-' &&
            //         (e.key === 'Delete' || e.key === 'Backspace')) {
            //         e.preventDefault(); // Block delete and backspace
            //     }
            // });
            // poInput_edit.on('input', function() {
            //     const value = $(this).val();
            //     if (value === 'CV-' || value === 'CHV-') {
            //         $(this).val(value);
            //     }
            // });

            // const poInput_edit = $('#expenses_voucher_no_edit');

            // poInput_edit.on('keydown', function(e) {
            //     const value = $(this).val();
            //     const selectionStart = this.selectionStart;
            //     const selectionEnd = this.selectionEnd;
            //     const prefix = value.startsWith('CHV-') ? 'CHV-' : 'CV-';
            //     const prefixLength = prefix === 'CHV-' ? 5 : 4;

            //     // Prevent prefix from being modified
            //     if ((selectionStart < prefixLength || selectionEnd < prefixLength) &&
            //         (e.key === 'Delete' || e.key === 'Backspace' ||
            //             (e.key.length === 1 && !e.ctrlKey && !e.metaKey))) {
            //         e.preventDefault();
            //     }
            // });

            // poInput_edit.on('input', function() {
            //     let value = $(this).val().toUpperCase();
            //     const prefix = value.startsWith('CHV-') ? 'CHV-' : 'CV-';

            //     // Ensure the prefix remains intact
            //     if (prefix === 'CHV-') {
            //         value = 'CHV-' + value.substring(4).replace(/[^A-Z0-9]/g, '');
            //     } else {
            //         value = 'CV-' + value.substring(3).replace(/[^A-Z0-9]/g, '');
            //     }

            //     $(this).val(value);
            // });


            const poInput_edit = $('#expenses_voucher_no_edit');

            poInput_edit.on('keydown', function(e) {
                const value = $(this).val();
                const selectionStart = this.selectionStart;
                const selectionEnd = this.selectionEnd;
                const prefix = value.startsWith('CHV-') ? 'CHV-' : 'CV-';
                const prefixLength = prefix.length;

                // Prevent prefix from being modified when selection includes prefix characters
                if (selectionStart < prefixLength && 
                    (e.key === 'Delete' || e.key === 'Backspace' ||
                    (e.key.length === 1 && !e.ctrlKey && !e.metaKey))) {
                    // Allow if cursor is at the end and Backspace is pressed (normal behavior)
                    if (!(selectionStart === prefixLength && e.key === 'Backspace' && selectionStart === selectionEnd)) {
                        e.preventDefault();
                    }
                }
            });

            poInput_edit.on('input', function() {
                let value = $(this).val().toUpperCase();
                const originalPrefix = value.startsWith('CHV-') ? 'CHV-' : 'CV-';
                const newPrefix = value.startsWith('CHV') ? 'CHV-' : 'CV-';
                
                // Remove all hyphens first to avoid multiple hyphens
                value = value.replace(/-/g, '');
                
                // Reconstruct the value with proper prefix
                if (newPrefix === 'CHV-') {
                    value = 'CHV-' + value.substring(3); // Remove "CHV" from the remaining part
                } else {
                    value = 'CV-' + value.substring(2); // Remove "CV" from the remaining part
                }
                
                // Ensure we don't end up with just "CHV" or "CV" without hyphen
                if (value === 'CHV') value = 'CHV-';
                if (value === 'CV') value = 'CV-';
                
                $(this).val(value);
            });


            ///////////////////////

            const ref_no_poInput = $('#expenses_ref_no');
            ref_no_poInput.on('keydown', function(e) {
                const value = $(this).val();
                if (value === 'PO ' && (e.key === 'Delete' || e.key === 'Backspace') || value === 'CHV-' &&
                    (e.key === 'Delete' || e.key === 'Backspace')) {
                    e.preventDefault(); // Block delete and backspace
                }
            });
            ref_no_poInput.on('input', function() {
                const value = $(this).val();
                if (value === 'PO ') {
                    $(this).val(value);
                }
            });

            ////////////////////////////////////////////////////
            let grandTotalDisplayed_disburse = false;
            $('.btn-success[data-target="#addaccountsetupModal_po_disburse"]').click(function() {
                let accountSelectId = 'account-select-po-disburse-add-' + Date.now();
                const grand_total_amount__disburse_po = parseFloat($('#grand_total_amount').text().replace(
                    /,/g, ''));
                const newEntryHtml = `
                        <div class="form-row account-entry_po_disburse mt-4" data-expensesid="0">
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
                                <input type="text" class="form-control debit-input-po-disburse" value="${grandTotalDisplayed_disburse ? '0.00' : (isNaN(grand_total_amount__disburse_po) ? '0.00' : grand_total_amount__disburse_po)}"
                                    style="width: 90%;height: 38px !important; padding: 10px 15px !important;
                                        line-height: 1.5 !important;">
                            </div>
                            <div class="form-group col-md-4 d-flex align-items-center">
                                <div style="width: 100%;">
                                    <label style="font-weight: 600; font-size: 13px;">Credit Account</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control credit-input-po-disburse" value="0.00"
                                            style="height: 38px !important; padding: 10px 15px !important;
                                                  line-height: 1.5 !important;">
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

            ////////////////////////////////////////////////////////

            $('.btn-success[data-target="#addaccountsetupModal_po_disburse_edit"]').click(function() {
                let accountSelectId = 'account_select_po_disburse_edit_0' + Date.now();
                const newEntryHtml = `
                        <div class="form-row account-entry_po_disburse mt-4" data-expensesid="0">
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
                                <input type="text" class="form-control debit-input-po-disburse-edit" id="debit_input_po_disburse_edit_edit_0" value="0.00"
                                style="width: 86%;height: 38px !important; padding: 10px 15px !important;
                                                  line-height: 1.5 !important;">
                            </div>
                            <div class="form-group col-md-4 d-flex align-items-center">
                                <div style="width: 100%;">
                                    <label style="font-weight: 600; font-size: 13px;">Credit Account</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control credit-input-po-disburse-edit" id="credit_input_po_disburse_edit_edit_0" value="0.00"
                                        style="width: 86%;height: 38px !important; padding: 10px 15px !important;
                                                  line-height: 1.5 !important;">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-sm ml-3 remove-account-entry-po-disburse-edit" style="background-color: transparent;">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                $('#po-disburse-account-entry-container-edit').append(newEntryHtml);
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
                const newEntry = $('#po-disburse-account-entry-container-edit .account-entry_po_disburse')
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


            // $(document).on('input', '.debit-input-po-disburse-edit, .credit-input-po-disburse-edit, .debit-input-po-disburse-edit-edit, .credit-input-po-disburse-edit-edit', function() {
            //     po_disburse_calculateTotals_edit();
            // });

            // // Calculate Totals
            // function po_disburse_calculateTotals_edit() {
            //     var debitTotal = 0;
            //     var creditTotal = 0;

            //     $('.account-entry_po_disburse').each(function() {
            //         var debit = parseFloat($(this).find('.debit-input-po-disburse-edit').val()) || 0;
            //         var credit = parseFloat($(this).find('.credit-input-po-disburse-edit').val()) || 0;
            //         var debitEdit = parseFloat($(this).find('.debit-input-po-disburse-edit-edit').val()) || 0;
            //         var creditEdit = parseFloat($(this).find('.credit-input-po-disburse-edit-edit').val()) || 0;

            //         debitTotal += debit + debitEdit;
            //         creditTotal += credit + creditEdit;
            //     });

            //     // $('.form-row.mb-3 input').each(function(index) {
            //     $('#po-disburse-total-row-edit input').each(function(index) {
            //         if (index === 0) {
            //             $(this).val('Debit: ' + debitTotal.toFixed(2) + ' | Credit: ' + creditTotal.toFixed(
            //                 2));
            //         } else if (index === 1) {
            //             $(this).val(debitTotal.toFixed(2));
            //         } else {
            //             $(this).val(creditTotal.toFixed(2));
            //         }
            //     });
            // }



            // Check every 500ms
            setInterval(function() {
                const po_disburse_debit = parseFloat($('#po_total_debit_amount_edit').val()) || 0;
                const po_disburse_credit = parseFloat($('#po_total_credit_amount_edit').val()) || 0;
                const expenses_voucher_no_disburse_po = $('#expenses_voucher_no').val();
                // const po_disburse_receiving_account = $('.account-entry_po-disburse .account-select-po-disburse').val();
                // console.log('Selected Receiving Account:', po_disburse_receiving_account);

                if (expenses_voucher_no_disburse_po == '' && po_disburse_debit !== po_disburse_credit) {
                    $('#updateDisbursement').prop('disabled', true);
                } else if (expenses_voucher_no_disburse_po == '' && po_disburse_debit ==
                    po_disburse_credit) {
                    $('#updateDisbursement').prop('disabled', true);
                } else if (expenses_voucher_no_disburse_po != '' && po_disburse_debit ==
                    po_disburse_credit) {
                    $('#updateDisbursement').prop('disabled', false);
                } else if (expenses_voucher_no_disburse_po != '' && po_disburse_debit !==
                    po_disburse_credit) {
                    $('#updateDisbursement').prop('disabled', true);
                }

            }, 500);

            setInterval(function() {
                po_disburse_calculateTotals()
                const po_disburse_debit = parseFloat($('#po_total_debit_amount').val()) || 0;
                const po_disburse_credit = parseFloat($('#po_total_credit_amount').val()) || 0;
                const expenses_voucher_no_disburse_po = $('#expenses_voucher_no').val();
                // const po_disburse_receiving_account = $('.account-entry_po-disburse .account-select-po-disburse').val();
                // console.log('Selected Receiving Account:', po_disburse_receiving_account);

                if (expenses_voucher_no_disburse_po == '' && po_disburse_debit !== po_disburse_credit) {
                    $('#saveDisbursement').prop('disabled', true);
                } else if (expenses_voucher_no_disburse_po == '' && po_disburse_debit ==
                    po_disburse_credit) {
                    $('#saveDisbursement').prop('disabled', true);
                } else if (expenses_voucher_no_disburse_po != '' && po_disburse_debit ==
                    po_disburse_credit) {
                    $('#saveDisbursement').prop('disabled', false);
                } else if (expenses_voucher_no_disburse_po != '' && po_disburse_debit !==
                    po_disburse_credit) {
                    $('#saveDisbursement').prop('disabled', true);
                }

            }, 500);

            $('.stock_in').on('click', function(event) {
                // event.preventDefault();
                stock_in()

            });

            $('#cashier_debit_account_edit').select2({
                placeholder: "Select Debit Account",
                allowClear: true,
                theme: 'bootstrap4'
            }).on('select2:open', function(e) {
                // Force high z-index for dropdown
                $('.select2-container').css('z-index', 99999);
                $('.select2-dropdown').css('z-index', 99999);
            });

            function stock_in() {
                var item_name = $('#item_name').val();
                var item_code = $('#item_code').val();
                var item_quantity = $('#item_quantity').val();
                var item_amount = $('#item_amount').val();
                var itemType = $('input[name="itemType"]:checked').val();
                var debit_account = $('#cashier_debit_account_edit').val();

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
                            $("#cashier_debit_account_edit").val(null).trigger('change');
                            po_expensesItems()


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

                        $("#itemName").empty().trigger('change');
                        $("#itemName").append(
                            '<option value="" selected disabled>Select Item</option>'
                        );
                        $("#itemName").append(
                            '<option style="color:blue;  text-decoration: underline;" value="add">+ Add New                                         Item</option>'
                        );
                        all_items.forEach(items => {
                            $("#itemName").append(
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
                    }

                });
            }


            $(document).on('click', '.remove-account-entry-po-disburse-edit-edit', function() {
                var deletedisburse_itemId = $(this).attr('data-id')
                Swal.fire({
                    text: 'Are you sure you want to remove journal entry?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Remove'
                }).then((result) => {
                    if (result.value) {
                        delete_disburse_je(deletedisburse_itemId)
                    }
                })
            });

            function delete_disburse_je(deletedisburse_itemId) {
                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/disbursement/delete_selected_je',
                    data: {
                        deletedisburse_itemId: deletedisburse_itemId

                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully deleted'
                            });
                            $('.edit_expense_monitoring').click();
                            $('#edit_purchase_order').modal('hide');
                            setTimeout(() => {
                                $('#edit_purchase_order').modal(
                                    'show');
                            }, 500);

                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }

            $('#add_account_btn_disburseje').on('click', function() {
                $('#po-disburse-total-row').show();
            });

            $('#add_purchase_order').on('hidden.bs.modal', function() {
                $('#po-disburse-total-row').hide();
            });







        });
    </script>
@endsection
