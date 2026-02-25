<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'old_price',
        'original_price',
        'image',
        'images',
        'category',
        'stock',
        'is_active',
        'status',
        'is_featured',
        'featured',
        'rating',
        'reviews_count',
        'specifications',
        'meta_title',
        'meta_description',
        'created_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'is_active' => 'boolean',
        'status' => 'string',
        'is_featured' => 'boolean',
        'featured' => 'boolean',
        'rating' => 'decimal:2',
        'specifications' => 'array',
        'images' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name) . '-' . uniqid();
            }
        });

        static::updating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name) . '-' . uniqid();
            }
        });
    }

    /**
     * Obtenir l'utilisateur qui a créé le produit
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Obtenir les avis du produit
     */
    public function reviews()
    {
        // return $this->hasMany(ProductReview::class);
        return $this->hasMany(Review::class);
    }

    /**
     * Calculer la note moyenne
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Vérifier si le produit est en stock
     */
    public function isInStock()
    {
        return $this->stock > 0;
    }

    /**
     * Obtenir le pourcentage de réduction
     */
    public function getDiscountPercentageAttribute()
    {
        if (!$this->old_price || $this->old_price <= $this->price) {
            return 0;
        }
        
        return round((($this->old_price - $this->price) / $this->old_price) * 100);
    }

    /**
     * Scope pour les produits actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les produits mis en avant
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope pour les produits en stock
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope pour les produits par catégorie
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
