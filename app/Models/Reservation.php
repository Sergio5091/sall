<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'salle_id',
        'date_heure',
        'duree',
        'nombre_personnes',
        'prix_total',
        'statut',
        'message',
        'promoter_notes'
    ];

    protected $casts = [
        'date_heure' => 'datetime',
        'prix_total' => 'decimal:2'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    // Statuts possibles
    public static function statuts()
    {
        return [
            'en_attente' => 'En attente',
            'confirme' => 'Confirmé',
            'annule' => 'Annulé',
            'termine' => 'Terminé'
        ];
    }

    // Scope pour les réservations à venir
    public function scopeAVenir($query)
    {
        return $query->where('date_heure', '>', now());
    }

    // Scope pour les réservations passées
    public function scopePasse($query)
    {
        return $query->where('date_heure', '<', now());
    }

    // Scope pour les réservations confirmées
    public function scopeConfirme($query)
    {
        return $query->where('statut', 'confirme');
    }
}
