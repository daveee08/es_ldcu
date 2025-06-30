@extends('teacher.layouts.app')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
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
    <div class="modal fade" id="modal_view_questionnaire" style="display: none;" aria-hidden="true" data-backdrop="static"
        data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" id="modal_1_title">Teacher’s Evaluation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="" class="mb-0">Teacher : </label> <span id="teacher_holder"></span>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="mb-0">Term : </label> <span id="teacher_term"></span>
                        </div>
                    </div>
                    <div class="row  mt-2 pl-2 pr-2">
                        <div class="col-md-12 border">
                            <label for="">Rating Legends:</label>
                            <div class="row" id="rating_holder">

                            </div>
                        </div>
                    </div>
                    <div class="row  mt-2">
                        <div class="col-md-12">
                            <div class="error-container"></div>
                        </div>
                        <div class="col-md-12  table-responsive" style="height: 500px;">
                            <table class=" table table-sm table-bordered" id="questionnaire_holder">

                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary" onclick="submit_answer()">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Grades</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Grades</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content pt-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12">
                    <div class="info-box">
                        <div class="info-box-content">
                            <span class="info-box-number">
                                <div class="row">
                                    <div class="col-md-3 ">
                                        <label for="">School Year</label>
                                        <select class="form-control form-control-sm select2" id="filter_sy">
                                            @php
                                                $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
                                            @endphp
                                            @foreach ($sy as $item)
                                                @php
                                                    $selected = '';
                                                    if ($item->isactive == 1) {
                                                        $selected = 'selected="selected"';
                                                    }
                                                @endphp
                                                <option value="{{ $item->id }}" {{ $selected }}
                                                    value="{{ $item->id }}">{{ $item->sydesc }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Teacher</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Evaluation Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="sched_holder">

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
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        $('#filter_sy').select2()
        $('#filter_sem').select2()
    </script>

    <script>
        function displayMessage(type, message) {
            var messageContainer = $('.error-container');
            messageContainer.empty();

            if (type === 'success') {
                var alert = 'alert-success'
            } else if (type === 'error') {
                var alert = 'alert-danger'
            }

            $('.error-container').append(`<div class="alert ` + alert + ` alert-dismissible fade show" role="alert">
                        ` + message + `
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>`);
        }

        function displayErrors(errors) {
            // Clear previous errors
            $('.error-container').empty();

            // Display new errors
            $.each(errors, function(key, value) {
                $('.error-container').append(`<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        ` + value + `
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>`);
            });
        }

        $('.modal').on('hidden.bs.modal', function() {
            $('.alert').alert('close')
        });

        $('.modal').on('shown.bs.modal', function() {
            $('.alert').alert('close')
        });
    </script>
    <script>
        var all_teachers = []
        var selected_subj = null
        var selected_teacher = null
        var sy_eval_setup_term = null
        var all_question = []
        var with_setup = false;

        // get_answer()

        function update_form(id) {
            var teacher_info = all_teachers.find(x => x.id == id)
            selected_teacher = teacher_info.id

            $('#teacher_holder').text(teacher_info.fullname)
            $('.question_ans').removeAttr('disabled')
            $('.question_ans').val("").change()
            $('.question_ans').val("")
            $('.btn[onclick="submit_answer()"]').removeAttr('disabled')
            $('.question_ans').each(function(a, b) {
                $(this).removeClass('is-invalid')
            })

            var ans_info = all_answer.filter(x => x.teacherid == id)

            if (ans_info.length > 0) {
                var answers = ans_info[0].answer
                console.log("sdfsf")
                $.each(answers, function(a, b) {
                    $('.question_ans[data-id="' + b.teval_qid + '"]').val(b.answer).change()
                    $('.question_ans[data-id="' + b.teval_qid + '"]').val(b.answer)
                })
                $('.question_ans').attr('disabled', 'disabled')
                $('.btn[onclick="submit_answer()"]').attr('disabled', 'disabled')
            }

        }

        function submit_answer() {

            if (!with_setup) {
                Toast.fire({
                    type: 'warning',
                    title: 'No Setup available!'
                })
                return false;
            }

            var all_answered = true;
            $('.question_ans').each(function(a, b) {
                if ($(this).val() == "") {
                    all_answered = false;
                    $(this).addClass('is-invalid')
                } else {
                    $(this).removeClass('is-invalid')
                }
            })

            if (!all_answered) {
                displayMessage('error', 'Please complete form!');
                return false;
            }

            $('.question_ans[data-type="long_answer"]').each(function(a, b) {
                if ($(this).val().length > 255) {
                    displayMessage('error', 'Answer should not exceed 255 characters!');
                    $(this).addClass('is-invalid')
                    all_answered = false
                } else {
                    $(this).removeClass('is-invalid')
                }
            })

            if (!all_answered) {
                return false;
            }

            Swal.fire({
                title: 'Are you sure you want to submit evaluation?',
                text: "You will not be able to edit your answer.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Submit'
            }).then((result) => {
                if (result.value) {
                    var ans = []
                    $('.btn[onclick="submit_answer()"]').attr('disabled', 'disabled')

                    $('.question_ans').each(function(a, b) {
                        ans.push({
                            'teval_qid': $(this).attr('data-id'),
                            'answer': $(this).val()
                        })
                    })

                    $.ajax({
                        type: 'GET',
                        url: '/teval/evaluation/submit/teacher',
                        data: {
                            syid: $('#filter_sy').val(),
                            teacherid: selected_teacher,
                            term: sy_eval_setup_term,
                            answers: ans
                        },
                        success: function(response) {
                            if (response.success) {
                                $('.question_ans').attr('disabled', 'disabled')
                                get_answer()
                                displayMessage('success', response.message);
                            } else {
                                displayMessage('error', response.message);
                            }
                        },
                        error: function(response) {
                            if (response.status === 422) {
                                var errors = response.responseJSON.errors;
                                displayErrors(errors);
                            } else if (response.status === 404) {
                                displayMessage('error', 'Page not found');
                            } else {
                                displayMessage('error', response.responseJSON.message);
                            }
                            $('.btn[onclick="submit_answer()"]').removeAttr('disabled')
                        }
                    })
                }
            })
        }

        var all_answer = []

        function get_answer() {
            $.ajax({
                type: 'GET',
                url: '/teval/evaluation/answer/teacher',
                data: {
                    syid: $('#filter_sy').val(),
                },
                success: function(response) {
                    all_answer = response

                    $('.status_button').each(function(a, b) {
                        var subjid = $(this).attr('data-subj')
                        var teacherid = $(this).attr('data-teacherid')
                        var check = all_answer.filter(x => x.teacherid == teacherid)
                        if (check.length == 0) {
                            if (with_setup) {
                                $(this)[0].innerHTML =
                                    ' <button class="btn btn-primary" style="font-size:.7rem !important" href="#modal_view_questionnaire" role="button" data-toggle="modal" onclick="update_form(' +
                                    teacherid + ')">Evaluate</button>  '
                            } else {
                                $(this)[0].innerHTML = ' No Setup'
                            }

                        } else {
                            $(this)[0].innerHTML = '<span class="badge badge-success">Evaluated</span>'
                        }
                    })

                    $('.status_date').each(function(a, b) {
                        var subjid = $(this).attr('data-subj')
                        var teacherid = $(this).attr('data-teacherid')
                        var check = all_answer.filter(x => x.teacherid == teacherid)
                        if (check.length > 0) {
                            $(this)[0].innerHTML =
                                '<a href="#modal_view_questionnaire" role="button" data-toggle="modal" onclick="update_form(' +
                                check[0].teacherid + ')"><i style="font-size:.8rem !important">' +
                                check[0].createddatetime + '</i></a>'
                        }
                    })
                },
            })
        }
    </script>

    <script>
        $(document).ready(function() {

            $(document).on('change', '#filter_sy', function() {

            })

            $(document).on('change', '#filter_sem', function() {

            })

            var school = @json(DB::table('schoolinfo')->first()->abbreviation)


            get_teachers()

            function get_teachers() {

                $.ajax({
                    type: 'GET',
                    url: '/teacher/profile/list',
                    data: {
                        syid: $('#filter_sy').val()
                    },
                    success: function(data) {
                        // $('#all_sched').append(data)
                        all_teachers = data

                        $.each(all_teachers, function(a, b) {

                            $('#sched_holder').append(`<tr>
                                                        <td class="align-middle">` + b.fullname + `</td>
                                                        <td class="text-center status_button" data-teacherid="` + b
                                .id + `" >
                                                        </td>
                                                        <td class="text-center status_date" data-subj="` + b.id +
                                `" data-teacherid="` + b.id + `">
                                                            
                                                        </td>
                                                    </tr>`)
                        })

                        get_setup()

                    }
                })
            }

            function get_setup(syid) {
                $.ajax({
                    type: 'GET',
                    url: '/teval/sy/list',
                    data: {
                        syid: $('#filter_sy').val(),
                        type: 'teacher'
                    },
                    success: function(response) {
                        if (response.length > 0) {
                            if (response[0].activated == 1) {
                                with_setup = true
                            } else {
                                with_setup = false
                            }
                            sy_eval_setup_term = response[0].term
                            $('#teacher_term').text(sy_eval_setup_term)
                            if (response.length > 0) {
                                get_questions(response[0].teval_id)
                            }
                        } else {
                            with_setup = false
                        }
                        get_answer()
                    }
                })
            }

            function get_questions(teval_id) {

                $.ajax({
                    type: 'GET',
                    url: '/teval/group/list',
                    data: {
                        teval_id: teval_id,
                    },
                    success: function(response) {
                        all_question = response
                        display_questionnaire()

                    }
                })
            }

            function display_questionnaire() {

                $('#rating_holder').empty();

                $.each(all_question, function(a, b) {
                    $('#teval_quetion_holder').append(
                        `<tr>
                        <th class="text-center align-middle">` + b.group_sort + `</th>
                        <th class="align-middle">` + b.description +
                        `</th>
                        <td></td>
                        <th class="text-center"><button class="btn btn-sm btn-primary " href="#modal_teval_question_form" role="button" data-toggle="modal" style="font-size: .8rem !important" onclick="form_teval_question(` +
                        null + `,` + b.id + `)">Add Question</button></th>
                        <td class="align-middle text-center"><a onclick="form_teval_group(` + b.id +
                        `)" href="#modal_teval_qgroup_form" role="button" data-toggle="modal"><i class="far fa-edit text-primary"></i></a></td>
                        <td  class="align-middle text-center"><a href="javascript:void(0)" onclick="delete_teval_group(` + b.id + `)"><i class="far fa-trash-alt text-danger"></i></a></td>
                    </tr>`
                    )

                    $('#questionnaire_holder').append(
                        `<tr>
                        <td width="85%"><b>` + b.description + `</b></td>   
                        <td  width="15%"></td> 
                    </tr>`
                    )


                    var question = b.question

                    if (question.length == 0) {
                        $('#teval_quetion_holder').append(
                            `<tr>
                        <th></th>
                        <th class="text-center align-middle" colspan="5"><a href="#modal_teval_question_form" role="button" data-toggle="modal" onclick="form_teval_question(` +
                            null + `,` + b.id + `)">Click here</a> to add question to ` + b
                            .description + `</th>
                    
                    </tr>`)


                    }

                    $.each(question, function(c, d) {

                        var temp_type = 'MC'
                        if (d.type == 'long_answer') {
                            temp_type = 'LA'
                        }

                        var type_info = '255 Characters';
                        if (d.type == 'multiple_choice') {
                            type_info =
                                '<a href="#modal_teval_question_option_detail" role="button" data-toggle="modal" onclick="list_teval_question_option_detail(' +
                                d.option_id + ')">' + d.option_desc + '</a>'
                        }

                        $('#teval_quetion_holder').append(
                            ` <tr>
                            <td class="text-center" >` + d.sort + `</td>
                            <td class="pl-4">` + d.question_desc + `</td>
                            <td class="text-center">` + temp_type + `</td>
                            <td class="text-center align-middle">` + type_info + `</td>
                            <td class="align-middle text-center"><a onclick="form_teval_question(` + d.id + `,` + b
                            .id +
                            `)" href="#modal_teval_question_form" role="button" data-toggle="modal"><i class="far fa-edit text-primary"></i></a></td>
                            <td class="align-middle text-center"><a href="javascript:void(0)" onclick="delete_teval_question(` +
                            d.id + `)"><i class="far fa-trash-alt text-danger"></i></a></td>
                        </tr>`
                        )


                        if (d.type == 'multiple_choice') {

                            var temp_select =
                                `<select class="form-control form-control-sm question_ans" data-id="` +
                                d.id + `" data-type="multiple_choice">
                                                <option values=""></option>`


                            $.each(d.mc_detail, function(a, b) {
                                temp_select += '<option values="' + b.value + '" >' + b
                                    .display + '</option>'
                            })

                            if ($('#rating_holder').text() == "") {
                                $.each(d.mc_detail, function(a, b) {
                                    $('#rating_holder').append('<div class="col-md-4">' + b
                                        .display + ' - ' + b.desc + '</div>')
                                })
                            }

                            temp_select += '</select>'

                            $('#questionnaire_holder').append(
                                ` <tr>
                                <td class="pl-4">
                                   ` + d.question_desc + `
                                </td>
                                <td class="align-middle">
                                    ` + temp_select + `
                                </td>
                            </tr>`
                            )
                        } else {
                            $('#questionnaire_holder').append(
                                ` <tr>
                                <td  colspan="2" class="pl-4">
                                    <p class="mb-0">` + d.question_desc + `</p>
                                    <textarea class="form-control question_ans" row="3" data-id="` + d.id + `" data-type="long_answer"></textarea>
                                </td>
                            </tr>`
                            )
                        }

                    })


                })
            }

        })
    </script>
@endsection
