<?php

namespace App\Listeners;

use App\Events\SendNoteEvent;
use App\Mail\SendNoteMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendNoteListener
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
    public function handle(SendNoteEvent $event): void
    {
        $email = $event->admission->email;
        $admission = $event->admission;
        $adminComment = $event->admin_comment;
        Mail::to($email)->send(new SendNoteMail($admission, $adminComment));
    }
}
