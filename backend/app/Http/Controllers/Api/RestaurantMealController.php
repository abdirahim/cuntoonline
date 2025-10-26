<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantMealResource;
use App\Models\RestaurantMeal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RestaurantMealController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = RestaurantMeal::with('restaurant')
            ->where('is_available', true);

        if ($request->has('restaurant_id')) {
            $query->where('restaurant_id', $request->input('restaurant_id'));
        }

        if ($request->has('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 20);
        $meals = $query->latest()->paginate($perPage);

        return RestaurantMealResource::collection($meals);
    }

    public function show(int $id)
    {
        $meal = RestaurantMeal::with('restaurant')->findOrFail($id);

        return new RestaurantMealResource($meal);
    }
}
