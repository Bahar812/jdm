<?php

namespace App\Http\Requests\Admin;

use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class InventoryMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'type' => trim((string) $this->input('type')),
            'note' => $this->filled('note') ? trim((string) $this->input('note')) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'type' => ['required', Rule::in(InventoryMovement::types())],
            'quantity' => ['required', 'integer', 'min:1', 'max:1000000'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty() || $this->input('type') !== InventoryMovement::TYPE_OUT) {
                return;
            }

            $product = Product::query()->find($this->integer('product_id'));
            if (! $product) {
                return;
            }

            if ($this->integer('quantity') > (int) $product->stock) {
                $validator->errors()->add('quantity', 'Quantity stock out tidak boleh melebihi stok tersedia.');
            }
        });
    }
}
