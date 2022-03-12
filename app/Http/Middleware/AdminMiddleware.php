<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // checking whether the user is an admin or not
        if(Auth::check() || Auth::user()->is_admin){
            // returning a response
            return $next($request);
        }
        return response(['status' => 'unauthorized', 'user' => Auth::user(), 'msg' => 'Administration role is required'],403);

    }
}
