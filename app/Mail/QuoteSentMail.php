<?php

namespace App\Mail;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuoteSentMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Quote $quote)
    {
    }

    public function build(): self
    {
        return $this->subject('Quote '.$this->quote->number)
            ->view('quotes.mail');
    }
}
