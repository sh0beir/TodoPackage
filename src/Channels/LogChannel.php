<?php

namespace sh0beir\todo\Channels;

use Illuminate\Support\Facades\Log;

class LogChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, $notification)
    {
        $message = $notification->toLog($notifiable);
    }
}
