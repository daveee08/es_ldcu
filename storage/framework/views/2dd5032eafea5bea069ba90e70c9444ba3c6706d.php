<style>
    img {
        border-radius: unset !important;
    }
</style>
<?php
  $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
  $semester = DB::table('semester')->orderBy('id', 'asc')->get();
?>
<div class="row">
    <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc'): ?>
        <div class="col-md-3 mb-2">
            <label>Registrar</label>
            <input type="text" class="form-control form-control-sm" id="input-registrar" placeholder="Registrar"
                value="<?php echo e(collect($signatories)->where('title', 'Registrar')->first()->name ?? ''); ?>" />
        </div>
        <div class="col-md-3 mb-2">
            <label>Prepared by</label>
            <input type="text" class="form-control form-control-sm" id="input-preparedby" placeholder="Prepared by"
                value="<?php echo e(collect($signatories)->where('description', 'Prepared by')->first()->name ?? ''); ?>" />
        </div>
        <div class="col-md-3 mb-2">
            <label>Checked by</label>
            <input type="text" class="form-control form-control-sm" id="input-checkedby" placeholder="Checked by"
                value="<?php echo e(collect($signatories)->where('description', 'Checked by')->first()->name ?? ''); ?>" />
        </div>
        <div class="col-md-3 mb-2">
            <label>Date Issued:</label><input type="date" class="form-control form-control-sm p-1"
                id="input-date-issued" placeholder="Date Issued:" value="<?php echo e(date('Y-m-d')); ?>" />
        </div>
    <?php elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc'): ?>
        <div class="col-md-3 mb-2">
            <label>O.R #</label>
            <input type="text" class="form-control form-control-sm" id="input-or" placeholder="O.R #:" />
        </div>
        <div class="col-md-3 mb-2">
            <label>Date Issued:</label><input type="date" class="form-control form-control-sm p-1"
                id="input-date-issued" placeholder="Date Issued:" value="<?php echo e(date('Y-m-d')); ?>" />
        </div>
        <div class="col-md-6 mb-2">
            <em><small>Note: Go to <strong><a href="/setup/signatories">SETUP > School Configuration > School Form
                            Signatories</a></strong> to add signatories.</small></em>
        </div>
    <?php elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci'): ?>
        <div class="col-md-3 mb-2">
            <label>Assistant Registrar</label>
            <input type="text" class="form-control form-control-sm" id="input-assistantreg"
                placeholder="Assistant Registrar" />
        </div>
        <div class="col-md-3 mb-2">
            <label>Registrar</label>
            <input type="text" class="form-control form-control-sm" id="input-registrar" placeholder="Registrar"
                value="<?php echo e(collect($signatories)->where('title', 'Registrar')->first()->name ?? ''); ?>" />
        </div>
    <?php else: ?>
        <div class="col-md-3 mb-2">
            <label>Registrar</label>
            <input type="text" class="form-control form-control-sm" id="input-registrar" placeholder="Registrar"
                value="<?php echo e(collect($signatories)->where('title', 'Registrar')->first()->name ?? ''); ?>" />
        </div>
        <div class="col-md-3 mb-2">
            <label>Assistant Registrar</label>
            <input type="text" class="form-control form-control-sm" id="input-assistantreg"
                placeholder="Assistant Registrar" />
        </div>
        <div class="col-md-3 mb-2">
            <label>O.R #</label>
            <input type="text" class="form-control form-control-sm" id="input-or" placeholder="O.R #:" />
        </div>
        <div class="col-md-3 mb-2">
            <label>Date Issued:</label><input type="date" class="form-control form-control-sm p-1"
                id="input-date-issued" placeholder="Date Issued:" value="<?php echo e(date('Y-m-d')); ?>" />
        </div>
    <?php endif; ?>
    <div class="col-md-12 text-right mb-2">
        <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc'): ?>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    Export Transcript of Records (TOR)
                </button>
                <div class="dropdown-menu dropdown-menu-right" style="font-size: 14px;">
                    <button class="btn-exportform dropdown-item" data-exporttype="pdf" data-template="1"><i
                            class="fa fa-file-pdf"></i> &nbsp;Template 1 - Board Exam</button>
                    <button class="btn-exportform dropdown-item" data-exporttype="pdf" data-template="2"><i
                            class="fa fa-file-pdf"></i> &nbsp;Template 2 - Employment</button>
                </div>
            </div>
        <?php else: ?>
            
        <?php endif; ?>

    </div>
</div>
<hr />
<?php

    if (strtoupper($studentinfo->gender) == 'FEMALE') {
        $avatar = 'avatar/S(F) 1.png';
    } else {
        $avatar = 'avatar/S(M) 1.png';
    }
?>

<div class="row">

    <div class="col-md-12">
        <div class="callout callout-info pt-0">
                <!-- SAIT -->

                <div class="row p-0" style="font-size: 13px !important;">
                    <div class="col-md-12 text-center">
                        <h6 class="text-bold">PERSONAL DATASHEET</h6>
                    </div>
                    <div class="col-md-12">
                        <table style="width: 100%; table-layout: fixed;">
                            <tr>
                                <td style="width: 12%;">Name</td>
                                <td width="21%">: <input type="text" style="width: 90%;" value="<?php echo e($studentinfo->lastname); ?>, <?php echo e($studentinfo->firstname); ?> <?php echo e($studentinfo->suffix); ?> <?php echo e($studentinfo->middlename); ?>" disabled>
                                <td style="width: 12%;">Sex</td>
                                <td width="21%">: <input type="text" style="width: 90%;" value="<?php echo e($studentinfo->gender); ?>" disabled></td>
                                <td style="width: 12%;">Date of Entrance</td>
                                <td width="21%">: <input type="date" style="width: 90%;"
                                        value="<?php echo e($details->entrancedate ?? ''); ?>" id="input-entrancedate" /></td>
                            </tr>
                            <tr>
                                <td style="width: 12%;">Date of Birth</td>
                                <td>: <input type="text" style="width: 90%;" value="<?php echo e($studentinfo->dob ?? ''); ?>" disabled></td>
                                <td style="width: 12%;">Citizenship</td>
                                <td>: <input type="text" style="width: 90%;"
                                        value="<?php echo e($details->citizenship ?? ''); ?>" id="input-citizenship" /></td>
                                <td style="width: 12%;">Entrance Data</td>
                                <td>: <input type="text" style="width: 90%;"
                                        value="<?php echo e($details->entrancedata ?? ''); ?>" id="input-entrancedata" /></td>
                            </tr>
                            <tr>
                                <td style="width: 12%;">Father’s Name </td>
                                <td>: <input type="text" style="width: 90%;" value="<?php echo e($studentinfo->fathername ?? ''); ?>" disabled></td>
                                <td style="width: 12%;">Civil Status</td>
                                <td>: <input type="text" style="width: 90%;"
                                        value="<?php echo e($details->civilstatus ?? ''); ?>" id="input-civilstatus" /></td>
                                <td style="width: 12%;">Religion</td>
                                <td>: <input type="text" style="width: 90%;" value="<?php echo e($studentinfo->religionanme ?? ''); ?>" disabled></td>
                            </tr>
                            <tr>
                                <td style="width: 12%;">Mother’s Name </td>
                                <td>: <input type="text" style="width: 90%;" value="<?php echo e($studentinfo->religionanme ?? ''); ?>" disabled></td>
                                <td style="width: 12%;">Home Address</td>
                                <td>: <input type="text" style="width: 90%;" value="<?php echo e($studentinfo->street); ?>, <?php echo e($studentinfo->barangay); ?>,
                                    <?php echo e($studentinfo->city); ?>, <?php echo e($studentinfo->province); ?>" disabled></td>
                                <td style="width: 12%;">Birth Place</td>
                                <td>: <input type="text" style="width: 90%;" value="<?php echo e($studentinfo->pob ?? ''); ?>" disabled></td>
                            </tr>
                            <tr>
                                <td>NSTP Serial No.</td>
                                <td>: <input type="text" style="width: 90%;"
                                        value="<?php echo e($details->nstpserialno ?? ''); ?>" id="input-nstpserialno" /></td>
                            </tr>
                        </table>
                    </div>
                </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="callout callout-info pt-0">
                <!-- SAIT -->
                <div class="row p-0" style="font-size: 13px !important;">
                    <div class="col-md-12 text-center">
                        <h6 class="text-bold">RECORDS OF PRELIMINARY EDUCATION</h6>
                    </div>
                    
                    <div class="col-md-12">
                        
                        <table width="100%" class="mt-2">
                            <tr>
                                <td class="align-middle">Category:</td>
                                <td class="align-middle">
                                    <input type="checkbox" class="day" id="shs_check" value="1" <?php echo e(($details->glits == 14 || $details->glits == 15) ? 'checked' : ''); ?> disabled>
                                    <label for="Mon" class="text-sm">SHS</label>
                                </td>
                                <td class="align-middle">
                                    <input type="checkbox" class="day" id="voca_grad" value="1" <?php echo e($details->glits == 26 ? 'checked' : ''); ?> disabled>
                                    <label for="Mon" class="text-sm">Vocational Level / Graduate</label>
                                </td>
                                <td class="align-middle">
                                    <input type="checkbox" class="day" id="college_level" value="1" <?php echo e(($details->glits >= 17 && $details->glits <= 21) ? 'checked' : ''); ?> disabled>
                                    <label for="Mon" class="text-sm">College Level</label>
                                </td>
                                <td class="align-middle">
                                    <input type="checkbox" class="day" id="college_grad" value="1" disabled>
                                    <label for="Mon" class="text-sm">College Graduate</label>
                                </td>
                            </tr>
                        </table>
                        <div class="row mt-2 pt-1">
                            <div class="row col-md-6">
                                <div class="col-md-5">Junior High School Completed At:</div>
                                <div class="col-md-7"><input type="text" value="<?php echo e($details->jhsschoolname); ?>" style="width: 90%;" id="input-schoolname-junior" /></div>
                            </div>
                             <div class="row col-md-6">
                                <div class="col-md-5">Senior High School Completed At:</div>
                                <div class="col-md-7"><input type="text" value="<?php echo e($details->shsschoolname); ?>" style="width: 90%;" id="input-schoolname-senior" /></div>
                            </div>
                        </div>
                        <div class="row mt-2 pt-1">
                            <div class="col-md-2">Last School Attended </div>
                            <div class="col-md-4"><input type="text" value="<?php echo e($studentinfo->lastschoolatt); ?>" style="width: 90%;" id="last_school_att" /></div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4">Date Graduated / Last Term and School Year Attended:</div>
                            <div class="col-md-4"><input type="text" value="<?php echo e($studentinfo->lastschoolsy); ?>" style="width: 90%;" id="last_school_att_date" /></div>
                        </div>
                    </div>
                </div>
            <div class="col-md-12 text-right mt-2">
                <button type="button" class="btn btn-outline-primary" id="btn-details-save"><i
                        class="fa fa-share"></i>&nbsp; Save Changes</button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mb-2">
        <button type="button" class="btn btn-info btn-sm" id="btn-addnewrecord" data-toggle="modal"
            data-target="#modal-newrecord"><i class="fa fa-plus"></i> Add new record</button>
    </div>
    <?php if(count($records) > 0): ?>
        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-12 <?php if($record->type == 'auto'): ?> auto-disabled <?php endif; ?>">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <h6><strong><?php echo e($record->sydesc); ?> / <?php if($record->semid == 1): ?>
                                            1st Semester
                                        <?php elseif($record->semid == 2): ?>
                                            2nd Semester
                                        <?php elseif($record->semid == 3): ?>
                                            Summer
                                        <?php endif; ?> </strong></h6>
                            </div>
                            <div class="col-md-8">
                                <h6><strong><?php echo e($record->coursename); ?></strong></h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="row">
                                    <div class="col-2">
                                        <label>School ID</label>
                                        <input type="text" class="form-control form-control-sm"
                                            value="<?php echo e($record->schoolid); ?>" style="border: none;" readonly />
                                    </div>
                                    <div class="col-4">
                                        <label>School Name</label>
                                        <input type="text" class="form-control form-control-sm"
                                            value="<?php echo e($record->schoolname); ?>" style="border: none;" readonly />
                                    </div>
                                    <div class="col-6">
                                        <label>School Address</label>
                                        <input type="text" class="form-control form-control-sm"
                                            value="<?php echo e($record->schooladdress); ?>" style="border: none;" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 text-right mb-2">
                                
                                
                                
                                <button type="button" <?php echo e($record->type == 'auto' ? 'hidden' : ''); ?>

                                    class="btn btn-sm btn-default btn-adddata text-success"
                                    data-torid="<?php echo e($record->id); ?>" data-semid="<?php echo e($record->semid); ?>"
                                    data-sydesc="<?php echo e($record->sydesc); ?>"
                                    data-courseid="<?php echo e($record->courseid); ?>"><i class="fa fa-plus"></i> Add
                                    Data</button>
                                <button type="button" <?php echo e($record->type == 'auto' ? 'hidden' : ''); ?>

                                    class="btn btn-sm btn-default btn-editrecord"
                                    data-torid="<?php echo e($record->id); ?>"><i class="fa fa-edit text-warning"></i> Edit
                                    Record Info</button>
                                <button type="button" <?php echo e($record->type == 'auto' ? 'hidden' : ''); ?>

                                    class="btn btn-default btn-sm text-danger btn-deleterecord"
                                    data-torid="<?php echo e($record->id); ?>"><i class="fa fa-trash"></i> Delete this
                                    record</button>

                                
                            </div>
                        </div>
                    </div>
                    <?php if(count($record->subjdata) > 0): ?>
                        <div class="card-body p-0">
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <table class="table" style="font-size: 14px;">
                                        <thead class="text-center">
                                            <tr>
                                                <th style="width: 15%;">Subject Code</th>
                                                <th style="width: 10%;">Units</th>
                                                <th>Description</th>
                                                <th style="width: 11%;">Grade</th>
                                                <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc' ||
                                                        strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ccsa'): ?>
                                                    <th style="width: 11%;">Re-Ex</th>
                                                <?php endif; ?>
                                                <th style="width: 11%;">Credits</th>
                                                <?php if($record->type != 'auto'): ?>
                                                    <th style="width: 15%;"></th>
                                                <?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody id="">
                                            
                                            <?php $__currentLoopData = collect($record->subjdata)->unique('subjcode'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td class="p-0"><input type="text"
                                                            class="form-control form-control-sm input-subjcode"
                                                            placeholder="Code" value="<?php echo e($subj->subjcode); ?>"
                                                            disabled /></td>
                                                    <td class="p-0"><input type="number"
                                                            class="form-control form-control-sm input-subjunit"
                                                            placeholder="Units" value="<?php echo e($subj->subjunit); ?>"
                                                            disabled /></td>
                                                    <td class="p-0"><input type="text"
                                                            class="form-control form-control-sm input-subjdesc"
                                                            placeholder="Description"
                                                            value="<?php echo e($subj->subjdesc); ?>" disabled /></td>
                                                    
                                                        <td class="p-0"><input type="text"
                                                                class="form-control form-control-sm input-subjgrade"
                                                                placeholder="Grade" value="<?php echo e($subj->subjgrade); ?>"
                                                                disabled /></td>
                                                        
                                                        <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc' ||
                                                                strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ccsa'): ?>
                                                            <td class="p-0"><input type="number"
                                                                    class="form-control form-control-sm input-subjreex"
                                                                    placeholder="Re-Ex"
                                                                    value="<?php echo e($subj->subjreex ?? null); ?>" disabled />
                                                            </td>
                                                        <?php endif; ?>
                                                        <td class="p-0"><input type="number"
                                                                class="form-control form-control-sm input-subjcredit"
                                                                placeholder="Credit" value="<?php echo e($subj->subjcredit); ?>"
                                                                disabled /></td>
                                                    
                                                        <td class="p-0"><input type="text"
                                                            class="form-control form-control-sm input-subjgrade"
                                                            placeholder="Grade" value=""
                                                            disabled /></td>
                                                        <td class="p-0"><input type="number"
                                                            class="form-control form-control-sm input-subjcredit"
                                                            placeholder="Credit" value=""
                                                            disabled /></td>
                                                    
                                                    <?php if($record->type != 'auto'): ?>
                                                        <td class="p-0 text-right"><button type="button"
                                                                class="btn btn-default btn-sm btn-editdata"
                                                                data-subjgradeid="<?php echo e($subj->id); ?>"><i
                                                                    class="fa fa-edit text-warning"></i></button><button
                                                                type="button"
                                                                class="btn btn-default btn-sm btn-editdata-save"
                                                                data-subjgradeid="<?php echo e($subj->id); ?>" disabled><i
                                                                    class="fa fa-share text-success"></i></button><button
                                                                type="button"
                                                                class="btn btn-default btn-sm btn-delete-subjdata"
                                                                data-subjgradeid="<?php echo e($subj->id); ?>" disabled><i
                                                                    class="fa fa-trash text-danger"></i></button></td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc'): ?>
                <div class="col-md-12">
                    <div class="card p-0">
                        <div class="card-body p-0">
                            <div class="col-md-12 p-0">
                                <div class="alert alert-info m-0" role="alert">
                                    Note: Insert texts here. Please separate each line.
                                </div>
                            </div>
                            <?php if(count($record->texts) > 0): ?>
                                <?php $__currentLoopData = $record->texts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eachtext): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="row mb-2">
                                        <div class="col-9">
                                            <input type="text" class="form-control form-control-sm"
                                                class="texts" value="<?php echo e($eachtext->description); ?>" />
                                        </div>
                                        <div class="col-3 text-right">
                                            <button type="button"
                                                class="btn btn-sm btn-default text-success btn-save-text"
                                                data-id="<?php echo e($eachtext->id); ?>"
                                                data-sydesc="<?php echo e($eachtext->sydesc); ?>"
                                                data-semid="<?php echo e($eachtext->semid); ?>"><i class="fa fa-share"></i>
                                                Save changes</button>
                                            <button type="button"
                                                class="btn btn-sm btn-default text-danger btn-delete-text"
                                                data-id="<?php echo e($eachtext->id); ?>"><i class="fa fa-trash-alt"></i>
                                                Remove</button>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                            <div class="col-md-12 text-right mt-2 mb-2">
                                <button type="button" class="btn btn-outline-success btn-addtexts"
                                    data-sydesc="<?php echo e($record->sydesc); ?>" data-semid="<?php echo e($record->semid); ?>"><i
                                        class="fa fa-plus"></i> Add Text</button>
                            </div>
                            <div class="col-md-12 container-texts"></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>


    <div class="modal fade" id="modal-newrecord" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Record</h4>
                    <button type="button" id="closeremarks" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label>School ID</label>
                            <input type="text" class="form-control" id="input-schoolid" />
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label>School Name</label>
                            <input type="text" class="form-control" id="input-schoolname" />
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label>School Address</label>
                            <input type="text" class="form-control" id="input-schooladdress" />
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label>Select School Year</label>
                            <select class="form-control select2" id="select-sy">
                                <option value="0">Not on this selection</option>
                                <?php $__currentLoopData = $schoolyears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schoolyear): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($schoolyear->syid); ?>"><?php echo e($schoolyear->sydesc); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6" id="div-customsy">
                            <label>Custom School Year</label>
                            <input type="text" class="form-control" id="input-sy" />
                            <small id="small-inputsy" class="text-danger">*Please fill in custom school year</small>
                        </div>
                        <small id="small-selectsy" class="text-danger col-md-12">*Please select school year. If not
                            on the selection, please specify in the next highlighted field</small>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label>Select Semester</label>
                            <select class="form-control select2" id="select-sem">
                                <?php $__currentLoopData = DB::table('semester')->where('deleted', '0')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($semester->id); ?>"><?php echo e($semester->semester); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label>Select Course</label>
                            <select class="form-control select2" id="select-course">
                                <option value="0">Not on this selection</option>
                                <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($course->id); ?>"><?php echo e($course->courseDesc); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <small id="small-selectcourse" class="text-danger">*Please select course. If not on the
                                selection, please specify in the next highlighted field </small>
                        </div>
                    </div>
                    <div class="row mb-2" id="div-customcourse">
                        <div class="col-md-12">
                            <label>Custom Course</label>
                            <input type="text" class="form-control" id="input-coursename" />
                            <small id="small-inputcoursename">*Please fill in custom course</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" id="btn-close-addnewrecord"
                        data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-submit-addnewrecord">Submit</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>



    <div class="modal fade" id="modal-updaterecord" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Record</h4>
                    <button type="button" id="closeremarks" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="container-editrecord">

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" id="btn-close-updaterecord"
                        data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-submit-updaterecord">Save
                        Changes</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
<?php
  $semester = DB::table('semester')->orderBy('id', 'asc')->get();
?>
<!-- /.modal-dialog -->
<script>
    $('.btn-addtexts').on('click', function() {
        var thiscard = $(this).closest('.card');
        var thiscontainer = thiscard.find('.container-texts');
        var sydesc = $(this).attr('data-sydesc');
        var semid = $(this).attr('data-semid');
        thiscontainer.append(
            '<div class="row mb-2">' +
            '<div class="col-9">' +
            '<input type="text" class="form-control form-control-sm" class="texts"/>' +
            '</div>' +
            '<div class="col-3 text-right">' +
            '<button type="button" class="btn btn-sm btn-default text-danger btn-remove"><i class="fa fa-times"></i> Remove</button>&nbsp;&nbsp;' +
            '<button type="button" class="btn btn-sm btn-default text-success btn-save-text" data-id="0" data-sydesc="' +
            sydesc + '" data-semid="' + semid + '"><i class="fa fa-share"></i> Save</button>' +
            '</div>' +
            '</div>'
        )
    })
    $(document).on('click', '.btn-remove', function() {
        $(this).closest('.row').remove()
    })
</script>

<script>
    $(document).ready(function() {
        $('#img-preview').hide()
        $('.upload-result').hide()
        $uploadCrop = $('#img-preview').croppie({
            enableExif: true,
            viewport: {
                width: 304,
                height: 289,
                // type: 'circle'        
            },
            boundary: {
                width: 304,
                height: 289
            }
        });
        var studid = <?php echo json_encode($studentid, 15, 512) ?>
        
        $(document).on('hide', '.swal2-loading', function() {
            setTimeout(function() {
                    get_data()
            }, 500);
        })
        get_data()
        function get_data() {
              $.ajax({
                url: "/schoolform/tor/getinfo",
                type: "GET",
                data: {
                    "_token": "<?php echo e(csrf_token()); ?>",
                    studid: studid
                },
                success: function(data) {
                    $('#studentIdNumber').html(data.sid)
                    $('#studentLevel').html(data.levelname)
                    $('#studentCourse').html(data.courseDesc)
                }
            });
        }
        $(document).on('click', '#credSubjects', function() {
            $('#creditedSubjects').modal('show')
            get_schoolcred_subjcred()
        })

        function get_schoolcred_subjcred(){
            $.ajax({
                type: 'GET',
                url: '/superadmin/student/grade/evaluation/get/credit',
                data: {
                    studid: studid
                }, success: function(data) {
                    $('.school_credit_modal_table').remove()
                    append_credited_schools(data);
                    insert_data_to_credited_table(data)
                }
                
            })
        }

        var semesters = <?php echo json_encode($semester, 15, 512) ?>;
        
        function append_credited_schools(data) {
            if (data.length == 0) {
                $('.no_credit').empty();
                $('.no_credit').append(`
                    <p class="mb-0 text-sm font-weight-bold">NO CREDITED UNITS RECORDED</p>
                    <span class="text-sm">(<i class="text-info">Please add subject/s to credit at the Grade Evaluation Module</i>)</span>
                `);
            } else {
                $('.no_credit').empty();
                $.each(data, function (key, school) {
                    $('#credited_table').append(`
                        <div style="font-size: 15px;" class="ml-1 font-weight-bold school_credit_modal_table">${school.schoolname}
                            <button class="btn btn-sm edit_school_credit d-none" data-id="${school.headerid}">
                                <i class="fas fa-edit text-sm text-info ml-2"></i>
                            </button>
                            <button class="btn btn-sm delete_school_credit d-none" data-id="${school.headerid}">
                                <i class="fas fa-trash text-sm text-danger ml-2"></i>
                            </button>
                        </div>
                    `);

                    $.each(semesters, function (index, sem) {
                        
                        $('#credited_table').append(`
                            <div class="card mt-2 d-none school_div_credit-${school.headerid}-${sem.id} school_credit_modal_table">
                                <div class="card-body">
                                    <div>
                                        <div class="d-flex flex-row justify-content-between mb-1">
                                            <div style="font-size: 12px; font-weight: bold">${school.sydesc} - ${sem.semester}</div>
                                        </div>
                                        <table width="100%" class="table table-sm table-bordered table-striped" id="school_credit-${school.headerid}-${sem.id}">
                                            <thead>
                                                <tr style="font-size: 11px">
                                                    <th width="7%">Code</th>
                                                    <th width="25%">Subject Description</th>
                                                    <th width="25%">Pre-Requisite</th>
                                                    <th width="7%" class="text-center">Lecture</th>
                                                    <th width="7%" class="text-center">Laboratory</th>
                                                    <th width="7%" class="text-center">Credited Units</th>
                                                    <th width="7%" class="text-center">GPA</th>
                                                    <th width="7%" class="text-center">Credited</th>
                                                    <th width="8%" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <td contenteditable="true" class="credit_subjCode bg-info" data-school="${school.headerid}" data-sem="${sem.id}"></td>
                                                    <td contenteditable="true" class="credit_subjDesc bg-info" data-school="${school.headerid}" data-sem="${sem.id}"></td>
                                                    <td class="credit_subjPrereq"></td>
                                                    <td contenteditable="true" class="credit_subjLec bg-info text-center" data-school="${school.headerid}" data-sem="${sem.id}"></td>
                                                    <td contenteditable="true" class="credit_subjLab bg-info text-center" data-school="${school.headerid}" data-sem="${sem.id}"></td>
                                                    <td contenteditable="true" class="credit_subjCred bg-info text-center" data-school="${school.headerid}" data-sem="${sem.id}"></td>
                                                    <td contenteditable="true" class="credit_subjGPA text-center bg-info" data-school="${school.headerid}" data-sem="${sem.id}"></td>
                                                    <td></td>
                                                    <td class="text-center">
                                                        <button class="btn btn-sm btn-success credit_to_prospectus_subject" data-school="${school.headerid}" data-sem="${sem.id}" data-id="${school.credsubj?.[0]?.levelID || ''}">
                                                            <i class="fa fa-save"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <div class="d-flex flex-row mb-1">
                                            <div style="font-size: 12px;color: red"><i>Please Input Non-Credited Subjects To be Displayed on TOR</i></div>
                                            <div class="d-flex flex-row text-success ml-auto align-middle">
                                                <i class="fa fa-check text-lg  ml-auto"></i> - Credited
                                            </div>
                                            <div class="d-flex flex-row text-danger ml-2 align-middle">
                                                <i class="fa fa-times text-lg  ml-auto"></i> - Not-Credited
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
                    });
                });
            }
        }
        function insert_data_to_credited_table(data){
            $.each(data, function(key, school) {
                $.each(semesters, function(key, sem) {
                    $.each(school.credsubj, function(key, cred) {
                        $('.school_div_credit-' + school.headerid + '-' + cred.semid).removeClass('d-none')
                    })
                    let credited_subj = school.credsubj.filter(cred => cred.schoolID == school.headerid && cred.semid == sem.id)
                    $('#school_credit-' + school.headerid + '-' + sem.id).DataTable({
                        destroy: true,
                        order: false,
                        data: credited_subj,
                        lengthChange: false,
                        info: false,
                        paging: false,
                        searching: false,
                        columns: [
                            {data: 'subjCode'},
                            {data: 'subjDesc'},
                            {data: null},
                            {data: null},
                            {data: null},
                            {data: null},
                            {data: null},
                            {data: null},
                            {data: null},
                        ],
                        columnDefs: [
                            {
                                targets: 0,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    let credpreq = rowData.credpreq.length > 0 ? rowData.credpreq[0].subjDesc : ''
                                    $(td).html(`${rowData.subjCode}`)
                                    .addClass('mb-0 align-middle credited_subjCode')
                                    .attr('data-school', rowData.schoolID)
                                    .attr('data-sem', rowData.semid)
                                    .attr('data-id', rowData.id)
                                }
                            },
                            {
                                targets: 1,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    let credpreq = rowData.credpreq.length > 0 ? rowData.credpreq[0].subjDesc : ''
                                    $(td).html(`${rowData.subjDesc}`)
                                    .addClass('mb-0 align-middle credited_subjDesc')
                                    .attr('data-school', rowData.schoolID)
                                    .attr('data-sem', rowData.semid)
                                    .attr('data-id', rowData.id)
                                }
                            },
                            {
                                targets: 2,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    let credpreq = rowData.credpreq.length > 0 ? rowData.credpreq[0].subjDesc : ''
                                    $(td).html(`<p class="mb-0">${credpreq}</p>`)
                                    .addClass('mb-0 ')
                                }
                            },
                            {
                                targets: 3,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html(`<p class="mb-0">${rowData.lecunits ? rowData.lecunits : ''}</p>`)
                                    .addClass('text-center  align-middle credited_lecunits')
                                    .attr('data-school', rowData.schoolID)
                                    .attr('data-sem', rowData.semid)
                                    .attr('data-id', rowData.id)
                                }
                            },
                            {
                                targets: 4,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html(`<p  class="mb-0">${rowData.labunits ? rowData.labunits : ''}</p>`)
                                    .addClass('text-center m-0 mb-0  align-middle credited_labunits')
                                    .attr('data-school', rowData.schoolID)
                                    .attr('data-sem', rowData.semid)
                                    .attr('data-id', rowData.id)
                                }
                            },
                            {
                                targets: 5,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html(`<p  class="mb-0">${rowData.credunits ? rowData.credunits : ''}</p>`)
                                    .addClass('text-center p-0 mb-0  align-middle credited_credunits')
                                    .attr('data-school', rowData.schoolID)
                                    .attr('data-sem', rowData.semid)
                                    .attr('data-id', rowData.id)
                                }
                            },
                            {
                                targets: 6,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html(`<p  class="mb-0">${rowData.gpa != null ? rowData.gpa : ''}</p>`)
                                    .addClass('text-center p-0 mb-0  align-middle credited_gpa')
                                    .attr('data-school', rowData.schoolID)
                                    .attr('data-sem', rowData.semid)
                                    .attr('data-id', rowData.id)
                                }
                            },
                            {
                                targets: 7,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    if(rowData.status == 0){
                                        $(td).html(`<p  class="mb-0"><i class="fa fa-check text-success"></i></p>`)
                                    }else{
                                        $(td).html(`<p  class="mb-0"><i class="fa fa-times text-danger"></i></p>`)
                                    }
                                    $(td).addClass('text-center p-0 mb-0  align-middle')
                                }
                            },
                            {
                                targets: 8,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    if(rowData.status == 1){
                                        $(td).html(`<p  class="mb-0">
                                            <button class="btn btn-sm save_edit_additional_credit d-none" data-id="${rowData.id}" data-school="${rowData.schoolID}" data-sem="${rowData.semID}"><i class="fa fa-save text-success"></i></button>
                                            <button class="btn btn-sm close_edit_additional_credit d-none" data-id="${rowData.id}" data-school="${rowData.schoolID}" data-sem="${rowData.semID}"><i class="fa fa-window-close text-danger"></i></button>
                                            <button class="btn btn-sm edit_additional_credit" data-id="${rowData.id}" data-school="${rowData.schoolID}" data-sem="${rowData.semID}"><i class="fa fa-edit text-primary"></i></button>
                                            <button class="btn btn-sm delete_additional_credit" data-id="${rowData.id}"><i class="fa fa-trash text-danger"></i></button>
                                            </p>`)
                                        .addClass('text-center mb-0  align-middle ')

                                    }else{
                                        $(td).html(`<p  class="mb-0"></p>`)
                                        .addClass('text-center mb-0  align-middle ')

                                    }
                                }
                            },
                        ]
                    })
                })
            })
        }


        

        $(document).on('change', '#input-upload-photo', function() {
            var reader = new FileReader();
            reader.onload = function(e) {
                $uploadCrop.croppie('bind', {
                    url: e.target.result
                }).then(function() {
                    console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
            $('#img-preview').show()
            $('.upload-result').show()
        });
        $('.upload-result').on('click', function(ev) {
            var studid = $('#select-studentid').val();
            $uploadCrop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function(resp) {
                $.ajax({
                    url: "/setup/studdisplayphoto/uploadphoto",
                    type: "POST",
                    data: {
                        "_token": "<?php echo e(csrf_token()); ?>",
                        "image": resp,
                        "studid": '<?php echo e($studentinfo->id); ?>'
                    },
                    success: function(data) {
                        $('#image-view').attr('src', data + '?random=' + new Date($
                            .now()))
                        $('.upload-result').hide()
                        $('#img-preview').hide()
                        $('#input-upload-photo').val('')
                    }
                });
            });
        });
    })
</script>
<?php /**PATH C:\laragon\www\es_ldcu\resources\views/registrar/forms/tor/torrecords.blade.php ENDPATH**/ ?>