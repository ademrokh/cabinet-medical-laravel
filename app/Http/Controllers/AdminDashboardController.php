<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function index()
    {
        $stats = [
            'total_patients' => User::where('role', 'patient')->count(),
            'total_doctors' => Doctor::count(),
            'total_appointments' => Appointment::count(),
            'today_appointments' => Appointment::today()->count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
        ];

        $today_appointments = Appointment::today()
            ->with('patient', 'doctor.user', 'doctor.specialty')
            ->orderBy('appointment_date_time')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'today_appointments'));
    }

    /**
     * Doctor Dashboard
     */
    public function doctorDashboard()
    {
        $doctor = auth()->user()->doctor;

        if (!$doctor) {
            abort(403);
        }

        $upcomingCount = $doctor->appointments()
            ->upcoming()
            ->count();

        $completedCount = $doctor->appointments()
            ->where('status', 'completed')
            ->count();

        $upcomingAppointments = $doctor->appointments()
            ->upcoming()
            ->with('patient')
            ->take(5)
            ->get();

        return view('doctor.dashboard', compact('upcomingCount', 'completedCount', 'upcomingAppointments'));
    }

}
