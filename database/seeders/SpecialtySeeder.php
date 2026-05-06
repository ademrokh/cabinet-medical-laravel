<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialties = [
            ['name' => 'Cardiology', 'description' => 'Heart and cardiovascular diseases'],
            ['name' => 'Dermatology', 'description' => 'Skin diseases and conditions'],
            ['name' => 'Neurology', 'description' => 'Nervous system disorders'],
            ['name' => 'Pediatrics', 'description' => 'Children medical care'],
            ['name' => 'Ophthalmology', 'description' => 'Eye and vision care'],
            ['name' => 'General Medicine', 'description' => 'General medical consultations'],
            ['name' => 'Orthopedics', 'description' => 'Bone and joint care'],
            ['name' => 'Dentistry', 'description' => 'Dental care'],
        ];

        foreach ($specialties as $specialty) {
            \App\Models\Specialty::create($specialty);
        }
    }
}
