<?php

namespace Tests\Feature;

use App\Mail\QuoteSentMail;
use App\Models\Client;
use App\Models\Company;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class QuotesTest extends TestCase
{
    use RefreshDatabase;

    private function createCompany(array $attributes = [])
    {
        return Company::create(array_merge([
            'name' => 'Acme Inc',
            'slug' => 'acme'.uniqid(),
            'plan' => 'starter',
            'currency' => 'PEN',
        ], $attributes));
    }

    public function test_can_create_quote_with_items_and_totals(): void
    {
        $company = $this->createCompany();
        $user = User::factory()->create(['company_id' => $company->id, 'role' => 'admin']);
        $client = Client::factory()->create(['company_id' => $company->id]);

        $payload = [
            'number' => 'Q-001',
            'client_id' => $client->id,
            'currency' => 'PEN',
            'expires_at' => now()->addDay()->toDateString(),
            'items' => [
                ['description' => 'Service', 'qty' => 2, 'unit_price_cents' => 1500],
            ],
        ];

        $response = $this->actingAs($user)->post('/quotes', $payload);
        $quote = Quote::first();
        $response->assertRedirect(route('quotes.show', $quote));
        $this->assertDatabaseHas('budget_quote_items', ['quote_id' => $quote->id, 'description' => 'Service']);
        $this->assertEquals(3000, $quote->total_cents);
    }

    public function test_can_send_quote_and_queue_mail(): void
    {
        $company = $this->createCompany();
        $user = User::factory()->create(['company_id' => $company->id, 'role' => 'admin']);
        $client = Client::factory()->create(['company_id' => $company->id]);
        $quote = Quote::factory()->create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'client_id' => $client->id,
            'client_email' => $client->email,
        ]);

        Mail::fake();
        Queue::fake();

        $this->actingAs($user)->post("/quotes/{$quote->id}/send");
        $quote->refresh();
        $this->assertEquals('sent', $quote->status);
        Mail::assertQueued(QuoteSentMail::class, function ($mail) use ($quote) {
            $mail->build();
            return $mail->quote->is($quote) && count($mail->attachments) === 1;
        });
    }

    public function test_can_accept_and_reject_quote(): void
    {
        $company = $this->createCompany();
        $user = User::factory()->create(['company_id' => $company->id, 'role' => 'admin']);
        $quote = Quote::factory()->create(['company_id' => $company->id, 'user_id' => $user->id]);
        $quote2 = Quote::factory()->create(['company_id' => $company->id, 'user_id' => $user->id]);

        $this->actingAs($user)->post("/quotes/{$quote->id}/accept");
        $this->assertEquals('accepted', $quote->fresh()->status);

        $this->actingAs($user)->post("/quotes/{$quote2->id}/reject");
        $this->assertEquals('rejected', $quote2->fresh()->status);
    }

    public function test_cannot_access_quotes_of_other_company(): void
    {
        $company = $this->createCompany();
        $otherCompany = $this->createCompany(['slug' => 'other'.uniqid()]);
        $user = User::factory()->create(['company_id' => $company->id, 'role' => 'admin']);
        $otherQuote = Quote::factory()->create(['company_id' => $otherCompany->id]);

        $response = $this->actingAs($user)->get("/quotes/{$otherQuote->id}");
        $response->assertStatus(403);
    }
}
