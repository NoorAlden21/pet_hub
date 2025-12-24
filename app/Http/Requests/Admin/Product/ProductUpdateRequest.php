<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductUpdateRequest extends FormRequest
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

        $product = $this->route('product');

        $categoryId = $this->input('product_category_id', $product?->product_category_id);

        return [
            'product_category_id' => ['sometimes', 'exists:product_categories,id'],
            'pet_type_id' => ['sometimes', 'exists:pet_types,id'],
            'name_en' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('products', 'name_en')
                    ->where(fn ($query) => $query->where('product_category_id', $categoryId))
                    ->ignore($product?->id),
            ],

            'name_ar' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('products', 'name_ar')
                    ->where(fn ($query) => $query->where('product_category_id', $categoryId))
                    ->ignore($product?->id),
            ],
            'description' => ['sometimes', 'string'],
            'price' => ['sometimes', 'numeric', 'gte:0'],
            'stock_quantity' => ['sometimes', 'integer', 'gte:0'],
            'is_active' => ['sometimes', 'boolean'],

            'images' => ['sometimes', 'array', 'max:10'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}
