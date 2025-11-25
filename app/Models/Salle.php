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
        
        // Adresse complète
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
        
        // Capacité et équipements
        'capacite',
        'surface_area',
        'nombre_etalages',
        'machines_arcade',
        'casques_vr',
        'flippers',
        'consoles_retro',
        'pc_gaming',
        'tables_bowling',
        'tables_billard',
        
        // Équipements et services
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
        'horaires_ouverture',
        'jours_fermes',
        
        // Médias
        'image_couverture',
        'images_galerie',
        'video_presentation',
        'images_360',
        
        // SEO
        'meta_titre',
        'meta_description',
        'mots_cles',
        
        // Statut
        'statut',
        'valide_par_admin',
        'date_validation',
        'motif_rejet',
        
        // Tarifs
        'tarifs',
        'reservation_en_ligne',
        'paiement_en_ligne',
        
        // Évaluation
        'note_moyenne',
        'nombre_avis',
        'nombre_vues',
        'nombre_favoris',
        
        // Géolocalisation
        'rayon_action_km',
        'zones_couvertes',
        'point_repere',
    ];

    protected $casts = [
        'reseaux_sociaux' => 'array',
        'horaires_ouverture' => 'array',
        'jours_fermes' => 'array',
        'images_galerie' => 'array',
        'images_360' => 'array',
        'mots_cles' => 'array',
        'tarifs' => 'array',
        'zones_couvertes' => 'array',
        'wifi_gratuit' => 'boolean',
        'parking' => 'boolean',
        'climatisation' => 'boolean',
        'accessibilite_pmr' => 'boolean',
        'surveillance_24h' => 'boolean',
        'snack_bar' => 'boolean',
        'restaurant' => 'boolean',
        'bar' => 'boolean',
        'terrasse' => 'boolean',
        'espace_fumeur' => 'boolean',
        'vestiaires' => 'boolean',
        'valide_par_admin' => 'boolean',
        'reservation_en_ligne' => 'boolean',
        'paiement_en_ligne' => 'boolean',
        'date_validation' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relations
    public function promoter(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function evenements(): HasMany
    {
        return $this->hasMany(Evenement::class);
    }

    public function avis(): HasMany
    {
        return $this->hasMany(Avis::class);
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
        return $this->image_couverture ?? 'https://picsum.photos/seed/salle/800/600.jpg';
    }

    public function getStatusBadgeAttribute(): array
    {
        $badges = [
            'actif' => ['color' => 'green', 'text' => 'Active'],
            'inactif' => ['color' => 'red', 'text' => 'Inactive'],
            'en_attente' => ['color' => 'yellow', 'text' => 'En attente'],
            'suspendu' => ['color' => 'red', 'text' => 'Suspendu']
        ];

        return $badges[$this->statut] ?? ['color' => 'gray', 'text' => 'Inconnu'];
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif')->where('valide_par_admin', true);
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('pays', $country);
    }

    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('ville', $city);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
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

    // Génération du slug unique
    public static function generateUniqueSlug($nom): string
    {
        $slug = \Str::slug($nom);
        $count = static::where('slug', 'LIKE', "{$slug}%")->count();
        
        return $count > 0 ? "{$slug}-{$count}" : $slug;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($salle) {
            if (empty($salle->slug)) {
                $salle->slug = static::generateUniqueSlug($salle->nom);
            }
            
            // Définir le pays par défaut si non spécifié
            if (empty($salle->pays)) {
                $salle->pays = 'Sénégal';
            }
        });

        static::updating(function ($salle) {
            if ($salle->isDirty('nom') && empty($salle->slug)) {
                $salle->slug = static::generateUniqueSlug($salle->nom);
            }
        });
    }
}
