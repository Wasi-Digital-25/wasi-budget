<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScopeCompanyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_view_client_of_another_company(): void
    {
        $client = Client::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($otherUser)
            ->get(route('clients.show', $client))
            ->assertForbidden();
    }

    public function test_user_cannot_update_client_of_another_company(): void
    {
        $client = Client::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($otherUser)
            ->put(route('clients.update', $client), [
                'name' => 'New Name',
            ])
            ->assertForbidden();
    }

    public function test_user_cannot_view_quote_of_another_company(): void
    {
        $quote = Quote::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($otherUser)
            ->get(route('quotes.show', $quote))
            ->assertForbidden();
    }

    public function test_user_cannot_update_quote_of_another_company(): void
    {
        $quote = Quote::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($otherUser)
            ->put(route('quotes.update', $quote), [
                'number' => 'Q-123',
            ])
            ->assertForbidden();
    }
}
