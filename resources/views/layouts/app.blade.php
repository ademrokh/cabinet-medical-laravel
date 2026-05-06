<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Cabinet Médical')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=sora:400,500,600,700,800&family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts / Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root {
                --brand-1: #14b8a6;
                --brand-2: #f97316;
                --brand-3: #3b82f6;
                --ink-1: #0f172a;
            }
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            h1, h2, h3, h4 { font-family: 'Sora', sans-serif; }
        </style>
    </head>
    <body class="bg-gradient-to-br from-teal-50 via-white to-orange-50 text-slate-800 antialiased">
        <div id="app">
            <nav class="bg-white/70 backdrop-blur-xl border-b border-white/60 shadow-sm sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-20">
                        <div class="flex items-center">
                            <a href="{{ route('home') }}" class="flex items-center gap-2">
                                <div class="bg-gradient-to-br from-emerald-500 via-teal-500 to-sky-500 p-2.5 rounded-xl shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <span class="text-2xl font-bold text-slate-900 tracking-tight">Cabinet</span>
                            </a>
                        </div>
                        <div class="hidden md:flex items-center space-x-2">
                            <a href="{{ route('home') }}" class="text-slate-600 hover:text-emerald-600 transition-colors px-4 py-2 rounded-lg font-medium">Accueil</a>
                            <a href="{{ route('doctors.index') }}" class="text-slate-600 hover:text-emerald-600 transition-colors px-4 py-2 rounded-lg font-medium">Medecins</a>
                            @auth
                                <a href="{{ route('dashboard') }}" class="text-slate-600 hover:text-emerald-600 transition-colors px-4 py-2 rounded-lg font-medium">Tableau</a>
                                @if(Auth::user()->isDoctor() || Auth::user()->isAdmin())
                                    <a href="{{ route('planning.index') }}" class="text-slate-600 hover:text-emerald-600 transition-colors px-4 py-2 rounded-lg font-medium">Planning IA</a>
                                @endif
                            @endauth
                        </div>
                        <div class="flex items-center space-x-4">
                            @auth
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="flex items-center gap-2 text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium focus:outline-none">
                                        <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=7F9CF5&background=EBF4FF' }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover border-2 border-transparent hover:border-indigo-500 transition">
                                        <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-2xl py-2 z-20"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                                        x-transition:enter-end="opacity-100 transform translate-y-0"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 transform translate-y-0"
                                        x-transition:leave-end="opacity-0 transform -translate-y-2">

                                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                                        </div>

                                        @if(Auth::user()->isPatient())
                                            <a href="{{ route('patient.dashboard') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700">Tableau de bord</a>
                                            <a href="{{ route('appointments.patient') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700">Mes RDV</a>
                                        @elseif(Auth::user()->isDoctor())
                                            <a href="{{ route('doctor.dashboard') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700">Tableau de bord</a>
                                            <a href="{{ route('doctor.appointments') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700">Mes RDV</a>
                                        @elseif(Auth::user()->isAdmin())
                                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700">Admin Dashboard</a>
                                        @endif
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700">Profil</a>

                                        <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>

                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                                Déconnexion
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="text-slate-600 hover:text-emerald-600 font-medium transition-colors">Connexion</a>
                                @if(Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-emerald-600 text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-emerald-700 transition-all duration-300 shadow-md hover:shadow-lg">Inscription</a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <main class="min-h-screen">
                {{ $slot ?? '' }}
                @yield('content')
            </main>

            <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                        <div class="col-span-2 md:col-span-1">
                            <a href="{{ route('home') }}" class="flex items-center gap-2">
                                <div class="bg-indigo-600 p-2 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <span class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Cabinet</span>
                            </a>
                            <p class="text-gray-500 dark:text-gray-400 mt-4">Votre santé, notre priorité.</p>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Navigation</h4>
                            <ul class="space-y-2">
                                <li><a href="{{ route('home') }}" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600">Accueil</a></li>
                                <li><a href="{{ route('doctors.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600">Médecins</a></li>
                                <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600">Services</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contact</h4>
                            <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                                <li>Tél: 01 23 45 67 89</li>
                                <li>Email: contact@cabinet.fr</li>
                                <li>123 Rue de la Santé, 75000 Paris</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Horaires</h4>
                            <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                                <li>Lun-Ven: 9h - 18h</li>
                                <li>Sam: 9h - 14h</li>
                                <li>Dim: Fermé</li>
                            </ul>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700 mt-8 pt-8 text-center text-gray-500 dark:text-gray-400">
                        <p>&copy; {{ date('Y') }} Cabinet Médical. Tous droits réservés.</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>

