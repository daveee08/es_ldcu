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
        height: 40px !important;


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
</style>

</head>
@section('content')

    <body>
        <div class="card p-3">
            <div class="bg-none">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-wallet nav-icon mr-2" style="font-size: 33px;"></i>
                    <h1 class="text-black m-0">Expense Monitoring</h1>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Expense Monitoring</li>
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
                                <label for="dateRange" class="font-weight-bold">Date Range</label>
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
                                    <option value="0">Select Department</option>
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
                                    data-toggle="modal" data-target="#addExpensesModal">
                                    + Add Expense
                                </button>
                                <button class="btn btn-primary btn-sm" id="print_expense_monitoring">
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
            <div class="modal fade show" id="addExpensesModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="addExpensesModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content overflow-hidden" style="border-radius: 16px;">
                        <div class="modal-header" style="background-color:#cac9c9; border-top--radius: 16px !important;">
                            <h5 class="modal-title" id="addExpensesModalLabel">Expenses Monitoring</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                id="add_expenses_modal_close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
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
                                                <option value="" selected>Select Employee</option>
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
                                            <option value="">Select Department</option>
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
                            {{-- <button type="button" class="btn btn-dark">Print</button> --}}
                        </div>
                    </div>
                </div>
            </div>

            <!-- EDIT Expenses Modal -->
            <div class="modal fade show" id="editExpensesModal" data-backdrop="static" tabindex="-1" role="dialog"
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
                                        <table class="table table-bordered table-sm expensesItemsTable_edit"
                                            id="expensesItemsTable_edit">
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
                            {{-- <button type="button" class="btn btn-dark">Print</button> --}}
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
                                    placeholder="Enter QTY" required>
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
                                    <option value="">Select Item</option>
                                    <!-- The "Add New Item" option -->
                                    {{-- @foreach (DB::table('bk_expenses_items')->where('deleted', 0)->get() as $item)
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
                                        placeholder="Enter QTY" required>
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
                                        placeholder="Enter QTY" required>
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
                                    @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                        <option value="{{ $coa->id }}">{{ $coa->account_name }}</option>
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

            <div class="modal fade" id="add_disbursement_receiving" data-backdrop="static" role="dialog"
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
                                        <input type="text" id="expenses_voucher_no_disburse_po" class="form-control"
                                            value="CV/CHV 0000">
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
                                        {{-- <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-right"><strong>TOTAL</strong></td>
                                            <td colspan="2" id="grand_total_amount__disburse_po"></td>
                                        </tr>
                                    </tfoot> --}}
                                    </table>
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

            $('#amount_edit_selected_selected, #qty_edit_selected_selected').on('input', function() {
                var amount = $('#amount_edit_selected_selected').val();
                var qty = $('#qty_edit_selected_selected').val();
                let total = amount * qty;
                $('#totalAmount_edit_selected_selected').val(total);
            });

            $('#amount_edit_selected, #qty_edit_selected').on('input', function() {
                var amount = $('#amount_edit_selected').val();
                var qty = $('#qty_edit_selected').val();
                let total = amount * qty;
                $('#totalAmount_edit_selected').val(total);
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

            $('#amount_selected, #qty_selected').on('input', function() {
                var amount = $('#amount_selected').val();
                var qty = $('#qty_selected').val();
                let total = amount * qty;
                $('#totalAmount_selected').val(total);
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

                row.append($("<td>").html(
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
                }
            });

            $('.addExpensesBtn').on('click', function(event) {
                event.preventDefault();
                create_expenses_monitoring()
            });

            function create_expenses_monitoring() {

                var expenseDate = $('#expenseDate').val();
                var referenceNo = $('#referenceNo').val();
                var expenseName = $('#expenseName').val();
                var employeeName = $('#employeeName').val();
                var department = $('#department').val();
                var remarks = $('#remarks').val();
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

                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/expenses_monitoring/create',
                    data: {
                        expensesItem: tableData,
                        expenseDate: expenseDate,
                        referenceNo: referenceNo,
                        expenseName: expenseName,
                        employeeName: employeeName,
                        department: department,
                        remarks: remarks,
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
                            $("#expenseDate").val("");
                            $("#referenceNo").val("");
                            $("#expenseName").val("");
                            $("#employeeName").val("");
                            $("#department").val("");
                            $("#remarks").val("");
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
                        url: '/bookkeeper/expenses_monitoring/fetch',
                        type: 'GET',
                        dataSrc: function(json) {
                            return json;
                        }
                    },
                    columns: [{
                            "data": "refnum"
                        },
                        {
                            "data": "firstname"
                        },
                        {
                            "data": "department"
                        },
                        {
                            "data": "remarks"
                        },
                        {
                            "data": "amount"
                        },
                        {
                            "data": "transdate"
                        },
                        {
                            "data": "status"
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
                                $(td).html(rowData.refnum).addClass(
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
                                $(td).html(rowData.department).addClass('align-middle');

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
                                $(td).html(rowData.transdate.split(' ')[0]).addClass(
                                    'align-middle');
                            }
                        },

                        {
                            'targets': 6,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.status).addClass(
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
                                    '" data-toggle="modal" data-target="#editExpensesModal"><i class="far fa-edit text-primary"></i></a>' +
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

            $(document).on('click', '.edit_expense_monitoring', function() {

                var expense_monitoring_id = $(this).attr('data-id')

                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/expenses_monitoring/edit',
                    data: {
                        expense_monitoring_id: expense_monitoring_id
                    },
                    success: function(response) {

                        var expense_monitoring_selected = response.expense_monitoring;
                        var expense_monitoring_items = response.expense_monitoring_items;
                        var employees = response.employees;
                        var departments = response.departments;

                        var expenseDate_edit = expense_monitoring_selected[0].transdate.split(
                            ' ')[0];
                        $("#expenseDate_edit").val(expenseDate_edit);
                        $("#expenseID_edit").val(expense_monitoring_selected[0].id);
                        $("#referenceNo_edit").val(expense_monitoring_selected[0].refnum);
                        $("#expenseName_edit").val(expense_monitoring_selected[0].description);

                        $("#employeeName_edit").empty().trigger('change');
                        $("#employeeName_edit").append(
                            '<option value="" selected disabled>Select Employee</option>'
                        );
                        employees.forEach(employee => {
                            if (employee.id == expense_monitoring_selected[0]
                                .requestedbyid) {
                                $("#employeeName_edit").append(
                                    `<option value="${employee.id}" selected>${employee.firstname} ${employee.lastname}</option>`
                                );
                            } else {
                                $("#employeeName_edit").append(
                                    `<option value="${employee.id}">${employee.firstname} ${employee.lastname}</option>`
                                );
                            }
                        });

                        $("#department_edit").empty().trigger('change');
                        $("#department_edit").append(
                            '<option value="" selected disabled>Select Department</option>'
                        );
                        departments.forEach(department => {
                            if (department.id == expense_monitoring_selected[0]
                                .departmentid) {
                                $("#department_edit").append(
                                    `<option value="${department.id}" selected>${department.department}</option>`
                                );
                            } else {
                                $("#department_edit").append(
                                    `<option value="${department.id}">${department.department}</option>`
                                );
                            }
                        });

                        $("#remarks_Edit").val(expense_monitoring_selected[0].description);

                        $("#grand_total_amount_edit").text(expense_monitoring_selected[0]
                            .amount);

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
                                        "data": "itemprice"
                                    },
                                    {
                                        "data": "qty"
                                    },
                                    {
                                        "data": "total"
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
                                            $(td).html(rowData.itemprice)
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
                                            $(td).html(rowData.total)
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




                    }
                });

            });

            $(document).on('click', '#edit_selected_expenses_item', function() {

                var selected_expenses_item_id = $(this).attr('data-id')

                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/expenses_monitoring/edit_selected',
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
                            .itemprice);

                        $("#qty_edit_selected").val(expense_monitoring_items_selected[0]
                            .qty);

                        $("#totalAmount_edit_selected").val(expense_monitoring_items_selected[0]
                            .total);

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
                        url: '/bookkeeper/expenses_monitoring/update_selected',
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
                                    url: '/bookkeeper/expenses_monitoring/edit',
                                    data: {
                                        expense_monitoring_id: $("#expenseID_edit")
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
                                                            "data": "itemprice"
                                                        },
                                                        {
                                                            "data": "qty"
                                                        },
                                                        {
                                                            "data": "total"
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
                                                                        .itemprice
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
                                                                        .total
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
                                $('#amount_edit_selected').val(item.amount);
                                $('#qty_edit_selected').val(item.qty);
                                let total = item.amount * item.qty;
                                $('#totalAmount_edit_selected').val(total);
                            }
                        }
                    });
                }
            });





            $(document).on('click', '.delete_selected_expenses_item', function() {
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
                    url: '/bookkeeper/expenses_monitoring/delete_selected',
                    data: {
                        deletepoId: deletepoId

                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully deleted'
                            })

                            $.ajax({
                                type: 'GET',
                                url: '/bookkeeper/expenses_monitoring/edit',
                                data: {
                                    expense_monitoring_id: $("#expenseID_edit")
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
                                                        "data": "itemprice"
                                                    },
                                                    {
                                                        "data": "qty"
                                                    },
                                                    {
                                                        "data": "total"
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
                                                                    .itemprice
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
                                                                    .total
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
                var deleteexpmonitorId = $(this).attr('data-id')
                Swal.fire({
                    text: 'Are you sure you want to remove selected expenses?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Remove'
                }).then((result) => {
                    if (result.value) {
                        delete_expenses_monitoring(deleteexpmonitorId)
                    }
                })
            });

            function delete_expenses_monitoring(deleteexpmonitorId) {
                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/expenses_monitoring/delete',
                    data: {
                        deleteexpmonitorId: deleteexpmonitorId

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

            $('#print_expense_monitoring').on('click', function() {
                const dateRange = $('#dateRange').val();
                const employee_val = $('#employee_id').val();
                const department = $('#department_id').val();

                window.open('/bookkeeper/expenses_monitoring_print?date_range=' + dateRange +
                    '&employee_val=' + employee_val +
                    '&department_val=' + department, '_blank');
            });


            /////////////////////////////////////////////////////////////

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


            $('#add_expenses_modal_close').on('click', function() {
                var hasData =
                    $("#expenseDate").val().trim() !== "" ||
                    // $("#referenceNo").val().trim() !== "" ||
                    $("#expenseName").val().trim() !== "" ||

                    $("#remarks").val().trim() !== "";

                if ($('#employeeName').val()) {
                    hasData = true;
                }
                if ($('#department').val()) {
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

                            $('#expenseDate').val("");
                            // $('#referenceNo').val("");
                            $('#expenseName').val("");
                            $('#remarks').val("");

                            $('#employeeName').val("").trigger('change');
                            $('#department').val("").trigger('change');

                        } else {

                            $('#addExpensesModal').modal('show');

                        }
                    });
                } else {

                    $('#addExpensesModal').modal('hide');


                }
            });


            $('.stock_in').on('click', function(event) {
                // event.preventDefault();
                stock_in()

            });

            function stock_in() {
                var item_name = $('#item_name').val();
                var item_code = $('#item_code').val();
                var item_quantity = $('#item_quantity').val();
                var item_amount = $('#item_amount').val();
                var itemType = $('#inventory').attr('checked') ? 'inventory' : 'non-inventory';
                var debit_account = $('#debit_account').val();

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


        });
    </script>
@endsection
