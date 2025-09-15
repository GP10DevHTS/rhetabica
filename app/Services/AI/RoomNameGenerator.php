<?php

namespace App\Services\AI;

class RoomNameGenerator
{
    protected array $providers = [
        'gemini' => GeminiRoomNameGenerator::class,
        // 'openai' => OpenAIRoomNameGenerator::class,  // placeholder for future
        // 'deepseek' => DeepSeekRoomNameGenerator::class, // placeholder
    ];

    protected string $default = 'gemini';

    public function generate(string $tournamentName, int $count, string $provider = null, array $existingNames = []): array
    {
        $provider = $provider ?? $this->default;

        if (!isset($this->providers[$provider])) {
            throw new \Exception("AI provider {$provider} not available");
        }

        return app($this->providers[$provider])->generate($tournamentName, $count, $existingNames);
    }
}
