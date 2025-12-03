<?php

namespace App\Http\Requests\Admin\ProductCategories;

use Illuminate\Foundation\Http\FormRequest;

class ProductCategoryStoreRequest extends FormRequest
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
            'name_en' => ['required', 'string', 'max:255', 'unique:product_categories,name_en'],
            'name_ar' => ['required', 'string', 'max:255', 'unique:product_categories,name_ar']
        ];
    }
}
