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

        // Patient Account
        User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@cabinet.com',
            'password' => Hash::make('password'),
            'role' => 'patient',
        ]);
    }
}
