<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalDocumentController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminPatientController;
use App\Http\Controllers\PdfExportController;
use App\Http\Controllers\PlanningController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    $doctors = \App\Models\Doctor::with('user', 'specialty')->take(3)->get();
    return view('home', compact('doctors'));
})->name('home');

Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');

// Authenticated routes
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
            $patient = auth()->user();
            $upcomingAppointments = $patient->appointments()
                ->upcoming()
                ->with('doctor.user', 'doctor.specialty')
                ->take(5)
                ->get();
            $upcomingCount = $patient->appointments()
                ->upcoming()
                ->count();
            $completedCount = $patient->appointments()
                ->where('status', 'completed')
                ->count();
            $documentsCount = $patient->medicalDocuments()
                ->count();
            return view('patient.dashboard', compact('upcomingCount', 'completedCount', 'documentsCount', 'upcomingAppointments'));
        })->name('patient.dashboard');

        Route::get('/appointments', [AppointmentController::class, 'patientIndex'])->name('appointments.patient');
        Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
        Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

        Route::get('/documents', [MedicalDocumentController::class, 'patientIndex'])->name('documents.patient');
    });

    Route::get('/documents/{document}', [MedicalDocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/download', [MedicalDocumentController::class, 'download'])->name('documents.download');

    // Doctor routes
    Route::middleware('doctor')->group(function () {
        Route::get('/doctor/dashboard', [AdminDashboardController::class, 'doctorDashboard'])->name('doctor.dashboard');
        Route::get('/doctor/appointments', [AppointmentController::class, 'doctorIndex'])->name('doctor.appointments');
        Route::patch('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
        Route::patch('/appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
        Route::get('/doctor/documents', [MedicalDocumentController::class, 'doctorIndex'])->name('documents.doctor');
        Route::post('/doctor/documents', [MedicalDocumentController::class, 'store'])->name('documents.store');
    });

    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('admin/patients', AdminPatientController::class, ['as' => 'admin'])->only(['index', 'edit', 'update', 'destroy']);
        Route::resource('admin/doctors', DoctorController::class, ['as' => 'admin'])->except('show');
        Route::resource('admin/appointments', AppointmentController::class, ['as' => 'admin'])->except('show');
        Route::resource('admin/specialties', 'SpecialtyController', ['as' => 'admin']);
    });

    // Planning
    Route::get('/planning', [PlanningController::class, 'index'])->name('planning.index');
    Route::post('/planning/generate', [PlanningController::class, 'generate'])->name('planning.generate');

    // PDF Export
    Route::get('/export-pdf/{type}/{id}', [PdfExportController::class, 'showExportPage'])->name('export.page');
    Route::get('/export-pdf/{type}/{id}/summary', [PdfExportController::class, 'getSummary'])->name('export.summary');
    Route::post('/export-pdf/{type}/{id}/download', [PdfExportController::class, 'generatePdf'])->name('export.pdf');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
