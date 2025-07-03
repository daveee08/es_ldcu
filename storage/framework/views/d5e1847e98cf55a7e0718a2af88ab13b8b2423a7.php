<?php $__env->startSection('pagespecificscripts'); ?>
    <style>
        .card-body .btn-app {
            width: 40%;
            height: 100px;
            border: none;
            padding-top: 20px;
            transition: .3s;
            font-size: 15px;
            background-color: transparent !important;
        }

        .card-body {
            text-align: center;
        }

        .icon-display {
            font-size: 35px !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <div class="row">
            <div class="col-md-4 mt-3">
                <div class="card h-100">
                    <div class="card-header bg-success ">
                        <h3 class="card-title"><b>School Setup</b></h3>
                    </div>
                    <div class="card-body bg-success p-2">
                        <a class="btn btn-app text-white ml-0" href="/superadmin/view/paymentoptions">
                            <i class="fas fa-cog text-warning icon-display mb-2"></i> <b>Payment Options</b>
                        </a>
                        <a class="btn btn-app text-white ml-0" href="/superadmin/view/schoolinfo">
                            <i class="fas fa-cog text-warning icon-display mb-2"></i> <b>School Information</b>
                        </a>
                    </div>
                </div>
            </div>
            
            
            <div class="col-md-4 mt-3">
                <div class="card h-100">
                    <div class="card-header bg-success">
                        <h3 class="card-title"><b>Password Resseter</b></h3>
                    </div>
                    <div class="card-body bg-success p-2">
                        <a class="btn btn-app text-white ml-0" href="/student/information">
                            <i class="fas fa-keyboard text-warning icon-display mb-2"></i> <b>Parents / Students</b>
                        </a>
                        <a class="btn btn-app text-white ml-0" href="/manageaccounts">
                            <i class="fas fa-keyboard text-warning icon-display mb-2"></i> <b>Faculty /<br> Staff</b>
                        </a>

                    </div>
                </div>
            </div>

        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footerscript'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('superadmin.layouts.app2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\es_ldcu2\resources\views/superadmin/pages/home.blade.php ENDPATH**/ ?>