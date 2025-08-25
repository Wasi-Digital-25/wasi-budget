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
        // 🔧 Limpia el historial de unique() antes de generar datos
        fake()->unique(true);

        $company = Company::factory()->create([
            'name' => 'Wasi Digital',
            'slug'  => 'wasi-digital',
            'plan'  => 'starter',
            'currency' => 'PEN',
        ]);

        $admin = User::factory()->create([
            'email' => 'admin@wasi.test',
            'name'  => 'Admin Wasi',
            'password' => Hash::make('password'),
            'company_id' => $company->id,
            'role'  => 'admin',
            'email_verified_at' => now(),
            'remember_token'   => Str::random(10),
        ]);

        User::factory()->count(2)->for($company)->state(['role' => 'staff'])->create();

        $clients = Client::factory()->count(10)
            ->for($company)
            ->for($admin, 'creator')
            ->create();

        Quote::factory()->count(20)
            ->for($company)
            ->for($admin)
            ->state(['client_id' => null]) // mantener esto
            ->create()
            ->each(function (Quote $quote) use ($clients) {
                $client = $clients->random();
                $quote->client()->associate($client);
                $quote->client_name  = $client->name;
                $quote->client_email = $client->email;
                $quote->client_phone = $client->phone;
                $quote->save();

                QuoteItem::factory()->count(rand(1, 4))->for($quote)->create();
                $quote->save();
            });
    }
}

