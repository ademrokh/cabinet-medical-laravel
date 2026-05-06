<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use App\Services\PdfService;
use App\Services\LlmService;
use App\Models\Appointment;
use App\Models\Consultation;

class PdfExportController extends Controller
{
    public function __construct(
        private PdfService $pdfService,
        private LlmService $llmService,
    ) {}

    // Page that shows the summary + download button
    public function showExportPage(Request $request, $type, $id)
    {
        $data = $this->loadData($type, $id);
        return view('pdf.export-page', array_merge($data, ['type' => $type, 'id' => $id]));
    }

    // AJAX endpoint — called by the page to get AI summary
    public function getSummary(Request $request, $type, $id)
    {
        $data = $this->loadData($type, $id);
        $summary = $this->llmService->generateSummary($type, $data);
        return response()->json(['summary' => $summary]);
    }

    // PDF download — summary posted from the frontend
    public function generatePdf(Request $request, $type, $id)
    {
        $request->validate([
            'summary' => 'nullable|string|max:5000',
        ]);

        $data = $this->loadData($type, $id);
        $data['summary'] = $request->input('summary', 'Résumé non disponible.');

        $viewName = $type === 'appointment' ? 'pdf.appointment' : 'pdf.consultation';

        return $this->pdfService->generate($viewName, $data, $type . '-' . $id . '.pdf');
    }

    private function loadData(string $type, int|string $id): array
    {
        return match($type) {
            'appointment' => [
                'appointment' => Appointment::with(['patient', 'doctor.user', 'doctor.specialty'])->findOrFail($id),
            ],
            'consultation' => [
                'consultation' => Consultation::with(['appointment.patient', 'appointment.doctor.user', 'appointment.doctor.specialty'])->findOrFail($id),
            ],
            default => abort(404, "Type de PDF non supporté."),
        };
    }
}
