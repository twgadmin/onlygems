<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MyTestMail extends Mailable
{
    use Queueable, SerializesModels;
    public $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->details = [
            'title' => 'Fred Fund - NFT Collectible Asset Fund',
            'body' => 'This is for testing email using smtp'
        ];
        return $this->subject('Mail from ItSolutionStuff.com')->view('emails.myTestMail',$this->details);
    }
}
