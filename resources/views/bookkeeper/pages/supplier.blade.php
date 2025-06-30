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
            font-weight: 600 !important;
            border: 1px solid #7d7d7d !important;
        }

        td {
            border: 1px solid #7d7d7d !important;
        }

        /* #modal-footer {
            display: flex;
            justify-content: center;
            align-items: center;
        } */
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
    </style>

</head>
@section('content')

    <body>
        <div class="container-fluid mt-3">

            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="fas fa-file-alt ml-2 mt-2 mb-2" style="font-size: 33px;"></i> <i
                        class="fas fa-pencil-alt mt-2 mr-3 mb-2" style="font-size: 18px;"></i>
                    <h1 class="text-black m-0">Supplier Setup</h1>

                </div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Supplier Setup</li>
                    </ol>
                </nav>

            </div>
            <div class="mb-3" style="color: black;  font-size: 13px;">
                <ul class="nav nav-tabs" style="border-bottom: 4px solid #d9d9d9;;">
                    <li class="nav-item">
                        <a class="nav-link active" href="/bookkeeper/supplier"
                            class="nav-link {{ Request::url() == url('/bookkeeper/supplier') ? 'active' : '' }}"
                            style="color: black; font-weight: 600; background-color: #d9d9d9; border-top-left-radius: 10px; border-top-right-radius: 10px;">Supplier</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/bookkeeper/purchase_order"
                            class="nav-link {{ Request::url() == url('/bookkeeper/purchase_order') ? 'active' : '' }}"
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
                        <a class="nav-link" href="/bookkeeper/stock_card"
                            class="nav-link {{ Request::url() == url('bookkeeper/stock_card') ? 'active' : '' }}"
                            style="color: black;">Stock Card</a>
                    </li>
                </ul>
            </div>
            <hr style="border-top: 2px solid #d9d9d9;">

            <div class="row py-3">
                <div class="col-md-12">
                    <button class="btn btn-success btn-sm" id="add_suppliers" style="background-color: #015918;">
                        + Add Supplier
                    </button>
                </div>
            </div>
            <div id="edit_supplier" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                        <div class="modal-header " style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                            <h5 class="modal-title">Edit New Supplier</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <!-- Supplier Name -->
                                <div class="form-group">
                                    <input type="text" class="form-control" id="supplier_id" hidden>
                                    <label>Supplier Name</label>
                                    <input type="text" class="form-control" id="supplier_name_edit"
                                        placeholder="Supplier Name">
                                </div>

                                <!-- Contact Number & Email Address -->
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Contact Number</label>
                                        <input type="text" class="form-control" id="contact_number_edit"
                                            placeholder="09xx-xxx-xxxx">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Email Address</label>
                                        <input type="email" class="form-control" id="email_address_edit"
                                            placeholder="abc@gmail.com">
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="form-group">
                                    <input type="text" class="form-control" id="supplier_address_edit"
                                        placeholder="Enter Supplier Address">
                                </div>

                                <!-- Account & Credit Account -->
                                <div class="form-row p-3 shadow-lg rounded bg-white">
                                    <div class="col-md-3">
                                        <label class="font-weight-bold">Account</label>
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
                            <button type="submit" class="btn-success btn-save update_supplier"
                                style="font-size: 19.8px;">Update</button>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row bg-white py-3">
                <div class="col-lg-12 col-md-12">
                    <div class="table-responsive w-100">
                        <table id="accounting_supplier_table" class="table table-bordered table-sm w-100">
                            <thead class="table-secondary">
                                <tr>
                                    <th class="text-left">Supplier Name</th>
                                    <th class="text-left">Address</th>
                                    <th class="text-left">Phone Number</th>
                                    <th class="text-left">Email Add</th>
                                    <th class="text-left">Account</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>



            <!-- Modal -->
            <div id="add_supplier" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                        <div class="modal-header " style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                            <h5 class="modal-title">Add New Supplier</h5>
                            <button type="button" class="close" data-dismiss="modal" id="add_supplier_modal_close"
                                aria-label="Close">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <!-- Supplier Name -->
                                <div class="form-group">
                                    <label>Supplier Name</label>
                                    <input type="text" class="form-control" id="supplier_name"
                                        placeholder="Supplier Name" required>
                                </div>

                                <!-- Contact Number & Email Address -->
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Contact Number</label>
                                        <input type="text" class="form-control" id="contact_number"
                                            placeholder="09xx-xxx-xxxx" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Email Address</label>
                                        <input type="email" class="form-control" id="email_address"
                                            placeholder="abc@gmail.com" required>
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" class="form-control" id="supplier_address"
                                        placeholder="Enter Supplier Address" required>
                                </div>

                                <!-- Account & Credit Account -->
                                <div class="form-row p-3 shadow-lg rounded bg-white">
                                    <div class="col-md-3">
                                        <label class="font-weight-bold">Account</label>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="font-weight-bold">Credit Account</label>
                                        <select class="form-control" id="credit_account">
                                            <option value="" selected disabled>Assign Credit Account</option>
                                            @foreach (DB::table('chart_of_accounts')->get() as $coa)
                                                <option value="{{ $coa->id }}">{{ $coa->code }} -
                                                    {{ $coa->account_name }}</option>
                                                @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->get() as $subcoa)
                                                    <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        {{ $subcoa->sub_code }} - {{ $subcoa->sub_account_name }}</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                        {{-- <select class="form-control" id="credit_account">
                                            <option value="" selected disabled>Assign Credit Account</option>
                                            @foreach (DB::table('chart_of_accounts')->get() as $coa)
                                                <option value="{{ $coa->id }}">{{ $coa->account_name }}</option>
                                                @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->get() as $subcoa)
                                                    <option value="{{ $subcoa->id }}">-- {{ $subcoa->sub_account_name }}</option>
                                                @endforeach
                                            @endforeach
                                        </select> --}}
                                    </div>
                                </div>

                            </form>
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer" id="modal-footer"
                            style="display: flex;justify-content: center;align-items: center;">
                            <button type="submit" class="btn-success btn-save create_supplier"
                                style="font-size: 19.8px;">Save</button>
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
        $('#add_suppliers').on('click', function() {
            $('#add_supplier').modal('show');
        })

        $('#credit_account').select2({
            placeholder: "Select Credit Account",
            allowClear: true,
            theme: 'bootstrap4'
        });

        $(document).on('click', '#edit_suppliers', function() {
            var id = $(this).data('id');
            $('#edit_supplier').data('id', id).modal('show');
        })

        $('#credit_account_edit').select2({
            placeholder: "Select Credit Account",
            allowClear: true,
            theme: 'bootstrap4'
        });


        $('.create_supplier').on('click', function(event) {
            // event.preventDefault();
            create_supplier()

        });

        function create_supplier() {
            var supplier_name = $('#supplier_name').val().trim();
            var contact_number = $('#contact_number').val().trim();
            var email_address = $('#email_address').val().trim();
            var supplier_address = $('#supplier_address').val().trim();
            var credit_account = $('#credit_account').val();

            var hasError = false;

            if (!supplier_name || !contact_number || !email_address || !supplier_address || !credit_account) {
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
                url: '/bookkeeper/supplier/create',
                data: {
                    supplier_name: supplier_name,
                    contact_number: contact_number,
                    email_address: email_address,
                    supplier_address: supplier_address,
                    credit_account: credit_account,

                },
                success: function(data) {
                    if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: 'Successfully created'
                        })
                        $("#supplier_name").val("");
                        $("#contact_number").val("");
                        $("#email_address").val("");
                        $("#supplier_address").val("");
                        $("#credit_account").val(null).trigger('change');
                        supplierTable()

                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }
                }
            });
        }

        supplierTable()

        function supplierTable() {

            $("#accounting_supplier_table").DataTable({
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
                    url: '/bookkeeper/supplier/fetch',
                    type: 'GET',
                    dataSrc: function(json) {
                        return json;
                    }
                },
                columns: [{
                        "data": "suppliername"
                    },
                    {
                        "data": "address"
                    },
                    {
                        "data": "contactno"
                    },
                    {
                        "data": "email"
                    },
                    {
                        "data": "account_name"
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
                            $(td).html(rowData.suppliername).addClass(
                                'align-middle');
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).html(rowData.address).addClass(
                                'align-middle');
                        }
                    },

                    {
                        'targets': 2,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            // $(td).html('');
                            $(td).html(rowData.contactno).addClass('align-middle');

                        }
                    },

                    {
                        'targets': 3,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).html(rowData.email).addClass(
                                'align-middle');
                        }
                    },

                    {
                        'targets': 4,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).html(rowData.account_name).addClass(
                                'align-middle');
                        }
                    },

                    {
                        'targets': 5,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            var edit_button =
                                '<button type="button" class="btn btn-sm btn-primary edit_supplier" id="edit_suppliers" data-id="' +
                                rowData.id +
                                '" ><i class="far fa-edit"></i></button>';
                            var delete_button =
                                '<button type="button" class="btn btn-sm btn-danger delete_supplier" id="delete_supplier" data-id="' +
                                rowData.id +
                                '"><i class="far fa-trash-alt"></i></button>';
                            $(td)[0].innerHTML = edit_button + ' ' + delete_button;
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


        // $(document).on('click', '.edit_supplier', function() {


        //     var supplier_id = $(this).attr('data-id')

        //     $.ajax({
        //         type: 'GET',
        //         url: '/bookkeeper/supplier/edit',
        //         data: {
        //             supplier_id: supplier_id
        //         },
        //         success: function(response) {

        //             var supplier_selected = response.supplier;
        //             var coa_all = response.chart_of_accounts;

        //             $("#supplier_id").val(supplier_selected[0].id);
        //             $("#supplier_name_edit").val(supplier_selected[0].suppliername);
        //             $("#contact_number_edit").val(supplier_selected[0].contactno);
        //             $("#email_address_edit").val(supplier_selected[0].email);
        //             $("#supplier_address_edit").val(supplier_selected[0].address);


        //             $("#credit_account_edit").empty().trigger('change');
        //             $("#credit_account_edit").append(
        //                 '<option value="" selected disabled>Select Credit Account</option>');
        //             coa_all.forEach(coa_all => {
        //                 if (coa_all.id == supplier_selected[0]
        //                     .coaid) {
        //                     $("#credit_account_edit").append(
        //                         `<option value="${coa_all.id}" selected>${coa_all.account_name}</option>`
        //                     );
        //                 } else {
        //                     $("#credit_account_edit").append(
        //                         `<option value="${coa_all.id}">${coa_all.account_name}</option>`
        //                     );
        //                 }
        //             });




        //         }
        //     });

        // });

        $(document).on('click', '.edit_supplier', function() {


            var supplier_id = $(this).attr('data-id')

            $.ajax({
                type: 'GET',
                url: '/bookkeeper/supplier/edit',
                data: {
                    supplier_id: supplier_id
                },
                success: function(response) {

                    var supplier_selected = response.supplier;
                    var coa_all = response.chart_of_accounts;
                    var bk_sub_chart_of_accounts = response.bk_sub_chart_of_accounts;

                    $("#supplier_id").val(supplier_selected[0].id);
                    $("#supplier_name_edit").val(supplier_selected[0].suppliername);
                    $("#contact_number_edit").val(supplier_selected[0].contactno);
                    $("#email_address_edit").val(supplier_selected[0].email);
                    $("#supplier_address_edit").val(supplier_selected[0].address);


                    $("#credit_account_edit").empty().trigger('change');
                    $("#credit_account_edit").append(
                        '<option value="" selected disabled>Select Credit Account</option>');

                    coa_all.forEach(coa_all => {
                        if (coa_all.id == supplier_selected[0]
                            .coaid) {
                            $("#credit_account_edit").append(
                                `<option value="${coa_all.id}" selected>${coa_all.code} - ${coa_all.account_name}</option>`
                            );
                        } else {
                            $("#credit_account_edit").append(
                                `<option value="${coa_all.id}">${coa_all.code} - ${coa_all.account_name}</option>`
                            );
                        }
                        bk_sub_chart_of_accounts.forEach(bk_sub_chart_of_accounts => {
                            if (bk_sub_chart_of_accounts.coaid == coa_all.id) {
                                if (bk_sub_chart_of_accounts.id == supplier_selected[0]
                                    .coaid) {
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




                }
            });

        });



        $(document).on('click', '.update_supplier', function() {
            update_supplier()
        })

        function update_supplier() {
            var supplier_id = $('#supplier_id').val();
            var suppliername = $('#supplier_name_edit').val();
            var suppllier_contactno = $('#contact_number_edit').val();
            var supplier_email = $('#email_address_edit').val();
            var suppllier_address = $('#supplier_address_edit').val();
            var credit_account = $('#credit_account_edit').val();


            $.ajax({
                type: 'POST',
                url: '/bookkeeper/supplier/update',
                data: {
                    _token: '{{ csrf_token() }}',
                    supplier_id: supplier_id,
                    suppliername: suppliername,
                    suppllier_contactno: suppllier_contactno,
                    supplier_email: supplier_email,
                    suppllier_address: suppllier_address,
                    credit_account: credit_account

                },
                success: function(data) {
                    if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: 'Successfully updated'
                        })

                        supplierTable();
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }
                }
            });
        }

        $(document).on('click', '.delete_supplier', function() {
            var deletesupplierId = $(this).attr('data-id')
            Swal.fire({
                text: 'Are you sure you want to remove supplier?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
            }).then((result) => {
                if (result.value) {
                    delete_supplier(deletesupplierId)
                }
            })
        });

        function delete_supplier(deletesupplierId) {
            $.ajax({
                type: 'GET',
                url: '/bookkeeper/supplier/delete',
                data: {
                    deletesupplierId: deletesupplierId

                },
                success: function(data) {
                    if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: 'Successfully deleted'
                        })

                        supplierTable();
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }
                }
            });
        }

        $('#add_supplier_modal_close').on('click', function() {
            var hasData =
                $("#supplier_name").val().trim() !== "" ||
                $("#contact_number").val().trim() !== "" ||
                $("#email_address").val().trim() !== "" ||

                $("#supplier_address").val().trim() !== "";


            if ($('#credit_account').val()) {
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

                        $('#supplier_name').val("");
                        $('#contact_number').val("");
                        $('#email_address').val("");
                        $('#supplier_address').val("");


                        $('#credit_account').val("").trigger('change');





                    } else {

                        $('#add_supplier').modal('show');

                    }
                });
            } else {

                $('#add_supplier').modal('hide');


            }
        });
    </script>
@endsection
