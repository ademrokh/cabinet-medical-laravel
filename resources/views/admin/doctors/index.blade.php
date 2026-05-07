@extends('layouts.app')

@section('title', 'Admin - Doctors')

@section('content')
<style>
    @keyframes fade-up {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-up {
        animation: fade-up 0.45s ease-out both;
    }

    .fade-up-delay-1 {
        animation-delay: 0.06s;
    }

    .fade-up-delay-2 {
        animation-delay: 0.12s;
    }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8 fade-up">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Doctors</h1>
            <p class="text-slate-600 mt-1">Manage doctor profiles for the clinic.</p>
        </div>
        <a href="{{ route('admin.doctors.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-emerald-600 text-white font-semibold shadow-sm hover:bg-emerald-700 transition">
            Add Doctor
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-emerald-100 bg-emerald-50 text-emerald-800 px-4 py-3 fade-up fade-up-delay-1">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden fade-up fade-up-delay-2">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Doctor</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Specialty</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Availability</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($doctors as $doctor)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $doctor->user->photo ? asset('storage/' . $doctor->user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($doctor->user->name) . '&color=0F172A&background=E2E8F0&size=64' }}" alt="{{ $doctor->user->name }}" class="w-10 h-10 rounded-full object-cover shadow">
                                    <div>
                                        <p class="text-slate-900 font-semibold">{{ $doctor->user->name }}</p>
                                        <p class="text-slate-500 text-sm">{{ $doctor->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-700">
                                {{ $doctor->specialty->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($doctor->available)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Available</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">Unavailable</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.doctors.edit', $doctor) }}" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 text-sm font-semibold hover:border-slate-300 hover:text-slate-900 transition">Edit</a>
                                    <form method="POST" action="{{ route('admin.doctors.destroy', $doctor) }}" onsubmit="return confirm('Delete this doctor profile?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg border border-rose-200 text-rose-600 text-sm font-semibold hover:border-rose-300 hover:text-rose-700 transition">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-500">No doctor profiles yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($doctors->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $doctors->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
