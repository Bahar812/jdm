<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $name = trim((string) $this->input('name'));
        $slug = trim((string) $this->input('slug'));

        $this->merge([
            'name' => $name,
            'slug' => $slug !== '' ? Str::slug($slug) : ($name !== '' ? Str::slug($name) : null),
            'category' => trim((string) $this->input('category')),
            'badge' => $this->filled('badge') ? trim((string) $this->input('badge')) : null,
            'unit' => trim((string) $this->input('unit')),
            'image_url' => $this->filled('image_url') ? trim((string) $this->input('image_url')) : null,
            'description' => $this->filled('description') ? trim((string) $this->input('description')) : null,
        ]);
    }

    public function rules(): array
    {
        $product = $this->route('product');
        $ignoreId = $product instanceof Product ? $product->getKey() : null;

        return [
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'slug' => [
                'required',
                'string',
                'max:170',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('products', 'slug')->ignore($ignoreId),
            ],
            'category' => ['required', 'string', 'min:2', 'max:100'],
            'badge' => ['nullable', 'string', 'max:50'],
            'unit' => ['required', 'string', 'max:30'],
            'price' => ['required', 'integer', 'min:0', 'max:1000000000'],
            'stock' => ['required', 'integer', 'min:0', 'max:1000000'],
            'image_url' => ['nullable', 'url', 'max:1000'],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.regex' => 'Slug hanya boleh berisi huruf kecil, angka, dan tanda hubung.',
        ];
    }
}
