<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
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

        User::firstOrCreate(
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
    }
}
