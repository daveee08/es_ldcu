<style>
    .alert-primary {
        color: #004085;
        background-color: #cce5ff;
        border-color: #b8daff;
    }

    .alert-secondary {
        color: #383d41;
        background-color: #e2e3e5;
        border-color: #d6d8db;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .alert-warning {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;
    }

    .alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }

    .alert-light {
        color: #818182;
        background-color: #fefefe;
        border-color: #fdfdfe;
    }

    .alert-dark {
        color: #1b1e21;
        background-color: #d6d8d9;
        border-color: #c6c8ca;
    }
</style>
<?php if($acadprogid == 6): ?>
    <?php
        $strands = collect($students)->groupBy('strandcode');
    ?>
    <div class="card card-success card-eachsection">
        <div class="card-header">
            <div class="row">
                <div class="col-12">
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="checkboxesc" name="escCheck"
                            <?php if($esc > 0): ?> value="1" checked <?php else: ?> value="0" <?php endif; ?>>
                        <label for="checkboxesc">
                            ESC Grantee
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <?php if(count($students) == 0): ?>
            <div class="alert alert-primary" role="alert">
                No students enrolled!
            </div>
        <?php else: ?>
            <div class="card-body">
                <div class="row">
                    <?php if(count($students) == 0): ?>
                        <div class="col-md-12">
                            <div class="alert alert-primary" role="alert">
                                No students enrolled!
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-md-12 mb-2 text-right">
                            <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc' ||
                                    strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc'): ?>
                                <form action="/reports_studentmasterlist/print/<?php echo e($syid); ?>/<?php echo e($sectionid); ?>"
                                    target="_blank" id="exportform" class="m-0 p-0">
                                    <input type="hidden" name="exporttype" id="exporttype" />
                                    <input type="hidden" name="levelid" value="<?php echo e($levelid); ?>" />
                                    <input type="hidden" name="semid" value="<?php echo e($semid); ?>" />
                                    <input type="hidden" name="syid" value="<?php echo e($syid); ?>" />
                                    <input type="hidden" name="collegeid" value="<?php echo e($collegeid); ?>" />
                                    <input type="hidden" name="courseid" value="<?php echo e($courseid); ?>" />
                                    <input type="hidden" name="sectionid" value="0" />
                                    <input type="hidden" name="acadprogid" value="<?php echo e($acadprogid); ?>" />
                                    <div class="row">
                                        <div class="col-md-3 text-left">
                                            <select class="form-control form-control-sm" name="format">
                                                <option value="lastname_first">Template - Last Name First</option>
                                                <option value="firstname_first">Template - First Name First</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 text-left">
                                            <button type="button" class="btn btn-default btn-sm btn-export"
                                                id="exportpdf">Export to PDF</button>
                                            <button type="button" class="btn btn-default btn-sm btn-export"
                                                id="exportexcel">Export to EXCEL</button>
                                        </div>
                                        <div class="col-md-5">
                                            <button type="button" class="btn btn-default btn-sm btn-export"
                                                id="exportexcelinfo">Export to EXCEL (INFO)</button>
                                            <button type="button" class="btn btn-default btn-sm btn-export"
                                                id="exportexcellist">Export to EXCEL (LIST)</button>
                                        </div>
                                    </div>
                                    <input type="hidden" value="<?php echo e($esc); ?>" name="esc" />
                                </form>
                            <?php else: ?>
                                <form action="/reports_studentmasterlist/print/<?php echo e($syid); ?>/0" target="_blank"
                                    id="exportform" class="m-0 p-0">
                                    <input type="hidden" name="exporttype" id="exporttype" />
                                    <input type="hidden" name="levelid" value="<?php echo e($levelid); ?>" />
                                    <input type="hidden" name="semid" value="<?php echo e($semid); ?>" />
                                    <input type="hidden" name="syid" value="<?php echo e($syid); ?>" />
                                    <input type="hidden" name="collegeid" value="<?php echo e($collegeid); ?>" />
                                    <input type="hidden" name="courseid" value="<?php echo e($courseid); ?>" />
                                    <input type="hidden" name="sectionid" value="0" />
                                    <input type="hidden" name="acadprogid" value="<?php echo e($acadprogid); ?>" />
                                    <div class="row">
                                        <div class="col-md-3 text-left">
                                            <select class="form-control form-control-sm" name="format">
                                                <option value="lastname_first">Template - Last Name First</option>
                                                <option value="firstname_first">Template - First Name First</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 text-left">
                                            <button type="button" class="btn btn-default btn-sm btn-export"
                                                id="exportpdf">Export to PDF</button>
                                            <button type="button" class="btn btn-default btn-sm btn-export"
                                                id="exportexcel">Export to EXCEL</button>
                                        </div>
                                        <div class="col-md-5">
                                            <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi'): ?>
                                                <button type="button" class="btn btn-default btn-sm btn-export"
                                                    id="exportexcelinfo">Export to EXCEL (LIST)</button>
                                                <button type="button" class="btn btn-default btn-sm btn-export"
                                                    id="exportexcellist">Export to EXCEL (INFO)</button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-default btn-sm btn-export"
                                                    id="exportexcelinfo">Export to EXCEL (INFO)</button>
                                                <button type="button" class="btn btn-default btn-sm btn-export"
                                                    id="exportexcellist">Export to EXCEL (LIST)</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <input type="hidden" value="<?php echo e($esc); ?>" name="esc" />
                                </form>
                            <?php endif; ?>

                        </div>

                        <div class="col-md-12">
                            <?php $__currentLoopData = $strands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $eachstrand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5><?php echo e($key); ?></h5>
                                    </div>
                                    <div class="col-md-6">
                                        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MALE</label>
                                        <ol>
                                            <?php $__currentLoopData = $eachstrand; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(strtolower($student->gender) == 'male'): ?>
                                                    <li
                                                        style="display: list-item;list-style: decimal; list-style-position: inside; <?php if($student->studstatus == 3 || $student->studstatus == 5): ?> text-decoration: line-through <?php endif; ?>">
                                                        <?php echo e($student->lastname); ?>, <?php echo e($student->firstname); ?>

                                                        <?php echo e($student->middlename); ?> <?php if($student->studstatus == 3 || $student->studstatus == 5): ?>
                                                            <?php echo e(DB::table('studentstatus')->where('id', $student->studstatus)->first()->description); ?>

                                                            <?php endif; ?> <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak'): ?>
                                                                <?php if($esc == 1): ?>
                                                                    <?php if(strtolower($student->granteedesc) == 'esc'): ?>
                                                                        - <button type="button"
                                                                            class="btn btn-sm btn-default btn-each-esccert"
                                                                            data-id="<?php echo e($student->id); ?>"><i
                                                                                class="fa fa-file-pdf text-secondary"></i></button>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ol>
                                    </div>
                                    <div class="col-md-6">
                                        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FEMALE</label>
                                        <ol>
                                            <?php $__currentLoopData = $eachstrand; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(strtolower($student->gender) == 'female'): ?>
                                                    <li
                                                        style="display: list-item;list-style: decimal; list-style-position: inside; <?php if($student->studstatus == 3 || $student->studstatus == 5): ?> text-decoration: line-through <?php endif; ?>">
                                                        <?php echo e($student->lastname); ?>, <?php echo e($student->firstname); ?>

                                                        <?php echo e($student->middlename); ?> <?php if($student->studstatus == 3 || $student->studstatus == 5): ?>
                                                            <?php echo e(DB::table('studentstatus')->where('id', $student->studstatus)->first()->description); ?>

                                                            <?php endif; ?> <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak'): ?>
                                                                <?php if($esc == 1): ?>
                                                                    <?php if(strtolower($student->granteedesc) == 'esc'): ?>
                                                                        - <button type="button"
                                                                            class="btn btn-sm btn-default btn-each-esccert"
                                                                            data-id="<?php echo e($student->id); ?>"><i
                                                                                class="fa fa-file-pdf text-secondary"></i></button>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ol>
                                    </div>
                                </div>
                                <hr />
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <!-- /.card-body -->
    </div>
<?php elseif($acadprogid == 5): ?>
    <?php
        $strands = collect($students)->groupBy('strandcode');
    ?>
    <div class="card card-success card-eachsection">
        <div class="card-header">
            <div class="row">
                <div class="col-12">
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="checkboxesc" name="escCheck"
                            <?php if($esc > 0): ?> value="1" checked <?php else: ?> value="0" <?php endif; ?>>
                        <label for="checkboxesc">
                            ESC Grantee
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <?php if(count($students) == 0): ?>
            <div class="alert alert-primary" role="alert">
                No students enrolled!
            </div>
        <?php else: ?>
            <div class="card-body">
                <div class="row">
                    <?php if(count($students) == 0): ?>
                        <div class="col-md-12">
                            <div class="alert alert-primary" role="alert">
                                No students enrolled!
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-md-12 mb-2 text-right">
                            <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc' ||
                                    strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc'): ?>
                                <form
                                    action="/reports_studentmasterlist/print/<?php echo e($syid); ?>/<?php echo e($sectionid); ?>"
                                    target="_blank" id="exportform" class="m-0 p-0">
                                    <input type="hidden" name="exporttype" id="exporttype" />
                                    <input type="hidden" name="levelid" value="<?php echo e($levelid); ?>" />
                                    <input type="hidden" name="semid" value="<?php echo e($semid); ?>" />
                                    <input type="hidden" name="syid" value="<?php echo e($syid); ?>" />
                                    <input type="hidden" name="collegeid" value="<?php echo e($collegeid); ?>" />
                                    <input type="hidden" name="courseid" value="<?php echo e($courseid); ?>" />
                                    <input type="hidden" name="sectionid" value="<?php echo e($sectionid); ?>" />
                                    <input type="hidden" name="acadprogid" value="<?php echo e($acadprogid); ?>" />
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select class="form-control form-control-sm" name="format">
                                                <option value="lastname_first">Template - Last Name First</option>
                                                <option value="firstname_first">Template - First Name First</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <button type="button" class="btn btn-default btn-sm btn-export"
                                                id="exportpdf">Export to PDF</button>
                                            <button type="button" class="btn btn-default btn-sm btn-export"
                                                id="exportexcelinfo">Export to EXCEL (INFO)</button>
                                            <button type="button" class="btn btn-default btn-sm btn-export"
                                                id="exportexcellist">Export to EXCEL (LIST)</button>
                                        </div>
                                    </div>
                                    <input type="hidden" value="<?php echo e($esc); ?>" name="esc" />
                                </form>
                            <?php else: ?>
                                <form
                                    action="/reports_studentmasterlist/print/<?php echo e($syid); ?>/<?php echo e($sectionid); ?>"
                                    target="_blank" id="exportform" class="m-0 p-0">
                                    <input type="hidden" name="exporttype" id="exporttype" />
                                    <input type="hidden" name="levelid" value="<?php echo e($levelid); ?>" />
                                    <input type="hidden" name="semid" value="<?php echo e($semid); ?>" />
                                    <input type="hidden" name="syid" value="<?php echo e($syid); ?>" />
                                    <input type="hidden" name="collegeid" value="<?php echo e($collegeid); ?>" />
                                    <input type="hidden" name="courseid" value="<?php echo e($courseid); ?>" />
                                    <input type="hidden" name="sectionid" value="<?php echo e($sectionid); ?>" />
                                    <input type="hidden" name="acadprogid" value="<?php echo e($acadprogid); ?>" />
                                    <div class="row">
                                        <div class="col-md-3 text-left">
                                            <select class="form-control form-control-sm" name="format">
                                                <option value="lastname_first">Template - Last Name First</option>
                                                <option value="firstname_first">Template - First Name First</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 text-left">
                                            <button type="button" class="btn btn-default btn-sm btn-export"
                                                id="exportpdf">Export to PDFasda</button>
                                            <button type="button" class="btn btn-default btn-sm btn-export"
                                                id="exportexcel">Export to EXCEL</button>
                                        </div>
                                        <div class="col-md-5">
                                            <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi'): ?>
                                                <button type="button" class="btn btn-default btn-sm btn-export"
                                                    id="exportexcelinfo">Export to EXCEL (LIST)</button>
                                                <button type="button" class="btn btn-default btn-sm btn-export"
                                                    id="exportexcellist">Export to EXCEL (INFO)</button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-default btn-sm btn-export"
                                                    id="exportexcelinfo">Export to EXCEL (INFO)</button>
                                                <button type="button" class="btn btn-default btn-sm btn-export"
                                                    id="exportexcellist">Export to EXCEL (LIST)</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <input type="hidden" value="<?php echo e($esc); ?>" name="esc" />
                                </form>
                            <?php endif; ?>
                            
                        </div>

                        <div class="col-md-12">
                            <?php $__currentLoopData = $strands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $eachstrand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5><?php echo e($key); ?></h5>
                                    </div>
                                    <div class="col-md-6">
                                        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MALE</label>
                                        <ol>
                                            <?php $__currentLoopData = $eachstrand; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(strtolower($student->gender) == 'male'): ?>
                                                    <li
                                                        style="display: list-item;list-style: decimal; list-style-position: inside; <?php if($student->studstatus == 3 || $student->studstatus == 5): ?> text-decoration: line-through <?php endif; ?>">
                                                        <?php echo e($student->lastname); ?>, <?php echo e($student->firstname); ?>

                                                        <?php echo e($student->middlename); ?> <?php if($student->studstatus == 3 || $student->studstatus == 5): ?>
                                                            <?php echo e(DB::table('studentstatus')->where('id', $student->studstatus)->first()->description); ?>

                                                            <?php endif; ?> <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak'): ?>
                                                                <?php if($esc == 1): ?>
                                                                    <?php if(strtolower($student->granteedesc) == 'esc'): ?>
                                                                        - <button type="button"
                                                                            class="btn btn-sm btn-default btn-each-esccert"
                                                                            data-id="<?php echo e($student->id); ?>"><i
                                                                                class="fa fa-file-pdf text-secondary"></i></button>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ol>
                                    </div>
                                    <div class="col-md-6">
                                        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FEMALE</label>
                                        <ol>
                                            <?php $__currentLoopData = $eachstrand; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(strtolower($student->gender) == 'female'): ?>
                                                    <li
                                                        style="display: list-item;list-style: decimal; list-style-position: inside; <?php if($student->studstatus == 3 || $student->studstatus == 5): ?> text-decoration: line-through <?php endif; ?>">
                                                        <?php echo e($student->lastname); ?>, <?php echo e($student->firstname); ?>

                                                        <?php echo e($student->middlename); ?> <?php if($student->studstatus == 3 || $student->studstatus == 5): ?>
                                                            <?php echo e(DB::table('studentstatus')->where('id', $student->studstatus)->first()->description); ?>

                                                            <?php endif; ?> <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak'): ?>
                                                                <?php if($esc == 1): ?>
                                                                    <?php if(strtolower($student->granteedesc) == 'esc'): ?>
                                                                        - <button type="button"
                                                                            class="btn btn-sm btn-default btn-each-esccert"
                                                                            data-id="<?php echo e($student->id); ?>"><i
                                                                                class="fa fa-file-pdf text-secondary"></i></button>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ol>
                                    </div>
                                </div>
                                <hr />
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <!-- /.card-body -->
    </div>
<?php else: ?>
    
    <div class="card card-success card-eachsection">
        
        <div class="card-body">
            <div class="row">
                <?php if(count($students) == 0): ?>
                    <div class="col-md-12">
                        <div class="alert alert-primary" role="alert">
                            No students enrolled!
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-md-12 mb-2 text-right">
                        <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc' ||
                                strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc'): ?>
                            <form action="/reports_studentmasterlist/print/<?php echo e($syid); ?>/<?php echo e($sectionid); ?>"
                                target="_blank" id="exportform" class="m-0 p-0">
                                <input type="hidden" name="exporttype" id="exporttype" />
                                <input type="hidden" name="levelid" value="<?php echo e($levelid); ?>" />
                                <input type="hidden" name="semid" value="<?php echo e($semid); ?>" />
                                <input type="hidden" name="syid" value="<?php echo e($syid); ?>" />
                                <input type="hidden" name="collegeid" value="<?php echo e($collegeid); ?>" />
                                <input type="hidden" name="courseid" value="<?php echo e($courseid); ?>" />
                                <input type="hidden" name="sectionid" value="<?php echo e($sectionid); ?>" />
                                <input type="hidden" name="acadprogid" value="<?php echo e($acadprogid); ?>" />
                                <div class="row">
                                    <div class="col-md-4">
                                        <select class="form-control form-control-sm" name="format">
                                            <option value="lastname_first">Template - Last Name First</option>
                                            <option value="firstname_first">Template - First Name First</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <button type="button" class="btn btn-default btn-sm btn-export"
                                            id="exportpdf">Export to PDF</button>
                                        <button type="button" class="btn btn-default btn-sm btn-export"
                                            id="exportexcelinfo">Export to EXCEL (INFO)</button>
                                        <button type="button" class="btn btn-default btn-sm btn-export"
                                            id="exportexcellist">Export to EXCEL (LIST)</button>
                                    </div>
                                </div>
                                <input type="hidden" value="<?php echo e($esc); ?>" name="esc" />
                            </form>
                        <?php else: ?>
                            <form action="/reports_studentmasterlist/print/<?php echo e($syid); ?>/<?php echo e($sectionid); ?>"
                                target="_blank" id="exportform" class="m-0 p-0">
                                <input type="hidden" name="exporttype" id="exporttype" />
                                <input type="hidden" name="levelid" value="<?php echo e($levelid); ?>" />
                                <input type="hidden" name="semid" value="<?php echo e($semid); ?>" />
                                <input type="hidden" name="syid" value="<?php echo e($syid); ?>" />
                                <input type="hidden" name="collegeid" value="<?php echo e($collegeid); ?>" />
                                <input type="hidden" name="courseid" value="<?php echo e($courseid); ?>" />
                                <input type="hidden" name="sectionid" value="<?php echo e($sectionid); ?>" />
                                <input type="hidden" name="acadprogid" value="<?php echo e($acadprogid); ?>" />
                                <div class="row">
                                    <div class="col-md-3 text-left">
                                        <select class="form-control form-control-sm" name="format">
                                            <option value="lastname_first">Template - Last Name First</option>
                                            <option value="firstname_first">Template - First Name First</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 text-left">
                                        <button type="button" class="btn btn-default btn-sm btn-export"
                                            id="exportpdf">Export to PDFa</button>
                                        <button type="button" class="btn btn-default btn-sm btn-export"
                                            id="exportexcel">Export to EXCEL</button>
                                    </div>
                                    <div class="col-md-5">
                                        <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi'): ?>
                                            <button type="button" class="btn btn-default btn-sm btn-export"
                                                id="exportexcelinfo">Export to EXCEL (LIST)</button>
                                            <button type="button" class="btn btn-default btn-sm btn-export"
                                                id="exportexcellist">Export to EXCEL (INFO)</button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-default btn-sm btn-export"
                                                id="exportexcelinfo">Export to EXCEL (INFO)</button>
                                            <button type="button" class="btn btn-default btn-sm btn-export"
                                                id="exportexcellist">Export to EXCEL (LIST)</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <input type="hidden" value="<?php echo e($esc); ?>" name="esc" />
                            </form>
                        <?php endif; ?>
                        
                    </div>
                    <div class="col-md-6">
                        <label>MALE</label>
                        <ol>
                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(strtolower($student->gender) == 'male'): ?>
                                    <li
                                        style="display: list-item;list-style: decimal; list-style-position: inside; <?php if($student->studstatus == 3 || $student->studstatus == 5): ?> text-decoration: line-through <?php endif; ?>">
                                        <?php echo e($student->lastname); ?>, <?php echo e($student->firstname); ?>

                                        <?php echo e($student->middlename); ?> <?php if($student->studstatus == 3 || $student->studstatus == 5): ?>
                                            <?php echo e(DB::table('studentstatus')->where('id', $student->studstatus)->first()->description); ?>

                                            <?php endif; ?> <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak'): ?>
                                                <?php if($esc == 1): ?>
                                                    <?php if(strtolower($student->granteedesc) == 'esc'): ?>
                                                        - <button type="button"
                                                            class="btn btn-sm btn-default btn-each-esccert"
                                                            data-id="<?php echo e($student->id); ?>"><i
                                                                class="fa fa-file-pdf text-secondary"></i></button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ol>
                    </div>
                    <div class="col-md-6">
                        <label>FEMALE</label>
                        <ol>
                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(strtolower($student->gender) == 'female'): ?>
                                    <li
                                        style="display: list-item;list-style: decimal; list-style-position: inside; <?php if($student->studstatus == 3 || $student->studstatus == 5): ?> text-decoration: line-through <?php endif; ?>">
                                        <?php echo e($student->lastname); ?>, <?php echo e($student->firstname); ?>

                                        <?php echo e($student->middlename); ?> <?php if($student->studstatus == 3 || $student->studstatus == 5): ?>
                                            <?php echo e(DB::table('studentstatus')->where('id', $student->studstatus)->first()->description); ?>

                                            <?php endif; ?> <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak'): ?>
                                                <?php if($esc == 1): ?>
                                                    <?php if(strtolower($student->granteedesc) == 'esc'): ?>
                                                        - <button type="button"
                                                            class="btn btn-sm btn-default btn-each-esccert"
                                                            data-id="<?php echo e($student->id); ?>"><i
                                                                class="fa fa-file-pdf text-secondary"></i></button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ol>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
<?php endif; ?>
<script>
    $('#btn-addnew-note').on('click', function() {
        $('#container-notes').append(
            '<div class="row mt-1"><div class="col-md-10"><textarea class="form-control text-area-note" style="height: 35px !important;"></textarea></div>' +
            '<div class="col-md-2 text-right"><button type="button" class="btn btn-sm btn-outline-success btn-save-note">Save <i class="fa fa-share"></i></button></div></div>'
        )
    })
</script>
<?php /**PATH C:\laragon\www\es_ldcu\resources\views/registrar/forms/masterlist/students.blade.php ENDPATH**/ ?>