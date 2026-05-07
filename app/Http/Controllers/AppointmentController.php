<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->routeIs('admin.appointments.*')) {
            $query = Appointment::with('patient', 'doctor.user', 'doctor.specialty')
                ->orderByDesc('appointment_date_time');

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $appointments = $query->paginate(15)->withQueryString();
            $status = $request->get('status', '');

            return view('admin.appointments.index', compact('appointments', 'status'));
        }

        abort(404);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $doctors = Doctor::with('user', 'specialty')->get();
        $selectedDoctorId = $request->get('doctor_id');
        return view('appointments.create', compact('doctors', 'selectedDoctorId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date_time' => 'required|date|after:now',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::create([
            'patient_id' => auth()->id(),
            'doctor_id' => $validated['doctor_id'],
            'appointment_date_time' => $validated['appointment_date_time'],
            'reason' => $validated['reason'],
            'notes' => $validated['notes'],
            'status' => 'scheduled',
        ]);

        return redirect()->route('appointments.patient')->with('success', 'Rendez-vous créé avec succès!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource (admin).
     */
    public function edit(Request $request, Appointment $appointment)
    {
        if (!$request->routeIs('admin.appointments.*')) {
            abort(404);
        }

        $this->authorize('update', $appointment);

        $patients = \App\Models\User::where('role', 'patient')
            ->orderBy('name')
            ->get();

        $doctors = Doctor::with('user', 'specialty')->orderBy('id')->get();

        return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    /**
     * Get patient appointments.
     */
    public function patientIndex(Request $request)
    {
        $query = Appointment::where('patient_id', auth()->id())
            ->with('doctor.user', 'doctor.specialty');

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            if ($request->status === 'scheduled') {
                // Scheduled = upcoming appointments (after today)
                $query->where('status', 'scheduled')
                    ->where('appointment_date_time', '>', now());
            } else {
                $query->where('status', $request->status);
            }
        }

        $appointments = $query->orderBy('appointment_date_time', 'desc')
            ->paginate(10);

        $status = $request->get('status', '');

        return view('appointments.patient-index', compact('appointments', 'status'));
    }

    /**
     * Get doctor appointments.
     */
    public function doctorIndex(Request $request)
    {
        $doctor = auth()->user()->doctor;
        $query = $doctor->appointments()
            ->with('patient');

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            if ($request->status === 'scheduled') {
                // Scheduled = upcoming appointments (after today)
                $query->where('status', 'scheduled')
                    ->where('appointment_date_time', '>', now());
            } else {
                $query->where('status', $request->status);
            }
        }

        $appointments = $query->orderBy('appointment_date_time', 'desc')
            ->paginate(10);

        $status = $request->get('status', '');

        return view('appointments.doctor-index', compact('appointments', 'status'));
    }

    /**
     * Update the specified resource in storage (admin).
     */
    public function update(Request $request, Appointment $appointment)
    {
        if (!$request->routeIs('admin.appointments.*')) {
            abort(404);
        }

        $this->authorize('update', $appointment);

        $validated = $request->validate([
            'patient_id' => ['required', 'exists:users,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'appointment_date_time' => ['required', 'date'],
            'reason' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:scheduled,confirmed,completed,cancelled,pending'],
            'notes' => ['nullable', 'string'],
        ]);

        $appointment->update($validated);

        return redirect()
            ->route('admin.appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Appointment $appointment)
    {
        if ($request->routeIs('admin.appointments.*')) {
            $this->authorize('delete', $appointment);
            $appointment->delete();

            return redirect()
                ->route('admin.appointments.index')
                ->with('success', 'Appointment deleted successfully.');
        }

        $this->authorize('delete', $appointment);

        if ($appointment->appointment_date_time < now()->addHours(24)) {
            return back()->with('error', 'Impossible d\'annuler un rendez-vous moins de 24h à l\'avance');
        }

        $appointment->update(['status' => 'cancelled']);

        return back()->with('success', 'Rendez-vous annulé avec succès!');
    }

    /**
     * Confirm appointment (doctor only).
     */
    public function confirm(Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        $appointment->update(['status' => 'confirmed']);
        return back()->with('success', 'Rendez-vous confirmé!');
    }

    /**
     * Complete appointment (doctor only).
     */
    public function complete(Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        $appointment->update(['status' => 'completed']);
        return back()->with('success', 'Rendez-vous terminé!');
    }
}
