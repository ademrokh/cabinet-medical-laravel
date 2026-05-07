<?php

namespace App\Http\Controllers;

use App\Jobs\GeneratePlanningJob;
use App\Models\PlanningSuggestion;
use App\Models\SmartPlanning;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;

class PlanningController extends Controller
{
    public function index(Request $request)
    {
        $this->ensurePlannerAccess();
        $doctorId = $request->get('doctor_id');
        $query = SmartPlanning::with('doctor.user')->orderByDesc('created_at');

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        $plannings = $query->paginate(10);
        $doctors = Doctor::with('user')->orderBy('id')->get();

        return view('planning.index', compact('plannings', 'doctors', 'doctorId'));
    }

    public function generate(Request $request)
    {
        $this->ensurePlannerAccess();
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date',
        ]);

        $doctor = Doctor::with(['user', 'availabilities'])->findOrFail($validated['doctor_id']);
        $patients = User::where('role', 'patient')->select('id', 'name', 'email')->get();

        $patientPayload = $patients->map(function ($patient) {
            return [
                'id' => $patient->id,
                'name' => $patient->name,
                'email' => $patient->email,
                'priority' => 'normal',
            ];
        })->toArray();

        $availability = $doctor->availabilities->map(function ($slot) {
            return [
                'day_of_week' => $slot->day_of_week,
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
            ];
        })->toArray();

        if (app()->environment('local')) {
            GeneratePlanningJob::dispatchSync($doctor->id, $patientPayload, $availability, $validated['date']);
        } else {
            GeneratePlanningJob::dispatch($doctor->id, $patientPayload, $availability, $validated['date']);
        }

        return back()->with('success', 'Generation du planning lancee.');
    }

    public function showApi(SmartPlanning $planning)
    {
        return response()->json($planning);
    }

    public function generateApi(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date',
        ]);

        $doctor = Doctor::with(['availabilities'])->findOrFail($validated['doctor_id']);
        $patients = User::where('role', 'patient')->select('id', 'name', 'email')->get();

        $patientPayload = $patients->map(function ($patient) {
            return [
                'id' => $patient->id,
                'name' => $patient->name,
                'email' => $patient->email,
                'priority' => 'normal',
            ];
        })->toArray();

        $availability = $doctor->availabilities->map(function ($slot) {
            return [
                'day_of_week' => $slot->day_of_week,
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
            ];
        })->toArray();

        if (app()->environment('local')) {
            GeneratePlanningJob::dispatchSync($doctor->id, $patientPayload, $availability, $validated['date']);
        } else {
            GeneratePlanningJob::dispatch($doctor->id, $patientPayload, $availability, $validated['date']);
        }

        return response()->json([
            'status' => 'queued',
        ], 202);
    }

    public function validateSuggestion(PlanningSuggestion $suggestion)
    {
        $suggestion->update(['validated' => true]);

        return response()->json([
            'status' => 'validated',
        ]);
    }

    private function ensurePlannerAccess(): void
    {
        $user = auth()->user();

        if (!$user || (!$user->isDoctor() && !$user->isAdmin())) {
            abort(403);
        }
    }
}
