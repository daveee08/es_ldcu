<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="google" content="notranslate">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>Essentiel | Finance</title>

    <!-- Scripts -->
    <script src="<?php echo e(asset('plugins/jquery/jquery.min.js')); ?>"></script>
    <link href="<?php echo e(asset('assets/css/gijgo.min.css')); ?>" rel="stylesheet" />
    <link rel="shortcut icon" href="<?php echo e(asset('assets/ckicon.ico')); ?>" type="image/x-icon" />

    <link rel="stylesheet" href="<?php echo e(asset('plugins/fontawesome-free/css/all.min.css')); ?>">
    <link href="<?php echo e(asset('dist/css/adminlte.css')); ?>" rel="stylesheet">
    
    <link rel="stylesheet"
        href="<?php echo e(asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dist/css/adminlte.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/pace-progress/themes/blue/pace-theme-flat-top.css')); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('plugins/jquery-image-viewer-magnify/css/jquery.magnify.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/jquery-image-viewer-magnify/css/magnify-bezelless-theme.css')); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('plugins/daterangepicker/daterangepicker.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dist/css/fontfamily.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dist/css/ionicons.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dist/css/googleapis-font.css')); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('dist/css/simplePagination.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets\css\sideheaderfooter.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/toastr/toastr.min.css')); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('plugins/jqvmap/jqvmap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/daterangepicker/daterangepicker.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/summernote/summernote-bs4.css')); ?>">
    
    <link rel="stylesheet" href="<?php echo e(asset('plugins/croppie/croppie.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/select2/css/select2.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dist/css/select2-bootstrap4.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')); ?>">
    <!-- dropzonejs -->
    
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="<?php echo e(asset('plugins/ekko-lightbox/ekko-lightbox.css')); ?>">


    <link rel="stylesheet" href="<?php echo e(asset('dist/css/simplePagination.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css')); ?>">

    <?php
    $schoolinfo = DB::table('schoolinfo')->first();
    ?>
    <style>
        .nav-bg {
            background-color: <?php echo $schoolinfo->schoolcolor; ?> !important;
        }

        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active,
        .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
            background-color: <?php echo $schoolinfo->schoolcolor; ?>;
        }

    </style>

    <?php echo $__env->yieldContent('headerjavascript'); ?>
    <?php echo $__env->yieldContent('pagespecificscripts'); ?>
    <?php echo $__env->yieldContent('jsUP'); ?>

    <style>
        .card {
            box-shadow: 0 0 1px rgba(153, 26, 26, 0.08), 0 1px 1px rgba(0,0,0,0.12) !important;
            border: unset !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed" style="height: auto;">
    <div class="wrapper">
        <?php echo $__env->make('finance.layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <?php echo $__env->make('general.queuingactionbutton.qab', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </section>
        </div>

        <script src="<?php echo e(asset('plugins/jquery/jquery.min.js')); ?>"></script>
        <script src="<?php echo e(asset('plugins/jquery-ui/jquery-ui.min.js')); ?>"></script>
        <script src="<?php echo e(asset('plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
        
        <script src="<?php echo e(asset('plugins/daterangepicker/daterangepicker.js')); ?>"></script>
        <script src="<?php echo e(asset('plugins/daterangepicker/moment.min.js')); ?>"></script>
        <script src="<?php echo e(asset('plugins/jquery-knob/jquery.knob.min.js')); ?>"></script>

        <script src="<?php echo e(asset('plugins/daterangepicker/daterangepicker.js')); ?>"></script>
        

        <script src="<?php echo e(asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')); ?>"></script>
        <script src="<?php echo e(asset('dist/js/adminlte.js')); ?>"></script>
        <script src="<?php echo e(asset('dist/js/demo.js')); ?>"></script>
        <script src="<?php echo e(asset('dist/js/select2.full.min.js')); ?>"></script>

        <script src="<?php echo e(asset('dist/js/jquery.simplePagination.js')); ?>"></script>
        <script src="<?php echo e(asset('plugins/datatables/jquery.dataTables.js')); ?>"></script>
        <script src="<?php echo e(asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')); ?>"></script>
        <script src="<?php echo e(asset('plugins/sweetalert2/sweetalert2.all.min.js')); ?>"></script>
        <script src="<?php echo e(asset('plugins/pace-progress/pace.min.js')); ?>"></script>
        <script src="<?php echo e(asset('plugins/jquery-image-viewer-magnify/js/jquery.magnify.min.js')); ?>"></script>
        <script src="<?php echo e(asset('plugins/toastr/toastr.min.js')); ?>"></script>
        <script src="<?php echo e(asset('plugins/moment/moment.min.js')); ?>"></script>
        <script src="<?php echo e(asset('assets/scripts/gijgo.min.js')); ?>"></script>
        <script src="<?php echo e(asset('plugins/croppie/croppie.js')); ?>"></script>
        <!-- dropzonejs -->
        
        <script src="<?php echo e(asset('plugins/ekko-lightbox/ekko-lightbox.min.js')); ?>"></script>
        <!-- Filterizr-->
        <script src="<?php echo e(asset('plugins/filterizr/jquery.filterizr.min.js')); ?>"></script>

        <?php if(DB::table('schoolinfo')->first()->snr == 'ldcu'): ?>
            <script type="module">
                import Chatbot from "https://cdn.jsdelivr.net/npm/flowise-embed/dist/web.js"
                Chatbot.init({
                    chatflowid: "be0a5761-31f0-4b7a-a752-0df8769d1efe",
                    apiHost: "https://flowisechatbot-nxra.onrender.com",
                    chatflowConfig: {
                        // topK: 2
                    },
                    theme: {
                        button: {
                            backgroundColor: "#EC0C8C",
                            right: 20,
                            bottom: 20,
                            size: 48, // small | medium | large | number
                            dragAndDrop: true,
                            iconColor: "white",
                            customIconSrc: "https://raw.githubusercontent.com/itsnothyun/CK-Resources/main/bot2.png",
                        },
                        tooltip: {
                            showTooltip: false,
                            tooltipMessage: 'Hi There! 👋 How can I help you today?',
                            tooltipBackgroundColor: '#EC0C8C',
                            tooltipTextColor: 'white',
                            tooltipFontSize: 16,
                        },
                        chatWindow: {
                            showTitle: true,
                            title: 'CK Bot',
                            titleAvatarSrc: 'https://raw.githubusercontent.com/itsnothyun/CK-Resources/main/Logo%20Designs.png',
                            showAgentMessages: true,
                            welcomeMessage: 'Hello! This is CK Bot, How can I help you?',
                            errorMessage: 'This is a custom error message',
                            backgroundColor: "#ffffff",
                            height: 700,
                            width: 400,
                            fontSize: 16,
                            poweredByTextColor: "#303235",
                            botMessage: {
                                backgroundColor: "#f7f8ff",
                                textColor: "#303235",
                                showAvatar: true,
                                avatarSrc: "https://raw.githubusercontent.com/itsnothyun/CK-Resources/main/bot3.png",
                            },
                            userMessage: {
                                backgroundColor: "#EC0C8C",
                                textColor: "#ffffff",
                                showAvatar: true,
                                avatarSrc: "https://raw.githubusercontent.com/zahidkhawaja/langchain-chat-nextjs/main/public/usericon.png",
                            },
                            textInput: {
                                placeholder: 'Type your question',
                                backgroundColor: '#ffffff',
                                textColor: '#303235',
                                sendButtonColor: '#EC0C8C',
                                maxChars: 50,
                                maxCharsWarningMessage: 'You exceeded the characters limit. Please input less than 50 characters.',
                                autoFocus: true, // If not used, autofocus is disabled on mobile and enabled on desktop. true enables it on both, false disables it on both.
                                sendMessageSound: true,
                                // sendSoundLocation: "send_message.mp3", // If this is not used, the default sound effect will be played if sendSoundMessage is true.
                                receiveMessageSound: true,
                                // receiveSoundLocation: "receive_message.mp3", // If this is not used, the default sound effect will be played if receiveSoundMessage is true.
                            },
                            feedback: {
                                color: '#303235',
                            },
                            footer: {
                                textColor: '#303235',
                                text: 'Powered by',
                                company: 'Flowise',
                                companyLink: 'https://flowiseai.com',
                            }
                        }
                    }
                })
            </script>
        <?php endif; ?>
        <?php
            $schoolinfo = db::table('schoolinfo')->first();
        ?>

        <?php echo $__env->yieldContent('modal'); ?>
        <?php echo $__env->yieldContent('js'); ?>
        <?php echo $__env->yieldContent('footerscripts'); ?>
        <?php echo $__env->yieldContent('footerjavascript'); ?>
        <?php echo $__env->yieldContent('qab_sript'); ?>
        <script>
            var school_setup = <?php echo json_encode($schoolinfo, 15, 512) ?>;

            function get_last_index(tablename) {
                $.ajax({
                    type: 'GET',
                    url: '/monitoring/tablecount',
                    data: {
                        tablename: tablename
                    },
                    success: function(data) {
                        lastindex = data[0].lastindex
                        update_local_table_display(tablename, lastindex)
                    },
                })
            }

            function update_local_table_display(tablename, lastindex) {
                $.ajax({
                    type: 'GET',
                    url: school_setup.es_cloudurl + '/monitoring/table/data',
                    data: {
                        tablename: tablename,
                        tableindex: lastindex
                    },
                    success: function(data) {
                        if (data.length > 0) {
                            process_create(tablename, 0, data)
                        }
                    },
                    error: function() {
                        $('td[data-tablename="' + tablename + '"]')[0].innerHTML = 'Error!'
                    }
                })
            }

            function process_create(tablename, process_count, createdata) {
                if (createdata.length == 0) {
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
                        type: 'success',
                        title: 'Save successfully'
                    })
                    return false;
                }

                var b = createdata[0]
                $.ajax({
                    type: 'GET',
                    url: '/synchornization/insert',
                    data: {
                        tablename: tablename,
                        data: b
                    },
                    success: function(data) {
                        process_count += 1
                        createdata = createdata.filter(x => x.id != b.id)
                        process_create(tablename, process_count, createdata)
                    },
                    error: function() {
                        process_count += 1
                        createdata = createdata.filter(x => x.id != b.id)
                        process_create(tablename, process_count, createdata)
                    }
                })
            }

            $(document).ready(function() {

                setInterval(function() {

                    // $('.pace').css('display','none !important')

                    window.paceOptoins = {
                        ajax: false,
                        restartOnRequestAfter: false,
                    }


                    Pace.ignore(function() {
                        $.ajax({
                            url: '/finance/salaryrateelevation/reloadcount',
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {

                                $('.countsalaryrateelevation').text(data.rateCount)
                                $('.viewolpaycount').text(data.OLpayCount);
                            },
                        });
                    });
                }, 30000);
            })
        </script>

        <script>
            $(document).ready(function() {
                var keysPressed = {}
                document.addEventListener("keydown", function(event) {
                    keysPressed[event.key] = true;
                    if (keysPressed['g'] && (event.key === '1' || event.key === '1')) {
                        window.location = '/changeUser/1'
                    }
                });
                document.addEventListener('keyup', (event) => {
                    keysPressed = {}
                });
            })
        </script>

        <!-- dropzonejs -->
        <script src="<?php echo e(asset('plugins/dropzone/min/dropzone.min.js')); ?>"></script>
        

</body>

</html>



<?php /**PATH C:\laragon\www\es_ldcu\resources\views/finance/layouts/app.blade.php ENDPATH**/ ?>