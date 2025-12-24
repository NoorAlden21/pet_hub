<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'description' => $this->description,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'is_active' => $this->is_active,
            'category' => new ProductCategoryResource($this->whenLoaded('productCategory')),
            'pet_type' => new PetTypeResource($this->whenLoaded('petType')),

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
        ];
    }
}
