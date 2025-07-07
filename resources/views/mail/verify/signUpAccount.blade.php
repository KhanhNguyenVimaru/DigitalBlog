<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify Your Digital Blog Account</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f6f6f6; padding: 30px;">
    <div style="max-width: 600px; margin: auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #eee; padding: 32px;">
        <h2 style="color: #2563eb;">Welcome to Digital Blog!</h2>
        <p>Hello <strong>{{ $user->name ?? 'there' }}</strong>,</p>
        <p>Thank you for registering an account at Digital Blog.</p>
        <p>Please click the button below to verify your email address and activate your account:</p>
        <div style="text-align: center; margin: 32px 0;">
            <a href="{{ $verifyUrl }}" style="background: #2563eb; color: #fff; padding: 12px 32px; border-radius: 6px; text-decoration: none; font-weight: bold;">
                Verify My Account
            </a>
        </div>
        <p>If you did not sign up for this account, please ignore this email.</p>
        <hr>
        <p style="font-size: 13px; color: #888;">Digital Blog &copy; {{ date('Y') }}</p>
    </div>
</body>
</html> 