<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Quote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_quote_with_items(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->post(route('quotes.store'), [
            'number' => 'Q-1',
            'items' => [
                ['description' => 'Item 1', 'qty' => 2, 'price' => 10],
                ['description' => 'Item 2', 'qty' => 1, 'price' => 5],
            ],
        ]);

        $quote = Quote::first();

        $response->assertRedirect(route('quotes.show', $quote));
        $this->assertEquals(2, $quote->items()->count());
        $this->assertEquals(2500, $quote->total_cents);
    }

    public function test_user_can_update_quote_items_and_total(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $quote = Quote::factory()->create(['company_id' => $user->company_id, 'user_id' => $user->id]);

        $response = $this->actingAs($user)->put(route('quotes.update', $quote), [
            'number' => 'NEW-1',
            'items' => [
                ['description' => 'Updated', 'qty' => 3, 'price' => 20],
            ],
        ]);

        $quote->refresh();

        $response->assertRedirect(route('quotes.show', $quote));
        $this->assertEquals('NEW-1', $quote->number);
        $this->assertEquals(1, $quote->items()->count());
        $this->assertEquals(6000, $quote->total_cents);
    }

    public function test_user_can_change_quote_status(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $quote = Quote::factory()->create(['company_id' => $user->company_id, 'user_id' => $user->id]);

        $this->actingAs($user)->post(route('quotes.send', $quote));
        $quote->refresh();
        $this->assertEquals('sent', $quote->status);

        $this->actingAs($user)->post(route('quotes.accept', $quote));
        $quote->refresh();
        $this->assertEquals('accepted', $quote->status);
    }

    public function test_quotes_are_scoped_by_company(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $otherQuote = Quote::factory()->create(); // different company

        $this->actingAs($user)
            ->get(route('quotes.index'))
            ->assertDontSee($otherQuote->number);

        $this->actingAs($user)
            ->get(route('quotes.show', $otherQuote))
            ->assertStatus(403);
    }
}

