<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if(!Auth::guard('admin')->check()){
            
            return redirect()->route('login')->with('error_message', 'Your are not authorized to visit this route!');
        }
        
        if(Auth::guard('admin')->user()->role == 'super-admin' || Auth::guard('admin')->user()->role == 'admin'){
            
            return redirect()->route('login')->with('error_message', 'Your are not authorized to visit this route!');
        }
        return $next($request);
    }
}
