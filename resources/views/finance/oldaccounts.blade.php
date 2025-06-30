@extends('finance.layouts.app')

@section('content')
    @php
        $check_refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->select('refid')->first();
        $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
        $refid = $check_refid->refid;
        $semester = DB::table('semester')->get();

        $strand = DB::table('sh_strand')
            ->select('id', 'strandname', 'strandcode')
            ->where('deleted', 0)
            ->where('active', 1)
            ->get();
        $acadprog = DB::table('academicprogram')->select('academicprogram.*', 'progname as text')->get();

        $acadprog_list = [];
        foreach ($acadprog as $item) {
            array_push($acadprog_list, $item->id);
        }

        $all_gradelevel = DB::table('gradelevel')
            ->where('deleted', 0)
            ->orderBy('sortid')
            ->select('id', 'levelname', 'levelname as text', 'acadprogid')
            ->get();

        $gradelevel = DB::table('gradelevel')
            ->where('deleted', 0)
            ->whereIn('acadprogid', $acadprog_list)
            ->orderBy('sortid')
            ->select('id', 'levelname', 'levelname as text', 'acadprogid')
            ->get();
        $curriculum = DB::table('college_curriculum')
            ->where('deleted', 0)
            ->select('id', 'courseID', 'curriculumname as text')
            ->get();

        $courses1 = DB::table('college_courses')
            ->join('college_colleges', function ($join) {
                $join->on('college_courses.collegeid', '=', 'college_colleges.id');
                $join->where('college_colleges.acadprogid', 6);
                $join->where('college_colleges.deleted', 0);
            })
            ->where('college_courses.deleted', 0)
            ->select('college_courses.*')
            ->get();
        $courses2 = DB::table('college_courses')
            ->join('college_colleges', function ($join) {
                $join->on('college_courses.collegeid', '=', 'college_colleges.id');
                $join->where('college_colleges.acadprogid', 8);
                $join->where('college_colleges.deleted', 0);
            })
            ->where('college_courses.deleted', 0)
            ->select('college_courses.*')
            ->get();
    @endphp
    <section class="content">
        <style>
            .legend-box {
                width: 12px;
                height: 12px;
                display: inline-block;
                border-radius: 2px;
            }
        </style>
        <div class="row">
            <div class="col-md-8">
                <h1>
                    Old Accounts
                </h1>
            </div>
            <div class="col-md-4 text-right">
                <button id="oa_setup" class="btn btn-default btn-lg" data-toggle="tooltip" title="Old Account Setup">
                    <i class="fas fa-cogs"></i>
                </button>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-2">
                <label for="" class="mb-1">School Year</label>
                <select name="sy" id="sy" class="form-control form-control-sm select2">
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

            <div class="col-md-2  form-group mb-0" id="filter_gradelevel_holder">
                <label for="" class="mb-1">Grade Level</label>
                <select class="form-control select2" id="filter_gradelevel" style="width:100%;">
                </select>
            </div>
            {{-- <div class="col-md-2"> --}}
            {{-- <button id="oa_createoldacc" class="btn btn-info" style="margin-top: 32px;">Create Old Account</button> --}}

            {{-- </div>
            <div class="col-md-2">
                <label>Level</label>
                <select id="oa_levelid" class="select2bs4" style="width: 100%;">
                    <option value="0">Grade Level</option>
                    @foreach (db::table('gradelevel')->where('deleted', 0)->orderBy('sortid')->get() as $level)
                        <option value="{{ $level->id }}">{{ $level->levelname }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Old Account from</label>
                <select id="oa_syid" class="select2bs4" style="width: 100%;">

                </select>
            </div>
            <div class="col-md-2">
                <label>&nbsp;</label>
                <select id="oa_semid" class="select2bs4" style="width: 100%;">
                </select>
            </div>
            <div class="col-md-4">
                <label>Forward to <span>{{ App\FinanceModel::getSYDesc() }}</span> <span id="oa_txtsem"
                        style="display: none;"> - {{ App\FinanceModel::getSemDesc() }}</span></label>
                <div class="input-group">
                    <input type="search" id="oa_filter" class="form-control" placeholder="Search"> --}}
            {{-- ///////////////////////////////////////////////// --}}
            {{-- <div class="input-group-append">
            <span class="input-group-text"></span>
          </div> --}}
            {{-- <div class="input-group-append">
                        <button id="oa_search" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                    </div>
                </div>
            </div> --}}
            {{-- /////////////////////////////////////////////// --}}
        </div>
        <div class="row form-group">
            <div class="col-md-12">
                <div class="main-card card">
                    <div class="card-header bg-info">
                    </div>
                    {{-- <div id="main_table" class="card-body table-responsive p-0">
                        <table class="table table-striped table-sm text-sm">
                            <thead class="">
                                <th>Name</th>
                                <th class="text-center">Charges</th>
                                <th class="text-center">Payment</th>
                                <th class="text-center">Balance</th>
                                <th colspan="2">
                                    <button id="forward_all" class="btn btn-primary btn-block" style="display: none"
                                        data-toggle="tooltip" title="Forward All">
                                        <i class="fas fa-external-link-alt"></i> &nbsp;
                                        <span class="badge badge-danger bal_count">0</span>
                                    </button>
                                </th>
                            </thead>
                            <tbody id="balancelist">

                            </tbody>
                        </table>
                    </div> --}}
                    <div class="row mt-4">
                        <div class="col-md-4 ml-2">
                            <button class="btn btn-primary btn-block" id="create_new_student"><i
                                    class="fas fa-plus mr-2"></i>
                                Add Old Student Account</button>
                        </div>
                        <div class="col-md-4 ml-2">
                            <button class="btn btn-danger btn-block" id="print_old_account"><i
                                    class="fas fa-print mr-2"></i>
                                Export</button>
                        </div>

                    </div>

                    <div class="row mt-3">
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

                                            {{-- <th width="8%" class="align-middle text-center p-0 prereg_head"
                                                style="font-size:.9rem !important" data-id="2">Grade Level</th>
                                            <th width="20%" class="align-middle prereg_head" data-id="3">
                                                Section</th> --}}
                                            <th width="20%" class="align-middle prereg_head" data-id="3">
                                                School Year</th>
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
                                    <span class="legend-text" style="color: #fd7e14;"
                                        id="transferredOutCount">TRANSFERRED
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
        </div>
    </section>
@endsection
@section('modal')
    {{-- //working v4 code --}}
    {{-- <div class="modal fade" id="add_old_account_Modal" tabindex="-1" role="dialog"
        aria-labelledby="addOldStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Add Old Student Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 d-flex justify-content-center">
                        <div class="form-group flex-grow-1 mr-2">
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
                        <button type="submit" class="btn btn-success"
                            style="height: calc(2.25rem + 2px); display: none;" id="create_new_student_for_old_Account">
                            Create New Student</button>
                    </div>
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>School Year</label>
                                <select name="sy" id="old_account_sy" class="form-control form-control-sm select2">
                                    @foreach ($sy as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $item->isactive == 1 ? 'selected="selected"' : '' }}>
                                            {{ $item->sydesc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Semester</label>
                                <select name="semester" id="old_account_sem"
                                    class="form-control form-control-sm select2">
                                    @foreach ($semester as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $item->isactive == 1 ? 'selected="selected"' : '' }}>
                                            {{ $item->semester }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Student ID</label>
                                <input type="text" class="form-control" placeholder="Enter Student ID"
                                    id="old_account_studid">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Student Name</label>
                                <input type="text" class="form-control" placeholder="Student Name"
                                    id="old_account_studname">
                            </div>
                            <div class="col-md-6">
                                <label>Last Grade Level Attended</label>
                                <select class="form-control" id="old_account_gradelvl">
                                    <option>Select Grade Level</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Balance Payables</label>
                                <input type="text" class="form-control" placeholder="Enter Balance Payable"
                                    id="old_account_balance">
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="modal fade" id="add_old_account_Modal" tabindex="-1" role="dialog"
        aria-labelledby="addOldStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Add Old Student Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 d-flex justify-content-center">
                        <div class="form-group flex-grow-1 mr-2">
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
                        <button type="submit" class="btn btn-success" style="height: calc(2.25rem + 2px); "
                            id="create_new_student_for_old_Account">
                            Create New Student</button>
                    </div>
                    <form>
                        <div class="row mb-3">
                            <input type="text" class="form-control" id="old_account_id" hidden>

                             <div class="col-md-12 mt-2" style="background-color: #f8f9fa;">
                                <label>Origin: </label>
                            </div>
                            <div class="col-md-6">
                                <label>From School Year</label>
                                <select name="from_sy" id="from_sy"
                                    class="form-control form-control-sm select2bs4">
                                    @foreach ($sy as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $item->isactive == 1 ? 'selected="selected"' : '' }}>
                                            {{ $item->sydesc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>From Semester</label>
                                <select name="from_sem" id="from_sem"
                                    class="form-control form-control-sm select2bs4">
                                    @foreach ($semester as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $item->isactive == 1 ? 'selected="selected"' : '' }}>
                                            {{ $item->semester }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 mt-3" style="background-color: #f8f9fa;">
                                <label>Destination: </label>
                            </div>
                            <div class="col-md-6">
                                <label>For School Year</label>
                                <select name="sy" id="old_account_sy"
                                    class="form-control form-control-sm select2bs4">
                                    @foreach ($sy as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $item->isactive == 1 ? 'selected="selected"' : '' }}>
                                            {{ $item->sydesc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>For Semester</label>
                                <select name="semester" id="old_account_sem"
                                    class="form-control form-control-sm select2bs4">
                                    @foreach ($semester as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $item->isactive == 1 ? 'selected="selected"' : '' }}>
                                            {{ $item->semester }}</option>
                                    @endforeach
                                </select>
                            </div>
                           
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Student ID</label>
                                <input type="text" class="form-control" placeholder="Enter Student ID"
                                    id="old_account_studid">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Student Name</label>
                                <input type="text" class="form-control" placeholder="Student Name"
                                    id="old_account_studname">
                            </div>
                            <div class="col-md-6">
                                <label>Last Grade Level Attended</label>
                                <select class="form-control select2bs4" id="old_account_gradelvl">
                                    <option>Select Grade Level</option>
                                </select>
                            </div>
                            {{-- <div class="col-md-6">
                                <label>Last Grade Level Attended</label>
                                <input type="text" class="form-control" placeholder="Enter Last Grade Level"
                                    id="old_account_gradelvl">
                            </div> --}}
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Balance Payables</label>
                                <input type="text" class="form-control" placeholder="Enter Balance Payable"
                                    id="old_account_balance">
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-success" id="old_account_save">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="student_info_modal" style="display: none;" aria-hidden="true" data-backdrop="static"
        data-keyboard="false">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Student Information</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body pt-2" style="font-size:.9rem !important">
                    <div class="row">

                        <div class="col-md-12 table-responsive " style="height: 476px;" id="studinfo_holder">
                            {{-- <div class="row mb-2">
                                <div class="col-md-12 bg-primary pt-1">
                                    <h6 class="mb-1">Student Information</h6>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">Stud. ID</label>
                                    <input type="text" class="form-control form-control-sm-form" id="input_sid_new"
                                        placeholder="SID" style="height: calc(1.7rem + 1px);" autocomplete="off"
                                        disabled>
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">Alternative Stud. ID</label>
                                    <input type="text" class="form-control form-control-sm-form" id="input_altsid_new"
                                        placeholder="Alternate SID" style="height: calc(1.7rem + 1px);"
                                        autocomplete="off">
                                </div>
                                {{-- <div class="col-md-3 form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <!-- Upload Button -->
                                <button class="btn btn-primary btn-sm" id="upload_photo" style="margin: 0;">Upload Photo</button>
                            </div>
                            <input type="file" id="photo_input" accept="image/*" style="display: none;">
                        </div>
                        <div class="col-md-3 form-group">
                            <!-- Image Preview -->
                            <div id="image-preview-container" style="width: 100px; height: 100px; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                <img id="image-preview" src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                            </div>
                        </div> --}}


                            </div>
                            <hr class="mb-1 mt-1">
                            <div class="row">
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1"><span class="text-danger">*</span>Grade Level to
                                        enroll</label>
                                    {{-- <select name="" id="input_gradelevel_new"
                                        class="form-control form-control-sm-form select2 is-required is-invalid"> --}}
                                    <select name="" id="input_gradelevel_new"
                                        class="form-control form-control-sm-form select2 is-required">
                                        <option value="">Select Grade Level</option>
                                        @foreach ($gradelevel as $item)
                                            <option value="{{ $item->id }}">{{ $item->levelname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">LRN</label>
                                    <input type="text" class="form-control form-control-sm-form" id="input_lrn_new"
                                        placeholder="LRN" style="height: calc(1.7rem + 1px);" autocomplete="off">
                                </div>
                                <div class="col-md-2 form-group mb-2">
                                    <label for="" class="mb-1">Student Type</label>
                                    <select name="" id="input_studtype_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off">
                                        <option value="new" selected="selected">New</option>
                                        <option value="old">Old</option>
                                        <option value="transferee">Transferee</option>
                                        <option value="returnee">Returnee</option>
                                    </select>
                                </div>
                                <div class="col-md-2 form-group mb-2">
                                    <label for="" class="mb-1">Grantee</label>
                                    <select name="" id="input_grantee_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off">

                                        <option value="1" selected="selected">REGULAR</option>
                                        <option value="2">ESC</option>
                                        <option value="3">VOUCHER</option>
                                    </select>
                                </div>
                                <div class="col-md-2 form-group mb-2">
                                    <label for="" class="mb-1">Gov. Assistance</label>
                                    <select name="" id="input_pantawid_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off">
                                        <option value="0" selected="selected">Select Government Assistance</option>
                                        <option value="1">4PS</option>
                                        <option value="2">IPS</option>
                                        <option value="3">UCT</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group mb-2" hidden>
                                    <label for="" class="mb-1">Admission Type</label>
                                    <select name="" id="input_addtype_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off"></select>
                                </div>

                                <div class="col-md-7 form-group mb-2" id="input_strand_holder" hidden>
                                    <label for="" class="mb-1"><span class="text-danger">*</span>Strand</label>
                                    <select name="" id="input_strand_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off">
                                        <option value="">Select Strand</option>
                                        @foreach ($strand as $item)
                                            <option value="{{ $item->id }}">{{ $item->strandname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-7 form-group mb-2 input_course_holder" hidden>
                                    <label for="" class="mb-1"><span class="text-danger">*</span>Course</label>
                                    <select name="" id="input_course_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off">
                                        <option value="">Select Course</option>

                                    </select>
                                </div>
                                <div class="col-md-5 form-group mb-2 input_course_holder" hidden>
                                    <label for="" class="mb-1"><span
                                            class="text-danger">*</span>Curriculum</label>
                                    <select name="" id="input_curriculum_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off">
                                        <option value="">Select Curriculum</option>
                                    </select>
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">Mode of learning</label>
                                    <select name="" id="input_mol_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off">
                                        <option value="">Select Mode of Learning</option>
                                    </select>
                                </div>

                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1"><span class="text-danger">*</span>First
                                        Name</label>
                                    <input onkeyup="this.value = this.value.toUpperCase();" type="text"
                                        class="form-control form-control-sm-form is-required" id="input_fname_new"
                                        placeholder="First Name" autocomplete="off">
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="input_mname_new" class="mb-1">Middlename</label>
                                    <input onkeyup="this.value = this.value.toUpperCase();" type="text"
                                        class="form-control form-control-sm-form" id="input_mname_new"
                                        placeholder="Middle Name" autocomplete="off">

                                    <!-- Smaller Checkbox for "No Middlename" -->
                                    <div class="d-inline mt-2">
                                        <input type="checkbox" id="nomiddlename_new" name="nomiddlename_new"
                                            value="1" style="transform: scale(0.9);">
                                        <label for="nomiddlename_new" style="font-size: 0.9rem;">No Middlename</label>
                                    </div>
                                </div>

                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1"><span class="text-danger">*</span>Last
                                        Name</label>
                                    <input onkeyup="this.value = this.value.toUpperCase();" type="text"
                                        class="form-control form-control-sm-form is-required" id="input_lname_new"
                                        placeholder="Last Name" autocomplete="off">
                                </div>
                                <div class="col-md-1 form-group mb-2">
                                    <label for="" class="mb-1">Suffix</label>
                                    <input onkeyup="this.value = this.value.toUpperCase();" type="text"
                                        class="form-control form-control-sm-form" id="input_suffix_new"
                                        placeholder="Suffix" autocomplete="off">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1"><span class="text-danger">*</span>Birth
                                        Date</label>
                                    <input class="form-control form-control-sm-form is-required" id="input_dob_new"
                                        type="date" autocomplete="off" max="9999-12-31">
                                </div>
                                <div class="col-md-2 form-group mb-2">
                                    <label for="" class="mb-1"><span class="text-danger">*</span>Gender</label>
                                    <select name="" id="input_gender_new"
                                        class="form-control form-control-sm-form select2 is-required" autocomplete="off">
                                        <option value="MALE">MALE</option>
                                        <option value="FEMALE">FEMALE</option>
                                    </select>
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1"><span
                                            class="text-danger">*</span>Nationality</label>
                                    <select name="" id="input_nationality_new"
                                        class="form-control form-control-sm-form select2 is-required" autocomplete="off">
                                        @foreach (DB::table('nationality')->where('deleted', 0)->get() as $item)
                                            @if ($item->id == 77)
                                                <option value="{{ $item->id }}" selected="selected">
                                                    {{ $item->nationality }}</option>
                                            @else
                                                <option value="{{ $item->id }}">{{ $item->nationality }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1"><span class="text-danger">*</span>Student
                                        Contact #</label>
                                    <input id="input_scontact_new" class="form-control form-control-sm-form is-required"
                                        placeholder="09XX-XXXX-XXXX" autocomplete="off"">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group mb-2">
                                    <label for="" class="mb-1">Student Email</label>
                                    <input type="text" class="form-control form-control-sm-form" id="input_semail_new"
                                        placeholder="Student Email" autocomplete="off">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">Religion</label><a href="javascript:void(0)"
                                        class="edit_religion pl-2" hidden><i class="far fa-edit"></i></a>
                                    <a href="javascript:void(0)" class="delete_religion pl-2" hidden><i
                                            class="far fa-trash-alt text-danger"></i></a></label>
                                    <select name="" id="input_religion_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off">
                                    </select>
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">Mother Tongue</label><a
                                        href="javascript:void(0)" class="edit_mothertongue pl-2" hidden><i
                                            class="far fa-edit"></i></a>
                                    <a href="javascript:void(0)" class="delete_mothertongue pl-2" hidden><i
                                            class="far fa-trash-alt text-danger"></i></a></label>
                                    <select name="" id="input_mt_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off"></select>
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">Ethnic Group</label><a href="javascript:void(0)"
                                        class="edit_ethnicgroup pl-2" hidden><i class="far fa-edit"></i></a>
                                    <a href="javascript:void(0)" class="delete_ethnicgroup pl-2" hidden><i
                                            class="far fa-trash-alt text-danger"></i></a></label>
                                    <select name="" id="input_egroup_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mb-2">
                                    <label for="" class="mb-1">Place of Birth</label>
                                    <input type="text" class="form-control form-control-sm-form" id="pob"
                                        placeholder="Place of Birth" autocomplete="off">
                                </div>
                                <div class="col-md-4 form-group mb-2">
                                    <label for="" class="mb-1">Marital Status</label>
                                    <select name="" id="input_marital_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off">
                                        <option value="SINGLE">Single</option>
                                        <option value="MARRIED">Married</option>
                                        <option value="DIVORCED">Divorced</option>
                                        <option value="SEPARATED">Separated</option>
                                        <option value="WIDOWED">Widowed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mb-2">
                                    <label for="" class="mb-1">Number of Children in the Family</label>
                                    <input type="text" class="form-control form-control-sm-form" id="input_ncf_new"
                                        placeholder="Number of Children in the Family" autocomplete="off"
                                        oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                </div>
                                <div class="col-md-6 form-group mb-2">
                                    <label for="" class="mb-1">Number of Children Enrolled</label>
                                    <input type="text" class="form-control form-control-sm-form" id="input_nce_new"
                                        placeholder="Number of Children Enrolled" autocomplete="off"
                                        oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <label class="mb-0">Order in the Family (please check):</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group  mb-2">
                                    <div class="icheck-primary d-inline pt-2 mr-2">
                                        <input type="radio" id="input_oitf_eldest" name="oitf" class="oitf"
                                            value="eldest">
                                        <label for="input_oitf_eldest">eldest</label>
                                    </div>
                                    <div class="icheck-primary d-inline pt-2  mr-2">
                                        <input type="radio" id="input_oitf_2nd" name="oitf" class="oitf"
                                            value="2nd">
                                        <label for="input_oitf_2nd">2<sup>nd</sup></label>
                                    </div>
                                    <div class="icheck-primary d-inline pt-2  mr-2">
                                        <input type="radio" id="input_oitf_3rd" name="oitf" class="oitf"
                                            value="3rd">
                                        <label for="input_oitf_3rd">3<sup>rd</sup></label>
                                    </div>
                                    <div class="icheck-primary d-inline pt-2 mr-2">
                                        <input type="radio" id="input_oitf_youngest" name="oitf" class="oitf"
                                            value="youngest">
                                        <label for="input_oitf_youngest">youngest</label>
                                    </div>
                                </div>

                                <div class="col-md-1 form-group">
                                    <label for="" class="mb-1">Others:</label>
                                </div>
                                <div class="col-md-5 form-group">
                                    <input type="text" class="form-control form-control-sm-form" id="input_oitf_new"
                                        placeholder="Other" autocomplete="off">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mb-2">
                                    <label for="" class="mb-1">Monthly Family Income</label>
                                    <input type="text" data-type="currency" class="form-control form-control-sm-form"
                                        id="input_mfi_new" placeholder="Monthly Family Income" autocomplete="off">
                                </div>
                                <div class="col-md-6 form-group mb-2">
                                    <label for="" class="mb-1">Language/s spoken at home</label>
                                    <input type="text" class="form-control form-control-sm-form" id="input_lsah_new"
                                        placeholder="Language/s spoken at home" autocomplete="off">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-12 bg-primary pt-1">
                                    <h6 class="mb-1">Student Address</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">Street</label>
                                    <input type="text" class="form-control form-control-sm-form" id="input_street_new"
                                        placeholder="Street" autocomplete="off">
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">Barangay</label>
                                    <input type="text" class="form-control form-control-sm-form"
                                        id="input_barangay_new" placeholder="Barangay" autocomplete="off">
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">District</label>
                                    <input type="text" class="form-control form-control-sm-form"
                                        id="input_district_new" placeholder="District" autocomplete="off">
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">City/Municipality</label>
                                    <input type="text" class="form-control form-control-sm-form" id="input_city_new"
                                        placeholder="City" autocomplete="off">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="" class="mb-1">Province</label>
                                    <input type="text" class="form-control form-control-sm-form"
                                        id="input_province_new" placeholder="Province" autocomplete="off">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="" class="mb-1">Region</label>
                                    <input type="text" class="form-control form-control-sm-form" id="input_region_new"
                                        placeholder="Region" autocomplete="off">
                                </div>
                            </div>
                            <div class="row  mb-2">
                                <div class="col-md-12 bg-primary pt-1">
                                    <h6 class="mb-1">Parent/Guardian Information </h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <i style="font-size:12px!important" class="text-danger">Scroll right for more
                                        information</i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 table-responsive ">
                                    <table class="table table-sm table-bordered mb-0" width="100%"
                                        style="width:1300px">
                                        <thead>
                                            <tr>
                                                <th class="p-0" width="6%"></th>
                                                <th class="p-0 text-center" width="11%">First Name</th>
                                                <th class="p-0 text-center" width="11%">Middle Name</th>
                                                <th class="p-0 text-center" width="11%">Last Name</th>
                                                <th class="p-0 text-center" width="4%">Suffix</th>
                                                <th class="p-0 text-center" width="9%">Contact #</th>
                                                <th class="p-0 text-center" width="13%" hidden>Occupation/Relation
                                                </th>
                                                <th class="p-0 text-center" width="17%" hidden>Educational Attainment
                                                </th>
                                                <th class="p-0 text-center" width="18%">Home Address</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th class="p-1 align-middle pl-1">Father</th>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_father_fname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_father_mname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_father_lname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_father_sname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_father_contact_new" placeholder="09XX-XXXX-XXXX"
                                                        autocomplete="off"></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="fha" autocomplete="off"
                                                        placeholder="Father Home Address"></td>
                                            </tr>
                                            <tr>
                                                <th class="p-1 align-middle pl-1">Mother</th>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_mother_fname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_mother_mname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_mother_lname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_mother_sname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_mother_contact_new" placeholder="09XX-XXXX-XXXX"
                                                        autocomplete="off"></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="mha" autocomplete="off"
                                                        placeholder="Mother Home Address"></td>
                                            </tr>
                                            <tr>
                                                <th class="p-1 align-middle pl-1" colspan="1"
                                                    style="font-size:7pt !important">Mother Maiden Name </th>
                                                <td class="p-1" colspan="3"><input
                                                        class="form-control form-control-sm-form"
                                                        id="input_mothermaidename_new" autocomplete="off"></td>

                                                <td class="p-1" colspan="2"></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1"></td>
                                            </tr>
                                            <tr>
                                                <th class="p-1 align-middle pl-1">Guardian</th>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_guardian_fname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_guardian_mname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_guardian_lname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_guardian_sname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_guardian_contact_new" placeholder="09XX-XXXX-XXXX"
                                                        autocomplete="off"></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="gha" autocomplete="off"
                                                        placeholder="Guardian Home Address"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label style="font-size: 13px !important" class="text-danger mb-0"><span
                                                    class="text-danger">*</span><b>In case of emergency ( Recipient for
                                                    News, Announcement and School Information)</b></label>
                                        </div>
                                        <div class="col-md-4 pt-1">
                                            <div class="icheck-success d-inline">
                                                <input class="form-control" type="radio" id="father" name="incase"
                                                    value="1" required>
                                                <label for="father">Father
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 pt-1">
                                            <div class="icheck-success d-inline">
                                                <input class="form-control" type="radio" id="mother" name="incase"
                                                    value="2" required>
                                                <label for="mother">Mother
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 pt-1">
                                            <div class="icheck-success d-inline">
                                                <input class="form-control" type="radio" id="guardian" name="incase"
                                                    value="3" required>
                                                <label for="guardian">Guardian
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label style="font-size: .7rem !important"><i>Scroll down for more information.</i></label>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <button class="btn btn-primary btn-sm" id="create_new_student_button"><i
                                    class="fa fa-save"></i> Save</button>
                            <button class="btn btn-success btn-sm" id="update_student_information_button" hidden><i
                                    class="fa fa-save"></i> Save</button>

                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-default btn-sm float-right" id="enrollment_form" hidden><i
                                    class="fa fa-print"></i> Print (PDF)</button>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="view_acountModal" tabindex="-1" role="dialog" aria-labelledby="addTermModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="addTermModalLabel">Student Ledger Account Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form for Adding a New Term -->
                    <input type="text" id="studNamev4" hidden>
                    <input type="text" id="editsy" hidden>
                    <input type="text" id="editsem" hidden>
                    <div class="row mt-4">
                        <div class="col-md-7">
                            {{-- <span id="ledger_info">Level|Section: </span> --}}
                            <span id="ledger_info"></span>
                        </div>
                        {{-- <div class="col-md-5 text-right">
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
                        </div> --}}

                    </div>
                    <div class="row form-group mt-1">
                        {{-- <div class="col-md-3">
                            <span id="ledger_info_status">Status: </span>
                        </div> --}}
                        {{-- <div class="col-md-9 text-right">
                            Current Fees: <span id="feesname" class="text-bold" data-id=""></span>
                        </div> --}}
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div id="table_main" class="table-responsive">
                                <h5 style="font-weight: bold;">Last Grade Level Payable</h5>
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
                                    {{-- <tbody id="ledger-list"> --}}
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
                <div class="modal-footer ">
                    {{-- <button type="submit" form="addTermForm" class="btn btn-primary" id="saveTerm">Add</button> --}}

                    {{-- <div class="">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div> --}}
                    <div>
                        <button id="modalfwdBal_lower_school" type="button" style="display: none"
                            class="btn btn-success float-right" data-id="" action-id=""><i
                                class="fas fa-share"></i> Forward Balance</button>
                        <button id="modalfwdBal_higher_school" type="button" style="display: none"
                            class="btn btn-success float-right" data-id="" action-id=""><i
                                class="fas fa-share"></i> Forward Balance</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="forward_balanceModal_lower_school" tabindex="-1" role="dialog"
        aria-labelledby="addTermModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="addTermModalLabel">Forward Balance To</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="card-body">
                            <input type="text" id="current_school_year_lower" hidden>
                            <div class="form-group row">
                                <label for="sy" class="col-sm-2 col-form-label">School Year</label>
                                <div class="col-sm-10">
                                    <select name="sy" id="oa_sy_lower" class="form-control select2bs4"
                                        style="width: 100%;">
                                        <option value="0"></option>
                                        {{-- @foreach ($sy as $item)
                                            <option value="{{ $item->id }}" data-id="{{ $item->id }}">
                                                {{ $item->sydesc }}
                                            </option>
                                        @endforeach --}}
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="sem" class="col-sm-2 col-form-label oa_sem_label">Semester</label>
                                <div class="col-sm-10">
                                    <select name="oa_sem_lower" id="oa_sem_lower" class="form-control oa_sem_lower"
                                        style="width: 100%; display: none;">
                                        @foreach ($semester as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $item->isactive == 1 ? 'selected="selected"' : '' }}>
                                                {{ $item->semester }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="button" class="btn btn-success"
                                        id="forward_balance_to_nextsy_lower">Forward</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="forward_balanceModal_higher_school" tabindex="-1" role="dialog"
        aria-labelledby="addTermModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="addTermModalLabel">Forward Balance To</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="card-body">
                            <input type="text" id="current_school_year_higher" hidden>
                            <div class="form-group row">
                                <label for="sy" class="col-sm-2 col-form-label">School Year</label>
                                <div class="col-sm-10">
                                    <select name="sy" id="oa_sy_higher" class="form-control select2bs4"
                                        style="width: 100%;">
                                        <option value="0"></option>
                                        {{-- @foreach ($sy as $item)
                                        <option value="{{ $item->id }}" data-id="{{ $item->id }}">
                                            {{ $item->sydesc }}
                                        </option>
                                    @endforeach --}}
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="sem" class="col-sm-2 col-form-label oa_sem_label">Semester</label>
                                <div class="col-sm-10">
                                    <select name="oa_sem_higher" id="oa_sem_higher" class="form-control oa_sem_higher"
                                        style="width: 100%; display: none;">
                                        @foreach ($semester as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $item->isactive == 1 ? 'selected="selected"' : '' }}>
                                                {{ $item->semester }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="button" class="btn btn-success"
                                        id="forward_balance_to_nextsy_higher">Forward</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>




    <div class="modal fade show" id="modal-setup" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h4 class="modal-title">Setup</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="particulars" class="">Classification</label>
                                <div class="col-md-12">
                                    <select id="oa_setupclassid" class="form-control select2bs4" style="width: 100%;">
                                        <option value="0"></option>
                                        @foreach (db::table('itemclassification')->where('deleted', 0)->orderBy('description')->get() as $class)
                                            <option value="{{ $class->id }}">{{ $class->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="particulars" class="">Mode of Payment</label>
                                <div class="col-md-12">
                                    <select id="oa_setupmop" class="form-control select2bs4" style="width: 100%;">
                                        <option value="0"></option>
                                        @foreach (db::table('paymentsetup')->where('deleted', 0)->where('noofpayment', 1)->get() as $mop)
                                            <option value="{{ $mop->id }}">{{ $mop->paymentdesc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="oa_setupclassified">
                                        <label for="oa_setupclassified">
                                            Classified
                                        </label>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <!-- /.card-body -->
                        <!-- /.card-footer -->
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    <div>
                        <button id="oa_setupsave" type="button" class="btn btn-primary" data-dismiss="modal"
                            data-id="" action-id="">Save</button>
                    </div>



                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-mbf" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Create Old Account</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-sm-2">

                                </div>
                                <div class="col-md-6">
                                    <label>
                                        Old Account from: <span id="mbf_sy" data-sy=""></span> <span
                                            id="mbf_sem" data-sem=""></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="particulars" class="col-sm-2 col-form-label">Student</label>
                                <div class="col-md-10">
                                    <select id="studlist" class="form-control select2bs4">
                                        <option value="0"></option>
                                        @php
                                            $students = db::table('studinfo')
                                                ->select(
                                                    db::raw(
                                                        'id, sid, concat(lastname, ", ", firstname) as fullname, middlename',
                                                    ),
                                                )
                                                ->where('deleted', 0)
                                                ->orderBy('lastname')
                                                ->orderBy('firstname')
                                                ->get();
                                        @endphp

                                        @foreach ($students as $stud)
                                            <option value="{{ $stud->id }}">{{ $stud->sid }} -
                                                {{ $stud->fullname }} {{ $stud->middlename }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-2 col-form-label">Amount</label>
                                <div class="col-md-6">
                                    <input type="number" class="form-control validation" id="fwdamount"
                                        placeholder="0.00">
                                </div>
                            </div>


                        </div>
                        <!-- /.card-body -->
                        <!-- /.card-footer -->
                    </form>
                </div>
                <div class="modal-footer justify-content-between ">
                    {{-- <div class="">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    <div>
                        <button id="modalfwdBal" type="button" class="btn btn-primary " data-dismiss="modal"
                            data-id="" action-id="" data-syid="">Forward Balance</button>
                    </div> --}}



                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    {{-- <div class="modal fade show" id="modal-ledger" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Student Ledger</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <span id="oa_studname" class="text-bold"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-body" style="height: 380px; overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-12 table-responsive p-0">
                            <table class="table table-striped table-head-fixed table-sm text-sm">
                                <thead>
                                    <th>DATE</th>
                                    <th>PARTICULARS</th>
                                    <th class="text-right">CHARGES</th>
                                    <th class="text-right">PAYMENT</th>
                                    <th class="text-right">BALANCE</th>
                                </thead>
                                <tbody id="ledger-list">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class=""> --}}
    {{--    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> --}}
    {{-- </div>
                    <div>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div> 
    </div> --}}

    <div class="modal fade" id="modal-old_add" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="margin-top: 110px;">
                <div id="modalhead" class="modal-header bg-success">
                    <h4 class="modal-title">Add Old Accounts</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <select id="old_add_studlist" class="select2bs4 old_req is-invalid" style="width: 100%;">
                                <option value="0">NAME</option>
                                {{-- @foreach (db::table('studinfo')->where('deleted', 0)->orderBy('lastname')->orderBy('firstname')->get() as $stud)
                                    <option value="{{$stud->id}}">
                                        {{$stud->sid . ' - ' . $stud->lastname . ', ' . $stud->firstname}}
                                    </option>
                                @endforeach --}}
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            Level: <span id="old_add_levelname" class="text-bold"></span>
                        </div>
                        <div class="form-group col-md-6">
                            Section/Course: <span id="old_add_section" class="text-bold"></span>
                        </div>
                        <div class="form-group col-md-2 old_add_granteelabel" style="display: block;">
                            Grantee: <span id="old_add_grantee" class="text-bold"></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <h6>Old Account Info</h6>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-8">
                            <div class="form-group col-md-12" style="display: block;">
                                Level|Section: <span id="old_info_level" class="text-bold"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Old Accounts from</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <select id="old_add_sy" class="select2bs4 w-100 old_req is-invalid"
                                        style="width: 100%;">
                                        <option value="0">SCHOOL YEAR</option>
                                        {{-- @foreach (db::table('sy')->orderBy('sydesc')->where('sydesc', '<', App\CashierModel::getSYDesc())->get() as $sy)
                                            <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <select id="old_add_sem" class="select2bs4 w-100 old_req is-invalid"
                                        style="width: 100%;">
                                        <option value="0">SEMESTER</option>
                                        {{-- @foreach (db::table('semester')->where('deleted', 0)->get() as $sem)
                                            <option value="{{$sem->id}}">{{$sem->semester}}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Amount</label>
                                    <input id="old_add_amount" type="number" class="form-control is-invalid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="old_post" type="button" class="btn btn-primary" disabled="">POST</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-lbf" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('fwdbalpdf') }}" method="GET">
                    <div class="modal-header bg-info">
                        <input type="hidden" name="hsy" id="hsy">
                        <input type="hidden" name="hsem" id="hsem">
                        <input type="hidden" name="hclassid" id="hclassid ">
                        <h4 class="modal-title">List of Balance Forwarded</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                Forwarded to: <span id="modalfwdsy" class="text-bold"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive" style="height: 350px">
                                    <table class="table table-striped p-0">
                                        <thead class="bg-warning">
                                            <th>Name</th>
                                            <th>Amount</th>
                                        </thead>
                                        <tbody id="balList">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <div class="">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                        <div>
                            <button class="btn btn-primary" id="btnprint" formtarget="_blank"><i
                                    class="fas fa-print"></i> Print</button>
                        </div>
                    </div>
                </form>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade" id="modal-overlay" data-backdrop="static" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content bg-gray-dark" style="opacity: 78%; margin-top: 15em">
                <div class="modal-body" style="height: 250px">
                    <div class="row">
                        <div class="col-md-12 text-center text-lg text-bold b-close">
                            Please Wait
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="loader"></div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: -30px">
                        <div class="col-md-12 text-center text-lg text-bold">
                            Processing...
                        </div>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal_receipts_view" aria-modal="true"
        style="padding-right: 17px; display: none;">
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
                    <button id="" type="button" class="btn btn-default"
                        data-dismiss="modal">CLOSE</button>
                </div>
            </div>
        </div> {{-- dialog --}}
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
                    <button id="" type="button" class="btn btn-default"
                        data-dismiss="modal">CLOSE</button>
                    {{-- <button id="debit_itemsave" type="button" class="btn btn-primary">ADD ITEM</button> --}}
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>
@endsection
@section('js')
    <style type="text/css">
        .pointer {
            cursor: pointer;
        }

        .loader {
            width: 100px;
            height: 100px;
            margin: 50px auto;
            position: relative;
        }

        .loader:before,
        .loader:after {
            content: "";
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: solid 8px transparent;
            position: absolute;
            -webkit-animation: loading-1 1.4s ease infinite;
            animation: loading-1 1.4s ease infinite;
        }

        .loader:before {
            border-top-color: #d72638;
            border-bottom-color: #07a7af;
        }

        .loader:after {
            border-left-color: #ffc914;
            border-right-color: #66dd71;
            -webkit-animation-delay: 0.7s;
            animation-delay: 0.7s;
        }

        @-webkit-keyframes loading-1 {
            0% {
                -webkit-transform: rotate(0deg) scale(1);
                transform: rotate(0deg) scale(1);
            }

            50% {
                -webkit-transform: rotate(180deg) scale(0.5);
                transform: rotate(180deg) scale(0.5);
            }

            100% {
                -webkit-transform: rotate(360deg) scale(1);
                transform: rotate(360deg) scale(1);
            }
        }

        @keyframes loading-1 {
            0% {
                -webkit-transform: rotate(0deg) scale(1);
                transform: rotate(0deg) scale(1);
            }

            50% {
                -webkit-transform: rotate(180deg) scale(0.5);
                transform: rotate(180deg) scale(0.5);
            }

            100% {
                -webkit-transform: rotate(360deg) scale(1);
                transform: rotate(360deg) scale(1);
            }
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {

            $('#print_old_account').click(function() {
                const syid = $('#sy').val();
                const semid = $('#sem').val();
                const url = `{{ route('print_old_account') }}?exporttype=exportOldAccounts&syid=${syid}&semid=${semid}`;
                window.open(url, '_blank');
            })

            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            $(window).resize(function() {
                screenadjust()
            })

            screenadjust()

            function screenadjust() {
                var screen_height = $(window).height();

                $('#main_table').css('height', screen_height - 315)
                // $('.screen-adj').css('height', screen_height - 223);
            }

            $('#mop').val('');
            $('#mop').trigger('change');

            // loadbalfwdsetup();
            // checksetup();
            // oa_load();

            function oa_load() {
                levelid = $('#oa_levelid').val();
                syid = $('#oa_syid').val();
                semid = $('#oa_semid').val();
                filter = $('#oa_filter').val();

                $.ajax({
                    url: '{{ route('oa_load') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        levelid: levelid,
                        syid: syid,
                        semid: semid,
                        filter: filter
                    },
                    success: function(data) {
                        $('#balancelist').html(data.list);
                    }
                });

            }

            var forwardtype = 1;

            function forwardbalance(studid, studname, syid, semid, manualamount) {
                $.ajax({
                    url: "{{ route('fwdbal') }}",
                    method: 'GET',
                    data: {
                        studid: studid,
                        syid: syid,
                        semid: semid,
                        manualamount: manualamount
                    },
                    dataType: '',
                    success: function(data) {
                        if ($('#glevel').val() == 14 || $('#glevel').val() == 15) {
                            loadstudbalance($('#glevel').val(), $('#sy').val(), $('#sem').val());
                        } else if ($('#glevel').val() >= 17 && $('#glevel').val() <= 20) {
                            loadstudbalance($('#glevel').val(), $('#sy').val(), $('#sem').val());
                        } else {
                            loadstudbalance($('#glevel').val(), $('#sy').val(), 0);
                        }

                        if (forwardtype == 1) {
                            Swal.fire(
                                'Success',
                                'Balance successfully forwarded.',
                                'success'
                            );
                        } else {
                            row_count += 1;
                            console.log(row_count);
                            if (row_count == length_count) {
                                $('#modal-overlay').modal('hide');
                            }
                        }
                    }
                });
            }

            function loadstudbalance(levelid, syid, semid) {
                $.ajax({
                    url: "{{ route('studbal') }}",
                    method: 'GET',
                    data: {
                        levelid: levelid,
                        syid: syid,
                        semid: semid,
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#balancelist').html(data.list);
                        if ($('#balancelist tr').length > 0) {
                            $('#forward_all').show();
                            $('.bal_count').text($('#balancelist tr').length)
                        } else {
                            $('#forward_all').hide();
                            $('.bal_count').text(0);
                        }
                    }
                });
            }

            function loadbalfwdsetup() {
                $.ajax({
                    url: "{{ route('loadbalfwdsetup') }}",
                    method: 'GET',
                    data: {

                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#fsy').val(data.syid);
                        $('#fsem').val(data.semid);
                        $('#fmop').val(data.mopid);
                        $('#fclass').val(data.classid);

                        $('.select2bs4').trigger('change');


                    }
                });
            }

            function checksetup() {
                var syid = $('#fsy').val();
                var semid = $('#fsem').val();
                var mopid = $('#fmop').val();
                var classid = $('#fclass').val();



                $.ajax({
                    url: "{{ route('checkbalfwdsetup') }}",
                    method: 'GET',
                    data: {
                        syid: syid,
                        semid: semid,
                        mopid: mopid,
                        classid: classid
                    },
                    dataType: '',
                    success: function(data) {
                        if (data == 0) {
                            $('#savesetup').prop('disabled', true);
                        } else {
                            $('#savesetup').prop('disabled', false);
                        }
                    }
                });
            }

            $(document).on('change', '#glevel', function() {
                if ($(this).val() == 14 || $(this).val() == 15) {
                    $('.divsem').prop('hidden', false);
                    // loadstudbalance(levelid, syid, 0);
                } else if ($(this).val() >= 17 && $(this).val() <= 20) {
                    $('.divsem').prop('hidden', false);
                } else {
                    $('.divsem').prop('hidden', true);
                    // loadstudbalance(levelid, syid, sem);
                }
            })

            $(document).on('change', '.bal-field', function() {
                var levelid = $('#glevel').val();
                var syid = $('#sy').val();
                var semid = $('#sem').val();

                // console.log(levelid);

                if (levelid == 14 || levelid == 15) {
                    loadstudbalance(levelid, syid, semid)
                } else if (levelid >= 17 && levelid <= 20) {
                    loadstudbalance(levelid, syid, semid)
                } else {
                    loadstudbalance(levelid, syid, 0)
                }
            });

            $(document).on('click', '#mbf', function() {
                $('#fwdamount').val('');
                $.ajax({
                    url: "{{ route('loadstud') }}",
                    method: 'GET',
                    data: {

                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#studlist').html(data.list);
                        $('#studlist').val('');
                        $('studlist').trigger('change');
                    }
                });
            });

            $(document).on('click', '#savesetup', function() {
                var syid = $('#fsy').val();
                var semid = $('#fsem').val();
                var mopid = $('#fmop').val();
                var classid = $('#fclass').val();
                $.ajax({
                    url: "{{ route('savefsetup') }}",
                    method: 'GET',
                    data: {
                        syid: syid,
                        semid: semid,
                        mopid: mopid,
                        classid: classid
                    },
                    dataType: '',
                    success: function(data) {
                        checksetup();
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
                            title: 'Setup saved.'
                        })
                    }
                });
            });

            $(document).on('change', '.setup', function() {
                checksetup();
            });

            $(document).on('click', '.bal-fwd', function() {
                var studname = $(this).attr('data-value');

                forwardtype = 1;

                Swal.fire({
                    title: 'Balance forwarding.',
                    text: "Forward balance of " + studname + " to  SY: " + $('#fsy option:selected')
                        .text() + ' - ' + $('#fsem option:selected').text(),
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes!'
                }).then((result) => {
                    if (result.value) {
                        forwardbalance($(this).attr('data-id'), studname, $('#sy').val(), $('#sem')
                            .val(), 0)
                    }
                })
            });

            $(document).on('click', '.v-ledger', function() {
                var studid = $(this).attr('data-id');
                var syid = $('#oa_syid').val();
                var semid = $('#oa_semid').val();

                $.ajax({
                    url: "{{ route('fwdVledger') }}",
                    method: 'GET',
                    data: {
                        studid: studid,
                        syid: syid,
                        semid: semid,
                    },
                    // dataType:'json',
                    success: function(data) {
                        console.log('studname: ' + data.studname)

                        $('#oa_studname').text(data.studname);
                        $('#ledger-list').html(data.list);
                        $('#modal-ledger').modal('show');
                    }
                });

            });

            // $(document).on('click', '#modalfwdBal', function() {
            //     $('#forward_balanceModal').modal('show');
            //     var studid = $(this).attr('data-id');
            //     var syfrom = $('#oa_syid').val();
            //     var semfrom = $('#oa_semid').val();
            //     var amount = $('#fwdamount').val();

            //     Swal.fire({
            //         title: studname,
            //         text: "Forward balance to " + sydesc,
            //         type: 'warning',
            //         showCancelButton: true,
            //         confirmButtonColor: '#3085d6',
            //         cancelButtonColor: '#d33',
            //         confirmButtonText: 'Forward Balance'
            //     }).then((result) => {
            //         if (result.value == true) {
            //             $.ajax({
            //                 url: '{{ route('oa_forward') }}',
            //                 type: 'GET',
            //                 data: {
            //                     studid: studid,
            //                     syfrom: syfrom,
            //                     semfrom: semfrom,
            //                     amount: amount
            //                 },
            //                 success: function(data) {
            //                     if (data == 'done') {
            //                         oa_load();
            //                         const Toast = Swal.mixin({
            //                             toast: true,
            //                             position: 'top',
            //                             showConfirmButton: false,
            //                             timer: 3000,
            //                             timerProgressBar: true,
            //                             didOpen: (toast) => {
            //                                 toast.addEventListener(
            //                                     'mouseenter', Swal
            //                                     .stopTimer)
            //                                 toast.addEventListener(
            //                                     'mouseleave', Swal
            //                                     .resumeTimer)
            //                             }
            //                         })

            //                         Toast.fire({
            //                             type: 'success',
            //                             title: 'Forward successfully'
            //                         })
            //                     } else if (data == 'error') {
            //                         const Toast = Swal.mixin({
            //                             toast: true,
            //                             position: 'top',
            //                             showConfirmButton: false,
            //                             timer: 4000,
            //                             timerProgressBar: true,
            //                             didOpen: (toast) => {
            //                                 toast.addEventListener(
            //                                     'mouseenter', Swal
            //                                     .stopTimer)
            //                                 toast.addEventListener(
            //                                     'mouseleave', Swal
            //                                     .resumeTimer)
            //                             }
            //                         })

            //                         Toast.fire({
            //                             type: 'error',
            //                             title: 'Something went wrong. Please Check Old Account Setup'
            //                         })
            //                     } else {
            //                         const Toast = Swal.mixin({
            //                             toast: true,
            //                             position: 'top',
            //                             showConfirmButton: false,
            //                             timer: 3000,
            //                             timerProgressBar: true,
            //                             didOpen: (toast) => {
            //                                 toast.addEventListener(
            //                                     'mouseenter', Swal
            //                                     .stopTimer)
            //                                 toast.addEventListener(
            //                                     'mouseleave', Swal
            //                                     .resumeTimer)
            //                             }
            //                         })

            //                         Toast.fire({
            //                             type: 'error',
            //                             title: 'Old accounts already forwarded'
            //                         })
            //                     }
            //                 }
            //             });
            //         }
            //     })
            // });

            // $(document).on('click', '#modalfwdBal', function() {
            //     $('#forward_balanceModal').modal('show');
            //     var studid = $(this).attr('data-id');
            //     var syfrom = $('#oa_syid').val();
            //     var semfrom = $('#oa_semid').val();
            //     var amount = $('#fwdamount').val();

            //     Swal.fire({
            //         title: studname,
            //         text: "Forward balance to " + sydesc,
            //         type: 'warning',
            //         showCancelButton: true,
            //         confirmButtonColor: '#3085d6',
            //         cancelButtonColor: '#d33',
            //         confirmButtonText: 'Forward Balance'
            //     }).then((result) => {
            //         if (result.value == true) {
            //             $.ajax({
            //                 url: '{{ route('oa_forward') }}',
            //                 type: 'GET',
            //                 data: {
            //                     studid: studid,
            //                     syfrom: syfrom,
            //                     semfrom: semfrom,
            //                     amount: amount
            //                 },
            //                 success: function(data) {
            //                     if (data == 'done') {
            //                         oa_load();
            //                         const Toast = Swal.mixin({
            //                             toast: true,
            //                             position: 'top',
            //                             showConfirmButton: false,
            //                             timer: 3000,
            //                             timerProgressBar: true,
            //                             didOpen: (toast) => {
            //                                 toast.addEventListener(
            //                                     'mouseenter', Swal
            //                                     .stopTimer)
            //                                 toast.addEventListener(
            //                                     'mouseleave', Swal
            //                                     .resumeTimer)
            //                             }
            //                         })

            //                         Toast.fire({
            //                             type: 'success',
            //                             title: 'Forward successfully'
            //                         })
            //                     } else if (data == 'error') {
            //                         const Toast = Swal.mixin({
            //                             toast: true,
            //                             position: 'top',
            //                             showConfirmButton: false,
            //                             timer: 4000,
            //                             timerProgressBar: true,
            //                             didOpen: (toast) => {
            //                                 toast.addEventListener(
            //                                     'mouseenter', Swal
            //                                     .stopTimer)
            //                                 toast.addEventListener(
            //                                     'mouseleave', Swal
            //                                     .resumeTimer)
            //                             }
            //                         })

            //                         Toast.fire({
            //                             type: 'error',
            //                             title: 'Something went wrong. Please Check Old Account Setup'
            //                         })
            //                     } else {
            //                         const Toast = Swal.mixin({
            //                             toast: true,
            //                             position: 'top',
            //                             showConfirmButton: false,
            //                             timer: 3000,
            //                             timerProgressBar: true,
            //                             didOpen: (toast) => {
            //                                 toast.addEventListener(
            //                                     'mouseenter', Swal
            //                                     .stopTimer)
            //                                 toast.addEventListener(
            //                                     'mouseleave', Swal
            //                                     .resumeTimer)
            //                             }
            //                         })

            //                         Toast.fire({
            //                             type: 'error',
            //                             title: 'Old accounts already forwarded'
            //                         })
            //                     }
            //                 }
            //             });
            //         }
            //     })
            // });

            $(document).on('click', '#listFwdBal', function() {

                $('#modalfwdsy').text('SY ' + $('#fsy option:selected').text())
                $('#hsy').val($('#fsy').val())
                $('#hsem').val($('#fsem').val())
                $('#hclassid').val($('#fclass').val())

                $.ajax({
                    url: "{{ route('listfwdbal') }}",
                    method: 'GET',
                    data: {

                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#balList').html(data.list);
                    }
                });
            });

            $(document).on('change', '.bal-field', function() {
                if ($('#sy').val() != 0) {
                    // $('#mbf').prop('disabled', false);
                    if ($('#glevel').val() == 14 || $('#glevel').val() == 15) {
                        // console.log($('#sem').val() + '!='+ $('#fsem()').val());
                        if ($('#sy').val() != 0) {
                            if ($('#sem').val() != 0) {
                                if ($('#sy').val() == $('#fsy').val()) {
                                    if ($('#sem').val() == $('#fsem').val()) {
                                        $('#mbf').prop('disabled', true);
                                    } else {
                                        $('#mbf').prop('disabled', false);
                                    }
                                } else {
                                    $('#mbf').prop('disabled', false);
                                }
                            } else {
                                $('#mbf').prop('disabled', true);
                            }
                        } else {
                            $('#mbf').prop('disabled', true);
                        }
                    } else {
                        if ($('#sy').val() == $('#fsy').val()) {
                            $('#mbf').prop('disabled', true);
                        } else {
                            $('#mbf').prop('disabled', false);
                        }
                    }
                } else {
                    $('#mbf').prop('disabled', true);
                }
            });

            var length_count = 0;
            var row_count = 0;

            $(document).on('click', '#forward_all', function() {
                forwardtype = 2;
                row_count = 0;
                length_count = $('#balancelist tr').length;

                Swal.fire({
                    title: 'Balance forwarding',
                    text: "Forward all?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes!'
                }).then((result) => {
                    if (result.value) {
                        $('#modal-overlay').modal('show');
                        $('#balancelist tr').each(function() {
                            var studid = $(this).find('.bal-fwd').attr('data-id');
                            var studname = $(this).find('.bal-fwd').attr('data-value');

                            forwardbalance(studid, studname, $('#sy').val(), $('#sem')
                                .val(), 0)
                            // return false;
                        })
                    }
                });
            });

            syactive = 0;
            semactive = 0;
            shssetup = 0;

            $(document).on('select2:close', '#oa_levelid', function() {
                var levelid = $(this).val();

                $.ajax({
                    url: '{{ route('oa_loadsy') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        levelid: levelid
                    },
                    success: function(data) {
                        syactive = data.syactive;
                        semactive = data.semactive;
                        shssetup = data.shssetup;
                        $('#oa_syid').html(data.sylist);
                        $('#oa_syid').trigger('select2:close');

                        if (levelid == 14 || levelid == 15) {
                            if ($shssetup == 0) {
                                $('#oa_txtsem').show();
                            } else {
                                $('#oa_txtsem').hide();
                            }
                        } else if (levelid >= 17 && levelid <= 21) {
                            $('#oa_txtsem').show();
                        } else {
                            $('#oa_txtsem').hide();
                        }
                    }
                });
            });

            $(document).on('select2:close', '#oa_syid', function() {
                var levelid = $('#oa_levelid').val();
                var syid = $(this).val();

                $('#oa_semid').empty();

                if (levelid == 14 || levelid == 15) {
                    if (shssetup == 0) {
                        if (syid == syactive) {
                            if (semactive == 2) {
                                $('#oa_semid').append(
                                    `
							<option value="1">1st Semester</option>
							`
                                );
                            }
                        } else {
                            $('#oa_semid').append(
                                `
							<option value="1">1st Semester</option>
							<option value="2">2nd Semester</option>
							<option value="3">Summer</option>
						`
                            );
                        }
                    } else {

                    }
                } else if (levelid >= 17 && levelid <= 20) {
                    if (syid == syactive) {
                        console.log(semactive)
                        if (semactive == 2) {
                            $('#oa_semid').append(
                                `
							<option value="1">1st Semester</option>
						`
                            );
                        }
                    } else {
                        $('#oa_semid').append(
                            `
							<option value="1">1st Semester</option>
							<option value="2">2nd Semester</option>
							<option value="3">Summer</option>
						`
                        );
                    }
                }
            });

            $(document).on('click', '#oa_search', function() {
                oa_load();
            });

            $(document).on('click', '.oa_forward', function() {
                var studid = $(this).attr('data-id');
                var syfrom = $('#oa_syid').val();
                var semfrom = $('#oa_semid').val();
                var amount = $(this).attr('data-amount');

                var studname = $(this).closest('tr').find('.fullname').text();
                var studinfo = $(this).find('.fullname').text();
                var sydesc = "{{ App\FinanceModel::getSYDesc() }}";


                // alert(studname);

                Swal.fire({
                    title: studname,
                    text: "Forward balance to " + sydesc,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Forward Balance'
                }).then((result) => {
                    if (result.value == true) {
                        $.ajax({
                            url: '{{ route('oa_forward') }}',
                            type: 'GET',
                            data: {
                                studid: studid,
                                syfrom: syfrom,
                                semfrom: semfrom,
                                amount: amount
                            },
                            success: function(data) {
                                if (data == 'done') {
                                    oa_load();
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top',
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
                                        title: 'Forward successfully'
                                    })
                                } else if (data == 'error') {
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top',
                                        showConfirmButton: false,
                                        timer: 4000,
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
                                        title: 'Something went wrong. Please Check Old Account Setup'
                                    })
                                } else {
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top',
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
                                        title: 'Old accounts already forwarded'
                                    })
                                }
                            }
                        });
                    }
                })
            });

            $(document).on('click', '#oa_setup', function() {
                $.ajax({
                    url: '{{ route('oa_setup') }}',
                    type: 'GET',
                    success: function(data) {
                        $('#oa_setupclassid').val(data.classid);
                        $('#oa_setupclassid').trigger('change');
                        $('#oa_setupmop').val(data.mopid);
                        $('#oa_setupmop').trigger('change');

                        if (data.classified == 0) {
                            $('#oa_setupclassified').prop('checked', false)
                        } else {
                            $('#oa_setupclassified').prop('checked', true)
                        }

                        $('#modal-setup').modal('show');
                    }
                });
            })

            $(document).on('click', '#oa_setupsave', function() {
                var classid = $('#oa_setupclassid').val();
                var mop = $('#oa_setupmop').val();

                if ($('#oa_setupclassified').prop('checked') == true) {
                    var classified = 1
                } else {
                    var classified = 0
                }

                $.ajax({
                    url: '{{ route('oa_setupsave') }}',
                    type: 'GET',
                    data: {
                        classid: classid,
                        mop: mop,
                        classified: classified
                    },
                    success: function(data) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal
                                    .resumeTimer)
                            }
                        })

                        Toast.fire({
                            type: 'success',
                            title: 'Successfully saved.'
                        })

                        $('#modal-setup').modal('hide');
                    }
                });
            });

            $(document).on('click', '#oa_createoldacc', function() {
                old_add_clearinputs();
                $('#modal-old_add').modal('show');
            });


            //-====---------=====-----------------==========-----------------------

            $(document).on('click', '#old_add', function() {

            });

            function old_add_clearinputs() {
                $('#old_add_studlist').val(0);
                $('#old_add_studlist').trigger('change');
                $('#old_add_sy').val(0);
                $('#old_add_sy').trigger('change');
                $('#old_add_sem').val(0);
                $('#old_add_sem').trigger('change');
                $('#old_add_amount').val('');
                $('#old_add_amount').removeClass('is-valid');
                $('#old_add_amount').addClass('is-invalid');
            }

            function old_generate() {
                var syid = $('#old_sy').val();
                var semid = $('#old_sem').val();
                var levelid = $('#old_gradelevel').val();

                $.ajax({
                    url: '{{ route('old_load') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        syid: syid,
                        semid: semid,
                        levelid: levelid
                    },
                    success: function(data) {
                        $('#old_list').html(data.list);
                    }
                });

            }

            $(document).on('click', '#old_generate', function() {
                old_generate();
            });

            $(document).on('change', '#old_add_studlist', function() {
                var studid = $(this).val();
                console.log('aaa');
                $.ajax({
                    url: '{{ route('old_add_studlist') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        studid: studid
                    },
                    success: function(data) {
                        if (studid > 0) {
                            $('#old_add_levelname').text(data.levelname);
                            $('#old_add_section').text(data.section);
                            $('#old_add_grantee').text(data.grantee);
                            $('#old_add_sy').html(data.sylist);
                            $('#old_add_sem').html(data.semlist);

                            console.log(data.levelid);

                            if (data.levelid >= 17 && data.levelid <= 21) {
                                $('.old_add_granteelabel').hide();
                            } else {
                                $('.old_add_granteelabel').show();
                            }
                        } else {
                            $('#old_add_studlist').html(data.studlist);
                        }
                    }
                });

            });

            var valcount = 0;
            $(document).on('change', '.old_req', function() {
                if ($(this).val() > 0) {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                } else {
                    $(this).addClass('is-invalid');
                    $(this).removeClass('is-valid');
                }

                checkreq();
            });

            $(document).on('keyup', '#old_add_amount', function() {
                if ($(this).val() != '') {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                } else {
                    $(this).addClass('is-invalid');
                    $(this).removeClass('is-valid');
                }

                checkreq();
            });

            $(document).on('change', '#old_add_amount', function() {
                if ($(this).val() < 0) {
                    $(this).val(0)
                }
            })

            function checkreq() {
                thiscount = 0;

                $('.old_req').each(function() {
                    if ($(this).hasClass('is-invalid')) {
                        thiscount = 0;
                    } else {
                        thiscount += 1;
                    }
                });

                if (thiscount == 3 && $('#old_add_amount').hasClass('is-valid')) {
                    $('#old_post').attr('disabled', false);
                } else {
                    $('#old_post').attr('disabled', true);
                }
            }

            $(document).on('click', '#old_post', function() {
                var studid = $('#old_add_studlist').val();
                var syfrom = $('#old_add_sy').val();
                var semfrom = $('#old_add_sem').val();
                var amount = $('#old_add_amount').val();
                var action = 'create'

                $.ajax({
                    url: '{{ route('oa_forward') }}',
                    type: 'GET',
                    dataType: '',
                    data: {
                        studid: studid,
                        syfrom: syfrom,
                        semfrom: semfrom,
                        amount: amount,
                        action: action
                    },
                    success: function(data) {
                        if (data == 'done') {
                            oa_load();
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal
                                        .stopTimer)
                                    toast.addEventListener('mouseleave', Swal
                                        .resumeTimer)
                                }
                            })

                            Toast.fire({
                                type: 'success',
                                title: 'Forward successfully'
                            })

                            $('#modal-old_add').modal('hide');
                        } else if (data == 'error') {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 4000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal
                                        .stopTimer)
                                    toast.addEventListener('mouseleave', Swal
                                        .resumeTimer)
                                }
                            })

                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong. Please Check Old Account Setup'
                            })
                        } else {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal
                                        .stopTimer)
                                    toast.addEventListener('mouseleave', Swal
                                        .resumeTimer)
                                }
                            })

                            Toast.fire({
                                type: 'error',
                                title: 'Old accounts already forwarded'
                            })
                        }
                    }
                });

            });

            function old_getsem() {
                var syid = $('#old_add_sy').val();

                $.ajax({
                    url: '{{ route('old_getsem') }}',
                    type: 'GET',
                    data: {
                        syid: syid
                    },
                    success: function(data) {
                        $('#old_add_sem').html(data);
                    }
                });

            }

            $(document).on('change', '#old_add_sy', function() {
                old_getsem();
            });
        });

        ////////////////////////////////////////////////////////////

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

        $(document).on('change', '#sy, #sem', function() {
            load_update_info_datatable()

        })
        $(document).on('change', '#filter_gradelevel', function(e) {
            console.log(123123231);
            var gradeLevel = $(this).val();
            load_update_info_datatable();
        });

        var activeRequestsTable = $('#update_info_request_table').DataTable();
        activeRequestsTable.state.clear();
        activeRequestsTable.destroy();
        load_update_info_datatable(true)

        //WORKING V4 CODE
        // function load_update_info_datatable(withpromp = false) {

        //     // var temp_data = all_students
        //     var filter_status = $('#filter_studstatus').val()
        //     // var filter_gradelevel = $('#filter_gradelevel').val()
        //     // var filter_entype = $('#filter_entype').val()
        //     // var filter_ptype = $('#filter_paymentstat').val()
        //     // var filter_process = $('#filter_process').val()






        //     // if (filter_status != "") {
        //     //     // temp_data = temp_data.filter(x=>x.studstatus == filter_status)
        //     //     if (filter_status == 0) {
        //     //         $('.prereg_head[data-id="2"]')[0].innerHTML = 'Grade Level<br>To Enroll'
        //     //         $('.prereg_head[data-id="5"]')[0].innerHTML = ''
        //     //         $('.prereg_head[data-id="3"]')[0].innerHTML = 'Admission Type'
        //     //         $('.prereg_head[data-id="4"]')[0].innerHTML = 'Approval'
        //     //         // var temp_data = temp_data.filter(x=>x.status == 'SUBMITTED')
        //     //     } else {

        //     //         $('.prereg_head[data-id="2"]')[0].innerHTML = 'Enrolled<br>Grade Level'
        //     //         $('.prereg_head[data-id="3"]')[0].innerHTML = 'Section'
        //     //         if (school_setup.withMOL == 1) {
        //     //             $('.prereg_head[data-id="4"]')[0].innerHTML = 'MOL'
        //     //         } else {
        //     //             $('.prereg_head[data-id="4"]')[0].innerHTML = ''
        //     //         }

        //     //         $('.prereg_head[data-id="3"]').removeAttr('hidden')
        //     //         // $('.prereg_head[data-id="4"]').removeAttr('hidden')
        //     //         $('.prereg_head[data-id="2"]')[0].innerHTML = 'Grade Level'
        //     //         // $('.prereg_head[data-id="5"]')[0].innerHTML = 'Enrollment Date'
        //     //     }
        //     // } else {
        //     //     // var temp_data = temp_data.filter(x=>x.status == 'SUBMITTED' || ( x.status == 'ENROLLED' && x.studstatus != 0))
        //     //     // $('.prereg_head[data-id="5"]')[0].innerHTML = 'Date'
        //     //     $('.prereg_head[data-id="3"]').text('Section')
        //     //     $('.prereg_head[data-id="3"]').removeAttr('hidden')
        //     //     $('.prereg_head[data-id="4"]').removeAttr('hidden')
        //     //     $('.prereg_head[data-id="2"]')[0].innerHTML = 'Grade Level'
        //     //     $('.prereg_head[data-id="7"]')[0].innerHTML = 'Admission Type'
        //     // }

        //     // if(filter_gradelevel != ""){
        //     //       temp_data = temp_data.filter(x=>x.levelid == filter_gradelevel)
        //     // }

        //     // if(filter_entype != ""){
        //     //       temp_data = temp_data.filter(x=>x.type == filter_entype)
        //     // }

        //     // if(filter_ptype != ""){
        //     //       if(filter_ptype == 1){
        //     //             temp_data = temp_data.filter(x=>x.withpayment == 1 || x.nodp == 1)
        //     //       }else if(filter_ptype == 2){
        //     //             temp_data = temp_data.filter(x=>x.withpayment == 0)
        //     //       }
        //     //       // else if(filter_ptype == 3){
        //     //       //       temp_data = temp_data.filter(x=>x.nodp == 1)
        //     //       // }
        //     // }

        //     // if(filter_process != ""){
        //     //       temp_data = temp_data.filter(x=>x.transtype == filter_process)
        //     // }

        //     // if(withpromp){
        //     //       if(temp_data.length == 0){
        //     //             Toast.fire({
        //     //                   type: 'error',
        //     //                   title: 'No student found'
        //     //             })
        //     //       }else{
        //     //             Toast.fire({
        //     //                   type: 'warning',
        //     //                   title: temp_data.length+' student(s) found'
        //     //             })
        //     //       }
        //     // }

        //     var temp_sy = sy.filter(x => x.id == $('#sy').val())

        //     if (temp_sy.length == 0) {
        //         var temp_sy = sy.filter(x => x.active == 1)
        //     }

        //     temp_sy = temp_sy[0]
        //     // var firstPrompt = true


        //     $("#update_info_request_table").DataTable({
        //         destroy: true,
        //         // data:temp_data,
        //         autoWidth: false,
        //         stateSave: true,
        //         serverSide: true,
        //         processing: true,
        //         // info: false,
        //         // ajax:'/student/preregistration/list',
        //         ajax: {
        //             url: '/student/preregistration/list/oldaccounts',
        //             type: 'GET',
        //             data: {
        //                 syid: $('#sy').val(),
        //                 semid: $('#sem').val(),
        //                 addtype: $('#filter_entype').val(),
        //                 paystat: $('#filter_paymentstat').val(),
        //                 procctype: $('#filter_process').val(),
        //                 studstat: $('#filter_studstatus').val(),
        //                 fillevelid: $('#filter_gradelevel').val(),
        //                 fillsectionid: $('#filter_section').val(),
        //                 activestatus: $('#filter_activestatus').val(),
        //                 transdate: $('#filter_transdate').val(),
        //                 enrollmentdate: $('#filter_enrollmentdate').val(),
        //                 processtype: $('#filter_process').val(),
        //             },
        //             dataSrc: function(json) {

        //                 all_students = json.data.filter(x => x.ledgerbalance != 0 && x.ledgerbalance >= 0)
        //                 // all_students = json.data.filter(x => x.ledgerbalance != 0)
        //                 // if (withpromp) {

        //                 // Toast.fire({
        //                 //     type: 'info',
        //                 //     title: json.recordsTotal + ' student(s) found.'
        //                 // })

        //                 //     firstPrompt = false
        //                 // }
        //                 // return json.data.filter(x => x.ledgerbalance != 0 && x.ledgerbalance >= 0);
        //                 return json.data.filter(x => x.ledgerbalance != 0 && x.ledgerbalance >= 0);

        //                 // all_students = json.data
        //                 // if (withpromp) {

        //                 //     // Toast.fire({
        //                 //     //     type: 'info',
        //                 //     //     title: json.recordsTotal + ' student(s) found.'
        //                 //     // })

        //                 //     firstPrompt = false
        //                 // }
        //                 // return json.data;

        //             }
        //         },
        //         // order: [[ 1, "asc" ]],
        //         columns: [{
        //                 "data": null,
        //                 'orderable': false,
        //                 "className": 'align-middle',
        //                 "render": function(data, type, row) {
        //                     var html = ''
        //                     if (row.studstatus == 0) {
        //                         html =
        //                             '<div class="bg-secondary" style="width:20px;height:20px;margin:0 auto;"></div>'
        //                     } else if (row.studstatus == 1) {
        //                         html =
        //                             '<div style="width:20px;height:20px;background-color:green;margin:0 auto;"></div>'
        //                     } else if (row.studstatus == 2) {
        //                         html =
        //                             '<div style="width:20px;height:20px;background-color:#58715f;margin:0 auto;"></div>'
        //                     } else if (row.studstatus == 3) {
        //                         html =
        //                             '<div style="width:20px;height:20px;background-color:red;margin:0 auto;"></div>'
        //                     } else if (row.studstatus == 4) {
        //                         html =
        //                             '<div class="bg-primary" style="width:20px;height:20px;margin:0 auto;"></div>'
        //                     } else if (row.studstatus == 5) {
        //                         html =
        //                             '<div style="width:20px;height:20px;background-color:#fd7e14;margin:0 auto;"></div>'
        //                     } else if (row.studstatus == 6) {
        //                         html =
        //                             '<div class="bg-warning" style="width:20px;height:20px;margin:0 auto;"></div>'
        //                     } else if (row.studstatus == 7) {
        //                         html =
        //                             '<div style="width:20px;height:20px;background-color:black;margin:0 auto;"></div>'
        //                     }
        //                     return '<div class="text-center">' + html + '</div>'
        //                 }

        //             },
        //             {
        //                 "data": "sid"
        //             },
        //             {
        //                 "data": "student"
        //             },

        //             {
        //                 "data": "sortid"
        //             },
        //             {
        //                 "data": "sydesc",

        //             },
        //             // {
        //             //     "data": null
        //             // },
        //             // {
        //             //     "data": null
        //             // },
        //             {
        //                 "data": null
        //             },
        //             {
        //                 "data": null
        //             },
        //             {
        //                 "data": null
        //             },
        //             // {
        //             //     "data": "enrollment"
        //             // },
        //             // {
        //             //     "data": "search"
        //             // },
        //         ],
        //         columnDefs: [{
        //                 'targets': 1,
        //                 'orderable': false,
        //                 'createdCell': function(td, cellData, rowData, row, col) {
        //                     $(td).addClass('align-middle')
        //                 }
        //             },
        //             {
        //                 'targets': 2,
        //                 'orderable': false,
        //                 'createdCell': function(td, cellData, rowData, row, col) {
        //                     var text = rowData.student

        //                     // if(rowData.nodp == 1){
        //                     // //      text += '<br>'
        //                     //      text += ' <span class="badge-primary badge">No DP Allowed</span>'
        //                     // }

        //                     // if(rowData.withpayment == 1){
        //                     //      if(rowData.nodp == 1 == 0){
        //                     //       // text += '<br>'
        //                     //      }
        //                     //      text += ' <span class="badge-success badge">Payment : &#8369;'+rowData.payment+'</span>'
        //                     // }

        //                     $(td)[0].innerHTML = text

        //                     $(td).addClass('align-middle')
        //                 }
        //             },

        //             // {
        //             //     'targets': 3,
        //             //     'orderable': false,
        //             //     'createdCell': function(td, cellData, rowData, row, col) {
        //             //         if (rowData.studstatus == 0) {
        //             //             // $(td).text(rowData.levelname)
        //             //             $(td)[0].innerHTML =
        //             //                 '<span  style="font-size:13px !important">' + rowData
        //             //                 .levelname + '</span>'
        //             //         } else {
        //             //             $(td)[0].innerHTML =
        //             //                 '<span style="font-size:13px !important">' + rowData
        //             //                 .levelname + '</span>'
        //             //         }
        //             //         $(td).addClass('align-middle')
        //             //         $(td).addClass('text-center')
        //             //     }
        //             // },
        //             // {
        //             //     'targets': 4,
        //             //     'orderable': false,
        //             //     'createdCell': function(td, cellData, rowData, row, col) {
        //             //         console.log(rowData, 'askjdhaksjdhasdakhdkajsd');

        //             //         $(td).removeAttr('hidden')
        //             //         if (filter_status == 0 && filter_status != '') {
        //             //             if (rowData.withprereg == 1) {
        //             //                 $(td)[0].innerHTML = rowData.admission_type_desc + ' : ' +
        //             //                     '<span class="text-success" style="font-size:11px !important">' +
        //             //                     rowData.submission + '</span>'
        //             //             } else {
        //             //                 $(td).text(null)
        //             //             }

        //             //         } else {
        //             //             if (rowData.enlevelid == 14 || rowData.enlevelid == 15) {
        //             //                 $(td)[0].innerHTML = rowData.sectionname +
        //             //                     ' : <span class="text-success" style="font-size:11px !important">' +
        //             //                     rowData.strandcode + '</span>'
        //             //             }

        //             //         }
        //             //         $(td).addClass('align-middle')
        //             //     }
        //             // },
        //             {
        //                 'targets': 3,
        //                 'orderable': false,
        //                 'width': '12.8%',
        //                 'createdCell': function(td, cellData, rowData, row, col) {
        //                     // $(td).text('')
        //                     var text = rowData.sydesc;
        //                     $(td)[0].innerHTML = text;
        //                     $(td).addClass('align-middle text-left');
        //                 }
        //             },
        //             {
        //                 'targets': 4,
        //                 'orderable': false,
        //                 'width': '13%',
        //                 'createdCell': function(td, cellData, rowData, row, col) {
        //                     // $(td).text('')
        //                     var text = (parseFloat(rowData.ledgeramount)).toFixed(
        //                         2).replace(
        //                         /\d(?=(\d{3})+\.)/g, '$&,');
        //                     $(td)[0].innerHTML = text;
        //                     $(td).addClass('align-middle text-left');
        //                 }
        //             },
        //             // {
        //             //     'targets': 6,
        //             //     'orderable': false,
        //             //     'createdCell': function(td, cellData, rowData, row, col) {
        //             //         if (usertype_session != 6 && usertype_session != 14 &&
        //             //             usertype_session != 16 &&
        //             //             refid != 28 && refid != 29) {
        //             //             var text = ''

        //             //             if (usertype == 6 || refid == 28) {
        //             //                 $(td)[0].innerHTML = text
        //             //                 $(td).addClass('align-middle')
        //             //                 return false;
        //             //             }

        //             //             if (rowData.nodp == 1) {
        //             //                 var text =
        //             //                     ' <span class="badge-primary badge">No DP Allowed</span>'
        //             //                 $(td).addClass('bg-primary')
        //             //                 $(td).addClass('text-center')
        //             //             }

        //             //             if (rowData.withpayment == 1) {

        //             //                 if (rowData.studstatus == 1 || rowData.studstatus == 2 ||
        //             //                     rowData
        //             //                     .studstatus == 4) {
        //             //                     var text = 'DP Paid'
        //             //                     $(td).addClass('text-center')
        //             //                 } else {
        //             //                     var text =
        //             //                         '<span style="font-size:.7rem !important"> &#8369; &nbsp;' +
        //             //                         rowData.payment.toFixed(2).replace(
        //             //                             /\d(?=(\d{3})+\.)/g, "$&,") +
        //             //                         '</span>'
        //             //                     $(td).addClass('text-right')
        //             //                 }



        //             //                 $(td).addClass('bg-success')
        //             //             }

        //             //             $(td)[0].innerHTML = text
        //             //             $(td).addClass('align-middle')
        //             //         } else {
        //             //             var text = null
        //             //             $(td)[0].innerHTML = text
        //             //             $(td).addClass('align-middle')
        //             //         }

        //             //     }
        //             // },

        //             {
        //                 'targets': 5,
        //                 'orderable': false,
        //                 'width': '13%',
        //                 'createdCell': function(td, cellData, rowData, row, col) {
        //                     var text = (parseFloat(rowData.ledgerpayment)).toFixed(
        //                         2).replace(
        //                         /\d(?=(\d{3})+\.)/g, '$&,');
        //                     $(td)[0].innerHTML = text;
        //                     $(td).addClass('align-middle');
        //                 }
        //             },

        //             {
        //                 'targets': 6,
        //                 'orderable': false,
        //                 'width': '13%',
        //                 'createdCell': function(td, cellData, rowData, row, col) {
        //                     // $(td).text('')
        //                     var text = (parseFloat(rowData.ledgerbalance)).toFixed(
        //                         2).replace(
        //                         /\d(?=(\d{3})+\.)/g, '$&,');

        //                     // var text = rowData.ledgerbalance;
        //                     $(td)[0].innerHTML = text;
        //                     $(td).addClass('align-middle');

        //                 }
        //             },
        //             {
        //                 'targets': 7,
        //                 'orderable': false,
        //                 'width': '12%',
        //                 'createdCell': function(td, cellData, rowData, row, col) {
        //                     var link =
        //                         '<a href="#" style="color: #blue; text-decoration: underline;" id="studName" class="view_account" data-id="' +
        //                         rowData.studid +
        //                         '"  data-syid="' + $('#sy').val() +
        //                         '"  data-sydesc="' + $('#sy').find('option:selected').text() +
        //                         '"  data-semid="' + $('#sem').val() +
        //                         '"> View Account</a>';
        //                     $(td)[0].innerHTML = link;
        //                     $(td).addClass('text-center align-middle');
        //                     // $(td)[0].innerHTML = '<span style="font-size:.7rem !important">' +
        //                     //     rowData
        //                     //     .description + '</span>'
        //                     // if (filter_status == 0 && filter_status != '') {

        //                     //     var text = ''
        //                     //     if (rowData.finance_status == 'APPROVED') {
        //                     //         text +=
        //                     //             '<span class="badge badge-success d-block mt-1">Finance Approved</span> '
        //                     //     }
        //                     //     if (rowData.admission_status == 'APPROVED') {
        //                     //         text +=
        //                     //             '<span class="badge badge-warning d-block mt-1">Admission Approved</span> '
        //                     //     }

        //                     //     $(td)[0].innerHTML = text
        //                     // } else {

        //                     //     if (school_setup.withMOL == 1) {
        //                     //         var temp_mol = all_mol.filter(x => x.id == rowData.mol)
        //                     //         if (temp_mol.length > 0) {
        //                     //             $(td).text(temp_mol[0].description)
        //                     //         } else {
        //                     //             $(td).text(null)
        //                     //         }
        //                     //     } else {
        //                     //         $(td).text(null)
        //                     //     }
        //                     //     // if(rowData.studstatus == 0){
        //                     //     //       $(td)[0].innerHTML = '<span style="font-size:.7rem !important">'+rowData.description+'</span>'
        //                     //     // }else{
        //                     //     //       // $(td)[0].innerHTML = '<a href="javascript:void(0)" data-preregid="'+rowData.id+'" class="view_enrollment" data-id="'+rowData.studid+'" style="font-size:.7rem !important">'+rowData.description+'</a>'
        //                     //     //       rowData.description
        //                     //     // }
        //                     //     $(td).removeAttr('hidden')

        //                     // }

        //                     // if (rowData.studstatus == 1 || rowData.studstatus == 2 || rowData
        //                     //     .studstatus ==
        //                     //     4) {
        //                     //     // $(td).addClass('bg-success')
        //                     // } else if (rowData.studstatus == 0) {

        //                     // } else {
        //                     //     // $(td).addClass('bg-secondary')
        //                     // }

        //                     // $(td).addClass('align-middle')
        //                 }
        //             }
        //             // {
        //             //     'targets': 7,
        //             //     'orderable': false,
        //             //     'createdCell': function(td, cellData, rowData, row, col) {
        //             //         if (rowData.studstatus == 0) {
        //             //             // $(td).text(rowData.submission)
        //             //         } else {
        //             //             $(td).text(rowData.enrollment)
        //             //         }
        //             //         $(td).addClass('align-middle')
        //             //     }
        //             // },
        //             // {
        //             //     'targets': 9,
        //             //     'orderable': false,
        //             //     'createdCell': function(td, cellData, rowData, row, col) {
        //             //         $(td).text('')
        //             //         // $(row).addClass('enroll')
        //             //         // $(row).addClass('enroll')
        //             //         // if(rowData.studstatus == 0 && temp_sy.ended == 0){
        //             //         //       $(td).addClass('text-center')
        //             //         //       if(usertype == 8 || usertype == 4 || usertype == 15 || usertype_session == 8 || usertype_session == 4 || usertype_session == 15){
        //             //         //           var buttons = '<button data-preregid="'+rowData.id+'" data-id="'+rowData.studid+'" class="btn btn-sm btn-primary enroll btn-block" style="font-size:.5rem !important">VIEW INFO.</button>';
        //             //         //       }else{
        //             //         //             // if(rowData.withprereg == 1){
        //             //         //                   var buttons = '<button data-preregid="'+rowData.id+'" data-id="'+rowData.studid+'" class="btn btn-sm btn-primary enroll btn-block" style="font-size:.5rem !important">ENROLL</button>';
        //             //         //             // }else{
        //             //         //             //       var buttons = '<button data-preregid="'+rowData.id+'" data-id="'+rowData.studid+'" class="btn btn-sm btn-secondary add_student_to_prereg btn-block" style="font-size:.5rem !important">ADD TO PREREG</button>';
        //             //         //             // }
        //             //         //       }
        //             //         //       $(td)[0].innerHTML =  buttons

        //             //         // }else if(rowData.studstatus == 1 || rowData.studstatus == 2 || rowData.studstatus == 4){
        //             //         //       // if(rowData.isearly == 1){
        //             //         //       //       $(td).addClass('bg-warning')
        //             //         //       //       $(td).text('EARLY')
        //             //         //       // }else{
        //             //         //       //       $(td).addClass('bg-success')
        //             //         //       //       $(td).text('REGULAR')
        //             //         //       // }
        //             //         //       $(td).text(null)
        //             //         // }
        //             //         // else if(temp_sy.ended == 1){
        //             //         //       $(td).text(null)
        //             //         // }else{
        //             //         //       $(td).text(null)
        //             //         // }

        //             //         // if (rowData.studstatus == 1 || rowData.studstatus == 2 || rowData
        //             //         //     .studstatus ==
        //             //         //     4) {
        //             //         //     var desc = all_admissiontype.filter(x => x.id == rowData.type)
        //             //         //     if (desc.length > 0) {
        //             //         //         // $(td).text(desc[0].description)
        //             //         //         $(td)[0].innerHTML =
        //             //         //             '<span style="font-size:.7rem !important">' + desc[
        //             //         //                 0].description + '</span>'
        //             //         //     } else {
        //             //         //         $(td).text(null)
        //             //         //     }

        //             //         // } else {
        //             //         //     $(td).text(null)
        //             //         // }


        //             //         // $(td).addClass('align-middle')
        //             //         // $(td).addClass('text-center')
        //             //     }
        //             // },
        //         ],
        //         // createdRow: function(row, data, dataIndex) {


        //         //     $(row).attr("data-id", data.studid);
        //         //     $(row).attr("data-preregid", data.id);

        //         //     // if(usertype == 8 || usertype == 4 || usertype == 15 || usertype_session == 8 || usertype_session == 4 || usertype_session == 15){
        //         //     //       $(row).addClass("enroll");
        //         //     // }

        //         //     if (data.studstatus != 0) {
        //         //         $(row).addClass("view_enrollment");
        //         //     } else {
        //         //         $(row).addClass("enroll");
        //         //     }
        //         // },

        //     });


        //     // var mol_options =
        //     //     '<div class="btn-group ml-2 col-sm-12 col-md-3">' +
        //     //     '<button type="button" class="btn btn-default btn-sm">Printables</button>' +
        //     //     '<button type="button" class="btn btn-default dropdown-toggle dropdown-icon btn-sm" data-toggle="dropdown">' +
        //     //     '<span class="sr-only">Toggle Dropdown</span>' +
        //     //     '</button>' +
        //     //     '<div class="dropdown-menu" role="menu">' +
        //     //     '<a class="dropdown-item print_mol" data-id="1" href="#">MOL By MOL</a>' +
        //     //     '<a class="dropdown-item print_mol" data-id="2" href="#">MOL By Grade Level</a>' +
        //     //     '<a class="dropdown-item print_mol" data-id="3" href="#">MOL By Section</a>' +
        //     //     '<a class="dropdown-item print_sf1" data-id="pdf" href="#">SF1(PDF)</a>' +
        //     //     '<a class="dropdown-item print_sf1" data-id="excel" href="#">SF1(EXCEL)</a>' +
        //     //     '<a class="dropdown-item print_enrollment"  href="#" >Enrollment</a>' +
        //     //     '</div>' +
        //     //     '</div>'
        //     // var btn_readyto_enroll =
        //     //     '<button type="button" class="btn btn-sm ml-2 btn-warning" id="ready_to_enroll"><i class="fa fa-check-circle"></i> Ready to Enroll</button>'



        //     // if (school_setup.abbreviation == 'BCT') {
        //     //     if (usertype_session == 8) {
        //     //         var label_text = $($('#update_info_request_table_wrapper')[0].children[0])[0].children[0]
        //     //         // $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm add_student_to_prereg">Add Student to Preregistration</button><button class="btn btn-primary btn-sm ml-2" id="reservation_list">Reservation List</button>'
        //     //         $(label_text)[0].innerHTML =
        //     //             ' <button class="btn btn-primary btn-sm" id="create_new_student"><i class="fa fa-plus"></i> Create New Student</button> <button class="btn btn-default btn-sm ml-2" id="vac_info"><i class="fa fa-medkit"></i> Vaccine Information</button>' +
        //     //             mol_options
        //     //     }

        //     // } else {
        //     //     // var label_text = $($('#update_info_request_table_wrapper')[0].children[0])[0].children[0]
        //     //     var label_text = $('.btn_wrap')
        //     //     if (usertype_session == 3 || usertype_session == 17 || usertype_session == 8) {
        //     //         // $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm add_student_to_prereg" >Add Student to Preregistration</button>'
        //     //         $(label_text)[0].innerHTML =
        //     //             ' <div class="col-md-3 col-sm-12">' +
        //     //             ' <button style="width:100% !important" class="btn btn-primary btn-sm d-block d-sm-inline-block" id="create_new_student"><i class="fa fa-plus"></i> Create New Student</button>' +
        //     //             ' </div>' +
        //     //             ' <div class="col-md-3 col-sm-12 mt-2 mt-md-0">' +
        //     //             ' <button style="width:100% !important" class="btn btn-default btn-sm d-block d-sm-inline-block" id="vac_info"><i class="fa fa-medkit"></i> Vaccine Information</button>' +
        //     //             ' </div>' +
        //     //             ' <div class="col-md-3 col-sm-12 mt-2 mt-md-0">' +
        //     //             ' <button style="width:100% !important" class="btn btn-warning btn-sm d-block d-sm-inline-block" id="ready_to_enroll"><i class="fa fa-check-circle"></i> Ready to Enroll</button>' +
        //     //             ' </div>' +
        //     //             ' <div class="col-md-3 col-sm-12 mt-2 mt-md-0">' +
        //     //             mol_options +
        //     //             ' </div>'
        //     //     } else if (refid == 30) {
        //     //         $(label_text)[0].innerHTML =
        //     //             ' <div class="col-md-3 col-sm-12">' +
        //     //             ' <button style="width:100% !important" class="btn btn-primary btn-sm d-block d-sm-inline-block" id="create_new_student"><i class="fa fa-plus"></i> Create New Student</button>' +
        //     //             ' </div>'
        //     //     } else {
        //     //         $(label_text)[0].innerHTML = ''
        //     //     }
        //     // }

        //     // if (temp_sy.ended == 1) {
        //     //     $('.add_student_to_prereg').remove()
        //     // }


        //     // if(usertype == 3 || usertype_session == 3 || usertype == 17 || usertype_session == 17){
        //     //       if(student_to_enroll != null && student_to_enroll != ""){
        //     //             var oTable = $('#update_info_request_table').DataTable();
        //     //             oTable.search( student_to_enroll ).draw();
        //     //       }
        //     // }

        //     // if(student_to_enroll == null || student_to_enroll == ""){
        //     //       var oTable = $('#update_info_request_table').DataTable();
        //     //       oTable.search("").draw();
        //     // }

        //     $('#gradelevel_readytoenroll').select2();
        //     $(document).on('change', '#gradelevel_readytoenroll', function() {
        //         fetchReadyToEnrollStud();
        //     });
        //     // Trigger the modal when the button is clicked
        //     $(document).on('click', '#ready_to_enroll', function() {
        //         fetchReadyToEnrollStud();
        //     });

        //     $('#notifyAll').on('click', function() {
        //         Swal.fire(
        //             'Success!',
        //             'Notified Successfully!',
        //             'success'
        //         )
        //     });

        //     // Handle applying filters (You can replace this with an AJAX request to filter data)
        //     $('#applyFilters').on('click', function() {
        //         var startDate = $('#startDate').val();
        //         var endDate = $('#endDate').val();
        //         var gradeLevel = $('#gradeLevel').val();

        //         // Example of handling the filter - in practice, you can use AJAX to load filtered data


        //         // Close the modal after applying filters
        //         $('#readyToEnrollModal').modal('hide');
        //     });

        //     $(document).on('click', '.notify_individual', function() {
        //         var phone = $(this).data('phone');
        //         var parentphone = $(this).data('parentphone');
        //         if (phone == null || phone == '') {
        //             phone = parentphone;
        //         }

        //         if (phone.length != 11) {
        //             Swal.fire(
        //                 'Error!',
        //                 'Phone number is not valid!',
        //                 'error'
        //             )
        //         } else {
        //             phone = "+63" + phone.substr(1);
        //         }

        //         $(this).html('<i class="fas fa-bell"></i>');

        //         $.ajax({
        //             url: '{{ route('notify_individual_student') }}',
        //             method: 'POST',
        //             data: {
        //                 phone: phone,
        //                 _token: '{{ csrf_token() }}'
        //             },
        //             success: function(data) {
        //                 if (data.status == 'success') {

        //                     Swal.fire(
        //                         'Success!',
        //                         'Student successfully notified!',
        //                         'success'
        //                     )
        //                 } else {
        //                     Swal.fire(
        //                         data.status,
        //                         data.message,
        //                     )
        //                 }
        //             },
        //             error: function(xhr, status, error) {
        //                 Swal.fire(
        //                     'Error!',
        //                     xhr.responseText,
        //                     'error'
        //                 )
        //             }
        //         });
        //     })
        // }


        function load_update_info_datatable(withpromp = false) {

            // var temp_data = all_students
            var filter_status = $('#filter_studstatus').val()
            var filter_gradelevel = $('#filter_gradelevel').val()
            // var filter_entype = $('#filter_entype').val()
            // var filter_ptype = $('#filter_paymentstat').val()
            // var filter_process = $('#filter_process').val()






            // if (filter_status != "") {
            //     // temp_data = temp_data.filter(x=>x.studstatus == filter_status)
            //     if (filter_status == 0) {
            //         $('.prereg_head[data-id="2"]')[0].innerHTML = 'Grade Level<br>To Enroll'
            //         $('.prereg_head[data-id="5"]')[0].innerHTML = ''
            //         $('.prereg_head[data-id="3"]')[0].innerHTML = 'Admission Type'
            //         $('.prereg_head[data-id="4"]')[0].innerHTML = 'Approval'
            //         // var temp_data = temp_data.filter(x=>x.status == 'SUBMITTED')
            //     } else {

            //         $('.prereg_head[data-id="2"]')[0].innerHTML = 'Enrolled<br>Grade Level'
            //         $('.prereg_head[data-id="3"]')[0].innerHTML = 'Section'
            //         if (school_setup.withMOL == 1) {
            //             $('.prereg_head[data-id="4"]')[0].innerHTML = 'MOL'
            //         } else {
            //             $('.prereg_head[data-id="4"]')[0].innerHTML = ''
            //         }

            //         $('.prereg_head[data-id="3"]').removeAttr('hidden')
            //         // $('.prereg_head[data-id="4"]').removeAttr('hidden')
            //         $('.prereg_head[data-id="2"]')[0].innerHTML = 'Grade Level'
            //         // $('.prereg_head[data-id="5"]')[0].innerHTML = 'Enrollment Date'
            //     }
            // } else {
            //     // var temp_data = temp_data.filter(x=>x.status == 'SUBMITTED' || ( x.status == 'ENROLLED' && x.studstatus != 0))
            //     // $('.prereg_head[data-id="5"]')[0].innerHTML = 'Date'
            //     $('.prereg_head[data-id="3"]').text('Section')
            //     $('.prereg_head[data-id="3"]').removeAttr('hidden')
            //     $('.prereg_head[data-id="4"]').removeAttr('hidden')
            //     $('.prereg_head[data-id="2"]')[0].innerHTML = 'Grade Level'
            //     $('.prereg_head[data-id="7"]')[0].innerHTML = 'Admission Type'
            // }

            // if(filter_gradelevel != ""){
            //       temp_data = temp_data.filter(x=>x.levelid == filter_gradelevel)
            // }

            // if(filter_entype != ""){
            //       temp_data = temp_data.filter(x=>x.type == filter_entype)
            // }

            // if(filter_ptype != ""){
            //       if(filter_ptype == 1){
            //             temp_data = temp_data.filter(x=>x.withpayment == 1 || x.nodp == 1)
            //       }else if(filter_ptype == 2){
            //             temp_data = temp_data.filter(x=>x.withpayment == 0)
            //       }
            //       // else if(filter_ptype == 3){
            //       //       temp_data = temp_data.filter(x=>x.nodp == 1)
            //       // }
            // }

            // if(filter_process != ""){
            //       temp_data = temp_data.filter(x=>x.transtype == filter_process)
            // }

            // if(withpromp){
            //       if(temp_data.length == 0){
            //             Toast.fire({
            //                   type: 'error',
            //                   title: 'No student found'
            //             })
            //       }else{
            //             Toast.fire({
            //                   type: 'warning',
            //                   title: temp_data.length+' student(s) found'
            //             })
            //       }
            // }

            var temp_sy = sy.filter(x => x.id == $('#sy').val())

            if (temp_sy.length == 0) {
                var temp_sy = sy.filter(x => x.active == 1)
            }

            temp_sy = temp_sy[0]
            // var firstPrompt = true


            $("#update_info_request_table").DataTable({
                destroy: true,
                // data:temp_data,
                autoWidth: false,
                stateSave: true,
                serverSide: true,
                processing: true,
                // info: false,
                // ajax:'/student/preregistration/list',
                ajax: {
                    url: '/student/preregistration/list/oldaccounts',
                    type: 'GET',
                    data: {
                        syid: $('#sy').val(),
                        semid: $('#sem').val(),
                        addtype: $('#filter_entype').val(),
                        paystat: $('#filter_paymentstat').val(),
                        procctype: $('#filter_process').val(),
                        studstat: $('#filter_studstatus').val(),
                        fillevelid: $('#filter_gradelevel').val(),
                        fillsectionid: $('#filter_section').val(),
                        activestatus: $('#filter_activestatus').val(),
                        transdate: $('#filter_transdate').val(),
                        enrollmentdate: $('#filter_enrollmentdate').val(),
                        processtype: $('#filter_process').val(),
                    },
                    dataSrc: function(json) {

                        // all_students = json.data.filter(x => x.ledgerbalance != 0 && x.ledgerbalance >= 0)
                        // // all_students = json.data.filter(x => x.ledgerbalance != 0)
                        // // if (withpromp) {

                        // // Toast.fire({
                        // //     type: 'info',
                        // //     title: json.recordsTotal + ' student(s) found.'
                        // // })

                        // //     firstPrompt = false
                        // // }
                        // // return json.data.filter(x => x.ledgerbalance != 0 && x.ledgerbalance >= 0);
                        // return json.data.filter(x => x.ledgerbalance != 0 && x.ledgerbalance >= 0);

                        all_students = json.data
                        // if (withpromp) {

                        //     // Toast.fire({
                        //     //     type: 'info',
                        //     //     title: json.recordsTotal + ' student(s) found.'
                        //     // })

                        //     firstPrompt = false
                        // }
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
                        "data": "sydesc",

                    },
                    // {
                    //     "data": null
                    // },
                    // {
                    //     "data": null
                    // },
                    {
                        "data": null
                    },
                    {
                        "data": null
                    },
                    {
                        "data": null
                    },
                    // {
                    //     "data": "enrollment"
                    // },
                    // {
                    //     "data": "search"
                    // },
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

                            // if(rowData.nodp == 1){
                            // //      text += '<br>'
                            //      text += ' <span class="badge-primary badge">No DP Allowed</span>'
                            // }

                            // if(rowData.withpayment == 1){
                            //      if(rowData.nodp == 1 == 0){
                            //       // text += '<br>'
                            //      }
                            //      text += ' <span class="badge-success badge">Payment : &#8369;'+rowData.payment+'</span>'
                            // }

                            $(td)[0].innerHTML = text

                            $(td).addClass('align-middle')
                        }
                    },

                    // {
                    //     'targets': 3,
                    //     'orderable': false,
                    //     'createdCell': function(td, cellData, rowData, row, col) {
                    //         if (rowData.studstatus == 0) {
                    //             // $(td).text(rowData.levelname)
                    //             $(td)[0].innerHTML =
                    //                 '<span  style="font-size:13px !important">' + rowData
                    //                 .levelname + '</span>'
                    //         } else {
                    //             $(td)[0].innerHTML =
                    //                 '<span style="font-size:13px !important">' + rowData
                    //                 .levelname + '</span>'
                    //         }
                    //         $(td).addClass('align-middle')
                    //         $(td).addClass('text-center')
                    //     }
                    // },
                    // {
                    //     'targets': 4,
                    //     'orderable': false,
                    //     'createdCell': function(td, cellData, rowData, row, col) {
                    //         console.log(rowData, 'askjdhaksjdhasdakhdkajsd');

                    //         $(td).removeAttr('hidden')
                    //         if (filter_status == 0 && filter_status != '') {
                    //             if (rowData.withprereg == 1) {
                    //                 $(td)[0].innerHTML = rowData.admission_type_desc + ' : ' +
                    //                     '<span class="text-success" style="font-size:11px !important">' +
                    //                     rowData.submission + '</span>'
                    //             } else {
                    //                 $(td).text(null)
                    //             }

                    //         } else {
                    //             if (rowData.enlevelid == 14 || rowData.enlevelid == 15) {
                    //                 $(td)[0].innerHTML = rowData.sectionname +
                    //                     ' : <span class="text-success" style="font-size:11px !important">' +
                    //                     rowData.strandcode + '</span>'
                    //             }

                    //         }
                    //         $(td).addClass('align-middle')
                    //     }
                    // },
                    // {
                    //     'targets': 3,
                    //     'orderable': false,
                    //     'width': '12.8%',
                    //     'createdCell': function(td, cellData, rowData, row, col) {
                    //         // $(td).text('')
                    //         var text = rowData.sydesc;
                    //         $(td)[0].innerHTML = text;
                    //         $(td).addClass('align-middle text-left');
                    //     }
                    // },
                    {
                        'targets': 3,
                        'orderable': false,
                        'width': '12.8%',
                        'createdCell': function(td, cellData, rowData, row, col) {
                            var text = rowData.sydesc;
                            if (rowData.studstatus == 0) {
                                text += ' <span style="font-size:13px !important;display:none;">' + rowData
                                    .levelname +
                                    '</span>';
                            } else {
                                text += ' <span style="font-size:13px !important;display:none;">' + rowData
                                    .levelname +
                                    '</span>';
                            }
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-left');
                        }
                    },
                    {
                        'targets': 4,
                        'orderable': false,
                        'width': '13%',
                        'createdCell': function(td, cellData, rowData, row, col) {
                            // $(td).text('')
                            var text = (parseFloat(rowData.ledgeramount)).toFixed(
                                2).replace(
                                /\d(?=(\d{3})+\.)/g, '$&,');
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-left');
                        }
                    },
                    // {
                    //     'targets': 6,
                    //     'orderable': false,
                    //     'createdCell': function(td, cellData, rowData, row, col) {
                    //         if (usertype_session != 6 && usertype_session != 14 &&
                    //             usertype_session != 16 &&
                    //             refid != 28 && refid != 29) {
                    //             var text = ''

                    //             if (usertype == 6 || refid == 28) {
                    //                 $(td)[0].innerHTML = text
                    //                 $(td).addClass('align-middle')
                    //                 return false;
                    //             }

                    //             if (rowData.nodp == 1) {
                    //                 var text =
                    //                     ' <span class="badge-primary badge">No DP Allowed</span>'
                    //                 $(td).addClass('bg-primary')
                    //                 $(td).addClass('text-center')
                    //             }

                    //             if (rowData.withpayment == 1) {

                    //                 if (rowData.studstatus == 1 || rowData.studstatus == 2 ||
                    //                     rowData
                    //                     .studstatus == 4) {
                    //                     var text = 'DP Paid'
                    //                     $(td).addClass('text-center')
                    //                 } else {
                    //                     var text =
                    //                         '<span style="font-size:.7rem !important"> &#8369; &nbsp;' +
                    //                         rowData.payment.toFixed(2).replace(
                    //                             /\d(?=(\d{3})+\.)/g, "$&,") +
                    //                         '</span>'
                    //                     $(td).addClass('text-right')
                    //                 }



                    //                 $(td).addClass('bg-success')
                    //             }

                    //             $(td)[0].innerHTML = text
                    //             $(td).addClass('align-middle')
                    //         } else {
                    //             var text = null
                    //             $(td)[0].innerHTML = text
                    //             $(td).addClass('align-middle')
                    //         }

                    //     }
                    // },

                    {
                        'targets': 5,
                        'orderable': false,
                        'width': '13%',
                        'createdCell': function(td, cellData, rowData, row, col) {
                            var text = (parseFloat(rowData.ledgerpayment)).toFixed(
                                2).replace(
                                /\d(?=(\d{3})+\.)/g, '$&,');
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle');
                        }
                    },

                    {
                        'targets': 6,
                        'orderable': false,
                        'width': '13%',
                        'createdCell': function(td, cellData, rowData, row, col) {
                            // $(td).text('')
                            var text = (parseFloat(rowData.ledgerbalance)).toFixed(
                                2).replace(
                                /\d(?=(\d{3})+\.)/g, '$&,');

                            // var text = rowData.ledgerbalance;
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle');

                        }
                    },
                    {
                        'targets': 7,
                        'orderable': false,
                        'width': '12%',
                        'createdCell': function(td, cellData, rowData, row, col) {
                            var link =
                                '<a href="#" style="color: #blue; text-decoration: underline;" id="studName" class="view_account" data-id="' +
                                rowData.studid +
                                '"  data-syid="' + $('#sy').val() +
                                '"  data-sydesc="' + $('#sy').find('option:selected').text() +
                                '"  data-semid="' + $('#sem').val() +
                                '"> View Account</a>';
                            $(td)[0].innerHTML = link;
                            $(td).addClass('text-center align-middle');
                            // $(td)[0].innerHTML = '<span style="font-size:.7rem !important">' +
                            //     rowData
                            //     .description + '</span>'
                            // if (filter_status == 0 && filter_status != '') {

                            //     var text = ''
                            //     if (rowData.finance_status == 'APPROVED') {
                            //         text +=
                            //             '<span class="badge badge-success d-block mt-1">Finance Approved</span> '
                            //     }
                            //     if (rowData.admission_status == 'APPROVED') {
                            //         text +=
                            //             '<span class="badge badge-warning d-block mt-1">Admission Approved</span> '
                            //     }

                            //     $(td)[0].innerHTML = text
                            // } else {

                            //     if (school_setup.withMOL == 1) {
                            //         var temp_mol = all_mol.filter(x => x.id == rowData.mol)
                            //         if (temp_mol.length > 0) {
                            //             $(td).text(temp_mol[0].description)
                            //         } else {
                            //             $(td).text(null)
                            //         }
                            //     } else {
                            //         $(td).text(null)
                            //     }
                            //     // if(rowData.studstatus == 0){
                            //     //       $(td)[0].innerHTML = '<span style="font-size:.7rem !important">'+rowData.description+'</span>'
                            //     // }else{
                            //     //       // $(td)[0].innerHTML = '<a href="javascript:void(0)" data-preregid="'+rowData.id+'" class="view_enrollment" data-id="'+rowData.studid+'" style="font-size:.7rem !important">'+rowData.description+'</a>'
                            //     //       rowData.description
                            //     // }
                            //     $(td).removeAttr('hidden')

                            // }

                            // if (rowData.studstatus == 1 || rowData.studstatus == 2 || rowData
                            //     .studstatus ==
                            //     4) {
                            //     // $(td).addClass('bg-success')
                            // } else if (rowData.studstatus == 0) {

                            // } else {
                            //     // $(td).addClass('bg-secondary')
                            // }

                            // $(td).addClass('align-middle')
                        }
                    }
                    // {
                    //     'targets': 7,
                    //     'orderable': false,
                    //     'createdCell': function(td, cellData, rowData, row, col) {
                    //         if (rowData.studstatus == 0) {
                    //             // $(td).text(rowData.submission)
                    //         } else {
                    //             $(td).text(rowData.enrollment)
                    //         }
                    //         $(td).addClass('align-middle')
                    //     }
                    // },
                    // {
                    //     'targets': 9,
                    //     'orderable': false,
                    //     'createdCell': function(td, cellData, rowData, row, col) {
                    //         $(td).text('')
                    //         // $(row).addClass('enroll')
                    //         // $(row).addClass('enroll')
                    //         // if(rowData.studstatus == 0 && temp_sy.ended == 0){
                    //         //       $(td).addClass('text-center')
                    //         //       if(usertype == 8 || usertype == 4 || usertype == 15 || usertype_session == 8 || usertype_session == 4 || usertype_session == 15){
                    //         //           var buttons = '<button data-preregid="'+rowData.id+'" data-id="'+rowData.studid+'" class="btn btn-sm btn-primary enroll btn-block" style="font-size:.5rem !important">VIEW INFO.</button>';
                    //         //       }else{
                    //         //             // if(rowData.withprereg == 1){
                    //         //                   var buttons = '<button data-preregid="'+rowData.id+'" data-id="'+rowData.studid+'" class="btn btn-sm btn-primary enroll btn-block" style="font-size:.5rem !important">ENROLL</button>';
                    //         //             // }else{
                    //         //             //       var buttons = '<button data-preregid="'+rowData.id+'" data-id="'+rowData.studid+'" class="btn btn-sm btn-secondary add_student_to_prereg btn-block" style="font-size:.5rem !important">ADD TO PREREG</button>';
                    //         //             // }
                    //         //       }
                    //         //       $(td)[0].innerHTML =  buttons

                    //         // }else if(rowData.studstatus == 1 || rowData.studstatus == 2 || rowData.studstatus == 4){
                    //         //       // if(rowData.isearly == 1){
                    //         //       //       $(td).addClass('bg-warning')
                    //         //       //       $(td).text('EARLY')
                    //         //       // }else{
                    //         //       //       $(td).addClass('bg-success')
                    //         //       //       $(td).text('REGULAR')
                    //         //       // }
                    //         //       $(td).text(null)
                    //         // }
                    //         // else if(temp_sy.ended == 1){
                    //         //       $(td).text(null)
                    //         // }else{
                    //         //       $(td).text(null)
                    //         // }

                    //         // if (rowData.studstatus == 1 || rowData.studstatus == 2 || rowData
                    //         //     .studstatus ==
                    //         //     4) {
                    //         //     var desc = all_admissiontype.filter(x => x.id == rowData.type)
                    //         //     if (desc.length > 0) {
                    //         //         // $(td).text(desc[0].description)
                    //         //         $(td)[0].innerHTML =
                    //         //             '<span style="font-size:.7rem !important">' + desc[
                    //         //                 0].description + '</span>'
                    //         //     } else {
                    //         //         $(td).text(null)
                    //         //     }

                    //         // } else {
                    //         //     $(td).text(null)
                    //         // }


                    //         // $(td).addClass('align-middle')
                    //         // $(td).addClass('text-center')
                    //     }
                    // },
                ],
                // createdRow: function(row, data, dataIndex) {


                //     $(row).attr("data-id", data.studid);
                //     $(row).attr("data-preregid", data.id);

                //     // if(usertype == 8 || usertype == 4 || usertype == 15 || usertype_session == 8 || usertype_session == 4 || usertype_session == 15){
                //     //       $(row).addClass("enroll");
                //     // }

                //     if (data.studstatus != 0) {
                //         $(row).addClass("view_enrollment");
                //     } else {
                //         $(row).addClass("enroll");
                //     }
                // },

            });


            // var mol_options =
            //     '<div class="btn-group ml-2 col-sm-12 col-md-3">' +
            //     '<button type="button" class="btn btn-default btn-sm">Printables</button>' +
            //     '<button type="button" class="btn btn-default dropdown-toggle dropdown-icon btn-sm" data-toggle="dropdown">' +
            //     '<span class="sr-only">Toggle Dropdown</span>' +
            //     '</button>' +
            //     '<div class="dropdown-menu" role="menu">' +
            //     '<a class="dropdown-item print_mol" data-id="1" href="#">MOL By MOL</a>' +
            //     '<a class="dropdown-item print_mol" data-id="2" href="#">MOL By Grade Level</a>' +
            //     '<a class="dropdown-item print_mol" data-id="3" href="#">MOL By Section</a>' +
            //     '<a class="dropdown-item print_sf1" data-id="pdf" href="#">SF1(PDF)</a>' +
            //     '<a class="dropdown-item print_sf1" data-id="excel" href="#">SF1(EXCEL)</a>' +
            //     '<a class="dropdown-item print_enrollment"  href="#" >Enrollment</a>' +
            //     '</div>' +
            //     '</div>'
            // var btn_readyto_enroll =
            //     '<button type="button" class="btn btn-sm ml-2 btn-warning" id="ready_to_enroll"><i class="fa fa-check-circle"></i> Ready to Enroll</button>'



            // if (school_setup.abbreviation == 'BCT') {
            //     if (usertype_session == 8) {
            //         var label_text = $($('#update_info_request_table_wrapper')[0].children[0])[0].children[0]
            //         // $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm add_student_to_prereg">Add Student to Preregistration</button><button class="btn btn-primary btn-sm ml-2" id="reservation_list">Reservation List</button>'
            //         $(label_text)[0].innerHTML =
            //             ' <button class="btn btn-primary btn-sm" id="create_new_student"><i class="fa fa-plus"></i> Create New Student</button> <button class="btn btn-default btn-sm ml-2" id="vac_info"><i class="fa fa-medkit"></i> Vaccine Information</button>' +
            //             mol_options
            //     }

            // } else {
            //     // var label_text = $($('#update_info_request_table_wrapper')[0].children[0])[0].children[0]
            //     var label_text = $('.btn_wrap')
            //     if (usertype_session == 3 || usertype_session == 17 || usertype_session == 8) {
            //         // $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm add_student_to_prereg" >Add Student to Preregistration</button>'
            //         $(label_text)[0].innerHTML =
            //             ' <div class="col-md-3 col-sm-12">' +
            //             ' <button style="width:100% !important" class="btn btn-primary btn-sm d-block d-sm-inline-block" id="create_new_student"><i class="fa fa-plus"></i> Create New Student</button>' +
            //             ' </div>' +
            //             ' <div class="col-md-3 col-sm-12 mt-2 mt-md-0">' +
            //             ' <button style="width:100% !important" class="btn btn-default btn-sm d-block d-sm-inline-block" id="vac_info"><i class="fa fa-medkit"></i> Vaccine Information</button>' +
            //             ' </div>' +
            //             ' <div class="col-md-3 col-sm-12 mt-2 mt-md-0">' +
            //             ' <button style="width:100% !important" class="btn btn-warning btn-sm d-block d-sm-inline-block" id="ready_to_enroll"><i class="fa fa-check-circle"></i> Ready to Enroll</button>' +
            //             ' </div>' +
            //             ' <div class="col-md-3 col-sm-12 mt-2 mt-md-0">' +
            //             mol_options +
            //             ' </div>'
            //     } else if (refid == 30) {
            //         $(label_text)[0].innerHTML =
            //             ' <div class="col-md-3 col-sm-12">' +
            //             ' <button style="width:100% !important" class="btn btn-primary btn-sm d-block d-sm-inline-block" id="create_new_student"><i class="fa fa-plus"></i> Create New Student</button>' +
            //             ' </div>'
            //     } else {
            //         $(label_text)[0].innerHTML = ''
            //     }
            // }

            // if (temp_sy.ended == 1) {
            //     $('.add_student_to_prereg').remove()
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



        //working v4 code
        // $(document).on('click', '.view_account', function() {
        //     $('#view_acountModal').modal('show');
        //     // var syid = $('#sy').val();
        //     // var semid = $('#sem').val();
        //     // var studid = $('#studName').val();

        //     var syid = $(this).attr('data-syid');
        //     var sydesc = $(this).attr('data-sydesc');
        //     var semid = $(this).attr('data-semid');
        //     var studids = $(this).attr('data-id');
        //     var batchid = $('#tvlbatch').val();

        //     console.log(syid);
        //     console.log(semid);
        //     console.log(studid);
        //     console.log(batchid);

        //     $('#studid').val(studid);
        //     $('#editsy').val(syid);
        //     $('#editsem').val(semid);

        //     $('#studNamev4').val(studids);
        //     $('#modalfwdBal').attr('data-sycurrent', sydesc);

        //     // console.log(studid);
        //     $.ajax({
        //         url: "{{ route('oldAccountsgetStudLedger') }}",
        //         method: 'GET',
        //         data: {
        //             syid: syid,
        //             studid: studids,
        //             semid: semid,
        //             batchid: batchid
        //         },
        //         dataType: 'json',
        //         success: function(data) {
        //             console.log(data.list);
        //             $('#ledger-list').html(data.list);

        //             $('#btnstudyload').attr('data-level', data.levelid);
        //             $('#btnstudyload').attr('data-status', data.studstatus);

        //             // if (data.levelid >= 17 && data.levelid <= 21) {
        //             if (data.levelid >= 15 && data.levelid <= 21) {
        //                 $('.div_studyload').show();
        //                 $('.oa_sem_label').show();
        //                 $('.oa_sem').show();
        //             } else {
        //                 $('.div_studyload').hide();
        //                 $('.oa_sem_label').hide();
        //                 $('.oa_sem').hide();
        //             }

        //             if (data.istvl == 1) {
        //                 $('.filters').hide();
        //                 $('.tv_filters').show();
        //             } else {
        //                 $('.filters').show();
        //                 $('.tv_filters').hide();
        //             }

        //             // if (data.levelid == 14 || data.levelid == 15) {
        //             //     $('#ledger_info').text('Student Name:' + data.studname + ' - ' +
        //             //         data.levelname + ' - ' +
        //             //         data.section + ' | ' + data.strand + ' | ' + data.grantee);
        //             // }
        //             if (data.levelid == 14 || data.levelid == 15) {
        //                 $('#ledger_info').html(
        //                     '<span style="font-weight:bold;margin-right:27px;">Student Name: </span>' +
        //                     data
        //                     .studname + ' - ' +
        //                     '<span style="margin-right:10px;">' + data.levelname + '</span> - ' +
        //                     '<span style="margin-right:10px;">' + data.section +
        //                     '</span> , <span>' + data.strand + '</span>');
        //             } else if (data.levelid >= 17 && data.levelid <= 21) {
        //                 $('#ledger_info').html(
        //                     '<span style="font-weight:bold;margin-right:27px;">Student Name: </span>' +
        //                     data
        //                     .studname + ' - ' +
        //                     '<span style="margin-right:10px;">' + data.levelname + '</span> - ' +
        //                     '<span style="margin-right:10px;">' + data.section +
        //                     '</span> , <span>' + data.strand + '</span>');
        //             } else {
        //                 $('#ledger_info').html(
        //                     '<span style="font-weight:bold;margin-right:27px;">Student Name: </span>' +
        //                     data
        //                     .studname + ' - ' +
        //                     '<span style="margin-right:10px;">' + data.levelname + '</span> - ' +
        //                     '<span style="margin-right:10px;">' + data.section +
        //                     '</span> , <span>' + data.strand + '</span>');
        //             }

        //             $('#ledger_info_status').text('Status: ' + data.studstatus);
        //             $('#feesname').text(data.feesname);
        //             $('#feesname').attr('data-id', data.feesid);

        //         }
        //     });
        // });





        $(document).on('click', '.view_account', function() {
            $('#view_acountModal').modal('show');
            // var syid = $('#sy').val();
            // var semid = $('#sem').val();
            // var studid = $('#studName').val();

            var syid = $(this).attr('data-syid');
            var sydesc = $(this).attr('data-sydesc');
            var semid = $(this).attr('data-semid');
            var studids = $(this).attr('data-id');
            var batchid = $('#tvlbatch').val();

            console.log(syid);
            console.log(semid);
            console.log(studid);
            console.log(batchid);

            $('#studid').val(studid);
            $('#editsy').val(syid);
            $('#editsem').val(semid);

            $('#studNamev4').val(studids);
            $('#modalfwdBal_higher_school').attr('data-sycurrent', sydesc);
            $('#modalfwdBal_higher_school').attr('data-sycurrentid', syid);

            $('#modalfwdBal_lower_school').attr('data-sycurrent', sydesc);
            $('#modalfwdBal_lower_school').attr('data-sycurrentid', syid);


            get_student_ledger()

            function get_student_ledger() {
                var syid = $('#sy').val();
                var semid = $('#sem').val();
                // var studid = $('#studNamev4').val();
                var studid = studids;
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
                            console.log(totalCharges, 'totalCharges');

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
                                 ${item.history && item.history.length > 0 ? `
                                                                                                                        <a href="javascript:void(0)" class="view-items2" data-items2='${JSON.stringify(item.history)}' data-bs-toggle="tooltip" data-bs-html="true" title="Breakdown">
                                                                                                                            <i class="fas fa-caret-down"></i>
                                                                                                                        </a>
                                                                                                                    ` : ''}
                            </td>
                            <td class="text-center">${charge.toFixed(2)}</td>
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
                        <td class="text-center" id="TOTAL_BALANCE">${totalBalance.toFixed(2)}</td>
                    </tr>
                `);
                    }
                });
            }

            // console.log(studid);
            $.ajax({
                url: "{{ route('oldAccountsgetStudLedger') }}",
                method: 'GET',
                data: {
                    syid: syid,
                    studid: studids,
                    semid: semid,
                    batchid: batchid
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data.list);
                    $('#ledger-list').html(data.list);

                    $('#btnstudyload').attr('data-level', data.levelid);
                    $('#btnstudyload').attr('data-status', data.studstatus);

                    // if (data.levelid >= 17 && data.levelid <= 21) {
                    if (data.levelid >= 14 && data.levelid <= 21) {

                        $('.div_studyload').show();
                        $('.oa_sem_label').show();
                        $('.oa_sem_higher').show();

                        // var current_sydesc = $('#sy').find('option:selected').text();
                        // console.log("Current School Year Description:", current_sydesc);

                        // $.ajax({
                        //     url: "{{ route('OldAccount_SchoolYear') }}",
                        //     method: 'GET',
                        //     data: {
                        //         current_sydesc: current_sydesc
                        //     },
                        //     success: function(response) {
                        //         var selected_sy = response.Sy;
                        //         var all_sy = response.AllSy;
                        //         var isCurrentActive = selected_sy && selected_sy.isactive;

                        //         if (isCurrentActive) {
                        //             $('#modalfwdBal_higher_school').prop('disabled', true);
                        //         } else {
                        //             $('#modalfwdBal_higher_school').prop('disabled', false);
                        //         }

                        //         console.log("Selected SY:", selected_sy);
                        //         console.log("All SY:", all_sy);
                        //     },
                        // });

                        var current_sydesc = $('#sy').find('option:selected').text();

                        $.ajax({
                            url: "{{ route('OldAccount_SchoolYear') }}",
                            method: 'GET',
                            data: {
                                current_sydesc: current_sydesc
                            },
                            success: function(response) {
                                var selected_sy = response.Sy;
                                var active_sy = response.ActiveSy;

                                if (!selected_sy || !active_sy) {
                                    console.error("School year data not found!");
                                    $('#modalfwdBal_lower_school').prop('disabled', true);
                                    return;
                                }

                                // Enable button for ALL previous SY (where id < active_sy.id)
                                // Disable only for active SY
                                var shouldEnable = selected_sy.id < active_sy.id;

                                $('#modalfwdBal_lower_school').prop('disabled', !
                                    shouldEnable);

                                // For debugging:
                                console.log('Selected SY:', selected_sy.sydesc, 'ID:',
                                    selected_sy.id);
                                console.log('Active SY:', active_sy.sydesc, 'ID:', active_sy
                                    .id);
                                console.log('Button enabled:', shouldEnable);
                            },
                            error: function(xhr) {
                                console.error("Error fetching school year data:", xhr
                                    .responseText);
                                $('#modalfwdBal_lower_school').prop('disabled', true);
                            }
                        });




                        $('#modalfwdBal_higher_school').show();


                        $('#modalfwdBal_lower_school').hide();
                        ////////////////////////////////////////

                        $('#modalfwdBal_higher_school').on('click', function() {
                            $('#forward_balanceModal_higher_school').modal('show');

                            var selectedSyDescid = $(this).attr(
                                'data-sycurrentid'); // Get the selected school year description

                            var selectedSyDesc = $(this).attr(
                                'data-sycurrent'); // Get the selected school year description
                            // var selectedSyDesc = $(this).find('option:selected').text(); // Get the selected school year description
                            var selectedYear = parseInt(selectedSyDesc.split('-')[
                                0]); // Extract the starting year


                            $('#current_school_year_higher').val(selectedSyDesc);
                            // Calculate the next school year
                            var nextYear = selectedYear + 1;
                            var nextSyDesc = nextYear + '-' + (nextYear + 1);

                            // Remove previous school years
                            $('#oa_sy_higher option').each(function() {
                                var optionYear = parseInt($(this).text().split('-')[0]);
                                if (optionYear < selectedYear) {
                                    $(this).remove();
                                }
                            });

                            // if (!$('#oa_sy option[value="' + selectedSyDescid + '"]').length) {
                            //     var currentOption = new Option(selectedSyDesc,
                            //         selectedSyDescid);
                            //     $('#oa_sy').append(currentOption); // Add the current option
                            //     $('#oa_sy').append(currentOption).val(selectedSyDescid).trigger(
                            //         'change'); // Add and select the current option
                            // } else {
                            //     $('#oa_sy').val(selectedSyDescid).trigger(
                            //         'change'); // Select the existing option
                            // }

                            // // Add the current school year
                            // if (!$('#oa_sy option[value="' + selectedSyDescid + '"]').length) {
                            //     var currentOption = new Option(selectedSyDesc,
                            //         selectedSyDescid);
                            //     $('#oa_sy').append(currentOption); // Add the current option
                            // }



                            // Set the current school year as selected
                            $('#oa_sy_higher').val(selectedSyDescid).prop('selected', true);

                            // Send an AJAX request to fetch the next school year
                            $.ajax({
                                url: "{{ route('getNextSchoolYear') }}",
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}', // CSRF token for security
                                    nextSyDesc: nextSyDesc // Send the next school year description
                                },
                                success: function(response) {
                                    if (response.nextSy) {
                                        console.log(response.nextSy);
                                        // If the next school year exists, check for duplicates before adding
                                        if (!$('#oa_sy_higher option[value="' +
                                                response
                                                .nextSy.id + '"]').length) {
                                            var newOption = new Option(response
                                                .nextSy.sydesc, response.nextSy
                                                .id);
                                            $('#oa_sy_higher').append(newOption)
                                                .val(
                                                    response.nextSy
                                                    .id).trigger(
                                                    'change'
                                                ); // Add and select the new option
                                        }

                                    } else {
                                        Toast.fire({
                                            type: 'info',
                                            title: 'Next school year not found.',
                                            timer: 900 // Set the desired timeout duration in milliseconds
                                        }).then(() => {
                                            // $('#forward_balanceModal').modal('hide');

                                        });
                                    }

                                    // Add the current school year
                                    if (!$('#oa_sy_higher option[value="' +
                                            selectedSyDescid + '"]').length) {
                                        var currentOption = new Option(
                                            selectedSyDesc,
                                            selectedSyDescid);
                                        $('#oa_sy_higher').append(
                                            currentOption
                                        ); // Add the current option
                                        $('#oa_sy_higher').val(selectedSyDescid)
                                            .prop(
                                                'selected', true
                                            ); // Make it selected
                                    }
                                },
                            });
                        });

                        //////////////////////////////
                    } else {
                        $('.div_studyload').hide();
                        $('.oa_sem_label').hide();
                        $('.oa_sem').hide();

                        // var current_sydesc = $('#sy').find('option:selected').text();
                        // console.log("Current School Year Description:", current_sydesc);

                        // $.ajax({
                        //     url: "{{ route('OldAccount_SchoolYear') }}",
                        //     method: 'GET',
                        //     data: {
                        //         current_sydesc: current_sydesc
                        //     },
                        //     success: function(response) {
                        //         var selected_sy = response.Sy;
                        //         var all_sy = response.AllSy;
                        //         var isCurrentActive = selected_sy && selected_sy.isactive;

                        //         if (isCurrentActive) {
                        //             $('#modalfwdBal_lower_school').prop('disabled', true);
                        //         } else {
                        //             $('#modalfwdBal_lower_school').prop('disabled', false);
                        //         }

                        //         console.log("Selected SY:", selected_sy);
                        //         console.log("All SY:", all_sy);
                        //     },
                        // });


                        // var current_sydesc = $('#sy').find('option:selected').text();

                        // $.ajax({
                        //     url: "{{ route('OldAccount_SchoolYear') }}",
                        //     method: 'GET',
                        //     data: {
                        //         current_sydesc: current_sydesc
                        //     },
                        //     success: function(response) {
                        //         var selected_sy = response.Sy;
                        //         var all_sy = response.AllSy;

                        //         if (!selected_sy) {
                        //             console.error("Selected school year not found!");
                        //             $('#modalfwdBal_lower_school').prop('disabled', true);
                        //             return;
                        //         }

                        //         // Enable Forward Balance ONLY if selected SY is the PREVIOUS SY
                        //         $('#modalfwdBal_lower_school').prop('disabled', !all_sy
                        //             .some(sy => sy.id === selected_sy.id - 1));

                        //     },
                        //     error: function(xhr) {
                        //         console.error("Error fetching school year data:", xhr
                        //             .responseText);
                        //         $('#modalfwdBal_lower_school').prop('disabled', true);
                        //     }
                        // });

                        // var current_sydesc = $('#sy').find('option:selected').text();

                        // $.ajax({
                        //     url: "{{ route('OldAccount_SchoolYear') }}",
                        //     method: 'GET',
                        //     data: {
                        //         current_sydesc: current_sydesc
                        //     },
                        //     success: function(response) {
                        //         var selected_sy = response.Sy;
                        //         var all_sy = response.AllSy;
                        //         var active_sy = response.ActiveSy;

                        //         if (!selected_sy || !active_sy) {
                        //             console.error("School year data not found!");
                        //             $('#modalfwdBal_lower_school').prop('disabled', true);
                        //             return;
                        //         }

                        //         // Check if selected SY is the active SY
                        //         if (selected_sy.id === active_sy.id) {
                        //             // Disable if active SY is selected
                        //             $('#modalfwdBal_lower_school').prop('disabled', true);
                        //         } else {
                        //             // Find the previous SY (the one right before active SY)
                        //             var activeIndex = all_sy.findIndex(sy => sy.id ===
                        //                 active_sy.id);
                        //             var previous_sy = all_sy[activeIndex +
                        //             1]; // Next in the ordered list

                        //             // Enable only if selected SY is the previous SY
                        //             $('#modalfwdBal_lower_school').prop('disabled', !
                        //                 previous_sy || selected_sy.id !== previous_sy.id
                        //                 );
                        //         }
                        //     },
                        //     error: function(xhr) {
                        //         console.error("Error fetching school year data:", xhr
                        //             .responseText);
                        //         $('#modalfwdBal_lower_school').prop('disabled', true);
                        //     }
                        // });

                        var current_sydesc = $('#sy').find('option:selected').text();

                        $.ajax({
                            url: "{{ route('OldAccount_SchoolYear') }}",
                            method: 'GET',
                            data: {
                                current_sydesc: current_sydesc
                            },
                            success: function(response) {
                                var selected_sy = response.Sy;
                                var active_sy = response.ActiveSy;

                                if (!selected_sy || !active_sy) {
                                    console.error("School year data not found!");
                                    $('#modalfwdBal_lower_school').prop('disabled', true);
                                    return;
                                }

                                // Enable button for ALL previous SY (where id < active_sy.id)
                                // Disable only for active SY
                                var shouldEnable = selected_sy.id < active_sy.id;

                                $('#modalfwdBal_lower_school').prop('disabled', !
                                    shouldEnable);

                                // For debugging:
                                console.log('Selected SY:', selected_sy.sydesc, 'ID:',
                                    selected_sy.id);
                                console.log('Active SY:', active_sy.sydesc, 'ID:', active_sy
                                    .id);
                                console.log('Button enabled:', shouldEnable);
                            },
                            error: function(xhr) {
                                console.error("Error fetching school year data:", xhr
                                    .responseText);
                                $('#modalfwdBal_lower_school').prop('disabled', true);
                            }
                        });


                        $('#modalfwdBal_lower_school').show();
                        $('#modalfwdBal_higher_school').hide();

                        ///////////////////////
                        $('#modalfwdBal_lower_school').on('click', function() {
                            $('#forward_balanceModal_lower_school').modal('show');
                            var selectedSyDesc = $(this).attr(
                                'data-sycurrent'); // Get the selected school year description
                            // var selectedSyDesc = $(this).find('option:selected').text(); // Get the selected school year description
                            var selectedYear = parseInt(selectedSyDesc.split('-')[
                                0]); // Extract the starting year

                            $('#current_school_year_lower').val(selectedSyDesc);

                            // Calculate the next school year
                            var nextYear = selectedYear + 1;
                            var nextSyDesc = nextYear + '-' + (nextYear + 1);

                            // Send an AJAX request to fetch the next school year
                            $.ajax({
                                url: "{{ route('getNextSchoolYear') }}",
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}', // CSRF token for security
                                    nextSyDesc: nextSyDesc // Send the next school year description
                                },
                                success: function(response) {
                                    if (response.nextSy) {
                                        console.log(response.nextSy);
                                        // If the next school year exists, check for duplicates before adding
                                        if (!$('#oa_sy_lower option[value="' +
                                                response
                                                .nextSy.id + '"]').length) {
                                            var newOption = new Option(response
                                                .nextSy.sydesc, response.nextSy
                                                .id);
                                            $('#oa_sy_lower').append(newOption).val(
                                                response.nextSy
                                                .id
                                            ); // Add and select the new option
                                        } else {
                                            $('#oa_sy_lower').val(response.nextSy
                                                .id); // Select the existing option
                                        }
                                    } else {
                                        Toast.fire({
                                            type: 'info',
                                            title: 'Next school year not found.',
                                            timer: 900 // Set the desired timeout duration in milliseconds
                                        }).then(() => {
                                            $('#forward_balanceModal_lower_school')
                                                .modal('hide');
                                        });
                                    }
                                },
                            });
                        });
                        /////////////////////
                    }

                    if (data.istvl == 1) {
                        $('.filters').hide();
                        $('.tv_filters').show();
                    } else {
                        $('.filters').show();
                        $('.tv_filters').hide();
                    }

                    // if (data.levelid == 14 || data.levelid == 15) {
                    //     $('#ledger_info').text('Student Name:' + data.studname + ' - ' +
                    //         data.levelname + ' - ' +
                    //         data.section + ' | ' + data.strand + ' | ' + data.grantee);
                    // }
                    if (data.levelid == 14 || data.levelid == 15) {
                        $('#ledger_info').html(
                            '<span style="font-weight:bold;margin-right:27px;">Student Name: </span>' +
                            data
                            .studname + ' - ' +
                            '<span style="margin-right:10px;">' + data.levelname + '</span> - ' +
                            '<span style="margin-right:10px;">' + data.section +
                            '</span> , <span>' + data.strand + '</span>');
                    } else if (data.levelid >= 17 && data.levelid <= 21) {
                        $('#ledger_info').html(
                            '<span style="font-weight:bold;margin-right:27px;">Student Name: </span>' +
                            data
                            .studname + ' - ' +
                            '<span style="margin-right:10px;">' + data.levelname + '</span> - ' +
                            '<span style="margin-right:10px;">' + data.section +
                            '</span> , <span>' + data.strand + '</span>');
                    } else {
                        $('#ledger_info').html(
                            '<span style="font-weight:bold;margin-right:27px;">Student Name: </span>' +
                            data
                            .studname + ' - ' +
                            '<span style="margin-right:10px;">' + data.levelname + '</span> - ' +
                            '<span style="margin-right:10px;">' + data.section +
                            '</span> , <span>' + data.strand + '</span>');
                    }

                    $('#ledger_info_status').text('Status: ' + data.studstatus);
                    $('#feesname').text(data
                        .feesname);
                    $('#feesname').attr('data-id', data.feesid);

                }
            });


            get_history_ledger(studids)

            function get_history_ledger(studids) {
                var syid = $('#sy').val();
                var semid = $('#sem').val();
                var studid = $('#studNamev4').val();
                console.log("Student ID:", studid);
                var batchid = $('#tvlbatch').val();

                $.ajax({
                    type: "GET",
                    url: "/finance/history_list",
                    data: {
                        syid: syid,
                        studid: studids,
                        semid: semid,
                        batchid: batchid
                    },
                    success: function(data) {

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
                                    ${item.particulars} 
                                    ${item.classid !== null ? `
                                                                                                                                                                    <span class="text-sm text-danger adj_delete" style="cursor:pointer" data-id="${item.ornum}">
                                                                                                                                                                        <i class="far fa-trash-alt"></i>
                                                                                                                                                                    </span>
                                                                                                                                                                    <span class="text-sm text-info adj_view" style="cursor:pointer" data-id="${item.ornum}" data-toggle="tooltip" title="View Adjustment">
                                                                                                                                                                        <i class="fas fa-archive"></i>
                                                                                                                                                                    </span>
                                                                                                                                                                ` : ''}

                                    ${item.classid === null && !item.particulars.includes('DISCOUNT:') ? `
                                                                                                                                                                    <a href="javascript:void(0)" transid="${item.transid}" id="view_receipts">
                                                                                                                                                                        view receipts
                                                                                                                                                                    </a>
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
        });

        // v1 but not final
        // $('#modalfwdBal').on('click', function() {
        //     var selectedSyDesc = $(this).attr('data-sycurrent'); // Get the selected school year description
        //     // var selectedSyDesc = $(this).find('option:selected').text(); // Get the selected school year description
        //     var selectedYear = parseInt(selectedSyDesc.split('-')[0]); // Extract the starting year

        //     // Calculate the next school year
        //     var nextYear = selectedYear + 1;
        //     var nextSyDesc = nextYear + '-' + (nextYear + 1);

        //     // Send an AJAX request to fetch the next school year
        //     $.ajax({
        //         url: "{{ route('getNextSchoolYear') }}",
        //         method: 'POST',
        //         data: {
        //             _token: '{{ csrf_token() }}', // CSRF token for security
        //             nextSyDesc: nextSyDesc // Send the next school year description
        //         },
        //         success: function(response) {
        //             if (response.nextSy) {
        //                 // If the next school year exists, add it to the select element
        //                 var newOption = new Option(response.nextSy.sydesc, response.nextSy.id);
        //                 $('#oa_sy').append(newOption).val(response.nextSy
        //                     .id); // Add and select the new option
        //             } else {
        //                 alert('Next school year not found.');
        //             }
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Error fetching next school year:', error);
        //         }
        //     });
        // });
        // v2 working code
        // $('#modalfwdBal').on('click', function() {
        //     $('#forward_balanceModal').modal('show');
        //     var selectedSyDesc = $(this).attr('data-sycurrent'); // Get the selected school year description
        //     // var selectedSyDesc = $(this).find('option:selected').text(); // Get the selected school year description
        //     var selectedYear = parseInt(selectedSyDesc.split('-')[0]); // Extract the starting year

        //     // Calculate the next school year
        //     var nextYear = selectedYear + 1;
        //     var nextSyDesc = nextYear + '-' + (nextYear + 1);

        //     // Send an AJAX request to fetch the next school year
        //     $.ajax({
        //         url: "{{ route('getNextSchoolYear') }}",
        //         method: 'POST',
        //         data: {
        //             _token: '{{ csrf_token() }}', // CSRF token for security
        //             nextSyDesc: nextSyDesc // Send the next school year description
        //         },
        //         success: function(response) {
        //             if (response.nextSy) {
        //                 console.log(response.nextSy);
        //                 // If the next school year exists, check for duplicates before adding
        //                 if (!$('#oa_sy option[value="' + response.nextSy.id + '"]').length) {
        //                     var newOption = new Option(response.nextSy.sydesc, response.nextSy.id);
        //                     $('#oa_sy').append(newOption).val(response.nextSy
        //                         .id); // Add and select the new option
        //                 } else {
        //                     $('#oa_sy').val(response.nextSy.id); // Select the existing option
        //                 }
        //             } else {
        //                 Toast.fire({
        //                     type: 'info',
        //                     title: 'Next school year not found.',
        //                     timer: 900 // Set the desired timeout duration in milliseconds
        //                 }).then(() => {
        //                     $('#forward_balanceModal').modal('hide');
        //                 });
        //             }
        //         },
        //     });
        // });
        //working v4 code 
        // $('#modalfwdBal').on('click', function() {
        //     $('#forward_balanceModal').modal('show');
        //     var selectedSyDesc = $(this).attr('data-sycurrent'); // Get the selected school year description
        //     // var selectedSyDesc = $(this).find('option:selected').text(); // Get the selected school year description
        //     var selectedYear = parseInt(selectedSyDesc.split('-')[0]); // Extract the starting year


        //     $('#current_school_year').val(selectedSyDesc);
        //     // Calculate the next school year
        //     var nextYear = selectedYear + 1;
        //     var nextSyDesc = nextYear + '-' + (nextYear + 1);

        //     // Remove previous school years
        //     $('#oa_sy option').each(function() {
        //         var optionYear = parseInt($(this).text().split('-')[0]);
        //         if (optionYear < selectedYear) {
        //             $(this).remove();
        //         }
        //     });

        //     // Send an AJAX request to fetch the next school year
        //     $.ajax({
        //         url: "{{ route('getNextSchoolYear') }}",
        //         method: 'POST',
        //         data: {
        //             _token: '{{ csrf_token() }}', // CSRF token for security
        //             nextSyDesc: nextSyDesc // Send the next school year description
        //         },
        //         success: function(response) {
        //             if (response.nextSy) {
        //                 console.log(response.nextSy);
        //                 // If the next school year exists, check for duplicates before adding
        //                 if (!$('#oa_sy option[value="' + response.nextSy.id + '"]').length) {
        //                     var newOption = new Option(response.nextSy.sydesc, response.nextSy.id);
        //                     $('#oa_sy').append(newOption).val(response.nextSy.id).trigger(
        //                         'change'); // Add and select the new option
        //                 } else {
        //                     $('#oa_sy').val(response.nextSy.id).trigger(
        //                         'change'); // Select the existing option
        //                 }
        //             } else {
        //                 Toast.fire({
        //                     type: 'info',
        //                     title: 'Next school year not found.',
        //                     timer: 900 // Set the desired timeout duration in milliseconds
        //                 }).then(() => {
        //                     $('#forward_balanceModal').modal('hide');
        //                 });
        //             }
        //         },
        //     });
        // });

        //working v2 code
        // $('#forward_balance_to_nextsy').on('click', function() {
        //     console.log('yowwww');
        // console.log($('#ledger-list').find('tr').map(function() {
        //     return $(this).find('td').map(function() {
        //         return $(this).text();
        //     }).get();
        // }).get());

        //     // var ledgerListValues = $('#ledger-list').find('tr').map(function() {
        //     //     return $(this).find('td').map(function() {
        //     //         return $(this).text();
        //     //     }).get();
        //     // }).get());



        // });

        // $('#forward_balance_to_nextsy').on('click', function() {
        //     var ledgerListValues = [];
        //     var columns = $('#ledger-list thead th').length;
        //     $('#ledger-list tbody tr').each(function() {
        //         var rowValues = [];
        //         for (var i = 0; i < columns; i++) {
        //             rowValues.push($(this).find('td:eq(' + i + ')').text());
        //         }
        //         ledgerListValues.push(rowValues);
        //     });

        //     $.ajax({
        //         url: "{{ route('forwardBalanceToNextSY') }}",
        //         method: 'POST',
        //         data: {
        //             _token: '{{ csrf_token() }}',
        //             ledgerListValues: ledgerListValues
        //         },
        //         success: function(response) {
        //             console.log(response);
        //         }
        //     });
        // });

        //v3
        // $('#forward_balance_to_nextsy').on('click', function() {
        //     var ledgerListValues = [];
        //     var columns = $('#ledger-list thead th').length;
        //     $('#ledger-list tbody tr').each(function() {
        //         var rowValues = [];
        //         for (var i = 0; i < columns; i++) {
        //             rowValues.push($(this).find('td:eq(' + i + ')').text());
        //         }
        //         ledgerListValues.push(rowValues);
        //     });

        //     var formData = new FormData();
        //     formData.append('_token', '{{ csrf_token() }}');
        //     formData.append('ledgerListValues', JSON.stringify(ledgerListValues));

        //     $.ajax({
        //         url: "{{ route('forwardBalanceToNextSY') }}",
        //         method: 'POST',
        //         data: formData,
        //         processData: false,
        //         contentType: false,
        //         success: function(response) {
        //             console.log(response);
        //         }
        //     });
        // });

        //working ni siya 
        // $('#forward_balance_to_nextsy').on('click', function() {
        //     var ledgerListValues = $('#ledger-list').find('tr').map(function() {
        //         return $(this).find('td').map(function() {
        //             return $(this).text();
        //         }).get();
        //     }).get();

        //     var formData = new FormData();
        //     formData.append('_token', '{{ csrf_token() }}');
        //     formData.append('ledgerListValues', JSON.stringify(ledgerListValues));

        //     $.ajax({
        //         url: "{{ route('forwardBalanceToNextSY') }}",
        //         method: 'POST',
        //         data: formData,
        //         processData: false,
        //         contentType: false,
        //         success: function(response) {
        //             console.log(response);
        //         }
        //     });
        // });

        // $('#forward_balance_to_nextsy').on('click', function() {
        //     var ledgerListValues = [];
        //     $('#ledger-list').find('tr').each(function(index) {
        //         var rowValues = {};
        //         $(this).find('td').each(function(columnIndex) {
        //             rowValues[`ledgerListValues[${index}][${columnIndex}]`] = $(this).text();
        //         });
        //         $.extend(ledgerListValues, rowValues);
        //     });

        //     var formData = new FormData();
        //     formData.append('_token', '{{ csrf_token() }}');
        //     $.each(ledgerListValues, function(key, value) {
        //         formData.append(key, value);
        //     });

        //     $.ajax({
        //         url: "{{ route('forwardBalanceToNextSY') }}",
        //         method: 'POST',
        //         data: formData,
        //         processData: false,
        //         contentType: false,
        //         success: function(response) {
        //             console.log(response);
        //         }
        //     });
        // });

        // $('#forward_balance_to_nextsy').on('click', function() {
        //     var ledgerListValues = $('#ledger-list').find('tr').map(function(index) {
        //         return {
        //             ledgername: $(this).find('td').eq(0).text(),
        //             charges: $(this).find('td').eq(1).text(),
        //             payments: $(this).find('td').eq(2).text()
        //         };
        //     }).get();

        //     var formData = new FormData();
        //     formData.append('_token', '{{ csrf_token() }}');
        //     $.each(ledgerListValues, function(index, value) {
        //         formData.append(`ledger_accounts[${index}][ledgername]`, value.ledgername);
        //         formData.append(`ledger_accounts[${index}][charges]`, value.charges);
        //         formData.append(`ledger_accounts[${index}][payments]`, value.payments);
        //     });
        //     formData.append('studid', $('#studNamev4').val());
        //     formData.append('syid', $('#oa_sy').val());

        //     formData.append('sem', $('#oa_sem').val() || 1);

        //     // formData.append('sem', $('#editsem').val());
        //     // formData.append('sem', 1);

        //     $.ajax({
        //         url: "{{ route('forwardBalanceToNextSY') }}",
        //         method: 'POST',
        //         data: formData,
        //         processData: false,
        //         contentType: false,
        //         success: function(data) {
        //             const Toast = Swal.mixin({
        //                 toast: true,
        //                 position: 'top-end',
        //                 showConfirmButton: false,
        //                 timer: 3000,
        //                 timerProgressBar: true,
        //                 didOpen: (toast) => {
        //                     toast.addEventListener('mouseenter', Swal.stopTimer)
        //                     toast.addEventListener('mouseleave', Swal.resumeTimer)
        //                 }
        //             });

        //             if (data.status == '1') {
        //                 Toast.fire({
        //                     type: 'success',
        //                     title: 'Forwarded balances to next school year!'
        //                 });
        //             } else {
        //                 Toast.fire({
        //                     icon: 'error',
        //                     title: data.message
        //                 });
        //             }
        //         }
        //     });
        // });

        $('#forward_balance_to_nextsy_higher').on('click', function() {
            var ledgerListValues = $('#ledger-list').find('tr').map(function(index) {
                return {
                    ledgername: $(this).find('td').eq(0).text(),
                    charges: $(this).find('td').eq(1).text().replace(/,/g, ''),
                    // payments: $(this).find('td').eq(2).text()
                    payments: $(this).find('td').eq(2).text().replace(/,/g, '')

                };
            }).get();

            var ledgerListBalance = $('#student_fees').find('tr').map(function(index) {
                // var balance = $(this).find('td').eq(4).text().trim().replace(/,/g, '');
                var balance = $(this).find('td#TOTAL_BALANCE').text().trim().replace(/,/g, '');
                if (balance !== '') {
                    console.log(`Row ${index}: Balance = ${balance}`);
                    return {
                        balances: balance
                    };
                }
            }).get().filter(Boolean);

            var lastTotalBalance = ledgerListBalance.length > 0 ? ledgerListBalance[ledgerListBalance.length - 1]
                .balances : null;
            console.log(`Last Total Balance: ${lastTotalBalance}`);


            var formData = new FormData();

            formData.append('_token', '{{ csrf_token() }}');

            $.each(ledgerListValues, function(index, value) {
                formData.append(`ledger_accounts[${index}][ledgername]`, value.ledgername);
                formData.append(`ledger_accounts[${index}][charges]`, value.charges);
                formData.append(`ledger_accounts[${index}][payments]`, value.payments);
            });

            formData.append('studid', $('#studNamev4').val());
            formData.append('syid', $('#oa_sy_higher').val());

            formData.append('sem', $('#oa_sem_higher').val() || 1);

            formData.append('semdesc', $('#oa_sem_higher').find('option:selected').text());

            formData.append('balance', lastTotalBalance);

            formData.append('nextsydesc', $('#oa_sy_higher').find('option:selected').text());

            formData.append('currentsydesc', $('#current_school_year_higher').val());

            formData.append('currentsy', $('#sy').val());

            formData.append('currentsem', $('#sem').val());
            formData.append('currentsemdesc', $('#sem').find('option:selected').text());

            // formData.append('sem', $('#editsem').val());
            // formData.append('sem', 1);

            $.ajax({
                url: "{{ route('forwardBalanceToNextSY') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
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
                    });
                    if (data.status == '2') {


                    }

                    if (data.status == '1') {
                        Toast.fire({
                            type: 'success',
                            title: 'Forwarded balances to next school year!'
                        });
                        $('#forward_balanceModal_higher_school').modal('hide');
                        $('#view_acountModal').modal('hide');
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message
                        });
                    }
                }
            });
        });

        $('#forward_balance_to_nextsy_lower').on('click', function() {
            // var ledgerListValues = $('#ledger-list').find('tr').map(function(index) {
            //     return {
            //         ledgername: $(this).find('td').eq(0).text(),
            //         charges: $(this).find('td').eq(1).text().replace(/,/g, ''),
            //         // payments: $(this).find('td').eq(2).text()
            //         payments: $(this).find('td').eq(2).text().replace(/,/g, '')

            //     };
            // }).get();

            var ledgerListValues = $('#student_fees').find('tr').map(function(index) {
                return {
                    ledgername: $(this).find('td').eq(0).text(),
                    charges: $(this).find('td').eq(1).text().replace(/,/g, ''),
                    // payments: $(this).find('td').eq(2).text()
                    payments: $(this).find('td').eq(2).text().replace(/,/g, '')

                };
            }).get();


            var ledgerListBalance = $('#student_fees').find('tr').map(function(index) {
                // var balance = $(this).find('td').eq(4).text().trim().replace(/,/g, '');
                var balance = $(this).find('td#TOTAL_BALANCE').text().trim().replace(/,/g, '');
                if (balance !== '') {
                    console.log(`Row ${index}: Balance = ${balance}`);
                    return {
                        balances: balance
                    };
                }
            }).get().filter(Boolean);

            var lastTotalBalance = ledgerListBalance.length > 0 ? ledgerListBalance[ledgerListBalance.length - 1]
                .balances : null;
            console.log(`Last Total Balance: ${lastTotalBalance}`);


            var formData = new FormData();

            formData.append('_token', '{{ csrf_token() }}');

            $.each(ledgerListValues, function(index, value) {
                formData.append(`ledger_accounts[${index}][ledgername]`, value.ledgername);
                formData.append(`ledger_accounts[${index}][charges]`, value.charges);
                formData.append(`ledger_accounts[${index}][payments]`, value.payments);
            });

            formData.append('studid', $('#studNamev4').val());
            formData.append('syid', $('#oa_sy_lower').val());

            formData.append('sem', $('#oa_sem_lower').val() || 1);

            formData.append('semdesc', $('#oa_sem_lower').find('option:selected').text());

            formData.append('balance', lastTotalBalance);

            formData.append('nextsydesc', $('#oa_sy_lower').find('option:selected').text());

            formData.append('currentsydesc', $('#current_school_year_lower').val());

            formData.append('currentsy', $('#sy').val());

            formData.append('currentsem', $('#sem').val());
            formData.append('currentsemdesc', $('#sem').find('option:selected').text());

            // formData.append('sem', $('#editsem').val());
            // formData.append('sem', 1);

            $.ajax({
                url: "{{ route('forwardBalanceToNextSY') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
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
                    });

                    if (data.status == '1') {
                        Toast.fire({
                            type: 'success',
                            title: 'Forwarded balances to next school year!'
                        });

                        $('#forward_balanceModal_lower_school').modal('hide');
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message
                        });
                    }
                }
            });
        });

        function filterCourses() {
            const courses1 = @json($courses1);
            const courses2 = @json($courses2);

            if ($('#input_gradelevel').val() >= 17 && $('#input_gradelevel').val() <= 21) {
                $('#input_course').empty().select2({
                    allowClear: true,
                    data: courses1.map(item => ({
                        id: item['id'],
                        text: item['courseabrv']
                    })),
                    placeholder: "Select course",
                })
            } else if ($('#input_gradelevel').val() >= 22 && $('#input_gradelevel').val() <= 25) {
                $('#input_course').empty().select2({
                    allowClear: true,
                    data: courses2.map(item => ({
                        id: item['id'],
                        text: item['courseabrv']
                    })),
                    placeholder: "Select course",
                })

            }
        }
        var userid = @json(auth()->user()->id);
        var studcurriculum = null
        var curriculum = @json($curriculum);
        $(document).on('change', '#input_course_new', function() {
            var tempcur = curriculum.filter(x => x.courseID == $(this).val());
            $('#input_curriculum_new').empty()
            $('#input_curriculum_new').append('<option value="">Select Curriculum</option>')
            $('#input_curriculum_new').select2({
                data: tempcur,
                allowClear: true,
                placeholder: "Select Curriculum"
            })

            if (studcurriculum != null) {
                $('#input_curriculum_new').val(studcurriculum).change()
            } else {
                $('#input_curriculum_new').val("")
            }

        })

        var all_admissionsetup = []
        var gradelevel = @json($gradelevel);


        $(document).on('change', '#input_gradelevel_new', function() {

            var temp_id = $(this).val()
            var temp_acad = gradelevel.filter(x => x.id == temp_id)
            if (temp_acad.length > 0) {
                var enrollment_type = all_admissionsetup.filter(x => x.acadprogid == temp_acad[0]
                    .acadprogid)
                $("#input_addtype_new").empty();
                $("#input_addtype_new").select2({
                    placeholder: 'Select Admission Type',
                    data: enrollment_type,
                })
            }

            $('#input_strand_holder').attr('hidden', 'hidden')
            $('.input_course_holder').attr('hidden', 'hidden')
            if ($(this).val() == 14 || $(this).val() == 15) {
                $('#input_strand_holder').removeAttr('hidden')
            } else if ($(this).val() >= 17) {
                $('.input_course_holder').removeAttr('hidden')
            }

            if ($(this).val() >= 17 && $(this).val() <= 21) {
                $('#input_course_new').empty();
                $('#input_course_new').append(`
        <option value="">Select Grade Level</option>
        @foreach ($courses1 as $item)
            <option value="{{ $item->id }}">{{ $item->courseDesc }}</option>
        @endforeach
    `)
            } else {
                $('#input_course_new').empty();
                $('#input_course_new').append(`
        <option value="">Select Grade Level</option>
        @foreach ($courses2 as $item)
            <option value="{{ $item->id }}">{{ $item->courseDesc }}</option>
        @endforeach
    `)
            }


        })

        $(document).on('click', '#create_new_student_for_old_Account', function() {


            $('#old_account_studid').val("")
            $('#old_account_studname').val("")
            $('#old_account_gradelvl').val("").trigger('change');
            $('#studName').val("").trigger('change');

            $("#mother").prop("checked", false)
            $("#father").prop("checked", false)
            $("#guardian").prop("checked", false)

            $('#input_gradelevel_new').val("").change()
            $('#input_lrn_new').val("")

            $('#input_sid_new').val("")
            $('#input_altsid_new').val("")

            $('#input_studtype_new').val("new").change()
            $('#input_grantee_new').val(1).change()



            $("#input_mol_new").empty();
            $("#input_mol_new").append('<option value="">Select Mode of Learning</option>');
            $("#input_mol_new").select2({
                data: [],
                allowClear: true,
                placeholder: "Select a Mode of Learning",
            })

            $('#input_mol_new').val("").change()


            $('#input_pantawid_new').val("").change()
            $('#input_addtype_new').val("")
            $('#input_strand_new').val("").change()
            $('#input_course_new').val("").change()

            $('#input_mothermaidename_new').val("")
            $('#input_fname_new').val("")
            $('#input_lname_new').val("")
            $('#input_mname_new').val("")
            $('#input_suffix_new').val("")
            $('#input_dob_new').val("")
            $('#input_semail_new').val("")
            $('#input_gender_new').val("MALE").change()
            $('#input_nationality_new').val(77).change()
            $('#input_scontact_new').val("")

            $('#input_religion_new').val("").change()
            $('#input_mt_new').val("").change()
            $('#input_egroup_new').val("").change()

            $('#input_street_new').val("")
            $('#input_barangay_new').val("")
            $('#input_district_new').val("")
            $('#input_city_new').val("")
            $('#input_province_new').val("")
            $('#input_region_new').val("")

            $('#input_father_fname_new').val("")
            $('#input_father_mname_new').val("")
            $('#input_father_lname_new').val("")
            $('#input_father_sname_new').val("")
            $('#input_father_contact_new').val("")
            $('#input_father_occupation_new').val("")
            $('#fha').val("")

            $('#input_mother_fname_new').val("")
            $('#input_mother_mname_new').val("")
            $('#input_mother_lname_new').val("")
            $('#input_mother_sname_new').val("")
            $('#input_mother_contact_new').val("")
            $('#input_mother_occupation_new').val("")
            $('#mha').val("")

            $('#input_guardian_fname_new').val("")
            $('#input_guardian_mname_new').val("")
            $('#input_guardian_lname_new').val("")
            $('#input_guardian_sname_new').val("")
            $('#input_guardian_contact_new').val("")
            $('#input_guardian_occupation_new').val("")
            $('#input_guardian_relation_new').val("")
            $('#gha').val("")

            $('#studinfo_holder').animate({
                scrollTop: 0
            }, 'slow');

            $('#psschoolname').val("")
            $('#pssy').val("")
            $('#gsschoolname').val("")
            $('#gssy').val("")
            $('#jhsschoolname').val("")
            $('#jhssy').val("")
            $('#shsschoolname').val("")
            $('#shssy').val("")
            $('#collegeschoolname').val("")
            $('#collegesy').val("")

            $('#last_school_att').val("")

            $('#pob').val("").change()
            $('#input_marital_new').val("")
            $('#input_ncf_new').val("")
            $('#input_nce_new').val("")
            $('#input_lsah_new').val("")

            $('#fea').val("")
            $('#mea').val("")
            $('#gea').val("")

            $('#fmi').val("")
            $('#mmi').val("")
            $('#gmi').val("")

            $('#fosoi').val("")
            $('#mosoi').val("")
            $('#gosoi').val("")

            $('#fethnicity').val("")
            $('#methnicity').val("")
            $('#gethnicity').val("")

            $('#last_school_lvlid').val("")
            $('#last_school_no').val("")
            $('#last_school_add').val("")

            $('input[name="vacc"]').prop('checked', false)
            $('#vacc_type_1st').val("").change()
            $('#vacc_type_2nd').val("").change()
            $('#vacc_type_booster').val("").change()
            $('#vacc_card_id').val("")
            $('#dose_date_1st').val("")
            $('#dose_date_2nd').val("")
            $('#dose_date_booster').val("")
            $('#philhealth').val("")
            $('#bloodtype').val("").change()

            $('#allergy_to_med').val("")
            $('#med_his').val("")
            $('#other_med_info').val("")

            $('#bec_cell').val("")
            $('#chapelzone').val("")

            $('#input_mfi_new').val("")
            $('#psschooltype').val("").change()
            $('#gsschooltype').val("").change()
            $('#jhsschooltype').val("").change()
            $('#shsschooltype').val("").change()
            $('#collegeschooltype').val("").change()

            $('#input_gradelevel_new').removeAttr('disabled')
            $('#input_studtype_new').removeAttr('disabled')

            $('#create_new_student_button').removeAttr('hidden')
            $('#update_student_information_button').attr('hidden', 'hidden')
            $('#enrollment_form').attr('hidden', 'hidden')

            $('#student_info_modal').modal()
        })

        function check_studinfo_input() {

            $('.has-error').removeClass('has-error')
            $('.is-invalid').removeClass('is-invalid')
            $('.is-required').each(function(a, b) {
                if ($(this).val() == "") {
                    $(this).addClass('is-invalid')
                } else {
                    $(this).removeClass('is-invalid')
                }
            })

            if ($('#input_gradelevel_new').val() == "") {
                Toast.fire({
                    type: 'info',
                    title: 'Grade Level is Empty!'
                })

                $("#input_gradelevel_new").next().addClass("has-error");
                scrolltoview('input_gradelevel_new')
                return false;
            }

            var levelid = $('#input_gradelevel_new').val()

            if (levelid == 14 || levelid == 15) {
                if ($('#input_strand_new').val() == "") {
                    Toast.fire({
                        type: 'info',
                        title: 'Strand is Empty!'
                    })
                    scrolltoview('input_strand_new')
                    $("#input_strand_new").next().addClass("has-error");
                    return false;
                }
            } else if (levelid >= 17) {
                if ($('#input_course_new').val() == "") {
                    Toast.fire({
                        type: 'info',
                        title: 'Course is Empty!'
                    })
                    scrolltoview('input_course_new')
                    $("#input_course_new").next().addClass("has-error");
                    return false;
                }
                if ($('#input_curriculum_new').val() == "") {
                    Toast.fire({
                        type: 'info',
                        title: 'Curriculum is Empty!'
                    })
                    scrolltoview('input_curriculum_new')
                    $("#input_curriculum_new").next().addClass("has-error");
                    return false;
                }
            } else {
                $('#input_strand_new').val("").change()
                $('#input_course_new').val("").change()
            }

            if ($('#input_fname_new').val() == "") {
                Toast.fire({
                    type: 'info',
                    title: 'First Name is Empty!'
                })
                scrolltoview('input_fname_new')
                return false;
            } else if ($('#input_lname_new').val() == "") {
                Toast.fire({
                    type: 'info',
                    title: 'Last Name is Empty!'
                })
                scrolltoview('input_lname_new')
                return false;
            } else if ($('#input_dob_new').val() == "") {
                Toast.fire({
                    type: 'info',
                    title: 'Birth Date is Empty!'
                })
                scrolltoview('input_dob_new')
                return false;
            } else if ($('#input_gender').val() == "") {
                Toast.fire({
                    type: 'info',
                    title: 'Gender is Empty!'
                })
                scrolltoview('input_gender')
                return false;
            } else if ($('#input_scontact_new').val() == "") {
                Toast.fire({
                    type: 'info',
                    title: 'Student Contact # is Empty!'
                })
                scrolltoview('input_scontact_new')
                return false;
            }





            var ismothernum = 0
            var isfathernum = 0
            var isguardiannum = 0

            if ($('#guardian').prop('checked') == true) {
                isguardiannum = 1
            }
            if ($('#mother').prop('checked') == true) {
                ismothernum = 1
            }
            if ($('#father').prop('checked') == true) {
                isfathernum = 1
            }


            if ($('#input_scontact_new').val() != "" && ($('#input_scontact_new').val()).toString().replace(/-|_/g, '')
                .length != 11) {
                Toast.fire({
                    type: 'warning',
                    title: "Student contact # is invalid!"
                })
                $('#input_scontact_new').addClass('is-invalid')
                scrolltoview('input_scontact_new')
                return false
            } else if ($('#input_father_contact_new').val() != "" && ($('#input_father_contact_new').val()).toString()
                .replace(/-|_/g, '').length != 11) {
                Toast.fire({
                    type: 'warning',
                    title: "Father contact # is invalid!"
                })
                $('#input_father_contact_new').addClass('is-invalid')
                scrolltoview('input_father_contact_new')
                return false
            } else if ($('#input_mother_contact_new').val() != "" && ($('#input_mother_contact_new').val()).toString()
                .replace(/-|_/g, '').length != 11) {
                Toast.fire({
                    type: 'warning',
                    title: "Mother contact # is invalid!"
                })
                $('#input_mother_contact_new').addClass('is-invalid')
                scrolltoview('input_mother_contact_new')
                return false
            } else if ($('#input_guardian_contact_new').val() != "" && ($('#input_guardian_contact_new').val()).toString()
                .replace(/-|_/g, '').length != 11) {
                Toast.fire({
                    type: 'warning',
                    title: "Guardian contact # is invalid!"
                })
                $('#input_guardian_contact_new').addClass('is-invalid')
                scrolltoview('input_guardian_contact_new')
                return false
            } else if (isguardiannum == 0 && ismothernum == 0 && isfathernum == 0) {
                Toast.fire({
                    type: 'warning',
                    title: "Select in case of emergency!"
                })
                scrolltoview('father')
                return false
            }

            if (isfathernum == 1 && $('#input_father_contact_new').val() == "") {
                Toast.fire({
                    type: 'warning',
                    title: "Father contact # is empty!"
                })
                $('#input_father_contact_new').addClass('is-invalid')
                scrolltoview('input_father_contact_new')
                return false
            } else if (ismothernum == 1 && $('#input_mother_contact_new').val() == "") {
                Toast.fire({
                    type: 'warning',
                    title: "Mother contact # is empty!"
                })
                $('#input_mother_contact_new').addClass('is-invalid')
                scrolltoview('input_mother_contact_new')
                return false
            } else if (isguardiannum == 1 && $('#input_guardian_contact_new').val() == "") {
                Toast.fire({
                    type: 'warning',
                    title: "Guardian contact # is empty!"
                })
                $('#input_guardian_contact_new').addClass('is-invalid')
                scrolltoview('input_guardian_contact_new')
                return false
            } else if (isguardiannum == 1 && ($('#input_guardian_contact_new').val()).toString().replace(/-|_/g, '')
                .length != 11) {
                Toast.fire({
                    type: 'warning',
                    title: "Mother contact # is invalid!"
                })
                $('#input_guardian_contact_new').addClass('is-invalid')
                scrolltoview('input_guardian_contact_new')
                return false
            } else if (ismothernum == 1 && ($('#input_mother_contact_new').val()).toString().replace(/-|_/g, '').length !=
                11) {
                Toast.fire({
                    type: 'warning',
                    title: "Mother contact # is invalid!"
                })
                $('#input_mother_contact_new').addClass('is-invalid')
                scrolltoview('input_mother_contact_new')
                return false
            } else if (isfathernum == 1 && ($('#input_father_contact_new').val()).toString().replace(/-|_/g, '').length !=
                11) {
                Toast.fire({
                    type: 'warning',
                    title: "Mother contact # is invalid!"
                })
                $('#input_father_contact_new').addClass('is-invalid')
                scrolltoview('input_father_contact_new')
                return false
            }

            return true
        }

        $(document).on('click', '#create_new_student_button', function() {

            var check = check_studinfo_input()

            if (!check) {
                return false
            }

            var ismothernum = 0
            var isfathernum = 0
            var isguardiannum = 0

            if ($('#guardian').prop('checked') == true) {
                isguardiannum = 1
            }
            if ($('#mother').prop('checked') == true) {
                ismothernum = 1
            }
            if ($('#father').prop('checked') == true) {
                isfathernum = 1
            }


            var oitf = null

            if ($('input[name=oitf]:checked').length > 0) {
                oitf = $('input[name=oitf]:checked').val()
            }

            if ($('#input_oitf_new').val() != "") {
                oitf = $('#input_oitf_new').val()
            }


            if (school_setup.setup == 1) {
                $.ajax({
                    type: 'GET',
                    url: school_setup.es_cloudurl + 'student/preregistration/createnewstudent',
                    data: {
                        userid: userid,
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_sem').val(),

                        levelid: $('#input_gradelevel_new').val(),
                        lrn: $('#input_lrn_new').val(),

                        altsid: $('#input_altsid_new').val(),
                        mol: $('#input_mol_new').val(),

                        studtype: $('#input_studtype_new').val(),
                        studgrantee: $('#input_grantee_new').val(),
                        pantawid: $('#input_pantawid_new').val(),
                        admissiontype: $('#input_addtype_new').val(),
                        strandid: $('#input_strand_new').val(),
                        courseid: $('#input_course_new').val(),
                        curriculum: $('#input_curriculum_new').val(),
                        maidenname: $('#input_mothermaidename_new').val(),
                        firstname: $('#input_fname_new').val(),
                        lastname: $('#input_lname_new').val(),
                        middlename: $('#input_mname_new').val(),
                        suffix: $('#input_suffix_new').val(),
                        dob: $('#input_dob_new').val(),
                        semail: $('#input_semail_new').val(),
                        gender: $('#input_gender_new').val(),
                        nationality: $('#input_nationality_new').val(),
                        contactno: $('#input_scontact_new').val(),
                        ismothernum: ismothernum,
                        isfathernum: isfathernum,
                        isguardiannum: isguardiannum,

                        street: $('#input_street_new').val(),
                        barangay: $('#input_barangay_new').val(),
                        district: $('#input_district_new').val(),
                        city: $('#input_city_new').val(),
                        province: $('#input_province_new').val(),
                        region: $('#input_region_new').val(),

                        ffname: $('#input_father_fname_new').val(),
                        fmname: $('#input_father_mname_new').val(),
                        flname: $('#input_father_lname_new').val(),
                        fsuffix: $('#input_father_sname_new').val(),
                        fcontactno: $('#input_father_contact_new').val(),
                        foccupation: $('#input_father_occupation_new').val(),

                        mfname: $('#input_mother_fname_new').val(),
                        mmname: $('#input_mother_mname_new').val(),
                        mlname: $('#input_mother_lname_new').val(),
                        msuffix: $('#input_mother_sname_new').val(),
                        mcontactno: $('#input_mother_contact_new').val(),
                        moccupation: $('#input_mother_occupation_new').val(),

                        gfname: $('#input_guardian_fname_new').val(),
                        gmname: $('#input_guardian_mname_new').val(),
                        glname: $('#input_guardian_lname_new').val(),
                        gsuffix: $('#input_guardian_sname_new').val(),
                        gcontactno: $('#input_guardian_contact_new').val(),
                        relation: $('#input_guardian_relation_new').val(),
                        goccupation: $('#input_guardian_occupation_new').val(),

                        mtname: $('#input_mt_new option:selected').text(),
                        egname: $('#input_egroup_new option:selected').text(),
                        religionname: $('#input_religion_new option:selected').text(),

                        mtid: $('#input_mt_new').val(),
                        egid: $('#input_egroup_new').val(),
                        religionid: $('#input_religion_new').val(),

                        // psschoolname: $('#psschoolname').val(),
                        // pssy: $('#pssy').val(),
                        // gsschoolname: $('#gsschoolname').val(),
                        // gssy: $('#gssy').val(),
                        // jhsschoolname: $('#jhsschoolname').val(),
                        // jhssy: $('#jhssy').val(),
                        // shsschoolname: $('#shsschoolname').val(),
                        // shsstrand: $('#shsstrand').val(),
                        // shssy: $('#shssy').val(),
                        // collegeschoolname: $('collegeschoolname').val(),
                        // collegesy: $('#collegesy').val(),

                        // vacc: $('input[name="vacc"]:checked').val(),
                        // vacc_type_1st: $('#vacc_type_1st').val(),
                        // vacc_type_2nd: $('#vacc_type_2nd').val(),
                        // vacc_type_booster: $('#vacc_type_booster').val(),
                        // vacc_type_text_1st: $('#vacc_type_1st option:selected').text(),
                        // vacc_type_text_2nd: $('#vacc_type_2nd option:selected').text(),
                        // vacc_type_text_booster: $('#vacc_type_booster option:selected').text(),
                        // vacc_card_id: $('#vacc_card_id').val(),
                        // dose_date_1st: $('#dose_date_1st').val(),
                        // dose_date_2nd: $('#dose_date_2nd').val(),
                        // dose_date_booster: $('#dose_date_booster').val(),
                        // philhealth: $('#philhealth').val(),
                        // bloodtype: $('#bloodtype').val(),
                        allergy: $('#allergy').val(),
                        // allergy_to_med: $('#allergy_to_med').val(),
                        // med_his: $('#med_his').val(),
                        // other_med_info: $('#other_med_info').val(),

                        // bec_cell: $('#bec_cell').val(),
                        // chapelzone: $('#chapelzone').val(),

                        mfi: $('#input_mfi_new').val(),
                        // psschooltype: $('#psschooltype').val(),
                        // gsschooltype: $('#gsschooltype').val(),
                        // jhsschooltype: $('#jhsschooltype').val(),
                        // shsschooltype: $('#shsschooltype').val(),
                        // collegeschooltype: $('#collegeschooltype').val(),

                        // lastschoolatt: $('#last_school_att').val(),

                        pob: $('#pob').val(),
                        maritalstatus: $('#input_marital_new').val(),
                        nocitf: $('#input_ncf_new').val(),
                        noce: $('#input_nce_new').val(),
                        lsah: $('#input_lsah_new').val(),
                        oitf: oitf,

                        // glits: $('#last_school_lvlid').val(),
                        // scn: $('#last_school_no').val(),
                        // cmaosla: $('#last_school_add').val(),

                        // fea: $('#fea').val(),
                        // mea: $('#mea').val(),
                        // gea: $('#gea').val(),

                        // moccupation: $('#input_mother_occupation_new').val(),
                        // foccupation: $('#input_father_occupation_new').val(),
                        // relation: $('#input_guardian_relation_new').val(),

                        // fmi: $('#fmi').val(),
                        // mmi: $('#mmi').val(),
                        // gmi: $('#gmi').val(),

                        fha: $('#fha').val(),
                        mha: $('#mha').val(),
                        gha: $('#gha').val(),

                        // fosoi: $('#fosoi').val(),
                        // mosoi: $('#mosoi').val(),
                        // gosoi: $('#gosoi').val(),

                        // fethnicity: $('#fethnicity').val(),
                        // methnicity: $('#methnicity').val(),
                        // gethnicity: $('#gethnicity').val(),


                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                            get_last_index('studinfo_more')
                            get_last_index('apmc_midinfo')
                            get_last_index('studinfo')
                            get_last_index('student_pregistration')
                        } else if (data[0].status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data[0].message
                            })
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
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
            } else {
                $.ajax({
                    type: 'GET',
                    // url: '/student/preregistration/createnewstudent',
                    url: "{{ route('create_student_information') }}",
                    data: {
                        userid: userid,
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_sem').val(),

                        levelid: $('#input_gradelevel_new').val(),
                        levelname: $('#input_gradelevel_new :selected').text(),
                        lrn: $('#input_lrn_new').val(),

                        altsid: $('#input_altsid_new').val(),
                        mol: $('#input_mol_new').val(),

                        studtype: $('#input_studtype_new').val(),
                        studgrantee: $('#input_grantee_new').val(),
                        pantawid: $('#input_pantawid_new').val(),
                        admissiontype: $('#input_addtype_new').val(),
                        strandid: $('#input_strand_new').val(),
                        courseid: $('#input_course_new').val(),
                        curriculum: $('#input_curriculum_new').val(),
                        maidenname: $('#input_mothermaidename_new').val(),
                        firstname: $('#input_fname_new').val(),
                        lastname: $('#input_lname_new').val(),
                        middlename: $('#input_mname_new').val(),
                        suffix: $('#input_suffix_new').val(),
                        dob: $('#input_dob_new').val(),
                        semail: $('#input_semail_new').val(),
                        gender: $('#input_gender_new').val(),
                        nationality: $('#input_nationality_new').val(),
                        contactno: $('#input_scontact_new').val(),
                        ismothernum: ismothernum,
                        isfathernum: isfathernum,
                        isguardiannum: isguardiannum,

                        street: $('#input_street_new').val(),
                        barangay: $('#input_barangay_new').val(),
                        district: $('#input_district_new').val(),
                        city: $('#input_city_new').val(),
                        province: $('#input_province_new').val(),
                        region: $('#input_region_new').val(),

                        fha: $('#fha').val(),
                        ffname: $('#input_father_fname_new').val(),
                        fmname: $('#input_father_mname_new').val(),
                        flname: $('#input_father_lname_new').val(),
                        fsuffix: $('#input_father_sname_new').val(),
                        fcontactno: $('#input_father_contact_new').val(),
                        foccupation: $('#input_father_occupation_new').val(),

                        mha: $('#mha').val(),
                        mfname: $('#input_mother_fname_new').val(),
                        mmname: $('#input_mother_mname_new').val(),
                        mlname: $('#input_mother_lname_new').val(),
                        msuffix: $('#input_mother_sname_new').val(),
                        mcontactno: $('#input_mother_contact_new').val(),
                        moccupation: $('#input_mother_occupation_new').val(),

                        gha: $('#gha').val(),
                        gfname: $('#input_guardian_fname_new').val(),
                        gmname: $('#input_guardian_mname_new').val(),
                        glname: $('#input_guardian_lname_new').val(),
                        gsuffix: $('#input_guardian_sname_new').val(),
                        gcontactno: $('#input_guardian_contact_new').val(),
                        relation: $('#input_guardian_relation_new').val(),
                        goccupation: $('#input_guardian_occupation_new').val(),

                        mtname: $('#input_mt_new option:selected').text(),
                        egname: $('#input_egroup_new option:selected').text(),
                        religionname: $('#input_religion_new option:selected').text(),

                        mtid: $('#input_mt_new').val(),
                        egid: $('#input_egroup_new').val(),
                        religionid: $('#input_religion_new').val(),

                        // psschoolname: $('#psschoolname').val(),
                        // pssy: $('#pssy').val(),
                        // gsschoolname: $('#gsschoolname').val(),
                        // gssy: $('#gssy').val(),
                        // jhsschoolname: $('#jhsschoolname').val(),
                        // jhssy: $('#jhssy').val(),
                        // shsschoolname: $('#shsschoolname').val(),
                        // shsstrand: $('#shsstrand').val(),
                        // shssy: $('#shssy').val(),
                        // collegeschoolname: $('collegeschoolname').val(),
                        // collegesy: $('#collegesy').val(),

                        // vacc: $('input[name="vacc"]:checked').val(),
                        // vacc_type_1st: $('#vacc_type_1st').val(),
                        // vacc_type_2nd: $('#vacc_type_2nd').val(),
                        // vacc_type_booster: $('#vacc_type_booster').val(),
                        // vacc_type_text_1st: $('#vacc_type_1st option:selected').text(),
                        // vacc_type_text_2nd: $('#vacc_type_2nd option:selected').text(),
                        // vacc_type_text_booster: $('#vacc_type_booster option:selected').text(),
                        // vacc_card_id: $('#vacc_card_id').val(),
                        // dose_date_1st: $('#dose_date_1st').val(),
                        // dose_date_2nd: $('#dose_date_2nd').val(),
                        // dose_date_booster: $('#dose_date_booster').val(),
                        // philhealth: $('#philhealth').val(),
                        // bloodtype: $('#bloodtype').val(),
                        allergy: $('#allergy').val(),
                        // allergy_to_med: $('#allergy_to_med').val(),
                        // med_his: $('#med_his').val(),
                        // other_med_info: $('#other_med_info').val(),

                        // bec_cell: $('#bec_cell').val(),
                        // chapelzone: $('#chapelzone').val(),

                        mfi: $('#input_mfi_new').val(),
                        // psschooltype: $('#psschooltype').val(),
                        // gsschooltype: $('#gsschooltype').val(),
                        // jhsschooltype: $('#jhsschooltype').val(),
                        // shsschooltype: $('#shsschooltype').val(),
                        // collegeschooltype: $('#collegeschooltype').val(),

                        // lastschoolatt: $('#last_school_att').val(),
                        // glits: $('#last_school_lvlid').val(),
                        // scn: $('#last_school_no').val(),
                        // cmaosla: $('#last_school_add').val(),

                        pob: $('#pob').val(),
                        maritalstatus: $('#input_marital_new').val(),
                        nocitf: $('#input_ncf_new').val(),
                        noce: $('#input_nce_new').val(),
                        lsah: $('#input_lsah_new').val(),
                        oitf: oitf,


                        //socio economic profile start
                        // fea: $('#fea').val(),
                        // mea: $('#mea').val(),
                        // gea: $('#gea').val(),

                        // moccupation: $('#input_mother_occupation_new').val(),
                        // foccupation: $('#input_father_occupation_new').val(),
                        // relation: $('#input_guardian_relation_new').val(),

                        // fmi: $('#fmi').val(),
                        // mmi: $('#mmi').val(),
                        // gmi: $('#gmi').val(),

                        // fosoi: $('#fosoi').val(),
                        // mosoi: $('#mosoi').val(),
                        // gosoi: $('#gosoi').val(),

                        // fethnicity: $('#fethnicity').val(),
                        // methnicity: $('#methnicity').val(),
                        // gethnicity: $('#gethnicity').val(),
                        //socio economic profile end
                    },
                    success: function(data) {
                        console.log('data AFTER SAVING NI', data);
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                            $('#student_info_modal').modal('hide');


                            $('#old_account_id').val(data[0].id);
                            $('#old_account_studid').val(data[0].sid);


                            $('#old_account_studname').val(data[0].studid.firstname + ', ' + data[0]
                                .studid.lastname +
                                ' ' +
                                (data[0].studid.middlename || ''));

                            $('#old_account_gradelvl').html('');
                            $('#old_account_gradelvl').append("<option value='" + data[0].levelid +
                                "'>" +
                                data[0].levelname + "</option>");
                            // load_update_info_datatable()
                            // load_all_preregstudent()
                        } else if (data[0].status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data[0].message
                            })
                        } else if (data[0].status == 3) {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('error', xhr.responseText);
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                })
            }
        })

        $(document).on('click', '#create_new_student', function() {
            $('#add_old_account_Modal').modal('show');

        })

        $('#studName').select2({
            placeholder: "SELECT STUDENT",
            allowClear: true
        });

        $('#studName').on('select2:open', function() {
            let observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if ($('.select2-results__option').length === 1 && $(
                            '.select2-results__message').text() ===
                        "No results found") {
                        $('#create_new_student_for_old_Account').show();
                    } else {
                        // $('#create_new_student_for_old_Account').hide();
                    }
                });
            });

            let targetNode = document.querySelector('.select2-results');
            if (targetNode) {
                observer.observe(targetNode, {
                    childList: true,
                    subtree: true
                });
            }
        });

        // $('#studName').change(function() {
        //     if ($(this).val()) {
        //         $('#create_new_student_for_old_Account').hide();
        //     }
        // });

        $('#studName').change(function() {
            let studentId = $(this).val();
            $.ajax({
                url: "{{ route('oldAccounts_getStudData') }}",
                type: 'GET',
                data: {
                    studentId: studentId
                },
                success: function(data) {
                    console.log(data, 'data ni sa mga students');
                    if (data.length > 0) {
                        let student = data[0];
                        $('#old_account_id').val(student.id);
                        $('#old_account_studid').val(student.sid);
                        $('#old_account_studname').val(student.lastname + ', ' + student.firstname +
                            ' ' +
                            (student.middlename || ''));
                        $('#old_account_gradelvl').html('');
                        $.each(data, function(key, value) {
                            $('#old_account_gradelvl').append("<option value='" + value
                                .levelid + "'>" + value.levelname + "</option>");
                        });
                        // $('#old_account_gradelvl').val(student.levelid).find('option:selected').text(student.levelname);
                    }
                }
            });
        });


        $('#old_account_save').on('click', function() {
            event.preventDefault();
            
            var formData = new FormData();

            formData.append('_token', '{{ csrf_token() }}');

            if ($('#old_account_id').val() == "") {
                Toast.fire({
                    type: 'info',
                    title: 'Please select a student first!'
                })
                return false;
            }

            if ($('#old_account_balance').val() == "") {
                Toast.fire({
                    type: 'info',
                    title: 'Balance is required!'
                })
                return false;
            }

            if ($('#old_account_sy').val() == $('#from_sy').val()) {

                if ($('#old_account_sem').val() == $('#from_sem').val()) {
                    Toast.fire({
                        type: 'info',
                        title: 'School Year and Semester must be different!'
                    })
                    return false;
                }
            }else{
                if ($('#old_account_sem').val() == $('#from_sem').val()) {
                    Toast.fire({
                        type: 'info',
                        title: 'Semester must be different!'
                    })
                    return false;
                }
            }


           


            formData.append('studid', $('#old_account_id').val());
            formData.append('syid', $('#old_account_sy').val());
            formData.append('sydesc', $('#from_sy').find('option:selected').text());

            formData.append('sem', $('#old_account_sem').val() || 1);
            formData.append('semdesc', $('#from_sem').find('option:selected').text());

            formData.append('balance', $('#old_account_balance').val());

            $.ajax({
                url: "{{ route('newoldAccounts_saving') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: data.message
                        })

                        $("#add_old_account_Modal").modal('hide');
                        $('#old_account_id').val("");
                        $('#old_account_studid').val("");
                        $('#old_account_studname').val("");
                        $('#old_account_gradelvl').val("").trigger('change');
                        $('#old_account_balance').val("");
                    }
                }
            });

        });

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

        $(document).on("click", ".view-items2", function() {
            var row = $(this).closest("tr"); // Get the parent row
            var nextRow = row.next(); // Check if the next row is already a breakdown row

            if (nextRow.hasClass("breakdown-row")) {
                nextRow.remove(); // Remove the breakdown row if already opened
                return;
            }

            var items = JSON.parse($(this).attr("data-items2")); // Get items from attribute
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
                            let charge = parseFloat(item.amount) || 0;
                            let payment = parseFloat(item.payment) || 0;
                            let balance = 0;

                            return `
                                                                                                <tr style="background-color: white;">
                                                                                                    <td width="15%"></td>
                                                                                                    <td width="40%">${item.particulars}</td>
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
    </script>
@endsection
