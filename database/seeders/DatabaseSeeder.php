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

        // Create default admin user
        $this->command->info('Creating default admin user...');
        $admin = User::firstOrCreate(
            ['email' => 'admin@rhetabica.net'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]
        );
        $this->command->info("Created or found admin user: {$admin->name} ({$admin->email})");

        // Create default test user
        $this->command->info('Creating default test user...');
        $user = User::firstOrCreate(
            ['email' => 'test@rhetabica.net'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'is_admin' => false,
            ]
        );
        $this->command->info("Created or found test user: {$user->name} ({$user->email})");

        // Seed institutions
        $this->call(InstitutionSeeder::class);
        $this->call(ParticipantCategoriesSeeder::class);

        // Seed speaker roles
        $this->call(SpeakerRoleSeeder::class);

        $this->command->info('Database seeding completed successfully!');
    }
}
