<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Supprimer l'admin existant s'il y en a un
        $existingAdmin = User::where('email', 'admin@youpihub.com')->first();
        if ($existingAdmin) {
            $existingAdmin->delete();
            echo "Ancien administrateur supprimé\n";
        }

        // Créer le nouvel administrateur
        $admin = User::create([
            'name' => 'Administrateur YOUPIHUB',
            'email' => 'admin@youpihub.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        echo "Administrateur créé avec succès!\n";
        echo "Email: admin@youpihub.com\n";
        echo "Mot de passe: admin123\n";
        echo "Rôle: admin\n";
    }
}
