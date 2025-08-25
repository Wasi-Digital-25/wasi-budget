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
        $email = $this->subject('Quote '.$this->quote->number)
            ->view('quotes.mail');

        $content = app()->bound('dompdf.wrapper')
            ? app('dompdf.wrapper')->loadView('quotes.pdf', ['quote' => $this->quote])->output()
            : view('quotes.pdf', ['quote' => $this->quote])->render();

        return $email->attachData($content, 'quote-'.$this->quote->number.'.pdf', ['mime' => 'application/pdf']);
    }
}
