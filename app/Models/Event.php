<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        // Relations
        'salle_id',
        'promoter_id',
        
        // Informations de base
        'titre',
        'description',
        'slug',
        'image_affiche',
        'image_banniere',
        
        // Lieu et localisation
        'lieu',
        'adresse',
        'code_postal',
        'ville',
        'pays',
        'latitude',
        'longitude',
        
        // Dates et heures
        'date_debut',
        'date_fin',
        'date_limite_inscription',
        
        // Tarifs
        'prix_base',
        'prix_vip',
        'prix_groupe',
        'devise',
        'gratuit',
        
        // Capacité
        'capacite_max',
        'places_disponibles',
        'limite_inscription',
        
        // Catégorie et type
        'categorie',
        'type',
        'tags',
        
        // Public cible
        'public_cible',
        'age_minimum',
        
        // Contenu et programme
        'programme',
        'activites',
        'prix_speciaux',
        
        // Réseaux sociaux et contact
        'contact_email',
        'contact_telephone',
        'contact_whatsapp',
        'reseaux_sociaux',
        'site_web',
        
        // Configuration
        'inscription_obligatoire',
        'paiement_en_ligne',
        'certificat_participation',
        'streaming',
        'url_streaming',
        
        // Statut et visibilité
        'statut',
        'visibilite',
        'mis_en_avant',
        
        // SEO
        'meta_titre',
        'meta_description',
        'mots_cles',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'date_limite_inscription' => 'date',
        'prix_base' => 'decimal:2',
        'prix_vip' => 'decimal:2',
        'prix_groupe' => 'decimal:2',
        'gratuit' => 'boolean',
        'tags' => 'array',
        'programme' => 'array',
        'activites' => 'array',
        'prix_speciaux' => 'array',
        'reseaux_sociaux' => 'array',
        'inscription_obligatoire' => 'boolean',
        'paiement_en_ligne' => 'boolean',
        'certificat_participation' => 'boolean',
        'streaming' => 'boolean',
        'mis_en_avant' => 'boolean',
        'mots_cles' => 'array',
    ];

    // Relations
    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function promoter()
    {
        return $this->belongsTo(User::class, 'promoter_id');
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function galerie()
    {
        return $this->hasMany(EventGalerie::class);
    }

    // Scopes
    public function scopePublie($query)
    {
        return $query->where('statut', 'publie');
    }

    public function scopeAVenir($query)
    {
        return $query->where('date_debut', '>', Carbon::now());
    }

    public function scopeEnCours($query)
    {
        return $query->where('date_debut', '<=', Carbon::now())
                     ->where('date_fin', '>=', Carbon::now());
    }

    public function scopePasse($query)
    {
        return $query->where('date_fin', '<', Carbon::now());
    }

    public function scopeMisEnAvant($query)
    {
        return $query->where('mis_en_avant', true);
    }

    public function scopeDuPromoter($query, $promoterId)
    {
        return $query->where('promoter_id', $promoterId);
    }

    // Mutators
    public function setTitreAttribute($value)
    {
        $this->attributes['titre'] = $value;
        $this->attributes['slug'] = Str::slug($value) . '-' . uniqid();
    }

    // Accessors
    public function getPrixFormatteAttribute()
    {
        if ($this->gratuit) {
            return 'Gratuit';
        }

        $prix = $this->prix_base;
        $devise = $this->devise;

        switch ($devise) {
            case 'XOF':
                return number_format($prix, 0, ',', ' ') . ' FCFA';
            case 'EUR':
                return number_format($prix, 2, ',', ' ') . ' €';
            case 'USD':
                return '$' . number_format($prix, 2, '.', ',');
            default:
                return $prix . ' ' . $devise;
        }
    }

    public function getDateDebutFormatteeAttribute()
    {
        return $this->date_debut->format('d F Y à H:i');
    }

    public function getDateFinFormatteeAttribute()
    {
        return $this->date_fin->format('d F Y à H:i');
    }

    public function getDureeAttribute()
    {
        return $this->date_debut->diffInHours($this->date_fin) . ' heures';
    }

    public function getPlacesRestantesAttribute()
    {
        if (!$this->limite_inscription || !$this->capacite_max) {
            return 'Illimité';
        }

        $placesPrises = $this->inscriptions()->where('statut', 'confirme')->count();
        $placesRestantes = $this->capacite_max - $placesPrises;

        return max(0, $placesRestantes);
    }

    public function getEstCompletAttribute()
    {
        if (!$this->limite_inscription || !$this->capacite_max) {
            return false;
        }

        return $this->places_restantes <= 0;
    }

    public function getStatutTexteAttribute()
    {
        $statuts = [
            'brouillon' => 'Brouillon',
            'publie' => 'Publié',
            'annule' => 'Annulé',
            'termine' => 'Terminé'
        ];

        return $statuts[$this->statut] ?? $this->statut;
    }

    public function getVisibiliteTexteAttribute()
    {
        $visibilites = [
            'public' => 'Public',
            'prive' => 'Privé',
            'invite_seulement' => 'Invités seulement'
        ];

        return $visibilites[$this->visibilite] ?? $this->visibilite;
    }

    public function getCategorieTexteAttribute()
    {
        $categories = [
            'tournoi' => 'Tournoi',
            'soiree' => 'Soirée',
            'atelier' => 'Atelier',
            'lancement' => 'Lancement',
            'festival' => 'Festival',
            'conference' => 'Conférence',
            'formation' => 'Formation',
            'meetup' => 'Meetup',
            'competition' => 'Compétition',
            'autre' => 'Autre'
        ];

        return $categories[$this->categorie] ?? $this->categorie;
    }

    public function getTypeTexteAttribute()
    {
        $types = [
            'online' => 'En ligne',
            'offline' => 'Sur place',
            'hybride' => 'Hybride'
        ];

        return $types[$this->type] ?? $this->type;
    }

    // Méthodes utilitaires
    public function peutSInscrire()
    {
        if ($this->statut !== 'publie') {
            return false;
        }

        if ($this->est_complet) {
            return false;
        }

        if ($this->date_limite_inscription && Carbon::now()->gt($this->date_limite_inscription)) {
            return false;
        }

        if (Carbon::now()->gt($this->date_debut)) {
            return false;
        }

        return true;
    }

    public function estEnCours()
    {
        $now = Carbon::now();
        return $now->between($this->date_debut, $this->date_fin);
    }

    public function estPasse()
    {
        return Carbon::now()->gt($this->date_fin);
    }

    public function estAVenir()
    {
        return Carbon::now()->lt($this->date_debut);
    }

    public function getUrlImageAfficheAttribute()
    {
        if ($this->image_affiche) {
            return asset('storage/events/affiches/' . $this->image_affiche);
        }

        return asset('images/default-event.jpg');
    }

    public function getUrlImageBanniereAttribute()
    {
        if ($this->image_banniere) {
            return asset('storage/events/bannieres/' . $this->image_banniere);
        }

        return $this->url_image_affiche;
    }

    // Méthodes statiques
    public static function categories()
    {
        return [
            'tournoi' => 'Tournoi de gaming',
            'soiree' => 'Soirée gaming',
            'atelier' => 'Atelier/Formation',
            'lancement' => 'Lancement produit',
            'festival' => 'Festival gaming',
            'conference' => 'Conférence',
            'formation' => 'Formation',
            'meetup' => 'Meetup',
            'competition' => 'Compétition',
            'autre' => 'Autre'
        ];
    }

    public static function types()
    {
        return [
            'online' => 'En ligne',
            'offline' => 'Sur place',
            'hybride' => 'Hybride'
        ];
    }

    public static function statuts()
    {
        return [
            'brouillon' => 'Brouillon',
            'publie' => 'Publié',
            'annule' => 'Annulé',
            'termine' => 'Terminé'
        ];
    }

    public static function visibilites()
    {
        return [
            'public' => 'Public',
            'prive' => 'Privé',
            'invite_seulement' => 'Invités seulement'
        ];
    }

    public static function devises()
    {
        return [
            'XOF' => 'FCFA - Franc CFA',
            'EUR' => 'EUR - Euro',
            'USD' => 'USD - Dollar américain',
            'GBP' => 'GBP - Livre sterling',
            'CAD' => 'CAD - Dollar canadien'
        ];
    }

    public static function publicsCibles()
    {
        return [
            'tous' => 'Tout public',
            '18+' => '18 ans et plus',
            '21+' => '21 ans et plus',
            'famille' => 'Familial',
            'professionnel' => 'Professionnel',
            'etudiant' => 'Étudiants'
        ];
    }
}
