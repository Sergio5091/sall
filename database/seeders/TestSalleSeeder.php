<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Salle;
use App\Models\User;

class TestSalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer le premier utilisateur promoteur
        $promoter = User::where('role', 'promoter')->first();
        
        if (!$promoter) {
            $this->command->error('Aucun promoteur trouvé dans la base de données');
            return;
        }

        // Créer une salle de test
        $salle = Salle::create([
            'nom' => 'Salle Test - Gaming Arena',
            'description' => 'Une salle de gaming parfaite pour les tournois et événements.',
            'adresse' => '123 Rue de la Victoire',
            'ville' => 'Paris',
            'code_postal' => '75001',
            'pays' => 'France',
            'latitude' => 48.8566,
            'longitude' => 2.3522,
            'capacite_max' => 50,
            'surface' => 150,
            'prix_heure' => 5000,
            'telephone' => '+33123456789',
            'email' => 'contact@gamingarena.fr',
            'statut' => 'actif',
            'valide' => false, // En attente de validation
            'promoter_id' => $promoter->id,
        ]);

        $this->command->info("Salle de test créée avec l'ID: {$salle->id}");
        $this->command->info("Promoteur ID: {$promoter->id}");
        $this->command->info("Nom du promoteur: {$promoter->name}");
    }
}
