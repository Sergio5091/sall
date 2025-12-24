<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Salle;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AllEventTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les salles validées et les promoteurs
        $salles = Salle::where('valide', true)->where('statut', 'actif')->get();
        $promoters = User::where('role', 'promoter')->get();
        
        if ($salles->isEmpty()) {
            $this->command->warn('Aucune salle valide trouvée. Créez d\'abord des salles avec le SalleSeeder.');
            return;
        }

        if ($promoters->isEmpty()) {
            $this->command->warn('Aucun promoteur trouvé. Créez d\'abord des promoteurs.');
            return;
        }

        $categories = Event::categories();
        $types = Event::types();
        $statuts = Event::statuts();
        $visibilites = Event::visibilites();
        $publicsCibles = Event::publicsCibles();
        $devises = Event::devises();

        $eventsData = [];

        // Tournois
        $eventsData[] = $this->createEventData([
            'titre' => 'Championnat National CS:GO',
            'categorie' => 'tournoi',
            'type' => 'offline',
            'description' => 'Le plus grand tournoi de CS:GO au niveau national avec des prix exceptionnels et une qualification internationale.',
            'prix_base' => 2500,
            'capacite_max' => 128,
            'date_debut' => Carbon::now()->addDays(15),
            'date_fin' => Carbon::now()->addDays(17),
            'mis_en_avant' => true,
        ], $salles, $promoters);

        $eventsData[] = $this->createEventData([
            'titre' => 'League of Legends Championship',
            'categorie' => 'tournoi',
            'type' => 'hybride',
            'description' => 'Tournoi de League of Legends avec phases en ligne et finale sur place. Cashprize de 10 000€.',
            'prix_base' => 1500,
            'prix_vip' => 5000,
            'capacite_max' => 256,
            'date_debut' => Carbon::now()->addDays(30),
            'date_fin' => Carbon::now()->addDays(32),
            'mis_en_avant' => true,
        ], $salles, $promoters);

        // Soirées
        $eventsData[] = $this->createEventData([
            'titre' => 'Gaming Night Extravaganza',
            'categorie' => 'soiree',
            'type' => 'offline',
            'description' => 'Une soirée gaming inoubliable avec DJ, tournois rapides et lots de prix à gagner.',
            'prix_base' => 2000,
            'capacite_max' => 200,
            'date_debut' => Carbon::now()->addDays(7),
            'date_fin' => Carbon::now()->addDays(7)->addHours(8),
            'mis_en_avant' => true,
        ], $salles, $promoters);

        $eventsData[] = $this->createEventData([
            'titre' => 'Retro Gaming Party',
            'categorie' => 'soiree',
            'type' => 'offline',
            'description' => 'Plongez dans l\'univers des jeux rétro avec consoles classiques et jeux d\'arcade.',
            'gratuit' => true,
            'capacite_max' => 150,
            'date_debut' => Carbon::now()->addDays(10),
            'date_fin' => Carbon::now()->addDays(10)->addHours(6),
        ], $salles, $promoters);

        // Ateliers
        $eventsData[] = $this->createEventData([
            'titre' => 'Workshop Esports Management',
            'categorie' => 'atelier',
            'type' => 'offline',
            'description' => 'Apprenez à gérer une équipe esportive professionnelle avec des experts du secteur.',
            'prix_base' => 5000,
            'capacite_max' => 30,
            'date_debut' => Carbon::now()->addDays(20),
            'date_fin' => Carbon::now()->addDays(20)->addHours(4),
            'certificat_participation' => true,
        ], $salles, $promoters);

        $eventsData[] = $this->createEventData([
            'titre' => 'Formation Streaming',
            'categorie' => 'atelier',
            'type' => 'online',
            'description' => 'Devenez streamer professionnel : techniques, matériel et monétisation.',
            'prix_base' => 3000,
            'capacite_max' => 50,
            'date_debut' => Carbon::now()->addDays(25),
            'date_fin' => Carbon::now()->addDays(25)->addHours(3),
            'streaming' => true,
            'url_streaming' => 'https://twitch.tv/example',
        ], $salles, $promoters);

        // Lancements
        $eventsData[] = $this->createEventData([
            'titre' => 'Lancement PlayStation 6',
            'categorie' => 'lancement',
            'type' => 'offline',
            'description' => 'Soyez les premiers à découvrir la nouvelle génération de gaming avec PlayStation 6.',
            'prix_base' => 0,
            'gratuit' => true,
            'capacite_max' => 500,
            'date_debut' => Carbon::now()->addDays(5),
            'date_fin' => Carbon::now()->addDays(5)->addHours(12),
            'mis_en_avant' => true,
        ], $salles, $promoters);

        $eventsData[] = $this->createEventData([
            'titre' => 'Lancement Jeu Indépendant "CyberQuest"',
            'categorie' => 'lancement',
            'type' => 'hybride',
            'description' => 'Découvrez en avant-première le nouveau jeu indépendant CyberQuest avec les développeurs.',
            'prix_base' => 1000,
            'capacite_max' => 100,
            'date_debut' => Carbon::now()->addDays(12),
            'date_fin' => Carbon::now()->addDays(12)->addHours(5),
        ], $salles, $promoters);

        // Festivals
        $eventsData[] = $this->createEventData([
            'titre' => 'Gaming Festival 2024',
            'categorie' => 'festival',
            'type' => 'offline',
            'description' => '3 jours de gaming pur : tournois, exhibitions, conférences et animations.',
            'prix_base' => 3500,
            'prix_vip' => 10000,
            'capacite_max' => 1000,
            'date_debut' => Carbon::now()->addDays(45),
            'date_fin' => Carbon::now()->addDays(47),
            'mis_en_avant' => true,
        ], $salles, $promoters);

        $eventsData[] = $this->createEventData([
            'titre' => 'Retro Gaming Festival',
            'categorie' => 'festival',
            'type' => 'offline',
            'description' => 'Célébration de l\'histoire du gaming avec des centaines de machines rétro et classiques.',
            'prix_base' => 1500,
            'capacite_max' => 800,
            'date_debut' => Carbon::now()->addDays(60),
            'date_fin' => Carbon::now()->addDays(61),
        ], $salles, $promoters);

        // Conférences
        $eventsData[] = $this->createEventData([
            'titre' => 'Conférence Gaming Industry',
            'categorie' => 'conference',
            'type' => 'hybride',
            'description' => 'Conférence sur les tendances et l\'avenir de l\'industrie du gaming avec des speakers internationaux.',
            'prix_base' => 8000,
            'prix_vip' => 15000,
            'capacite_max' => 300,
            'date_debut' => Carbon::now()->addDays(40),
            'date_fin' => Carbon::now()->addDays(40)->addHours(8),
            'certificat_participation' => true,
        ], $salles, $promoters);

        $eventsData[] = $this->createEventData([
            'titre' => 'Tech Talk: VR Gaming',
            'categorie' => 'conference',
            'type' => 'online',
            'description' => 'Exploration des dernières innovations en réalité virtuelle pour le gaming.',
            'prix_base' => 0,
            'gratuit' => true,
            'capacite_max' => 1000,
            'date_debut' => Carbon::now()->addDays(18),
            'date_fin' => Carbon::now()->addDays(18)->addHours(2),
            'streaming' => true,
        ], $salles, $promoters);

        // Formations
        $eventsData[] = $this->createEventData([
            'titre' => 'Formation Développement Jeux Vidéo',
            'categorie' => 'formation',
            'type' => 'offline',
            'description' => 'Formation intensive de 2 semaines sur le développement de jeux avec Unity et Unreal Engine.',
            'prix_base' => 150000,
            'capacite_max' => 20,
            'date_debut' => Carbon::now()->addDays(90),
            'date_fin' => Carbon::now()->addDays(104),
            'certificat_participation' => true,
        ], $salles, $promoters);

        $eventsData[] = $this->createEventData([
            'titre' => 'Formation Game Design',
            'categorie' => 'formation',
            'type' => 'online',
            'description' => 'Apprenez les principes du game design avec des professionnels de l\'industrie.',
            'prix_base' => 25000,
            'capacite_max' => 40,
            'date_debut' => Carbon::now()->addDays(75),
            'date_fin' => Carbon::now()->addDays(77),
            'certificat_participation' => true,
            'streaming' => true,
        ], $salles, $promoters);

        // Meetups
        $eventsData[] = $this->createEventData([
            'titre' => 'GameDev Meetup Paris',
            'categorie' => 'meetup',
            'type' => 'offline',
            'description' => 'Rencontre mensuelle des développeurs de jeux pour partager et réseauter.',
            'prix_base' => 0,
            'gratuit' => true,
            'capacite_max' => 80,
            'date_debut' => Carbon::now()->addDays(3),
            'date_fin' => Carbon::now()->addDays(3)->addHours(4),
        ], $salles, $promoters);

        $eventsData[] = $this->createEventData([
            'titre' => 'Indie Game Dev Meetup',
            'categorie' => 'meetup',
            'type' => 'hybride',
            'description' => 'Meetup dédié aux développeurs de jeux indépendants pour partager expériences et projets.',
            'prix_base' => 500,
            'capacite_max' => 60,
            'date_debut' => Carbon::now()->addDays(22),
            'date_fin' => Carbon::now()->addDays(22)->addHours(3),
        ], $salles, $promoters);

        // Compétitions
        $eventsData[] = $this->createEventData([
            'titre' => 'Speedrun Competition',
            'categorie' => 'competition',
            'type' => 'offline',
            'description' => 'Compétition de speedrun sur les jeux classiques avec des records à battre.',
            'prix_base' => 1000,
            'capacite_max' => 50,
            'date_debut' => Carbon::now()->addDays(28),
            'date_fin' => Carbon::now()->addDays(28)->addHours(6),
            'mis_en_avant' => true,
        ], $salles, $promoters);

        $eventsData[] = $this->createEventData([
            'titre' => 'Game Jam 48h',
            'categorie' => 'competition',
            'type' => 'offline',
            'description' => 'Créez un jeu en 48 heures avec une équipe et gagnez des prix.',
            'prix_base' => 2000,
            'capacite_max' => 100,
            'date_debut' => Carbon::now()->addDays(35),
            'date_fin' => Carbon::now()->addDays(37),
        ], $salles, $promoters);

        // Autres
        $eventsData[] = $this->createEventData([
            'titre' => 'LAN Party Traditionnelle',
            'categorie' => 'autre',
            'type' => 'offline',
            'description' => 'LAN party classique avec PC, consoles et tournois amicaux.',
            'prix_base' => 1500,
            'capacite_max' => 120,
            'date_debut' => Carbon::now()->addDays(8),
            'date_fin' => Carbon::now()->addDays(9),
        ], $salles, $promoters);

        $eventsData[] = $this->createEventData([
            'titre' => 'Exposition Gaming Art',
            'categorie' => 'autre',
            'type' => 'offline',
            'description' => 'Exposition d\'art inspirée par l\'univers du gaming avec des artistes renommés.',
            'prix_base' => 0,
            'gratuit' => true,
            'capacite_max' => 300,
            'date_debut' => Carbon::now()->addDays(50),
            'date_fin' => Carbon::now()->addDays(55),
        ], $salles, $promoters);

        // Créer tous les événements
        foreach ($eventsData as $eventData) {
            Event::create($eventData);
        }

        $this->command->info('Création de ' . count($eventsData) . ' événements terminée avec tous les types disponibles!');
    }

    /**
     * Crée les données d'un événement
     */
    private function createEventData(array $baseData, $salles, $promoters): array
    {
        $salle = $salles->random();
        $promoter = $promoters->random();

        return array_merge([
            // Relations
            'salle_id' => $salle->id,
            'promoter_id' => $promoter->id,
            
            // Lieu et localisation
            'lieu' => $salle->nom,
            'adresse' => $salle->adresse,
            'code_postal' => $salle->code_postal,
            'ville' => $salle->ville,
            'pays' => $salle->pays,
            'latitude' => $salle->latitude,
            'longitude' => $salle->longitude,
            
            // Dates et heures
            'date_limite_inscription' => Carbon::parse($baseData['date_debut'])->subDays(7),
            
            // Tarifs
            'devise' => 'XOF',
            
            // Capacité
            'places_disponibles' => $baseData['capacite_max'],
            'limite_inscription' => true,
            
            // Tags et activités
            'tags' => ['gaming', 'esports', 'compétition', 'réseautage'],
            'programme' => [
                '09:00' => 'Accueil et café',
                '10:00' => 'Ouverture officielle',
                '11:00' => 'Sessions principales',
                '14:00' => 'Pause déjeuner',
                '15:00' => 'Sessions après-midi',
                '18:00' => 'Remise des prix',
                '19:00' => 'Réseautage'
            ],
            'activites' => ['Tournois', 'Ateliers', 'Conférences', 'Expositions'],
            
            // Contact
            'contact_email' => $promoter->email,
            'contact_telephone' => $salle->telephone,
            'contact_whatsapp' => $salle->whatsapp,
            'reseaux_sociaux' => [
                'twitter' => '@example',
                'facebook' => 'example',
                'instagram' => '@example'
            ],
            
            // Configuration
            'inscription_obligatoire' => true,
            'paiement_en_ligne' => !($baseData['gratuit'] ?? false),
            
            // Statut et visibilité
            'statut' => 'publie',
            'visibilite' => 'public',
            
            // SEO
            'meta_titre' => $baseData['titre'],
            'meta_description' => substr($baseData['description'], 0, 160),
            'mots_cles' => ['gaming', 'esports', 'événement', 'compétition'],
        ], $baseData);
    }
}
