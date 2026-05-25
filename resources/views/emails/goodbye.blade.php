<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We're sad to see you go</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7fb; color: #1a202c; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
        <div style="background-color: #2c3e50; padding: 30px; text-align: center;">
            <h1 style="color: #e6e6fa; margin: 0; font-size: 24px; font-weight: 600;">Taskly</h1>
        </div>
        
        <div style="padding: 40px 30px;">
            <p style="font-size: 16px; line-height: 1.6; margin-bottom: 20px;">Hi {{ explode(' ', $user->name)[0] }},</p>
            
            <p style="font-size: 16px; line-height: 1.6; margin-bottom: 20px;">We noticed that your Taskly account has been deleted. We're truly sorry to see you go.</p>
            
            <p style="font-size: 16px; line-height: 1.6; margin-bottom: 20px;">Your account and all associated data have been permanently removed from our system.</p>
            
            <p style="font-size: 16px; line-height: 1.6; margin-bottom: 20px;">If this was a mistake, or if you ever decide to come back, you're always welcome &mdash; simply create a new account at any time.</p>

            <p style="font-size: 16px; line-height: 1.6; margin-bottom: 20px;">We'd love to know what we could have done better. If you have a moment, feel free to reply to this email with any feedback &mdash; it genuinely helps us improve Taskly for everyone.</p>

            <p style="font-size: 16px; line-height: 1.6; margin-bottom: 20px;">Thank you for being part of the Taskly community. We wish you all the best.</p>
            
            <p style="font-size: 16px; line-height: 1.6; margin-bottom: 0;">Warm regards,<br><strong>The Taskly Team</strong></p>
        </div>
        
        <div style="background-color: #f8fafc; padding: 20px; text-align: center; border-top: 1px solid #edf2f7;">
            <p style="font-size: 12px; color: #718096; margin: 0;">&copy; {{ date('Y') }} Taskly. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
