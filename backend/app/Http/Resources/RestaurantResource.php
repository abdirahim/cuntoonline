<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image ? url('storage/' . $this->image) : null,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zip_code,
            'phone' => $this->phone,
            'email' => $this->email,
            'delivery_fee' => (float) $this->delivery_fee,
            'min_order_amount' => $this->min_order_amount,
            'estimated_delivery_time' => $this->estimated_delivery_time,
            'rating' => (float) $this->rating,
            'total_reviews' => $this->total_reviews,
            'is_active' => $this->is_active,
            'times' => RestaurantTimeResource::collection($this->whenLoaded('times')),
            'meals' => RestaurantMealResource::collection($this->whenLoaded('meals')),
            'created_at' => $this->created_at,
        ];
    }
}
