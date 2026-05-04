<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'customer_name' => trim((string) $this->input('customer_name')),
            'customer_email' => $this->filled('customer_email')
                ? strtolower(trim((string) $this->input('customer_email')))
                : null,
            'customer_phone' => trim((string) $this->input('customer_phone')),
            'shipping_address' => trim((string) $this->input('shipping_address')),
            'notes' => $this->filled('notes') ? trim((string) $this->input('notes')) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'min:3', 'max:100'],
            'customer_email' => ['nullable', 'email:rfc', 'max:255'],
            'customer_phone' => ['required', 'string', 'regex:/^\+?[0-9][0-9\s().-]{7,24}$/'],
            'shipping_address' => ['required', 'string', 'min:10', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_phone.regex' => 'Nomor HP harus berupa nomor valid, contoh 081234567890 atau +6281234567890.',
        ];
    }
}
