@extends('layouts.app')

@section('title', $doctor->user->name . ' - Cabinet Médical')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('doctors.index') }}" class="text-indigo-600 hover:text-indigo-700 mb-8 inline-block">
        ← Retour aux médecins
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 h-64 flex items-center justify-center">
            <img src="{{ asset('icons/doctor.svg') }}" alt="Doctor" class="w-24 h-24 text-indigo-600 mx-auto">
        </div>

        <!-- Content -->
        <div class="p-8">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $doctor->user->name }}</h1>

            <div class="flex flex-wrap gap-4 mb-8">
                <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 px-4 py-2 rounded-full font-medium">
                    {{ $doctor->specialty->name }}
                </span>
                @if($doctor->available)
                    <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-4 py-2 rounded-full font-medium">
                        ✓ Disponible
                    </span>
                @else
                    <span class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 px-4 py-2 rounded-full font-medium">
                        Indisponible
                    </span>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 py-8 border-y border-gray-200 dark:border-gray-700">
                @if($doctor->user->telephone)
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Téléphone</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $doctor->user->telephone }}</p>
                    </div>
                @endif
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Email</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $doctor->user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Patients</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $doctor->appointments()->count() }}+</p>
                </div>
            </div>

            <!-- Biography -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">À propos</h2>
                <p class="text-gray-600 dark:text-gray-400 text-lg leading-relaxed">
                    {{ $doctor->biography }}
                </p>
            </div>

            <!-- Availabilities -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Horaires de disponibilité</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($doctor->availabilities()->orderBy('day_of_week')->get() as $availability)
                        <div class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-lg">
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $availability->getDayName() }}</p>
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ $availability->start_time }} - {{ $availability->end_time }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-600 dark:text-gray-400">Aucun horaire de disponibilité défini.</p>
                    @endforelse
                </div>
            </div>

            <!-- CTA -->
            @if($doctor->available)
                <div class="bg-indigo-50 dark:bg-indigo-900/20 p-8 rounded-lg text-center">
                    @if(auth()->check() && auth()->user()->isPatient())
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Prêt à consulter {{ $doctor->user->name }} ?</p>
                        <a href="{{ route('appointments.create', ['doctor_id' => $doctor->id]) }}" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition font-semibold inline-block">
                            Prendre un rendez-vous
                        </a>
                    @elseif(!auth()->check())
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Connectez-vous pour prendre un rendez-vous</p>
                        <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition font-semibold inline-block">
                            Se connecter
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
