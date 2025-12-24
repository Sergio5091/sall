<?php

namespace Database\Seeders;

use App\Models\Salle;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestSalleStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des salles avec différents statuts pour tester
        // Créer d'abord les promoteurs nécessaires
        $promoters = [
            [
                'name' => 'Promoter Test 1',
                'email' => 'promoter1@test.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Promoter Test 2',
                'email' => 'promoter2@test.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Promoter Test 3',
                'email' => 'promoter3@test.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Promoter Test 4',
                'email' => 'promoter4@test.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        ];

        $createdPromoters = [];
        foreach ($promoters as $promoterData) {
            $user = \App\Models\User::create($promoterData);
            // Assigner le rôle promoter
            $promoterRole = \Spatie\Permission\Models\Role::where('name', 'promoter')->first();
            if ($promoterRole) {
                $user->assignRole($promoterRole);
            }
            $createdPromoters[] = $user;
        }

        $testSalles = [
            [
                'promoter_id' => $createdPromoters[0]->id,
                'nom' => 'Salle Test Active',
                'slug' => 'salle-test-active',
                'description' => 'Salle de test avec statut actif',
                'adresse' => 'Test Address 1',
                'ville' => 'Dakar',
                'code_postal' => '12000',
                'pays' => 'Sénégal',
                'latitude' => 14.6928,
                'longitude' => -17.4467,
                'telephone' => '+221 33 111 11 11',
                'email' => 'test1@example.com',
                'capacite_max' => 25,
                'surface' => 100,
                'prix_heure' => 2000,
                'prix_journee' => 15000,
                'statut' => 'actif',
                'valide' => true,
                'valide_par_admin' => true,
                'validated_at' => now(),
                'horaires' => json_encode([
                    'lundi' => '09:00-22:00',
                    'mardi' => '09:00-22:00',
                    'mercredi' => '09:00-22:00',
                    'jeudi' => '09:00-22:00',
                    'vendredi' => '09:00-23:00',
                    'samedi' => '09:00-23:00',
                    'dimanche' => '10:00-21:00'
                ]),
                'equipements' => json_encode(['PC Gaming', 'WiFi', 'Climatisation']),
                'services' => json_encode(['Bar', 'Parking']),
                'images' => json_encode(['test1.jpg', 'test2.jpg'])
            ],
            [
                'promoter_id' => $createdPromoters[1]->id,
                'nom' => 'Salle Test Inactive',
                'slug' => 'salle-test-inactive',
                'description' => 'Salle de test avec statut inactif',
                'adresse' => 'Test Address 2',
                'ville' => 'Dakar',
                'code_postal' => '12000',
                'pays' => 'Sénégal',
                'latitude' => 14.6928,
                'longitude' => -17.4467,
                'telephone' => '+221 33 222 22 22',
                'email' => 'test2@example.com',
                'capacite_max' => 30,
                'surface' => 120,
                'prix_heure' => 2500,
                'prix_journee' => 18000,
                'statut' => 'inactif',
                'valide' => true,
                'valide_par_admin' => true,
                'validated_at' => now(),
                'horaires' => json_encode([
                    'lundi' => '09:00-22:00',
                    'mardi' => '09:00-22:00',
                    'mercredi' => '09:00-22:00',
                    'jeudi' => '09:00-22:00',
                    'vendredi' => '09:00-23:00',
                    'samedi' => '09:00-23:00',
                    'dimanche' => '10:00-21:00'
                ]),
                'equipements' => json_encode(['PC Gaming', 'WiFi', 'Climatisation']),
                'services' => json_encode(['Bar', 'Parking']),
                'images' => json_encode(['test3.jpg', 'test4.jpg'])
            ],
            [
                'promoter_id' => $createdPromoters[2]->id,
                'nom' => 'Salle Test En Attente',
                'slug' => 'salle-test-attente',
                'description' => 'Salle de test en attente de validation',
                'adresse' => 'Test Address 3',
                'ville' => 'Dakar',
                'code_postal' => '12000',
                'pays' => 'Sénégal',
                'latitude' => 14.6928,
                'longitude' => -17.4467,
                'telephone' => '+221 33 333 33 33',
                'email' => 'test3@example.com',
                'capacite_max' => 20,
                'surface' => 80,
                'prix_heure' => 1500,
                'prix_journee' => 12000,
                'statut' => 'actif',
                'valide' => false,
                'valide_par_admin' => false,
                'validated_at' => null,
                'horaires' => json_encode([
                    'lundi' => '09:00-22:00',
                    'mardi' => '09:00-22:00',
                    'mercredi' => '09:00-22:00',
                    'jeudi' => '09:00-22:00',
                    'vendredi' => '09:00-23:00',
                    'samedi' => '09:00-23:00',
                    'dimanche' => '10:00-21:00'
                ]),
                'equipements' => json_encode(['PC Gaming', 'WiFi', 'Climatisation']),
                'services' => json_encode(['Bar', 'Parking']),
                'images' => json_encode(['test5.jpg', 'test6.jpg'])
            ],
            [
                'promoter_id' => $createdPromoters[3]->id,
                'nom' => 'Salle Test Validée Inactive',
                'slug' => 'salle-test-validee-inactive',
                'description' => 'Salle validée mais désactivée par sous-admin',
                'adresse' => 'Test Address 4',
                'ville' => 'Dakar',
                'code_postal' => '12000',
                'pays' => 'Sénégal',
                'latitude' => 14.6928,
                'longitude' => -17.4467,
                'telephone' => '+221 33 444 44 44',
                'email' => 'test4@example.com',
                'capacite_max' => 35,
                'surface' => 140,
                'prix_heure' => 3000,
                'prix_journee' => 22000,
                'statut' => 'inactif',
                'valide' => true,
                'valide_par_admin' => true,
                'validated_at' => now(),
                'horaires' => json_encode([
                    'lundi' => '09:00-22:00',
                    'mardi' => '09:00-22:00',
                    'mercredi' => '09:00-22:00',
                    'jeudi' => '09:00-22:00',
                    'vendredi' => '09:00-23:00',
                    'samedi' => '09:00-23:00',
                    'dimanche' => '10:00-21:00'
                ]),
                'equipements' => json_encode(['PC Gaming', 'WiFi', 'Climatisation']),
                'services' => json_encode(['Bar', 'Parking']),
                'images' => json_encode(['test7.jpg', 'test8.jpg'])
            ]
        ];

        foreach ($testSalles as $salle) {
            Salle::create($salle);
        }

        $this->command->info('Salles de test créées avec différents statuts !');
    }
}
