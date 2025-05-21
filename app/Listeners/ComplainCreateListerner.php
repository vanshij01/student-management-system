<?php

namespace App\Listeners;

use App\Events\ComplainCreateEvent;
use App\Mail\ComplainCreateMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class ComplainCreateListerner
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
    public function handle(ComplainCreateEvent $event): void
    {
        // $email = 'student@sklpsahmedabad.com';
        $email = 'vanshi.binstellar@gmail.com';
        Mail::to($email)->send(new ComplainCreateMail($event->complain, $event->studentData));
    }
}
