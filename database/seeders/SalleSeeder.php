<?php

namespace Database\Seeders;

use App\Models\Salle;
use App\Models\User;
use Illuminate\Database\Seeder;

class SalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer une salle pour chaque utilisateur de type promoteur
        $promoters = User::where('role', 'promoter')->get();
        
        if ($promoters->isEmpty()) {
            // Si aucun promoteur n'existe, créer pour le premier utilisateur
            $user = User::first();
            if ($user) {
                $this->createSalleForUser($user->id);
            }
        } else {
            // Créer une salle pour chaque promoteur
            foreach ($promoters as $promoter) {
                $this->createSalleForUser($promoter->id);
            }
        }
    }

    private function createSalleForUser($userId)
    {
        // Vérifier si l'utilisateur a déjà une salle
        $existingSalle = Salle::where('promoter_id', $userId)->first();
        
        if (!$existingSalle) {
            Salle::create([
                'promoter_id' => $userId,
                'nom' => 'CyberZone Arena',
                'slug' => 'cyberzone-arena-' . $userId,
                'description' => 'La meilleure salle gaming de la région avec équipements dernier cri',
                'adresse' => '123 Rue du Gaming',
                'ville' => 'Paris',
                'pays' => 'France',
                'latitude' => 48.8566,
                'longitude' => 2.3522,
                'capacite' => 50,
                'telephone' => '+33 1 23 45 67 89',
                'email' => 'contact@cyberzone.com',
                'site_web' => 'https://cyberzone.com',
                'statut' => 'actif',
                'prix_heure' => 25.00,
                'prix_jour' => 400.00,
                'wifi' => true,
                'parking' => true,
                'climatisation' => true,
                'accessibilite' => true,
                'actif' => true,
                'note' => 5,
                'nombre_avis' => 128,
                'certifie' => true,
                'description_certification' => 'Certifiée par la Fédération Française de Esport',
                'meta_description' => 'Réservez la CyberZone Arena pour vos événements gaming et tournois esport',
                'meta_keywords' => 'salle gaming, esport, tournoi, Paris, location salle',
                'equipements' => json_encode([
                    'PC Gaming RTX 4090',
                    'Écrans 4K 144Hz',
                    'Casques gaming',
                    'Consoles PlayStation 5',
                    'Consoles Xbox Series X',
                    'Nintendo Switch'
                ]),
                'services' => json_encode([
                    'Bar',
                    'Snacking',
                    'WiFi haut débit',
                    'Surveillance',
                    'Coaching gaming'
                ]),
                'horaires' => json_encode([
                    'lundi' => '10:00-23:00',
                    'mardi' => '10:00-23:00',
                    'mercredi' => '10:00-23:00',
                    'jeudi' => '10:00-23:00',
                    'vendredi' => '10:00-02:00',
                    'samedi' => '10:00-02:00',
                    'dimanche' => '12:00-22:00'
                ]),
                'images' => json_encode([
                    'salle1.jpg',
                    'salle2.jpg',
                    'salle3.jpg'
                ]),
                'reseaux_sociaux' => json_encode([
                    'facebook' => 'https://facebook.com/cyberzone',
                    'twitter' => 'https://twitter.com/cyberzone',
                    'instagram' => 'https://instagram.com/cyberzone'
                ])
            ]);
            
            echo "Salle créée pour l'utilisateur ID: {$userId}\n";
        } else {
            echo "L'utilisateur ID: {$userId} a déjà une salle\n";
        }
    }
}
