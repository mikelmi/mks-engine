<?php

namespace App\Http\Controllers;


use App\Mail\Feedback;
use App\Models\Widget;
use App\Notifications\NewFeedback;
use App\Services\CaptchaManager;
use App\User;
use Illuminate\Http\Request;

class ContactsController extends SiteController
{
    public function send(Request $request, CaptchaManager $captchaManager)
    {
        $rules = [
            'name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'message' => 'required|max:2000',
            'widget_id' => 'required|exists:widgets,id'
        ];

        $rules = array_merge($rules, $captchaManager->rules());

        $this->validate($request, $rules);

        $widget = Widget::findOrFail($request->get('widget_id'));
        $toEmail = $widget->param('email');

        if (!$toEmail) {
            abort(500, 'System Error (invalid feedback email configuration)');
        }

        $from = $request->get('email');
        $name = $request->get('name');
        $message = $request->get('message');

        \Notification::send(User::admins()->get(), new NewFeedback($message, $from, $name));

        \Mail::to($toEmail)
            ->send(new Feedback($message, $from, $name));

        return [
            'message' => __('messages.sent_success'),
        ];
    }
}