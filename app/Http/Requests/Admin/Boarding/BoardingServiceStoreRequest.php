<?php

namespace App\Http\Requests\Admin\Boarding;

use Illuminate\Foundation\Http\FormRequest;

class BoardingServiceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_en' => 'required|string|max:255|unique:boarding_services,name_en',
            'name_ar' => 'required|string|max:255|unique:boarding_services,name_ar',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ];
    }
}
