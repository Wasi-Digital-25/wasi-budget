<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ScopeCompany
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()) {
            app()->instance('scope.company_id', $request->user()->company_id);
        }

        return $next($request);
    }
}
