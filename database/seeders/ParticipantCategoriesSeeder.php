<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\ParticipantCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ParticipantCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = User::first()?->id ?? 1; // fallback if no user exists

        $categories = [
            ['name' => 'Junior', 'description' => 'Beginner level debaters'],
            ['name' => 'General', 'description' => 'Intermediate debaters'],
            ['name' => 'Advanced', 'description' => 'Experienced debaters'],
            ['name' => 'Senior', 'description' => 'Top-level debaters'],
        ];

        foreach ($categories as $category) {
            ParticipantCategory::firstOrCreate(
                ['name' => $category['name']],
                [
                    'description' => $category['description'],
                    'uuid' => \Illuminate\Support\Str::slug($category['name']),
                    'user_id' => $userId,
                ]
            );

            $this->command->info("Created category: {$category['name']}");
        }
    }
}