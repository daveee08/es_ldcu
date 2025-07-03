
<style>
    *{
        
        font-family: Arial, Helvetica, sans-serif;
    }
    #header, #header td{
        font-size: 13px;
        width: 100%;
        table-layout: fixed;
    }
    #header th{
        table-layout: fixed;
        /* border: 1px solid black; */
    }
    .contentTable{
        font-size: 12px;
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
        font-family: Arial, Helvetica, sans-serif;

    }
    .contentTable th{
        text-align: center;
        border:1px solid black !important;
        border-collapse: collapse;
    }
    .contentTable td{
        text-align: center;
        border:1px solid black !important;
        border-collapse: collapse;
    }
    h2{
        margin:5px;
    }
    div.box{
        border: 1px solid black;
        padding: 3px;
        text-align: center;
        margin-top: 3px;
        text-transform: uppercase;
    }
    .cellRight{
        text-align: right;
    }
    .rotate {
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        width: 1.5em;
        padding: 10px !important;
        font-size: 11px;
    }
    .rotate div {
        -moz-transform: rotate(-90.0deg);  /* FF3.5+ */
        -o-transform: rotate(-90.0deg);  /* Opera 10.5 */
        -webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
                filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
            -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
            margin-left: -10em;
            margin-right: -10em;
    }
    .num{
        width:30px;
        border-right: 1px solid black;
        text-align: center;
        padding: 4px;
    }
    .small, small {
        font-size: 80%;
        font-weight: 400;
    }
    /* ol {
        display: block;
        list-style-type: decimal;
        margin-block-start: 1em;
        margin-block-end: 1em;
        margin-inline-start: 0px;
        margin-inline-end: 0px;
        padding-inline-start: 40px;
    } */
    li {
        display: list-item;
        text-align: -webkit-match-parent;
    }
    .container {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr; /* fraction*/
    }
    .guidelines{
        /* border: 1px solid black; */
        /* text-align: */
        table-layout: fixed;
        vertical-align: top !important;
        /* font-size: 11px; */
        padding: 0px !important;
        page-break-inside: auto !important;
        font-family: Arial, Helvetica, sans-serif;
                   
    }
    .guidelines th{
        border: 1px solid black;
        /* text-align: */
        vertical-align: top left !important;
        /* font-size: 11px; */
        
    }
    .summary{
        width: 100%;
        border-collapse: collapse;
    }
    .summary th{
        font-size: 11px;
        text-align: center;
        vertical-align: middle !important;
    }
    .summary td{
        font-size: 12px;
        text-align: center;
        vertical-align: middle !important;
        border: 1px solid black;
        /* padding: 2px; */
    }
    .late{
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 20px 20px 0 0;
        border-color: #b5afaf transparent transparent transparent;
    }
    .cc{
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 0 20px 20px;
        border-color: transparent transparent #b5afaf transparent;

    }
    .contentTable td{
        text-transform: uppercase;
    }
    #formtable {
        border-collapse:collapse;
    }
    #formtable td, #formtable th {
        border: 1px solid black;
    }
    .indicatortable td{
        border: 1px solid black;
    }
    #pSpan span         { display: block; visibility: hidden; }
</style>

<div style="page-break-inside: avoid;">
<table id="header">
    <tr>
        <th rowspan="2" width="7%">
            <img src="<?php echo e(base_path()); ?>/public/<?php echo e($forms[0]->schoolinfo->picurl); ?>" alt="school" width="80px">
        </th>
        <th colspan="11" style="padding-left:5%">
            <h2><center>School Form 1 (SF 1) School Register</center></h2>
            <small><em><center>This replaced Form 1, Master List & STS Form 2-Family Background and Profile</center></em></small>
        </th>
        <th colspan="2" rowspan="2" style="text-align: right;">
            <img src="<?php echo e(base_path()); ?>/public/assets/images/department_of_Education.png" alt="school" width="80px">
        </th>
    </tr>
    <tr>
        <th class="cellRight" width="10%">School ID</th>
        <th style="width: 10%; !important"><div class="box"><?php echo e($forms[0]->schoolinfo->schoolid); ?></div></th>
        <th class="cellRight" style="width: 5%; !important">Region</th>
        <th><div class="box"><?php echo e($forms[0]->schoolinfo->regDesc); ?></div></th>
        <th class="cellRight" style="width:5%;">Division</th>
        <th colspan="2">
            <div class="box">
                <?php echo e($forms[0]->schoolinfo->citymunDesc); ?>

            </div>
        </th>
        <th class="cellRight" style="">District</th>
        <th colspan="3">
            <div class="box">
                <?php echo e($forms[0]->schoolinfo->district); ?>

            </div>
        </th>
    </tr>
    <tr>
        <th colspan="2" class="cellRight">Name of School</th>
        <th colspan="3" ><div class="box"><?php echo e($forms[0]->schoolinfo->schoolname); ?></div></th>
        <th></th>
        <th class="cellRight">School Year</th>
        <th><div class="box"><?php echo e($forms[0]->schoolyear); ?></div></th>
        <th class="cellRight">Grade Level</th>
        <th><div class="box"><?php echo e($forms[0]->gradelevel); ?></div></th>
        <th class="cellRight">Section</th>
        <th colspan="2"><div class="box"><?php echo e($forms[0]->section); ?></div></th>
        <th >&nbsp;</th>
    </tr>
</table>
<?php
    $countstudentmale = 0;
    $countstudentfemale = 0;
?>
<table style="border: 1px solid black; width: 100%; font-size: 9px;" id="formtable">
    <thead>
        <tr>
            <th rowspan="2" style="width: 15px !important;">

            </th>
            <th rowspan="2"  style="width: 70px;">
                LRN
            </th>
            <th rowspan="2">
                NAME
                <br>
                (Last Name, First Name, Middle Name)
            </th>
            <th rowspan="2" style="width: 10px;">
                Sex
                <br>
                (M/F)
            </th>
            <th rowspan="2" style="width: 25px;">
                BIRTH DATE  
                <br>
                (mm/dd/yyyy)
            </th>
            <th rowspan="2" style="width: 10px;">
                AGE as<br>
                of 1st<br>
                Friday
                <br>
                June
            </th>
            <th rowspan="2">
                MOTHER
                <br>
                TONGUE
            </th>
            <th rowspan="2">
                IP
                <br>
                (Ethnic Group)
            </th>
            <th rowspan="2">
                RELIGION
            </th>
            <th colspan="4">
                ADDRESS
            </th>
            <th colspan="2">
                PARENTS
            </th>
            <th colspan="2">
                GUARDIAN
                <br>
                (If not Parent)
            </th>
            <th rowspan="2">
                Contact Number of Parent or Guardian
            </th>
            <th>
                REMARKS
            </th>
        </tr>
        <tr>
            <th>
                House #/Street/<br>Sitio/Purok
            </th>
            <th>
                Barangay
            </th>
            <th>
                Municipality/<br>City
            </th>
            <th>
                Province
            </th>
            <th>
                Father's Name (Last Name,<br>
                First Name, Middle Name)
            </th>
            <th>
                Mother's Maiden Name (Last<br>
                Name, First Name, Middle<br>
                Name)
            </th>
            <th>
                Name
            </th>
            <th>
                Relation-ship
            </th>
            <th>
                (Please refer to the<br>
                legend on last page)
            </th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $forms[0]->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $studentinfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(strtolower($studentinfo->gender) == 'male'): ?>
                <?php
                    $countstudentmale+=1;
                ?>
                <tr>
                    <td style="text-align: center;"><?php echo e($countstudentmale); ?></td>
                    <td>
                        <?php echo e($studentinfo->lrn); ?>

                    </td>
                    <td><?php echo e($studentinfo->lastname); ?>, <?php echo e($studentinfo->firstname); ?> <?php echo e($studentinfo->middlename); ?> <?php echo e($studentinfo->suffix); ?></td>
                    <td style="text-align: center;"><?php echo e($studentinfo->gender[0]); ?></td>
                    <td style="text-align: center;"><?php echo e($studentinfo->dob); ?></td>
                    <td style="text-align: center;"><?php echo e($studentinfo->age); ?></td>
                    <td style="text-align: center;"><?php echo e($studentinfo->mtname); ?></td>
                    <td style="text-align: center;"><?php echo e($studentinfo->egname); ?></td>
                    <td style="text-align: center;"><?php echo e($studentinfo->religionname); ?></td>
                    <td style="text-align: center;"><?php echo e($studentinfo->street); ?></td>
                    <td style="text-align: center;"><?php echo e($studentinfo->barangay); ?></td>
                    <td style="text-align: center;"><?php echo e($studentinfo->city); ?></td>
                    <td><?php echo e($studentinfo->province); ?></td>
                    <td>
                        <?php if($studentinfo->fathername != ','): ?>
                            <?php echo e($studentinfo->fathername); ?>

                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($studentinfo->mothername != ','): ?>
                            <?php echo e($studentinfo->mothername); ?>

                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($studentinfo->guardianname != ','): ?>
                            <?php echo e($studentinfo->guardianname); ?>

                        <?php endif; ?>
                    </td>
                    <td><?php echo e($studentinfo->guardianrelation); ?></td>
                    <td style="text-align: center;">
                        <?php if($studentinfo->fcontactno != null): ?>
                            <?php echo e($studentinfo->fcontactno); ?><br>
                        <?php endif; ?>
                        <?php if($studentinfo->mcontactno != null): ?>
                            <?php echo e($studentinfo->mcontactno); ?><br>
                        <?php endif; ?>
                        <?php if($studentinfo->gcontactno != null): ?>
                            <?php echo e($studentinfo->gcontactno); ?>

                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php if($studentinfo->studstatus == 2): ?> 
                            LE Date: <?php echo e($studentinfo->dateenrolled); ?>

                        <?php elseif($studentinfo->studstatus == 3): ?> 
                            DRP
                        <?php elseif($studentinfo->studstatus == 4): ?> 
                            T/I Date: <?php echo e($studentinfo->dateenrolled); ?>

                        <?php elseif($studentinfo->studstatus == 5): ?> 
                            T/O Date: <?php echo e($studentinfo->dateenrolled); ?>

                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <tr style="text-align: center;">
            <td></td>
            <td><?php echo e($countstudentmale); ?></td>
            <td>==TOTAL MALE</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <?php $__currentLoopData = $forms[0]->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $studentinfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(strtolower($studentinfo->gender) == 'female'): ?>
                <?php
                    $countstudentfemale+=1;
                ?>
                <tr>
                    <td style="text-align: center;"><?php echo e($countstudentfemale); ?></td>
                    <td>
                        <?php echo e($studentinfo->lrn); ?>

                    </td style="text-align: center;">
                    <td><?php echo e($studentinfo->lastname); ?>, <?php echo e($studentinfo->firstname); ?> <?php echo e($studentinfo->middlename); ?> <?php echo e($studentinfo->suffix); ?></td>
                    <td style="text-align: center;"><?php echo e($studentinfo->gender[0]); ?></td>
                    <td style="text-align: center;"><?php echo e($studentinfo->dob); ?></td>
                    <td style="text-align: center;"><?php echo e($studentinfo->age); ?></td>
                    <td style="text-align: center;"><?php echo e($studentinfo->mtname); ?></td>
                    <td style="text-align: center;"><?php echo e($studentinfo->egname); ?></td>
                    <td style="text-align: center;"><?php echo e($studentinfo->religionname); ?></td>
                    <td style="text-align: center;"><?php echo e($studentinfo->street); ?></td>
                    <td style="text-align: center;"><?php echo e($studentinfo->barangay); ?></td>
                    <td style="text-align: center;"><?php echo e($studentinfo->city); ?></td>
                    <td><?php echo e($studentinfo->province); ?></td>
                    <td>
                        <?php if($studentinfo->fathername != ','): ?>
                            <?php echo e($studentinfo->fathername); ?>

                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($studentinfo->mothername != ','): ?>
                            <?php echo e($studentinfo->mothername); ?>

                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($studentinfo->guardianname != ','): ?>
                            <?php echo e($studentinfo->guardianname); ?>

                        <?php endif; ?>
                    </td>
                    <td><?php echo e($studentinfo->guardianrelation); ?></td>
                    <td style="text-align: center;">
                        <?php if($studentinfo->fcontactno != null): ?>
                            <?php echo e($studentinfo->fcontactno); ?><br>
                        <?php endif; ?>
                        <?php if($studentinfo->mcontactno != null): ?>
                            <?php echo e($studentinfo->mcontactno); ?><br>
                        <?php endif; ?>
                        <?php if($studentinfo->gcontactno != null): ?>
                            <?php echo e($studentinfo->gcontactno); ?>

                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php if($studentinfo->studstatus == 2): ?> 
                            LE Date: <?php echo e($studentinfo->dateenrolled); ?>

                        <?php elseif($studentinfo->studstatus == 3): ?> 
                            DRP
                        <?php elseif($studentinfo->studstatus == 4): ?> 
                            T/I Date: <?php echo e($studentinfo->dateenrolled); ?>

                        <?php elseif($studentinfo->studstatus == 5): ?> 
                            T/O Date: <?php echo e($studentinfo->dateenrolled); ?>

                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <tr style="text-align: center;">
            <td></td>
            <td><?php echo e($countstudentfemale); ?></td>
            <td>==TOTAL FEMALE</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="text-align: center;">
            <td></td>
            <td><?php echo e($countstudentfemale + $countstudentmale); ?></td>
            <td>==COMBINED</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
<br>
<table style="width: 100%; border-collapse: collapse; page-break-inside: avoid;">
    <tr>
        <td >
            <table style="width: 100%; border-collapse: collapse; font-size: 10px; " class="indicatortable">
                <tr>
                    <th colspan="5" style="text-align: center;">List and Code of Indicators under REMARKS column</th>
                    <th colspan="4"></th>
                    <th>&nbsp;&nbsp;&nbsp;</th>
                    <th rowspan="2">Prepared by:</th>
                    <th rowspan="6"> &nbsp;&nbsp;&nbsp;</th>
                    <th rowspan="2">Certified Correct:</th>
                </tr>
                <tr>
                    <td>Indicator</td>
                    <td style="text-align: center;">Code</td>
                    <td>Required Information</td>
                    <td style="text-align: center;">Code</td>
                    <td>Required Information</td>
                    <td rowspan="5" style="border-top: none; border-bottom: none;"></td>
                    <td style="text-align: center;">REGISTERED</td>
                    <td style="text-align: center;">BoSY</td>
                    <td style="text-align: center;">EoSy</td>
                    <td rowspan="5" style="border: none;"></td>
                </tr>
                <tr>
                    <td style="border-bottom: none;">Transferred Out</td>
                    <td style="border-bottom: none;text-align: center;">T/O</td>
                    <td style="border-bottom: none;">Name of Public (P) Private (PR) School & Effectivity Date</td>
                    <td style="border-bottom: none;text-align: center;">CCT</td>
                    <td style="border-bottom: none;">CCT Control/reference number & Effectivity Date</td>
                    <td style="text-align: center;">MALE</td>
                    <td style="text-align: center;"><?php echo e($countstudentmale); ?></td>
                    <td></td>
                    <td style="border: none; border-bottom: 1px solid black; text-transform: uppercase; text-align: center;">
                        <?php echo e($forms[0]->preparedby->firstname); ?> <?php echo e($forms[0]->preparedby->middlename[0].'.'); ?> <?php echo e($forms[0]->preparedby->lastname); ?> <?php echo e($forms[0]->preparedby->suffix); ?>

                    </td>
                    <td style="border: none; border-bottom: 1px solid black; text-align: center;">
                        <?php echo e($forms[0]->schoolinfo->authorized); ?>

                    </td>
                </tr>
                <tr>
                    <td style="border-top: none; border-bottom: none;">Transferred IN</td>
                    <td style="border-top: none; border-bottom: none;text-align: center;">T/I</td>
                    <td style="border-top: none; border-bottom: none;">Name of Public (P) Private (PR) School & Effectivity Date</td>
                    <td style="border-top: none; border-bottom: none;text-align: center;">B/A</td>
                    <td style="border-top: none; border-bottom: none;">Name of school last attended & Year</td>
                    <td style="text-align: center;">FEMALE</td>
                    <td style="text-align: center;"><?php echo e($countstudentfemale); ?></td>
                    <td></td>
                    <td style="border: none; font-size: 8px; text-align: center; padding: 0px;">
                        <sup>
                            <em>
                                (Signature of Adviser over Printed Name)
                            </em>
                        </sup>
                    </td>
                    <td style="border: none; font-size: 8px; text-align: center;">
                        <sup>
                            <em>
                                (Signature of School Head over Printed Name)
                            </em>
                        </sup>
                    </td>
                </tr>
                <tr>
                    <td style="border-top: none; border-bottom: none;">DROPPED</td>
                    <td style="border-top: none; border-bottom: none;text-align: center;">DRP</td>
                    <td style="border-top: none; border-bottom: none;">Reason and Effectivity Date</td>
                    <td style="border-top: none; border-bottom: none;text-align: center;">LWD</td>
                    <td style="border-top: none; border-bottom: none;">Specify</td>
                    <td rowspan="2" style="text-align: center;">TOTAL</td>
                    <td rowspan="2" style="text-align: center;"><?php echo e($countstudentmale+$countstudentfemale); ?></td>
                    <td rowspan="2"></td>
                    <td rowspan="2" style="border: none; border-bottom: 1px solid;">
                        BoSy Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; EoSY Date:
                    </td>
                    <td rowspan="2" style="border: none; border-bottom: 1px solid;">
                        BoSy Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; EoSY Date:
                    </td>
                </tr>
                <tr>
                    <td style="border-top: none;">Late Enrollment</td>
                    <td style="border-top: none;text-align: center;">LE</td>
                    <td style="border-top: none;">Reason (Enrollment beyond 1st Friday of June)</td>
                    <td style="border-top: none;text-align: center;">ACL</td>
                    <td style="border-top: none;">Specify </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
    

  <?php /**PATH C:\laragon\www\es_ldcu2\resources\views/registrar/pdf/pdf_schoolform1.blade.php ENDPATH**/ ?>