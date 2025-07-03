<head>
    <title>Student Masterlist</title>
</head>

<style>
    html {
        /*text-transform: uppercase;*/

        font-family: Arial, Helvetica, sans-serif;
    }

    .logo {
        width: 100%;
        table-layout: fixed;
    }

    .header {
        width: 100%;
    }

    .studentsMale th,
    .studentsMale td,
    .studentsFemale th,
    .studentsFemale td {
        border: 1px solid black;
    }

    .logo td,
    .header td {
        /* border: 1px solid black; */
    }

    .studentsMale {
        font-size: 11px;
        table-layout: fixed;
        font-family: Arial, Helvetica, sans-serif;
        border-spacing: 0;
    }

    .studentsFemale {
        font-size: 11px;
        table-layout: fixed;
        font-family: Arial, Helvetica, sans-serif;
        border-spacing: 0;
    }

    .studentsFemale td,
    .studentsMale td {
        border-top: hidden;
    }

    .studentsFemale th,
    .studentsMale th {
        text-align: center;
    }

    .total {
        text-align: left;
        font-size: 11px;
        width: 20%;
        table-layout: fixed;
        font-family: Arial, Helvetica, sans-serif;
        border-spacing: 0;
    }

    .total td {
        border: 1px solid black;
        text-align: center;
    }

    .clear:after {
        clear: both;
        content: "";
        display: table;
        border: 1px solid black;
    }

    table {
        border-collapse: collapse;
    }

    @media  print {
        button.download {
            display: none;
        }
    }

    footer {
        position: fixed;
        bottom: -50px;
        left: 0px;
        right: 0px;
        height: 100px;

        /** Extra personal styles **/
        color: black;
        text-align: left;
        line-height: 20px;
    }

    @page  {
        margin: 20px 30px
    }
</style>
<?php

    $signatories = DB::table('signatory')
        ->where('form', 'report_masterlist')
        ->where('syid', $syid)
        ->where('deleted', '0')
        ->where('acadprogid', $acadprogid)
        ->get();

    if (count($signatories) == 0) {
        $signatories = DB::table('signatory')
            ->where('form', 'report_masterlist')
            ->where('syid', $syid)
            ->where('deleted', '0')
            ->where('acadprogid', 0)
            ->get();

        if (count($signatories) > 0) {
            if (collect($signatories)->where('levelid', $levelid)->count() == 0) {
                $signatories = collect($signatories)->where('levelid', 0)->values();
            } else {
                $signatories = collect($signatories)->where('levelid', $levelid)->values();
            }
        }
    } else {
        if (collect($signatories)->where('levelid', $levelid)->count() == 0) {
            $signatories = collect($signatories)->where('levelid', 0)->values();
        } else {
            $signatories = collect($signatories)->where('levelid', $levelid)->values();
        }
    }
?>

<table class="logo">
    <tr>
        <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi'): ?>
            <td width="25%" style="text-align: right;">
                <?php if(!empty($schoolinfo[0]->picurl)): ?>
                    <img src="<?php echo e(base_path()); ?>/public/<?php echo e($schoolinfo[0]->picurl); ?>" alt="school" width="70px">
                <?php endif; ?>
            </td>
            <td>
                <center>
                    
                    <strong><?php echo e($schoolinfo[0]->schoolname); ?></strong>
                    <br>
                    <span style="font-size: 11px;"><?php echo e($schoolinfo[0]->address); ?></span>
                    

                </center>
            </td>
            <td width="25%"></td>
        <?php else: ?>
            <td width="15%">
                <?php if(!empty($schoolinfo[0]->picurl)): ?>
                    <img src="<?php echo e(base_path()); ?>/public/<?php echo e($schoolinfo[0]->picurl); ?>" alt="school" width="70px">
                <?php endif; ?>
            </td>
            <td>
                
                
                <strong><?php echo e($schoolinfo[0]->schoolname); ?></strong>
                <br>
                <span style="font-size: 11px;"><?php echo e($schoolinfo[0]->address); ?></span>
                

                
            </td>
            <td width="15%"></td>
        <?php endif; ?>
    </tr>
</table>
<table class="header">
    <tr>
        <td width="15%"></td>
        <td style="width: 25%;">
            <span style="font-size: 11px;"><strong>School Year: </strong></span>
        </td>
        <td>
            <span style="font-size: 11px;"><u><?php echo e($schoolyear[0]->sydesc); ?></u></span>
        </td>
    </tr>
    <tr>
        <td width="15%"></td>
        <td>
            <span style="font-size: 11px;"><strong>Grade Level & Section:</strong></span>
        </td>
        <?php if($sectionid > 0): ?>
            <td>
                <span style="font-size: 11px;"><u><?php echo e($data[0]->gradelevelname); ?> -
                        <?php echo e($data[0]->sectionname); ?></u></span>
            </td>
        <?php else: ?>
            <td>
                <span style="font-size: 11px;"><u><?php echo e($data[0]->gradelevelname); ?> - ALL SECTIONS</u></span>
            </td>
        <?php endif; ?>
    </tr>
    <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hccsi'): ?>
        <tr>
            <td width="15%"></td>
            <td>
                <span style="font-size: 11px;"><strong>Adviser:</strong></span>
            </td>
            <td>
                <span
                    style="font-size: 11px;"><u><?php echo e(is_object($teacher) ? trim(($teacher->firstname ?? '') . ' ' . ($teacher->lastname ?? '')) : $teacher ?? ''); ?></u></span>
            </td>
        </tr>
    <?php endif; ?>
    <tr>
        <td width="15%"></td>
        <td>
            <span style="font-size: 11px;"><strong>Room:</strong></span>
        </td>
        <td>
            <span style="font-size: 11px;"><u><?php echo e($roomname); ?></u></span>
        </td>
    </tr>
    
</table>
<br>
<span style="font-size: 12px;">
    <center><strong>List of Students</strong></center>
</span>
<?php if($esc == 1): ?>
    <span style="font-size: 12px;">
        <center><strong>(ESC Grantees)</strong></center>
    </span>
<?php endif; ?>
<br>
<?php if($acadprogid == 5 || $acadprogid == 6): ?>
    <?php
        $strands = collect($data)->groupBy('strandcode')->all();
    ?>
    <?php $__currentLoopData = $strands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eachkey => $eachstrand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div style="font-size: 15px; background-color: #ddd;">
            <center><strong><?php echo e($eachkey); ?></strong></center>
        </div>
        <?php if(collect($eachstrand)->where('gender', 'male')->count() == 0 ||
                collect($eachstrand)->where('gender', 'female')->count() == 0): ?>
            <?php
                $width = '100%';
            ?>
        <?php elseif(collect($eachstrand)->where('gender', 'male')->count() != 0 &&
                collect($eachstrand)->where('gender', 'female')->count() != 0): ?>
            <?php
                $width = '50%';
            ?>
        <?php endif; ?>
        <?php
            $male = 0;
            $female = 0;
            $maxnum = max([
                collect($eachstrand)->where('gender', 'male')->count(),
                collect($eachstrand)->where('gender', 'female')->count(),
            ]);

            $collectionmale = collect($eachstrand)->where('gender', 'male')->values();
            $collectionfemale = collect($eachstrand)->where('gender', 'female')->values();
        ?>
        <table style="width:100%; font-size: 10.5px; table-layout: fixed;" border="1">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th>MALE</th>
                    <th width="5%">No.</th>
                    <th>FEMALE</th>
                </tr>
            </thead>
            <?php for($x = 0; $x < $maxnum; $x++): ?>
                <tr>
                    <td style="text-align: center;">
                        <?php if(isset($collectionmale[$x])): ?>
                            <?php echo e($x + 1); ?>

                        <?php endif; ?>
                    </td>
                    <td style="padding-left: 10px;">
                        <?php if(isset($collectionmale[$x])): ?>
                            <?php if($format == 'lastname_first'): ?>
                                <?php echo e(ucwords(mb_strtolower($collectionmale[$x]->student_lastname, 'UTF-8'))); ?>,
                                <?php echo e(ucwords(strtolower($collectionmale[$x]->student_firstname))); ?>

                                <?php echo e(isset($collectionmale[$x]->student_middlename[0]) ? ucwords(strtolower($collectionmale[$x]->student_middlename[0] . '.')) : ''); ?>

                                <?php echo e($collectionmale[$x]->student_suffix); ?>

                            <?php else: ?>
                                <?php echo e(ucwords(strtolower($collectionmale[$x]->student_firstname))); ?>

                                <?php echo e(isset($collectionmale[$x]->student_middlename[0]) ? ucwords(strtolower($collectionmale[$x]->student_middlename[0] . '.')) : ''); ?>

                                <?php echo e(ucwords(mb_strtolower($collectionmale[$x]->student_lastname, 'UTF-8'))); ?>

                                <?php echo e($collectionmale[$x]->student_suffix); ?>

                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php if(isset($collectionfemale[$x])): ?>
                            <?php echo e($x + 1); ?>

                        <?php endif; ?>
                    </td>
                    <td style="padding-left: 10px;">
                        <?php if(isset($collectionfemale[$x])): ?>
                            <?php if($format == 'lastname_first'): ?>
                                <?php echo e(ucwords(mb_strtolower($collectionfemale[$x]->student_lastname, 'UTF-8'))); ?>,
                                <?php echo e(ucwords(strtolower($collectionfemale[$x]->student_firstname))); ?>

                                <?php echo e(isset($collectionfemale[$x]->student_middlename[0]) ? ucwords(strtolower($collectionfemale[$x]->student_middlename[0] . '.')) : ''); ?>

                                <?php echo e($collectionfemale[$x]->student_suffix); ?>

                            <?php else: ?>
                                <?php echo e(ucwords(strtolower($collectionfemale[$x]->student_firstname))); ?>

                                <?php echo e(isset($collectionfemale[$x]->student_middlename[0]) ? ucwords(strtolower($collectionfemale[$x]->student_middlename[0] . '.')) : ''); ?>

                                <?php echo e(ucwords(mb_strtolower($collectionfemale[$x]->student_lastname, 'UTF-8'))); ?>

                                <?php echo e($collectionfemale[$x]->student_suffix); ?>

                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endfor; ?>

        </table>
        
        <div style="clear: both;"></div>
        <br>
        
        
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <table class="total">
        <tr>
            <td style="text-align: left;">
                <strong>&nbsp;&nbsp;Male</strong>
            </td>
            <td>
                <strong><?php echo e(collect($data)->where('gender', 'male')->count()); ?></strong>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;">
                <strong>&nbsp;&nbsp;Female</strong>
            </td>
            <td>
                <strong><?php echo e(collect($data)->where('gender', 'female')->count()); ?></strong>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;">
                <strong>&nbsp;&nbsp;Total</strong>
            </td>
            <td>
                <strong><?php echo e(collect($data)->count()); ?></strong>
            </td>
        </tr>
    </table>
<?php else: ?>
    <?php if($genderCount['maleCount'] == 0 || $genderCount['femaleCount'] == 0): ?>
        <?php
            $width = '100%';
        ?>
    <?php elseif($genderCount['maleCount'] != 0 && $genderCount['femaleCount'] != 0): ?>
        <?php
            $width = '50%';
            // if($sectionid > 0)
            // {
            // $width = '50%';
            // }else{
            // $width = '100%';
            // }
        ?>
    <?php endif; ?>
    <?php
        $male = 0;
        $female = 0;
        $maxnum = max([$genderCount['maleCount'], $genderCount['femaleCount']]);

        $collectionmale = collect($data)->where('gender', 'male')->values();
        $collectionfemale = collect($data)->where('gender', 'female')->values();
    ?>
    
    <table style="width:100%; font-size: 10.5px; table-layout: fixed;" border="1">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th>MALE</th>
                <th width="5%">No.</th>
                <th>FEMALE</th>
            </tr>
        </thead>
        <?php for($x = 0; $x < $maxnum; $x++): ?>
            <tr>
                <td style="text-align: center;">
                    <?php if(isset($collectionmale[$x])): ?>
                        <?php echo e($x + 1); ?>

                    <?php endif; ?>
                </td>
                <td style="padding-left: 10px;">
                    <?php if(isset($collectionmale[$x])): ?>
                        <?php if($format == 'lastname_first'): ?>
                            <?php echo e(ucwords(mb_strtolower($collectionmale[$x]->student_lastname, 'UTF-8'))); ?>,
                            <?php echo e(ucwords(strtolower($collectionmale[$x]->student_firstname))); ?>

                            <?php echo e(isset($collectionmale[$x]->student_middlename[0]) ? ucwords(strtolower($collectionmale[$x]->student_middlename[0] . '.')) : ''); ?>

                            <?php echo e($collectionmale[$x]->student_suffix); ?>

                        <?php else: ?>
                            <?php echo e(ucwords(strtolower($collectionmale[$x]->student_firstname))); ?>

                            <?php echo e(isset($collectionmale[$x]->student_middlename[0]) ? ucwords(strtolower($collectionmale[$x]->student_middlename[0] . '.')) : ''); ?>

                            <?php echo e(ucwords(mb_strtolower($collectionmale[$x]->student_lastname, 'UTF-8'))); ?>

                            <?php echo e($collectionmale[$x]->student_suffix); ?>

                        <?php endif; ?>
                    <?php endif; ?>
                </td>
                <td style="text-align: center;">
                    <?php if(isset($collectionfemale[$x])): ?>
                        <?php echo e($x + 1); ?>

                    <?php endif; ?>
                </td>
                <td style="padding-left: 10px;">
                    <?php if(isset($collectionfemale[$x])): ?>
                        <?php if($format == 'lastname_first'): ?>
                            <?php echo e(ucwords(mb_strtolower($collectionfemale[$x]->student_lastname, 'UTF-8'))); ?>,
                            <?php echo e(ucwords(strtolower($collectionfemale[$x]->student_firstname))); ?>

                            <?php echo e(isset($collectionfemale[$x]->student_middlename[0]) ? ucwords(strtolower($collectionfemale[$x]->student_middlename[0] . '.')) : ''); ?>

                            <?php echo e($collectionfemale[$x]->student_suffix); ?>

                        <?php else: ?>
                            <?php echo e(ucwords(strtolower($collectionfemale[$x]->student_firstname))); ?>

                            <?php echo e(isset($collectionfemale[$x]->student_middlename[0]) ? ucwords(strtolower($collectionfemale[$x]->student_middlename[0] . '.')) : ''); ?>

                            <?php echo e(ucwords(mb_strtolower($collectionfemale[$x]->student_lastname, 'UTF-8'))); ?>

                            <?php echo e($collectionfemale[$x]->student_suffix); ?>

                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endfor; ?>

    </table>
    
    <div style="clear: both;"></div>
    <br>
    <table class="total">
        <tr>
            <td>
                <strong>Male = <?php echo e(count($collectionmale)); ?></strong>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Female = <?php echo e(count($collectionfemale)); ?></strong>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Total = <?php echo e(count($collectionmale) + count($collectionfemale)); ?></strong>
            </td>
        </tr>
    </table>
<?php endif; ?>
<br />
<?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi'): ?>
    <table style="width: 100%; font-size: 12px; text-transform: unset; border-collapse: collapse; table-layout: fixed;">
        <tr>
            <td style="text-align: right;">Certified and verified under oath to be true and correct:</td>
            <td></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                &nbsp;<?php echo e(is_object($teacher) ? trim(($teacher->firstname ?? '') . ' ' . ($teacher->lastname ?? '')) : $teacher ?? ''); ?>

                &nbsp;</td>
            <td style="font-weight: bold;">CHRISTINE J. CASILAGAN&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php if($sectionid > 0): ?>
                    Adviser
                <?php endif; ?>
            </td>
            <td>School Registrar</td>
        </tr>
    </table>
<?php else: ?>
    <?php if($academicprogram != 'seniorhighschool'): ?>
        <?php if($teacher != null): ?>
            <div class="label"
                style="display:inline-block;
        background-color:White;
        width: auto; text-align:center; font-size: 12px;">
                <div class="label-text"
                    style=" float:left; text-align: center; line-height: 30px; vertical-align: center; white-space: nowrap; overflow: hidden;">
                    <span
                        style="text-align:center;border-bottom: 1px solid black;">&nbsp;<?php echo e(is_object($teacher) ? trim(($teacher->firstname ?? '') . ' ' . ($teacher->lastname ?? '')) : $teacher ?? ''); ?></span>
                    
                    <br />
                    <sup style="text-align:center">Class Adviser</sup>
                </div>
            </div>
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
        <?php endif; ?>
    <?php endif; ?>
    <?php if(count($signatories) > 0): ?>
        <table style="width: 100%; table-layout: fixed; font-size: 12px;">
            <tr>
                <?php $__currentLoopData = $signatories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $signatory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td style=""><?php echo e($signatory->title); ?></td>
                    <td></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
            <tr>
                <?php $__currentLoopData = $signatories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $signatory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td style="">&nbsp;</td>
                    <td></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
            <tr>
                <?php $__currentLoopData = $signatories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $signatory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td style="border-bottom: 1px solid black; text-align: center;"><?php echo e($signatory->name); ?></td>
                    <td></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
            <tr>
                <?php $__currentLoopData = $signatories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $signatory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td style="text-align: center;"><?php echo e($signatory->description); ?></td>
                    <td></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        </table>
        
    <?php endif; ?>
<?php endif; ?>

<?php /**PATH C:\laragon\www\es_ldcu2\resources\views/registrar/pdf/pdf_studentmasterlist.blade.php ENDPATH**/ ?>