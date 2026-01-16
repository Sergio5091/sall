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
        'type',
        'categorie',
        
        // Adresse
        'adresse',
        'code_postal',
        'ville',
        'pays',
        'region',
        'departement',
        'quartier',
        
        // Coordonnées GPS
        'latitude',
        'longitude',
        
        // Contact
        'telephone',
        'whatsapp',
        'email',
        'site_web',
        'reseaux_sociaux',
        
        // Capacité et surface
        'capacite_max',
        'capacite', // Ajout pour compatibilité
        'surface',
        'surface_area',
        
        // Équipements et services
        'equipements',
        'services',
        'machines_arcade',
        'casques_vr',
        'flippers',
        'consoles_retro',
        'pc_gaming',
        'tables_bowling',
        'tables_billard',
        'wifi_gratuit',
        'parking',
        'climatisation',
        'accessibilite_pmr',
        'surveillance_24h',
        'snack_bar',
        'restaurant',
        'bar',
        'terrasse',
        'espace_fumeur',
        'vestiaires',
        
        // Horaires
        'horaires',
        'horaires_ouverture',
        'jours_fermes',
        
        // Médias
        'image_couverture',
        'image_url',
        'images',
        'images_galerie',
        'video_presentation',
        'images_360',
        
        // Tarifs
        'prix_heure',
        'prix_journee',
        'tarifs',
        
        // Statut et validation
        'statut',
        'valide',
        'date_validation',
        'motif_rejet',
        
        // Réservation et paiement
        'reservation_en_ligne',
        'paiement_en_ligne',
        
        // SEO
        'meta_titre',
        'meta_description',
        'mots_cles',
        
        // Statistiques
        'note_moyenne',
        'nombre_avis',
        'nombre_vues',
        'nombre_favoris',
        
        // Localisation
        'rayon_action_km',
        'zones_couvertes',
        'point_repere',
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

    public function getValidationStatusAttribute(): string
    {
        return $this->valide ? 'validée' : 'en attente';
    }

    public function getValidationBadgeAttribute(): array
    {
        return $this->valide 
            ? ['color' => 'green', 'text' => 'Validée']
            : ['color' => 'yellow', 'text' => 'En attente'];
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

    public function getFullStatusBadgeAttribute(): array
    {
        if (!$this->valide) {
            return ['color' => 'yellow', 'text' => 'En attente de validation'];
        }
        
        return $this->getStatusBadgeAttribute();
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif')->where('valide', true);
    }

    public function scopePending($query)
    {
        return $query->where('valide', false);
    }

    public function scopeValidated($query)
    {
        return $query->where('valide', true);
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

    // Méthodes de validation
    public function validate(): bool
    {
        return $this->update(['valide' => true]);
    }

    public function isPendingValidation(): bool
    {
        return !$this->valide;
    }

    public function isValidated(): bool
    {
        return $this->valide;
    }

    public function canBePublished(): bool
    {
        return $this->valide && $this->statut === 'actif';
    }
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
