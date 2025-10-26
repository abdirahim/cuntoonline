<?php

namespace App\Http\Middleware;

use Closure;

class Guestadmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
     //   dd('aaa');
        return $next($request);
    }
}
