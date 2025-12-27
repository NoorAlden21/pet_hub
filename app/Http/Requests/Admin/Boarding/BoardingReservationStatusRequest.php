<?php

namespace App\Http\Requests\Admin\Boarding;

use Illuminate\Foundation\Http\FormRequest;

class BoardingReservationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:pending,confirmed,rejected,cancelled,completed',
        ];
    }
}
