@extends('layouts.app')

@section('title', 'Rendez-vous Médecin - Cabinet Médical')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Mes Rendez-vous</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Gérez vos consultations</p>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-8 border-b border-gray-200 dark:border-gray-700">
        <div class="flex space-x-8">
            <a href="?status=" class="px-4 py-2 font-medium text-indigo-600 border-b-2 border-indigo-600">
                Tous
            </a>
            <a href="?status=pending" class="px-4 py-2 font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900">
                En attente
            </a>
            <a href="?status=confirmed" class="px-4 py-2 font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900">
                Confirmés
            </a>
            <a href="?status=completed" class="px-4 py-2 font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900">
                Terminés
            </a>
        </div>
    </div>

    <!-- Appointments Grid -->
    <div class="space-y-4">
        @forelse($appointments as $appointment)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Patient</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $appointment->patient->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Date et heure</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $appointment->appointment_date_time->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Motif</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $appointment->reason }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Statut</p>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($appointment->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($appointment->status == 'confirmed') bg-green-100 text-green-800
                            @elseif($appointment->status == 'completed') bg-blue-100 text-blue-800
                            @endif
                        ">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                    <div class="flex gap-2 justify-end">
                        @if($appointment->status == 'pending')
                            <form action="{{ route('appointments.confirm', $appointment) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700 transition text-sm">
                                    Confirmer
                                </button>
                            </form>
                        @elseif($appointment->status == 'confirmed')
                            <form action="{{ route('appointments.complete', $appointment) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 transition text-sm">
                                    Terminer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @if($appointment->notes)
                    <p class="mt-4 text-gray-600 dark:text-gray-400 text-sm border-t pt-4">
                        <strong>Notes:</strong> {{ $appointment->notes }}
                    </p>
                @endif
            </div>
        @empty
            <div class="text-center py-12">
                <img src="{{ asset('icons/calendar.svg') }}" alt="No Appointments" class="w-16 h-16 text-gray-400 mx-auto mb-4">
                <p class="text-gray-600 dark:text-gray-400 text-lg">Aucun rendez-vous</p>
            </div>
        @endforelse
    </div>

    @if($appointments->hasPages())
        <div class="mt-8">
            {{ $appointments->links() }}
        </div>
    @endif
</div>
@endsection
