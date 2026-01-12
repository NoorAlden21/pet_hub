<?php

namespace App\Http\Requests\User\Appointment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAppointmentStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in(['approved', 'rejected', 'completed', 'missed']),
            ],
            'rejection_reason' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
