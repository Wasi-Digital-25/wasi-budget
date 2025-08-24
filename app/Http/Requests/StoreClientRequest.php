<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Client::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'email' => ['nullable','email'],
            'phone' => ['nullable','string','max:50'],
            'tax_id' => ['nullable','string','max:50'],
            'address' => ['nullable','string'],
            'notes' => ['nullable','string'],
        ];
    }
}
