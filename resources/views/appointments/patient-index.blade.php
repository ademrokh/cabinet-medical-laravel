@extends('layouts.app')

@section('title', 'Mes Rendez-vous - Cabinet Médical')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Mes Rendez-vous</h1>
        @if(auth()->user()->isPatient())
            <a href="{{ route('appointments.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                + Nouveau RDV
            </a>
        @endif
    </div>

    <!-- Tabs -->
    <div class="mb-8 border-b border-gray-200 dark:border-gray-700">
        <div class="flex space-x-8">
            <a href="?status=&" class="px-4 py-2 font-medium text-indigo-600 border-b-2 border-indigo-600">
                Tous
            </a>
            <a href="?status=pending" class="px-4 py-2 font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300">
                En attente
            </a>
            <a href="?status=confirmed" class="px-4 py-2 font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300">
                Confirmés
            </a>
            <a href="?status=completed" class="px-4 py-2 font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300">
                Terminés
            </a>
        </div>
    </div>

    <!-- Appointments List -->
    <div class="space-y-4">
        @forelse($appointments as $appointment)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Médecin</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $appointment->doctor->user->name }}</p>
                        <p class="text-sm text-indigo-600">{{ $appointment->doctor->specialty->name }}</p>
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
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($appointment->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                            @elseif($appointment->status == 'confirmed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                            @elseif($appointment->status == 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                            @elseif($appointment->status == 'completed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                            @endif
                        ">
                            {{ ucfirst($appointment->status) }}
                        </span>
                        <div class="dropdown relative group">
                            <button class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300">
                                ⋮
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-10">
                                <a href="{{ route('appointments.show', $appointment) }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-t-lg">
                                    Voir détails
                                </a>
                                @if($appointment->status == 'pending' && $appointment->appointment_date_time > now()->addHours(24))
                                    <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Êtes-vous sûr ?')" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-gray-600 rounded-b-lg">
                                            Annuler
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <img src="{{ asset('icons/calendar.svg') }}" alt="No Appointments" class="w-16 h-16 text-gray-400 mx-auto mb-4">
                <p class="text-gray-600 dark:text-gray-400 text-lg mb-6">Aucun rendez-vous pour le moment</p>
                @if(auth()->user()->isPatient())
                    <a href="{{ route('appointments.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition inline-block">
                        Prendre un rendez-vous
                    </a>
                @endif
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
