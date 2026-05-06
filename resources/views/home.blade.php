@extends('layouts.app')

@section('title', 'Accueil - Cabinet Médical')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-r from-indigo-600 to-blue-600 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-5xl md:text-6xl font-bold mb-6">Bienvenue au Cabinet Médical</h1>
                <p class="text-xl text-indigo-100 mb-8">Votre santé est notre priorité. Des médecins qualifiés et expérimentés à votre service 24h/24.</p>
                <div class="flex gap-4">
                    <a href="{{ route('doctors.index') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-indigo-50 transition">
                        Nos Médecins
                    </a>
                    @if(!auth()->check())
                        <a href="{{ route('register') }}" class="bg-indigo-700 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-800 transition">
                            S'inscrire
                        </a>
                    @else
                        <a href="{{ route('appointments.create') }}" class="bg-indigo-700 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-800 transition">
                            Prendre RDV
                        </a>
                    @endif
                </div>
            </div>
            <div class="hidden md:block">
                <div class="bg-indigo-400 rounded-full w-64 h-64 mx-auto flex items-center justify-center text-6xl">
                    <img src="{{ asset('icons/doctor.svg') }}" alt="Doctors" class="w-10 h-10 text-indigo-600">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Services Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <h2 class="text-4xl font-bold text-center mb-12 text-gray-900 dark:text-white">Nos Services</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-8 shadow-lg hover:shadow-xl transition">
            <div class="text-4xl mb-4">🩺</div>
            <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Consultations Médicales</h3>
            <p class="text-gray-600 dark:text-gray-400">Consultations avec nos médecins qualifiés pour tous vos besoins de santé.</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg p-8 shadow-lg hover:shadow-xl transition">
            <img src="{{ asset('icons/clipboard.svg') }}" alt="Appointments" class="w-12 h-12 text-indigo-600 mx-auto mb-4">
            <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Suivi Médical</h3>
            <p class="text-gray-600 dark:text-gray-400">Suivis réguliers et gestion de vos dossiers médicaux en toute sécurité.</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg p-8 shadow-lg hover:shadow-xl transition">
            <div class="text-4xl mb-4">💉</div>
            <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Vaccinations</h3>
            <p class="text-gray-600 dark:text-gray-400">Tous les vaccins recommandés et préventifs disponibles.</p>
        </div>
    </div>
</section>

<!-- Doctors Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <h2 class="text-4xl font-bold text-center mb-12 text-gray-900 dark:text-white">Nos Médecins</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($doctors as $doctor)
            <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition transform hover:scale-105">
                <div class="bg-gradient-to-r from-indigo-500 to-blue-500 h-32 flex items-center justify-center">
                    <img src="{{ asset('icons/doctor.svg') }}" alt="Doctor" class="w-16 h-16 text-indigo-600 mx-auto">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ $doctor->user->name }}</h3>
                    <p class="text-indigo-600 font-medium mb-3">{{ $doctor->specialty->name }}</p>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">{{ $doctor->biography }}</p>
                    <a href="{{ route('doctors.show', $doctor) }}" class="text-indigo-600 hover:text-indigo-700 font-semibold">
                        Voir le profil →
                    </a>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-600 dark:text-gray-400 col-span-3">Aucun médecin disponible.</p>
        @endforelse
    </div>
    <div class="text-center mt-12">
        <a href="{{ route('doctors.index') }}" class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition inline-block">
            Voir tous les médecins
        </a>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-indigo-600 text-white py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold mb-6">Prêt à prendre un rendez-vous ?</h2>
        <p class="text-xl text-indigo-100 mb-8">Consultez nos médecins et prenez soin de votre santé dès aujourd'hui.</p>
        @if(!auth()->check())
            <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-indigo-50 transition inline-block">
                Créer un compte
            </a>
        @else
            <a href="{{ route('appointments.create') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-indigo-50 transition inline-block">
                Prendre RDV
            </a>
        @endif
    </div>
</section>

<!-- Info Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
        <div>
            <div class="text-4xl font-bold text-indigo-600 mb-2">8+</div>
            <p class="text-gray-600 dark:text-gray-400">Spécialités Médicales</p>
        </div>
        <div>
            <div class="text-4xl font-bold text-indigo-600 mb-2">6+</div>
            <p class="text-gray-600 dark:text-gray-400">Médecins Expérimentés</p>
        </div>
        <div>
            <div class="text-4xl font-bold text-indigo-600 mb-2">1000+</div>
            <p class="text-gray-600 dark:text-gray-400">Patients Satisfaits</p>
        </div>
    </div>
</section>
@endsection
