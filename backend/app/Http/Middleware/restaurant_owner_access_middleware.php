
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestaurantOwnerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isRestaurantOwner()) {
            return response()->json([
                'message' => 'Unauthorized. Restaurant owner access required.',
            ], 403);
        }

        return $next($request);
    }
}
