@extends('layouts.app')

@section('title', 'Nos Médecins - Cabinet Médical')

@section('content')
<div class="bg-gradient-to-br from-emerald-50 via-white to-orange-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-16" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" x-show="show" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight">Découvrez nos medecins</h1>
            <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">Une equipe attentionnee et disponible pour chaque patient.</p>
        </div>

        <!-- Filter -->
        <div class="mb-12" x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)" x-show="show" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
            <form method="GET" action="{{ route('doctors.index') }}" class="bg-white/90 backdrop-blur p-4 rounded-2xl shadow-lg flex flex-col md:flex-row items-center gap-4 max-w-2xl mx-auto">
                <div class="flex-1 w-full">
                    <label for="specialty" class="sr-only">Spécialité</label>
                    <select name="specialty" id="specialty" class="w-full px-4 py-3 border-slate-200 bg-white rounded-lg text-slate-900 focus:ring-emerald-400 focus:border-emerald-400 transition">
                        <option value="">Toutes les spécialités</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}" @if(request('specialty') == $specialty->id) selected @endif>
                                {{ $specialty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full md:w-auto bg-emerald-600 text-white px-8 py-3 rounded-lg font-semibold text-lg hover:bg-emerald-700 transition-transform transform hover:scale-105 shadow-lg">
                    Filtrer
                </button>
            </form>
        </div>

        <!-- Doctors Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($doctors as $index => $doctor)
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2" x-data="{ show: false }" x-init="setTimeout(() => show = true, {{ ($index % 3) * 200 + 500 }})" x-show="show" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="h-48 bg-gradient-to-r from-emerald-200 via-sky-200 to-orange-200 flex items-end justify-center p-4 relative">
                        <img src="{{ $doctor->user->photo ? asset('storage/' . $doctor->user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($doctor->user->name) . '&color=7F9CF5&background=EBF4FF&size=128' }}" alt="Photo de {{ $doctor->user->name }}" class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-700 object-cover -mb-16 shadow-lg">
                    </div>
                    <div class="p-6 pt-20 text-center">
                        <h3 class="text-2xl font-bold text-slate-900">{{ $doctor->user->name }}</h3>
                        <p class="text-emerald-600 font-semibold mt-1">{{ $doctor->specialty->name }}</p>
                        <p class="text-slate-600 text-sm mt-4 h-10 line-clamp-2">{{ $doctor->biography }}</p>

                        <div class="mt-6 flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('doctors.show', $doctor) }}" class="flex-1 bg-emerald-100 text-emerald-700 px-4 py-2.5 rounded-lg hover:bg-emerald-200 transition text-center font-medium">
                                Voir le profil
                            </a>
                            @if(auth()->check() && auth()->user()->isPatient())
                                <a href="{{ route('appointments.create', ['doctor_id' => $doctor->id]) }}" class="flex-1 bg-slate-900 text-white px-4 py-2.5 rounded-lg hover:bg-slate-800 transition text-center font-medium shadow-md hover:shadow-lg">
                                    Prendre RDV
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <svg class="w-24 h-24 mx-auto text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-gray-500 dark:text-gray-400 text-xl mt-6">Aucun médecin ne correspond à votre recherche.</p>
                    <p class="text-gray-400 dark:text-gray-500 mt-2">Essayez de sélectionner une autre spécialité.</p>
                </div>
            @endforelse
        </div>

        @if($doctors->hasPages())
            <div class="mt-16">
                {{ $doctors->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

