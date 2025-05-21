<?php

namespace App\Listeners;

use App\Events\ComplainStatusChangeEvent;
use App\Mail\ComplainStatusChangeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class ComplainStatusChangeListener
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
    public function handle(ComplainStatusChangeEvent $event)
    {
        $email = $event->studentData->email;
        Mail::to($email)->send(new ComplainStatusChangeMail($event->complain, $event->studentData));
    }
}
