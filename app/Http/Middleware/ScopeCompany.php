<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ScopeCompany
{
    public function handle(Request $request, Closure $next)
    {
        // Placeholder middleware for multi-company scoping
        return $next($request);
    }
}
