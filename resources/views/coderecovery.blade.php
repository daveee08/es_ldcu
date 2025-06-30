@extends('layouts.app')

@section('headerscript')
    <script type="text/javascript" src="{{ asset('assets/scripts/jquery-3.3.1.min.js') }}"></script>

    <style>
        fieldset.scheduler-border {
            border: 2px groove #ddd !important;
            padding: 0 1.4em 1.4em 1.4em !important;
            margin: 0 0 1.5em 0 !important;
            background-color: #fbfbfb;
            min-height: 400px
        }

        legend.scheduler-border {
            font-size: 1.2em !important;
            font-weight: bold !important;
            text-align: left !important;
            width: auto;
            padding: 0 10px;
            border-bottom: none;
            background-color: #fbfbfb
        }

        .center-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endsection

@section('content')
    <section class="fxt-template-animation fxt-template-layout20 m-0">
        <div class="container center-container">
            <div class="card" style="width: 100%; max-width: 500px;">
                <div class="card-header">
                    <h3 class="card-title mb-0">USERNAME / PASSWORD RECOVERY</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label><strong>FIRST NAME</strong></label>
                        <input autocomplete="off" id="fname" class="form-control" placeholder="FIRST NAME"
                            onkeyup="this.value = this.value.toUpperCase();">
                    </div>
                    <div class="form-group">
                        <label><strong>LAST NAME</strong></label>
                        <input autocomplete="off" id="lname" class="form-control" placeholder="LAST NAME"
                            onkeyup="this.value = this.value.toUpperCase();">
                    </div>
                    <div class="form-group" id="answerholder">
                        <label><strong>BIRTH DATE</strong></label>
                        <input autocomplete="off" type="date" id="answer" name="answer" class="form-control">
                    </div>
                    <button class="btn btn-success" id="recover">GET CREDENTIALS</button>
                    <a class="btn btn-success" href="/login">LOGIN TO PORTAL</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footerscript')
    <script src="{{ asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            var get = true;

            $(document).on('click', '#recover', function() {
                if (!get) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Please reload the page'
                    }).then(function() {
                        location.reload();
                    });
                    return;
                }

                $('.with_contact').attr('hidden', 'hidden');

                Swal.fire({
                    title: 'Wait',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    type: 'GET',
                    url: '/proccess/recoverycode',
                    data: {
                        a: $("#fname").val(),
                        b: $("#lname").val(),
                        c: 4,
                        d: $("#answer").val()
                    },
                    success: function(data) {
                        Swal.close(); // Close loading
                        if (data[0].sid != 'Not Found') {
                            $('.with_contact').removeAttr('hidden');
                            $('#sid').text(data[0].sid);
                            $('#message').text(data[0].message);
                            $('#student_contact').text(data[0].scontactno);
                            $('#parent_contact').text(data[0].pcontactno);

                            if (data[0].email_sent) {
                                get = false; // Only set to false if email was sent
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Your credentials have been sent to your email.',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'No User was Found',
                                    text: 'No user is registered for this credentials.'
                                });
                            }
                        } else {
                            $('#sid').text(data[0].sid);
                            $('#message').text(data[0].message);
                            // Do NOT set get = false here
                            Swal.fire({
                                icon: 'error',
                                title: 'Not Found',
                                text: data[0].message
                            });
                        }
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Something went wrong'
                        });
                    }
                });
            });
        });

        var keysPressed = {};
        document.addEventListener('keydown', (event) => {
            keysPressed[event.key] = true;
            if (keysPressed['p'] && event.key == 'v') {
                Swal.fire({
                    icon: 'info',
                    title: 'Date Version: 10282023'
                });
            }
        });
    </script>
@endsection
