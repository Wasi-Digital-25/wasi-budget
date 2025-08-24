<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Quote;
use App\Policies\ClientPolicy;
use App\Policies\QuotePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Client::class => ClientPolicy::class,
        Quote::class => QuotePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
