<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;

class GeminiRoomNameGenerator implements RoomNameGeneratorInterface
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    public function generate(string $tournamentName, int $count): array
    {
        try {
            $prompt = "Suggest {$count} creative, unique, short room names for a debate tournament called '{$tournamentName}'.";

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

            // Extract Gemini response
            $text = $response->json('candidates.0.content.parts.0.text', '');

            // Parse response into array of names
            $names = [];

            $lines = preg_split("/\r\n|\r|\n/", $text);

            foreach ($lines as $line) {
                // Match **Room Name** style
                if (preg_match('/\*\*(.*?)\*\*/', $line, $matches)) {
                    $names[] = trim($matches[1]);
                } else {
                    // Fallback: detect "1. Room Name"
                    if (preg_match('/^\d+\.\s*(.+?)(:|$)/', $line, $matches)) {
                        $names[] = trim($matches[1]);
                    }
                }
            }

            // Last fallback: split plain text
            if (empty($names)) {
                $names = array_filter(array_map('trim', preg_split("/[\r\n,]+/", $text)));
            }

            // dd($names);
            return array_slice($names, 0, $count);

        } catch (\Exception $e) {
            // Fallback list if AI fails
            $fallback = [
                'The Grand Oratorium', 'The Persuasion Hall', 'The Logic Arena',
                'The Eloquence Chamber', 'Debate Den', 'Rhetoric Room', 'Reason Retreat'
            ];

            shuffle($fallback);

            return array_slice($fallback, 0, $count);
        }
    }
}
