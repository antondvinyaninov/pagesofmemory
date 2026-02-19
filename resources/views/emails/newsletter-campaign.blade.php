<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>{{ $subjectLine }}</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,sans-serif;color:#111827;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="640" cellpadding="0" cellspacing="0" style="max-width:640px;background:#ffffff;border-radius:12px;overflow:hidden;">
                    <tr>
                        <td style="background:#1f2937;padding:16px 24px;color:#ffffff;">
                            <table role="presentation" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding-right:10px;vertical-align:middle;">
                                        <img src="{{ project_email_icon_url() }}" alt="Memory" width="28" height="28" style="display:block;border:0;border-radius:6px;">
                                    </td>
                                    <td style="vertical-align:middle;font-size:20px;font-weight:700;line-height:1.2;">
                                        Memory {{ $isTest ? '• Тестовое письмо' : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <h1 style="margin:0 0 16px;font-size:22px;">{{ $subjectLine }}</h1>
                            <div style="line-height:1.7;white-space:pre-line;">{{ $bodyText }}</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
