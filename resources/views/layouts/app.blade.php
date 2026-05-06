<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Cabinet Médical')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts / Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Poppins', sans-serif; }
        </style>
    </head>
    <body class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-900 dark:to-gray-800 antialiased">
        <nav class="bg-white dark:bg-gray-800 shadow sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center">
                            <div class="text-2xl font-bold text-indigo-600 flex items-center gap-2">
                                <img src="{{ asset('icons/hospital.svg') }}" alt="Cabinet" class="w-6 h-6">
                                Cabinet Médical
                            </div>
                        </a>
                        <div class="hidden md:flex space-x-8 ml-10">
                            <a href="{{ route('home') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 transition px-3 py-2">Accueil</a>
                            <a href="{{ route('doctors.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 transition px-3 py-2">Médecins</a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <div class="relative group">
                                <button class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 font-medium">
                                    {{ Auth::user()->name }} ▼
                                </button>
                                <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-10">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-600">Profil</a>
                                    @if(Auth::user()->isPatient())
                                        <a href="{{ route('appointments.patient') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-600">Mes RDV</a>
                                        <a href="{{ route('patient.dashboard') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-600">Tableau de bord</a>
                                    @endif
                                    @if(Auth::user()->isDoctor())
                                        <a href="{{ route('doctor.appointments') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-600">Mes RDV</a>
                                        <a href="{{ route('doctor.dashboard') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-600">Tableau de bord</a>
                                    @endif
                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-600">Admin</a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-gray-600">Déconnexion</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Connexion</a>
                            @if(Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Inscription</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main>
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <footer class="bg-gray-900 dark:bg-gray-950 text-white mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                            <img src="{{ asset('icons/hospital.svg') }}" alt="Cabinet" class="w-5 h-5">
                            Cabinet Médical
                        </h3>
                        <p class="text-gray-400">Votre santé, notre priorité</p>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Horaires</h4>
                        <p class="text-gray-400 text-sm">Lun-Ven: 9h - 18h<br>Sam: 9h - 14h<br>Dim: Fermé</p>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Contact</h4>
                        <p class="text-gray-400 text-sm">Tél: 01 23 45 67 89<br>Email: contact@cabinet.fr</p>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Adresse</h4>
                        <p class="text-gray-400 text-sm">123 Rue de la Santé<br>75000 Paris, France</p>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                    <p>&copy; 2026 Cabinet Médical. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
