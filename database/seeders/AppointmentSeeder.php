<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = \App\Models\Doctor::all();
        $patients = \App\Models\User::where('role', 'patient')->get();

        if ($patients->isEmpty()) {
            return;
        }

        $reasons = [
            'Consultation générale',
            'Suivi médical',
            'Examen de routine',
            'Bilan de santé',
            'Traitement de problème médical',
            'Suivi post-opératoire',
            'Consultation urgente',
            'Visite de contrôle',
            'Prescription de médicaments',
            'Examen dermatologique',
            'Neurological assessment',
            'Cardiac check-up',
        ];

        $statuses = ['scheduled', 'completed', 'cancelled', 'pending'];

        $appointmentCount = 0;
        $maxAppointments = 50;

        // Create appointments across different doctors and dates
        foreach ($doctors as $doctor) {
            for ($i = 0; $i < 8 && $appointmentCount < $maxAppointments; $i++) {
                $daysAhead = rand(0, 30);
                $appointmentDate = now()->addDays($daysAhead);

                // Set random time slot
                $hour = rand(9, 16);
                $minute = rand(0, 1) * 30; // 00 or 30 minutes
                $appointmentDate->setTime($hour, $minute);

                // Pick random patient
                $patient = $patients->random();

                $status = $statuses[array_rand($statuses)];

                \App\Models\Appointment::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'appointment_date_time' => $appointmentDate,
                    'reason' => $reasons[array_rand($reasons)],
                    'status' => $status,
                    'notes' => $this->generateNotes($status),
                ]);

                $appointmentCount++;
            }
        }
    }

    private function generateNotes(string $status): string
    {
        $notes = [
            'scheduled' => 'Rendez-vous confirmé avec le patient',
            'completed' => 'Consultation effectuée avec succès',
            'cancelled' => 'Annulé par le patient',
            'pending' => 'En attente de confirmation',
        ];

        return $notes[$status] ?? '';
    }
}
