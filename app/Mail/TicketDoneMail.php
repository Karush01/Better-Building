<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketDoneMail extends Mailable
{
    use Queueable, SerializesModels;

	public $user;
	public $ticket;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$ticket)
    {
	    $this->user = $user;
	    $this->ticket = $ticket;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	    return $this->subject('פניה בוצעה בהצלחה')->view('emails.ticketdone');
    }
}
