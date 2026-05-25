<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to Taskly!</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f3f4f6; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #1a202c; padding: 30px; text-align: center;">
                            <h1 style="color: #e6e6fa; margin: 0; font-size: 28px; font-weight: bold; letter-spacing: 1px;">Taskly</h1>
                        </td>
                    </tr>
                    
                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 30px; color: #2d3748; line-height: 1.6; font-size: 16px;">
                            @php
                                $firstName = explode(' ', trim($user->name))[0];
                            @endphp
                            <h2 style="margin-top: 0; color: #1a202c; font-size: 22px;">Hi {{ $firstName }},</h2>
                            
                            <p style="margin-bottom: 20px;">Welcome to Taskly! We are absolutely thrilled to have you on board.</p>
                            
                            <p style="margin-bottom: 20px;">Taskly is designed to help you organize your life and work seamlessly. Whether you want to manage daily tasks, track your long-term progress, or collaborate efficiently with your team, we've got you covered.</p>
                            
                            <p style="margin-bottom: 30px;">We can't wait to see what you'll achieve!</p>
                            
                            <p style="margin: 0;">Warm regards,</p>
                            <p style="margin: 5px 0 0 0; font-weight: bold; color: #1a202c;">The Taskly Team</p>
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
