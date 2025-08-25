<?php

namespace App\Services;

use App\Models\Quote;
use App\Models\QuoteItem;

class QuoteService
{
    public function syncItems(Quote $quote, array $items): Quote
    {
        $existingIds = collect($items)->pluck('id')->filter()->all();
        $quote->items()->whereNotIn('id', $existingIds)->delete();

        foreach ($items as $itemData) {
            $attributes = [
                'description' => $itemData['description'],
                'quantity' => $itemData['quantity'],
                'unit_cost_cents' => $itemData['unit_cost_cents'],
            ];

            if (isset($itemData['id'])) {
                $quote->items()->where('id', $itemData['id'])->update($attributes);
            } else {
                $quote->items()->create($attributes);
            }
        }

        return $quote->refresh();
    }

    public function recalculateTotals(Quote $quote): void
    {
        $total = $quote->items->sum(fn (QuoteItem $item) => $item->quantity * $item->unit_cost_cents);
        $quote->total_cents = $total;
        $quote->save();
    }
}
