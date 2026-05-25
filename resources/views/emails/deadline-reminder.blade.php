<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You have tasks due today</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7fb; color: #1a202c; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
        <div style="background-color: #2c3e50; padding: 30px; text-align: center;">
            <h1 style="color: #e6e6fa; margin: 0; font-size: 24px; font-weight: 600;">Taskly Reminders</h1>
        </div>
        
        <div style="padding: 40px 30px;">
            <p style="font-size: 16px; line-height: 1.6; margin-bottom: 20px;">Hi {{ explode(' ', $user->name)[0] }},</p>
            
            <p style="font-size: 16px; line-height: 1.6; margin-bottom: 20px;">This is a friendly reminder that the following task(s) are due today and need your attention:</p>
            
            <ul style="list-style-type: none; padding: 0; margin-bottom: 20px;">
                @foreach ($tasks as $task)
                    <li style="font-size: 16px; margin-bottom: 10px; padding: 12px; background-color: #f8fafc; border-left: 4px solid {{ strtolower($task->priority) === 'high' ? '#e53e3e' : '#e6e6fa' }}; border-radius: 4px;">
                        &bull; <strong>{{ $task->title }}</strong> &mdash; 
                        Priority: 
                        @if(strtolower($task->priority) === 'high')
                            <strong style="color: #e53e3e;">{{ ucfirst($task->priority) }}</strong>
                        @else
                            {{ ucfirst($task->priority) }}
                        @endif
                        &mdash; Status: {{ ucfirst($task->status) }}
                    </li>
                @endforeach
            </ul>
            
            <p style="font-size: 16px; line-height: 1.6; margin-bottom: 20px;">Please make sure to prioritize these tasks today to stay on track with your goals.</p>
            
            <p style="font-size: 16px; line-height: 1.6; margin-bottom: 20px; font-weight: bold;">You've got this!</p>
            
            <p style="font-size: 16px; line-height: 1.6; margin-bottom: 0;">Warm regards,<br><strong>The Taskly Team</strong></p>
        </div>
        
        <div style="background-color: #f8fafc; padding: 20px; text-align: center; border-top: 1px solid #edf2f7;">
            <p style="font-size: 12px; color: #718096; margin: 0;">&copy; {{ date('Y') }} Taskly. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
