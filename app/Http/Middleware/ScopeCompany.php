<?php

namespace App\Http\Middleware;

use App\Models\Client;
use App\Models\Quote;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScopeCompany
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $companyId = Auth::user()->company_id;

            $applyScope = function (string $model) use ($companyId): void {
                $model::addGlobalScope('company', function (Builder $builder) use ($companyId) {
                    $builder->where('company_id', $companyId);
                });
            };

            $applyScope(Client::class);
            $applyScope(Quote::class);
        }

        return $next($request);
    }
}

