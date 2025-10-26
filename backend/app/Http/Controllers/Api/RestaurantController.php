<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantResource;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RestaurantController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Restaurant::with(['times'])
            ->where('is_active', true);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->has('city')) {
            $query->where('city', $request->input('city'));
        }

        $perPage = $request->input('per_page', 12);
        $restaurants = $query->latest()->paginate($perPage);

        return RestaurantResource::collection($restaurants);
    }

    public function show(string $slug): JsonResponse
    {
        $restaurant = Restaurant::with(['times', 'meals' => function ($query) {
            $query->where('is_available', true);
        }])->where('slug', $slug)->firstOrFail();

        return response()->json([
            'data' => new RestaurantResource($restaurant),
        ]);
    }
}
