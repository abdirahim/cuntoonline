<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\RestaurantMealController;
use App\Http\Controllers\Api\UserAddressController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\RestaurantController as AdminRestaurantController;
use App\Http\Controllers\Api\Admin\MealController as AdminMealController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CuisineController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Cuisines - Public
Route::get('/cuisines', [CuisineController::class, 'index']);
Route::get('/cuisines/{slug}', [CuisineController::class, 'show']);


// Restaurants - Public
    Route::get('/restaurants', [RestaurantController::class, 'index']);
    Route::get('/restaurants/{slug}', [RestaurantController::class, 'show']);

// Meals - Public
    Route::get('/meals', [RestaurantMealController::class, 'index']);
    Route::get('/meals/{id}', [RestaurantMealController::class, 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // User Addresses
    Route::apiResource('addresses', UserAddressController::class)->except(['show']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel']);

    // Admin routes
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('dashboard/stats', [DashboardController::class, 'stats']);

        // Restaurants Management
        Route::apiResource('restaurants', AdminRestaurantController::class);
        Route::post('restaurants/{id}/toggle-status', [AdminRestaurantController::class, 'toggleStatus']);

        // Meals Management
        Route::apiResource('meals', AdminMealController::class);
        Route::post('meals/{id}/toggle-availability', [AdminMealController::class, 'toggleAvailability']);

        // Orders Management
        Route::get('orders', [AdminOrderController::class, 'index']);
        Route::get('orders/{id}', [AdminOrderController::class, 'show']);
        Route::put('orders/{id}/status', [AdminOrderController::class, 'updateStatus']);

        // Users Management
        Route::apiResource('users', AdminUserController::class);
    });
});