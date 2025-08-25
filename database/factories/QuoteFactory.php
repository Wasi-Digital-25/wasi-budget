<?php

namespace Database\Factories;

use App\Models\Quote;
use App\Models\Company;
use App\Models\User;
use App\Models\Client;
use App\Models\QuoteItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Quote>
 */
class QuoteFactory extends Factory
{
    protected $model = Quote::class;

    public function definition(): array
    {
        return [
            // Reutiliza atributos si vienen; si no, crea con factories.
            'company_id' => fn (array $attributes) => $attributes['company_id'] ?? Company::factory(),

            'user_id' => function (array $attributes) {
                return $attributes['user_id']
                    ?? User::factory()->state(fn (array $userAttrs) => [
                        'company_id' => $attributes['company_id'] ?? Company::factory(),
                    ]);
            },

            'client_id' => function (array $attributes) {
                return $attributes['client_id']
                    ?? Client::factory()->state(fn (array $clientAttrs) => [
                        'company_id' => $attributes['company_id'] ?? Company::factory(),
                        'created_by' => $attributes['user_id']
                            ?? User::factory()->state(fn (array $userAttrs) => [
                                'company_id' => $attributes['company_id'] ?? Company::factory(),
                            ]),
                    ]);
            },

            // Evita OverflowException de Faker con unique()->numerify()
            'number'       => 'Q-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4)),
            'client_name'  => $this->faker->name(),
            'client_email' => $this->faker->safeEmail(),
            'client_phone' => $this->faker->phoneNumber(),
            'currency'     => 'PEN',
            'total_cents'  => 0,
            'status'       => 'draft',
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Quote $quote) {
            QuoteItem::factory()->count(1)->create(['quote_id' => $quote->id]);
            $quote->recalculateTotals();
        });
    }
}


