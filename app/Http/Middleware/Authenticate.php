<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
// use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        // if(Auth::guard($guard)->check()) {
        //     return redirect()->to('/user/dashboard');
        // }

        return route('web.home');

        // if (!$request->expectsJson()) {
        //     return redirect()->route('web.login');
        // }

         // return $next($request);
        
    }
}
