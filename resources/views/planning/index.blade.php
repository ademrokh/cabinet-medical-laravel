@extends('layouts.app')

@section('title', 'Planning Intelligent - Cabinet Medical')

@section('content')
@php
    $formatValue = function ($value, string $fallback = '') {
        if (is_string($value) || is_numeric($value)) {
            return (string) $value;
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        return $fallback;
    };
@endphp
<div class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 via-sky-50 to-rose-50"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-8">
            <div>
                <p class="text-sm uppercase tracking-[0.25em] text-emerald-500 font-semibold">Planning IA</p>
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900">Planification intelligente</h1>
                <p class="mt-3 text-lg text-slate-600">Generez un planning optimise avec priorites et suggestions de creneaux.</p>
            </div>
            <form action="{{ route('planning.generate') }}" method="POST" class="bg-white/80 backdrop-blur rounded-2xl shadow-xl p-5 flex flex-col sm:flex-row gap-3">
                @csrf
                <div>
                    <label class="text-xs font-semibold text-slate-600">Medecin</label>
                    <select name="doctor_id" class="mt-1 w-56 rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400">
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @if($doctorId == $doctor->id) selected @endif>{{ $doctor->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-600">Date</label>
                    <input type="date" name="date" class="mt-1 w-44 rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400" value="{{ now()->toDateString() }}">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl hover:bg-emerald-700 transition">
                        Generer
                    </button>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl bg-emerald-100 text-emerald-800 px-4 py-3 font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6">
            @forelse($plannings as $planning)
                <div class="bg-white/90 backdrop-blur rounded-3xl shadow-xl p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <p class="text-sm text-slate-500">Medecin</p>
                            <h2 class="text-2xl font-bold text-slate-900">{{ $planning->doctor->user->name }}</h2>
                            <p class="text-sm text-slate-500">{{ $planning->date->format('d/m/Y') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">{{ ucfirst($planning->status) }}</span>
                            @if($planning->generated_at)
                                <span class="text-xs text-slate-500">Genere: {{ $planning->generated_at->format('d/m/Y H:i') }}</span>
                            @endif
                        </div>
                    </div>

                    @if($planning->generated_plan && is_array($planning->generated_plan))
                        <div class="mt-6 grid lg:grid-cols-3 gap-6">
                            <div class="lg:col-span-2">
                                <h3 class="text-lg font-semibold text-slate-800 mb-3">Planning IA</h3>
                                <div class="space-y-3">
                                    @foreach(($planning->generated_plan['planning'] ?? []) as $planSlot)
                                        <div class="rounded-2xl border border-slate-100 p-4 bg-gradient-to-r from-white via-slate-50 to-emerald-50">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="font-semibold text-slate-900">{{ $formatValue($planSlot['patient'] ?? null, 'Patient') }}</p>
                                                    <p class="text-sm text-slate-500">{{ $formatValue($planSlot['time'] ?? null, 'Heure a confirmer') }}</p>
                                                </div>
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">
                                                    {{ $formatValue($planSlot['priority'] ?? null, 'normal') }}
                                                </span>
                                            </div>
                                            @if(!empty($planSlot['notes']))
                                                <p class="mt-2 text-sm text-slate-600">{{ $formatValue($planSlot['notes']) }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800 mb-3">Suggestions</h3>
                                <div class="space-y-3">
                                    @foreach(($planning->generated_plan['suggestions'] ?? []) as $suggestion)
                                        <div class="rounded-2xl border border-slate-100 p-4 bg-white">
                                            <p class="font-semibold text-slate-900">{{ $formatValue($suggestion['patient'] ?? null, 'Patient') }}</p>
                                            <p class="text-sm text-slate-500">{{ $formatValue($suggestion['suggested_time'] ?? null, 'Horaire a definir') }}</p>
                                            <p class="text-xs text-emerald-600 font-semibold mt-2">{{ $formatValue($suggestion['priority'] ?? null, 'normal') }}</p>
                                            @if(!empty($suggestion['reason']))
                                                <p class="text-xs text-slate-500 mt-1">{{ $formatValue($suggestion['reason']) }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="mt-6 text-slate-500">Aucun resultat disponible pour ce planning.</p>
                    @endif
                </div>
            @empty
                <div class="bg-white/90 backdrop-blur rounded-3xl shadow-xl p-10 text-center">
                    <p class="text-slate-600">Aucun planning genere pour le moment.</p>
                </div>
            @endforelse
        </div>

        @if($plannings->hasPages())
            <div class="mt-8">
                {{ $plannings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
