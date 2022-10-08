<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Auth;

class BlockUser
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

        if (Auth::user()->active == 0) {
            Auth::logout();
            Session()->flush();
            return redirect()->route('login')->with('error', 'User account not Active Or User account is locked!.');
        } else {
            return $next($request);
        }

        //return $next($request);
    }
}
