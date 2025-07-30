<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 9.99, 99.99),
            'max_tab_spaces' => fake()->numberBetween(1, 10),
            'max_tournaments_per_tab' => fake()->numberBetween(5, 50),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the package is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the package has unlimited tab spaces.
     */
    public function unlimitedTabSpaces(): static
    {
        return $this->state(fn (array $attributes) => [
            'max_tab_spaces' => -1,
        ]);
    }

    /**
     * Indicate that the package has unlimited tournaments per tab.
     */
    public function unlimitedTournamentsPerTab(): static
    {
        return $this->state(fn (array $attributes) => [
            'max_tournaments_per_tab' => -1,
        ]);
    }
} 