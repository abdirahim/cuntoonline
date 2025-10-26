<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckCORS
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the origin from the request
        $origin = $request->header('Origin', 'http://localhost');

        // Define allowed origins
        $allowedOrigins = [
            'http://localhost:5173',
            'http://localhost:3000',
            'http://localhost',
            // Add your production domain here when needed
        ];

        // Check if the origin is allowed
        if (in_array($origin, $allowedOrigins)) {
            $allowOrigin = $origin;
        } else {
            $allowOrigin = 'http://localhost';
        }

        $headers = [
            'Access-Control-Allow-Origin' => $allowOrigin,
            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers' => 'Content-Type, X-Auth-Token, Origin, Authorization',
            'Access-Control-Allow-Credentials' => 'true'
        ];

        if ($request->getMethod() == "OPTIONS") {
            return response('OK', 200)
                ->withHeaders($headers);
        }

        $response = $next($request);
        foreach ($headers as $key => $value) {
            $response->header($key, $value);
        }
        return $response;
    }
}