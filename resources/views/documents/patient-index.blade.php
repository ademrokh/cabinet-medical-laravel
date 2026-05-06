@extends('layouts.app')

@section('title', 'Mes Documents Médicaux - Cabinet Médical')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('patient.dashboard') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold mb-4 inline-block">← Retour au tableau de bord</a>
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">Mes Documents Médicaux</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">Accédez à vos ordonnances, résultats et diagnoses.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if($documents->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
                <!-- Documents Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Type</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Médecin</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Description</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Date</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($documents as $document)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                                            @switch($document->type)
                                                @case('prescription')
                                                    bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                    @break
                                                @case('result')
                                                    bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @break
                                                @case('diagnosis')
                                                    bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                                    @break
                                                @default
                                                    bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                            @endswitch
                                        ">
                                            {{ ucfirst($document->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $document->doctor->user->photo ? asset('storage/' . $document->doctor->user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($document->doctor->user->name) . '&color=7F9CF5&background=EBF4FF&size=32' }}" alt="{{ $document->doctor->user->name }}" class="w-8 h-8 rounded-full object-cover">
                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-white">Dr. {{ $document->doctor->user->name }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $document->doctor->specialty->name ?? 'Généraliste' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                        {{ Str::limit($document->description ?? 'Pas de description', 50) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $document->created_at->isoFormat('D MMMM YYYY') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <a href="{{ route('documents.show', $document) }}" class="text-emerald-600 hover:text-emerald-700 font-semibold transition">
                                                Voir
                                            </a>
                                            <a href="{{ route('documents.download', $document) }}" class="text-blue-600 hover:text-blue-700 font-semibold transition" download>
                                                📥
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4">
                    {{ $documents->links() }}
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center">
                <svg class="w-20 h-20 mx-auto text-gray-300 dark:text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500 dark:text-gray-400 text-lg mb-6">Vous n'avez aucun document médical pour le moment.</p>
                <p class="text-gray-600 dark:text-gray-400">Les documents seront disponibles ici une fois uploadés par vos médecins.</p>
            </div>
        @endif
    </div>
</div>
@endsection
