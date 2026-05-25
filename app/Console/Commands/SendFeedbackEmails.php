<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Mail\FeedbackRequestMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendFeedbackEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'taskly:send-feedback-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send feedback request emails to users who registered exactly 10 days ago';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenDaysAgo = Carbon::now()->subDays(10)->toDateString();
        
        $users = User::whereDate('created_at', $tenDaysAgo)->get();
        $sentCount = 0;

        foreach ($users as $user) {
            $cacheKey = "feedback_email_sent_user_{$user->id}";

            // If we haven't sent the email to this user yet
            if (!Cache::has($cacheKey)) {
                Mail::to($user->email)->send(new FeedbackRequestMail($user));
                
                // Cache forever so we never send it again
                Cache::forever($cacheKey, true);
                
                $sentCount++;
            }
        }

        $this->info("Feedback emails sent: {$sentCount}");
        Log::info("Feedback emails sent: {$sentCount}");
    }
}
