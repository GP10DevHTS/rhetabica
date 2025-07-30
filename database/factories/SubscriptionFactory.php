<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'package_id' => Package::factory(),
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'status' => 'active',
        ];
    }

    /**
     * Indicate that the subscription is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Indicate that the subscription is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'expired',
            'end_date' => now()->subDays(30),
        ]);
    }

    /**
     * Indicate that the subscription has expired end date.
     */
    public function expiredDate(): static
    {
        return $this->state(fn (array $attributes) => [
            'end_date' => now()->subDays(30),
        ]);
    }

    /**
     * Indicate that the subscription is for a specific user.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Indicate that the subscription is for a specific package.
     */
    public function forPackage(Package $package): static
    {
        return $this->state(fn (array $attributes) => [
            'package_id' => $package->id,
        ]);
    }
} 