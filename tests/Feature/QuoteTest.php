<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_and_update_quote_with_items(): void
    {
        $company = Company::factory()->create();
        $user = User::factory()->create([
            'company_id' => $company->id,
            'role' => 'admin',
        ]);

        $this->actingAs($user);

        $response = $this->post(route('quotes.store'), [
            'number' => 'Q-001',
            'items' => [
                ['description' => 'Item 1', 'qty' => 2, 'price' => 1.5],
                ['description' => 'Item 2', 'qty' => 1, 'price' => 2],
            ],
        ]);

        $quote = Quote::first();
        $response->assertRedirect(route('quotes.show', $quote));

        $this->assertEquals(2, $quote->items()->count());
        $this->assertEquals(
            $quote->items->sum(fn ($i) => $i->quantity * $i->unit_price_cents),
            $quote->total_cents
        );

        $response = $this->put(route('quotes.update', $quote), [
            'number' => 'Q-001',
            'items' => [
                ['description' => 'Updated', 'qty' => 3, 'price' => 1],
            ],
        ]);

        $quote->refresh();
        $response->assertRedirect(route('quotes.show', $quote));

        $this->assertEquals(1, $quote->items()->count());
        $this->assertEquals(
            $quote->items->sum(fn ($i) => $i->quantity * $i->unit_price_cents),
            $quote->total_cents
        );
    }
}
