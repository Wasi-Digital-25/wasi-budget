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
            'number' => ['required','string'],
            'items' => ['required','array','min:1'],
            'items.*.description' => ['required','string'],
            'items.*.qty' => ['required','numeric','gt:0'],
            'items.*.price' => ['required','numeric','gte:0'],
        ];
    }
}
