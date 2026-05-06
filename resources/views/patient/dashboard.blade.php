@extends('layouts.app')

@section('title', 'Tableau de Bord Patient - Cabinet Médical')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Bienvenue, {{ auth()->user()->name }} 👋</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Voici un aperçu de votre espace patient</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Rendez-vous à venir</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $upcomingCount }}</p>
                </div>
                <img src="{{ asset('icons/calendar.svg') }}" alt="Appointments" class="w-12 h-12 text-indigo-600 mx-auto">
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Rendez-vous complétés</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $completedCount }}</p>
                </div>
                <div class="text-4xl">✓</div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Documents médicaux</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $documentsCount }}</p>
                </div>
                <div class="text-4xl">📄</div>
            </div>
        </div>
    </div>

    <!-- Prochains rendez-vous -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Prochains rendez-vous</h2>
            <a href="{{ route('appointments.patient') }}" class="text-indigo-600 hover:text-indigo-700">Voir tous →</a>
        </div>

        @if($upcomingAppointments->count() > 0)
            <div class="space-y-4">
                @foreach($upcomingAppointments->take(3) as $appointment)
                    <div class="flex items-center justify-between p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $appointment->doctor->user->name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $appointment->appointment_date_time->format('d/m/Y à H:i') }}
                            </p>
                            <p class="text-sm text-indigo-600">{{ $appointment->reason }}</p>
                        </div>
                        <span class="px-3 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 rounded-full text-sm font-medium">
                            Confirmé
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-600 dark:text-gray-400 mb-4">Vous n'avez aucun rendez-vous prévu</p>
                <a href="{{ route('appointments.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition inline-block">
                    Prendre un rendez-vous
                </a>
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-lg p-8 cursor-pointer hover:shadow-lg transition">
            <h3 class="text-2xl font-bold mb-2">Prendre un rendez-vous</h3>
            <p class="mb-4 opacity-90">Consultez l'un de nos médecins</p>
            <a href="{{ route('appointments.create') }}" class="text-indigo-100 hover:text-white font-semibold">
                Commencer →
            </a>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg p-8 cursor-pointer hover:shadow-lg transition">
            <h3 class="text-2xl font-bold mb-2">Mes documents</h3>
            <p class="mb-4 opacity-90">Accédez à vos ordonnances et résultats</p>
            <a href="#documents" class="text-green-100 hover:text-white font-semibold">
                Consulter →
            </a>
        </div>
    </div>
</div>
@endsection
