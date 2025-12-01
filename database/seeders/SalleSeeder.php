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
        // Créer des salles avec des données réelles du Sénégal (adaptées aux colonnes existantes)
        $this->createRealSalles();
    }

    private function createRealSalles()
    {
        $salles = [
            [
                'promoter_id' => 1,
                'nom' => 'GameZone Dakar',
                'description' => 'Salle gaming moderne à Plateau avec équipements dernier cri pour tournois esport et événements gaming',
                'adresse' => 'Rue 10x4, Angle Avenue Cheikh Anta Diop',
                'ville' => 'Dakar',
                'code_postal' => '12000',
                'pays' => 'Sénégal',
                'latitude' => 14.6928,
                'longitude' => -17.4467,
                'telephone' => '+221 33 123 45 67',
                'email' => 'contact@gamezone.sn',
                'site_web' => 'https://gamezone.sn',
                'capacite_max' => 60,
                'surface' => 250,
                'prix_heure' => 5000,
                'prix_journee' => 35000,
                'image_url' => 'https://picsum.photos/seed/gamezone/800/600.jpg',
                'statut' => 'actif',
                'valide' => true,
                'horaires' => json_encode([
                    'lundi' => '10:00-23:00',
                    'mardi' => '10:00-23:00',
                    'mercredi' => '10:00-23:00',
                    'jeudi' => '10:00-23:00',
                    'vendredi' => '10:00-02:00',
                    'samedi' => '10:00-02:00',
                    'dimanche' => '12:00-22:00'
                ]),
                'equipements' => json_encode([
                    'PC Gaming RTX 4090',
                    'Écrans 4K 144Hz',
                    'Casques gaming',
                    'Consoles PlayStation 5',
                    'Consoles Xbox Series X',
                    'Nintendo Switch',
                    'Casques VR'
                ]),
                'services' => json_encode([
                    'Bar',
                    'Snacking',
                    'WiFi haut débit',
                    'Surveillance',
                    'Coaching gaming',
                    'Parking',
                    'Climatisation'
                ]),
                'images' => json_encode([
                    'gamezone1.jpg',
                    'gamezone2.jpg',
                    'gamezone3.jpg',
                    'gamezone4.jpg'
                ])
            ],
            [
                'promoter_id' => 2,
                'nom' => 'CyberTech Pikine',
                'description' => 'Espace gaming et technologie à Pikine avec VR, PC gaming et consoles pour tous les niveaux',
                'adresse' => 'Boulevard de la Liberation, Quartier Guédiawaye',
                'ville' => 'Pikine',
                'code_postal' => '12000',
                'pays' => 'Sénégal',
                'latitude' => 14.7510,
                'longitude' => -17.3986,
                'telephone' => '+221 33 987 65 43',
                'email' => 'info@cybertech.sn',
                'site_web' => 'https://cybertech.sn',
                'capacite_max' => 40,
                'surface' => 180,
                'prix_heure' => 3500,
                'prix_journee' => 25000,
                'image_url' => 'https://picsum.photos/seed/cybertech/800/600.jpg',
                'statut' => 'actif',
                'valide' => true,
                'horaires' => json_encode([
                    'lundi' => '09:00-22:00',
                    'mardi' => '09:00-22:00',
                    'mercredi' => '09:00-22:00',
                    'jeudi' => '09:00-22:00',
                    'vendredi' => '09:00-23:00',
                    'samedi' => '09:00-23:00',
                    'dimanche' => '10:00-21:00'
                ]),
                'equipements' => json_encode([
                    'PC Gaming RTX 4070',
                    'Casques VR Oculus',
                    'Consoles PlayStation 5',
                    'Consoles Xbox Series X',
                    'Machines Arcade'
                ]),
                'services' => json_encode([
                    'Snack Bar',
                    'Restaurant',
                    'WiFi gratuit',
                    'Parking',
                    'Climatisation'
                ]),
                'images' => json_encode([
                    'cybertech1.jpg',
                    'cybertech2.jpg',
                    'cybertech3.jpg'
                ])
            ],
            [
                'promoter_id' => 3,
                'nom' => 'Retro Gaming Thiès',
                'description' => 'Salle spécialisée dans le rétro-gaming avec consoles vintage, arcade classics et ambiance années 90',
                'adresse' => 'Avenue Lamine Guèye, Centre ville',
                'ville' => 'Thiès',
                'code_postal' => '23000',
                'pays' => 'Sénégal',
                'latitude' => 14.7759,
                'longitude' => -16.9256,
                'telephone' => '+221 33 456 78 90',
                'email' => 'contact@retrogaming.sn',
                'site_web' => 'https://retrogaming.sn',
                'capacite_max' => 30,
                'surface' => 150,
                'prix_heure' => 2500,
                'prix_journee' => 18000,
                'image_url' => 'https://picsum.photos/seed/retro/800/600.jpg',
                'statut' => 'actif',
                'valide' => true,
                'horaires' => json_encode([
                    'lundi' => '14:00-22:00',
                    'mardi' => '14:00-22:00',
                    'mercredi' => '14:00-22:00',
                    'jeudi' => '14:00-22:00',
                    'vendredi' => '14:00-23:00',
                    'samedi' => '12:00-23:00',
                    'dimanche' => '12:00-21:00'
                ]),
                'equipements' => json_encode([
                    'Machines Arcade originales',
                    'Consoles Retro (NES, SNES, Mega Drive)',
                    'Flippers',
                    'Tables de billard',
                    'Consoles PlayStation 1 et 2',
                    'Nintendo 64'
                ]),
                'services' => json_encode([
                    'Bar',
                    'Terrasse',
                    'WiFi gratuit',
                    'Parking',
                    'Climatisation'
                ]),
                'images' => json_encode([
                    'retro1.jpg',
                    'retro2.jpg',
                    'retro3.jpg',
                    'retro4.jpg',
                    'retro5.jpg'
                ])
            ]
        ];

        foreach ($salles as $salleData) {
            // Vérifier si la salle existe déjà par le nom
            $existingSalle = Salle::where('nom', $salleData['nom'])->first();
            
            if (!$existingSalle) {
                Salle::create($salleData);
                echo "Salle créée: {$salleData['nom']}\n";
            } else {
                echo "La salle {$salleData['nom']} existe déjà\n";
            }
        }
    }
}
