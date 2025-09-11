<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SpeakerRole;

class SpeakerRoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'First Speaker', 'abbreviation' => '1st', 'description' => 'First speaker of the team', 'order' => 1],
            ['name' => 'Second Speaker', 'abbreviation' => '2nd', 'description' => 'Second speaker of the team', 'order' => 2],
            ['name' => 'Third Speaker', 'abbreviation' => '3rd', 'description' => 'Third speaker of the team', 'order' => 3],
            ['name' => 'Fourth Speaker', 'abbreviation' => '4th', 'description' => 'Fourth speaker of the team', 'order' => 4],
            ['name' => 'Leader', 'abbreviation' => 'Ld', 'description' => 'Team leader / captain', 'order' => 0],
            ['name' => 'Reply Speaker', 'abbreviation' => 'RS', 'description' => 'Gives reply speech', 'order' => 5],
            ['name' => 'Adjudicator', 'abbreviation' => 'Adj', 'description' => 'Judge of the debate', 'order' => 0],
            ['name' => 'Prime Minister', 'abbreviation' => 'PM', 'description' => 'BP style government side speaker', 'order' => 1],
            ['name' => 'Leader of Opposition', 'abbreviation' => 'LO', 'description' => 'BP style opposition side speaker', 'order' => 1],
            ['name' => 'Deputy Prime Minister', 'abbreviation' => 'DPM', 'description' => 'BP style government side second speaker', 'order' => 2],
            ['name' => 'Deputy Leader of Opposition', 'abbreviation' => 'DLO', 'description' => 'BP style opposition side second speaker', 'order' => 2],
            ['name' => 'Member', 'abbreviation' => 'M', 'description' => 'General team member', 'order' => 3],
            ['name' => 'Whip', 'abbreviation' => 'Whip', 'description' => 'Closing speaker in BP style', 'order' => 4],
            ['name' => 'Interjector', 'abbreviation' => 'Int', 'description' => 'Sometimes used in informal debates', 'order' => 0],
        ];

        foreach ($roles as $role) {
            SpeakerRole::updateOrCreate(['name' => $role['name']], $role);
            $this->command->info("Seeded role: {$role['name']}");
        }
    }
}
