<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeder de Admin
        $this->call([
            AdminUserSeeder::class,
            DemoSeeder::class,
        ]);

        // Usuario demo idempotente (no choca con tests)
        User::firstOrCreate(
            ['email' => 'demo@wasi-budget.test'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('Demo123!'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );
    }
}
