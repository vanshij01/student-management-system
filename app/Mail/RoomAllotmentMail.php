<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RoomAllotmentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $roomAllomentDetail;

    public function __construct($roomAllomentDetail)
    {
        $this->roomAllomentDetail = $roomAllomentDetail;
    }

    public function build()
    {
        return $this->subject('Room Allotment Details')
            ->view('mail.room_allotment', [
                'roomAllomentDetail' => $this->roomAllomentDetail,
            ]);

    }
}
