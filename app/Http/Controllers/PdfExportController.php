<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Support\Facades\Http;
use App\Models\Appointment;
use App\Models\Consultation;

class PdfExportController extends Controller
{
    public function generatePdf(Request $request, $type, $id)
    {
        $data = [];
        $viewName = '';

        switch ($type) {
            case 'appointment':
                $appointment = Appointment::with(['patient', 'doctor.user', 'doctor.specialty'])->findOrFail($id);
                $data['appointment'] = $appointment;
                $viewName = 'pdf.appointment';
                break;
            case 'consultation':
                $consultation = Consultation::with(['appointment.patient', 'appointment.doctor.user', 'appointment.doctor.specialty'])->findOrFail($id);
                $data['consultation'] = $consultation;
                $viewName = 'pdf.consultation';
                break;
            default:
                abort(404, "Type de PDF non supporté.");
        }

        // Generate AI summary
        $summary = $this->getAiSummary($type, $data);
        $data['summary'] = $summary;

        return Pdf::view($viewName, $data)
            ->format('a4')
            ->name($type . '-' . $id . '.pdf');
    }

    private function getAiSummary($type, $data)
    {
        $apiKey = config('services.nvidia.api_key');
        $apiEndpoint = config('services.nvidia.endpoint');
        $model = config('services.nvidia.model');

        if (!$apiKey || !$apiEndpoint || !$model) {
            return "Erreur: La configuration de l'API NVIDIA est incomplète (clé, endpoint ou modèle manquant).";
        }

        $prompt = $this->createPrompt($type, $data);

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$apiKey}",
            'Accept' => 'application/json',
        ])->post($apiEndpoint, [
            'model' => $model,
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7,
            'top_p' => 1,
            'max_tokens' => 1024,
            'stream' => false
        ]);

        if ($response->successful()) {
            return $response->json('choices.0.message.content');
        }

        return "Impossible de générer le résumé AI. " . $response->body();
    }

    private function createPrompt($type, $data)
    {
        if ($type === 'appointment') {
            $appointment = $data['appointment'];
            return "Génère un résumé pour le rendez-vous suivant:\n" .
                   "Patient: " . optional($appointment->patient)->name . "\n" .
                   "Docteur: " . optional(optional($appointment->doctor)->user)->name . "\n" .
                   "Date: " . optional($appointment->appointment_date_time)->toDateTimeString() . "\n" .
                   "Raison: " . ($appointment->reason ?? '');
        }

        if ($type === 'consultation') {
            $consultation = $data['consultation'];
            return "Génère un résumé pour la consultation suivante:\n" .
                   "Patient: " . optional(optional($consultation->appointment)->patient)->name . "\n" .
                   "Docteur: " . optional(optional(optional($consultation->appointment)->doctor)->user)->name . "\n" .
                   "Date: " . optional(optional($consultation->appointment)->appointment_date_time)->toDateTimeString() . "\n" .
                   "Notes: " . ($consultation->notes ?? '');
        }

        return '';
    }
}
