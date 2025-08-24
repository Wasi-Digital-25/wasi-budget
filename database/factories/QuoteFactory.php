<?php

namespace Database\Factories;

use App\Models\Quote;
use App\Models\Company;
use App\Models\User;
use App\Models\Client;
use App\Models\QuoteItem;
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
            'user_id' => User::factory()->state(fn (array $attributes) => [
                'company_id' => $attributes['company_id'],
            ]),
            'client_id' => Client::factory()->state(fn (array $attributes) => [
                'company_id' => $attributes['company_id'],
                'created_by' => $attributes['user_id'],
            ]),
            'number' => 'Q-' . $this->faker->unique()->numerify('000000'),
            'client_name' => $this->faker->name(),
            'client_email' => $this->faker->safeEmail(),
            'client_phone' => $this->faker->phoneNumber(),
            'currency' => 'PEN',
            'total_cents' => 0,
            'status' => 'draft',
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Quote $quote) {
            QuoteItem::factory()->count(1)->create(['quote_id' => $quote->id]);
            $quote->refresh();
            $quote->save();
        });
    }
}
