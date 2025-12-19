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
            'salles_en_attente' => DB::table('salles')->where('valide', false)->count(),
            'total_reservations' => DB::table('reservations')->count(),
        ];

        // Utilisateurs récents
        $recentUsers = DB::table('users')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Salles en attente de validation
        $sallesEnAttente = DB::table('salles')
            ->where('valide', false)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'sallesEnAttente' => $sallesEnAttente,
        ]);
    }
}
