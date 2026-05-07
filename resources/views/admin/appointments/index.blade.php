@extends('layouts.app')

@section('title', 'Admin - Appointments')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Appointments</h1>
            <p class="text-slate-600 mt-1">Manage all appointments.</p>
        </div>
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <select name="status" class="rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400">
                <option value="">All statuses</option>
                @foreach(['scheduled', 'confirmed', 'completed', 'cancelled', 'pending'] as $option)
                    <option value="{{ $option }}" @if($status === $option) selected @endif>{{ ucfirst($option) }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition">
                Filter
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Patient</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Doctor</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Date</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($appointments as $appointment)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-6 py-4 text-slate-900 font-semibold">
                                {{ $appointment->patient->name ?? 'Patient' }}
                                <p class="text-xs text-slate-500 font-normal">{{ $appointment->patient->email ?? '' }}</p>
                            </td>
                            <td class="px-6 py-4 text-slate-700">
                                Dr. {{ $appointment->doctor->user->name ?? 'Doctor' }}
                                <p class="text-xs text-slate-500">{{ $appointment->doctor->specialty->name ?? 'General' }}</p>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $appointment->appointment_date_time->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.appointments.edit', $appointment) }}" class="text-emerald-600 font-semibold hover:text-emerald-700 transition">Edit</a>
                                    <form method="POST" action="{{ route('admin.appointments.destroy', $appointment) }}" onsubmit="return confirm('Delete this appointment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 font-semibold hover:text-rose-700 transition">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500">No appointments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($appointments->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
