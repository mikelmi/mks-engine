<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Feedback extends Mailable
{
    use Queueable, SerializesModels;

    private $message;

    private $userEmail;

    private $userName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message, $from, $name = null)
    {
        $this->userEmail = $from;
        $this->userName = $name;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(trans('messages.feedback_subject'))
            //->from($this->userEmail, $this->userName)
            ->view('emails.feedback', [
                'email' => $this->userEmail,
                'name' => $this->userName,
                'msg' => $this->message
            ]);
    }
}
