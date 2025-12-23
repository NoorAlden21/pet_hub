<?php

namespace App\Http\Requests\Admin\Pet;

use Illuminate\Foundation\Http\FormRequest;

class PetUpdateRequest extends FormRequest
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
            'owner_id' => ['sometimes', 'exists:users,id'],
            'pet_type_id' => ['sometimes', 'exists:pet_types,id'],
            'pet_breed_id' => ['sometimes', 'exists:pet_breeds,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'date_of_birth' => ['sometimes', 'date'],
            'gender' => ['sometimes', 'in:male,female,unknown'],
            'description' => ['sometimes', 'string'],
            'is_adoptable' => ['sometimes', 'boolean'],

            'images' => ['sometimes', 'array', 'max:10'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}
