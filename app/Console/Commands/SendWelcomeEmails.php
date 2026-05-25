<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Mail\WelcomeMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendWelcomeEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'taskly:send-welcome-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send welcome emails to users who registered within the last minute';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Welcome emails are now handled by the UserObserver's created event.
        // This command is kept intact as requested, but no longer sends emails.
    }
}
