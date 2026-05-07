<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminPatientController extends Controller
{
    /**
     * Display a listing of the patients.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'patient')->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $patients = $query->paginate(15)->withQueryString();
        $search = $request->get('search', '');

        return view('admin.patients.index', compact('patients', 'search'));
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit(User $patient)
    {
        $this->ensurePatient($patient);

        return view('admin.patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(Request $request, User $patient)
    {
        $this->ensurePatient($patient);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($patient->id)],
            'telephone' => ['nullable', 'string', 'max:30'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'date_naissance' => ['nullable', 'date'],
        ]);

        $patient->update($validated);

        return redirect()
            ->route('admin.patients.index')
            ->with('success', 'Patient updated successfully.');
    }

    /**
     * Remove the specified patient from storage.
     */
    public function destroy(User $patient)
    {
        $this->ensurePatient($patient);

        $patient->delete();

        return redirect()
            ->route('admin.patients.index')
            ->with('success', 'Patient deleted successfully.');
    }

    private function ensurePatient(User $patient): void
    {
        if ($patient->role !== 'patient') {
            abort(404);
        }
    }
}
