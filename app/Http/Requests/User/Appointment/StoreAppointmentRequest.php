<?php

namespace App\Http\Requests\User\Appointment;

use App\Models\PetBreed;
use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pet_type_id' => ['required', 'exists:pet_types,id'],
            'pet_breed_id' => ['nullable', 'exists:pet_breeds,id'],
            'appointment_category_id' => ['required', 'exists:appointment_categories,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
