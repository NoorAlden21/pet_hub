<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'product_id' => $this->product_id,
            'quantity'   => $this->quantity,
            'unit_price' => (float) $this->unit_price,
            'line_total' => (float) $this->line_total,

            'product'    => $this->whenLoaded('product', function () {
                return [
                    'id'    => $this->product->id,
                    'name'  => $this->product->name,
                    'price' => (float) $this->product->price,
                    'cover_image' => $this->product->relationLoaded('coverImage')
                        ? ($this->product->coverImage?->path
                            ? asset('storage/' . ltrim($this->product->coverImage->path, '/'))
                            : null)
                        : null,
                ];
            }),

            'created_at' => $this->created_at,
        ];
    }
}
