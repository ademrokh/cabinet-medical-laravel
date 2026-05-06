<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SmartPlanning;
use App\Models\Doctor;
use Carbon\Carbon;

class SmartPlanningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = Doctor::all();

        foreach ($doctors as $doctor) {
            // Create multiple planning for different dates
            for ($i = 0; $i < 3; $i++) {
                $planDate = Carbon::now()->addDays($i * 3);

                SmartPlanning::create([
                    'doctor_id' => $doctor->id,
                    'date' => $planDate,
                    'generated_plan' => $this->generateAIPlan($doctor, $planDate),
                    'status' => $this->getRandomStatus(),
                    'generated_at' => Carbon::now()->subHours(rand(1, 24)),
                ]);
            }
        }
    }

    private function generateAIPlan(Doctor $doctor, Carbon $date): array
    {
        $timeSlots = [
            ['time' => '09:00', 'duration' => 30],
            ['time' => '09:30', 'duration' => 30],
            ['time' => '10:00', 'duration' => 30],
            ['time' => '10:30', 'duration' => 30],
            ['time' => '11:00', 'duration' => 30],
            ['time' => '11:30', 'duration' => 30],
            ['time' => '14:00', 'duration' => 30],
            ['time' => '14:30', 'duration' => 30],
            ['time' => '15:00', 'duration' => 30],
            ['time' => '15:30', 'duration' => 30],
            ['time' => '16:00', 'duration' => 30],
            ['time' => '16:30', 'duration' => 30],
        ];

        $patients = [
            ['name' => 'Jean Martin', 'reason' => 'Consultation générale', 'priority' => 'normal'],
            ['name' => 'Marie Dubois', 'reason' => 'Suivi médical', 'priority' => 'normal'],
            ['name' => 'Pierre Lefevre', 'reason' => 'Examen urgent', 'priority' => 'urgent'],
            ['name' => 'Sophie Laurent', 'reason' => 'Bilan de santé', 'priority' => 'normal'],
            ['name' => 'Antoine Moreau', 'reason' => 'Consultation rapide', 'priority' => 'low'],
            ['name' => 'Claire Durand', 'reason' => 'Suivi chronique', 'priority' => 'normal'],
            ['name' => 'Luc Bernard', 'reason' => 'Urgence', 'priority' => 'urgent'],
            ['name' => 'Isabelle Rousseau', 'reason' => 'Suivi post-opératoire', 'priority' => 'normal'],
        ];

        $planning = [];
        $slotIndex = 0;

        // Sort patients by priority (urgent first)
        $priorityOrder = ['urgent' => 0, 'normal' => 1, 'low' => 2];
        usort($patients, function ($a, $b) use ($priorityOrder) {
            return $priorityOrder[$a['priority']] - $priorityOrder[$b['priority']];
        });

        foreach ($patients as $patient) {
            if ($slotIndex < count($timeSlots)) {
                $slot = $timeSlots[$slotIndex];
                $planning[] = [
                    'time' => $slot['time'],
                    'end_time' => $this->addMinutes($slot['time'], $slot['duration']),
                    'patient' => $patient['name'],
                    'reason' => $patient['reason'],
                    'priority' => $patient['priority'],
                    'duration_minutes' => $slot['duration'],
                    'room' => 'Salle ' . (($slotIndex % 3) + 1),
                    'notes' => $this->generateNotes($patient['priority']),
                ];
                $slotIndex++;
            }
        }

        return [
            'date' => $date->format('Y-m-d'),
            'doctor_name' => $doctor->user->name,
            'specialty' => $doctor->specialty->name ?? 'Généraliste',
            'total_appointments' => count($planning),
            'planning' => $planning,
            'optimization_metrics' => [
                'utilization_rate' => number_format((count($planning) / count($timeSlots)) * 100, 2),
                'average_wait_time' => rand(5, 15),
                'high_priority_scheduled' => count(array_filter($planning, fn($p) => $p['priority'] === 'urgent')),
                'efficiency_score' => number_format(rand(75, 98), 2),
            ],
            'recommendations' => $this->generateRecommendations($doctor, $planning),
        ];
    }

    private function addMinutes(string $time, int $minutes): string
    {
        $timestamp = strtotime($time);
        $timestamp += $minutes * 60;
        return date('H:i', $timestamp);
    }

    private function generateNotes(string $priority): string
    {
        $notes = [
            'urgent' => 'Patient prioritaire - Nécessite attention immédiate',
            'normal' => 'Consultation standard - Suivi régulier',
            'low' => 'Visite de courtoisie - Peu urgent',
        ];

        return $notes[$priority] ?? 'Consultation médicale';
    }

    private function generateRecommendations(Doctor $doctor, array $planning): array
    {
        return [
            'schedule_optimization' => 'Les créneaux sont optimisés pour minimiser les temps d\'attente',
            'priority_distribution' => count(array_filter($planning, fn($p) => $p['priority'] === 'urgent')) . ' patients prioritaires programmés en matinée',
            'break_suggestion' => 'Pause recommandée entre 12:00 et 14:00',
            'resource_allocation' => '3 salles suffisantes pour la journée',
            'workload_balance' => 'Charge équilibrée tout au long de la journée',
        ];
    }

    private function getRandomStatus(): string
    {
        $statuses = ['completed', 'processing', 'pending', 'completed', 'completed'];
        return $statuses[array_rand($statuses)];
    }
}
