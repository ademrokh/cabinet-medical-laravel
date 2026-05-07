@extends('layouts.app')

@section('title', 'Document Médical - Cabinet Médical')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ auth()->user()->isDoctor() ? route('documents.doctor') : route('documents.patient') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold mb-4 inline-block">← Retour aux documents</a>
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">
                        @switch($document->type)
                            @case('prescription')
                                💊 Ordonnance
                                @break
                            @case('result')
                                🔬 Résultat d'analyse
                                @break
                            @case('diagnosis')
                                📋 Diagnostic
                                @break
                            @default
                                📄 Document
                        @endswitch
                    </h1>
                </div>
                <a href="{{ route('documents.download', $document) }}" download class="bg-emerald-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-emerald-700 transition-colors">
                    📥 Télécharger
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Document Info -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Informations</h2>

                <div class="space-y-4">
                    <!-- Doctor -->
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold mb-2">Médecin</p>
                        <div class="flex items-center gap-3">
                            <img src="{{ $document->doctor->user->photo ? asset('storage/' . $document->doctor->user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($document->doctor->user->name) . '&color=7F9CF5&background=EBF4FF&size=48' }}" alt="{{ $document->doctor->user->name }}" class="w-10 h-10 rounded-full object-cover">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">Dr. {{ $document->doctor->user->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $document->doctor->specialty->name ?? 'Généraliste' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Type -->
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold mb-2">Type</p>
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
                    </div>

                    <!-- Date -->
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold mb-2">Date</p>
                        <p class="text-gray-900 dark:text-white">{{ $document->created_at->isoFormat('D MMMM YYYY à H:mm') }}</p>
                    </div>

                    @if($document->appointment)
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold mb-2">Rendez-vous</p>
                            <p class="text-gray-900 dark:text-white">
                                {{ $document->appointment->appointment_date_time->isoFormat('D MMMM YYYY à H:mm') }}
                            </p>
                        </div>
                    @endif

                    <!-- File -->
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold mb-2">Fichier</p>
                        <p class="text-gray-900 dark:text-white text-sm break-all">{{ basename($document->file_path) }}</p>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Notes du Médecin</h2>

                @if($document->description)
                    <div class="prose prose-sm dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $document->description }}</p>
                    </div>
                @else
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                        <p class="text-gray-500 dark:text-gray-400 italic">Aucune note ajoutée par le médecin.</p>
                    </div>
                @endif

                <!-- File Preview (if supported) -->
                <div class="mt-8">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Aperçu du Document</h3>

                    @php
                        $fileExtension = pathinfo($document->file_path, PATHINFO_EXTENSION);
                    @endphp

                    @if(in_array($fileExtension, ['pdf', 'jpg', 'jpeg', 'png', 'gif']))
                        @if($fileExtension === 'pdf')
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg h-96 flex items-center justify-center">
                                <div class="text-center">
                                    <p class="text-gray-500 dark:text-gray-400 mb-4">Document PDF</p>
                                    <a href="{{ route('documents.download', $document) }}" download class="inline-block bg-emerald-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-emerald-700 transition-colors">
                                        📥 Télécharger pour voir
                                    </a>
                                </div>
                            </div>
                        @else
                            <img src="{{ Storage::disk('public')->url($document->file_path) }}" alt="Document preview" class="max-w-full h-auto rounded-lg shadow">
                        @endif
                    @else
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-8 text-center">
                            <p class="text-gray-500 dark:text-gray-400 mb-4">Aperçu non disponible pour ce type de fichier</p>
                            <a href="{{ route('documents.download', $document) }}" download class="inline-block bg-emerald-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-emerald-700 transition-colors">
                                📥 Télécharger le fichier
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
