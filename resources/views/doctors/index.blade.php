@extends('layouts.app')

@section('title', 'Nos Médecins - Cabinet Médical')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-12">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Nos Médecins</h1>
        <p class="text-lg text-gray-600 dark:text-gray-400">Découvrez notre équipe de médecins qualifiés et expérimentés</p>
    </div>

    <!-- Filter -->
    <div class="mb-8">
        <form method="GET" action="{{ route('doctors.index') }}" class="flex gap-4">
            <div class="flex-1">
                <select name="specialty" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 rounded-lg text-gray-900 dark:text-white">
                    <option value="">Toutes les spécialités</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}" @if(request('specialty') == $specialty->id) selected @endif>
                            {{ $specialty->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                Filtrer
            </button>
        </form>
    </div>

    <!-- Doctors Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($doctors as $doctor)
            <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                <div class="bg-gradient-to-r from-indigo-500 to-blue-500 h-40 flex items-center justify-center">
                    <img src="{{ asset('icons/doctor.svg') }}" alt="Doctor" class="w-20 h-20 text-indigo-600 mx-auto">
                </div>
                <div class="p-6">
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">{{ $doctor->user->name }}</h3>
                    <p class="text-indigo-600 font-medium text-lg mb-4">{{ $doctor->specialty->name }}</p>

                    @if($doctor->user->telephone)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">📞 {{ $doctor->user->telephone }}</p>
                    @endif

                    <p class="text-gray-600 dark:text-gray-400 mb-6 line-clamp-3">{{ $doctor->biography }}</p>

                    <div class="flex gap-3">
                        <a href="{{ route('doctors.show', $doctor) }}" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-center font-medium">
                            Voir le profil
                        </a>
                        @if(auth()->check() && auth()->user()->isPatient())
                            <a href="{{ route('appointments.create', ['doctor_id' => $doctor->id]) }}" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-center font-medium">
                                Prendre RDV
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12">
                <p class="text-gray-600 dark:text-gray-400 text-lg">Aucun médecin disponible.</p>
            </div>
        @endforelse
    </div>

    @if($doctors->hasPages())
        <div class="mt-12">
            {{ $doctors->links() }}
        </div>
    @endif
</div>
@endsection
