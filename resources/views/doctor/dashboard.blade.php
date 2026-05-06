@extends('layouts.app')

@section('title', 'Tableau de Bord Médecin - Cabinet Médical')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-3">
        <img src="{{ asset('icons/doctor.svg') }}" alt="Doctor" class="w-10 h-10 text-indigo-600">
        Bienvenue, Dr. {{ auth()->user()->name }}
    </h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">RDV à venir</p>
            <p class="text-3xl font-bold text-green-600">{{ $upcomingCount }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">RDV Complétés</p>
            <p class="text-3xl font-bold text-indigo-600">{{ $completedCount }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">Taux de satisfaction</p>
            <p class="text-3xl font-bold text-blue-600">98%</p>
        </div>
    </div>

    <!-- Prochains RDV -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Prochains rendez-vous</h2>
            <a href="{{ route('doctor.appointments') }}" class="text-indigo-600 hover:text-indigo-700">Voir tous →</a>
        </div>

        @if($upcomingAppointments->count() > 0)
            <div class="space-y-4">
                @foreach($upcomingAppointments as $appointment)
                    <div class="flex items-center justify-between p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg border-l-4 border-indigo-600">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $appointment->patient->name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $appointment->appointment_date_time->format('d/m/Y à H:i') }}
                            </p>
                            <p class="text-sm text-indigo-600">{{ $appointment->reason }}</p>
                        </div>
                        <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-medium">
                            Confirmer
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-600 dark:text-gray-400 text-lg">Aucun rendez-vous à venir</p>
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <a href="{{ route('doctor.appointments') }}" class="bg-gradient-to-br from-indigo-600 to-indigo-700 text-white rounded-lg p-8 hover:shadow-lg transition">
            <h3 class="text-2xl font-bold mb-2">Mes Rendez-vous</h3>
            <p class="opacity-90 mb-4">Consultez et gérez vos rendez-vous</p>
            <span>Accéder →</span>
        </a>
        <a href="{{ route('profile.edit') }}" class="bg-gradient-to-br from-green-600 to-green-700 text-white rounded-lg p-8 hover:shadow-lg transition">
            <h3 class="text-2xl font-bold mb-2">Mon Profil</h3>
            <p class="opacity-90 mb-4">Mettre à jour vos informations</p>
            <span>Modifier →</span>
        </a>
    </div>
</div>
@endsection
