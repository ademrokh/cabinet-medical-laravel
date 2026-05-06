<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin users
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@cabinet-medical.fr',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'telephone' => '0123456789',
            'adresse' => '1 Avenue de la République, Paris 75001',
        ]);

        User::create([
            'name' => 'Marie Leclerc',
            'email' => 'admin@cabinet.fr',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'telephone' => '0134567890',
            'adresse' => '50 Boulevard Saint-Michel, Paris 75005',
        ]);

        // Secretary users
        User::create([
            'name' => 'Sophie Martin',
            'email' => 'secretary@cabinet-medical.fr',
            'password' => bcrypt('password'),
            'role' => 'secretary',
            'telephone' => '0145678901',
            'adresse' => '15 Rue de Rivoli, Paris 75004',
        ]);

        User::create([
            'name' => 'Nathalie Dubois',
            'email' => 'secretary2@cabinet.fr',
            'password' => bcrypt('password'),
            'role' => 'secretary',
            'telephone' => '0156789012',
            'adresse' => '200 Avenue des Champs-Élysées, Paris 75008',
        ]);

        // Create sample patients with realistic French names
        $patientNames = [
            ['name' => 'Jean Dupont', 'email' => 'jean.dupont@email.com'],
            ['name' => 'Marie Bernard', 'email' => 'marie.bernard@email.com'],
            ['name' => 'Pierre Moreau', 'email' => 'pierre.moreau@email.com'],
            ['name' => 'Claire Fournier', 'email' => 'claire.fournier@email.com'],
            ['name' => 'Michel Thomas', 'email' => 'michel.thomas@email.com'],
            ['name' => 'Anne Robert', 'email' => 'anne.robert@email.com'],
            ['name' => 'François Laurent', 'email' => 'francois.laurent@email.com'],
            ['name' => 'Isabelle Garnier', 'email' => 'isabelle.garnier@email.com'],
            ['name' => 'Marc Rousseau', 'email' => 'marc.rousseau@email.com'],
            ['name' => 'Valérie Blanc', 'email' => 'valerie.blanc@email.com'],
            ['name' => 'Christian Bonnet', 'email' => 'christian.bonnet@email.com'],
            ['name' => 'Sylvie Renard', 'email' => 'sylvie.renard@email.com'],
            ['name' => 'Luc Gérard', 'email' => 'luc.gerard@email.com'],
            ['name' => 'Nicole Vincent', 'email' => 'nicole.vincent@email.com'],
            ['name' => 'Philippe Mercier', 'email' => 'philippe.mercier@email.com'],
        ];

        $cities = ['Paris', 'Lyon', 'Marseille', 'Nice', 'Toulouse', 'Bordeaux', 'Lille', 'Strasbourg', 'Nantes', 'Cannes'];

        foreach ($patientNames as $patient) {
            User::create([
                'name' => $patient['name'],
                'email' => $patient['email'],
                'password' => bcrypt('password'),
                'role' => 'patient',
                'date_naissance' => \Carbon\Carbon::now()->subYears(rand(18, 85))->format('Y-m-d'),
                'telephone' => '0' . rand(1, 9) . rand(10000000, 99999999),
                'adresse' => rand(1, 250) . ' ' . ['Rue', 'Avenue', 'Boulevard', 'Place'][rand(0, 3)] . ' de ' . $cities[array_rand($cities)],
            ]);
        }

        // Run seeders
        $this->call([
            SpecialtySeeder::class,
            DoctorSeeder::class,
        ]);
    }
}
