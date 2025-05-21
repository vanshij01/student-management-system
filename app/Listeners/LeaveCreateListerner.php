<?php

namespace App\Listeners;

use App\Events\LeaveCreateEvent;
use App\Mail\LeaveCreateMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class LeaveCreateListerner
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
    public function handle(LeaveCreateEvent $event)
    {
        $email = $event->studentData->email;
        Mail::to($email)->send(new LeaveCreateMail($event->leave, $event->studentData));
    }
}
