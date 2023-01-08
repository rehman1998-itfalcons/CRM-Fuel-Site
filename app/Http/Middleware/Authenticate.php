<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

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
        if (\Auth::check()) {
            $expiresAt = \Carbon\Carbon::now()->addMinutes(1);
            \Cache::put('user-is-online-' . \Auth::user()->id, true, $expiresAt);
        }        
        
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
