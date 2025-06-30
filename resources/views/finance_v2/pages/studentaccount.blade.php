@extends('finance_v2.layouts.app2')

@section('pagespecificscripts')
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.js') }}"></script>
    <script src="{{ asset('plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedheader/js/fixedHeader.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection

@section('content')
    @php
        $schoolyear = DB::table('sy')->get();
        $semester = DB::table('semester')->get();
        $academicprogram = DB::table('academicprogram')->get();
        $gradelevel = DB::table('gradelevel')->get();
        $itemclassification = DB::table('itemclassification')->where('deleted', 0)->get();
        $item = DB::table('fn_items')->where('deleted', 0)->get();
        $coa = DB::table('acc_coa')->where('deleted', 0)->get();
        $sy = DB::table('sy')->get();
        $semesters = DB::table('semester')->get();
        @endphp
    <style>
        .card {
            border: 0.5px solid #d2d2d2 !important;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
    </style>
    <div>
        <div class="d-flex align-items-center gap-2">
            <ion-icon name="settings-outline" size="large" class="px-1"></ion-icon>
            <h5 class="text-black m-0">Student Accounts</h5>

        </div>
        <nav style="--bs-breadcrumb-divider: '<';" aria-label="breadcrumb">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Student Accounts</li>
            </ol>
        </nav>
        <hr>

        <!-- Filter Section -->
        <div class="card mt-3" style="border-radius: 0;">
            <div class="border rounded p-3" style="background-color: #f8f9fa;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-filter fa-lg px-1"></i>
                    <h6 class="m-0">Filter</h6>
                </div>
                <form>
                    <div class="row ">
                        <div class="col-md-3">
                            <label for="schoolYear" class="form-label"
                                style="font-size: 12.5px; font-weight: normal;">School Year</label>
                            <br>
                            <select id="schoolYear" class="form-select w-100 select2"
                                style="font-size: 12.5px; font-weight: normal; height: 33px;">
                                @foreach ($schoolyear as $sy)
                                    <option>{{ $sy->sydesc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="semester" class="form-label"
                                style="font-size: 12.5px; font-weight: normal;">Semester</label>
                            <br>
                            <select id="semester" class="form-select w-100 select2"
                                style="font-size: 12.5px; font-weight: normal; height: 33px;">
                                @foreach ($semester as $sem)
                                    <option>{{ $sem->semester }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="academicProgram" class="form-label"
                                style="font-size: 12.5px; font-weight: normal;">Academic Program</label>
                            <br>
                            <select id="academicProgram" class="form-select w-100 select2"
                                style="font-size: 12.5px; font-weight: normal; height: 33px;">
                                @foreach ($academicprogram as $acadprog)
                                    <option>{{ $acadprog->progname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="academicLevel" class="form-label"
                                style="font-size: 12.5px; font-weight: normal;">Academic Level</label>
                            <br>
                            <select id="academicLevel" class="form-select w-100 select2"
                                style="font-size: 12.5px; font-weight: normal; height: 33px;">
                                @foreach ($gradelevel as $gl)
                                    <option>{{ $gl->levelname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="course" class="form-label"
                                style="font-size: 12.5px; font-weight: normal;">Course</label>
                            <br>
                            <select id="course" class="form-select w-100 select2"
                                style="font-size: 12.5px; font-weight: normal; height: 33px;">
                                <option></option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="strand" class="form-label"
                                style="font-size: 12.5px; font-weight: normal;">Strand</label>
                            <br>
                            <select id="strand" class="form-select w-100 select2"
                                style="font-size: 12.5px; font-weight: normal; height: 33px;">
                                <option></option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="section" class="form-label"
                                style="font-size: 12.5px; font-weight: normal;">Section</label>
                            <br>
                            <select id="section" class="form-select w-100 select2"
                                style="font-size: 12.5px; font-weight: normal; height: 33px;">
                                <option></option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="scholarship" class="form-label"
                                style="font-size: 12.5px; font-weight: normal;">Scholarship</label>
                            <br>
                            <select id="scholarship" class="form-select w-100 select2"
                                style="font-size: 12.5px; font-weight: normal; height: 33px;">
                                <option></option>
                                <option>Scholarship 1</option>
                                <option>Scholarship 2</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card" style="border-radius: 0">

            <div class="container mt-2 d-flex justify-content-between align-items-center">
                <h6>Students Accounts</h6>

                <!-- Filters -->
                <div class="d-flex align-items-center">
                    <div style="margin-right: 10px;">
                        <input type="checkbox" id="examPermit">
                        <label for="examPermit" style="font-weight: normal; font-size: 12.5px;">Examination
                            Permit</label>
                    </div>
                    <div>
                        <input type="checkbox" id="noDownPayment" checked>
                        <label for="noDownPayment" style="font-weight: normal; font-size: 12.5px;">Allow NO
                            DOWNPAYMENT</label>
                    </div>
                </div>
            </div>
            <hr style="border-color: #a2a2a2; margin-bottom: 10px">

            <!-- Action Buttons -->
            <div class="row pl-3 d-flex justify-content-between">
                <div class="col d-flex">
                    <button class="btn btn-success btn-sm p-1.5 me-2 mr-2"
                        style="display: flex; align-items: center; font-size: 12.5px; background-color: #005728; color: white;">
                        <i class="fas fa-money-bill-wave p-1"></i> Fees Adjustment
                    </button>
                    <button class="btn btn-sm p-1.5 me-2 mr-2"
                        style="display: flex; align-items: center; font-size: 12.5px; background-color: #4B4B4B; color: white;"
                        data-toggle="modal" data-target="#DiscountModal">
                        <i class="fas fa-plus p-1"></i> Add Discount
                    </button>
                    <button class="btn btn-sm p-1.5 me-2 mr-2"
                        style="display: flex; align-items: center; font-size: 12.5px; background-color: #A65C19; color: white;"
                        data-toggle="modal" data-target="#oldAccountsForwardingModal">
                        <i class="fas fa-folder-open p-1"></i> Old Accounts Forwarding
                    </button>
                    <button class="btn btn-sm p-1.5"
                        style="display: flex; align-items: center; font-size: 12.5px; background-color: #724A79; color: white;"
                        data-toggle="modal" data-target="#bookEntry1Modal">
                        <i class="fas fa-book p-1"></i> Book Entry
                    </button>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary btn-sm p-1.5 mr-2"
                        style="display: flex; align-items: center; font-size: 12.5px;" data-toggle="modal">
                        <i class="fas fa-print p-1"></i> Print
                    </button>
                </div>
            </div>

            <div class="card-body">
                {{-- <div class="d-flex justify-content-between align-items-center"
                    style="font-size: 12.5px; font-weight: normal;">
                    <div class="d-flex align-items-center mr-2">
                        <label class="" style="font-weight: normal;">Show</label>
                        <select id="entriesPerPage" class="form-select form-select-sm"
                            style="width: 50px; font-size: 12.5px;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <label class="ms-2 " style="font-weight: normal;">entries</label>
                    </div>
                    <div class="d-flex align-items-center">
                        <label class="me-2" style="font-weight: normal;">Search:</label>
                        <input type="search" id="tableSearch" class="form-control form-control-sm"
                            style="width: 200px; font-size: 12.5px;">
                    </div>
                </div> --}}

                <div class="table-responsive mt-1" style="font-size: 0.8rem">
                    <table id="studentAccountsTable" class="table table-bordered table-sm" style="font-size: 12px;">
                        <thead style="font-size: 12px; background-color: #d3d3d3;">
                            <tr>
                                <th style="font-weight: normal; border: 1px solid #a2a2a2;"></th>
                                <th style="font-weight: normal; border: 1px solid #a2a2a2;">Student ID</th>
                                <th style="font-weight: normal; border: 1px solid #a2a2a2;">Student Name</th>
                                <th style="font-weight: normal; border: 1px solid #a2a2a2;">Academic Level</th>
                                <th style="font-weight: normal; border: 1px solid #a2a2a2;">Course/Strand</th>
                                <th style="font-weight: normal; border: 1px solid #a2a2a2;">Section</th>
                                <th style="font-weight: normal; border: 1px solid #a2a2a2;">Payment</th>
                                <th style="font-weight: normal; border: 1px solid #a2a2a2;">Balance</th>
                                <th style="font-weight: normal; border: 1px solid #a2a2a2;">Exam Permit</th>
                                <th style="font-weight: normal; border: 1px solid #a2a2a2;">Action</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 12px;">

                        </tbody>
                    </table>
                </div>

                <div class="mt-3" style="font-size: 0.8rem; font-weight: normal;">
                    <strong>Legend:</strong>
                    <span
                        style="background-color: green; width: 15px; height: 15px; display: inline-block; margin-right: 5px;"></span>
                    Enrolled
                    <span
                        style="background-color: red; width: 15px; height: 15px; display: inline-block; margin-right: 5px;"></span>
                    Dropped
                    <span
                        style="background-color: orange; width: 15px; height: 15px; display: inline-block; margin-right: 5px;"></span>
                    Withdrawn
                    <span
                        style="background-color: blue; width: 15px; height: 15px; display: inline-block; margin-right: 5px;"></span>
                    Transferred IN
                    <span
                        style="background-color: yellow; width: 15px; height: 15px; display: inline-block; margin-right: 5px;"></span>
                    Transferred OUT
                    <span
                        style="background-color: gray; width: 15px; height: 15px; display: inline-block; margin-right: 5px;"></span>
                    Deceased
                    <span
                        style="background-color: white; border: 1px solid black; width: 15px; height: 15px; display: inline-block; margin-right: 5px;"></span>
                    Not Enrolled
                </div>
            </div>

        </div>

        <!-- View Accounts Modal -->
        <div class="modal fade" id="studentAccountModal" data-backdrop="static" tabindex="-1"
            aria-labelledby="studentAccountModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog" style="font-size: 0.8rem; margin-top: 0px; max-width: 90%;">
                <div class="modal-content" style="width: 100%; border-radius: 0px;">
                    <div class="modal-header" style="padding: 0.75rem 1rem; background-color: #d9d9d9; ">
                        <h5 class="modal-title" id="studentAccountName" style="font-size: 1rem;">Student Name
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <!-- Top Section with Photo and Info -->
                        <div class="row g-0">
                            <div class="col-3 border-end">
                                <!-- Student Info Section -->
                                <div class="p-3" style="position: sticky; top: 0;">
                                    <div class="card border-1 shadow-sm">
                                        <div class="card-body">
                                            <div class="text-center mb-4">
                                                <img id="studentPhoto" src="{{ asset('avatar/S(F) 1.png') }}"
                                                    alt="" class="img-fluid rounded-circle bg-light"
                                                    style="max-width: 120px; height: 120px; object-fit: cover; display: inline-block;">
                                            </div>
                                            <div class="student-info">
                                                <div
                                                    class="mb-3 d-flex justify-content-between align-items-center border-bottom pb-2">
                                                    <div style="font-weight: 600;">ID Number</div>
                                                    <div id="studentId" class="text-end">11220322</div>
                                                </div>
                                                <div
                                                    class="mb-3 d-flex justify-content-between align-items-center border-bottom pb-2">
                                                    <div style="font-weight: 600;">Level/Section</div>
                                                    <div id="levelSection" class="text-end">
                                                    </div>
                                                </div>
                                                <div
                                                    class="mb-3 d-flex justify-content-between align-items-center border-bottom pb-2">
                                                    <div style="font-weight: 600;">Course/Strand</div>
                                                    <div id="courseStrand" class="text-end"></div>
                                                </div>
                                                <div
                                                    class="mb-3 d-flex justify-content-between align-items-center border-bottom pb-2">
                                                    <div style="font-weight: 600;">Scholarship</div>
                                                    <div id="scholarship" class="text-end"></div>
                                                </div>
                                                <div class="mb-2 d-flex justify-content-between align-items-center">
                                                    <div style="font-weight: 600;">Student Status</div>
                                                    <div id="studentStatus" class="text-end"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card border-0 shadow-sm" style="font-size: 13px;">
                                        <div class="card-body p-3">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="allowNoDownpayment"
                                                    checked>
                                                <label class="form-check-label" for="allowNoDownpayment"
                                                    style="font-weight: 600;">
                                                    Allow No Downpayment
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="examinationPermit">
                                                <label class="form-check-label" for="examinationPermit"
                                                    style="font-weight: 600;">
                                                    Examination Permit
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-9 mt-3">
                                <!-- Navigation Tabs -->
                                <div class="sticky-top" style="position: sticky; top: 0;">
                                    <ul class="nav nav-tabs nav-fill border-0" style="background-color: #f8f9fa;">
                                        <li class="nav-item">
                                            <a class="nav-link py-2 px-4 rounded-0 border-0 active" data-toggle="tab"
                                                href="#studentLedger">
                                                Student Ledger
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link py-2 px-4 rounded-0 border-0" data-toggle="tab"
                                                href="#oldAccounts">
                                                OLD Accounts
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link py-2 px-4 rounded-0 border-0" data-toggle="tab"
                                                href="#studentInformation">
                                                Student Information
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link py-2 px-4 rounded-0 border-0" data-toggle="tab"
                                                href="#studentLoads">
                                                Student Loads
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link py-2 px-4 rounded-0 border-0" data-toggle="tab"
                                                href="#bookledger">
                                                Book Ledger
                                            </a>
                                    </ul>
                                    <hr style="border-color: #d3d3d3; border-width: 5px; margin-top: 0; margin-bottom: 0;">
                                </div>

                                <!-- Tab Content -->
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="studentLedger" role="tabpanel"
                                        aria-labelledby="studentLedger-tab">
                                        @include('finance_v2.pages.setup.student_ledger')
                                    </div>

                                    <div class="tab-pane fade show" id="oldAccounts" role="tabpanel"
                                        aria-labelledby="oldAccounts-tab">
                                        @include('finance_v2.pages.setup.old_account')
                                    </div>

                                    <div class="tab-pane fade show" id="studentInformation" role="tabpanel"
                                        aria-labelledby="studentInformation-tab">
                                        @include('finance_v2.pages.setup.student_information')
                                    </div>

                                    <div class="tab-pane fade show" id="studentLoads" role="tabpanel"
                                        aria-labelledby="studentLoads-tab">
                                        @include('finance_v2.pages.setup.student_loads')
                                    </div>

                                    <div class="tab-pane fade show" id="bookledger" role="tabpanel"
                                        aria-labelledby="bookledger-tab">
                                        @include('finance_v2.pages.setup.book_ledger')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- refund modal --}}
    {{-- <div class="modal fade" id="disbursementModal" tabindex="-1" data-backdrop="static" role="dialog"
        aria-labelledby="disbursementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                <div class="modal-header" style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                    <h5 class="modal-title">Disbursement</h5>
                    <button type="button" id="close_refund_modal" class="close close_modal_fees" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="disbursement_form">
                        <!-- Disbursement Type & Date -->
                        <div class="form-group row mb-3">
                            <label class="col-sm-3 col-form-label"
                                style="font-weight: normal; font-size: 0.8rem">Disbursement Type:</label>
                            <div class="col-sm-6 d-flex align-items-center">
                                <div class="form-check mr-3">
                                    <input class="form-check-input" type="checkbox" id="expense_type" value="expenses"
                                        disabled>
                                    <label class="form-check-label" style="font-weight: normal; font-size: 0.8rem"
                                        for="expense_type">Expenses</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="student_refund"
                                        value="students_refund" checked>
                                    <label class="form-check-label" style="font-weight: normal; font-size: 0.8rem"
                                        for="student_refund">Students
                                        Refund</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <input type="date" class="form-control" id="disbursement_date">
                            </div>
                        </div>


                        <div class="form-row align-items-center">
                            <!-- Voucher No. -->
                            <div class="col-md-3">
                                <label for="voucher_no" style="font-size: 0.8rem; font-weight: normal; ">Voucher
                                    No.</label>
                                <input type="text" class="form-control" id="voucher_no" placeholder="CSV/CHV 0000">
                            </div>

                            <!-- Student No. -->
                            <div class="col-md-3">
                                <label for="student_no" style="font-size: 0.8rem; font-weight: normal; ">Student
                                    No.</label>
                                <input type="text" class="form-control" id="student_no" placeholder="ID 112300212"
                                    disabled>
                            </div>

                            <!-- Refund To -->
                            <div class="col-md-4">
                                <label for="refund_to" style="font-size: 0.8rem; font-weight: normal;">Refund
                                    To</label>
                                <div class="input-group">
                                    <select class="form-control" id="refund_to">
                                        <option selected style="font-weight: normal; font-size: 0.8rem">Abcede,
                                            Raymund Jake T.</option>
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary">
                                            <i class="fa fa-user-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Grade Level -->
                            <div class="col-md-2">
                                <label for="grade_level" style="font-size: 0.8rem; font-weight: normal">Grade
                                    Level</label>
                                <input type="text" class="form-control" id="grade_level" value="Grade 7" disabled>
                            </div>
                        </div>

                        <!-- Reimbursement Type -->
                        <div class="form-group row mb-3 mt-3">
                            <label class="col-3 col-form-label"
                                style="font-weight: normal; font-size: 0.8rem">Reimbursement Type</label>
                            <div class="col-9">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="reimbursement_type"
                                        id="cash_check_reimbursement" value="cash_check" checked>
                                    <label class="form-check-label" for="cash_check_reimbursement"
                                        style="font-weight: normal; font-size: 0.8rem">Cash/Check
                                        Reimbursement</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="reimbursement_type"
                                        id="forward_next_year" value="forward">
                                    <label class="form-check-label" for="forward_next_year"
                                        style="font-weight: normal; font-size: 0.8rem">Forward to Next School
                                        Year</label>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Section (Initially Visible) -->
                        <div id="payment_section">
                            <div class="form-row align-items-center">
                                <!-- Payment Type -->
                                <div class="col-md-3">
                                    <label style="font-weight: normal; font-size: 0.8rem">Payment Type</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="payment_type"
                                            id="cash_payment" value="cash" checked>
                                        <label class="form-check-label" for="cash_payment"
                                            style="font-weight: normal; font-size: 0.8rem">CASH</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="payment_type"
                                            id="cheque_payment" value="cheque">
                                        <label class="form-check-label" for="cheque_payment"
                                            style="font-weight: normal; font-size: 0.8rem;">CHEQUE</label>
                                    </div>
                                </div>

                                <!-- Amount -->
                                <div class="col-md-2">
                                    <label for="amount" style="font-weight: normal; font-size: 0.8rem">Amount</label>
                                    <input type="text" style="font-weight: normal; font-size: 0.8rem"
                                        class="form-control" id="amount" value="PHP 3,000.00">
                                </div>

                                <!-- Bank -->
                                <div class="col-md-2">
                                    <label for="bank_select" style="font-weight: normal; font-size: 0.8rem">Bank</label>
                                    <select class="form-control" id="bank_select" disabled>
                                        <option style="font-weight: normal; font-size: 0.8rem">Select Bank</option>
                                    </select>
                                </div>

                                <!-- Cheque No. -->
                                <div class="col-md-3">
                                    <label for="cheque_no" style="font-weight: normal; font-size: 0.8rem">Cheque
                                        No.</label>
                                    <input type="text" class="form-control" id="cheque_no" disabled>
                                </div>

                                <!-- Cheque Date -->
                                <div class="col-md-2">
                                    <label for="cheque_date" style="font-weight: normal; font-size: 0.8rem">Cheque
                                        Date</label>
                                    <input type="date" style="font-weight: normal; font-size: 0.8rem"
                                        class="form-control" id="cheque_date" disabled>
                                </div>
                            </div>
                        </div>



                        <div id="payment_details" class="form-row d-none">
                            <!-- Amount -->
                            <div class="col-md-3">
                                <label for="amount" style="font-weight: normal; font-size: 0.8rem">Amount</label>
                                <input type="text" style="font-weight: normal; font-size: 0.8rem"
                                    class="form-control" id="amount" value="PHP 3,000.00">
                            </div>

                            <!-- School Year -->
                            <div class="col-md-3">
                                <label for="school_year" style="font-weight: normal; font-size: 0.8rem">School
                                    Year</label>
                                <select class="form-control" id="school_year">
                                    <option style="font-weight: normal; font-size: 0.8rem">School Year</option>
                                </select>
                            </div>

                            <!-- Semester -->
                            <div class="col-md-3">
                                <label for="semester" style="font-weight: normal; font-size: 0.8rem">Semester</label>
                                <select class="form-control" id="semester">
                                    <option style="font-weight: normal; font-size: 0.8rem">1st Semester</option>
                                </select>
                            </div>

                            <!-- Apply To -->
                            <div class="col-md-3">
                                <label for="apply_to" style="font-weight: normal; font-size: 0.8rem">Apply
                                    To</label>
                                <select class="form-control" id="apply_to">
                                    <option style="font-weight: normal; font-size: 0.8rem">Tuition</option>
                                </select>
                            </div>
                        </div>




                        <!-- Remarks -->
                        <div class="form-group row mt-3 mb-3 p-3">
                            <div class="col-sm-12">
                                <label class="h5 font-weight-bold"
                                    style="font-weight: normal; font-size: 0.8rem">Remarks/Explanation</label>
                            </div>
                            <div class="col-sm-12">
                                <textarea class="form-control" id="remarks" placeholder="Enter Description Here"
                                    style="font-weight: normal; font-size: 0.8rem"></textarea>
                            </div>
                        </div>

                    </form>
                </div>


                <div class="modal-footer">
                    <button type="button" id="save" class="btn btn-success"
                        style="font-weight: normal; font-size: 0.8rem">Save</button>
                    <button type="button" id="print" class="btn btn-secondary"
                        style="font-weight: normal; font-size: 0.8rem">Print</button>
                </div>
            </div>
        </div>
    </div> --}}
    
    <!-- Credit Adjustment Modal -->
    {{-- <div class="modal fade" id="creditAdjustmentModal" tabindex="-1" aria-labelledby="creditAdjustmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="creditAdjustmentModalLabel">Credit (-) Adjustment</p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: normal; font-size: 0.8rem;">Adjustment To:
                            Grade 7 - Section (All)</label>
                        <br>
                        <label class="form-label" style="font-weight: normal; font-size: 0.8rem;">Applied To: 13
                            Students</label>
                    </div>
                    <div class="mb-3" style="font-size: 0.8rem;">
                        <label class="form-label" style="font-weight: normal;">Classification</label>
                        <br>
                        <select class="form-select w-50 h-85" id="classificationSelect">
                            <option value="">Select Classification</option>
                            <option value="tuition">Tuition</option>
                            <option value="misc">Miscellaneous</option>
                            <option value="other">Other Fees</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: normal; font-size: 0.8rem;">Amount</label>
                        <input type="number" class="form-control w-50" placeholder="0.00" step="0.01">
                    </div>
                    <div class="mb-3">
                        <button type="button" class="btn btn-link text-primary p-0" id="addItem1Btn"
                            style="font-size: 0.8rem;">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>

                    <!-- Items Table -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm">
                            <thead class="table-dark text-dark" style="font-size: 0.8rem; background-color: #d3d3d3;">
                                <tr>
                                    <th style="font-weight: normal; border: black;">Particulars</th>
                                    <th style="font-weight: normal; border: black;">Principal Amount</th>
                                    <th style="font-weight: normal; border: black;">Adjusted Amount</th>
                                    <th style="font-weight: normal; border: black;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="font-size: 0.8rem;">
                                    <td>PE Uniform</td>
                                    <td>570.00</td>
                                    <td>300.00</td>
                                    <td><button class="btn btn-link text-danger p-0"
                                            style="font-size: 0.8rem; text-decoration: underline;">Cancel</button>
                                    </td>
                                </tr>
                                <tr style="font-size: 0.8rem;">
                                    <td>ID Sling</td>
                                    <td>120.00</td>
                                    <td>0.00</td>
                                    <td><button class="btn btn-link text-danger p-0"
                                            style="font-size: 0.8rem; text-decoration: underline;">Cancel</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-info mt-3" style="font-size: 0.8rem;" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        The Selected items will be <strong>Deducted</strong> from the payables to all Selected students
                        under (Other Fees) Classification
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btn-sm text-center">Save</button>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Add Item Modal -->
    <div class="modal fade" id="addItem1Modal" tabindex="-1" aria-labelledby="addItem1ModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItem1ModalLabel">Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3" style="font-size: 0.8rem;">
                        <label class="form-label w-100">Item</label>
                        <br>
                        <select class="form-select" id="itemTypeSelect">
                            <option value="">Select Item Type</option>

                            <option value="tv_damage">TV Damage</option>
                            <option value="test_paper">Test Paper</option>
                            <option value="id_sling">ID Sling</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" style="font-weight: normal; font-size: 0.8rem;">Principal
                                    Amount</label>
                                <input type="number" class="form-control bg-light" style="font-size: 0.8rem;"
                                    value="570.00" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" style="font-weight: normal; font-size: 0.8rem;">Adjustment
                                    Amount</label>
                                <input type="number" style="font-size: 0.8rem;" class="form-control" style
                                    placeholder="0.00" step="0.01">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3" style="font-size: 0.8rem;" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        The Selected items will be <span class="fw-bold">Added</span> to the payables to all
                        Selected
                        students under (Other Fees) Classification
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btn-xs text-center">Save</button>
                </div>
            </div>
        </div>
    {{-- </div> --}}

    {{-- Discount Modal --}}
    <div class="modal fade" id="DiscountModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="DiscountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 60%">
            <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                <div class="modal-header" style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                    <h5 class="modal-title">Discount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label style="font-weight: normal; font-size: 0.9rem"> Discounted to: Grade 7 - Section
                        (All)</label>
                    <div class="mb-3">
                        <button class="btn btn-xs" style="background-color: #005728; color: white" data-toggle="modal"
                            data-target="#addDiscountModal"><i class="fas fa-plus"></i>
                            Discount</button>
                    </div>


                    <!-- Students Table -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm">
                            <thead class="table-dark text-dark" style="font-size: 0.8rem; background-color: #d3d3d3;">
                                <tr>
                                    <th style="font-weight: normal; border: black;">Student ID</th>
                                    <th style="font-weight: normal; border: black;">Student Name</th>
                                    <th style="font-weight: normal; border:  black;">Tuition Payable</th>
                                    <th style="font-weight: normal; border:  black;">Payment</th>
                                    <th style="font-weight: normal; border:  black;">Balance</th>
                                    <th style="font-weight: normal; border:  black;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Male Students -->
                                <tr class="table table-bordered table-sm" style="font-size: 0.8rem;">
                                    <td style="font-weight: normal;">Male</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>



                                </tr>
                                <tr style="font-size: 0.8rem;">
                                    <td>112003022</td>
                                    <td>Adollantes, Rudolph Vann</td>
                                    <td>40,969.07</td>
                                    <td>12,033.50</td>
                                    <td>28,935.57</td>
                                    <td><button class="btn btn-danger btn-xs exclude-btn"
                                            style="border-radius: 6rem;">Exclude</button></td>
                                </tr>
                                <!-- Male Students -->
                                <tr class="table table-bordered table-sm" style="font-size: 0.8rem;">
                                    <td style="font-weight: normal;">Female</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                </tr>
                                <tr style="font-size: 0.8rem;">
                                    <td>112003022</td>
                                    <td>Basor, Elisha Joy</td>
                                    <td>40,969.07</td>
                                    <td>12,033.50</td>
                                    <td>28,935.57</td>
                                    <td><button class="btn btn-danger btn-xs exclude-btn"
                                            style="border-radius: 6rem;">Exclude</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Discount History -->
                    <div class="mb-3" style="font-size: 0.8rem; margin-bottom: 1rem;">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <h6 class="mb-3" style="font-size: 0.9rem; margin-right: 1rem; font-weight: normal;">
                                Discount History </h6>

                        </div>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm">
                                <thead class="table-dark text-dark"
                                    style="font-size: 0.8rem; background-color: #d3d3d3;">
                                    <tr>
                                        <th style="font-weight: normal; border: black;">Student ID</th>
                                        <th style="font-weight: normal; border: black;">Student Name</th>
                                        <th style="font-weight: normal; border: black;">Discount Type</th>
                                        <th style="font-weight: normal; border: black;">Classification/Item</th>
                                        <th style="font-weight: normal; border: black;">Amount/%</th>
                                        <th style="font-weight: normal; border: black;">Date</th>
                                        <th style="font-weight: normal; border: black;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>112003022</td>
                                        <td>Saromenes, Hazel Ann</td>
                                        <td> Full Payment Discont</td>
                                        <td>Other Fees (Educ. Trip)</td>
                                        <td>2,000.00</td>
                                        <td>12-12-2024</td>
                                        <td><button class="btn btn-warning btn-sm"
                                                style="border-radius: 6rem; font-size: 0.8rem;" data-toggle="modal"
                                                data-target="#pinModal">Void</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- Add discount modal --}}
                <div class="modal fade" id="addDiscountModal" data-backdrop="static" tabindex="-1" role="dialog"
                    aria-labelledby="addDiscountModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 30%;">
                        <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">

                            {{-- Modal Header --}}
                            <div class="modal-header"
                                style="background-color: #d9d9d9; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                                <h5 class="modal-title">Discount</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>

                            {{-- Modal Body --}}
                            <div class="modal-body">
                                {{-- Discount Type Dropdown --}}
                                <div class="mb-3">
                                    <h6 class="font-weight-normal" style="font-size: 0.8rem;">Discount Type</h6>
                                    <select class="form-control"
                                        style="height: 32px; border-color: #a2a2a2 !important;">
                                        <option selected disabled>Select Discount Type</option>
                                    </select>
                                </div>

                                {{-- Discounted To & Applied To --}}
                                <p class="mb-1" style="font-size: 0.9rem;">
                                    Discounted To: <span class="text-success">Grade 7 - Section (All)</span>
                                </p>
                                <p class="mb-3" style="font-size: 0.9rem;">
                                    Applied To: <span class="text-success">13 Students</span>
                                </p>

                                {{-- Discount Card --}}
                                <div class="card shadow-sm border-0"
                                    style="background-color: #E5E5E5; border-radius: 12px; width: 250px;">
                                    <div class="p-3">
                                        <button type="button" class="close position-absolute"
                                            style="top: 8px; right: 10px;" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h6 class="fw-bold mb-1">Full Payment Discount (10%)</h6>
                                        <p class="mb-1" style="font-size: 0.8rem;">
                                            Applied to: <span class="text-success fw-bold">Classification
                                                (Tuition)</span>
                                        </p>
                                        <p class="mb-1" style="font-size: 0.8rem;">
                                            Discount as: <span class="text-success fw-bold">Whole Amount</span>
                                        </p>
                                    </div>
                                    <div class="bg-white text-center p-2 border-top"
                                        style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                                        <a href="#" class="text-primary fw-bold text-decoration-none"
                                            style="font-size: 0.8rem">
                                            View Details <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>


                                {{-- Notice Box --}}
                                <div class="d-flex align-items-center p-2 mt-3"
                                    style="background: #f8f9fa; border-radius: 8px; border: 1px solid #ddd;">
                                    <i class="fas fa-info-circle mr-2" style="color: #6c757d;"></i>
                                    <p class="mb-0" style="font-size: 0.6rem;">
                                        The Selected Discount will be <span class="text-danger">Deducted</span> from
                                        the payables to all selected students applied only to (Tuition) Classification.
                                    </p>
                                </div>
                            </div>

                            {{-- Modal Footer --}}
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success w-100" style="border-radius: 8px;">Add
                                    Discount</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Book Entry Modal -->
    {{-- <div class="modal fade" id="bookEntry1Modal" tabindex="-1" aria-labelledby="bookEntry1ModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" style="width: 62%;">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #d3d3d3;">
                    <h5 class="modal-title" id="bookEntry1ModalLabel" style="font-size: 1rem;">Book Entry</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label"
                                    style="font-size: 0.8rem; font-weight: normal; color: #000000;">Entry Books
                                    To:
                                    Grade 7 - Section (All)</label>
                            </div>
                        </div>
                    </div>

                    <!-- Add Book Button -->
                    <div class="mb-3">
                        <button id="addBook" class="btn btn-xs me-2" style="" data-toggle="modal"
                            data-target="#addBookModal"><i class="fas fa-plus"></i> Add Books</button>
                    </div>

                    <!-- Students Table -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm">
                            <thead class="table-dark text-dark" style="font-size: 0.8rem; background-color: #d3d3d3;">
                                <tr>
                                    <th style="font-weight: normal; border: black;">Student ID</th>
                                    <th style="font-weight: normal; border: black;">Student Name</th>
                                    <th style="font-weight: normal; border:  black;">Tuition Payable</th>
                                    <th style="font-weight: normal; border:  black;">Payment</th>
                                    <th style="font-weight: normal; border:  black;">Balance</th>
                                    <th style="font-weight: normal; border:  black;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Male Students -->
                                <tr class="table table-bordered table-sm" style="font-size: 0.8rem;">
                                    <td style="font-weight: normal;">Male</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>



                                </tr>
                                <tr style="font-size: 0.8rem;">
                                    <td>112003022</td>
                                    <td>Adollantes, Rudolph Vann</td>
                                    <td>40,969.07</td>
                                    <td>12,033.50</td>
                                    <td>28,935.57</td>
                                    <td><button class="btn btn-danger btn-xs exclude-btn"
                                            style="border-radius: 6rem;">Exclude</button></td>
                                </tr>
                                <!-- Male Students -->
                                <tr class="table table-bordered table-sm" style="font-size: 0.8rem;">
                                    <td style="font-weight: normal;">Female</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                </tr>
                                <tr style="font-size: 0.8rem;">
                                    <td>112003022</td>
                                    <td>Basor, Elisha Joy</td>
                                    <td>40,969.07</td>
                                    <td>12,033.50</td>
                                    <td>28,935.57</td>
                                    <td><button class="btn btn-danger btn-xs exclude-btn"
                                            style="border-radius: 6rem;">Exclude</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm">
                            <thead class="table-dark text-dark" style="font-size: 0.8rem; background-color: #d3d3d3;">
                                <tr>
                                    <th style="font-weight: normal; border: black;">Student ID</th>
                                    <th style="font-weight: normal; border: black;">Student Name</th>
                                    <th style="font-weight: normal; border: black;">Discount Type</th>
                                    <th style="font-weight: normal; border: black;">Classification Item</th>
                                    <th style="font-weight: normal; border: black;">Amount/%</th>
                                    <th style="font-weight: normal; border: black;">Date</th>
                                    <th style="font-weight: normal; border: black;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="font-size: 0.8rem;">112003022</td>
                                    <td style="font-size: 0.8rem;">Saromenes, Hazel Ann</td>
                                    <td style="font-size: 0.8rem;">Full Payment Discount</td>
                                    <td style="font-size: 0.8rem;">Item</td>
                                    <td style="font-size: 0.8rem;">2,000.00</td>
                                    <td style="font-size: 0.8rem;">12-12-2024</td>
                                    <td><button class="btn btn-warning btn-sm"
                                            style="border-radius: 6rem; font-size: 0.8rem;" data-toggle="modal"
                                            data-target="#pinModal">Void</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Books Modal -->
        <div class="modal fade" id="addBookModal" data-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="addBookModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                    <div class="modal-header" style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                        <label class="modal-title">Book Entry</label>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">


                        <!-- Classification -->
                        <div class="form-group">
                            <label for="classification">Classification</label>
                            <select class="form-control" id="classification">
                                <option selected="Books Fee">Books Fee</option>
                            </select>
                        </div>

                        <!-- Amount -->
                        <div class="form-group d-flex align-items-center">
                            <label for="amount" class="mr-3 mb-0">Amount</label>
                            <input type="text" class="form-control" id="amount" value="0.00"
                                style="max-width: 150px;">

                            <div class="form-check ml-3">
                                <input type="checkbox" class="form-check-input" id="itemized_books_payable">
                                <label class="form-check-label" for="itemized_books_payable">Itemized Books
                                    Payable</label>
                            </div>
                        </div>




                        <!-- Table (Initially Hidden) -->
                        <div id="books_table" class="mt-3 d-none">
                            <label for="select_books">Select Books</label>
                            <select class="form-control mb-2" id="select_books"></select>

                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Book Name</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-right font-weight-bold">Total</td>
                                        <td class="font-weight-bold">0</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>


                        <!-- Checkboxes -->
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="display_books">
                                <label class="form-check-label" for="display_books">Display to Cashier as
                                    Books</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="display_itemized">
                                <label class="form-check-label" for="display_itemized">Display to Cashier as
                                    Itemized</label>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="text-center">
                            <button type="button" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div> --}}

    <!-- Old Accounts Forwarding Modal -->
    {{-- <div class="modal fade" id="oldAccountsForwardingModal" tabindex="-1" --}}
        aria-labelledby="oldAccountsForwardingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" style="width: 70%; margin-top: 0; border-radius: 0px;">
            <div class="modal-content" style="border-radius: 0;">
                <div class="modal-header" style="padding: 10px; background-color: #d9d9d9;">
                    <h6 class="modal-title" id="oldAccountsForwardingModalLabel"
                        style="font-size: 0.875rem; font-weight: bold; margin-left: 12px;">Old Accounts Forwarding
                    </h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6 col-12 ">
                                <p style="font-size:12px">Select School Year/Semester to Forward Old Accounts</p>
                                <div class="card" style="box-shadow: none; padding: 10px;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label for="fromSchoolYear"
                                            style="font-size: 12.5px; font-weight: normal; margin-right: 12px; margin-top: 5px;"
                                            class="me-2">School Year</label>
                                        <select class="form-select me-3 p-1" id="fromSchoolYear"
                                            style="font-size: 12.5px; border-radius: 5px; margin-right: 12px;">
                                            <option selected>2024-2025</option>
                                        </select>
                                        <label for="fromSemester"
                                            style="font-size: 12.5px; margin-right: 12px; font-weight: normal; margin-top: 5px;"
                                            class="me-2">Semester</label>
                                        <select class="form-select p-1"
                                            style="font-size: 12.5px; border-radius: 5px; margin-right: 12px;"
                                            id="fromSemester">
                                            <option selected>1st Semester</option>
                                        </select>
                                        <span
                                            style="font-size: 12px; font-weight: bold; position: absolute; top: -20px; right: 5px; font-style: oblique;">(FROM)</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12" style="margin-top: 3.7%;">
                                <div class="card" style="padding: 10px;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label for="toSchoolYear"
                                            style="font-size: 12.5px; font-weight: normal; margin-right: 12px; margin-top: 5px;"
                                            class="me-2">School Year</label>
                                        <select class="form-select me-3 p-1" id="toSchoolYear"
                                            style="font-size: 12.5px; margin-right: 12px; border-radius: 5px;">
                                            <option selected>2024-2025</option>
                                        </select>
                                        <label for="toSemester"
                                            style="font-size: 12.5px; font-weight: normal; margin-right: 12px; margin-top: 5px;"
                                            class="me-2">Semester</label>
                                        <select class="form-select p-1" id="toSemester"
                                            style="font-size: 12.5px; margin-right: 12px; border-radius: 5px;">
                                            <option selected>1st Semester</option>
                                        </select>
                                        <span
                                            style="font-size: 12px; font-weight: bold; font-style: oblique; position: absolute; top: -20px; right: 5px;">(TO)</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div style="font-size: 13px;">
                                    <span><strong>Grade 7</strong> (13 Students)</span>
                                </div>
                                <button class="btn btn-success btn-xs" style="font-size: 12.5px;">
                                    <i class="fas fa-share-square" style="font-weight: 400;"></i> Forward All
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr style="font-size:13px; background-color: #b9b9b9">
                                        <th style="font-weight: 600; border: 1px solid #8e8e8e;">Student ID</th>
                                        <th style="font-weight: 600; border: 1px solid #8e8e8e;">Student Name</th>
                                        <th style="font-weight: 600; border: 1px solid #8e8e8e;">Tuition Payable
                                        </th>
                                        <th style="font-weight: 600; border: 1px solid #8e8e8e;">Payment</th>
                                        <th style="font-weight: 600; border: 1px solid #8e8e8e;">Balance</th>
                                        <th style="font-weight: 600; border: 1px solid #8e8e8e;">Action</th>
                                        <th style="font-weight: 600; padding-left: 1.5rem; border: 1px solid #8e8e8e;">
                                            Forward Old Accounts</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 13px;">

                                </tbody>
                            </table>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-xs btn-danger">
                                <i class="fas fa-times-circle"></i> Cancel
                            </button>
                            <button type="button" class="btn btn-xs btn-success">
                                <i class="fas fa-check-circle"></i> Apply Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Ledger Adjust Fees Modal -->
            <div class="modal fade" id="debitAdjustmentModal" tabindex="-1"
                aria-labelledby="debitAdjustmentModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <p class="modal-title" id="debitAdjustmentModalLabel">Student</p>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" style="font-weight: normal; font-size: 0.8rem;">Adjustment
                                    To:
                                    Grade 7 - Section (All)</label>
                                <br>
                                <label class="form-label" style="font-weight: normal; font-size: 0.8rem;">Applied
                                    To: 13
                                    Students</label>
                            </div>
                            <div class="mb-3" style="font-size: 0.8rem;">
                                <label class="form-label" style="font-weight: normal;">Classification</label>
                                <br>
                                <select class="form-select w-50 h-85" id="classificationSelect">
                                    <option value="">Select Classification</option>
                                    <option value="tuition">Tuition</option>
                                    <option value="misc">Miscellaneous</option>
                                    <option value="other">Other Fees</option>.
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-weight: normal; font-size: 0.8rem;">Amount</label>
                                <input type="number" class="form-control w-50" placeholder="0.00" step="0.01">
                            </div>
                            <div class="mb-3">
                                <button type="button" class="btn btn-link text-primary p-0" id="addItemBtn"
                                    style="font-size: 0.8rem;"><i class="fas fa-plus"></i> Add Item</button>
                            </div>

                            <!-- Items Table -->
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-dark text-dark"
                                        style="font-size: 0.8rem; background-color: #d3d3d3;">
                                        <tr>
                                            <th style="font-weight: normal; border: black;">Particulars</th>
                                            <th style="font-weight: normal; border: black;">Principal Amount</th>
                                            <th style="font-weight: normal; border: black;">Adjusted Amount</th>
                                            <th style="font-weight: normal; border: black;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="font-size: 0.8rem;">
                                            <td>TV Damage</td>
                                            <td>-</td>
                                            <td>1,200.00</td>
                                            <td><button class="btn btn-link text-danger p-0"
                                                    style="font-size: 0.8rem; text-decoration: underline;">Cancel</button>
                                            </td>
                                        </tr>
                                        <tr style="font-size: 0.8rem;">
                                            <td>Test Paper</td>
                                            <td>-</td>
                                            <td>150.00</td>
                                            <td><button class="btn btn-link text-danger p-0"
                                                    style="font-size: 0.8rem; text-decoration: underline;">Cancel</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="alert alert-info mt-3" style="font-size: 0.8rem;" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                The Selected items will be <strong>Added</strong> to the payables to all Selected
                                students
                                under (Other Fees) Classification
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success btn-sm text-center">Save</button>
                        </div>

                    </div>

                </div>


            </div>

            {{-- Student Ledger add discount modal --}}
            {{-- Add discount modal --}}
            <div class="modal fade" id="addDiscount1Modal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="addDiscount1ModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 30%;">
                    <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">

                        {{-- Modal Header --}}
                        <div class="modal-header"
                            style="background-color: #d9d9d9; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                            <h5 class="modal-title">Discount</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>

                        {{-- Modal Body --}}
                        <div class="modal-body">
                            {{-- Discount Type Dropdown --}}
                            <div class="mb-3">
                                <h6 class="font-weight-normal" style="font-size: 0.8rem;">Discount Type</h6>
                                <select class="form-control" style="height: 32px; border-color: #a2a2a2 !important;">
                                    <option selected disabled>Select Discount Type</option>
                                </select>
                            </div>

                            {{-- Discounted To & Applied To --}}
                            <p class="mb-1" style="font-size: 0.9rem;">
                                Discounted To: <span class="text-success">Grade 7 - Section (All)</span>
                            </p>
                            <p class="mb-3" style="font-size: 0.9rem;">
                                Applied To: <span class="text-success">13 Students</span>
                            </p>

                            {{-- Discount Card --}}
                            <div class="card shadow-sm border-0"
                                style="background-color: #E5E5E5; border-radius: 12px; width: 250px;">
                                <div class="p-3">
                                    <button type="button" class="close position-absolute"
                                        style="top: 8px; right: 10px;" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h6 class="fw-bold mb-1">Full Payment Discount (10%)</h6>
                                    <p class="mb-1" style="font-size: 0.8rem;">
                                        Applied to: <span class="text-success fw-bold">Classification
                                            (Tuition)</span>
                                    </p>
                                    <p class="mb-1" style="font-size: 0.8rem;">
                                        Discount as: <span class="text-success fw-bold">Whole Amount</span>
                                    </p>
                                </div>
                                <div class="bg-white text-center p-2 border-top"
                                    style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                                    <a href="#" class="text-primary fw-bold text-decoration-none"
                                        style="font-size: 0.8rem">
                                        View Details <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>


                            {{-- Notice Box --}}
                            <div class="d-flex align-items-center p-2 mt-3"
                                style="background: #f8f9fa; border-radius: 8px; border: 1px solid #ddd;">
                                <i class="fas fa-info-circle mr-2" style="color: #6c757d;"></i>
                                <p class="mb-0" style="font-size: 0.6rem;">
                                    The Selected Discount will be <span class="text-danger">Deducted</span> from
                                    the payables to all selected students applied only to (Tuition) Classification.
                                </p>
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success w-100" style="border-radius: 8px;">Add
                                Discount</button>
                        </div>

                    </div>
                </div>
            </div>



        </div>

    {{-- </div> --}}

    <!-- PIN Modal -->
    {{-- <div class="modal fade" id="pinModal" tabindex="-1" role="dialog" aria-labelledby="pinModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document" style="max-width: 25%;">
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <!-- PIN Entry -->
                    <div id="pinEntry">
                        <h5>Enter Pin</h5>
                        <input type="password" class="form-control form-control-lg mb-3 text-center" placeholder="***"
                            disabled>

                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-4"><button class="btn btn-secondary btn-block btn-lg mb-2">1</button>
                                </div>
                                <div class="col-4"><button class="btn btn-secondary btn-block btn-lg mb-2">2</button>
                                </div>
                                <div class="col-4"><button class="btn btn-secondary btn-block btn-lg mb-2">3</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4"><button class="btn btn-secondary btn-block btn-lg mb-2">4</button>
                                </div>
                                <div class="col-4"><button class="btn btn-secondary btn-block btn-lg mb-2">5</button>
                                </div>
                                <div class="col-4"><button class="btn btn-secondary btn-block btn-lg mb-2">6</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4"><button class="btn btn-secondary btn-block btn-lg mb-2">7</button>
                                </div>
                                <div class="col-4"><button class="btn btn-secondary btn-block btn-lg mb-2">8</button>
                                </div>
                                <div class="col-4"><button class="btn btn-secondary btn-block btn-lg mb-2">9</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4"><button class="btn btn-secondary btn-block btn-lg mb-2">Del</button>
                                </div>
                                <div class="col-4"><button class="btn btn-secondary btn-block btn-lg mb-2">0</button>
                                </div>
                                <div class="col-4"><button class="btn btn-secondary btn-block btn-lg mb-2">.</button>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-primary btn-block btn-lg mt-3">Enter</button>


                        <p class="mt-2 mb-0 small">Ask your Finance Admin for a PIN to continue</p>
                        <p><a href="#" class="text-primary" onclick="toggleForms()">Try Another way</a></p>
                    </div>

                    <!-- Username & Password Entry (Hidden Initially) -->
                    <div id="authEntry" style="display: none;">
                        <h5>Finance Verification Username & Password</h5>
                        <input type="text" class="form-control form-control-lg mb-3 text-center"
                            placeholder="Username">
                        <input type="password" class="form-control form-control-lg mb-3 text-center"
                            placeholder="Password">
                        <button class="btn btn-success btn-block btn-lg">Enter</button>

                        <p class="mt-3"><a href="#" class="text-primary" onclick="toggleForms()">←
                                Pin</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Username & Password Modal -->
    <div class="modal fade" id="authModal" tabindex="-1" role="dialog" aria-labelledby="authModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <h5>Finance Verification Username & Password</h5>
                    <input type="text" class="form-control form-control-lg mb-3 text-center"
                        placeholder="Username">
                    <input type="password" class="form-control form-control-lg mb-3 text-center"
                        placeholder="Password">
                    <button class="btn btn-success btn-block btn-lg">Enter</button>

                    <p class="mt-3"><a href="#" class="text-primary" data-toggle="modal"
                            data-target="#pinModal" data-dismiss="modal">← Pin</a></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addNewItemModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label style="font-weight: normal; font-size: 0.8rem;">Item Code</label>
                            <input type="text" class="form-control" placeholder="Enter Item Code" id="itemCode">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label style="font-weight: normal; font-size: 0.8rem;">Item Description</label>
                            <input type="text" class="form-control" placeholder="Enter Item Description"
                                id="itemDescription">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label style="font-weight: normal; font-size: 0.8rem;">Chart of Account</label>
                            <select class="form-select select2" id="coaSelect">
                                <option value="">Select an option</option>
                                @foreach ($coa as $item)
                                    <option value="{{ $item->id }}">{{ $item->account }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <label style="font-weight: normal; font-size: 0.8rem;">Item Type</label>

                        </div>
                        <div class="col-md-4">
                            <div>
                                <input type="checkbox" id="tuitionItem">
                                <label for="tuitionItem" style="font-weight: normal; font-size: 0.8rem;">Tuition
                                    Item</label>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div>
                                <input type="checkbox" id="nonTuitionItem">
                                <label for="nonTuitionItem" style="font-weight: normal; font-size: 0.8rem;">Non-Tuition
                                    Item</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="addItem">Add</button>
                </div>
            </div>
        </div>
    </div>


    </body>
@endsection

@section('footerjavascript')
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            fetchStudentAccounts();
        });

        function fetchStudentAccounts() {
            $.ajax({
                url: "studentaccounts/getstudentaccounts",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if ($.fn.DataTable.isDataTable("#studentAccountsTable")) {
                        $("#studentAccountsTable").DataTable().destroy();
                    }

                    var maleStudents = response.filter(student => student.gender.toUpperCase() === "MALE");
                    var femaleStudents = response.filter(student => student.gender.toUpperCase() === "FEMALE");

                    var formattedData = [];

                    if (maleStudents.length > 0) {
                        formattedData.push({
                            isHeader: true,
                            gender: "MALE"
                        });
                        formattedData = formattedData.concat(maleStudents);
                    }

                    if (femaleStudents.length > 0) {
                        formattedData.push({
                            isHeader: true,
                            gender: "FEMALE"
                        });
                        formattedData = formattedData.concat(femaleStudents);
                    }

                    var table = $("#studentAccountsTable").DataTable({
                        data: formattedData,
                        columns: [{
                                data: null,
                                render: function(data) {
                                    if (data.isHeader) {
                                        return `<strong>${data.gender.toUpperCase()}</strong>`;
                                    }
                                    var statusColors = {
                                        "ENROLLED": "green",
                                        "DROPPED OUT": "red",
                                        "WITHDRAWN": "orange",
                                        "TRANSFERRED IN": "blue",
                                        "TRANSFERRED OUT": "yellow",
                                        "DECEASED": "gray",
                                        "NOT ENROLLED": "white"
                                    };

                                    var bgColor = statusColors[data.student_status] || "white";
                                    var border = data.student_status === "NOT ENROLLED" ?
                                        "border: 1px solid black;" : "";

                                    return `<span style="background-color: ${bgColor}; ${border} width: 15px; height: 15px; display: inline-block; margin-right: 5px;"></span>`;
                                },
                                orderable: false
                            },
                            {
                                data: "sid",
                                render: function(data, type, row) {
                                    return row.isHeader ? "" : data;
                                },
                                orderable: false
                            },
                            {
                                data: null,
                                render: function(data) {
                                    return data.isHeader ? "" :
                                        `${data.lastname}, ${data.firstname} ${data.middlename ?? ''}`;
                                },
                                orderable: false
                            },
                            {
                                data: "levelname",
                                render: function(data, type, row) {
                                    return row.isHeader ? "" : data;
                                },
                                orderable: false
                            },
                            {
                                data: null,
                                render: function(data) {
                                    return data.isHeader ? "" : (data.academicLevel >= 17 ? data
                                        .college_course ?? '' : data.shs_strand ?? '');
                                },
                                orderable: false
                            },
                            {
                                data: null,
                                render: function(data) {
                                    return data.isHeader ? "" : (data.academicLevel >= 17 ? data
                                        .college_section ?? '' : (data.shs_section ?? data
                                            .basic_section ?? ''));
                                },
                                orderable: false
                            },
                            {
                                data: null,
                                defaultContent: "₱0.00",
                                render: function(data, type, row) {
                                    return row.isHeader ? "" : "₱0.00";
                                },
                                orderable: false
                            },
                            {
                                data: null,
                                defaultContent: "₱0.00",
                                render: function(data, type, row) {
                                    return row.isHeader ? "" : "₱0.00";
                                },
                                orderable: false
                            },
                            {
                                data: null,
                                defaultContent: "N/A",
                                render: function(data, type, row) {
                                    return row.isHeader ? "" : "N/A";
                                },
                                orderable: false
                            },
                            {
                                data: "id",
                                render: function(data, type, row) {
                                    return row.isHeader ? "" :
                                        `<span class="text-primary px-2 py-1 view-account" data-id="${data}" data-sectionid="${row.sectionid}" style="cursor: pointer;">View Account</span>`;
                                },
                                orderable: false,
                            },
                        ],
                        createdRow: function(row, data) {
                            if (data.isHeader) {
                                $(row).addClass("gender-header");
                                $(row).css({
                                    "text-align": "center"
                                });
                            }
                        },
                        responsive: true,
                        paging: true,
                        searching: true,
                        ordering: false,
                        lengthMenu: [10, 25, 50, 100],
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching student accounts:", error);
                },
            });
        }
    </script>
@endsection
