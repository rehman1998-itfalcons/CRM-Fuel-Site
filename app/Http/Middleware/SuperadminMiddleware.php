<?php

namespace App\Http\Middleware;

use Closure;

class SuperadminMiddleware
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
        if (\Auth::check()) {
            $expiresAt = \Carbon\Carbon::now()->addMinutes(1);
            \Cache::put('user-is-online-' . \Auth::user()->id, true, $expiresAt);
        }
        
      	$user = \Auth::user();
      	if ($user->role_id == 1)
        	return $next($request);
      	else
          	return abort(403, "Unathorized action.");
    }
}
