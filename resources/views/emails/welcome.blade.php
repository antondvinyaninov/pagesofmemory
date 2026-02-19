<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добро пожаловать</title>
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
                                    <td style="vertical-align:middle;font-size:20px;font-weight:700;line-height:1.2;">Memory</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <h1 style="margin:0 0 12px;font-size:22px;">Добро пожаловать, {{ $user->name }}!</h1>
                            <p style="margin:0 0 16px;line-height:1.6;">
                                Вы успешно зарегистрировались на платформе Memory. Теперь вы можете создавать страницы памяти, делиться воспоминаниями и поддерживать близких.
                            </p>
                            <p style="margin:0 0 20px;line-height:1.6;">
                                Перейдите в профиль, чтобы дополнить информацию о себе и начать работу с мемориалами.
                            </p>
                            <a href="{{ route('profile') }}" style="display:inline-block;background:#ef4444;color:#ffffff;text-decoration:none;padding:10px 16px;border-radius:8px;font-weight:600;">
                                Открыть профиль
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
