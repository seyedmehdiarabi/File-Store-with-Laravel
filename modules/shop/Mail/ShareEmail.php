<?php

namespace Modules\shop\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ShareEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user_name;

    public $product;
    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($user_name,$product)
    {
        $this->user_name=$user_name;
        $this->product=$product;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        return $this->view('shop::mail.share-email')
            ->subject('از طرف دوست شما');
    }
}
