<?php

namespace App\Http\Requests\Admin\Boarding;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BoardingServiceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_en' => 'sometimes|string|max:255|', Rule::unique('boarding_services', 'name_en')->ignore($this->route('boarding_service')->id),
            'name_ar' => 'sometimes|string|max:255|', Rule::unique('boarding_services', 'name_ar')->ignore($this->route('boarding_service')->id),
            'price' => 'sometimes|numeric|min:0',
            'is_active' => 'boolean',
        ];
    }
}
