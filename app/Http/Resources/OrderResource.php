<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'user'        => $this->whenLoaded('user', function () {
                return [
                    'user_id' => $this->user_id,
                    'user_name' => $this->user->name
                ];
            }),
            'total'          => (float) $this->total,
            'status'         => __('statuses.' . $this->status),
            'payment_status' => __('statuses.' . $this->payment_status),

            'items'          => OrderItemResource::collection(
                $this->whenLoaded('items')
            ),

            'created_at'     => $this->created_at,
        ];
    }
}
