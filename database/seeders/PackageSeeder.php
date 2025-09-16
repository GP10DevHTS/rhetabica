<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding packages...');

        $packages = [
            [
                'name' => 'Free',
                'description' => 'Basic package for new users with limited features',
                'price' => 0.00,
                'max_tab_spaces' => 1,
                'max_tournaments_per_tab' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Starter',
                'description' => 'Perfect for small teams and individual users',
                'price' => 9.99,
                'max_tab_spaces' => 3,
                'max_tournaments_per_tab' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Professional',
                'description' => 'Advanced features for growing organizations',
                'price' => 29.99,
                'max_tab_spaces' => 10,
                'max_tournaments_per_tab' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'Enterprise',
                'description' => 'Unlimited access for large organizations',
                'price' => 99.99,
                'max_tab_spaces' => -1, // Unlimited
                'max_tournaments_per_tab' => -1, // Unlimited
                'is_active' => true,
            ],
        ];

        foreach ($packages as $package) {
            $createdPackage = Package::updateOrCreate(
                ['name' => $package['name']], // Unique identifier
                $package // Values to update or set
            );
            $this->command->info("Created or updated package: {$createdPackage->name} - \${$createdPackage->price}");
        }

        $this->command->info('Packages seeded successfully!');
    }
}
