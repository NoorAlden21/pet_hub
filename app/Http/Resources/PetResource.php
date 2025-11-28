<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'pet_type' => $this->whenLoaded('petType', function () {
                return [
                    'id' => $this->petType->id,
                    'name' => $this->petType->name,
                ];
            }),
            'pet_breed' => $this->whenLoaded('petBreed', function () {
                return [
                    'id' => $this->petBreed->id,
                    'name' => $this->petBreed->name,
                ];
            }),
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth?->toDateString(),
            'description' => $this->description,
            'is_adoptable' => $this->is_adoptable,
        ];
    }
}
