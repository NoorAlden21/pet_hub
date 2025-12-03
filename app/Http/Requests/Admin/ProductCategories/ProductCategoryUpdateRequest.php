<?php

namespace App\Http\Requests\Admin\ProductCategories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductCategoryUpdateRequest extends FormRequest
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
        $productCategory = $this->route('product_category');
        $id = $productCategory?->id;

        return [
            'name_en' => ['sometimes', 'string', 'max:255', Rule::unique('product_categories', 'name_en')->ignore($id)],
            'name_ar' => ['sometimes', 'string', 'max:255', Rule::unique('product_categories', 'name_en')->ignore($id)]
        ];
    }
}
