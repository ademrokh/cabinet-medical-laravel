@extends('layouts.app')

@section('title', 'Tableau de Bord Admin - Cabinet Médical')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8">Tableau de Bord Admin</h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">Patients</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_patients'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">Médecins</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_doctors'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">Total RDV</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_appointments'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">RDV Aujourd'hui</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['today_appointments'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">En attente</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_appointments'] }}</p>
        </div>
    </div>

    <!-- RDV Aujourd'hui -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Rendez-vous d'aujourd'hui</h2>

        @if($today_appointments->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="py-3 px-4 font-semibold text-gray-900 dark:text-white">Heure</th>
                            <th class="py-3 px-4 font-semibold text-gray-900 dark:text-white">Patient</th>
                            <th class="py-3 px-4 font-semibold text-gray-900 dark:text-white">Médecin</th>
                            <th class="py-3 px-4 font-semibold text-gray-900 dark:text-white">Motif</th>
                            <th class="py-3 px-4 font-semibold text-gray-900 dark:text-white">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($today_appointments as $appointment)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="py-3 px-4 text-gray-900 dark:text-white font-medium">
                                    {{ $appointment->appointment_date_time->format('H:i') }}
                                </td>
                                <td class="py-3 px-4 text-gray-900 dark:text-white">
                                    {{ $appointment->patient->name }}
                                </td>
                                <td class="py-3 px-4 text-gray-900 dark:text-white">
                                    {{ $appointment->doctor->user->name }}
                                </td>
                                <td class="py-3 px-4 text-gray-600 dark:text-gray-400">
                                    {{ $appointment->reason }}
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium
                                        @if($appointment->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($appointment->status == 'confirmed') bg-green-100 text-green-800
                                        @elseif($appointment->status == 'completed') bg-blue-100 text-blue-800
                                        @endif
                                    ">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-600 dark:text-gray-400 text-center py-8">Aucun rendez-vous aujourd'hui</p>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
        <a href="{{ route('doctors.index') }}" class="bg-indigo-600 text-white rounded-lg p-6 hover:bg-indigo-700 transition">
            <h3 class="text-xl font-bold mb-2">Médecins</h3>
            <p class="opacity-90">Gérer les médecins</p>
        </a>
        <a href="{{ route('admin.appointments.index') }}" class="bg-green-600 text-white rounded-lg p-6 hover:bg-green-700 transition">
            <h3 class="text-xl font-bold mb-2">Rendez-vous</h3>
            <p class="opacity-90">Gérer tous les rendez-vous</p>
        </a>
        <a href="{{ route('planning.index') }}" class="bg-sky-600 text-white rounded-lg p-6 hover:bg-sky-700 transition">
            <h3 class="text-xl font-bold mb-2">Planning IA</h3>
            <p class="opacity-90">Generer des plannings optimises</p>
        </a>
        <a href="#" class="bg-purple-600 text-white rounded-lg p-6 hover:bg-purple-700 transition">
            <h3 class="text-xl font-bold mb-2">Patients</h3>
            <p class="opacity-90">Gérer les patients</p>
        </a>
    </div>
</div>
@endsection
