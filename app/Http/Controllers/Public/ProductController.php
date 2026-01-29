<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Afficher la liste des produits
     */
    public function index(Request $request)
    {
        $query = Product::query()->where('is_active', true);

        // Filtrage par catégorie
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }

        // Filtrage par prix
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Tri
        switch ($request->get('sort', 'featured')) {
            case 'price-low':
                $query->orderBy('price', 'asc');
                break;
            case 'price-high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default: // featured
                $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();

        // Debug pour voir les produits
        \Log::info('Produits trouvés: ' . $products->count());
        \Log::info('Premier produit: ' . ($products->first() ? $products->first()->name : 'aucun'));

        // Transformer les produits pour le frontend
        $products->getCollection()->transform(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'original_price' => $product->old_price, // Utiliser old_price de la base
                'image' => $product->image ?: "https://picsum.photos/seed/product-{$product->id}/400/300", // Image par défaut
                'category' => $product->category,
                'stock' => $product->stock,
                'featured' => $product->is_featured,
                'rating' => $product->rating,
                'created_at' => $product->created_at->toISOString(),
            ];
        });

        // Catégories disponibles
        $categories = Product::where('is_active', true)
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return Inertia::render('Public/Products', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    /**
     * Afficher les détails d'un produit
     */
    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        // Produits similaires
        $similarProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(8)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                    'rating' => $product->rating,
                ];
            });

        return Inertia::render('Public/ProductDetail', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'original_price' => $product->old_price,
                'image' => $product->image,
                'images' => [], // Sera implémenté plus tard
                'category' => $product->category,
                'stock' => $product->stock,
                'featured' => $product->is_featured,
                'rating' => $product->rating,
                'specifications' => $product->specifications ?? [],
                'created_at' => $product->created_at->toISOString(),
            ],
            'similarProducts' => $similarProducts,
        ]);
    }
}
