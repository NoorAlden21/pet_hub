<?php

namespace App\Http\Requests\User\Boarding;

use Illuminate\Foundation\Http\FormRequest;

class BoardingQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pet_type_id' => 'required|exists:pet_types,id',
            'pet_breed_id' => 'nullable|exists:pet_breeds,id',
            'age_months' => 'nullable|integer|min:0|max:600',

            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',

            'services' => 'nullable|array',
            'services.*.id' => 'required|exists:boarding_services,id',
            'services.*.quantity' => 'nullable|integer|min:1',
        ];
    }
}
