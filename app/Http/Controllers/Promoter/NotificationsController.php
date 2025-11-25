<?php

namespace App\Http\Controllers\Promoter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * Display the notifications page.
     */
    public function index()
    {
        // Get the authenticated promoter
        $promoter = Auth::user();
        
        // Sample notifications data (in real app, this would come from database)
        $notifications = [
            [
                'id' => 1,
                'type' => 'alert',
                'title' => 'Alerte Admin',
                'message' => 'Votre salle "Pixel Palace" a été désactivée pour informations incomplètes.',
                'created_at' => now()->subMinutes(15),
                'read' => false,
                'icon' => 'exclamation-circle',
                'color' => 'red'
            ],
            [
                'id' => 2,
                'type' => 'message',
                'title' => 'Nouveau message',
                'message' => 'Alice Martin vous a envoyé un message concernant "CyberZone Arena".',
                'created_at' => now()->subHours(2),
                'read' => false,
                'icon' => 'comment',
                'color' => 'blue'
            ],
            [
                'id' => 3,
                'type' => 'reservation',
                'title' => 'Réservation récente',
                'message' => 'Nouvelle réservation pour "Tournoi Super Smash" par Bob Johnson.',
                'created_at' => now()->subDay(),
                'read' => true,
                'icon' => 'ticket-alt',
                'color' => 'green'
            ],
            [
                'id' => 4,
                'type' => 'comment',
                'title' => 'Nouveau commentaire',
                'message' => 'Charlie Brown a commenté votre événement "Soirée Découverte VR".',
                'created_at' => now()->subDays(3),
                'read' => true,
                'icon' => 'comment-dots',
                'color' => 'yellow'
            ],
            [
                'id' => 5,
                'type' => 'review',
                'title' => 'Nouvel avis',
                'message' => 'Sarah a laissé un avis 5 étoiles pour votre salle "Pixel Palace".',
                'created_at' => now()->subDays(5),
                'read' => true,
                'icon' => 'star',
                'color' => 'purple'
            ],
            [
                'id' => 6,
                'type' => 'report',
                'title' => 'Rapport hebdomadaire',
                'message' => 'Votre rapport de performance hebdomadaire est disponible.',
                'created_at' => now()->subDays(6),
                'read' => true,
                'icon' => 'chart-line',
                'color' => 'indigo'
            ]
        ];

        return Inertia::render('Promoter/Notifications', [
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead($id)
    {
        // Logic to mark notification as read
        return back()->with('success', 'Notification marquée comme lue');
    }

    /**
     * Delete notification.
     */
    public function delete($id)
    {
        // Logic to delete notification
        return back()->with('success', 'Notification supprimée');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        // Logic to mark all notifications as read
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }

    /**
     * Delete all notifications.
     */
    public function deleteAll()
    {
        // Logic to delete all notifications
        return back()->with('success', 'Toutes les notifications ont été supprimées');
    }
}
