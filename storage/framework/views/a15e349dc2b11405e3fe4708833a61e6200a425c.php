<!DOCTYPE html>
<html>

    <head>
        <title>Statement of Account</title>
    </head>

    <body>
        <p>Dear <?php echo e($student->firstname); ?>,</p>

        <p>Attached is your Statement of Account in PDF format.</p>

        <p>Thank you,<br>
            <?php echo e($school_name ?? 'LDCU'); ?></p>
    </body>

</html>
<?php /**PATH C:\laragon\www\es_ldcu\resources\views/emails/soaemail.blade.php ENDPATH**/ ?>