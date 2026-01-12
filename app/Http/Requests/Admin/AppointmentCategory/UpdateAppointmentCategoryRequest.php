<?php

namespace App\Http\Requests\Admin\AppointmentCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAppointmentCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_en' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('appointment_categories', 'name_en')->ignore($this->appointment_category)],
            'name_ar' => ['sometimes', 'nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
