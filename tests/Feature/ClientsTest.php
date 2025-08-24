<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientsTest extends TestCase
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

    public function test_list_clients_of_user_company(): void
    {
        $company = $this->createCompany();
        $user = User::factory()->create(['company_id' => $company->id, 'role' => 'admin']);
        $client = Client::factory()->create(['company_id' => $company->id]);
        $otherClient = Client::factory()->create();

        $response = $this->actingAs($user)->get('/clients');
        $response->assertOk();
        $response->assertSee($client->name);
        $response->assertDontSee($otherClient->name);
    }

    public function test_can_crud_client_within_company(): void
    {
        $company = $this->createCompany();
        $user = User::factory()->create(['company_id' => $company->id, 'role' => 'admin']);

        $payload = ['name' => 'Client1'];
        $response = $this->actingAs($user)->post('/clients', $payload);
        $response->assertRedirect('/clients');
        $this->assertDatabaseHas('clients', ['name' => 'Client1', 'company_id' => $company->id]);

        $client = Client::where('name', 'Client1')->first();
        $update = $this->actingAs($user)->put("/clients/{$client->id}", ['name' => 'Updated']);
        $update->assertRedirect('/clients');
        $this->assertDatabaseHas('clients', ['id' => $client->id, 'name' => 'Updated']);

        $delete = $this->actingAs($user)->delete("/clients/{$client->id}");
        $delete->assertRedirect('/clients');
        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }

    public function test_cannot_access_clients_of_other_company(): void
    {
        $company = $this->createCompany();
        $otherCompany = $this->createCompany(['slug' => 'other'.uniqid()]);
        $user = User::factory()->create(['company_id' => $company->id, 'role' => 'admin']);
        $otherClient = Client::factory()->create(['company_id' => $otherCompany->id]);

        $response = $this->actingAs($user)->get("/clients/{$otherClient->id}/edit");
        $response->assertStatus(403);
    }
}
