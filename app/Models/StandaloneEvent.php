<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class StandaloneEvent extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'organizer_name',
        'organizer_email',
        'organizer_phone',
        'location',
        'country',
        'city',
        'event_date',
        'price',
        'status',
        'created_by',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'price' => 'decimal:2',
        'status' => 'string',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', Carbon::now());
    }

    public function scopePast($query)
    {
        return $query->where('event_date', '<', Carbon::now());
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function getFormattedDateAttribute()
    {
        return $this->event_date->format('d/m/Y H:i');
    }

    public function getFormattedPriceAttribute()
    {
        return $this->price ? number_format($this->price, 2, ',', ' ') . ' €' : 'Gratuit';
    }

    public function isUpcoming()
    {
        return $this->event_date >= Carbon::now();
    }

    public function isPast()
    {
        return $this->event_date < Carbon::now();
    }
}
