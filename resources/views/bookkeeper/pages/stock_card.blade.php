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
    <title>Stock Card</title>
    <style>
        select,
        input,
        th,
        td {
            font-size: 13px;
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
            font-weight: 600 !important;
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

        #assign_item {
            display: none;
        }

        td:hover #assign_item {
            display: inline-block;
        }
        .search-container {
    display: flex;
    align-items: center;
    gap: 10px;
}

#stock_history_table_filter {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 15px;
}
    </style>

</head>



<body>
    @section('content')
        <div class="container-fluid mt-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="fas fa-file-alt ml-2 mt-2 mb-2" style="font-size: 33px;"></i> <i
                        class="fas fa-pencil-alt mt-2 mr-3 mb-2" style="font-size: 18px;"></i>
                    <h1 class="text-black m-0">Stock Card</h1>

                </div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Stock Card</li>
                    </ol>
                </nav>

            </div>
            <div class="mb-3" style="color: black;  font-size: 13px;">
                <ul class="nav nav-tabs" style="border-bottom: 4px solid #d9d9d9;">
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
                        <a class="nav-link" href="/bookkeeper/receiving"
                            class="nav-link {{ Request::url() == url('/bookkeeper/receiving') ? 'active' : '' }}"
                            style="color: black;">Receiving
                            Setup</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/stock_card"
                            class="nav-link {{ Request::url() == url('/stock_card') ? 'active' : '' }}"
                            style="color: black; font-weight: 600; background-color: #d9d9d9; border-top-left-radius: 10px; border-top-right-radius: 10px;">Stock
                            Card</a>
                    </li>
                </ul>
            </div>
            {{-- <hr style="border-top: 2px solid #d9d9d9;"> --}}
          


            <div class="row py-3">
                <div class="col-md-12">
                    <button class="btn btn-success btn-sm" id="add_suppliers" style="background-color: #015918;">
                        + Add Inventory Item
                    </button>
                </div>
            </div>

            <div class="row bg-white py-3">
                <div class="col-lg-12 col-md-12">
                    <div class="table-responsive w-100">
                        <table id="accounting_inventory_table" class="table table-bordered table-sm w-100">
                            <thead class="table-secondary">
                                <tr>
                                    <th class="text-left">Item Code</th>
                                    <th class="text-left">Item Name</th>
                                    <th class="text-left">Initial Stock</th>
                                    <th class="text-left">Stock IN</th>
                                    <th class="text-left">Stock OUT</th>
                                    <th class="text-left">Stock Onhdand</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

              {{-- <div class="container-fluid p-0 mt-3"> --}}
                <div class="bg-light p-4 border rounded" id="filter">
                    <div class="row align-items-center">
                        <!-- Date Range -->
                        <div class="col-md-3">
                            <label for="dateRange" class="font-weight-bold">Date Range</label>
                            <input type="text" id="dateRange" class="form-control">
                        </div>

                        <!-- Supplier Name -->
                        <div class="col-md-3">
                            <label for="supplierName" class="font-weight-bold">Department</label>
                            <select id="select_departmentName" class="form-control">
                                <option value="0" selected>All</option>
                                @foreach (DB::table('hr_departments')->where('deleted', 0)->get() as $department)
                                    <option value="{{ $department->department }}">{{ $department->department }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="poStatus" class="font-weight-bold">Item</label>
                            <select id="select_item" class="form-control">
                                <option value="0" selected>All</option>
                                @foreach (DB::table('bk_expenses_items')->where('deleted', 0)->get() as $expenses)
                                    <option value="{{ $expenses->description }}">{{ $expenses->description }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="poStatus" class="font-weight-bold">Assign / Received</label>
                            <select id="select_assign" class="form-control">
                                <option value="00"  style = "background-color: #d9d9d9;" selected>All </option>
                                <option value="0">Assign</option>
                                <option value="1">Received</option>
                                {{-- @foreach (DB::table('teacher')->where('deleted', 0)->get() as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->firstname }} {{ $teacher->lastname }}</option>
                                @endforeach --}}
                            </select>
                        </div>
                    </div>
                </div>
            {{-- </div> --}}

            <div class="row bg-white py-3">
                <div class="col-lg-12 col-md-12">
                    <h5>Stock History</h5>
                    <div class="table-responsive w-100">
                        <table id="stock_history_table" class="table table-bordered table-sm w-100">
                            <thead class="table-secondary">
                                <tr>
                                    <th class="text-left">Item Code</th>
                                    <th class="text-left">Item Name</th>
                                    <th class="text-left">QTY</th>
                                    <th class="text-center">Transaction Date</th>
                                    <th class="text-center">Invoice No.</th>
                                    <th class="text-center">Remarks</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>


            <!-- New Item Modal -->
            <div class="modal fade" id="add_item" tabindex="-1" data-backdrop="static" role="dialog"
                aria-labelledby="newItemModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">

                    <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                        <div class="modal-header " style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                            <p class="modal-title">New Item</p>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                id="add_stock_modal_close">
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
                                        value="non-inventory" disabled>
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

            <div class="modal fade" id="edit_item" tabindex="-1" data-backdrop="static" role="dialog"
                aria-labelledby="newItemModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">

                    <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                        <div class="modal-header " style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                            <p class="modal-title">Edit Item</p>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                id="add_stock_modal_close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Item Name -->
                            <div class="form-group">
                                <input type="text" class="form-control " placeholder="Item Name" id="item_idd"
                                    hidden>
                                <label>Item Name</label>
                                <input type="text" class="form-control " placeholder="Item Name" id="itemName_edit">
                            </div>

                            <!-- Item Code & Quantity -->
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label>Item Code</label>
                                    <input type="text" class="form-control" placeholder="Code" id="itemCode_edit">
                                </div>
                                <div class="col-md-6">
                                    <label>Quantity</label>
                                    <input type="number" class="form-control" placeholder="QTY" id="quantity_edit">
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="form-group mt-3">
                                <label>Amount</label>
                                <input type="text" class="form-control" id="amount_edit">
                            </div>

                            <!-- Item Type -->
                            <div class="form-group row">
                                <label class="col-3">Item Type</label>
                                <div class="form-check col-6">
                                    <input class="form-check-input" type="radio" name="itemType_edit"
                                        id="nonInventory_edit" value="non-inventory">
                                    <label class="form-check-label" for="nonInventory">Non Inventory Item</label>
                                </div>
                                <div class="form-check col-3">
                                    <input class="form-check-input" type="radio" name="itemType_edit"
                                        id="inventory_edit" value="inventory" checked>
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
                            <button type="button" class="btn btn-primary stock_in_update">Update</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="assign_item_modal" tabindex="-1" data-backdrop="static" role="dialog"
                aria-labelledby="newItemModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">

                    <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                        <div class="modal-header " style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                            <p class="modal-title" id="assigned_item_label"></p>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                id="add_stock_modal_close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Item Name -->
                            <div class="form-group">
                                <label>Department</label>
                                <select class="form-control" id="department_select" name="department" required>
                                    <option value="">Select Department</option>
                                    @foreach (DB::table('hr_departments')->where('deleted', 0)->get() as $department)
                                        <option value="{{ $department->id }}">{{ $department->department }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Item Code & Quantity -->
                            <div class="form-group">
                                <label>Employee (Not Required)</label>
                                <select class="form-control" id="employeeName" name="employeeName" required>
                                    <option value="" selected>Select Employee</option>
                                    @foreach (DB::table('teacher')->where('deleted', 0)->get() as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->firstname }}
                                            {{ $employee->lastname }}</option>
                                    @endforeach

                                </select>
                            </div>

                            <!-- Amount -->
                            <div class="d-flex align-items-center">

                                <input type="text" class="form-control" id="itemid" hidden>

                                <div class="form-group flex-fill mr-3">
                                    <label>Onhand Stock</label>
                                    <input type="text" class="form-control" id="onhand_stock" readonly>
                                </div>
                                <div class="form-group flex-fill">
                                    <label>Assign</label>
                                    <input type="text" class="form-control" id="assign">
                                </div>
                            </div>


                            <!-- Debit Account -->
                            <div class="form-group">
                                <label>Remarks</label>
                                <br>
                                <textarea id="remarks" name="remarks" placeholder="Enter remarks here" style="width: 100%"></textarea>

                            </div>
                        </div>

                        <!-- Footer with Save Button -->
                        <div class="modal-footer d-flex justify-content-center">
                            <button type="button" class="btn btn-primary assign_item">Assign Item</button>
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
        $('#department_select').select2({
            placeholder: "Select Department",
            allowClear: true,
            theme: 'bootstrap4'
        });

        $('#employeeName').select2({
            placeholder: "Select Employee",
            allowClear: true,
            theme: 'bootstrap4'
        });

        $('#cashier_debit_account_edit').select2({
            placeholder: "Select Employee",
            allowClear: true,
            theme: 'bootstrap4'
        });



        $('.stock_in').on('click', function(event) {
            // event.preventDefault();
            stock_in()

        });

        $(document).on('click', '#assign_item', function() {
            var id = $(this).data('id');
            $('#assign_item_modal').data('id', id).modal('show');
        })

        $('#debit_account').select2({
            placeholder: "Select Debit Account",
            allowClear: true,
            theme: 'bootstrap4'
        });

        $('#add_suppliers').on('click', function() {
            $('#add_item').modal('show');
        })

        function stock_in() {
            var item_name = $('#item_name').val();
            var item_code = $('#item_code').val();
            var item_quantity = $('#item_quantity').val();
            var item_amount = $('#item_amount').val();
            var itemType = $('input[name="itemType"]:checked').val();
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

                        stockCardTable()

                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }
                }
            });
        }

        stockCardTable()

        function stockCardTable() {

            $("#accounting_inventory_table").DataTable({
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
                    url: '/bookkeeper/item/fetch',
                    type: 'GET',
                    dataSrc: function(json) {
                        return json;
                    }
                },
                columns: [{
                        "data": "itemcode"
                    },
                    {
                        "data": "description"
                    },
                    {
                        "data": "qty"
                    },
                    {
                        "data": null
                    },
                    {
                        "data": null
                    },
                    {
                        "data": "stock_onhand"
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
                            $(td).html(rowData.itemcode).addClass(
                                'align-middle');
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false,
                        'width': '290px',
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).html(`
                                ${rowData.description}
                                <button class="btn btn-xs ml-3" data-id="${rowData.id}" id="assign_item"
                                    style="border-radius: 5px; background-color:#00581f; color: white; padding: 2px 5px; font-style: italic;">
                                    <i class="fas fa-plus-circle" style="margin-right: 2px;"></i> Assign Item
                                </button>
                            `).addClass('align-middle');
                        }
                    },

                    {
                        'targets': 2,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            // $(td).html('');
                            $(td).html(rowData.qty).addClass('align-middle');

                        }
                    },

                    {
                        'targets': 3,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).html((rowData.total_stockin == null || rowData.total_stockin == '') ?
                                '0' : rowData.total_stockin).addClass('align-middle');
                        }
                    },

                    {
                        'targets': 4,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).html((rowData.total_assign_stock == null || rowData.total_assign_stock ==
                                '') ? '0' : rowData.total_assign_stock).addClass('align-middle');

                        }
                    },

                    {
                        'targets': 5,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            if ((rowData.total_assign_stock == rowData.qty) && (rowData
                                    .total_assign_stock != 0)) {
                                $(td).html(0).addClass('align-middle');
                            } else if ((rowData.total_assign_stock == 0) && (rowData.qty != 0)) {
                                $(td).html(rowData.qty).addClass('align-middle');
                            } else {
                                $(td).html(rowData.stock_onhand).addClass(
                                    'align-middle');
                            }
                        }
                    },
                    {
                        'targets': 6,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            var edit_button =
                                '<button type="button" class="btn btn-sm btn-primary edit_items_btn" id="edit_items_btn" data-id="' +
                                rowData.id +
                                '" ><i class="far fa-edit"></i></button>';
                            var delete_button =
                                '<button type="button" class="btn btn-sm btn-danger delete_items" id="delete_items" data-id="' +
                                rowData.id +
                                '"><i class="far fa-trash-alt"></i></button>';
                            $(td)[0].innerHTML = edit_button + ' ' + delete_button;
                            $(td).addClass('text-center align-middle');
                        }
                    }

                ],
                initComplete: function() {
                    var api = this.api();
                    $('#accounting_inventory_table_filter input')
                        // .off('.DT')
                        // .on('keyup.DT', function (e) {
                        //     if (e.keyCode == 13) {
                        //         api.search(this.value).draw();
                        //     }
                        // })
                        .css('width', '360px');
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

        // $('#assign_item').on('click', function() {
        $(document).on('click', '#assign_item', function() {
            var item_id = $(this).attr('data-id');
            $.ajax({
                type: 'GET',
                url: '/bookkeeper/item/fetch/edit',
                data: {
                    item_id: item_id
                },
                success: function(data) {
                    $('#assigned_item_label').text(data.selected_item[0].description);
                    $('#itemid').val(data.selected_item[0].id);

                    $('#assign')
                        .attr('max', function() {
                            if (data.selected_item[0].total_assign_stock == data.selected_item[0]
                                .qty && data.selected_item[0].total_assign_stock != 0) {
                                return 0;
                            } else if (data.selected_item[0].total_assign_stock == 0 && data
                                .selected_item[0].qty != 0) {
                                return data.selected_item[0].qty;
                            } else {
                                return data.selected_item[0].stock_onhand;
                            }
                        })
                        .val('')
                        .on('input', function() {
                            var max = parseInt($(this).attr('max'));
                            var value = parseInt($(this).val()) || 0;
                            if (value > max || value == max) {
                                $(this).val(max);
                            }
                        });



                    $('#onhand_stock').val(function() {
                        if (data.selected_item[0].total_assign_stock == data.selected_item[0]
                            .qty && data.selected_item[0].total_assign_stock != 0) {
                            return 0;
                        } else if (data.selected_item[0].total_assign_stock == 0 && data
                            .selected_item[0].qty != 0) {
                            return data.selected_item[0].qty;
                        } else {
                            return data.selected_item[0].stock_onhand;
                        }
                    }());
                }
            });
        })

        $('.assign_item').on('click', function(event) {
            // event.preventDefault();
            assign_item()

        });

        function assign_item() {
            var itemid = $('#itemid').val().trim();
            var department_select = $('#department_select').val().trim();
            var employee_select = $('#employeeName').val().trim();
            var onhand_stock = $('#onhand_stock').val().trim();
            var assign = $('#assign').val().trim();
            var remarks = $('#remarks').val();

            var hasError = false;

            if (!itemid || !department_select || !employee_select || !onhand_stock || !assign || !remarks) {
                hasError = true;
            }

            if (hasError) {
                Toast.fire({
                    type: 'warning',
                    title: 'Please fill all fields correctly!'
                });
                return false;
            }

            $.ajax({
                type: 'GET',
                url: '/bookkeeper/item/assign_item',
                data: {
                    itemid: itemid,
                    department_select: department_select,
                    employee_select: employee_select,
                    onhand_stock: onhand_stock,
                    assign: assign,
                    remarks: remarks,

                },
                success: function(data) {
                    if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: 'Successfully assigned item!'
                        })
                        $("#remarks").val("");
                        $("#assign").val("");
                        $("#onhand_stock").val("");
                        $("#department_select").val(null).trigger('change');
                        $("#employeeName").val(null).trigger('change');

                        $("#assign_item_modal").modal('hide');
                        stockCardTable()
                        stockCardTableHistory()

                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }
                }
            });
        }


      

       

        stockCardTableHistory()

        function stockCardTableHistory() {
            $("#stock_history_table").DataTable({
                destroy: true,
                autoWidth: false,
                paging: false,
                info: false,
                ajax: {
                    url: '/bookkeeper/stockhistory/fetch',
                    type: 'GET',
                    dataSrc: function(json) {
                        return json;
                    }
                },
                columns: [{
                        "data": "itemcode"
                    },
                    {
                        "data": "description"
                    },
                    {
                        "data": "receivedqty"
                    },
                    {
                        "data": "posteddatetime"
                    },
                    {
                        "data": "invoiceno"
                    },
                    {
                        "data": "remarks"
                    },
                ],
                columnDefs: 
                    [{
                        'targets': 0,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).html(rowData.itemcode)
                                .addClass('align-middle')
                                .attr('data-department', rowData.department)
                                .attr('data-teacher', rowData.item_assign_employee)
                                .attr('data-isReceived', rowData.isReceived);
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).html(rowData.description).addClass('align-middle');
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).html(rowData.receivedqty).addClass('align-middle');
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).html(moment(rowData.posteddatetime).format('MM/DD/YYYY')).addClass(
                                'text-center align-middle');
                        }
                    },
                    {
                        'targets': 4,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).html(rowData.invoiceno).addClass('text-center align-middle');
                        }
                    },
                    {
                        'targets': 5,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).html(rowData.remarks).addClass('text-center align-middle');
                        }
                    }
                ],
                initComplete: function() {
                        var api = this.api();
                        
                        // Create a container div for the button and search
                        var container = $('<div style="display:flex; justify-content:flex-end; width:100%;"></div>');
                        
                        // Create button container (left side)
                        var buttonContainer = $('<div style="margin-right:auto;"></div>');
                        
                        // Create your custom button
                        var customButton = $('<button class="btn btn-primary btn-sm" id="print_stock_history"><i class="fas fa-print"></i>Print</button>')
                            .on('click', function() {
                                const dateRange = $('#dateRange').val();
                                const selectedDepartment = $('#select_departmentName').val();
                                const selectedItem = $('#select_item').val();
                                const selectedAssign = $('#select_assign').val() || '';

                                window.open('/bookkeeper/stock_history_print?date_range=' + dateRange +
                                    '&selectedDepartment=' + selectedDepartment +
                                    '&selectedItem=' + selectedItem +
                                    '&selectedAssign=' + selectedAssign, '_blank');
                                console.log('Custom button clicked');
                            });
                        
                        // Get the current search input
                        var searchInput = $('#stock_history_table_filter input').detach();
                        searchInput.css('width', '360px');
                        
                        // Append elements to containers
                        buttonContainer.append(customButton);
                        container.append(buttonContainer);
                        container.append(searchInput);
                        
                        // Append the container to the filter element
                        $('#stock_history_table_filter').html('').append(container);
                        
                        // Focus on the search input after initialization
                        searchInput.focus();
                    },
                    dom: '<"top"f>rt<"bottom"lip><"clear">' // This helps with the layout structure
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
                $('#stock_history_table').DataTable().draw(); // trigger redraw
            });

            $('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                minDateFilter = '';
                maxDateFilter = '';
                $('#stock_history_table').DataTable().draw(); // trigger redraw
            });


        var selectedDepartment = '0';


        $('#select_departmentName').on('change', function() {
            selectedDepartment = $(this).val();
            console.log('selectedDepartment:', selectedDepartment);
            $('#stock_history_table').DataTable().draw(); // trigger redraw
        });

        var selectedItem = '0';


        $('#select_item').on('change', function() {
            selectedItem = $(this).val();
            console.log('selectedItem:', selectedItem);
            $('#stock_history_table').DataTable().draw(); // trigger redraw
        });

        var selectedAssign = '00';

        $('#select_assign').on('change', function() {
            selectedAssign = $(this).val();
            console.log('selectedAssign:', selectedAssign);
            $('#stock_history_table').DataTable().draw(); // trigger redraw
        });

      
           // Custom date filter
           $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                if (settings.nTable.id !== 'stock_history_table')
                    return true; // filter only the selected table

                // const dateStr = data[4]; // date column (already formatted to 'YYYY-MM-DD' by createdCell)
                // const date = moment(dateStr, 'YYYY-MM-DD');

                const itemName = data[1];

                // const department_name = $(data[0]).attr('data-department');
                 // Get the department from the first column's data-department attribute

                var dateStr = data[3]; // date column (already formatted to 'YYYY-MM-DD' by createdCell)
                var date = moment(dateStr, 'YYYY-MM-DD');


                var rowData = $('#stock_history_table').DataTable().row(dataIndex).data();
                var department_name = rowData ? rowData.department : '';
             
                // Date filter logic
                // if (minDateFilter && maxDateFilter) {
                //     if (!date.isSameOrAfter(minDateFilter) || !date.isSameOrBefore(maxDateFilter)) {
                //         return false; // Exclude if the date is out of the range
                //     }
                // }

                var assign_orreceived = rowData ? rowData.isReceived : '';

                if (selectedItem !== '0' && itemName !== selectedItem) {
                    return false; // Exclude if the supplier does not match the selected one
                }

                // Date filter logic
                if (minDateFilter && maxDateFilter) {
                    if (!date.isSameOrAfter(minDateFilter) || !date.isSameOrBefore(maxDateFilter)) {
                        return false; // Exclude if the date is out of the range
                    }
                }


                if (selectedDepartment !== '0' && department_name !== selectedDepartment) {
                    return false;
                }

                // Assign filter - convert both to string for comparison
                if (selectedAssign !== '00' && String(assign_orreceived) !== String(selectedAssign)) {
                    return false;
                }
                return true; // Include the row if it matches both filters

        });


        $(document).on('click', '#edit_items_btn', function() {
            var itemId = $(this).data('id');

            $.ajax({
                url: '/bookkeeper/item/fetch/edit_stock',
                type: 'GET',
                data: {
                    id: itemId
                },
                success: function(item) {
                    $('#item_idd').val(item.id);
                    $('#itemName_edit').val(item.description);
                    $('#itemCode_edit').val(item.itemcode);
                    $('#quantity_edit').val(item.qty);
                    $('#amount_edit').val(item.amount);

                    if (item.itemtype === 'inventory') {
                        $('#inventory_edit').prop('checked', true);
                    } else {
                        $('#nonInventory_edit').prop('checked', true);
                    }

                    var accountOption = $('<option>', {
                        value: item.coaid,
                        text: item.account_code + ' - ' + item.account_name
                    });

                    if ($('#cashier_debit_account_edit option[value="' + accountOption.val() +
                            '"]').length === 0) {
                        $('#cashier_debit_account_edit').append(accountOption);
                    }

                    setTimeout(() => {
                        $('#cashier_debit_account_edit').val(accountOption.val())
                            .trigger('change');
                    }, 500);

                    // $('#addItemModalLabel').text('Edit Expense Item');

                    // $('#updateExpenseItem').data('id', item.id);

                    // $('#addExpenseItem').hide();
                    // $('#updateExpenseItem').show();

                    $('#edit_item').modal('show');
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire('Error', 'Unable to fetch item details.', 'error');
                }
            });
        });

        $(document).on('click', '.stock_in_update', function() {
            update_item_stock()
        })

        function update_item_stock() {
            var item_idd = $('#item_idd').val();
            var itemName_edit = $('#itemName_edit').val();
            var itemCode_edit = $('#itemCode_edit').val();
            var quantity_edit = $('#quantity_edit').val();
            var amount_edit = $('#amount_edit').val();
            var itemType = $('input[name="itemType_edit"]:checked').val();


            $.ajax({
                type: 'POST',
                url: '/bookkeeper/item/fetch/update_stock',
                data: {
                    _token: '{{ csrf_token() }}',
                    item_idd: item_idd,
                    itemName_edit: itemName_edit,
                    itemCode_edit: itemCode_edit,
                    quantity_edit: quantity_edit,
                    amount_edit: amount_edit,
                    itemType: itemType

                },
                success: function(data) {
                    if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: 'Successfully updated'
                        })

                        stockCardTable();
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }
                }
            });
        }

        $(document).on('click', '.delete_items', function() {
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
                        url: '/bookkeeper/delete-expense-item_stock',
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: itemId
                        },
                        success: function(response) {
                            Swal.fire('Deleted!', response.message, 'success');
                            stockCardTable();
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

        $('#add_stock_modal_close').on('click', function() {
            var hasData =
                $("#item_name").val().trim() !== "" ||
                $("#item_code").val().trim() !== "" ||
                $("#item_quantity").val().trim() !== "" ||

                $("#item_amount").val().trim() !== "";

            if ($('#debit_account').val()) {
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

                        $('#item_name').val("");
                        $('#item_code').val("");
                        $('#item_quantity').val("");
                        $('#item_amount').val("");

                        $('#debit_account').val("").trigger('change');

                    } else {

                        $('#add_item').modal('show');

                    }
                });
            } else {

                $('#add_item').modal('hide');


            }

            // $('#print_stock_history').on('click', function() {
              
            // });




            
        

     

     
        

        });
    </script>
@endsection
