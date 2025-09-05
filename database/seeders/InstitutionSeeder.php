<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institution;
use App\Models\User;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = User::first()?->id ?? 1; // fallback if no users exist yet

        $institutions = [
            // Debate bodies
            [
                'name' => 'Debate Institute Africa (DIA)',
                'country' => 'Uganda',
                'city' => 'Kampala',
                'website' => 'https://debateinstituteafrica.org',
                'logo_path' => null,
                'user_id' => $userId,
            ],
            [
                'name' => 'Uganda National Students’ Association (UNSA)',
                'country' => 'Uganda',
                'city' => 'Kampala',
                'website' => 'https://unsa.ug',
                'logo_path' => null,
                'user_id' => $userId,
            ],

            // Major Universities
            [
                'name' => 'Makerere University',
                'country' => 'Uganda',
                'city' => 'Kampala',
                'website' => 'https://www.mak.ac.ug',
                'logo_path' => null,
                'user_id' => $userId,
            ],
            [
                'name' => 'Kyambogo University',
                'country' => 'Uganda',
                'city' => 'Kampala',
                'website' => 'https://kyu.ac.ug',
                'logo_path' => null,
                'user_id' => $userId,
            ],
            [
                'name' => 'Uganda Christian University',
                'country' => 'Uganda',
                'city' => 'Mukono',
                'website' => 'https://ucu.ac.ug',
                'logo_path' => null,
                'user_id' => $userId,
            ],
            [
                'name' => 'Mbarara University of Science and Technology',
                'country' => 'Uganda',
                'city' => 'Mbarara',
                'website' => 'https://www.must.ac.ug',
                'logo_path' => null,
                'user_id' => $userId,
            ],
            [
                'name' => 'Gulu University',
                'country' => 'Uganda',
                'city' => 'Gulu',
                'website' => 'https://gu.ac.ug',
                'logo_path' => null,
                'user_id' => $userId,
            ],

            // Major Secondary Schools
            [
                'name' => 'Namilyango College',
                'country' => 'Uganda',
                'city' => 'Mukono',
                'website' => null,
                'logo_path' => null,
                'user_id' => $userId,
            ],
            [
                'name' => 'Gayaza High School',
                'country' => 'Uganda',
                'city' => 'Kampala',
                'website' => null,
                'logo_path' => null,
                'user_id' => $userId,
            ],
            [
                'name' => 'Kings College Budo',
                'country' => 'Uganda',
                'city' => 'Wakiso',
                'website' => null,
                'logo_path' => null,
                'user_id' => $userId,
            ],
            [
                'name' => 'St. Mary’s Kitende',
                'country' => 'Uganda',
                'city' => 'Wakiso',
                'website' => null,
                'logo_path' => null,
                'user_id' => $userId,
            ],
        ];

        foreach ($institutions as $institution) {
            Institution::firstOrCreate(
                ['name' => $institution['name']],
                $institution
            );

            $this->command->info("Seeded institution: {$institution['name']}");
        }
    }
}
