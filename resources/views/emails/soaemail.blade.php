<!DOCTYPE html>
<html>

    <head>
        <title>Statement of Account</title>
    </head>

    <body>
        <p>Dear {{ $student->firstname }},</p>

        <p>Attached is your Statement of Account in PDF format.</p>

        <p>Thank you,<br>
            {{ $school_name ?? 'LDCU' }}</p>
    </body>

</html>
