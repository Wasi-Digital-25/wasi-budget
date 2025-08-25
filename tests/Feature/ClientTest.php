<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_update_delete_client(): void
    {
        $company = Company::factory()->create();
        $user = User::factory()->create([
            'company_id' => $company->id,
            'role' => 'admin',
        ]);

        $this->actingAs($user);

        $response = $this->post(route('clients.store'), [
            'name' => 'Test Client',
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect(route('clients.index'))
            ->assertSessionHas('success');

        $client = Client::first();
        $this->assertNotNull($client);
        $this->assertEquals($user->company_id, $client->company_id);
        $this->assertEquals($user->id, $client->created_by);

        $response = $this->put(route('clients.update', $client), [
            'name' => 'Updated Client',
            'email' => 'updated@example.com',
            'phone' => '123456789',
            'tax_id' => 'ABC123',
            'address' => 'Some street',
            'notes' => 'Some notes',
        ]);

        $response->assertRedirect(route('clients.index'))
            ->assertSessionHas('success');

        $client->refresh();
        $this->assertEquals('Updated Client', $client->name);
        $this->assertEquals($user->company_id, $client->company_id);

        $response = $this->delete(route('clients.destroy', $client));

        $response->assertRedirect(route('clients.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('clients', ['id' => $client->id]);
    }

    public function test_clients_are_isolated_by_company(): void
    {
        $companyA = Company::factory()->create();
        $companyB = Company::factory()->create();

        $userA = User::factory()->create([
            'company_id' => $companyA->id,
            'role' => 'admin',
        ]);

        $clientA = Client::factory()->create([
            'company_id' => $companyA->id,
            'created_by' => $userA->id,
        ]);
        $clientB = Client::factory()->create([
            'company_id' => $companyB->id,
        ]);

        $this->actingAs($userA);

        $this->get(route('clients.index'))
            ->assertSee($clientA->name)
            ->assertDontSee($clientB->name);

        $this->get(route('clients.show', $clientB))
            ->assertNotFound();
    }
}

