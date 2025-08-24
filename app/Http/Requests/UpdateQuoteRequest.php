<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('quote'));
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required','exists:clients,id'],
            'items' => ['required','array','min:1'],
            'items.*.description' => ['required','string'],
            'items.*.qty' => ['required','numeric','gt:0'],
            'items.*.unit_price_cents' => ['required','numeric','gte:0'],
            'items.*.discount_cents' => ['nullable','numeric','gte:0'],
            'currency' => ['required','in:PEN,USD'],
            'expires_at' => ['required','date','after:today'],
            'number' => ['required','string'],
        ];
    }
}
