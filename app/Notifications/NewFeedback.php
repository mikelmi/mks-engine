<?php

namespace App\Notifications;


use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewFeedback extends Notification implements ReadableNotification
{
    use Queueable;

    private $from;

    private $name;

    private $message;

    public function __construct($message, $from, $name = null)
    {
        $this->message = $message;
        $this->from = $from;
        $this->name = $name;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase()
    {
        return [
            'from' => $this->from,
            'name' => $this->name,
            'message' => $this->message
        ];
    }

    public static function title($data)
    {
        return __('events.new_feedback', ['from' => array_get($data, 'from'), 'name' => array_get($data, 'name')]);
    }

    public static function details($data)
    {
        return view('admin._partial.feedback-details', $data)->render();
    }
}