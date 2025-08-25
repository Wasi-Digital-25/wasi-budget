<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuoteStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Quote::class);
    }

    public function rules(): array
    {
        return [
            'number' => ['required', 'string'],
            'items' => ['array'],
            'items.*.id' => ['nullable', 'integer', 'exists:budget_quote_items,id'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost_cents' => ['required', 'integer', 'min:0'],
        ];
    }
}
