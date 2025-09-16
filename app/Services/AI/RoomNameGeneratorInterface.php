<?php

namespace App\Services\AI;

interface RoomNameGeneratorInterface
{
    public function generate(string $tournamentName, int $count, array $existingNames = []): array;
}

