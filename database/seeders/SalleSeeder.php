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
                'slug' => 'gamezone-dakar',
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
                'slug' => 'cybertech-pikine',
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
                'slug' => 'retro-gaming-thies',
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
            ],
            [
                'promoter_id' => 4,
                'nom' => 'VR Zone Saint-Louis',
                'slug' => 'vr-zone-saint-louis',
                'description' => 'Centre spécialisé réalité virtuelle avec casques VR dernier génération et espace e-sport',
                'adresse' => 'Rue Blanchot, Quartier Nord',
                'ville' => 'Saint-Louis',
                'code_postal' => '25000',
                'pays' => 'Sénégal',
                'latitude' => 16.0186,
                'longitude' => -16.3715,
                'telephone' => '+221 33 876 54 32',
                'email' => 'contact@vrzone.sn',
                'site_web' => 'https://vrzone.sn',
                'capacite_max' => 35,
                'surface' => 200,
                'prix_heure' => 4000,
                'prix_journee' => 28000,
                'image_url' => 'https://picsum.photos/seed/vrzone/800/600.jpg',
                'statut' => 'actif',
                'valide' => true,
                'horaires' => json_encode([
                    'lundi' => '10:00-22:00',
                    'mardi' => '10:00-22:00',
                    'mercredi' => '10:00-22:00',
                    'jeudi' => '10:00-22:00',
                    'vendredi' => '10:00-23:00',
                    'samedi' => '10:00-23:00',
                    'dimanche' => '12:00-21:00'
                ]),
                'equipements' => json_encode([
                    'Casques VR Quest 2',
                    'PC Gaming RTX 4080',
                    'Écrans 4K 144Hz',
                    'Casques gaming',
                    'Consoles PlayStation 5',
                    'Consoles Xbox Series X'
                ]),
                'services' => json_encode([
                    'Bar',
                    'Snacking',
                    'WiFi haut débit',
                    'Surveillance',
                    'Parking',
                    'Climatisation'
                ]),
                'images' => json_encode([
                    'vrzone1.jpg',
                    'vrzone2.jpg',
                    'vrzone3.jpg'
                ])
            ],
            [
                'promoter_id' => 5,
                'nom' => 'Laser Game Kaolack',
                'slug' => 'laser-game-kaolack',
                'description' => 'Complexe laser game et gaming avec arène multijoueur et espace détente',
                'adresse' => 'Avenue Malick Sy',
                'ville' => 'Kaolack',
                'code_postal' => '24000',
                'pays' => 'Sénégal',
                'latitude' => 14.1466,
                'longitude' => -16.2518,
                'telephone' => '+221 33 765 43 21',
                'email' => 'info@lasergame.sn',
                'site_web' => 'https://lasergame.sn',
                'capacite_max' => 50,
                'surface' => 300,
                'prix_heure' => 3000,
                'prix_journee' => 20000,
                'image_url' => 'https://picsum.photos/seed/lasergame/800/600.jpg',
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
                    'Équipements Laser Game',
                    'PC Gaming RTX 4060',
                    'Consoles PlayStation 5',
                    'Consoles Xbox Series X',
                    'Nintendo Switch',
                    'Tables de billard'
                ]),
                'services' => json_encode([
                    'Bar',
                    'Restaurant',
                    'WiFi gratuit',
                    'Parking',
                    'Climatisation',
                    'Vestiaires'
                ]),
                'images' => json_encode([
                    'lasergame1.jpg',
                    'lasergame2.jpg',
                    'lasergame3.jpg',
                    'lasergame4.jpg'
                ])
            ],
            [
                'promoter_id' => 6,
                'nom' => 'E-Space Mbour',
                'slug' => 'e-space-mbour',
                'description' => 'Espace gaming moderne et e-sport à Mbour avec tournois réguliers et coaching',
                'adresse' => 'Boulevard du Président Lamine Gueye',
                'ville' => 'Mbour',
                'code_postal' => '21000',
                'pays' => 'Sénégal',
                'latitude' => 14.4250,
                'longitude' => -16.9750,
                'telephone' => '+221 33 654 32 10',
                'email' => 'contact@espace.sn',
                'site_web' => 'https://espace.sn',
                'capacite_max' => 45,
                'surface' => 220,
                'prix_heure' => 3500,
                'prix_journee' => 25000,
                'image_url' => 'https://picsum.photos/seed/espace/800/600.jpg',
                'statut' => 'actif',
                'valide' => true,
                'horaires' => json_encode([
                    'lundi' => '10:00-22:00',
                    'mardi' => '10:00-22:00',
                    'mercredi' => '10:00-22:00',
                    'jeudi' => '10:00-22:00',
                    'vendredi' => '10:00-23:00',
                    'samedi' => '10:00-23:00',
                    'dimanche' => '12:00-21:00'
                ]),
                'equipements' => json_encode([
                    'PC Gaming RTX 4070',
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
                    'Coaching gaming',
                    'Parking',
                    'Climatisation'
                ]),
                'images' => json_encode([
                    'espace1.jpg',
                    'espace2.jpg',
                    'espace3.jpg'
                ])
            ],
            [
                'promoter_id' => 7,
                'nom' => 'Arcade Paradise Ziguinchor',
                'slug' => 'arcade-paradise-ziguinchor',
                'description' => 'Salle arcade et gaming avec machines classiques et équipements modernes',
                'adresse' => 'Rue de la République',
                'ville' => 'Ziguinchor',
                'code_postal' => '26000',
                'pays' => 'Sénégal',
                'latitude' => 12.5589,
                'longitude' => -16.2727,
                'telephone' => '+221 33 543 21 09',
                'email' => 'info@arcadeparadise.sn',
                'site_web' => 'https://arcadeparadise.sn',
                'capacite_max' => 40,
                'surface' => 190,
                'prix_heure' => 2500,
                'prix_journee' => 18000,
                'image_url' => 'https://picsum.photos/seed/arcade/800/600.jpg',
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
                    'Machines Arcade',
                    'PC Gaming RTX 4060',
                    'Consoles PlayStation 5',
                    'Consoles Xbox Series X',
                    'Flippers',
                    'Tables de baby-foot'
                ]),
                'services' => json_encode([
                    'Bar',
                    'Snacking',
                    'WiFi gratuit',
                    'Parking',
                    'Climatisation'
                ]),
                'images' => json_encode([
                    'arcade1.jpg',
                    'arcade2.jpg',
                    'arcade3.jpg',
                    'arcade4.jpg'
                ])
            ],
            [
                'promoter_id' => 8,
                'nom' => 'Battle Arena Touba',
                'slug' => 'battle-arena-touba',
                'description' => 'Arène e-sport et gaming à Touba avec équipements professionnels et tournois',
                'adresse' => 'Avenue Cheikh Ibrahima Niass',
                'ville' => 'Touba',
                'code_postal' => '22000',
                'pays' => 'Sénégal',
                'latitude' => 14.8456,
                'longitude' => -15.8834,
                'telephone' => '+221 33 432 10 98',
                'email' => 'contact@battlearena.sn',
                'site_web' => 'https://battlearena.sn',
                'capacite_max' => 55,
                'surface' => 280,
                'prix_heure' => 4500,
                'prix_journee' => 32000,
                'image_url' => 'https://picsum.photos/seed/battle/800/600.jpg',
                'statut' => 'actif',
                'valide' => true,
                'horaires' => json_encode([
                    'lundi' => '10:00-22:00',
                    'mardi' => '10:00-22:00',
                    'mercredi' => '10:00-22:00',
                    'jeudi' => '10:00-22:00',
                    'vendredi' => '10:00-23:00',
                    'samedi' => '10:00-23:00',
                    'dimanche' => '12:00-21:00'
                ]),
                'equipements' => json_encode([
                    'PC Gaming RTX 4080',
                    'Écrans 4K 144Hz',
                    'Casques gaming',
                    'Consoles PlayStation 5',
                    'Consoles Xbox Series X',
                    'Nintendo Switch',
                    'Casques VR'
                ]),
                'services' => json_encode([
                    'Bar',
                    'Restaurant',
                    'WiFi haut débit',
                    'Surveillance',
                    'Coaching gaming',
                    'Parking',
                    'Climatisation',
                    'Streaming'
                ]),
                'images' => json_encode([
                    'battle1.jpg',
                    'battle2.jpg',
                    'battle3.jpg',
                    'battle4.jpg'
                ])
            ],
            [
                'promoter_id' => 9,
                'nom' => 'Gaming Lounge Diourbel',
                'slug' => 'gaming-lounge-diourbel',
                'description' => 'Lounge gaming moderne avec espace détente et équipements dernier cri',
                'adresse' => 'Boulevard du Général de Gaulle',
                'ville' => 'Diourbel',
                'code_postal' => '27000',
                'pays' => 'Sénégal',
                'latitude' => 14.6548,
                'longitude' => -16.2356,
                'telephone' => '+221 33 321 09 87',
                'email' => 'info@gaminglounge.sn',
                'site_web' => 'https://gaminglounge.sn',
                'capacite_max' => 38,
                'surface' => 175,
                'prix_heure' => 3000,
                'prix_journee' => 22000,
                'image_url' => 'https://picsum.photos/seed/lounge/800/600.jpg',
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
                    'PC Gaming RTX 4060',
                    'Écrans 4K 144Hz',
                    'Casques gaming',
                    'Consoles PlayStation 5',
                    'Consoles Xbox Series X',
                    'Nintendo Switch'
                ]),
                'services' => json_encode([
                    'Bar',
                    'Snacking',
                    'WiFi gratuit',
                    'Parking',
                    'Climatisation',
                    'Espace détente'
                ]),
                'images' => json_encode([
                    'lounge1.jpg',
                    'lounge2.jpg',
                    'lounge3.jpg'
                ])
            ],
            [
                'promoter_id' => 10,
                'nom' => 'Pixel Heaven Louga',
                'slug' => 'pixel-heaven-louga',
                'description' => 'Espace gaming familial et professionnel avec activités pour tous les âges',
                'adresse' => 'Avenue du 4 Mars',
                'ville' => 'Louga',
                'code_postal' => '28000',
                'pays' => 'Sénégal',
                'latitude' => 15.6184,
                'longitude' => -16.0896,
                'telephone' => '+221 33 210 98 76',
                'email' => 'contact@pixelheaven.sn',
                'site_web' => 'https://pixelheaven.sn',
                'capacite_max' => 42,
                'surface' => 210,
                'prix_heure' => 2800,
                'prix_journee' => 19000,
                'image_url' => 'https://picsum.photos/seed/pixel/800/600.jpg',
                'statut' => 'actif',
                'valide' => true,
                'horaires' => json_encode([
                    'lundi' => '10:00-22:00',
                    'mardi' => '10:00-22:00',
                    'mercredi' => '10:00-22:00',
                    'jeudi' => '10:00-22:00',
                    'vendredi' => '10:00-23:00',
                    'samedi' => '10:00-23:00',
                    'dimanche' => '12:00-21:00'
                ]),
                'equipements' => json_encode([
                    'PC Gaming RTX 4060',
                    'Consoles PlayStation 5',
                    'Consoles Xbox Series X',
                    'Nintendo Switch',
                    'Machines Arcade',
                    'Tables de billard'
                ]),
                'services' => json_encode([
                    'Bar',
                    'Snacking',
                    'WiFi gratuit',
                    'Parking',
                    'Climatisation',
                    'Espace familial'
                ]),
                'images' => json_encode([
                    'pixel1.jpg',
                    'pixel2.jpg',
                    'pixel3.jpg',
                    'pixel4.jpg'
                ])
            ],
            [
                'promoter_id' => 11,
                'nom' => 'Tech Gaming Fatick',
                'slug' => 'tech-gaming-fatick',
                'description' => 'Centre gaming et technologie avec focus sur e-sport et développement',
                'adresse' => 'Rue du Marché',
                'ville' => 'Fatick',
                'code_postal' => '26000',
                'pays' => 'Sénégal',
                'latitude' => 14.3219,
                'longitude' => -16.3916,
                'telephone' => '+221 33 109 87 65',
                'email' => 'info@techgaming.sn',
                'site_web' => 'https://techgaming.sn',
                'capacite_max' => 36,
                'surface' => 165,
                'prix_heure' => 3200,
                'prix_journee' => 23000,
                'image_url' => 'https://picsum.photos/seed/tech/800/600.jpg',
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
                    'Coaching gaming',
                    'Parking',
                    'Climatisation',
                    'Formation'
                ]),
                'images' => json_encode([
                    'tech1.jpg',
                    'tech2.jpg',
                    'tech3.jpg'
                ])
            ],
            [
                'promoter_id' => 12,
                'nom' => 'Elite Gaming Kédougou',
                'slug' => 'elite-gaming-kedougou',
                'description' => 'Salle gaming premium avec équipements haut de gamme et tournois professionnels',
                'adresse' => 'Avenue du Mali',
                'ville' => 'Kédougou',
                'code_postal' => '29000',
                'pays' => 'Sénégal',
                'latitude' => 12.5456,
                'longitude' => -12.2218,
                'telephone' => '+221 33 098 76 54',
                'email' => 'contact@elitegaming.sn',
                'site_web' => 'https://elitegaming.sn',
                'capacite_max' => 48,
                'surface' => 240,
                'prix_heure' => 4000,
                'prix_journee' => 28000,
                'image_url' => 'https://picsum.photos/seed/elite/800/600.jpg',
                'statut' => 'actif',
                'valide' => true,
                'horaires' => json_encode([
                    'lundi' => '10:00-22:00',
                    'mardi' => '10:00-22:00',
                    'mercredi' => '10:00-22:00',
                    'jeudi' => '10:00-22:00',
                    'vendredi' => '10:00-23:00',
                    'samedi' => '10:00-23:00',
                    'dimanche' => '12:00-21:00'
                ]),
                'equipements' => json_encode([
                    'PC Gaming RTX 4080',
                    'Écrans 4K 144Hz',
                    'Casques gaming',
                    'Consoles PlayStation 5',
                    'Consoles Xbox Series X',
                    'Nintendo Switch',
                    'Casques VR'
                ]),
                'services' => json_encode([
                    'Bar',
                    'Restaurant',
                    'WiFi haut débit',
                    'Surveillance',
                    'Coaching gaming',
                    'Parking',
                    'Climatisation',
                    'Streaming'
                ]),
                'images' => json_encode([
                    'elite1.jpg',
                    'elite2.jpg',
                    'elite3.jpg',
                    'elite4.jpg'
                ])
            ],
            [
                'promoter_id' => 13,
                'nom' => 'Neon Arena Tambacounda',
                'slug' => 'neon-arena-tambacounda',
                'description' => 'Arène gaming moderne avec néons et ambiance festive pour tournois et événements',
                'adresse' => 'Boulevard de l\'Indépendance',
                'ville' => 'Tambacounda',
                'code_postal' => '31000',
                'pays' => 'Sénégal',
                'latitude' => 13.7707,
                'longitude' => -13.6673,
                'telephone' => '+221 33 987 65 43',
                'email' => 'info@neonarena.sn',
                'site_web' => 'https://neonarena.sn',
                'capacite_max' => 52,
                'surface' => 260,
                'prix_heure' => 3800,
                'prix_journee' => 27000,
                'image_url' => 'https://picsum.photos/seed/neon/800/600.jpg',
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
                    'Écrans 4K 144Hz',
                    'Casques gaming',
                    'Consoles PlayStation 5',
                    'Consoles Xbox Series X',
                    'Nintendo Switch',
                    'Système néons'
                ]),
                'services' => json_encode([
                    'Bar',
                    'Snacking',
                    'WiFi haut débit',
                    'Surveillance',
                    'Parking',
                    'Climatisation',
                    'Éclairage néons'
                ]),
                'images' => json_encode([
                    'neon1.jpg',
                    'neon2.jpg',
                    'neon3.jpg',
                    'neon4.jpg'
                ])
            ],
            [
                'promoter_id' => 14,
                'nom' => 'Digital Lab Matam',
                'slug' => 'digital-lab-matam',
                'description' => 'Laboratoire digital et gaming avec focus sur apprentissage et compétition',
                'adresse' => 'Avenue du 23 Juin',
                'ville' => 'Matam',
                'code_postal' => '32000',
                'pays' => 'Sénégal',
                'latitude' => 15.6584,
                'longitude' => -13.4567,
                'telephone' => '+221 33 876 54 32',
                'email' => 'contact@digitallab.sn',
                'site_web' => 'https://digitallab.sn',
                'capacite_max' => 34,
                'surface' => 155,
                'prix_heure' => 3000,
                'prix_journee' => 21000,
                'image_url' => 'https://picsum.photos/seed/digital/800/600.jpg',
                'statut' => 'actif',
                'valide' => true,
                'horaires' => json_encode([
                    'lundi' => '10:00-22:00',
                    'mardi' => '10:00-22:00',
                    'mercredi' => '10:00-22:00',
                    'jeudi' => '10:00-22:00',
                    'vendredi' => '10:00-23:00',
                    'samedi' => '10:00-23:00',
                    'dimanche' => '12:00-21:00'
                ]),
                'equipements' => json_encode([
                    'PC Gaming RTX 4060',
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
                    'Coaching gaming',
                    'Parking',
                    'Climatisation',
                    'Formation'
                ]),
                'images' => json_encode([
                    'digital1.jpg',
                    'digital2.jpg',
                    'digital3.jpg'
                ])
            ],
            [
                'promoter_id' => 15,
                'nom' => 'Future Pod Podor',
                'slug' => 'future-pod-podor',
                'description' => 'Espace gaming futuriste avec VR et technologies émergentes',
                'adresse' => 'Rue du Fleuve',
                'ville' => 'Podor',
                'code_postal' => '34000',
                'pays' => 'Sénégal',
                'latitude' => 16.5456,
                'longitude' => -14.9654,
                'telephone' => '+221 33 765 43 21',
                'email' => 'info@futurepod.sn',
                'site_web' => 'https://futurepod.sn',
                'capacite_max' => 40,
                'surface' => 195,
                'prix_heure' => 3500,
                'prix_journee' => 25000,
                'image_url' => 'https://picsum.photos/seed/future/800/600.jpg',
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
                    'Casques VR Quest 2',
                    'PC Gaming RTX 4070',
                    'Écrans 4K 144Hz',
                    'Casques gaming',
                    'Consoles PlayStation 5',
                    'Consoles Xbox Series X'
                ]),
                'services' => json_encode([
                    'Bar',
                    'Snacking',
                    'WiFi haut débit',
                    'Surveillance',
                    'Parking',
                    'Climatisation',
                    'VR expériences'
                ]),
                'images' => json_encode([
                    'future1.jpg',
                    'future2.jpg',
                    'future3.jpg',
                    'future4.jpg'
                ])
            ],
            [
                'promoter_id' => 16,
                'nom' => 'Rush Hour Linguère',
                'slug' => 'rush-hour-linguere',
                'description' => 'Salle gaming dynamique avec focus sur speed gaming et compétitions rapides',
                'adresse' => 'Avenue du Sahel',
                'ville' => 'Linguère',
                'code_postal' => '35000',
                'pays' => 'Sénégal',
                'latitude' => 15.3734,
                'longitude' => -15.1234,
                'telephone' => '+221 33 654 32 10',
                'email' => 'info@rushhour.sn',
                'site_web' => 'https://rushhour.sn',
                'capacite_max' => 44,
                'surface' => 205,
                'prix_heure' => 3200,
                'prix_journee' => 23000,
                'image_url' => 'https://picsum.photos/seed/rush/800/600.jpg',
                'statut' => 'actif',
                'valide' => true,
                'horaires' => json_encode([
                    'lundi' => '10:00-22:00',
                    'mardi' => '10:00-22:00',
                    'mercredi' => '10:00-22:00',
                    'jeudi' => '10:00-22:00',
                    'vendredi' => '10:00-23:00',
                    'samedi' => '10:00-23:00',
                    'dimanche' => '12:00-21:00'
                ]),
                'equipements' => json_encode([
                    'PC Gaming RTX 4060',
                    'Écrans 4K 144Hz',
                    'Casques gaming',
                    'Consoles PlayStation 5',
                    'Consoles Xbox Series X',
                    'Nintendo Switch'
                ]),
                'services' => json_encode([
                    'Bar',
                    'Snacking',
                    'WiFi gratuit',
                    'Parking',
                    'Climatisation',
                    'Compétitions'
                ]),
                'images' => json_encode([
                    'rush1.jpg',
                    'rush2.jpg',
                    'rush3.jpg'
                ])
            ],
            [
                'promoter_id' => 17,
                'nom' => 'Game Hub Bakel',
                'slug' => 'game-hub-bakel',
                'description' => 'Hub gaming communautaire avec espace coworking et activités sociales',
                'adresse' => 'Rue du Commerce',
                'ville' => 'Bakel',
                'code_postal' => '38000',
                'pays' => 'Sénégal',
                'latitude' => 14.8765,
                'longitude' => -12.8765,
                'telephone' => '+221 33 543 21 09',
                'email' => 'info@gamehub.sn',
                'site_web' => 'https://gamehub.sn',
                'capacite_max' => 46,
                'surface' => 215,
                'prix_heure' => 3000,
                'prix_journee' => 22000,
                'image_url' => 'https://picsum.photos/seed/hub/800/600.jpg',
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
                    'PC Gaming RTX 4060',
                    'Consoles PlayStation 5',
                    'Consoles Xbox Series X',
                    'Nintendo Switch',
                    'Machines Arcade',
                    'Tables de billard'
                ]),
                'services' => json_encode([
                    'Bar',
                    'Snacking',
                    'WiFi gratuit',
                    'Parking',
                    'Climatisation',
                    'Espace coworking'
                ]),
                'images' => json_encode([
                    'hub1.jpg',
                    'hub2.jpg',
                    'hub3.jpg',
                    'hub4.jpg'
                ])
            ],
            [
                'promoter_id' => 18,
                'nom' => 'Cyber Palace Kaffrine',
                'slug' => 'cyber-palace-kaffrine',
                'description' => 'Palais cyber moderne avec équipements premium et service client haut de gamme',
                'adresse' => 'Boulevard du Centenaire',
                'ville' => 'Kaffrine',
                'code_postal' => '36000',
                'pays' => 'Sénégal',
                'latitude' => 14.1098,
                'longitude' => -15.5432,
                'telephone' => '+221 33 432 10 98',
                'email' => 'info@cyberpalace.sn',
                'site_web' => 'https://cyberpalace.sn',
                'capacite_max' => 50,
                'surface' => 245,
                'prix_heure' => 3800,
                'prix_journee' => 27000,
                'image_url' => 'https://picsum.photos/seed/palace/800/600.jpg',
                'statut' => 'actif',
                'valide' => true,
                'horaires' => json_encode([
                    'lundi' => '10:00-22:00',
                    'mardi' => '10:00-22:00',
                    'mercredi' => '10:00-22:00',
                    'jeudi' => '10:00-22:00',
                    'vendredi' => '10:00-23:00',
                    'samedi' => '10:00-23:00',
                    'dimanche' => '12:00-21:00'
                ]),
                'equipements' => json_encode([
                    'PC Gaming RTX 4070',
                    'Écrans 4K 144Hz',
                    'Casques gaming',
                    'Consoles PlayStation 5',
                    'Consoles Xbox Series X',
                    'Nintendo Switch',
                    'Casques VR'
                ]),
                'services' => json_encode([
                    'Bar',
                    'Restaurant',
                    'WiFi haut débit',
                    'Surveillance',
                    'Coaching gaming',
                    'Parking',
                    'Climatisation',
                    'VIP lounge'
                ]),
                'images' => json_encode([
                    'palace1.jpg',
                    'palace2.jpg',
                    'palace3.jpg',
                    'palace4.jpg'
                ])
            ],
            [
                'promoter_id' => 19,
                'nom' => 'Next Level Bambey',
                'slug' => 'next-level-bambey',
                'description' => 'Espace gaming avec focus sur progression et développement de compétences',
                'adresse' => 'Avenue de la Révolution',
                'ville' => 'Bambey',
                'code_postal' => '40000',
                'pays' => 'Sénégal',
                'latitude' => 14.6543,
                'longitude' => -16.6543,
                'telephone' => '+221 33 321 09 87',
                'email' => 'info@nextlevel.sn',
                'site_web' => 'https://nextlevel.sn',
                'capacite_max' => 42,
                'surface' => 200,
                'prix_heure' => 3400,
                'prix_journee' => 24000,
                'image_url' => 'https://picsum.photos/seed/nextlevel/800/600.jpg',
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
                    'PC Gaming RTX 4060',
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
                    'Coaching gaming',
                    'Parking',
                    'Climatisation',
                    'Formation'
                ]),
                'images' => json_encode([
                    'nextlevel1.jpg',
                    'nextlevel2.jpg',
                    'nextlevel3.jpg'
                ])
            ],
            [
                'promoter_id' => 20,
                'nom' => 'Power Play Mekhé',
                'slug' => 'power-play-mekhe',
                'description' => 'Centre gaming puissant avec équipements haut de gamme et tournois réguliers',
                'adresse' => 'Route Nationale 2',
                'ville' => 'Mekhé',
                'code_postal' => '42000',
                'pays' => 'Sénégal',
                'latitude' => 14.9876,
                'longitude' => -16.8765,
                'telephone' => '+221 33 210 98 76',
                'email' => 'info@powerplay.sn',
                'site_web' => 'https://powerplay.sn',
                'capacite_max' => 48,
                'surface' => 230,
                'prix_heure' => 3600,
                'prix_journee' => 26000,
                'image_url' => 'https://picsum.photos/seed/powerplay/800/600.jpg',
                'statut' => 'actif',
                'valide' => true,
                'horaires' => json_encode([
                    'lundi' => '10:00-22:00',
                    'mardi' => '10:00-22:00',
                    'mercredi' => '10:00-22:00',
                    'jeudi' => '10:00-22:00',
                    'vendredi' => '10:00-23:00',
                    'samedi' => '10:00-23:00',
                    'dimanche' => '12:00-21:00'
                ]),
                'equipements' => json_encode([
                    'PC Gaming RTX 4070',
                    'Écrans 4K 144Hz',
                    'Casques gaming',
                    'Consoles PlayStation 5',
                    'Consoles Xbox Series X',
                    'Nintendo Switch',
                    'Casques VR'
                ]),
                'services' => json_encode([
                    'Bar',
                    'Restaurant',
                    'WiFi haut débit',
                    'Surveillance',
                    'Coaching gaming',
                    'Parking',
                    'Climatisation',
                    'Tournois'
                ]),
                'images' => json_encode([
                    'powerplay1.jpg',
                    'powerplay2.jpg',
                    'powerplay3.jpg',
                    'powerplay4.jpg'
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
