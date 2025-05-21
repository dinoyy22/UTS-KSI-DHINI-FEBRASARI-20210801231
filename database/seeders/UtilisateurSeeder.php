<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

class UtilisateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test admin user
        Utilisateur::create([
            'nom' => 'Admin',
            'prenom' => 'User',
            'email' => 'admin@example.com',
            'mot_de_passe' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Make sure this email matches the one in your User model that you're authenticated with
        Utilisateur::create([
            'nom' => 'Test',
            'prenom' => 'User',
            'email' => 'user@example.com',
            'mot_de_passe' => Hash::make('password'),
            'is_admin' => false,
        ]);
    }
}
