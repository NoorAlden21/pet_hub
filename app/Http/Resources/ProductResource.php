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

            'cover_image' => $this->whenLoaded('coverImage', fn () => $this->coverImage?->url),
            'images' => $this->whenLoaded(
                'images',
                fn () =>
                $this->images->map(fn ($img) => [
                    'id' => $img->id,
                    'url' => $img->url,
                ])
            ),
        ];
    }
}
