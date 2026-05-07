@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Export PDF</h1>

    @if($type === 'appointment')
        <div class="bg-white rounded-lg shadow p-4 mb-4">
            <p><strong>Patient:</strong> {{ optional($appointment->patient)->name }}</p>
            <p><strong>Docteur:</strong> {{ optional(optional($appointment->doctor)->user)->name }}</p>
            <p><strong>Date:</strong> {{ optional($appointment->appointment_date_time)->toDateTimeString() }}</p>
            <p><strong>Raison:</strong> {{ $appointment->reason }}</p>
        </div>
    @elseif($type === 'consultation')
        <div class="bg-white rounded-lg shadow p-4 mb-4">
            <p><strong>Patient:</strong> {{ optional(optional($consultation->appointment)->patient)->name }}</p>
            <p><strong>Docteur:</strong> {{ optional(optional(optional($consultation->appointment)->doctor)->user)->name }}</p>
            <p><strong>Date:</strong> {{ optional(optional($consultation->appointment)->appointment_date_time)->toDateTimeString() }}</p>
            <p><strong>Notes:</strong> {{ $consultation->notes }}</p>
        </div>
    @endif

    {{-- AI Summary box --}}
    <div style="border-left: 4px solid #2563eb; background: #eff6ff; padding: 20px; border-radius: 0 6px 6px 0; margin-bottom: 24px;">
        <h2 style="color: #1d4ed8; margin-top: 0;">🤖 Résumé IA</h2>
        <div id="summary-box">
            <span id="summary-loading" style="color: #9ca3af; font-style: italic;">Génération du résumé en cours...</span>
            <div id="summary-text" style="display:none;"></div>
        </div>
    </div>

    {{-- Download form --}}
    <form id="download-form" method="POST" target="_blank" action="{{ route('export.pdf', ['type' => $type, 'id' => $id]) }}">
        @csrf
        <input type="hidden" name="summary" id="summary-input" value="">
        <button type="submit" id="download-btn" disabled
            class="bg-blue-600 text-white px-6 py-2 rounded opacity-50 cursor-not-allowed transition">
            ⏳ En attente du résumé...
        </button>
    </form>
</div>

<script>
    fetch("{{ route('export.summary', ['type' => $type, 'id' => $id]) }}")
        .then(res => res.json())
        .then(data => {
            let summary = data.summary || 'Résumé non disponible.';

            // Strip wrapping curly braces
            summary = summary.replace(/^\s*\{+\s*/, '').replace(/\s*\}+\s*$/, '').trim();

            document.getElementById('summary-loading').style.display = 'none';
            document.getElementById('summary-text').innerHTML = summary;
            document.getElementById('summary-text').style.display = 'block';
            document.getElementById('summary-input').value = summary;

            const btn = document.getElementById('download-btn');
            btn.disabled = false;
            btn.textContent = '⬇️ Télécharger le PDF';
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        })
        .catch(() => {
            document.getElementById('summary-loading').textContent = 'Résumé non disponible.';
            document.getElementById('summary-input').value = 'Résumé non disponible.';

            const btn = document.getElementById('download-btn');
            btn.disabled = false;
            btn.textContent = '⬇️ Télécharger le PDF';
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        });
</script>
@endsection
