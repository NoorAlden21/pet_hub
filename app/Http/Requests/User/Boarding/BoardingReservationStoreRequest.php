<?php

namespace App\Http\Requests\User\Boarding;

class BoardingReservationStoreRequest extends BoardingQuoteRequest
{
    public function rules(): array
    {
        return parent::rules() + [];
    }
}
