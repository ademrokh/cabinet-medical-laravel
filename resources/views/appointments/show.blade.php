@extends('layouts.app')

@section('title', 'Détail du Rendez-vous - Cabinet Médical')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="javascript:history.back()" class="text-indigo-600 hover:text-indigo-700 mb-8 inline-block">
        ← Retour
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Détails du Rendez-vous</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Patient Info -->
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Informations du Patient</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Nom</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $appointment->patient->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Email</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $appointment->patient->email }}</p>
                    </div>
                    @if($appointment->patient->telephone)
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Téléphone</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $appointment->patient->telephone }}</p>
                        </div>
                    @endif
                    @if($appointment->patient->date_naissance)
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Date de naissance</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($appointment->patient->date_naissance)->format('d/m/Y') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Doctor Info -->
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Informations du Médecin</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Médecin</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $appointment->doctor->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Spécialité</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $appointment->doctor->specialty->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Email</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $appointment->doctor->user->email }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointment Details -->
        <div class="border-t border-gray-200 dark:border-gray-700 mt-8 pt-8">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Détails de la Consultation</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Date et heure</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $appointment->appointment_date_time->format('d/m/Y à H:i') }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Motif</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $appointment->reason }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Statut</p>
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($appointment->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($appointment->status == 'confirmed') bg-green-100 text-green-800
                        @elseif($appointment->status == 'cancelled') bg-red-100 text-red-800
                        @elseif($appointment->status == 'completed') bg-blue-100 text-blue-800
                        @endif
                    ">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </div>
            </div>

            @if($appointment->notes)
                <div class="mt-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Notes</p>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-gray-900 dark:text-white">{{ $appointment->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="border-t border-gray-200 dark:border-gray-700 mt-8 pt-8 flex gap-4">
            @if(auth()->user()->isPatient() && $appointment->status == 'pending' && $appointment->appointment_date_time > now()->addHours(24))
                <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Êtes-vous sûr ?')" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
                        Annuler
                    </button>
                </form>
            @endif
            <a href="javascript:history.back()" class="bg-gray-300 dark:bg-gray-700 text-gray-900 dark:text-white px-6 py-2 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                Fermer
            </a>
        </div>
    </div>
</div>
@endsection
