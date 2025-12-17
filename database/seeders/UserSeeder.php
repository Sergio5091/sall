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
            ],
            [
                'name' => 'Marc Dubois',
                'email' => 'marc@vrzone.sn',
                'password' => Hash::make('password'),
                'role' => 'promoter',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Oumar Faye',
                'email' => 'oumar@lasergame.sn',
                'password' => Hash::make('password'),
                'role' => 'promoter',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Aïssa Konaté',
                'email' => 'aissa@espace.sn',
                'password' => Hash::make('password'),
                'role' => 'promoter',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ibrahim Sow',
                'email' => 'ibrahim@arcadeparadise.sn',
                'password' => Hash::make('password'),
                'role' => 'promoter',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Khadija Fall',
                'email' => 'khadija@battlearena.sn',
                'password' => Hash::make('password'),
                'role' => 'promoter',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Baba Cissé',
                'email' => 'baba@gaminglounge.sn',
                'password' => Hash::make('password'),
                'role' => 'promoter',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Adama Diop',
                'email' => 'adama@pixelheaven.sn',
                'password' => Hash::make('password'),
                'role' => 'promoter',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Moussa Touré',
                'email' => 'moussa@techgaming.sn',
                'password' => Hash::make('password'),
                'role' => 'promoter',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mariam Samb',
                'email' => 'mariam@elitegaming.sn',
                'password' => Hash::make('password'),
                'role' => 'promoter',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Papa Ndiaye',
                'email' => 'papa@neonarena.sn',
                'password' => Hash::make('password'),
                'role' => 'promoter',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Rokhaya Gueye',
                'email' => 'rokhaya@digitallab.sn',
                'password' => Hash::make('password'),
                'role' => 'promoter',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Cheikh Seck',
                'email' => 'cheikh@futurepod.sn',
                'password' => Hash::make('password'),
                'role' => 'promoter',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Fatoumata Camara',
                'email' => 'fatoumata@rushhour.sn',
                'password' => Hash::make('password'),
                'role' => 'promoter',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Modou Lo',
                'email' => 'modou@gamehub.sn',
                'password' => Hash::make('password'),
                'role' => 'promoter',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Aminata Ka',
                'email' => 'aminata@cyberpalace.sn',
                'password' => Hash::make('password'),
                'role' => 'promoter',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ousmane Ba',
                'email' => 'ousmane@nextlevel.sn',
                'password' => Hash::make('password'),
                'role' => 'promoter',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Yacine Dieng',
                'email' => 'yacine@powerplay.sn',
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

        // Créer des utilisateurs clients pour les graphiques
        $clients = [];
        $firstNames = ['Mohamed', 'Aïssa', 'Ibrahim', 'Fatou', 'Oumar', 'Mariam', 'Baba', 'Khadija', 'Adama', 'Moussa', 'Rokhaya', 'Papa', 'Cheikh', 'Fatoumata', 'Modou', 'Aminata', 'Ousmane', 'Yacine', 'Binta', 'Lamine'];
        $lastNames = ['Sall', 'Ndiaye', 'Ba', 'Fall', 'Diop', 'Touré', 'Sow', 'Gueye', 'Seck', 'Camara', 'Ka', 'Lo', 'Dieng', 'Cissé', 'Konaté', 'Samb', 'Faye', 'Ly', 'Diallo', 'Aidara'];
        
        for ($i = 1; $i <= 50; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $clients[] = [
                'name' => $firstName . ' ' . $lastName,
                'email' => strtolower($firstName . '.' . $lastName . $i . '@client.sn'),
                'password' => Hash::make('password'),
                'role' => 'client',
                'email_verified_at' => now()->subDays(rand(1, 365)), // Dates aléatoires sur l'année
            ];
        }

        foreach ($clients as $client) {
            $existingClient = User::where('email', $client['email'])->first();
            
            if (!$existingClient) {
                User::create($client);
                echo "Client créé: {$client['name']}\n";
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
