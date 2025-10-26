<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'status' => $this->status,
            'subtotal' => (float) $this->subtotal,
            'delivery_fee' => (float) $this->delivery_fee,
            'tax' => (float) $this->tax,
            'total' => (float) $this->total,
            'special_instructions' => $this->special_instructions,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'estimated_delivery_time' => $this->estimated_delivery_time,
            'delivered_at' => $this->delivered_at,
            'restaurant' => new RestaurantResource($this->whenLoaded('restaurant')),
            'address' => new UserAddressResource($this->whenLoaded('address')),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at,
        ];
    }
}
