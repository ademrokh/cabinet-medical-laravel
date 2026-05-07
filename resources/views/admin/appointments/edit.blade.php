@extends('layouts.app')

@section('title', 'Admin - Edit Appointment')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <a href="{{ route('admin.appointments.index') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition">Back to appointments</a>
        <h1 class="text-3xl font-bold text-slate-900 mt-2">Edit Appointment</h1>
        <p class="text-slate-600 mt-1">Update appointment details and status.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form method="POST" action="{{ route('admin.appointments.update', $appointment) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label for="patient_id" class="block text-sm font-semibold text-slate-700">Patient</label>
                    <select id="patient_id" name="patient_id" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400">
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @if(old('patient_id', $appointment->patient_id) == $patient->id) selected @endif>
                                {{ $patient->name }} ({{ $patient->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="doctor_id" class="block text-sm font-semibold text-slate-700">Doctor</label>
                    <select id="doctor_id" name="doctor_id" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400">
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @if(old('doctor_id', $appointment->doctor_id) == $doctor->id) selected @endif>
                                Dr. {{ $doctor->user->name }} - {{ $doctor->specialty->name ?? 'General' }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="appointment_date_time" class="block text-sm font-semibold text-slate-700">Date & Time</label>
                    <input id="appointment_date_time" name="appointment_date_time" type="datetime-local" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400" value="{{ old('appointment_date_time', $appointment->appointment_date_time?->format('Y-m-d\TH:i')) }}">
                    @error('appointment_date_time')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-700">Status</label>
                    <select id="status" name="status" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400">
                        @foreach(['scheduled', 'confirmed', 'completed', 'cancelled', 'pending'] as $option)
                            <option value="{{ $option }}" @if(old('status', $appointment->status) === $option) selected @endif>{{ ucfirst($option) }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="reason" class="block text-sm font-semibold text-slate-700">Reason</label>
                <input id="reason" name="reason" type="text" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400" value="{{ old('reason', $appointment->reason) }}">
                @error('reason')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="notes" class="block text-sm font-semibold text-slate-700">Notes</label>
                <textarea id="notes" name="notes" rows="4" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400">{{ old('notes', $appointment->notes) }}</textarea>
                @error('notes')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.appointments.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 font-semibold hover:border-slate-300 hover:text-slate-900 transition">Cancel</a>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-emerald-600 text-white font-semibold shadow-sm hover:bg-emerald-700 transition">
                    Save changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
