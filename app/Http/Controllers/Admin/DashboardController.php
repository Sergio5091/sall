<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Afficher le dashboard admin
     */
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_users' => DB::table('users')->count(),
            'total_salles' => DB::table('salles')->count(),
            'salles_actives' => DB::table('salles')->where('valide', true)->count(),
            'salles_en_attente' => DB::table('salles')->where('valide', false)->count(),
            'total_reservations' => DB::table('reservations')->count(),
        ];

        // Évolution des inscriptions (7 derniers jours)
        $inscriptionsEvolution = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = DB::table('users')
                ->whereDate('created_at', $date)
                ->count();
            $inscriptionsEvolution[] = [
                'date' => now()->subDays($i)->format('D'),
                'count' => $count
            ];
        }

        // Répartition des salles
        $totalSalles = DB::table('salles')->count();
        $sallesActives = DB::table('salles')->where('valide', true)->count();
        $sallesEnAttente = DB::table('salles')->where('valide', false)->count();
        $sallesDesactivees = $totalSalles - $sallesActives - $sallesEnAttente;

        // Activité récente
        $recentActivity = [];
        
        // Utilisateurs récents
        $recentUsers = DB::table('users')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
            
        foreach ($recentUsers as $user) {
            $recentActivity[] = [
                'type' => 'user',
                'title' => 'Nouveau utilisateur : ' . $user->name,
                'description' => 'Compte créé via le formulaire d\'inscription.',
                'time' => \Carbon\Carbon::parse($user->created_at)->diffForHumans(),
                'icon' => 'fa-user-plus',
                'color' => 'blue'
            ];
        }

        // Salles récemment validées
        $recentSalles = DB::table('salles')
            ->where('valide', true)
            ->orderBy('updated_at', 'desc')
            ->limit(2)
            ->get();
            
        foreach ($recentSalles as $salle) {
            $recentActivity[] = [
                'type' => 'salle',
                'title' => 'Salle activée : ' . $salle->nom,
                'description' => 'La salle a été validée par le système.',
                'time' => \Carbon\Carbon::parse($salle->updated_at)->diffForHumans(),
                'icon' => 'fa-check',
                'color' => 'green'
            ];
        }

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'inscriptionsEvolution' => $inscriptionsEvolution,
            'sallesRepartition' => [
                'total' => $totalSalles,
                'actives' => $sallesActives,
                'en_attente' => $sallesEnAttente,
                'desactivees' => $sallesDesactivees,
            ],
            'recentActivity' => $recentActivity,
        ]);
    }
}
