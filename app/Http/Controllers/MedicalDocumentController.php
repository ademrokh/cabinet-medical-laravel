<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MedicalDocumentController extends Controller
{
    /**
     * Display patient's medical documents.
     */
    public function patientIndex()
    {
        $documents = MedicalDocument::where('patient_id', auth()->id())
            ->with('doctor.user', 'doctor.specialty')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('documents.patient-index', compact('documents'));
    }

    /**
     * Display doctor's medical documents and upload form.
     */
    public function doctorIndex()
    {
        $doctor = auth()->user()->doctor;

        if (!$doctor) {
            abort(403);
        }

        $documents = MedicalDocument::where('doctor_id', $doctor->id)
            ->with(['patient', 'appointment'])
            ->orderByDesc('created_at')
            ->paginate(15);

        $appointments = Appointment::with('patient')
            ->where('doctor_id', $doctor->id)
            ->upcoming()
            ->orderBy('appointment_date_time')
            ->get();

        $patients = $appointments
            ->map(fn ($appointment) => $appointment->patient)
            ->filter()
            ->unique('id')
            ->sortBy('name')
            ->values();

        return view('documents.doctor-index', compact('documents', 'appointments', 'patients'));
    }

    /**
     * Display the specified document.
     */
    public function show(MedicalDocument $document)
    {
        $this->authorize('view', $document);

        return view('documents.show', compact('document'));
    }

    /**
     * Download a document.
     */
    public function download(MedicalDocument $document)
    {
        $this->authorize('view', $document);

        if (!Storage::disk('public')->exists($document->file_path)) {
            return redirect()->back()->with('error', 'Document not found.');
        }

        return Storage::disk('public')->download($document->file_path, basename($document->file_path));
    }

    /**
     * Store a newly created document (for doctors).
     */
    public function store(Request $request)
    {
        $this->authorize('create', MedicalDocument::class);

        $validated = $request->validate([
            'patient_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('role', 'patient'),
            ],
            'appointment_id' => 'nullable|exists:appointments,id',
            'type' => 'required|in:prescription,result,diagnosis,other',
            'description' => 'nullable|string|max:500',
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $doctor = auth()->user()->doctor;

        if (!empty($validated['appointment_id'])) {
            $appointment = Appointment::with('patient')
                ->where('doctor_id', $doctor->id)
                ->findOrFail($validated['appointment_id']);

            if ((int) $validated['patient_id'] !== (int) $appointment->patient_id) {
                return back()->withErrors([
                    'patient_id' => 'Selected patient does not match the appointment.',
                ])->withInput();
            }
        }

        $filePath = $request->file('file')->store('medical-documents', 'public');

        MedicalDocument::create([
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $doctor->id,
            'appointment_id' => $validated['appointment_id'] ?? null,
            'type' => $validated['type'],
            'file_path' => $filePath,
            'description' => $validated['description'],
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully!');
    }
}
