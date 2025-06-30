@extends('ctportal.layouts.app2')

@section('pagespecificscripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowgroup/1.1.0/css/rowGroup.dataTables.min.css">
    <style>
        .tableFixHead thead th {
            position: sticky;
            top: 0;
            background-color: #fff;
            outline: 2px solid #dee2e6;
            outline-offset: -1px;


        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .grade_td {
            cursor: pointer;
            vertical-align: middle !important;
        }

        #dropdown-item {
            background-color: green;
            color: white;
            cursor: pointer;
            border-radius: 5%;
            margin-bottom: 2px;
            width: 120px;
            font-size: 13px;
            padding: 6px;
            text-align: center;
        }

        .sort-icon {
            font-size: 14px;
            color: black;
            /* Example color */
        }

        .sort-icon:hover {
            color: blue;
            /* Example hover color */
        }

        #grade_submissions {
            cursor: pointer;
            background-color: rgb(60, 114, 181);
            color: white;
        }

        #grade_submissions:hover {
            background-color: rgba(29, 62, 103, 0.859);
        }
    </style>
@endsection

@section('content')
    @php
        $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
        $semester = DB::table('semester')->get();
        $levelname = DB::table('college_year')->get();

    @endphp

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Student Grades</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">System Grading</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid mt-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                                        <li class="nav-item col-md-2 ">
                                            <a class="nav-link active" href="{{ url('college/teacher/student/collegesystemgrading') }}">
                                                System Grading
                                            </a>
                                        </li>
                                        {{-- <li class="nav-item col-md-2 ">
                                            <a class="nav-link" href="{{ url('college/teacher/student/excelupload') }}">
                                                Excel Upload
                                            </a>
                                        </li> --}}
                                        <li class="nav-item col-md-2 ">
                                            <a class="nav-link " href="{{ url('college/teacher/student/grades') }}">
                                                Final Grading
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                {{-- <div class="col-md-3"></div> --}}
                            </div>
                            <div class="info-box shadow">
                                {{-- <span class="info-box-icon bg-primary"><i class="fas fa-calendar-check"></i></span> --}}
                                <div class="info-box-content">
                                    <div class="row pb-2 d-flex">
                                        <div class="col-md-2 col-sm-1">
                                            <label for="">School Year</label>
                                            <select class="form-control form-control-sm select2" id="syid">
                                                @foreach ($sy as $item)
                                                    @if ($item->isactive == 1)
                                                        <option value="{{ $item->id }}" selected="selected">
                                                            {{ $item->sydesc }}</option>
                                                    @else
                                                        <option value="{{ $item->id }}">{{ $item->sydesc }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-1">
                                            <label for="">Semester</label>
                                            <select class="form-control form-control-sm select2" id="semester">
                                                @foreach ($semester as $item)
                                                    <option {{ $item->isactive == 1 ? 'selected' : '' }}
                                                        value="{{ $item->id }}">{{ $item->semester }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-1">
                                            <label for="">Level</label>
                                            <select class="form-control form-control-sm select2" id="levelid">
                                                <option value="0">ALL</option>
                                                @foreach ($levelname as $item)
                                                    <option value="{{ $item->levelid }}">{{ $item->yearDesc }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-1" hidden>
                                            <label for="">Term</label>
                                            <select class="form-control form-control-sm select2" id="term">
                                                <option value="">All</option>
                                                <option value="Whole Sem">Whole Sem</option>
                                                <option value="1st Term">1st Term</option>
                                                <option value="2nd Term">2nd Term</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header  bg-primary">
                            <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Class Schedule Details</h3>
                        </div>
                        <div class="card-body  p-2">
                        </div>
                        <div class="row p-2">
                            <div class="col-md-12" style="font-size:.8rem">
                                <table class="table table-sm display table-bordered table-responsive-sm" id="systemgrading"
                                    width="100%">
                                    <thead>
                                        <tr class="">
                                            <th>Section</th>
                                            <th>Subject</th>
                                            <th class="text-center">Level</th>
                                            <th>Time Schedule</th>
                                            <th class="text-center">Day</th>
                                            <th class="text-center">Room</th>
                                            <th class="text-center">Grading Template</th>
                                            <th class="text-center">Enrolled</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </section>

    <div class="modal fade" id="modal_1" data-backdrop="static" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0 bg-dark">
                    <h4 class="modal-title">
                        <span class="mt-1" id="sectionNameHeader"></span>
                    </h4>
                    <div class="d-flex align-items-center ml-auto">
                        <a class="btn btn-primary btn-sm ml-2" id="view_pdf" href="#">
                            <i class="far fa-file-pdf mr-1"></i> PRINT PDF
                        </a>
                        <button type="button" class="close pb-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
                <div class="container-fluid headerText p-3">
                    <div class="row py-3">
                        <!-- Teacher -->
                        <div class="col-md-3">
                            <div class="d-flex flex-column text-center">
                                <span class="font-weight-bold h6"><i class="fas fa-chalkboard-teacher"></i> Teacher</span>
                                <span id="teacherName">{{ auth()->user()->name }}</span>
                                <a class="text-primary" id="teacherID">{{ auth()->user()->email }}</a>
                            </div>
                        </div>
                        <!-- Subject -->
                        <div class="col-md-3">
                            <div class="d-flex flex-column text-center">
                                <span class="font-weight-bold h6"><i class="fas fa-book"></i> Subject</span>
                                <span id="subjectDesc"></span>
                                <a class="text-primary" id="subjectCode"></a>
                            </div>
                        </div>
                        <!-- Level -->
                        <div class="col-md-3">
                            <div class="d-flex flex-column text-center">
                                <span class="font-weight-bold h6"><i class="fas fa-graduation-cap"></i> Level</span>
                                <span id="collegeLevel"></span>
                            </div>
                        </div>
                        <!-- Section -->
                        <div class="col-md-3">
                            <div class="d-flex flex-column text-center">
                                <span class="font-weight-bold h6"><i class="fas fa-building"></i> Section</span>
                                <span id="section"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body" style="font-size:.8rem;height: 450px; overflow-y: auto;">
                    <table class="table table-sm table-striped" width="100%">
                        <thead>
                            <tr id="enrolled_datatable_table">
                                <th width="10%">Student ID No.</th>
                                <th width="15%" class="text-center">
                                    <div class="d-flex flex-row justify-content-between">
                                        <p class="mb-0">Student Name</p>
                                        <a href="" id="sort"><i class="fa fa-sort"></i></a>
                                    </div>
                                </th>
                                <th width="10%">Academic Level</th>
                                <th width="12%">Course</th>
                                <th width="10%">Contact No.</th>
                                <th width="10%">Email Address</th>
                                <th width="auto">Address</th>
                            </tr>
                        </thead>
                        <tbody id="student_table">
                            <tr class="male_students">
                                <td colspan="7" class="font-weight-bold male_sort_arrow" style="background-color: #8ec9fd">Male <a
                                        href="" id="male_sort"><i class="fa fa-sort"></td>
                            </tr>
                            <tr class="female_students">
                                <td colspan="7" class=" font-weight-bold female_sort_arrow" style="background-color: #fd8ec9">Female <a
                                        href="" id="female_sort"><i class="fa fa-sort"></td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('footerscript')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    {{-- <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script> --}}
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    {{-- <script src="https://cdn.datatables.net/rowgroup/1.1.0/js/dataTables.rowGroup.min.js"></script> --}}


    <script>
        $(document).ready(function () {
            

            var syid = $('#syid').val();
            var semid = $('#semester').val();
            var levelid = $('#levelid').val();

            get_schedule_details();

            $(document).on('change','#syid', function () {
                syid = $(this).val();
                get_schedule_details()
            })

            $(document).on('change','#semester', function () {
                semid = $(this).val();
                get_schedule_details()
            })

            $(document).on('change','#levelid', function () {
                levelid = $(this).val();
                get_schedule_details()
            })


            function get_schedule_details(){
                $.ajax({
                    url: '/college/teacher/schedule/get/sched_grades/',
                    type: 'GET',
                    data: {
                        syid: syid,
                        semid: semid,
                        gradelevel: levelid
                    },
                    success: function (response) {
                        schedule_datatable(response)
                    }
                })
            }

            function schedule_datatable(data){
                console.log(data,'data');
                
                $('#systemgrading').DataTable({
                    destroy: true,
                    order: false,
                    data: data,
                    lengthChange: false,
                    info: false,
                    paging: false,
                    columns: [
                        {data: 'sectionDesc'},
                        {data: 'subjDesc'},
                        {data: 'yearDesc'},
                        {data: 'schedtime'},
                        {data: 'days'},
                        {data: 'rooms'},
                        {data: null},
                        {data: null},
                        {data: null},
                    ],
                    columnDefs: [
                        {
                            targets: 0,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 1,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).addClass('align-middle')
                            }
                        },
                        
                        {
                        targets: 2,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).addClass('align-middle text-center')
                            }
                        },
                        {
                            targets: 3,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).addClass('align-middle text-center')
                            }
                        },

                        {
                            targets: 4,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).addClass('align-middle text-center')
                            }
                        },
                        {
                            targets: 5,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {

                                $(td).addClass('align-middle text-center')
                            }
                        },
                        {
                            targets: 6,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<div  class="" >${rowData.ecrDesc ? rowData.ecrDesc : ''}</div>`)

                                $(td).addClass('align-middle text-center')
                            }
                        },

                        {
                            targets: 7,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<a href="#" class="enrolled_stud" data-toggle="modal" data-target="#modal_1" data-section="${rowData.sectionDesc}" data-levelname="${rowData.levelname}" data-subject="${rowData.subject}" data-subjectid="${rowData.subjectid}" data-sectionid="${rowData.sectionid}" data-id="${rowData.schedid}">${rowData.enrolled}</a>`)

                                $(td).addClass('align-middle text-center')
                            }
                        },

                        {
                            targets: 8,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<a href="#"  data-subjectid="${rowData.subjectid}" data-sectionid="${rowData.sectionid}" class="goto_grading" style="text-decoration: underline;">Grading</a>`)

                                $(td).addClass('align-middle text-center')
                            }
                        },
                    ]
                })
            }

            $(document).on('click','.goto_grading', function(e){
                var subjectid = $(this).data('subjectid');
                var sectionid = $(this).data('sectionid');
                $.ajax({
                    url: '/college/teacher/student/systemgrading/' + subjectid + '/' + syid + '/'  + semid + '/' + sectionid,
                    type: 'GET',
                    success: function (response) {
                        if(response === 'No Grading Template Selected'){
                            Swal.fire({
                                type: 'warning',
                                title: 'No Grading Template Selected',
                                text: 'Please contact your Dean for Grading Template'
                            })
                        }else if(response === 'Final Grading Already Exists'){
                            Swal.fire({
                                type: 'warning',
                                title: 'Final Grading Already Used!',
                                text: 'Please Continue Grading at Final Grading'
                            })
                        }else{
                            window.location.href = '/college/teacher/student/systemgrading/' + subjectid + '/' + syid + '/'  + semid + '/' + sectionid;
                        }
                        
                    }

                })
            })

            function show_students() {
                $('.appended_row_stud').remove(); // Remove previously appended rows
                var students = student;
                console.log(students, 'toggle');

                if (toggle == 1) {
                    // Sort students by lastname (assuming students have a 'lastname' field)
                    students.sort(function(a, b) {
                        if (a.lastname.toLowerCase() < b.lastname.toLowerCase()) {
                            return -1;
                        }
                        if (a.lastname.toLowerCase() > b.lastname.toLowerCase()) {
                            return 1;
                        }
                        return 0;
                    });

                    // Separate male and female rows before appending
                    let maleRows = '';
                    let femaleRows = '';

                    $.each(students, function(k, student) {
                        let row = `
                            <tr class="appended_row_stud ${student.gender === 'MALE' ? 'male_row' : 'female_row'}">
                                <td>${student.sid}</td>
                                <td>${student.student_name}</td>
                                <td>${student.levelname}</td>
                                <td>${student.courseabrv}</td>
                                <td>${student.contactno ? student.contactno : ''}</td>
                                <td>${student.semail ? student.semail : ''}</td>
                                <td>${student.street} ${student.barangay} ${student.city}</td>
                            </tr>
                        `;

                        if (student.gender == "MALE") {
                            maleRows += row; // Accumulate male student rows
                            $(row).addClass('appended_row_stud_male');
                        } else if (student.gender == "FEMALE") {
                            femaleRows += row; // Accumulate female student rows
                            $(row).addClass('appended_row_stud_female');
                        }
                    });

                    // Insert rows after male and female sections
                    $('.male_students').after(maleRows);
                    $('.female_students').after(femaleRows);

                    $('.male_students').removeClass('display-none');
                    $('.female_students').removeClass('display-none');

                } else if (toggle == 2) {
                    // If toggle is not 1, append all students to the main table
                    let allRows = '';

                    $.each(students, function(k, student) {
                        allRows += `
                            <tr class="appended_row_stud">
                                <td>${student.sid}</td>
                                <td>${student.student_name}</td>
                                <td>${student.levelname}</td>
                                <td>${student.courseabrv}</td>
                                <td>${student.contactno ? student.contactno : ''}</td>
                                <td>${student.semail ? student.semail : ''}</td>
                                <td>${student.street} ${student.barangay} ${student.city}</td>
                            </tr>
                        `;
                    });

                    $('#student_table').append(allRows);
                    $('.male_students').addClass('display-none');
                    $('.female_students').addClass('display-none');
                } else {
                    let allRows = '';

                    students.sort(function(a, b) {
                        if (a.lastname.toLowerCase() > b.lastname.toLowerCase()) {
                            return -1;
                        }
                        if (a.lastname.toLowerCase() < b.lastname.toLowerCase()) {
                            return 1;
                        }
                        return 0;
                    });

                    $.each(students, function(k, student) {
                        allRows += `
                            <tr class="appended_row_stud">
                                <td>${student.sid}</td>
                                <td>${student.student_name}</td>
                                <td>${student.levelname}</td>
                                <td>${student.courseabrv}</td>
                                <td>${student.contactno ? student.contactno : ''}</td>
                                <td>${student.semail ? student.semail : ''}</td>
                                <td>${student.street} ${student.barangay} ${student.city}</td>
                            </tr>
                        `;
                    });

                    $('#student_table').append(allRows);
                    $('.male_students').addClass('display-none');
                    $('.female_students').addClass('display-none');
                }
            }

            function show_students_function(subjectid,sectionid) {
                var syid = $('#syid').val()
                var semid = $('#semester').val()
                $.ajax({
                    type: 'get',
                    url: '/college/teacher/student-list-for-all/' + syid + '/' + semid + '/' + subjectid + '/' + sectionid,
                    success: function(data) {
                        console.log(data)
                        student = data.students
                        show_students()
                    }
                })
            }

            var studschedid;
            $(document).on('click', '.enrolled_stud', function(e) {
                e.preventDefault()
                toggle = 1;
                studschedid = $(this).data('id')
                var subject = $(this).attr('data-subject')
                var levelname = $(this).attr('data-levelname')
                var section = $(this).attr('data-section')
                var sectionid = $(this).attr('data-sectionid')
                var subjectid = $(this).attr('data-subjectid')
                $('#view_pdf').attr('data-sectionid', sectionid)
                $('#view_pdf').attr('data-subjectid', subjectid)
                $('#subjectDesc').text(subject)
                $('#collegeLevel').text(levelname)
                $('#section').text(section)
                $('#sectionNameHeader').text(section + ' ' + subject)
                show_students_function(subjectid, sectionid)
            })

            $(document).on('click','#view_pdf', function(){
                var syid = $('#syid').val()
                var semester = $('#semester').val()
                var subjectid = $(this).attr('data-subjectid')
                var sectionid = $(this).attr('data-sectionid')
                

                window.open('/college/teacher/student-list/print/' + syid + '/' + semester + '/' + subjectid + '/' + sectionid + '/' + studschedid);
            })
        })
    </script>
@endsection
