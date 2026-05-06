<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Str;
use Parsedown;

class LlmService
{
    public function generatePlanning(array $data): array
    {
        $endpoint = config('services.nvidia.endpoint');
        $apiKey   = config('services.nvidia.api_key');
        $model    = config('services.nvidia.model');

        if (!$endpoint || !$apiKey || !$model) {
            return [
                'status' => 'failed',
                'error'  => 'NVIDIA API configuration is missing.',
            ];
        }

        $prompt = $this->buildPlanningPrompt($data);

        try {
            $response = Http::timeout(120)
                ->retries(2, 1000)
                ->withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                    'Accept'        => 'application/json',
                ])
                ->post($endpoint, [
                    'model'       => $model,
                    'messages'    => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.3,
                    'top_p'       => 1,
                    'max_tokens'  => 1200,
                    'stream'      => false,
                ]);

            if (!$response->successful()) {
                return [
                    'status' => 'failed',
                    'error'  => $response->body(),
                ];
            }

            $raw  = (string) $response->json('choices.0.message.content');
            $json = $this->extractJson($raw);

            if (!$json) {
                return [
                    'status'   => 'completed',
                    'planning' => [],
                    'raw'      => $raw,
                ];
            }

            $decoded = json_decode($json, true);

            return is_array($decoded) ? $decoded : [
                'status'   => 'completed',
                'planning' => [],
                'raw'      => $raw,
            ];

        } catch (\Exception $e) {
            \Log::error('LLM API Error: ' . $e->getMessage(), [
                'endpoint'    => $endpoint,
                'error_class' => get_class($e),
            ]);

            return [
                'status' => 'failed',
                'error'  => 'API request failed: ' . $e->getMessage(),
            ];
        }
    }

    public function generateSummary(string $type, array $data): string
    {
        $endpoint = config('services.nvidia.endpoint');
        $apiKey   = config('services.nvidia.api_key');
        $model    = config('services.nvidia.model');

        if (!$endpoint || !$apiKey || !$model) {
            return "Erreur: Configuration API NVIDIA incomplète.";
        }

        $prompt = $this->buildSummaryPrompt($type, $data);

        if (empty($prompt)) {
            return "Impossible de créer le prompt pour ce type de document.";
        }

        try {
            $response = Http::connectTimeout(10)
                ->timeout(120)
                ->withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                    'Accept'        => 'application/json',
                ])
                ->post($endpoint, [
                    'model'       => $model,
                    'messages'    => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.7,
                    'top_p'       => 1,
                    'max_tokens'  => 1024,
                    'stream'      => false,
                ]);

            if ($response->successful()) {
                $raw = $response->json('choices.0.message.content') ?? '';
                return $this->markdownToHtml($raw);
            }

            \Log::warning('NVIDIA summary API failed', ['status' => $response->status()]);
            return "Erreur API ({$response->status()}): Résumé non disponible.";

        } catch (ConnectionException $e) {
            \Log::error('NVIDIA summary timeout', ['error' => $e->getMessage()]);
            return "Délai dépassé: Le résumé AI n'est pas disponible.";

        } catch (\Exception $e) {
            \Log::error('NVIDIA summary error', ['error' => $e->getMessage()]);
            return "Erreur inattendue lors de la génération du résumé.";
        }
    }

    private function markdownToHtml(string $markdown): string
    {
        $markdown = trim($markdown);

        // Strip wrapping curly braces the model sometimes adds
        $markdown = preg_replace('/^\s*\{+\s*/', '', $markdown);
        $markdown = preg_replace('/\s*\}+\s*$/', '', $markdown);
        $markdown = trim($markdown);

        $parsedown = new Parsedown();
        $parsedown->setSafeMode(true);
        return $parsedown->text($markdown);
    }

    private function buildSummaryPrompt(string $type, array $data): string
    {
        if ($type === 'appointment') {
    $a = $data['appointment'];

    $patient = optional($a->patient)->name ?? 'Inconnu';
    $doctor  = optional(optional($a->doctor)->user)->name ?? 'Inconnu';
    $date    = optional($a->appointment_date_time)->toDateTimeString() ?? 'Non précisée';
    $reason  = $a->reason ?? 'Non précisée';

    return "Tu es un assistant médical. Génère un résumé médical en français en respectant EXACTEMENT ce format markdown, chaque ligne séparée:\n\n"
         . "**Patient:** {$patient}\n\n"
         . "**Médecin:** {$doctor}\n\n"
         . "**Date:** {$date}\n\n"
         . "**Raison:** {$reason}\n\n"
         . "**Résumé:** [écris ici 2-3 phrases de résumé médical]\n\n"
         . "**Recommandations:**\n"
         . "- [recommandation 1]\n"
         . "- [recommandation 2]";
}

if ($type === 'consultation') {
    $c = $data['consultation'];
    $a = $c->appointment;

    $patient = optional(optional($a)->patient)->name ?? 'Inconnu';
    $doctor  = optional(optional(optional($a)->doctor)->user)->name ?? 'Inconnu';
    $date    = optional(optional($a)->appointment_date_time)->toDateTimeString() ?? 'Non précisée';
    $notes   = $c->notes ?? 'Aucune note';

    return "Tu es un assistant médical. Génère un résumé médical en français en respectant EXACTEMENT ce format markdown, chaque ligne séparée:\n\n"
         . "**Patient:** {$patient}\n\n"
         . "**Médecin:** {$doctor}\n\n"
         . "**Date:** {$date}\n\n"
         . "**Notes cliniques:** {$notes}\n\n"
         . "**Résumé:** [écris ici 2-3 phrases de résumé médical]\n\n"
         . "**Recommandations:**\n"
         . "- [recommandation 1]\n"
         . "- [recommandation 2]";
}

        return '';
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
        $end   = strrpos($raw, '}');

        if ($start === false || $end === false || $end <= $start) {
            return null;
        }

        return trim(Str::substr($raw, $start, $end - $start + 1));
    }
}
