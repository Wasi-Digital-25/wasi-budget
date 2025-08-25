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
            'items'                   => ['required','array','min:1'],
            'items.*.description'     => ['nullable','string','max:255'],
            'items.*.qty'             => ['required','integer','min:1'],
            'items.*.price'           => ['required','numeric','min:0'],
        ];
    }
}
