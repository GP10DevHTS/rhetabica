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
        $this->command->info('Starting database seeding...');

        // Seed packages first
        $this->command->info('Calling PackageSeeder...');
        $this->call(PackageSeeder::class);

        // User::factory(10)->create();

        // Create default admin user
        $this->command->info('Creating default admin user...');
        $admin = User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@rhetabica.net',
            'password' => bcrypt('password'),
        ]);
        $this->command->info("Created admin user: {$admin->name} ({$admin->email})");

        // Create default test user
        $this->command->info('Creating default test user...');
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@rhetabica.net',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);
        $this->command->info("Created test user: {$user->name} ({$user->email})");

        $this->command->info('Database seeding completed successfully!');
    }
}
