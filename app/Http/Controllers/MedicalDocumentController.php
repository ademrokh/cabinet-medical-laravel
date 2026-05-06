<?php

namespace App\Http\Controllers;

use App\Models\MedicalDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        if (!Storage::exists($document->file_path)) {
            return redirect()->back()->with('error', 'Document not found.');
        }

        return Storage::download($document->file_path, basename($document->file_path));
    }

    /**
     * Store a newly created document (for doctors).
     */
    public function store(Request $request)
    {
        $this->authorize('create', MedicalDocument::class);

        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'type' => 'required|in:prescription,result,diagnosis,other',
            'description' => 'nullable|string|max:500',
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $filePath = $request->file('file')->store('medical-documents');

        MedicalDocument::create([
            'patient_id' => $validated['patient_id'],
            'doctor_id' => auth()->user()->doctor->id,
            'type' => $validated['type'],
            'file_path' => $filePath,
            'description' => $validated['description'],
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully!');
    }
}
