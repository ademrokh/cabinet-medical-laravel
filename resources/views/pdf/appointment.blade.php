<!DOCTYPE html>
<html>
<head>
    <title>Résumé du Rendez-vous</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; color: #1a1a1a; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2563eb; padding-bottom: 10px; }
        .header h1 { color: #2563eb; margin: 0; font-size: 24px; }
        .content { margin: 20px; }
        .info-grid { background: #f8fafc; border-radius: 6px; padding: 16px; margin-bottom: 20px; }
        .info-grid p { margin: 6px 0; font-size: 14px; }
        .info-grid strong { color: #374151; }
        .summary { margin-top: 20px; padding: 20px; border-left: 4px solid #2563eb; background: #eff6ff; border-radius: 0 6px 6px 0; }
        .summary h2 { color: #1d4ed8; margin-top: 0; font-size: 18px; margin-bottom: 12px; }
        .summary p { margin: 6px 0; line-height: 1.7; }
        .summary ul, .summary ol { padding-left: 20px; line-height: 1.8; }
        .summary strong { color: #1e3a5f; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Résumé du Rendez-vous</h1>
    </div>
    <div class="content">
        <div class="info-grid">
            <p><strong>Patient:</strong> {{ $appointment->patient->name }}</p>
            <p><strong>Docteur:</strong> {{ $appointment->doctor->user->name }}</p>
            <p><strong>Date:</strong> {{ $appointment->appointment_date_time }}</p>
            <p><strong>Raison:</strong> {{ $appointment->reason }}</p>
        </div>
        <div class="summary">
            <h2>🤖 Résumé IA</h2>
            {!! $summary !!}
        </div>
    </div>
</body>
</html>
