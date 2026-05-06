<!DOCTYPE html>
<html>
<head>
    <title>Résumé du Rendez-vous</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .content { margin: 20px; }
        .summary { margin-top: 20px; padding: 10px; border: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Résumé du Rendez-vous</h1>
    </div>
    <div class="content">
        <p><strong>Patient:</strong> {{ $appointment->patient->name }}</p>
        <p><strong>Docteur:</strong> {{ $appointment->doctor->user->name }}</p>
        <p><strong>Date:</strong> {{ $appointment->appointment_date_time }}</p>
        <p><strong>Raison:</strong> {{ $appointment->reason }}</p>
        <div class="summary">
            <h2>Résumé IA</h2>
            <p>{{ $summary }}</p>
        </div>
    </div>
</body>
</html>
