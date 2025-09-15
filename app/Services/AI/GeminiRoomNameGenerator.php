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

            $text = $response->json('candidates.0.content.parts.0.text', '');

            $names = [];
            $lines = preg_split("/\r\n|\r|\n/", $text);

            foreach ($lines as $line) {
                if (preg_match('/\*\*(.*?)\*\*/', $line, $matches)) {
                    $names[] = rtrim(trim($matches[1]), ':');
                } elseif (preg_match('/^\d+\.\s*(.+?)(:|$)/', $line, $matches)) {
                    $names[] = rtrim(trim($matches[1]), ':');
                }
            }

            if (empty($names)) {
                $names = array_filter(array_map(fn($n) => rtrim(trim($n), ':'), preg_split("/[\r\n,]+/", $text)));
            }

            // --- Log success ---
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
            Log::error('AI room name generation failed', [
                'user'       => Auth::check() ? "Id: " . Auth::id() . ", Email: " . Auth::user()->email : 'guest',
                'tournament' => $tournamentName,
                'count'      => $count,
                'error'      => $e->getMessage(),
            ]);

            $fallback = [
                'The Grand Oratorium', 'The Persuasion Hall', 'The Logic Arena',
                'The Eloquence Chamber', 'Debate Den', 'Rhetoric Room', 'Reason Retreat'
            ];

            shuffle($fallback);

            return array_slice($fallback, 0, $count);
        }
    }
}
