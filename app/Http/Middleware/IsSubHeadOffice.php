<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Closure;

class IsSubHeadOffice
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
        if (Auth::guard('head')->check()) {
            if (Auth::guard('head')->user()){
                return $next($request);
            }
        }
        return redirect()->route('sub-head-office-login')->with('unsuccess',"You don't have access to that section");
    }
}
