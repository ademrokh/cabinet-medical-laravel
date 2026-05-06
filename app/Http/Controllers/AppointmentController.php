<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
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
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
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
