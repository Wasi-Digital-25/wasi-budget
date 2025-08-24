<?php

namespace Database\Factories;

use App\Models\Quote;
use App\Models\Company;
use App\Models\User;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Quote>
 */
class QuoteFactory extends Factory
{
    protected $model = Quote::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'user_id' => User::factory(),
            'client_id' => Client::factory(),
            'number' => 'Q-' . $this->faker->unique()->numerify('000000'),
            'client_name' => $this->faker->name(),
            'client_email' => $this->faker->safeEmail(),
            'client_phone' => $this->faker->phoneNumber(),
            'currency' => 'PEN',
            'total_cents' => 0,
            'status' => 'draft',
        ];
    }
}
