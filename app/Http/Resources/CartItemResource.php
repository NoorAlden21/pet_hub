<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'cart_id'    => $this->cart_id,
            'product_id' => $this->product_id,
            'quantity'   => $this->quantity,

            'product'    => $this->whenLoaded('product', function () {
                return [
                    'id'          => $this->product->id,
                    'name'        => $this->product->name,
                    'price'       => $this->product->price,
                    'description' => $this->product->description,
                ];
            }),

            'line_total' => $this->when(
                $this->relationLoaded('product') && $this->product,
                fn () => (float) $this->product->price * $this->quantity
            ),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
