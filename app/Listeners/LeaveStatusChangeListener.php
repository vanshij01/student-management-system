<?php

namespace App\Listeners;

use App\Events\LeaveStatusChangeEvent;
use App\Mail\LeaveStatusChangeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class LeaveStatusChangeListener
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
    public function handle(LeaveStatusChangeEvent $event)
    {
      $email = $event->studentData->email;
      Mail::to($email)->send(new LeaveStatusChangeMail($event->leave, $event->studentData));
    }
}
