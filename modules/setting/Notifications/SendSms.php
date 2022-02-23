<?php

namespace Modules\setting\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\setting\Channels\SmsChannel;

class SendSms extends Notification implements ShouldQueue
{
    use Queueable;

    protected $args;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public function __construct($args)
    {
        $this->args=$args;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [SmsChannel::class];
    }

    public function toSms($notifiable)
    {
        return $this->args;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [


        ];
    }
}
