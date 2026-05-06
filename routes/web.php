<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    $doctors = \App\Models\Doctor::with('user', 'specialty')->take(3)->get();
    return view('home', compact('doctors'));
})->name('home');

Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');

// Patient routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->isPatient()) {
            return redirect()->route('patient.dashboard');
        } elseif ($user->isDoctor()) {
            return redirect()->route('doctor.dashboard');
        } elseif ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
    })->name('dashboard');

    // Patient routes
    Route::middleware('patient')->group(function () {
        Route::get('/patient/dashboard', function () {
            return view('patient.dashboard');
        })->name('patient.dashboard');

        Route::get('/appointments', [AppointmentController::class, 'patientIndex'])->name('appointments.patient');
        Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
        Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    });

    // Doctor routes
    Route::middleware('doctor')->group(function () {
        Route::get('/doctor/dashboard', [AdminDashboardController::class, 'doctorDashboard'])->name('doctor.dashboard');
        Route::get('/doctor/appointments', [AppointmentController::class, 'doctorIndex'])->name('doctor.appointments');
        Route::patch('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
        Route::patch('/appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
    });

    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('admin/doctors', DoctorController::class, ['as' => 'admin'])->except('show');
        Route::resource('admin/appointments', AppointmentController::class, ['as' => 'admin'])->except('show');
        Route::resource('admin/specialties', 'SpecialtyController', ['as' => 'admin']);
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
