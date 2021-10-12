<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckBlockedAcount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth('sanctum')->user()->blocked){
            return response()->json(['error' => 'Sorry your account is blocked by system administrator.'], 403);
        }
        return $next($request);
    }
}
