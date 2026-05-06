<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Account
        User::create([
            'name' => 'Admin',
            'email' => 'admin@cabinet.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Doctor Account
        User::create([
            'name' => 'Dr. Smith',
            'email' => 'dr.smith@cabinet.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
        ]);

        // Patient Accounts
        $patients = [
            ['name' => 'John Doe', 'email' => 'john.doe@cabinet.com'],
            ['name' => 'Marie Dupont', 'email' => 'marie.dupont@cabinet.com'],
            ['name' => 'Pierre Lefevre', 'email' => 'pierre.lefevre@cabinet.com'],
            ['name' => 'Sophie Laurent', 'email' => 'sophie.laurent@cabinet.com'],
            ['name' => 'Antoine Moreau', 'email' => 'antoine.moreau@cabinet.com'],
            ['name' => 'Claire Durand', 'email' => 'claire.durand@cabinet.com'],
            ['name' => 'Luc Bernard', 'email' => 'luc.bernard@cabinet.com'],
            ['name' => 'Isabelle Rousseau', 'email' => 'isabelle.rousseau@cabinet.com'],
            ['name' => 'Marc Fournier', 'email' => 'marc.fournier@cabinet.com'],
            ['name' => 'Nathalie Martin', 'email' => 'nathalie.martin@cabinet.com'],
            ['name' => 'Jacques Petit', 'email' => 'jacques.petit@cabinet.com'],
            ['name' => 'Catherine Blanc', 'email' => 'catherine.blanc@cabinet.com'],
            ['name' => 'François Renard', 'email' => 'francois.renard@cabinet.com'],
            ['name' => 'Monique Leclerc', 'email' => 'monique.leclerc@cabinet.com'],
            ['name' => 'Daniel Mercier', 'email' => 'daniel.mercier@cabinet.com'],
        ];

        foreach ($patients as $patient) {
            User::create([
                'name' => $patient['name'],
                'email' => $patient['email'],
                'password' => Hash::make('password'),
                'role' => 'patient',
                'telephone' => '06' . rand(10000000, 99999999),
                'adresse' => rand(1, 999) . ' Rue ' . ['de la Paix', 'Victor Hugo', 'du Commerce', 'de la République', 'des Halles'][array_rand(['de la Paix', 'Victor Hugo', 'du Commerce', 'de la République', 'des Halles'])],
            ]);
        }
    }
}
