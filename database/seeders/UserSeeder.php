<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des utilisateurs promoteurs pour les salles
        $promoters = [
            [
                'name' => 'Alassane Sall',
                'email' => 'alassane@gamezone.sn',
                'password' => Hash::make('password'),
                'role' => 'promoter',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Fatou Ndiaye',
                'email' => 'fatou@cybertech.sn',
                'password' => Hash::make('password'),
                'role' => 'promoter',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mamadou Ba',
                'email' => 'mamadou@retrogaming.sn',
                'password' => Hash::make('password'),
                'role' => 'promoter',
                'email_verified_at' => now(),
            ]
        ];

        foreach ($promoters as $promoter) {
            $existingUser = User::where('email', $promoter['email'])->first();
            
            if (!$existingUser) {
                User::create($promoter);
                echo "Promoteur créé: {$promoter['name']}\n";
            } else {
                echo "Le promoteur {$promoter['name']} existe déjà\n";
            }
        }

        // Créer un utilisateur admin si nécessaire
        $admin = User::where('email', 'admin@sall.sn')->first();
        if (!$admin) {
            User::create([
                'name' => 'Administrateur',
                'email' => 'admin@sall.sn',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
            echo "Administrateur créé\n";
        }

        // Créer un utilisateur client test
        $client = User::where('email', 'client@sall.sn')->first();
        if (!$client) {
            User::create([
                'name' => 'Client Test',
                'email' => 'client@sall.sn',
                'password' => Hash::make('password'),
                'role' => 'client',
                'email_verified_at' => now(),
            ]);
            echo "Client test créé\n";
        }
    }
}
