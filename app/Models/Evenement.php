<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'date_debut',
        'date_fin',
        'statut',
        'salle_id',
        'organisateur',
        'participants_max',
        'participants_actuels',
        'prix',
        'image_url',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'prix' => 'decimal:2',
    ];

    // Relations
    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    // Scopes
    public function scopePublie($query)
    {
        return $query->where('statut', 'publie');
    }

    public function scopeAVenir($query)
    {
        return $query->where('date_debut', '>=', now());
    }

    // Accessors
    public function getStatutFormattedAttribute()
    {
        return match($this->statut) {
            'publie' => 'Publié',
            'brouillon' => 'Brouillon',
            'annule' => 'Annulé',
            'termine' => 'Terminé',
            default => ucfirst($this->statut),
        };
    }

    public function getDureeAttribute()
    {
        return $this->date_debut->diffInHours($this->date_fin);
    }

    public function getPlacesDisponiblesAttribute()
    {
        return $this->participants_max - $this->participants_actuels;
    }

    public function estComplet()
    {
        return $this->participants_actuels >= $this->participants_max;
    }

    public function estPasse()
    {
        return $this->date_fin < now();
    }
}
