<?php

namespace Database\Factories;

use App\Models\QuoteItem;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuoteItem>
 */
class QuoteItemFactory extends Factory
{
    protected $model = QuoteItem::class;

    public function definition(): array
    {
        $qty = $this->faker->numberBetween(1,5);
        $unitPrice = $this->faker->numberBetween(1000,5000);
        $lineTotal = $qty * $unitPrice;
        return [
            'quote_id' => Quote::factory(),
            'description' => $this->faker->sentence(),
            'quantity' => $qty,
            'unit_price_cents' => $unitPrice,
            'total_cents' => $lineTotal,
        ];
    }
}
