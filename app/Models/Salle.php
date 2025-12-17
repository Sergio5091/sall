<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salle extends Model
{
    use HasFactory;

    protected $fillable = [
        // Informations de base
        'promoter_id',
        'nom',
        'slug',
        'description',
        
        // Adresse
        'adresse',
        'code_postal',
        'ville',
        'pays',
        
        // Coordonnées GPS
        'latitude',
        'longitude',
        
        // Contact
        'telephone',
        'email',
        'site_web',
        
        // Capacité et surface
        'capacite_max',
        'surface',
        
        // Équipements et services
        'equipements',
        'services',
        
        // Tarifs
        'prix_heure',
        'prix_journee',
        
        // Médias
        'image_url',
        'images',
        
        // Horaires
        'horaires',
        
        // Statut
        'statut',
        'valide',
    ];

    protected $casts = [
        'equipements' => 'array',
        'services' => 'array',
        'horaires' => 'array',
        'images' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'prix_heure' => 'decimal:2',
        'prix_journee' => 'decimal:2',
    ];

    // Relations
    public function promoter(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function evenements(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function avis(): HasMany
    {
        return $this->hasMany(Avis::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    // Accesseurs
    public function getFullAddressAttribute(): string
    {
        return "{$this->adresse}, {$this->code_postal} {$this->ville}, {$this->pays}";
    }

    public function getCoordinatesAttribute(): array
    {
        return [
            'lat' => (float) $this->latitude,
            'lng' => (float) $this->longitude
        ];
    }

    public function getFormattedPhoneAttribute(): string
    {
        return $this->telephone ? preg_replace('/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/', '$1 $2 $3 $4 $5', $this->telephone) : '';
    }

    public function getMainImageAttribute(): string
    {
        return $this->image_url ?? 'https://picsum.photos/seed/salle/800/600.jpg';
    }

    public function getStatusBadgeAttribute(): array
    {
        $badges = [
            'actif' => ['color' => 'green', 'text' => 'Active'],
            'inactif' => ['color' => 'red', 'text' => 'Inactive'],
            'maintenance' => ['color' => 'yellow', 'text' => 'Maintenance']
        ];

        return $badges[$this->statut] ?? ['color' => 'gray', 'text' => 'Inconnu'];
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif')->where('valide', true);
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('pays', $country);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('ville', $city);
    }

    // Méthodes de géolocalisation
    public function calculateDistanceFrom($latitude, $longitude): float
    {
        $earthRadius = 6371; // Rayon de la Terre en kilomètres

        $latFrom = deg2rad($latitude);
        $lonFrom = deg2rad($longitude);
        $latTo = deg2rad($this->latitude);
        $lonTo = deg2rad($this->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }

    public function isWithinRadius($latitude, $longitude, $radiusKm): bool
    {
        return $this->calculateDistanceFrom($latitude, $longitude) <= $radiusKm;
    }

    // Génération de slug unique
    public static function generateUniqueSlug($nom)
    {
        $slug = \Str::slug($nom);
        $originalSlug = $slug;
        $counter = 1;

        while (self::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($salle) {
            // Générer un slug unique si non spécifié
            if (empty($salle->slug)) {
                $salle->slug = self::generateUniqueSlug($salle->nom);
            }
            
            // Définir le pays par défaut si non spécifié
            if (empty($salle->pays)) {
                $salle->pays = 'Bénin';
            }
        });
    }
}
