@extends('layouts.app')

@section('title', $doctor->user->name . ' - Cabinet Médical')

@section('content')
<div class="bg-gradient-to-br from-emerald-50 via-white to-orange-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="mb-8" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" x-show="show" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform -translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
            <a href="{{ route('doctors.index') }}" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-semibold transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Retour aux médecins
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden" x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)" x-show="show" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
            <!-- Header -->
            <div class="relative">
                <div class="h-48 md:h-64 bg-gradient-to-r from-emerald-200 via-sky-200 to-orange-200"></div>
                <div class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2">
                    <img src="{{ $doctor->user->photo ? asset('storage/' . $doctor->user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($doctor->user->name) . '&color=0F172A&background=E2E8F0&size=160' }}" alt="Photo de {{ $doctor->user->name }}" class="w-32 h-32 md:w-40 md:h-40 rounded-full border-8 border-white object-cover shadow-2xl">
                </div>
            </div>

            <!-- Content -->
            <div class="pt-24 md:pt-28 pb-12 px-6 md:px-12 text-center">
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight">{{ $doctor->user->name }}</h1>
                <p class="mt-2 text-xl font-medium text-emerald-600">{{ $doctor->specialty->name }}</p>

                <div class="mt-6 flex flex-wrap gap-3 justify-center">
                    @if($doctor->available)
                        <span class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-800 px-4 py-2 rounded-full font-semibold text-sm">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            Disponible
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 bg-rose-100 text-rose-800 px-4 py-2 rounded-full font-semibold text-sm">
                             <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            Indisponible
                        </span>
                    @endif
                </div>

                <!-- Biography -->
                <div class="mt-10 max-w-2xl mx-auto">
                    <h2 class="text-2xl font-bold text-slate-900 mb-4">A propos</h2>
                    <p class="text-slate-600 text-lg leading-relaxed text-left md:text-center">
                        {{ $doctor->biography }}
                    </p>
                </div>
            </div>

            <!-- Details & Availabilities -->
            <div class="bg-white/80 px-6 md:px-12 py-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-6">Informations</h2>
                        <div class="space-y-4">
                            <div class="flex items-start gap-4">
                                <div class="bg-emerald-100 text-emerald-600 rounded-lg p-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500">Email</p>
                                    <a href="mailto:{{ $doctor->user->email }}" class="text-lg font-semibold text-emerald-600 hover:underline">{{ $doctor->user->email }}</a>
                                </div>
                            </div>
                            @if($doctor->user->telephone)
                            <div class="flex items-start gap-4">
                                <div class="bg-emerald-100 text-emerald-600 rounded-lg p-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500">Telephone</p>
                                    <p class="text-lg font-semibold text-slate-900">{{ $doctor->user->telephone }}</p>
                                </div>
                            </div>
                            @endif
                            <div class="flex items-start gap-4">
                                <div class="bg-emerald-100 text-emerald-600 rounded-lg p-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 016-6h6a6 6 0 016 6v1h-3"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500">Patients</p>
                                    <p class="text-lg font-semibold text-slate-900">{{ $doctor->appointments()->distinct('patient_id')->count('patient_id') }}+</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-6">Horaires de disponibilite</h2>
                        <div class="space-y-3">
                            @forelse($doctor->availabilities()->orderBy('day_of_week')->get() as $availability)
                                <div class="bg-white p-4 rounded-lg flex justify-between items-center shadow-sm">
                                    <p class="font-semibold text-slate-800">{{ $availability->getDayName() }}</p>
                                    <p class="text-slate-600 font-mono">
                                        {{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}
                                    </p>
                                </div>
                            @empty
                                <div class="bg-white p-6 rounded-lg text-center">
                                    <p class="text-slate-500">Aucun horaire de disponibilite defini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            @if($doctor->available)
                <div class="px-6 md:px-12 py-10">
                    <div class="bg-gradient-to-r from-emerald-600 via-sky-600 to-orange-500 rounded-2xl p-8 md:p-12 text-center shadow-xl">
                        @if(auth()->check() && auth()->user()->isPatient())
                            <h2 class="text-3xl font-extrabold text-white">Prêt à consulter {{ $doctor->user->name }} ?</h2>
                            <p class="text-white/80 mt-4 max-w-xl mx-auto">Prenez rendez-vous en quelques clics pour une consultation.</p>
                            <a href="{{ route('appointments.create', ['doctor_id' => $doctor->id]) }}" class="mt-8 inline-block bg-white text-slate-900 px-8 py-3 rounded-lg font-semibold text-lg hover:bg-emerald-50 transition-transform transform hover:scale-105 shadow-lg">
                                Prendre un rendez-vous
                            </a>
                        @elseif(!auth()->check())
                            <h2 class="text-3xl font-extrabold text-white">Connectez-vous pour prendre rendez-vous</h2>
                            <p class="text-white/80 mt-4 max-w-xl mx-auto">Rejoignez notre communaute pour acceder a nos services.</p>
                            <a href="{{ route('login') }}" class="mt-8 inline-block bg-white text-slate-900 px-8 py-3 rounded-lg font-semibold text-lg hover:bg-emerald-50 transition-transform transform hover:scale-105 shadow-lg">
                                Se connecter
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

