@extends('layouts.app')

@section('title', 'Tableau de Bord - Cabinet Médical')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-2xl sm:rounded-3xl" x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)" x-show="show" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
            <div class="p-8 md:p-12">
                <div class="text-center">
                    <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white">Tableau de Bord</h1>
                    <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">Redirection en cours...</p>
                </div>
                <div class="mt-8 text-center">
                    <p class="text-gray-500 dark:text-gray-400">Vous allez être redirigé vers votre tableau de bord approprié dans quelques instants.</p>
                    <div class="mt-6">
                        <svg class="animate-spin h-8 w-8 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

