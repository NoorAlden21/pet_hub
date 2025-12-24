<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_category_id' => ['required', 'exists:product_categories,id'],
            'pet_type_id' => ['required', 'exists:pet_types,id'],
            'name_en' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name_en')
                    ->where(fn ($query) => $query->where(
                        'product_category_id',
                        $this->input('product_category_id')
                    )),
            ],

            'name_ar' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name_ar')
                    ->where(fn ($query) => $query->where(
                        'product_category_id',
                        $this->input('product_category_id')
                    )),
            ],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'gte:0'],
            'stock_quantity' => ['sometimes', 'integer', 'gte:0'],
            'is_active' => ['nullable', 'boolean'],

            'images' => ['sometimes', 'array', 'max:10'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}
