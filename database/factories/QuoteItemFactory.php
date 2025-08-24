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
        $qty = $this->faker->randomFloat(2,1,5);
        $unitPrice = $this->faker->numberBetween(1000,5000);
        $lineTotal = (int) ($qty * $unitPrice);
        return [
            'quote_id' => Quote::factory(),
            'sku' => $this->faker->ean8(),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'quantity' => $qty,
            'unit' => 'units',
            'unit_price_cents' => $unitPrice,
            'discount_cents' => 0,
            'tax_cents' => 0,
            'line_total_cents' => $lineTotal,
        ];
    }
}
