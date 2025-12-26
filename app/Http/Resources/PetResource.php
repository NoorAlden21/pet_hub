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

            'cover_image' => $this->whenLoaded('coverImage', function () use ($request) {
                return $this->coverImage?->path
                    ? $request->getSchemeAndHttpHost() . '/storage/' . ltrim($this->coverImage->path, '/')
                    : null;
            }),

            'images' => $this->whenLoaded('images', function () use ($request) {
                return $this->images->map(function ($img) use ($request) {
                    return [
                        'id' => $img->id,
                        'url' => $img->path
                            ? $request->getSchemeAndHttpHost() . '/storage/' . ltrim($img->path, '/')
                            : null,
                    ];
                });
            }),

            'adoption_applications' => AdoptionApplicationResource::collection(
                $this->whenLoaded('adoptionApplication')
            ),
        ];
    }
}
