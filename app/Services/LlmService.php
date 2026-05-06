<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class LlmService
{
    public function generatePlanning(array $data): array
    {
        $endpoint = config('services.nvidia.endpoint');
        $apiKey = config('services.nvidia.api_key');
        $model = config('services.nvidia.model');

        if (!$endpoint || !$apiKey || !$model) {
            return [
                'status' => 'failed',
                'error' => 'NVIDIA API configuration is missing.',
            ];
        }

        $prompt = $this->buildPlanningPrompt($data);

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$apiKey}",
            'Accept' => 'application/json',
        ])->post($endpoint, [
            'model' => $model,
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.3,
            'top_p' => 1,
            'max_tokens' => 1200,
            'stream' => false,
        ]);

        if (!$response->successful()) {
            return [
                'status' => 'failed',
                'error' => $response->body(),
            ];
        }

        $raw = (string) $response->json('choices.0.message.content');
        $json = $this->extractJson($raw);

        if (!$json) {
            return [
                'status' => 'completed',
                'planning' => [],
                'raw' => $raw,
            ];
        }

        $decoded = json_decode($json, true);

        return is_array($decoded) ? $decoded : [
            'status' => 'completed',
            'planning' => [],
            'raw' => $raw,
        ];
    }

    private function buildPlanningPrompt(array $data): string
    {
        $context = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return "Tu es un assistant medical. Genere un planning JSON strict avec ces cles: planning (liste), suggestions (liste), conflicts (liste). "
            . "Chaque element planning doit contenir: time, patient, priority, notes. "
            . "Suggestions: patient, suggested_time, priority, reason. "
            . "Conflicts: time, reason. Voici le contexte: \n" . $context;
    }

    private function extractJson(string $raw): ?string
    {
        $start = strpos($raw, '{');
        $end = strrpos($raw, '}');

        if ($start === false || $end === false || $end <= $start) {
            return null;
        }

        return trim(Str::substr($raw, $start, $end - $start + 1));
    }
}
