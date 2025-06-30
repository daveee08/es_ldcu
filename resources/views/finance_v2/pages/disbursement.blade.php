@extends('finance_v2.layouts.app2')
@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <style>
        .subj_tr {
            vertical-align: middle !important;
            cursor: pointer;
        }

        .stud_subj_tr {
            vertical-align: middle !important;
            cursor: pointer;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }
    </style>
@endsection
@section('content')
    @php
        $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
        $semester = DB::table('semester')->get();
    @endphp

    <section class="content-header">
        <div class="container-fluid">
            <div class="mb-2">
                <div class="">
                    <h1><i class="fa fa-cog"></i> Disbursement</h1>
                </div>
                <div class="ml-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active font-weight-bold">Disbursement</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        {{-- <div class="card-header">
                           
                        </div> --}}
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-filter fa-lg px-1"></i>
                                <h6 class="m-0" style="font-size: 1.5rem;">Filter</h6>
                            </div>
                            <br>
                            <div class="row">


                                <div class="col-md-2">
                                    <label for="" class="mb-1">School Year</label>
                                    <select name="filter_sy" id="sy" class="form-control form-control-sm select2">
                                        @foreach ($sy as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $item->isactive == 1 ? 'selected="selected"' : '' }}>
                                                {{ $item->sydesc }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="" class="mb-1">Semester</label>
                                    <select name="filter_semester" id="sem"
                                        class="form-control form-control-sm select2">
                                        @foreach ($semester as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $item->isactive == 1 ? 'selected="selected"' : '' }}>
                                                {{ $item->semester }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label for="" class="mb-1">Academic Program</label>
                                    <select id="academicLevel" class="form-control form-control-sm select2">
                                        <option>Grade 7</option>
                                        <option>Grade 8</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label for="" class="mb-1">Grade Level</label>
                                    <select class="form-control " id="filter_gradelevel">
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label for="academicLevel" class="form-label me-2" style="font-size: 1rem;">Date
                                        Range</label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" class="form-control float-right" id="reservation"
                                            name="reservation" placeholder="Select Date Range">
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        <div class="card-header">
                            Disbursement
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-between">

                                    <button class="btn btn-success" data-toggle="modal" data-target="#AddBookForm">
                                        <i class="fas fa-money-bill-wave mr-2"></i> Add Voucher
                                    </button>
                                    <button class="btn btn-primary" id="btnPrintLedger"><i class="fas fa-print mr-2"></i>
                                        Print</button>
                                </div>
                            </div>
                            <br>
                            <div class="table">
                                <table id="voucher_Table" class="table table-bordered table-sm w-100">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th class="text-left">Date</th>
                                            <th class="text-left">Voucher No.</th>
                                            <th class="text-left">Name</th>
                                            <th class="text-left">Disbursement Type</th>
                                            <th class="text-left">Payment Type</th>
                                            <th class="text-left">Amount</th>
                                            <th class="text-center" style="width: 5rem;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="book_list">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- <div class="modal fade" id="AddBookForm" tabindex="-1" aria-labelledby="duplicateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered super-wide-modal">
            <div class="modal-content overflow-hidden">
                <div class="modal-header" style="background-color:#d9d9d9;">
                    <h5 class="modal-title" id="exampleModalLongTitle">Disbursement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div> --}}
    <div class="modal fade" id="AddBookForm" tabindex="-1" aria-labelledby="duplicateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered super-wide-modal">
            <div class="modal-content overflow-hidden">
                <div class="modal-header" style="background-color:#d9d9d9;">
                    <h5 class="modal-title">Disbursement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="checked_expenses">
                    <form>
                        <div class="row mb-3">
                            <div class="d-flex justify-content-between w-100">
                                <div class="col-md-7 d-flex align-items-center shadow">
                                    <label class="mr-2">Disbursement Type:</label>
                                    <div style="margin-left:10%;">
                                        <input type="checkbox" id="checked_expenses_expenses" checked> <label
                                            for="expenses">Expenses</label>
                                        <input type="checkbox" id="checked_expenses_refund" style="margin-left:10px;">
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
                                <input type="text" id="expenses_voucher_no" class="form-control" value="CV/CHV 0000">
                            </div>
                            <div class="col-md-3">
                                <label>Reference No.</label>
                                <input type="text" class="form-control" id="expenses_ref_no" value="PO 0000">
                            </div>
                            <div class="col-md-3">
                                <label>Disburse To</label>
                                <select class="form-control" id="expenses_disbursement_to">
                                    <option value="">Select Employee/Supplier Name</option>
                                    <option value="Richard Popera">Richard Popera</option>
                                    <option value="Clydev Bacons">Clydev Bacons</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Company/Department</label>
                                <select class="form-control" id="expenses_departmewnt">
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
                                    <input type="radio" name="payment" id="expenses_cash" checked> <label
                                        for="cash">CASH</label>
                                    <input type="radio" name="payment" id="expenses_cheque" style="margin-left:10px;">
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
                            <h5 class="m-0">ITEMS</h5>
                            <button type="button" style="background-color: transparent; border-color: transparent;"
                                id="add_item"><i class="fas fa-plus-circle text-success"></i></button>
                        </div>
                        <table class="table table-bordered mt-2" id="items_table">
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
                                    <td colspan="2" id="grand_total"></td>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="text-right">
                            <button type="button" class="btn btn-success" id="expenses_save">Save</button>
                            <button type="button" class="btn btn-dark" id="expenses_print">Print</button>
                        </div>
                    </form>
                </div>

                <div class="modal-body" id="checked_students_refund" style="display:none;">
                    <form>
                        <div class="row mb-3">
                            <div class="d-flex justify-content-between w-100">
                                <div class="col-md-7 d-flex align-items-center shadow">
                                    <label class="mr-2">Disbursement Type:</label>
                                    <div style="margin-left:10%;">
                                        <input type="checkbox" id="checked_students_refund_expenses"> <label
                                            for="expenses">Expenses</label>
                                        <input type="checkbox" id="checked_students_refund_refund"
                                            style="margin-left:10px;" checked> <label for="refund">Students
                                            Refund</label>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-center">
                                    <label class="mr-2">Date:</label>
                                    <input type="date" id="checked_students_refund_date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label>Voucher No.</label>
                                <input type="text" id="checked_students_refund_voucher" class="form-control"
                                    value="CV/CHV 0000">
                            </div>
                            <div class="col-md-3">
                                <label>Student No.</label>
                                <input type="text" id="checked_students_refund_stud_no" class="form-control"
                                    value="PO 0000">
                            </div>
                            <div class="col-md-3">
                                <label>Refund To</label>
                                <select class="form-control" id="checked_students_refund_to">
                                    <option>Select Student Name</option>
                                    <option value="Richard Popera">Richard Popera</option>
                                    <option value="Clydev Bacons">Clydev Bacons</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Grade Level</label>
                                <select class="form-control" id="filter_gradelevel">
                                    <option value="1">Grade 7</option>
                                    <option value="2">Grade 8</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 d-flex align-items-center shadow">
                            <label class="mr-2">Reimbursement Type:</label>
                            <div style="margin-left:3%;">
                                <input type="checkbox" id="cash_reimbursement" checked> <label for="expenses">Cash/Check
                                    Reimbursement</label>
                                <input type="checkbox" id="forward_next_sy" class="ml-4"> <label
                                    for="expenses">Forward to
                                    Next School
                                    year</label>
                                <input type="checkbox" id="forward_siblings" class="ml-4"> <label
                                    for="expenses">Forward to
                                    Siblings/Others payable</label>
                            </div>
                        </div>
                        <br>

                        <div class="row mb-3" id="reimburse_cash_div">
                            <div class="col-md-2">
                                <label>Payment Type:</label>
                                <div>
                                    <input type="radio" name="payment" id="checked_students_refund_cash" checked>
                                    <label for="cash">CASH</label>
                                    <input type="radio" name="payment" id="checked_students_refund_cheque"
                                        style="margin-left:10px;">
                                    <label for="cheque">CHEQUE</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>Amount</label>
                                <input type="text" class="form-control" id="students_refund_cheque_amount"
                                    value="PHP 0.00">
                            </div>
                            <div class="col-md-2">
                                <label>Bank</label>
                                <select class="form-control" id="checked_students_refund_cheque_bank">
                                    <option>Select Bank</option>
                                    <option value="Union Bank">Union Bank</option>
                                    <option value="BPI">BPI</option>
                                    <option value="DBP">DBP</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Cheque No.</label>
                                <input type="text" class="form-control" id="checked_students_refund_cheque_no"
                                    placeholder="Select Bank">
                            </div>
                            <div class="col-md-3">
                                <label>Cheque Date.</label>
                                <input type="date" class="form-control" id="checked_students_refund_cheque_date">
                            </div>
                        </div>

                        <div class="row mb-3" id="forward_sy_div" style="display:none;">
                            <div class="col-md-2">
                                <label>Amount</label>
                                <input type="text" id="forward_sy_amount" class="form-control" value="PHP 0.00">
                            </div>
                            <div class="col-md-2">
                                <label>School Year</label>
                                <select class="form-control" id="forward_sy_sy">
                                    <option>School Year</option>
                                    <option value="1">2025-2026</option>
                                    <option value="2">2026-2027</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Semester</label>
                                <select class="form-control" id="forward_sy_sem">
                                    <option value="1">1st Semester</option>
                                    <option value="2">2nd Semester</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Apply To</label>
                                <select class="form-control" id="forward_sy_apply_to">
                                    <option value="1">Tuition</option>
                                    <option value="2">Others</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3" id="forward_siblings_div" style="display:none;">
                            <div class="col-md-3">
                                <label>Student Name</label>
                                <select class="form-control" id="forward_siblings_student">
                                    <option>Select Name</option>
                                    <option value="1">Richard Popera</option>
                                    <option value="2">Clydev Bacons</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Amount</label>
                                <input type="text" id="forward_siblings_amount" class="form-control"
                                    value="PHP 0.00">
                            </div>
                            <div class="col-md-2">
                                <label>School Year</label>
                                <select class="form-control" id="forward_siblings_sy">
                                    <option>School Year</option>
                                    <option value="1">2025-2026</option>
                                    <option value="2">2026-2027</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Semester</label>
                                <select class="form-control" id="forward_siblings_sem">
                                    <option value="1">1st Semester</option>
                                    <option value="2">2nd Semester</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Apply to</label>
                                <select class="form-control" id="forward_siblings_apply_to">
                                    <option value="1">Tuition</option>
                                    <option value="2">Others</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            {{-- <div class="col-md-6">
                                <label>Cheque Date:</label>
                                <input type="date" class="form-control">
                            </div> --}}
                            <div class="col-md-12">
                                <label>Remarks/Explanation</label>
                                <input type="text" class="form-control" id="checked_students_refund_remarks"
                                    placeholder="Enter Description Here">
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <div class="text-right">
                            <button type="button" class="btn btn-success" id="students_refund_save">Save</button>
                            <button type="button" class="btn btn-dark ml-5" id="students_refund_print">Print</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- /////////////////////////////////////// edit disbursements ///////////////////////////////////// --}}

    <div class="modal fade" id="EditDisbursementForm" tabindex="-1" aria-labelledby="duplicateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered super-wide-modal">
            <div class="modal-content overflow-hidden">
                <div class="modal-header" style="background-color:#d9d9d9;">
                    <h5 class="modal-title">Disbursement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="edit_checked_expenses">
                    <form>
                        <div class="row mb-3">
                            <div class="d-flex justify-content-between w-100">
                                <div class="col-md-7 d-flex align-items-center shadow">
                                    <label class="mr-2">Disbursement Type:</label>
                                    <div style="margin-left:10%;">
                                        <input type="checkbox" id="edit_checked_expenses_expenses" checked> <label
                                            for="expenses">Expenses</label>
                                        <input type="checkbox" id="edit_checked_expenses_refund"
                                            style="margin-left:10px;">
                                        <label for="refund">Students
                                            Refund</label>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-center">
                                    <label class="mr-2">Date:</label>
                                    <input type="date" id="edit_expenses_date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="row mb-3">
                            <input type="text" id="expenses_id" hidden value="">
                            <div class="col-md-3">
                                <label>Voucher No.</label>
                                <input type="text" id="edit_expenses_voucher_no" class="form-control"
                                    value="CV/CHV 0000">
                            </div>
                            <div class="col-md-3">
                                <label>Reference No.</label>
                                <input type="text" class="form-control" id="edit_expenses_ref_no" value="PO 0000">
                            </div>
                            <div class="col-md-3">
                                <label>Disburse To</label>
                                <select class="form-control" id="edit_expenses_disbursement_to">
                                    <option value="">Select Employee/Supplier Name</option>
                                    <option value="Richard Popera">Richard Popera</option>
                                    <option value="Clydev Bacons">Clydev Bacons</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Company/Department</label>
                                <select class="form-control" id="edit_expenses_departmewnt">
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
                                    <input type="radio" name="payment" id="edit_expenses_cash" checked> <label
                                        for="cash">CASH</label>
                                    <input type="radio" name="payment" id="edit_expenses_cheque"
                                        style="margin-left:10px;">
                                    <label for="cheque">CHEQUE</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>Amount</label>
                                <input type="text" class="form-control" id="edit_expenses_amount" value="PHP 0.00">
                            </div>
                            <div class="col-md-2">
                                <label>Bank</label>
                                <select class="form-control" id="edit_expenses_bank">
                                    <option>Select Bank</option>
                                    <option value="Union Bank">Union Bank</option>
                                    <option value="BPI">BPI</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Cheque No.</label>
                                <input type="text" class="form-control" id="edit_expenses_cheque_no"
                                    placeholder="Select Bank">
                            </div>
                            <div class="col-md-3">
                                <label>Cheque Date.</label>
                                <input type="date" class="form-control" id="edit_expenses_cheque_date">
                            </div>
                        </div>
                        <div class="row mb-3">
                            {{-- <div class="col-md-6">
                                <label>Cheque Date:</label>
                                <input type="date" class="form-control">
                            </div> --}}
                            <div class="col-md-12">
                                <label>Remarks/Explanation</label>
                                <input type="text" class="form-control" id="edit_expenses_remarks"
                                    placeholder="Enter Description Here">
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <h5 class="m-0">ITEMS</h5>
                            <button type="button" style="background-color: transparent; border-color: transparent;"
                                id="selected_vouchers_add_item"><i class="fas fa-plus-circle text-success"></i></button>
                        </div>
                        <table class="table table-bordered mt-2" id="edit_items_table">
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
                                    <td colspan="3" id="edit_grand_total"></td>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="text-right">
                            <button type="button" class="btn btn-success" id="expenses_update">Save</button>
                            <button type="button" class="btn btn-dark" id="expenses_print">Print</button>
                        </div>
                    </form>
                </div>

                <div class="modal-body" id="edit_checked_students_refund" style="display:none;">
                    <form>
                        <div class="row mb-3">
                            <div class="d-flex justify-content-between w-100">
                                <div class="col-md-7 d-flex align-items-center shadow">
                                    <label class="mr-2">Disbursement Type:</label>
                                    <div style="margin-left:10%;">
                                        <input type="checkbox" id="edit_checked_students_refund_expenses"> <label
                                            for="expenses">Expenses</label>
                                        <input type="checkbox" id="edit_checked_students_refund_refund"
                                            style="margin-left:10px;" checked> <label for="refund">Students
                                            Refund</label>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-center">
                                    <label class="mr-2">Date:</label>
                                    <input type="date" id="checked_students_refund_date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="row mb-3">
                            <input type="text" id="students_refund_id" hidden value="">
                            <div class="col-md-3">
                                <label>Voucher No.</label>
                                <input type="text" id="checked_students_refund_voucher" class="form-control"
                                    value="CV/CHV 0000">
                            </div>
                            <div class="col-md-3">
                                <label>Student No.</label>
                                <input type="text" id="checked_students_refund_stud_no" class="form-control"
                                    value="PO 0000">
                            </div>
                            <div class="col-md-3">
                                <label>Refund To</label>
                                <select class="form-control" id="checked_students_refund_to">
                                    <option>Select Student Name</option>
                                    <option value="Richard Popera">Richard Popera</option>
                                    <option value="Clydev Bacons">Clydev Bacons</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Grade Level</label>
                                <select class="form-control" id="filter_gradelevel">
                                    <option value="1">Grade 7</option>
                                    <option value="2">Grade 8</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 d-flex align-items-center shadow">
                            <label class="mr-2">Reimbursement Type:</label>
                            <div style="margin-left:3%;">
                                <input type="checkbox" id="cash_reimbursement" checked> <label for="expenses">Cash/Check
                                    Reimbursement</label>
                                <input type="checkbox" id="forward_next_sy" class="ml-4"> <label
                                    for="expenses">Forward to
                                    Next School
                                    year</label>
                                <input type="checkbox" id="forward_siblings" class="ml-4"> <label
                                    for="expenses">Forward to
                                    Siblings/Others payable</label>
                            </div>
                        </div>
                        <br>

                        <div class="row mb-3" id="reimburse_cash_div">
                            <div class="col-md-2">
                                <label>Payment Type:</label>
                                <div>
                                    <input type="radio" name="payment" id="checked_students_refund_cash" checked>
                                    <label for="cash">CASH</label>
                                    <input type="radio" name="payment" id="checked_students_refund_cheque"
                                        style="margin-left:10px;">
                                    <label for="cheque">CHEQUE</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>Amount</label>
                                <input type="text" class="form-control" id="students_refund_cheque_amount"
                                    value="PHP 0.00">
                            </div>
                            <div class="col-md-2">
                                <label>Bank</label>
                                <select class="form-control" id="checked_students_refund_cheque_bank">
                                    <option>Select Bank</option>
                                    <option value="Union Bank">Union Bank</option>
                                    <option value="BPI">BPI</option>
                                    <option value="DBP">DBP</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Cheque No.</label>
                                <input type="text" class="form-control" id="checked_students_refund_cheque_no"
                                    placeholder="Select Bank">
                            </div>
                            <div class="col-md-3">
                                <label>Cheque Date.</label>
                                <input type="date" class="form-control" id="checked_students_refund_cheque_date">
                            </div>
                        </div>

                        <div class="row mb-3" id="forward_sy_div" style="display:none;">
                            <div class="col-md-2">
                                <label>Amount</label>
                                <input type="text" id="forward_sy_amount" class="form-control" value="PHP 0.00">
                            </div>
                            <div class="col-md-2">
                                <label>School Year</label>
                                <select class="form-control" id="forward_sy_sy">
                                    <option>School Year</option>
                                    <option value="1">2025-2026</option>
                                    <option value="2">2026-2027</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Semester</label>
                                <select class="form-control" id="forward_sy_sem">
                                    <option value="1">1st Semester</option>
                                    <option value="2">2nd Semester</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Apply To</label>
                                <select class="form-control" id="forward_sy_apply_to">
                                    <option value="1">Tuition</option>
                                    <option value="2">Others</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3" id="forward_siblings_div" style="display:none;">
                            <div class="col-md-3">
                                <label>Student Name</label>
                                <select class="form-control" id="forward_siblings_student">
                                    <option>Select Name</option>
                                    <option value="1">Richard Popera</option>
                                    <option value="2">Clydev Bacons</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Amount</label>
                                <input type="text" id="forward_siblings_amount" class="form-control"
                                    value="PHP 0.00">
                            </div>
                            <div class="col-md-2">
                                <label>School Year</label>
                                <select class="form-control" id="forward_siblings_sy">
                                    <option>School Year</option>
                                    <option value="1">2025-2026</option>
                                    <option value="2">2026-2027</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Semester</label>
                                <select class="form-control" id="forward_siblings_sem">
                                    <option value="1">1st Semester</option>
                                    <option value="2">2nd Semester</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Apply to</label>
                                <select class="form-control" id="forward_siblings_apply_to">
                                    <option value="1">Tuition</option>
                                    <option value="2">Others</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            {{-- <div class="col-md-6">
                                <label>Cheque Date:</label>
                                <input type="date" class="form-control">
                            </div> --}}
                            <div class="col-md-12">
                                <label>Remarks/Explanation</label>
                                <input type="text" class="form-control" id="checked_students_refund_remarks"
                                    placeholder="Enter Description Here">
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <div class="text-right">
                            <button type="button" class="btn btn-success" id="students_refund_save">Save</button>
                            <button type="button" class="btn btn-dark ml-5" id="students_refund_print">Print</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- /////////////// --}}

    <div class="modal fade" id="AdditemForm" tabindex="-1" aria-labelledby="duplicateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered super-wide-modal">
            <div class="modal-content overflow-hidden">
                <div class="modal-header" style="background-color:#d9d9d9;">
                    <p class="modal-title" id="exampleModalLongTitle">Add Item</p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">

                        <input type="text" class="form-control form-control-sm" id="booklistTypeId" hidden required>
                        <div class="form-group">
                            <label for="bookCode">Item Name</label>
                            <select class="form-control shadow-sm" id="disbursement_item">
                                <option selected value="1">Printer Ink</option>
                                <option selected value="2">Bond Paper</option>
                                <option selected value="3">Ballpen</option>
                            </select>
                        </div>
                        <div class="form-group mt-4">
                            <label for="bookName">Amount</label>
                            <input type="text" class="form-control shadow-sm" id="item_amount"
                                placeholder="Enter Amount">
                        </div>
                        <div class="form-group mt-4">
                            <label for="feesClassification">QTY</label>
                            <input type="text" class="form-control shadow-sm" id="item_quantity"
                                placeholder="Enter QTY">
                        </div>
                        <div class="form-group mt-4">
                            <label for="amount">Total Amount</label>
                            <input type="number" class="form-control shadow-sm" id="item_total" placeholder="0.00"
                                readonly>
                        </div>
                        <div class="text-center mt-4">
                            <button class="btn btn-primary" id="add_item_btn">Save</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="EdititemForm" tabindex="-1" aria-labelledby="duplicateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered super-wide-modal">
            <div class="modal-content overflow-hidden">
                <div class="modal-header" style="background-color:#d9d9d9;">
                    <p class="modal-title" id="exampleModalLongTitle">Edit Item</p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">

                        <input type="text" class="form-control form-control-sm" id="booklistTypeId" hidden required>
                        <div class="form-group">
                            <label for="bookCode">Item Name</label>
                            <select class="form-control shadow-sm" id="edit_disbursement_item">
                                <option selected value="1">Printer Ink</option>
                                <option selected value="2">Bond Paper</option>
                                <option selected value="3">Ballpen</option>
                            </select>
                        </div>
                        <div class="form-group mt-4">
                            <label for="bookName">Amount</label>
                            <input type="text" class="form-control shadow-sm" id="edit_item_amount"
                                placeholder="Enter Amount">
                        </div>
                        <div class="form-group mt-4">
                            <label for="feesClassification">QTY</label>
                            <input type="text" class="form-control shadow-sm" id="edit_item_quantity"
                                placeholder="Enter QTY">
                        </div>
                        <div class="form-group mt-4">
                            <label for="amount">Total Amount</label>
                            <input type="number" class="form-control shadow-sm" id="edit_item_total" placeholder="0.00"
                                readonly>
                        </div>
                        <div class="text-center mt-4">
                            <button class="btn btn-primary" id="update_selected_item_btn">Save</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ///////////////////// for selected vouchers ////////////////////// --}}
    <div class="modal fade" id="Selected_Vouchers_AdditemForm" tabindex="-1" aria-labelledby="duplicateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered super-wide-modal">
            <div class="modal-content overflow-hidden">
                <div class="modal-header" style="background-color:#d9d9d9;">
                    <p class="modal-title" id="exampleModalLongTitle">Add Item</p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">

                        <input type="text" class="form-control form-control-sm" id="booklistTypeId" hidden required>
                        <div class="form-group">
                            <label for="bookCode">Item Name</label>
                            <select class="form-control shadow-sm" id="selected_new_disbursement_item">
                                <option selected value="1">Printer Ink</option>
                                <option selected value="2">Bond Paper</option>
                                <option selected value="3">Ballpen</option>
                            </select>
                        </div>
                        <div class="form-group mt-4">
                            <label for="bookName">Amount</label>
                            <input type="text" class="form-control shadow-sm" id="selected_new_item_amount"
                                placeholder="Enter Amount">
                        </div>
                        <div class="form-group mt-4">
                            <label for="feesClassification">QTY</label>
                            <input type="text" class="form-control shadow-sm" id="selected_new_item_quantity"
                                placeholder="Enter QTY">
                        </div>
                        <div class="form-group mt-4">
                            <label for="amount">Total Amount</label>
                            <input type="number" class="form-control shadow-sm" id="selected_new_item_total"
                                placeholder="0.00" readonly>
                        </div>
                        <div class="text-center mt-4">
                            <button class="btn btn-primary" id="selected_new_add_item_btn">Save</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="Selected_Vouchers_EdititemForm" tabindex="-1" aria-labelledby="duplicateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered super-wide-modal">
            <div class="modal-content overflow-hidden">
                <div class="modal-header" style="background-color:#d9d9d9;">
                    <p class="modal-title" id="exampleModalLongTitle">Edit Item</p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">

                        <input type="text" class="form-control form-control-sm" id="booklistTypeId" hidden required>
                        <div class="form-group">
                            <label for="bookCode">Item Name</label>
                            <select class="form-control shadow-sm" id="edit_selected_new_disbursement_item">
                                <option selected value="1">Printer Ink</option>
                                <option selected value="2">Bond Paper</option>
                                <option selected value="3">Ballpen</option>
                            </select>
                        </div>
                        <div class="form-group mt-4">
                            <label for="bookName">Amount</label>
                            <input type="text" class="form-control shadow-sm" id="edit_selected_new_item_amount"
                                placeholder="Enter Amount">
                        </div>
                        <div class="form-group mt-4">
                            <label for="feesClassification">QTY</label>
                            <input type="text" class="form-control shadow-sm" id="edit_selected_new_item_quantity"
                                placeholder="Enter QTY">
                        </div>
                        <div class="form-group mt-4">
                            <label for="amount">Total Amount</label>
                            <input type="number" class="form-control shadow-sm" id="edit_selected_new_item_total"
                                placeholder="0.00" readonly>
                        </div>
                        <div class="text-center mt-4">
                            <button class="btn btn-primary" id="update_selected_new_selected_item_btn">Save</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="Selected_Vouchers_EdititemForm_Notappend" tabindex="-1"
        aria-labelledby="duplicateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered super-wide-modal">
            <div class="modal-content overflow-hidden">
                <div class="modal-header" style="background-color:#d9d9d9;">
                    <p class="modal-title" id="exampleModalLongTitle">Edit Item</p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">

                        <input type="text" class="form-control form-control-sm" id="booklistTypeId" hidden required>
                        <div class="form-group">
                            <label for="bookCode">Item Name</label>
                            <select class="form-control shadow-sm" id="edit_selected_new_disbursement_item_notappend">
                                <option selected value="1">Printer Ink</option>
                                <option selected value="2">Bond Paper</option>
                                <option selected value="3">Ballpen</option>
                            </select>
                        </div>
                        <div class="form-group mt-4">
                            <label for="bookName">Amount</label>
                            <input type="text" class="form-control shadow-sm"
                                id="edit_selected_new_item_amount_notappend" placeholder="Enter Amount">
                        </div>
                        <div class="form-group mt-4">
                            <label for="feesClassification">QTY</label>
                            <input type="text" class="form-control shadow-sm"
                                id="edit_selected_new_item_quantity_notappend" placeholder="Enter QTY">
                        </div>
                        <div class="form-group mt-4">
                            <label for="amount">Total Amount</label>
                            <input type="number" class="form-control shadow-sm"
                                id="edit_selected_new_item_total_notappend" placeholder="0.00" readonly>
                        </div>
                        <div class="text-center mt-4">
                            <button class="btn btn-primary"
                                id="update_selected_new_selected_item_btn_notappend">Save</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footerjavascript')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })

            $("#filter_departmewnt").select2();
            $("#filter_disbursement_to").select2();

            $("#disbursement_item").select2();
            $("#sy").select2();
            $("#sem").select2();
            $("#academicLevel").select2();
            get_gradelvl()

            function get_gradelvl() {

                $('#no_acad_holder').attr('hidden', 'hidden')

                $.ajax({
                    type: 'GET',
                    url: '/student/preregistration/getgradelevel',
                    data: {
                        syid: $('#filter_sy').val()
                    },
                    success: function(data) {
                        if (data.length > 0) {
                            gradelevel = data
                            $("#filter_gradelevel").empty();
                            $('#filter_gradelevel').append('<option value="">All</option>')
                            $("#filter_gradelevel").select2({
                                data: gradelevel,
                                allowClear: true,
                                placeholder: "All",
                                dropdownCssClass: "myFont"
                            })
                        } else {
                            $("#filter_gradelevel").empty();
                            $("#filter_gradelevel").empty();
                            $('#filter_gradelevel').append('<option value="">All</option>')
                            $("#filter_gradelevel").select2({
                                data: [],
                                allowClear: true,
                                placeholder: "All",
                                dropdownCssClass: "myFont"
                            })
                            $('#no_acad_holder').removeAttr('hidden')
                            Toast.fire({
                                type: 'error',
                                title: 'No academic program assigned'
                            })
                        }
                    },
                    error: function() {
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                })
            }

            $(function() {
                $('#reservation').daterangepicker({
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                });
            });


            $('#checked_expenses_expenses').change(function() {
                if ($(this).is(':checked')) {
                    $('#checked_expenses_refund').prop('checked', false);
                }
            });

            $('#checked_expenses_refund').change(function() {
                if ($(this).is(':checked')) {
                    $('#checked_expenses_expenses').prop('checked', false);
                }
            });

            //////////////////////////////////////////////////
            // $('#checked_students_refund_expenses').change(function() {
            //     if ($(this).is(':checked')) {
            //         $('#checked_students_refund_refund').prop('checked', false);
            //     }
            // });

            // $('#checked_students_refund_refund').change(function() {
            //     if ($(this).is(':checked')) {
            //         $('#checked_students_refund_expenses').prop('checked', false);
            //     }
            // });


            $('#checked_expenses_expenses').change(function() {
                if ($(this).is(':checked')) {
                    $('#checked_expenses').show();
                    $('#checked_students_refund').hide();

                    // $('#checked_expenses_expenses').prop('checked', true);
                    $('#checked_students_refund_expenses').prop('checked', true);

                    $('#checked_expenses_refund').prop('checked', false);
                    $('#checked_students_refund_refund').prop('checked', false);
                }
            });

            $('#checked_expenses_refund').change(function() {
                if ($(this).is(':checked')) {
                    $('#checked_students_refund').show();
                    $('#checked_expenses').hide();

                    // $('#checked_expenses_refund').prop('checked', true);
                    $('#checked_students_refund_refund').prop('checked', true);
                    $('#checked_expenses_expenses').prop('checked', false);
                    $('#checked_students_refund_expenses').prop('checked', false);
                }
            });

            $('#checked_students_refund_expenses').change(function() {
                if ($(this).is(':checked')) {
                    $('#checked_expenses').show();
                    $('#checked_students_refund').hide();

                    // $('#checked_students_refund_expenses').prop('checked', true);
                    $('#checked_expenses_expenses').prop('checked', true);
                    $('#checked_students_refund_refund').prop('checked', false);
                    $('#checked_expenses_refund').prop('checked', false);
                }
            });

            $('#checked_students_refund_refund').change(function() {
                if ($(this).is(':checked')) {
                    $('#checked_students_refund').show();
                    $('#checked_expenses').hide();

                    // $('#checked_students_refund_refund').prop('checked', true);
                    $('#checked_expenses_refund').prop('checked', true);
                    $('#checked_students_refund_expenses').prop('checked', false);
                    $('#checked_expenses_expenses').prop('checked', false);
                }
            });

            //////////////////////////////under checked_students_refund filters////////////////////////////////

            var checked_students_refund_isCashChecked = $('#checked_students_refund_cash').is(':checked');
            $('#checked_students_refund_bank').prop('disabled', checked_students_refund_isCashChecked);
            $('#checked_students_refund_cheque_no').prop('disabled', checked_students_refund_isCashChecked);
            $('#checked_students_refund_cheque_date').prop('disabled', checked_students_refund_isCashChecked);

            $('#checked_students_refund_cheque').change(function() {
                if ($(this).is(':checked')) {
                    $('#checked_students_refund_bank').prop('disabled', false);
                    $('#checked_students_refund_cheque_no').prop('disabled', false);
                    $('#checked_students_refund_cheque_date').prop('disabled', false);
                }
            });
            $('#checked_students_refund_cash').change(function() {
                if ($(this).is(':checked')) {
                    $('#checked_students_refund_bank').prop('disabled', true);
                    $('#checked_students_refund_cheque_no').prop('disabled', true);
                    $('#checked_students_refund_cheque_date').prop('disabled', true);
                }
            });

            $('#cash_reimbursement').change(function() {
                if ($(this).is(':checked')) {
                    $('#forward_next_sy').prop('checked', false);
                    $('#forward_siblings').prop('checked', false);
                }
            });

            $('#forward_next_sy').change(function() {
                if ($(this).is(':checked')) {
                    $('#cash_reimbursement').prop('checked', false);
                    $('#forward_siblings').prop('checked', false);
                }
            });

            $('#forward_siblings').change(function() {
                if ($(this).is(':checked')) {
                    $('#cash_reimbursement').prop('checked', false);
                    $('#forward_next_sy').prop('checked', false);
                }
            });


            ///////////////////// Cash/Check Reimbursement //////////////////////////////
            $('#cash_reimbursement').change(function() {
                if ($(this).is(':checked')) {
                    $('#reimburse_cash_div').show();
                    $('#forward_sy_div').hide();
                    $('#forward_siblings_div').hide();
                } else {
                    $('#reimburse_cash_div').hide();
                }
            });

            ///////////////////// Forward Next School Year //////////////////////////////
            $('#forward_next_sy').change(function() {
                if ($(this).is(':checked')) {
                    $('#forward_sy_div').show();
                    $('#reimburse_cash_div').hide();
                    $('#forward_siblings_div').hide();
                } else {
                    $('#forward_sy_div').hide();
                }
            });

            ///////////////////// Forward Siblings //////////////////////////////

            $('#forward_siblings').change(function() {
                if ($(this).is(':checked')) {
                    $('#forward_siblings_div').show();
                    $('#reimburse_cash_div').hide();
                    $('#forward_sy_div').hide();
                } else {
                    $('#forward_siblings_div').hide();
                }
            });




            $('#cash').change(function() {
                if ($(this).is(':checked')) {
                    $('#bank').prop('disabled', true);
                    $('#cheque_no').prop('disabled', true);
                    $('#cheque_date').prop('disabled', true);
                } else {
                    $('#bank').prop('disabled', false);
                    $('#cheque_no').prop('disabled', false);
                    $('#cheque_date').prop('disabled', false);
                }
            });

            var isCashChecked = $('#expenses_cash').is(':checked');
            $('#expenses_bank').prop('disabled', isCashChecked);
            $('#expenses_cheque_no').prop('disabled', isCashChecked);
            $('#expenses_cheque_date').prop('disabled', isCashChecked);


            $('#expenses_cheque').change(function() {
                if ($(this).is(':checked')) {
                    $('#expenses_bank').prop('disabled', false);
                    $('#expenses_cheque_no').prop('disabled', false);
                    $('#expenses_cheque_date').prop('disabled', false);
                }
            });

            $('#expenses_cash').change(function() {
                if ($(this).is(':checked')) {
                    $('#expenses_bank').prop('disabled', true);
                    $('#expenses_cheque_no').prop('disabled', true);
                    $('#expenses_cheque_date').prop('disabled', true);
                }
            });

            $(document).on('click', '#add_item', function() {
                $('#AdditemForm').modal()

            });

            $(document).on('keyup', '#item_amount, #item_quantity', function() {
                var amount = $("#item_amount").val();
                var qty = $("#item_quantity").val();
                var totalamount = parseFloat(amount) * parseFloat(qty);
                $("#item_total").val(totalamount);
            });

            $(document).on('keyup', '#edit_item_amount, #edit_item_quantity', function() {
                var amount = $("#edit_item_amount").val();
                var qty = $("#edit_item_quantity").val();
                var totalamount = parseFloat(amount) * parseFloat(qty);
                $("#edit_item_total").val(totalamount);
            });

            $("#add_item_btn").click(function(e) {
                e.preventDefault();
                var disbursement_item = $("#disbursement_item").val();
                var item_amount = $("#item_amount").val();
                var item_quantity = $("#item_quantity").val();
                var item_total = $("#item_total").val();

                if (disbursement_item.trim() == "" || item_amount.trim() == "" ||
                    item_quantity.trim() == "" || item_total.trim() == "") {
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: 'All fields are required',
                    });
                    return;
                }

                var table = $("#items_table");
                // var row = $("<tr data-id='" + (table.find("tbody tr").length + 1) + "'>");

                var row = $("<tr class='appended-row'>");

                row.append($("<td data-disburseid='" + disbursement_item + "'>").text($(
                    "#disbursement_item option:selected").text()));
                row.append($("<td>").text(item_amount));
                row.append($("<td>").text(item_quantity));
                row.append($("<td>").text(parseFloat(item_total).toLocaleString()));

                row.append($("<td>").html(

                    // '<button type="button" class="btn btn-danger btn-xs remove-append-row"><i class="far fa-trash-alt text-danger"></i></button>'
                    '<a href="javascript:void(0)" class="text-center align-middle ml-2 edit-append-row"><i class="far fa-edit text-primary"></i></a>'
                ));
                row.append($("<td>").html(

                    // '<button type="button" class="btn btn-danger btn-xs remove-append-row"><i class="far fa-trash-alt text-danger"></i></button>'
                    '<a href="javascript:void(0)" class="text-center align-middle ml-2 remove-append-row"><i class="far fa-trash-alt text-danger"></i></button>'
                ));



                table.find("tbody").on('click', '.remove-append-row', function() {
                    var row = $(this).closest('tr');
                    var rowTotal = parseFloat(row.find("td:eq(3)").text().replace(/,/g, ""));
                    if (!isNaN(rowTotal)) {
                        totalAmount -= rowTotal;
                    }
                    row.remove();
                    $("#grand_total").text(parseFloat(totalAmount).toLocaleString());
                });
                table.find("tbody").append(row);

                Toast.fire({
                    type: 'success',
                    title: 'New item added successfully'
                });

                $("#item_amount").val("");
                $("#item_quantity").val("");
                $("#item_total").val("");

                let currentAppendRow; // Variable to store the currently selected row

                var totalAmount = 0;
                $("#items_table tbody tr").each(function() {
                    var rowTotal = parseFloat($(this).find("td:eq(3)").text().replace(/,/g, ""));
                    if (!isNaN(rowTotal)) {
                        totalAmount += rowTotal;
                    }
                });
                $("#grand_total").text(parseFloat(totalAmount).toLocaleString());

                $('.edit-append-row').on('click', function() {
                    // Get the current row
                    currentAppendRow = $(this).closest('tr');

                    // Extract data from the row
                    const disbursement_item = currentAppendRow.find('td:eq(0)').text()
                        .trim();
                    const item_amount = currentAppendRow.find('td:eq(1)').text()
                        .trim();
                    const item_quantity = currentAppendRow.find('td:eq(2)').text().trim();
                    const item_total = currentAppendRow.find('td:eq(3)').text().trim();

                    // Populate modal inputs
                    $('#edit_disbursement_item').val(currentAppendRow.find('td:eq(0)').data(
                        'disburseid'));
                    $('#edit_item_amount').val(item_amount);
                    $('#edit_item_quantity').val(item_quantity);
                    $('#edit_item_total').val(item_total);

                    $('#EdititemForm').modal()
                });

                $('#update_selected_item_btn').on('click', function() {
                    if (currentAppendRow) {
                        // Get updated values from modal inputs
                        const edit_disbursement_item = $('#edit_disbursement_item').val().trim();
                        const edit_item_amount = $('#edit_item_amount').val().trim();
                        const edit_item_quantity = $('#edit_item_quantity').val().trim();
                        const edit_item_total = $('#edit_item_total').val().trim();

                        currentAppendRow.find('td:eq(0)').data('disburseid', edit_disbursement_item)
                            .text($(
                                "#edit_disbursement_item option:selected").text());
                        currentAppendRow.find('td:eq(1)').text(edit_item_amount);
                        currentAppendRow.find('td:eq(2)').text(edit_item_quantity);
                        currentAppendRow.find('td:eq(3)').text(edit_item_total);
                    }
                    Toast.fire({
                        type: 'success',
                        title: 'Successfully updated'
                    })

                    var totalAmount = 0;
                    $("#items_table tbody tr").each(function() {
                        var rowTotal = parseFloat($(this).find("td:eq(3)").text().replace(
                            /,/g, ""));
                        if (!isNaN(rowTotal)) {
                            totalAmount += rowTotal;
                        }
                    });
                    $("#grand_total").text(parseFloat(totalAmount).toLocaleString());

                });

            });

            $('#expenses_save').on('click', function() {
                add_expenses()
            });

            function add_expenses() {
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
                var expenses_departmewnt = $('#expenses_departmewnt').val();

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

                var tableData = [];
                $("#items_table").find("tbody tr").each(function() {
                    if ($(this).is(":visible")) {
                        var disbursement_item = $(this).find("td:eq(0)").text().trim();
                        var item_amount = $(this).find("td:eq(1)").text().trim();
                        var item_quantity = $(this).find("td:eq(2)").text().trim();
                        var item_total_amount = $(this).find("td:eq(3)").text().trim();

                        if (disbursement_item && item_amount && item_quantity &&
                            item_total_amount) {
                            tableData.push({
                                disbursement_item: disbursement_item,
                                item_amount: item_amount,
                                item_quantity: item_quantity,
                                item_total_amount: item_total_amount
                            });
                        }
                    }
                });

                $.ajax({
                    type: 'GET',
                    url: '/financev2/setup/disbursement_expenses/create',
                    data: {
                        disbursement_type: disbursement_type,
                        expenses_date: expenses_date,
                        expenses_voucher_no: expenses_voucher_no,
                        expenses_ref_no: expenses_ref_no,
                        expenses_disbursement_to: expenses_disbursement_to,
                        expenses_departmewnt: expenses_departmewnt,
                        expenses_amount: expenses_amount,
                        payment_type: payment_type,
                        bank: bank,
                        cheque_no: cheque_no,
                        cheque_date: cheque_date,
                        expenses_remarks: expenses_remarks,
                        tableData: tableData
                    },
                    success: function(data) {
                        if (data[0].status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data[0].message
                            })
                            voucherstable()
                        } else if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully created'
                            })

                            var table = $("#items_table");
                            table.find("tbody .appended-row").remove();

                            voucherstable()

                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });

            }

            $('#students_refund_save').on('click', function() {
                add_students_refund()
            });

            function add_students_refund() {
                var disbursement_type = '';
                if ($('#checked_students_refund_expenses').is(':checked')) {
                    disbursement_type = 'Expenses';
                } else if ($('#checked_students_refund_refund').is(':checked')) {
                    disbursement_type = 'Students Refund';
                }
                var students_refund_date = $('#checked_students_refund_date').val();
                var students_refund_voucher_no = $('#checked_students_refund_voucher').val();
                var students_refund_stud_no = $('#checked_students_refund_stud_no').val();
                var students_refund_to = $('#checked_students_refund_to').val();
                var grade_level = $('#filter_gradelevel').val();

                var reimburse_type = '';
                if ($('#cash_reimbursement').is(':checked')) {
                    reimburse_type = 'Cash/Check Reimbursement';
                } else if ($('#forward_next_sy').is(':checked')) {
                    reimburse_type = 'Foward Next SY';
                } else if ($('#forward_siblings').is(':checked')) {
                    reimburse_type = 'Foward to Siblings';
                }
                if ($('#cash_reimbursement').is(':checked')) {
                    var payment_type = '';
                    if ($('#checked_students_refund_cash').is(':checked')) {
                        payment_type = 'Cash';
                    } else if ($('#checked_students_refund_cheque').is(':checked')) {
                        payment_type = 'Cheque';
                    }
                }

                var students_refund_cheque_amount = $('#students_refund_cheque_amount').val();

                var bank = '';
                var cheque_no = '';
                var cheque_date = '';
                if ($('#checked_students_refund_cheque').is(':checked')) {
                    bank = $('#checked_students_refund_cheque_bank').val();
                    cheque_no = $('#checked_students_refund_cheque_no').val();
                    cheque_date = $('#checked_students_refund_cheque_date').val();
                }

                var students_refund_remarks = $('#checked_students_refund_remarks').val();


                var forward_sy_amount = '';
                var forward_sy_sy = '';
                var forward_sy_sem = '';
                var forward_sy_apply_to = '';

                var forward_siblings_amount = '';
                var forward_siblings_sy = '';
                var forward_siblings_sem = '';
                var forward_siblings_apply_to = '';

                if ($('#forward_next_sy').is(':checked')) {
                    forward_sy_amount = $('#forward_sy_amount').val();
                    forward_sy_sy = $('#forward_sy_sy').val();
                    forward_sy_sem = $('#forward_sy_sem').val();
                    forward_sy_apply_to = $('#forward_sy_apply_to').val();
                }

                if ($('#forward_siblings').is(':checked')) {
                    forward_siblings_amount = $('#forward_siblings_amount').val();
                    forward_siblings_sy = $('#forward_siblings_sy').val();
                    forward_siblings_sem = $('#forward_siblings_sem').val();
                    forward_siblings_apply_to = $('#forward_siblings_apply_to').val();
                }


                $.ajax({
                    type: 'GET',
                    url: '/financev2/setup/disbursement_students_refund/create',
                    data: {
                        disbursement_type: disbursement_type,
                        students_refund_date: students_refund_date,
                        students_refund_voucher_no: students_refund_voucher_no,
                        students_refund_stud_no: students_refund_stud_no,
                        students_refund_to: students_refund_to,
                        grade_level: grade_level,
                        reimburse_type: reimburse_type,
                        payment_type: payment_type,
                        students_refund_cheque_amount: students_refund_cheque_amount,
                        bank: bank,
                        cheque_no: cheque_no,
                        cheque_date: cheque_date,
                        students_refund_remarks: students_refund_remarks,
                        forward_sy_amount: forward_sy_amount,
                        forward_sy_sy: forward_sy_sy,
                        forward_sy_sem: forward_sy_sem,
                        forward_sy_apply_to: forward_sy_apply_to,

                        forward_siblings_amount: forward_siblings_amount,
                        forward_siblings_sy: forward_siblings_sy,
                        forward_siblings_sem: forward_siblings_sem,
                        forward_siblings_apply_to: forward_siblings_apply_to,
                    },
                    success: function(data) {

                        if (data[0].status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data[0].message
                            })
                            voucherstable()
                        } else if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully created'
                            })

                            voucherstable()

                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });

            }

            voucherstable()

            function voucherstable() {

                $("#voucher_Table").DataTable({
                    destroy: true,
                    // data:temp_subj,
                    // bInfo: true,
                    autoWidth: false,
                    // lengthChange: true,
                    // stateSave: true,
                    // serverSide: true,
                    // processing: true,
                    ajax: {
                        url: '/financev2/setup/disbursements/fetch',
                        type: 'GET',
                        dataSrc: function(json) {
                            return json;
                        }
                    },
                    columns: [{
                            "data": "date"
                        },
                        {
                            "data": "voucher_no"
                        },
                        {
                            "data": "disburse_to"
                        },
                        {
                            "data": "disbursement_type"
                        },
                        {
                            "data": "payment_type"
                        },
                        {
                            "data": "amount"
                        },
                        {
                            "data": null
                        }
                    ],
                    columnDefs: [

                        {
                            'targets': 0,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.date).addClass(
                                    'align-middle');
                            }
                        },
                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.voucher_no).addClass(
                                    'align-middle');
                            }
                        },

                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                if (!rowData.disburse_to || rowData.disburse_to == null) {
                                    $(td).html("--").addClass('align-middle');
                                } else {
                                    $(td).html(rowData.disburse_to).addClass('align-middle');
                                }

                            }
                        },

                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                // $(td).html('');
                                $(td).html(rowData.disbursement_type).addClass('align-middle');

                            }
                        },

                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                if (!rowData.payment_type || rowData.payment_type == null) {
                                    $(td).html("--").addClass('align-middle');
                                } else {
                                    $(td).html(rowData.payment_type).addClass('align-middle');
                                }

                            }
                        },

                        {
                            'targets': 5,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(parseInt(rowData.amount).toString().replace(
                                        /\B(?=(\d{3})+(?!\d))/g, ","))
                                    .addClass(
                                        'align-middle');
                            }
                        },

                        {
                            'targets': 6,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var buttons =
                                    '<a href="javascript:void(0)" class="edit_voucher" data-id="' +
                                    rowData.id + '"data-disbursetype="' +
                                    rowData.disbursement_type +
                                    '"><i class="far fa-edit text-primary"></i></a> ' +
                                    '<a href="javascript:void(0)" class="delete_voucher" data-id="' +
                                    rowData.id + '"data-disbursetype="' +
                                    rowData.disbursement_type +
                                    '"><i class="far fa-trash-alt text-danger"></i></a>';
                                $(td)[0].innerHTML = buttons;
                                $(td).addClass('text-center justify-content-center align-middle');
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

            $(document).on('click', '.delete_voucher', function() {
                var deletevoucherId = $(this).attr('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        delete_voucher(deletevoucherId);
                    }
                });
            });

            function delete_voucher(deletevoucherId) {
                $.ajax({
                    type: 'GET',
                    url: '/financev2/setup/disbursements/delete',
                    data: {
                        deletevoucherId: deletevoucherId
                    },
                    success: function(data) {
                        if (data[0].status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data[0].message
                            });
                            voucherstable();
                        } else if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully deleted'
                            });
                            voucherstable();
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            });
                        }
                    }
                });
            }
            $(document).on('click', '.edit_voucher', function() {
                var editvoucherId = $(this).attr('data-id');
                var editvoucher_disbursetype = $(this).attr('data-disbursetype');

                if (editvoucher_disbursetype == "Expenses") {

                    $('#EditDisbursementForm').modal('show');
                    $('#edit_checked_expenses').show();
                    $('#edit_checked_students_refund').hide();
                    $('#edit_checked_expenses_refund').prop('disabled', true);

                    $('#expenses_id').val(editvoucherId);


                    $.ajax({
                        type: 'GET',
                        url: '/financev2/setup/disbursements_expenses/edit',
                        data: {
                            voucherid: editvoucherId,
                            disburse_type: editvoucher_disbursetype
                        },
                        success: function(response) {

                            console.log("mga disbursement expenses", response);

                            var disburse_expenses_selected = response.disbursement_expenses;
                            var disbursement_expenses_items_selected = response
                                .disbursement_expenses_items;


                            $("#edit_expenses_date").val(disburse_expenses_selected[0].date);
                            $("#edit_expenses_voucher_no").val(disburse_expenses_selected[0]
                                .voucher_no);
                            $("#edit_expenses_ref_no").val(disburse_expenses_selected[0]
                                .reference_no);

                            $("#edit_expenses_disbursement_to").val(disburse_expenses_selected[
                                    0]
                                .disburse_to).find('option').each(function() {
                                if ($(this).val() == disburse_expenses_selected[0]
                                    .disburse_to) {
                                    $(this).prop('selected', true);
                                }
                            });


                            $("#edit_expenses_departmewnt").val(disburse_expenses_selected[
                                    0]
                                .company_department).find('option').each(function() {
                                if ($(this).val() == disburse_expenses_selected[0]
                                    .company_department) {
                                    $(this).prop('selected', true);
                                }
                            });

                            if (disburse_expenses_selected[0].payment_type == "Cash") {
                                $("#edit_expenses_cash").prop('checked', true);
                            } else if (disburse_expenses_selected[0].payment_type == "Cheque") {
                                $("#edit_expenses_cheque").prop('checked', true);
                            }

                            $("#edit_expenses_amount").val(disburse_expenses_selected[0]
                                .amount);

                            $("#edit_expenses_bank").val(disburse_expenses_selected[
                                    0]
                                .bank).find('option').each(function() {
                                if ($(this).val() == disburse_expenses_selected[0]
                                    .bank) {
                                    $(this).prop('selected', true);
                                }
                            });

                            $("#edit_expenses_cheque_no").val(disburse_expenses_selected[
                                0].cheque_no);

                            $("#edit_expenses_cheque_date").val(disburse_expenses_selected[
                                0].cheque_date);

                            $("#edit_expenses_remarks").val(disburse_expenses_selected[
                                0].remarks);

                            var isCashChecked = $('#edit_expenses_cash').is(':checked');
                            $('#edit_expenses_bank').prop('disabled', isCashChecked);
                            $('#edit_expenses_cheque_no').prop('disabled', isCashChecked);
                            $('#edit_expenses_cheque_date').prop('disabled', isCashChecked);


                            $('#edit_expenses_cheque').change(function() {
                                if ($(this).is(':checked')) {
                                    $('#edit_expenses_bank').prop('disabled', false);
                                    $('#edit_expenses_cheque_no').prop('disabled',
                                        false);
                                    $('#edit_expenses_cheque_date').prop('disabled',
                                        false);
                                }
                            });

                            $('#edit_expenses_cash').change(function() {
                                if ($(this).is(':checked')) {
                                    $('#edit_expenses_bank').prop('disabled', true);
                                    $('#edit_expenses_cheque_no').prop('disabled',
                                        true);
                                    $('#edit_expenses_cheque_date').prop('disabled',
                                        true);
                                }
                            });

                        }
                    });

                    $("#edit_items_table").DataTable({
                        destroy: true,
                        autoWidth: false,
                        ajax: {
                            url: '/financev2/setup/disbursements_expenses/edit',
                            type: 'GET',
                            data: {
                                voucherid: editvoucherId
                            },
                            dataSrc: function(json) {
                                return json.disbursement_expenses_items;
                            }
                        },
                        columns: [{
                                "data": "item_name"
                            },
                            {
                                "data": "amount"
                            },
                            {
                                "data": "qty"
                            },
                            {
                                "data": "total_amount"
                            },
                            {
                                "data": null
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
                                    $(td).html(rowData.item_name)
                                        .addClass('align-middle')
                                        .attr('data-disburseid', rowData.id)
                                        .attr('data-disburse_forid', 0);
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
                                    $(td).html(rowData.total_amount)
                                        .addClass('align-middle');
                                }
                            },
                            {
                                'targets': 4,
                                'orderable': false,
                                'createdCell': function(td, cellData,
                                    rowData, row, col) {
                                    var buttons =
                                        '<a href="javascript:void(0)" class="edit_disbursement_item" data-id="' +
                                        rowData.id +
                                        '"><i class="far fa-edit text-primary"></i></a>';
                                    $(td)[0].innerHTML = buttons;
                                    $(td).addClass(
                                        'text-center align-middle');
                                }
                            },
                            {
                                'targets': 5,
                                'orderable': false,
                                'createdCell': function(td, cellData,
                                    rowData, row, col) {
                                    var buttons =
                                        '<a href="javascript:void(0)" class="delete_disbursement_item" data-id="' +
                                        rowData.id +
                                        '"><i class="far fa-trash-alt text-danger"></i></a>';
                                    $(td)[0].innerHTML = buttons;
                                    $(td).addClass(
                                        'text-center align-middle');
                                }
                            }
                        ]
                    });

                    //////////////////////////// Add Item For Selected Voucher //////////////////////////////
                    $(document).on('click', '#selected_vouchers_add_item', function() {
                        $('#Selected_Vouchers_AdditemForm').modal()
                    });

                    $(document).on('keyup', '#selected_new_item_amount, #selected_new_item_quantity',
                        function() {
                            var amount = $("#selected_new_item_amount").val();
                            var qty = $("#selected_new_item_quantity").val();
                            var totalamount = parseFloat(amount) * parseFloat(qty);
                            $("#selected_new_item_total").val(totalamount);
                        });

                    $(document).on('keyup',
                        '#edit_selected_new_item_amount, #edit_selected_new_item_quantity',
                        function() {
                            var amount = $("#edit_selected_new_item_amount").val();
                            var qty = $("#edit_selected_new_item_quantity").val();
                            var totalamount = parseFloat(amount) * parseFloat(qty);
                            $("#edit_selected_new_item_total").val(totalamount);
                        });

                    $("#selected_new_add_item_btn").click(function() {

                        var disbursement_item = $("#selected_new_disbursement_item").val();
                        var item_amount = $("#selected_new_item_amount").val();
                        var item_quantity = $("#selected_new_item_quantity").val();
                        var item_total = $("#selected_new_item_total").val();

                        // if (disbursement_item.trim() == "" || item_amount.trim() == "" ||
                        //     item_quantity.trim() == "" || item_total.trim() == "") {
                        //     Swal.fire({
                        //         type: 'error',
                        //         title: 'Error',
                        //         text: 'All fields are required',
                        //     });
                        //     return;
                        // }

                        var table = $("#edit_items_table");
                        // var row = $("<tr data-id='" + (table.find("tbody tr").length + 1) + "'>");

                        var row = $("<tr class='appended-row'>");

                        // row.append($("<td data-disburseid='" + $("#selected_new_disbursement_item")
                        //     .val() + "'>").text($(
                        //     "#selected_new_disbursement_item option:selected").text()));
                        row.append($("<td data-disburseid='" + '0' + "'>").text($(
                            "#selected_new_disbursement_item option:selected").text()));
                        row.append($("<td>").text(item_amount));
                        row.append($("<td>").text(item_quantity));
                        row.append($("<td>").text(parseFloat(item_total).toLocaleString()));

                        row.append($("<td class='text-center'>").html(

                            // '<button type="button" class="btn btn-danger btn-xs remove-append-row"><i class="far fa-trash-alt text-danger"></i></button>'
                            '<a href="javascript:void(0)" class="edit-append-row"><i class="far fa-edit text-primary"></i></a>'
                        ));
                        row.append($("<td class='text-center'>").html(

                            // '<button type="button" class="btn btn-danger btn-xs remove-append-row"><i class="far fa-trash-alt text-danger"></i></button>'
                            '<a href="javascript:void(0)" class="remove-append-row"><i class="far fa-trash-alt text-danger"></i></button>'
                        ));



                        table.find("tbody").on('click', '.remove-append-row', function() {
                            var row = $(this).closest('tr');
                            var rowTotal = parseFloat(row.find("td:eq(3)").text().replace(
                                /,/g, ""));
                            if (!isNaN(rowTotal)) {
                                totalAmount -= rowTotal;
                            }
                            row.remove();
                            $("#edit_grand_total").text(parseFloat(totalAmount)
                                .toLocaleString());
                        });
                        table.find("tbody").append(row);

                        Toast.fire({
                            type: 'success',
                            title: 'New item added successfully'
                        });

                        $("#selected_new_item_amount").val("");
                        $("#selected_new_item_quantity").val("");
                        $("#selected_new_item_total").val("");

                        let currentAppendRow; // Variable to store the currently selected row

                        var totalAmount = 0;
                        $("#edit_items_table tbody tr").each(function() {
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
                            $('#edit_selected_new_disbursement_item').val(currentAppendRow
                                .find(
                                    'td:eq(0)').data(
                                    'disburseid'));
                            $('#edit_selected_new_item_amount').val(item_amount);
                            $('#edit_selected_new_item_quantity').val(item_quantity);
                            $('#edit_selected_new_item_total').val(item_total);

                            $('#Selected_Vouchers_EdititemForm').modal()
                        });

                        $('#update_selected_new_selected_item_btn').on('click', function() {
                            if (currentAppendRow) {
                                // Get updated values from modal inputs
                                const edit_disbursement_item = $(
                                        '#edit_selected_new_disbursement_item')
                                    .val().trim();
                                const edit_item_amount = $('#edit_selected_new_item_amount')
                                    .val()
                                    .trim();
                                const edit_item_quantity = $(
                                        '#edit_selected_new_item_quantity').val()
                                    .trim();
                                const edit_item_total = $('#edit_selected_new_item_total')
                                    .val().trim();

                                currentAppendRow.find('td:eq(0)').data('disburseid',
                                        edit_disbursement_item)
                                    .text($(
                                            "#edit_selected_new_disbursement_item option:selected"
                                        )
                                        .text());
                                currentAppendRow.find('td:eq(1)').text(edit_item_amount);
                                currentAppendRow.find('td:eq(2)').text(edit_item_quantity);
                                currentAppendRow.find('td:eq(3)').text(edit_item_total);
                            }
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully updated'
                            })

                            var totalAmount = 0;
                            $("#edit_items_table tbody tr").each(function() {
                                var rowTotal = parseFloat($(this).find("td:eq(3)")
                                    .text().replace(
                                        /,/g, ""));
                                if (!isNaN(rowTotal)) {
                                    totalAmount += rowTotal;
                                }
                            });
                            $("#edit_grand_total").text(parseFloat(totalAmount)
                                .toLocaleString());

                        });



                    });
                    ////////////// not append ///////////////////

                    let updatecurrentRow;

                    $(document).on('click', '.edit_disbursement_item', function() {

                        updatecurrentRow = $(this).closest('tr');

                        // Extract data from the row

                        // console.log(disbursement_item_notappend, ' pandamay ni siya');
                        const item_amount_notappend = updatecurrentRow.find('td:eq(1)').text()
                            .trim();
                        const item_quantity_notappend = updatecurrentRow.find('td:eq(2)').text()
                            .trim();
                        const item_total_notappend = updatecurrentRow.find('td:eq(3)').text()
                            .trim();

                        const disbursement_item_notappend = updatecurrentRow.find('td:eq(0)')
                            .text()
                            .trim();

                        const disburseid = updatecurrentRow.find('td:eq(0)').data('disburseid');

                        // // Populate modal inputs
                        // $('#edit_selected_new_disbursement_item_notappend')
                        //     .find('option')
                        //     .each(function() {
                        //         if ($(this).val() == disburseid) {
                        //             $(this).prop('selected', true);
                        //             $(this).text(disbursement_item_notappend);
                        //         }
                        // });
                        $('#edit_selected_new_disbursement_item_notappend')
                            .find('option')
                            .each(function() {
                                if ($(this).text() == disbursement_item_notappend) {
                                    $(this).prop('selected', true);
                                }
                            });

                        $('#edit_selected_new_item_amount_notappend').val(item_amount_notappend);
                        $('#edit_selected_new_item_quantity_notappend').val(
                            item_quantity_notappend);
                        $('#edit_selected_new_item_total_notappend').val(item_total_notappend);

                        $('#Selected_Vouchers_EdititemForm_Notappend').modal('show');
                    });



                    $(document).on('click', '.delete_disbursement_item', function() {

                        var row = $(this).closest('tr');

                        row.remove();

                    });


                    $(document).on('keyup',
                        '#edit_selected_new_item_amount_notappend, #edit_selected_new_item_quantity_notappend',
                        function() {
                            var amount = $("#edit_selected_new_item_amount_notappend").val();
                            var qty = $("#edit_selected_new_item_quantity_notappend").val();
                            var totalamount = parseFloat(amount) * parseFloat(qty);
                            $("#edit_selected_new_item_total_notappend").val(totalamount);
                        });

                    $(document).on('keyup',
                        '#edit_selected_new_item_amount_notappend, #edit_selected_new_item_quantity_notappend',
                        function() {
                            var amount = $("#edit_selected_new_item_amount_notappend").val();
                            var qty = $("#edit_selected_new_item_quantity_notappend").val();
                            var totalamount = parseFloat(amount) * parseFloat(qty);
                            $("#edit_selected_new_item_total_notappend").val(totalamount);
                        });


                    $('#update_selected_new_selected_item_btn_notappend').on('click', function() {
                        if (updatecurrentRow) {
                            // Get updated values from modal inputs
                            const edit_disbursement_item = $(
                                    '#edit_selected_new_disbursement_item_notappend')
                                .val().trim();
                            const edit_item_amount = $('#edit_selected_new_item_amount_notappend')
                                .val()
                                .trim();
                            const edit_item_quantity = $(
                                    '#edit_selected_new_item_quantity_notappend').val()
                                .trim();
                            const edit_item_total = $('#edit_selected_new_item_total_notappend')
                                .val().trim();

                            updatecurrentRow.find('td:eq(0)').data('disburse_forid',
                                    edit_disbursement_item)
                                .text($(
                                        "#edit_selected_new_disbursement_item_notappend option:selected"
                                    )
                                    .text());

                            updatecurrentRow.find('td:eq(1)').text(edit_item_amount);
                            updatecurrentRow.find('td:eq(2)').text(edit_item_quantity);
                            updatecurrentRow.find('td:eq(3)').text(edit_item_total);
                        }
                        Toast.fire({
                            type: 'success',
                            title: 'Successfully updated'
                        })

                    });

                    ////////////////////////////////////////////////

                    ////////////////update expenses//////////////

                    $('#expenses_update').on('click', function() {
                        expenses_update()
                    });

                    function expenses_update() {
                        var id = $('#expenses_id').val();
                        var disbursement_type = '';
                        if ($('#edit_checked_expenses_expenses').is(':checked')) {
                            disbursement_type = 'Expenses';
                        }
                        var expenses_date = $('#edit_expenses_date').val();
                        var expenses_voucher_no = $('#edit_expenses_voucher_no').val();
                        var expenses_ref_no = $('#edit_expenses_ref_no').val();
                        var expenses_disbursement_to = $('#edit_expenses_disbursement_to').val();
                        var expenses_departmewnt = $('#edit_expenses_departmewnt').val();

                        var payment_type = '';
                        if ($('#edit_expenses_cash').is(':checked')) {
                            payment_type = 'Cash';
                        } else if ($('#edit_expenses_cheque').is(':checked')) {
                            payment_type = 'Cheque';
                        }

                        var expenses_amount = $('#edit_expenses_amount').val();

                        var bank = '';
                        var cheque_no = '';
                        var cheque_date = '';
                        if ($('#edit_expenses_cheque').is(':checked')) {
                            bank = $('#edit_expenses_bank').val();
                            cheque_no = $('#edit_expenses_cheque_no').val();
                            cheque_date = $('#edit_expenses_cheque_date').val();
                        }

                        var expenses_remarks = $('#edit_expenses_remarks').val();

                        var tableData = [];
                        $("#edit_items_table").find("tbody tr").each(function() {
                            if ($(this).is(":visible")) {
                                var disbursement_item = $(this).find("td:eq(0)").data('disburseid');
                                var item_name = $(this).find("td:eq(0)").text().trim();
                                var item_amount = $(this).find("td:eq(1)").text().trim();
                                var item_quantity = $(this).find("td:eq(2)").text().trim();
                                var item_total_amount = $(this).find("td:eq(3)").text().trim();

                                if (disbursement_item && item_name && item_amount &&
                                    item_quantity &&
                                    item_total_amount) {
                                    tableData.push({
                                        disbursement_item: disbursement_item,
                                        item_name: item_name,
                                        item_amount: item_amount,
                                        item_quantity: item_quantity,
                                        item_total_amount: item_total_amount
                                    });
                                }
                            }
                        });

                        $.ajax({
                            type: 'GET',
                            url: '/financev2/setup/disbursement_expenses/update',
                            data: {
                                id: id,
                                disbursement_type: disbursement_type,
                                expenses_date: expenses_date,
                                expenses_voucher_no: expenses_voucher_no,
                                expenses_ref_no: expenses_ref_no,
                                expenses_disbursement_to: expenses_disbursement_to,
                                expenses_departmewnt: expenses_departmewnt,
                                expenses_amount: expenses_amount,
                                payment_type: payment_type,
                                bank: bank,
                                cheque_no: cheque_no,
                                cheque_date: cheque_date,
                                expenses_remarks: expenses_remarks,
                                tableData: tableData
                            },
                            success: function(data) {
                                if (data[0].status == 2) {
                                    Toast.fire({
                                        type: 'warning',
                                        title: data[0].message
                                    })
                                    voucherstable()
                                } else if (data[0].status == 1) {
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Successfully created'
                                    })

                                    var table = $("#edit_items_table");
                                    table.find("tbody .appended-row").remove();

                                    voucherstable()

                                } else {
                                    Toast.fire({
                                        type: 'error',
                                        title: data[0].message
                                    })
                                }
                            }
                        });

                    }





                } else {

                    $('#students_refund_id').val(editvoucherId);

                    $('#EditDisbursementForm').modal('show');

                    $('#edit_checked_students_refund').show();
                    $('#edit_checked_expenses').hide();
                    $('#edit_checked_students_refund_expenses').prop('disabled', true);

                }


            });





        });
    </script>
@endsection
