@extends('finance.layouts.app')

@section('content')
    @php
        if (!Auth::check()) {
            header('Location: ' . URL::to('/'), true, 302);
            exit();
        }

        $check_refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->select('refid')->first();

        if (auth()->user()->type == 17) {
            $extend = 'superadmin.layouts.app2';
        } elseif (Session::get('currentPortal') == 3) {
            $extend = 'registrar.layouts.app';
        } elseif (Session::get('currentPortal') == 4) {
            $extend = 'finance.layouts.app';
        } elseif (Session::get('currentPortal') == 15 || Session::get('currentPortal') == 41) {
            $extend = 'finance.layouts.app';
        } elseif (Session::get('currentPortal') == 14) {
            $extend = 'deanportal.layouts.app2';
        } elseif (Session::get('currentPortal') == 8) {
            $extend = 'admission.layouts.app2';
        }
        //else if(auth()->user()->type == 3 || auth()->user()->type == 8 ){
        //$extend = 'registrar.layouts.app';
        //}else if(auth()->user()->type == 4){
        //$extend = 'finance.layouts.app';
        //}else if(auth()->user()->type == 15 ){
        //$extend = 'finance.layouts.app';
        //}else if(auth()->user()->type == 14 ){
        //$extend =  'deanportal.layouts.app2';
        elseif (auth()->user()->type == 6) {
            $extend = 'adminportal.layouts.app2';
        } else {
            if (isset($check_refid->refid)) {
                if ($check_refid->refid == 26) {
                    $extend = 'registrar.layouts.app';
                } elseif ($check_refid->refid == 28) {
                    $extend = 'officeofthestudentaffairs.layouts.app2';
                } elseif ($check_refid->refid == 29) {
                    $extend = 'idmanagement.layouts.app2';
                } elseif ($check_refid->refid == 31) {
                    $extend = 'guidance.layouts.app2';
                } elseif ($check_refid->refid == 30) {
                    $extend = 'encoder.layouts.app2';
                } elseif ($check_refid->refid == 35) {
                    $extend = 'tesda.layouts.app2';
                }
            }
        }

        $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
        $semester = DB::table('semester')->get();
        $gradelevel = DB::table('gradelevel')
            ->where('deleted', 0)
            ->whereIn('acadprogid', [6, 8])
            ->orderBy('sortid')
            ->get();
        $course = DB::table('college_courses')->where('deleted', 0)->get();
        $schoolinfo = DB::table('schoolinfo')->value('abbreviation');
        // $StudentSection = DB::table('college_sections')->where('deleted', 0)->get();
        $studStatus = DB::table('studentstatus')->get();

        $teacher = DB::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first();

        $courses = DB::table('teacherdean')
            ->where('teacherdean.deleted', 0)
            ->where('college_colleges.deleted', 0)
            ->where('college_courses.deleted', 0)
            ->where('teacherid', $teacher->id)
            ->join('college_colleges', function ($join) {
                $join->on('teacherdean.collegeid', '=', 'college_colleges.id')->where('college_colleges.deleted', 0);
            })
            ->join('college_courses', function ($join) {
                $join->on('college_colleges.id', '=', 'college_courses.collegeid')->where('college_courses.deleted', 0);
            })
            ->select('college_courses.*')
            ->get();

        // dd( $studSection);

        // if(auth()->user()->type == 16 || Session::get('currentPortal') == 16){

        //       $teacher = DB::table('teacher')
        //                         ->where('tid',auth()->user()->email)
        //                         ->first();

        //       $colleges = DB::table('college_courses')
        //                   ->join('college_colleges',function($join){
        //                         $join->on('college_courses.collegeid','=','college_colleges.id');
        //                         $join->where('college_colleges.deleted',0);
        //                   })
        //                   ->where('courseChairman',$teacher->id)
        //                   ->where('college_courses.deleted',0)
        //                   ->select('college_colleges.*')
        //                   ->get();

        // }else if(auth()->user()->type == 14  || Session::get('currentPortal') == 14){

        //       $teacher = DB::table('teacher')
        //                         ->where('tid',auth()->user()->email)
        //                         ->first();

        //       $colleges = DB::table('college_colleges')
        //                         ->where('dean',$teacher->id)
        //                         ->where('college_colleges.deleted',0)
        //                         ->select('college_colleges.*')
        //                         ->get();

        // }else{
        //       $colleges = DB::table('college_colleges')->where('deleted',0)->get();
        // }
        $refid = $check_refid->refid;

        $colleges = DB::table('college_colleges')->where('cisactive', 1)->where('deleted', 0)->get();
        $college_gradelevel = DB::table('gradelevel')->where('acadprogid', 6)->where('deleted', 0)->get();

        $college_section = DB::table('college_sections')->where('deleted', 0)->get();

    @endphp
    @php
        // $itemclassification = db::table('itemclassification')
        //     ->select('itemclassification.id', 'itemclassification.description', 'itemized')
        //     ->join('chrngsetup', 'itemclassification.id', '=', 'chrngsetup.classid')
        //     ->where('itemized', 0)
        //     ->where('itemclassification.deleted', 0)
        //     ->where('chrngsetup.deleted', 0)
        //     ->get();
    @endphp
    <section class="content">
        <style>
            .legend-box {
                width: 12px;
                height: 12px;
                display: inline-block;
                border-radius: 2px;
            }
            .modal {
                overflow-y: auto !important;
                padding-right: 0 !important;
            }

            .modal-open {
                overflow: auto !important;
                padding-right: 0 !important;
            }
        </style>

        <div class="main-card card">
            <div class="card-header text-lg bg-info">
                <h4 class="text-warning" style="text-shadow: 1px 1px 1px gray">
                    <i class="fa fa-file-invoice nav-icon"></i>
                    <b>STUDENT LEDGER</b>
                </h4>
            </div>
            <div class="card-body">
                {{-- <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <select id="studName" name="studid" class="text-secondary form-control select2bs4 updq"
                                value="">
                                <option>SELECT STUDENT</option>
                                @php
                                    $student = db::table('studinfo')
                                        ->select('id', 'sid', 'lastname', 'firstname', 'middlename')
                                        ->where('deleted', 0)
                                        ->orderBy('lastname')
                                        ->orderBy('firstname')
                                        ->get();
                                @endphp
                                @foreach ($student as $stud)
                                    <option value="{{ $stud->id }}">{{ $stud->sid }} - {{ $stud->lastname }},
                                        {{ $stud->firstname }} {{ $stud->middlename }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">

                    </div>

                    <div class="col-md-3 filters">
                        <select id="sem" class="select2bs4 updq" style="width: 100%;">
                            @php
                                $semid = App\RegistrarModel::getSemID();
                            @endphp

                            @foreach (App\RegistrarModel::getSem() as $sem)
                                @if ($sem->id == $semid)
                                    <option selected value="{{ $sem->id }}">{{ $sem->semester }}</option>
                                @else
                                    <option value="{{ $sem->id }}">{{ $sem->semester }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 filters">
                        <select id="sy" class="form-control updq select2bs4" style="width: 100%;">
                        </select>
                    </div>

                    <div class="col-md-6 tv_filters" style="display: none;">
                        <div class="input-group row">
                            <label for="tvl_batch" class="mt-2">Batch:</label>
                            <div class="col-9">
                                <div class="input-group mb-2">
                                    <select id="tvlbatch" class="w-100 select2bs4 updq">
                                        <option></option>
                                        @foreach (db::table('tv_batch')->where('deleted', 0)->get() as $batch)
                                            @php
                                                $sdate = date_create($batch->startdate);
                                                $sdate = date_format($sdate, 'm/d/Y');
                                                $edate = date_create($batch->enddate);
                                                $edate = date_format($edate, 'm/d/Y');
                                            @endphp


                                            @if ($batch->isactive == 1)
                                                <option value="{{ $batch->id }}" selected="">
                                                    {{ $sdate . ' - ' . $edate }}</option>
                                            @else
                                                <option value="{{ $batch->id }}">{{ $sdate . ' - ' . $edate }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="row">
                    <div class="col-md-2">
                        <label for="" class="mb-1">School Year</label>
                        <select name="filter_sy" id="sy" class="form-control form-control-sm select2">
                            @foreach ($sy as $item)
                                <option value="{{ $item->id }}" {{ $item->isactive == 1 ? 'selected="selected"' : '' }}>
                                    {{ $item->sydesc }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="" class="mb-1">Semester</label>
                        <select name="filter_semester" id="sem" class="form-control form-control-sm select2">
                            @foreach ($semester as $item)
                                <option value="{{ $item->id }}" {{ $item->isactive == 1 ? 'selected="selected"' : '' }}>
                                    {{ $item->semester }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="" class="mb-1">Student Status</label>
                        <select name="filter_students" id="filter_studentstatus"
                            class="form-control form-control-sm select2">
                            <option value="" selected disabled>Choose Student Status</option>
                            @foreach ($studStatus as $item)
                                <option value="{{ $item->id }}">{{ $item->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2  form-group mb-0" id="filter_gradelevel_holder">
                        <label for="" class="mb-1">Grade Level</label>
                        <select class="form-control select2 form-control-sm" id="filter_gradelevel" style="width:100%;">
                        </select>
                    </div>

                    {{-- <div class="col-md-3  form-group mb-0">
                        <label for="" class="mb-1">Section <span class="error invalid-feedback">*Select grade
                                level</span></label>
                        <select class="form-control select2 form-control-sm" id="filter_section" style="width:100%;">
                            <option value="">All</option>

                            @foreach ($college_section as $item)
                                <option value="{{ $item->id }}">{{ $item->sectionDesc }}</option>
                            @endforeach
                        </select>
                        <span id="exampleInputEmail1-error" class="error invalid-feedback" style="display: block"></span>
                    </div> --}}

                </div>
                <br>
                <hr>
                {{-- <div class="row mt-4">
                    <div class="col-md-7">
                        <span id="ledger_info">Level|Section: </span>
                    </div>
                    <div class="col-md-5 text-right">
                        <button class="btn btn-info text-sm btn-sm div_studyload" id="btnstudyload" style="display: none;"
                            formtarget="_blank" data-level="" data-status=""><i class="fas fa-book-open">
                            </i> Study Load
                        </button>
                        <span class="btn btn-success btn-sm text-sm" id="btnadjustledger"><i
                                class="fas fa-compress-arrows-alt"></i> Adjustment</span>
                        <button class="btn btn-primary btn-sm text-sm" id="btnprint" formtarget="_blank"><i
                                class="fas fa-print"></i> Print</button>
                        <span class="btn btn-outline-secondary btn-sm text-sm" id="btnreloadledger"><i
                                class="fas fa-sync"></i> Update Ledger</span>
                    </div>

                </div>
                <div class="row form-group mt-1">
                    <div class="col-md-3">
                        <span id="ledger_info_status">Status: </span>
                    </div>
                    <div class="col-md-9 text-right">
                        Current Fees: <span id="feesname" class="text-bold" data-id=""></span>
                    </div>
                </div> --}}


                {{-- <div class="row">
                    <div class="col-12">
                        <div id="table_main" class="table-responsive">
                            <table class="table table-striped table-sm text-sm">
                                <thead class="bg-warning">
                                    <tr>
                                        <th>DATE</th>
                                        <th class="">PARTICULARS</th>
                                        <th class="text-center">CHARGES</th>
                                        <th class="text-center">PAYMENT</th>
                                        <th class="text-center">BALANCE</th>
                                    </tr>
                                </thead>
                                <tbody id="ledger-list">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> --}}

                {{-- <br> --}}
                {{-- <br> --}}
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div class="table-responsive">
                            <table class="table-hover table table-striped table-sm table-bordered"
                                id="update_info_request_table" width="100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="5%"></th>
                                        <th width="7%" class="align-middle prereg_head" data-id="0">ID
                                            #</th>
                                        <th width="20%" class="align-middle prereg_head" data-id="1">
                                            Students</th>

                                        <th width="8%" class="align-middle text-center p-0 prereg_head"
                                            style="font-size:.9rem !important" data-id="2">Grade Level</th>
                                        <th width="20%" class="align-middle prereg_head" data-id="3">
                                            Section</th>
                                        <th width="6%" class="align-middle prereg_head" data-id="1">
                                            Charges</th>
                                        <th width="6%" class="align-middle prereg_head" data-id="1">
                                            Payment</th>
                                        <th width="6%" class="align-middle prereg_head" data-id="1">
                                            Balance</th>
                                        {{-- <th width="11%" class="align-middle prereg_head" data-id="4">
                                            Approval</th> --}}

                                        {{-- <th width="10%" class="align-middle prereg_head" data-id="5"
                                            style="font-size:.66rem !important" data-id="6">Enrollment
                                            Date
                                        </th> --}}
                                        <th width="13%" class="align-middle text-center prereg_head" data-id="7">
                                            Action
                                        </th>
                                        {{-- <th width="13%" class="align-middle text-center prereg_head" data-id="7">

                                        </th> --}}
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="legend mt-3 text-center d-flex justify-content-start flex-wrap"
                            style="font-size:10px; gap: 15px; font-weight: bold;">
                            <div class="d-flex align-items-center mb-2">
                                <span class="legend-box bg-secondary mr-2"></span>
                                <span class="legend-text text-secondary" id="notEnrolledCount">NOT ENROLLED</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="legend-box bg-success mr-2"></span>
                                <span class="legend-text text-success" id="enrolledCount">ENROLLED</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="legend-box mr-2" style="background-color: #58715f;"></span>
                                <span class="legend-text" style="color: #58715f;" id="lateEnrollmentCount">LATE
                                    ENROLLMENT</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="legend-box bg-danger mr-2"></span>
                                <span class="legend-text text-danger" id="droppedOutCount">DROPPED OUT</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="legend-box bg-warning mr-2"></span>
                                <span class="legend-text text-warning" id="withdrawnCount">WITHDRAWN</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="legend-box bg-primary mr-2"></span>
                                <span class="legend-text text-primary" id="transferredInCount">TRANSFERRED IN</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="legend-box mr-2" style="background-color: #fd7e14;"></span>
                                <span class="legend-text" style="color: #fd7e14;" id="transferredOutCount">TRANSFERRED
                                    OUT</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="legend-box bg-dark mr-2"></span>
                                <span class="legend-text text-dark" id="deceasedCount">DECEASED</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modal')

   
    <div class="modal fade" id="view_acountModal" tabindex="-1" role="dialog" aria-labelledby="addTermModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="Student_Ledger_Name"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form for Adding a New Term -->
                    <input type="hidden" id="studNamev4">
                    <div class="row mt-4">
                        <div class="col-md-7">
                            <span id="ledger_info">Level|Section: </span>
                        </div>
                        <div class="col-md-5 text-right">
                            <button class="btn btn-danger text-sm btn-sm " id="btn_cor"> <i class="fas fa-file-alt"></i> COR </button>
                            {{-- <button class="btn btn-warning text-sm btn-sm " id="btn_oldbalance"> <i class="fas fa-edit"></i> Old Balance </button> --}}
                            <button class="btn btn-info text-sm btn-sm div_studyload" id="btnstudyload"
                                style="display: none;" formtarget="_blank" data-level="" data-status=""><i
                                    class="fas fa-book-open">
                                </i> Study Load
                            </button>
                            <span class="btn btn-success btn-sm text-sm" id="btnadjustledger"><i
                                    class="fas fa-compress-arrows-alt"></i> Adjustment</span>
                            <button class="btn btn-primary btn-sm text-sm" id="btnprint" formtarget="_blank"><i
                                    class="fas fa-print"></i> Print</button>
                            <span class="btn btn-outline-secondary btn-sm text-sm" id="btnreloadledger"><i
                                    class="fas fa-sync"></i> Update Ledger</span>
                        </div>

                    </div>
                    <div class="row form-group mt-1">
                        <div class="col-md-3">
                            <span id="ledger_info_status">Status: </span>
                        </div>
                        <div class="col-md-9 text-right">
                            Current Fees: <span id="feesname" class="text-bold" data-id=""></span>
                        </div>
                    </div>

                    <div class="row" hidden>
                        <div class="col-12">
                            <div id="table_main" class="table-responsive">
                                <table class="table table-striped table-sm text-sm">
                                    <thead class="bg-warning">
                                        <tr>
                                            <th>DATE</th>
                                            <th class="">PARTICULARS</th>
                                            <th class="text-left">CHARGES</th>
                                            <th class="text-left">PAYMENT</th>
                                            <th class="text-left">BALANCE</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ledger-list">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div id="table_main2" class="table-responsive">
                                <table class="table table-striped table-sm text-sm">
                                    <thead class="bg-warning">
                                        <tr>
                                            <th width="15%">DATE</th>
                                            <th width="40%"class="">PARTICULARS</th>
                                            <th width="15%"class="text-center">CHARGES</th>
                                            <th width="15%"class="text-center">PAYMENT</th>
                                            <th width="15%"class="text-center">BALANCE</th>
                                        </tr>
                                    </thead>
                                    <tbody id="student_fees">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h4>History</h4>
                            <div id="payment_history" class="table-responsive">
                                <table width="100%" class="table table-striped table-sm text-sm"
                                    style="table-layout: fixed;">
                                    <thead class="bg-info">
                                        <tr>
                                            <th width="15%">DATE</th>
                                            <th width="40%"class="">PARTICULARS</th>
                                            <th width="15%"class="text-center">CHARGES</th>
                                            <th width="15%"class="text-center">PAYMENT</th>
                                            <th width="15%"class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="history_list">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- <button type="submit" form="addTermForm" class="btn btn-primary" id="saveTerm">Add</button> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="chooseFormatModal" tabindex="-1" role="dialog" aria-labelledby="chooseFormatModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chooseFormatModalLabel">Choose Format</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="format">Format</label>
                        <select class="form-control" id="cor_format">
                            <option value="1">Format 1</option>
                            <option value="2">Format 2</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="chooseFormat">Choose</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit_old_account_balance" tabindex="-1" role="dialog"
        aria-labelledby="edit_old_account_balanceLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_old_account_balanceLabel">Edit Old Account Balance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="edit_old_account_balance_form">
                        <div class="form-group">
                            <label for="current_balance">Current Balance</label>
                            <input type="number" class="form-control" id="current_balance" name="current_balance" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_balance">New Balance</label>
                            <input type="number" class="form-control" id="new_balance" name="new_balance" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade show" id="modal-studlist" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Select Student</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-sm-10 input-group">
                                    <input id="txtsearch" type="text" class="form-control validation" id="item-code"
                                        placeholder="SEARCH STUDENT" onkeyup="this.value = this.value.toUpperCase();">
                                    <span class="input-group-append">
                                        <span type="button" class="btn btn-info btn-flat"><i
                                                class="fas fa-search"></i></span>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID NO.</th>
                                                <th>NAME</th>
                                                <th>GRADE | SECTION</th>
                                                <th>ESC</th>
                                            </tr>
                                        </thead>
                                        <tbody id="stud-list">

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->
                        <!-- /.card-footer -->
                    </form>
                </div>
                <div class="modal-footer ">
                    <button id="savestud" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-fees" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h4 class="modal-title">Select Fees</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="loadfeelist" class="row">

                    </div>

                </div>
                <div class="modal-footer ">
                    <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
                    <button id="btnreloadproceed" type="button" class="btn btn-primary"
                        data-dismiss="modal">PROCEED</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-adjustment" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title"><i class="fas fa-compress-arrows-alt"></i> Adjustment</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <button id="adj_btndebit" class="btn btn-outline-primary active btn-block">DEBIT (-
                                Adjustment)</button>
                        </div>
                        <div class="col-md-3">
                            <button id="adj_btncredit" class="btn btn-outline-success btn-block">CREDIT (+
                                Adjustment)</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <div class="row mt-2">
                                <div id="divname" class="col-md-6">
                                    <label>Name: </label> <span class="studname"></span>
                                </div>
                                <div id="divname" class="col-md-3">
                                    <label>LEVEL: </label> <span class="levelname"></span>
                                </div>
                                <div id="divname" class="col-md-3">
                                    <label>GRANTEE: </label> <span class="grantee"></span>
                                </div>
                                <div id="divname" class="col-md-3">
                                    <label id="divstrand">Strand: </label> <span class="strand"></span>
                                </div>
                            </div>
                            <hr>
                            <div id="row_debit" class="row" style="display: block">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input id="debit_description" type="" name=""
                                                class="form-control w-100 debit_field is-invalid" autofocus=""
                                                placeholder="Debit Description">
                                        </div>

                                        <div class="col-md-3">
                                            <select class="select2bs4 form-control debit_field" id="debit_classid">
                                                <option value="0"></option>
                                                <option value="add_new"> + Add Classification</option>
                                            </select>
                                        </div>


                                        <div class="col-md-2">
                                            <input id="debit_amount" type="text" name="currency-field"
                                                pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"
                                                autocomplete="off" class="form-control debit_field is-invalid"
                                                placeholder="0.00" disabled>
                                        </div>

                                        <div class="col-md-3">
                                            <select class="select2bs4 form-control debit_field is-invalid" id="debit_mop">
                                                <option value="0">Mode of Payment</option>
                                                @foreach (db::table('paymentsetup')->where('deleted', 0)->get() as $mop)
                                                    @if (!empty($mop->id) && !empty($mop->paymentdesc))
                                                        <option value="{{ $mop->id }}">{{ $mop->paymentdesc }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-10">
                                            <table class="table table-striped table-sm text-sm">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 230px;">Item</th>
                                                        <th style="width: 230px;">Classification</th>
                                                        <th>Amount</th>
                                                        <th>
                                                            <button id="debit_additem"
                                                                class="btn btn-primary btn-sm text-sm btn-block">Add
                                                                Item</button>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="debit_itemlist">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer ">
                                    <button id="" type="button" class="btn btn-default"
                                        data-dismiss="modal">CLOSE</button>
                                    <button id="debit_save" type="button" class="btn btn-primary"
                                        disabled="">POST</button>
                                </div>
                            </div>
                            <div id="row_credit" class="row" style="display: none">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input id="credit_description" type="" name=""
                                                class="form-control w-100 credit_field is-invalid" autofocus=""
                                                placeholder="Credit Description">
                                        </div>

                                        <div class="col-md-3">
                                            <select class="select2bs4 form-control credit_field" id="credit_classids">
                                                <option value="0"></option>
                                                <option value="add_new"> + Add Classification</option>
                                                {{-- @foreach (db::table('itemclassification')->where('deleted', 0)->orderBy('description')->get() as $class)
                                                    <option value="{{ $class->id }}">{{ $class->description }}
                                                    </option>
                                                @endforeach --}}
                                            </select>
                                        </div>

                                        <!-- Add Classification Modal -->
                                        <div id="addClassificationModal" class="modal fade" tabindex="-1"
                                            role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Add New Classification</h5>
                                                        <button type="button" class="close"
                                                            data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="text" id="new_classification"
                                                            class="form-control" placeholder="Enter classification name">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-primary"
                                                            id="saveClassification">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-2">
                                            <input id="credit_amount" type="text" name="currency-field"
                                                pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"
                                                autocomplete="off" class="form-control credit_field is-invalid"
                                                placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-10">
                                            <table class="table table-striped table-sm text-sm">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 230px;">Item</th>
                                                        <th style="width: 230px;">Classification</th>
                                                        <th>Amount</th>
                                                        <th>
                                                            <button id="credit_additem"
                                                                class="btn btn-primary btn-sm text-sm btn-block">Add
                                                                Item</button>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="credit_itemlist">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer ">
                                        <button id="" type="button" class="btn btn-default"
                                            data-dismiss="modal">CLOSE</button>
                                        <button id="credit_save" type="button" class="btn btn-success"
                                            disabled="">POST</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="modal-footer ">
          <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
          <button id="btnreloadproceed" type="button" class="btn btn-primary" data-dismiss="modal">PROCEED</button>
        </div> --}}
            </div>
        </div> {{-- dialog --}}
    </div>
    <div class="modal fade show" id="modal-reminder" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">Reminder Slip</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="" class="row">
                        <div class="col-md-2">
                            <label>Due Date</label>
                        </div>
                        <div class="col-md-6">
                            <input id="reminder_due" class="form-control" type="date" name="">
                        </div>
                    </div>
                    <hr>
                    <div class="row" style="height: 20em;">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-borderless table-sm text-sm">
                                <tbody id="reminder_list">

                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
                <div class="modal-footer ">
                    <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
                    <button id="reminder_print" type="button" class="btn btn-primary">PRINT</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-studyload" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Study Load</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="" class="row">
                        <div class="col-md-2">Course: </div>
                        <div class="col-md-9 text-bold" id="sl_course"></div>
                    </div>
                    <div id="" class="row form-group">
                        <div class="col-md-2">School Year: </div>
                        <div class="col-md-2 text-bold" id="sl_sy"></div>
                        <div class="col-md-2">Semester: </div>
                        <div class="col-md-2 text-bold" id="sl_sem"></div>
                    </div>

                    <div class="row" style="">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-sm text-sm">
                                <thead>
                                    <tr>
                                        <th>CODE</th>
                                        <th>DESCRIPTION</th>
                                        <th>UNITS</th>

                                    </tr>
                                </thead>
                                <tbody id="sl_list">

                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
                <div class="modal-footer ">
                    <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
                    {{-- <button id="reminder_print" type="button" class="btn btn-primary">PRINT</button> --}}
                </div>
            </div>
        </div> {{-- dialog --}}
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
                                <label for="class-desc" class="col-sm-3 col-form-label">Class Code</label>
                                <div class="col-sm-9">
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
        </div>
    </div>

    <div class="modal fade show" id="modal-classification-credit" aria-modal="true"
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
                                    <input type="text" class="form-control validation" id="class_description-credit"
                                        placeholder="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Accounts</label>
                                <div class="col-sm-9">
                                    <select class="form-control select2" id="class_account-credit">
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
                                    <select class="form-control select2" id="class_group-credit">
                                        <option value="">&nbsp;</option>
                                        <option value="TUI">TUITION</option>
                                        <option value="MISC">MISCELLANEOUS</option>
                                        <option value="OTH">OTHER FEES</option>
                                    </select>
                                </div>
                                <div class="col-sm-3 mt-2">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="class_itemized-credit" disabled>
                                        <label for="class_itemized">
                                            Itemized
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Class Code</label>
                                <div class="col-sm-9">
                                    <select class="form-control select2" id="class_code-credit">
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
                    <button id="class_save-credit" type="button" class="btn btn-primary" data-id="0">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade show" id="modal-debit_items" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-md mt-4">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Debit - Items</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col-md-7">
                            <select id="debit_item" class="select2bs4" style="width: 100%;">
                                @php
                                    $items = db::table('items')
                                        ->select('id AS itemid', 'description', 'classid', 'amount')
                                        ->where('deleted', 0)
                                        ->get();
                                @endphp
                                <option value="0">&nbsp;</option>
                                <option value="add_new">+ Add New Item</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->itemid }}" data-class="{{ $item->classid }}">
                                        {{ $item->description }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-5">
                            <input type="number" id="debit_itemamount" class="form-control" placeholder="AMOUNT">
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
                    <button id="debit_itemsave" type="button" class="btn btn-primary">ADD ITEM</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-credit_items" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-md mt-4">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Credit - Items</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col-md-7">
                            <select id="credit_item" class="select2bs4" style="width: 100%;">
                                @php
                                    $items = db::table('items')
                                        ->select('id AS itemid', 'description', 'classid', 'amount')
                                        ->where('deleted', 0)
                                        ->get();
                                @endphp
                                <option value="0">&nbsp;</option>
                                <option value="add_new">+ Add New Item</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->itemid }}" data-class="{{ $item->classid }}">
                                        {{ $item->description }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <input type="number" id="credit_itemamount" class="form-control" placeholder="AMOUNT">
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
                    <button id="credit_itemsave" type="button" class="btn btn-primary">ADD ITEM</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade show" id="modal-adjustment_view" aria-modal="true"
        style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg mt-4">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Adjustment Info</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2 "><label for=""> Particulars: </label></div>
                        <div id="adjv_particulars" class="col-md-6"></div>
                        <div class="col-md-2 text-right"><label>Type: </label></div>
                        <div id="adjv_type" class="col-md-2"></div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-2 "><label for="">Classification: </label></div>
                        <div id="adjv_class" class="col-md-6"></div>
                        <div class="col-md-2 text-right"><label>Date: </label></div>
                        <div id="adjv_date" class="col-md-2"></div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-2 "><label for="">Total Amount: </label></div>
                        <div id="adjv_totalamount" class="col-md-6"></div>
                        {{-- <div class="col-md-2 text-right"><label>Date: </label></div>
                <div id="adjv_date" class="col-md-4"></div> --}}
                    </div>
                    <div class="row">
                        <div id="table_adjinfo" class="col-md-12 table-responsive">
                            <table class="table table-striped table-sm text-sm">
                                <thead>
                                    <tr>
                                        <th>ITEM NAME</th>
                                        <th>CLASSIFICATION</th>
                                        <th>AMOUNT</th>
                                    </tr>
                                </thead>
                                <tbody id="adjinfolist">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
                    {{-- <button id="debit_itemsave" type="button" class="btn btn-primary">ADD ITEM</button> --}}
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal_receipts_view" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg mt-4">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">PAID PARTICULARS ( <span id="total_amount"></span> )</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table width="100%" class="table table-striped table-sm text-sm" style="table-layout: fixed;">
                        <thead>
                            <tr>
                                <th width="5%"></th>
                                <th width="55%">ITEM NAME</th>
                                <th width="20%" class="text-center">ITEM PRICE</th>
                                <th width="20%" class="text-center">PAID AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody id="paid_list">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer ">
                    <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade" id="modal-items_detail" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content text-sm">
                <!-- Modal Header -->
                <div id="modalhead" class="modal-header bg-info text-white">
                    <h4 class="modal-title">Items <span id="item_action"></span></h4>
                    <button type="button" class="close text-black" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="item_code" class="col-sm-3 col-form-label">Item Code</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control validation" id="item_code" placeholder="Item Code"
                                onkeyup="this.value = this.value.toUpperCase();">
                        </div>

                        <label for="item_classcode" class="col-sm-3 col-form-label text-right">Consolidated
                            Grouping</label>
                        <div class="col-sm-3">
                            <select id="item_classcode" class="select2 form-control">
                                <option value="0">Payment Group</option>
                                @foreach (DB::table('items_classcode')->get() as $itemclass)
                                    <option value="{{ $itemclass->id }}">{{ $itemclass->description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="item_desc" class="col-sm-3 col-form-label">Description</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control validation" id="item_desc"
                                placeholder="Description" onkeyup="this.value = this.value.toUpperCase();">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="item_classid" class="col-sm-3 col-form-label">Classification</label>
                        <div class="col-sm-9">
                            <select class="select2 form-control" id="item_classid">
                                <option value="0"></option>
                                @foreach (DB::table('itemclassification')->where('deleted', 0)->orderBy('description')->get() as $class)
                                    <option value="{{ $class->id }}">{{ $class->description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="item_amount" class="col-sm-3 col-form-label">Amount</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control validation" id="item_amount">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9 d-flex justify-content-between">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="item_cash">
                                <label for="item_cash">Cash</label>
                            </div>
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="item_receivable">
                                <label for="item_receivable">Receivable</label>
                            </div>
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="item_expense">
                                <label for="item_expense">Expense</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="item_glid" class="col-sm-3 col-form-label">GL Account</label>
                        <div class="col-sm-9">
                            <select id="item_glid" class="select2 form-control">
                                <option value="0"></option>
                                @foreach (DB::table('acc_coa')->where('deleted', 0)->orderBy('code')->get() as $coa)
                                    <option value="{{ $coa->id }}">{{ $coa->code . ' - ' . $coa->account }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="item_save" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-items_detail-credit" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content text-sm">
                <!-- Modal Header -->
                <div id="modalhead" class="modal-header bg-info text-white">
                    <h4 class="modal-title">Items <span id="item_action"></span></h4>
                    <button type="button" class="close text-black" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="item_code" class="col-sm-3 col-form-label">Item Code</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control validation" id="item_code-credit"
                                placeholder="Item Code" onkeyup="this.value = this.value.toUpperCase();">
                        </div>

                        <label for="item_classcode" class="col-sm-3 col-form-label text-right">Consolidated
                            Grouping</label>
                        <div class="col-sm-3">
                            <select id="item_classcode-credit" class="select2 form-control">
                                <option value="0">Payment Group</option>
                                @foreach (DB::table('items_classcode')->get() as $itemclass)
                                    <option value="{{ $itemclass->id }}">{{ $itemclass->description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="item_desc" class="col-sm-3 col-form-label">Description</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control validation" id="item_desc-credit"
                                placeholder="Description" onkeyup="this.value = this.value.toUpperCase();">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="item_classid" class="col-sm-3 col-form-label">Classification</label>
                        <div class="col-sm-9">
                            <select class="select2 form-control" id="item_classid-credit">
                                <option value="0"></option>
                                @foreach (DB::table('itemclassification')->where('deleted', 0)->orderBy('description')->get() as $class)
                                    <option value="{{ $class->id }}">{{ $class->description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="item_amount" class="col-sm-3 col-form-label">Amount</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control validation" id="item_amount-credit">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9 d-flex justify-content-between">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="item_cash-credit">
                                <label for="item_cash">Cash</label>
                            </div>
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="item_receivable-credit">
                                <label for="item_receivable">Receivable</label>
                            </div>
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="item_expense-credit">
                                <label for="item_expense">Expense</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="item_glid" class="col-sm-3 col-form-label">GL Account</label>
                        <div class="col-sm-9">
                            <select id="item_glid-credit" class="select2 form-control">
                                <option value="0"></option>
                                @foreach (DB::table('acc_coa')->where('deleted', 0)->orderBy('code')->get() as $coa)
                                    <option value="{{ $coa->id }}">{{ $coa->code . ' - ' . $coa->account }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="item_save-credit" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        var all_admissiontype = []

        var sy = @json($sy);
        var usertype = @json(auth()->user()->type);
        var refid = @json($refid);
        var usertype_session = @json(Session::get('currentPortal'));

        if (usertype_session == 15 || usertype_session == 4 || usertype_session == 14 || usertype_session == 16) {
            $('#delete_student_button').remove()
            $('#mark_as_inactive').remove()
            $('#mark_as_active').remove()
            $('#view_requirements').remove()
        }

        if (usertype == 6 || refid == 28 || refid == 29) {
            $('#delete_student_button').remove()
            $('#mark_as_inactive').remove()
            $('#enroll_student_button').remove()
            $('#update_student_information_button').remove()
            $('#enrollment_form').remove()
            $('#enrollment_form').remove()

            $('input').attr('disabled', 'disabled')
            $('select').attr('disabled', 'disabled')

            $('#filter_studstatus').removeAttr('disabled')
            $('#filter_sy').removeAttr('disabled')
            $('#filter_sem').removeAttr('disabled')
            $('#filter_gradelevel').removeAttr('disabled')
            $('#filter_paymentstat').removeAttr('disabled')
            $('#filter_activestatus').removeAttr('disabled')
            $('#filter_section').removeAttr('disabled')

            $('#filter_studstatus').val("").change()

        }

        if (usertype_session == 14 || usertype_session == 16) {
            $('#update_student_information_button').remove()
            $('#enrollment_form').remove()


            $('#filter_studstatus').removeAttr('disabled')
            $('#filter_sy').removeAttr('disabled')
            $('#filter_sem').removeAttr('disabled')
            $('#filter_gradelevel').removeAttr('disabled')
            $('#filter_paymentstat').removeAttr('disabled')
            $('#filter_activestatus').removeAttr('disabled')
            $('#filter_section').removeAttr('disabled')

            $('#filter_studstatus').val("").change()

            $('#stdprgEnrollmentForm').attr('hidden', 'hidden')
        }


        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        })

        $("input[data-type='currency']").on({
            keyup: function() {
                formatCurrency($(this));
            },
            blur: function() {
                formatCurrency($(this), "blur");
            }
        });

        function formatNumber(n) {
            // format number 1000000 to 1,234,567
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        }

        function formatCurrency(input, blur) {
            // appends $ to value, validates decimal side
            // and puts cursor back in right position.

            // get input value
            var input_val = input.val();

            // don't validate empty input
            if (input_val === "") {
                return;
            }

            // original length
            var original_len = input_val.length;

            // initial caret position
            var caret_pos = input.prop("selectionStart");

            // check for decimal
            if (input_val.indexOf(".") >= 0) {

                // get position of first decimal
                // this prevents multiple decimals from
                // being entered
                var decimal_pos = input_val.indexOf(".");

                // split number by decimal point
                var left_side = input_val.substring(0, decimal_pos);
                var right_side = input_val.substring(decimal_pos);

                // add commas to left side of number
                left_side = formatNumber(left_side);

                // validate right side
                right_side = formatNumber(right_side);

                // On blur make sure 2 numbers after decimal
                if (blur === "blur") {
                    right_side += "00";
                }

                // Limit decimal to only 2 digits
                right_side = right_side.substring(0, 2);

                // join number by .
                input_val = left_side + "." + right_side;

            } else {
                // no decimal entered
                // add commas to number
                // remove all non-digits
                input_val = formatNumber(input_val);
                input_val = input_val;

                // final formatting
                if (blur === "blur") {
                    input_val += ".00";
                }
            }

            // send updated string to input
            input.val(input_val);

            // put caret back in the right position
            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }

        function forceKeyPressUppercase(e) {
            var charInput = e.keyCode;
            if ((charInput >= 97) && (charInput <= 122)) { // lowercase
                if (!e.ctrlKey && !e.metaKey && !e.altKey) { // no modifier key
                    var newChar = charInput - 32;
                    var start = e.target.selectionStart;
                    var end = e.target.selectionEnd;
                    e.target.value = e.target.value.substring(0, start) + String.fromCharCode(newChar) + e.target.value
                        .substring(end);
                    e.target.setSelectionRange(start + 1, start + 1);
                    e.preventDefault();
                }
            }
        }

        document.getElementById("debit_description").addEventListener("keypress", forceKeyPressUppercase, false);
        document.getElementById("credit_description").addEventListener("keypress", forceKeyPressUppercase, false);

        $(document).ready(function() {
            $('#modal_receipts_view').on('hidden.bs.modal', function () {
                // Re-enable scrolling on the parent modal
                $('body').css('overflow', 'auto');
                $('body').css('padding-right', '0');
            });

            $('#view_acountModal').on('hidden.bs.modal', function() {
                $("#student_fees").empty();
                $("#history_list").empty();
            });

            $(document).on('click', '#debit_additem', function() {
                $('#modal-debit_items').modal('show');
            });

            $(document).on('click', '#credit_additem', function() {
                $('#modal-credit_items').modal('show');
            });

            loadSY();
            // $('#studName').val(null).trigger('change');
            // searchStud();

            $('.select2bs4').select2({
                theme: 'bootstrap4'

            });

            $(window).resize(function() {
                screenadjust();
            })

            screenadjust();



            function screenadjust() {
                var screen_height = $(window).height() - 324;
                $('#table_main').css('height', screen_height);
            }

            function loadSY() {
                $.ajax({
                    url: "{{ route('loadSY') }}",
                    method: 'GET',
                    data: {

                    },
                    dataType: '',
                    success: function(data) {
                        // console.log(data);
                        $('#sy').html(data);
                    }
                });
            }

            function searchStud(text = '') {
                // var query = $('#txtsearch').val();
                $.ajax({
                    url: "{{ route('searchStud') }}",
                    method: 'GET',
                    data: {
                        query: text
                    },
                    dataType: 'json',
                    success: function(data) {
                        // $('#stud-list').html(data.list);

                        $('#studName').html(data.list);

                        $('#studName').val('');

                    }
                });
            }

            $(document).on('click', '#btnsearch', function() {
                // $('#txtsearch').focus();
                // console.log('test');
            });

            // $(document).on('keyup', '.select2-search__field', function(){
            //   var text = $(this).val();
            //   console.log(text);
            //   searchStud(text);
            // });

            $(document).on('click', '.btnsel', function() {
                $('#modal-studlist').modal('hide');

                var syid = $('#sy').val();
                var studid = $(this).attr('data-id');
                var batchid = $('#tvlbatch').val();

                $('#studid').val(studid);
                $('#syid').val(syid);

                $('#studName').text($('#n-' + studid).text()).removeClass('text-secondary');
                $('#glevel').text($('#g-' + studid).text());

                console.log(studid, 'studid');

                refreshLedger(studid, syid, null, batchid);
            });



            // $('#studName').focus(function(){
            //   console.log('test');
            //   searchStud();
            // })

            // old working code v2
            // $(document).on('change', '.updq', function() {
            //     var syid = $('#sy').val();
            //     var semid = $('#sem').val();
            //     var studid = $('#studName').val();
            //     var batchid = $('#tvlbatch').val();

            //     $('#studid').val(studid);
            //     $('#syid').val(syid);

            //     // console.log(studid);
            //     $.ajax({
            //         url: "{{ route('getStudLedger') }}",
            //         method: 'GET',
            //         data: {
            //             syid: syid,
            //             studid: studid,
            //             semid: semid,
            //             batchid: batchid
            //         },
            //         dataType: 'json',
            //         success: function(data) {
            //             $('#ledger-list').html(data.list);

            //             $('#btnstudyload').attr('data-level', data.levelid);
            //             $('#btnstudyload').attr('data-status', data.studstatus);

            //             if (data.levelid >= 17 && data.levelid <= 21) {
            //                 $('.div_studyload').show();
            //             } else {
            //                 $('.div_studyload').hide();
            //             }

            //             if (data.istvl == 1) {
            //                 $('.filters').hide();
            //                 $('.tv_filters').show();
            //             } else {
            //                 $('.filters').show();
            //                 $('.tv_filters').hide();
            //             }

            //             if (data.levelid == 14 || data.levelid == 15) {
            //                 $('#ledger_info').text('Level|Section: ' + data.levelname + ' - ' +
            //                     data.section + ' | ' + data.strand + ' | ' + data.grantee);
            //             } else if (data.levelid >= 17 && data.levelid <= 21) {
            //                 $('#ledger_info').text('Level|Course: ' + data.levelname + ' ' +
            //                     data.section);
            //             } else {
            //                 $('#ledger_info').text('Level|Section: ' + data.levelname + ' ' +
            //                     data.section + ' | ' + data.grantee);
            //             }

            //             $('#ledger_info_status').text('Status: ' + data.studstatus);
            //             $('#feesname').text(data.feesname);
            //             $('#feesname').attr('data-id', data.feesid);

            //         }
            //     });
            // });

            function refreshLedger(studid, syid, semid, batchid = null) {
                console.log(studid, syid, semid, batchid);
                
                $.ajax({
                    url: "{{ route('getStudLedger') }}",
                    method: 'GET',
                    data: {
                        syid: syid,
                        studid: studid,
                        semid: semid,
                        batchid: batchid
                    },
                    dataType: 'json',
                    success: function(data) {
                        get_student_ledger()
                        get_history_ledger()
                        $('#Student_Ledger_Name').html(data.studname);
                        $('#ledger-list').html(data.list);
                        $('#ledger_info_status').text('Status: ' + data.studstatus);
                        $('#feesname').text(data.feesname).attr('data-id', data.feesid);

                        $('#btnstudyload').attr('data-level', data.levelid);
                        $('#btnstudyload').attr('data-status', data.studstatus);

                        if (data.levelid >= 17 && data.levelid <= 21) {
                            $('.div_studyload').show();
                        } else {
                            $('.div_studyload').hide();
                        }

                        if (data.istvl == 1) {
                            $('.filters').hide();
                            $('.tv_filters').show();
                        } else {
                            $('.filters').show();
                            $('.tv_filters').hide();
                        }

                        if (data.levelid == 14 || data.levelid == 15) {
                            $('#ledger_info').text('Level|Section: ' + data.levelname + ' - ' +
                                data.section + ' | ' + data.strand + ' | ' + data.grantee);
                        } else if (data.levelid >= 17 && data.levelid <= 21) {
                            $('#ledger_info').text('Level|Course: ' + data.levelname + ' ' + data
                                .section);
                        } else {
                            $('#ledger_info').text('Level|Section: ' + data.levelname + ' ' +
                                data.section + ' | ' + data.grantee);
                        }

                        load_update_info_datatable();

                    }
                });
            }

            // View Account Modal Click
            $(document).on('click', '.view_account', function() {
                $('#view_acountModal').modal('show');

                var syid = $('#sy').val();
                var semid = $('#sem').val();
                var studid = $(this).attr('data-id');
                var batchid = $('#tvlbatch').val();

                $('#studid').val(studid);
                $('#syid').val(syid);
                $('#studNamev4').val(studid);

                refreshLedger(studid, syid, semid, batchid);


            });

            // COR 
            $('#btn_cor').click(function() {
                $('#chooseFormatModal').modal('show');
            });
            $('#chooseFormat').click(function() {

                const studid = $('#studid').val();
                const format = $('#cor_format').val();
                const syid = $('#sy').val();
                const semid = $('#sem').val();
                const levelid = $('#btnstudyload').attr('data-level');
                const portal = 'finance';

                const corurl = `/printcor/${studid}?syid=${syid}&semid=${semid}&levelid=${levelid}&format=${format}&portal=${portal}`;

                window.open(corurl, '_blank');

            });


            // CUSTOMIZE OLD BALANCE
            $(document).on('click', '.btn_oldbalance', function() {
                var currentbalance = $(this).text().trim();

                $('#current_balance').val(currentbalance);
                $('#edit_old_account_balance').modal('show');
            });

            $('#edit_old_account_balance_form').submit(function() {
                event.preventDefault();
                if ($('#old_balance').val() == '') {
                    Swal.fire({
                        position: 'center',
                        type: 'error',
                        title: 'Old Balance is required',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('finance.edit_old_account_balance') }}",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'studid': $('#studid').val(),
                        'syid': $('#sy').val(),
                        'semid':  $('#sem').val(),
                        'old_balance': $('#new_balance').val()
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            $('#edit_old_account_balance').modal('hide');
                            Swal.fire({
                                position: 'center',
                                type: 'success',
                                title: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            })

                            refreshLedger($('#studid').val(), $('#sy').val(), $('#sem').val());

                        } else {
                            $('#edit_old_account_balance').modal('hide');
                            Swal.fire({
                                position: 'center',
                                type: 'error',
                                title: data.error,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    }
                });
            });
            // CUSTOMIZE OLD BALANCE


            $(document).on('click', '#btnreloadledger', function() {
                var studid = $('#studNamev4').val();
                var syid = $('#sy').val();
                var semid = $('#sem').val();

                $.ajax({
                    url: "{{ route('loadfees') }}",
                    method: 'GET',
                    data: {
                        studid: studid,
                        syid: syid,
                        semid: semid
                    },
                    dataType: 'json',
                    success: function(data) {

                        $('#loadfeelist').html(data.feelist);
                        $('#modal-fees').modal('show');

                        selectFees($('#feesname').attr('data-id'));
                    }
                });

            });

            function selectFees(id = 0) {
                $('.col-fees').each(function() {
                    if ($(this).attr('data-id') == id) {
                        $(this).trigger('click');
                    }
                })
            }

            $(document).on('click', '.col-fees', function() {
                dataid = $(this).attr('data-id');

                $('.col-fees').each(function() {
                    if ($(this).attr('data-id') == dataid) {
                        $(this).find('.card-header').removeClass('bg-info');
                        $(this).find('.card-header').addClass('bg-success');
                        $(this).find('.card-body').addClass('bg-light');
                    } else {
                        $(this).find('.card-header').removeClass('bg-success');
                        $(this).find('.card-header').addClass('bg-info');
                        $(this).find('.card-body').removeClass('bg-light');
                    }

                });

                $('#btnreloadproceed').attr('data-id', dataid);

            });

            $(document).on('click', '#btnreloadproceed', function() {
                // var studid = $('#studName').val();
                var studid = $('#studNamev4').val();
                var feesid = $(this).attr('data-id');
                var syid = $('#sy').val();
                var semid = $('#sem').val();

                Swal.fire({
                    title: 'Reset Payment Account?',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Reload it!'
                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            url: "{{ route('resetpayment_v3') }}",
                            method: 'GET',
                            data: {
                                studid: studid,
                                feesid: feesid,
                                syid: syid,
                                semid: semid
                            },
                            dataType: '',
                            success: function(data) {
                                $('.updq').trigger('change');
                                Swal.fire(
                                    'Success!',
                                    'Account has been Reloaded',
                                    'success'
                                );
                            }
                        });
                    }
                });
            });

            $(document).on('keyup', '#debit_description', function() {
                if ($(this).val() != '') {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                } else {
                    $(this).removeClass('is-valid');
                    $(this).addClass('is-invalid');
                }

                debit_validation();
            })

            $(document).on('keyup', '#debit_amount', function() {
                if ($(this).val() != '') {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                } else {
                    $(this).removeClass('is-valid');
                    $(this).addClass('is-invalid');
                }

                debit_validation();
            });

            genclassid();

            $(document).on('change', '#debit_classid', function() {
                var selectedValue = $(this).val();
                console.log(selectedValue);

                if (selectedValue === "add_new") {
                    $('#modal-classification').modal('show'); // Show the modal
                    $(this).val('').trigger('change.select2'); // Reset dropdown selection
                }

                if ($(this).val() != 0) {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                } else {
                    $(this).removeClass('is-valid');
                    $(this).addClass('is-invalid');
                }

                debit_validation();

                $(document).on('click', '#class_body tr', function() {
                    var headerid = $(this).attr('data-id')


                })

                $(document).on('change', '#class_group', function() {
                    if ($(this).val() == '') {
                        $('#class_itemized').prop('disabled', true);
                        $('#class_itemized').prop('checked', false);
                    } else {
                        $('#class_itemized').prop('disabled', false);
                    }
                });

                $(document).on('click', '#class_create', function() {
                    $('#modal-classification').modal('show')
                    $('#class_description').val('')
                    $('#class_account').val(0).change()
                    $('#class_group').val('').change()
                    $('#class_save').attr('data-id', 0)
                })

                $(document).on('click', '#class_save', function() {
                    console.log('naclick ni');

                    var description = $('#class_description').val()
                    var account = $('#class_account').val()
                    var group = $('#class_group').val()
                    var dataid = $('#class_save').attr('data-id')
                    var classcode = $('#class_code').val()

                    if ($('#class_itemized').prop('checked') == true) {
                        var itemized = 1
                    } else {
                        var itemized = 0
                    }



                    if (dataid == 0) {
                        $.ajax({
                            type: "GET",
                            url: "{{ route('itmclscreate') }}",
                            data: {
                                description: description,
                                account: account,
                                group: group,
                                itemized: itemized,
                                classcode: classcode
                            },
                            // dataType: "dataType",
                            success: function(data) {
                                if (data == 'done') {
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.addEventListener(
                                                'mouseenter', Swal
                                                .stopTimer)
                                            toast.addEventListener(
                                                'mouseleave', Swal
                                                .resumeTimer)
                                        }
                                    })

                                    Toast.fire({
                                        type: 'success',
                                        title: 'Item classification saved successfully'
                                    })

                                    $('#modal-classification').modal('hide')
                                    genclassid()
                                } else {
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.addEventListener(
                                                'mouseenter', Swal
                                                .stopTimer)
                                            toast.addEventListener(
                                                'mouseleave', Swal
                                                .resumeTimer)
                                        }
                                    })

                                    Toast.fire({
                                        type: 'error',
                                        title: 'Item classification already exist'
                                    })
                                }
                            }
                        })
                    } else {
                        $.ajax({
                            type: "GET",
                            url: "{{ route('itmclsupdate') }}",
                            data: {
                                dataid: dataid,
                                description: description,
                                account: account,
                                group: group,
                                itemized: itemized,
                                classcode: classcode
                            },
                            // dataType: "dataType",
                            success: function(data) {
                                if (data == 'done') {
                                    console.log('aaa')
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.addEventListener(
                                                'mouseenter', Swal
                                                .stopTimer)
                                            toast.addEventListener(
                                                'mouseleave', Swal
                                                .resumeTimer)
                                        }
                                    })

                                    Toast.fire({
                                        type: 'success',
                                        title: 'Item classification updated successfully'
                                    })

                                    $('#modal-classification').modal('hide')
                                    genclassid()
                                } else {
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.addEventListener(
                                                'mouseenter', Swal
                                                .stopTimer)
                                            toast.addEventListener(
                                                'mouseleave', Swal
                                                .resumeTimer)
                                        }
                                    })

                                    Toast.fire({
                                        type: 'success',
                                        title: 'Item classification already exist'
                                    })
                                }
                            }
                        });
                    }
                })
            })

            $("#debit_itemamount").on("input", function() {
                $("#debit_amount").val($(this).val()).trigger("keyup");
            });

            $("#debit_itemsave").click(function(e) {
                e.preventDefault();

                var itemid = $("#debit_item").val();
                var amount = $("#debit_itemamount").val() || 0;
                var studid = $("#studName").val();

                $.ajax({
                    url: '{{ route('adjitems_append') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        itemid: itemid,
                        amount: amount,
                        studid: studid
                    },
                    success: function(data) {
                        console.log(data, 'data');

                        var newRow = `
                            <tr data-id="${data.id}" data-amount="${data.amount}" data-classid="${data.classid}">
                                <td>${data.description}</td>
                                <td>${data.classification}</td>
                                <td>${data.amount}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm remove-item" data-id="${data.id}">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        `;

                        $('#debit_itemlist').append(newRow);
                        updateTotalAmount();

                        if ($('#debit_classid').val() == 0) {
                            $('#debit_classid').val(data.classid).trigger('change');
                            $('#debit_classid').trigger('select2:close');
                        }
                    },
                    error: function(xhr) {
                        console.log("Error:", xhr.responseText);
                    }
                });
            });

            $(document).on('change', '#debit_item', function() {
                var selectedValue = $(this).val();

                if (selectedValue === "add_new") {
                    $('#modal-items_detail').modal('show');
                    $(this).val('').trigger('change.select2');
                }
            });
            updateSelect2Items()

            function updateSelect2Items() {
                $.ajax({
                    url: '{{ route('getitems') }}',
                    type: 'GET',
                    success: function(response) {
                        var select = $('#debit_item');
                        select.empty();

                        select.append('<option value="0">&nbsp;</option>');
                        select.append('<option value="add_new">+ Add New Item</option>');

                        $.each(response, function(index, item) {
                            select.append('<option value="' + item.id + '" data-class="' +
                                item.classid + '">' + item.description + '</option>');
                        });

                        select.trigger('change');
                    },
                    error: function(xhr) {
                        console.error("Error fetching items:", xhr.responseText);
                    }
                });
            }

            $(document).on('click', '#item_save', function() {
                var itemcode = $('#item_code').val();
                var itemdesc = $('#item_desc').val();
                var classid = $('#item_classid').val();
                var amount = $('#item_amount').val();
                var slid = 0;
                var isdp = $('#item_cash').prop('checked') ? 1 : 0;
                var isreceivable = $('#item_receivable').prop('checked') ? 1 : 0;
                var isexpense = $('#item_expense').prop('checked') ? 1 : 0;

                $.ajax({
                    url: "{{ route('saveItem') }}",
                    method: 'GET',
                    data: {
                        itemcode: itemcode,
                        itemdesc: itemdesc,
                        classid: classid,
                        slid: slid,
                        amount: amount,
                        isdp: isdp,
                        isreceivable: isreceivable,
                        isexpense: isexpense
                    },
                    dataType: '',
                    success: function(data) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            onOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal
                                    .resumeTimer)
                            }
                        })

                        Toast.fire({
                            type: 'success',
                            title: 'Item successfully saved.'
                        })
                        updateSelect2Items();
                    }
                });
            });

            $(document).on('change', '#credit_item', function() {
                var selectedValue = $(this).val();

                if (selectedValue === "add_new") {
                    $('#modal-items_detail-credit').modal('show');
                    $(this).val('').trigger('change.select2');
                }
            });
            updateSelect2ItemsCredit()

            function updateSelect2ItemsCredit() {
                $.ajax({
                    url: '{{ route('getitems') }}',
                    type: 'GET',
                    success: function(response) {
                        console.log(response);
                        var select = $('#credit_item');
                        select.empty();
                        select.append('<option value="0">&nbsp;</option>');
                        select.append('<option value="add_new">+ Add New Item</option>');

                        $.each(response, function(index, item) {
                            select.append('<option value="' + item.id + '" data-class="' +
                                item.classid + '">' + item.description + '</option>');
                        });

                        select.trigger('change');
                    },
                    error: function(xhr) {
                        console.error("Error fetching items:", xhr.responseText);
                    }
                });
            }

            $(document).on('click', '#item_save-credit', function() {
                var itemcode = $('#item_code-credit').val();
                var itemdesc = $('#item_desc-credit').val();
                var classid = $('#item_classid-credit').val();
                var amount = $('#item_amount-credit').val();
                var slid = 0;
                var isdp = $('#item_cash-credit').prop('checked') ? 1 : 0;
                var isreceivable = $('#item_receivable-credit').prop('checked') ? 1 : 0;
                var isexpense = $('#item_expense-credit').prop('checked') ? 1 : 0;

                $.ajax({
                    url: "{{ route('saveItem') }}",
                    method: 'GET',
                    data: {
                        itemcode: itemcode,
                        itemdesc: itemdesc,
                        classid: classid,
                        slid: slid,
                        amount: amount,
                        isdp: isdp,
                        isreceivable: isreceivable,
                        isexpense: isexpense
                    },
                    dataType: '',
                    success: function(data) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            onOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal
                                    .resumeTimer)
                            }
                        })

                        Toast.fire({
                            type: 'success',
                            title: 'Item successfully saved.'
                        })
                        updateSelect2ItemsCredit();
                    }
                });
            });
            genclassidcredit()

            function genclassidcredit() {
                $.ajax({
                    type: "GET",
                    url: "/finance/itemclassification/fetchItemClassification",
                    success: function(data) {
                        var select = $('#credit_classids');

                        select.empty();

                        select.append('<option value="add_new">+ Add Classification</option>');

                        $.each(data, function(index, classItem) {
                            select.append('<option value="' + classItem.id + '">' + classItem
                                .description + '</option>');
                        });

                        select.select2({
                            theme: 'bootstrap4',
                            width: '100%',
                            placeholder: "Select Classification",
                            allowClear: true
                        });

                        select.val(null).trigger('change');
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching classifications:", error);
                    }
                });
            }

            $(document).on('change', '#credit_classids', function() {
                var selectedValue = $(this).val();
                console.log(selectedValue);

                if (selectedValue === "add_new") {
                    $('#modal-classification-credit').modal('show');
                    $(this).val('').trigger('change.select2');
                }

                if ($(this).val() != 0) {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                } else {
                    $(this).removeClass('is-valid');
                    $(this).addClass('is-invalid');
                }

                credit_validation();

                $(document).on('click', '#class_body tr', function() {
                    var headerid = $(this).attr('data-id');
                });

                $(document).on('change', '#class_group-credit', function() {
                    if ($(this).val() == '') {
                        $('#class_itemized-credit').prop('disabled', true);
                        $('#class_itemized-credit').prop('checked', false);
                    } else {
                        $('#class_itemized-credit').prop('disabled', false);
                    }
                });

                $(document).on('click', '#class_create', function() {
                    $('#modal-classification-credit').modal('show');
                    $('#class_description-credit').val('');
                    $('#class_account-credit').val(0).change();
                    $('#class_group-credit').val('').change();
                    $('#class_save-credit').attr('data-id', 0);
                });

                $(document).on('click', '#class_save-credit', function() {
                    console.log('naclick ni');

                    var description = $('#class_description-credit').val();
                    var account = $('#class_account-credit').val();
                    var group = $('#class_group-credit').val();
                    var dataid = $('#class_save-credit').attr('data-id');
                    var classcode = $('#class_code-credit').val();

                    var itemized = $('#class_itemized-credit').prop('checked') ? 1 : 0;

                    if (dataid == 0) {
                        $.ajax({
                            type: "GET",
                            url: "{{ route('itmclscreate') }}",
                            data: {
                                description: description,
                                account: account,
                                group: group,
                                itemized: itemized,
                                classcode: classcode
                            },
                            success: function(data) {
                                if (data == 'done') {
                                    Swal.fire({
                                        toast: true,
                                        position: 'top-end',
                                        icon: 'success',
                                        title: 'Item classification saved successfully',
                                        showConfirmButton: false,
                                        timer: 3000
                                    });

                                    $('#modal-classification').modal('hide');
                                    genclassidcredit();
                                } else {
                                    Swal.fire({
                                        toast: true,
                                        position: 'top-end',
                                        icon: 'error',
                                        title: 'Item classification already exists',
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                                }
                            }
                        });
                    } else {
                        $.ajax({
                            type: "GET",
                            url: "{{ route('itmclsupdate') }}",
                            data: {
                                dataid: dataid,
                                description: description,
                                account: account,
                                group: group,
                                itemized: itemized,
                                classcode: classcode
                            },
                            success: function(data) {
                                if (data == 'done') {
                                    Swal.fire({
                                        toast: true,
                                        position: 'top-end',
                                        icon: 'success',
                                        title: 'Item classification updated successfully',
                                        showConfirmButton: false,
                                        timer: 3000
                                    });

                                    $('#modal-classification-credit').modal('hide');
                                    genclassidcredit();
                                } else {
                                    Swal.fire({
                                        toast: true,
                                        position: 'top-end',
                                        icon: 'error',
                                        title: 'Item classification already exists',
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                                }
                            }
                        });
                    }
                });
            });

            $("#credit_classids").change(function() {
                if ($(this).val() === "add_new") {
                    $("#modal-classification-credit").modal("show");
                    $(this).val("0"); // Reset dropdown to default
                }
            });


            $("#credit_itemsave").click(function(e) {
                e.preventDefault();

                var itemid = $("#credit_item").val();
                var amount = $("#credit_itemamount").val() || 0;
                var studid = $("#studNamev4").val();

                $.ajax({
                    url: '{{ route('adjitems_append') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        itemid: itemid,
                        amount: amount,
                        studid: studid
                    },
                    success: function(data) {
                        console.log(data, 'data');

                        var newRow = `
                            <tr data-id="${data.id}" data-amount="${data.amount}" data-classid="${data.classid}">
                                <td>${data.description}</td>
                                <td>${data.classification}</td>
                                <td>${data.amount}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm remove-item" data-id="${data.id}">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        `;

                        $('#credit_itemlist').append(newRow);
                        updateTotalAmount();

                        if ($('#credit_classid').val() == 0) {
                            $('#credit_classid').val(data.classid).trigger('change');
                            $('#credit_classid').trigger('select2:close');
                        }
                    },
                    error: function(xhr) {
                        console.log("Error:", xhr.responseText);
                    }
                });
            });

            $(document).on("click", ".remove-item", function() {
                var row = $(this).closest("tr");
                row.remove();
                updateTotalAmount();
            });

            function updateTotalAmount() {
                var itemamount = 0;

                $('#debit_itemlist tr').each(function() {
                    var amountStr = $(this).attr('data-amount') || "0";
                    var amount = amountStr.replace(/,/g, '');
                    itemamount += parseFloat(amount);
                });

                console.log(itemamount, 'Updated itemamount');
                $('#debit_amount').val(itemamount.toFixed(2));
            }

            $("#debit_classid").on("select2:open", function() {
                $(".select2-results__option").each(function() {
                    if ($(this).text().includes("+ Add Classification")) {
                        $(this).css({
                            "color": "blue",
                            "font-weight": "bold"
                        });
                    }
                });
            });

            function genclassid() {
                $.ajax({
                    type: "GET",
                    url: "/finance/itemclassification/fetchItemClassification",
                    success: function(data) {
                        var select = $('#debit_classid');

                        select.empty();

                        select.append('<option value="add_new">+ Add Classification</option>');

                        $.each(data, function(index, classItem) {
                            select.append('<option value="' + classItem.id + '">' + classItem
                                .description + '</option>');
                        });

                        select.select2({
                            theme: 'bootstrap4',
                            width: '100%',
                            placeholder: "Select Classification",
                            allowClear: true
                        });

                        select.val(null).trigger('change');
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching classifications:", error);
                    }
                });
            }

            $(document).on("select2:open", "#debit_classid", function() {
                $(".select2-results__option").each(function() {
                    if ($(this).text().trim() === "+ Add Classification") {
                        $(this).css({
                            "color": "blue",
                            "font-weight": "bold"
                        });
                    }
                });
            });

            $(document).on('change', '#debit_mop', function() {
                if ($(this).val() != 0) {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                } else {
                    $(this).removeClass('is-valid');
                    $(this).addClass('is-invalid');
                }

                debit_validation();
            });

            $(document).on('keyup', '#credit_description', function() {
                if ($(this).val() != '') {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                } else {
                    $(this).removeClass('is-valid');
                    $(this).addClass('is-invalid');
                }

                credit_validation();
            });

            $(document).on('keyup', '#credit_amount', function() {
                if ($(this).val() != '') {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                } else {
                    $(this).removeClass('is-valid');
                    $(this).addClass('is-invalid');
                }

                credit_validation();
            });

            $('#modal-adjustment').on('hidden.bs.modal', function() {
                $(this).find('input').val('');
                $(this).find('select').prop('selectedIndex', 0);
                $(this).find('.is-invalid').removeClass('is-invalid');
                $('#debit_itemlist').empty();
                $('#debit_save, #credit_save').prop('disabled', true);
            });

            $(document).on('click', '#debit_save', function() {
                var desc = $('#debit_description').val();
                var classid = $('#debit_classid').val();
                var amount = $('#debit_amount').val();
                var mop = $('#debit_mop').val();
                var grantee = $('#debit_grantee').val();

                var studid = $(this).data('studid') || $('#studName').val();
                var syid = $(this).data('syid') || $('#sy').val();
                var semid = $(this).data('semid') || $('#sem').val();
                var levelid = $(this).data('levelid');
                var mol = $(this).data('mol');
                var granteeid = $(this).data('granteeid');
                var acadprogid = $(this).data('acadprogid');

                console.log("Level ID:", levelid);
                console.log("Student ID:", studid);
                console.log("SY ID:", syid);
                console.log("Semester ID:", semid);
                console.log("Grantee ID:", granteeid);
                console.log("Mode of Learning (MOL):", mol);

                let itemIds = [];

                $('#debit_itemlist tr').each(function() {
                    let id = $(this).data('id');
                    if (id !== undefined) {
                        itemIds.push(id);
                    }
                });

                if (debit_validation() == 0) {
                    $('#debit_save').prop('disabled', true);

                    $.ajax({
                        url: '{{ route('ledgeradj_debitsave') }}',
                        type: 'GET',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            studid: studid,
                            description: desc,
                            classid: classid,
                            amount: amount,
                            mop: mop,
                            syid: syid,
                            semid: semid,
                            mol: mol,
                            grantee: granteeid,
                            acadprog: acadprogid,
                            levelid: levelid,
                            items: itemIds
                        },
                        success: function(data) {
                            $('.updq').trigger('change');
                            $('#modal-adjustment').modal('hide');

                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Debit adjustment saved successfully!',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });

                            refreshLedger(studid, syid, semid, null);
                            $('#debit_save').prop('disabled', false);
                        },
                        error: function(xhr) {
                            $('#debit_save').prop('disabled', false);

                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to save debit adjustment. Please try again.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'warning',
                        title: 'Please fill all the required fields',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            });


            $(document).on('hidden.bs.modal', 'modal-adjustment', function() {
                $("#debit_save").prop('disabled', false);
            });

            $(document).on('click', '#btnadjustledger', function() {
                if ($('#studNamev4').val() > 0) {
                    $('#row_debit').show();
                    $('#row_credit').hide();
                    $('#adj_btndebit').addClass('active');
                    $('#adj_btncredit').removeClass('active');

                    $('#debit_description').val('');
                    $('#debit_amount').val('');
                    $('#debit_classid').val(0);
                    $('#debit_mop').val(0);
                    $('#debit_mop').trigger('change');
                    $('#debit_classid').trigger('change');
                    $('#debit_description').trigger('keyup');
                    $('#debit_amount').trigger('keyup');

                    $('#credit_description').val('');
                    $('#credit_classid').val(0);
                    $('#credit_amount').val('');
                    $('#credit_classid').trigger('change');
                    $('#credit_description').trigger('keyup');
                    $('#credit_amount').trigger('keyup');


                    loadadjinfo($('#studNamev4').val());
                }
            });

            function loadadjinfo(studid) {
                var syid = $('#sy').val();
                var semid = $('#sem').val();

                $.ajax({
                    url: '{{ route('ledgeradj_loadadjinfo') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        studid: studid,
                        syid: syid,
                        semid: semid
                    },
                    success: function(data) {
                        $('.studname').text(data.name);
                        $('.levelname').text(data.levelname);
                        $('.grantee').text(data.grantee);
                        $('.strand').text(data.strand);

                        if (data.strand == null) {
                            $('#divstrand').hide();
                            $('.strand').hide();
                        } else {
                            $('#divstrand').show();
                            $('.strand').show();
                        }

                        $('#credit_classid').html(data.class);
                        $('#modal-adjustment').modal('show');

                        $('#debit_description').val('');
                        $('#debit_classid').val(0).trigger('change');
                        $('#debit_amount').val('');
                        $('#debit_mop').val(0).trigger('change');

                        $('#debit_save').attr('data-levelid', data.levelid);
                        $('#debit_save').attr('data-studid', studid);
                        $('#debit_save').attr('data-granteeid', data.granteeid);
                        $('#debit_save').attr('data-mol', data.mol);
                        $('#debit_save').attr('data-acadprogid', data.acadprogid);
                        $('#adj_delete').attr('data-studid', studid);
                    }
                });
            }


            $(document).on('click', '#adj_btndebit', function() {

                $(this).addClass('active');
                $('#adj_btncredit').removeClass('active');
                debit_validation();
                $('#row_debit').show();
                $('#row_credit').hide();
            });

            $(document).on('click', '#adj_btncredit', function() {

                $('#row_debit').hide();
                $('#row_credit').show();
                $(this).addClass('active');
                $('#adj_btndebit').removeClass('active');

                credit_validation();
            });

            // working v2 code
            // $(document).on('click', '#credit_save', function() {
            //     var studid = $('#studNamev4').val();
            //     var desc = $('#credit_description').val();
            //     var classid = $('#credit_classid').val();
            //     var amount = $('#credit_amount').val();
            //     var syid = $('#sy').val();
            //     var semid = $('#sem').val();

            //     if (credit_validation() == 0) {
            //         $.ajax({
            //             url: '{{ route('ledgeradj_creditsave') }}',
            //             type: 'GET',
            //             dataType: '',
            //             data: {
            //                 studid: studid,
            //                 desc: desc,
            //                 classid: classid,
            //                 amount: amount,
            //                 syid: syid,
            //                 semid: semid
            //             },
            //             success: function(data) {
            //                 $('.updq').trigger('change');
            //                 $('#modal-adjustment').modal('hide');
            //             }
            //         });
            //     } else {
            //         const Toast = Swal.mixin({
            //             toast: true,
            //             position: 'top-end',
            //             showConfirmButton: false,
            //             timer: 3000,
            //             timerProgressBar: true,
            //             didOpen: (toast) => {
            //                 toast.addEventListener('mouseenter', Swal.stopTimer)
            //                 toast.addEventListener('mouseleave', Swal.resumeTimer)
            //             }
            //         })

            //         Toast.fire({
            //             type: 'warning',
            //             title: 'Please fill all the required fields'
            //         })
            //     }
            // });

            $(document).on('click', '#credit_save', function() {
                var studid = $('#studNamev4').val();
                var desc = $('#credit_description').val();
                var classid = $('#credit_classids').val();
                var amount = $('#credit_amount').val();
                var syid = $('#sy').val();
                var semid = $('#sem').val();

                let itemIds = [];

                $('#credit_itemlist tr').each(function() {
                    let id = $(this).data('id');
                    if (id !== undefined) {
                        itemIds.push(id);
                    }
                });
                
                if (credit_validation() == 0) {
                    $.ajax({
                        url: '{{ route('ledgeradj_creditsave') }}',
                        type: 'GET',
                        dataType: '',
                        data: {
                            studid: studid,
                            desc: desc,
                            classid: classid,
                            amount: amount,
                            syid: syid,
                            semid: semid,
                            items: itemIds
                        },
                        success: function(data) {
                            if (data.status == 2) {
                                // const Toast = Swal.mixin({
                                //     toast: true,
                                //     position: 'top-end',
                                //     showConfirmButton: false,
                                //     timer: 3000,
                                //     timerProgressBar: true,
                                //     didOpen: (toast) => {
                                //         toast.addEventListener('mouseenter', Swal
                                //             .stopTimer)
                                //         toast.addEventListener('mouseleave', Swal
                                //             .resumeTimer)
                                //     }
                                // })

                                // Toast.fire({
                                //     type: 'error',
                                //     title: 'Adjustment already exist'
                                // })

                            } else {


                                $('.updq').trigger('change');
                                $('#modal-adjustment').modal('hide');

                                refreshLedger(studid, syid, semid, null);

                            }
                        }
                    });
                } else {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        type: 'warning',
                        title: 'Please fill all the required fields'
                    })
                }
            });

            function debit_validation() {
                var count = 0;
                $('.debit_field').each(function() {
                    if ($(this).hasClass('is-invalid')) {
                        count += 1;
                    }
                });

                if (count > 0) {
                    $('#debit_save').prop('disabled', true);
                } else {
                    $('#debit_save').prop('disabled', false);
                }

                return count;
            }

            function credit_validation() {
                var count = 0;
                $('.credit_field').each(function() {
                    if ($(this).hasClass('is-invalid')) {
                        count += 1;
                    }
                });

                if (count > 0) {
                    $('#credit_save').prop('disabled', true);
                } else {
                    $('#credit_save').prop('disabled', false);
                }

                return count;
            }

            $(document).on('click', '#btnprint', function() {
                if ($('#studNamev4').val() > 0) {
                    var syid = $('#sy').val();
                    var semid = $('#sem').val();
                    var studid = $('#studNamev4').val();

                    // window.open('/finance/pdfledger/' + studid + '/' + syid + '/' + semid , '_blank');
                    window.open('/finance/pdfledger?studid=' + studid + '&syid=' + syid + '&semid=' + semid,
                        '_blank');
                } else {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        type: 'error',
                        title: 'Please select a student'
                    })
                }
            })

            $(document).on('click', '#btnreminder', function() {
                $('#modal-reminder').modal('show');
            });

            $(document).on('change', '#reminder_due', function() {
                var studid = $('#studName').val();
                var syid = $('#sy').val();
                var semid = $('#sem').val();
                var duedate = $(this).val();

                $.ajax({
                    url: '{{ route('ledger_reminder') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        studid: studid,
                        syid: syid,
                        semid: semid,
                        duedate: duedate,
                        action: 'generate'
                    },
                    success: function(data) {
                        $('#reminder_list').html(data.list);
                    }
                });
            });

            $(document).on('click', '#reminder_print', function() {
                var studid = $('#studName').val();
                var syid = $('#sy').val();
                var semid = $('#sem').val();
                var duedate = $('#reminder_due').val();


                window.open('/finance/ledger_reminder?duedate=' + duedate + '&action=print&studid=' +
                    studid + '&syid=' + syid + '&semid=' + semid, '_blank');
            })

            $(document).on('click', '#btnstudyload', function() {
                var syid = $('#sy').val();
                var semid = $('#sem').val();
                var studid = $('#studNamev4').val();

                if (studid != null) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('ledger_studyload') }}",
                        data: {
                            syid: syid,
                            semid: semid,
                            studid: studid
                        },
                        dataType: "json",
                        success: function(data) {
                            $('#sl_list').html(data.list);
                            $('#sl_sy').text(data.sydesc);
                            $('#sl_sem').text(data.semdesc);
                            $('#sl_course').text(data.course);

                            if (data.droppedSubjects.length > 0) {
                                Swal.fire({
                                    type: 'warning',
                                    title: 'Dropped Subjects Detected',
                                    text: 'The following subjects have been dropped: ' +
                                        data.droppedSubjects.join(', '),
                                    confirmButtonText: 'OK'
                                });
                            }
                        }
                    });

                    $('#modal-studyload').modal('show');
                } else {
                    Swal.fire({
                        type: 'warning',
                        title: 'Please Select a Student',
                        timer: 3000,
                        toast: true,
                        position: 'top',
                        showConfirmButton: false
                    });
                }
            });


            $(document).on('click', '.adj_delete', function() {
                var dataid = $(this).attr('data-id');
                // var studid = $(this).attr('data-studid');
                var studid = $('#studNamev4').val();
                var feesid = $('#feesname').attr('data-id');
                var syid = $('#sy').val();
                var semid = $('#sem').val();

                Swal.fire({
                    title: 'Remove Adjustment?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Remove'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ route('ledgeradj_delete') }}',
                            type: 'GET',
                            data: {
                                dataid: dataid,
                                studid: studid,
                                feesid: feesid,
                                syid: syid,
                                semid: semid
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                $('.updq').trigger('change');

                                Swal.fire({
                                    type: 'success',
                                    title: 'Adjustment removed',
                                    showConfirmButton: false,
                                    timer: 3000
                                });

                                refreshLedger(studid, syid, semid);
                            }
                        });
                    }
                });
            });



            $(document).on('click', ".adj_view", function() {
                var dataid = $(this).attr('data-id');
                console.log('naclick ni');


                $.ajax({
                    type: "GET",
                    url: "{{ route('ledgeradj_view') }}",
                    data: {
                        dataid: dataid
                    },
                    success: function(data) {
                        console.log(data, 'Response Data');

                        // Update header fields
                        $('#adjv_particulars').text(data.particulars);
                        $('#adjv_type').text(data.type);
                        $('#adjv_class').text(data.headerclassname);
                        $('#adjv_date').text(data.adjdate);
                        $('#adjv_totalamount').text(data.headeramount);

                        // Clear previous table content
                        $('#adjinfolist').empty();

                        if (Array.isArray(data.adjitems) && data.adjitems.length > 0) {
                            $.each(data.adjitems, function(index, val) {
                                $('#adjinfolist').append(`
                                    <tr data-id="${val.adjitemid}">
                                        <td>${val.itemname}</td>
                                        <td>${val.classname}</td>
                                        <td class="text-right">${parseFloat(val.amount.replace(/,/g, '')).toLocaleString()}</td>
                                    </tr>
                                `);
                            });

                            // Display Total Row
                            let totalClass = (data.headeramount == data.totalitemamount) ?
                                "text-bold" : "text-bold text-danger";
                            $('#adjinfolist').append(`
                                <tr>
                                    <td colspan="2" class="text-right ${totalClass}">TOTAL:</td>
                                    <td class="text-right ${totalClass}">${parseInt(data.totalitemamount.replace(/,/g, '')).toLocaleString()}</td>
                                </tr>
                            `);
                        } else {
                            $('#adjinfolist').append(`
                                <tr>
                                    <td colspan="3" class="text-center">No Items Found</td>
                                </tr>
                            `);
                        }

                        // Show modal
                        $("#modal-adjustment_view").modal('show');
                    }
                });
            })


            $(document).on('click', '.discount_delete', function() {
                var dataid = $(this).attr('data-id');
                var studid = $(this).attr('studid');
                var feesid = $('#feesname').attr('data-id');
                var syid = $('#sy').val();
                var semid = $('#sem').val();

                Swal.fire({
                    title: 'Remove Discount?',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Remove'
                }).then((result) => {
                    if (result.value == true) {
                        $.ajax({
                            url: '{{ route('ledgerdiscount_delete') }}',
                            type: 'GET',
                            dataType: '',
                            data: {
                                dataid: dataid,
                                studid: studid,
                                feesid: feesid,
                                syid: syid,
                                semid: semid
                            },
                            success: function(data) {
                                // get_history_ledger();
                                refreshLedger(studid, syid, semid);
                                $('.updq').trigger('change');
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter',
                                            Swal.stopTimer)
                                        toast.addEventListener('mouseleave',
                                            Swal.resumeTimer)
                                    }
                                })

                                Toast.fire({
                                    type: 'success',
                                    title: 'Discount removed'
                                })
                            }
                        });

                    }
                })
            });

            $(document).on('click', '.ledgeroa_delete', function() {
                var dataid = $(this).attr('data-id');
                var studid = $('#studName').val();
                var feesid = $('#feesname').attr('data-id');
                var syid = $('#sy').val();
                var semid = $('#sem').val();

                Swal.fire({
                    title: 'Remove Old account?',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Remove'
                }).then((result) => {
                    if (result.value == true) {
                        $.ajax({
                            url: '{{ route('ledgeroa_delete') }}',
                            type: 'GET',
                            dataType: '',
                            data: {
                                dataid: dataid,
                                studid: studid,
                                feesid: feesid,
                                syid: syid,
                                semid: semid
                            },
                            success: function(data) {
                                $('.updq').trigger('change');
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter',
                                            Swal.stopTimer)
                                        toast.addEventListener('mouseleave',
                                            Swal.resumeTimer)
                                    }
                                })

                                Toast.fire({
                                    type: 'success',
                                    title: 'Old account removed'
                                })
                            }
                        });

                    }
                })
            });

            //working v2 code
            // function get_student_ledger() {
            //     var syid = $('#sy').val();
            //     var semid = $('#sem').val();
            //     var studid = $('#studNamev4').val();
            //     var batchid = $('#tvlbatch').val();

            //     $.ajax({
            //         type: "GET",
            //         url: "/finance/getStudLedgerV2",
            //         data: {
            //             syid: syid,
            //             studid: studid,
            //             semid: semid,
            //             batchid: batchid
            //         },
            //         success: function(data) {
            //             console.log(data, 'getStudLedgerV2');
            //             var tbody = $("#student_fees");
            //             tbody.empty(); // Clear previous rows

            //             let totalCharges = 0;
            //             let totalPayments = 0;
            //             let totalBalance = 0; // Sum of all (charge - payment)

            //             data.forEach(item => {
            //                 // Format date
            //                 let date = new Date(item.createddatetime);
            //                 let formattedDate = date.toLocaleDateString("en-US", {
            //                     year: "numeric",
            //                     month: "short",
            //                     day: "2-digit"
            //                 }); // Example: "Jul 16, 2024"

            //                 // Convert amounts to float for calculation
            //                 let charge = parseFloat(item.amount) || 0;
            //                 let totalpaid = parseFloat(item.totalpaid) || 0;
            //                 if (item.payment > 0 && totalpaid == 0 && item.particulars.includes(
            //                         "OLD ACCOUNTS FORWARDED TO")) {
            //                     totalpaid = parseFloat(item.payment) || 0;
            //                 }

            //                 // Calculate balance for this row
            //                 let balance = charge - totalpaid;

            //                 // Update total values
            //                 totalCharges += charge;
            //                 console.log(totalCharges , 'totalCharges');

            //                 totalPayments += totalpaid;
            //                 console.log(totalPayments, 'totalPayments');

            //                 totalBalance += balance;
            //                 console.log(totalBalance, 'totalBalance');
            //                 // Append row to table
            //                 tbody.append(`
            //                     <tr>
            //                         <td>${formattedDate}</td>
            //                         <td style="cursor:pointer" data-syid="${item.syid}" data-studid="${item.studid}" data-semid="${item.semid}" class="view_ledger">${item.particulars}
            //                             ${item.particular_items.length > 0 ? `
            //                                                             <a href="javascript:void(0)" class="view-items" data-items='${JSON.stringify(item.particular_items)}' data-bs-toggle="tooltip" data-bs-html="true" title="Breakdown">
            //                                                                 <i class="fas fa-caret-down"></i>
            //                                                             </a>
            //                                                         ` : ''}
            //                              ${item.history && item.history.length > 0 ? `
            //                                                             <a href="javascript:void(0)" class="view-items2" data-items2='${JSON.stringify(item.history)}' data-bs-toggle="tooltip" data-bs-html="true" title="Breakdown">
            //                                                                 <i class="fas fa-caret-down"></i>
            //                                                             </a>
            //                                                         ` : ''}
            //                         </td>
            //                         <td class="text-center">${charge.toFixed(2)}</td>
            //                         <td class="text-center">${totalpaid.toFixed(2)}</td>
            //                         <td class="text-center">${balance.toFixed(2)}</td>
            //                         </tr>
            //                     `);
            //             });

            //             // Append total row at the bottom
            //             tbody.append(`
            //                 <tr class="bg-warning" style="font-weight: bold;">
            //                     <td colspan="2" class="text-right">TOTAL:</td>
            //                     <td class="text-center">${totalCharges.toFixed(2)}</td>
            //                     <td class="text-center">${totalPayments.toFixed(2)}</td>
            //                     <td class="text-center">${totalBalance.toFixed(2)}</td>
            //                 </tr>
            //             `);
            //         }
            //     });
            // }

                function get_student_ledger() {
                var syid = $('#sy').val();
                var semid = $('#sem').val();
                var studid = $('#studNamev4').val();
                var batchid = $('#tvlbatch').val();

                $.ajax({
                    type: "GET",
                    url: "/finance/getStudLedgerV2",
                    data: {
                        syid: syid,
                        studid: studid,
                        semid: semid,
                        batchid: batchid
                    },
                    success: function(data) {
                        console.log(data, 'getStudLedgerV2');
                        var tbody = $("#student_fees");
                        tbody.empty(); // Clear previous rows

                        let totalCharges = 0;
                        let totalPayments = 0;
                        let totalBalance = 0; // Sum of all (charge - payment)

                        data.forEach(item => {
                            // Format date
                            let date = new Date(item.createddatetime);
                            let formattedDate = date.toLocaleDateString("en-US", {
                                year: "numeric",
                                month: "short",
                                day: "2-digit"
                            }); // Example: "Jul 16, 2024"

                            // Convert amounts to float for calculation
                            let charge = parseFloat(item.amount) || 0;
                            let totalpaid = parseFloat(item.totalpaid) || 0;
                            if (item.payment > 0 && totalpaid == 0 && item.particulars.includes(
                                    "OLD ACCOUNTS FORWARDED TO")) {
                                totalpaid = parseFloat(item.payment) || 0;
                            }

                            // Calculate balance for this row
                            let balance = charge - totalpaid;

                            // Update total values
                            totalCharges += charge;
                            console.log(totalCharges , 'totalCharges');

                            totalPayments += totalpaid;
                            console.log(totalPayments, 'totalPayments');

                            totalBalance += balance;
                            console.log(totalBalance, 'totalBalance');
                            // Append row to table
                            tbody.append(`
                                <tr>
                                    <td>${formattedDate}</td>
                                    <td style="cursor:pointer" data-syid="${item.syid}" data-studid="${item.studid}" data-semid="${item.semid}" class="view_ledger">${item.particulars}
                                        ${item.particular_items.length > 0 ? `
                                                                        <a href="javascript:void(0)" class="view-items" data-items='${JSON.stringify(item.particular_items)}' data-bs-toggle="tooltip" data-bs-html="true" title="Breakdown">
                                                                            <i class="fas fa-caret-down"></i>
                                                                        </a>
                                                                    ` : ''}
                                         ${item.history ? `
                                                                        <a href="javascript:void(0)" class="view-items2" data-items2='${JSON.stringify(item.history)}' data-bs-toggle="tooltip" data-bs-html="true" title="Breakdown">
                                                                            <i class="fas fa-caret-down"></i>
                                                                        </a>
                                                                    ` : ''}
                                    </td>
                                    ${ item.particulars.includes("OLD ACCOUNTS FORWARDED FROM") ?
                                        `<td class="text-center"> <a href="javascript:void(0)" class="btn_oldbalance"> ${charge.toFixed(2)} </a></td>` :  `<td class="text-center">${charge.toFixed(2)}</td>`
                                    }
                                    <td class="text-center">${totalpaid.toFixed(2)}</td>
                                    <td class="text-center">${balance.toFixed(2)}</td>
                                    </tr>
                                `);
                        });

                        // Append total row at the bottom
                        tbody.append(`
                            <tr class="bg-warning" style="font-weight: bold;">
                                <td colspan="2" class="text-right">TOTAL:</td>
                                <td class="text-center">${totalCharges.toFixed(2)}</td>
                                <td class="text-center">${totalPayments.toFixed(2)}</td>
                                <td class="text-center">${totalBalance.toFixed(2)}</td>
                            </tr>
                        `);
                    }
                });
            }


            $('[data-bs-toggle="tooltip"]').tooltip();

            function get_history_ledger() {
                var syid = $('#sy').val();
                var semid = $('#sem').val();
                var studid = $('#studNamev4').val();
                var batchid = $('#tvlbatch').val();

                $.ajax({
                    type: "GET",
                    url: "/finance/history_list",
                    data: {
                        syid: syid,
                        studid: studid,
                        semid: semid,
                        batchid: batchid
                    },
                    success: function(data) {
                        console.log(data, 'eheheeheh');

                        var tbody = $("#history_list");
                        tbody.empty(); // Clear previous rows

                        let totalCharge = 0;
                        let totalPayment = 0;

                        data.forEach(item => {
                            // Format date
                            let date = new Date(item.createddatetime);
                            let formattedDate = date.toLocaleDateString("en-US", {
                                year: "numeric",
                                month: "short",
                                day: "2-digit"
                            }); // Example: "Jul 16, 2024"

                            // Convert amounts to float for calculation
                            let charge = parseFloat(item.amount) || 0;
                            let payment = parseFloat(item.payment) || 0;

                            totalCharge += charge;
                            totalPayment += payment;

                            // Display empty string if 0, otherwise format to 2 decimal places
                            let chargeDisplay = charge === 0 ? "" : charge.toFixed(2);
                            let paymentDisplay = payment === 0 ? "" : payment.toFixed(2);

                            // Append row to table
                            tbody.append(`
                            <tr>
                                <td>${formattedDate}</td>
                                <td>
                                    <span ${item.cashier_items_count > 0 || item.notrecievable_count > 0 ? 'style="color: green;"' : ''}>${item.particulars}</span>
                                    ${item.classid !== null || item.particulars.includes('ADJ:') ? `
                                                                <span class="text-sm text-danger adj_delete" style="cursor:pointer" data-id="${item.ornum}">
                                                                    <i class="far fa-trash-alt"></i>
                                                                </span>
                                                                <span class="text-sm text-info adj_view" style="cursor:pointer" data-id="${item.ornum}" data-toggle="tooltip" title="View Adjustment">
                                                                    <i class="fas fa-archive"></i>
                                                                </span>
                                                            ` : ''}

                                    ${item.classid === null && !item.particulars.includes('DISCOUNT:') && !item.particulars.includes('ADJ:') ? `
                                                                <a href="javascript:void(0)" transid="${item.transid}" id="view_receipts">
                                                                    view receipts
                                                                </a>
                                                            ` : ''}

                                    ${item.particulars.includes('DISCOUNT:') ? `
                                                                <span class="text-sm text-danger discount_delete" studid="${studid}" style="cursor:pointer" data-id="${item.ornum}">
                                                                    <i class="far fa-trash-alt"></i>
                                                                </span>
                                                            ` : ''}
                                </td>
                                <td class="text-center" style="vertical-align: middle">${chargeDisplay}</td>
                                <td class="text-center" style="vertical-align: middle">${paymentDisplay}</td>
                                <td class="text-center"></td>
                            </tr>
                        `);


                        });

                        // Append total row
                        tbody.append(`
                    <tr class="font-weight-bold bg-info">
                        <td colspan="2" class="text-right"><b>Total:</b></td>
                        <td class="text-center">${totalCharge.toFixed(2)}</td>
                        <td class="text-center">${totalPayment.toFixed(2)}</td>
                        <td></td>
                    </tr>
                    `);
                    }
                });
            }

            $(document).on('click', '#view_receipts', function() {
                var transid = $(this).attr('transid');

                $.ajax({
                    type: "GET",
                    url: "/finance/view_receipts",
                    data: {
                        transid: transid
                    },
                    success: function(data) {


                        var tbody = $('#paid_list');
                        tbody.empty();

                        data.or_details.forEach((item, index) => {
                            tbody.append(`
                            <tr>
                            <td class="text-center">${index + 1}.</td>  
                            <td>${item.particulars}</td>  
                            <td class="text-center">${item.itemprice}</td>  
                            <td class="text-center">${item.amount}</td>  
                            </tr>
                        `);
                        });

                        tbody.append(`
                        <tr>
                            <td colspan="2" class="text-right font-weight-bold">Total:</td>  
                            <td class="text-center font-weight-bold">${parseFloat(data.total_itemprice).toFixed(2)}</td>  
                            <td class="text-center font-weight-bold">${parseFloat(data.total_amount).toFixed(2)}</td>  
                        </tr>
                        `);

                        $('#total_amount').text(`${parseFloat(data.total_amount).toFixed(2)}`);

                        $('#modal_receipts_view').modal('show');
                    }
                });
            });


            // added by clydev
            // toggle old history
            $(document).on("click", ".view-items2", function() {
                var row = $(this).closest("tr"); // Get the parent row
                var nextRow = row.next(); // Check if the next row is already a breakdown row

                if (nextRow.hasClass("breakdown-row")) {
                    nextRow.remove(); // Remove the breakdown row if already opened
                    return;
                }

                var items = JSON.parse($(this).attr("data-items2")); // Get items from attribute
                console.log(items, 'items...');
                
                var breakdownHTML = `
                    <tr class="breakdown-row bg-light">
                        <td colspan="5" class="p-0">
                        <table width="100%" class="table table-sm text-sm" style="background-color: white;">
                            <thead hidden>
                            <tr>
                                <th width="15%"></th>
                                <th width="40%">PARTICULARS</th>
                                <th width="15%" class="text-center">CHARGES</th>
                                <th width="15%" class="text-center">PAYMENT</th>
                                <th width="15%" class="text-center">BALANCE</th>
                            </tr>
                            </thead>
                            <tbody style="background-color: white;">
                            ${(Array.isArray(items) ? items : Object.values(items || {})).map(item => {
                                // Safely extract values with defaults
                                const particulars = item?.particulars || '';
                                const charge = Math.max(0, parseFloat(item?.balance) || 0);
                                const payment = 0;
                                const balance = 0;

                                return `
                                    <tr style="background-color: white;">
                                        <td width="15%"></td>
                                        <td width="40%">${particulars}</td>
                                        <td width="15%" class="text-center">${charge.toFixed(2)}</td>
                                        <td width="15%" class="text-center">${payment.toFixed(2)}</td>
                                        <td width="15%" class="text-center">${balance.toFixed(2)}</td>
                                    </tr>
                                `;
                            }).join('')}
                            </tbody>
                        </table>
                        </td>
                    </tr>
                `;

                row.after(breakdownHTML); // Insert breakdown row below main row
            });


            $(document).on("click", ".view-items", function() {
                var row = $(this).closest("tr"); // Get the parent row
                var nextRow = row.next(); // Check if the next row is already a breakdown row

                if (nextRow.hasClass("breakdown-row")) {
                    nextRow.remove(); // Remove the breakdown row if already opened
                    return;
                }

                var items = JSON.parse($(this).attr("data-items")); // Get items from attribute
                var breakdownHTML = `
                <tr class="breakdown-row bg-light">
                    <td colspan="5" class="p-0">
                    <table width="100%" class="table table-sm text-sm" style="background-color: white;">
                        <thead hidden>
                        <tr>
                            <th width="15%"></th>
                            <th width="40%">PARTICULARS</th>
                            <th width="15%" class="text-center">CHARGES</th>
                            <th width="15%" class="text-center">PAYMENT</th>
                            <th width="15%" class="text-center">BALANCE</th>
                        </tr>
                        </thead>
                        <tbody style="background-color: white;">
                        ${items.map(item => {
                            // Ensure all numeric values are properly converted
                            let charge = parseFloat(item.ledger_classid === 1 ? item.amount : item.itemamount) || 0;
                            let payment = parseFloat(item.ledger_classid === 1 ? item.amountpay : item.totalamount) || 0;
                            let balance = (item.ledger_classid === 1 ? parseFloat(item.balance) : (charge - payment)) || 0;
                            
                            return `
                                <tr style="background-color: white;">
                                    <td width="15%"></td>
                                    <td width="40%">${item.ledger_classid === 1 ? item.tuition_month : item.description}</td>
                                    <td width="15%" class="text-center">${charge.toFixed(2)}</td>
                                    <td width="15%" class="text-center">${payment.toFixed(2)}</td>
                                    <td width="15%" class="text-center">${balance.toFixed(2)}</td>
                                </tr>
                            `;
                        }).join("")}
                        </tbody>
                    </table>
                    </td>
                </tr>
                `;



                row.after(breakdownHTML); // Insert breakdown row below main row
            });

            // $(document).on('mouseover', '#ledger-list tr', function(){
            //   if($(this).hasClass('t_hover'))
            //   {
            //     $(this).addClass('bg-primary');
            //   }
            // });

            // $(document).on('mouseout', '#ledger-list tr', function(){
            //   if($(this).hasClass('t_hover'))
            //   {
            //     $(this).removeClass('bg-primary');
            //   }
            // });

            // $(document).on('click', '#adj_btncredit', function(){
            //   var studid = $('#studName').val();

            //   $.ajax({
            //     url: '{route('ledgeradj_loadcreditinfo')}}',
            //     type: 'GET',
            //     dataType: 'json',
            //     data: {
            //       studid:studid
            //     },
            //     success:function(data)
            //     {
            //       $('#row_debit').hide();
            //       $('#row_credit').show();
            //       $(this).addClass('active');
            //       $('#adj_btndebit').removeClass('active');
            //       $('#credit_class_list').html(data.list);
            //       $('#credit_totalbalance').text(data.totalbalance);

            //     }
            //   });
            // });

            // $(document).on('change', '.credit_adjamount', function(){
            //   var totalamount = 0;
            //   var _amount = 0;

            //   $('.credit_adjamount').each(function(){
            //     if($(this).val() != null && $(this).val() != '')
            //     {

            //       _amount = $(this).val().replace(',', '');
            //       console.log(_amount);
            //       totalamount += parseFloat(_amount);
            //     }
            //   });

            //   $('#credit_totaladj').val(totalamount);
            //   $('#credit_totaladj').focus();
            //   $(this).focus();
            // })
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

            $(document).on('change', '#filter_sy', function() {
                all_students = []
                load_update_info_datatable()
                get_admission_setup()
                load_all_sections()
                load_enrollment_summary()
                // load_all_preregstudent()
                // load_all_student()
                get_gradelvl()
                get_mol()
            })

            $(document).on('change', '#filter_sem', function() {
                all_students = []
                load_update_info_datatable()
                load_all_sections()
                load_enrollment_summary()
                get_admission_setup()
                // load_all_preregstudent()
                // load_all_student()
            })

            $(document).on('change', '#sy', function() {
                all_students = []
                load_update_info_datatable()
                get_admission_setup()
                load_all_sections()
                load_enrollment_summary()
                // load_all_preregstudent()
                // load_all_student()
                get_gradelvl()
                get_mol()
            })

            $(document).on('change', '#sem', function() {
                all_students = []
                load_update_info_datatable()
                load_all_sections()
                load_enrollment_summary()
                get_admission_setup()
                // load_all_preregstudent()
                // load_all_student()
            })

            $(document).on('change', '#filter_section', function() {
                var activeRequestsTable = $('#update_info_request_table').DataTable();
                activeRequestsTable.state.clear();
                activeRequestsTable.destroy();
                load_update_info_datatable(true)
            })

            $(document).on('change', '#filter_studentstatus', function() {
                var activeRequestsTable = $('#update_info_request_table').DataTable();

                if ($(this).val() == 0 || $(this).val() == null) {
                    $('.transdate_holder').attr('hidden', 'hidden')
                    $('#filter_transdate').val("")
                    $('#filter_enrollmentdate').val("")
                } else {
                    $('.transdate_holder').removeAttr('hidden')
                }

                activeRequestsTable.state.clear();
                activeRequestsTable.destroy();
                load_update_info_datatable()
            })
            $(document).on('change', '#filter_gradelevel', function(e) {
                var gradeLevel = $(this).val();
                load_update_info_datatable();
            });

            var activeRequestsTable = $('#update_info_request_table').DataTable();
            activeRequestsTable.state.clear();
            activeRequestsTable.destroy();
            load_update_info_datatable(true)

            // $(document).on('change', '#filter_gradelevel', function() {
            //     console.log('naclick');

            //     // $('#filter_section').val("").change()
            //     var activeRequestsTable = $('#update_info_request_table').DataTable();
            //     activeRequestsTable.state.clear();
            //     activeRequestsTable.destroy();
            //     load_update_info_datatable(true)
            // })


            // 



            // load_update_info_datatable();

            function load_update_info_datatable(withpromp = false) {
                var filter_status = $('#filter_studstatus').val();
                var gradeLevel = $('#filter_gradelevel').val();
                var section = $('#filter_section').val();
                var temp_sy = sy.filter(x => x.id == $('#filter_sy').val());

                if (temp_sy.length == 0) {
                    var temp_sy = sy.filter(x => x.active == 1)
                }

                temp_sy = temp_sy[0]
                $("#update_info_request_table").DataTable({
                    destroy: true,
                    // data:temp_data,
                    autoWidth: false,
                    stateSave: true,
                    serverSide: true,
                    processing: true,
                    // ajax:'/student/preregistration/list',
                    ajax: {
                        url: '/student/preregistration/list',
                        type: 'GET',
                        data: {
                            syid: $('#sy').val(),
                            semid: $('#sem').val(),
                            addtype: $('#filter_entype').val(),
                            paystat: $('#filter_paymentstat').val(),
                            procctype: $('#filter_process').val(),
                            studstat: $('#filter_studentstatus').val(),
                            fillevelid: gradeLevel, // Filter by Grade Level
                            fillsectionid: section,
                            activestatus: $('#filter_activestatus').val(),
                            transdate: $('#filter_transdate').val(),
                            enrollmentdate: $('#filter_enrollmentdate').val(),
                            processtype: $('#filter_process').val(),
                            // processtype: $('#filter_studentstatus').val(),
                        },
                        dataSrc: function(json) {

                            all_students = json.data
                            if (withpromp) {

                                Toast.fire({
                                    type: 'info',
                                    title: json.recordsTotal + ' student(s) found.'
                                })

                                firstPrompt = false
                            }
                            return json.data;
                        }
                    },
                    // order: [[ 1, "asc" ]],
                    columns: [{
                            "data": null,
                            'orderable': false,
                            "className": 'align-middle',
                            "render": function(data, type, row) {
                                var html = ''
                                if (row.studstatus == 0) {
                                    html =
                                        '<div class="bg-secondary" style="width:20px;height:20px;margin:0 auto;"></div>'
                                } else if (row.studstatus == 1) {
                                    html =
                                        '<div style="width:20px;height:20px;background-color:green;margin:0 auto;"></div>'
                                } else if (row.studstatus == 2) {
                                    html =
                                        '<div style="width:20px;height:20px;background-color:#58715f;margin:0 auto;"></div>'
                                } else if (row.studstatus == 3) {
                                    html =
                                        '<div style="width:20px;height:20px;background-color:red;margin:0 auto;"></div>'
                                } else if (row.studstatus == 4) {
                                    html =
                                        '<div class="bg-primary" style="width:20px;height:20px;margin:0 auto;"></div>'
                                } else if (row.studstatus == 5) {
                                    html =
                                        '<div style="width:20px;height:20px;background-color:#fd7e14;margin:0 auto;"></div>'
                                } else if (row.studstatus == 6) {
                                    html =
                                        '<div class="bg-warning" style="width:20px;height:20px;margin:0 auto;"></div>'
                                } else if (row.studstatus == 7) {
                                    html =
                                        '<div style="width:20px;height:20px;background-color:black;margin:0 auto;"></div>'
                                }
                                return '<div class="text-center">' + html + '</div>'
                            }

                        },
                        {
                            "data": "sid"
                        },
                        {
                            "data": "student"
                        },

                        {
                            "data": "sortid"
                        },
                        {
                            "data": "sectionname",
                            "render": function(data, type, row) {
                                return data ? data.toUpperCase() : '';
                            }
                        },
                        {
                            "data": null
                        },
                        {
                            "data": null
                        },
                        {
                            "data": null
                        },
                        {
                            "data": "description"
                        },
                    ],
                    columnDefs: [{
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var text = rowData.student
                                $(td)[0].innerHTML = text
                                $(td).addClass('align-middle')
                            }
                        },

                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                if (rowData.studstatus == 0) {
                                    $(td)[0].innerHTML =
                                        '<span  style="font-size:13px !important">' + rowData
                                        .levelname + '</span>'
                                } else {
                                    $(td)[0].innerHTML =
                                        '<span style="font-size:13px !important">' + rowData
                                        .levelname + '</span>'
                                }
                                $(td).addClass('align-middle')
                                $(td).addClass('text-center')
                            }
                        },
                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {

                                $(td).removeAttr('hidden')
                                if (filter_status == 0 && filter_status != '') {
                                    if (rowData.withprereg == 1) {
                                        $(td)[0].innerHTML = rowData.admission_type_desc + ' : ' +
                                            '<span class="text-success" style="font-size:11px !important">' +
                                            rowData.submission + '</span>'
                                    } else {
                                        $(td).text(null)
                                    }

                                } else {
                                    if (rowData.enlevelid == 14 || rowData.enlevelid == 15) {
                                        $(td)[0].innerHTML = rowData.sectionname +
                                            ' : <span class="text-success" style="font-size:11px !important">' +
                                            rowData.strandcode + '</span>'
                                    }

                                }
                                $(td).addClass('align-middle')
                            }
                        },

                        {
                            'targets': 5,
                            'orderable': false,
                            'width': '10%',
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var text = (parseFloat(rowData.ledgeramount)).toFixed(
                                    2).replace(
                                    /\d(?=(\d{3})+\.)/g, '$&,');
                                $(td)[0].innerHTML = text;
                                $(td).addClass('align-middle text-left');
                            }
                        },
                        {
                            'targets': 6,
                            'orderable': false,
                            'width': '10%',
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var text = (parseFloat(rowData.ledgerpayment)).toFixed(
                                    2).replace(
                                    /\d(?=(\d{3})+\.)/g, '$&,');
                                $(td)[0].innerHTML = text;
                                $(td).addClass('align-middle text-left');
                            }
                        },
                        {
                            'targets': 7,
                            'orderable': false,
                            'width': '10%',
                            'createdCell': function(td, cellData, rowData, row, col) {
                                // $(td).text('')
                                var text = (parseFloat(rowData.ledgerbalance)).toFixed(
                                    2).replace(
                                    /\d(?=(\d{3})+\.)/g, '$&,');

                                // var text = rowData.ledgerbalance;
                                $(td)[0].innerHTML = text;
                                $(td).addClass('align-middle text-left');

                            }
                        },
                        {
                            'targets': 8,
                            'orderable': false,
                            'width': '15%',
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var link =
                                    '<a href="#" style="color: #blue; text-decoration: underline;" id="studName" class="view_account" data-id="' +
                                    rowData.studid +
                                    '"> View Account</a>';
                                $(td)[0].innerHTML = link;
                                $(td).addClass('text-center align-middle');

                            }
                        }
                    ],
                });



                var mol_options =
                    '<div class="btn-group ml-2 col-sm-12 col-md-3">' +
                    '<button type="button" class="btn btn-default btn-sm">Printables</button>' +
                    '<button type="button" class="btn btn-default dropdown-toggle dropdown-icon btn-sm" data-toggle="dropdown">' +
                    '<span class="sr-only">Toggle Dropdown</span>' +
                    '</button>' +
                    '<div class="dropdown-menu" role="menu">' +
                    '<a class="dropdown-item print_mol" data-id="1" href="#">MOL By MOL</a>' +
                    '<a class="dropdown-item print_mol" data-id="2" href="#">MOL By Grade Level</a>' +
                    '<a class="dropdown-item print_mol" data-id="3" href="#">MOL By Section</a>' +
                    '<a class="dropdown-item print_sf1" data-id="pdf" href="#">SF1(PDF)</a>' +
                    '<a class="dropdown-item print_sf1" data-id="excel" href="#">SF1(EXCEL)</a>' +
                    '<a class="dropdown-item print_enrollment"  href="#" >Enrollment</a>' +
                    '</div>' +
                    '</div>'
                var btn_readyto_enroll =
                    '<button type="button" class="btn btn-sm ml-2 btn-warning" id="ready_to_enroll"><i class="fa fa-check-circle"></i> Ready to Enroll</button>'



                if (school_setup.abbreviation == 'BCT') {
                    if (usertype_session == 8) {
                        var label_text = $($('#update_info_request_table_wrapper')[0].children[0])[0].children[0]
                        // $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm add_student_to_prereg">Add Student to Preregistration</button><button class="btn btn-primary btn-sm ml-2" id="reservation_list">Reservation List</button>'
                        $(label_text)[0].innerHTML =
                            ' <button class="btn btn-primary btn-sm" id="create_new_student"><i class="fa fa-plus"></i> Create New Student</button> <button class="btn btn-default btn-sm ml-2" id="vac_info"><i class="fa fa-medkit"></i> Vaccine Information</button>' +
                            mol_options
                    }

                } else {
                    // var label_text = $($('#update_info_request_table_wrapper')[0].children[0])[0].children[0]
                    var label_text = $('.btn_wrap')
                    if (usertype_session == 3 || usertype_session == 17 || usertype_session == 8) {
                        // $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm add_student_to_prereg" >Add Student to Preregistration</button>'
                        $(label_text)[0].innerHTML =
                            ' <div class="col-md-3 col-sm-12">' +
                            ' <button style="width:100% !important" class="btn btn-primary btn-sm d-block d-sm-inline-block" id="create_new_student"><i class="fa fa-plus"></i> Create New Student</button>' +
                            ' </div>' +
                            ' <div class="col-md-3 col-sm-12 mt-2 mt-md-0">' +
                            ' <button style="width:100% !important" class="btn btn-default btn-sm d-block d-sm-inline-block" id="vac_info"><i class="fa fa-medkit"></i> Vaccine Information</button>' +
                            ' </div>' +
                            ' <div class="col-md-3 col-sm-12 mt-2 mt-md-0">' +
                            ' <button style="width:100% !important" class="btn btn-warning btn-sm d-block d-sm-inline-block" id="ready_to_enroll"><i class="fa fa-check-circle"></i> Ready to Enroll</button>' +
                            ' </div>' +
                            ' <div class="col-md-3 col-sm-12 mt-2 mt-md-0">' +
                            mol_options +
                            ' </div>'
                    } else if (refid == 30) {
                        $(label_text)[0].innerHTML =
                            ' <div class="col-md-3 col-sm-12">' +
                            ' <button style="width:100% !important" class="btn btn-primary btn-sm d-block d-sm-inline-block" id="create_new_student"><i class="fa fa-plus"></i> Create New Student</button>' +
                            ' </div>'
                    } else {
                        console.error('No button options available for this user type.');
                    }
                }

                // if (temp_sy.ended == 1) {
                //     $('.add_student_to_prereg').remove() whats the purpose
                // }


                // if(usertype == 3 || usertype_session == 3 || usertype == 17 || usertype_session == 17){
                //       if(student_to_enroll != null && student_to_enroll != ""){
                //             var oTable = $('#update_info_request_table').DataTable();
                //             oTable.search( student_to_enroll ).draw();
                //       }
                // }

                // if(student_to_enroll == null || student_to_enroll == ""){
                //       var oTable = $('#update_info_request_table').DataTable();
                //       oTable.search("").draw();
                // }

                $('#gradelevel_readytoenroll').select2();
                $(document).on('change', '#gradelevel_readytoenroll', function() {
                    fetchReadyToEnrollStud();
                });
                // Trigger the modal when the button is clicked
                $(document).on('click', '#ready_to_enroll', function() {
                    fetchReadyToEnrollStud();
                });

                $('#notifyAll').on('click', function() {
                    Swal.fire(
                        'Success!',
                        'Notified Successfully!',
                        'success'
                    )
                });

                // Handle applying filters (You can replace this with an AJAX request to filter data)
                $('#applyFilters').on('click', function() {
                    var startDate = $('#startDate').val();
                    var endDate = $('#endDate').val();
                    var gradeLevel = $('#gradeLevel').val();

                    // Example of handling the filter - in practice, you can use AJAX to load filtered data


                    // Close the modal after applying filters
                    $('#readyToEnrollModal').modal('hide');
                });

                $(document).on('click', '.notify_individual', function() {
                    var phone = $(this).data('phone');
                    var parentphone = $(this).data('parentphone');
                    if (phone == null || phone == '') {
                        phone = parentphone;
                    }

                    if (phone.length != 11) {
                        Swal.fire(
                            'Error!',
                            'Phone number is not valid!',
                            'error'
                        )
                    } else {
                        phone = "+63" + phone.substr(1);
                    }

                    $(this).html('<i class="fas fa-bell"></i>');

                    $.ajax({
                        url: '{{ route('notify_individual_student') }}',
                        method: 'POST',
                        data: {
                            phone: phone,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            if (data.status == 'success') {

                                Swal.fire(
                                    'Success!',
                                    'Student successfully notified!',
                                    'success'
                                )
                            } else {
                                Swal.fire(
                                    data.status,
                                    data.message,
                                )
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Error!',
                                xhr.responseText,
                                'error'
                            )
                        }
                    });
                })
            }
            // $("#update_info_request_table").DataTable({
            //     destroy: true,
            //     // data:temp_data,
            //     autoWidth: false,
            //     stateSave: true,
            //     serverSide: true,
            //     processing: true,
            //     // ajax:'/student/preregistration/list',
            //     ajax: {
            //         url: '/student/preregistration/list',
            //         type: 'GET',
            //         data: {
            //             syid: $('#filter_sy').val(),
            //             semid: $('#filter_sem').val(),
            //             addtype: $('#filter_entype').val(),
            //             paystat: $('#filter_paymentstat').val(),
            //             procctype: $('#filter_process').val(),
            //             studstat: $('#filter_studstatus').val(),
            //             fillevelid: $('#filter_gradelevel').val(),
            //             fillsectionid: $('#filter_section').val(),
            //             activestatus: $('#filter_activestatus').val(),
            //             transdate: $('#filter_transdate').val(),
            //             enrollmentdate: $('#filter_enrollmentdate').val(),
            //             processtype: $('#filter_process').val(),
            //         },
            //         dataSrc: function(json) {

            //             all_students = json.data
            //             if (withpromp) {

            //                 Toast.fire({
            //                     type: 'info',
            //                     title: json.recordsTotal + ' student(s) found.'
            //                 })

            //                 firstPrompt = false
            //             }
            //             return json.data;
            //         }
            //     },
            //     // order: [[ 1, "asc" ]],
            //     columns: [{
            //             "data": null,
            //             'orderable': false,
            //             "className": 'align-middle',
            //             "render": function(data, type, row) {
            //                 var html = ''
            //                 if (row.studstatus == 0) {
            //                     html =
            //                         '<div class="bg-secondary" style="width:20px;height:20px;margin:0 auto;"></div>'
            //                 } else if (row.studstatus == 1) {
            //                     html =
            //                         '<div style="width:20px;height:20px;background-color:green;margin:0 auto;"></div>'
            //                 } else if (row.studstatus == 2) {
            //                     html =
            //                         '<div style="width:20px;height:20px;background-color:#58715f;margin:0 auto;"></div>'
            //                 } else if (row.studstatus == 3) {
            //                     html =
            //                         '<div style="width:20px;height:20px;background-color:red;margin:0 auto;"></div>'
            //                 } else if (row.studstatus == 4) {
            //                     html =
            //                         '<div class="bg-primary" style="width:20px;height:20px;margin:0 auto;"></div>'
            //                 } else if (row.studstatus == 5) {
            //                     html =
            //                         '<div style="width:20px;height:20px;background-color:#fd7e14;margin:0 auto;"></div>'
            //                 } else if (row.studstatus == 6) {
            //                     html =
            //                         '<div class="bg-warning" style="width:20px;height:20px;margin:0 auto;"></div>'
            //                 } else if (row.studstatus == 7) {
            //                     html =
            //                         '<div style="width:20px;height:20px;background-color:black;margin:0 auto;"></div>'
            //                 }
            //                 return '<div class="text-center">' + html + '</div>'
            //             }

            //         },
            //         {
            //             "data": "sid"
            //         },
            //         {
            //             "data": "student"
            //         },
            //         {
            //             "data": null
            //         },
            //         {
            //             "data": "sortid"
            //         },
            //         {
            //             "data": "sectionname",
            //             "render": function(data, type, row) {
            //                 return data ? data.toUpperCase() : '';
            //             }
            //         },
            //         {
            //             "data": "description"
            //         },
            //         {
            //             "data": "enrollment"
            //         },
            //         {
            //             "data": "search"
            //         },
            //     ],
            //     columnDefs: [{
            //             'targets': 1,
            //             'orderable': false,
            //             'createdCell': function(td, cellData, rowData, row, col) {
            //                 $(td).addClass('align-middle')
            //             }
            //         },
            //         {
            //             'targets': 2,
            //             'orderable': false,
            //             'createdCell': function(td, cellData, rowData, row, col) {
            //                 var text = rowData.student

            //                 // if(rowData.nodp == 1){
            //                 // //      text += '<br>'
            //                 //      text += ' <span class="badge-primary badge">No DP Allowed</span>'
            //                 // }

            //                 // if(rowData.withpayment == 1){
            //                 //      if(rowData.nodp == 1 == 0){
            //                 //       // text += '<br>'
            //                 //      }
            //                 //      text += ' <span class="badge-success badge">Payment : &#8369;'+rowData.payment+'</span>'
            //                 // }

            //                 $(td)[0].innerHTML = text

            //                 $(td).addClass('align-middle')
            //             }
            //         },
            //         {
            //             'targets': 3,
            //             'orderable': false,
            //             'createdCell': function(td, cellData, rowData, row, col) {
            //                 if (usertype_session != 6 && usertype_session != 14 &&
            //                     usertype_session != 16 &&
            //                     refid != 28 && refid != 29) {
            //                     var text = ''

            //                     if (usertype == 6 || refid == 28) {
            //                         $(td)[0].innerHTML = text
            //                         $(td).addClass('align-middle')
            //                         return false;
            //                     }

            //                     if (rowData.nodp == 1) {
            //                         var text =
            //                             ' <span class="badge-primary badge">No DP Allowed</span>'
            //                         $(td).addClass('bg-primary')
            //                         $(td).addClass('text-center')
            //                     }

            //                     if (rowData.withpayment == 1) {

            //                         if (rowData.studstatus == 1 || rowData.studstatus == 2 ||
            //                             rowData
            //                             .studstatus == 4) {
            //                             var text = 'DP Paid'
            //                             $(td).addClass('text-center')
            //                         } else {
            //                             var text =
            //                                 '<span style="font-size:.7rem !important"> &#8369; &nbsp;' +
            //                                 rowData.payment.toFixed(2).replace(/\d(?=(\d{3})+\.)/g,
            //                                     "$&,") +
            //                                 '</span>'
            //                             $(td).addClass('text-right')
            //                         }



            //                         $(td).addClass('bg-success')
            //                     }

            //                     $(td)[0].innerHTML = text
            //                     $(td).addClass('align-middle')
            //                 } else {
            //                     var text = null
            //                     $(td)[0].innerHTML = text
            //                     $(td).addClass('align-middle')
            //                 }

            //             }
            //         },
            //         {
            //             'targets': 4,
            //             'orderable': false,
            //             'createdCell': function(td, cellData, rowData, row, col) {
            //                 if (rowData.studstatus == 0) {
            //                     // $(td).text(rowData.levelname)
            //                     $(td)[0].innerHTML = '<span  style="font-size:13px !important">' +
            //                         rowData
            //                         .levelname + '</span>'
            //                 } else {
            //                     $(td)[0].innerHTML = '<span style="font-size:13px !important">' +
            //                         rowData
            //                         .levelname + '</span>'
            //                 }
            //                 $(td).addClass('align-middle')
            //                 $(td).addClass('text-center')
            //             }
            //         },
            //         {
            //             'targets': 5,
            //             'orderable': false,
            //             'createdCell': function(td, cellData, rowData, row, col) {
            //                 console.log(rowData, 'askjdhaksjdhasdakhdkajsd');

            //                 $(td).removeAttr('hidden')
            //                 if (filter_status == 0 && filter_status != '') {
            //                     if (rowData.withprereg == 1) {
            //                         $(td)[0].innerHTML = rowData.admission_type_desc + ' : ' +
            //                             '<span class="text-success" style="font-size:11px !important">' +
            //                             rowData.submission + '</span>'
            //                     } else {
            //                         $(td).text(null)
            //                     }

            //                 } else {
            //                     if (rowData.enlevelid == 14 || rowData.enlevelid == 15) {
            //                         $(td)[0].innerHTML = rowData.sectionname +
            //                             ' : <span class="text-success" style="font-size:11px !important">' +
            //                             rowData.strandcode + '</span>'
            //                     }

            //                 }
            //                 $(td).addClass('align-middle')
            //             }
            //         },
            //         {
            //             'targets': 6,
            //             'orderable': false,
            //             'createdCell': function(td, cellData, rowData, row, col) {
            //                 $(td)[0].innerHTML = '<span style="font-size:.7rem !important">' +
            //                     rowData
            //                     .description + '</span>'
            //                 if (filter_status == 0 && filter_status != '') {

            //                     var text = ''
            //                     if (rowData.finance_status == 'APPROVED') {
            //                         text +=
            //                             '<span class="badge badge-success d-block mt-1">Finance Approved</span> '
            //                     }
            //                     if (rowData.admission_status == 'APPROVED') {
            //                         text +=
            //                             '<span class="badge badge-warning d-block mt-1">Admission Approved</span> '
            //                     }

            //                     $(td)[0].innerHTML = text
            //                 } else {

            //                     if (school_setup.withMOL == 1) {
            //                         var temp_mol = all_mol.filter(x => x.id == rowData.mol)
            //                         if (temp_mol.length > 0) {
            //                             $(td).text(temp_mol[0].description)
            //                         } else {
            //                             $(td).text(null)
            //                         }
            //                     } else {
            //                         $(td).text(null)
            //                     }
            //                     // if(rowData.studstatus == 0){
            //                     //       $(td)[0].innerHTML = '<span style="font-size:.7rem !important">'+rowData.description+'</span>'
            //                     // }else{
            //                     //       // $(td)[0].innerHTML = '<a href="javascript:void(0)" data-preregid="'+rowData.id+'" class="view_enrollment" data-id="'+rowData.studid+'" style="font-size:.7rem !important">'+rowData.description+'</a>'
            //                     //       rowData.description
            //                     // }
            //                     $(td).removeAttr('hidden')

            //                 }

            //                 if (rowData.studstatus == 1 || rowData.studstatus == 2 || rowData
            //                     .studstatus ==
            //                     4) {
            //                     // $(td).addClass('bg-success')
            //                 } else if (rowData.studstatus == 0) {

            //                 } else {
            //                     // $(td).addClass('bg-secondary')
            //                 }

            //                 $(td).addClass('align-middle')
            //             }
            //         },
            //         {
            //             'targets': 7,
            //             'orderable': false,
            //             'createdCell': function(td, cellData, rowData, row, col) {
            //                 // if (rowData.studstatus == 0) {
            //                 //     // $(td).text(rowData.submission)
            //                 // } else {
            //                 //     $(td).text(rowData.enrollment)
            //                 // }
            //                 // $(td).addClass('align-middle')

            //             }
            //         },
            //         {
            //             'targets': 8,
            //             'orderable': false,
            //             'createdCell': function(td, cellData, rowData, row, col) {




            //             }
            //         },
            //     ],
            //     createdRow: function(row, data, dataIndex) {


            //         $(row).attr("data-id", data.studid);
            //         $(row).attr("data-preregid", data.id);

            //         // if(usertype == 8 || usertype == 4 || usertype == 15 || usertype_session == 8 || usertype_session == 4 || usertype_session == 15){
            //         //       $(row).addClass("enroll");
            //         // }

            //         if (data.studstatus != 0) {
            //             $(row).addClass("view_enrollment");
            //         } else {
            //             $(row).addClass("enroll");
            //         }
            //     },

            // });
        });
   
   </script>
@endsection