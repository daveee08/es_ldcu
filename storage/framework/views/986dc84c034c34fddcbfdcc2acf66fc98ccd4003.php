<?php
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
    } elseif (Session::get('currentPortal') == 15) {
        $extend = 'finance.layouts.app';
    } elseif (Session::get('currentPortal') == 14) {
        $extend = 'deanportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 8) {
        $extend = 'admission.layouts.app2';
    } elseif (auth()->user()->type == 6) {
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
            }
        }
    }

    $refid = $check_refid->refid;

?>


<?php $__env->startSection('pagespecificscripts'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('plugins/select2/css/select2.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')); ?>">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Certifications </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Certifications</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
    <section class="content pt-0">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label>Select School Year</label>
                            <select class="form-control select2" id="select-syid">
                                <?php $__currentLoopData = DB::table('sy')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($sy->id); ?>" <?php if($sy->isactive == 1): ?> selected <?php endif; ?>>
                                        <?php echo e($sy->sydesc); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label>Select Semester</label>
                            <select class="form-control select2" id="select-semid">
                                <?php $__currentLoopData = DB::table('semester')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($semester->id); ?>" <?php if($semester->isactive == 1): ?> selected <?php endif; ?>>
                                        <?php echo e($semester->semester); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label>Select Certification</label>
                            <select class="form-control select2" id="select-certtype">
                                <option value="coe">Certificate of Enrollment</option>
                                <option value="cogm">Certificate of Good Moral</option>
                                <option value="cog">Certificate of Grades</option>
                                <option value="coett">Certificate of Eligibility to Transfer</option>
                                <option value="hd_so">Honorable Dismissal For Special Order</option>
                                <option value="hd_ug">Honorable Dismissal For Under Graduate</option>
                                <option value="hd_g">Honorable Dismissal For Graduate</option>
                                <option value="cert_g">Certification of graduation</option>
                                <option value="cert_g_so">Certification of graduation with special order</option>
                            </select>
                        </div>
                        <div class="col-md-2 text-right align-self-end mb-2">
                            <button type="button" class="btn btn-primary" id="btn-generate"><i
                                    class="fa fa-sync fa-sm"></i> Generate</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="container-filter">
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('footerjavascript'); ?>
    <script src="<?php echo e(asset('plugins/select2/js/select2.full.min.js')); ?>"></script>



    <script>
        $(window).on('load', function() {
            sessionStorage.setItem('activetab', '#custom-content-below-profile-tab');
            sessionStorage.setItem('activetabpane', '#custom-content-below-profile');
        });
        $('.select2').select2({
            theme: 'bootstrap4'
        })
        var lettercase = ["capitalize", "upper", 'lower']
        $('#btn-change-letter-case').on('click', function() {

        })
        $('#select-syid').on('change', function() {
            $('#container-filter').empty()
        })
        $('#select-semid').on('change', function() {
            $('#container-filter').empty()
        })
        $('#select-certtype').on('change', function() {
            $('#container-filter').empty()
        })
        $('#btn-generate').on('click', function() {
            var syid = $('#select-syid').val();
            var semid = $('#select-semid').val();
            var certtype = $('#select-certtype').val();

            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            $.ajax({
                url: '/printable/certifications',
                type: 'GET',
                // dataType: 'json',
                data: {
                    action: 'filter',
                    syid: syid,
                    semid: semid,
                    certtype: certtype
                },
                success: function(data) {
                    $('#container-filter').empty()
                    $('#container-filter').append(data)
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')

                    if (certtype === 'coe') {
                        $('#btn-printpdf').prop('hidden', false)
                    }
                }
            })
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($extend, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\es_ldcu2\resources\views/registrar/otherprintables/certifications/index.blade.php ENDPATH**/ ?>