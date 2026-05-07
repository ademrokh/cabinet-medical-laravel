<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->routeIs('admin.doctors.*')) {
            $doctors = Doctor::with('user', 'specialty')
                ->orderByDesc('id')
                ->paginate(10);

            return view('admin.doctors.index', compact('doctors'));
        }

        $query = Doctor::with('user', 'specialty');

        if ($request->has('specialty') && $request->specialty != '') {
            $query->where('specialty_id', $request->specialty);
        }

        $doctors = $query->paginate(9);
        $specialties = Specialty::all();

        return view('doctors.index', compact('doctors', 'specialties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $specialties = Specialty::orderBy('name')->get();
        $users = User::where('role', 'doctor')
            ->whereDoesntHave('doctor')
            ->orderBy('name')
            ->get();

        return view('admin.doctors.create', compact('specialties', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('role', 'doctor'),
                'unique:doctors,user_id',
            ],
            'specialty_id' => ['required', 'integer', 'exists:specialties,id'],
            'biography' => ['nullable', 'string'],
            'available' => ['nullable', 'boolean'],
        ]);

        $validated['available'] = (bool) ($validated['available'] ?? false);

        Doctor::create($validated);

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Doctor profile created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        $doctor->load('user', 'specialty', 'availabilities');
        return view('doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $doctor = Doctor::with('user', 'specialty')->findOrFail($id);
        $specialties = Specialty::orderBy('name')->get();
        $users = User::where('role', 'doctor')
            ->where(function ($query) use ($doctor) {
                $query->whereDoesntHave('doctor')
                    ->orWhere('id', $doctor->user_id);
            })
            ->orderBy('name')
            ->get();

        return view('admin.doctors.edit', compact('doctor', 'specialties', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $doctor = Doctor::findOrFail($id);
        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('role', 'doctor'),
                Rule::unique('doctors', 'user_id')->ignore($doctor->id),
            ],
            'specialty_id' => ['required', 'integer', 'exists:specialties,id'],
            'biography' => ['nullable', 'string'],
            'available' => ['nullable', 'boolean'],
        ]);

        $validated['available'] = (bool) ($validated['available'] ?? false);

        $doctor->update($validated);

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Doctor profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Doctor profile deleted successfully.');
    }
}
