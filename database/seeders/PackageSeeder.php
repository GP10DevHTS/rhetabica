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
        $packages = [
            [
                'name' => 'Free',
                'description' => 'Basic package for new users with limited features',
                'price' => 0.00,
                'max_namespaces' => 1,
                'max_tournaments' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Starter',
                'description' => 'Perfect for small teams and individual users',
                'price' => 9.99,
                'max_namespaces' => 3,
                'max_tournaments' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Professional',
                'description' => 'Advanced features for growing organizations',
                'price' => 29.99,
                'max_namespaces' => 10,
                'max_tournaments' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'Enterprise',
                'description' => 'Unlimited access for large organizations',
                'price' => 99.99,
                'max_namespaces' => -1, // Unlimited
                'max_tournaments' => -1, // Unlimited
                'is_active' => true,
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
} 