<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminAccount
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
        if(auth('sanctum')->user() && auth('sanctum')->user()->admin){
            return $next($request);
        }else{
            return response()->json(['error' => "Sorry can't access this page"], 403);
        }
    }
}
