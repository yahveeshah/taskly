<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Mail\DeadlineReminderMail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendDeadlineReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'taskly:send-deadline-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send deadline reminders to users for tasks due today';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->toDateString();
        
        $tasksDueToday = Task::whereDate('deadline', $today)
            ->where('status', '!=', 'completed')
            ->with('user')
            ->get();
            
        $tasksByUser = $tasksDueToday->groupBy('user_id');
        
        $sentCount = 0;
        
        foreach ($tasksByUser as $userId => $tasks) {
            $user = $tasks->first()->user;
            
            if (!$user) {
                continue;
            }
            
            $cacheKey = "deadline_reminder_{$userId}_{$today}";
            
            if (!Cache::has($cacheKey)) {
                Mail::to($user->email)->send(new DeadlineReminderMail($user, $tasks));
                
                // Cache for 24 hours to prevent duplicates today
                Cache::put($cacheKey, true, now()->addHours(24));
                
                $sentCount++;
            }
        }

        $this->info("Deadline reminder emails sent: {$sentCount}");
        Log::info("Deadline reminder emails sent: {$sentCount}");
    }
}
