<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Request;
use Redirect;
use App\User;

class FrontAuth
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
        if(Auth::check()){
            $email =	User::where('id',Auth::user()->id)->pluck('email');
            if($email=='' && Request::segment(1)!='edit-profile'){
                Session::flash('error','Please add your email first.');
                return View::make('myaccount.edit_profile',compact('email'));
            }
        }

        if (Auth::guest()){
            if(Request::ajax()){
                return Response::view('ajaxlogin')->header('Content-Type', 'text/javascript'); die;
            }else{
                return Redirect::to('login?redirect=1');
            }

        }

        return $next($request);
    }
}
