<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'pet_type' => $this->petType ? [
                'id' => $this->petType->id,
                'name' => $this->petType->name,
            ] : null,

            'pet_breed' => $this->petBreed ? [
                'id' => $this->petBreed->id,
                'name' => $this->petBreed->name,
            ] : null,

            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ] : null,

            'appointment_date' => $this->appointment_date?->toDateString(),
            'notes' => $this->notes,

            'status' => $this->status,
            'rejection_reason' => $this->rejection_reason,

            'user' => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ] : null,

            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
