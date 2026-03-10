<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reset Password</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style type="text/css">
        /* Reset */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { margin: 0; padding: 0; width: 100% !important; height: 100% !important; }
        a[x-apple-data-detectors] { color: inherit !important; text-decoration: none !important; font-size: inherit !important; font-family: inherit !important; font-weight: inherit !important; line-height: inherit !important; }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    <!-- Outer Wrapper -->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f3f4f6;">
        <tr>
            <td align="center" style="padding: 40px 16px;">

                <!-- Email Container -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="max-width: 600px; width: 100%;">

                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color: #174D9D; padding: 32px 40px; border-radius: 12px 12px 0 0;">
                            <h1 style="margin: 0; font-size: 22px; font-weight: 700; color: #ffffff; letter-spacing: 0.5px;">
                                PT. Buana Enjiniring Konsultan
                            </h1>
                            <p style="margin: 6px 0 0 0; font-size: 13px; color: #b3c9e8;">
                                Enterprise Project Management System
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="background-color: #ffffff; padding: 40px;">

                            <!-- Greeting -->
                            <h2 style="margin: 0 0 20px 0; font-size: 20px; font-weight: 600; color: #1f2937;">
                                Hello, {{ $name }}
                            </h2>

                            <!-- Main Text -->
                            <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.7; color: #4b5563;">
                                You are receiving this email because we received a password reset request for your account.
                            </p>

                            <!-- CTA Button -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="padding: 8px 0 32px 0;">
                                        <!--[if mso]>
                                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $url }}" style="height:50px;v-text-anchor:middle;width:280px;" arcsize="16%" stroke="f" fillcolor="#174D9D">
                                            <w:anchorlock/>
                                            <center style="color:#ffffff;font-family:sans-serif;font-size:16px;font-weight:bold;">Reset Password</center>
                                        </v:roundrect>
                                        <![endif]-->
                                        <!--[if !mso]><!-->
                                        <a href="{{ $url }}"
                                           target="_blank"
                                           style="display: inline-block; background-color: #174D9D; color: #ffffff; font-size: 16px; font-weight: 600; text-decoration: none; padding: 14px 48px; border-radius: 8px; letter-spacing: 0.3px; mso-padding-alt: 0; text-align: center;">
                                            Reset Password
                                        </a>
                                        <!--<![endif]-->
                                    </td>
                                </tr>
                            </table>

                            <!-- Expire Notice -->
                            <p style="margin: 0 0 24px 0; font-size: 14px; line-height: 1.6; color: #6b7280;">
                                This password reset link will expire in {{ $expireMinutes }} minutes.
                            </p>

                            <!-- Warning Text -->
                            <p style="margin: 0 0 0 0; font-size: 14px; line-height: 1.6; color: #6b7280;">
                                If you did not request a password reset, no further action is required.
                            </p>

                            <!-- Divider -->
                            <hr style="margin: 32px 0; border: none; border-top: 1px solid #e5e7eb;">

                            <!-- Fallback URL -->
                            <p style="margin: 0; font-size: 12px; line-height: 1.6; color: #9ca3af;">
                                If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
                            </p>
                            <p style="margin: 8px 0 0 0; font-size: 12px; line-height: 1.6; word-break: break-all;">
                                <a href="{{ $url }}" style="color: #174D9D; text-decoration: underline;">{{ $url }}</a>
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #f9fafb; padding: 24px 40px; border-radius: 0 0 12px 12px; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0; font-size: 12px; color: #9ca3af; line-height: 1.6;">
                                &copy; {{ date('Y') }} PT. Brantas Energi. All rights reserved.
                            </p>
                            <p style="margin: 6px 0 0 0; font-size: 11px; color: #d1d5db;">
                                This is an automated message. Please do not reply to this email.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
