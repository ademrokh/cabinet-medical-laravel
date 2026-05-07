@extends('layouts.app')

@section('title', 'Documents Medicaux - Medecin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Documents medicaux</h1>
            <p class="text-slate-600 mt-1">Televersez des documents pour vos patients et liez-les aux rendez-vous.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-emerald-100 bg-emerald-50 text-emerald-800 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Nouveau document</h2>
                <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @if($appointments->isEmpty())
                        <div class="rounded-xl border border-amber-100 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                            Aucun rendez-vous a venir pour selectionner un patient.
                        </div>
                    @endif
                    <div>
                        <label for="patient_id" class="block text-sm font-semibold text-slate-700">Patient</label>
                        <select id="patient_id" name="patient_id" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400" @if($appointments->isEmpty()) disabled @endif>
                            <option value="">Selectionner un patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" @if(old('patient_id') == $patient->id) selected @endif>
                                    {{ $patient->name }} ({{ $patient->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="appointment_id" class="block text-sm font-semibold text-slate-700">Rendez-vous (optionnel)</label>
                        <select id="appointment_id" name="appointment_id" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400" @if($appointments->isEmpty()) disabled @endif>
                            <option value="">Aucun</option>
                            @foreach($appointments as $appointment)
                                <option value="{{ $appointment->id }}" data-patient-id="{{ $appointment->patient_id }}" @if(old('appointment_id') == $appointment->id) selected @endif>
                                    {{ $appointment->patient->name }} - {{ $appointment->appointment_date_time->format('d/m/Y H:i') }}
                                </option>
                            @endforeach
                        </select>
                        @error('appointment_id')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-semibold text-slate-700">Type</label>
                        <select id="type" name="type" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400">
                            <option value="prescription" @if(old('type') === 'prescription') selected @endif>Prescription</option>
                            <option value="result" @if(old('type') === 'result') selected @endif>Resultat</option>
                            <option value="diagnosis" @if(old('type') === 'diagnosis') selected @endif>Diagnostic</option>
                            <option value="other" @if(old('type') === 'other') selected @endif>Autre</option>
                        </select>
                        @error('type')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-700">Description</label>
                        <textarea id="description" name="description" rows="4" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400" placeholder="Details cliniques, recommandations, etc.">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="file" class="block text-sm font-semibold text-slate-700">Fichier</label>
                        <input id="file" name="file" type="file" class="mt-2 w-full rounded-xl border-slate-200 bg-white px-3 py-2 focus:ring-2 focus:ring-emerald-400" required>
                        @error('file')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full px-5 py-2.5 rounded-lg bg-emerald-600 text-white font-semibold shadow-sm hover:bg-emerald-700 transition" @if($appointments->isEmpty()) disabled @endif>
                        Televerser
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Documents recents</h2>
                </div>
                @if($documents->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Patient</th>
                                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Type</th>
                                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Rendez-vous</th>
                                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($documents as $document)
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="px-6 py-4 text-slate-900 font-semibold">
                                            {{ $document->patient->name ?? 'Patient' }}
                                            <p class="text-xs text-slate-500 font-normal">{{ $document->patient->email ?? '' }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-slate-700">{{ ucfirst($document->type) }}</td>
                                        <td class="px-6 py-4 text-slate-600">
                                            @if($document->appointment)
                                                {{ $document->appointment->appointment_date_time->format('d/m/Y H:i') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-end gap-3">
                                                <a href="{{ route('documents.show', $document) }}" class="text-emerald-600 font-semibold hover:text-emerald-700 transition" target="_blank" rel="noopener">Voir</a>
                                                <a href="{{ route('documents.download', $document) }}" class="text-slate-600 font-semibold hover:text-slate-800 transition" download>Telecharger</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $documents->links() }}
                    </div>
                @else
                    <div class="px-6 py-10 text-center text-slate-500">Aucun document pour le moment.</div>
                @endif
            </div>
        </div>
    </div>
    <script>
        (function () {
            const patientSelect = document.getElementById('patient_id');
            const appointmentSelect = document.getElementById('appointment_id');

            if (!patientSelect || !appointmentSelect) {
                return;
            }

            const findFirstAppointmentForPatient = (patientId) => {
                return Array.from(appointmentSelect.options).find((option) => {
                    return option.dataset.patientId === String(patientId);
                });
            };

            const syncAppointmentForPatient = () => {
                const patientId = patientSelect.value;

                if (!patientId) {
                    appointmentSelect.value = '';
                    return;
                }

                const match = findFirstAppointmentForPatient(patientId);
                appointmentSelect.value = match ? match.value : '';
            };

            patientSelect.addEventListener('change', syncAppointmentForPatient);

            if (!appointmentSelect.value) {
                if (!patientSelect.value) {
                    const firstAppointment = Array.from(appointmentSelect.options).find((option) => option.dataset.patientId);
                    if (firstAppointment) {
                        patientSelect.value = firstAppointment.dataset.patientId;
                    }
                }

                syncAppointmentForPatient();
            }
        })();
    </script>
</div>
@endsection
