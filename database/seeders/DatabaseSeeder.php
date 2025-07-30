<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create default admin user
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@rhetabica.net',
            'password' => bcrypt('password'),
        ]);

        // Create default test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@rhetabica.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);
    }
}
