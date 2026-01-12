<?php

namespace App\Http\Requests\Admin\AppointmentCategory;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_en' => ['required', 'string', 'max:255', 'unique:appointment_categories,name_en'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
