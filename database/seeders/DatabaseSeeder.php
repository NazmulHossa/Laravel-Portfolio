<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\SkillSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// This is the MASTER seeder.
// When you run: php artisan db:seed
// Laravel runs this file, which calls all individual seeders in order.
//
// Commands:
//   php artisan db:seed                 → runs this file (adds data on top)
//   php artisan migrate:fresh --seed    → drops ALL tables, re-migrates, then seeds
//                                         USE THIS when you want a clean fresh start

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Create Admin User ─────────────────────────────────
        // Hash::make() uses Bcrypt algorithm — REQUIRED by Laravel auth.
        // NEVER store a plain text password like 'password' directly.
        // Hash::make('password') → produces something like:
        //   $2y$12$eImiTXuWVxfM37uY4JANjQ==...
        User::updateOrCreate(
            ['email' => 'admin@portfolio.com'],   // find by email (avoid duplicates)
            [
                'name'     => 'Admin',
                'email'    => 'admin@portfolio.com',
                'password' => Hash::make('password'), // ← Bcrypt hashed
            ]
        );
        // Login at: /login
        // Email:    admin@portfolio.com
        // Password: password

        // ── Skills & Projects ─────────────────────────────────
        $this->call([
            SkillSeeder::class,
            ProjectSeeder::class,
        ]);
    }
}