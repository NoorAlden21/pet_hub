<?php

namespace App\Http\Requests\Admin\PetBreed;

use Illuminate\Foundation\Http\FormRequest;

class PetBreedStoreRequest extends FormRequest
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
            'pet_type_id' => ['required', 'exists:pet_types,id'],
            'name_en' => ['required', 'string', 'max:255', 'unique:pet_breeds,name_en'],
            'name_ar' => ['required', 'string', 'max:255', 'unique:pet_breeds,name_ar']
        ];
    }
}
