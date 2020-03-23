<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CronUpdateAdminMail extends Mailable
{
    use Queueable, SerializesModels;

	public $user;
	public $overduetasks;
	public $newskus;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $overduetasks, $newskus)
    {
	    $this->user = $user;
	    $this->overduetasks = $overduetasks;
	    $this->newskus = $newskus;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('עדכון פעולת CRON')->view('emails.cronupdateadmin');
    }
}
