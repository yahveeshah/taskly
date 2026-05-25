<?php

namespace App\Observers;

use App\Models\User;
use App\Mail\WelcomeMail;
use App\Mail\GoodbyeMail;
use Illuminate\Support\Facades\Mail;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        Mail::to($user->email)->send(new WelcomeMail($user));
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        Mail::to($user->email)->send(new GoodbyeMail($user));
    }
}
