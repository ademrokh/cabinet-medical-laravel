<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PlanningSuggestion;
use App\Models\User;
use Carbon\Carbon;

class PlanningSuggestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get patients (users with role 'patient')
        $patients = User::where('role', 'patient')->get();

        foreach ($patients as $patient) {
            // Create multiple suggestions for each patient
            $numSuggestions = rand(1, 3);

            for ($i = 0; $i < $numSuggestions; $i++) {
                $daysAhead = rand(1, 14);
                $suggestedDate = Carbon::now()->addDays($daysAhead);

                // Set random time slots
                $timeSlots = ['09:00', '09:30', '10:00', '10:30', '11:00', '14:00', '14:30', '15:00', '15:30', '16:00'];
                $randomTime = $timeSlots[array_rand($timeSlots)];

                $suggestedDate->setTimeFromTimeString($randomTime);

                PlanningSuggestion::create([
                    'patient_id' => $patient->id,
                    'suggested_time' => $suggestedDate,
                    'priority' => $this->getRandomPriority(),
                    'validated' => $this->shouldValidate(),
                ]);
            }
        }
    }

    private function getRandomPriority(): string
    {
        // Weighted distribution: 40% normal, 35% low, 25% urgent
        $rand = rand(1, 100);

        if ($rand <= 25) {
            return 'urgent';
        } elseif ($rand <= 60) {
            return 'normal';
        } else {
            return 'low';
        }
    }

    private function shouldValidate(): bool
    {
        // 60% of suggestions should be validated
        return rand(1, 100) <= 60;
    }
}
