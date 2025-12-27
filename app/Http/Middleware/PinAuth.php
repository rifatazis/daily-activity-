<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PinAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('authenticated')) {
            return redirect('/pin');
        }

        return $next($request);
    }
}
