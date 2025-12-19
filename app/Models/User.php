<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;


use Illuminate\Notifications\Notifiable;
use App\Models\Salle;
use App\Models\Reservation;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'referral_code',
        'parent_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relation avec les salles du promoteur
     */
    public function salles()
    {
        return $this->hasMany(Salle::class, 'promoter_id');
    }

    /**
     * Obtenir la salle principale du promoteur
     */
    public function salle()
    {
        return $this->hasOne(Salle::class, 'promoter_id');
    }

    /**
     * Relation avec les comptes promoteurs liés (si c'est un compte principal)
     */
    public function linkedPromoterAccounts()
    {
        return $this->belongsToMany(User::class, 'promoter_accounts', 'main_user_id', 'linked_user_id')
                    ->withPivot(['is_active', 'nickname'])
                    ->withTimestamps();
    }

    /**
     * Relation avec le compte principal (si c'est un compte lié)
     */
    public function mainPromoterAccount()
    {
        return $this->belongsToMany(User::class, 'promoter_accounts', 'linked_user_id', 'main_user_id')
                    ->withPivot(['is_active', 'nickname'])
                    ->withTimestamps();
    }

    /**
     * Obtenir tous les comptes promoteurs (principal + liés)
     */
    public function getAllPromoterAccounts()
    {
        $accounts = collect([$this]);
        
        if ($this->isMainPromoter()) {
            $linkedAccounts = $this->linkedPromoterAccounts()->get();
            $accounts = $accounts->merge($linkedAccounts);
        }
        
        return $accounts;
    }

    /**
     * Obtenir le compte promoteur actif
     */
    public function getActivePromoterAccount()
    {
        if ($this->isMainPromoter()) {
            $activeAccount = $this->linkedPromoterAccounts()->wherePivot('is_active', true)->first();
            return $activeAccount ?: $this; // Si aucun compte lié actif, retourner le compte principal
        }
        
        // Si c'est un compte lié, vérifier s'il est actif
        $mainAccount = $this->mainPromoterAccount()->first();
        if ($mainAccount) {
            $isActive = $this->mainPromoterAccount()->wherePivot('is_active', true)->exists();
            return $isActive ? $this : $mainAccount;
        }
        
        return $this;
    }

    /**
     * Définir le compte promoteur actif
     */
    public function setActivePromoterAccount($userId)
    {
        if (!$this->isMainPromoter()) {
            throw new \Exception('Seul le compte principal peut changer de compte actif');
        }

        // Désactiver tous les comptes
        $this->linkedPromoterAccounts()->updateExistingPivot(
            $this->linkedPromoterAccounts()->pluck('users.id'),
            ['is_active' => false]
        );
        
        // Activer le compte spécifié
        if ($userId != $this->id) {
            $this->linkedPromoterAccounts()->updateExistingPivot($userId, ['is_active' => true]);
        }
        
        // Mettre à jour la session
        session(['active_promoter_account_id' => $userId]);
    }

    /**
     * Ajouter un compte promoteur lié
     */
    public function addPromoterAccount($userId, $nickname = null, $isActive = false)
    {
        if (!$this->isMainPromoter()) {
            throw new \Exception('Seul le compte principal peut ajouter des comptes liés');
        }

        $this->linkedPromoterAccounts()->attach($userId, [
            'nickname' => $nickname,
            'is_active' => $isActive
        ]);
        
        if ($isActive) {
            session(['active_promoter_account_id' => $userId]);
        }
    }

    /**
     * Retirer un compte promoteur lié
     */
    public function removePromoterAccount($userId)
    {
        if (!$this->isMainPromoter()) {
            throw new \Exception('Seul le compte principal peut retirer des comptes liés');
        }

        $this->linkedPromoterAccounts()->detach($userId);
        
        // Si c'était le compte actif, effacer de la session
        if (session('active_promoter_account_id') == $userId) {
            session()->forget('active_promoter_account_id');
        }
    }

    /**
     * Vérifier si c'est un compte promoteur principal
     */
    public function isMainPromoter()
    {
        return $this->role === 'promoter' && !$this->mainPromoterAccount()->exists();
    }

    /**
     * Vérifier si c'est un compte promoteur lié
     */
    public function isLinkedPromoter()
    {
        return $this->role === 'promoter' && $this->mainPromoterAccount()->exists();
    }

    /**
     * Obtenir le compte principal (si c'est un compte lié)
     */
    public function getMainAccount()
    {
        if ($this->isLinkedPromoter()) {
            return $this->mainPromoterAccount()->first();
        }
        return $this;
    }

    /**
     * Relation avec les réservations du client
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public static function generateUniqueReferralCode(int $length = 10): string
    {
        do {
            $code = strtoupper(Str::random($length));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }
    
    /**
     * Générer un code de parrainage uniquement si la colonne existe
     */
    public static function generateReferralCodeIfExists(): ?string
    {
        try {
            // Vérifier si la colonne existe avant de générer le code
            if (Schema::hasColumn('users', 'referral_code')) {
                return self::generateUniqueReferralCode();
            }
        } catch (\Exception $e) {
            // En cas d'erreur, retourner null
        }
        
        return null;
    }
}
