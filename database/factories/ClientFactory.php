<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            // Si la factory recibe company_id lo reutiliza; si no, crea una.
            'company_id' => Company::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'tax_id' => $this->faker->unique()->numerify('###########'),
            'address' => $this->faker->address(),
            'notes' => $this->faker->sentence(),

            // El usuario creador pertenecerá a la misma company (o una nueva si no se pasó).
            'created_by' => User::factory()->state(fn (array $attributes) => [
                'company_id' => $attributes['company_id'] ?? Company::factory(),
            ]),
        ];
    }
}
