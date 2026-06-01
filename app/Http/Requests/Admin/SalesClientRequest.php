<?php

namespace App\Http\Requests\Admin;

use App\Models\SalesClient;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalesClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'business_name' => trim((string) $this->input('business_name')),
            'business_type' => trim((string) $this->input('business_type')),
            'contact_person' => $this->filled('contact_person') ? trim((string) $this->input('contact_person')) : null,
            'phone' => $this->filled('phone') ? trim((string) $this->input('phone')) : null,
            'email' => $this->filled('email') ? strtolower(trim((string) $this->input('email'))) : null,
            'address' => $this->filled('address') ? trim((string) $this->input('address')) : null,
            'cooperation_offer' => $this->filled('cooperation_offer') ? trim((string) $this->input('cooperation_offer')) : null,
            'status' => trim((string) $this->input('status')),
            'notes' => $this->filled('notes') ? trim((string) $this->input('notes')) : null,
            'activity_notes' => $this->filled('activity_notes') ? trim((string) $this->input('activity_notes')) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'business_name' => ['required', 'string', 'min:3', 'max:150'],
            'business_type' => ['required', Rule::in(SalesClient::businessTypes())],
            'contact_person' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'regex:/^\+?[0-9][0-9\s().-]{7,24}$/'],
            'email' => ['nullable', 'email:rfc', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'cooperation_offer' => ['nullable', 'string', 'max:1500'],
            'status' => ['required', Rule::in(SalesClient::statuses())],
            'last_contacted_at' => ['nullable', 'date'],
            'next_follow_up_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'activity_notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Nomor HP client harus berupa nomor valid, contoh 081234567890 atau +6281234567890.',
        ];
    }
}
