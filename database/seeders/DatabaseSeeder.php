<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\SkillSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;



class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
      
        User::updateOrCreate(
            ['email' => 'admin@portfolio.com'],   // find by email (avoid duplicates)
            [
                'name'     => 'Admin',
                'email'    => 'admin@portfolio.com',
                'password' => Hash::make('password'), // ← Bcrypt hashed
            ]
        );
        
        $this->call([
            SkillSeeder::class,
            ProjectSeeder::class,
        ]);
    }
}
