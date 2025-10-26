<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantMealResource;
use App\Models\RestaurantMeal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MealController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = RestaurantMeal::with('restaurant');

        if ($request->has('restaurant_id')) {
            $query->where('restaurant_id', $request->input('restaurant_id'));
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->has('is_available')) {
            $query->where('is_available', $request->boolean('is_available'));
        }

        $perPage = $request->input('per_page', 15);
        $meals = $query->latest()->paginate($perPage);

        return RestaurantMealResource::collection($meals);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'is_available' => 'nullable|boolean',
            'is_vegetarian' => 'nullable|boolean',
            'is_vegan' => 'nullable|boolean',
            'is_gluten_free' => 'nullable|boolean',
            'preparation_time' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('meals', 'public');
        }

        $meal = RestaurantMeal::create($validated);

        return response()->json([
            'data' => new RestaurantMealResource($meal->load('restaurant')),
            'message' => 'Meal created successfully',
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $meal = RestaurantMeal::with('restaurant')->findOrFail($id);

        return response()->json([
            'data' => new RestaurantMealResource($meal),
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $meal = RestaurantMeal::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'sometimes|required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'is_available' => 'nullable|boolean',
            'is_vegetarian' => 'nullable|boolean',
            'is_vegan' => 'nullable|boolean',
            'is_gluten_free' => 'nullable|boolean',
            'preparation_time' => 'nullable|integer|min:0',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if ($request->hasFile('image')) {
            if ($meal->image) {
                Storage::disk('public')->delete($meal->image);
            }
            $validated['image'] = $request->file('image')->store('meals', 'public');
        }

        $meal->update($validated);

        return response()->json([
            'data' => new RestaurantMealResource($meal->load('restaurant')),
            'message' => 'Meal updated successfully',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $meal = RestaurantMeal::findOrFail($id);
        
        if ($meal->image) {
            Storage::disk('public')->delete($meal->image);
        }
        
        $meal->delete();

        return response()->json([
            'message' => 'Meal deleted successfully',
        ]);
    }

    public function toggleAvailability(int $id): JsonResponse
    {
        $meal = RestaurantMeal::findOrFail($id);
        $meal->update(['is_available' => !$meal->is_available]);

        return response()->json([
            'data' => new RestaurantMealResource($meal),
            'message' => 'Meal availability updated successfully',
        ]);
    }
}
