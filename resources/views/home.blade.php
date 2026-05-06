@extends('layouts.app')

@section('title', 'Accueil - Cabinet Medical')

@section('content')
<div class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 via-white to-orange-50"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <p class="text-sm uppercase tracking-[0.35em] text-emerald-500 font-semibold">Cabinet Medical</p>
                <h1 class="mt-4 text-4xl md:text-6xl font-extrabold text-slate-900 leading-tight">
                    Une experience medicale
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 via-sky-500 to-orange-500">claire et humaine</span>
                </h1>
                <p class="mt-5 text-lg text-slate-600 max-w-xl">
                    Prenez rendez-vous, suivez vos consultations et accedez a vos documents en un espace lumineux, rapide et intelligent.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('doctors.index') }}" class="px-8 py-3 rounded-xl bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl hover:bg-emerald-700 transition">
                        Choisir un medecin
                    </a>
                    @if(auth()->check())
                        <a href="{{ route('appointments.create') }}" class="px-8 py-3 rounded-xl bg-slate-900 text-white font-semibold hover:bg-slate-800 transition">
                            Prendre RDV
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="px-8 py-3 rounded-xl border border-emerald-200 bg-white text-emerald-700 font-semibold hover:bg-emerald-50 transition">
                            Creer un compte
                        </a>
                    @endif
                </div>
            </div>
            <div class="relative">
                <div class="absolute -top-10 -right-10 h-32 w-32 rounded-full bg-emerald-200 blur-3xl opacity-70"></div>
                <div class="absolute -bottom-8 -left-8 h-40 w-40 rounded-full bg-orange-200 blur-3xl opacity-70"></div>
                <img src="https://images.unsplash.com/photo-1504814532849-92751b0bc8a4?q=80&w=1800&auto=format&fit=crop" alt="Equipe medicale" class="relative rounded-3xl shadow-2xl">
                <div class="absolute -bottom-6 left-6 bg-white/90 backdrop-blur rounded-2xl px-5 py-4 shadow-xl">
                    <p class="text-sm text-slate-500">Satisfaction patients</p>
                    <p class="text-2xl font-bold text-slate-900">98%</p>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="relative py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white/90 backdrop-blur rounded-2xl p-6 shadow-lg hover:shadow-xl transition">
                <div class="h-12 w-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">24/7</div>
                <h3 class="mt-4 text-xl font-semibold text-slate-900">Suivi continu</h3>
                <p class="mt-2 text-slate-600">Des rapports clairs et un suivi medical simplifie.</p>
            </div>
            <div class="bg-white/90 backdrop-blur rounded-2xl p-6 shadow-lg hover:shadow-xl transition">
                <div class="h-12 w-12 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center font-bold">IA</div>
                <h3 class="mt-4 text-xl font-semibold text-slate-900">Planning intelligent</h3>
                <p class="mt-2 text-slate-600">Des plannings optimises et des suggestions de creneaux.</p>
            </div>
            <div class="bg-white/90 backdrop-blur rounded-2xl p-6 shadow-lg hover:shadow-xl transition">
                <div class="h-12 w-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-bold">SOS</div>
                <h3 class="mt-4 text-xl font-semibold text-slate-900">Priorites medicales</h3>
                <p class="mt-2 text-slate-600">Un tri des patients pour mieux gerer les urgences.</p>
            </div>
        </div>
    </div>
</section>

<section class="relative py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-10">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Experts</p>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900">Nos medecins</h2>
            </div>
            <a href="{{ route('doctors.index') }}" class="text-emerald-600 font-semibold">Voir tous</a>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($doctors as $doctor)
                <div class="bg-white rounded-3xl shadow-lg overflow-hidden hover:-translate-y-1 transition">
                    <div class="h-36 bg-gradient-to-r from-emerald-200 via-sky-200 to-orange-200"></div>
                    <div class="p-6 -mt-12">
                        <img src="{{ $doctor->user->photo ? asset('storage/' . $doctor->user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($doctor->user->name) . '&color=0F172A&background=E2E8F0' }}" class="w-20 h-20 rounded-2xl border-4 border-white shadow-md" alt="{{ $doctor->user->name }}">
                        <h3 class="mt-4 text-xl font-semibold text-slate-900">{{ $doctor->user->name }}</h3>
                        <p class="text-emerald-600 font-semibold">{{ $doctor->specialty->name }}</p>
                        <p class="mt-3 text-sm text-slate-600 line-clamp-2">{{ $doctor->biography }}</p>
                        <a href="{{ route('doctors.show', $doctor) }}" class="mt-4 inline-flex items-center text-slate-900 font-semibold">
                            Voir le profil →
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-slate-600">Aucun medecin disponible.</p>
            @endforelse
        </div>
    </div>
</section>

<section class="relative py-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-emerald-600 via-sky-600 to-orange-500 rounded-3xl p-10 text-white shadow-2xl">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold">Pret a demarrer ?</h2>
                    <p class="mt-3 text-white/80">Accedez a vos rendez-vous, vos documents et a nos plannings IA.</p>
                </div>
                <div class="flex flex-wrap gap-4">
                    @if(auth()->check())
                        <a href="{{ route('dashboard') }}" class="px-8 py-3 rounded-xl bg-white text-slate-900 font-semibold">Ouvrir mon tableau</a>
                    @else
                        <a href="{{ route('register') }}" class="px-8 py-3 rounded-xl bg-white text-slate-900 font-semibold">Creer un compte</a>
                    @endif
                    <a href="{{ route('doctors.index') }}" class="px-8 py-3 rounded-xl border border-white/40 font-semibold">Voir les medecins</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
