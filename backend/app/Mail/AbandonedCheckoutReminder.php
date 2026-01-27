<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AbandonedCheckoutReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $checkout;

    public function __construct($checkout)
    {
        $this->checkout = $checkout;
    }

    public function build()
    {
        return $this->subject('Complete Your Purchase at Rupchorcha')
            ->view('emails.abandoned_checkout_reminder');
    }
}
