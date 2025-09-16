<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class GeminiRoomNameGenerator implements RoomNameGeneratorInterface
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    /**
     * Generate AI room names
     *
     * @param string $tournamentName
     * @param int $count
     * @param array $existingNames
     * @return array
     */
    public function generate(string $tournamentName, int $count, array $existingNames = []): array
    {
        try {
            $existingNamesText = empty($existingNames)
                ? ''
                : "\nAlready taken room names: " . implode(', ', $existingNames) . ". Please avoid these.";

            $prompt = "Suggest {$count} creative, unique, short room names for a debate tournament called '{$tournamentName}'.{$existingNamesText}
- Each room name should be on its own line.
- Do not include numbers, bullets, or descriptions.
- Do not repeat any existing names.
- Output only the room names, one per line.";

            $response = Http::post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$this->apiKey}",
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                ]
            );

            $text = $response->json('candidates.0.content.parts.0.text', '');

            // Split lines and clean
            $lines = preg_split("/\r\n|\r|\n/", $text);
            $names = array_filter(array_map(fn($n) => rtrim(trim($n), ':'), $lines));

            // Remove duplicates and existing names
            $names = array_values(array_unique(array_diff($names, $existingNames)));

            // Log success
            Log::info('AI room name generation success', [
                'user'       => Auth::check() ? "Id: " . Auth::id() . ", Email: " . Auth::user()->email : 'guest',
                'tournament' => $tournamentName,
                'count'      => $count,
                'prompt'     => $prompt,
                'raw'        => $response->json(),
                'parsed'     => $names,
            ]);

            return array_slice($names, 0, $count);

        } catch (\Exception $e) {
            // Log failure
            Log::error('AI room name generation failed', [
                'user'       => Auth::check() ? "Id: " . Auth::id() . ", Email: " . Auth::user()->email : 'guest',
                'tournament' => $tournamentName,
                'count'      => $count,
                'error'      => $e->getMessage(),
            ]);

            // Fallback room names
            $fallback = [
                'The Grand Oratorium', 'The Persuasion Hall', 'The Logic Arena',
                'The Eloquence Chamber', 'Debate Den', 'Rhetoric Room', 'Reason Retreat'
            ];

            shuffle($fallback);

            return array_slice($fallback, 0, $count);
        }
    }
}
