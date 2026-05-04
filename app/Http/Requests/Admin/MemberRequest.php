<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class MemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim((string) $this->input('name')),
            'email' => strtolower(trim((string) $this->input('email'))),
            'phone' => $this->filled('phone') ? trim((string) $this->input('phone')) : null,
            'address' => $this->filled('address') ? trim((string) $this->input('address')) : null,
        ]);
    }

    public function rules(): array
    {
        $member = $this->route('member');
        $ignoreId = $member instanceof User ? $member->getKey() : null;
        $passwordRequirement = $ignoreId ? 'nullable' : 'required';

        return [
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:255', Rule::unique('users', 'email')->ignore($ignoreId)],
            'phone' => ['nullable', 'string', 'regex:/^\+?[0-9][0-9\s().-]{7,24}$/'],
            'address' => ['nullable', 'string', 'max:1000'],
            'role' => ['required', Rule::in(['admin', 'customer'])],
            'password' => [$passwordRequirement, 'string', 'max:255', Password::min(8)->letters()->numbers()],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Nomor HP harus berupa nomor valid, contoh 081234567890 atau +6281234567890.',
        ];
    }
}
