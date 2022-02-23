<?php
namespace Modules\setting\Channels;

use Illuminate\Notifications\Notification;
class SmsChannel
{
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toSms($notifiable);
        $channel=$data['channel'];
        run_action($channel.'_send_sms',[$data]);
    }
}
