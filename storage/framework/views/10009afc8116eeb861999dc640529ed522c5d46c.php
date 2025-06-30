<?php $__env->startSection('pagespecificscripts'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('plugins/select2/css/select2.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')); ?>">
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
        }

        .no-border-col {
            border-left: 0 !important;
            border-right: 0 !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<section class="content pt-0">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">
                <div class="info-box shadow-lg">
                    <div class="info-box-content">
                        <div class="row">
                            <?php if(auth()->user()->type == 17): ?>
                                <div class="col-md-12 form-group mb-0">
                                    <label for="">Teacher</label>
                                    <select class="form-control select2" id="filter_teacher">
                                    </select>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-5  form-group mb-0">
                                <label for="">School Year</label>
                                <select class="form-control select2" id="filter_sy">
                                    <?php $__currentLoopData = $sy; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($item->isactive == 1): ?>
                                            <option value="<?php echo e($item->id); ?>" selected="selected">
                                                <?php echo e($item->sydesc); ?></option>
                                        <?php else: ?>
                                            <option value="<?php echo e($item->id); ?>"><?php echo e($item->sydesc); ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-5  form-group mb-0">
                                <label for="">Semester</label>
                                <select class="form-control select2  form-control-sm" id="filter_sem">
                                    <?php $__currentLoopData = $semester; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($item->isactive == 1): ?>
                                            <option value="<?php echo e($item->id); ?>" selected="selected">
                                                <?php echo e($item->semester); ?></option>
                                        <?php else: ?>
                                            <option value="<?php echo e($item->id); ?>"><?php echo e($item->semester); ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12" style="font-size:14px !important">
                                <table class="table table-sm table-bordered table-striped" id="subjectplot_table"
                                    width="100%">
                                    <thead>
                                        <tr>
                                            <th width="20%">Section</th>
                                            <th width="40%">Subject</th>
                                            <th width="22%" class="text-center">Time & Day</th>
                                            <th width="10%" class="text-center">Students</th>
                                            <th width="8%" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="schedule">
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

<?php $__env->startSection('footerjavascript'); ?>
    <script src="<?php echo e(asset('plugins/select2/js/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(asset('plugins/datatables/jquery.dataTables.js')); ?>"></script>
    <script src="<?php echo e(asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')); ?>"></script>
    <script src="<?php echo e(asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js')); ?>"></script>

    <script>
        $(document).ready(function() {

                    $('.select2').select2()

                    get_teachers()

                    function get_teachers() {
                        $.ajax({
                            type: 'GET',
                            url: '/teacher/list',
                            success: function(data) {
                                var all_teacher = data
                                $("#filter_teacher").empty()
                                $("#filter_teacher").append('<option value="">Select Teacher</option>')
                                $("#filter_teacher").val("")
                                $('#filter_teacher').select2({
                                    allowClear: true,
                                    data: all_teacher,
                                    placeholder: "Select teacher",
                                })
                            }
                        })
                    }

                    var all_sched = [];
                    var temp_teacher = <?php echo json_encode($temp_teacherid, 15, 512) ?>;

                    load_schedule()

                    $(document).on('click', '#button_filter', function() {
                        load_schedule()
                    })

                    $(document).on('change', '#filter_sy', function() {
                        all_sched = []
                        load_schedule()
                    })

                    $(document).on('change', '#filter_sem', function() {
                        all_sched = []
                        load_schedule()
                    })

                    $(document).on('change', '#filter_teacher', function() {
                        load_schedule()
                    })

                    function load_schedule() {

                        if (temp_teacher == 0) {
                            var teacherid = $('#filter_teacher').val()
                        } else {
                            var teacherid = temp_teacher
                        }

                        if ($('#filter_teacher').val() != "") {
                            $.ajax({
                                type: 'GET',
                                url: '/teacher/schedule',
                                data: {
                                    syid: $('#filter_sy').val(),
                                    semid: $('#filter_sem').val(),
                                    teacherid: teacherid
                                },
                                success: function(data) {
                                    if (data.length == 0) {
                                        Toast.fire({
                                            type: 'info',
                                            title: 'No schedule found!'
                                        })
                                        all_sched = []
                                        load_gradesetup_datatable()
                                    } else {
                                        if (data[0].status == undefined) {
                                            all_sched = data
                                            load_gradesetup_datatable()
                                        } else {
                                            all_sched = []
                                            load_gradesetup_datatable()
                                        }
                                    }
                                }
                            })
                        } else {
                            Toast.fire({
                                type: 'info',
                                title: 'No student selected!'
                            })
                            all_sched = [];
                            load_gradesetup_datatable()
                        }
                    }

                    function load_gradesetup_datatable() {

                        $("#subjectplot_table").DataTable({
                            destroy: true,
                            data: [],
                            lengthChange: false,
                            columns: [{
                                    "data": "search"
                                },
                                {
                                    "data": null
                                },
                                {
                                    "data": null
                                },
                                {
                                    "data": null
                                },
                                {
                                    "data": null
                                },
                            ],
                            columnDefs: [{
                                    'targets': 0,
                                    'orderable': true,
                                    'createdCell': function(td, cellData, rowData, row, col) {
                                        var
                                            text = '<a class="mb-0">Section Name</a><p class="text-muted mb-0" >Level Name</p>';
                                        $(td)[0].innerHTML = text
                                        $(td).addClass('align-middle')
                                    }
                                },
                                {
                                    'targets': 1,
                                    'orderable': true,
                                    'createdCell': function(td, cellData, rowData, row, col) {
                                        var
                                            text = '<a class="mb-0">Subject Description</a><p class="text-muted mb-0">Subject Code - <i class="text-danger">Type</i></p>';
                                        $(td)[0].innerHTML = text
                                        $(td).addClass('align-middle')
                                    }
                                },
                                {
                                    'targets': 2,
                                    'orderable': true,
                                    'createdCell': function(td, cellData, rowData, row, col) {
                                        var table = 'table-borderless'
                                        var multiple = ''
                                        var text = '<table class="table table-sm mb-0 ' + table + '" >'
                                        text +=
                                            '<tr style="background-color:transparent !important" ><td width="100%" class="' + multiple + '">Start - End<br>Day</td></tr>'
                                        text += '</table>'
                                        $(td)[0].innerHTML = text
                                        $(td).addClass('align-middle')
                                        $(td).addClass('p-0')
                                    }
                                },
                                {
                                    'targets': 3,
                                    'orderable': true,
                                    'createdCell': function(td, cellData, rowData, row, col) {
                                        var text = '0'
                                        $(td)[0].innerHTML = text
                                        $(td).addClass('align-middle')
                                        $(td).addClass('text-center')
                                    }
                                },
                                {
                                    'targets': 4,
                                    'orderable': false,
                                    'createdCell': function(td, cellData, rowData, row, col) {
                                        var text =
                                            '<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                                            '<i class="fas fa-ellipsis-v"></i>' +
                                            '</button>' +
                                            '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">' +
                                            '<a class="dropdown-item view_classrecord" href="#" data-id="0">' +
                                            '<i class="fas fa-clipboard"></i>  Class Record</a>' +
                                            '</div>';
                                        $(td)[0].innerHTML = text
                                        $(td).addClass('align-middle')
                                        $(td).addClass('text-center')
                                    }
                                },

                            ]

                        });

                        var label_text = $($("#subjectplot_table_wrapper")[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '<h4 class="mb-0">Grade Status</h4>'
                    }
<?php /**PATH C:\laragon\www\es_ldcu\resources\views/superadmin/pages/migration/modals/studentmasterlist.blade.php ENDPATH**/ ?>