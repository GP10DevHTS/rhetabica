<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class GeminiTeamNameGenerator
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    /**
     * Generate debate team names using AI.
     *
     * @param string $context Name or description of the team (e.g., "Makerere University Team")
     * @param int $count Number of team names to generate
     * @param string|null $style Optional style: creative, funny, aggressive
     * @param array $excludedNames Names to avoid
     * @return array
     */
    public function generate(string $context, int $count = 5, ?string $style = null, array $excludedNames = []): array
    {
        try {
            $styleText = $style ? " in a {$style} style" : '';
            $excludedText = empty($excludedNames) ? '' : "\nAvoid using these names: " . implode(', ', $excludedNames) . ".";

            $prompt = "Suggest {$count} unique, short debate team names for {$context}{$styleText}. 
Each line must be exactly one name with no extra description.{$excludedText}";

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

            // --- Parse the response into clean team names ---
            $names = [];
            $lines = preg_split("/\r\n|\r|\n/", $text);

            foreach ($lines as $line) {
                // Remove bullets, numbers, leading/trailing whitespace
                $line = preg_replace('/^[\*\-\d\.\s]+/', '', trim($line));

                // Skip empty lines or lines that are clearly instructions
                if ($line === '' || preg_match('/here are|unique|suitable|for/i', $line)) {
                    continue;
                }

                $names[] = $line;
            }

            // Remove duplicates and excluded names
            $names = array_values(array_unique(array_diff($names, $excludedNames)));

            // Log the AI generation
            Log::info('AI team name generation success', [
                'user'    => Auth::check() ? "Id: " . Auth::id() . ", Email: " . Auth::user()->email : 'guest',
                'context' => $context,
                'count'   => $count,
                'style'   => $style,
                'prompt'  => $prompt,
                'raw'     => $response->json(),
                'parsed'  => $names,
            ]);

            return array_slice($names, 0, $count);

        } catch (\Exception $e) {
            Log::error('AI team name generation failed', [
                'user'    => Auth::check() ? "Id: " . Auth::id() . ", Email: " . Auth::user()->email : 'guest',
                'context' => $context,
                'count'   => $count,
                'style'   => $style,
                'error'   => $e->getMessage(),
            ]);

            // Fallback names if AI fails
            $fallback = [
                'Logic Legends', 'Rhetoric Rangers', 'The Persuaders',
                'Debate Dynamos', 'Argument Avengers', 'The Orators',
                'Speech Spartans', 'The Disputers'
            ];

            shuffle($fallback);

            return array_slice($fallback, 0, $count);
        }
    }
}
