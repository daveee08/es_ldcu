@extends('bookkeeper.layouts.app')

@section('pagespecificscripts')

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Enrollment Set up</title>

        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

        <!-- jQuery first -->
        <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

        <!-- Bootstrap 4 -->
        {{-- <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}"> --}}
        <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

        <!-- Select2 -->
        <script src="{{ asset('plugins/select2/js/select2.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.css') }}">

        <!-- DataTables -->
        {{-- <link rel="stylesheet" href="{{ asset('plugins/datatables/css/dataTables.bootstrap5.min.css') }}">
        <script src="{{ asset('plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables/js/dataTables.bootstrap5.min.js') }}"></script> --}}
        <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.css') }}">
        <script src="{{ asset('plugins/datatables-fixedheader/js/fixedHeader.bootstrap4.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
        <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    </head>
@endsection

@section('content')
    @php
        $acadprog = DB::table('academicprogram')->get();
        $items = DB::table('itemclassification')->where('deleted', '0')->get();
        $coa = DB::table('chart_of_accounts')->where('deleted', 0)->get();
    @endphp
    <style>
        /* #classification-rows-container {
                                                                                                                                        min-height: 100px;
                                                                                                                                    }

                                                                                                                                    .classification-row {
                                                                                                                                        border: none;
                                                                                                                                        box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);
                                                                                                                                    }

                                                                                                                                    .classification-row select {
                                                                                                                                        height: 30px;
                                                                                                                                        font-size: 12px;
                                                                                                                                        border-radius: 8px;
                                                                                                                                        border: none;
                                                                                                                                        box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);
                                                                                                                                    }

                                                                                                                                    .classification-row label {
                                                                                                                                        font-size: 13px;
                                                                                                                                        color: #333;
                                                                                                                                        font-weight: 600;
                                                                                                                                    } */
        .modal-body {
            overflow-y: auto !important;
            max-height: none !important;
        }

        #classification-rows-container {
            display: block !important;
            /* Force visibility */
            opacity: 1 !important;
            /* Ensure not transparent */
            height: auto !important;
            /* Remove fixed height */
        }

        .modal-content {
            z-index: 1050 !important;
            /* Bootstrap modals use 1050 by default */
        }
    </style>

    <body>
        <div class="container-fluid">
            <div class="container-fluid">
                <!-- Page Header -->
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-graduation-cap fa-lg mr-2" style="font-size: 33px;"></i>
                    <h1>Enrollment Setup</h1>
                </div>
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item" style="font-size: 0.8rem;"><a href="#"
                                style="color:rgba(0,0,0,0.5);">Home</a></li>
                        <li class="breadcrumb-item active" style="font-size: 0.8rem; color:rgba(0,0,0,0.5);">Enrollment
                            Setup</li>
                    </ol>
                </nav>

                <div class="mb-3" style="color: black;  font-size: 13px;">
                    <ul class="nav nav-tabs" style="border-bottom: 4px solid #d9d9d9; font-weight: 600;">
                        <li class="nav-item">
                            <a href="/bookkeeper/chart_of_accounts" class="nav-link"
                                {{ Request::url() == url('/bookkeeper/chart_of_accounts') ? 'active' : '' }}
                                style="color: black;">Chart Of Account</a>
                        </li>
                        <li class="nav-item">
                            <a href="/bookkeeper/fiscal_year" class="nav-link"
                                {{ Request::url() == url('/bookkeeper/fiscal_year') ? 'active' : '' }}
                                style="color: black;">Fiscal Year</a>
                        </li>
                        <li class="nav-item">
                            <a href="/bookkeeper/enrollment_setup" class="nav-link active"
                                {{ Request::url() == url('/bookkeeper/enrollment_setup') ? 'active' : '' }}
                                style="color: black;  font-weight: 600; background-color: #d9d9d9; border-top-left-radius: 10px; border-top-right-radius: 10px;">Enrollment
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
            </div>
            <div class="card mt-4" style="border-color: white;">
                <div class="card-header d-flex justify-content-between align-items-center" style="font-size: 12.5px;">

                    <!-- Left Section: Buttons -->
                    <div>
                        <button type="button" class="btn btn-success rounded" id="addaccountsetup"
                            style="background-color: #00581f; height: 30px; font-size: 12.5px; font-family: Arial, sans-serif; ">
                            <i class="fas fa-plus-circle mr-1"></i> Add Accounts Setup
                        </button>
                    </div>

                    <!-- Right Section: Select & Search -->
                    <div class="d-flex align-items-center ml-auto">
                        <!-- Search Input -->
                        <div class="input-group">
                            <input type="text" class="form-control classified-search" placeholder="Search"
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
                    <table class="table table-bordered table-sm" style="border-right: 1px solid #7d7d7d; align-items: left">
                        <thead style="font-size: 13px; background-color: #b9b9b9;">
                            <tr>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 13% ">Academic Program</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 13%">Academic Level</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 13%">Classification</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 20%">Debit Account</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d;; text-align: left; width:20%">Credit
                                    Account</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d;; text-align: left; width:20%">
                                    Cashier JR</th>
                                <th style="font-weight: 600; border: 1px solid #7d7d7d;; text-align: left; width:20%">Action
                                </th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 12.5px; font-style:" id = "classifieditems">


                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add accounts set up Modal -->
        <div class="modal fade" id="addaccountsetupModal" data-backdrop="static" data-keyboard="false" tabindex="-1">

            <div class="modal-dialog modal-xl p-3" style="width: 70%; " role="document">
                <div class="modal-content" style="border-radius: 15px">
                    <div class="modal-header" style="padding: 10px; background-color: #d9d9d9;">
                        <h5 class="modal-title ml-2" id="addaccountsetupModalLabel" style="font-size: 14px;">Add Account
                            Form</h5>
                        <button type="button" class="close" id="closeModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group d-flex justify-content-between align-items-center">
                            <label style="font-weight: normal; font-size: 13px;">Academic Program</label>
                        </div>
                        <div class="form-group d-flex justify-content-between align-items-center">
                            <div class="form-group flex-grow-1 d-flex align-items-center">
                                <select id="academic_program" class="form-control"
                                    style="width:45%; height: 40px; font-size: 12px; border-radius: 10px;">
                                    <option value="">Select Academic Program</option>
                                    @foreach ($acadprog as $item)
                                        <option value="{{ $item->id }}">{{ $item->progname }}</option>
                                    @endforeach
                                </select>
                                <label class="form-check-label ml-5" for="isActive"
                                    style="font-weight: normal; font-size: 13px;">
                                    <input class="form-check-input" type="checkbox" id="isActive"
                                        style="margin-right: 5px;" checked> Apply To All Academic Level
                                </label>
                                <label class="form-check-label ml-5" for="isActive"
                                    style="font-weight: normal; font-size: 13px;">
                                    <input class="form-check-input" type="checkbox" id="perclassification"
                                        style="margin-right: 5px;"> Per Classification
                                </label>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-success rounded" id="add-classification-btn"
                                style="background-color: #00581f; height: 30px; font-size: 12.5px; font-family: Arial, sans-serif;display: none; ">
                                <i class="fas fa-plus-circle mr-1"></i> Add Account
                            </button>
                            <button type="button" class="btn btn-success rounded" id="add-account-btn"
                                style="background-color: #00581f; height: 30px; font-size: 12.5px; font-family: Arial, sans-serif; ">
                                <i class="fas fa-plus-circle mr-1"></i> Add Account
                            </button>
                        </div>

                        <div class="form-row mb-3 mt-3" id="classification-rows-container">
                        </div>

                        <div class="card mt-3"
                            style="border:none; width: 60%; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                            <div class="card-body d-flex justify-content-between">
                                <div>
                                    <label style="font-size: 13px; color: #333; font-weight: 600;">Cashier Journal
                                        Entry</label>
                                </div>
                                <div class="ml-1">
                                    <label style="font-size: 13px; color: #333; font-weight: 600;">Debit Account</label>
                                    <select id="cashier_debit_account"
                                        style="width: 100%; height: 30px; font-size: 12px; padding: 8px; border-radius: 8px; border:none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                                        <option disabled style="display:none;">Select Account</option>
                                        @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                            <option value="{{ $coa->id }}">{{ $coa->code }} -
                                                {{ $coa->account_name }}</option>
                                            @foreach (DB::table('bk_sub_chart_of_accounts')->where('deleted', 0)->where('coaid', $coa->id)->get() as $subcoa)
                                                <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    {{ $subcoa->sub_code }} - {{ $subcoa->sub_account_name }}</option>
                                            @endforeach
                                        @endforeach
                                        {{-- @foreach ($coa as $item)
                                            <option value="{{ $item->id }}">{{ $item->code }} -
                                                {{ $item->account_name }}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-success"
                                style="background-color: #00581f; height: 30px; border: none; font-size: 12.5px;"
                                id="saveclassified">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="modal fade" id="editaccountsetupModal" data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-xl p-3" style="width: 70%;" role="document">
                <div class="modal-content" style="border-radius: 15px">
                    <div class="modal-header" style="padding: 10px; background-color: #d9d9d9;">
                        <h5 class="modal-title ml-2" id="addaccountsetupModalLabel" style="font-size: 14px;">Edit Account
                            Form</h5>
                        <button type="button" class="close" id="edit_closeModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label style="font-weight: normal; font-size: 13px;">Academic Program</label>
                            <div class="d-flex align-items-center mt-1">
                                <select id="academic_program" class="form-control"
                                    style="width:45%; height: 30px; font-size: 12px; border-radius: 10px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                                    <option value="">Select Academic Program</option>
                                    @foreach ($acadprog as $item)
                                        <option value="{{ $item->id }}">{{ $item->progname }}</option>
                                    @endforeach
                                </select>
                                <label class="form-check-label ml-5" for="isActive"
                                    style="font-weight: normal; font-size: 13px;">
                                    <input class="form-check-input" type="checkbox" id="isActive"
                                        style="margin-right: 5px;" checked> Apply To All Academic Level
                                </label>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-success rounded" id="edit-add-classification-btn"
                                style="background-color: #00581f; height: 30px; font-size: 12.5px; font-family: Arial, sans-serif;">
                                <i class="fas fa-plus-circle mr-1"></i> Add Classification
                            </button>
                        </div>

                        <div class="mt-3" id="classification-rows-container">
                            <!-- Dynamic classification rows will be inserted here -->
                        </div>

                        <div class="card mt-3" style="border:none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label style="font-size: 13px; color: #333; font-weight: 600;">Cashier Journal
                                            Entry</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label style="font-size: 13px; color: #333; font-weight: 600;">Debit
                                            Account</label>
                                        <select id="edit_cashier_debit_account" class="form-control"
                                            style="height: 30px; font-size: 12px; border-radius: 8px; border:none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                                            <option value="" disabled selected>Select Account</option>
                                            @foreach ($coa as $item)
                                                <option value="{{ $item->id }}">{{ $item->code }} -
                                                    {{ $item->account_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer justify-content-center mt-3">
                            <button type="button" class="btn btn-secondary mr-2" id="edit_closeModalBtn"
                                style="height: 30px; border: none; font-size: 12.5px;">Cancel</button>
                            <button type="button" class="btn btn-success" id="saveclassified"
                                style="background-color: #00581f; height: 30px; border: none; font-size: 12.5px;">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="modal fade" id="editaccountsetupModal" data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-xl p-3" style="width: 70%;" role="document">
                <div class="modal-content" style="border-radius: 15px">
                    <div class="modal-header" style="padding: 10px; background-color: #d9d9d9;">
                        <h5 class="modal-title ml-2" id="addaccountsetupModalLabel" style="font-size: 14px;">Edit Account
                            Form</h5>
                        <button type="button" class="close" id="edit_closeModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="form-group">
                            <label style="font-weight: normal; font-size: 13px;">Academic Program</label>
                            <div class="d-flex align-items-center mt-1">
                                <select id="academic_program" class="form-control" disabled
                                    style="width:45%; height: 40px; font-size: 12px; border-radius: 10px; ">
                                    <option value="">Select Academic Program</option>
                                    @foreach ($acadprog as $item)
                                        <option value="{{ $item->id }}">{{ $item->progname }}</option>
                                    @endforeach
                                </select>
                                <label class="form-check-label ml-5" for="isActive"
                                    style="font-weight: normal; font-size: 13px;">
                                    <input class="form-check-input" type="checkbox" id="isActive"
                                        style="margin-right: 5px;" checked disabled> Apply To All Academic Level
                                </label>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-success rounded" id="edit-add-classification-btn"
                                style="background-color: #00581f; height: 30px; font-size: 12.5px; font-family: Arial, sans-serif;">
                                <i class="fas fa-plus-circle mr-1"></i> Add Classification
                            </button>
                        </div>


                        <div class="card mt-3">
                            <div class="card-body" id="classification-rows-container-edit">

                            </div>
                        </div>

                        <div class="card mt-3" style="border:none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label style="font-size: 13px; color: #333; font-weight: 600;">Cashier Journal
                                            Entry</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label style="font-size: 13px; color: #333; font-weight: 600;">Debit
                                            Account</label>
                                        <select id="edit_cashier_debit_account" class="form-control"
                                            style="height: 30px; font-size: 12px; border-radius: 8px; border:none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                                            {{-- <option value="" disabled selected>Select Account</option>
                                            @foreach ($coa as $item)
                                                <option value="{{ $item->id }}">{{ $item->code }} -
                                                    {{ $item->account_name }}</option>
                                            @endforeach --}}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-success"
                                style="background-color: #00581f; height: 30px; border: none; font-size: 12.5px;"
                                id="updateclassified">Update</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade show" id="modal-classification" aria-modal="true"
            style="padding-right: 17px; display: none;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title">Item Classification</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="class-desc" class="col-sm-3 col-form-label">Description</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control validation" id="class_description"
                                            placeholder="" onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="class-desc" class="col-sm-3 col-form-label">Accounts</label>
                                    <div class="col-sm-9">
                                        <select class="form-control select2" id="class_account">
                                            <option value="0">&nbsp;</option>
                                            @foreach (db::table('acc_coa')->where('deleted', 0)->get() as $gl)
                                                <option value="{{ $gl->id }}">{{ $gl->code }} -
                                                    {{ $gl->account }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="class-glid" class="col-sm-3 col-form-label">Group</label>
                                    <div class="col-sm-5">
                                        <select class="form-control select2" id="class_group">
                                            <option value="">&nbsp;</option>
                                            <option value="TUI">TUITION</option>
                                            <option value="MISC">MISCELLANEOUS</option>
                                            <option value="OTH">OTHER FEES</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3 mt-2">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="class_itemized" disabled>
                                            <label for="class_itemized">
                                                Itemized
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="class-desc" class="col-sm-5 col-form-label">Consolidated Grouping</label>
                                    <div class="col-sm-7">
                                        <select class="form-control select2" id="class_code">
                                            <option value="0">&nbsp;</option>
                                            @foreach (db::table('items_classcode')->get() as $code)
                                                <option value="{{ $code->id }}">{{ $code->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button id="class_delete" type="button" class="btn btn-danger"
                            style="display: none">Delete</button>
                        <button id="class_save" type="button" class="btn btn-primary" data-id="0">Save</button>
                    </div>
                </div>
            </div> {{-- dialog --}}
        </div>
    </body>
@endsection

@section('footerjavascript')
    <script>
        const classifications = @json($items);
        const accounts = @json($coa);
    </script>

    <script>
        $(document).ready(function() {
            $('#academic_program').on('change', function() {
                if ($(this).val()) {
                    $('#isActive').prop('checked', false).trigger('change');
                }
            });

            $('#isActive').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#academic_program').prop('disabled', true).val('');
                } else {
                    $('#academic_program').prop('disabled', false);
                }
            });

            $('#isActive').trigger('change');

            // $('.select2').select2();

            let isFormDirty = false;

            // Track changes in the modal
            $(document).on('change input', '#addaccountsetupModal :input', function() {
                isFormDirty = true;
            });

            $('#addaccountsetup').on('click', function() {
                $('#addaccountsetupModal').modal('show');
            })

            $('#cashier_debit_account').select2({
                placeholder: "Select Credit Account",
                allowClear: true,
                theme: 'bootstrap4'
            });






            // Reset the dirty flag on save
            // $(document).on('click', '#saveclassified', function(e) {
            //     e.preventDefault();

            //     var acadprog = $('#academic_program').val();
            //     var applyToAll = $('#isActive').is(':checked');
            //     var rows = [];

            //     $('.classification-row').each(function() {
            //         var classification = $(this).find('.classification-select').val();
            //         var debitaccid = $(this).find('.debit-account-select').val();
            //         var creditaccid = $(this).find('.credit-account-select').val();
            //         var payment_debitacc = $('#cashier_debit_account').val();

            //         // if (classification && debitaccid && creditaccid) {
            //         //     rows.push({
            //         //         classification,
            //         //         debitaccid,
            //         //         creditaccid,
            //         //         payment_debitacc,
            //         //     });
            //         // }
            //         if ( debitaccid && creditaccid) {
            //             rows.push({
            //                 classification,
            //                 debitaccid,
            //                 creditaccid,
            //                 payment_debitacc,
            //             });
            //         }
            //     });

            //     if (rows.length === 0) {
            //         Swal.fire('Error', 'Please complete at least one row (classification, debit, credit).',
            //             'error');
            //         return;
            //     }

            //     $.ajax({
            //         url: '/bookkeeper/save-classified',
            //         method: 'POST',
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         data: {
            //             acadprog: acadprog,
            //             applyToAll: applyToAll,
            //             items: JSON.stringify(rows)
            //         },
            //         success: function(response) {
            //             Swal.fire({
            //                 title: 'Success!',
            //                 text: response.message,
            //                 type: 'success'
            //             }).then(() => {
            //                 fetchEnrollmentSetup();
            //             });

            //             isFormDirty = false; // reset flag
            //             $('#addaccountsetupModal').modal('hide');

            //             $('.modal-backdrop').removeClass('show');
            //             $('.modal-backdrop').addClass('hide');
            //         },
            //         error: function(xhr) {
            //             console.error(xhr.responseText);
            //             Swal.fire('Error!', 'An error occurred while saving.', 'error');
            //         }
            //     });
            // });

            $(document).on('click', '#saveclassified', function(e) {
                e.preventDefault();

                var acadprog = $('#academic_program').val();
                var applyToAll = $('#isActive').is(':checked');
                var rows = [];
                var cashier_debit_account = $('#cashier_debit_account').val();

                if (!applyToAll && !acadprog) {
                    Swal.fire('Error', 'Please select an Academic Program.', 'error');
                    return;
                }
                
                $('.classification-row').each(function() {
                    var classification = $(this).find('.classification-select').val() || 0;
                    var debitaccid = $(this).find('.debit-account-select').val();
                    var creditaccid = $(this).find('.credit-account-select').val();
                    var payment_debitacc = $('#cashier_debit_account').val();

                    /////////////////////////////////////////////////////////


                    // if (classification && debitaccid && creditaccid) {
                    //     rows.push({
                    //         classification,
                    //         debitaccid,
                    //         creditaccid,
                    //         payment_debitacc,
                    //     });
                    // }
                    if (debitaccid && creditaccid) {
                        rows.push({
                            classification,
                            debitaccid,
                            creditaccid,
                            payment_debitacc,
                        });
                    }
                });

                // if (rows.length === 0) {
                //     Swal.fire('Error', 'Please complete at least one row (classification, debit, credit).',
                //         'error');
                //     return;
                // }

                if (rows.length === 0 || !cashier_debit_account) {
                    Swal.fire('Error',
                        'Please complete at least one row (debit, credit) and ensure cashier debit account is selected.',
                        'error');
                    return;
                }

                $.ajax({
                    url: '/bookkeeper/save-classified',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        acadprog: acadprog,
                        applyToAll: applyToAll,
                        items: JSON.stringify(rows)
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            type: 'success'
                        }).then(() => {
                            fetchEnrollmentSetup();
                        });

                        isFormDirty = false; // reset flag
                        $('#addaccountsetupModal').modal('hide');

                        $('.modal-backdrop').removeClass('show');
                        $('.modal-backdrop').addClass('hide');
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire('Error!', 'An error occurred while saving.', 'error');
                    }
                });
            });

            // backup
            // $(document).on('click', '#saveclassified', function(e) {
            //     e.preventDefault();

            //     var acadprog = $('#academic_program').val();
            //     var applyToAll = $('#isActive').is(':checked');
            //     var rows = [];
            //     var cashier_debit_account = $('#cashier_debit_account').val();

            //     $('.classification-row').each(function() {
            //         var classification = $(this).find('.classification-select').val() || 0;
            //         var debitaccid = $(this).find('.debit-account-select').val();
            //         var creditaccid = $(this).find('.credit-account-select').val();
            //         var payment_debitacc = $('#cashier_debit_account').val();

            //         if (debitaccid && creditaccid) {
            //             rows.push({
            //                 classification,
            //                 debitaccid,
            //                 creditaccid,
            //                 payment_debitacc,
            //             });
            //         }
            //     });

            //     if (rows.length === 0 || !cashier_debit_account) {
            //         Swal.fire('Error',
            //             'Please complete at least one row (debit, credit) and ensure cashier debit account is selected.',
            //             'error');
            //         return;
            //     }

            //     $.ajax({
            //         url: '/bookkeeper/save-classified',
            //         method: 'POST',
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         data: {
            //             acadprog: acadprog,
            //             applyToAll: applyToAll,
            //             items: JSON.stringify(rows)
            //         },
            //         success: function(response) {
            //             if (response.error) {
            //                 Swal.fire('Error!', response.error, 'error');
            //                 return;
            //             }

            //             Swal.fire('Success!', 'Classified account setup saved.', 'success')
            //                 .then(() => {
            //                     fetchEnrollmentSetup();
            //                 });

            //             isFormDirty = false; // reset flag
            //             $('#addaccountsetupModal').modal('hide');
            //             $('.modal-backdrop').removeClass('show');
            //             $('.modal-backdrop').addClass('hide');
            //         },
            //         error: function(xhr) {
            //             console.error(xhr.responseText);
            //             Swal.fire('Error!', 'An error occurred while saving.', 'error');
            //         }
            //     });
            // });



            $('#closeModal').on('click', function(e) {
                e.preventDefault();
                if (isFormDirty) {

                    Swal.fire({
                        title: 'Unsaved Changes',
                        text: 'You have unsaved changes. Do you want to discard them?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Discard',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.value) {
                            isFormDirty = false;

                            setTimeout(() => {
                                $('#addaccountsetupModal').modal('hide');
                                $('.modal-backdrop').removeClass('show');
                                $('.modal-backdrop').addClass('hide');
                            }, 100);
                        }
                        // Else: do nothing (modal stays open with data intact)
                    });
                } else {
                    $('#addaccountsetupModal').modal('hide');
                    $('.modal-backdrop').removeClass('show');
                    $('.modal-backdrop').addClass('hide');
                }
            });





            // Cleanup on actual close
            $('#addaccountsetupModal').on('hidden.bs.modal', function() {
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('#academic_program').val('');
                $('#isActive').prop('checked', true);
                // $('#cashier_debit_account').val('');
                $('#classification-rows-container').empty();
                isFormDirty = false;
            });


            $('#addaccountsetupModal').on('hidden.bs.modal', function() {
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();

                $('#academic_program').val('');
                $('#isActive').prop('checked', true);
                // $('#cashier_debit_account').val('');

                $('#classification-rows-container').empty();
            });



            fetchEnrollmentSetup();

            function fetchEnrollmentSetup() {
                $.ajax({
                    url: '/bookkeeper/enrollmentsetup',
                    type: 'GET',
                    success: function(data) {
                        var tbody = $('#classifieditems');
                        tbody.empty();

                        data.forEach((program) => {
                            if (program.levels && program.levels.length > 0) {
                                let isFirstProg = true;
                                program.levels.forEach((level) => {
                                    let isFirstLevel = true;
                                    level.classifications.forEach((classification) => {
                                        tbody.append(`
                                            <tr class="classification-row" data-classid="${classification.classid}" data-id="${classification.id}">
                                                <td style="border: 1px solid #7d7d7d;">${isFirstProg ? program.progname : ''}</td>
                                                <td style="border: 1px solid #7d7d7d;">${isFirstLevel ? level.levelname : ''}</td>
                                                <td style="border: 1px solid #7d7d7d;">${classification.description || ''}</td>
                                                <td style="border: 1px solid #7d7d7d;">${classification.debitacc || ''}</td>
                                                <td style="border: 1px solid #7d7d7d;">${classification.creditacc || ''}</td>
                                                <td style="border: 1px solid #7d7d7d;">${classification.cashier_debitacc || ''}</td>
                                                <td style="border: 1px solid #7d7d7d; text-align: center;">
                                                    <i class="far fa-edit text-primary editEnrollmentSetup" 
                                                        data-id="${classification.id}" style="cursor: pointer;"></i>
                                                </td>
                                            </tr>
                                        `);



                                        // <td style="border: 1px solid #7d7d7d; text-align: center;">
                                        //     ${isFirstProg ? `<i class="far fa-edit text-primary editEnrollmentSetup" 
                                    //         data-id="${program.progid}" style="cursor: pointer;"></i>` : ''}
                                        // </td>

                                        isFirstProg = false;
                                        isFirstLevel = false;
                                    });
                                });
                            }
                        });
                    },
                    error: function(err) {
                        console.error('Failed to fetch classified setup:', err);
                    }
                });
            }


            // function fetchEnrollmentSetup() {
            //     $.ajax({
            //         url: '/bookkeeper/enrollmentsetup',
            //         type: 'GET',
            //         success: function(data) {
            //             var tbody = $('#classifieditems');
            //             tbody.empty();

            //             data.forEach((program) => {
            //                 if (program.levels && program.levels.length > 0) {
            //                     // Add program header row
            //                     // tbody.append(`
        //                     //     <tr class="program-header" data-progid="${program.progid}">
        //                     //         <td colspan="7" style="border: 1px solid #7d7d7d; font-weight: bold; background-color: #f5f5f5;">
        //                     //             ${program.progname}
        //                     //         </td>
        //                     //     </tr>
        //                     // `);

            //                     // Add each level and its classifications
            //                     program.levels.forEach((level) => {
            //                         // Add classification rows with level name in the first row
            //                         level.classifications.forEach((classification, index) => {
            //                             tbody.append(`
        //                                 <tr class="classification-row" data-classid="${classification.classid}" data-id="${classification.id}">
        //                                     <td style="border: 1px solid #7d7d7d;">${index === 0 ? program.progname : ''}</td>
        //                                     <td style="border: 1px solid #7d7d7d;">${index === 0 ? level.levelname : ''}</td>
        //                                     <td style="border: 1px solid #7d7d7d;">${classification.description || ''}</td>
        //                                     <td style="border: 1px solid #7d7d7d;">${classification.debitacc || ''}</td>
        //                                     <td style="border: 1px solid #7d7d7d;">${classification.creditacc || ''}</td>
        //                                     <td style="border: 1px solid #7d7d7d;">${classification.cashier_debitacc || ''}</td>
        //                                     <td style="border: 1px solid #7d7d7d; text-align: center;">
        //                                         ${index === 0 ? `<i class="far fa-edit text-primary editEnrollmentSetup" 
            //                                         data-id="${program.progid}" style="cursor: pointer;"></i>` : ''}
        //                                     </td>
        //                                 </tr>
        //                             `);
            //                         });
            //                     });
            //                 }
            //             });
            //         },
            //         error: function(err) {
            //             console.error('Failed to fetch classified setup:', err);
            //         }
            //     });
            // }


            // function fetchEnrollmentSetup() {
            //     $.ajax({
            //         url: '/bookkeeper/enrollmentsetup',
            //         type: 'GET',
            //         success: function(data) {
            //             var tbody = $('#classifieditems');
            //             tbody.empty();

            //             var lastProgname = '';
            //             var lastLevelname = '';

            //             data.forEach((item, index) => {
            //                 var groupKey = `${item.progname}-${item.levelname}`;

            //                 tbody.append(`
        //                             <tr data-group="${groupKey}">
        //                                 <td style="border: 1px solid #7d7d7d;">${item.progname !== lastProgname ? item.progname : ''}</td>
        //                                 <td style="border: 1px solid #7d7d7d;">${item.levelname !== lastLevelname || item.progname !== lastProgname ? item.levelname : ''}</td>
        //                                 <td style="border: 1px solid #7d7d7d;">${item.description === null ? '' : item.description}</td>
        //                                 <td style="border: 1px solid #7d7d7d;">${item.debitacc}</td>
        //                                 <td style="border: 1px solid #7d7d7d;">${item.creditacc}</td>
        //                                 <td style="border: 1px solid #7d7d7d;">${item.cashier_debitacc ?? ''}</td>
        //                                 <td style="border: 1px solid #7d7d7d; text-align: center;">
        //                                     ${item.progname !== lastProgname ? `<i class="far fa-edit text-primary editEnrollmentSetup" data-id="${item.acadprogid}" style="cursor: pointer;"></i>` : ''}
        //                                 </td>
        //                             </tr>
        //                         `);

            //                 lastProgname = item.progname;
            //                 lastLevelname = item.levelname;
            //             });

            //         },
            //         error: function(err) {
            //             console.error('Failed to fetch classified setup:', err);
            //         }
            //     });
            // }

            // function fetchEnrollmentSetup() {
            //     $.ajax({
            //         url: '/bookkeeper/enrollmentsetup',
            //         type: 'GET',
            //         success: function(data) {
            //             var tbody = $('#classifieditems');
            //             tbody.empty();

            //             var lastProgname = '';
            //             var lastLevelname = '';

            //             data.forEach((item, index) => {
            //                 var groupKey = `${item.progname}-${item.levelname}`;

            //                 tbody.append(`
        //                             <tr data-group="${groupKey}">
        //                                 <td style="border: 1px solid #7d7d7d;">${item.progname !== lastProgname ? item.progname : ''}</td>
        //                                 <td style="border: 1px solid #7d7d7d;">${item.levelname !== lastLevelname || item.progname !== lastProgname ? item.levelname : ''}</td>
        //                                 <td style="border: 1px solid #7d7d7d;">${item.description === null ? '' : item.description}</td>
        //                                 <td style="border: 1px solid #7d7d7d;">${item.debitacc}</td>
        //                                 <td style="border: 1px solid #7d7d7d;">${item.creditacc}</td>
        //                                 <td style="border: 1px solid #7d7d7d;">${item.cashier_debitacc ?? ''}</td>
        //                                 <td style="border: 1px solid #7d7d7d; text-align: center;">
        //                                     ${item.progname !== lastProgname ? `<i class="far fa-edit text-primary editEnrollmentSetup" data-id="${item.acadprogid}" style="cursor: pointer;"></i>` : ''}
        //                                 </td>
        //                             </tr>
        //                         `);

            //                 lastProgname = item.progname;
            //                 lastLevelname = item.levelname;
            //             });

            //         },
            //         error: function(err) {
            //             console.error('Failed to fetch classified setup:', err);
            //         }
            //     });
            // }
            ////////////////////////////
            // $(document).on('click', '.editEnrollmentSetup', function() {
            //     let enrollmentSetup_academicId = $(this).data('id');
            //     $('#editaccountsetupModal').modal('show');
            //     // Add this before the AJAX call
            //     console.log('Starting edit for ID:', enrollmentSetup_academicId);

            //     $.ajax({
            //         type: 'GET',
            //         url: '/bookkeeper/enrollement_Setup/edit_enrollment_setup',
            //         data: {
            //             enrollmentSetup_academicId: enrollmentSetup_academicId
            //         },
            //         success: function(response) {
            //             // Add this in the success handler
            //             console.log('Number of classifications:', response.classified ? response
            //                 .classified.length : 0);
            //             console.log('First classification:', response.classified ? response
            //                 .classified[0] : null);

            //             // $('#classification-rows-container').empty();

            //             // First, ensure the container is properly cleared
            //             $('#classification-rows-container-edit').empty();

            //             // Process classifications
            //             if (response.classified && response.classified.length) {
            //                 response.classified.forEach(function(item) {
            //                     const rowId = 'row-' + Date.now();

            //                     // Create classification select
            //                     let classificationSelect =
            //                         '<select class="form-control classification-select-select">';
            //                     classificationSelect +=
            //                         '<option value="">Select Classification</option>';

            //                     (response.itemclassification || []).forEach(ic => {
            //                         classificationSelect +=
            //                             `<option value="${ic.id}" ${ic.id == item.classid ? 'selected' : ''}>${ic.description}</option>`;
            //                     });
            //                     classificationSelect += '</select>';

            //                     // Create debit account select
            //                     let debitSelect =
            //                         '<select class="form-control debit-account-select-select">';
            //                     debitSelect +=
            //                         '<option value="">Select Debit Account</option>';

            //                     (response.chart_of_accounts || []).forEach(account => {
            //                         debitSelect +=
            //                             `<option value="${account.id}" ${account.account_name == item.debitacc ? 'selected' : ''}>${account.code} - ${account.account_name}</option>`;
            //                     });
            //                     debitSelect += '</select>';

            //                     // Create credit account select
            //                     let creditSelect =
            //                         '<select class="form-control credit-account-select-select">';
            //                     creditSelect +=
            //                         '<option value="">Select Credit Account</option>';

            //                     (response.chart_of_accounts || []).forEach(account => {
            //                         creditSelect +=
            //                             `<option value="${account.id}" ${account.account_name == item.creditacc ? 'selected' : ''}>${account.code} - ${account.account_name}</option>`;
            //                     });
            //                     creditSelect += '</select>';

            //                     // Create the row HTML - simplified version
            //                     const rowHtml = `
        //                 <div class="card mb-3 classification-row" id="${rowId}">
        //                     <div class="card-body">
        //                         <div class="row">
        //                             <div class="col-md-3">
        //                                 <label>Classification</label>
        //                                 ${classificationSelect}
        //                             </div>
        //                             <div class="col-md-4">
        //                                 <label>Debit Account</label>
        //                                 ${debitSelect}
        //                             </div>
        //                             <div class="col-md-4">
        //                                 <label>Credit Account</label>
        //                                 ${creditSelect}
        //                             </div>
        //                             <div class="col-md-1 d-flex align-items-end">
        //                                 <button type="button" class="btn btn-danger btn-sm remove-classification-btn">
        //                                     <i class="fas fa-trash"></i>
        //                                 </button>
        //                             </div>
        //                         </div>
        //                     </div>
        //                 </div>
        //             `;

            //                     // Append using plain HTML (not jQuery-wrapped)
            //                     $('#classification-rows-container-edit').append(rowHtml);
            //                 });
            //             } else {
            //                 $('#classification-rows-container-edit').html(
            //                     '<p class="text-muted">No classifications found</p>');
            //             }
            //         },
            //         error: function(xhr) {
            //             console.error('Error:', xhr.responseText);
            //             alert('Failed to load enrollment setup data');
            //         }
            //     });
            // });
            ////////////////////////////very working
            // $(document).on('click', '.editEnrollmentSetup', function() {
            //     let enrollmentSetup_academicId = $(this).data('id');
            //     $('#editaccountsetupModal').modal('show');
            //     console.log('Starting edit for ID:', enrollmentSetup_academicId);

            //     $.ajax({
            //         type: 'GET',
            //         url: '/bookkeeper/enrollement_Setup/edit_enrollment_setup',
            //         data: {
            //             enrollmentSetup_academicId: enrollmentSetup_academicId
            //         },
            //         success: function(response) {
            //             console.log('AJAX Response:', response);

            //             const container = $('#classification-rows-container-edit');
            //             container.empty();

            //             if (response.classified && response.classified.length) {
            //                 response.classified.forEach(function(item) {
            //                     const rowId = 'row-' + Date.now() + '-' + Math.floor(
            //                         Math.random() * 1000);

            //                     // Classification select
            //                     let classificationSelect =
            //                         '<select class="form-control classification-select-select">';
            //                     classificationSelect +=
            //                         '<option value="">Select Classification</option>';
            //                     (response.itemclassification || []).forEach(ic => {
            //                         classificationSelect +=
            //                             `<option value="${ic.id}" ${ic.id == item.classid ? 'selected' : ''}>${ic.description}</option>`;
            //                     });
            //                     classificationSelect += '</select>';

            //                     // Debit account select
            //                     let debitSelect =
            //                         '<select class="form-control debit-account-select-select">';
            //                     debitSelect +=
            //                         '<option value="">Select Debit Account</option>';
            //                     (response.chart_of_accounts || []).forEach(account => {
            //                         debitSelect +=
            //                             `<option value="${account.id}" ${account.account_name == item.debitacc ? 'selected' : ''}>${account.code} - ${account.account_name}</option>`;
            //                     });
            //                     debitSelect += '</select>';

            //                     // Credit account select
            //                     let creditSelect =
            //                         '<select class="form-control credit-account-select-select">';
            //                     creditSelect +=
            //                         '<option value="">Select Credit Account</option>';
            //                     (response.chart_of_accounts || []).forEach(account => {
            //                         creditSelect +=
            //                             `<option value="${account.id}" ${account.account_name == item.creditacc ? 'selected' : ''}>${account.code} - ${account.account_name}</option>`;
            //                     });
            //                     creditSelect += '</select>';

            //                     // Create the row HTML
            //                     const rowHtml = `
        //                         <div class="card mb-3 classification-row" id="${rowId}">
        //                             <div class="card-body">
        //                                 <div class="row">
        //                                     <div class="col-md-3">
        //                                         <label>Classification</label>
        //                                         ${classificationSelect}
        //                                     </div>
        //                                     <div class="col-md-4">
        //                                         <label>Debit Account</label>
        //                                         ${debitSelect}
        //                                     </div>
        //                                     <div class="col-md-4">
        //                                         <label>Credit Account</label>
        //                                         ${creditSelect}
        //                                     </div>
        //                                     <div class="col-md-1 d-flex align-items-end">
        //                                         <button type="button" class="btn btn-danger btn-sm remove-classification-btn">
        //                                             <i class="fas fa-trash"></i>
        //                                         </button>
        //                                     </div>
        //                                 </div>
        //                             </div>
        //                         </div>
        //                     `;

            //                     console.log('Generated HTML:',
            //                         rowHtml); // Debug the generated HTML
            //                     container.append(rowHtml);
            //                     console.log('Container children:', $(
            //                             '#classification-rows-container').children()
            //                         .length);
            //                 });
            //             } else {
            //                 container.html(
            //                     '<p class="text-muted">No classifications found</p>');
            //             }
            //         },
            //         error: function(xhr) {
            //             console.error('Error:', xhr.responseText);
            //             alert('Failed to load enrollment setup data');
            //         }
            //     });
            // });
            ////////////////////////////

            $(document).on('click', '.editEnrollmentSetup', function() {
                let enrollmentSetup_academicId = $(this).data('id');
                $('#editaccountsetupModal').modal('show');

                $.ajax({
                    type: 'GET',
                    url: '/bookkeeper/enrollement_Setup/edit_enrollment_setup',
                    data: {
                        enrollmentSetup_academicId: enrollmentSetup_academicId
                    },
                    success: function(response) {
                        console.log('Response:', response); // Debugging

                        $('#classification-rows-container-edit').empty();

                        // Process classifications
                        if (response.classified && response.classified.length) {
                            response.classified.forEach(function(item) {
                                console.log('Processing item:', item);

                                const rowId = item.id;
                                const levelid = item.levelid;
                                const acadprogid = item.acadprogid;
                                const classid = item.classid;

                                // Create classification select
                                let classificationSelect =
                                    '<select class="form-control classification-select" style="height: 40px; font-size: 12px; border-radius: 8px; ">' +
                                    '<option value="">Select Classification</option>';

                                (response.itemclassification || []).forEach(ic => {
                                    const selected = ic.id == item.classid ?
                                        'selected' : '';
                                    classificationSelect +=
                                        `<option value="${ic.id}" ${selected}>${ic.description}</option>`;
                                });
                                classificationSelect += '</select>';

                                let debitSelectId = 'debit-account-select-edit-' + Date
                                    .now();

                                // Create debit account select
                                let debitSelect =
                                    '<select class="form-control debit-account-select" id="' +
                                    debitSelectId +
                                    '" style="height: 30px; font-size: 12px; border-radius: 8px; border:none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">' +
                                    '<option value="">Select Debit Account</option>';

                                (response.chart_of_accounts || []).forEach(account => {
                                    // First add the main account
                                    const debitacc_sub_account = (response
                                        .sub_chart_of_accounts || []).find(
                                        sub_account =>
                                        sub_account.coaid == account.id &&
                                        sub_account.id == item.debitaccid
                                    );

                                    const selected = (debitacc_sub_account ?
                                            debitacc_sub_account
                                            .sub_account_name : account
                                            .account_name) == item.debitacc ?
                                        'selected' : '';

                                    debitSelect +=
                                        `<option value="${account.id}" ${!debitacc_sub_account && selected ? 'selected' : ''}>
                                            ${account.code} - ${account.account_name}
                                        </option>`;

                                    // Then add all sub-accounts for this main account
                                    (response.sub_chart_of_accounts || [])
                                    .forEach(sub_account => {
                                        if (sub_account.coaid == account
                                            .id) {
                                            const sub_selected =
                                                sub_account.id == item
                                                .debitaccid ?
                                                'selected' : '';
                                            debitSelect +=
                                                `<option value="${sub_account.id}" ${sub_selected}>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;${sub_account.sub_code} - ${sub_account.sub_account_name}
                                                </option>`;
                                        }
                                    });
                                });

                                debitSelect += '</select>';

                                // // First, add the select element to the DOM
                                // $('#debit-account-select-edit-container').html(debitSelect); 
                                // setTimeout(function() {
                                //     $('.debit-account-select').select2({
                                //         placeholder: "Select Debit Account",
                                //         allowClear: true,
                                //         theme: 'bootstrap4',
                                //         width: '100%'
                                //     });
                                // }, 100);

                                setTimeout(function() {
                                    $('#' + debitSelectId).select2({
                                        placeholder: "Select Debit Account",
                                        allowClear: true,
                                        theme: 'bootstrap4',
                                        width: '100%'
                                    });
                                }, 100);

                                // Then initialize Select2

                                let creditSelectId = 'credit-account-select-edit-' +
                                    Date.now();
                                // Create credit account select
                                let creditSelect =
                                    '<select class="form-control credit-account-select" id="' +
                                    creditSelectId +
                                    '" style="height: 30px; font-size: 12px; border-radius: 8px; border:none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">' +
                                    '<option value="">Select Credit Account</option>';

                                (response.chart_of_accounts || []).forEach(account => {
                                    const creditacc_sub_account = (response
                                        .sub_chart_of_accounts || []).find(
                                        sub_account =>
                                        sub_account.coaid == account.id &&
                                        sub_account.id == item.creditaccid
                                    );

                                    const selected = (creditacc_sub_account ?
                                            creditacc_sub_account
                                            .sub_account_name : account
                                            .account_name) == item.creditacc ?
                                        'selected' : '';

                                    creditSelect +=
                                        `<option value="${account.id}" ${!creditacc_sub_account && selected ? 'selected' : ''}>
                                            ${account.code} - ${account.account_name}
                                        </option>`;

                                    (response.sub_chart_of_accounts || [])
                                    .forEach(sub_account => {
                                        if (sub_account.coaid == account
                                            .id) {
                                            const sub_selected =
                                                sub_account.id == item
                                                .creditaccid ?
                                                'selected' : '';
                                            creditSelect +=
                                                `<option value="${sub_account.id}" ${sub_selected}>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;${sub_account.sub_code} - ${sub_account.sub_account_name}
                                                </option>`;
                                        }
                                    });
                                });
                                creditSelect += '</select>';

                                setTimeout(function() {
                                    $('#' + creditSelectId).select2({
                                        placeholder: "Select Credit Account",
                                        allowClear: true,
                                        theme: 'bootstrap4',
                                        width: '100%'
                                    });
                                }, 100);

                                const rowHtml = `
                                <div class="card mb-3 classification-row" id="${rowId}" levelid="${levelid}" acadprogid="${acadprogid}"  style="border:none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label style="font-size: 13px; color: #333; font-weight: 600;">Classification</label>
                                                ${classificationSelect}
                                            </div>
                                            <div class="col-md-4">
                                                <label style="font-size: 13px; color: #333; font-weight: 600;">Debit Account</label>
                                                ${debitSelect}
                                            </div>
                                            <div class="col-md-4">
                                                <label style="font-size: 13px; color: #333; font-weight: 600;">Credit Account</label>
                                                ${creditSelect}
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm remove-classification-btn-edit" data-classid="${classid}"  data-acadprogid="${acadprogid}" data-levelid="${levelid}" style="height: 30px;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;

                                $('#classification-rows-container-edit').append($(
                                    rowHtml));

                                    function addClassificationRow_edit() {
                                    var count = 0;

                                    $('#edit-add-classification-btn').click(function() {
                                        count++;

                                        var row = `
                                                <div class="form-row mb-3 classification-row" data-id="${count}" id="0" levelid="${levelid}" acadprogid="${acadprogid}">
                                                    <div class="form-group col-md-4">
                                                        <label style="font-weight: 600; font-size: 13px;">Classification</label>
                                                        <select class="form-control select2 classification-select"
                                                            style="width: 90%; height: 40px; font-size: 12px; border-radius: 10px; ">
                                                            <option value="">Select Classification</option>
                                                            ${classifications.map(item => `<option value="${item.id}">${item.description}</option>`).join('')}
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label style="font-weight: 600; font-size: 13px;">Debit Account</label>
                                                        <select class="form-control select2 debit-account-select"
                                                            style="width: 90%; height: 30px; font-size: 12px; border-radius: 10px; border: none;
                                                                box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                                                            <option value="">Select Debit</option>
                                                            @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                                                <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
                                                                @foreach (DB::table('bk_sub_chart_of_accounts')->where('deleted', 0)->where('coaid', $coa->id)->get() as $subcoa)
                                                                    <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $subcoa->sub_code }} -
                                                                        {{ $subcoa->sub_account_name }}</option>
                                                                @endforeach
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label style="font-weight: 600; font-size: 13px;">Credit Account</label>
                                                        <select class="form-control select2 credit-account-select"
                                                            style="width: 100%; height: 30px; font-size: 12px; border-radius: 10px; border: none;
                                                                box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                                                            <option value="">Select Credit</option>
                                                            @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                                                <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
                                                                @foreach (DB::table('bk_sub_chart_of_accounts')->where('deleted', 0)->where('coaid', $coa->id)->get() as $subcoa)
                                                                    <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $subcoa->sub_code }} -
                                                                        {{ $subcoa->sub_account_name }}</option>
                                                                @endforeach
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-1 d-flex align-items-end">
                                                        <button type="button" class="btn btn-danger btn-sm remove-row" style="border-radius: 50%; height: 30px;">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            `;
                                        $('#classification-rows-container-edit').append(row);
                                        $('.debit-account-select').select2({
                                            placeholder: "Select Debit Account",
                                            allowClear: true,
                                            theme: 'bootstrap4'
                                        });
                                        $('.credit-account-select').select2({
                                            placeholder: "Select Credit Account",
                                            allowClear: true,
                                            theme: 'bootstrap4'
                                        });
                                    });
                                }

                                addClassificationRow_edit();
                            });

                            // Set cashier debit account from first item if available
                            const cashierDebitAcc = response.classified[0].cashier_debitacc;
                            if (cashierDebitAcc) {
                                console.log('Cashier debit account found:', cashierDebitAcc);
                                let options =
                                    '<option value="">Select Cashier Debit Account</option>';
                                (response.chart_of_accounts || []).forEach(acc => {
                                    const selected = acc.account_name ==
                                        cashierDebitAcc ? 'selected' : '';
                                    options +=
                                        `<option value="${acc.id}" ${selected}>${acc.code} - ${acc.account_name}</option>`;
                                    (response.sub_chart_of_accounts || [])
                                    .filter(subAcc => subAcc.coaid == acc.id)
                                        .forEach(subAcc => {
                                            const selected = subAcc
                                                .sub_account_name ==
                                                cashierDebitAcc ? 'selected' : '';
                                            options +=
                                                `<option value="${subAcc.id}" ${selected}>&nbsp;&nbsp;&nbsp;&nbsp;${subAcc.sub_code} - ${subAcc.sub_account_name}</option>`;
                                        });
                                });
                                $('#edit_cashier_debit_account').html(options);

                                $('#edit_cashier_debit_account').select2({
                                    placeholder: "Select Debit Account",
                                    allowClear: true,
                                    theme: 'bootstrap4'
                                });


                            }
                        } else {
                            console.log('No classified data found in response');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                        alert('Failed to load enrollment setup data');
                    }
                });
            });

             $('#editaccountsetupModal').on('hidden.bs.modal', function() {
                $('#edit-add-classification-btn').unbind('click');
            })

            // function addClassificationRow(item) {
            //     console.log('Adding classification row:', item);

            //     const coa = $('#editaccountsetupModal').data('coa') || [];
            //     const itemClassification = $('#editaccountsetupModal').data('itemclassification') || [];

            //     const rowId = 'row-' + Date.now();

            //     // Create classification select
            //     let classificationSelect =
            //         '<select class="form-control classification-select" style="height: 30px; font-size: 12px; border-radius: 8px; border:none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);"><option value="">Select Classification</option>';
            //     itemClassification.forEach(ic => {
            //         const selected = ic.id == item.classid ? 'selected' : '';
            //         classificationSelect +=
            //             `<option value="${ic.id}" ${selected}>${ic.description}</option>`;
            //     });
            //     classificationSelect += '</select>';

            //     // Create debit account select
            //     let debitSelect =
            //         '<select class="form-control debit-account-select" style="height: 30px; font-size: 12px; border-radius: 8px; border:none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);"><option value="">Select Debit Account</option>';
            //     coa.forEach(account => {
            //         const selected = account.account_name === item.debitacc ? 'selected' : '';
            //         debitSelect +=
            //             `<option value="${account.id}" ${selected}>${account.code} - ${account.account_name}</option>`;
            //     });
            //     debitSelect += '</select>';

            //     // Create credit account select
            //     let creditSelect =
            //         '<select class="form-control credit-account-select" style="height: 30px; font-size: 12px; border-radius: 8px; border:none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);"><option value="">Select Credit Account</option>';
            //     coa.forEach(account => {
            //         const selected = account.account_name === item.creditacc ? 'selected' : '';
            //         creditSelect +=
            //             `<option value="${account.id}" ${selected}>${account.code} - ${account.account_name}</option>`;
            //     });
            //     creditSelect += '</select>';

            //     const rowHtml = `
        //         <div class="card mb-3 classification-row" id="${rowId}" style="border:none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
        //             <div class="card-body">
        //                 <div class="row">
        //                     <div class="col-md-3">
        //                         <label style="font-size: 13px; color: #333; font-weight: 600;">Classification</label>
        //                         ${classificationSelect}
        //                     </div>
        //                     <div class="col-md-4">
        //                         <label style="font-size: 13px; color: #333; font-weight: 600;">Debit Account</label>
        //                         ${debitSelect}
        //                     </div>
        //                     <div class="col-md-4">
        //                         <label style="font-size: 13px; color: #333; font-weight: 600;">Credit Account</label>
        //                         ${creditSelect}
        //                     </div>
        //                     <div class="col-md-1 d-flex align-items-end">
        //                         <button type="button" class="btn btn-danger btn-sm remove-classification-btn" style="height: 30px;">
        //                             <i class="fas fa-trash"></i>
        //                         </button>
        //                     </div>
        //                 </div>
        //             </div>
        //         </div>
        //     `;

            //     $('#classification-rows-container').append(rowHtml);
            // }

            // function fetchEnrollmentSetup() {
            //     $.ajax({
            //         url: '/bookkeeper/enrollmentsetup',
            //         type: 'GET',
            //         success: function(data) {
            //             var tbody = $('#classifieditems');
            //             tbody.empty();

            //             var lastProgname = '';
            //             var lastLevelname = '';

            //             data.forEach((item, index) => {
            //                 var groupKey = `${item.progname}-${item.levelname}`;

            //                 tbody.append(`
        //                             <tr data-group="${groupKey}">
        //                                 <td style="border: 1px solid #7d7d7d;">${item.progname !== lastProgname ? item.progname : ''}</td>
        //                                 <td style="border: 1px solid #7d7d7d;">${item.levelname !== lastLevelname || item.progname !== lastProgname ? item.levelname : ''}</td>
        //                                 <td style="border: 1px solid #7d7d7d;">${item.description}</td>
        //                                 <td style="border: 1px solid #7d7d7d;">${item.debitacc}</td>
        //                                 <td style="border: 1px solid #7d7d7d;">${item.creditacc}</td>
        //                                 <td style="border: 1px solid #7d7d7d;">${item.cashier_debitacc ?? ''}</td>
        //                                 <td style="border: 1px solid #7d7d7d; text-align: center;">
        //                                     <i class="far fa-edit text-primary editEnrollmentSetup" data-toggle="modal" data-target="#editaccountsetupModal" data-id="${item.id}" style="cursor: pointer;"></i>
        //                                 </td>
        //                             </tr>
        //                         `);

            //                 lastProgname = item.progname;
            //                 lastLevelname = item.levelname;
            //             });

            //         },
            //         error: function(err) {
            //             console.error('Failed to fetch classified setup:', err);
            //         }
            //     });
            // }

            ///////////////////////////////////
            // function addClassificationRow() {
            //     var count = 0;

            //     $('#add-classification-btn').click(function() {
            //         count++;

            //         var row = `
        //                 <div class="form-row mb-3 classification-row" data-id="${count}">
        //                     <div class="form-group col-md-4">
        //                         <label style="font-weight: 600; font-size: 13px;">Classification</label>
        //                         <select class="form-control select2 classification-select"
        //                             style="width: 90%; height: 30px; font-size: 12px; border-radius: 10px; border: none;
        //                                 box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
        //                             <option value="">Select Classification</option>
        //                             ${classifications.map(item => `<option value="${item.id}">${item.description}</option>`).join('')}
        //                         </select>
        //                     </div>
        //                     <div class="form-group col-md-4">
        //                         <label style="font-weight: 600; font-size: 13px;">Debit Account</label>
        //                         <select class="form-control select2 debit-account-select"
        //                             style="width: 90%; height: 30px; font-size: 12px; border-radius: 10px; border: none;
        //                                 box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
        //                             <option value="">Select Debit</option>
        //                             @foreach (DB::table('chart_of_accounts')->get() as $coa)
        //                                 <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
        //                                 @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->get() as $subcoa)
        //                                     <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        //                                         {{ $subcoa->sub_code }} - {{ $subcoa->sub_account_name }}</option>
        //                                 @endforeach
        //                             @endforeach
        //                         </select>
        //                     </div>
        //                     <div class="form-group col-md-3">
        //                         <label style="font-weight: 600; font-size: 13px;">Credit Account</label>
        //                         <select class="form-control select2 credit-account-select"
        //                             style="width: 100%; height: 30px; font-size: 12px; border-radius: 10px; border: none;
        //                                 box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
        //                             <option value="">Select Credit</option>
        //                             @foreach (DB::table('chart_of_accounts')->get() as $coa)
        //                                 <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
        //                                 @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->get() as $subcoa)
        //                                     <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        //                                         {{ $subcoa->sub_code }} - {{ $subcoa->sub_account_name }}</option>
        //                                 @endforeach
        //                             @endforeach
        //                         </select>
        //                     </div>
        //                     <div class="form-group col-md-1 d-flex align-items-end">
        //                         <button type="button" class="btn btn-danger btn-sm remove-row" style="border-radius: 50%; height: 30px;">
        //                             <i class="fas fa-times"></i>
        //                         </button>
        //                     </div>
        //                 </div>
        //             `;
            //         $('#classification-rows-container').append(row);
            //         $('.debit-account-select').select2({ 
            //             placeholder: "Select Debit Account", 
            //             allowClear: true, 
            //             theme: 'bootstrap4' 
            //         });
            //         $('.credit-account-select').select2({ 
            //             placeholder: "Select Credit Account", 
            //             allowClear: true, 
            //             theme: 'bootstrap4' 
            //         });
            //     });


            // }

            // addClassificationRow();
            function addClassificationRow() {
                var count = 0;
                var selectedClassifications = new Set(); // Track selected classification IDs

                // Function to update disabled options in all dropdowns
                // function updateDisabledOptions() {
                //     $('.classification-select').each(function() {
                //         var currentSelected = $(this).val();
                //         $(this).find('option').each(function() {
                //             var optionValue = $(this).val();
                //             if (optionValue === '') return; // Don't disable the default option

                //             // Enable all options first
                //             $(this).prop('disabled', false);

                //             // Disable if selected in another dropdown (except current selection)
                //             if (selectedClassifications.has(optionValue) && optionValue !==
                //                 currentSelected) {
                //                 $(this).prop('disabled', true);
                //                 $(this).css('background-color', '#007bff !important');
                //             }
                //         });

                //         // If current selection is disabled (due to removal), reset it
                //         if (currentSelected && $(this).find('option:selected').prop('disabled')) {
                //             $(this).val('').trigger('change');
                //             selectedClassifications.delete(currentSelected);
                //             updateDisabledOptions();
                //         }
                //     });
                // }

                // Function to update disabled options and apply styling
                function updateDisabledOptions() {
                    $('.classification-select').each(function() {
                        var currentSelected = $(this).val();
                        $(this).find('option').each(function() {
                            var optionValue = $(this).val();
                            if (optionValue === '') return;

                            // Reset all options first
                            $(this).prop('disabled', false);
                            $(this).removeClass('bg-selected-option');

                            // Disable and style if selected in another dropdown
                            if (selectedClassifications.has(optionValue) && optionValue !==
                                currentSelected) {
                                $(this).prop('disabled', true);
                                $(this).addClass('bg-selected-option');
                            }
                        });

                        // Reset if current selection is disabled
                        if (currentSelected && $(this).find('option:selected').prop('disabled')) {
                            $(this).val('').trigger('change');
                            selectedClassifications.delete(currentSelected);
                            updateDisabledOptions();
                        }
                    });
                }

                $('#add-classification-btn').click(function() {
                    count++;

                    var row = `
                        <div class="form-row mb-3 classification-row" data-id="${count}">
                            <div class="form-group col-md-4">
                                <label style="font-weight: 600; font-size: 13px;">Classification</label>
                                <select class="form-control select2 classification-select"
                                    style="width: 90%; height: 30px; font-size: 12px; border-radius: 10px; border: none;
                                        box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                                    <option value="">Select Classification</option>
                                    ${classifications.map(item => `<option value="${item.id}">${item.description}</option>`).join('')}
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label style="font-weight: 600; font-size: 13px;">Debit Account</label>
                                <select class="form-control select2 debit-account-select"
                                    style="width: 90%; height: 30px; font-size: 12px; border-radius: 10px; border: none;
                                        box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                                    <option value="">Select Debit</option>
                                    @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                        <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
                                        @foreach (DB::table('bk_sub_chart_of_accounts')->where('deleted', 0)->where('coaid', $coa->id)->get() as $subcoa)
                                            <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                {{ $subcoa->sub_code }} - {{ $subcoa->sub_account_name }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label style="font-weight: 600; font-size: 13px;">Credit Account</label>
                                <select class="form-control select2 credit-account-select"
                                    style="width: 100%; height: 30px; font-size: 12px; border-radius: 10px; border: none;
                                        box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                                    <option value="">Select Credit</option>
                                    @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                        <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
                                        @foreach (DB::table('bk_sub_chart_of_accounts')->where('deleted', 0)->where('coaid', $coa->id)->get() as $subcoa)
                                            <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                {{ $subcoa->sub_code }} - {{ $subcoa->sub_account_name }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-row" style="border-radius: 50%; height: 30px;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    `;

                    $('#classification-rows-container').append(row);

                    // Initialize Select2
                    // Initialize Select2
                    $('.classification-select').select2({
                        placeholder: "Select option",
                        allowClear: true,
                        theme: 'bootstrap4',
                        templateResult: function(option) {
                            // Apply custom styling to disabled options
                            if (option.element && $(option.element).hasClass(
                                    'bg-selected-option')) {
                                return $('<span class="bg-selected-option">' + option.text +
                                    '</span>');
                            }
                            return option.text;
                        }
                    });
                    $('.debit-account-select').select2({
                        placeholder: "Select Debit Account",
                        allowClear: true,
                        theme: 'bootstrap4'
                    });
                    $('.credit-account-select').select2({
                        placeholder: "Select Credit Account",
                        allowClear: true,
                        theme: 'bootstrap4'
                    });
                    // $('.classification-select').select2({
                    //     placeholder: "Select Classification",
                    //     allowClear: true,
                    //     theme: 'bootstrap4'
                    // });

                    // Update disabled options when a new row is added
                    updateDisabledOptions();
                });

                // Add CSS for selected options
                // $('<style>.bg-selected-option { background-color: #ffeeba !important; color: #856404 !important; }</style>')
                //     .appendTo('head');
                $('<style>\
                                            .bg-selected-option { \
                                                background-color: #d3d3d3 !important; \
                                                color: #333 !important; \
                                                padding: 8px 12px !important; \
                                                margin: -8px -12px !important; \
                                                display: block !important; \
                                                width: calc(100% + 24px) !important; \
                                            }\
                                            .select2-results__option[aria-disabled=true] { \
                                                padding: 0 !important; \
                                            }\
                                        </style>').appendTo('head');

                // Handle classification selection changes
                $(document).on('change', '.classification-select', function() {
                    var selectedValue = $(this).val();
                    var previousValue = $(this).data('previous-value');

                    // Remove previous value from tracking
                    if (previousValue) {
                        selectedClassifications.delete(previousValue);
                    }

                    // Add new value to tracking
                    if (selectedValue) {
                        selectedClassifications.add(selectedValue);
                    }

                    // Store current value as previous for next change
                    $(this).data('previous-value', selectedValue);

                    // Update all dropdowns
                    updateDisabledOptions();
                });



                // Handle row removal
                $(document).on('click', '.remove-row', function() {
                    var row = $(this).closest('.classification-row');
                    var classificationSelect = row.find('.classification-select');
                    var selectedValue = classificationSelect.val();

                    // Remove the classification from tracking if it was selected
                    if (selectedValue) {
                        selectedClassifications.delete(selectedValue);
                    }

                    // Remove the row
                    row.remove();

                    // Update all dropdowns
                    updateDisabledOptions();
                });
            }

            addClassificationRow();
            ///////////////////////////////////

            // Remove row
            $(document).on('click', '.remove-row', function() {
                $(this).closest('.classification-row').remove();
            });

            $('.classified-search').on('keyup', function() {
                var value = $(this).val().toLowerCase();

                $('#classifieditems tr').hide();

                let matchedGroups = new Set();

                $('#classifieditems tr').each(function() {
                    if ($(this).text().toLowerCase().indexOf(value) > -1) {
                        matchedGroups.add($(this).data('group'));
                    }
                });
                $('#classifieditems tr').each(function() {
                    if (matchedGroups.has($(this).data('group'))) {
                        $(this).show();
                    }
                });
                if (value === '') {
                    $('#classifieditems tr').show();
                }
            });

            ////////////////////////////

            // $(document).on('click', '.editEnrollmentSetup', function() {
            //     let enrollmentSetup_academicId = $(this).data('id');
            //     $('#editaccountsetupModal').modal('show');
            //     $.ajax({
            //         type: 'GET',
            //         url: '/bookkeeper/enrollement_Setup/edit_enrollment_setup',
            //         data: {
            //             enrollmentSetup_academicId: enrollmentSetup_academicId
            //         },
            //         success: function(item) {
            //             // $('#fiscalYearDescription').val(item.description);
            //             // $('#startDateFiscal').val(item.stime.split(' ')[0]);
            //             // $('#endDateFiscal').val(item.etime.split(' ')[0]);
            //             // $('#isActive').prop('checked', item.isactive == 1);
            //             // $('#fiscalYearId').val(item.id);

            //             // $('#updatedfiscalyear').attr('data-id', item.id);

            //         },
            //         error: function(xhr) {
            //             console.error(xhr.responseText);
            //         }
            //     });

            // });

            ////////////////////////
            // $(document).on('click', '.editEnrollmentSetup', function() {
            //     let enrollmentSetup_academicId = $(this).data('id');
            //     $('#editaccountsetupModal').modal('show');

            //     $.ajax({
            //         type: 'GET',
            //         url: '/bookkeeper/enrollement_Setup/edit_enrollment_setup',
            //         data: {
            //             enrollmentSetup_academicId: enrollmentSetup_academicId
            //         },
            //         success: function(response) {
            //             console.log('API Response:', response);

            //             // Clear previous rows
            //             $('#classification-rows-container').empty();

            //             // Set academic program
            //             $('#academic_program').val(enrollmentSetup_academicId);

            //             // Store data in modal for later use
            //             $('#editaccountsetupModal').data('coa', response.chart_of_accounts);
            //             $('#editaccountsetupModal').data('itemclassification', response
            //                 .itemclassification);

            //             // Process classifications
            //             if (response.classified && response.classified.length) {
            //                 response.classified.forEach(function(item) {
            //                     addClassificationRow(item);
            //                 });

            //                 // Set cashier debit account from first item
            //                 if (response.classified[0].cashier_debitacc) {
            //                     const cashierAccount = response.chart_of_accounts.find(acc =>
            //                         acc.account_name === response.classified[0]
            //                         .cashier_debitacc
            //                     );
            //                     if (cashierAccount) {
            //                         $('#edit_cashier_debit_account').val(cashierAccount.id);
            //                     }
            //                 }
            //             }
            //         },
            //         error: function(xhr) {
            //             console.error('Error:', xhr.responseText);
            //             alert('Failed to load enrollment setup data');
            //         }
            //     });
            // });

            // function addClassificationRow(item) {
            //     const coa = $('#editaccountsetupModal').data('coa') || [];
            //     const itemClassification = $('#editaccountsetupModal').data('itemclassification') || [];

            //     const rowId = 'row-' + Date.now();

            //     // Create classification select
            //     let classificationSelect =
            //         '<select class="form-control classification-select" style="height: 30px; font-size: 12px; border-radius: 8px; border:none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);"><option value="">Select Classification</option>';
            //     itemClassification.forEach(ic => {
            //         const selected = ic.id == item.classid ? 'selected' : '';
            //         classificationSelect +=
            //             `<option value="${ic.id}" ${selected}>${ic.description}</option>`;
            //     });
            //     classificationSelect += '</select>';

            //     // Create debit account select
            //     let debitSelect =
            //         '<select class="form-control debit-account-select" style="height: 30px; font-size: 12px; border-radius: 8px; border:none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);"><option value="">Select Debit Account</option>';
            //     coa.forEach(account => {
            //         const selected = account.account_name === item.debitacc ? 'selected' : '';
            //         debitSelect +=
            //             `<option value="${account.id}" ${selected}>${account.code} - ${account.account_name}</option>`;
            //     });
            //     debitSelect += '</select>';

            //     // Create credit account select
            //     let creditSelect =
            //         '<select class="form-control credit-account-select" style="height: 30px; font-size: 12px; border-radius: 8px; border:none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);"><option value="">Select Credit Account</option>';
            //     coa.forEach(account => {
            //         const selected = account.account_name === item.creditacc ? 'selected' : '';
            //         creditSelect +=
            //             `<option value="${account.id}" ${selected}>${account.code} - ${account.account_name}</option>`;
            //     });
            //     creditSelect += '</select>';

            //     const rowHtml = `
        //         <div class="card mb-3 classification-row" id="${rowId}" style="border:none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
        //             <div class="card-body">
        //                 <div class="row">
        //                     <div class="col-md-3">
        //                         <label style="font-size: 13px; color: #333; font-weight: 600;">Classification</label>
        //                         ${classificationSelect}
        //                     </div>
        //                     <div class="col-md-4">
        //                         <label style="font-size: 13px; color: #333; font-weight: 600;">Debit Account</label>
        //                         ${debitSelect}
        //                     </div>
        //                     <div class="col-md-4">
        //                         <label style="font-size: 13px; color: #333; font-weight: 600;">Credit Account</label>
        //                         ${creditSelect}
        //                     </div>
        //                     <div class="col-md-1 d-flex align-items-end">
        //                         <button type="button" class="btn btn-danger btn-sm remove-classification-btn" style="height: 30px;">
        //                             <i class="fas fa-trash"></i>
        //                         </button>
        //                     </div>
        //                 </div>
        //             </div>
        //         </div>
        //     `;

            //     $('#classification-rows-container').append(rowHtml);
            // }
            /////////////////////////////////

            $(document).on('click', '#edit_closeModal', function() {

                if (isFormDirty) {

                    Swal.fire({
                        title: 'Unsaved Changes',
                        text: 'You have unsaved changes. Do you want to discard them?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Discard',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.value) {
                            isFormDirty = false;

                            setTimeout(() => {
                                $('#editaccountsetupModal').modal('hide');
                                $('body').removeClass('modal-open');
                                $('.modal-backdrop')
                                    .remove(); // Manually removes lingering backdrop 
                                // $('.modal-backdrop').removeClass('show');
                                // $('.modal-backdrop').addClass('hide');
                            }, 100);
                        }
                        // Else: do nothing (modal stays open with data intact)
                    });
                } else {
                    $('#editaccountsetupModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop')
                        .remove(); // Manually removes lingering backdrop 
                }
            });

            $(document).on('click', '#updateclassified', function() {

                let classifications = [];

                $('.classification-row').each(function(index) {

                    var classid = $(this).closest('.classification-row').attr('id');
                    var class_levelid = $(this).closest('.classification-row').attr('levelid');
                    var class_acadprogid = $(this).closest('.classification-row').attr(
                        'acadprogid');
                    var classacc = $(this).find('select.form-control.classification-select').val();
                    var debitacc = $(this).find('select.form-control.debit-account-select').val();
                    var creditacc = $(this).find('select.form-control.credit-account-select').val();


                    // Create an object for each emergency contact
                    let classification = {
                        classified_setupid: classid,
                        classified_setup_levelid: class_levelid,
                        classified_setup_acadprogid: class_acadprogid,
                        classacc: classacc,
                        debitacc: debitacc,
                        creditacc: creditacc
                    };

                    // Push emergency contact object to the array
                    classifications.push(classification);

                });

                var formData = new FormData();
                var acadprogid = $('.classification-row').attr('acadprogid');
                if (acadprogid) {
                    formData.append('acadprogid', acadprogid);
                }

                var cashierDebitAcc = $('#edit_cashier_debit_account').val();
                if (cashierDebitAcc) {
                    formData.append('cashier_debit_account', cashierDebitAcc);
                }

                // else {
                //     var acadprogids = [];
                //     $('.classification-row').each(function(index){
                //         var acadprogid = $(this).attr('acadprogid');
                //         acadprogids.push(acadprogid);
                //     });
                //     formData.append('acadprogids', JSON.stringify(acadprogids));
                // }


                classifications.forEach(function(classification, index) {
                    formData.append(`classifications[${index}][classified_setupid]`, classification
                        .classified_setupid);
                    formData.append(`classifications[${index}][classified_setup_levelid]`,
                        classification
                        .classified_setup_levelid);
                    formData.append(`classifications[${index}][classified_setup_acadprogid]`,
                        classification
                        .classified_setup_acadprogid);
                    formData.append(`classifications[${index}][classacc]`, classification
                        .classacc);
                    formData.append(`classifications[${index}][debitacc]`, classification
                        .debitacc);
                    formData.append(`classifications[${index}][creditacc]`, classification
                        .creditacc);
                });


                $.ajax({
                    type: 'POST',
                    url: '/bookkeeper/enrollement_Setup/edit_enrollment_setup_update',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data.status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data.message
                            });
                        } else if (data.status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully Updated'
                            });
                            $('#editaccountsetupModal').modal('hide');
                            $('.modal-backdrop').removeClass('show');
                            $('.modal-backdrop').addClass('hide');
                            fetchEnrollmentSetup();



                        } else {
                            Toast.fire({
                                type: 'warning',
                                title: 'Please complete all fields or verify your input'
                            });
                        }
                    }
                });



            });
            
           

            // function addClassificationRow_edit() {
            //     var count = 0;

            //     $('#edit-add-classification-btn').click(function() {
            //         count++;

            //         var row = `
            //                 <div class="form-row mb-3 classification-row" data-id="${count}" id="0" >
            //                     <div class="form-group col-md-4">
            //                         <label style="font-weight: 600; font-size: 13px;">Classification</label>
            //                         <select class="form-control select2 classification-select"
            //                             style="width: 90%; height: 30px; font-size: 12px; border-radius: 10px; border: none;
            //                                 box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
            //                             <option value="">Select Classification</option>
            //                             ${classifications.map(item => `<option value="${item.id}">${item.description}</option>`).join('')}
            //                         </select>
            //                     </div>
            //                     <div class="form-group col-md-4">
            //                         <label style="font-weight: 600; font-size: 13px;">Debit Account</label>
            //                         <select class="form-control select2 debit-account-select"
            //                             style="width: 90%; height: 30px; font-size: 12px; border-radius: 10px; border: none;
            //                                 box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
            //                             <option value="">Select Debit</option>
            //                             @foreach (DB::table('chart_of_accounts')->get() as $coa)
            //                                 <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
            //                                 @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->get() as $subcoa)
            //                                     <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $subcoa->sub_code }} -
            //                                         {{ $subcoa->sub_account_name }}</option>
            //                                 @endforeach
            //                             @endforeach
            //                         </select>
            //                     </div>
            //                     <div class="form-group col-md-3">
            //                         <label style="font-weight: 600; font-size: 13px;">Credit Account</label>
            //                         <select class="form-control select2 credit-account-select"
            //                             style="width: 100%; height: 30px; font-size: 12px; border-radius: 10px; border: none;
            //                                 box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
            //                             <option value="">Select Credit</option>
            //                             @foreach (DB::table('chart_of_accounts')->get() as $coa)
            //                                 <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
            //                                 @foreach (DB::table('bk_sub_chart_of_accounts')->where('coaid', $coa->id)->get() as $subcoa)
            //                                     <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $subcoa->sub_code }} -
            //                                         {{ $subcoa->sub_account_name }}</option>
            //                                 @endforeach
            //                             @endforeach
            //                         </select>
            //                     </div>
            //                     <div class="form-group col-md-1 d-flex align-items-end">
            //                         <button type="button" class="btn btn-danger btn-sm remove-row" style="border-radius: 50%; height: 30px;">
            //                             <i class="fas fa-times"></i>
            //                         </button>
            //                     </div>
            //                 </div>
            //             `;
            //         $('#classification-rows-container-edit').append(row);
            //         $('.debit-account-select').select2({
            //             placeholder: "Select Debit Account",
            //             allowClear: true,
            //             theme: 'bootstrap4'
            //         });
            //         $('.credit-account-select').select2({
            //             placeholder: "Select Credit Account",
            //             allowClear: true,
            //             theme: 'bootstrap4'
            //         });
            //     });
            // }

            // addClassificationRow_edit();

            $(document).on('click', '.remove-classification-btn-edit', function() {
                var classified_setupid = $(this).attr('data-classid');
                var classified_acadprogid = $(this).attr('data-acadprogid');
                var classified_levelid = $(this).attr('data-levelid');

                Swal.fire({
                    title: 'Are you sure you want to remove this classification?',
                    type: 'warning',
                    confirmButtonColor: '#dc3545c9',
                    confirmButtonText: 'Delete',
                    showCancelButton: true,
                    allowOutsideClick: true
                }).then((confirm) => {
                    if (confirm.value) {
                        $.ajax({
                            type: "GET",
                            url: "/bookkeeper/enrollement_Setup/delete",
                            data: {
                                classified_setupid: classified_setupid,
                                classified_acadprogid: classified_acadprogid,
                                classified_levelid: classified_levelid
                            },
                            success: function(data) {
                                if (data[0].status == 0) {
                                    Toast.fire({
                                        type: 'error',
                                        title: data[0].message
                                    });
                                } else {
                                    fetchEnrollmentSetup();
                                    // getemployees()
                                    // getinactiveemployees()
                                    Toast.fire({
                                        type: 'success',
                                        title: data[0].message
                                    });
                                    // $('.editEnrollmentSetup').click();
                                    $('#editaccountsetupModal').modal('hide');
                                    // setTimeout(() => {
                                    //     $('#editaccountsetupModal').modal(
                                    //         'show');
                                    // }, 500);
                                }
                            }
                        });
                    }
                })
            })

            $('#perclassification').change(function() {
                if ($(this).is(':checked')) {
                    $('#add-classification-btn').show();
                    $('#add-account-btn').hide();
                } else {
                    $('#add-classification-btn').hide();
                    $('#add-account-btn').show();
                }
            });


            function add_noClassificationRow() {
                var count = 0;

                $('#add-account-btn').click(function() {
                    count++;

                    var row = `
                            <div class="form-row mb-3 classification-row" data-id="${count}">
                               
                                <div class="form-group col-md-4">
                                    <label style="font-weight: 600; font-size: 13px;">Debit Account</label>
                                    <select class="form-control select2 debit-account-select"
                                        style="width: 90%; height: 30px; font-size: 12px; border-radius: 10px; border: none;
                                            box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                                        <option value="">Select Debit</option>
                                        @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                            <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
                                            @foreach (DB::table('bk_sub_chart_of_accounts')->where('deleted', 0)->where('coaid', $coa->id)->get() as $subcoa)
                                                <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    {{ $subcoa->sub_code }} - {{ $subcoa->sub_account_name }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label style="font-weight: 600; font-size: 13px;">Credit Account</label>
                                    <select class="form-control select2 credit-account-select"
                                        style="width: 100%; height: 30px; font-size: 12px; border-radius: 10px; border: none;
                                            box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                                        <option value="">Select Credit</option>
                                        @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                            <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
                                            @foreach (DB::table('bk_sub_chart_of_accounts')->where('deleted', 0)->where('coaid', $coa->id)->get() as $subcoa)
                                                <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    {{ $subcoa->sub_code }} - {{ $subcoa->sub_account_name }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-row" style="border-radius: 50%; height: 30px;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                    $('#classification-rows-container').append(row);
                    $('.debit-account-select').select2({
                        placeholder: "Select Debit Account",
                        allowClear: true,
                        theme: 'bootstrap4'
                    });
                    $('.credit-account-select').select2({
                        placeholder: "Select Credit Account",
                        allowClear: true,
                        theme: 'bootstrap4'
                    });
                });
            }

            add_noClassificationRow();




            // $(document).on('change', '#class_group', function() {
            //     if ($(this).val() !== '') {
            //         $('#class_itemized').prop('disabled', false);
            //     } else {
            //         $('#class_itemized').prop('disabled', true);
            //     }
            // });

            // $(document).on('change', '#classification', function() {
            //     if ($(this).val() === 'new') {
            //         $('#class_description').val('');
            //         $('#class_account').val('0').trigger('change');
            //         $('#class_group').val('').trigger('change');
            //         $('#class_itemized').prop('checked', false);
            //         $('#class_code').val('0').trigger('change');
            //         $('#class_save').attr('data-id', 0);
            //         $('#modal-classification').modal('show');
            //     }
            // });

            // $(document).on('click', '#class_save', function() {
            //     var description = $('#class_description').val();
            //     var account = $('#class_account').val();
            //     var group = $('#class_group').val();
            //     var classcode = $('#class_code').val();
            //     var itemized = $('#class_itemized').prop('checked') ? 1 : 0;

            //     $.ajax({
            //         type: 'POST',
            //         url: '/bookkeeper/add-classification',
            //         data: {
            //             description: description,
            //             account: account,
            //             group: group,
            //             itemized: itemized,
            //             classcode: classcode
            //         },
            //         success: function(data) {
            //             const Toast = Swal.mixin({
            //                 toast: true,
            //                 position: 'top-end',
            //                 showConfirmButton: false,
            //                 timer: 3000,
            //                 timerProgressBar: true,
            //                 didOpen: (toast) => {
            //                     toast.addEventListener('mouseenter', Swal
            //                         .stopTimer);
            //                     toast.addEventListener('mouseleave', Swal
            //                         .resumeTimer);
            //                 }
            //             });

            //             if (data.status === 'done') {
            //                 Toast.fire({
            //                     icon: 'success',
            //                     title: 'Item classification saved successfully'
            //                 });

            //                 $('#modal-classification').modal('hide');

            //                 // Refresh the Select2 options
            //                 genclassid(function() {
            //                     // After repopulating, select the newly added item
            //                     $('#classification').val(data.id).trigger('change');
            //                 });

            //             } else if (data.status === 'exists') {
            //                 Toast.fire({
            //                     icon: 'error',
            //                     title: 'Item classification already exists'
            //                 });
            //             }
            //         }

            //     });
            // });

            // function genclassid(callback) {
            //     $.ajax({
            //         type: 'GET',
            //         url: '/bookkeeper/classification-list',
            //         success: function(data) {
            //             let options = '<option value="">Select Classification</option>';
            //             data.forEach(function(item) {
            //                 options += `<option value="${item.id}">${item.description}</option>`;
            //             });
            //             $('#classification').html(options).trigger('change');

            //             if (typeof callback === 'function') callback();
            //         }
            //     });
            // }



        })
    </script>
@endsection
