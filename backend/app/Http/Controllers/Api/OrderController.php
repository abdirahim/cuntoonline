<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\RestaurantMeal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $orders = $request->user()
            ->orders()
            ->with(['restaurant', 'items.meal', 'address'])
            ->latest()
            ->paginate(10);

        return OrderResource::collection($orders);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $order = $request->user()
            ->orders()
            ->with(['restaurant', 'items.meal', 'address'])
            ->findOrFail($id);

        return response()->json([
            'data' => new OrderResource($order),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'user_address_id' => 'required|exists:user_addresses,id',
            'items' => 'required|array|min:1',
            'items.*.meal_id' => 'required|exists:restaurant_meals,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.special_instructions' => 'nullable|string',
            'special_instructions' => 'nullable|string',
            'payment_method' => 'required|in:cash,card,online',
        ]);

        $order = DB::transaction(function () use ($validated, $request) {
            $subtotal = 0;
            $orderItems = [];

            foreach ($validated['items'] as $item) {
                $meal = RestaurantMeal::findOrFail($item['meal_id']);
                $itemSubtotal = $meal->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $orderItems[] = [
                    'restaurant_meal_id' => $meal->id,
                    'meal_name' => $meal->name,
                    'price' => $meal->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemSubtotal,
                    'special_instructions' => $item['special_instructions'] ?? null,
                ];
            }

            $restaurant = \App\Models\Restaurant::findOrFail($validated['restaurant_id']);
            $deliveryFee = $restaurant->delivery_fee;
            $tax = $subtotal * 0.1; // 10% tax
            $total = $subtotal + $deliveryFee + $tax;

            $order = Order::create([
                'user_id' => $request->user()->id,
                'restaurant_id' => $validated['restaurant_id'],
                'user_address_id' => $validated['user_address_id'],
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'tax' => $tax,
                'total' => $total,
                'special_instructions' => $validated['special_instructions'] ?? null,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'status' => 'pending',
                'estimated_delivery_time' => now()->addMinutes($restaurant->estimated_delivery_time),
            ]);

            $order->items()->createMany($orderItems);

            return $order->load(['restaurant', 'items.meal', 'address']);
        });

        return response()->json([
            'data' => new OrderResource($order),
            'message' => 'Order created successfully',
        ], 201);
    }

    public function cancel(Request $request, int $id): JsonResponse
    {
        $order = $request->user()
            ->orders()
            ->findOrFail($id);

        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return response()->json([
                'message' => 'This order cannot be cancelled',
            ], 422);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json([
            'data' => new OrderResource($order->load(['restaurant', 'items.meal', 'address'])),
            'message' => 'Order cancelled successfully',
        ]);
    }
}
