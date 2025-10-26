<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantResource;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RestaurantController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Restaurant::with(['user', 'times']);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $perPage = $request->input('per_page', 15);
        $restaurants = $query->latest()->paginate($perPage);

        return RestaurantResource::collection($restaurants);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'delivery_fee' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|integer|min:0',
            'estimated_delivery_time' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('restaurants', 'public');
        }

        $restaurant = Restaurant::create($validated);

        return response()->json([
            'data' => new RestaurantResource($restaurant->load('user', 'times')),
            'message' => 'Restaurant created successfully',
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $restaurant = Restaurant::with(['user', 'times', 'meals'])->findOrFail($id);

        return response()->json([
            'data' => new RestaurantResource($restaurant),
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $restaurant = Restaurant::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:100',
            'state' => 'sometimes|required|string|max:100',
            'zip_code' => 'sometimes|required|string|max:20',
            'phone' => 'sometimes|required|string|max:20',
            'email' => 'nullable|email|max:255',
            'delivery_fee' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|integer|min:0',
            'estimated_delivery_time' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if ($request->hasFile('image')) {
            if ($restaurant->image) {
                Storage::disk('public')->delete($restaurant->image);
            }
            $validated['image'] = $request->file('image')->store('restaurants', 'public');
        }

        $restaurant->update($validated);

        return response()->json([
            'data' => new RestaurantResource($restaurant->load('user', 'times')),
            'message' => 'Restaurant updated successfully',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $restaurant = Restaurant::findOrFail($id);
        
        if ($restaurant->image) {
            Storage::disk('public')->delete($restaurant->image);
        }
        
        $restaurant->delete();

        return response()->json([
            'message' => 'Restaurant deleted successfully',
        ]);
    }

    public function toggleStatus(int $id): JsonResponse
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->update(['is_active' => !$restaurant->is_active]);

        return response()->json([
            'data' => new RestaurantResource($restaurant),
            'message' => 'Restaurant status updated successfully',
        ]);
    }
}
