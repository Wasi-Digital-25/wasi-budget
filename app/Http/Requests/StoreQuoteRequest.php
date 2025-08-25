<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Quote::class);
    }

    public function rules(): array
    {
        return [
            'number' => ['required','string'],
            'items' => ['required','array','min:1'],
            'items.*.description' => ['required','string'],
            'items.*.qty' => ['required','numeric','gt:0'],
            'items.*.price' => ['required','numeric','gte:0'],
        ];
    }
}
