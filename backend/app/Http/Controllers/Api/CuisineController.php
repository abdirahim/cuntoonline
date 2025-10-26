<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cuisine;
use Illuminate\Http\Request;

class CuisineController extends Controller
{
    /**
     * Display a listing of active cuisines.
     */
    public function index(Request $request)
    {
        $query = Cuisine::where('is_active', true);

        // Optionally include restaurant count
        if ($request->query('with_count')) {
            $query->withCount('restaurants');
        }

        $cuisines = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $cuisines
        ]);
    }

    /**
     * Display the specified cuisine.
     */
    public function show($slug)
    {
        $cuisine = Cuisine::where('slug', $slug)
            ->where('is_active', true)
            ->withCount('restaurants')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $cuisine
        ]);
    }
}