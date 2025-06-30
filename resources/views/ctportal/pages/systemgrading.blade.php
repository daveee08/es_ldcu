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

        .loader{
            width: 100px;
            height: 100px;
            margin: 50px auto;
            position: relative;
        }
        .loader:before,
        .loader:after{
            content: "";
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: solid 8px transparent;
            position: absolute;
            -webkit-animation: loading-1 1.4s ease infinite;
            animation: loading-1 1.4s ease infinite;
        }
        .loader:before{
            border-top-color: #d72638;
            border-bottom-color: #07a7af;
        }
        .loader:after{
            border-left-color: #ffc914;
            border-right-color: #66dd71;
            -webkit-animation-delay: 0.7s;
            animation-delay: 0.7s;
        }
        @-webkit-keyframes loading-1{
            0%{
                -webkit-transform: rotate(0deg) scale(1);
                transform: rotate(0deg) scale(1);
            }
            50%{
                -webkit-transform: rotate(180deg) scale(0.5);
                transform: rotate(180deg) scale(0.5);
            }
            100%{
                -webkit-transform: rotate(360deg) scale(1);
                transform: rotate(360deg) scale(1);
            }
        }
        @keyframes loading-1{
            0%{
                -webkit-transform: rotate(0deg) scale(1);
                transform: rotate(0deg) scale(1);
            }
            50%{
                -webkit-transform: rotate(180deg) scale(0.5);
                transform: rotate(180deg) scale(0.5);
            }
            100%{
                -webkit-transform: rotate(360deg) scale(1);
                transform: rotate(360deg) scale(1);
            }
        }
    </style>
@endsection

@section('content')
    @php
        $levelname = DB::table('college_year')->get();

    @endphp

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Grade Input</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="/college/teacher/student/collegesystemgrading">System
                                Grading</a></li>
                        <li class="breadcrumb-item active">Grades</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="d-flex flex-row justify-content-end" id="term_container">
            {{-- <div>
                <button class="btn btn-sm  btn-success ml-2 term_buttons" data-term="Prelim">Prelim</button>
            </div>
            <div>
                <button class="btn btn-sm  btn-success ml-2 term_buttons" data-term="Midterm">Midterm</button>
            </div>
            <div>
                <button class="btn btn-sm  btn-success ml-2 term_buttons" data-term="Pre-Final">Pre-Final</button>
            </div>
            <div>
                <button class="btn btn-sm  btn-success ml-2 mr-2 term_buttons" data-term="Final">Final</button>
            </div> --}}
        </div>
        <div class="info-box shadow">
            {{-- <span class="info-box-icon bg-primary"><i class="fas fa-calendar-check"></i></span> --}}
            <div class="info-box-content">
                <div class="row" style="font-size:.7rem">
                    <div class="col-md-2">
                        <label for="" class="mb-0 p-0"><i class="fa fa-book"></i> Teacher</label>
                        <p class="mb-0" id="teacherName"></p>
                    </div>
                    <div class="col-md-3">
                        <label for="" class="mb-0 p-0"><i class="fa fa-book"></i> Subject</label>
                        <p class="mb-0" id="subjectDesc"></p>
                    </div>
                    <div class="col-md-2">
                        <label for="" class="mb-0 p-0"><i class="fa fa-book"></i> Level</label>
                        <p class="mb-0" id="levelName"></p>
                    </div>
                    <div class="col-md-3">
                        <label for="" class="mb-0 p-0"><i class="fa fa-book"></i> Section</label>
                        <p class="mb-0" id="sectionName"></p>
                    </div>
                </div>
                <div style="font-size: .7rem" class="mt-2">
                    <div>Press the Following Buttons to Mark Student/s:</div>
                    <div><span class="font-weight-bold text-warning">I</span> - INC/Incomplete</div>
                    <div><span class="font-weight-bold text-danger">D</span> - DRP/DROPPED</div>
                </div>
            </div>
            
        </div>
        
        <div class="info-box shadow mt-2 row mx-1" id="ecr_table_container" style="height: 500px!important;max-height: 1000px!important; overflow-y: scroll" >
            
        </div>
        <div width="100%" class="info-box shadow mt-2 row mx-1 py-4 d-flex flex-row justify-content-between">
            
            <button class="btn btn-success btn-sm d-none ml-auto" id="save_grades">Save Grades</button>
            <button class="btn btn-primary btn-sm ml-2 d-none" id="submit_grades">Submit Grades</button>
        </div>
       
    </section>

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
    
@endsection

@section('footerscript')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    {{-- <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script> --}}
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    {{-- <script src="https://cdn.datatables.net/rowgroup/1.1.0/js/dataTables.rowGroup.min.js"></script> --}}


    <script>
        $(document).ready(function() {
            var subjectid = @json($subjectid);
            var sectionid = @json($sectionid);
            var syid = @json($syid);
            var semid = @json($semid);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            });

            get_scheddetails();

            function get_scheddetails() {
                
                $.ajax({
                    type: 'get',
                    url: '/college/teacher/student/systemgrades/getsched',
                    data: {
                        subjectid: subjectid,
                        syid: syid,
                        semid: semid,
                        sectionid: sectionid
                    },
                    success: function(response) {
                        $('#teacherName').text(response.lastname + ', ' + response.firstname);
                        $('#subjectDesc').text(response.subjCode + ' - ' + response.subjDesc);
                        $('#levelName').text(response.levelname);
                        $('#sectionName').text(response.sectionDesc);
                        get_ecr_template()
                    }
                })
            }

            function disable_ecr() {
                $('.score').removeAttr('contenteditable');
                $('.highest_score').removeAttr('contenteditable');
                $('.date_time').removeAttr('contenteditable');

            }

            var syid = @json($syid);
            var semid = @json($semid);

            function get_ecr_template() {
                $.ajax({
                    type: 'get',
                    url: '/college/teacher/student/systemgrades/getecr',
                    data: {
                        subjectid: subjectid,
                        sectionid: sectionid,
                        syid: syid,
                        semid: semid

                    },
                    success: function(response) {
                        $('#ecr_table_container').html(response);
                        disable_ecr()
                    }
                })
            }
            terms_applied()

            function terms_applied() {
                $.ajax({
                    type: 'get',
                    url: '/college/teacher/student/systemgrades/getterms',
                    data: {
                        syid: syid,
                        semid: semid,
                        subjectid: subjectid,
                        sectionid: sectionid
                    },
                    success(response) {
                        $.each(response, function(a, term) {
                            $('#term_container').append(
                                `
                                    <div>
                                        <button class="btn btn-sm  btn-success ml-2 term_buttons" data-term="${term.quarter}">${term.description}</button>
                                    </div>
                                `
                            )
                        })
                    }
                })
            }

            var term;
            $(document).on('click', '.term_buttons', function() {
                if (changes == 1) {
                    Swal.fire({
                        type: 'warning',
                        title: 'Please save your changes before changing term'
                    })
                } else {
                    $('[data-stud-id]').removeClass('bg-success');
                    $('[data-stud-id]').removeClass('bg-info');
                    $('[data-stud-id]').removeClass('bg-warning');
                    $('[data-stud-id]').removeClass('bg-primary');
                    $('#gradeRibbon').remove();
                    $('.checkbox').removeAttr('disabled')
                    term = $(this).attr('data-term');
                    $('.term_buttons').removeClass('btn-outline-success').addClass('btn-success');
                    $('.term_buttons[data-term=' + term + ']').removeClass('btn-success').addClass(
                        'btn-outline-success');
                    $('.date_time').text('').trigger('change')
                    $('.highest_score').text(0)
                    // $('.score').trigger('input')
                    $('.score').text(0);
                    $('.gen_average').text(0);
                    $('.total_score').text(0);
                    $('.total_average').text(0);
                    $('.average_score').text(0);
        

                    $('.score').attr('contenteditable', 'true');
                    $('.highest_score').attr('contenteditable', 'true');
                    $('.date_time').attr('contenteditable', 'true');
                    $('#save_grades').removeClass('d-none');
                    $('#submit_grades').removeClass('d-none');
                    $('.gender_checkbox').prop('checked', true).trigger('change');
                    $('.total_average').removeClass('bg-warning');

                    display_term_grading()
                }
               

            })
    



            var show_dropped = 0
            function display_term_grading() {
                $('#modal-overlay').modal('show');

                $.ajax({
                    type: 'get',
                    url: '/college/teacher/student/systemgrades/get_grades',
                    data: {
                        subjectid: subjectid,
                        sectionid: sectionid,
                        term: term
                    },
                    success: function(grades) {
                        $('[data-stud-id]').removeClass('bg-success');
                        $('[data-stud-id]').removeClass('bg-info');
                        $('[data-stud-id]').removeClass('bg-warning');
                        $('[data-stud-id]').removeClass('bg-primary');
                        
                        $.each(grades.highest_scores, function(a, high_score) {

                            if (high_score.subcomponent_id != 0) {
                                $('.highest_score[data-sort-id=' + high_score.column_number +
                                        '][data-comp-id=' + high_score.component_id +
                                        '][data-sub-id=' + high_score.subcomponent_id + ']')
                                    .text(high_score.score);
                                $('.date_time[data-sort-id=' + high_score.column_number +
                                        '][data-comp-id=' + high_score.component_id +
                                        '][data-sub-id=' + high_score.subcomponent_id + ']')
                                    .text(high_score.date).trigger('change');
                            } else {
                                $('.highest_score[data-sort-id=' + high_score.column_number +
                                    '][data-comp-id=' + high_score.component_id + ']').text(
                                    high_score.score);
                                $('.date_time[data-sort-id=' + high_score.column_number +
                                    '][data-comp-id=' + high_score.component_id + ']').text(
                                    high_score.date).trigger('change');
                            }
                        })
                        $.each(grades.grade_scores, function(a, grade) {
                            if (grade.subcomponent_id != 0) {
                                if (grade.score === 'INC') {
                                    $('.scores[data-sort-id=' + grade.column_number +
                                            '][data-comp-id=' + grade.componentid +
                                            '][data-sub-id=' + grade.subcomponent_id +
                                            '][data-stud-id=' + grade.studid + ']').text('I')
                                        .trigger('input')
                                } else if (grade.score === 'DRP') {
                                    show_dropped = 1
                                    $('.scores[data-sort-id=' + grade.column_number +
                                            '][data-comp-id=' + grade.componentid +
                                            '][data-sub-id=' + grade.subcomponent_id +
                                            '][data-stud-id=' + grade.studid + ']').text('d')
                                        .trigger('input')
                                }else {
                                    $('.scores[data-sort-id=' + grade.column_number +
                                        '][data-comp-id=' + grade.componentid +
                                        '][data-sub-id=' + grade.subcomponent_id +
                                        '][data-stud-id=' + grade.studid + ']').text(grade
                                        .score).trigger('input')
                                }
                            } else {
                                if (grade.score === 'INC') {
                                    $('.scores[data-sort-id=' + grade.column_number +
                                            '][data-comp-id=' + grade.componentid +
                                            '][data-stud-id=' + grade.studid + ']').text('I')
                                        .trigger('input')
                                }else if(grade.score === 'DRP') {
                                    show_dropped = 1
                                    $('.scores[data-sort-id=' + grade.column_number +
                                            '][data-comp-id=' + grade.componentid +
                                            '][data-stud-id=' + grade.studid + ']').text('d')
                                            .trigger('input')

                                }else {
                                    $('.scores[data-sort-id=' + grade.column_number +
                                        '][data-comp-id=' + grade.componentid +
                                        '][data-stud-id=' + grade.studid + ']').text(grade
                                        .score).trigger('input')
                                }
                            }
                        })


                        var studCount = $('.student').length;
                        let statusBool = grades.grade_status.every(status => status.status_flag > 0 && (status.status_flag !== 6 && status.status_flag !== 8));
                        let statusPosted = grades.grade_status.every(status => status.status_flag == 5);
                        let statusSubmitted = grades.grade_status.every(status => status.status_flag == 1);
                        let statusApproved = grades.grade_status.every(status => status.status_flag == 2);
                        if (statusBool && grades.grade_status.length > 0 && grades.grade_status.length == studCount && statusSubmitted) {
                            $('#ecr_table_container').append('<div class="ribbon-wrapper ribbon-lg" id="gradeRibbon" style="z-index: 101"><div class="ribbon bg-success" id="gradeRibbonMessage">SUBMITTED</div></div>')
                            $('.highest_score').removeAttr('contenteditable');
                            $('#save_grades').addClass('d-none');
                            $('#submit_grades').addClass('d-none');
                        } else if (statusBool && grades.grade_status.length > 0 && grades.grade_status.length == studCount && statusPosted) {
                            $('#ecr_table_container').append('<div class="ribbon-wrapper ribbon-lg" id="gradeRibbon" style="z-index: 101"><div class="ribbon bg-info" id="gradeRibbonMessage">POSTED</div></div>')
                            $('.highest_score').removeAttr('contenteditable');
                            $('#save_grades').addClass('d-none');
                            $('#submit_grades').addClass('d-none');
                        }else if (statusBool && grades.grade_status.length > 0 && grades.grade_status.length == studCount && statusApproved) {
                            $('#ecr_table_container').append('<div class="ribbon-wrapper ribbon-lg" id="gradeRibbon" style="z-index: 101"><div class="ribbon bg-primary" id="gradeRibbonMessage">Approved</div></div>')
                            $('.highest_score').removeAttr('contenteditable');
                            $('#save_grades').addClass('d-none');
                            $('#submit_grades').addClass('d-none');
                        }else {
                            $('#gradeRibbon').remove();
                            $('.scores').attr('contenteditable', 'true');
                            $('.highest_score').attr('contenteditable', 'true');
                            $('.date_time').attr('contenteditable', 'true');
                        }

                        $.each(grades.grade_status, function(a, status) {
                            if (status.status_flag > 0 && (status.status_flag != 8 && status.status_flag != 6)) {
                                $('[data-stud-id=' + status.studid + ']').removeClass(
                                    'bg-warning')
                                $('[data-stud-id=' + status.studid + ']').addClass('bg-success')
                                    .removeAttr('contenteditable');
                                $('.checkbox[data-stud-id=' + status.studid + ']').attr(
                                    'disabled', true).prop('checked', false);
                            }
                            
                            if (status.status_flag == 6) {
                                $('[data-stud-id=' + status.studid + ']').removeClass('bg-success')
                                $('[data-stud-id=' + status.studid + ']').removeClass('bg-info')
                                $('[data-stud-id=' + status.studid + ']').removeClass('bg-primary')
                                $('[data-stud-id=' + status.studid + ']').addClass('bg-warning')
                            }else if (status.status_flag == 8) {
                                $('[data-stud-id=' + status.studid + ']').removeClass('bg-success')
                                $('[data-stud-id=' + status.studid + ']').removeClass('bg-info')
                                $('[data-stud-id=' + status.studid + ']').removeClass('bg-primary')
                                $('[data-stud-id=' + status.studid + ']').addClass('bg-danger')
                            }else if(status.status_flag == 5){
                                $('[data-stud-id=' + status.studid + ']').removeClass('bg-success')
                                $('[data-stud-id=' + status.studid + ']').removeClass('bg-primary')
                                $('[data-stud-id=' + status.studid + ']').addClass('bg-info')
                            }else if(status.status_flag == 2){
                                $('[data-stud-id=' + status.studid + ']').removeClass('bg-success')
                                $('[data-stud-id=' + status.studid + ']').removeClass('bg-info')
                                $('[data-stud-id=' + status.studid + ']').addClass('bg-primary')
                            }
                        })

                        dropped_students()

                        
                    }
                })
            }

            function dropped_students(){
                $.each($('.stud_row'), function() {

                    
                    var studid = $(this).attr('data-stud-id')
                    
                    $.ajax({
                        type: 'post',
                        url: '/college/teacher/student/systemgrades/checked_dropped',
                        data: {
                            studid: studid,
                            subjectid: subjectid,
                            sectionid: sectionid,
                            term: term,
                            syid: syid,
                            semid: semid
                        },
                        success: function(data) {
                            if(data == 'dropped'){
                                $('.scores[data-stud-id=' + studid + ']').addClass('bg-danger').removeAttr('contenteditable');
                                $('.stud_row[data-stud-id=' + studid + ']').addClass('bg-danger').removeAttr('contenteditable');
                                $('.total_score[data-stud-id=' + studid + ']').addClass('bg-danger').removeAttr('contenteditable');
                                $('.total_average[data-stud-id=' + studid + ']').addClass('bg-danger').removeAttr('contenteditable');
                                $('.average_score[data-stud-id=' + studid + ']').addClass('bg-danger').removeAttr('contenteditable');
                                $('.gen_average[data-stud-id=' + studid + ']').addClass('bg-danger').removeAttr('contenteditable');
                                $('.checkbox[data-stud-id=' + studid + ']').attr('disabled', true).prop('checked', false);
                            }

                        }
                    })
                })
                setTimeout(() => {
                    $('#modal-overlay').modal('hide')
                    
                }, 500);

                

            }


            function drop_grades(studid){
                var scores = []
                $.each($('.scores[data-stud-id=' + studid + ']'), function() {
                    var sort = $(this).data('sort-id')
                    var component_id = $(this).data('comp-id')
                    var subid = typeof $(this).data('sub-id') !== 'undefined' ? $(
                        this).data('sub-id') : 0;
                    var score_text = $(this).text()
                    var studid = $(this).data('stud-id')

                    var score = {
                        subjectid: subjectid,
                        sectionid: sectionid,
                        studid: studid,
                        component_id: component_id,
                        subid: subid,
                        syid: syid,
                        semid: semid,
                        score: score_text,
                        term: term,
                        sort: sort
                    }
                    scores.push(score)
                })
                console.log(scores);
                
                $.ajax({
                    type: 'POST',
                    url: '/college/teacher/student/systemgrades/drop_grades',
                    data: {
                        subjectid: subjectid,
                        sectionid: sectionid,
                        term: term,
                        studid: studid,
                        syid: syid,
                        semid: semid,
                        scores: scores
                    },
                    success: function(data) {
                        display_term_grading()
                    }
                })
            }

            function drop_grades_warning(studid, droppedCell){ 
                if(show_dropped == 0) {
                    Swal.fire({
                        title: 'Drop Student?',
                        text: 'Are you sure you want to drop this Student?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, drop Student!'
                    }).then((result) => {
                        if (result.value) {

                            Swal.fire({
                                title: 'Are you Really Sure?',
                                text: 'This Action Cannot be Reversed!',
                                type: 'warning',  // `type` instead of `icon`
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, Drop Already!',
                                cancelButtonText: 'No! I changed my Mind!',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                onOpen: () => {
                                    setTimeout(() => {
                                        let confirmButton = document.querySelector('.swal2-confirm');

                                        if (!confirmButton) {
                                            console.error("Confirm button not found!");
                                            return;
                                        }

                                        confirmButton.disabled = true; // Disable the button
                                        let timeLeft = 5;
                                        confirmButton.innerText = `Yes, Drop Already! (${timeLeft})`;

                                        let countdown = setInterval(() => {
                                            timeLeft--;
                                            confirmButton.innerText = `Yes, Drop Already! (${timeLeft})`;

                                            if (timeLeft <= 0) {
                                                clearInterval(countdown);
                                                confirmButton.disabled = false; // Enable button
                                                confirmButton.innerText = 'Yes, Drop Already!';
                                            }
                                        }, 1000);
                                    }, 100); // Small delay ensures SweetAlert is fully initialized
                                }
                            }).then((result) => {
                                if (result.value) {  // `.value` instead of `.isConfirmed`
                                    drop_grades(studid)
                                    droppedCell.addClass('bg-danger');
                                    $(`.score[data-stud-id=${studid}]`).addClass('bg-danger');
                                    $(`.average[data-stud-id=${studid}]`).html('DRP').addClass('bg-danger');
                                    $(`.total_score[data-stud-id=${studid}]`).html('DRP').addClass('bg-danger');
                                    $(`.gen_average[data-stud-id=${studid}]`).html('DRP').addClass('bg-danger');
                                    $(`.average_score[data-stud-id=${studid}]`).html('DRP').addClass('bg-danger');
                                    $(`.total_average[data-stud-id=${studid}]`).html('DRP').addClass('bg-danger');
                                    $(`[data-stud-id=${studid}]`).removeAttr('contenteditable');
                                    $(`.checkbox[data-stud-id=${studid}]`).attr('disabled', true).prop('checked', false);
                                }else{
                                    droppedCell.html('0');
                                }
                            });


                        }else{
                            droppedCell.html('0');
                        }
                    })
                }
                show_dropped = 0
                
                
            
            }

            var total_score = 0;
            var total_highest_score = 0;
            var changes = 0;
            $(document).on('input','.score',function(){
                var studid = $(this).attr('data-stud-id');
                var compid = $(this).attr('data-comp-id');
                var subid = $(this).attr('data-sub-id');
                var sort = $(this).attr('data-sort-id');
             
                if($(this).text() === 'i' || $(this).text() === 'I'){
                    $(this).text('INC')
                }else if($(this).text() === 'd' || $(this).text() === 'D'){
                    var thisCell =  $(this)
                    $(this).text('DRP')
                    
                    drop_grades_warning(studid, thisCell)

                }
                else{
                    let text = $(this).text().replace(/[^0-9]/g, "");
                    $(this).text(text);
                }


                let range = document.createRange();
                let sel = window.getSelection();
                range.selectNodeContents(this);
                range.collapse(false);
                sel.removeAllRanges();
                sel.addRange(range);


                

                
                    
                if($(this).text().trim() === 'INC'){
                    $('.score[data-stud-id=' + studid + ']').removeClass('bg-warning')
                    $(this).addClass('bg-warning').attr('data-inc', '1')

                    $('.total_average[data-stud-id=' + studid + ']').html('INC').addClass('bg-warning');

                } else if (subid) {
                    $(this).removeClass('bg-warning')
                    $('.total_average[data-stud-id=' + studid + ']').removeClass('bg-warning')
                    $('.total_score[data-stud-id=' + studid + ']').removeClass('bg-warning')
                    $('.average_score[data-stud-id=' + studid + ']').removeClass('bg-warning')
                    $('.gen_average[data-stud-id=' + studid + ']').removeClass('bg-warning')
                    $('.scores[data-stud-id=' + studid + ']').not('[data-inc]').removeClass('bg-warning')

                    var high_sort = parseInt($('.highest_score[data-sort-id=' + sort + '][data-sub-id=' +
                        subid + ']').text());
                    if (parseInt($(this).text()) > high_sort) {
                        $(this).text(high_sort)
                    }

                    total_score = 0
                    $('.scores[data-stud-id=' + studid + '][data-sub-id=' + subid + ']').each(function() {
                        total_score += parseInt($(this).text()) || 0;
                    });
                    $('.total_score[data-stud-id=' + studid + '][data-sub-id=' + subid + ']').html(
                        total_score);
                    total_highest_score = 0
                    $('.highest_score[data-sub-id=' + subid + ']').each(function() {
                        total_highest_score += parseInt($(this).text()) || 0;
                    });
                    var average = isNaN(parseFloat((total_score / total_highest_score) * 100)) ? 0 :
                        parseFloat((total_score / total_highest_score) * 100).toFixed(2)

                    $('.average_score[data-stud-id=' + studid + '][data-sub-id=' + subid + ']').html(
                        average, '%')
                    var gen_average = 0;

                    $('.average_score[data-stud-id=' + studid + '][data-comp-id=' + compid + ']').each(
                        function() {
                            var score = parseFloat($(this).text()) || 0;
                            var percentage = parseFloat($(this).attr('data-percentage')) || 0;

                            gen_average += (score * (percentage / 100));
                        });

                    $('.gen_average[data-stud-id=' + studid + '][data-comp-id=' + compid + ']').html(
                        gen_average.toFixed(2));


                } else {
                    var high_sort = parseInt($('.highest_score[data-sort-id=' + sort + '][data-comp-id=' +
                        compid + ']').text());
                    if (parseInt($(this).text()) > high_sort) {
                        $(this).text(high_sort)
                    }

                    total_score = 0
                    $('.scores[data-stud-id=' + studid + '][data-comp-id=' + compid + ']').each(function() {
                        total_score += parseInt($(this).text()) || 0;
                    });
                    $('.total_score[data-stud-id=' + studid + '][data-comp-id=' + compid + ']').html(
                        total_score);
                    total_highest_score = 0
                    $('.highest_score[data-comp-id=' + compid + ']').each(function() {
                        total_highest_score += parseInt($(this).text()) || 0;
                    });
                    var average = isNaN(total_score / total_highest_score) ? 0 : parseFloat((total_score /
                        total_highest_score) * 100).toFixed(2);

                    $('.average_score[data-stud-id=' + studid + '][data-comp-id=' + compid + ']').html(
                        average, '%')
                    var gen_average = 0
                    $('.average_score[data-stud-id=' + studid + '][data-comp-id=' + compid + ']').each(
                        function() {
                            gen_average += parseFloat($(this).text()) || 0;
                        })
                    gen_average = isNaN(gen_average) ? 0 : parseFloat(gen_average / $(
                            '.average_score[data-stud-id=' + studid + '][data-comp-id=' + compid + ']')
                        .length).toFixed(2)
                    $('.gen_average[data-stud-id=' + studid + '][data-comp-id=' + compid + ']').html(
                        gen_average);
                }

                var hasINC = false;
                $('.scores[data-stud-id=' + studid + ']').each(function() {
                    if ($(this).text().trim() === 'INC') {
                        hasINC = true;
                        return false; // break the loop
                    }
                });


                if (hasINC) {
                    $('.total_average[data-stud-id=' + studid + ']').html('INC').addClass('bg-warning');
                } else {
                    var total_average = 0;
                    $('.gen_average[data-stud-id=' + studid + ']').each(function() {
                        var gen_ave = $(this).text();
                        var percentage = parseFloat($(this).attr('data-percentage')) || 0;

                        total_average += (gen_ave * (percentage / 100));
                        
                    });
                    if(total_average < 60){
                            total_average = 60
                        }
                    $('.total_average[data-stud-id=' + studid + ']').html(total_average.toFixed(2));
                }

            })


            $(document).on('click', '#save_grades', function() {
                if (!term) {
                    Toast.fire({
                        type: 'error',
                        title: 'Please select a term'
                    })
                } else {
                    var highest_scores = []
                    var scores = []
                    var term_averages = []

                    $.each($('.highest_score'), function() {
                        var sort = $(this).data('sort-id')
                        var component_id = $(this).data('comp-id')
                        var subid = typeof $(this).data('sub-id') !== 'undefined' ? $(this).data(
                            'sub-id') : 0;
                        var highest_score_text = $(this).text()
                        if (subid == 0) {
                            var date = $('.date_time[data-sort-id=' + sort + '][data-comp-id=' +
                                component_id + ']').text();
                            if (date == 'MM/DD/YYYY') {
                                date = ''
                            }

                        } else {
                            var date = $('.date_time[data-sort-id=' + sort + '][data-sub-id=' +
                                subid + ']').text();
                            if (date == 'MM/DD/YYYY') {
                                date = ''
                            }
                        }

                        var highest_score = {
                            subjectid: subjectid,
                            sectionid: sectionid,
                            component_id: component_id,
                            subid: subid,
                            syid: syid,
                            semid: semid,
                            highest_score: highest_score_text,
                            term: term,
                            date: date,
                            sort: sort
                        }
                        highest_scores.push(highest_score)
                    })
                    $.each($('.checkbox:checked'), function() {
                        var studid = $(this).data('stud-id')
                        $.each($('.total_average[data-stud-id=' + studid + ']'), function() {
                            var term_average = $(this).text()
                            var averages = {
                                term_average: term_average,
                                studid: studid,
                                subjectid: subjectid,
                                sectionid: sectionid,
                                syid: syid,
                                semid: semid,
                                term: term
                            }

                            term_averages.push(averages)
                        })
                        $.each($('.scores[data-stud-id=' + studid + ']'), function() {
                            var sort = $(this).data('sort-id')
                            var component_id = $(this).data('comp-id')
                            var subid = typeof $(this).data('sub-id') !== 'undefined' ? $(
                                this).data('sub-id') : 0;
                            var score_text = $(this).text()
                            var studid = $(this).data('stud-id')

                            var score = {
                                subjectid: subjectid,
                                sectionid: sectionid,
                                studid: studid,
                                component_id: component_id,
                                subid: subid,
                                syid: syid,
                                semid: semid,
                                score: score_text,
                                term: term,
                                sort: sort
                            }
                            scores.push(score)
                        })

                    })
                    if($('.checkbox:checked').length === 0) {
                            Toast.fire({
                                type: 'info',
                                title: 'No student selected'
                            })
                    }else{
                        $('#modal-overlay').modal('show');

                        $.ajax({
                            type: 'POST',
                            url: '/college/teacher/student/systemgrades/savegrades',
                            data: {
                                highest_scores: JSON.stringify(highest_scores),
                                scores: JSON.stringify(scores),
                                term_averages: JSON.stringify(term_averages)
                            },
                            success: function(response) {
                                if(response == 'No Grade Point Scale Setup'){
                                    Toast.fire({
                                        type: 'error',
                                        title: 'No Grade Point Scale Setup'
                                    })
                                }else{
                                    $('#modal-overlay').modal('hide');

                                    Toast.fire({
                                        type: 'success',
                                        title: 'Grades successfully saved'
                                    })
                                    changes = 0
                                }
                                
                            }
                        })
                    }
                }

            })

            $(document).on('click', '#submit_grades', function() {
                if (changes != 0) {
                    Swal.fire({
                        type: 'warning',
                        title: 'Please save your changes before submitting'
                    })
                } else {
                    var scores = []
                    var students = []
                    $.each($('.checkbox:checked'), function() {
                        var studid = $(this).data('stud-id')
                        $.each($('.total_average[data-stud-id=' + studid + ']'), function() {
                            var term_average = $(this).text()
                            // console.log(term_average,'asd');

                            var student = {
                                studid: studid,
                                subjectid: subjectid,
                                sectionid: sectionid,
                                syid: syid,
                                semid: semid,
                                term: term,
                                term_average: term_average
                            }

                            students.push(student)
                        })
                        $.each($('.scores[data-stud-id=' + studid + ']'), function() {
                            var sort = $(this).data('sort-id')
                            var component_id = $(this).data('comp-id')
                            var subid = typeof $(this).data('sub-id') !== 'undefined' ? $(
                                this).data('sub-id') : 0;
                            var score_text = $(this).text()
                            var studid = $(this).data('stud-id')

                            var score = {
                                subjectid: subjectid,
                                sectionid: sectionid,
                                syid: syid,
                                semid: semid,
                                studid: studid,
                                component_id: component_id,
                                subid: subid,
                                score: score_text,
                                term: term,
                                sort: sort,
                            }
                            scores.push(score)
                        })

                    })
                    if($('.checkbox:checked').length === 0) {
                            Toast.fire({
                                type: 'info',
                                title: 'No student selected'
                            })
                    }else{
                        $('#modal-overlay').modal('show');
                        $.ajax({
                            type: 'POST',
                            url: '/college/teacher/student/systemgrades/submit_grades',
                            data: {
                                grades: JSON.stringify(scores),
                                students: JSON.stringify(students)
                            },
                            success: function(response) {
                                // $('#modal-overlay').modal('hide');

                                Toast.fire({
                                    type: 'success',
                                    title: 'Grades Submitted Succesfully'
                                })
                                display_term_grading()
                            }
                        })
                    }
                }
            })

            $(document).on('change', '.date_time', function() {
                if ($(this).text() == '') {
                    $(this).text('MM/DD/YYYY')
                }
            })
            $(document).on('input', '.date_time', function(e) {
                var val = $(this).text().replace(/\D/g, ''); 
                if (val.length > 8) val = val.slice(0, 8);
                
                var formatted = val.replace(/^(\d{2})(\d{2})(\d{0,4})$/, '$1/$2/$3');
                
                $(this).text(formatted);

                var range = document.createRange();
                var sel = window.getSelection();
                range.selectNodeContents(this);
                range.collapse(false);
                sel.removeAllRanges();
                sel.addRange(range);
            
            })

            $(document).on('change', '#male_checkbox', function(){

                if ($(this).is(':checked')) {
                    $('.male_checkbox:not([disabled])').prop('checked', true)
                } else {
                    $('.male_checkbox:not([disabled])').prop('checked', false)

                }
            })

            $(document).on('change', '#female_checkbox', function() {

                if ($(this).is(':checked')) {
                    $('.female_checkbox:not([disabled])').prop('checked', true)
                } else {
                    $('.female_checkbox:not([disabled])').prop('checked', false)
                }
            })

            $(document).on('change', '.female_checkbox', function() {
                $('.female_checkbox').each(function() {
                    var count = $('.female_checkbox:checked').length
                    if (count == 0) {
                        $('#female_checkbox').prop('checked', false)
                    }
                })
            })
            $(document).on('change', '.male_checkbox', function() {
                $('.male_checkbox').each(function() {
                    var count = $('.male_checkbox:checked').length
                    if (count == 0) {
                        $('#male_checkbox').prop('checked', false)
                    }
                })
            })

            $(document).on('click', '.score', function() {
                var text = $(this).text()
                var compid = $(this).attr('data-comp-id');
                var subid = $(this).attr('data-sub-id');
                var sort = $(this).attr('data-sort-id');
                if(subid){
                    var highest_score = $('.highest_score[data-sort-id='+sort+'][data-comp-id='+compid+'][data-sub-id='+subid+']').text()
                }else{
                    var highest_score = $('.highest_score[data-sort-id='+sort+'][data-comp-id='+compid+']').text()
                }
                if($(this).hasClass('bg-danger')){
                    Toast.fire({
                        type: 'error',
                        title: 'Student is Already Dropped'
                    })
                }else if(highest_score == 0){
                    Toast.fire({
                        type: 'error',
                        title: 'No highest possible score found'
                    })
                }
            })

            $(document).on('focus', '.score, .highest_score, .date_time', function(){
                if(!term){
                    Toast.fire({
                        type: 'error',
                        title: 'Please select a term'
                    })
                }else{
                   
                    if (!$('#gradeRibbon').length && !$(this).hasClass('bg-success')) {

                        $('.score, .highest_score').each(function(){
                        if($(this).text() == 0 || $(this).text() == ''){
                            $(this).text(0)
                        }
                        })

                        $('.date_time').each(function() {
                            if ($(this).text() == 'MM/DD/YYYY' || $(this).text() == '') {
                                $(this).text('MM/DD/YYYY')
                            }
                        })

                    if($(this).text() == 0 || $(this).text() == '' || $(this).text() == 'MM/DD/YYYY'){
                        $(this).text('')
                    }else{
                        $(this).text(text)
                    }
                       
                    }
                    
                }         
            })


            $(document).on('keydown', '.score, .highest_score, .date_time', function(e) {
                
                if ($(this).text() != 0 && (e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)) {
                    changes = 1
                }
            })

            

            $(document).on('keydown', '.score', function(e) {
                var td = $(this).closest('td'); // Get current cell

                if (e.keyCode == 37) { // Left Arrow
                    e.preventDefault();

                    var prevTd = td.prevAll('.score').first(); // Get previous <td> with .score

                    if (prevTd.length) {
                        prevTd.focus(); // Focus directly since <td> is .score
                    } 
                } else if (e.keyCode == 39) { // Right Arrow
                    e.preventDefault();

                    var nextTd = td.nextAll('.score').first(); // Get next <td> with .score

                    if (nextTd.length) {
                        nextTd.focus();
                    } 
                } else if (e.keyCode == 38) { // Up Arrow
                    e.preventDefault();

                    var idx = td.index(); // Get column index
                    var prevRow = td.closest('tr').prev(); // Get previous row
                    var prevTd = prevRow.find('td').eq(idx); // Get the same column in previous row

                    if (prevTd.hasClass('score')) {
                        prevTd.focus();
                    }
                } else if (e.keyCode == 40) { // Down Arrow
                    e.preventDefault();

                    var idx = td.index();
                    var nextRow = td.closest('tr').next();
                    var nextTd = nextRow.find('td').eq(idx);

                    if (nextTd.hasClass('score')) {
                        nextTd.focus();
                    }
                }
            });

            $(document).on('keydown', '.highest_score', function(e) {
                var th = $(this).closest('th'); // Get current cell

                if (e.keyCode == 37) { // Left Arrow
                    e.preventDefault();

                    var prevTd = th.prevAll('.highest_score').first(); // Get previous <td> with .highest_score

                    if (prevTd.length) {
                        prevTd.focus(); // Focus directly since <td> is .highest_score
                    }else{
                        console.log('test');
                    }
                } else if (e.keyCode == 39) { // Right Arrow
                    e.preventDefault();

                    var nextTd = th.nextAll('.highest_score').first(); // Get next <td> with .highest_score

                    if (nextTd.length) {
                        nextTd.focus();
                    }
                } else if (e.keyCode == 38) { // Up Arrow
                    e.preventDefault();

                    var idx = th.index(); // Get column index
                    var prevRow = th.closest('tr').prev(); // Get previous row
                    var prevTd = prevRow.find('th').eq(idx); // Get the same column in previous row

                    if (prevTd.hasClass('highest_score')) {
                        prevTd.focus();
                    }
                } else if (e.keyCode == 40) { // Down Arrow
                    e.preventDefault();

                    var idx = th.index();
                    var nextRow = th.closest('tr').next();
                    var nextTd = nextRow.find('th').eq(idx);

                    if (nextTd.hasClass('highest_score')) {
                        nextTd.focus();
                    }
                }
            });

            $(document).on('keydown', '.date_time', function(e) {
                var th = $(this).closest('th'); // Get current cell

                if (e.keyCode == 37) { // Left Arrow
                    e.preventDefault();

                    var prevTd = th.prevAll('.date_time').first(); // Get previous <td> with .highest_score

                    if (prevTd.length) {
                        prevTd.focus(); // Focus directly since <td> is .highest_score
                    }else{
                        console.log('test');
                    }
                } else if (e.keyCode == 39) { // Right Arrow
                    e.preventDefault();

                    var nextTd = th.nextAll('.date_time').first(); // Get next <td> with .highest_score

                    if (nextTd.length) {
                        nextTd.focus();
                    }
                } else if (e.keyCode == 38) { // Up Arrow
                    e.preventDefault();

                    var idx = th.index(); // Get column index
                    var prevRow = th.closest('tr').prev(); // Get previous row
                    var prevTd = prevRow.find('th').eq(idx); // Get the same column in previous row

                    if (prevTd.hasClass('date_time')) {
                        prevTd.focus();
                    }
                } else if (e.keyCode == 40) { // Down Arrow
                    e.preventDefault();

                    var idx = th.index();
                    var nextRow = th.closest('tr').next();
                    var nextTd = nextRow.find('th').eq(idx);

                    if (nextTd.hasClass('date_time')) {
                        nextTd.focus();
                    }
                }
            });


            


        })
    </script>
@endsection
