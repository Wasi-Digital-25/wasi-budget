<?php

namespace App\Mail;

use App\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $pdf = Pdf::loadView('quotes.pdf', ['quote' => $this->quote]);
        return $this->subject('Quote '.$this->quote->number)
            ->view('quotes.mail')
            ->attachData($pdf->output(), 'quote-'.$this->quote->number.'.pdf');
    }
}
