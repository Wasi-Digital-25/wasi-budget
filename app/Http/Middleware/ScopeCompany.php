<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ScopeCompany
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user || !$user->company_id) {
            abort(403);
        }

        $client = $request->route('client');
        if ($client && $client->company_id !== $user->company_id) {
            abort(403);
        }

        $quote = $request->route('quote');
        if ($quote && $quote->company_id !== $user->company_id) {
            abort(403);
        }

        return $next($request);
    }
}
