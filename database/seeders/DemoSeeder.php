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
        $company = Company::firstOrCreate(
            ['slug' => 'wasi-digital'],
            ['name' => 'Wasi Digital', 'plan' => 'starter', 'currency' => 'PEN']
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@wasi.test'],
            [
                'name' => 'Admin Wasi',
                'password' => Hash::make('password'),
                'company_id' => $company->id,
                'role' => 'admin',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );

        User::factory()->count(2)->create([
            'company_id' => $company->id,
            'role' => 'staff',
        ]);

        $clients = Client::factory()->count(10)->create([
            'company_id' => $company->id,
            'created_by' => $admin->id,
        ]);

        Quote::factory()->count(20)->create([
            'company_id' => $company->id,
            'user_id' => $admin->id,
        ])->each(function (Quote $quote) use ($clients) {
            $client = $clients->random();
            $quote->client_id = $client->id;
            $quote->client_name = $client->name;
            $quote->client_email = $client->email;
            $quote->client_phone = $client->phone;
            $quote->save();

            QuoteItem::factory()->count(rand(2,5))->create(['quote_id' => $quote->id]);
            $quote->save();
        });
    }
}
