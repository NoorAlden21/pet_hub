<?php

namespace App\Http\Requests\Admin\PetType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PetTypeUpdateRequest extends FormRequest
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
        $petType = $this->route('pet_type');
        $id = $petType?->id;
        return [
            'name_en' => ['sometimes', 'string', Rule::unique('pet_types', 'name_en')->ignore($id)],
            'name_ar' => ['sometimes', 'string', Rule::unique('pet_types', 'name_ar')->ignore($id)],
        ];
    }
}
