<!DOCTYPE html>
<html>

    <head>
        <title>Student Masterlist</title>
    </head>

    <body>
        <h1>Section: <?php echo e($section->sectionname); ?></h1>
        <h2>Grade: <?php echo e($grade->levelname); ?></h2>
        <ul>
            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e(is_array($student) ? $student['lastname'] : $student->lastname); ?>,
                    <?php echo e(is_array($student) ? $student['firstname'] : $student->firstname); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </body>

</html>
<?php /**PATH C:\laragon\www\es_ldcu\resources\views/superadmin/pages/migration/pdf/pdf.blade.php ENDPATH**/ ?>