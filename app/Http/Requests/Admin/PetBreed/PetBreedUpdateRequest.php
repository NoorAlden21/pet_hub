<?php

namespace App\Http\Requests\Admin\PetBreed;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PetBreedUpdateRequest extends FormRequest
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
        $petBreed = $this->route('pet_breed');
        $id = $petBreed?->id;

        return [
            'pet_type_id' => ['sometimes', 'exists:pet_types,id'],
            'name_en' => ['sometimes', 'string', Rule::unique('pet_breeds', 'name_en')->ignore($id)],
            'name_ar' => ['sometimes', 'string', Rule::unique('pet_breeds', 'name_ar')->ignore($id)]
        ];
    }
}
