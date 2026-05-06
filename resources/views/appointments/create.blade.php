@extends('layouts.app')

@section('title', 'Prendre un Rendez-vous - Cabinet Médical')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8">Prendre un rendez-vous</h1>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
        <form action="{{ route('appointments.store') }}" method="POST">
            @csrf

            <!-- Doctor Selection -->
            <div class="mb-6">
                <label for="doctor_id" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Sélectionnez un médecin
                </label>
                <select name="doctor_id" id="doctor_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500" required @if(request('doctor_id')) disabled @endif>
                    <option value="">-- Choisir un médecin --</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" @if(request('doctor_id') == $doctor->id || isset($selectedDoctorId) && $selectedDoctorId == $doctor->id) selected @endif>
                            {{ $doctor->user->name }} - {{ $doctor->specialty->name }}
                        </option>
                    @endforeach
                </select>
                @error('doctor_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date Selection -->
            <div class="mb-6">
                <label for="appointment_date_time" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Date et heure du rendez-vous
                </label>
                <input type="datetime-local" name="appointment_date_time" id="appointment_date_time" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500" required min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}">
                @error('appointment_date_time')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Le rendez-vous doit être pris au minimum 30 minutes à l'avance.</p>
            </div>

            <!-- Reason -->
            <div class="mb-6">
                <label for="reason" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Motif de la consultation
                </label>
                <input type="text" name="reason" id="reason" placeholder="Ex: Consultation générale, Douleur à l'épaule..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500" required>
                @error('reason')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label for="notes" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Notes additionnelles (optionnel)
                </label>
                <textarea name="notes" id="notes" rows="4" placeholder="Informations supplémentaires..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"></textarea>
                @error('notes')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition font-semibold">
                    Confirmer le rendez-vous
                </button>
                <a href="{{ route('home') }}" class="flex-1 bg-gray-300 dark:bg-gray-700 text-gray-900 dark:text-white px-6 py-3 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 transition font-semibold text-center">
                    Annuler
                </a>
            </div>
        </form>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mt-8">
        <h3 class="font-semibold text-blue-900 dark:text-blue-300 mb-2">ℹ️ Informations importantes</h3>
        <ul class="text-sm text-blue-800 dark:text-blue-400 space-y-1">
            <li>• Veuillez arriver 10 minutes avant votre rendez-vous</li>
            <li>• Apportez votre carte vitale et une pièce d'identité</li>
            <li>• Pour annuler ou reporter, veuillez nous contacter 24h à l'avance</li>
            <li>• Vous recevrez une confirmation par email</li>
        </ul>
    </div>
</div>
@endsection
