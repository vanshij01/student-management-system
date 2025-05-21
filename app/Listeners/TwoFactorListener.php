<?php

namespace App\Listeners;

use App\Events\TwoFactorEvent;
use App\Mail\TwoFactorMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class TwoFactorListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TwoFactorEvent $event): void
    {
        $email = $event->maildata->email;
        Mail::to($email)->send(new TwoFactorMail($event->maildata));
    }
}
