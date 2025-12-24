<?php

namespace App\Http\Requests\User\AdoptionApplication;

use Illuminate\Foundation\Http\FormRequest;

class AdoptionApplicationStoreRequest extends FormRequest
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
            'pet_id' => 'required|exists:pets,id',
            'motivation' => 'required|string|max:1000',
        ];
    }
}
