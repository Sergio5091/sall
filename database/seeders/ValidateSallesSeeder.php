<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Salle;

class ValidateSallesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Valider toutes les salles qui ne le sont pas encore
        $sallesToValidate = Salle::where('valide', false)->update(['valide' => true]);
        
        $this->command->info($sallesToValidate . ' salles ont été validées avec succès.');
    }
}
