<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <button class="btn btn-sm btn-warning"><strong><?php echo e(count($students)); ?></strong> Students</button>
            </div>
            <div class="col-md-6 text-right">
                <td class="text-right">
                    <button type="button" class="btn btn-default printAllSOA bg-primary" style="font-size: 12px;">
                        <i class="fa fa-print"></i> Print All
                    </button>
                </td>
                
                
            </div>
        </div>
        <div class="row">
            <div class="col-12" id="selectedoptionscontainer"></div>
        </div>
        <div class="row mt-2">
            <div class="col-12" id="resultscontainer">
                <table id="example1" class="table">
                    <thead>
                        <tr>
                            <th style="width: 3%;">
                                <input type="checkbox" id="select_all_students" checked>
                            </th>
                            <th style="width: 5%;">SID</th>
                            

                            <th>
                                <div style="display: flex; justify-content: space-between;">
                                    <span>Student Grade</span>
                                    <span class="mr-3 text-success">Level/Section</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="soa_body">
                        <?php if(count($students) > 0): ?>
                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="student_checkbox" value="<?php echo e($student->id); ?>"
                                            checked>
                                    </td>
                                    <td><?php echo e($student->sid); ?></td>
                                    
                                    <td class="p-0">
                                        <div class="card collapsed-card p-0 mb-0"
                                            style="border: none !important; background-color: unset !important;box-shadow: none !important;">
                                            <div class="card-header">
                                                <div
                                                    style="display: flex; justify-content: space-between; align-items: center;">
                                                    <h3 class="card-title"><?php echo e($student->lastname); ?>,
                                                        <?php echo e($student->firstname); ?> <?php echo e($student->middlename); ?>

                                                        <?php echo e($student->suffix); ?></h3>

                                                    <h3 class="card-title"><?php echo e($student->levelname); ?>,
                                                        <?php echo e($student->sectionname); ?></h3>
                                                </div>

                                                <br>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool viewdetails"
                                                        data-card-widget="collapse" id="<?php echo e($student->id); ?>">View
                                                    </button>
                                                    <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xai' ||
                                                            strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc' ||
                                                            strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc'): ?>
                                                    <?php else: ?>
                                                        
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-tool printstatementofacct"
                                                        exporttype="pdf" studid="<?php echo e($student->id); ?>">PDF
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: none;" id="stud<?php echo e($student->id); ?>">
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                    </td>

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    // $('.printAllSOA').on('click', function () {
    //     window.open('/printallSOA', '_blank');
    // });
    document.getElementById('select_all_students').addEventListener('change', function() {
        let checked = this.checked;
        document.querySelectorAll('.student_checkbox').forEach(function(cb) {
            cb.checked = checked;
        });
    });
</script>
<?php /**PATH C:\laragon\www\es_ldcu\resources\views/finance/statementofaccount/filtertable.blade.php ENDPATH**/ ?>