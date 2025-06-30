<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Administrator Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php
        $schoolinfo = DB::table('schoolinfo')->first();
    ?>

    <link rel="stylesheet" href="<?php echo e(asset('plugins/fontawesome-free/css/all.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/pace-progress/themes/black/pace-theme-flat-top.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dist/css/adminlte.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')); ?>">
    <script src="<?php echo e(asset('plugins/jquery/jquery.min.js')); ?>"></script>

    <style>
        .nav-bg {
            background-color: <?php echo $schoolinfo->schoolcolor; ?> !important;
        }

        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active,
        .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
            background-color: <?php echo $schoolinfo->schoolcolor; ?>;
        }

        .sidehead {
            background-color: #002833 !important;
        }
    </style>

    <?php echo $__env->yieldContent('pagespecificscripts'); ?>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed  pace-primary">


    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-dark pace-primary nav-bg">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown sideright">
                    <a class="nav-link" data-toggle="dropdown" href="<?php echo e(route('logout')); ?>"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt logouthover"></i>
                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                            <?php echo csrf_field(); ?>
                        </form>
                    </a>
                </li>
            </ul>
        </nav>

        <?php echo $__env->make('superadmin.inc.sidenav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php echo $__env->yieldContent('modalSection'); ?>
        <div class="content-wrapper">
            <?php echo $__env->yieldContent('content'); ?>
        </div>

    </div>

    <?php echo $__env->make('sweetalert::alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script src="<?php echo e(asset('plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/js/adminlte.min.js')); ?>"></script>
    <script src="<?php echo e(asset('plugins/pace-progress/pace.min.js')); ?>"></script>
    <script src="<?php echo e(asset('plugins/sweetalert2/sweetalert2.all.min.js')); ?>"></script>
    <script src="<?php echo e(asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')); ?>"></script>
    <script>
        // var Toast = Swal.mixin({
        //     toast: true,
        //     position: 'top-end',
        //     showConfirmButton: false,
        //     timer: 2000
        // });

        // function notify(code, message) {
        //     Toast.fire({
        //         type: code,
        //         title: message,
        //     });

        // }
    </script>
    <?php echo $__env->yieldContent('footerjavascript'); ?>
    
</body>

</html>
<?php /**PATH C:\laragon\www\es_ldcu\resources\views/superadmin/layouts/app2.blade.php ENDPATH**/ ?>