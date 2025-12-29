<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoardingReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'user' => $this->whenLoaded('user', function () {
                return $this->user ? [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ] : null;
            }),
            'pet_type_id' => $this->pet_type_id,
            'pet_type' => $this->whenLoaded('petType', function () {
                return $this->petType ? [
                    'id' => $this->petType->id,
                    'name' => $this->petType->name,
                ] : null;
            }),
            'pet_breed_id' => $this->pet_breed_id,
            'pet_breed' => $this->whenLoaded('petBreed', function () {
                return $this->petBreed ? [
                    'id' => $this->petBreed->id,
                    'name' => $this->petBreed->name,
                ] : null;
            }),
            'age_months' => $this->age_months,

            'start_at' => $this->start_at?->toISOString(),
            'end_at' => $this->end_at?->toISOString(),
            'billable_hours' => $this->billable_hours,

            'status' => $this->status,
            'total' => $this->total,

            'services' => BoardingServiceResource::collection($this->whenLoaded('services')),
            // 'services' => $this->whenLoaded('services', function () {
            //     return $this->services->map(fn ($s) => [
            //         'id' => $s->id,
            //         'name' => $s->name,
            //         'price' => $s->price,
            //         'quantity' => $s->pivot->quantity,
            //     ])->values();
            // }),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
