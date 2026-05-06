<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = [
            ['name' => 'Dr. Jean Dupont', 'specialty_id' => 1, 'biography' => 'Experienced cardiologist with 20 years of practice'],
            ['name' => 'Dr. Marie Martin', 'specialty_id' => 2, 'biography' => 'Dermatology specialist focusing on skin health'],
            ['name' => 'Dr. Pierre Bernard', 'specialty_id' => 3, 'biography' => 'Neurologist with expertise in neurological disorders'],
            ['name' => 'Dr. Sophie Laurent', 'specialty_id' => 4, 'biography' => 'Pediatrician dedicated to children\'s health'],
            ['name' => 'Dr. Antoine Moreau', 'specialty_id' => 5, 'biography' => 'Ophthalmologist providing comprehensive eye care'],
            ['name' => 'Dr. Claire Durand', 'specialty_id' => 6, 'biography' => 'General physician offering holistic medical care'],
        ];

        foreach ($doctors as $doctorData) {
            $userData = [
                'name' => $doctorData['name'],
                'email' => strtolower(str_replace(' ', '.', $doctorData['name'])) . '@cabinet-medical.fr',
                'password' => bcrypt('password'),
                'role' => 'doctor',
                'telephone' => '01' . rand(20000000, 99999999),
            ];

            $user = \App\Models\User::create($userData);

            \App\Models\Doctor::create([
                'user_id' => $user->id,
                'specialty_id' => $doctorData['specialty_id'],
                'biography' => $doctorData['biography'],
                'available' => true,
            ]);

            // Create weekly availabilities (Monday to Friday, 9 AM to 5 PM)
            for ($day = 1; $day <= 5; $day++) {
                \App\Models\Availability::create([
                    'doctor_id' => \App\Models\Doctor::where('user_id', $user->id)->first()->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                ]);
            }
        }
    }
}
