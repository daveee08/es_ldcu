<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8" />
        <title>New Login Notification</title>
    </head>

    <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f5f5;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0"
            style="background-color: #f5f5f5; padding: 40px 0;">
            <tr>
                <td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" border="0"
                        style="background-color: #ffffff; padding: 30px; border-radius: 8px;">
                        <tr>
                            <td align="center" style="font-size: 48px; padding-bottom: 20px;">
                                <img src="{{ asset('schoollistlogo/bct.png') }}" alt="logo" width="72"
                                    height="72" style="display: block;" />
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 16px; color: #333333;">
                                <p style="text-align: center;">
                                    Hi <strong>{{ $data['fullname'] }}</strong>,
                                </p>
                                <p style="text-align: center;">
                                    Here is your credentials for your account
                                </p>
                                <p>
                                    <strong>Username:</strong> {{ $data['username'] }}<br />
                                    <strong>Password:</strong> {{ $data['password'] }}
                                </p>
                                <p>
                                    Log in to your account <a href="https://app-ldcu.essentiel.ph"
                                        style="color: #0066cc;">
                                        here</a>.
                                </p>
                                <p>
                                    Cheers,
                                    <br />
                                    CK Childrens Publishing Team
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" style="font-size: 12px; color: #999999; padding-top: 20px;">
                                <p style="margin: 0;">
                                    This is an automated message, please do not reply.
                                    <br>
                                    <br>
                                    ckcpublishingofficial@gmail.com | selsck@yahoo.com
                                    <br>
                                    +63-917-718-7665 (Globe) | +63-918-935-1942 (Smart)
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>

</html>
