<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Salle;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des événements réels pour les salles existantes
        $this->createRealEvents();
    }

    private function createRealEvents()
    {
        $salles = Salle::all();
        
        if ($salles->isEmpty()) {
            echo "Aucune salle trouvée. Veuillez d'abord exécuter SalleSeeder.\n";
            return;
        }

        $events = [
            // Événements pour GameZone Dakar (salle_id = 1)
            [
                'salle_id' => 1,
                'promoter_id' => 1,
                'titre' => 'Tournoi National FIFA 24',
                'description' => 'Le plus grand tournoi FIFA 24 du Sénégal avec prix de 500.000 FCFA et qualification pour la compétition régionale',
                'slug' => 'tournoi-national-fifa-24',
                'date_debut' => Carbon::now()->addDays(7)->setTime(14, 0),
                'date_fin' => Carbon::now()->addDays(7)->setTime(22, 0),
                'date_limite_inscription' => Carbon::now()->addDays(5),
                'prix_base' => 2000,
                'prix_vip' => 5000,
                'devise' => 'XOF',
                'gratuit' => false,
                'capacite_max' => 64,
                'places_disponibles' => 64,
                'limite_inscription' => true,
                'categorie' => 'tournoi',
                'type' => 'offline',
                'public_cible' => '18+',
                'age_minimum' => 18,
                'tags' => json_encode(['fifa', 'esport', 'tournoi', 'jeu vidéo', 'compétition']),
                'contact_email' => 'tournoi@gamezone.sn',
                'contact_telephone' => '+221 77 123 45 67',
                'contact_whatsapp' => '+221 77 123 45 67',
                'inscription_obligatoire' => true,
                'paiement_en_ligne' => true,
                'certificat_participation' => true,
                'streaming' => true,
                'url_streaming' => 'https://twitch.tv/gamezonesn',
                'statut' => 'publie',
                'visibilite' => 'public',
                'mis_en_avant' => true,
                'meta_titre' => 'Tournoi FIFA 24 Dakar - GameZone',
                'meta_description' => 'Participez au plus grand tournoi FIFA 24 du Sénégal à GameZone Dakar. Prix de 500.000 FCFA.',
                'mots_cles' => json_encode(['fifa', 'tournoi', 'dakar', 'esport', 'jeu vidéo'])
            ],
            [
                'salle_id' => 1,
                'promoter_id' => 1,
                'titre' => 'Soirée Gaming Retro',
                'description' => 'Une soirée spéciale dédiée aux jeux rétro avec Mario Kart 64, Street Fighter II et Pac-Man originaux',
                'slug' => 'soiree-gaming-retro',
                'date_debut' => Carbon::now()->addDays(14)->setTime(19, 0),
                'date_fin' => Carbon::now()->addDays(14)->setTime(23, 0),
                'date_limite_inscription' => Carbon::now()->addDays(12),
                'prix_base' => 3000,
                'devise' => 'XOF',
                'gratuit' => false,
                'capacite_max' => 40,
                'places_disponibles' => 40,
                'limite_inscription' => true,
                'categorie' => 'soiree',
                'type' => 'offline',
                'public_cible' => 'tous',
                'tags' => json_encode(['retro', 'soirée', 'mario kart', 'street fighter', 'pac-man']),
                'contact_email' => 'contact@gamezone.sn',
                'contact_telephone' => '+221 33 123 45 67',
                'inscription_obligatoire' => true,
                'paiement_en_ligne' => true,
                'statut' => 'publie',
                'visibilite' => 'public',
                'meta_titre' => 'Soirée Gaming Retro Dakar',
                'meta_description' => 'Revivez l\'âge d\'or du gaming avec Mario Kart 64, Street Fighter II et Pac-Man originaux',
                'mots_cles' => json_encode(['retro gaming', 'soirée', 'dakar', 'mario kart', 'street fighter'])
            ],
            // Événements pour CyberTech Pikine (salle_id = 2)
            [
                'salle_id' => 2,
                'promoter_id' => 2,
                'titre' => 'Atelier Initiation VR',
                'description' => 'Découvrez la réalité virtuelle avec nos casques dernier cri. Formation gratuite de 2 heures',
                'slug' => 'atelier-initiation-vr',
                'date_debut' => Carbon::now()->addDays(3)->setTime(10, 0),
                'date_fin' => Carbon::now()->addDays(3)->setTime(12, 0),
                'date_limite_inscription' => Carbon::now()->addDays(2),
                'prix_base' => 0,
                'devise' => 'XOF',
                'gratuit' => true,
                'capacite_max' => 20,
                'places_disponibles' => 20,
                'limite_inscription' => true,
                'categorie' => 'atelier',
                'type' => 'offline',
                'public_cible' => 'tous',
                'age_minimum' => 10,
                'tags' => json_encode(['vr', 'réalité virtuelle', 'atelier', 'formation', 'gratuit']),
                'contact_email' => 'info@cybertech.sn',
                'contact_telephone' => '+221 77 987 65 43',
                'inscription_obligatoire' => true,
                'paiement_en_ligne' => false,
                'certificat_participation' => true,
                'statut' => 'publie',
                'visibilite' => 'public',
                'mis_en_avant' => true,
                'meta_titre' => 'Atelier VR Gratuit Pikine',
                'meta_description' => 'Atelier d\'initiation gratuit à la réalité virtuelle à CyberTech Pikine. Places limitées.',
                'mots_cles' => json_encode(['vr', 'réalité virtuelle', 'atelier', 'gratuit', 'pikine'])
            ],
            [
                'salle_id' => 2,
                'promoter_id' => 2,
                'titre' => 'Lancement Beat Saber',
                'description' => 'Soirée de lancement officielle de Beat Saber avec démonstration, concours et prix à gagner',
                'slug' => 'lancement-beat-saber',
                'date_debut' => Carbon::now()->addDays(10)->setTime(18, 0),
                'date_fin' => Carbon::now()->addDays(10)->setTime(23, 0),
                'date_limite_inscription' => Carbon::now()->addDays(8),
                'prix_base' => 4000,
                'prix_vip' => 8000,
                'devise' => 'XOF',
                'gratuit' => false,
                'capacite_max' => 50,
                'places_disponibles' => 50,
                'limite_inscription' => true,
                'categorie' => 'lancement',
                'type' => 'offline',
                'public_cible' => '16+',
                'age_minimum' => 16,
                'tags' => json_encode(['beat saber', 'vr', 'lancement', 'musique', 'rythme']),
                'contact_email' => 'lancement@cybertech.sn',
                'contact_telephone' => '+221 77 987 65 43',
                'contact_whatsapp' => '+221 77 987 65 43',
                'inscription_obligatoire' => true,
                'paiement_en_ligne' => true,
                'streaming' => true,
                'url_streaming' => 'https://twitch.tv/cybertechsn',
                'statut' => 'publie',
                'visibilite' => 'public',
                'meta_titre' => 'Lancement Beat Saber CyberTech',
                'meta_description' => 'Soirée de lancement Beat Saber avec démonstration VR et concours à Pikine',
                'mots_cles' => json_encode(['beat saber', 'vr', 'lancement', 'pikine', 'rythme game'])
            ],
            // Événements pour Retro Gaming Thiès (salle_id = 3)
            [
                'salle_id' => 3,
                'promoter_id' => 3,
                'titre' => 'Championnat Pac-Man',
                'description' => 'Championnat national de Pac-Man sur machines originales des années 80. Prix de 100.000 FCFA',
                'slug' => 'championnat-pacman',
                'date_debut' => Carbon::now()->addDays(21)->setTime(15, 0),
                'date_fin' => Carbon::now()->addDays(21)->setTime(20, 0),
                'date_limite_inscription' => Carbon::now()->addDays(19),
                'prix_base' => 1500,
                'devise' => 'XOF',
                'gratuit' => false,
                'capacite_max' => 32,
                'places_disponibles' => 32,
                'limite_inscription' => true,
                'categorie' => 'competition',
                'type' => 'offline',
                'public_cible' => 'tous',
                'age_minimum' => 12,
                'tags' => json_encode(['pac-man', 'championnat', 'arcade', 'rétro', 'concours']),
                'contact_email' => 'championnat@retrogaming.sn',
                'contact_telephone' => '+221 76 456 78 90',
                'inscription_obligatoire' => true,
                'paiement_en_ligne' => false,
                'certificat_participation' => true,
                'statut' => 'publie',
                'visibilite' => 'public',
                'meta_titre' => 'Championnat Pac-Man Thiès',
                'meta_description' => 'Championnat national Pac-Man sur machines originales. Prix de 100.000 FCFA à Thiès.',
                'mots_cles' => json_encode(['pac-man', 'championnat', 'thiès', 'arcade', 'rétro gaming'])
            ],
            [
                'salle_id' => 3,
                'promoter_id' => 3,
                'titre' => 'Nuit Arcade Classics',
                'description' => 'Nuit entière dédiée aux classiques de l\'arcade : Space Invaders, Donkey Kong, Galaga et bien plus',
                'slug' => 'nuit-arcade-classics',
                'date_debut' => Carbon::now()->addDays(28)->setTime(20, 0),
                'date_fin' => Carbon::now()->addDays(29)->setTime(6, 0),
                'date_limite_inscription' => Carbon::now()->addDays(26),
                'prix_base' => 10000,
                'devise' => 'XOF',
                'gratuit' => false,
                'capacite_max' => 25,
                'places_disponibles' => 25,
                'limite_inscription' => true,
                'categorie' => 'soiree',
                'type' => 'offline',
                'public_cible' => '18+',
                'age_minimum' => 18,
                'tags' => json_encode(['arcade', 'nuit', 'space invaders', 'donkey kong', 'galaga']),
                'contact_email' => 'soiree@retrogaming.sn',
                'contact_telephone' => '+221 76 456 78 90',
                'inscription_obligatoire' => true,
                'paiement_en_ligne' => false,
                'statut' => 'publie',
                'visibilite' => 'public',
                'meta_titre' => 'Nuit Arcade Classics Thiès',
                'meta_description' => 'Nuit complète d\'arcade classics à Thiès. Space Invaders, Donkey Kong, Galaga et plus.',
                'mots_cles' => json_encode(['arcade', 'nuit', 'thiès', 'space invaders', 'donkey kong'])
            ]
        ];

        foreach ($events as $eventData) {
            // Vérifier si l'événement existe déjà
            $existingEvent = Event::where('slug', $eventData['slug'])->first();
            
            if (!$existingEvent) {
                Event::create($eventData);
                echo "Événement créé: {$eventData['titre']}\n";
            } else {
                echo "L'événement {$eventData['titre']} existe déjà\n";
            }
        }
    }
}
