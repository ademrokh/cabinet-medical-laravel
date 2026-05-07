@extends('layouts.app')

@section('title', 'Admin - Patients')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Patients</h1>
            <p class="text-slate-600 mt-1">Manage patient accounts.</p>
        </div>
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <input name="search" type="text" value="{{ $search }}" placeholder="Search by name or email" class="rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400">
            <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition">
                Search
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Name</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Email</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Telephone</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Address</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($patients as $patient)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-6 py-4 text-slate-900 font-semibold">{{ $patient->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $patient->email }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $patient->telephone ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $patient->adresse ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.patients.edit', $patient) }}" class="text-emerald-600 font-semibold hover:text-emerald-700 transition">Edit</a>
                                    <form method="POST" action="{{ route('admin.patients.destroy', $patient) }}" onsubmit="return confirm('Delete this patient?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 font-semibold hover:text-rose-700 transition">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500">No patients found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($patients->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $patients->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
