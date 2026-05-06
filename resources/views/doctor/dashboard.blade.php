@extends('layouts.app')

@section('title', 'Tableau de Bord Médecin - Cabinet Médical')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <header class="mb-12" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" x-show="show" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                Bienvenue, <span class="text-emerald-600">Dr. {{ auth()->user()->name }}</span>
            </h1>
            <p class="mt-3 text-lg text-gray-600 dark:text-gray-400">Votre tableau de bord pour une gestion efficace.</p>
        </header>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 transform hover:-translate-y-2 transition-transform duration-300" x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)" x-show="show" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">RDV à venir</p>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mt-1">{{ $upcomingCount }}</p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-300 rounded-xl w-16 h-16 flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 transform hover:-translate-y-2 transition-transform duration-300" x-data="{ show: false }" x-init="setTimeout(() => show = true, 400)" x-show="show" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">RDV Complétés</p>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mt-1">{{ $completedCount }}</p>
                    </div>
                    <div class="bg-emerald-100 text-emerald-600 rounded-xl w-16 h-16 flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 transform hover:-translate-y-2 transition-transform duration-300" x-data="{ show: false }" x-init="setTimeout(() => show = true, 500)" x-show="show" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Taux de satisfaction</p>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white mt-1">98%</p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 rounded-xl w-16 h-16 flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prochains RDV -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 md:p-8" x-data="{ show: false }" x-init="setTimeout(() => show = true, 600)" x-show="show" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Prochains rendez-vous</h2>
                <a href="{{ route('doctor.appointments') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold transition">Voir tous →</a>
            </div>

            @if($upcomingAppointments->count() > 0)
                <div class="space-y-4">
                    @foreach($upcomingAppointments as $appointment)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4">
                                <img src="{{ $appointment->patient->photo ? asset('storage/' . $appointment->patient->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($appointment->patient->name) . '&color=7F9CF5&background=EBF4FF&size=64' }}" alt="Photo de {{ $appointment->patient->name }}" class="w-12 h-12 rounded-full object-cover">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $appointment->patient->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $appointment->appointment_date_time->isoFormat('LLLL') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($appointment->status == 'pending')
                                    <form action="{{ route('appointments.confirm', $appointment) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300 px-4 py-2 rounded-lg hover:bg-green-200 transition text-sm font-medium">
                                            Confirmer
                                        </button>
                                    </form>
                                @else
                                    <span class="px-4 py-2 rounded-lg bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 text-sm font-medium">Confirmé</span>
                                @endif
                                <a href="{{ route('appointments.show', $appointment) }}" class="text-emerald-600 text-2xl">
                                    →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-20 h-20 mx-auto text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <p class="text-gray-500 dark:text-gray-400 mt-4 text-lg">Vous n'avez aucun rendez-vous à venir.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

