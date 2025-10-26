<?php

namespace App\Http\Middleware;

use Closure;

class AdminAuth
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

//        if (Auth::guest())
//        {
//            return Redirect::to('/admin/login');
//        }
//        if(Auth::check()){
//            if(Auth::user()->id != ADMIN_ID)
//                return Redirect::to('/');
//        }
//        return $next($request);
    }
}
