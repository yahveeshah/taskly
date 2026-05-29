<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password - Taskly</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f3f4f6; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #000080; padding: 30px; text-align: center;">
                            <h1 style="color: #C7A0CB; margin: 0; font-size: 28px; font-weight: bold; letter-spacing: 1px;">Taskly</h1>
                        </td>
                    </tr>
                    
                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 30px; color: #2d3748; line-height: 1.6; font-size: 16px;">
                            @php
                                $firstName = explode(' ', trim($user->name))[0];
                            @endphp
                            <h2 style="margin-top: 0; color: #000080; font-size: 22px;">Hi {{ $firstName }},</h2>
                            
                            <p style="margin-bottom: 20px;">You are receiving this email because we received a password reset request for your account.</p>
                            
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ $url }}" style="background-color: #000080; color: #ffffff; padding: 12px 24px; border-radius: 50px; text-decoration: none; font-weight: bold; display: inline-block;">Reset Password</a>
                            </div>
                            
                            <p style="margin-bottom: 20px;">This password reset link will expire in 60 minutes.</p>
                            <p style="margin-bottom: 30px;">If you did not request a password reset, no further action is required.</p>
                            
                            <p style="margin: 0; border-top: 1px solid #e2e8f0; padding-top: 20px; font-size: 13px; color: #718096; word-break: break-all;">
                                If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:<br>
                                <a href="{{ $url }}" style="color: #000080;">{{ $url }}</a>
                            </p>
                            
                            <p style="margin: 20px 0 0 0;">Warm regards,</p>
                            <p style="margin: 5px 0 0 0; font-weight: bold; color: #000080;">The Taskly Team</p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0; font-size: 13px; color: #718096;">&copy; {{ date('Y') }} Taskly. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
