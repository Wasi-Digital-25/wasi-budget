<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use App\Models\Client;
use App\Models\Quote;
use App\Models\QuoteItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Limpia el historial de valores únicos de Faker para evitar OverflowException
        fake()->unique(true);

        $company = Company::factory()->create([
            'name'     => 'Wasi Digital',
            'slug'     => 'wasi-digital',
            'plan'     => 'starter',
            'currency' => 'PEN',
        ]);

        $admin = User::factory()->create([
            'email'             => 'admin@wasi.test',
            'name'              => 'Admin Wasi',
            'password'          => Hash::make('password'),
            'company_id'        => $company->id,
            'role'              => 'admin',
            'email_verified_at' => now(),
            'remember_token'    => Str::random(10),
        ]);

        // 2 usuarios de staff en la misma empresa
        User::factory()
            ->count(2)
            ->for($company)
            ->state(['role' => 'staff'])
            ->create();

        // 10 clientes para la empresa, creados por el admin
        $clients = Client::factory()
            ->count(10)
            ->for($company)
            ->for($admin, 'creator')
            ->create();

        // 20 cotizaciones: primero sin cliente (client_id null),
        // luego se asigna un cliente existente y se generan items.
        Quote::factory()
            ->count(20)
            ->for($company)
            ->for($admin)
            ->state(['client_id' => null]) // mantener esto para que la factory no cree cliente nuevo
            ->create()
            ->each(function (Quote $quote) use ($clients) {
                $client = $clients->random();

                // Asociar cliente y sincronizar datos "denormalizados"
                $quote->client()->associate($client);
                $quote->client_name  = $client->name;
                $quote->client_email = $client->email;
                $quote->client_phone = $client->phone;
                $quote->save();

                // Ítems de 1 a 4 por cotización
                QuoteItem::factory()
                    ->count(rand(1, 4))
                    ->for($quote)
                    ->create();

                // Forzar recálculo/guardado si el modelo lo hace en eventos
                $quote->save();
            });
    }
}

