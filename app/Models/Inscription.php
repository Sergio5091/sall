<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'statut',
        'date_inscription',
        'paiement_statut',
        'montant_paye',
    ];

    protected $casts = [
        'date_inscription' => 'datetime',
        'montant_paye' => 'decimal:2',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
