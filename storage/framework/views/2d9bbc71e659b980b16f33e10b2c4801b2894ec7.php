<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>
        <?php if(isset(DB::table('schoolinfo')->first()->abbreviation)): ?>
            <?php echo e(DB::table('schoolinfo')->first()->abbreviation); ?>

        <?php else: ?>
            SCHOOL NAME
        <?php endif; ?>
    </title>

    
    <link href="<?php echo e(asset('assets/css/main.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('assets\css\login.css')); ?>">
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/pace-progress/themes/black/pace-theme-flat-top.css')); ?>">
    <script type="text/javascript" src="<?php echo e(asset('assets/scripts/jquery-3.3.1.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('js/jquery-migrate-1.2.1.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('js/jquery.browser.min.js')); ?>"></script>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/template.main.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/select2/css/select2.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')); ?>">
    <script src="<?php echo e(asset('plugins/sweetalert2/sweetalert2.all.min.js')); ?>"></script>


    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        })

        $(document).on('click', '.submit', function() {
            if ($('#email').val() == "" && $('#password').val() == "") {
                Toast.fire({
                    type: 'warning',
                    title: 'Incorrect Username and Password. Please try again!'
                })
            }
        })
    </script>

    <script>
        if ($(window).width() < 500) {
            $('#repub').text('Protected by Republic Act No. 10173')
        }

        $(document).ready(function() {

            if ($.browser.name != 'chrome') {
                $(document).ready(function() {
                    alert('This page does not support this browser')
                    window.history.back();
                })
            }
        })
    </script>



    <?php echo $__env->yieldContent('headerscript'); ?>


    <?php
        $schoolInfo = DB::table('schoolinfo')->first();

    ?>

    <script src="<?php echo e(asset('plugins/jquery/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/app.js')); ?>"></script>


    <style>
        /* body{
            background-color: <?php echo e($schoolInfo->schoolcolor); ?>;
        } */
        /* #app{
           
                background-image: linear-gradient( 405deg, <?php echo e($schoolInfo->schoolcolor); ?> 13%, #fbfbfb 13%, #fbfbfb 87%, <?php echo e($schoolInfo->schoolcolor); ?> 68% );
           
        }
        .submit{
                  background-color: <?php echo e($schoolInfo->schoolcolor); ?> !important;
            }
        .row{
            margin-right: 0;
            margin-left: 0;
        }

        @media  only screen and (max-width: 500px) {

            .schoolname {
                font-size: 12px !important;
            }
            .schoollogo {
                max-height: 50px !important;
            }
            .schooladd {
                font-size: 12px !important;
            }

            .navbar-brand{
                margin-right:0;
            }

            .navbar-toggler{
                line-height: 2;
                font-size: 1 rem;
                padding: 1px;

            }

        }
        .card-header{
            background-color: <?php echo e($schoolInfo->schoolcolor); ?>;
            color:white
        }
        .btn-success{
            background-color: <?php echo e($schoolInfo->schoolcolor); ?>;
            color:white;
            border-color: <?php echo e($schoolInfo->schoolcolor); ?>;
        } */

        .footer {
            color: white !important;
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 45px;
            line-height: 45px;
            background-color: <?php echo e($schoolInfo->schoolcolor); ?>;
        }

        @media  only screen and (max-width: 600px) {
            .footer {
                color: white !important;
                position: fixed;
                bottom: 0;
                width: 100%;
                height: 45px;
                line-height: 45px;
                background-color: <?php echo e($schoolInfo->schoolcolor); ?>;
                font-size: 10px;
            }
        }

        body {
            /* background-image:url(<?php echo e(url('assets/images/spct/background_spct.png')); ?>);
    background-size: cover;
  background-attachment: fixed; */
            background-color: <?php echo e($schoolInfo->schoolcolor); ?>;
        }

        /* .btn-success:hover {
            color: #fff;
            background-color: <?php echo e($schoolInfo->schoolcolor); ?>;
            border-color: <?php echo e($schoolInfo->schoolcolor); ?>';
        }

        .bg-success-perschool {

            color: #fff !important;
            background-color: <?php echo e($schoolInfo->schoolcolor); ?> !important;
            border-color: <?php echo e($schoolInfo->schoolcolor); ?> !important;

        }

        .appadd {
                white-space: nowrap;
                overflow: hidden;
        }

        .btn-success:focus, .btn-success.focus {
            color: #fff;
            background-color: <?php echo e($schoolInfo->schoolcolor); ?>;
            border-color: <?php echo e($schoolInfo->schoolcolor); ?>;
            box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0);
        } */
    </style>





</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed  pace-primary">
    <div id="app" class=" min-vh-100">
        

        <main>
            <?php echo $__env->yieldContent('content'); ?>

        </main>
        


        <?php echo $__env->make('sweetalert::alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <script src="<?php echo e(asset('plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
        <script src="<?php echo e(asset('dist/js/adminlte.min.js')); ?>"></script>
        <script src="<?php echo e(asset('plugins/pace-progress/pace.min.js')); ?>"></script>


        <!-- jquery-->
        <script src="<?php echo e(asset('plugins/tempotemp/js/jquery-3.5.0.min.js')); ?>"></script>
        <!-- Popper js -->
        <script src="<?php echo e(asset('plugins/tempotemp/js/popper.min.js')); ?>"></script>
        <!-- Bootstrap js -->
        <script src="<?php echo e(asset('plugins/tempotemp/js/bootstrap.min.js')); ?>"></script>
        <!-- Imagesloaded js -->
        <script src="<?php echo e(asset('plugins/tempotemp/js/imagesloaded.pkgd.min.js')); ?>"></script>
        <!-- Validator js -->
        <script src="<?php echo e(asset('plugins/tempotemp/js/validator.min.js')); ?>"></script>
        <!-- Custom Js -->
        <script src="<?php echo e(asset('plugins/tempotemp/js/main.js')); ?>"></script>
        <script src="<?php echo e(asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')); ?>"></script>
        <script src="<?php echo e(asset('plugins/select2/js/select2.full.min.js')); ?>"></script>
        <script src="<?php echo e(asset('plugins/sweetalert2/sweetalert2.all.min.js')); ?>"></script>
        <script>
            // var Toast = Swal.mixin({
            //     toast: true,
            //     position: 'top-end',
            //     showConfirmButton: false,
            //     timer: 2000
            // });
        </script>

        <?php echo $__env->yieldContent('footerscript'); ?>

        








</body>

</html>
<?php /**PATH C:\laragon\www\es_ldcu\resources\views/layouts/app.blade.php ENDPATH**/ ?>