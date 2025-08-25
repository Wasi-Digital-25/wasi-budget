<?php

namespace Tests\Feature;

use App\Mail\QuoteSentMail;
use App\Models\Quote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\SendQueuedMailable;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class QuotePdfEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_pdf_download_returns_ok(): void
    {
        $quote = Quote::factory()->create();

        $response = $this->actingAs($quote->user)->get(route('quotes.pdf', $quote));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_send_queues_mail_and_updates_status(): void
    {
        Queue::fake();

        $quote = Quote::factory()->create(['client_email' => 'client@example.com']);

        $this->actingAs($quote->user)->post(route('quotes.send', $quote));

        $quote->refresh();
        $this->assertSame('sent', $quote->status);

        Queue::assertPushed(SendQueuedMailable::class, function ($job) use ($quote) {
            return $job->mailable instanceof QuoteSentMail
                && $job->mailable->hasTo($quote->client_email);
        });
    }
}

