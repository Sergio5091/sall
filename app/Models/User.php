<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
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

    public function getReferralGenerationIds(): array
    {
        $generations = [];
        $currentIds = [$this->id];
        $visited = [$this->id => true];
        $level = 1;

        while (!empty($currentIds)) {
            $ids = self::query()
                ->whereIn('parent_id', $currentIds)
                ->pluck('id')
                ->all();

            if (empty($ids)) {
                break;
            }

            $filtered = [];
            foreach ($ids as $id) {
                if (isset($visited[$id])) {
                    continue;
                }
                $visited[$id] = true;
                $filtered[] = $id;
            }

            if (empty($filtered)) {
                break;
            }

            $generations[] = [
                'level' => $level,
                'ids' => $filtered,
            ];

            $currentIds = $filtered;
            $level++;
        }

        return $generations;
    }

    public function getReferralPointsSummary(): array
    {
        $generationIds = $this->getReferralGenerationIds();

        $generations = [];
        $totalCommunity = 0;
        $directCount = 0;

        $maxLevel = 0;
        foreach ($generationIds as $gen) {
            $maxLevel = max($maxLevel, (int) $gen['level']);
        }

        $totalScaled = '0';

        foreach ($generationIds as $gen) {
            $level = (int) $gen['level'];
            $count = count($gen['ids']);
            $totalCommunity += $count;

            if ($level === 1) {
                $directCount = $count;
            }

            $pow5 = self::bigPowInt(5, $level);
            $numerator = self::bigMulInt($pow5, $count);
            $multiplier = self::bigFormatDecimal($pow5, $level);
            $points = self::bigFormatDecimal($numerator, $level);

            $scaled = $numerator;
            if ($maxLevel > $level) {
                $scaled = self::bigMulPow10($scaled, $maxLevel - $level);
            }
            $totalScaled = self::bigAdd($totalScaled, $scaled);

            $generations[] = [
                'level' => $level,
                'count' => $count,
                'multiplier' => $multiplier,
                'points' => $points,
            ];
        }

        $totalPoints = self::bigFormatDecimal($totalScaled, $maxLevel);

        return [
            'generations' => $generations,
            'direct_referrals' => $directCount,
            'community_size' => $totalCommunity,
            'total_points' => $totalPoints,
        ];
    }

    private static function bigPowInt(int $base, int $exp): string
    {
        if ($exp <= 0) {
            return '1';
        }

        $value = '1';
        for ($i = 0; $i < $exp; $i++) {
            $value = self::bigMulInt($value, $base);
        }
        return $value;
    }

    private static function bigMulPow10(string $number, int $zeros): string
    {
        if ($zeros <= 0 || $number === '0') {
            return $number;
        }

        return $number . str_repeat('0', $zeros);
    }

    private static function bigFormatDecimal(string $number, int $scale): string
    {
        $number = ltrim($number, '0');
        if ($number === '') {
            $number = '0';
        }

        if ($scale <= 0) {
            return $number;
        }

        $len = strlen($number);
        if ($len <= $scale) {
            $number = str_pad($number, $scale + 1, '0', STR_PAD_LEFT);
            $len = strlen($number);
        }

        $whole = substr($number, 0, $len - $scale);
        $frac = substr($number, $len - $scale);
        $frac = rtrim($frac, '0');

        if ($frac === '') {
            return $whole;
        }

        return $whole . '.' . $frac;
    }

    private static function bigMulInt(string $number, int $multiplier): string
    {
        if ($multiplier === 0 || $number === '0') {
            return '0';
        }

        $carry = 0;
        $result = '';
        for ($i = strlen($number) - 1; $i >= 0; $i--) {
            $digit = (int) $number[$i];
            $prod = ($digit * $multiplier) + $carry;
            $result = ($prod % 10) . $result;
            $carry = intdiv($prod, 10);
        }

        while ($carry > 0) {
            $result = ($carry % 10) . $result;
            $carry = intdiv($carry, 10);
        }

        $result = ltrim($result, '0');
        return $result === '' ? '0' : $result;
    }

    private static function bigAdd(string $a, string $b): string
    {
        $a = ltrim($a, '0');
        $b = ltrim($b, '0');
        if ($a === '') {
            $a = '0';
        }
        if ($b === '') {
            $b = '0';
        }

        $i = strlen($a) - 1;
        $j = strlen($b) - 1;
        $carry = 0;
        $result = '';

        while ($i >= 0 || $j >= 0 || $carry > 0) {
            $sum = $carry;
            if ($i >= 0) {
                $sum += (int) $a[$i];
                $i--;
            }
            if ($j >= 0) {
                $sum += (int) $b[$j];
                $j--;
            }
            $result = ($sum % 10) . $result;
            $carry = intdiv($sum, 10);
        }

        $result = ltrim($result, '0');
        return $result === '' ? '0' : $result;
    }
}
